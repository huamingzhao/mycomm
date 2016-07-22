<?php
/**
 * 用户消息
 * @author 龚湧
 *
 */
class Service_User_Msg{

    private $_redis;

    public function __construct(){
        $this->_redis = Rediska_Manager::get("list");
    }

    public function redis(){
        return $this->_redis;
    }
    /**
     * 消息推送到临时队列
     * @param unknown $to_user_id
     * @param unknown $msg_type_name
     * @param unknown $msg_content
     * @param string $msg_tourl
     * @throws Kohana_Exception
     * @return boolean
     * @author 龚湧
     */
    public function pushMsg($to_user_id,$msg_type_name,$msg_content,$msg_tourl=""){
        $msg = array();
        $msg['user_id'] = $to_user_id;
        $msg['message_content'] = $msg_content;
        $msg['to_url'] = $msg_tourl?$msg_tourl:Arr::get(message_type::getTypeByName($msg_type_name),"to_url");
        $msg['msg_type_name'] = $msg_type_name;
        $msg['save_time'] = time();
        $value = json_encode($msg);
        $key = message_key::temporarykey();
        $this->_redis->appendToList($key, $value);
        return true;
    }

    /**
     * 获取未读消息总数
     * @param unknown $user_id
     * @author 龚湧
     * @package Service_Redis_List
     */
    public function getMsgCount($user_id){
    }
}