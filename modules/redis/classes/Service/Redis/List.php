<?php
/**
 * 消息队列应用
 * @author 龚湧
 *
 */
class Service_Redis_List{

    private $_redis;

    public function __construct(){
        $this->_redis = Rediska_Manager::get("list");
    }

    /**
     * 扩展
     * @return Ambigous <Rediska, multitype:>
     * @author 龚湧
     */
    public function redis(){
        return $this->_redis;
    }

    /**
     * 队列中插入一条新消息,最新的放在顶部 & 定时任务时使用
     *
     * @author 龚湧
     */
    public function pushMsgToList($key,array $msg){
        //写入到数据库
        $msg = $this->saveMsgToDb($msg);
        //推送到队列
        if($msg){
            $value = $msg->as_array();
            $value['id'] = "".$value['id']."";
            $value = json_encode($value);
            try{
                //事物开始
                $transcation = $this->_redis->transaction();
                $transcation->prependToList($key, $value);
                $transcation->increment($key."_counter");
                $transcation->execute();
                return true;
            //事务结束
            }
            catch(Rediska_Exception $e){
                return false;
            }
            //return $this->_redis->prependToList($key, $value);
        }
        return false;
    }

    /**
     * 队列中删除一条消息
     * @param unknown $key
     * @param unknown $msg_id 消息ID
     * @param array $update 更新数据库中消息状态
     * @return Ambigous <mixed, multitype:, boolean, array>
     * @author 龚湧
     */
    public function delMsgFromList($key,$msg_id,array $update= array()){
        //库中取消息体
        $obj = ORM::factory("Ucmsg",$msg_id);
        $value = json_encode($obj->as_array());
        //删除队列消息
        //print_r($value);exit();
        if($this->_redis->deleteFromList($key, $value,1)){
            $msg_id = $obj->id;
            $this->updateMsgFromDb($msg_id, $update);
            return true;
        }
        return false;
    }

    /**
     * 删除key
     * @param unknown $key
     * @author 龚湧
     */
    public function delList($key){
        $this->_redis->delete($key);
        //TODO 跟新数据库状态，搞到一个新的队列中
    }

    /**
     * 队列中更新一条消息
     * @param unknown $key
     * @param $msg_id 消息id
     * @param array $update 更新的字段
     * @param unknown $referenceValue 原消息体
     * @param unknown $value 新消息体
     * @author 龚湧
     */
    public function updateMsgFromList($key,$msg_id,array $update=array()){
        $obj = ORM::factory("Ucmsg",$msg_id);
        $referenceValue = json_encode($obj->as_array());
        if($update){
            foreach($update as $column=>$value){
                $column = "".$column."";
                $value = "".$value."";
                $obj->$column = $value;
            }
            try {
                $obj->update();
            }catch(Kohana_Exception $e){
                return false;
            }
        }
        //更新后的值
        $value = json_encode($obj->as_array());

        //事务开始
        try{
            $transcation = $this->_redis->transaction();
            $transcation->insertToList($key, "BEFORE", $referenceValue, $value);
            $transcation->deleteFromList($key, $referenceValue,1);
            $result = $transcation->execute();
        }
        catch (Rediska_Exception $e){
            return false;
        }
        //事务结束
        return true;

    }

    /**
     * 获取消息队列列表
     *
     * @author 龚湧
     */
    public function getMsgList($key){
        //队列总长度
        $len = $this->_redis->getListLength($key);
        //获取队列区间值
        $start = 0;
        $end = -1;
        if($len){
            $page = Pagination::factory(
                    array(
                            'total_items'       => $len,
                            'items_per_page'    => 10,
                    )
            );
            $start = $page->offset;
            $end = $page->offset+$page->items_per_page-1;
            $list = $this->_redis->getList($key,$start,$end);
            $result['list'] = $list;
            $result['page'] = $page;
            return $result;
        }
        return FALSE;
    }

    /**
     * 合并多个消息列表，并按时间排序&分页  TODO 采用管道新式更高效
     *
     * @author 龚湧
     */
    public function getMltMsgList(array $keys){
        /*
        $start = microtime(true);
        var_dump($keys);
        $pipeline = $this->_redis->pipeline();
        if($keys){
            foreach($keys as $key){
                 $pipeline->getList($key,0,100);
            }
            $result = $pipeline->execute();
            $end = microtime(true);
            echo $end - $start;
            print_r($result);exit();

        }
        */
        //防止socket返回数据大，每次取30条
        $msgs = array();
        if($keys){
            foreach($keys as $key){
                $len = $this->_redis->getListLength($key);
                if($len <=100){
                    $msg = $this->_redis->getList($key);
                    $msgs = array_merge($msgs,$msg);
                }
                else{
                    $l = 0;
                    while($l<$len){
                        $msg = $this->_redis->getList($key,$l,$l+29);
                        $l = $l+30;
                        $msgs = array_merge($msgs,$msg);
                    }
                }
            }
        }
        //排序，其实就是按照消息id来的
        rsort($msgs);
        $len = count($msgs);
        $start = 0;
        $end = -1;
        if($len){
            $page = Pagination::factory(
                    array(
                            'total_items'       => $len,
                            'items_per_page'    => 10,
                    )
            );
            $start = $page->offset;
            //$end = $page->offset+$page->items_per_page-1;
            $list = array_slice($msgs,$start,10);
            $result['list'] = $list;
            $result['page'] = $page;
            return $result;
        }
        return FALSE;
    }

    /**
     * 重置计数器
     * @param unknown $key
     * @author 龚湧
     */
    public function resetMsgCounter($key){
        $key .= "_counter";
        $this->_redis->set($key,0);
    }


    /**
     * 消息队列中新消息条数  从计数器中读取
     * @param unknown $key
     * @return Ambigous <mixed, multitype:, boolean, array>
     * @author 龚湧
     */
    public function getMsgCount($key){
        $key .= "_counter";
        return $this->_redis->get($key);
    }

    /**
     * 消息保存到数据库
     * @throws Kohana_Exception
     * @return ORM | boolean
     * @author 龚湧
     */
     public function saveMsgToDb(array $msg){
        $obj = ORM::factory("Ucmsg");
        $obj->user_id = "".Arr::get($msg,"user_id")."";
        $obj->message_content = Arr::get($msg,"message_content");
        $obj->to_url = Arr::get($msg,"to_url");
        $msg_type_name = Arr::get($msg,"msg_type_name");
        $obj->message_type = "".message_type::getTypeByName($msg_type_name,"id")."";
        $obj->read_time = "0";
        $obj->last_logintime = "0";
        $obj->message_state = "1";
        $obj->save_time = "".Arr::get($msg,"save_time")."";
        try{
            $obj->create();
        }catch(Kohana_Exception $e){
            return false;
        }
        return $obj;
    }

    /**
     * 更新数据库中保存消息体
     * @param unknown $msg_id
     * @param array $field
     * @return ORM | false
     * @author 龚湧
     */
    public function updateMsgFromDb($msg_id,array $field){
        $obj = ORM::factory("Ucmsg",$msg_id);
        if($field){
            foreach($field as $column=>$value){
                $column = "".$column."";
                $value = "".$value."";
                $obj->$column = $value;
            }
            try {
                $obj->update();
            }catch(Kohana_Exception $e){
                return false;
            }
        }
        return $obj;
    }


    /**
     * 获取一个key的长度
     * @author许晟玮
     */
    public function getKeyLen( $key ){
        $len = $this->_redis->getListLength($key);
        return $len;

    }

}