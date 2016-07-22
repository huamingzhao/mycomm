<?php
/**
 * Sso 登录客户端
 * @author gongyong
 *
 */
class Service_Sso_Client extends SoapClient {
    private function __construct($wsdl, array $options) {
        parent::SoapClient ( $wsdl, $options );
    }

    /**
     * 实例
     *
     * @var unknown
     */
    public static $client = NULL;

    /**
     * 创建实例
     *
     * @author 龚湧
     * @return unknown 2013-10-17 上午9:31:41
     */
    public static function instance() {
        $client = static::$client;
        if (static::$client === NULL) {
            $client = new Service_Sso_Client ( null, array (
                    'location' => Kohana::$config->load ( "sso.ws.location" ),
                    'uri' => 'sso.yijuhua.net',
                    'encoding' => 'utf8'
            ) );
            static::$client = $client;
        }
        return $client;
    }

    /**
     * 总入口 访问服务端
     *
     * @author 龚湧
     * @param unknown $function_name
     * @param array $arguments
     * @return mixed 2013-10-17 上午9:47:37
     */
    protected function call($function_name, array $arguments) {
        $result = array (
                'error' => false
        );
        try {
            array_push ( $arguments, Kohana::$config->load ( "sso.key" ) );
            $return = $this->__soapCall ( $function_name, $arguments );
            $result ['return'] = $return;
        } catch ( SoapFault $e ) {
            $result ['error'] = true;
            $result ['code'] = $e->getCode ();
            $result ['msg'] = $e->getMessage ();
        }
        return $result;
    }

    /**
     * 用户登录 返回token
     *
     * @author 龚湧
     * @param unknown $auth_name
     *        	合法的邮箱+验证通过的手机号码
     * @param unknown $password
     *        	密码
     * @return Ambigous <mixed, array>|Ambigous <mixed, multitype:boolean NULL mixed > 登录成功返回用户基本信息
     *         登录失败 返回错误码
     *         2013-10-21 下午2:21:00
     */
    public function login($auth_name, $password, $remember = 0) {
        $result = $this->call ( "appLoginAuth", array (
                $auth_name,
                $password
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            $SToken = Arr::get ( $return, "SToken" );
            if ($SToken) {
                $userinfo = $this->getUserInfo ( $SToken );
                $this->set_cookie ( $SToken, $userinfo, $remember );
                //Cookie::delete('cpa_aid');
            }
            return $return;
        }
        // 返回错误代码和消息
        return $result;
    }

    /**
     * 自动登录处理 内部使用
     *
     * @author 龚湧
     * @param unknown $auth_name
     * @param unknown $password
     * @param number $remember
     * @return Ambigous <mixed, array>|Ambigous <mixed, multitype:boolean NULL mixed > 登录成功见用户登录
     *         2013-11-6 下午1:25:37
     */
    public function autoLogin($user_id) {
        $result = $this->call ( "autoLoginById", array (
                $user_id
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            $SToken = Arr::get ( $return, "SToken" );
            if ($SToken) {
                $userinfo = $this->getUserInfo ( $SToken );
                $this->set_cookie ( $SToken, $userinfo );
                //Cookie::delete('cpa_aid');
            }
            return $return;
        }
        // 返回错误代码和消息
        return $result;
    }

    /**
     * 退出登录 主站和第三方通用
     *
     * @author 龚湧
     * @param unknown $token
     * @return Ambigous <mixed, multitype:boolean NULL mixed >
     *         2013-11-8 下午2:25:43
     */
    public function logout($token) {
        $url = parse_url ( Kohana::$config->load ( "site.website" ) );
        Cookie::$domain = ltrim ( $url ['host'], "www." );
        Cookie::delete ( 'authautologin' );
        Cookie::$domain = NULL;

        Cookie::delete ( 'user_name' );
        Cookie::delete ( 'email' );
        Cookie::delete ( 'user_id' );
        Cookie::delete ( 'user_type' );
        Session::instance()->delete('authautologin');
        $result = $this->call ( "logout", array (
                $token
        ) );
        $url = parse_url ( Kohana::$config->load ( "site.website" ) );
        Cookie::$domain = ltrim ( $url ['host'], "www." );
        Cookie::delete ( 'authautologin' );

        Cookie::delete ( 'user_name' );
        Cookie::delete ( 'email' );
        Cookie::delete ( 'user_id' );
        Cookie::delete ( 'user_type' );

        return $result;
    }

    /**
     * 判断用户是否登录
     *
     * @author 龚湧
     * @param unknown $token
     * @return Ambigous <mixed, array>|boolean
     *         2013-10-21 下午4:38:06
     */
    public function isLogin($token) {
        $result = $this->call ( "isLogin", array (
                $token
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            // return $return;
            if ($return === 2) {
                // 更新登录时间
                $ext = new Service_Sso_Ext ();
                $ext->activityLoginLog ( Cookie::get ( "user_id" ), Cookie::get ( "user_type" ) );
            }
            if ($return === 1 or $return === 2) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取用基本信息 token 为 cookie保存的
     *
     * @author 龚湧
     * @param unknown $token
     * @return mixed boolean 下午2:26:33
     */
    public function getUserInfo($token) {
        $result = $this->call ( "getUserInfo", array (
                $token
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return json_decode ( $return );
        }
        return false;
    }

    /**
     * 用户注册 【主站专用，暂时不对外提供】
     *
     * @author 龚湧
     * @param unknown $user
     * @param string $type
     * @return Ambigous <mixed, array>|boolean
     *         2013-11-8 下午2:27:10
     */
    public function appRegister($user, $type = "EMAIL") {
        $result = $this->call ( "appRegister", array (
                $user,
                $type
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return $return;
        }
        return false;
    }

    // 更具id获取用户信息 只获取信息 【主站专用，不对外提供】
    /**
     * 更具ID获取用户信息 对外提供只有token 才能获取用户信息 特殊对内接口 安全性依赖于 app key
     *
     * @author 龚湧
     * @param unknown $id
     * @return mixed boolean 下午2:31:45
     */
    public function getUserInfoById($id) {
        $result = $this->call ( "getUserInfoById", array (
                $id
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return json_decode ( $return );
        }
        return false;
    }

    // 更具手机号码获取用户信息 【主站专用，不对外提供】
    /**
     * 根据【已绑定】手机号获取用户信息
     *
     * @author 龚湧
     * @param unknown $mobile
     * @return mixed boolean 下午2:32:42
     */
    public function getUserInfoByMobile($mobile) {
        $result = $this->call ( "getUserInfoByMobile", array (
                $mobile
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return json_decode ( $return );
        }
        return false;
    }

    // 更具邮箱获取用户信息 【主站专用，不对外提供】
    /**
     * 更具邮箱获取用基本信息
     *
     * @author 龚湧
     * @param unknown $email
     * @return mixed boolean 下午2:33:33
     */
    public function getUserInfoByEmail($email) {
        $result = $this->call ( "getUserInfoByEmail", array (
                $email
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return json_decode ( $return );
        }
        return false;
    }

    /**
     * oauth_id 获取用户信息
     *
     * @author 龚湧
     * @param unknown $oauth_id
     * @return mixed boolean 下午2:29:44
     */
    public function getUserInfoByOauthId($oauth_id) {
        $result = $this->call ( "getUserInfoByOauthId", array (
                $oauth_id
        ) );

        $return = Arr::get ( $result, "return" );
        if ($return) {
            return json_decode ( $return );
        }
        return false;
    }

    // 根据ID 更新用户基本信息 【主站专用，不对外提供】
    /**
     * 更新用基本信息表
     *
     * @author 龚湧
     * @param unknown $id
     * @param unknown $field
     * @return Ambigous <mixed, array>|boolean
     *         2013-11-8 下午2:33:50
     */
    public function updateBasicInfoById($id, $field) {
        $result = $this->call ( "updateBasicInfoById", array (
                $id,
                $field
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return $return;
        }
        return false;
    }

    // 根据ID 更新用户手机信息 【主站专用，不对外提供】
    /**
     * 更新用户手机基本信息表
     *
     * @author 龚湧
     * @param unknown $id
     * @param unknown $field
     * @return Ambigous <mixed, array>|boolean
     *         2013-11-8 下午2:34:00
     */
    public function updateMobileInfoById($id, $field) {
        $result = $this->call ( "updateMobileInfoById", array (
                $id,
                $field
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return $return;
        }
        return false;
    }

    // 根据ID 更新用户邮件信息 【主站专用，不对外提供】
    /**
     * 更新邮件基本信息表
     *
     * @author 龚湧
     * @param unknown $id
     * @param unknown $field
     * @return Ambigous <mixed, array>|boolean
     *         2013-11-8 下午2:34:19
     */
    public function updateEmailInfoById($id, $field) {
        $result = $this->call ( "updateEmailInfoById", array (
                $id,
                $field
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return $return;
        }
        return false;
    }

    // 设置用户手机号码 【主站专用，不对外提供】 $tag 设置该手机号码绑定状态，默认为0不绑定
    /**
     * 初始化使用，不包含更新已绑定手机号码的操作
     *
     * @author 龚湧
     * @param unknown $id
     * @param unknown $mobile
     * @param number $tag
     *        	0为不绑定，1为绑定
     * @return Ambigous <mixed, array>|boolean
     *         2013-11-6 下午3:35:39
     */
    public function setUserMobileById($id, $mobile, $tag = 0) {
        // TODO 更新缓存 TODO 判断手机号码合法性
        $result = $this->call ( "setUserMobileById", array (
                $id,
                $mobile,
                $tag
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            $error = Arr::get ( $return, "error" );
            if (! $error) {
                $code = Arr::get ( $return, "code" );
                return true;
            }
        }
        return false;
    }

    /**
     * 初始化使用,设置会员邮箱
     *
     * @author 许晟玮
     */
    public function setUserEmailById($id, $email, $tag = 0) {
        $result = $this->call ( "setUserEmailId", array (
                $id,
                $email,
                $tag
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            $error = Arr::get ( $return, "error" );
            if (! $error) {
                $code = Arr::get ( $return, "code" );
                return true;
            }
        }
        return false;
    }

    // end functiion

    // 批量查询用户信息 只获取信息 【主站专用，不对外提供】
    public function getBatchUserInfoByIds($ids) {
    }

    // 检查注册用户名是否可用 邮箱+绑定手机号码检测 return boolen 【主站专用，不对外提供】
    /**
     * 检测注册用户邮件+手机号码是否合法
     *
     * @author 龚湧
     * @param unknown $reg_name
     * @return Ambigous <mixed, array>|boolean
     *         2013-11-8 下午2:34:58
     */
    public function isRegNameValid($reg_name) {
        $result = $this->call ( "isRegNameValid", array (
                $reg_name
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return $return;
        }
        return false;
    }

    // 用户密码修改
    /**
     * 用户密码修改
     *
     * @author 龚湧
     * @param unknown $id
     * @param unknown $password
     * @param unknown $new_password
     * @return Ambigous <mixed, array>|boolean
     *         2013-11-8 下午2:35:34
     */
    public function setNewPassword($id, $password, $new_password) {
        $result = $this->call ( "setNewPassword", array (
                $id,
                $password,
                $new_password
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return $return;
        }
        return false;
    }

    /**
     * 重置密码
     *
     * @author 龚湧
     * @param unknown $id
     * @param unknown $newpassword
     * @return Ambigous <mixed, array>|boolean
     *         2013-11-11 下午2:24:00
     */
    public function resetPassword($id, $newpassword) {
        $result = $this->call ( "resetPassword", array (
                $id,
                $newpassword
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return $return;
        }
        return false;
    }

    // 检查当前用户密码是否正确
    /**
     * 检查当前输入密码是否正确
     *
     * @author 龚湧
     * @param unknown $id
     * @param unknown $password
     * @return Ambigous <mixed, array>|boolean
     *         2013-11-8 下午2:37:10
     */
    public function isPassowrdOk($id, $password) {
        $result = $this->call ( "isPassowrdOk", array (
                $id,
                $password
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return $return;
        }
        return false;
    }

    /**
     * 检查第三方登录是否已经绑定
     *
     * @author 龚湧
     * @param unknown $oauth_id
     *        	2013-11-20 下午2:13:03
     */
    public function isTrdOuathExist($oauth_id) {
        $result = $this->call ( "isTrdOuathExist", array (
                $oauth_id
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return $return;
        }
        return false;
    }

    /**
     * 绑定第三方登录
     *
     * @author 龚湧
     * @param unknown $oauth_id
     * @param unknown $oauth_type
     *        	2013-11-20 下午2:14:06
     */
    public function trdRegister($oauth_id, $user_id, $type) {
        $result = $this->call ( "trdRegister", array (
                $oauth_id,
                $user_id,
                $type
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            if (Arr::get ( $return, "error" ) === false) {
                // 用户基本信息
                return $return;
            }
        }
        return false;
    }

    /**
     * 第三方用户首次绑定
     *
     * @author 龚湧
     * @param unknown $array("user_id","oauth_id","type")
     * @return Ambigous <mixed, array>|boolean
     *         2013-12-4 下午2:53:26
     */
    public function createTrdOauth($array) {
        $result = $this->call ( "createTrdOauth", array (
                $array
        ) );
        $return = Arr::get ( $result, "return" );
        if ($return) {
            return $return;
        }
        return false;
    }

    // 老版本移植过来，人走了，留下的代码,种cookie
    public function set_cookie($SToken, $user, $remember = 0) {
        $user = ( array ) $user;
        $lifetime = 0;
        if ($remember) {
            $lifetime = Kohana::$config->load ( "auth.lifetime" );
        }

        $url = parse_url ( Kohana::$config->load ( "site.website" ) );
        Cookie::$domain = ltrim ( $url ['host'], "www." );
        Cookie::set ( 'authautologin', $SToken, $lifetime, Cookie::$path );
        Cookie::$domain = NULL;

        Cookie::set ( 'user_id', Arr::get ( $user, "id" ), $lifetime, Cookie::$path );
        Cookie::set ( 'user_name', Arr::get ( $user, "user_name" ), $lifetime, Cookie::$path );
        Cookie::set ( 'email', Arr::get ( $user, 'email' ), $lifetime, Cookie::$path );
        Cookie::set ( 'user_type', Arr::get ( $user, "user_type" ), $lifetime, Cookie::$path );
        $session = Session::instance ();
        $session->set ( "user_id", Arr::get ( $user, "id" ) );
        $session->set ( "user_type", Arr::get ( $user, "user_type" ) );
        $session->set ( "authautologin", $SToken );
    }

    /**
     * 获取会员手机验证的会员列表 bi用(per/com)
     *
     * @author 许晟玮
     */
    public function getUserMobileBindList($now_page, $user_type, $valid, $begin, $end, $uid = array()) {
        $result = $this->call ( "getUserMobileBindList", array (
                $now_page,
                $user_type,
                $valid,
                $begin,
                $end,
                $uid
        ) );

        if ($result) {
            if (Arr::get ( $result, "error" ) === false) {
                // 用户基本信息
                return Arr::get ( $result, "return" );
            }
        }
        return false;
    }
    // end function

    /**
     * 获取会员手机验证的会员count bi用(per/com)
     *
     * @author 许晟玮
     */
    public function getUserMobileBindCount($user_type, $valid, $begin, $end, $uid = array()) {
        $result = $this->call ( "getUserMobileBindCount", array (
                $user_type,
                $valid,
                $begin,
                $end,
                $uid
        ) );
        if ($result) {
            if (Arr::get ( $result, "error" ) === false) {
                // 用户基本信息
                return Arr::get ( $result, "return" );
            }
        }
        return false;
    }
    // end function

    /**
     * 传入多个用户ID 获取数据
     *
     * @author 许晟玮
     */
    public function getUserInfoByMoreUserId($ids = array()) {
        $result = $this->call ( "getUserInfoByMoreUserId", array (
                $ids
        ) );
        if ($result) {
            if (Arr::get ( $result, "error" ) === false) {
                // 用户基本信息
                return Arr::get ( $result, "return" );
            }
        }
        return array();
    }
    // end function

    /**
     * 没有密码，创建一个新的密码
     *
     * @author 许晟玮
     */
    public function setPasswordOauth($user_id, $password, $appkey = '') {
        $result = $this->call ( "setPasswordOauth", array (
                $user_id,
                $password,
                $appkey
        ) );
        if ($result ['return'] === true) {
            return true;
        } else {
            return $result;
        }
    }
    // end function

    /**
     * 判断用户是否设置了密码 第三方登录用
     *
     * @author 许晟玮
     */
    public function getUserPasswordEof($uid) {
        $result = $this->call ( "getUserPasswordEof", array (
                $uid
        ) );
        if ($result ['return'] === true) {
            return true;
        } else {
            return false;
        }
    }

    // end function

    /**
     * 传入第三方 id 获取数据
     *
     * @author 许晟玮
     */
    public function getOauthInfoById($oauth_id, $uid) {
        $result = $this->call ( "getOauthInfoById", array (
                $oauth_id,
                $uid
        ) );
        return $result ['return'];
    }
    // end function

    /**
     * 解除第三方绑定
     *
     * @author 许晟玮
     */
    public function delOauth($oauth_id, $uid) {
        $result = $this->call ( "delOauth", array (
                $oauth_id,
                $uid
        ) );
        if ($result ['return'] === true) {
            return true;
        } else {
            return false;
        }
    }
    // end function

    /**
     * 新建帐号的绑定,设置bind_user_id (edit oauth table)
     *
     * @author 许晟玮
     */
    public function editOauth($oauth_id, $uid, $bind_user) {
        $result = $this->call ( "editOauth", array (
                $oauth_id,
                $uid,
                $bind_user
        ) );
        return true;
    }
    // end function
    /**
     * 获取时间段内用户注册list
     * @author 许晟玮
     */
    public function getRegUserListBydate($from_time,$to_time,$user_type=0){
        $result = $this->call ( "getRegUserListBydate", array (
                $from_time,
                $to_time,
                $user_type
        ) );
        return $result;
    }
    //end function

    /**
    * 通过手机号登录,手机号传入，写cookie
    *@author 许晟玮
    */
    public function userLoginByMobile( $mobile,$code ){
        $msg= array();
        //code valide
        $codes= new Service_User_MobileCodeLog();
        $rc= $codes->getCodeEof($mobile, $code);
        if( $rc===false ){
            $msg['result']= false;
            $msg['msg']= '验证码错误';
            return $msg;
        }
        $result= self::getUserInfoByMobile($mobile);

        if( $result===false ){
            $msg['result']= false;
            $msg['msg']= '手机号未验证';
            return $msg;
        }
        if( ceil( $result->id )>0 ){
            //判断是否禁用
            if( $result->user_status=='0' ){
                $msg['result']= false;
                $msg['msg']= '用户已禁用';
                return $msg;
            }

            // delete cache
            self::updateBasicInfoById($result->id, array('id'=>$result->id));
            $result= self::getUserInfoByMobile($mobile);
            if( $result===false ){
                $msg['result']= false;
                $msg['msg']= '手机号未验证';
                return $msg;
            }
            //存在，写cookie
            $return= self::autoLogin($result->id);
            if( $return['error']===false ){
                $msg['result']= true;
                $msg['msg']= 'ok';
                return $msg;
            }else{
                $msg['result']= false;
                $msg['msg']= '登录失败';
                return $msg;
            }
        }elseif ( $result->valid_mobile==0 ){
            $msg['result']= false;
            $msg['msg']= '手机号未验证';
            return $msg;
        }else{
            $msg['result']= false;
            $msg['msg']= '手机号不存在';
            return $msg;
        }

    }
    //end function

    /**
    *获取最新的几个会员信息( 针对手机注册的 )
    *@author 许晟玮
    */
    public function getMobileUserInfoByNum( $num=10,$desc='user_id' ){
        $result = $this->call ( "getMobileUserInfoByNum", array ( 'num'=>$num,'id'=>$desc ) );
        return $result;

    }

    //end function

    /**
    * 获取basic下的注册叔
    *@author 许晟玮
    */
    public function getUserRegNumInBasic( $begin,$end ){
        $result = $this->call ( "getUserRegNumInBasic", array ( 'begin'=>$begin,'end'=>$end ) );
        return $result;
    }
    //end function

    /**
    *判断是否存在手机号码
    *@author 许晟玮
    */
    public function getMobileEof( $mobile ){
        $result = $this->call ( "getMobileEof", array ( 'mobile'=>$mobile ) );
        $return= Arr::get($result, 'return');
        if( $return>0 ){
            return true;
        }else{
            return false;
        }
    }

    //end function

    /**
     * 获取时间段 内，注册的会员ID
     *@author 许晟玮
     */
    public function getUserIdsByDate( $begin,$end ){
        $result = $this->call ( "getUserIdsByDate", array ( 'begin'=>$begin,'end'=>$end ) );
        if( Arr::get($result, 'error')===false ){
            $return= Arr::get($result, 'return');
            return $return;
        }else{
            return false;
        }
    }
    //end function

}