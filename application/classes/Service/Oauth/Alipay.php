<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @brief Sina协议登录接口
 * @author 周进
 * @date 2013-05-07
 * @version 2.0
 */
class Service_Oauth_Alipay{
    private $partner    = '';
    private $key = '';
    //签名方式 不需修改
    private $sign_type    = '';
    //字符编码格式 目前支持 gbk 或 utf-8
    private $input_charset = '';
    //ca证书路径地址，用于curl中ssl校验
    //请保证cacert.pem文件在当前文件夹目录中
    private $cacert    = '';
    //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
    private $transport    = '';

    public function __construct($config)
    {
        $this->partner       = $config['partner'];
        $this->key           = $config['key'];
        $this->sign_type     = strtoupper('MD5');
        $this->input_charset = strtolower('utf-8');
        $this->cacert        = APPPATH.'classes/Service/Oauth/cacert.pem';
        //getcwd().'\\cacert.pem';
        $this->transport     = 'http';
    }

    //获取回调URL地址
    protected function getReturnUrl()
    {
        return URL::website('/ajaxcheck/oauthcallback');
    }

    public function getFields()
    {
        return array(
                'partner' => array(
                        'label' => 'partner',
                        'type'  => 'string',
                ),
                'key'=>array(
                        'label' => 'key',
                        'type'  => 'string',
                ),
        );
    }

    //获取登录url地址
    public function getLoginUrl()
    {
        //构造要请求的参数数组，不要改动
        $parameter = array(
                "service" => "alipay.auth.authorize",
                "partner" => trim($this->partner),
                "target_service"	=> "user.auth.quick.login",//目标服务地址
                "return_url"	=> $this->getReturnUrl(),//必填，页面跳转同步通知页面路径需http://格式的完整路径，不允许加?id=123这类自定义参数
                "anti_phishing_key"	=> "",//防钓鱼时间戳若要使用请调用类文件submit中的query_timestamp函数
                "exter_invoke_ip"	=> "",//客户端的IP地址如：221.0.0.1
                "_input_charset"	=> trim(strtolower($this->input_charset))
        );
        $alipay_config = array(
                'partner' => $this->partner,
                'key' => $this->key,
                'sign_type' => $this->sign_type,
                'input_charset' => $this->input_charset,
                'cacert' => $this->cacert,
                'transport' => $this->transport,
        );

        $alipaySubmit = new Service_Oauth_Alipaysubmit($alipay_config);
        //$url  = 'http://notify.alipay.com/trade/notify_query.do?';
        $url = "https://mapi.alipay.com/gateway.do?";
        $url .= $alipaySubmit->buildRequestParaToString($parameter);
        return $url;
    }

    //获取进入令牌
    public function getAccessToken($parms)
    {
        $session = Session::instance();
        if (isset($parms['user_id'])&&isset($parms['token'])) {
            $session->set('alipay_query_token', $parms);
        }
    }

    //获取用户数据
    public function getUserInfo()
    {
        $session = Session::instance();
        $alipay_config = array(
                'partner' => $this->partner,
                'key' => $this->key,
                'sign_type' => $this->sign_type,
                'input_charset' => $this->input_charset,
                'cacert' => $this->cacert,
                'transport' => $this->transport,
        );
        $alipayNotify = new Service_Oauth_Alipaynotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn($session->get('alipay_query_token'));
        $userInfo = array();
        if($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //支付宝用户号

            //$user_id = $_GET['user_id'];

            //授权令牌
            //$token = $_GET['token'];

            $userInfo['id']   = $_SESSION['alipay_query_token']['user_id']!=""?$_SESSION['alipay_query_token']['user_id']: '';
            $userInfo['name'] = '';

            //判断是否在商户网站中已经做过了这次通知返回的处理
            //如果没有做过处理，那么执行商户的业务程序
            //如果有做过处理，那么不执行商户的业务程序

            //echo "验证成功<br />";

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            //如要调试，请看alipay_notify.php页面的verifyReturn函数
            //echo "验证失败";
            $userInfo['id']   = '';
            $userInfo['name'] = '';
        }

        return $userInfo;
    }

    public function checkStatus($parms)
    {
        if( isset($parms['error'] ) || isset( $parms['error_code'] ) )
        {
            return false;
        }
        else
        {
            return true;
        }
    }



    /***************/


}
