<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @brief Sina协议登录接口
 * @author 周进
 * @date 2013-05-07
 * @version 2.0
 */
class Service_Oauth_Sina{

    function __construct($config)
    {
        $session = Session::instance();
        $session->set('apiKey',$config['apiKey']);
        $session->set('apiSecret',$config['apiSecret']);
    }
    //获取回调URL地址
    protected function getReturnUrl()
    {
        //return "http://www.qutouzi.com/ajaxcheck/oauthcallback";
        return URL::website('/ajaxcheck/oauthcallback');
    }
    function getLoginUrl()
    {
        $session = Session::instance();
        $o = new Service_Oauth_Saetoauthv( $session->get('apiKey') , $session->get('apiSecret')  );
        $aurl = $o->getAuthorizeURL($this->getReturnUrl());
        return $aurl;
    }

    function checkStatus($parms)
    {
        if( isset( $parms['error_code'] ) ){
            return false;
        }
        return true;
    }

    function getAccessToken($parms)
    {
        $session = Session::instance();
        if (isset($parms['code'])) {
            $keys = array();
            $keys['code'] = $parms['code'];
            $keys['redirect_uri'] = $this->getReturnUrl();
            $o = new Service_Oauth_Saetoauthv( $session->get('apiKey') , $session->get('apiSecret')  );
            $token = $o->getAccessToken( 'code', $keys ) ;
            if ($token) {
                $session->set('access_token', $token['access_token']);
                //$_SESSION['last_key']['access_token'] = $token['access_token'];
            }
        }

    }

    function getUserInfo()
    {
        $session = Session::instance();
        $c  = new Service_Oauth_Saetclientv( $session->get('apiKey') , $session->get('apiSecret') , $session->get('access_token'));
        $uids = $c->get_uid();
        $uid  = $uids['uid'];
        $me   = $c->show_user_by_id($uid);

        $userInfo = array();
        $userInfo['id']   = isset($uid)   ? $uid   : '';
        $userInfo['name'] = isset($me['screen_name']) ? $me['screen_name'] : '';
        return $userInfo;
    }

    public function getFields()
    {
        return array(
                'apiKey' => array(
                        'label' => 'apiKey',
                        'type'  => 'string',
                ),
                'apiSecret' => array(
                        'label' => 'apiSecret',
                        'type'  => 'string',
                ),
        );
    }

}
