<?php
/**
 * Sso 登录客户端
 * @author gongyong
 *
 */
class Service_Sso_Test extends SoapClient{
    private function __construct($wsdl,array $options){
        parent::SoapClient($wsdl, $options);
    }

    /**
     * 实例
     * @var unknown
     */
    public static $client = NULL;

    /**
     * app key
     * @var unknown
     */
    private $appkey = "ggQpl4UxtyugDmR2uwhvyTfL0ddXrKzB";

    /**
     * 创建实例
     * @author 龚湧
     * @return unknown
     * 2013-10-17 上午9:31:41
     */
    public static function instance(){
        $client = static::$client;
        if(static::$client === NULL){
            $client = new Service_Sso_Test(null,
                    array(
                            'location'=>"http://www.myczzs.com/api/ws",
                            'uri'=>'www.yijuhua.net',
                            'encoding' => 'utf8'
                    )
            );
            static::$client = $client;
        }
        return $client;
    }

    /**
     * 总入口 访问服务端
     * @author 龚湧
     * @param unknown $function_name
     * @param array $arguments
     * @return mixed
     * 2013-10-17 上午9:47:37
     */
    protected function call($function_name, array $arguments){
        $result = array('error'=>false);
        try{
            array_push($arguments, $this->appkey);
            $return = $this->__soapCall($function_name, $arguments);
            $result['return'] = $return;
        }
        catch(SoapFault $e){
            $result['error'] = true;
            $result['code'] = $e->getCode();
            $result['msg'] = $e->getMessage();
        }
        return $result;
    }

    /**
     * 更具邮箱获取用基本信息
     * @author 龚湧
     * @param unknown $email
     * @return mixed|boolean
     * 2013-11-8 下午2:33:33
     */
    public function soaptest(){
        $result = $this->call("getCardList",array('id'=>0));
        print_r($result);exit;
        return $result;

    }
}