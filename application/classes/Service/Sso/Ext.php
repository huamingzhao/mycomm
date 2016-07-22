<?php
//sso客户端扩展需求
class Service_Sso_Ext{
    //默认为一小时一次
    public function activityLoginLog($user_id,$user_type){
        $time = time();
        $ip = Request::$client_ip;
        //获取记录上次登录时间
        $ssouser_log = ORM::factory("User",$user_id);
        if($ssouser_log->loaded()){
            $ssouser_log->last_logintime =$time;
            $ssouser_log->last_login_ip =ip2long($ip);
            try{
                $ssouser_log->update();
            }
            catch (Kohana_Exception $e){
                return false;
            }
        }

        //写入登录日志，创建记录
        $detaillog = ORM::factory('UserLoginLog');
        $detaillog->user_id = $user_id;
        $detaillog->user_type =$user_type;
        $detaillog->log_time = $time;
        $detaillog->log_ip = $ip;
        $detaillog->log_type = 1;//自动登录
        try{
            $detaillog->create();
        }catch (Kohana_Exception $e){
            return false;
        }
    }
}