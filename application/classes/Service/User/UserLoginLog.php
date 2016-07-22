<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 登陆日志
 * @author 施磊
 *
 */
class Service_User_UserLoginLog {
    
    /**
     * 添加日志
     * @author 施磊
     */
    public function insertLogs($data) {
        if(!$data) return FALSE;
        $ormModel = ORM::factory('UserLoginLog');
        $ormModel->values($data)->create();
    }
    
    /**
     * 获得用户最后登陆日志
     * @param int $user_id 用户id
     * @param int $log_type 用户日志类型
     * @author 施磊
     */
    public function selectLastLogin($user_id, $log_type = 1) {
        if(!$user_id) return FALSE;
        $ormModel = ORM::factory('UserLoginLog')->where('user_id','=', $user_id)->where('log_type', '=', $log_type)->order_by('log_time','desc')->limit(2)->find_all();
        $return = array();
        foreach($ormModel  as $key =>  $val) {
            if($key == 1) {
               $return =  $val->as_array();
               break;
            }
        }
        return $return;
    }
}