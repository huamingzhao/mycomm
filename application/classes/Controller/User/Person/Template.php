<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户中心模板
 * @author 周进
 */
class Controller_User_Person_Template extends Controller_Template{
    /**
    * @var view
    */
    public $content;
    public $title;
    public $description;
    public $keywords;
    //定义一个值 现在最多多少条地域
    private $limitNum = 5;
    /**
     * 个人用户中心
     * @var 二级模板,指定即可
     */
    private  $content_template = "user/person/template";

    public function before(){
        parent::before();
        $this->isLogin();
        $user = $this->userInfo();
        $this->template->content = $this->content = View::factory($this->content_template);
        $this->content->user_name = empty($user->user_person->per_realname)?$user->email:$user->user_person->per_realname;
        //记录contrller_方法名
        $con_method= $this->request->controller().'_'.$this->request->action();
        //设置默认值
        $seo_title=common::getPersonSEOTitle($con_method);
        if($seo_title==''){
            $this->template->title = '个人中心－一句话投资招商平台|投资赚钱好项目，一句话的事。';
        }else{
            $this->template->title = $seo_title.'－个人中心－一句话投资招商平台|投资赚钱好项目，一句话的事。';
        }
        $this->template->description = '';
        $this->template->keywords = '';
        $this->content->rightcontent = '';
        $service= new Service_User_Person_User();
        $card_service=new Service_User_Company_Card();
        $per_phone=$service->getPerson($this->userId())->per_photo;
        if($per_phone){
            $this->content->logo = URL::imgurl($per_phone);
        }else{
            $this->content->logo ='';
        }
        //判断per_user_id是否存在
        if($service->getPerson($this->userId())->per_user_id !=""){
            $this->content->per_user_id = "ok";
        }else{
            $this->content->per_user_id = "no";
        }
        //避免非法链接直接进入
        if ($this->userInfo()->user_type!='2')
            $this->userType($this->userInfo()->user_type);
        //邮件未验证的相关操作
        $url = parse_url($_SERVER['REQUEST_URI']) ;
        //登录状态为第三方登录的人，不需要验证这个

        if( $service->getEmailValidCount($this->userId())!=1&&($url["path"]!="/person/member/basic/vemail")&&($url["path"]!="/person/member/basic/vemail/") && $this->userInfo()->valid_mobile!='1' && $this->userInfo()->from_type!=2 ){
            self::redirect("/person/member/basic/vemail");
        }


        $result = array();
        $ucmsg = Service::factory("User_Ucmsg");
        //生成新消息
        //$ucmsg->generateMsg($this->loginUserId(),$this->userInfo()->last_logintime,$this->userInfo()->user_type);//更新和创建消息
        //记录contrller_方法名
        $this->content->actionmethod =$con_method;
        //新收到的名片数量
        $this->content->receivecard_count=$card_service->getReceiveCardNewCount($this->userInfo()->user_id);
        //我递出的名片中新交换名片数量
        $this->content->exchangecard_count=$card_service->getExchangeCardNewCount($this->userInfo()->user_id);
        //判断是否晚上
        $this->content->is_complete_basic = $this->is_complete_basic($this->userId());
        //用户是否未第三方的帐号，实际上你丫只要看用户设置了密码没有
        $pefo= Service_Sso_Client::instance()->getUserPasswordEof( $this->userId() );
        $this->content->pefo= $pefo;
        //判断会员是否有绑定的第三方帐号
        $oauth_log= new Service_Oauth_Log();
        $oauth_eof= $oauth_log->getOauthEofByUid( $this->userId() );
        $this->content->oauth_eof= $oauth_eof;

    }

    public function after(){
        parent::after();
    }

    /**
     *个人基本信息是否完善 ，即判断是否有个人信息
     * @author 潘宗磊
     * @param int $user_id
     * @return bool
     */
    public function is_complete_basic($user_id){
        $person = ORM::factory('Personinfo')->where('per_user_id','=',$user_id)->find();
        //echo $person->per_investment_style;exit;
        if( $person->per_realname!=''  ){
            return true;
        }
        return false;
    }

    /**
     *判断是否完善意向投资信息
     * @author 潘宗磊
     * @param int $user_id
     * @return bool
     */
    public function is_complete_basic2($user_id){
        $person = ORM::factory('Personinfo')->where('per_user_id','=',$user_id)->find();
        if( $person->per_amount ){
            return true;
        }
        return false;
    }

    /**
     * 判断是否完善基本信息【不包括意向投资信息】
     * @author 潘宗磊
     * @param int $user_id
     * @return bool
     */
    public function is_complete_basic3($user_id){
        $person = ORM::factory('Personinfo')->where('per_user_id','=',$user_id)->find();
        if( $person->per_realname!=''){
            return true;
        }
        return false;
    }

    /**
     * 获得个人地域信息
     * @author 施磊
     * @param int $user_id
     */
    public function getPersonalArea($user_id) {
        $user_id = intval($user_id);
        if(!$user_id) return array();
        $service = new Service_User_Person_User();
        $personalArea = $service->selectPersonalAreaByUserId($user_id);
        $allTempArea = array();
        if($personalArea) {
            foreach($personalArea as $value) {
                if($value['pro_id'] == $value['area_id']) {
                    $allTempArea[$value['pro_id']]['name'] =  $value['cit_name'];
                }else{
                    $allTempArea[$value['pro_id']]['data'][$value['area_id']] = $value['cit_name'];
                }
             }
        }
        $return  = '';
        $lastArea = array();
        if($allTempArea) {
            foreach($allTempArea as $key => $val) {
                if(!isset($val['data'])) {
                   if($this->limitNum > 0) {
                       $lastArea[$key]['name'] = $val['name'];
                       $this->limitNum--;
                   }
               }else {
                   if($this->limitNum > 0) {
                       $lastArea[$key]['name'] = $val['name'];
                       foreach($val['data'] as $keyT => $valT) {
                           if($this->limitNum > 0) {
                                $lastArea[$key]['data'][$keyT] = $valT;
                                $this->limitNum--;
                           }
                       }
                   }
               }
            }
           foreach($lastArea as $val) {
               if(!isset($val['data'])) {
                   $return[] = $val['name'];
               }else {
                   $return[] = $val['name'].' '.implode(',', $val['data']);
               }
           }
          $return = implode(',', $return);
        }
        return $return;
    }
}