<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * ORM Auth driver.
 *
 * @package    Kohana/Auth
 * @author     Kohana Team
 * @copyright  (c) 2007-2012 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Auth_ORM extends Auth {

    /**
     * Checks if a session is active.
     *
     * @param   mixed    $role Role name string, role ORM object, or array with role names
     * @return  boolean
     */
    public function logged_in($role = NULL)
    {
        // Get the user from the session
        $user = $this->get_user();

        if ( ! $user){
            return FALSE;
        }else{
            return $user;
        }


    }

    /**
     * Logs a user in.
     *
     * @param   string   $username
     * @param   string   $password
     * @param   boolean  $remember  enable autologin
     * @return  string
     */
    protected function _login($user, $password, $remember)
    {

        $service = Service::factory("User");

        if(!$users = $service->checkLoginName($user)){
            return "email";
        }
        $user = $users->email;//和原版兼容，采用email方式

        // Create a hashed password
        $password = sha1($password.$user);

        // If the passwords match, perform a login
        if ( $users->password === $password)
        {
            // Set the autologin cookie
            $this->set_cookie($users,$remember);

            return $users;
        }else{

            return 'password';
        }
    }
    /**
     * Set Admin Login Status
     * @author 施磊
     */
    public function _setAdminLoginStatus($userInfo) {
        if(!$userInfo) return FALSE;
        if($userInfo['admin_user_status']) {
          $session = Session::instance();
          $session->set("admin_user_id", $userInfo['admin_user_id']);
          $session->set("admin_user_name", $userInfo['admin_user_name']);
          $session->set("user_group_name", $userInfo['user_group_name']);
          $session->set("admin_user_info", $userInfo);
          return TRUE;
        }
        return FALSE;
    }
    /**
     * 后台用户登出
     * @author 施磊
     */
    public function _unsetAdminUserStatus() {
        $session = Session::instance();
        $session->delete('admin_user_id');
        $session->delete('admin_user_info');
        $session->delete('adminPermission');
    }
    /**
     * Set the autologin cookie
     *
     * @return  boolean
     */
    public function set_cookie($user,$remember=0)
    {
    	//获取域名值 yijuhua.net
    	$t_website=URL::website('');
    	$t_website_one=explode("www.",$t_website);
    	$t_website_two=explode("/",arr::get($t_website_one,1));
    	$t_domain=arr::get($t_website_two,0);
    	Cookie::$domain=$t_domain;
        if($remember == 1){
            Cookie::set('authautologin', sha1($user->user_id.$this->_config['lifetime'].$user->email), $this->_config['lifetime'],Cookie::$path);
            Cookie::set('user_id', $user->user_id, $this->_config['lifetime'],Cookie::$path);
            Cookie::set('user_name', $user->user_name, $this->_config['lifetime'],Cookie::$path);
            Cookie::set('email', $user->email, $this->_config['lifetime'],Cookie::$path);
            Cookie::set('user_type', $user->user_type, $this->_config['lifetime'],Cookie::$path);
            $session = Session::instance();
            $session->set("user_id",$user->user_id);
        }else{
            Cookie::set('authautologin', sha1($user->user_id.$this->_config['lifetime'].$user->email), 0,Cookie::$path);
            Cookie::set('user_id', $user->user_id, 0,Cookie::$path);
            Cookie::set('user_name', $user->user_name, 0,Cookie::$path);
            Cookie::set('email', $user->email, 0,Cookie::$path);
            Cookie::set('user_type', $user->user_type, $this->_config['lifetime'],Cookie::$path);
            $session = Session::instance();
            $session->set("user_id",$user->user_id);
        }
        Cookie::$domain=NULL;
    }

    /**
     * Forces a user to be logged in, without specifying a password.
     *
     * @param   mixed    $user                    username string, or user ORM object
     * @param   boolean  $mark_session_as_forced  mark the session as forced
     * @return  boolean
     */
    public function force_login($user, $mark_session_as_forced = FALSE)
    {
        if ( ! is_object($user))
        {
            $username = $user;

            // Load the user
            $user = ORM::factory('User');
            $user->where($user->unique_key($username), '=', $username)->find();
        }

        if ($mark_session_as_forced === TRUE)
        {
            // Mark the session as forced, to prevent users from changing account information
            $this->_session->set('auth_forced', TRUE);
        }

        // Run the standard completion
        $this->complete_login($user);
    }

    /**
     * Logs a user in, based on the authautologin cookie.
     *
     * @return  mixed
     */
    public function auto_login()
    {
        if ($token = Cookie::get('authautologin'))
        {
            // Load the token and user
            $token = ORM::factory('User_Token', array('token' => $token));

            if ($token->loaded() AND $token->user->loaded())
            {
                if ($token->user_agent === sha1(Request::$user_agent))
                {
                    // Save the token to create a new unique token
                    $token->save();

                    // Set the new token
                    Cookie::set('authautologin', $token->token, $token->expires - time());

                    // Complete the login with the found data
                    $this->complete_login($token->user);

                    // Automatic login was successful
                    return $token->user;
                }

                // Token is invalid
                $token->delete();
            }
        }

        return FALSE;
    }

    /**
     * Gets the currently logged in user from the session (with auto_login check).
     * Returns $default if no user is currently logged in.
     *
     * @param   mixed    $default to return in case user isn't logged in
     * @return  mixed
     */
    public function get_user($default = NULL)
    {
        $user = parent::get_user($default);

        if(!$user){
            return false;
        }

        return $user;
    }

    /**
     * Log a user out and remove any autologin cookies.
     *
     * @param   boolean  $destroy     completely destroy the session
     * @param    boolean  $logout_all  remove all tokens for user
     * @return  boolean
     */
    public function logout($destroy = FALSE, $logout_all = FALSE)
    {
        // Set by force_login()
    	//获取域名值 yijuhua.net
    	$t_website=URL::website('');
    	$t_website_one=explode("www.",$t_website);
    	$t_website_two=explode("/",arr::get($t_website_one,1));
    	$t_domain=arr::get($t_website_two,0);
    	Cookie::$domain=$t_domain;
        // Delete the autologin cookie to prevent re-login
        $session = Session::instance();
        $session->delete("user_id");
        Cookie::delete('session');
        Cookie::delete('authautologin');
        Cookie::delete('user_name');
        Cookie::delete('email');
        Cookie::delete('user_id');
        Cookie::delete("guideConfig");
        Cookie::$domain=NULL;
        return parent::logout($destroy);
    }

    /**
     * Get the stored password for a username.
     *
     * @param   mixed   $user  username string, or user ORM object
     * @return  string
     */
    public function password($user)
    {
        if ( ! is_object($user))
        {
            $username = $user;

            // Load the user
            $user = ORM::factory('User');
            $user->where($user->unique_key($username), '=', $username)->find();
        }

        return $user->password;
    }



    /**
     * Compare password with original (hashed). Works for current (logged in) user
     *
     * @param   string  $password
     * @return  boolean
     */
    public function check_password($password)
    {
        $user = $this->get_user();

        if ( ! $user)
            return FALSE;


        return ($password === $user->password);
    }

} // End Auth ORM
