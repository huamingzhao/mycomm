<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 用户中心
 * @author 龚湧
 *
 */
class Service_User{
    /**
    * 创建用户
    * @param $form 用户注册表单内容
    * @author 龚湧
    * @return ORM $user
    */
    /*
    public function createUser(array $form){
        //表单验证开始
        $error = array();
        $user_id = null;
        $valid = new Validation($form);
        $valid->rule("email", "not_empty");
        $valid->rule("email", "email");
        $valid->rule("password", "not_empty");
        $valid->rule("password", 'min_length', array(':value', '6'));
        $valid->rule("password", 'max_length', array(':value', '20'));
       $valid->rule("confirm", "matches",array(':validation', 'confirm', 'password'));
        $valid->rule("user_type", "not_empty");//用户类型，1为企业用户，2为个人用户，3为政府机构
        if($valid->check()){

            //检查唯邮件唯一性
            $user = ORM::factory("User");
            if($user->check_email($form['email'])){
                //写入数据
                $password = sha1(Arr::get($form,'password').Arr::get($form, 'email'));
                //SID md5
                $sid_md5	= arr::get($_COOKIE, 'Hm_lvqtz_sid');


                //判断sid是否合法
                if( self::isSidValid($sid_md5)===false ){
                   $sid_md5	= '';
                }else{

                }

                $userinfo = array(
                    'email' => Arr::get($form, 'email'),
                    'user_name' => Arr::get($form,'user_name'),
                    'password' => $password,
                    'user_type' => Arr::get($form,'user_type'),
                    'reg_time' => time(),
                    'sid'       => $sid_md5,
                    'last_login_ip'	=>ip2long( Request::$client_ip )
                );

                try{
                    $user = $user->values($userinfo)->create();

                    return $user;
                }catch (Kohana_Exception $e){
                }
            }
        }else{
            //print_r ($form);exit;
            //print_r ($valid->errors());exit;
        }
        return false;
    }
    */
    /**
     * @sso 用户注册
     * @author 龚湧
     * @param array $form
     * @return ORM|boolean
     * 2013-11-4 下午1:38:27
     */
    public function createUser(array $form,$type='email'){
        //表单验证开始
        $error = array();
        $user_id = null;
        $valid = new Validation($form);
        if( $type=='email' ){
            $valid->rule("email", "not_empty");
            $valid->rule("email", "email");
        }

        $valid->rule("password", "not_empty");
        $valid->rule("password", 'min_length', array(':value', '6'));
        $valid->rule("password", 'max_length', array(':value', '20'));
        $valid->rule("confirm", "matches",array(':validation', 'confirm', 'password'));
        $valid->rule("user_type", "not_empty");//用户类型，1为企业用户，2为个人用户，3为政府机构
        if($valid->check()){
            if( $type=='email' ){
                $userinfo = array(
                        'email' => Arr::get($form, 'email'),
                        'user_name' => Arr::get($form,'user_name'),
                        'password' => Arr::get($form, "password"),
                        'user_type' => Arr::get($form,'user_type'),
                );
                $client = Service_Sso_Client::instance();
                $result = $client->appRegister($userinfo,"EMAIL");
            }

            if( $type=='mobile' ){
                $userinfo = array(
                        'mobile' => Arr::get($form, 'email'),
                        'user_name' => Arr::get($form,'user_name'),
                        'password' => Arr::get($form, "password"),
                        'user_type' => Arr::get($form,'user_type'),
                );
                $client = Service_Sso_Client::instance();
                $result = $client->appRegister($userinfo,"MOBILE");
            }


            if($result){
                if(Arr::get($result,"error") === false){
                    $user = Arr::get($result,"return");
                    $user_id = $user['id'];
                    //初始化 user==
                    try{
                        $basic = ORM::factory("User");
                        $basic->user_id = $user_id;
                        $basic->last_logintime = time();
                        $basic->last_login_ip = ip2long(Request::$client_ip);
                        $sid_md5	= arr::get($_COOKIE, 'Hm_lvqtz_sid');
                        $basic->sid = $sid_md5;
                        $aid= Cookie::get('cpa_aid','');
                        if( $aid!='' ){
                            $basic->aid = $aid;
                        }
                        $rbs= $basic->create();

                    }catch(Kohana_Exception $e){
                        //throw $e;
                        //抛出错误代码
                    }
                    //初始化本地结束==
                    //自动登录
                    if( $type=='email' ){
                        $rlogin= $client->login($userinfo['email'], $userinfo['password']);
                    }
                    if( $type=='mobile' ){
                        //绑定手机号。否则无法完成登录操作
                        $client= Service_Sso_Client::instance();
                        $client->updateMobileInfoById( $user_id,array( 'valid_status'=>'1','valid_time'=>time() ) );

                        $rlogin= $client->login($userinfo['mobile'], $userinfo['password']);
                    }

                    //返回用户信息
                    return $client->getUserInfoById($user_id);
                }else{
                    $error= Arr::get($result,"error");
                    return $error;
                }
            }else{
                return false;
            }

        }//$error_arr = $valid->errors();print_r ($error_arr);exit;
        return false;
    }
    /**
     * 会员邀请
     * @author 施磊
     */
   public function userInviter($user_id, $inviter_id, $inviter_type = 1) {
       $user_id = intval($user_id);
       $inviter_id = intval($inviter_id);
       $inviter_type = intval($inviter_type);
       if(!$user_id || !$inviter_id) return false;
       $user_inviter = array(
                        'user_id' => $user_id,
                        'inviter_user_id' => $inviter_id,
                        'invite_type' => $inviter_type,
                        'creat_time' => time()
                );
       $count = ORM::factory("UserInviter")->where('user_id', '=', $user_id)->where('inviter_user_id', '=', $inviter_id)->count_all();
       if($count) return true;
       $user = ORM::factory("UserInviter")->values($user_inviter)->create();
       return true;
   }

   /**
    * 用户验证
    * @author stone shi
    */
   public function changeUserChanceForVoild($user_id) {
           $user_id = intval($user_id);
           if(!$user_id) return false;
           $mod = ORM::factory("UserInviter")->where('inviter_user_id', '=', $user_id)->find();
           if(!$mod->loaded()) return false;
           return $this->changeUserHuodongChance(1, $mod->user_id, 1);
   }

   /**
    * 获得本期活动邀请的用户
    * @auhtor stone shi
    */
   public function getUserInviterCount($user_id) {
       $user_id = intval($user_id);
       if(!$user_id) return 0;
       $user_count = DB::select(DB::expr('count("id") as count'))->from('user_inviter')->where('user_id', '=', $user_id)->execute()->as_array();
       $user_count = arr::get(arr::get($user_count,0), 'count', 0);
       return $user_count;
   }
   /**
    * 获得邀请数据
    * @auhtor stone shi
    */
   public function getUserInviter($user_id, $type = 0) {
          $client = Service_Sso_Client::instance();
       $mod = ORM::factory("UserInviter")
                ->where('user_id', '=', $user_id);
       if($type) {
              $mod->where('invite_type', '=', $type);
       }
       $count = $mod->count_all();
       $page = Pagination::factory ( array (
                'total_items' => $count,
                'items_per_page' => 5,
                'view' => 'pagination/Simple'
        ) );
       $mod = ORM::factory("UserInviter")
                ->where('user_id', '=', $user_id);
       if($type) {
              $mod->where('invite_type', '=', $type);
       }

       $listArr = $mod->limit($page->items_per_page)->offset($page->offset)->find_all();
       $list = array();
       foreach($listArr as $val) {
          $list[] = $val->as_array();
       }
       $userIdTemps = array();
       $userinfo = array();
       if($list) {
           foreach($list as $key => $val) {
               //$userIdTemp = $val['inviter_user_id'];
               //$listTemp = ORM::factory("User", $userIdTemp)->as_array();
               //$list[$key]['userInfo'] = $listTemp;
               $userIdTemps[] = $val['inviter_user_id'];
           }
           $userinfo = $client->getUserInfoByMoreUserId($userIdTemps);
           foreach($userinfo as $k => $v){
               $list[$k]['userInfo'] = $v;
           }
       }
       $return['list'] = $list;
       $return['page'] = $page;
       return $return;
   }
   /**
    * 抽奖活动机会增减
    * @author stone.shi
    */
   public function changeUserHuodongChance($chance_num, $user_id, $type) {
       $chance_num = intval($chance_num);
       $user_id = intval($user_id);
       $type = intval($type);
       if(!$user_id  || !$type) return false;
       if(($chance_num < 0 && $this->getUserHuodongChance($user_id) > 0) || $chance_num > 0) {
           $huodong = new Service_User_Person_Huodong();
           $game_id = $huodong->getGameId();
           $cond = array(
               'user_id' => $user_id,
               'chance' => $chance_num,
               'type' => $type,
               'game_id' => $game_id,
               'creat_time' => time()
           );
           if($type == 2){
               $start_time = strtotime(date('Y-m-d 00:00:00', time()));
               $end_time = strtotime(date('Y-m-d 00:00:00', time()+86400));
               $count = ORM::factory('UserHuodongChance')->where('user_id','=',$user_id)->where('game_id','=',$game_id)->where('type','=',2)->where('creat_time','>=',$start_time)->where('creat_time','<',$end_time)->count_all();
               if($count > 0) return false;
           }
           $user = ORM::factory("UserHuodongChance")->values($cond)->create();
           return true;
       }else{
           return false;
       }

   }

   /**
    * 获得全部活动机会
    * @author stone shi
    */
   public function getUserHuodongChance($user_id) {
       $user_id = intval($user_id);
       if(!$user_id) return 0;
       $huodong = new Service_User_Person_Huodong();
       $game_id = $huodong->getGameId();
       $sum = DB::select(DB::expr('sum(chance) as num'))->from('user_huodong_chance')->where('user_id', '=', $user_id)->where('game_id', '=', $game_id)->execute()->as_array();
       $sum = arr::get(arr::get($sum,0), 'num', 0);
       return $sum;
   }

   /**
    * 获得邀请好友注册成功增加的机会
    * @author 郁政
    */
    public function getUserHuodongChanceInvite($user_id) {
       $user_id = intval($user_id);
       if(!$user_id) return 0;
       $huodong = new Service_User_Person_Huodong();
       $game_id = $huodong->getGameId();
       $sum = DB::select(DB::expr('SUM(chance) as num'))->from('user_huodong_chance')->where('user_id', '=', $user_id)->where('game_id', '=', $game_id)->where('chance' ,'>', 0)->where('type','=',1)->execute()->as_array();
       $sum = arr::get(arr::get($sum,0), 'num', 0);
       return $sum;
   }

   /**
    * 获得增加的机会
    * @author stone shi
    */
   public function getUserHuodongChanceAdd($user_id) {
       $user_id = intval($user_id);
       if(!$user_id) return 0;
       $huodong = new Service_User_Person_Huodong();
       $game_id = $huodong->getGameId();
       $sum = DB::select(DB::expr('SUM(chance) as num'))->from('user_huodong_chance')->where('user_id', '=', $user_id)->where('game_id', '=', $game_id)->where('chance' ,'>', 0)->execute()->as_array();
       $sum = arr::get(arr::get($sum,0), 'num', 0);
       return $sum;
   }

   /**
    * 获得减去的机会
    * @author stone shi
    */
   public function getUserHuodongChanceMin($user_id) {
       $user_id = intval($user_id);
       if(!$user_id) return 0;
       $huodong = new Service_User_Person_Huodong();
       $game_id = $huodong->getGameId();
       $sum = DB::select(DB::expr('sum(chance) as num'))->from('user_huodong_chance')->where('user_id', '=', $user_id)->where('game_id', '=', $game_id)->where('chance' ,'<', 0)->execute()->as_array();
       $sum = arr::get(arr::get($sum,0), 'num', 0);
       return $sum;
   }

   /**
     * 发送邀请邮箱
     * @author stone shi
     */
    public function userInvitereEmail($user_id, $email, $to_email, $send_content) {
        $service = new Service_User();
        $person_service = new Service_User_Person_User();
        $userinfo = $service->getUserInfoById($user_id);
        $userperson = $person_service->getPerson($user_id);
        $realname =  (isset($userperson->per_realname) && $userperson->per_realname != '') ? $userperson->per_realname : '';
        $mobile = (isset($userinfo->mobile) && $userinfo->mobile != '') ? common::decodeUserMobile($userinfo->mobile) : '';
        $str = '';
        if($realname != ''){
            if($mobile != ''){
                $str = $realname.'(手机号码为'.$mobile.')';
            }else{
                $str = $realname;
            }
        }
        //$content = "你的好友{$email}邀请你注册一句话，<a herf='http://".$_SERVER['HTTP_HOST']."/geren/zhuce.html?inviter_user_id={$user_id}&inviter_type=1'>连接</a>{$send_content}";
        $smsg = Smsg::instance();
        $smsg->register(
                'email_person_invite_friend4',
                Smsg::EMAIL,//类型
                array(
                        "to_email"=>$to_email,
                        "subject"=>'好友邀请'
                ),
                array(
                        "str" => $str,
                        "user_id" => $user_id,
                        "send_content" => $send_content,
                )

        );
           $msg['status'] = '1';
         return $msg;
    }



    /**
     * 登录验证(只验证email和password)
     * @author 曹怀栋
     * @modify by 龚湧 2012.12.20 删除了email 邮件验证
     */
    public function loginCheck(array $post){
        //登录表单验证
        $valid = new Validation($post);
        $valid->rule("email", "not_empty");//增加了手机验证，去掉了邮件验证
        $valid->rule("password", "not_empty");
        $valid->rule("password", 'min_length', array(':value', '6'));
        $valid->rule("password", 'max_length', array(':value', '20'));
        if(!$valid->check()){
            $error = $valid->errors("user/user");
            return $error;
        }else{
            return true;
        }
    }

    /**
     * 登录验证
     * @author 曹怀栋
     * @modify by 龚湧 2012.12.20
     */
    public function loginCaptcha(array $post){
        $auth_name=secure::secureInput(secure::secureUTF(arr::get($post,'email')));
        if(!Captcha::valid($post['valid_code'])){
            $error = array('captcha'=>"验证码错误");
        }else{
            $result = $this->loginCheck($post);
            if($result == 1){
                //old auth
                /*
                $users =Auth::instance()->login($email,arr::get($post,'password'),arr::get($post,'remember'));
                if ($users === 'email'){
                    $error = array('email'=>"用户名不存在");
                }elseif ($users === 'password'){
                    $error = array('password'=>"密码不正确");
                }else{
                    return true;
                }
                */
                $auth_result = Service_Sso_Client::instance()->login($auth_name,arr::get($post,'password'),arr::get($post,'remember'));
                if(Arr::get($auth_result,"error") === false){
                    //登录成功
                    return true;
                }
                else{
                    $error_code = Arr::get($auth_result, "code");
                    if($error_code == "001"){
                        $error = array('email'=>"用户名不存在");
                    }
                    elseif($error_code == "003"){
                        $error = array('password'=>"密码不正确");
                    }
                    else{
                        $error = array('email'=>"未知错误");
                    }
                }
            }else{
                $error = $result;
            }
        }
        return $error;
    }

    /**
     * @sso
     * 验证email是否存在
     * @author 曹怀栋
     */
    public function forgetPasswordEmail($email){
        $client = Service_Sso_Client::instance();
        $info = $client->getUserInfoByEmail($email);
        if($info){
            return true;
        }
        else{
            return array('email'=>"邮箱不存在");
        }
    }

    /**
     * @sso
     * 发送找回密码邮件是否成功
     *  @author 曹怀栋
     */
    public function sendMailPassword($email){
        $client = Service_Sso_Client::instance();
        $info = $client->getUserInfoByEmail($email);

        $swiftconfig = Kohana::$config->load('message.expire.email');
        $swiftconfig = $swiftconfig/(24*3600);
        //$user = ORM::factory('user')->where("email", "=", $email)->find()->as_array();
        $code = $this->createValidCode($info->id,3);
        if( $code==false ){
            //发送过邮件了，不发送
            return false;
        }
        $url = "http://".$_SERVER['HTTP_HOST']."/member/passwordmodification?key=".sha1($email.$swiftconfig.$code)."&email=".$email."&code=".time()."&sud=".$info->id;

        $smsg = Smsg::instance();
        $smsg->register(
                'email_user_forgetemail',
                Smsg::EMAIL,//类型
                array(
                        "to_email"=>$email,
                        "subject"=>'生意街--找回密码确认邮件'
                ),
                array(
                        "url"=>$url
                )

        );
        return true;

    }

    /**
     * @sso
     * 邮件找回密码修改验证
     * @author 曹怀栋
     */
    public function passwordModification($get){
        $swiftconfig = Kohana::$config->load('message.expire.email');
        $swiftconfig = $swiftconfig/(24*3600);
        if((arr::get($get,'code')+7200) >= time()){//两小时内
            $client = Service_Sso_Client::instance();
            $email = Arr::get($get,'email');
            $key = Arr::get($get, "key");
            if($email){
                $user = $client->getUserInfoByEmail($email);
                if($user){
                    $code = $this->getValidCode($user->id,3);
                    if(sha1($user->email.$swiftconfig.$code) == arr::get($get,'key')){
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * @sso
     * 邮件找回密码中验证两次密码是否正确
     * @author 曹怀栋
     */
    public function passwordSuccess($post){

        //验证email和uid匹配否
        // 检查email和uid 是否匹配,edit by 许晟玮
        $client = Service_Sso_Client::instance ();
        $info = $client->getUserInfoByEmail ( Arr::get ( $post, 'email' ) );
        if ($info === false) {
            return false;
        }
        if ($info->id != common::decodeMoible(Arr::get ( $post, 'suii' )) ) {
            return false;
        }


        if(arr::get($post,'newpassword') != arr::get($post,'confirm')){
            $error = "两次登录密码输入不正确！";
        }
        $client = Service_Sso_Client::instance();
        $password = Arr::get($post,'newpassword');
        $update = $client->getUserInfoByEmail(Arr::get($post,"email"));
        if(!$update){
            $error = "请不要非法操作！";
        }
        if(!isset($error)){
            $user['user_id'] = $update->id;
            $result = $client->resetPassword($user['user_id'], $password);
            if($result){
                if(Arr::get($result,"error")  === false){
                    return true;
                }
            }
        }
        return false;
    }
    /**
     * @sso
     * 验证已经发送的邮件url确认
     * @author 周进
     */
    public function editValidEmail($post){
        $result['status'] = false;
        if($post['code'] and $post['key'])
        {
            $user_id = substr($post['key'],0,strpos($post['key'],"O"));
            $validcode = $this->getValidCode($user_id,1);
            $result['status'] = false;
            $result['user_id'] = $user_id;
            //验证邮件的时效性
            $swiftconfig = Kohana::$config->load('message.expire.email');
            if((time()-$validcode)<$swiftconfig)
            {
                //验证URL合法性
                if (md5($validcode)==$post['code'])
                {
                    $status = Service_Sso_Client::instance()->updateEmailInfoById($user_id,array("valid_status"=>1));
                    /*$user = ORM::factory("User",$user_id);
                    //$user->valid_email = 1;*/
                    $result['status'] = $status;
                }
            }
        }
        return $result;
    }

    /**
     * 更新用户信息验证 last_logintime/last_login_ip
     * @author 曹怀栋
     * $user数组中必须有user_id
     */
    public function updateUser(array $user){
        //登录表单验证
        $update = ORM::factory("user",$user['user_id']);
        if( $update->loaded()===false ){
            //insert
            $orm= ORM::factory("user");
            $orm->user_id= $user['user_id'];
            foreach ($user as $k=>$v){
                if($k != 'user_id'){
                    $orm->$k = $v;
                }
            }
            $r= $orm->create();
        }else{
            //update
            $update = ORM::factory("user",$user['user_id']);
            foreach ($user as $k=>$v){
                if($k != 'user_id'){
                    $update->$k = $v;
                }
            }
            $r= $update->update();
        }


        return true;
    }

    /**
     * @sso
     * 修改登录密码验证
     * @author 曹怀栋
     */
    public function modifyPassword($post,$userid){
        if(arr::get($post,'newpassword_xg') != arr::get($post,'confirmpassword_xg')){
            $error = array('password'=>"两次登录密码输入不正确！");
        }
        $client = Service_Sso_Client::instance();
        $new_password = Arr::get($post,'newpassword_xg');
        $password = Arr::get($post,"password_xg");
        $result = $client->setNewPassword($userid, $password, $new_password);
        if($result){
            if(Arr::get($result,"error") === false){
                return true;
            }
            else{
                $code = Arr::get($result,"code");
                if($code == "004"){
                    array('password'=>"原密码错误！");
                }
            }
        }
        $error = array('password'=>"修改失败！");
        return $error;
    }

    /**
     * 生成验证码
     * @author 龚湧
     * @param int $user_id
     * @param int $type
     * @return int $code | false
     */
    public function createValidCode($user_id,$type){
        $valid = ORM::factory("Validcode")
                ->where("user_id", "=", $user_id)
                ->where("type","=",$type)
                ->find();
        if( $valid->code!='' ){
            //检查最后一个验证码时间是否距离现在5分钟
            if( time() <=$valid->code+300 ){
                return false;
            }
        }
        $valid->code = time();
        $valid->user_id = $user_id;
        $valid->type = $type;
        // 已经存在,更新操作,不存在插入
        try {
            $result = $valid->save();
        }
        catch ( Kohana_Exception $e ) {
            return false;
        }
        return $result->code;
    }

    /**
     * 获取验证码
     * @author 龚湧
     * @param int $user_id 用户id
     * @param  $type 验证码类型
     * @param int $expire 过期时间 秒
     * @return int $code | false
     */
    public function getValidCode($user_id,$type,$expire = NULL){
        $valid = ORM::factory("Validcode")
                ->where("user_id", "=", $user_id)
                ->where("type","=",$type)
                ->order_by("code","desc")
                ->find();
        //验证码不存在
        if(!$valid->code){
            return false;
        }
        if($expire !== NULL){
            //验证码过期
            $flag = time() - ((int)$valid->code+(int)$expire);
            if($flag > 0){
                return false;
            }
        }
        return $valid->code;
    }

    /**
     * 发送手机验证码
     * @author 龚湧
     * @param int $receiver 手机号码
     * @param int $user_id
     * @param int $type
     * @param int $expire
     * @return boolean
     */
    public function sendValidCode($receiver,$user_id,$type,$cty=""){
        if($cty == "mobile_add"){
            $view = "msg/mobile/mobile_add";
        }
        elseif($cty == "mobile_modify"){
            $view = "msg/mobile/mobile_modify";
        }
        //生成验证码,验证取后6位即可
        $valid_org = $this->createValidCode($user_id,$type);
        //成功添加验证码
        if($valid_org){
            $valid_code = substr((substr ( $valid_org, - 6 )*13+2),-6);
            $msg  = "您的验证码是".$valid_code ."";
            if(isset($view)){
                $view = View::factory($view);
                $view->valid_code = $valid_code;
                $msg = $view->render();
            }
            $msg2 = common::send_message($receiver, $msg,"online");//测试服务上的为
            //消息发送成功
            if($msg2->retCode === 0){
                $this->messageLog($receiver,$user_id,$type,$msg,1);
                return true;
            }else{//发送失败
                $this->messageLog($receiver,$user_id,$type,$msg,0);
            }
        }
        //删除发送失败验证码
        $code = ORM::factory("Validcode")
                ->where('user_id',"=",$user_id)
                ->where("type","=",$type)
                ->find()
                ->delete();
        return false;
    }

    /**
     * @sso
     * 查看电话号码是否已经被绑定,已经绑定为true，否则为false
     * @author 龚湧
     * @param int $mobile
     * @return boolean
     */
    public function isMobileBinded($mobile){
        $client = Service_Sso_Client::instance();
        $user = $client->getUserInfoByMobile($mobile);
        if($user){
            return true;
        }
        return false;
    }

    /**
     * 根据用户id查询用户信息
     * @author 施磊
     * @param int $user_id 用户id
     * @return obj $userInfo 用户信息
     */
    /*
    public function getUserInfoById($user_id){
        if(intval($user_id)) {
            $userInfo = ORM::factory("User", $user_id);
            return $userInfo;
        }
        return false;
    }
    */

    public function getUserInfoById($user_id,$clean=false){
        if(intval($user_id)) {
            $client = Service_Sso_Client::instance();
            $user = $client->getUserInfoById($user_id);
            $user->user_id = $user->id;
            //对应关系兼容性调整
            if($clean){
                $basic = ORM::factory("User",$user->id);
                $user->basic = $basic;
                //企业用户
                if($user->user_type == 1){
                    $user->user_company = $basic->user_company;
                }
                //个人用
                elseif($user->user_type == 2){
                    $user->user_person = $basic->user_person;
                }
            }
            return $user;
        }
        return false;
    }
    /**
     * @sso
     * 绑定手机号
     * @author 龚湧
     * @param ORM $user
     * @param int $receiver
     * @param int $check_code
     * @return boolean
     */
    public function bindMobile($user_id,$receiver,$check_code){
        //$user = ORM::factory("User",$user_id);
        $client = Service_Sso_Client::instance();
        $user = $client->getUserInfoById($user_id);
        $expire = Kohana::$config->load ( "message.expire.mobile" );
        $valid = Cookie::get("valid_mb");
        //手机号码验证
        if(!common::is_mobile($receiver)){
            return false;
        }
        if($valid !== md5($receiver.Kohana::$config->load("message.space_time.mobile"))){
            return false;
        }
        $code = $this->getValidCode ( $user->id, 2, $expire );
        if ($code) {
            $encode = substr((substr ( $code, - 6 )*13+2),-6);
            if ($check_code == $encode) {
                //$user->valid_mobile = 1;
                //$user->mobile = $receiver;
                try {
                    //update mobile status
                    $rs_userinfo= $client->getUserInfoById($user_id);
                    if( $rs_userinfo->mobile=='' && $rs_userinfo->valid_mobile=='' ){
                        $resulta= $client->setUserMobileById( $user_id,$receiver,1 );
                    }else{
                        $resulta= $client->updateMobileInfoById( $user_id,array('mobile'=>$receiver,'valid_status'=>'1','valid_time'=>time()) );
                    }
                    if( $resulta===false ){
                        return false;
                    }
                    //$result=$user->update ();

                    if($user->user_type==2){//个人用户手机通过验证添加活跃度 by钟涛
                        //向活动用临时用户表添加记录
                        $huodong = new Service_Platform_HuoDong4();
                        $huodong->addUserTeamp($user_id, time());

                        $ser1=new Service_User_Person_Points();
                        $ser1->addPoints($user_id, 'valid_mobile');//手机认证
                         //用户邀请验证
                        $this->changeUserChanceForVoild($user_id);

                        //判断info表中是否存在记录
                        $service_person= new Service_User_Person_User();
                        $result= $service_person->selectPersonalAreaByUserId( $user_id );
                        if( empty( $result ) ){
                            try{
                                $city_id= common::getMessageArea($receiver);

                                    if( $city_id>0 && $city_id!==false ){
                                        //insert into person_area
                                        $service_person->insertPersonalArea( $user_id,array( $city_id ) );

                                    }
                            }catch ( Kohana_Exception $e ){

                            }

                        }

                            return true;

                    }



                    if( $user->user_type==1 ){
                        return true;
                    }
                } catch ( Kohana_Exception $e ) {throw $e;
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * @sso
     * 解除手机绑定
     * @author 龚湧
     * @param int $user_id
     * @return boolean
     */
    public function unbindMobile($user_id){
        $client = Service_Sso_Client::instance();
        $upldate = $client->updateMobileInfoById($user_id, array("valid_status"=>0));
        return $upldate;
    }

    /**
     * 我的标签以逗号分割并返回
     * @author 曹怀栋
     */
    public function projectInvestmentProups($result){
        $groups = "";
        //投资人群（类型）取得tag表的name
        $res_crowd = ORM::factory('Personcrowd')->select('*')->where("user_id", "=",$result->per_user_id)->find_all();
        if(count($res_crowd) > 0){
            foreach ($res_crowd as $k=>$v){
                $tag = ORM::factory('Usertype',$v->tag_id);
                $groups .= $tag->tag_name.",";
            }
        }
        if($result->per_per_label != ""){
            $result->per_per_label =$groups.implode(",", unserialize($result->per_per_label));
        }else{
            $result->per_per_label =substr($groups,0,-1);
        }
        if($result->per_per_label == ","){
            $result->per_per_label ="";
        }
        return $result;
    }

    /**
     * 获取个人用户添加从业经验数量(0：即没有添加从业经验)
     * @author 钟涛
     */
    public function getExperienceCount($userid){
        return ORM::factory('Experience')
        ->where('exp_user_id', '=', $userid)//当前用户
        ->where('exp_status','=',1)//工作经验状态未删除的
        ->count_all();
    }


    /**
     * @sso
     * 判断用户名是否有效
     * @author 龚湧
     * @param string $login_name
     * @return boolean | ORM
     */
    public function checkLoginName($login_name){
        //TODO 手机号码和邮箱规格后端验证 return true false
        $client = Service_Sso_Client::instance();
        //邮箱登录
        if(strpos($login_name, "@")){
            $result = $client->getUserInfoByEmail($login_name);
            if($result){
                return $result;
            }
            return false;
        }
        //手机号验证
        else{
            //手机号码已经绑定
            $result = $client->getUserInfoByMobile($login_name);
            if($result){
                return $result;
            }
            return false;
        }
    }


    /**
     * @sso
     * 手机发送短信LOG日志
     * @author 龚湧[钟涛修改]
     * @param int $user_id
     * @param int $type
     * @return boolean
     */
    public function messageLog($mobile,$user_id,$type,$content,$status){
        $client = Service_Sso_Client::instance();
        $user =$client->getUserInfoById($user_id);
        $user_name='';
        if($user->user_type==1){//企业用户
            $com_user = ORM::factory('Companyinfo')->where('com_user_id','=',$user_id)->find();
            $user_name=$com_user->com_name;
        }elseif($user->user_type==2){//个人用户
            $per_user = ORM::factory('Personinfo')->where('per_user_id','=',$user_id)->find();
            $user_name=$per_user->per_realname;
        }else{    }
        $message = ORM::factory("Messagelog");
        $message->user_id = $user_id;//用户ID
        $message->mobile = $mobile;//用户手机
        $message->user_type = $user->user_type;//用户类型
        $message->user_name = $user_name;//用户名
        $message->send_content = $content;//内容
        $message->status = $status;//发送状态  1：成功 0：失败
        $message->type = $type;//类型2;发送手机验证码.3:通知新名片信息  4重置密码.....
        $message->send_time = time();
        try{
            $message->create();
        }catch(Kohana_Exception $e){
            return false;
        }
        return true;
    }

    /**
     * 根据验证邮箱获取一周未登陆并且有新收到名片的企业+个人用户
     * @param $usertpye 用户类型（1企业用户 2个人用户）
     * @author 钟涛
     */
    public function getNotLoginUserEmail($usertpye){
        $data=time()-604800;//一周前日期
        $model= ORM::factory('User')
        ->join('card_info','LEFT')->on('to_user_id','=','user_id')
        ->where("user_type", "=", $usertpye) //1企业用户 2个人用户
        ->where("valid_email", "=", 1) //邮箱已经通过验证
        ->where("week_send_email", "=", 1) //启用的状态
        ->where("last_logintime", "<", $data)//7天前登录的用户
        ->where("send_time",">",$data)//7天内发送的
        ->where("to_del_status","=","0");//未删除的名片
        return $model->select('*')->group_by('user_id')->find_all();
    }

    /**
     * 根据验证手机获取一周未登陆并且有新收到名片的企业+个人用户
     * @param $usertpye 用户类型（1企业用户 2个人用户）
     * @author 钟涛
     */
    public function getNotLoginUserMobile($usertpye){
        $data=time()-604800;//一周前日期
        $model= ORM::factory('User')
        ->join('card_info','LEFT')->on('to_user_id','=','user_id')
        ->where("user_type", "=", $usertpye) //1企业用户 2个人用户
        ->where("valid_mobile", "=", 1) //手机已经通过验证
        ->where("week_send_mobile", "=", 1) //手机启用的状态
        ->where("last_logintime", "<", $data)//7天前登录的用户
        ->where("send_time",">",$data)//7天内发送的
        ->where("to_del_status","=","0");//未删除的名片
        return $model->select('*')->group_by('user_id')->find_all();
    }

    /**
     * 对企业+个人用户发送短信通知(一周未登录且已收到新名片的用户)
     * @param $usertpye 用户类型（1企业用户 2个人用户）
     * @author 钟涛
     */
    public function sendMobileNewCardInfo($usertpye){
        //不限制PHP程序运行时间,避免执行超时
        //php.ini里max_excute_time无效
        @set_time_limit(0);
        //启动数据缓冲,采用ob_start将输出的信息及时显示
        @ob_end_clean();
        @ob_start();
        $lastweekdata=time()-604800;//一周前日期
        $nowdata=time();//当前日期
        $msg = array();
        $user = $this->getNotLoginUserMobile($usertpye);//获取周未登陆并且有新收到名片的企业用户
        $per_card=new Service_User_Company_Card();
        $com_user=new Service_User_Company_User();
        $per_user=new Service_User_Person_User();
        foreach ($user as $v){
            if ($v->user_id !='' && $v->mobile !=''){
                $newcardcount=$per_card->getReceivedCardCountByTimeSlice($v->user_id,$lastweekdata,$nowdata);
                $username='';
                if($usertpye==1){//企业用户
                    $userinfo= $com_user->getCompanyInfo($v->user_id);
                    $username = $userinfo->com_contact;
                }else{//个人用户
                    $userinfo=$per_user->getPerson($v->user_id);
                    $username=$userinfo->per_realname;
                }
                $megage='';
                if($usertpye==1){
                    $msgage='亲爱的'.$username.',您最近收到'.$newcardcount.'张新的名片，赶快登录一句话与投资者互动吧，助您招商的道路飞速发展！'.url::website('').'。回复TD退订[垂直招商平台]';
                }else{
                    $msgage='亲爱的'.$username.',您最近收到'.$newcardcount.'张新的名片，赶快登录一句话与项目的招商者互动吧，助您投资的道路财运亨通！'.url::website('').'。回复TD退订[垂直招商平台]”';
                }
                //必须一起使用, 不然无法及时显示, PHP文档有说明
                @ob_flush();
                @flush();
                $resultmsg = common::send_message($v->mobile, $msgage,"online");
                //wait for 0.01 second
                usleep(10000);
                //消息发送成功
                if($resultmsg->retCode === 0){
                    $type=3;//短信通知新名片信息
                    $this->messageLog($v->mobile,$v->user_id, $type,$msgage,1);
                    $msg[$v->user_id]=1;
                }
                else{
                    $msg[$v->user_id]=0;//发送失败
                    $this->messageLog($v->mobile,$v->user_id, $type,$msgage,0);
                }
            }
        }
        return $msg;
    }

    /**
     * 对用户发送邮件通知(一周未登录且已收到新名片的用户)
     * @param $usertpye 用户类型（1企业用户 2个人用户）
     * @author 钟涛
     */
    public function sendEmailNewCardInfo($usertpye){
        //不限制PHP程序运行时间,避免执行超时
        //php.ini里max_excute_time无效
        @set_time_limit(0);
        //启动数据缓冲,采用ob_start将输出的信息及时显示
        @ob_end_clean();
        @ob_start();
        $lastweekdata=time()-604800;//一周前日期
        $nowdata=time();//当前日期
        $msg = array();
        $user = $this->getNotLoginUserEmail($usertpye);//获取周未登陆并且有新收到名片的企业用户
        $com_card=new Service_User_Company_Card();
        $per_card=new Service_User_Person_Card();
        foreach ($user as $v){
            if ($v->user_id !='' && $v->email !=''){
                //生成验证码[4为取消订阅一周未登陆邮件提醒]
                $code = $this->createValidCode($v->user_id,4);
                $newcardcount=$com_card->getReceivedCardCountByTimeSlice($v->user_id,$lastweekdata,$nowdata);
                $limtnum=1;
                if($newcardcount>1){
                    $limtnum=2;
                }
                $cardlist='';
                $money_list=array();
                $industry=array();
                //获取收到的名片2张
                if($usertpye==1){
                    $cardlist = $com_card->getReceivedCardLimit($v->user_id,$limtnum);
                    $money_list = common::moneyArr();//投资金额
                    //投资行业
                    $allindustry = common::primaryIndustry(0);
                    $industry=array();
                    foreach ($allindustry as $key=>$lv){
                        $industry[$lv->industry_id] = $lv->industry_name;
                    }
                }else{
                    $return_arr=$per_card->twoReceiveCard($v->user_id,$limtnum);
                    $cardlist= $per_card->getSerializeArrayList($return_arr['list']);
                }
                //必须一起使用, 不然无法及时显示, PHP文档有说明
                @ob_flush();
                @flush();
                //获取发送邮件内容
                $sendcontent=$this->getSendEmailCardContent($usertpye,$v->user_id,$code,$cardlist,$newcardcount,$money_list,$industry);
                //wait for 0.01 second
                usleep(10000);
                $sendresult = false;
                //开始发送mail
                $sendresult = common::sendemail("收到新的名片信息邮件提醒", 'service@qutouzi.com', $v->email, $sendcontent);
                if ($sendresult==1){
                    $msg[$v->user_id] = '1';
                }else{
                    $msg[$v->user_id] = '0';//发送失败的用户
                }
            }
        }
        return $msg;
    }

    /**
     * 发送的邮件内容
     * @author 钟涛
     */
    public function getSendEmailCardContent($usertpye,$user_id,$code,$cardlist,$newcardcount,$money_list,$industry){
        $com_user=new Service_User_Company_User();
        $per_user=new Service_User_Person_User();
        $username='';//用户名
        $image='';//用户头像
       // print_r($money_list);exit;
        if($usertpye==1){//企业用户
            $userinfo= $com_user->getCompanyInfo($user_id);
            $username=$userinfo->com_contact;
            $image = URL::imgurl($userinfo->com_logo);
        }else{//个人用户
            $userinfo=$per_user->getPerson($user_id);
            $username=$userinfo->per_realname;
            $image = URL::imgurl($userinfo->per_photo);
        }
        //取消订阅url
        $url = url::website('')."regularscript/cancelSendEmail/?key=".$user_id."&code=".md5($code);
        $content = '
        <!DOCTYPE html>
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">
        *{margin:0;padding:0;}
        body{font-size:12px;font-family:"宋体";}
        ul{list-style:none;}
        img{border:0;}
        a{text-decoration:none;}
        .clear{clear:both;}
        .floleft{float:left;}
        .floright{float:right;}
        #dingy_email{width:700px;margin:0 auto;}
        #dingy_email .title{height:40px;background:url(../images/dingyue_email/a.jpg) no-repeat;margin-top:90px;color:#fff;font-size:16px;font-weight:bold;line-height:40px;padding-left:20px;}
        #dingy_email .context{padding-left:50px;font-size:14px;}
        #dingy_email .context p{line-height:25px;padding-top:20px;}
        #dingy_email .context p input{margin:0 10px -2px 0;}
        #dingy_email .context p textarea{width:590px;height:108px;margin-bottom:10px;}
        #dingy_email .email_top{border:1px solid #e7e7e7;height:78px;background:#f9f9f9;margin-top:70px;}
        #dingy_email .email_center{padding-top:20px;}
        #dingy_email .email_center .email_left{float:left;}
        #dingy_email .email_center .email_left img{width:116px;height:106px;padding:1px;border:1px solid #e3e3e3;}
        #dingy_email .email_center .email_right{float:right;width:570px;}
        #dingy_email .email_center .email_right h3{height:30px;line-height:30px;padding-left:10px;font-weight:bold;font-size:14px;color:#fff;background:#6696d6;}
        #dingy_email .email_center .email_right h3 span{color:#fcff00;}
        #dingy_email .email_center .email_right p{font-size:14px;line-height:26px;padding:10px 0 0 15px;}
        #dingy_email .email_center .email_right p a{color:#003cff;}
        #dingy_email .email_center .email_right .website{padding-top:0;}
        #dingy_email .email_center .email_right .website a{color:#2167c2;font-size:16px;font-weight:bold;text-decoration:underline;}
        #dingy_email .email_center .tishi{font-size:14px;font-weight:bold;border-bottom:1px solid #e3e3e3;padding-bottom:10px;padding-top:50px;}
        #dingy_email .email_center .tishi span{color:#ff4e00;}
        #dingy_email .email_center .email_card{padding:20px 8px;border-bottom:1px solid #e3e3e3;}
        #dingy_email .email_center .email_card .card_left{width:50%;float:left;}
        #dingy_email .email_center .email_card .card_left .user{width:100px;height:90px;float:left;}
        #dingy_email .email_center .email_card .card_left .user img{width:94px;height:84px;padding:2px;border:1px solid #e3e3e3;}
        #dingy_email .email_center .email_card .card_left .text{float:left;padding-left:10px;}
        #dingy_email .email_center .email_card .card_left .text p{line-height:23px;}
        #dingy_email .email_center .view_more{padding:10px;}
        #dingy_email .email_center .view_more a{color:#ff4e00;font-weight:bold;text-decoration:underline;}
        #dingy_email .email_center .bb{line-height:25px;font-size:14px;padding:40px 10px;}
        #dingy_email .email_center .bb a{color:#003cff;text-decoration:underline;}
        #dingy_email .email_bottom{padding:20px 30px;background:#eceff3;color:#5b5b5b;margin-bottom:50px;}
        #dingy_email .email_bottom p{line-height:22px;}
        #dingy_email .email_bottom p a{color:#002aff;text-decoration:underline;}
        #dingy_email .email_bottom p span{color:#f60;font-size:16px;font-weight:bold;font-family:Arial;}
        </style> </head><body>
        <div id="dingy_email">
        <div class="email_top"></div>
        <div class="email_center">
        <div class="email_left"><img src="'.$image.'" /></div>
        <div class="email_right">
        <h3>亲爱的<span>'.$username.'先生/女士：</span></h3>
        <p>您好，我们注意到您最近没有到<a href="'.url::website('').'">一句话</a>递交您的名片了，';
        if($usertpye==1){
            $content .='您是否找到您心仪的投资者了？在您忙碌的一周里，<a href="'.url::website('').'">一句话</a>的投资者很是挂念您，记得常来<a href="'.url::website('').'">垂直招商平台</a>与投资者互动哟！</p>';
        }else{
            $content .='您是否找到您心仪的招商项目了？在您忙碌的一周里，<a href="'.url::website('').'">一句话</a>的招商者很是挂念您，记得常来<a href="'.url::website('').'">垂直招商平台</a>与您心动项目的招商者互动哟！</p>';
        }
        $content .= '<p class="website"><a href="'.url::website('').'">'.url::website('').'</a></p>
        </div>
        <div class="clear"></div>
        <div class="tishi">('.date("Y/m/d",time()-604800).'至'.date("Y/m/d",time()).') 您收到的名片<span>'.$newcardcount.'张</span></div>
        <div class="email_card">';
        if($usertpye==1 && !empty($cardlist)){//企业收到个人名片信息
            foreach($cardlist as $card){
                $i=0;
                $content .='<div class="card_left">
                <div class="user"><img src="'.URL::imgurl($card->user->user_person->per_photo).'" /></div>
                <div class="text">
                <p>'.$card->user->user_name.'</p>
                <p>投资金额：'.$money_list[$card->user->user_person->per_amount].'</p>
                <p>投资行业：'.$industry[$card->user->user_person->per_industry].'</p>
                <p><span>收到名片时间：'.date("Y/m/d H:i:s",$card->send_time).'</span></p>
                </div>
                <div class="clear"></div>
                </div>';
                $i=$i+1;
                if($i==2) break;
            }
        }else{//个人收到企业名片信息
            if(!empty($cardlist)){
                $i=0;
                foreach($cardlist as $card){
                    $content .='<div class="card_left">
                    <div class="user"><img src="'.URL::imgurl($card['com_logo']).'" /></div>
                    <div class="text">
                    <p>'.$card['com_name'].'</p>
                    <p>项目列表：'.$card['project_brand_name'].'</p>
                    <p>招商金额：'.$card['project_amount_type'].'</p>
                    <p><span>收到名片时间：'.date("Y/m/d H:i:s",$card['send_time']).'</span></p>
                    </div>
                    <div class="clear"></div>
                    </div>';
                    $i=$i+1;
                    if($i==2) break;
                }
            }
        }
        if($usertpye==1){
            $content .= '<div class="clear"></div></div><p class="view_more"><a href="'.url::website('').'company/member/card/receivecard">查看更多收到名片></a></p><p class="bb">祝您在新的一周里项目合作更上一层楼，同时您可主动<a href="'.url::website('').'company/member/investor/search">搜索投资者</a>，增加您与投资者合作的机率！您也可通过<a href="'.url::website('').'company/member/investor/searchConditionsList">查看历史搜索记录</a>了解您感兴趣的投资者！时间就是机遇，赶快行动吧，祝您招商的道路飞速发展！</p>';
        }else{
            $content .= '<div class="clear"></div></div><p class="view_more"><a href="'.url::website('').'person/member/card/receivecard">查看更多收到名片></a></p>祝您在新的一周里项目投资更上一层楼，同时您可主动搜索项目，更精准的找到您心仪的对象！您也可通过查看我收藏的项目了解您曾感兴趣的项目！时间就是机遇，赶快行动吧，祝您投资的道路财运亨通！';
        }
        $content .= ' </div>
        <div class="email_bottom">
        <p>此为系统邮件，请勿回复</p>
        <p>如有任何疑问，可联系我们：<span>400-3989-3456</span></p>
        <p>如您不希望再收到此类邮件，可以点此<a href="'.$url.'">取消订阅</a>，感谢您的支持！</p>
        </div>
        </div></body></html>';
        return $content;
    }

    /**
     * 取消订阅收到新名片邮件提醒功能
     * @author 钟涛
     */
    public function cancelSendEmail($post){
        $result['status'] = false;
        if(arr::get($post,'code') && arr::get($post,'key'))
        {
            $user_id =  $post['key'];
            $validcode = $this->getValidCode($user_id,4);
            $result['status'] = false;
            //验证URL合法性
            if (md5($validcode)==$post['code']){
                $user = ORM::factory("User",$user_id);
                $user->week_send_email = 0;
                try {
                    $result['status'] = $user->update ();
                    $result['user_id'] = $user_id;
                }
                catch ( Kohana_Exception $e ) {
                    return $result;
                }
            }
        }
        return $result;
    }

    /**
     * 取消订阅个人为登录邮件提醒
     * @author 钟涛
     */
    public function cancelSendEmailByPerson($post){
        $result['status'] = false;
        if(arr::get($post,'code') && arr::get($post,'key') && arr::get($post,'unsubscribe_type') && arr::get($post,'unsubscribe_sec_type'))
        {
            $user_id =  $post['key'];
            $validcode = $user_id+1;
            //退订主类型[默认1邮箱退订，2短信退订]
            $unsubscribe_type= arr::get($post,'unsubscribe_type');
            //退订附属类型[默认1个人未登录邮件提醒，2....]
            $unsubscribe_sec_type= arr::get($post,'unsubscribe_sec_type');
            $result['status'] = false;
            //验证URL合法性[用户id+1 md5加密截取前6位]
            if (substr(md5($validcode),0,6)==$post['code']){
                $unsubscribe = ORM::factory("UserUnsubscribe")
                            ->where('user_id','=',$user_id)
                            ->where('unsubscribe_type','=',$unsubscribe_type)
                            ->where('unsubscribe_sec_type','=',$unsubscribe_sec_type)->find();
                if($unsubscribe->loaded()){
                    $unsubscribe->unsubscribe_status=1;
                    $unsubscribe->unsubscribe_time=time();
                    $unsubscribe->update();
                }else{
                    $unsubscribe2 = ORM::factory("UserUnsubscribe");
                    $unsubscribe2->user_id=$user_id;
                    $unsubscribe2->unsubscribe_type=$unsubscribe_type;
                    $unsubscribe2->unsubscribe_sec_type=$unsubscribe_sec_type;
                    $unsubscribe2->unsubscribe_time=time();
                    $unsubscribe2->unsubscribe_status=1;
                    $unsubscribe2->create();
                }
                $result['status'] = true;
                return $result;
            }
        }
        return $result;
    }

    /**
        * 获取注册的会员总数
        * @author 许晟玮
     */
    function getRegUserNum(){
         //$industry=ORM::factory('Industry')->where('industry_status','=',1)->find_all()->as_array();
         $user  = ORM::factory('User')->count_all();
         //print_r ($user);exit;
        return $user;
    }


    /**
     * 判断sid是否合法 TODO 判断sid状态
     * @param unknown $sid
     * @return ORM|boolean
     * @author 龚湧
     */
    public function isSidValid($sid){
        //从缓存中读取
        $cache = Cache::instance("memcache");
        $sids = array();
        $csids = $cache->get("stat_tongji_sids");
        $csids= array();
        if($csids){
            $sids = unserialize($csids);
        }
        else{
            //sapi bi
            $bi_service= new Service_Api_Stat();
            $objs= $bi_service->getSourceAll();
            $data= $objs['data'];
            if( !empty($data) ){
                foreach($data as $obj){
                    $sids[] = $obj;
                }
            }
            $cache->set("stat_tongji_sids", serialize($sids),300);
        }

        if(in_array($sid, $sids)){
            return $sid;
        }
        return false;
    }

    /**
     * 根据email获取个人信息user表
     * @author 周进
     */
    public function getUserFromemail($email){
        $user = ORM::factory('User')->where('email','=',$email)->find();
        return $user;
    }


    /**
     * 创建行业类别
     * @author许晟玮
     */
    public function setProfession( $name ){
        $orm= ORM::factory('UserProfession');
        $orm->profession_name= $name;
        $orm->create();
    }
    //end function

    /**
     * 创建职业类别/名称
     * @author许晟玮
     */
    public function setPosition( $name,$pid=0 ){
        $orm= ORM::factory('UserPosition');
        $orm->position_name= $name;
        $orm->position_pid= $pid;
        $return= $orm->create();
        return $return;
    }
    //end function

    /**
     * 获取所有行业类别
     * @author许晟玮
     */
    public function getProfessionAll(){
        $orm= ORM::factory('UserProfession');
        $result= $orm->find_all()->as_array();
        return $result;
    }
    //end function

    /**
     *获取对应的职业类型/名称
     *@author许晟玮
     */
    public function getPosition( $pid=0 ){
        $orm= ORM::factory('UserPosition');
        $orm->where('position_pid', '=', $pid);
        $result= $orm->find_all()->as_array();
        return $result;


    }
    //end function

    /**
     * 获取单条行业类别信息
     * @author许晟玮
     */
    public function getProfessionRow( $id ){
        $orm= ORM::factory('UserProfession',$id);
        if( $orm->loaded()===false ){
            return false;
        }else{
            return $orm->as_array();
        }

    }
    //end function

    /**
     *获取单条职业类型/名称数据
     *@author许晟玮
     */
    public function getPositionRow( $id=0 ){
        $orm= ORM::factory('UserPosition',$id);
          if( $orm->loaded()===false ){
              return false;
          }else{
              return $orm->as_array();
          }
    }
    //end function

    /**
     * @sso
     * 修改会员邮箱
     * @author许晟玮
     */
    public function editUserEmail( $uid,$email ){

        $res= Service_Sso_Client::instance()->updateEmailInfoById( $uid,array( 'email'=>$email ) );
        if( $res===false ){
            return false;
        }else{
            return true;
        }
    }
    //end function

    /**
     * 记录修改邮箱的日志
     * @author许晟玮
     */
    public function setEditEmailLog($uid,$open_status='0',$email_status='1'){
        $orm= ORM::factory('UserEmailStatus');
        try{
            $orm->user_id= $uid;
            $orm->open_status= $open_status;
            $orm->email_status= $email_status;
            $orm->act_datetime= time();
            $orm->act_ip= Request::$client_ip;
            $orm->create();
        }catch(Kohana_Exception $e){
            return false;
        }
        return true;
    }
    //end function

    /**
     * 判断是否修改过邮箱 true false
     * @author许晟玮
     */
    public function getEditEmailLog($uid){
        $orm= ORM::factory('UserEmailStatus');
        $count= $orm->where('user_id', '=', $uid)->where('email_status','>','0')->count_all();
        if( $count>0 ){
            return true;
        }else{
            return false;
        }
    }
    //end function

    /**
     * 判断是否需要弹出修改邮箱框
     * @author 许晟玮
     */
    public function openEditEmailEof( $uid ){
        $orm= ORM::factory('UserEmailStatus');
        $cus= $orm->where('user_id', '=', $uid)->count_all();
        if( $cus==0 ){
            return true;
        }

        $count= $orm->where('user_id', '=', $uid)->where('open_status','>','0')->count_all();
        if( $count>0 ){
            //需要弹出
            return true;
        }else{
            //不弹出
            return false;
        }


    }
    //end function

    /**
     * @sso
     * 解除邮箱绑定
     * @author 许晟玮
     * @param int $user_id
     * @return boolean
     */
    public function unbindEmail($user_id){

        $res= Service_Sso_Client::instance()->updateEmailInfoById( $user_id,array( 'valid_status'=>'0' ) );
        if( $res===false ){
            return false;
        }else{
            return true;
        }
    }
    //end functioin

    /**
     *修改密码
     *@author 许晟玮
     */
    public function editUserPsd( $uid,$psd,$email ){

        $res= Service_Sso_Client::instance()->resetPassword( $uid,$psd );
        if( $res===false ){
            return false;
        }else{
            return true;
        }
    }
    //end function

    /**
     *获取修改邮箱的日志次数
     * @author许晟玮
     */
    public function getEditEmailCount( $uid ){
        if( ceil( $uid )==0  ){
            return false;
        }
        $orm= ORM::factory('UserEmailStatus');
        $orm->where('user_id', '=', $uid);
        $count= $orm->count_all();
        return $count;

    }
    //end function

    /**
     * 企业中心【为你推荐】20位投资者
     * [根据项目的1级行业]
     * 2013-09-22
     * @author 钟涛
     */
    function getRecommendPerson($com_id){
        if(intval($com_id)){//企业用户
            //根据用户id获取我的项目
            $model = ORM::factory('Project');
            //获取我最新一个审核通过的项目
            $myprojectlist = $model->where('com_id', '=', $com_id)->where('project_status', '=', 2)->limit(1)->order_by('project_updatetime','DESC')->find_all();
            $proid=0;
            if(count($myprojectlist)){
                foreach ($myprojectlist as $v){
                    $proid=$v->project_id;
                }
            }else{//没有审核通过的项目，不推荐
                return array();
            }
            //1级行业id 默认1
            $one_id=1;
            if($proid){
                //找到我的项目的一级行业的id
                $p_industry= ORM::factory('Projectindustry')->where("project_id", "=",$proid)->find_all();
                if(count($p_industry)){
                    foreach ($p_industry as $ve){
                        $industry= ORM::factory("industry",$ve->industry_id);
                        if($industry->parent_id==0){//1级行业
                            $one_id=$ve->industry_id;
                        }
                    }
                }
            }
            $peruseridarr=array();
            //根据项目的一级行业去找推荐的投资者
            $perindustrlist= ORM::factory('UserPerIndustry')->where('parent_id','=',$one_id)->group_by('user_id')->limit(50)->find_all( );
            if(count($perindustrlist) && count($perindustrlist)>=20){//找到的投资者超过20位
                foreach($perindustrlist as $v){
                    $peruseridarr2[]=$v->user_id;
                }
                $peruseridarrkey = array_rand($peruseridarr2,20);//随机获取20条数据
                foreach ($peruseridarrkey as $key){
                    $peruseridarr [] = $peruseridarr2[$key];
                }
            }elseif(count($perindustrlist)>0){//找到了但不够20位
                foreach($perindustrlist as $v){
                    $peruseridarr2[]=$v->user_id;
                }
                $other_user=20-count($peruseridarr2);//还差的人数，不够20个，只能随机找几个凑数了
                $permodel= ORM::factory('Personinfo')->where('per_open_stutas','=',1)->where('per_user_id','not in',$peruseridarr2)->limit($other_user)->find_all( );
                foreach($permodel as $v){
                    $peruseridarr2[]=$v->per_user_id;
                }
                $peruseridarr= $peruseridarr2;//推荐的
            }else{//一个都没找到啊那不推荐了
                return array();
            }
            if(count($peruseridarr)>10){//最终找到了20位投资者，推荐去吧
                $permodel2= ORM::factory('Personinfo')->where('per_user_id','>',0)->where('per_user_id','in',$peruseridarr)->limit(20)->find_all( );
                $retrunvalue=$this->getPersonCommon($permodel2);
                return $retrunvalue;
            }else{//还不够20个？？数据不足，不推荐
                return array();
            }
        }
        return array();
    }

    /**
     * 企业中心【猜你喜欢】20位投资者
     * [根据企业查看名片的最后一位投资者的投资金额]
     * 2013-09-22
     * @author 钟涛
     */
    function getGuessPerson($com_user_id){
        if(intval($com_user_id)){//企业用户
            //根据用户id查找查看名片记录
            $cardlist= ORM::factory('Cardinfobehaviour')->where('user_type','=',1)->where('user_id','=',$com_user_id)->where('status','=',9)->limit(1)->order_by('add_time','DESC')->find_all();
            //投资金额
            $project_amount_type=0;
            if(count($cardlist)){
                foreach ($cardlist as $v){
                    $perinfo=ORM::factory('Personinfo')->where('per_user_id','=',$v->second_user_id)->find( );
                    $project_amount_type=$perinfo->per_amount;
                }
            }
            if($project_amount_type==0){//没有查看过名片或查看名片的投资者没有意向投资金额，那都不猜咯
                return array();
            }
            $peruseridarr=array();
            //根据项目的投资金额去猜测20个投资者
            $permodellist= ORM::factory('Personinfo')->where('per_amount','=',$project_amount_type)->where('per_open_stutas','=',1)->limit(50)->find_all( );
            if(count($permodellist) && count($permodellist)>=20){//找到的投资者超过20位
                foreach($permodellist as $v){
                    $peruseridarr2[]=$v->per_user_id;
                }
                $peruseridarrkey = array_rand($peruseridarr2,20);//随机获取20条数据
                foreach ($peruseridarrkey as $key){
                    $peruseridarr [] = $peruseridarr2[$key];
                }
            }elseif(count($permodellist)>0){//找到了但不够20位
                foreach($permodellist as $v){
                    $peruseridarr2[]=$v->per_user_id;
                }
                $other_user=20-count($peruseridarr2);//还差的人数，不够20个，只能随机找几个凑数了
                $permodel= ORM::factory('Personinfo')->where('per_open_stutas','=',1)->where('per_user_id','not in',$peruseridarr2)->limit($other_user)->find_all( );
                foreach($permodel as $v){
                    $peruseridarr2[]=$v->per_user_id;
                }
                $peruseridarr= $peruseridarr2;//猜测的
            }else{//一个都没找到啊，那不猜咯
                return array();
            }
            if(count($peruseridarr)>10){//最终找到了20位投资者，猜测喜欢的人有啦
                $permodel2= ORM::factory('Personinfo')->where('per_user_id','>',0)->where('per_user_id','in',$peruseridarr)->limit(20)->find_all( );
                $retrunvalue=$this->getPersonCommon($permodel2);
                return $retrunvalue;
            }else{//还不够20个？？数据不足，不猜咯
                return array();
            }
        }
        return array();
    }

    /**
     * 企业中心【最活跃会员】20位投资者
     * [月活跃度最高的20位投资者]
     * 2013-09-22
     * @author 钟涛
     */
    function getHuoyueduPerson(){
        $memcache = Cache::instance ( 'memcache' );
        $memcahcename='getHuoyueduPerson2';
        if($memcache->get($memcahcename)){
            return $memcache->get($memcahcename);
        }else{
            //走搜索引擎 找到月活跃度最高的20为投资者
            $Search1 = new Service_Api_Search ();
            $searchresult = $Search1->getSearchByInvestor ( '*:*', '', 0, 'vitality desc' ,2);
            $peruseridarr=$searchresult['matches'];
            if(count($peruseridarr)){
                $permodel= ORM::factory('Personinfo')->where('per_user_id','in',$peruseridarr)->limit(20)->find_all( );
            }else{
                $permodel= ORM::factory('Personinfo')->where('per_amount','>',0)->where('per_user_id','>',0)->limit(20)->find_all( );
            }
            $retrunvalue=$this->getPersonCommon($permodel);
            $retrunvalue=common::multi_array_sort($retrunvalue,'this_huoyuedu',SORT_DESC);
            if($retrunvalue){
                $memcache->set($memcahcename,$retrunvalue,7200);//两小时
            }
            return $retrunvalue;
        }
    }

    /**
     * 企业中心【新加入会员】20位投资者
     * [最新完善基本信息的20位投资者]
     * 2013-09-22
     * @author 钟涛
     */
    function getNewPerson(){
        $memcache = Cache::instance ( 'memcache' );
        $memcahcename='getNewPerson1';
        if($memcache->get($memcahcename)){
            return $memcache->get($memcahcename);
        }else{
            $permodel= ORM::factory('Personinfo')->where('per_amount','>',0)->where('per_open_stutas','!=',3)->limit(20)->order_by('per_createtime','DESC')->find_all( );
            $retrunvalue=$this->getPersonCommon($permodel);
            if($retrunvalue){
                $memcache->set($memcahcename,$retrunvalue,7200);//两小时
            }
            return $retrunvalue;
        }
    }

    /**
     * 企业中心推荐投资者 公共返回信息
     * 2013-09-22
     * @author 钟涛
     */
    function getPersonCommon($permodel=array()){
        if(count($permodel)){
            $retrunvalue=array();
            $moneyarr=common::moneyArr();
            $perser=new Service_Platform_SearchInvestor();
            $per_service = new Service_User_Person_User();
            foreach($permodel as $k=>$v){
                $retrunvalue[$k]['user_id']=$v->per_user_id;
                $retrunvalue[$k]['per_photo']=$v->per_photo?$v->per_photo:'http://pic.yjh.com/user_icon/plant/default_icon_11.jpg';
                $retrunvalue[$k]['per_gender']=$v->per_gender;
                $retrunvalue[$k]['per_realname']=$v->per_realname?$v->per_realname:'匿名';
                $retrunvalue[$k]['this_huoyuedu']=$perser->getAllScore($v->per_user_id);//活跃度
                //个人1级行业
                $retrunvalue[$k]['this_per_industry']=$per_service->getPerasonalParentIndustry($v->per_user_id,2);
                //意向投资地区
                $area= $per_service->getPerasonalAreaStringOnlyPro($v->per_user_id);
                //$area=$per_service->getPersonalArea($v->per_user_id);
                if($area){
                    $retrunvalue[$k]['this_per_area']=$area;
                }else{
                    $retrunvalue[$k]['this_per_area']='无';
                }
                //意向投资金额
                $retrunvalue[$k]['per_amount']=arr::get($moneyarr,$v->per_amount,'无');
            }
            return $retrunvalue;
        }else{
            return array();
        }
    }



}

