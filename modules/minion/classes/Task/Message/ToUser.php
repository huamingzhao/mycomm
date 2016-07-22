<?php
/**
 * 零时队列消息推送到用户队列
 * @author 龚湧
 *
 */
class Task_Message_ToUser extends Minion_Task{

    /**
     * task
     * (non-PHPdoc)
     * @see Kohana_Minion_Task::_execute()
     */
    protected function _execute(array $params){
        $service = new Service_Redis_List();
        $key = message_key::temporarykey();
        while($value = json_decode($this->_redis->popFromList($key),true)){
            $type = message_type::getTypeByName(Arr::get($value,"msg_type_name"),"group");
            $popkey = message_key::buildKey(Arr::get($value,"user_id"),$type);
            $result = $service->pushMsgToList($popkey, $value);
        }
    }

    private $_redis;

    public function __construct(){
        parent::__construct();
        $this->_redis = Rediska_Manager::get("list");
    }

}