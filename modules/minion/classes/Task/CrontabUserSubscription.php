<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Help task to display general instructons and list all tasks
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_CrontabUserSubscription extends Minion_Task
{
	/**
     * 订阅投资者脚本
     * @author 施磊     
     */
	protected function _execute(array $params){
		#php shell php minion --task=crontabUserSubscription
		$this->crontabUserSubscription(); 
	}
        
        /**
     * 订阅脚本
     * @author 施磊
     */
    public function crontabUserSubscription() {
        //初始化点数据
        //收集所有用户的查询记录
        $allSearchHistory = array();


       //第一步 获得今天要发数据的user
        $investor_service = new Service_User_Company_Investor();
        $user = new Service_User();
        $allUserObj = $investor_service->getNowSendUserSubscription();
        foreach($allUserObj as $val) {
            //循环获得用户的查询历史
            if(intval($val->subscription_user_id)) {
                $userSearchHistory = $investor_service->searchConditionsListForCron($val->subscription_user_id);
                $userSearchHistory = $this->_checkUserHistory($userSearchHistory);
                //获取用户数据
                $userInfo = $user->getUserInfoById($val->subscription_user_id);
                $updateTimeStatus = '';
                if($userInfo->email && $userSearchHistory) {
                    //发送邮件
                    $updateTimeStatus = $this->_sendSubscriptionMail($userInfo, $userSearchHistory, $val);
                }
                //修改下下次发送的时间
                $nextTime = $updateTimeStatus ? time() + 86400*7 : time() + 86400;
                $param = array('subscription_next_time' => $nextTime);
                $investor_service->updateUserSubscription($val->subscription_id, $param);
            }
        }
    }

    /*
     * 发送订阅邮件
     * @author 施磊
     * @param $userInfo 用户信息
     * @param $userSearchHistory 用户所属历史
     * @param $subscriptionInfo 用户订阅信息
     */

    private function _sendSubscriptionMail($userInfo, $userSearchHistory, $subscriptionInfo) {
        $html = '<!DOCTYPE html><html><head><title>EDM</title><meta charset="utf-8" /><style type="text/css"/>*{margin:0;padding:0;}
                .wrap{width:700px;margin:0 auto;font-size:14px;}
                .wrap .title{background:#5b8ccb;margin-top:90px;padding-left:15px;}
                .wrap h2{height:50px;line-height:50px;background:url('.URL::website("images/email_back/icon1.jpg").') left center no-repeat;font-size:14px;color:#fff;padding-left:30px;}
                .wrap h2 span{color:#fffc00;}
                .wrap .tishi{padding:30px 0 20px 10px;font-weight:bold;}
                .wrap .tishi span{color:#ff3000;}
                .wrap .list{border-top:1px solid #e3e3e3;padding:20px 0;background:url('.URL::website("images/email_back/edm1.png").') 10px center no-repeat;position:relative;padding-left:80px;}
                .wrap .list p{line-height:25px;}
                .wrap .list p span{color:#ff3000;font-weight:bold;}
                .wrap .list p a{color:#00f;text-decoration:none;}
                .wrap .list p a:hover{text-decoration:underline;}
                .wrap .list em{position:absolute;left:-60px;color:#5a8bca;font-size:48px;font-family:Arial;}
                .email_bottom{padding:20px 30px;background:#eceff3;color:#5b5b5b;margin-bottom:50px;font-size:12px;}
                .email_bottom p{line-height:22px;}
                .email_bottom p a{color:#002aff;text-decoration:underline;}
                .email_bottom p span{color:#f60;font-size:16px;font-weight:bold;font-family:Arial;}
                </style>
                </head>
                <body>';
        $html .= '<div class="wrap">
                    <div class="title"><h2>尊敬的<span>'.$userInfo->user_name.'</span>，您好！</h2></div>
                    <div class="tishi">以下是我们为您搜索到的在我们一句话网站<span>最新</span>注册的投资者，期待您和对方的愉快合作！</div>';
        if($userSearchHistory) {
            foreach($userSearchHistory as $val) {
                $html .= '<div class="list">
                            <em>1</em>
                            <p>为您搜索符合以下条件的投资者新增加<span>'.$val['nowtotalcount'].'</span>位：</p>
                            <p>“'.$val['group'].'” <a href="'.URL::website($val['url']).'">点击查看>></a> </p>
                        </div>';
            }
            $resetUrl = "/member/cancellationSubscription/?user_id=".$subscriptionInfo->subscription_user_id."&key=".$subscriptionInfo->subscription_email_key;
            $html .= '<div class="email_bottom">
                            <p>此为系统邮件，请勿回复</p>
                            <p>如有任何疑问，可联系我们：<span>400-3989-3456</span></p>
                            <p>如您不希望再收到此类邮件，可以点此<a href="'.URL::website($resetUrl).'">取消订阅</a>，感谢您的支持！</p>
                        </div>
                    </div>
                    </body>
                    </html>';
            //发送邮件
            $sendresult = common::sendemail("收到的新邮件订阅", 'service@qutouzi.com', $userInfo->email, $html);
            if ($sendresult==1){
                    return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * 根据PM的需求 来筛选可用的查询历史
     * @author 施磊
     * @param array $userSearchHistory 单用户搜索历史
     * @return array 处理好的结果
     */
    private function _checkUserHistory($userSearchHistory) {

        if(!$userSearchHistory) return array();
        //去除空值
        foreach($userSearchHistory as $key => $val) {
            if(!$val['nowtotalcount']) {
                unset($userSearchHistory[$key]);
            }
        }
        //第一 如果集合不满5条 直接返回
        if(count($userSearchHistory) <= 5)  return $userSearchHistory;

        //预设参数
        //返回值
        $return = array();
        //临时变量 用来对比数据
        $checkArr = array();
        //最终储存筛选下来条件的数组
        $allCond = array('cond' => array(), 'ids' => array());
        //第二如果满了5条 我们就要取并集了
        foreach($userSearchHistory as $val) {
            //预设7大搜索条件
            $checkArr = $this->_checkUserHistoryCond($val);
            $this->_checkCondMerge($arrCond, $checkArr);
        }
        $return = array();
        //返回成主程序需要的数据格式
        if($arrCond['cond']) {
            foreach ($arrCond['cond'] as $val) {
                $return[] = $val['val'];
            }
        }
        return $return;
    }

    /**
     * 判读的主逻辑
     * @author 施磊
     * @param array $allCond 符合条件的集合 应用调用
     * @param array $checkArr 单用户搜索历史
     */
    private function _checkCondMerge(&$arrCond, $checkArr) {
        //如果一个条件都没有。。无需判断。。绿色通道
        if(!$arrCond['cond']) {
            //有新增的投资者
            if($checkArr['val']['nowtotalcount']) {
                $arrCond['cond'][] = $checkArr;
                $arrCond['ids'][] = $checkArr['id'];
            }
        }else{
            foreach($arrCond['cond'] as $key => $val) {

                //累加值 如果累加值等于数组总数那说明此数据和数组没有一个是包涵和被包涵关系
                $status = 0;

                //如果没有新增投资者
                if(!$checkArr['val']['nowtotalcount']) {
                    break;
                }
                //首先判断是否有相同的数据即包涵
                //2个数组的交集 或者 当前数据是任意一组数据的子集 被包涵且小于
                $arrSameCount = count(array_uintersect_assoc($val['cond'], $checkArr['cond'], "strcasecmp"));
                //和任意一组数据相同
                if((count($val['cond']) == $arrSameCount && $arrSameCount == count($checkArr['cond'])) || (count($val['cond']) > $arrSameCount && count($checkArr['cond']) == $arrSameCount)) {
                    break;
                }
                //当前的数据是任意一组数据的父级 包涵且大于
                else if(count($val['cond']) == $arrSameCount && count($checkArr['cond']) > $arrSameCount){
                    if(in_array($checkArr['id'], $arrCond['ids'])) {
                        //如果已经存在了合集 则把这个unset掉
                        unset($arrCond['cond'][$key]);
                        unset($arrCond['ids'][$key]);
                        break;
                    }else{
                        //如果不存在。。替换
                        $arrCond['cond'][$key] = $checkArr;
                        $arrCond['ids'][$key] = $checkArr['id'];
                    }
                }
                //当前数据和任意一组数据部分有交集
                else if((count($val['cond']) > $arrSameCount && count($checkArr['cond']) > $arrSameCount)) {
                    $status ++;
                }
            }
            if($status == count($arrCond['cond']) && count($arrCond['cond'])<5 && $status != 0) {
                $arrCond['cond'][] = $checkArr;
                $arrCond['ids'][] = $checkArr['id'];
            }
        }
    }

    /**
     * 获得不是默认的值
     * @author 施磊
     * @param array $userSearchHistory 单条搜索历史
     * @return array 处理好的结果
     */
    private function _checkUserHistoryCond($userSearchHistory) {
        //预设的7个参数
        $param = array('per_industry', 'per_amount', 'per_identity', 'area_id', 'per_join_project', 'per_connections', 'per_investment_style');
        $return = array();
        $temp = array();
        foreach($param as $val){
            $temp[$val] = $userSearchHistory[$val];
        }
        //去除为默认为0值的项
        $return['cond'] = array_filter($temp);
        $return['val'] = $userSearchHistory;
        $return['id'] = $userSearchHistory['id'];
        return $return;
    }
    
}
?>