<?php
/**
 * soap 服务控制入口
 * @author gongyong
 *
 */
class Sr_Soap_Call{
    public function __call($name,$args){
        //TODO 这里可以统计登录次数 启用停用 app key 等
        $org_args = $args;
        $appkey = array_pop($args);
        if(Sr_Soap_App::checkAppKey($appkey)){
            return call_user_func_array(array("Sr_Soap_Handle",$name),$org_args);
        }
        return array(
                "error"=>true,
                "msg"=>"app key 无效"
        );

    }
}