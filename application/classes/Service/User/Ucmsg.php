<?php defined('SYSPATH') or die('No direct script access.');
/**
 * TODO 机制待完善
 * 用户中心消息service
 * @author 龚湧
 *
 */
class Service_User_Ucmsg{

    /**
    * 0 表示删除
    * @var int
    */
    const DEL = 0;

    /**
     * 2表示已读
     * @var int
     */
    const READED = 2;

    //企业用户
    const COMPANY = 1;
    //个人用户
    const PERSON = 2;

    private $_redis;

    public function __construct(){
        $this->_redis = Rediska_Manager::get("list");
    }

    /**
     * 查找特定时间段内最新消息
     * @author 龚湧
     * @param unknown_type $user_id
     * @param unknown_type $msg_type_id
     * @param unknown_type $from_time
     * @param unknown_type $to_time
     * @return boolean
     */
    public function isTypeMsgSendTimeSlice($user_id,$msg_type_id,$from_time,$to_time){
        $obj = ORM::factory("Ucmsg")
               ->where("user_id","=",$user_id)
               ->where("message_type","=",$msg_type_id)
               ->where("save_time",">=",$from_time)
               ->where("save_time","<=",$to_time)
               ->order_by("save_time","DESC")
               ->find();
        if($obj->id){
            return true;
        }
        return false;
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
        $msg['message_content'] = base64_encode($msg_content);
        $msg['to_url'] = $msg_tourl?$msg_tourl:Arr::get(message_type::getTypeByName($msg_type_name),"to_url");
        $msg['msg_type_name'] = $msg_type_name;
        $msg['save_time'] = time();
        $value = json_encode($msg);
        $key = message_key::temporarykey();
        $this->_redis->appendToList($key, $value);
        return true;
    }

    /**
     * 获取最后一次标记阅读消息时间
     * @author 龚湧
     * @param int $user_id
     * @return int | null
     */
    public function getLatestMsgReadTime($user_id,$msg_type_group,$last_logintime){
        $msg = ORM::factory("Ucmsg")
               ->where("user_id","=",$user_id)
               ->where("last_logintime","=",$last_logintime)
               ->where("message_type","in",$msg_type_group)
               ->order_by("read_time","desc")
               ->find();
        return $msg->read_time;
    }

    /**
     * 获取 类型  最新未读消息 添加时间 ，日期为最后一次登录时间为准
     * @author 龚湧
     * @param int $user_id
     * @param int $msg_type
     */
    public function getLatestNotReadedMsg($user_id,$msg_type,$last_logintime){
        $msg = ORM::factory("Ucmsg")
               ->where("user_id","=",$user_id)
               ->where("last_logintime","=",$last_logintime)
               ->where("read_time","=", 0)
               ->where("message_type","=",$msg_type)
               ->order_by("save_time","desc")
               ->find();
        return $msg;
    }


    /**
     * 获取未读消息总数量
     * @author 龚湧
     * @param int $user_id
     */
    public function getMsgCount($user_id){
        $group = message_type::msgGroup();
        $list_service= new Service_Redis_List();
        $pcount= 0;
        $ccount= 0;
        if( !empty( $group ) ){
            //获取用户类型
            $memcache= Cache::instance('memcache');
            $rs_user= $memcache->get('getMsgCount_user_info'.$user_id);
            //$rs_user= array();
            if( empty( $rs_user ) ){
                $service_user= new Service_User();
                $rs_user= $service_user->getUserInfoById($user_id);
                if( $rs_user!=false ){
                    $memcache->set('getMsgCount_user_info'.$user_id, $rs_user);
                    $user_type= $rs_user->user_type;
                }else{
                    $user_type= 0;
                }

            }else{
                $user_type= $rs_user->user_type;
            }


            if( $user_type=='2' ){
                foreach($group['person'] as $group_name=>$group_value){
                    $key= message_key::buildKey($user_id, $group_name);
                    $key_count= $list_service->getMsgCount($key);
                    $pcount= ceil($pcount)+ceil($key_count);
                }
            }

            if( $user_type=='1' ){
                foreach($group['company'] as $group_name=>$group_value){

                    $key= message_key::buildKey($user_id, $group_name);
                    $ck_count= $list_service->getMsgCount($key);
                    $ccount= ceil($ccount)+ceil($ck_count);
                }
            }

            $count= $pcount+$ccount;


        }else{
            $count= 0;
        }
        return $count;

    }


    /**
     * 更新企业用户消息提醒 用户中心顶部
     * @author 龚湧
     * @param int $user_id
     */
    public function updateCompanyMsgTips($user_id){
        $config =  Cookie::get("MSGTPS");
        //获取消息类型
        //1.名片信息 2.审核状态 3.账户中心
        $sort_count = array();
        $group = message_type::msgGroup();
        $list_service= new Service_Redis_List();
        foreach($group['company'] as $group_name=>$group_value){
            //redis
            $key= message_key::buildKey($user_id, $group_name);
            $count= $list_service->getMsgCount($key);
            $sort_count[$group_name] = (int)$count;
            //$sort_count[$group_name] = (int)$this->getMsgCountByType($user_id, $group_value);
        }

        if(!$config){//初始化
            $msg_show_flag = 1;
        }
        else{
            $org_config = json_decode(base64_decode($config),true);
            //初始化保存原始状态
            $msg_show_flag = $org_config['msg_show_flag'];
            $org_sort_count = $org_config['sort_count'];
            //某类型存在新消息，弹出
            foreach($sort_count as $type=>$count){
                if($count > Arr::get($org_sort_count,$type)){
                    $msg_show_flag = 1;
                    break;
                }
            }
        }
        $cookie = base64_encode(json_encode(array('sort_count'=>$sort_count,'msg_show_flag'=>$msg_show_flag)));
        Cookie::set("MSGTPS", $cookie,864000);
        return array('sort_count'=>$sort_count,'msg_show_flag'=>$msg_show_flag);
    }


    /**
     * 更新个人用户消息提醒 用户中心顶部 TODO 不同点后期修改
     * @author 龚湧
     * @param int $user_id
     */
    public function updatePersonMsgTips($user_id){
        $config =  Cookie::get("MSGTPS");
        //获取消息类型
        //1.名片信息 2.审核状态
        $sort_count = array();
        $group = message_type::msgGroup();
        //$group = message_type::msgGroup();
        $list_service= new Service_Redis_List();

        foreach($group['person'] as $group_name=>$group_value){
            $key= message_key::buildKey($user_id, $group_name);
            $count= $list_service->getMsgCount($key);
            $sort_count[$group_name]= (int)$count;
            //$sort_count[$group_name] = (int)$this->getMsgCountByType($user_id, $group_value);
        }


        if(!$config){//初始化
            $msg_show_flag = 1;
        }
        else{
            $org_config = json_decode(base64_decode($config),true);
            //初始化保存原始状态
            $msg_show_flag = $org_config['msg_show_flag'];
            $org_sort_count = $org_config['sort_count'];
            //某类型存在新消息，弹出
            foreach($sort_count as $type=>$count){
                if($count > Arr::get($org_sort_count,$type)){
                    $msg_show_flag = 1;
                    break;
                }
            }
        }
        $cookie = base64_encode(json_encode(array('sort_count'=>$sort_count,'msg_show_flag'=>$msg_show_flag)));
        Cookie::set("MSGTPS", $cookie,864000);
        return array('sort_count'=>$sort_count,'msg_show_flag'=>$msg_show_flag);
    }

    /**
     * 关闭消息提醒,相对独立
     * @author 龚湧
     */
    public function closeTips(){
        $config =  Cookie::get("MSGTPS");
        $org_config = json_decode(base64_decode($config),true);
        $sort_count = $org_config['sort_count'];
        $msg_show_flag = 0;
        $cookie = base64_encode(json_encode(array('sort_count'=>$sort_count,'msg_show_flag'=>$msg_show_flag)));
        Cookie::set("MSGTPS", $cookie,864000);
    }
}