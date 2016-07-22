<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户基本信息验证
 * @author 龚湧
 */
class Controller_User_Person_Valid extends Controller_User_Person_Template{
    /**
    * 手机验证 第一步
    * @author 龚湧
    */
    public function action_mobile(){
        $service_user = Service::factory("User");
        $user = $this->userInfo();
        $service = new Service_User_Person_User();
        $result=$service->getPersonInfo($this->userId());
        //$personalInfo =$result['person']->per_realname;
        $personalInfo = true;

        if(!$personalInfo) {
             $content = View::factory("user/person/personinfo_tel");
        }else{
            //默认第一步
            $content = View::factory("user/person/valid_mobile_one");
            $content->mobile = $user->mobile;
            //通过验证的显示模板
            if($user->valid_mobile and $this->request->query("to") != "change"){
                $content = View::factory("user/person/valid_mobile_success");
                $content->mobile = $user->mobile;
            }
            //判断是否设置了密码，没设置密码，设置密码去
            $eof= Service_Sso_Client::instance()->getUserPasswordEof( $this->userId() );

            if( $eof===false ){
                if( $user->valid_mobile==1 ){
                    self::redirect('/person/member/basic/setpassword?type=mobile');
                }
            }
            $content->type= Arr::get( Arr::map("HTML::chars",$this->request->query()) , 'type');
            //验证验证码
            if($this->request->method() == HTTP_Request::POST){
                //判断手机号码是否为绑定的号码
                $receiver = Arr::get($this->request->post(), "receiver");
                if($service_user->isMobileBinded($receiver)){
                    $error = "{$receiver}已经被绑定";
                }
                //验证验证码  绑定号码和更新状态
                else{
                    $check_code = Arr::get($this->request->post(),"check_code");
                    if($service_user->bindMobile($user->user_id,$receiver,$check_code)){
                        if( Arr::get($this->request->post(), 'type')=='oauth' ){
                            self::redirect("person/member/basic/setpassword");
                        }else{
                            self::redirect("person/member/valid/mobile");
                        }
                    }
                    else{
                        $content->mobile = Arr::get($this->request->post(),"receiver");
                        $error = "您输入的验证码错误，请重新输入";
                    }
                }
                $content->error = $error;
            }
        }
        $this->content->rightcontent = $content;
    }

}