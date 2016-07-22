<?php defined('SYSPATH') or die('No direct script access.');
/**
 * ajax
 * @author 施磊
 *
 */
class Controller_User_Person_Ajaxcheck extends Controller{
    /**
     * 上传个人图片
     * @author 施磊
     */
    public function action_uploadPerLogo() {
        //上传图片为空
        /*if (!$_FILES['avatar']['name']) $this->jsonEnArr('500', '上传图片为空');
            if($_FILES['avatar']['error']!="") $this->jsonEnArr('501', '图片上传错误');
            $org = getimagesize($_FILES['avatar']['tmp_name']);
            $img = common::uploadPic($_FILES,'avatar',array(array($org[0],$org[1]),array(150,120)));
            if(Arr::get($img,'error')) $this->jsonEnArr('502', $img);
            if(Arr::get($img, 'path')){
                $mod = new Service_User_Person_User();
                $param = array('per_photo' => Arr::get($img, 'path'));
                $mod->editPersonalUser($this->userId(), $param);
                $this->jsonEnArr('200', Arr::get($img, 'path'));
            }
         * */
         $post = Arr::map("HTML::chars", $this->request->post());
         if(isset($post['data'])) {
             $mod = new Service_User_Person_User();
             $param = array('per_photo' => $post['data']);
             $mod->editPersonalUser($this->userId(), $param);
             //顺便更改sso下的
             $servcie= Service_Sso_Client::instance();
             $servcie->updateBasicInfoById( $this->userId(),array('user_portrait'=>$post['data']) );
             $this->jsonEnArr('200', $post['data']);
         }else{
             $this->jsonEnArr('500', '上传图片为空');
         }
    }

    /**
     * 返回ajax状态
     * @author 施磊
     * @param int $code 状态码
     * @param string or array $msg 提示信息
     * @param int $type 0 为 直接echo 1 是return
     * @return json
     */
    private function jsonEnArr($code, $msg, $type = 0) {
        $arr = array('code' => $code, 'msg' => $msg, 'date' => time());
        $return = json_encode($arr);
        if($type) {
          return $return;
        }else{
        }
          echo $return;exit;
    }

    /**
     * 获取项目id下所有未过期的招商会
     * @author 潘宗磊
     */
    public function action_getAllInvest(){
        if($this->request->is_ajax()){
            $now = time();
            $post = Arr::map("HTML::chars", $this->request->post());
            $investment_id = $post['investment_id'];
            echo "<input type='hidden' name='invest_id' value='".$investment_id."'>";
       }
    }
    //end function

    /**
     * 传入职业类别 获取下级的职业名称
     * @author许晟玮
     */
    public function action_getPositionName(){

        $post = Arr::map("HTML::chars", $this->request->post());
        $pid= Arr::get($post, 'pid',0);
        if( $pid==0 ){
            $this->jsonEnArr('500', 'error');
        }else{
            $service_user= new Service_User();
            $result= $service_user->getPosition( $pid );
            $info[]= array();
            foreach( $result as $k=>$vs ){
                $info[$k]['name']= $vs->position_name;
                $info[$k]['id']= $vs->position_id;
            }

            $this->jsonEnArr('200', $info);
        }

    }
    //end function

    /**
     * 用户快速注册
     *
     * @author 龚湧
     */
    public function action_quickRegister(){
        if($this->request->is_ajax()){
            $post = Arr::map("HTML::chars", $this->request->post());
            $service = new Service_User_Person_User();
            //组装表单数据
            $form = array();
            $form['email'] = Arr::get($post,"email");
            $form['mobile'] = Arr::get($post,"mobile");
            $form['user_name'] = Arr::get($post,"user_name");
            $form['area_id'] = Arr::get($post,"per_area");
            $form['city_id'] = Arr::get($post,"area_id");
            $form['check_code'] = Arr::get($post,"check_code");
            //判断验证码是否正确
            $servicesend = new Service_User_MobileCodeLog();
            if($servicesend->getCodeEof($form['mobile'], $form['check_code'])){
                $client = Service_Sso_Client::instance();
                //验证码是正确的，那第一步判断手机是否已经是注册的
                $servicemobile = new Service_User ();
                if ($servicemobile->isMobileBinded ( $form['mobile'] )) {//手机号码已注册
                    //获取该用户基本信息
                    $result_user = $client->getUserInfoByMobile($form['mobile']);
                    if($result_user && $result_user->id ){
                        if($result_user->user_type==2){//个人用户
                            if(!$result_user->valid_mobile){//判断手机是否已经验证，如果没验证改为已验证
                                $client->updateMobileInfoById( $result_user->id,array( 'valid_status'=>'1','valid_time'=>time() ) );
                            }
                            if(!$result_user->email){//判断邮箱是否为空，如果为空，则填写邮箱
                                $client->updateEmailInfoById( $result_user->id,array( 'email'=>$form['email'] ) );
                            }
                            $cardser=new Service_Card();
                            $proser=new Service_Platform_Project();
                            $card_post=array();
                            $com_user_id=$proser->getUseridByProjectID(arr::get($post,'projectid',0));
                            if(!$com_user_id){
                                $com_user_id=0;
                            }
                            $card_post['to_user_id']=$com_user_id;
                            $card_post['projectid']=arr::get($post,'projectid',0);
                            $card_post['content']=arr::get($post,'leave_word','');
                            $card_post['type']=arr::get($post,'type',1);
                            $perservice2=new Service_User_Company_Card();
                            $ret=$perservice2->justIsSend( $result_user->id,$card_post);
                            if($ret===true){//已经咨询
                                $this->jsonEnArr('500', "您今天已经对该项目发送过名片，请明天再来");
                            }else{
                                if($ret>=10){//当天已经发送超过10次
                                    $this->jsonEnArr('500', "您今天已共咨询过10次，请明天再来");
                                }else{
                                    //判断用户姓名是否为空，如果为空更新姓名
                                    $updateper = ORM::factory("Personinfo")->where('per_user_id','=',$result_user->id)->find();
                                    if(!$updateper->per_realname && $form['user_name']){
                                        if($updateper->loaded()){
                                            $updateper->per_realname=$form['user_name'];
                                            $updateper->update();
                                        }else{
                                            $updateper->per_user_id=$result_user->id;
                                            $updateper->per_realname=$form['user_name'];
                                            $updateper->create();
                                        }
                                    }
                                    //开始发名片
                                    $cardser->addOutCardQuickRegister( $result_user->id,$card_post);
                                    $this->jsonEnArr('200', "注册成功");
                                }
                            }
                        }else{
                            $this->jsonEnArr('500', "您的手机已被企业用户已注册，发送名片只能个人用户哦");
                        }
                    }
                }elseif($servicemobile->forgetPasswordEmail($form['email'])===true){//邮箱已经注册
                    $result_user = $client->getUserInfoByEmail($form['email']);
                    if($result_user->user_type==2){//个人用户
                        if($result_user && $result_user->id ){
                            if($result_user->mobile){//修改手机
                                $client->updateMobileInfoById( $result_user->id,array('mobile'=>$form['mobile'],'valid_status'=>'1','valid_time'=>time()) );
                            }else{//添加手机
                                $client->setUserMobileById($result_user->id,$form['mobile'],1);
                            }
                            $cardser=new Service_Card();
                            $proser=new Service_Platform_Project();
                            $card_post=array();
                            $com_user_id=$proser->getUseridByProjectID(arr::get($post,'projectid',0));
                            if(!$com_user_id){
                                $com_user_id=0;
                            }
                            $card_post['to_user_id']=$com_user_id;
                            $card_post['projectid']=arr::get($post,'projectid',0);
                            $card_post['content']=arr::get($post,'leave_word','');
                            $card_post['type']=arr::get($post,'type',1);
                            $perservice2=new Service_User_Company_Card();
                            $ret=$perservice2->justIsSend( $result_user->id,$card_post);
                            if($ret===true){//已经咨询
                                $this->jsonEnArr('500', "您今天已经对该项目发送过名片，请明天再来");
                            }else{
                                if($ret>=10){//当天已经发送超过10次
                                    $this->jsonEnArr('500', "您今天已共咨询过10次，请明天再来");
                                }else{
                                    //判断用户姓名是否为空，如果为空更新姓名
                                    $updateper = ORM::factory("Personinfo")->where('per_user_id','=',$result_user->id)->find();
                                    if(!$updateper->per_realname && $form['user_name']){
                                        if($updateper->loaded()){
                                            $updateper->per_realname=$form['user_name'];
                                            $updateper->update();
                                        }else{
                                            $updateper->per_user_id=$result_user->id;
                                            $updateper->per_realname=$form['user_name'];
                                            $updateper->create();
                                        }
                                    }
                                    //开始发名片
                                    $cardser->addOutCardQuickRegister( $result_user->id,$card_post);
                                    $this->jsonEnArr('200', "注册成功");
                                }
                            }
                        }
                    }else{
                        $this->jsonEnArr('500', "您的邮箱已被企业用户已注册，发送名片只能个人用户哦");
                    }
                }else{
                    //生成密码,并发送到手机
                    $form['password'] = "yijuhua".mt_rand(100000, 999999);
                    $user = $service->personQuickReg($form);
                    if(is_object($user)){
                        //发送密码到手机
                        $result = common::send_message($form['mobile'], "您的登陆用户名为".$form['mobile']."，登陆密码为".$form['password'], 'online');
                        $userser=new Service_User();
                        //手机号码部分隐藏，格式如139****9476
                        if($form['mobile']){
                            $t_mobile=mb_substr($form['mobile'],0,3,'UTF-8').'****'.mb_substr($form['mobile'],7,11,'UTF-8');
                        }else{
                            $t_mobile='';
                        }
                        if($result->retCode!==0){
                            $this->jsonEnArr("300", "短信发送失败",1);
                            //回滚操作
                            $user->user_person->delete();
                            $user->delete();
                            //消息发送失败log
                            $userser->messageLog($form['mobile'],$user->user_id,5,"您的登陆用户名为".$t_mobile."，登陆密码为".$form['password'],0);
                        }else{//消息发送成功log
                            $userser->messageLog($form['mobile'],$user->user_id,5,"您的登陆用户名为".$t_mobile."，登陆密码为".$form['password'],1);
                        }
                        //$this->jsonEnArr('200', "注册成功",1);
                        //增加用户注册统计
                        $stat_service = new Service_Api_Stat();
                        $stat_service->setUserRegStat($user->user_id,$user->user_type, $user->reg_time, arr::get($_COOKIE, 'Hm_lvqtz_refer'),$user->sid );
                        //发信
                        $cardser=new Service_Card();
                        $proser=new Service_Platform_Project();
                        $card_post=array();
                        $com_user_id=$proser->getUseridByProjectID(arr::get($post,'projectid',0));
                        if(!$com_user_id){
                            $com_user_id=0;
                        }
                        $card_post['to_user_id']=$com_user_id;
                        $card_post['projectid']=arr::get($post,'projectid');
                        $card_post['content']=arr::get($post,'leave_word','');
                        $card_post['type']=arr::get($post,'type',1);

                        $cardser->addOutCardQuickRegister($user->user_id,$card_post);
                        //判断用户姓名是否为空，如果为空更新姓名
                        $updateper = ORM::factory("Personinfo")->where('per_user_id','=',$user->user_id)->find();
                        if(!$updateper->per_realname && $form['user_name']){
                            if($updateper->loaded()){
                                $updateper->per_realname=$form['user_name'];
                                if(!$updateper->per_photo){
                                    //默认头像
                                    $per_photo1 = "/user_icon/plant/default_icon_" . rand ( 1, 25 ) . ".jpg";
                                    $per_photo=common::getImgUrl($per_photo1);
                                    $updateper->per_photo=$per_photo;
                                }
                                $updateper->update();
                            }else{
                                $updateper->per_user_id=$user->user_id;
                                //默认头像
                                $per_photo1 = "/user_icon/plant/default_icon_" . rand ( 1, 25 ) . ".jpg";
                                $per_photo=common::getImgUrl($per_photo1);
                                $updateper->per_photo=$per_photo;
                                $updateper->per_realname=$form['user_name'];
                                $updateper->create();
                            }
                        }
                        //发送验证邮件by周进
                        //$mailservice = new Service_User_Company_User();
                        //$mailservice->updateCheckValidEmail($user->user_id,$user->email);
                        $this->jsonEnArr('200', "注册成功");
                    }
                    else{
                        $this->jsonEnArr('400', "数据写入失败");
                    }
                }
            }
            else{
                $this->jsonEnArr('500', "手机验证码错误");
            }
        }
    }

    /**
     * 用户快速注册(领红包，优惠劵)
     *
     * @author 郁政
     */
    public function action_quickRegisterForExhb(){
        if($this->request->is_ajax()){
            $post = Arr::map("HTML::chars", $this->request->post());
            $service = new Service_User_Person_User();
            //组装表单数据
            $form = array();
            $form['mobile'] = Arr::get($post,"mobile");
            $form['user_name'] = Arr::get($post,"user_name");
            $form['check_code'] = Arr::get($post,"check_code");
            $type = Arr::get($post,"type");
            //判断验证码是否正确
            $servicesend = new Service_User_MobileCodeLog();
            if($servicesend->getCodeEof($form['mobile'], $form['check_code'])){
                //生成密码,并发送到手机
                $form['password'] = "yijuhua".mt_rand(100000, 999999);
                $user = $service->personQuickRegForExhb($form);
                if(is_object($user)){
                    //发送密码到手机
                    $result = common::send_message($form['mobile'], "您的登陆用户名为".$form['mobile']."，登陆密码为".$form['password'], 'online');
                    $userser=new Service_User();
                    //手机号码部分隐藏，格式如139****9476
                    if($form['mobile']){
                        $t_mobile=mb_substr($form['mobile'],0,3,'UTF-8').'****'.mb_substr($form['mobile'],7,11,'UTF-8');
                    }else{
                        $t_mobile='';
                    }
                    if($result->retCode!==0){
                        $this->jsonEnArr("300", "短信发送失败",1);
                        //回滚操作
                        $user->user_person->delete();
                        $user->delete();
                        //消息发送失败log
                        $userser->messageLog($form['mobile'],$user->user_id,5,"您的登陆用户名为".$t_mobile."，登陆密码为".$form['password'],0);
                    }else{//消息发送成功log
                        $userser->messageLog($form['mobile'],$user->user_id,5,"您的登陆用户名为".$t_mobile."，登陆密码为".$form['password'],1);
                    }
                    //增加用户注册统计
                    $stat_service = new Service_Api_Stat();
                    $stat_service->setUserRegStat($user->user_id,$user->user_type, $user->reg_time, arr::get($_COOKIE, 'Hm_lvqtz_refer'),$user->sid );

                    //判断用户姓名是否为空，如果为空更新姓名
                    $updateper = ORM::factory("Personinfo")->where('per_user_id','=',$user->user_id)->find();
                    if(!$updateper->per_realname && $form['user_name']){
                        if($updateper->loaded()){
                            $updateper->per_realname=$form['user_name'];
                            if(!$updateper->per_photo){
                                //默认头像
                                $per_photo1 = "/user_icon/plant/default_icon_" . rand ( 1, 25 ) . ".jpg";
                                $per_photo=common::getImgUrl($per_photo1);
                                $updateper->per_photo=$per_photo;
                            }
                            $updateper->update();
                        }else{
                            $updateper->per_user_id=$user->user_id;
                            //默认头像
                            $per_photo1 = "/user_icon/plant/default_icon_" . rand ( 1, 25 ) . ".jpg";
                            $per_photo=common::getImgUrl($per_photo1);
                            $updateper->per_photo=$per_photo;
                            $updateper->per_realname=$form['user_name'];
                            $updateper->create();
                        }
                    }
                    $this->jsonEnArr('200', "注册成功");
                }else{
                    $this->jsonEnArr('400', "数据写入失败");
                }
            }else{
                $this->jsonEnArr('500', "手机验证码错误");
            }
        }
    }

}

