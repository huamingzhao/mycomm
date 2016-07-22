<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 临时队列中消息，定期推送到 用户队列中
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_Message_GetTemporaryMsg extends Minion_Task
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
            $this->_setMessage();
    }

    /**
     *获取临时的消息队列，写入数据库
     * @author 许晟玮
     */
    private function _setMessage(){
        //获取临时队列的数据
        $redis= Rediska_Manager::get("list");
        $key= message_key::temporarykey();

        $rs= $redis->get($key);
        if( !empty( $rs ) ){
            $redis_service= new Service_Redis_List();

            //获取定义的组信息
            $msg_type= message_type::types();


            $re_decode= json_decode($rs);
            if( !empty( $re_decode ) ){
                //内部生成key
                foreach ( $re_decode as $vs_decode ){
                    $uid= $vs_decode->user_id;
                    $tid= $vs_decode->message_type;
                    $group_name= '';
                    $set_array= array();
                    //取出的数据转换成array
                    foreach( $vs_decode as $tb=>$vss_decode ){
                        $set_array[$tb]= $vss_decode;
                    }

                    //获取组名
                    foreach( $msg_type as $vs_type ){
                        if( $vs_type['id']==$tid ){
                            $group_name= $vs_type['group'];break;
                        }

                    }


                    if( $group_name!='' && ceil($uid)!=0 && !empty( $set_array ) ){
                        $setkey= message_key::buildKey($uid, $group_name);
                        $redis_service->pushMsgToList($setkey, $set_array);
                    }
                }
            }else{
            }

        }else{

        }
        echo '<br>';
        echo 'aa';
        echo '<br>';

    }
    //end function

}
