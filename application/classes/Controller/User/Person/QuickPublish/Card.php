<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户名片+咨询/留言信息
 * author: 兔毛 2014-05-24
 */
class Controller_User_Person_QuickPublish_Card extends  Controller_User_Person_QuickPublish_Basic{

    /**
     * 【个人】发出的咨询/留言
     * @author：兔毛 2014-05-23
     */
    public function action_historyConsult(){
        $content = View::factory("quickPublish/person/history_consult");
        $this->content->rightcontent = $content;
        //获取登录的user_id
        $userid = $this->userId();
        if(!$userid){
            exit;
        }
        //project_id项目id可选
        $project_id =isset($_REQUEST['project_id'])?$_REQUEST['project_id']:0;  
        if(preg_match("/[^\d-., ]/",$project_id) || empty($project_id)){
        	$project_id=0;
        }
        $project_service = new Service_QuickPublish_ProjectComplaint();
        //获取金额的
        $monarr = common::moneyArr();
        $consult_list = array();
        $service = new Service_QuickPublish_Card();
        $return_arr = $service->getHistoryConsult($userid,$project_id);  //咨询列表
        if(count($return_arr['list'])>0){
            foreach($return_arr['list'] as $value){
                //获取地区
                $project_id=$value->to_project_id;
                $projectinfo=$project_service->getProjectInfo($project_id,0); //通用获取项目
                //d($projectinfo);
                $consult_list[] =array($project_id,
                		$projectinfo['project_area_name'],
                		$projectinfo['project_logo'],
                		$projectinfo['project_brand_name'],
                		$projectinfo['project_amount_type_name'],
                		$value->content,
                		date('Y-m-d H:i:s', $value->add_time),
                		$projectinfo['project_status']);
            }
        }
        $this->content->rightcontent->page = $return_arr['page'];
        $this->content->rightcontent->consult_list = $consult_list;
    }//end function
   
}