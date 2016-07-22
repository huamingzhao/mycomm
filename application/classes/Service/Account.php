<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 用户账户资金相关公用处理
 * @author 周进
 *
 */

class Service_Account{

    /**
    * 登录时候检查服务是否到期一天进行一次消息提示
    */
    public function checkLoginMsg($user_id=0){
        //检测当天是否有发送过
        $msg_service = new Service_User_Ucmsg();
        $status = $msg_service->isTypeMsgSendTimeSlice($user_id,'company_account_sp',strtotime(date('Y-m-d')),time());
        //余额少于30的推送
        $result = ORM::factory('Account')->where('account_user_id', '=', $user_id)->where('account_stastus', '=', 1)->find();
        if ($result->account!=""&&$result->account<30){
            if($status==FALSE){
                //$msg_service->pushMsg($user_id, "company_account_sp", '您的账户余额不足30元。为保证您的业务正常开展 ',URL::website("company/member/account/accountindex"));
            }
        }
        //服务到期或者第二天到期的推送[@钟涛修改 现在已经无服务了]
//         $buyService = ORM::factory('Buyservice');
//         $data = $buyService->where('user_id', "=", $user_id)
//         ->where('buy_usable_time','<=',time())->where('buy_status','=','1')->order_by('buy_usable_time','ASC')->find();
//         //查到数据则更新数据的buy_status为0
//         if ($data->buy_id>0){
//             $buyService->buy_id = $data->buy_id;
//             $buyService->buy_status = 0;
//             try {
//                 $buyService->save();
//                 $allBuyType = common::getBuyType();
//                 $selectBuyType = $allBuyType[$data->buy_type][$data->buy_type_config];
//                 $describe = $allBuyType[$data->buy_type]['remarks'].$selectBuyType['describe'];
//                 $msg_service = new Service_User_Ucmsg();
//                 $status = $msg_service->isTypeMsgSendTimeSlice($user_id,'company_account_sp',strtotime(date('Y-m-d')),time());
//                 if($status==FALSE)
//                     $msg_service->pushMsg($user_id, "company_account_sp", "您购买的".$describe."于".date('Y-m-d H:i:s',$data->buy_usable_time)."到期您已无法享受此次服务",URL::website("company/member/account/cardservice"));
//             }
//             catch (Kohana_Exception $e)
//             {

//             }
//         }
//         else{
//             $buyService = ORM::factory('Buyservice');
//             $data = $buyService->where('user_id', "=", $user_id)
//             ->where('buy_usable_time','>',time())->where('buy_status','=','1')->order_by('buy_usable_time','DESC')->find();
//             if ($data->buy_id>0){
//                 if(date('Ymd',$data->buy_usable_time) == date('Ymd'))
//                 {
//                     $allBuyType = common::getBuyType();
//                     $selectBuyType = $allBuyType[$data->buy_type][$data->buy_type_config];
//                     $describe = $allBuyType[$data->buy_type]['remarks'].$selectBuyType['describe'];
//                     $msg_service = new Service_User_Ucmsg();
//                     $status = $msg_service->isTypeMsgSendTimeSlice($user_id,'company_account_sp',strtotime(date('Y-m-d')),time());
//                     if($status==FALSE)
//                         $msg_service->pushMsg($user_id, "company_account_sp", "您购买的".$describe."将于".date('Y-m-d H:i:s',$data->buy_usable_time)."到期",URL::website("company/member/account/cardservice"));
//                 }
//             }
//         }
    }

    /**
    * 检查用户账户状态(所有账户相关操作的前提)
    * @author 周进
    * @param int $user_id
    * @return bool
    */
    public function checkAccountStatus($user_id=0){
        $result = ORM::factory('Account')->where('account_user_id', '=', $user_id)->find();
        if ($result->account_id==NULL){
            return TRUE;
        }elseif ($result->account_id>0 && $result->account_stastus==1){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /**
     * 检查用户账户状态(所有账户相关操作的前提)
     * @author 周进
     * @param int $user_id
     * @return bool
     */
    public function checkAccountStatusNote($user_id=0){
    	$result = ORM::factory('Account')->where('account_user_id', '=', $user_id)->find();
    	if ($result->account_id==NULL){
    		return false;//没有禁用
    	}elseif ($result->account_id>0 && $result->account_stastus==1){
    		return false;//没有禁用
    	}else{
    		return $result->account_note;//被禁用 返回禁用的理由
    	}
    }
    
    /**
     * 用户账户状态被禁用后返回相应的禁用原因,并根据原因进行分类 refund：因为退款账户暂时被禁用 ；other：非退款动作账户被禁止
     * @author 赵路生
     * @param int $user_id
     * @return ambiguous
     */
    public function getForbidAccountNote($user_id=0){
    	$return = array();
		if(!$this->checkAccountStatus($user_id)){
			$result = ORM::factory('Account')->where('account_user_id', '=', $user_id)->find();
			//等于这个字符串说明是退款的时候禁用的。md5('123456').'退款账户禁用' 
			if($result->account_note == 'e10adc3949ba59abbe56e057f20f883e退款账户禁用'){
				$return['type'] = 'refund';
				$return['note'] = '退款账户禁用';
			}else{
				$return['type'] = 'other';
				$return['note'] = $result->account_note;
			}			
		}
		return $return;
    }
/****掉用服务相关处理 start****/

    /**
     * 账户检测扣款额度及相关操作的判断(只检验金额及次数不执行实际扣费操作)
     * @author 周进
     * @date 2013/04/08
     * @param int $user_id 用户ID
     * @param int $type 操作类型（取自配置文件）
     * 1为线上充值2为线下汇款3为购买服务4为现金充值5银行卡6查看名片7递出名片8交换名片9报名招商会
     * @param int $action_id 操作ID非必须
     * @return array type=>0：账户禁用1:正常返回2：未购买相关服务（或余额不足）3：包月服务到期4：包次服务到期5：服务当天限制次数用完6:次服务被浪费
     */
    public function checkCardAccount($user_id=0,$type=0,$action_id=0){
        //获取客服电话号码
        $arrCustomerPhone = common::getCustomerPhone();
        $result = array('status'=>FALSE,'message'=>'您的账户被禁用，请联系客服：'.$arrCustomerPhone['1'].'！','type'=>'0');
        if($this->checkAccountStatus($user_id)==FALSE)
            return $result;
        $account = common::getAccount();
        //检车余额
        if(!$this->checkAccountNumber($user_id,$account[$type]['price'])){
            $result = array('status'=>FALSE,'message'=>'对不起，您的账户余额不足，前往账户中心充值以享受该服务？','type'=>'2');
            return $result;
        }
        else{
            //检测次数限制
            //查看名片的当天做20次的次数限制
            if ($type == 6){
                $sum = DB::select(array(DB::expr('SUM(order_id)'),'num'))->from('account_order')->where('order_user_id','=',$user_id)->and_where('order_status','=','2')
                        ->and_where('order_type','=',$type)
                        ->and_where(DB::expr('day(from_unixtime(order_create_time))'),'=',DB::expr('day(now())'))->execute();
                if ($sum[0]['num']>=20){
                    $result_arr = array('status'=>FALSE,'message'=>'很抱歉，您每天只能查看20张个人名片。今天的查看名额已满，无法再查看。','type'=>5);
                    return $result_arr;
                }
            }
            //递出名片的当天做30次的次数限制
            if ($type == 7){
                $sum = DB::select(array(DB::expr('SUM(order_id)'),'num'))->from('account_order')->where('order_user_id','=',$user_id)->and_where('order_status','=','2')
                    ->and_where('order_type','=',$type)
                    ->and_where(DB::expr('day(from_unixtime(order_create_time))'),'=',DB::expr('day(now())'))->execute();
                if ($sum[0]['num']>=30){
                    $result_arr = array('status'=>FALSE,'message'=>'很抱歉，您每天只能递出30张名片。今天的使用次数已满，无法再递出。','type'=>5);
                    return $result_arr;
                }
            }
            $result_arr = array('status'=>TRUE,'message'=>'确认此操作将扣除您账户中的'.$account[$type]['price'].'元人民币，是否继续？','type'=>1);
            return $result_arr;
        }
    }

    /**
    * 账户中心完成各种触发事件引起账户中心变动的程序处理
    * @param int $user_id 用户ID
    * @param int $type 操作类型（取自配置文件）
    * 1为线上充值2为线下汇款3为购买服务4为现金充值5银行卡6查看名片7递出名片8交换名片9报名招商会
    * @param int $action_id 操作ID非必须
    * @return array type=>0：账户禁用1:正常返回2：未购买相关服务（或余额不足）3：包月服务到期4：包次服务到期5：服务当天限制次数用完6:次服务被浪费
    * demo test
        $account = new Service_Account();
        $result = $account->manageAccount($user_id,$type,$action_id);
        $result = array('status'=>FALSE,'message'=>'账户被禁用',$type=>0);
    */
    public function manageAccount($user_id=0,$type=0,$action_id=0){
        //获取客服电话号码
        $arrCustomerPhone = common::getCustomerPhone();
        /*
        * 用户消费触发事件
        * 一、检查是否购买相应服务(若购买包月则从包月中查看否则检查购买次数中减少做相关处理)
        * 二、如果未购买相关服务则直接从资金中购买
        * 三、服务不足的返回购买服务
        */
        $result = array('status'=>FALSE,'message'=>'您的账户被禁用，请联系客服：'.$arrCustomerPhone['1'].'！','type'=>'0');
        if($this->checkAccountStatus($user_id)==FALSE)
            return $result;
        //目前先采用资金直接扣款，
        $account = common::getAccount();
        return $this->buyFromAccount($user_id,$type,$account[$type]['price'],2);
        exit;
        //处理服务相关的后续产品确定在添加 之前有过处理
        $data = $this->checkBuyService($user_id,$type,$action_id);
        if($data['status']==FALSE){
            return $data;
        }
        else{
            return $data;
        }
        return $result;
    }

    /**
     * 针对manageAccount的购买服务处理
     * @param int $user_id 用户ID
     * @param int $type 1:查看名片 2：递出名片 3：查看招商服务
     * @return array
     */
    public function checkBuyService($user_id=0,$type=0,$action_id=0){
        //获取客服电话号码
        $arrCustomerPhone = common::getCustomerPhone();
        $result = array('status'=>FALSE,'message'=>'未购买相关服务','type'=>'2');
        $this->closeBuyService($user_id);
        $buyService = ORM::factory('Buyservice');
        if ($type==1){//查看名片（先检测包月服务在检测包次服务，以下其他情况类似）
            $source = $buyService->where('user_id', '=', $user_id)->where('buy_type', '=', 1)
                ->where('buy_status', '=', '1')->where('buy_usable_time','>',time())->order_by('buy_time','ASC')->find();
            if ($source->user_id>0){//使用包月服务
                $data = $this->writeServiceLog($source->buy_id,$source->buy_type,$source->buy_type_config,$action_id);
                if($data['status']==TRUE){
                    $result = array('status'=>TRUE,'message'=>'操作成功','type'=>$data['type']);
                    return $result;
                }
                else{
                    $result = array('status'=>FALSE,'message'=>$data['message'],'type'=>$data['type']);
                    return $result;
                }
            }
            else{//包次服务
                $sourcetime = $buyService->where('user_id', '=', $user_id)->where('buy_type', '=', 2)
                ->where('buy_status', '=', '1')->where('buy_usable_time','>',time())->order_by('buy_time','ASC')->find();
                if ($sourcetime->user_id<=0){
                    //返回的三种情况 1包月过期 2包次过期 3都过期包次冲突
                    $monthsource = $buyService->where('user_id', '=', $user_id)->where('buy_type', '=', 1)->where('buy_status','=','0')->where('buy_usable_time','<',time())->find();
                    if ($monthsource->user_id>0){
                        $des1 = $this->getBuyTypeDescribe($monthsource->buy_type,$monthsource->buy_type_config);
                        $numsource = ORM::factory('Buyservice')->where('user_id', '=', $user_id)->where('buy_type', '=', 2)->where('buy_status','=','0')->find();
                        if ($numsource->user_id>0){
                            $des = $this->getBuyTypeDescribe($numsource->buy_type,$numsource->buy_type_config);
                            if ($numsource->buy_usable_time<time()&&$numsource->buy_confine_number>0){
                                $result = array('status'=>FALSE,'message'=>'您购买的'.$des['describe']."/￥".$des['price'].'已到期，请联系客服：'.$arrCustomerPhone['1'],'type'=>6);
                                return $result;
                            }
                        }
                        $result = array('status'=>FALSE,'message'=>'您购买的'.$des1['describe']."/￥".$des1['price'].'已到期','type'=>3);
                        return $result;
                    }
                    else{
                        $numsource = $buyService->where('user_id', '=', $user_id)->where('buy_type', '=', 2)->where('buy_status','=','0')->find();
                        if ($numsource->user_id>0){
                            $des = $this->getBuyTypeDescribe($numsource->buy_type,$numsource->buy_type_config);
                            if ($numsource->buy_confine_number==0)
                                $result = array('status'=>FALSE,'message'=>'您购买的'.$des['describe']."/￥".$des['price'].'已用完','type'=>5);
                            elseif ($numsource->buy_usable_time<time()&&$numsource->buy_confine_number>0)
                                $result = array('status'=>FALSE,'message'=>'您购买的'.$des['describe']."/￥".$des['price'].'已到期','type'=>4);
                            return $result;
                        }
                        else
                            $result = array('status'=>FALSE,'message'=>'未购买相关服务','type'=>2);
                    }
                    return $result;
                }
                $des = $this->getBuyTypeDescribe($sourcetime->buy_type,$sourcetime->buy_type_config);
                //当类型为包次的时候更新Buyservice表次数
                if ($sourcetime->buy_confine_number<=0){
                    $result = array('status'=>FALSE,'message'=>'您购买的'.$des['describe']."/￥".$des['price'].'已用完','type'=>'4');
                    return $result;
                }
                //使用包次服务
                $datatime = $this->writeServiceLog($sourcetime->buy_id,$sourcetime->buy_type,$sourcetime->buy_type_config,$action_id);
                if($datatime['status']==TRUE){
                    $service = ORM::factory('Buyservice',$sourcetime->buy_id);
                    $service->buy_confine_number = $sourcetime->buy_confine_number-1;
                    $service->update();
                    $result = array('status'=>TRUE,'message'=>'操作成功','type'=>$datatime['type']);
                    return $result;
                }
                else{
                    $result = array('status'=>FALSE,'message'=>$datatime['message'],'type'=>$datatime['type']);
                    return $result;
                }
            }
        }
        if ($type==2){//递出名片
            $source = $buyService->where('user_id', '=', $user_id)->where('buy_type', '=', 3)
                ->where('buy_status', '=', '1')->where('buy_usable_time','>',time())->order_by('buy_time','ASC')->find();
            if ($source->user_id>0){//包月服务
                $data = $this->writeServiceLog($source->buy_id,$source->buy_type,$source->buy_type_config,$action_id);
                if($data['status']==TRUE){
                    $result = array('status'=>TRUE,'message'=>'操作成功','type'=>$data['type']);
                    return $result;
                }
                else{
                    $result = array('status'=>FALSE,'message'=>$data['message'],'type'=>$data['type']);
                    return $result;
                }
            }
            else{//包次服务
                $sourcetime = $buyService->where('user_id', '=', $user_id)->where('buy_type', '=', 4)
                ->where('buy_status', '=', '1')->where('buy_usable_time','>',time())->order_by('buy_time','ASC')->find();
                if ($sourcetime->user_id<=0){
                    //返回的三种情况 1包月过期 2包次过期 3都过期包次冲突
                    $monthsource = $buyService->where('user_id', '=', $user_id)->where('buy_type', '=', 3)->where('buy_status','=','0')->where('buy_usable_time','<',time())->find();
                    if ($monthsource->user_id>0){
                        $des1 = $this->getBuyTypeDescribe($monthsource->buy_type,$monthsource->buy_type_config);
                        $numsource = ORM::factory('Buyservice')->where('user_id', '=', $user_id)->where('buy_type', '=', 4)->where('buy_status','=','0')->find();
                        if ($numsource->user_id>0){
                            $des = $this->getBuyTypeDescribe($numsource->buy_type,$numsource->buy_type_config);
                            if ($numsource->buy_usable_time<time()&&$numsource->buy_confine_number>0){
                                $result = array('status'=>FALSE,'message'=>'您购买的'.$des['describe']."/￥".$des['price'].'已到期 请联系客服：'.$arrCustomerPhone['1'],'type'=>6);
                                return $result;
                            }
                        }
                        $result = array('status'=>FALSE,'message'=>'您购买的'.$des1['describe']."/￥".$des1['price'].'已到期','type'=>3);
                        return $result;
                    }
                    else{
                        $numsource = $buyService->where('user_id', '=', $user_id)->where('buy_type', '=', 4)->where('buy_status','=','0')->find();
                        if ($numsource->user_id>0){
                            $des = $this->getBuyTypeDescribe($numsource->buy_type,$numsource->buy_type_config);
                            if ($numsource->buy_confine_number==0)
                                $result = array('status'=>FALSE,'message'=>'您购买的'.$des['describe']."/￥".$des['price'].'已用完','type'=>5);
                            elseif ($numsource->buy_usable_time<time()&&$numsource->buy_confine_number>0)
                            $result = array('status'=>FALSE,'message'=>'您购买的'.$des['describe']."/￥".$des['price'].'已到期','type'=>4);
                            return $result;
                        }
                        else
                            $result = array('status'=>FALSE,'message'=>'未购买相关服务','type'=>2);
                    }
                    return $result;
                }
                $des = $this->getBuyTypeDescribe($sourcetime->buy_type,$sourcetime->buy_type_config);
                //当类型为包次的时候更新Buyservice表次数
                if ($sourcetime->buy_confine_number<=0){
                    $result = array('status'=>FALSE,'message'=>'您购买的'.$des['describe']."/￥".$des['price'].'已用完','type'=>'4');
                    return $result;
                }
                //使用包次服务
                $datatime = $this->writeServiceLog($sourcetime->buy_id,$sourcetime->buy_type,$sourcetime->buy_type_config,$action_id);
                if($datatime['status']==TRUE){
                    $service = ORM::factory('Buyservice',$sourcetime->buy_id);
                    $service->buy_confine_number = $sourcetime->buy_confine_number-1;
                    $service->update();
                    $result = array('status'=>TRUE,'message'=>'操作成功','type'=>$datatime['type']);
                    return $result;
                }
                else{
                    $result = array('status'=>FALSE,'message'=>$datatime['message'],'type'=>$datatime['type']);
                    return $result;
                }
            }
        }
        if ($type==3){//查看招商会服务
                $sourcetime = $buyService->where('user_id', '=', $user_id)->where('buy_type', '=', 5)
                ->where('buy_status', '=', '1')->where('buy_usable_time','>',time())
                ->order_by('buy_time','ASC')->find();
                if ($sourcetime->user_id<=0){
                    $result = array('status'=>FALSE,'message'=>'未购买相关服务','type'=>'2');
                    return $result;
                }
                $des = $this->getBuyTypeDescribe($sourcetime->buy_type,$sourcetime->buy_type_config);
                //当类型为包次的时候更新Buyservice表次数
                if ($sourcetime->buy_confine_number<=0){
                    $result = array('status'=>FALSE,'message'=>'您购买的'.$des['describe']."/￥".$des['price'].'已用完','type'=>'4');
                    return $result;
                }
                //招商会服务单独操作Buyservicelog表
                //$datatime = $this->writeServiceLog($sourcetime->buy_id,$sourcetime->buy_type,$sourcetime->buy_type_config,$action_id);
                $buyServiceLog = ORM::factory('Buyservicelog');
                //先查出当天同类型已使用的次数，然后做+1处理
                $num = $buyServiceLog->where('log_buy_type', "=", $sourcetime->buy_type."_".$sourcetime->buy_type_config)
                ->where('log_time','<=',time())
                ->where('log_time','>',strtotime(date('Y-m-d')))
                ->count_all();

                //添加数据
                $buyServiceLog->log_buy_type = $sourcetime->buy_type."_".$sourcetime->buy_type_config;
                $buyServiceLog->buy_id = $sourcetime->buy_id;
                $buyServiceLog->log_time = time();
                $buyServiceLog->log_nums = $num+1;
                $buyServiceLog->log_action_id = $action_id;
                try {
                    $buyServiceLog->create();
                    $service = ORM::factory('Buyservice',$sourcetime->buy_id);
                    $service->buy_confine_number = $sourcetime->buy_confine_number-1;
                    $service->update();
                    $result = array('status'=>TRUE,'message'=>'操作成功','type'=>1);
                    return $result;
                }
                catch (Kohana_Exception $e)
                {
                    $des = $this->getBuyTypeDescribe($sourcetime->buy_type,$sourcetime->buy_type_config);
                    $result = array('status'=>FALSE,'message'=>'您购买的'.$des['describe']."/￥".$des['price'].'超过当天使用次数限制','type'=>5);
                    return $result;
                }
        }
        return $result;
    }

    /**
     * 服务使用日志添加入表(针对名片操作)
     * @param int $id 服务表ID
     * return bool
     */
    public function writeServiceLog($id=0,$buy_type=0,$buy_type_config=0,$action_id=0){
        $result = array('status'=>FALSE,'message'=>'未购买相应服务','type'=>2);
        if (is_numeric($id)&&$id>0){
            $buyServiceLog = ORM::factory('Buyservicelog');

            //先查出当天同类型已使用的次数，然后做+1处理
            $num = $buyServiceLog->where('log_buy_type', "=", $buy_type."_".$buy_type_config)
                   //->where('log_time','<=',time())
                   //->where('log_time','>',strtotime(date('Y-m-d')))
                   ->where('buy_id',"=",$id)
                   ->count_all();
            /*//次数操作当天使用次数限制 （目前设置为每天限定30次）
            if ($num>=30){
                $des = $this->getBuyTypeDescribe($buy_type,$buy_type_config);
                $result = array('status'=>FALSE,'message'=>'您购买的'.$des['describe']."/￥".$des['price'].',超过当天使用次数限制','type'=>5);
                return $result;
            }
            */
            //添加数据
            $buyServiceLog->log_buy_type = $buy_type."_".$buy_type_config;
            $buyServiceLog->buy_id = $id;
            $buyServiceLog->log_time = time();
            $buyServiceLog->log_nums = $num+1;
            $buyServiceLog->log_action_id = $action_id;
            try {
                $buyServiceLog->create();
                $result = array('status'=>TRUE,'message'=>'操作成功','type'=>1);
                return $result;
            }
            catch (Kohana_Exception $e)
            {
                return $result;
            }
            return $result;
        }
        else
            return $result;
    }

    /**
     * 服务表中若该过期则更新状态 无需返回值
     * @param int $user_id 服务表ID
     */
    public function closeBuyService($user_id=0){
        if (is_numeric($user_id)&&$user_id>0){
            $buyService = ORM::factory('Buyservice');
            $data = $buyService->where('user_id', "=", $user_id)
            ->where('buy_usable_time','<=',time())
            ->where('buy_status','=','1')
            ->order_by('buy_time','ASC')
            ->find();
            //查到数据则更新数据的buy_status为0
            if ($data->user_id>0){
                $buyService->buy_id = $data->buy_id;
                $buyService->buy_status = 0;
                try {
                    $buyService->save();
                }
                catch (Kohana_Exception $e)
                {

                }
            }
            //更新次数限制的状态
            $buyService = ORM::factory('Buyservice');
            $data = $buyService->where('user_id', "=", $user_id)
            ->where('buy_confine_number','=','0')->where('buy_status','=','1')
            ->and_where_open()->or_where_open()
            ->or_where('buy_type', '=', '2')->or_where('buy_type', '=', '4')->or_where('buy_type', '=', '5')
            ->or_where_close()->and_where_close()
            ->order_by('buy_time','ASC')
            ->find();
            //查到数据则更新数据的buy_status为0
            if ($data->user_id>0){
                $buyService->buy_id = $data->buy_id;
                $buyService->buy_status = 0;
                try {
                    $buyService->save();
                }
                catch (Kohana_Exception $e)
                {

                }
            }
        }
    }

/****掉用服务相关处理 end****/

    /**
     * 读取活动类型对应的配置名字（公用方法）
     * @author 周进
     * @$buy_type int
     * @$buy_type_config int
     * return array('describe'=>'','price'=>'');
     */
    public function getBuyTypeDescribe($buy_type=0,$buy_type_config=0){
        $result = array('describe'=>'','price'=>'');
        $allBuyType = common::getBuyType();
        $selectBuyType = $allBuyType[$buy_type][$buy_type_config];
        $result['describe'] = $allBuyType[$buy_type]['remarks'].$selectBuyType['describe'];
        $result['price'] = $selectBuyType['price'];
        return $result;
    }

    /**
    * 获取用户累计充值实际到账总额
    * @author 钟涛
    * @param unknown_type $user_id
    */
    public function getAccountTotalRecharge($user_id){
        //$total_recharge =0;
        //         $account_total = ORM::factory("Accountorder")
        //                          ->where("order_user_id","=",$user_id)
        //                          ->where("order_operate_status","=",2)
        //                          ->where("order_type","in",array(1,2,4,5))
        //                          ->find_all();
        //         if($account_total->count()){
        //             foreach($account_total as $count){
        //             	if($count->order_account){
        //             		$total_recharge += $count->order_real_account;//实际到账金额
        //             	}
        //             }
        //         }
        //order_type 1为线上充值;2为线下汇款;4为现金充值;5银行卡
        //线上总充值金额
        $total_account01 = DB::select(array(DB::expr('SUM(order_real_account)'),'sum'))->from('account_order')
                        ->where('order_user_id','=',$user_id)
                        ->and_where('order_type','=',1)
                        ->and_where('order_status','=',1)->execute();//充值状态默认0未成功1为返回成功
        //线下总充值金额
        $total_account02 = DB::select(array(DB::expr('SUM(order_real_account)'),'sum'))->from('account_order')
                ->where('order_user_id','=',$user_id)
                ->and_where('order_type','in',array(2,4,5))//2为线下汇款;4为现金充值;5银行卡
                ->and_where('order_line_status','=',3)->execute();//线下汇款状态默认0,1为新增，2为未通过，3为已审核
        return $total_account01[0]['sum']+$total_account02[0]['sum'];
    }

    /**
    * 检查用户余额是否足够支出(减少时必须先验证)
    * @author 周进
    */
    public function checkAccountNumber($user_id=0,$accoumt=0){
        $result = ORM::factory('Account')->where('account_user_id', '=', $user_id)->find();
        if ($result->account<$accoumt){
            return FALSE;
        }
        else
            return TRUE;
    }
    /**
     * 检测account账户金额表中是否已有该用户的信息,没有的加入并写入account_log表，有的更新
     * @author 周进
     * @param int $user_id
     * @param $account 变动的金额为0时不作修改
     * @param $account_class 变动类型资金影响方式：0为不变1为增加2为减少
     * @return bool
     */
    public function checkAccountUser($user_id=0,$account=0,$account_class=0){
        if($this->checkAccountStatus($user_id)==FALSE)//检查是否被禁用
            return FALSE;
        $model = ORM::factory('Account');
        $result = $model->where('account_user_id', '=', $user_id)->find();
        $model->account_user_id = intval($user_id);
        $model->account_stastus = 1;
        $model->account_time = time();
        if ($result->account_id!=""){//已有的做更新处理
            if ($account!=0){
                $account_change_amount = $account!=""?$account:'0.00';
                $model->account = common::getMark($result->account,$account_class,$account_change_amount);
                try {
                    $model->save();
                    return TRUE;
                }
                catch (Kohana_Exception $e)
                {
                    return FALSE;
                }
            }
            return TRUE;
        }
        else{//没有的初始使用用户，创建用户账户
            $model->account = '0.00';
            try {
                $model->create();
                return TRUE;
            }
            catch (Kohana_Exception $e)
            {
                return FALSE;
            }
        }
    }

    /**
     * 获取账户表的数据(如用户余额)
     * @author 周进
     * @param int $account_id czzs_account表对应ID
     * @return ORM
     */
    public function getAccount($user_id='',$account_id=''){
        $account = ORM::factory('Account');
        if ($user_id!="")
            return $account->where('account_user_id', '=', $user_id)->find()->as_array();
        elseif ($account_id!='')
            return $account->where('account_id', '=', $account_id)->find()->as_array();
    }

    /**
     * 获取充值表的单条数据
     * @author 周进
     * @param int $order_id czzs_account_order表对应ID
     * @return ORM
     */
    public function getAccountorder($order_id=0){
        $model = ORM::factory('Accountorder');
        return $model->where('order_id', '=', intval($order_id))->find()->as_array();
    }

    /**
     * 获取账户购买服务的数据
     * @author 周进
     * @param int $account_id czzs_account表对应ID
     * @return ORM
     */
    public function getBuyService($user_id=''){
        $return_arr=array();
        $model = ORM::factory('Buyservice');
        $model->where('user_id', '=', $user_id)->where('buy_show_status','=','1');
        $page = Pagination::factory(array(
                'total_items'    => $model->reset(false)->count_all(),
                'items_per_page' => 10,
        ));
        //查询支出收入的总额
        $list = $model->select("*")->limit($page->items_per_page)->offset($page->offset)->order_by('buy_time', 'DESC')->find_all( );
        $return_arr['list'] = $list;
        $return_arr['page'] = $page;
        return $return_arr;
    }

    /**
     * 公用方法资金操作表account_log加入数据
     * @author 周进
     * @param int $user_id 用户ID
     * @param array $post array('account_change_amount','account_class','account_note','account_comments')
     * @param int $account_type 操作类型(从配置文件读：1为充值、2为购买服务、3查看名片、4递出名片、5报名招商会、6为冻结账户)默认0为新账户建立
     * @param int $account_type_id 不同类型对应的数据操作表的ID如充值表ID
     * @param int $account_user_type 操作途径（默认1为用户自己操作,2为后台管理员操作）
     * @return bool
     */
    public function addAccountLog($user_id=0,$post=array(),$account_type=0,$account_type_id=0,$account_user_type=1){
        $account_change_amount = isset($post['account_change_amount'])?trim($post['account_change_amount']):"0.00";
        $account_class = isset($post['account_class'])?trim($post['account_class']):"0";
        //先检测更新账户表里的数据
        if (intval($user_id)>0){
            if(!$this->checkAccountUser($user_id))
                return FALSE;
        }
        else
            return FALSE;
        //提取余额
        $source = $this->getAccount($user_id);
        $account_amount = common::getMark($source['account'],$account_class,$account_change_amount);
        //处理加入czzs_account_log表数据
        $model = ORM::factory('Accountlog');
        $model->account_user_id = intval($user_id);
        $model->account_user_type = intval($account_user_type);
        $model->account_type = $account_type;
        $model->account_class = isset($post['account_class'])?trim($post['account_class']):"0";
        $model->account_change_amount = $account_change_amount;
        $model->account_amount = $account_amount!=""?$account_amount:"0.00";
        $model->account_comments = isset($post['account_comments'])?trim($post['account_comments']):"";
        $model->account_note = isset($post['account_note'])?trim($post['account_note']):"";
        $model->account_log_status = 1;
        $model->account_log_time = time();
        $model->account_type_id = $account_type_id;
        $model->account_comments_type = isset($post['account_comments_type'])?trim($post['account_comments_type']):"0";
        $model->account_ip = ip2long(Request::$client_ip);
        try {
            $model->create();
            return TRUE;
        }
        catch (Kohana_Exception $e)
        {
            return FALSE;
        }
        return FALSE;
    }

    /**
     * 添加线下汇款数据
     * @author 周进
     * @param int $user_id 用户ID
     * @param array $post
     * @return bool
     */
    public function editOutLineRecharge($user_id=0,$post=array()){
        $post = Arr::map("HTML::chars", $post);
        if($this->checkAccountUser(intval($user_id))!=TRUE)
            return FALSE;
        if(isset($post['order_realname'])&&$post['order_realname']=="")
            return FALSE;
        if(isset($post['order_bank_name'])&&$post['order_bank_name']=="")
            return FALSE;
        if(isset($post['order_account'])&&$post['order_account']=="")
            return FALSE;
        if(isset($post['order_line_time'])&&$post['order_line_time']=="")
            return FALSE;
        if (isset($post['order_code'])&&$post['order_code']=="")
            return FALSE;
        if(isset($post['order_line_time'])&&isset($post['order_bank_name'])&&isset($post['order_account'])&&isset($post['order_line_time'])){
            $model = ORM::factory('Accountorder');
            $model->order_user_id = intval($user_id);
            $model->order_no = time();
            $model->order_type = 2;
            $model->order_account = isset($post['order_account'])?trim($post['order_account']):"0.00";
            $model->order_real_account = '0.00';
            $model->order_bank_name = isset($post['order_bank_name'])?trim($post['order_bank_name']):NULL;
            $model->order_realname = isset($post['order_realname'])?trim($post['order_realname']):NULL;
            $model->order_code = isset($post['order_code'])?trim($post['order_code']):NULL;
            $model->order_out_no = isset($post['order_out_no'])?trim($post['order_out_no']):NULL;
            $model->order_remarks = isset($post['order_remarks'])?trim($post['order_remarks']):NULL;
            $model->order_line_note = isset($post['order_line_note'])?trim($post['order_line_note']):NULL;
            $model->order_line_time = isset($post['order_line_time'])?trim($post['order_line_time']):"0";
            $model->order_line_status = 1;
            $model->order_status = '0';
            $model->order_time = '0';
            $model->order_create_time = time();
            $model->order_completion_time = '0';
            $model->order_operate_status = '1';
            $model->order_delete = '0';
            try {
                $result = $model->create();//线下汇款先不加入记录表account_log，后台审核后加入
                return $result->order_id;
            }
            catch (Kohana_Exception $e)
            {
                return FALSE;
            }
        }
        return FALSE;
    }

    /**
     * 获取用户资金操作历史记录
     * @author周进
     * @param int $user_id 用户ID
     * @param int $time 时间段 7为近一周，30为1个月，90为3个月，365为一年
     * @return ORM
     */
    public function getAccountLog($user_id=0,$time=30){
        $user_id=intval($user_id);
        $return_arr=array();
        $acttime = time()-$time*24*60*60;
        $model = ORM::factory('Accountlog');
        $model->where('account_user_id', '=', intval($user_id))->where('account_log_time','>',$acttime);
        $model->where('account_comments_type', '!=', 14);//不包括赠送的金额列表
        $page = Pagination::factory(array(
                'total_items'    => $model->reset(false)->count_all(),
                'items_per_page' => 10,
        ));
        //查询支出收入的总额
        $list = $model->select("*")->limit($page->items_per_page)->offset($page->offset)->order_by('account_log_time', 'DESC')->find_all( );
        $addaccount = DB::select(array(DB::expr('SUM(account_change_amount)'),'account'))->from('account_log')->where('account_class','=',1)->and_where('account_user_id','=',$user_id)->and_where('account_log_time','>',$acttime)->execute();
        $reduceaccount = DB::select(array(DB::expr('SUM(account_change_amount)'),'reduceaccount'))->from('account_log')->where('account_class','=',2)->and_where('account_user_id','=',$user_id)->and_where('account_log_time','>',$acttime)->execute();
        $return_arr['list'] = $list;
        $return_arr['page'] = $page;
        $return_arr['addaccount'] = $addaccount[0]['account'];
        $return_arr['reduceaccount'] = $reduceaccount[0]['reduceaccount'];
        return $return_arr;
    }
    /**
     * 获取用户资金操作历史记录 修改版
     * @modify_author 赵路生 2013-8-21
     * @param int $user_id 用户ID
     * @param int $start_time $end_time 为时间戳类型
     * @return arr
     */
    public function getAccountLogModify($user_id=0,$start_time,$end_time,$account_comments_type ){
        $user_id=intval($user_id);
        $return_arr=array();
        $acttime = time();
        $start_time = intval($start_time);
        $end_time = intval($end_time);

        $account_comments_type = intval($account_comments_type );
        $account_comments_type_arr = common::getCountDetailClass();
        //判断传入的是不是时间戳类型
        if(!( is_numeric ($start_time) && $start_time <= 2147483647)){
            $start_time = 0;
        }
        if(!( is_numeric ($end_time) && $end_time <= 2147483647)){
            $end_time = time();
        }
        //还要对传进来的时间戳进行处理
        if($start_time > $end_time){
            $start_time = 0;
            $end_time = time();
        }
        if(!array_key_exists($account_comments_type,$account_comments_type_arr)){
            $account_comments_type = 0;
        }
        //开始组合筛选条件
        $model = ORM::factory('Accountlog');
        $model->where('account_user_id', '=', intval($user_id));

        if($account_comments_type){
            $model->where('account_comments_type', '=', $account_comments_type);
        }
        $model->where('account_log_time','>=',$start_time);
        $model->where('account_log_time','<=',$end_time);
        //不包括赠送金额的
        $model->where('account_comments_type','!=',14);
        //分页信息
//         $page = Pagination::factory(array(
//                 'total_items'    => $model->reset(false)->count_all(),
//                 'items_per_page' => 10,
//         ));
        $key = isset ( $_GET ['p'] ) ? 'p' : 'page';
   		$page = Pagination::factory(array(
				'total_items' => $model->reset(false)->count_all(),
				'view' => 'pagination/Simple',
				'current_page' => array('source' => 'accountlist', 'key' => $key),
				'items_per_page' => 10,
		));
        $list = $model->select("*")->limit($page->items_per_page)->offset($page->offset)->order_by('account_log_time', 'DESC')->find_all( );
        //查询总支出(没有时间段)
        //$addaccount = DB::select(array(DB::expr('SUM(account_change_amount)'),'account'))->from('account_log')->where('account_class','=',1)->and_where('account_user_id','=',$user_id)->and_where('account_log_time','>=',$start_time)->and_where('account_log_time','<=',$end_time)->execute();
        //$reduceaccount = DB::select(array(DB::expr('SUM(account_change_amount)'),'reduceaccount'))->from('account_log')->where('account_class','=',2)->and_where('account_user_id','=',$user_id)->and_where('account_log_time','>=',$start_time)->and_where('account_log_time','<=',$end_time)->execute();
        $addaccount = DB::select(array(DB::expr('SUM(account_change_amount)'),'account'))->from('account_log')->where('account_class','=',1)->and_where('account_user_id','=',$user_id)->execute();
        $reduceaccount = DB::select(array(DB::expr('SUM(account_change_amount)'),'reduceaccount'))->from('account_log')->where('account_class','=',2)->and_where('account_user_id','=',$user_id)->execute();
        //收支分额
        if($account_comments_type){
            $sub_reduceaccount = DB::select(array(DB::expr('SUM(account_change_amount)'),'sub_reduceaccount'))->from('account_log')->where('account_class','=',2)->and_where('account_user_id','=',$user_id)->and_where('account_comments_type','=',$account_comments_type)->and_where('account_log_time','>=',$start_time)->and_where('account_log_time','<=',$end_time)->execute();
        }else{
            $sub_reduceaccount = DB::select(array(DB::expr('SUM(account_change_amount)'),'sub_reduceaccount'))->from('account_log')->where('account_class','=',2)->and_where('account_user_id','=',$user_id)->and_where('account_log_time','>=',$start_time)->and_where('account_log_time','<=',$end_time)->execute();
        }

        //账户余额
        $model2 = ORM::factory('Account');
        $model_amount= $model2->where('account_user_id', '=', intval($user_id))->find();
        $return_arr['account_amount']  =$model_amount-> account;

        $return_arr['list'] = $list;
        $return_arr['page'] = $page;

        $return_arr['addaccount'] = $addaccount[0]['account'];
        $return_arr['reduceaccount'] = $reduceaccount[0]['reduceaccount'];
        $return_arr['sub_reduceaccount'] = $sub_reduceaccount[0]['sub_reduceaccount'];
        return $return_arr;
    }
    /**
     * 导出账户信息列表excel的数据
     * @author 周进
     * @modified by 赵路生 2013-8-23
     */
    public function getAccountExcel($user_id=0,$start_time,$end_time,$account_comments_type){
        $user_id=intval($user_id);
        $start_time = intval($start_time);
        $end_time = intval($end_time);

        $account_comments_type = intval($account_comments_type );
        $account_comments_type_arr = common::getCountDetailClass();
        //判断传入的是不是时间戳类型
        if(!( is_numeric ($start_time) && $start_time <= 2147483647)){
            $start_time = 0;
        }
        if(!( is_numeric ($end_time) && $end_time <= 2147483647)){
            $end_time = time();
        }
        //还要对传进来的时间戳进行处理
        if($start_time > $end_time){
            $start_time = 0;
            $end_time = time();
        }
        if(!array_key_exists($account_comments_type,$account_comments_type_arr)){
            $account_comments_type = 0;
        }
        $model = ORM::factory('Accountlog');
        if($account_comments_type){
            $model->where('account_user_id', '=', $user_id)->where('account_comments_type','=',$account_comments_type)->where('account_class','=',2)->where('account_log_time','>=',$start_time)->where('account_log_time','<=',$end_time);
        }else{
            $model->where('account_user_id', '=', $user_id)->where('account_log_time','>=',$start_time)->where('account_log_time','<=',$end_time);
        }

        $list = $model->select("*")->order_by('account_log_time', 'DESC')->find_all( );
        return $list;
    }

    /**
     * 线上充值新增数据处理类//只做处理不做验证，关于安全和事务以及加到日志表的数据 提到外层去做
     * @author 周进
     * @param int $user_id 用户ID
     * @param array $post
     * @return bool
     */
    public function editOnLineRecharge($user_id=0,$post=array()){
        $post = Arr::map("HTML::chars", $post);
        if($this->checkAccountUser(intval($user_id))!=TRUE)
            return FALSE;
        $model = ORM::factory('Accountorder');
        $model->order_user_id = intval($user_id);
        $model->order_no = time();
        $model->order_type = Arr::get($post, 'order_type','2');
        $model->order_type_id = Arr::get($post, 'order_type_id','0');
        $model->order_account = Arr::get($post, 'order_account','0.00');
        $model->order_real_account = Arr::get($post, 'order_account','0.00');
        $model->order_bank_name = Arr::get($post, 'order_bank_name','');
        $model->order_realname = Arr::get($post, 'order_realname','');
        $model->order_code = Arr::get($post, 'order_code','');
        $model->order_out_no = Arr::get($post, 'order_out_no','');
        $model->order_remarks = Arr::get($post, 'order_remarks','');
        $model->order_line_note = Arr::get($post, 'order_line_note','');
        $model->order_line_time = Arr::get($post, 'order_line_time','0');
        $model->order_status = Arr::get($post, 'order_status','0');
        $model->order_time = Arr::get($post, 'order_time','0');
        $model->order_create_time = time();
        $model->order_completion_time = Arr::get($post, 'order_completion_time','0');
        $model->order_operate_status = Arr::get($post, 'order_operate_status','1');
        $model->order_delete = Arr::get($post, 'order_delete','0');
        try {
            $result = $model->create();//先不加入记录表account_log，银行返回成功后加入，购买站内服务的去相应方法里面处理
            return $result->order_id;
        }
        catch (Kohana_Exception $e)
        {
            return FALSE;
        }
    }

    /**
     * 购买服务类 1.先检查用户金额是否足够，2.如果资金够，先加入czzs_account_order表->buy_service表->资金记录表->更新账户表
     * @author 周进
     * @param array $post service_id=>服务类型_服务类型对应配置_购买限制的总次数
     * @return bool
     * @date 2013/1/19
     */
    public function buyService($post =array('service_id'=>'0_0_0'),$user_id){
        //获取客服电话号码
        $arrCustomerPhone = common::getCustomerPhone();
        $result_arr = array('status'=>FALSE,'message'=>'很抱歉，您购买名片服务失败，您可重新购买','type'=>0);
        $service_id = Arr::get($post, 'service_id',"0_0_0");
        if($this->checkAccountUser(intval($user_id))!=TRUE){
            $result_arr = array('status'=>FALSE,'message'=>'您的账户被禁用，请联系客服：'.$arrCustomerPhone['1'].'！','type'=>-1);
            return $result_arr;
        }
        //开始事务
        $db = Database::instance();
        $db->begin();
        if (strrpos($service_id, "_") === false){
            $db->rollback();
            return $result_arr;
        }
        $service_config = explode("_", $service_id);
        //读取购买服务的相关配置
        $allBuyType = common::getBuyType();
        $selectBuyType = $allBuyType[$service_config[0]][$service_config[1]];

        //先检测账户金额是否足够
        if ($this->checkAccountNumber($user_id,$selectBuyType['price'])==FALSE){
            $result_arr = array('status'=>FALSE,'message'=>'对不起，您的账户余额不足，前往账户中心充值以享受名片服务？');
            return $result_arr;
        }

        //加入czzs_account_order订单表并返回订单ID
        $orderData = array('order_type'=>3,
                'order_account'=>$selectBuyType['price'],
                'order_real_account'=>$selectBuyType['price'],
                'order_remarks'=>$allBuyType[$service_config[0]]['remarks'].$selectBuyType['describe'],
                'order_status'=>'1',
                'order_type_id'=>$service_config[0]);
        $order_id = $this->editOnLineRecharge($user_id,$orderData);
        if ($order_id==FALSE){
            $db->rollback();
            //$result_arr = array('status'=>FALSE,'message'=>'购买名片服务失败，您可重新购买','type'=>0);
            return $result_arr;
        }
        //返回成功后处理购买服务表

            //查找上个包月服务的到期时间start
            $source = ORM::factory('Buyservice')->where('user_id', '=', $user_id)->where('buy_type', '=', $service_config[0])
            ->where('buy_status', '=', '1')->where('buy_usable_time','>',time())->order_by('buy_time','DESC')->find();
            if ($source->user_id>0){
                $buy_usable_time = $source->buy_usable_time+$selectBuyType['time'];
            }
            else
                $buy_usable_time = time()+$selectBuyType['time'];
            //end
            $buyService = ORM::factory('Buyservice');
            $buyService->user_id  = $user_id;
            $buyService->order_id = $order_id;
            $buyService->buy_type = $service_config[0];
            $buyService->buy_type_config = $service_config[1];
            $buyService->buy_time = time();
            $buyService->buy_status = 1;
            //购买时限制的总次数
            $buyService->buy_confine_number = $service_config[2]==""?'0':$service_config[2];
            //购买的使用有效时间(顺沿上次的时间)
            $buyService->buy_usable_time = $buy_usable_time;
            try {//加入购买服务表
                $result = $buyService->create();
                $data = array(
                            'account_change_amount'=>$selectBuyType['price'],
                            'account_class'=>'2',
                            'account_note'=>$allBuyType[$service_config[0]]['remarks'].$selectBuyType['describe'],
                            'account_comments'=>$allBuyType[$service_config[0]]['remarks'].$selectBuyType['describe'],
                        );
                //加入资金日志操作记录表
                $source = $this->addAccountLog($user_id,$data,'2',$order_id);
                if ($source!=TRUE){
                    $db->rollback();
                    //$result_arr = array('status'=>FALSE,'message'=>'很抱歉，加入名片服务失败，您可重新购买','type'=>0);
                    return $result_arr;
                }
                else{//订单表加入成功后更新资金操作记录表成功后执行事务
                    //更新用户金额表
                    $useraccount = $this->getAccount($user_id);
                    $this->checkAccountUser($user_id,$selectBuyType['price'],2);
                    $db->commit();
                    $result_arr = array('status'=>$result->buy_id,'message'=>'恭喜您，您已成功购买名片服务');
                    if ($service_config[0]==2){
                        $sta = ORM::factory('Buyservice')->where('user_id', "=", $user_id)->where('buy_usable_time','>',time())->where('buy_status','=','1')->where('buy_type','=','1')->find();
                        if ($sta->user_id>0)
                            $result_arr = array('status'=>$result->buy_id,'message'=>'恭喜您成功购买名片服务，您的放大镜服务将在名片包月服务到期后使用');
                    }
                    if ($service_config[0]==4){
                        $sta = ORM::factory('Buyservice')->where('user_id', "=", $user_id)->where('buy_usable_time','>',time())->where('buy_status','=','1')->where('buy_type','=','3')->find();
                        if ($sta->user_id>0)
                            $result_arr = array('status'=>$result->buy_id,'message'=>'恭喜您成功购买名片服务，您的邮票服务将在发送名片包月服务到期后使用');
                    }
                    return $result_arr;
                }
            }
            catch (Kohana_Exception $e)
            {
                $db->rollback();
                $result_arr = array('status'=>FALSE,'message'=>'很抱歉，购买名片服务失败，您可重新购买','type'=>0);
                return $result_arr;
            }
    }

    /**
     * 线上充值返回更新数据处理类//只做处理不做验证，关于安全验签提到支付类去处理
     * @author 周进
     * @param int $user_id 用户ID (不是必须的，当从外站比如支付宝返回的时候用户ID可以不存在)
     * @param array $post
     * @return bool
     */
    public function updateRecharge($post=array(),$order_id=0){
        $post = Arr::map("HTML::chars", $post);
        //开始事务
        $db = Database::instance();
        $db->begin();

        $model = ORM::factory('Accountorder');
        $order_no = Arr::get($post, 'order_no','');
        if ($order_id==0){
            if ($order_no=="")
                return FALSE;
            $result = $model->where('order_no', '=', $order_no)->find();
            //防止重复更新
            if ($result->order_status==1){
                $db->rollback();
                return $result->order_id;
            }
            if ($result->order_id==""){
                $db->rollback();
                return FALSE;
            }
        }
        else
            $result = $model->where('order_id', '=', $order_id)->find();
        $model->order_id = $result->order_id;
        $model->order_real_account = Arr::get($post, 'order_real_account','0');
        $model->order_bank_name = Arr::get($post, 'order_bank_name','支付宝');
        $model->order_code = Arr::get($post, 'order_code','');
        $model->order_out_no = Arr::get($post, 'order_out_no','');
        $model->order_status = Arr::get($post, 'order_status','1');
        $model->order_time = Arr::get($post, 'order_time','');
        $model->order_operate_status = 2;
        try {
            $accountorderresult = $model->update();
            //充值成功增加诚信点
            $service_ity = Service::factory('User_Company_Integrity');
            //$last_ity = $service_ity->getIntegrityByTime($result->order_user_id);
            $get_ity = $service_ity->getIntegrityByCharge($result->order_user_id);
            if($get_ity){
                //充值成功增加诚信点消息提醒 start
                //$now_ity = $service_ity->getIntegrityByTime($result->order_user_id);
                //$add_ity = $now_ity - $last_ity;
                //$msg_service = new Service_User_Ucmsg();
                //$msg_service->pushMsg($result->order_user_id, "company_integrity", "您成功充值".Arr::get($post, 'order_real_account','0')."元，增加".$get_ity."点诚信指数。",URL::website("company/member/basic/integrity"));
                //end
                $smsg = Smsg::instance();
                //内部消息发送
                $smsg->register(
                        "tip_company_integrity",//我的诚信
                        Smsg::TIP,//类型
                        array(
                                "to_user_id"=>$result->order_user_id,
                                "msg_type_name"=>"company_integrity",
                                "to_url"=>URL::website("company/member/basic/integrity")
                        ),
                        array(
                                "code"=>$get_ity,
                                "account"=>Arr::get($post, 'order_real_account','0'),
                                "type"=>"account"

                        )

                );


            }
            //更新成功后加入记录表account_log
            $data = array(
                    'account_change_amount'=>Arr::get($post, 'order_real_account','0'),
                    'account_class'=>'1',
                    'account_note'=>'充值成功返回',
                    'account_comments'=>'充值成功返回',
                    'account_comments_type'=>'3' //@modified by 赵路生
            );
            //加入资金日志操作记录表
            $source = $this->addAccountLog($result->order_user_id,$data,'1',$result->order_id);
            $account_new=0;
            if ($source){//赠送金额@钟涛
                $account_old=Arr::get($post, 'order_real_account','0');
                $account_new=common::getCostFree($account_old);
                if($account_new){//赠送金额再次加入资金日志操作记录表一条记录
                    $data02 = array(
                            'account_change_amount'=>$account_new,//获取赠送的金额数
                            'account_class'=>'1',
                            'account_note'=>'线上充值赠送金额：'.$account_new,
                            'account_comments'=>'线上充值赠送金额：'.$account_new,//赠送金额
                            'account_comments_type'=>14,
                    );
                    $source = $this->addAccountLog($result->order_user_id,$data02,'1',$result->order_id);
                }
            }
            if ($source!=TRUE){
                $db->rollback();
                return FALSE;
            }
            else{//订单表加入成功后更新资金操作记录表成功后执行事务
                //更新用户金额表
                $useraccount = $this->getAccount($result->order_user_id);
                $this->checkAccountUser($result->order_user_id,Arr::get($post, 'order_real_account','0'),1);
                if($account_new){//更新用户金额表【赠送金额】
                    $b = $this->checkAccountUser($result->order_user_id,$account_new,1);
                }
                $db->commit();
                //判断是否已经扣除平台服务费用
                $cominfo=ORM::factory('Companyinfo')->where('com_user_id', '=', $result->order_user_id)->find();
                if($cominfo->loaded() && $cominfo->platform_service_fee_status!=1){//未扣除需要扣除
                    sleep(1);//等待1秒后执行
                    $useaccout=$this->useAccount($result->order_user_id,13,1,'平台服务费用');
                    if(arr::get($useaccout,'status')){//扣除成功
                        $cominfo->platform_service_fee_status=1;
                        $cominfo->platform_service_fee_changetime=time();
                        $cominfo->update();//更新为开通服务
                    }
                }
                return $result->order_id;
            }
        }
        catch (Kohana_Exception $e)
        {
            $db->rollback();
            return FALSE;
        }
    }

    /**
     * 首页用数据
     * @author周进
     */
    public function checkAccountIndex($user_id=0){
        $user_id=intval($user_id);
        $result = array('day'=>'','num'=>'','today'=>'','tonum'=>'');
        $day = "";
        //包月累计时间
        $num = DB::select()->from('buy_service')->where('user_id','=',$user_id)->and_where('buy_status','=',1)->and_where('buy_usable_time','>',time())->and_where('buy_type','=',1)->order_by('buy_type', 'desc')->limit(1)->execute();
        if (count($num)==1){
            $day = ceil(($num[0]['buy_usable_time']-time())/(24*60*60));
        }
        //包次累计次数
        $sum = DB::select(array(DB::expr('sum(buy_confine_number)'),'num'))->from('buy_service')->where('user_id','=',$user_id)->and_where('buy_status','=',1)->and_where('buy_usable_time','>',time())->and_where('buy_type','=',2)->and_where('buy_confine_number','>',0)->execute();
        if ($sum[0]['num']!="")
            $result['num'] = $sum[0]['num'];
        $result['day'] = $day;
        $today = "";
        //包月累计时间
        $num = DB::select()->from('buy_service')->where('user_id','=',$user_id)->and_where('buy_status','=',1)->and_where('buy_usable_time','>',time())->and_where('buy_type','=',3)->order_by('buy_usable_time','desc')->limit(1)->execute();
        if (count($num)==1){
            $today = ceil(($num[0]['buy_usable_time']-time())/(24*60*60));
        }
        //包次累计次数
        $sum = DB::select(array(DB::expr('sum(buy_confine_number)'),'num'))->from('buy_service')->where('user_id','=',$user_id)->and_where('buy_status','=',1)->and_where('buy_usable_time','>',time())->and_where('buy_type','=',4)->and_where('buy_confine_number','>',0)->execute();
        if ($sum[0]['num']!="")
            $result['tonum'] = $sum[0]['num'];
        $result['today'] = $today;
        return $result;
    }

    /**
     * 直接通过金额购买来实现功能 1.先检查用户是否禁用，2金额是否足够，3.如果资金够，先加入czzs_account_order表->资金记录表->更新账户表
     * @author 周进
     * @param int $user_id 用户ID
     * @param int $account_type 操作类型1为线上充值2为线下汇款3为购买服务4为现金充值5银行卡6查看名片7递出名片8交换名片9报名招商会
     * @param decimal $account 金额
     * @param account_class 资金影响方式：0为不变1为增加2为减少
     * @return array
     * @date 2013/3/29
     */
    public function buyFromAccount($user_id=0,$account_type=1,$account=0,$account_class=0){
        //获取客服电话号码
        $arrCustomerPhone = common::getCustomerPhone();
        $user_id=intval($user_id);
        $result = array('status'=>FALSE,'message'=>'您的操作失败，请联系客服：'.$arrCustomerPhone['1'].'！','type'=>'0');
        //检查用户是否禁用
        if($this->checkAccountUser(intval($user_id))!=TRUE){
            $result_arr = array('status'=>FALSE,'message'=>'您的账户被禁用，请联系客服：'.$arrCustomerPhone['1'].'！','type'=>-1);
            return $result_arr;
        }
        //检测账户金额是否足够
        if ($this->checkAccountNumber($user_id,$account)==FALSE){
            $result_arr = array('status'=>FALSE,'message'=>'对不起，您的账户余额不足，前往账户中心充值以享受该服务？','type'=>2);
            return $result_arr;
        }
        //查看名片的当天做20次的次数限制
        if ($account_type == 6){
            $sum = DB::select(array(DB::expr('count(order_id)'),'num'))->from('account_order')->where('order_user_id','=',$user_id)->and_where('order_status','=','2')
            ->and_where('order_type','=',$account_type)
            ->and_where(DB::expr('day(from_unixtime(order_create_time))'),'=',DB::expr('day(now())'))->execute();
            if ($sum[0]['num']>=20){
                $result_arr = array('status'=>FALSE,'message'=>'很抱歉，您每天只能查看20张个人名片。今天的查看名额已满，无法再查看。','type'=>5);
                return $result_arr;
            }
        }
        //递出名片的当天做30次的次数限制
        if ($account_type == 7){
            $sum = DB::select(array(DB::expr('count(order_id)'),'num'))->from('account_order')->where('order_user_id','=',$user_id)->and_where('order_status','=','2')
            ->and_where('order_type','=',$account_type)
            ->and_where(DB::expr('day(from_unixtime(order_create_time))'),'=',DB::expr('day(now())'))->execute();
            if ($sum[0]['num']>=30){
                $result_arr = array('status'=>FALSE,'message'=>'很抱歉，您每天只能递出30张名片。今天的使用次数已满，无法再递出。','type'=>5);
                return $result_arr;
            }
        }

        //开始事务
        $db = Database::instance();
        $db->begin();

        //加入czzs_account_order订单表并返回订单ID
        $orderData = array('order_type'=>$account_type,
                        'order_account'=>$account,
                        'order_real_account'=>$account,
                        'order_remarks'=>common::getAccountType($account_type),
                        'order_status'=>'2',
                    );
        $order_id = $this->editOnLineRecharge($user_id,$orderData);
        //加入资金日志操作记录表
        $data = array(
                'account_change_amount'=>$account,
                'account_class'=>$account_class,
                'account_note'=>common::getAccountType($account_type),
                'account_comments'=>common::getAccountType($account_type),
                'account_comments_type'=>$account_type //@modified by 赵路生
        );
        $source = $this->addAccountLog($user_id,$data,$account_class,$order_id);
        if ($source!=TRUE){
            $db->rollback();
            return $result_arr;
        }
        else{//订单表加入成功后更新资金操作记录表成功后执行事务
            //更新用户金额表
            $useraccount = $this->getAccount($user_id);
            $this->checkAccountUser($user_id,$account,2);
            $db->commit();
            $result_arr = array('status'=>TRUE,'message'=>'操作成功','type'=>1);
            return $result_arr;
        }
        return $result_arr;
    }


    /**
     * @author 周进
     * 账户中心完成各种触发事件引起账户中心变动的程序处理
     * @param int $user_id 用户ID
     * @param int $type 操作类型（取自配置文件）1为线上充值2为线下汇款3为购买服务4为现金充值5银行卡6查看名片7递出名片8交换名片9报名招商会
     * $param int $accountstatus 是否执行资金更新 0为不执行至检测相关数据 1为直接扣费
     * @param int $note 操作备注说明 如查看张三名片
     * @return array type 0：账户禁用1:正常返回2：未购买相关服务（或余额不足）3：包月服务到期4：包次服务到期5：服务当天限制次数用完6:次服务被浪费，补充一个-1未提示信息未实际操作
     * @date 2013/5/9
     */
    public function useAccount($user_id=0,$type=0,$accountstatus=0,$note=''){
       /*
        * 用户消费触发事件
        * 一、检查账户是否被禁用
        * 二、检查购买相应服务存在且正常时，直接从服务扣除
        * 三、检查购买相应服务存在其他情况时
        *     1.没有购买名片服务时（第一次提醒）：您可以去购买名片服务，享受优惠，去购买名片服务/如不购买名片服务，查看此张名片将直接扣除您账户中的100元人民币，直接扣除
        *     2.名片服务过期或者已经用完（第一次提醒）：您购买的名片服务已过期/已用完，去购买名片服务/如不购买名片服务，查看此张名片将直接扣除您账户中的100元人民币，直接扣除
        *     3.名片服务过期或者已经用完或者没有购买过名片服务（非第一次提醒）：查看此张名片将扣除您账户中的100元人民币，是否继续？确定和取消按钮。
        *     4.余额不足：对不起，您的账户余额不足，充值
        *     5.余额如果未购买相关服务则直接从资金中购买
        *
        *
        * <span style="font-size:18px;color:#333;">您购买的名片服务已过期/已用完</span>
        * <a href="#" style="background:#cb230c;display:inline-block;height:28px;line-height:28px;color:#fff;font-size:13px;padding:0 8px;text-decoration:none;*margin-bottom:-5px;">去购买名片服务</a>'
        */
        $arrCustomerPhone = common::getCustomerPhone();
        $result = array('is_forbid'=>true,'forbid_content'=>'出现异常','status'=>FALSE,'message'=>'您的账户被禁用，请联系客服：'.$arrCustomerPhone['1'].'！','type'=>'0');
        //账户状态 是否禁用
        if($this->checkAccountStatus($user_id)==FALSE){
        	$forbid_return = $this->getForbidAccountNote($user_id);
        	if($forbid_return && $forbid_return['type'] == 'refund'){
        		$result = array('is_forbid'=>true,'forbid_content'=>'全额退款中','status'=>FALSE,'message'=>'您的账户因全额退款中，已被禁用，如有疑问请联系客服'.$arrCustomerPhone['1'].'！','type'=>'0');
        	}else{
        		if(!$forbid_return['note']){
        			$forbid_return['note']='出现异常';
        		}
        		$result = array('is_forbid'=>true,'forbid_content'=>$forbid_return['note'],'status'=>FALSE,'message'=>'您的账户因"'.$forbid_return['note'].'"，已被禁用，如有疑问请联系客服：'.$arrCustomerPhone['1'].'！','type'=>'0');
        	}
        	return $result;
        }
        //检测服务过期和使用完的自动更新buy_status=0
        $this->closeBuyService($user_id);
        //返回 0禁用-1提示性信息
        $buyService = ORM::factory('Buyservice');

        switch ($type) {
            case '5' :
                $buy_type = 2;//查看名片对应的Buyservice配置参数
            case '6' :
                $buy_type = 2;//查看名片对应的Buyservice配置参数
                break;
            case '7' :
                $buy_type = 4;//递出名片对应的Buyservice配置参数
                break;
            case '8' :
                $buy_type = 4;//交换名片对应的Buyservice配置参数 按递出名片一个处理
                break;
            case '9' :
                $buy_type = 5;//招商会报名对应的Buyservice配置参数
                break;
            case '10' :
                $buy_type = 10;//发布项目[暂无服务]
                break;
            case '11' :
                $buy_type = 11;//发布招商会[暂无服务]
                break;
            case '12' :
                $buy_type = 12;//发布软文章[暂无服务]
                break;
            case '13' :
                $buy_type = 13;//平台服务费用
                break;
            default:
                return $result;
                break;
        }
        $account = common::getAccount();
/***********************以下为公用部分**************************/
        //----------------start----------------
            if ($accountstatus==0){//检测不通过或则通过后直接扣服务 ，此处不存在直接扣钱$accountstatus==1时才走扣钱接口
                /*
                 * 三 检测是否购买过名片放大镜包次服务
                 */
                //$source = $buyService->where('user_id', '=', $user_id)->where('buy_type', '=', $buy_type)->find();
                //三.1未购买过名片放大镜服务【@钟涛修改 去除名片服务肯定是没购买过了】
                if (true){//$source->user_id==NULL
                    if ($this->checkAccountNumber($user_id,$account[$type]['price'])==FALSE){
                        $result_arr = array('is_forbid'=>false,'status'=>FALSE,'message'=>'对不起，您的账户余额不足，前往账户中心充值以享受该服务？','type'=>2);
                        return $result_arr;
                    }
                    //$result = array('status'=>FALSE,'message'=>'<span style="font-size:18px;color:#333;">您可以去购买名片服务，享受优惠</span><a href="'.URL::site('company/member/account/cardservice').'" style="background:#cb230c;display:inline-block;height:28px;line-height:28px;color:#fff;font-size:13px;padding:0 8px;text-decoration:none;*margin-bottom:-5px;">去购买名片服务?</a> ','type'=>'-1');
                    $result = array('is_forbid'=>false,'status'=>FALSE,'message'=>'本操作将会从您的账户中心扣除<b style="color:red;font-size: 17px;">'.$account[$type]['price'].'</b>元，是否确认操作？','type'=>'-1');
                    return $result;
                }
                /*
                 * 以下为购买过服务的 1.先检查是否全部过期 2.如果有未过期的，检查是否次数使用完了（未用完的直接使用，用完的返回提示）
                 */
                //三.2查找最后一条未过期
                $lastService = ORM::factory('Buyservice')->where('user_id', '=', $user_id)->where('buy_type', '=', $buy_type)->where('buy_usable_time','>',time())->order_by('buy_time','DESC')->find();
                if ($lastService->user_id==NULL){
                    //最后一条已经过期，说明其他的都已经过期
                    $lastService = ORM::factory('Buyservice')->where('user_id', '=', $user_id)->where('buy_type', '=', $buy_type)->where('buy_usable_time','<',time())->order_by('buy_time','DESC')->find();
                    if ($lastService->buy_timeout==1){//最后一条已经过期 不是第一次提醒
                        if ($this->checkAccountNumber($user_id,$account[$type]['price'])==FALSE){
                            $result_arr = array('is_forbid'=>false,'status'=>FALSE,'message'=>'对不起，您的账户余额不足，前往账户中心充值以享受该服务？','type'=>2);
                            return $result_arr;
                        }
                        $result = array('is_forbid'=>false,'status'=>FALSE,'message'=>'<span style="font-size:18px;color:#333;">您可以去购买名片服务，享受优惠</span><a href="'.URL::site('company/member/account/cardservice').'" style="background:#cb230c;display:inline-block;height:28px;line-height:28px;color:#fff;font-size:13px;padding:0 8px;text-decoration:none;*margin-bottom:-5px;">去购买名片服务?</a>','type'=>'-1');
                    }else{
                        $service = ORM::factory('Buyservice',$lastService->buy_id);
                        $service->buy_timeout = 1;
                        $service->update();
                        if ($this->checkAccountNumber($user_id,$account[$type]['price'])==FALSE){
                            $result_arr = array('is_forbid'=>false,'status'=>FALSE,'message'=>'对不起，您的账户余额不足，前往账户中心充值以享受该服务？','type'=>2);
                            return $result_arr;
                        }
                        $result = array('is_forbid'=>false,'status'=>FALSE,'message'=>'<span style="font-size:18px;color:#333;">您购买的名片服务已过期</span><a href="'.URL::site('company/member/account/cardservice').'" style="background:#cb230c;display:inline-block;height:28px;line-height:28px;color:#fff;font-size:13px;padding:0 8px;text-decoration:none;*margin-bottom:-5px;">去购买名片服务?</a>','type'=>'-1');
                    }
                    return $result;
                }
                /*
                 * 最后一条未过期的 检查最后一条使用次数是否都已经用完
                 */
                else{//start
                    //最后一条未过期的 检查使用次数是否都已经用完
                    $lastNumService = ORM::factory('Buyservice')->where('user_id', '=', $user_id)->where('buy_type', '=', $buy_type)->where('buy_usable_time','>',time())->where('buy_confine_number','>',0)->order_by('buy_time','DESC')->find();
                    if ($lastNumService->buy_id==NULL){
                        //最后一条的次数已经使用完
                        $lastNumService = ORM::factory('Buyservice')->where('user_id', '=', $user_id)->where('buy_type', '=', $buy_type)->where('buy_usable_time','>',time())->where('buy_confine_number','=',0)->order_by('buy_time','DESC')->find();
                        if ($lastNumService->buy_timeout==1){//最后一条已经使用完 不是第一次提醒
                            $result = array('is_forbid'=>false,'status'=>FALSE,'message'=>'<span style="font-size:18px;color:#333;">您可以去购买名片服务，享受优惠</span><a href="'.URL::site('company/member/account/cardservice').'" style="background:#cb230c;display:inline-block;height:28px;line-height:28px;color:#fff;font-size:13px;padding:0 8px;text-decoration:none;*margin-bottom:-5px;">去购买名片服务?</a>','type'=>'-1');
                            if ($this->checkAccountNumber($user_id,$account[$type]['price'])==FALSE){
                                $result_arr = array('is_forbid'=>false,'status'=>FALSE,'message'=>'对不起，您的账户余额不足，前往账户中心充值以享受该服务？','type'=>2);
                                return $result_arr;
                            }
                        }
                        else{
                            $service = ORM::factory('Buyservice',$lastNumService->buy_id);
                            $service->buy_timeout = 1;
                            $service->update();
                            if ($this->checkAccountNumber($user_id,$account[$type]['price'])==FALSE){
                                $result_arr = array('is_forbid'=>false,'status'=>FALSE,'message'=>'对不起，您的账户余额不足，前往账户中心充值以享受该服务？','type'=>2);
                                return $result_arr;
                            }
                            $result = array('is_forbid'=>false,'status'=>FALSE,'message'=>'<span style="font-size:18px;color:#333;">您购买的名片服务已经用完</span><a href="'.URL::site('company/member/account/cardservice').'" style="background:#cb230c;display:inline-block;height:28px;line-height:28px;color:#fff;font-size:13px;padding:0 8px;text-decoration:none;*margin-bottom:-5px;">去购买名片服务?</a>','type'=>'-1');
                        }
                        return $result;
                    }
                    /*
                     * 存在可用的服务且未超时未用完的
                     * 直接选择操作最前面能用的服务 从服务中扣除
                     * 并且加入log
                     */
                     else{
                        //找出最前面一条未过期的 使用次数未用完的
                        $firstNumService = ORM::factory('Buyservice')->where('user_id', '=', $user_id)->where('buy_type', '=', $buy_type)->where('buy_usable_time','>',time())->where('buy_confine_number','>',0)->order_by('buy_time','ASC')->find();
                        /*
                         * 使用包次服务写入czzs_buy_service_log表
                         * 更新czzs_buy_service表对应的次数
                         */
                         $datatime = $this->writeServiceLog($firstNumService->buy_id,$firstNumService->buy_type,$firstNumService->buy_type_config,$note);
                         if($datatime['status']==TRUE){
                            $service = ORM::factory('Buyservice',$firstNumService->buy_id);
                            $service->buy_confine_number = $firstNumService->buy_confine_number-1;
                            $service->buy_use_number = $firstNumService->buy_use_number+1;
                            $service->update();
                            $result = array('status'=>TRUE,'message'=>'使用名片放大镜服务成功','type'=>$datatime['type']);
                            return $result;
                         }
                         else{
                             if ($this->checkAccountNumber($user_id,$account[$type]['price'])==FALSE){
                                 $result_arr = array('is_forbid'=>false,'status'=>FALSE,'message'=>'对不起，您的账户余额不足，前往账户中心充值以享受该服务？','type'=>2);
                                 return $result_arr;
                             }
                            $result = array('is_forbid'=>false,'status'=>FALSE,'message'=>$datatime['message'],'type'=>$datatime['type']);
                            return $result;
                         }
                     }
                }//end
            }
            elseif ($accountstatus==1){//直接走账户扣款接口
                $account = common::getAccount();
                if ($this->checkAccountNumber($user_id,$account[$type]['price'])==FALSE){
                    $result_arr = array('is_forbid'=>false,'status'=>FALSE,'message'=>'对不起，您的账户余额不足，前往账户中心充值以享受该服务？','type'=>2);
                    return $result_arr;
                }
                return $this->buyUseAccount($user_id,$type,$account[$type]['price'],2,$note);
            }
        //----------------end----------------------

    }

    /**
     * 直接通过金额购买来实现功能 1.先检查用户是否禁用，2金额是否足够，3.如果资金够，先加入czzs_account_order表->资金记录表->更新账户表
     * @author 周进
     * @param int $user_id 用户ID
     * @param int $account_type 操作类型1为线上充值2为线下汇款3为购买服务4为现金充值5银行卡6查看名片7递出名片8交换名片9报名招商会
     * @param decimal $account 金额
     * @param account_class 资金影响方式：0为不变1为增加2为减少
     * @param $note 使用备注说明 来源记录
     * @return array
     * @date 2013/5/10
     */
    public function buyUseAccount($user_id=0,$account_type=1,$account=0,$account_class=0,$note=''){
        //获取客服电话号码
        $arrCustomerPhone = common::getCustomerPhone();
        $user_id=intval($user_id);
        $result = array('status'=>FALSE,'message'=>'您的操作失败，请联系客服：'.$arrCustomerPhone['1'].'！','type'=>'0');
        //检查用户是否禁用
        if($this->checkAccountUser(intval($user_id))!=TRUE){
            $result_arr = array('status'=>FALSE,'message'=>'您的账户被禁用，请联系客服：'.$arrCustomerPhone['1'].'！','type'=>-1);
            return $result_arr;
        }
        //检测账户金额是否足够
        if ($this->checkAccountNumber($user_id,$account)==FALSE){
            $result_arr = array('status'=>FALSE,'message'=>'对不起，您的账户余额不足，前往账户中心充值以享受该服务？','type'=>2);
            return $result_arr;
        }
        /*
        //查看名片的当天做20次的次数限制
        */
        //开始事务
        $db = Database::instance();
        $db->begin();

        $note = $note!=""?$note:common::getAccountType($account_type);
        //加入czzs_account_order订单表并返回订单ID
        $orderData = array('order_type'=>$account_type,
                'order_account'=>$account,
                'order_real_account'=>$account,
                'order_remarks'=>$note,
                'order_status'=>'2',
        );
        $order_id = $this->editOnLineRecharge($user_id,$orderData);
        //加入资金日志操作记录表
        $data = array(
                'account_change_amount'=>$account,
                'account_class'=>$account_class,
                'account_note'=>$note,
                'account_comments'=>$note,
                'account_comments_type'=>$account_type //@modified by 赵路生

        );
        $source = $this->addAccountLog($user_id,$data,$account_class,$order_id);
        if ($source!=TRUE){
            $db->rollback();
            return $result_arr;
        }
        else{//订单表加入成功后更新资金操作记录表成功后执行事务
            //更新用户金额表
            $useraccount = $this->getAccount($user_id);
            $this->checkAccountUser($user_id,$account,2);
            $db->commit();
            $result_arr = array('status'=>TRUE,'message'=>'操作成功','type'=>1);
            return $result_arr;
        }
        return $result_arr;
    }

    /**
     * 获取用户使用服务操作历史记录
     * @author周进
     * @return array
     */
    public function getServiceAccountLog($buy_id=0,$user_id=0){
        $user_id=intval($user_id);
        $return_arr=array();
        $model = ORM::factory('Buyservicelog');
        $model->where('buy_id','=',$buy_id);
        $page = Pagination::factory(array(
                'total_items'    => $model->reset(false)->count_all(),
                'items_per_page' => 5,
        ));
        //查询支出收入的总额
        $getBuyType = common::getBuyType();
        $list = $model->select("*")->limit($page->items_per_page)->offset($page->offset)->order_by('log_time', 'DESC')->find_all( );
        foreach ($list as $k=>$v){
            $result[$k]['log_time'] = date('Y.m.d H:i',$v->log_time);
            $result[$k]['log_action_id'] = $v->log_action_id;
            $str = explode("_", $v->log_buy_type);
            $result[$k]['log_nums'] = $getBuyType[$str[0]][$str[1]]['num']-$v->log_nums;
        }
        return $result;
    }

    /**
     * 用户删除 服务显示状态
     */
    public function updateBuyServiceShowstatus($buy_id=0,$user_id=0,$status=0){
        $service = ORM::factory('Buyservice',$buy_id);
        $service->buy_show_status = $status;
        $service->update();
    }

    /**
     * 冻结账户资金接口
     * @author 周进
     * @param int $user_id
     * @param decimal $account 冻结金额
     * @param int $type 冻结类型 0为退款1为安全保证服务
     * @param string $note 操作说明
     * @return array('status'=>bool,'message'=>string)
     */
    public function blockedAccount($user_id=0,$account=0,$type=0,$note='冻结资金'){
        $result = array('status'=>FALSE,'message'=>'冻结失败！');
        $flag = 0;
        if($this->checkAccountStatus($user_id)==FALSE){
            $result = array('status'=>FALSE,'message'=>'您的账户被禁用！');
            return $result;
        }
        if ($this->checkAccountNumber($user_id,$account)==FALSE){
            $result = array('status'=>FALSE,'message'=>'您的余额不足！');
            return $result;
        }
        $num = ORM::factory('Accountblocked')->where('blocked_type', '=', $type)->and_where('account_user_id','=',$user_id)->find();
        //开始事务
        $db = Database::instance();
        $db->begin();
        /***********start 操作冻结表****************/
        if ($num->blocked_id>0&&$type==1){//安全保证类型的有的更新，退款的还是继续新增
            $accountblocked = ORM::factory('Accountblocked',$num->blocked_id);
            $accountblocked->account_user_id = $user_id;
            $accountblocked->blocked_type = intval($type);
            $accountblocked->blocked_account = $account;
            $accountblocked->account_stastus = 1;
            $accountblocked->blocked_time = time();
            try {
                $accountblocked->update();
                $result = array('status'=>TRUE,'message'=>'冻结成功！');
                $flag = 1;
            } catch (Kohana_Exception $e) {
                $flag = 0;
            }
        }
        else{//有的新增
            $accountblocked = ORM::factory('Accountblocked');
            $accountblocked->account_user_id = $user_id;
            $accountblocked->blocked_type = intval($type);
            $accountblocked->blocked_account = $account;
            $accountblocked->account_stastus = 1;
            $accountblocked->blocked_time = time();
            try {
                $accountblocked->create();
                $result = array('status'=>TRUE,'message'=>'冻结成功！');
                $flag = 1;
            } catch (Kohana_Exception $e) {
                $flag = 0;
            }
        }
        /***********end 操作冻结表****************/
        /***********start 加入资金日志操作记录表 更新用户资金表****************/
        $data = array(
                'account_change_amount'=>$account,
                'account_class'=>2,//扣钱
                'account_note'=>$note,
                'account_comments'=>$note,
                'account_comments_type'=>0//@modified by 赵路生
        );
        $source = $this->addAccountLog($user_id,$data,6);//6为冻结账户
        if ($source!=TRUE){
            $db->rollback();
            $result = array('status'=>FALSE,'message'=>'冻结失败！');
            return $result;
        }
        else{
            //start加入成功后更新资金操作记录表成功后执行事务
            $accountresult = $this->getAccount($user_id);
            $model = ORM::factory('Account',$accountresult['account_id']);
            $model->account_time = time();
                if ($account!=0){
                    $account_change_amount = $account!=""?$account:'0.00';
                    $model->account = common::getMark($accountresult["account"],2,$account_change_amount);
                    $model->blocked_account = common::getMark($accountresult["blocked_account"],1,$account_change_amount);
                    try {
                        $model->update();
                        $db->commit();
                        return $result;
                    }
                    catch (Kohana_Exception $e)
                    {
                        $db->rollback();
                        $result = array('status'=>FALSE,'message'=>'冻结失败！');
                        return $result;
                    }
                }
                else{
                    $db->rollback();
                    $result = array('status'=>FALSE,'message'=>'冻结失败！');
                    return $result;
                }

            //end加入成功后更新资金操作记录表成功后执行事务
            $db->rollback();
            return $result;
        }
        /***********end  加入资金日志操作记录表 更新用户资金表****************/
        $db->rollback();
        return $result;
    }
}