<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业中心消息
 * @author 龚湧
 *
 */
class Controller_User_Company_Msg extends Controller_User_Company_Template{
    /**
    * 查看名片相关用户消息
    * @author 龚湧
    */
    public function action_index(){
        //默认消息列表
        $msg_group = message_type::msgGroup();

        $user = ORM::factory("User",$this->userId());
        $last_logintime = $user->last_logintime;
        $service = Service::factory("User_Ucmsg");

        //生成或更新消息
        $service->generateMsg($user->user_id,$last_logintime,$user->user_type);

        //标记所有未读消息已读
        $service->viewMsg($this->userId(),$msg_group['company']['cards']);

        $msg_list = $service->getMsgList($user->user_id,$msg_group['company']['cards']);

        //echo $service->getMsgCountByType($this->userId(),array(12));
        $content = View::factory("user/company/msg");
        $this->content->rightcontent = $content;

        //消息类表赋值
        $content->msg_list = $msg_list;

    }

    /**
     * 审核状态
     * @author 龚湧
     */
    public function action_auditStatus(){
        $msg_group = message_type::msgGroup();

        $user = ORM::factory("User",$this->userId());
        $last_logintime = $user->last_logintime;
        $service = Service::factory("User_Ucmsg");

        //生成或更新消息
        $service->generateMsg($user->user_id,$last_logintime,$user->user_type);

        //标记所有未读消息已读
        $service->viewMsg($this->userId(),$msg_group['company']['audit']);

        $msg_list = $service->getMsgList($user->user_id,$msg_group['company']['audit']);

        $content = View::factory("user/company/msgauditstatus");
        $this->content->rightcontent = $content;


        //消息类表赋值
        $content->msg_list = $msg_list;
    }

    /**
     * 账户信息 TODO待完善
     * @author 龚湧
     */
    public function action_account(){
        $msg_group = message_type::msgGroup();

        $user = ORM::factory("User",$this->userId());
        $last_logintime = $user->last_logintime;
        $service = Service::factory("User_Ucmsg");

        //生成或更新消息
        $service->generateMsg($user->user_id,$last_logintime,$user->user_type);

        //标记所有未读消息已读
        $service->viewMsg($this->userId(),$msg_group['company']['account']);

        $msg_list = $service->getMsgList($user->user_id,$msg_group['company']['account']);

        $content = View::factory("user/company/msgaccount");
        $this->content->rightcontent = $content;
        //消息类表赋值
        $content->msg_list = $msg_list;
    }

    /**
     * 诚信消息
     * @author 龚湧
     */
    public function action_integrity(){
        $msg_group = message_type::msgGroup();

        $user = ORM::factory("User",$this->userId());
        $last_logintime = $user->last_logintime;
        $service = Service::factory("User_Ucmsg");

        //生成或更新消息
        $service->generateMsg($user->user_id,$last_logintime,$user->user_type);

        //标记所有未读消息已读
        $service->viewMsg($this->userId(),$msg_group['company']['integrity']);

        $msg_list = $service->getMsgList($user->user_id,$msg_group['company']['integrity']);

        $content = View::factory("user/company/msgintegrity");
        $this->content->rightcontent = $content;
        //消息类表赋值
        $content->msg_list = $msg_list;
    }

    /**
     * 删除消息
     * @author 龚湧
     */
    public function action_del(){
        $msg_id = Arr::get($this->request->query(), "msg_id");
        $msg = Service::factory("User_Ucmsg");
        if($msg_id){
            //更新状态，跳转到列表页
            if($msg->changeMsgState($this->userId(),$msg_id,0)){
                //更新成功,跳转到查看页
                $message = ORM::factory("Ucmsg",$msg_id);
            }
        }
        self::redirect($this->request->referrer());
    }

    /**
     * 删除页面所有消息
     * @author 龚湧
     */
    public function action_delAll(){
        $group = Arr::get($this->request->query(),"group");
        $msg_types = message_type::msgGroup();
        $msg_group = Arr::get($msg_types['company'], $group);
        if($msg_group){
            $ucmsg = Service::factory("User_Ucmsg");
            $ucmsg->batchDelMsg($this->userId(),$msg_group);
        }
        $group_url = message_type::groupUrl();
        self::redirect($group_url['company'][$group]);
    }

    /**
     * 阅读单条消息
     */
    public function action_read(){
        $msg_id = Arr::get($this->request->query(), "msg_id");
        $msg = Service::factory("User_Ucmsg");
        if($msg_id){
            //更新状态，跳转到列表页
            if($msg->changeMsgState($this->userId(),$msg_id,2)){
                //更新成功,跳转到查看页
                $message = ORM::factory("Ucmsg",$msg_id);
                self::redirect($message->to_url);
            }
        }
        self::redirect("company/member/msg");
    }


    /**
     *个人消息列表
     * @author许晟玮
     */
    public function action_msglist(){
        $cache= Cache::instance('memcache');
        $cache_name= "company_msg_group_type_array";
        $content = View::factory("user/company/newmsg");
        $this->content->rightcontent = $content;

        $user_id= $this->userId();

        $service = new Service_Redis_List();
        //获取类型组
        $getarr = Arr::map("HTML::chars", $this->request->query());
        $stype= Arr::get($getarr, 'stype','all');
        if( $stype=='all' || $stype=='' ){
            //全部
            $all_key= $cache->get($cache_name);
            $all_key= array();
            if( empty( $all_key ) ){
                $msg_group= message_type::msgGroup();
                $msg_group_person= $msg_group['company'];
                //生成全部的key
                $all_key= array();
                foreach( $msg_group_person as $kg=>$vs_msg_group_person ){
                    $all_key[]= message_key::buildKey($user_id, $kg);
                    //重置计数器
                    $service->resetMsgCounter(message_key::buildKey($user_id, $kg));
                }
                //set memcache
                $cache->set($cache_name, $all_key);
            }else{
            }
            $result= $service->getMltMsgList( $all_key );
        }else{
            //生成key
            $search_key= message_key::buildKey($user_id, $stype);
            $result= $service->getMsgList( $search_key );
            //重置计数器
            $service->resetMsgCounter($search_key);
        }



        //获取类型组对应的ID
        $msggroupdy_info= $cache->get('msggroupdy_info');
        if( empty( $msggroupdy_info ) ){
            $msggroupdy_info= message_type::msggroupdy();
            $cache->set('msggroupdy_info',$msggroupdy_info);
        }


        $msggroupcalss_info= $cache->get('msggroupcalss_info');
        if( empty( $msggroupcalss_info ) ){
            $msggroupcalss_info= message_type::msggroupcalss();
            $cache->set('msggroupcalss_info',$msggroupcalss_info);
        }


        //删除单个消息
        if( Arr::get($getarr, 'action')=='del' ){
            $del_id= Arr::get($getarr, 'id');
            $msg_type= Arr::get($getarr, 'msg_type');
            if( ceil($del_id)==0 ){
                self::redirect( '/company/member/msg/msglist' );
            }else{
            }
            //删除
            $key= message_key::buildKey($user_id, $msg_type);
            $service->delMsgFromList($key, $del_id,array( "message_state"=>"0" ));
            self::redirect( '/company/member/msg/msglist?stype='.$stype );
        }else{
        }

        //删除全部消息
        if( Arr::get($getarr, 'action')=='delall' ){
            if( $stype=='all' ){
                foreach( $all_key as $v ){

                    //重置计数器
                    $service->resetMsgCounter($v);
                    $service->delList($v);
                }

            }else{

                //重置计数器
                $service->resetMsgCounter($search_key);
                $service->delList($search_key);
            }
            self::redirect( '/company/member/msg/msglist?stype='.$stype );

        }else{
        }

        $list= $result['list'];
        $page= $result['page'];
        $content->msg_list = $list;
        $content->msg_page = $page;
        $content->msggroupdy_info= $msggroupdy_info;
        $content->msggroupcalss_info= $msggroupcalss_info;
        //$content->msggroupurlname_info= $msggroupurlname_info;
        $content->stype= $stype;
        $content->ust= 'company';
        $content->cardsnum= $service->getKeyLen( message_key::buildKey($user_id, 'cards') );
        $content->projectnum= $service->getKeyLen( message_key::buildKey($user_id, 'project') );
        $content->auditnum= $service->getKeyLen( message_key::buildKey($user_id, 'audit') );
        $content->servernum= $service->getKeyLen( message_key::buildKey($user_id, 'server') );
        $content->accountnum= $service->getKeyLen( message_key::buildKey($user_id, 'account') );


    }
    //end function
}