<?php
defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * 用户注册登录 退出登录 公共部分 各种类型用户通用
 *
 * @author 龚湧
 *
 */
class Controller_User_User extends Controller_Template {
    private $_cache_get_project_total = 'getProjectTotal';
    private $_cache_get_user_total = 'getUserTotal';
    private $_cache_get_total_time = 86400;

    /**
     * 用户模板设置
     */
    public $template = 'template';

    /**
     * 判断用户类型做调整，现以企业中户
     *
     * @author 龚湧
     */
    public function action_index() {
        $user = $this->userInfo ();
        $this->userType ( $user->user_type );
    }

    /**
     * 用户注册
     *
     * @author 龚湧
     */
    public function action_register() {
        // 用户注册初始化
        $service_prev = new Service_User_PrevioReg ();
        $hash_id = $service_prev->genHashId ();

        $query = Arr::map ( "HTML::chars", $this->request->query () );
        $post = Arr::map ( "HTML::chars", $this->request->post () );
        // 记录前面点击过来的一个页面地址
        $url = parse_url ( $this->request->referrer () );
        setcookie ( 'cookie_reg_url_refer', arr::get ( $url, 'host', ' ' ) );

        $loginout = Arr::get ( $query, 'loginout', '' );
        if ($loginout == true) { // 退出登录
                                 // @sso 退出登录修改
            Service_Sso_Client::instance ()->logout ( Cookie::get ( "authautologin" ) );
        }
        // 判断是否已登录
        if ($this->loginUser ()) {
            $userinfo = $this->userInfo ();
            $this->userType ( $userinfo->user_type );
        }
        // 定义注册类型
        $type = array (
                "com" => 'com_register', // 企业
                "per" => 'per_register', // 个人
                'gov' => 'gov_register'  // 政府
                );
        $form = '';
        $templae = "user/" . Arr::get ( $type, Arr::get ( $query, "type" ), $type ['per'] ); // 默认页面为个人用户注册

        $content = View::factory ( $templae );
        if ($this->request->method () == HTTP_Request::POST) {
            $form = $post;
            $type = arr::get ( $query, 'type' );
            if ($type == "per") {
                $form ["user_type"] = '2';
            } else {
                $form ["user_type"] = '1';
            }
            $error = array ();
            // 验证码检查
            $valid_code = Captcha::valid ( $form ['valid_code'] );

            // 如果输入的是手机号，并且手机验证码是正确的，则注册类型为手机
            if (is_numeric ( $form ['email'] ) === true && strlen ( $form ['email'] ) == 11) {
                $mcs = new Service_User_MobileCodeLog ();
                $mrs = $mcs->getCodeEof ( $form ['email'], $form ['phoneCode'] );
                if ($mrs === true) {
                    $type = 'mobile';
                    $valid_code = true;
                } else {
                    $valid_code = false;
                }
            } else {
                $type = 'email';
            }

            if (! $service_prev->lastHash ( $hash_id ) and $form ['user_type'] == 2) {
                $error = array (
                        'reg_error' => "服务器忙不过来，请先喝杯茶吧"
                );
            } elseif ($valid_code) {
                $service = new Service_User ();

                // 返回注册用户ORM对象，为空则创建用户成功
                $user = $service->createUser ( $form, $type );
                if ($user) {
                    if ($user->id) {
                        // 邀请注册
                        if (Cookie::get ( 'cookie_user_inviter_id' ) && Cookie::get ( 'cookie_user_inviter_type' )) {
                            $service->userInviter ( Cookie::get ( 'cookie_user_inviter_id' ), $user->id, Cookie::get ( 'cookie_user_inviter_type' ) );
                        }
                        // 增加用户注册统计
                        $stat_service = new Service_Api_Stat ();
                        $sid_md5 = arr::get ( $_COOKIE, 'Hm_lvqtz_sid' );
                        $stat_service->setUserRegStat ( $user->id, $user->user_type, $user->reg_time, arr::get ( $_COOKIE, 'Hm_lvqtz_refer' ), $sid_md5 );

                        // 增加用户注册积分(注册为通用的，暂时放在company下，以后可能要做用户类型判断)
                        $points = Service::factory ( "User_Company_Points" );
                        $points->getPointsOnce ( $user->id, 'register' );
                        // end 增加用户积分

                        $user_type = $user->user_type;
                        if ($user_type == 2) { // 个人用户添加活跃度by钟涛
                            $ser1 = new Service_User_Person_Points ();
                            $ser1->addPoints ( $user->id, 'register' ); // 注册
                        }
                        // 注册日志
                        $service_prev->createPrev ();

                        // captcha_response
                        Session::instance ()->delete ( 'captcha_response' );
                        // 邮箱注册，注册完后先到邮件提示页
                        if ($type == 'email') {
                            // 发送验证邮件by周进
                            $mailservice = new Service_User_Company_User ();
                            $mailservice->updateCheckValidEmail ( $user->id, $user->email );
                            if ($user_type == 1) {
                                self::redirect ( "/member/comshowemail" ); // 企业
                            } elseif ($user_type == 2) {
                                self::redirect ( "/member/pershowemail" ); // 个人
                            } else {
                                self::redirect ( "/member/showemail" ); // 默认老版本的
                            }
                        }
                        // 手机注册，完成后跳转到引导页
                        if ($type == 'mobile') {

                            if ($form ["user_type"] == '1') {
                                self::redirect ( "/member/comlead" );
                            }

                            if ($form ["user_type"] == '2') {
                                // 用户邀请验证
                                $user_service = new Service_User ();
                                $user_service->changeUserChanceForVoild ( $user->id );
                                self::redirect ( "/member/userlead" );
                            }
                        }
                    }
                } else {
                    self::redirect ( $this->request->referrer () );
                }
            } else {
                $error = array (
                        'valid_code' => "验证码错误"
                );
            }
            $content->error = $error;
        }
        $this->template->content = $content;
        $this->template->content->form = $form;
        if (Arr::get ( $query, 'type' ) == "com")
            $this->template->title = '企业用户注册|投资赚钱好项目，一句话的事。';
        else
            $this->template->title = '个人用户注册|投资赚钱好项目，一句话的事。';
    }

    /**
     * 用户登录验证
     *
     * @author 曹怀栋
     */
    public function action_login() {
        //如果登录的cookie不存在，就删除sessioin
        $login_cookie= Cookie::get('authautologin');
        if( $login_cookie==null || $login_cookie=='' ){
            //删除session
            //echo 'a';
            Session::instance()->delete('authautologin');
        }

        $memcache = Cache::instance ( 'memcache' );
        // print_r ($memcache);
        // 总项目数
        try {
            $platform_num = $memcache->get ( $this->_cache_get_project_total );
        } catch ( Cache_Exception $e ) {
            $platform_num = 0;
        }

        if ($platform_num == 0) {
            $browing = new Service_Platform_Browsing ();
            $platform_num = $browing->getProjectCount ();
            $memcache->set ( $this->_cache_get_project_total, $platform_num, $this->_cache_get_total_time );
        } else {
        }

        // 总用户数
        try {
            $user_num = $memcache->get ( $this->_cache_get_user_total );
        } catch ( Cache_Exception $e ) {
            $user_num = 0;
        }
        if ($user_num == 0) {
            $service_user = new Service_User ();
            $user_num = $service_user->getRegUserNum ();
            $memcache->set ( $this->_cache_get_user_total, $user_num, $this->_cache_get_total_time );
        } else {
        }

        if ($this->request->method () == HTTP_Request::POST) {
            $post = Arr::map ( "HTML::chars", $this->request->post () );
            $p_email = secure::secureInput ( secure::secureUTF ( $this->request->post ( 'email' ) ) );
            // 登录验证
            $service = new Service_User ();
            $result = $service->loginCaptcha ( $post );

            if ($result != 1) {
                $content = View::factory ( 'user/login' );
                $content->platform_num = $platform_num;
                $content->user_num = $user_num;

                $content->error = $result;
                $content->emails = $p_email;

                $this->template->content = $content;
            } else {
                $last_login_user_status = ORM::factory ( "User", $this->loginUserId () );


                $user ['user_id'] = $this->loginUserId ();
                $user ['last_logintime'] = time ();
                $user ['last_login_ip'] = ip2long ( Request::$client_ip );

                // 用户信息更新
                $client= Service_Sso_Client::instance();
                $rus= $client->getUserInfoById($this->loginUserId ());

                    $session = Session::instance ();
                    if( $rus->user_name!='' ){
                        $session->set ( "username", $rus->user_name );
                    }
                    if( $rus->user_name=='' && $rus->email!='' ){
                        $session->set ( "username", $rus->email );
                    }
                    if( $rus->user_name=='' && $rus->email=='' && $rus->mobile!='' ){
                        $session->set ( "username", $rus->mobile );
                    }
                    if( $rus->user_name=='' && $rus->email=='' && $rus->mobile=='' ){
                        $session->set ( "username", '尊敬的会员您好' );
                    }


                $upldate = $service->updateUser ( $user );



                $usertype = $rus->user_type;

                $this->addUserLoginLog ( $user ['user_id'], $usertype );
                if ($usertype == 2) { // 个人用户添加活跃度by钟涛
                    $ser1 = new Service_User_Person_Points ();
                    $ser1->addPoints ( $user ['user_id'], 'login' ); // 每日用户登录
                    $info_service = new Service_User_Person_User();
                    $ipf= $info_service->getPerson($user ['user_id']);
                    if( $ipf->per_id!='' && $ipf->per_realname!='' ){
                        if( $ipf->per_realname != $rus->user_name )	{
                            //update sso user_name
                            $client->updateBasicInfoById($user ['user_id'], array('user_name'=>$ipf->per_realname));
                        }

                           if( $ipf->per_photo!=$rus->user_portrait ){
                               $client->updateBasicInfoById($user ['user_id'], array('user_portrait'=>$ipf->per_photo));

                           }
                    }

                }else{
                    $info_service = new Service_User_Company_User();
                    $icf= $info_service->getCompanyInfo($user ['user_id']);
                    if( $icf->com_id!='' && $icf->com_name!='' ){
                        if( $icf->com_name!=$rus->user_name ){
                            $client->updateBasicInfoById($user ['user_id'], array('user_name'=>$icf->com_name));
                        }
                        if( $icf->com_logo!=$rus->user_portrait ){
                            $client->updateBasicInfoById($user ['user_id'], array('user_portrait'=>$icf->com_logo));

                        }
                    }


                }
                // 上次跳转过来的地址
                $to_url = $this->request->query ( "to_url" );
                //整合
                $content = View::factory ( 'user/login' );
                $content->type = $to_url;
                $content->remember= Arr::get($post, 'remember');
                $this->template->content = $content;
                // 登录成功跳转,yamasa注释掉了,875登录跳转
               //$this->userType ( $usertype, $to_url );
            }
        } else {

            if ($this->loginUser ()) { // 已经登录的用户，自己跳转
                $userinfo = $this->userInfo ();
                $this->userType ( $userinfo->user_type );
            } else {
                $content = View::factory ( 'user/login' );
                // $content = View::factory('platform/home/comcenter');
                $content->platform_num = $platform_num;

                $content->user_num = $user_num;
                $this->template->content = $content;
            }
        }
        $this->template->title = "会员登录_一句话";
        $this->template->keywords = "会员登录";
        $this->template->description = "会员登录提示你：找项目、预约投资者、留言从这里开始。";
    }

    /**
     * 退出登录
     *
     * @author 龚湧
     *         2013-11-4 下午1:21:18
     */
    public function action_logout() {

        // 上次跳转过来的地址

        $to_url = Arr::get ( $this->request->query (), 'to_url' );
        //整合
        //$content = View::factory ( 'user/logout' );
        //$content->type = $to_url;
        //$this->template->content = $content;
        //$token = Cookie::get ( "authautologin" );
        //$service = Service_Sso_Client::instance ()->logout ( $token );
        /*
         * if ($to_url == '') { self::redirect ( "member/login" ); } else { self::redirect ( $to_url ); }
         */

         self::redirect("member/login");
    }

    /**
     * 忘记密码(第一步)
     *
     * @author yamasa
     */
    public function action_forgetPassword() {
        $session = Session::instance ();
        $username = $session->get ( 'username' );
        // var_dump($session->as_array());
        $login = cookie::get ( 'authautologin' );
        /*
         * $ajax=new Controller_Ajaxcheck("",""); $ajax->action_updatePersonMsg();
         */

        $get = Arr::map ( "HTML::chars", $this->request->query () );
        if ($this->request->method () == HTTP_Request::POST) {

            $sessionmobile = $session->get ( 'sessionmobile' );
            $post = Arr::map ( "HTML::chars", $this->request->post () );
            if ($sessionmobile && ! isset ( $post ['newPassword'] )) {
                $content = View::factory ( 'user/forgetpassword5' );
                $this->template->content = $content;
            } else if ($sessionmobile && isset ( $post ['newPassword'] )) {
                $client = Service_Sso_Client::instance ();
                $userinfo = $client->getUserInfoByMobile ( $sessionmobile );
                $result = $client->resetPassword ( $userinfo->id, $post ['newPassword'] );
                if ($result) {
                    $session->delete ( 'sessionmobile' );
                    self::redirect ( 'member/forgetPassword?suc=yes' );
                }
            } else {
                if (! isset ( $post ['login_name'] ))
                    exit ();
                if (strstr ( $post ['login_name'], "@" )) {

                    $content = View::factory ( 'user/forgetpassword3' );
                    $content->email = $post ['login_name'];
                    $hosts = explode ( "@", $post ['login_name'] );
                    $hosts = "mail." . $hosts [1];
                    $content->hosts = $hosts;
                    $service = new Service_User ();
                    $result = $service->sendMailPassword ( $post ['login_name'] );

                    $this->template->content = $content;
                } else {
                    $content = View::factory ( 'user/forgetpassword4' );
                    $content->mobile = $post ['login_name'];
                    $this->template->content = $content;
                }
            }
            /*
             * $content = View::factory('user/forgetpassword2'); $this->template->content = $content; $post = Arr::map("HTML::chars", $this->request->post()); if(!Captcha::valid($post['valid_code'])){ $error = array('captcha'=>"验证码错误"); }else{ //验证email是否存在 $service = new Service_User(); $result = $service->forgetPasswordEmail(arr::get($post,'email')); if($result != 1) $error = $result; } if(!isset($error)){ //发送找回密码邮件 $result = $service->sendMailPassword(arr::get($post,'email')); if($result !== true){ $error = array('email'=>"邮件发送失败！"); }else{ $emailurl = explode('@', arr::get($post,'email')); $content = View::factory('user/sendforgetpassword'); $this->template->content = $content; $content->emails = arr::get($post,'email'); $content->toemailurl ="http://mail.".$emailurl[1]; } }else{ $content->error = $error; }
             */
        } else if (isset ( $get ['suc'] )) {
            if ($get ['suc'] == 'yes') {
                $content = View::factory ( 'user/forgetpassword6' );
                $ref = Cookie::get ( 'forgetpassref' );
                if($ref=='' || strstr(URL::website(''),$ref)){
                    $ref=URL::website('')."/member/login";
                }else{
                    $ref="http://".$ref."/user/login";
                }
                $content->ref = $ref;
                $this->template->content = $content;
            } else {
                self::redirect ( 'member/forgetPassword' );
            }
        } else {
            if (isset ( $_SERVER ['HTTP_REFERER'] )) {
                $http_ref = explode ( '/', $_SERVER ['HTTP_REFERER'] );
                Cookie::set ( 'forgetpassref', $http_ref [2], 0, Cookie::$path );
            }
            $content = View::factory ( 'user/forgetpassword2' );
            $this->template->content = $content;
        }
        $this->template->username = $username;
        $this->template->login = $login;
        $this->template->title = "忘记密码_找回密码 - 一句话";
        $this->template->keywords = "忘记密码,一句话,找回密码";
        $this->template->description = "忘记密码，你可以通过输入您的邮箱地址，找回密码。";
    }

    /**
     * 查看邮件和重设密码（忘记密码）(第二步)
     *
     * @author 曹怀栋
     */
    public function action_sendForgetPassword() {
        $content = View::factory ( 'user/sendforgetpassword' );
        $this->template->content = $content;
    }

    /**
     * 邮件找回密码修改(第三步)
     *
     * @author 曹怀栋
     */
    public function action_passwordModification() {
        $content = View::factory ( 'user/passwordmodification' );
        $login = cookie::get ( 'authautologin' );
        $this->template->content = $content;
        $this->template->login = $login;
        $get = Arr::map ( "HTML::chars", $this->request->query () );

        // 检查email和uid 是否匹配,edit by 许晟玮
        $client = Service_Sso_Client::instance ();
        $info = $client->getUserInfoByEmail ( Arr::get ( $get, 'email' ) );
        if ($info === false) {
            self::redirect ( "/member/forgetpassword" );
        }
        if ($info->id != Arr::get ( $get, 'sud' )) {
            self::redirect ( "/member/forgetpassword" );
        }
        // edit end

        // 邮件找回密码修改验证
        $service = new Service_User ();
        $result = $service->passwordModification ( $get );
        if ($result === true) {
            $content->emails = arr::get ( $get, 'email' );
            $content->uid = common::encodeMoible(arr::get ( $get, 'sud' ));

        } else {
            // 链接失效，回到第二步
            $content = View::factory ( 'user/pwdFail' );
            $this->template->content = $content;
            $content->text = '找回密码链接已失效!';
        }
    }

    /**
     * 邮件找回密码成功(第四步)
     *
     * @author 曹怀栋
     */
    public function action_passwordSuccess() {
        $content = View::factory ( 'user/passwordsuccess' );
        $ref = Cookie::get ( 'forgetpassref' );
        if($ref=='' || strstr(URL::website(''),$ref)){
            $ref=URL::website('')."/member/login";
        }else{
            $ref="http://".$ref."/user/login";
        }
        $content->ref = $ref;
        $this->template->content = $content;
        $post = Arr::map ( "HTML::chars", $this->request->post () );
        $service = new Service_User ();
        $result = $service->passwordSuccess ( $post );

        // 修改密码是否成功
        if ($result != 1) {
            self::redirect ( "/member/forgetpassword" );
        } else {
            // edit true ,set edit email log
        }
    }

    /**
     * @sso
     * 验证已经发送的邮件url确认
     *
     * @author 周进 update @ 2012/12/6新增验证后自动登录到相应类型的用户中心
     */
    public function action_checkvEmail() {
        $status = false;
        $post = Arr::map ( "HTML::chars", $this->request->query () );
        $service = new Service_User ();
        $result = $service->editValidEmail ( $post );

        if ($result ['status'] == false) {
            /* 添加错误提示页面 */
            $data = "?";
            foreach ( $result as $k => $v )
                $data .= $k . "=" . $v . "&";
            self::redirect ( "/platform/index/showemailfail/" . $data );
            exit ();
        } else {

            // 通过邮箱验证后自动登录
            $userinfo = Service_Sso_Client::instance ()->getUserInfoById ( $result ['user_id'] );
            // $userinfo = ORM::factory("User",$result['user_id']);
            // 企业用户的处理，增加积分和诚信点
            if ($userinfo->user_type == 1) {
                // 增加邮箱通过验证后的积分
                $points = Service::factory ( "User_Company_Points" );
                $points->getPointsOnce ( $result ['user_id'], "valid_email" );

                // 邮箱通过验证增加诚信点
                $integrity = Service::factory ( "User_Company_Integrity" );
                if ($integrity->getIntegrityOnce ( $result ['user_id'], "valid_email" )) {
                    // 获取诚信点推送消息
                    // $msg_service = new Service_User_Ucmsg();
                    // $msg_service->pushMsg($result['user_id'], "company_integrity", "您已经验证邮箱，增加30点诚信指数。",URL::website("company/member/basic/integrity"));
                    $smsg = Smsg::instance ();
                    // 内部消息发送
                    $smsg->register ( "tip_company_integrity", 					// 我的诚信
                    Smsg::TIP, 					// 类型
                    array (
                            "to_user_id" => $result ['user_id'],
                            "msg_type_name" => "company_integrity",
                            "to_url" => URL::website ( "company/member/basic/integrity" )
                    ), array (
                            "code" => "30",
                            "type" => "email"
                    ) );
                }
            }
            // 个人用户添加活跃度 by钟涛
            if ($userinfo->user_type == 2) {
                $ser1 = new Service_User_Person_Points ();
                $ser1->addPoints ( $userinfo->id, 'valid_email' ); // 邮箱通过审核
            }

            // $login = Auth::instance();
            // $login->set_cookie($userinfo);
            // 自动登录处理
            Service_Sso_Client::instance ()->autoLogin ( $userinfo->id );

            $es = Arr::get ( $post, 'es', '0' );
            $tzdf = Arr::get ( $post, 'tzdf' );
            if ($tzdf == '') {
                switch ($userinfo->user_type) {
                    case 1 :
                        // company
                        if ($es == md5 ( $result ['user_id'] . '0' )) {
                            // edit email url
                            self::redirect ( "company/member/basic/editemailc" );
                        } else {
                            self::redirect ( "member/comlead" );
                        }
                        break;
                    case 2 :
                        // person
                        if ($es == md5 ( $result ['user_id'] . '0' )) {
                            // edit email url
                            self::redirect ( "person/member/basic/editemailc" );
                        } else {
                            self::redirect ( "member/userlead" );
                        }
                        break;
                    case 3 :
                        if ($es == md5 ( $result ['user_id'] . '0' )) {
                            // edit email url
                            self::redirect ( "company/member/basic/editemailc" );
                        } else {
                            self::redirect ( "member/comlead" );
                        }

                        break;
                    default :
                        self::redirect ( "/" );
                        break;
                }
            } elseif ($tzdf == md5 ( '2' )) {
                self::redirect ( "person/member/basic/setpassword?type=email" );
            } else {
            }

            // $this->userType($userinfo->user_type);
        }
    }

    /**
     * 注册完后跳转到邮箱发送过度页面
     */
    public function action_showemail() {
        $this->isLogin ();
        $content = View::factory ( 'user/showemail' );
        $this->template->content = $content;
        $content->email = $this->userInfo ()->email;
        $toemailurl = explode ( '@', $this->userInfo ()->email );
        if ($toemailurl [1] == "gmail.com") {
            $content->toemailurl = "http://mail.google.com/";
        } else {
            $content->toemailurl = "http://mail." . $toemailurl [1];
        }
        $get = Arr::map ( "HTML::chars", $this->request->query () );
        $type = Arr::get ( $get, 'type' );
        if ($type == 'ed') {
            $content->acttxt = '修改';
        }

       $content->script = '';

        // post
        if ($this->request->method () == HTTP_Request::POST) {
            $post = $this->request->post ();

            $mobile = Arr::get ( $post, 'mobile' );
            $adf_code = Arr::get ( $post, 'usdf_code' );
            // 验证
            $service = new Service_User_MobileCodeLog ();
            $res = $service->getCodeEof ( $mobile, $adf_code );
            if ($res === true) {
                // set mobile
                $client = Service_Sso_Client::instance ();
                $rmo = $client->setUserMobileById ( $this->userId (), $mobile, 1 );
                if ($rmo === true) {
                    $rs_user = $this->userInfo ( true );
                    // com user type
                    if ($rs_user->user_type == '1') {
                        self::redirect ( 'member/comlead' );
                    }
                    // per user type
                    if ($rs_user->user_type == '2') {
                        self::redirect ( 'member/userlead' );
                    }
                    // self::redirect('/member/ ');
                }
            } else {
                // false
                self::redirect ( '/member/showemail?er=' . urlencode ( '验证码错误' ) );
            }
        }
        // end function
    }

    /**
     * 企业注册完后跳转到邮箱发送过度页面
     */
    public function action_comshowemail() {
        $this->isLogin ();
        $content = View::factory ( 'user/showemail' );
        $this->template->content = $content;
        $content->email = $this->userInfo ()->email;
        $toemailurl = explode ( '@', $this->userInfo ()->email );
        if ($toemailurl [1] == "gmail.com") {
            $content->toemailurl = "http://mail.google.com/";
        } else {
            $content->toemailurl = "http://mail." . $toemailurl [1];
        }
        $get = Arr::map ( "HTML::chars", $this->request->query () );
        $type = Arr::get ( $get, 'type' );
        if ($type == 'ed') {
            $content->acttxt = '修改';
        }

                    $content->script = '';

        // post
        if ($this->request->method () == HTTP_Request::POST) {
            $post = $this->request->post ();

            $mobile = Arr::get ( $post, 'mobile' );
            $adf_code = Arr::get ( $post, 'usdf_code' );
            // 验证
            $service = new Service_User_MobileCodeLog ();
            $res = $service->getCodeEof ( $mobile, $adf_code );
            if ($res === true) {
                // set mobile
                $client = Service_Sso_Client::instance ();
                $rmo = $client->setUserMobileById ( $this->userId (), $mobile, 1 );
                if ($rmo === true) {
                    $rs_user = $this->userInfo ( true );
                    // com user type
                    if ($rs_user->user_type == '1') {
                        self::redirect ( 'member/comlead' );
                    }
                    // per user type
                    if ($rs_user->user_type == '2') {
                        self::redirect ( 'member/userlead' );
                    }
                    // self::redirect('/member/ ');
                }
            } else {
                // false
                self::redirect ( '/member/showemail?er=' . urlencode ( '验证码错误' ) );
            }
        }
        // end function
    }

    /**
     * 个人注册完后跳转到邮箱发送过度页面
     */
    public function action_pershowemail() {
        $this->isLogin ();
        $content = View::factory ( 'user/showemail' );
        $this->template->content = $content;
        $content->email = $this->userInfo ()->email;
        $toemailurl = explode ( '@', $this->userInfo ()->email );
        if ($toemailurl [1] == "gmail.com") {
            $content->toemailurl = "http://mail.google.com/";
        } else {
            $content->toemailurl = "http://mail." . $toemailurl [1];
        }
        $get = Arr::map ( "HTML::chars", $this->request->query () );
        $type = Arr::get ( $get, 'type' );
        if ($type == 'ed') {
            $content->acttxt = '修改';
        }

                    $content->script = '';

        // post
        if ($this->request->method () == HTTP_Request::POST) {
            $post = $this->request->post ();

            $mobile = Arr::get ( $post, 'mobile' );
            $adf_code = Arr::get ( $post, 'usdf_code' );
            // 验证
            $service = new Service_User_MobileCodeLog ();
            $res = $service->getCodeEof ( $mobile, $adf_code );
            if ($res === true) {
                // set mobile
                $client = Service_Sso_Client::instance ();
                $rmo = $client->setUserMobileById ( $this->userId (), $mobile, 1 );
                if ($rmo === true) {
                    $rs_user = $this->userInfo ( true );
                    // com user type
                    if ($rs_user->user_type == '1') {
                        self::redirect ( 'member/comlead' );
                    }
                    // per user type
                    if ($rs_user->user_type == '2') {
                        self::redirect ( 'member/userlead' );
                    }
                    // self::redirect('/member/ ');
                }
            } else {
                // false
                self::redirect ( '/member/showemail?er=' . urlencode ( '验证码错误' ) );
            }
        }
        // end function
    }
    /**
     * 用户邮件链接 取消订阅
     *
     * @author 施磊
     */
    public function action_cancellationSubscription() {
        $user_id = $this->request->post ( 'user_id' );
        // 如果存在post提交
        if ($user_id) {
            $user_id = $this->request->post ( 'user_id' );
            $key = $this->request->post ( 'key' );
            $reasone = $this->request->post ( 'reasone' );
            $text_reasone = $this->request->post ( 'text_reasone' );
            // 检查数据是否合法
            $investor_service = new Service_User_Company_Investor ();
            $status = $investor_service->checkUserSubscriptionByIdAndKey ( $user_id, $key );
            if (! $status)
                self::redirect ( "/member/login" );
                // 检查是否有必要修改
            $result = $investor_service->getUserSubscriptionByUserId ( $user_id );
            if (! $result->subscription_status)
                self::redirect ( "/member/login" );
                // 修改属性
            $param = array (
                    'subscription_status' => 0,
                    'subscription_cancel_reason' => $reasone ? $reasone : $text_reasone
            );
            $investor_service->updateUserSubscriptionByUserId ( $user_id, $param );
            $content = View::factory ( "user/company/cancelSuccessEmail" );
            $this->template->content = $content;
        } else {
            $userId = $this->request->query ( 'user_id' );
            $key = $this->request->query ( 'key' );
            $investor_service = new Service_User_Company_Investor ();
            $status = $investor_service->checkUserSubscriptionByIdAndKey ( $userId, $key );
            if (! $status)
                self::redirect ( "/member/login" );
            $result = $investor_service->getUserSubscriptionByUserId ( $userId );
            if (! $result->subscription_status)
                self::redirect ( "/member/login" );
            $content = View::factory ( "user/company/cnacelUserSubscriptionForm" );
            $this->template->content = $content;
            $this->template->content->key = $key;
            $this->template->content->user_id = $userId;
        }
    }
    /**
     * oauth返回处理
     *
     * @author 周进
     */
    public function action_oauthBindUser() {
        $content = View::factory ( "user/binduser" );
        $this->template->content = $content;
    }
    /**
     * oauth第三方登录测试接口（仅仅供线上测试接口）
     *
     * @author 周进
     */
    public function action_oauthQq() {
        $content = View::factory ( "user/bindqquser" );
        $this->template->content = $content;
    }

    /**
     * *
     * @sso
     * 用户注册后的基本信息完善
     *
     * @author 许晟玮
     */
    public function action_userlead() {
        // 登录判断
        $this->isLogin ();
        $content = View::factory ( "/user/person/userlead" );
        $this->template->content = $content;
        if ($this->request->method () != HTTP_Request::POST) {
            $refer= Arr::get($_SERVER, 'HTTP_REFERER');
            Cookie::delete('lead_fromly');
            if( $refer!='' ){
                $rfs= strpos( $refer,'home.875' );
                   if( $rfs===false ){
                        //来自一句话
                        $fromly= '0';
                   }else{
                        //来自875
                        $fromly= '1';
                   }
            }else{
                //无来源
                $fromly= '0';

            }
            Cookie::set('lead_fromly', $fromly);
        }else{
            $fromly= 0;
        }
        $content->fromly = $fromly;
        // 增加招商地区弹出层
        // 地区
        /** $z_area = array (
                'pro_id' => 0
        );
        **/
        //$content->z_areas = common::arrArea ( $z_area );

        $service = new Service_User_Person_User ();
        $invest = new Service_User_Person_Invest ();
        // 判断是否完善了个人信息
        $user_rs = $service->getPerson ( $this->userId () );
        if ($user_rs->per_id != '') {
            self::redirect ( '/' );
        }

        $user = $this->userInfo ( true );
        // 如果手机 邮箱 认证过了，直接首页
        if ($user->valid_mobile == '1' && $user->valid_email == '1') {
            self::redirect ( '/' );
        }
        // 个人用户信息
        $personinfo = $service->transformationData ( $user->user_person );
        /* $pro = $invest->getArea ();
        $all = array (
                'cit_id' => 88,
                'cit_name' => '全国'
        );
        array_unshift ( $pro, $all );

        $areaIds = $personinfo->per_city;
        // 获取城市地区
        $pro_id = $personinfo->per_area; */

        /* if ($pro_id != '' && $pro_id != '88') {
            $area = array (
                    'pro_id' => $pro_id
            );
            $cityarea = common::arrArea ( $area );
        } else {
            $cityarea = array ();
        } */
        $gettype = $this->request->query ();

                    $content->script = '';

        // post
        if ($this->request->method () == HTTP_Request::POST) {
            $post = Arr::map ( "HTML::chars", $this->request->post () );
            // 意向投资地区
//             $yx_areas = Arr::get ( $post, "project_city" );
//             unset ( $post ['project_city'] );
            $post ['user_id'] = $this->userId ();
            // $post['per_city'] = $post['area_id'];
            // unset( $post['area_id'] );
//             if ($post ['per_birthday'] != '') {
//                 $post ['per_birthday'] = strtotime ( $post ['per_birthday'] );
//             }

            // 添加数据
            $post ['per_user_id'] = $this->userId ();
            $check_code = Arr::get ( $post, "check_code" );
            unset ( $post ['check_code'] );
            // print_r ($post);exit;
            $valid_post = Validation::factory ( $post );

            $valid_post->rule ( "per_realname", function (Validation $valid_error, $field, $value) {
                if (Valid::not_empty ( $value )) {
                    $rule = "\x80-\xff";
                    if (! Valid::max_length ( $value, 12 )) {

                        $valid_error->error ( $field, 'error1' );
                    }
                    if (! preg_match ( "/[$rule]/", $value ) && ! preg_match ( '/^[a-zA-Z]+$/', $value )) {

                        $valid_error->error ( $field, 'error1' );
                    }
                }
            }, array (
                    ':validation',
                    ':field',
                    ':value'
            ) );

            if (! $valid_post->check ()) {
                $error_arr = $valid_post->errors ();
                foreach ( $error_arr as $key => $error_vss ) {

                    $post [$key] = '';
                }
            }
            $post['per_industry']= '';
            $post['per_industry_child']= '';

            $res = $service->addPerson ( $post );
            if ($res > 0) {

                // true
                // 数据添加完毕,判断验证码是否正确
                // 判断手机号码是否为绑定的号码
                $service_user = Service::factory ( "User" );
                // if( $service_user->isMobileBinded( $post['per_phone'] ) ){
                // }else{

                // }
                if (isset ( $post ['per_phone'] ) && $post ['per_phone'] != '') {
                    if ($service_user->bindMobile ( $this->userId (), $post ['per_phone'], $check_code )) {
                        // 验证ok
                    } else {
                        // 验证error
                    }
                }

                // send mail
                if (isset ( $post ['per_mail'] ) && $post ['per_mail'] != '') {
                    $ucs = new Service_User_Company_User ();
                    $ucs->updateCheckValidEmail ( $this->userId (), $post ['per_mail'] );
                } else {
                }

                self::redirect ( "/" );
            } else {

                self::redirect ( "/" );
                // false
            }
        }
        $content->user = $user;
        $content->mobile = $user->mobile;
        $content->email = $user->email;

        $content->type = isset ( $gettype ['type'] ) ? $gettype ['type'] : 1;


        $content->personinfo = $personinfo;
    }
    // end function
    /**
     * 引导页上传头像
     *
     * @author 许晟玮
     */
    public function action_userphoto() {
        // 判断是否完善了个人信息
        $service = new Service_User_Person_User ();
        $user_rs = $service->getPerson ( $this->userId () );
        if ($user_rs->per_id != '') {
            // self::redirect('/');
        }
        $content = View::factory ( "/user/person/userphoto" );
        $this->template->content = $content;
        $user_id = $this->userId ();
        // 默认头像载入就修改掉，省的丫不点完成直接点其他的跳过这个步骤
        $add_arr = array ();
        $add_arr ['per_photo'] = "/user_icon/plant/default_icon_" . rand ( 1, 25 ) . ".jpg";
        $service->editPersonalUser ( $user_id, $add_arr );

        if ($this->request->method () == HTTP_Request::POST) {
            $post = Arr::map ( "HTML::chars", $this->request->post () );
            // 随机头像
            $per_photo = Arr::get ( $post, 'per_photo', '' );
            if ($per_photo == '') {
                $post ['per_photo'] = "/user_icon/plant/default_icon_" . rand ( 1, 25 ) . ".jpg";
            }

            if ($post ["per_remark"] == "介绍一下您的投资风格和意向项目类型吧，让您找项目更容易！") {
                $post ["per_remark"] = "";
            }
            $service->editPersonalUser ( $user_id, $post );
            // 返回首页
            if( Cookie::get('lead_fromly')==0 ){
                self::redirect ( '/' );
            }else{
                self::redirect( kohana::$config->load('site')->get('875domain') );
            }
        }
    }
    // end function

    /**
     * 企业引导页
     *
     * @author 许晟玮
     */
    public function action_comlead() {

        $content = View::factory ( "/user/company/comlead" );
        $this->template->content = $content;
        if ($this->request->method () != HTTP_Request::POST) {
            $refer= Arr::get($_SERVER, 'HTTP_REFERER');
            Cookie::delete('lead_fromly');
            if( $refer!='' ){
                $rfs= strpos( $refer,'home.875' );
                if( $rfs===false ){
                    //来自一句话
                    $fromly= '0';
                }else{
                    //来自875
                    $fromly= '1';
                }
            }else{
                //无来源
                $fromly= '0';

            }
            Cookie::set('lead_fromly', $fromly);
        }else{
            $fromly= 0;
        }
        $content->fromly = $fromly;

        $service = new Service_User_Company_User ();
        $card_service = new Service_User_Company_Card ();
        $userid = $this->userInfo ()->user_id;
        // if (!$service->getEmailValidCount($userid)){//判断是否邮箱验证
        // self::redirect("/company/member/basic/vemail");
        // }
        // 判断是否完善了信息
        $cominfo = $service->getCompanyInfo ( $userid )->as_array ();
        if ($cominfo ["com_id"] > 0) {
            self::redirect ( '/' );
        }
        $user = $this->userInfo ( true );
        // 如果手机 邮箱 认证过了，直接首页
        if ($user->valid_email == '1' && $user->valid_mobile == '1') {
            self::redirect ( '/' );
        }
        $content->user = $user;

        // $companyinfo = $card_service->getCompanyCard($this->userInfo()->user_id);
        // $content->companyinfo = $companyinfo['companyinfo']->as_array();
        $content->user_name = $this->userInfo ()->user_name;

        // 获取地域信息
        $invest = new Service_User_Person_Invest ();
        $pro = $invest->getArea ();
        $content->area = $pro;

                    $content->script = '';

        // post
        if ($this->request->method () == HTTP_Request::POST) {

            $getdata = $this->request->query ();
            $post = Arr::map ( "HTML::chars", $this->request->post () );
            $check_code = Arr::get ( $post, "check_code" );
            unset ( $post ['check_code'] );
            $post ['com_site'] = '';
            $post ['com_logo'] = '/images/platform/base_infor_yd/pic_big_logo.jpg';
            $post ['com_desc'] = '';
            foreach ( $post as $k => $v ) {
                $data [$k] = trim ( $v );
            }

            $valid_post = Validation::factory ( $data );
            $valid_post->rule ( "com_contact", function (Validation $valid_error, $field, $value) {
                if (Valid::not_empty ( $value )) {
                    $rule = "\x80-\xff";
                    if (! preg_match ( "/[$rule]/", $value ) && ! preg_match ( '/^[a-zA-Z]+$/', $value )) {
                        $valid_error->error ( $field, '请输入2-10个字符，由汉字、拼音组成' );
                    } elseif (! Valid::min_length ( $value, 2 ) || ! Valid::max_length ( $value, 10 )) {
                        $valid_error->error ( $field, '请输入2-10个字符，由汉字、拼音组成' );
                    } else {
                    }
                }
            }, array (
                    ':validation',
                    ':field',
                    ':value'
            ) )->

            rule ( "com_phone", function (Validation $valid_error, $field, $value) {
                if (Valid::not_empty ( $value )) {
                    if (! Valid::not_empty ( $value ) || ! Valid::min_length ( $value, 5 ) || ! Valid::max_length ( $value, 20 )) {
                        $valid_error->error ( $field, '请输入正确的联系方式' );
                    }

                    if ($value == "" or preg_match ( "/^[0-9-]*$/", $value )) {
                    } else {
                        $valid_error->error ( $field, '请输入正确的联系方式' );
                    }
                }
            }, array (
                    ':validation',
                    ':field',
                    ':value'
            ) )->

            rule ( "branch_phone", function (Validation $valid_error, $field, $value) {
                if (Valid::not_empty ( $value )) {
                    if (! Valid::max_length ( $value, 5 )) {
                        $valid_error->error ( $field, '分机号最多只能5位' );
                    }
                    if ($value == "" or Valid::numeric ( $value )) {
                    } else {
                        $valid_error->error ( $field, '分机号错误' );
                    }
                }
            }, array (
                    ':validation',
                    ':field',
                    ':value'
            ) )->

            rule ( "com_registered_capital", function (Validation $valid_error, $field, $value) {
                if (Valid::not_empty ( $value )) {
                    if (! Valid::max_length ( $value, 10 ) || ! Valid::not_empty ( $value ) || ! Valid::numeric ( $value )) {
                        $valid_error->error ( $field, '请输入正确的公司注册资本' );
                    } else {
                    }
                }
            }, array (
                    ':validation',
                    ':field',
                    ':value'
            ) );

            if (! $valid_post->check ()) {
                $erorr_arr = $valid_post->errors ();
                foreach ( $erorr_arr as $key => $erorr_vss ) {
                    $data [$key] = "";
                }
            }
            $cltime = $data ["com_founding_time"];
            if ($cltime != "") {
                $cltime_arr = explode ( "-", $cltime );

                $data ["com_founding_time_year"] = $cltime_arr [0];
                $data ["com_founding_time_month"] = $cltime_arr [1];
            } else {
                $data ["com_founding_time_year"] = "";
                $data ["com_founding_time_month"] = "";
            }

            $result = $service->updateCompanyBasic ( $data, $this->userId (), $this->userInfo ()->user_name, $this->userInfo ()->mobile );
            // 添加用户完成基本信息积分
            if ($result ['status'] == 1) {
                // print_r ($post);exit;
                $points = Service::factory ( "User_Company_Points" );
                $points->getPointsOnce ( $userid, "complete_basic" );
                // 数据添加完毕,判断验证码是否正确
                // 判断手机号码是否为绑定的号码
                $service_user = Service::factory ( "User" );
                // if( $service_user->isMobileBinded( $post['mobile'] ) ){
                // }else{

                // }

                if (isset ( $data ['mobile'] ) && $data ['mobile'] != '') {
                }
                if (isset ( $data ['mobile'] ) && $data ['mobile'] != '') {
                    $rcom = $service_user->bindMobile ( $this->userId (), $post ['mobile'], $check_code );
                }
                if (isset ( $data ['per_mail'] ) && $data ['per_mail'] != '') {
                    $ucs = new Service_User_Company_User ();
                    $ucs->updateCheckValidEmail ( $this->userId (), $data ['per_mail'] );
                } else {
                }

                if ( isset( $rcom ) && $rcom  ) {
                    // 验证ok
                    // 手机绑定通过，增加积分
                    $points = Service::factory ( "User_Company_Points" );
                    $points->getPointsTimes ( $this->userId (), "valid_mobile" );
                    // 手机绑定成功，增加诚信点
                    $integrity = Service::factory ( "User_Company_Integrity" );
                    if ($integrity->getIntegrityOnce ( $this->userId (), "valid_mobile" )) {
                        // 手机号码通过验证，发送消息提醒
                        /**
                         * $msg_service = new Service_User_Ucmsg();
                         * $msg_service->pushMsg($this->userId(), "company_integrity", "您已经验证手机号码，增加60点诚信指数。",URL::website("company/member/basic/integrity"));*
                         */
                        $smsg = Smsg::instance ();
                        // 内部消息发送
                        $smsg->register ( "tip_company_integrity", 						// 我的诚信
                        Smsg::TIP, 						// 类型
                        array (
                                "to_user_id" => $this->userId (),
                                "msg_type_name" => "company_integrity",
                                "to_url" => URL::website ( "company/member/basic/integrity" )
                        ), array (
                                "code" => "60",
                                "type" => "tel"
                        ) );
                    }
                } else {
                    // 验证error || email valid
                }
            }
            // 跳转到下页
            self::redirect ( '/member/comphoto' );
        }
    }
    // end

    /**
     * 企业头像
     *
     * @author 许晟玮
     */
    public function action_comphoto() {
        $content = View::factory ( "/user/company/comphoto" );
        $this->template->content = $content;
        $service = new Service_User_Company_User ();

        $com_logo = "/user_icon/plant/default_icon_" . rand ( 1, 25 ) . ".jpg";
        $add_arr = array ();
        $add_arr ["com_logo"] = common::getImgUrl ( $com_logo );
        $comrsss = $service->getCompanyInfo ( $this->userId () );
        $service->editCompanyInfoByApi ( $comrsss->com_id, $add_arr );
        // post

        if ($this->request->method () == HTTP_Request::POST) {
            $post = Arr::map ( "HTML::chars", $this->request->post () );
            // 处理照片
            $com_logo = Arr::get ( $post, 'com_logo' );
            if (trim ( $com_logo ) == '') {
                $com_logo = "/user_icon/plant/default_icon_" . rand ( 1, 25 ) . ".jpg";
            }
            $arr ["com_logo"] = common::getImgUrl ( $com_logo );

            $arr ["com_desc"] = arr::get ( $post, "com_desc" );
            if ($arr ['com_desc'] == "介绍一下您的投资风格和意向项目类型吧，让您找项目更容易！") {
                $arr ['com_desc'] = "";
            }
            $comrs = $service->getCompanyInfo ( $this->userId () );

            $service->editCompanyInfoByApi ( $comrs->com_id, $arr );
            // 返回首页
            self::redirect ( '/company/member/project/addproject' );
        }

        // editCompanyInfoByApi
    }
    // end
    /**
     * 咨询预览页 article_status!=2
     * @周进
     */
    public function action_showzixun() {
        // 必须登录
        $this->isLogin ();
        $query = Arr::map ( "HTML::chars", $this->request->query () );
        $id = Arr::get ( $query, 'id', 0 );
        // 获取资讯文章数据
        $article_service = new Service_News_Article ();
        $rs = $article_service->getInfoRow ( $id );
        if ($rs === false) {
            $content = View::factory ( 'platform/page_404' );
            $this->content->maincontent = $content;
        } else {
            if ($this->userId () != $rs ['user_id']) {
                $content = View::factory ( 'platform/page_404' );
                $this->content->maincontent = $content;
            } else {
                $content = View::factory ( "news/show" );
                $this->template->content = $content;
                $content->info = $rs;
            }
        }
    }
    // end function
    /* 升级公告
         * @author stone shi
         */
    public function action_upgradeNotice() {
        $user = $this->userInfo();
        if($user->user_type == 1) {
            self::redirect ( '/company/member/basic/upgradeNotice' );
        }else {
            self::redirect ( '/person/member/basic/upgradeNotice' );
        }
    }
}