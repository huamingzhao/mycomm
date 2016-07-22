<?php
/**
 * key校验 服务第一步
 * @author gongyong
 *
 */
class Sr_Soap_App{
    /**
     * 检查app key 合法性
     * @param unknown $appkey
     * @return boolean
     */
    public static function checkAppKey($appkey){
        $config = Kohana::$config->load("appkey.".$appkey);
        if($config){
            return $config;
        }
        else{
            return FALSE;
        }
    }
}
