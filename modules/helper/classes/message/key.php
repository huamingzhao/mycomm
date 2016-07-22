<?php
class message_key{
    /**
     * 构建redis key
     * @param unknown $user_id
     * @param unknown $msg_type
     * @return string
     * @author 龚湧
     */
    public static function buildKey($user_id,$msg_type){
        return "msg_userid_".$user_id."_type_".$msg_type;
    }

    /**
     * 定义临时队列的key
     *@author许晟玮
     *@return string
     */
    public static function temporarykey(){
        return "rediskey_temporary";
    }
}