<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业信息认证
 * @author 龚湧
 */
class Controller_User_Company_Valid extends Controller_User_Company_Template{
    /**
    * 手机验证 第一步
    * @author 龚湧
    */
    public function action_mobile(){
        $service_user = Service::factory("User");
        $user = $this->userInfo();

        //默认第一步
        $content = View::factory("user/company/valid_mobile_one");

        $content->mobile = $user->mobile;
        //通过验证的显示模板
        if($user->valid_mobile and $this->request->query("to")!="change"){
            $content = View::factory("user/company/valid_mobile_success");
            $content->mobile = $user->mobile;
        }
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
                    //手机绑定通过，增加积分
                    $points = Service::factory("User_Company_Points");
                    $points->getPointsTimes($user->user_id,"valid_mobile");
                    //手机绑定成功，增加诚信点
                    $integrity = Service::factory("User_Company_Integrity");
                    if($integrity->getIntegrityOnce($user->user_id,"valid_mobile")){
                        //手机号码通过验证，发送消息提醒
                        //$msg_service = new Service_User_Ucmsg();
                        //$msg_service->pushMsg($user->user_id, "company_integrity", "您已经验证手机号码，增加60点诚信指数。",URL::website("company/member/basic/integrity"));

                        $smsg = Smsg::instance();
                        //内部消息发送
                        $smsg->register(
                                "tip_company_integrity",//我的诚信
                                Smsg::TIP,//类型
                                array(
                                        "to_user_id"=>$user->user_id,
                                        "msg_type_name"=>"company_integrity",
                                        "to_url"=>URL::website("company/member/basic/integrity")
                                ),
                                array(
                                        "code"=>"60",
                                        "type"=>"tel"

                                )

                        );

                    }


                    self::redirect("company/member/valid/mobile");
                }
                else{
                    $content->mobile = Arr::get($this->request->post(),"receiver");
                    $error = "验证码错误";
                }
            }
            $content->error = $error;
        }
        $this->content->rightcontent = $content;
    }

}