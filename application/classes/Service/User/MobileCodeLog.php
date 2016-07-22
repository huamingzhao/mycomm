<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 短信发送日志
 * @author 许晟玮
 *
 */
class Service_User_MobileCodeLog {

    /**
     * 添加日志
     * @author 许晟玮
     */
    public function setLogs($mobile,$code,$status=1) {
        $orm = ORM::factory('MobileCodeLog');
        $orm->code= ceil( $code );
        $orm->mobile= $mobile;
        $orm->datetime= time();
        $orm->status= $status;
        $result= $orm->create();
        return $result;
    }
    //end function

    /**
     * 传入手机号,验证码 ,获取是否存在记录
     * @author许晟玮
     * @return bool
     */
    public function getCodeEof( $mobile,$code,$exp=null,$status=1 ){
        $orm = ORM::factory('MobileCodeLog');
        $orm->where('mobile', '=', $mobile);
        $orm->where('code', '=', $code);
        $orm->where('status', '=', $status);
        if( $exp!=null ){
            $orm->where('datetime', '>=', $exp);
        }else{
        }

        $count= $orm->count_all();
        if( $count>0 ){
            return true;
        }else{
            return false;
        }
    }
    //end function



}