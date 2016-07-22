<?php
/**
 *
 * @author gongyong
 *
 */
class Kohana_Smsg{
    const TIP = 1;//消息类型
    const EMAIL = 2; //手机短信
    const MOBILE = 3; //邮件类型

    /**
     * 实例化
     * @author 龚湧
     * 2013-9-17 下午3:01:32
     */
    public static function instance(){
        if(Smsg::$instances === NULL){
            Smsg::$instances = new Smsg();
        }
        return Smsg::$instances;
    }

    /**
     * 实例
     * @var unknown
     */
    public static $instances = NULL;


    /**
     * 待执行方法，消息队列
     * @var unknown
     */
    public static $msgList = array();



    /**
     * 消息推送总开关
     * @author 龚湧
     * @param unknown $msg_type_name 非重复，回调方法名称、view名称
     * @param unknown $type 确定调用模板
     * @param array $call_param 调用参数
     * @param unknown $view_param 给模板赋值，生成内容， 方便分离
     * 2013-9-17 下午3:01:15
     */
    public function register($msg_type_name,$type,$call_param=array(),$view_param=array()){
        if($this->isMsgLive($msg_type_name)){
            static::$msgList[$msg_type_name] = array(
                    'callname'=>$msg_type_name,
                    'type'=>$type,
                    'call_param'=>$call_param,
                    'view_param'=>$view_param
            );
        }
    }

    /**
     *
     * @author 龚湧
     * @param unknown $msg_type_name
     * 2013-9-23 下午2:24:51
     */
    private function isMsgLive($msg_type_name){
        $cache = Cache::instance();
        $status = $cache->get("cacheMsgControl_".$msg_type_name);
        if($status === NULL){
            $obj = ORM::factory("MsgControl")
                        ->where("alias_name","=",$msg_type_name)
                        ->find();
            if($obj->loaded()){
                $status = $obj->status;
                $cache->set("cacheMsgControl_".$msg_type_name, $status);
            }

        }
        return (bool)$status;
    }

    /**
     *
     * @author 龚湧
     * 2013-9-17 下午3:14:12
     */
    private function __construct(){
    }

    /**
     * 消息发送 框架执行完毕后调用,无返回值
     * @author 龚湧
     * 2013-9-18 下午1:13:35
     */
    public static function send(){
        $msgs = static::$msgList;
        if(isset($_GET['debug']) and $_GET['debug']=== '1'){
            print_r($msgs);
        }
        if($msgs){
            foreach ($msgs as $msg){
                switch ($msg['type']){
                    case Smsg::TIP:{
                        static::sendTip($msg);
                        break;
                    }
                    case Smsg::EMAIL:{
                        static::sendEmail($msg);
                        break;
                    }
                    case Smsg::MOBILE:{
                        static::sendMobile($msg);
                        break;
                    }
                    default:{
                        break;
                    }
                }
            }
        }
    }

    /**
     * 发送消息站内消息
     * @author 龚湧
     * @param array $msg
     * 2013-9-22 下午1:19:44
     */
    protected static function sendTip(array $msg){
        $service_msg = new Service_User_Ucmsg();
        $msg_callname = Arr::get($msg,"callname");
        $msg_call = Arr::get($msg,"call_param");
        $msg_view = Arr::get($msg,"view_param");
        $msg_content = View::factory("msg/tip/".$msg_callname,$msg_view)->render();
        $service_msg->pushMsg(Arr::get($msg_call,"to_user_id"),Arr::get($msg_call,"msg_type_name"), $msg_content,Arr::get($msg_call,"to_url"));
    }

    /**
     * 邮件消息发送
     * @author 龚湧
     * @param array $msg
     * 2013-9-22 下午2:28:49
     */
    protected static  function sendEmail(array $msg){
        $msg_callname = Arr::get($msg,"callname");
        $msg_call = Arr::get($msg,"call_param");
        $msg_view = Arr::get($msg,"view_param");
        $msg_content = View::factory("msg/email/".$msg_callname,$msg_view)->render();
        $result = common::sendemail(Arr::get($msg_call,"subject"), '', Arr::get($msg_call,"to_email"), $msg_content);
    }

    /**
     * 短信消息发送
     * @author 龚湧
     * @param array $msg
     * 2013-9-22 下午2:29:15
     */
    protected static function sendMobile(array $msg){
        $msg_callname = Arr::get($msg,"callname");
        $msg_call = Arr::get($msg,"call_param");
        $msg_view = Arr::get($msg,"view_param");
        $msg_content = View::factory("msg/mobile/".$msg_callname,$msg_view)->render();
        $result = common::send_message(Arr::get($msg_call,"receiver"), $msg_content, "online");
    }
}