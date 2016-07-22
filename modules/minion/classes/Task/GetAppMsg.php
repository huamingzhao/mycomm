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
class Task_GetAppMsg extends Minion_Task
{
    private  $_mkey= 'list:chatrecord';

    /**
     * Generates a help list for all tasks
     *  @author 许晟玮
     * @return null
     */
    protected function _execute(array $params){
        #php shell php minion --task=test

            //获取临时队列的数据
        $redis= Rediska_Manager::get("list");

        $count= $redis->getListLength( $this->_mkey );
        if( $count==0 ){
            Minion_CLI::write('没有聊天记录了');
        }else{
            Minion_CLI::write('一共存在记录-------------'.$count);
            $al= 20;
            $all_page= ceil( $count/$al );
            for( $p=0;$p<=$all_page;$p++ ){

                $end= $p*$al;
                $list = $redis->getList($this->_mkey,$p,$end);
                if( !empty( $list ) ){
                    foreach( $list as $vs ){
                        $v= json_decode($vs);
                        //判断消息提的类型，如果存在type，就是系统消息
                        $xml_txt= $v->stanza;
                        $txt= @simplexml_load_string($xml_txt,NULL,LIBXML_NOCDATA);
                        $obj_body= json_decode($txt->body);
                        $time= $v->creationDate;
                        $save_time= substr( $time,0,-3 );

                        if( isset( $obj_body->type ) ){
                            $orm= ORM::factory('Appsysrecord');
                            $orm->username= $v->username;
                            $orm->messageID= $v->messageID;
                            $orm->creationDate= ceil($save_time);
                            $orm->messageSize= $v->messageSize;
                            $orm->stanza= $obj_body->body;
                            $result= $orm->create();
                        }else{

                            $orm= ORM::factory('Appchatrecord');
                           $orm->username= $v->username;
                           $orm->messageID= $v->messageID;
                           $orm->creationDate= ceil($save_time);
                           $orm->messageSize= $v->messageSize;
                           $orm->stanza= $txt->body;
                           $result= $orm->create();
                        }

                           if( $result->id>0 ){
                            //删除redis消息
                               $redis->deleteFromList($this->_mkey,$vs,1);
                               Minion_CLI::write($result->id);
                           }else{
                               Minion_CLI::write('写入失败');
                           }


                    }
                }else{
                }

            }
            Minion_CLI::write('ok');


        }

    }


    //end function

}
