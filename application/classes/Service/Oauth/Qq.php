<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @brief qq协议登录接口
 * @author 周进
 * @date 2013-05-07
 * @version 2.0
 */
class Service_Oauth_Qq{
    const VERSION = "2.0";
    const GET_AUTH_CODE_URL = "https://graph.qq.com/oauth2.0/authorize";
    const GET_ACCESS_TOKEN_URL = "https://graph.qq.com/oauth2.0/token";
    const GET_OPENID_URL = "https://graph.qq.com/oauth2.0/me";

    private $appid;
    private $appkey;
    private $errorMsg;

    function __construct($config)
    {
        $this->appid = $config['appid'];
        $this->appkey = $config['appkey'];
        $this->errorMsg = array(
                "20001" => "<h2>配置文件损坏或无法读取，请重新执行intall</h2>",
                "30001" => "<h2>The state does not match. You may be a victim of CSRF.</h2>",
                "50001" => "<h2>可能是服务器无法请求https协议</h2>可能未开启curl支持,请尝试开启curl支持，重启web服务器，如果问题仍未解决，请联系我们"
        );
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
        $appid = $this->appid;
        $callback = rawurlencode($this->getReturnUrl());
        $scope = 'get_user_info';//请求用户授权时向用户显示的可进行授权的列表，可选
        //-------生成唯一随机串防CSRF攻击
        $state = md5(uniqid(rand(), TRUE));
        //$this->recorder->write('state',$state);
        $session->set('qq_state',$state);
        //-------构造请求参数列表
        $keysArr = array(
                "response_type" => "code",
                "client_id" => $appid,
                "redirect_uri" => $callback,
                "state" => $state,
                "scope" => $scope
        );
        $login_url =  $this->combineURL(self::GET_AUTH_CODE_URL, $keysArr);
        return $login_url;
    }

    function checkStatus($parms)
    {
        return true;
    }

    function getAccessToken($parms)
    {
        $session = Session::instance();
        $state = $session->get('qq_state');

        //--------验证state防止CSRF攻击
        if(arr::get($parms, 'state') != $state){
            $this->error->showError("30001");
        }

        //-------请求参数列表
        $keysArr = array(
                "grant_type" => "authorization_code",
                "client_id" => $this->appid,
                "redirect_uri" => urlencode($this->getReturnUrl()),
                "client_secret" => $this->appkey,
                "code" => arr::get($parms, 'code')
        );

        //------构造请求access_token的url
        $token_url = $this->combineURL(self::GET_ACCESS_TOKEN_URL, $keysArr);
        $response = $this->get_contents($token_url);

        if(strpos($response, "callback") !== false){

            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
            $msg = json_decode($response);

            if(isset($msg->error)){
                $this->error->showError($msg->error, $msg->error_description);
            }
        }

        $params = array();
        parse_str($response, $params);

        $_SESSION['last_qq_key']['access_token'] = $params["access_token"];
        return $params["access_token"];

    }

    function getUserInfo()
    {
        $session = Session::instance();
        //-------请求参数列表
        $keysArr = array(
                "access_token" => $_SESSION['last_qq_key']['access_token']
        );

        $graph_url = $this->combineURL(self::GET_OPENID_URL, $keysArr);
        $response = $this->get_contents($graph_url);

        //--------检测错误是否发生
        if(strpos($response, "callback") !== false){
            $lpos = strpos($response, "(");
            $rpos = strrpos($response, ")");
            $response = substr($response, $lpos + 1, $rpos - $lpos -1);
        }

        $user = json_decode($response);
        if(isset($user->error)){
            $this->error->showError($user->error, $user->error_description);
        }
        $userInfo = array();
        $userInfo['id']   = $user->openid   ? $user->openid   : '';
        $userInfo['name'] = isset($user->nickname) ? $user->nickname : '';
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


    /********/
    /**
     * combineURL
     * 拼接url
     * @param string $baseURL   基于的url
     * @param array  $keysArr   参数列表数组
     * @return string           返回拼接的url
     */
    public function combineURL($baseURL,$keysArr){
        $combined = $baseURL."?";
        $valueArr = array();

        foreach($keysArr as $key => $val){
            $valueArr[] = "$key=$val";
        }

        $keyStr = implode("&",$valueArr);
        $combined .= ($keyStr);

        return $combined;
    }

    /**
     * get_contents
     * 服务器通过get请求获得内容
     * @param string $url       请求的url,拼接后的
     * @return string           请求返回的内容
     */
    public function get_contents($url){
        if (ini_get("allow_url_fopen") == "1") {
            $response = file_get_contents($url);
        }else{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            $response =  curl_exec($ch);
            curl_close($ch);
        }

        //-------请求为空
        if(empty($response)){
            $this->error->showError("50001");
        }

        return $response;
    }

    /**
     * get
     * get方式请求资源
     * @param string $url     基于的baseUrl
     * @param array $keysArr  参数列表数组
     * @return string         返回的资源内容
     */
    public function get($url, $keysArr){
        $combined = $this->combineURL($url, $keysArr);
        return $this->get_contents($combined);
    }

    /**
     * post
     * post方式请求资源
     * @param string $url       基于的baseUrl
     * @param array $keysArr    请求的参数列表
     * @param int $flag         标志位
     * @return string           返回的资源内容
     */
    public function post($url, $keysArr, $flag = 0){

        $ch = curl_init();
        if(! $flag) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $keysArr);
        curl_setopt($ch, CURLOPT_URL, $url);
        $ret = curl_exec($ch);

        curl_close($ch);
        return $ret;
    }

    /**
     * showError
     * 显示错误信息
     * @param int $code    错误代码
     * @param string $description 描述信息（可选）
     */
    public function showError($code, $description = '$'){
        $recorder = new Recorder();
        if(! $recorder->readInc("errorReport")){
            die();//die quietly
        }


        echo "<meta charset=\"UTF-8\">";
        if($description == "$"){
            die($this->errorMsg[$code]);
        }else{
            echo "<h3>error:</h3>$code";
            echo "<h3>msg  :</h3>$description";
            exit();
        }
        }
        public function showTips($code, $description = '$'){
    }
}
