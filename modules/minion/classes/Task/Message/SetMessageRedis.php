<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 数据库中取用户消息，推送到用户队列中
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_Message_SetMessageRedis extends Minion_Task
{
    private $_redis;

    public function __construct(){
        $this->_redis = Rediska_Manager::get("list");
    }

    /**
     * Generates a help list for all tasks
     *  @author 许晟玮
     * @return null
     */
    protected function _execute(array $params){
        #php shell php minion --task=test
            $this->_getMessage();

    }

    /**
     *
     * @author获取消息队列
     */
    private function _getMessage(){
        $resdis= $this->_redis;
     //获取message中存在的所有user_id
        $orm= ORM::factory('Ucmsg');
        $result= $orm->group_by('user_id')->find_all()->as_array();

        Minion_CLI::write(count($result));

        //msg group
        $group= message_type::msgGroup();
        $group_company= $group['company'];
        $group_person= $group['person'];


        foreach ( $result as $vss ){
            $info= $vss->as_array();
            $uid= $info['user_id'];
            if( ceil( $uid )!=0 ){

                //company
                foreach ( $group_company as $key=>$vs_company ){
                    if( $key!='integrity' ){
                        $orm= ORM::factory('Ucmsg');
                        $obj_com= $orm->where('user_id', '=', $uid)->where('message_type', 'in', $vs_company)->find_all()->as_array();
                        $new_com_arr= array();
                        if( !empty( $obj_com ) ){
                            $msg_key= message_key::buildKey($uid, $key);
                            foreach ( $obj_com as $vs_com ){
                                $info= $vs_com->as_array();
                                //事物开始
                                $transcation = $resdis->transaction();
                                $transcation->prependToList($msg_key, json_encode($info));
                                $transcation->increment($msg_key."_counter");
                                $transcation->execute();

                            }
                        }else{
                        }
                    }


                }

                foreach ( $group_person as $key=>$vs_person ){
                    $orm= ORM::factory('Ucmsg');
                    $obj_person= $orm->where('user_id', '=', $uid)->where('message_type', 'in', $vs_person)->find_all()->as_array();
                    $new_person_arr= array();
                    if( !empty( $obj_person ) ){
                        $msg_key= message_key::buildKey($uid, $key);
                        foreach ( $obj_person as $vs_person ){
                            $info= $vs_person->as_array();
                            $transcation = $resdis->transaction();
                            $transcation->prependToList($msg_key, json_encode($info));
                            $transcation->increment($msg_key."_counter");
                            $transcation->execute();
                        }
                    }else{
                    }




                }

            }

        }
        echo '/a/';

    }
    //end function

}
