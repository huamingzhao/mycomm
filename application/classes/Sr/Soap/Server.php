<?php
/**
 * 服务端
 * @author gongyong
 *
 */
class Sr_Soap_Server extends SoapServer{
    private function __construct($wsdl, array $options){
        parent::SoapServer($wsdl, $options);
    }

    /**
     * 保存实例
     * @var unknown
     */
    public static $instance = NULL;

    /**
     * 设置服务端类
     * @var unknown
     */
    public static $className = "Sr_Soap_Call";

    /**
     * 服务端实例
     * @author gongyong
     * @return unknown
     * 2013-10-17 9:31:41
     */
    public static function instance(){
        $server = static::$instance;
        if(static::$instance === NULL){
            $server = new Sr_Soap_Server(null,
                    array(
                            'uri'=>'www.yijuhua.net',
                            'encoding' => 'utf8'
                    )
            );
            static::$instance = $server;
        }
        return $server;
    }

    /**
     * 创建服务
     */
    public function excue(){
        $this->setClass(static::$className);
        $this->handle();
    }
}