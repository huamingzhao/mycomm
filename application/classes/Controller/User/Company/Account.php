<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业用户资金信息
 * @author 周进
 */
class Controller_User_Company_Account extends Controller_User_Company_Template{

    private  $service = "";

    public function before(){
        parent::before();
        //设置默认值,该service内公共调用
        $this->service = Service::factory("Account");
        if($this->service->checkAccountUser($this->userId())!=TRUE){
            self::redirect("/company/member/basic/accountforbidden");
        }
    }


    public function after(){
        parent::after();
    }

    /**
    * 账户充值默认页面
    * @author 周进
    */
    public function action_accountindex(){
        $companyresult = ORM::factory('Companyinfo')->where('com_user_id', '=', $this->userId())->find()->as_array();
        if( $this->is_complete_basic( $this->userId() )===false ){
            $content = View::factory("user/company/company_tel");
            $this->content->rightcontent = $content;
            $content->title = '我的名片';
            $content->errMsg = '请先填写企业基本信息，这样您可以使用账户中心功能！';
            $content->hrefUrl = "/company/member/basic/editcompany?type=1";
        }
        else{
            $content = View::factory("user/company/accountindex");
            $account = $this->service->getAccount($this->userId());
            $content->account = $account['account'];
            $result = $this->service->getAccountLog($this->userId());
            $content->list = $result['list'];
            $content->page = $result['page'];
            $content->isplatformfree =$companyresult['platform_service_fee_status'];
            $content->point = Service::factory('User_Company_Points')->getUsablePointsByTime($this->userId());
            $this->content->rightcontent = $content;
        }
    }
    /**
     * 资金操作列表
     * @author 周进
     * @modifi by 赵路生 2013-8-22
     */
    public function action_accountlist(){
        $companyresult = ORM::factory('Companyinfo')->where('com_user_id', '=', $this->userId())->find()->as_array();
        if( $this->is_complete_basic( $this->userId() )===false ){//判断是否已经完善企业信息
            $content = View::factory("user/company/company_tel");
            $this->content->rightcontent = $content;
            $content->title = '我的名片';
            $content->errMsg = '请先填写企业基本信息，这样您可以使用账户中心功能！';
        }
        else{
            $post = Arr::map("HTML::chars", $this->request->query());
            //输入的数据进行处理
            $start = isset($post['start'])?$post['start']:'';
            $end = isset($post['end'])?$post['end']:'';
            $model2 = ORM::factory('Accountlog');
            $model_amount= $model2->where('account_user_id', '=', intval($this->userId()))->order_by('account_log_time','ASC')->find();
            $start_earliest_time  =$model_amount-> account_log_time;

            if(preg_match ("/(\d{4})-(\d{2})-(\d{2})/",$start, $m)) {
                $start_time = mktime(0,0,0,$m[2],$m[3],$m[1]);
            }else{
                //$start_time = time()-30*24*60*60;//如果没有给起始时间，默认起始时间是30天前
                //$start_time = $start_earliest_time;
                $start_time = 0;
            }
            if(preg_match ("/(\d{4})-(\d{2})-(\d{2})/",$end, $m)) {
                $end_time = mktime(23,59,59,$m[2],$m[3],$m[1]);
            }else{
                $end_time = time();//如果没有给结束时间,当前时间为结束时间
            }

            $account_comments_type = isset($post['account_comments_type'])?intval($post['account_comments_type']):0;
            $account_comments_type_arr = common::getCountDetailClass();
            if(!array_key_exists($account_comments_type,$account_comments_type_arr)){
                $account_comments_type = 0;
            }
            $time = isset($post['time'])?$post['time']:'30';

            //$result = $this->service->getAccountLog($this->userId(),$time);
            $result = $this->service->getAccountLogModify($this->userId(),$start_time,$end_time,$account_comments_type);
            $content = View::factory("user/company/accountlist");

            $content->time = $time;

            $content->start = isset($post['start'])&&$post['start']?date('Y-m-d',$start_time):'';
            $content->end = isset($post['end'])&&$post['end']?date('Y-m-d',$end_time):'';

            $content->start_date =date('Y-m-d',$start_time);
            $content->end_date = date('Y-m-d',$end_time);
            $content->account_comments_type = $account_comments_type;
            $content->account_comments_type_arr = $account_comments_type_arr;

            $content->list = $result['list'];
            $content->page = $result['page'];
            $content->addaccount = $result['addaccount']?$result['addaccount']:0;
            $content->reduceaccount = $result['reduceaccount']?$result['reduceaccount']:0;
            $content->account_amount = $result['account_amount']?$result['account_amount']:0;
            $content->sub_reduceaccount = $result['sub_reduceaccount']?$result['sub_reduceaccount']:0;

            $this->content->rightcontent = $content;
        }
    }

    /**
    * 服务操作列表
    * @author 周进
    */
    public function action_servicelist(){
        $companyresult = ORM::factory('Companyinfo')->where('com_user_id', '=', $this->userId())->find()->as_array();
        if( $this->is_complete_basic( $this->userId() )===false ){//判断是否已经完善企业信息
            $content = View::factory("user/company/company_tel");
            $this->content->rightcontent = $content;
            $content->title = '我的名片';
            $content->errMsg = '请先填写企业基本信息，这样您可以使用账户中心功能！';
        }
        else{
            $post = Arr::map("HTML::chars", $this->request->query());
            if (arr::get($post, 'buy_id')!=""&&arr::get($post, 'status')!="")
                $this->service->updateBuyServiceShowstatus(arr::get($post, 'buy_id'),$this->userId(),arr::get($post, 'status'));
            $result = $this->service->getBuyService($this->userId());
            $Service_Company=new Service_User_Company_User();
            $applyinfo = $Service_Company->getApplyBusiness($this->userId());
            $content = View::factory("user/company/servicelist");
            $content->applyinfo = $applyinfo;
            $content->list = $result['list'];
            $content->page = $result['page'];
            $this->content->rightcontent = $content;
        }
    }
    /**
     * 线下充值银行汇款页面
     * @author 周进
     */
    public function action_outLine(){
        $content = View::factory("user/company/outlinerecharge");
        $this->content->rightcontent = $content;
    }
    /**
     * 线下充值银行汇款处理
     * @author 周进
     */
    public function action_outLineRecharge(){
        $content = View::factory("user/company/outlinerecharge");
        $this->content->rightcontent = $content;
    }
    /**
     * 购买名片服务
     * @author 周进
     * @date 2013/01/21
     */
    public function action_cardservice(){
        $companyresult = ORM::factory('Companyinfo')->where('com_user_id', '=', $this->userId())->find()->as_array();
        if( $this->is_complete_basic( $this->userId() )===false ){//判断是否已经完善企业信息
            $content = View::factory("user/company/company_tel");
            $this->content->rightcontent = $content;
            $content->title = '我的名片';
            $content->errMsg = '请先填写企业基本信息，这样您可以使用账户中心功能！';
        }
        else{
            $post = Arr::map("HTML::chars", $this->request->post());
            $content = View::factory("user/company/cardservice");
            if(Arr::get($post,'type_number')!=""){//购买处理,已提出至AXAJ
                if (!isset($post['buy_type_'.$post['type_number']]))
                    echo '<script>alert("选择无效！");</script>';
                else{
                    $data['service_id'] = $post['buy_type_'.$post['type_number']];
                    $result = $this->service->buyService($data,$this->userId());
                    if ($result['status']==FALSE)
                        echo '<script>alert("'.$result['message'].'");</script>';
                    else{
                        echo '<script>alert("'.$result['message'].'");</script>';
                        self::redirect("/company/member/account/accountindex");
                    }
                }
            }
            $this->content->rightcontent = $content;
        }
    }

    /**
     * 导出excel账户操作明细
     * @author 周进
     * @modified by 赵路生 2013-8-23
     */
    public function action_getAccountExcel(){
        $post = Arr::map("HTML::chars", $this->request->query());
        $start = isset($post['start'])?$post['start']:'';
        $end = isset($post['end'])?$post['end']:'';
        $account_comments_type = isset($post['account_comments_type'])?intval($post['account_comments_type']):0;
        if(preg_match ("/(\d{4})-(\d{2})-(\d{2})/",$start, $m)) {
            $start_time = mktime(0,0,0,$m[2],$m[3],$m[1]);
        }else{
            $start_time = 0;
        }
        if(preg_match ("/(\d{4})-(\d{2})-(\d{2})/",$end, $m)) {
            $end_time = mktime(23,59,59,$m[2],$m[3],$m[1]);
        }else{
            $end_time = time();//如果没有给结束时间,当前时间为结束时间
        }
        $result = $this->service->getAccountExcel($this->userId(),$start_time,$end_time,$account_comments_type);
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: attachment; filename=".date('Y_m_d')."_order.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        /*first line*/ //时间，内容明细，收入，支出，金额，支付方式，支付账户
        echo mb_convert_encoding("操作类型","gb2312")."\t";
        echo mb_convert_encoding("操作说明","gb2312")."\t";
        echo mb_convert_encoding("资金变动数额","gb2312")."\t";
        echo mb_convert_encoding("余额","gb2312")."\t";
        echo mb_convert_encoding("赠送金额","gb2312")."\t";
        echo mb_convert_encoding("操作时间","gb2312")."\t";
//         echo mb_convert_encoding("支付方式","gb2312")."\t";
//         echo mb_convert_encoding("支付账户","gb2312")."\t";
        echo "\t\n";
        /*start of second line*/
        foreach ($result as $v){
            echo mb_convert_encoding(common::getAccountType($v->account_comments_type),"gb2312")."\t";
            echo mb_convert_encoding($v->account_note,"gb2312")."\t";
            if($v->account_class ==2){
                echo "-";
            }
            if($v->account_class == 1){
                echo "+";
            }
            echo $v->account_change_amount;
            echo "\t";
            echo $v->account_amount."\t";
            if(mb_substr(mb_convert_encoding($v->account_comments,"utf8"),0,9,'UTF-8') == '线下汇款赠送金额：'){
                echo mb_convert_encoding(mb_substr(mb_convert_encoding($v->account_comments,"utf8"),9,40,'UTF-8'),"gb2312")."\t";
            }else{
                echo "--"."\t";
            }
            //echo mb_convert_encoding($v->account_comments,"gb2312")."\t";
            echo date('Y-m-d H:i:s',$v->account_log_time)."\t";
//             if($v->account_type==1){//如果为充值 则从充值表里面取得数据
//                 $account_recharge = $this->service->getAccountorder($v->account_type_id);
//                 echo mb_convert_encoding($account_recharge['order_bank_name'],"gb2312")."\t";
//                 echo mb_convert_encoding($account_recharge['order_code'],"gb2312")."\t";
//             }
//             else
//                 echo " \t \t";
            echo "\t\n";
        }
        exit;
    }

    /**
     * 线上充值处理/ 先将数据加入订单表然后再去付款，返回的时候在跟心订单表状态
     * @author 周进
     */
    public function action_recharge(){
        $query = Arr::map("HTML::chars", $this->request->query());
        if (strrpos(Arr::get($query, 'total_fee'), "."))
            $total_fee = Arr::get($query, 'total_fee')!=""?Arr::get($query, 'total_fee'):'0.00';
        else
            $total_fee = Arr::get($query, 'total_fee')!=""?Arr::get($query, 'total_fee').".00":'0.00';
        if ($total_fee>0){
            $order_no = time();
            //订单表
            $orderData = array(
                    'order_no'=>$order_no,
                    'order_type'=>1,
                    'order_account'=>$total_fee,
                    'order_remarks'=>'账户线上充值',
                    'order_status'=>'0',
                    'order_type_id'=>'0',
                    );
            $service = Service::factory('Account');
            $order_id = $service->editOnLineRecharge($this->userId(),$orderData);
            //支付接口
            $payment = new Service_Payment('alipay');
            $data = array('method'=>'post',
                    'out_trade_no'=>$order_no,
                    'total_fee'=>$total_fee,
                    'subject'=>"一句话招商平台用户充值",
                    'body'=>'一句话招商平台用户充值');
            echo $payment->toSubmit($data);
        }
        exit;
    }

    /**
     * 线上返回展示页面仅作展示
     * @author 周进
     */
    public function action_showRecharge(){
        $query = Arr::map("HTML::chars", $this->request->query());
        if (strrpos(Arr::get($query, 'total_fee'), "."))
            $total_fee = Arr::get($query, 'total_fee')!=""?Arr::get($query, 'total_fee'):'0.00';
        else
            $total_fee = Arr::get($query, 'total_fee')!=""?Arr::get($query, 'total_fee').".00":'0.00';
        $order_out_no = Arr::get($query, 'order_out_no')!=""?Arr::get($query, 'order_out_no'):'';
        if ($order_out_no=="")
                $content = View::factory("user/company/accountfail");
        else{
            $result = ORM::factory('Accountorder')->where('order_out_no', '=', $order_out_no)->find();
            if ($result->order_no==""){
                $content = View::factory("user/company/accountfail");
            }
            else{
                if (Arr::get($query, 'status')=="success"){
                    $content = View::factory("user/company/accountsuccess");
                }
                else{
                    $content = View::factory("user/company/accountfail");
                }
            }
        }
        $content->total_fee = $total_fee;
        $content->order_no = $result->order_no;
        $this->content->rightcontent = $content;
    }

    /**
     * @sso
     * 申请招商通会员[平台服务费1500]
     * @author 钟涛
     */
    public function action_applyPlatformServiceFee(){
        $content = View::factory("user/company/platformservicefee");
        $serviceUser = new Service_User_Company_User();
        $comUser = $serviceUser->getCompanyInfo($this->userInfo()->user_id);
        $content->platform_service_fee_status = $comUser->platform_service_fee_status;
        $this->content->rightcontent = $content;
    }

    /**
     * 一句话平台协议
     * @author 钟涛
     */
    public function action_platformAgreement(){
        $content = View::factory("user/company/platformagreement");
        $this->content->rightcontent = $content;
    }

    /**
     * 充值及服务说明
     * @author 钟涛
     */
    public function action_platformAccountAbout(){
        $content = View::factory("user/company/platformaccountabout");
        $this->content->rightcontent = $content;
    }
}
