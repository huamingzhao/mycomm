<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 【企业用户】名片+咨询/留言信息
 * author: 兔毛 2014-05-24
 */
class Controller_User_Company_QuickPublish_Card extends  Controller_User_Company_QuickPublish_Basic{

    /**
     * 【企业用户】收到的咨询/留言
     * @author：兔毛 2014-05-24
	 */
    public function action_receiveCard(){
        $service=new Service_User_Company_User();
        $card_service= new Service_QuickPublish_Card();
        //获取登录user_id
        $userid = $this->userInfo()->user_id;
        //判断企业用户是否收到过名片
        if($card_service->getFromOrToCardCount($userid,'to')){
            //view页面加载 已收到名片
            $content = View::factory("quickPublish/company/personinfo");
            //获取页面post表单值
            $search = $this->request->query();
            //2级取得行业
            $list_industry2=array();
            if(isset($search['parent_id']) && is_numeric($search['parent_id'])){
                $list_industry2s =common::primaryIndustry($search['parent_id']);
                foreach ($list_industry2s as $k=>$v){
                    $list_industry2[$k]['industry_id']=$v->industry_id;
                    $list_industry2[$k]['industry_name']=$v->industry_name;
                }
            }
            //project_id项目id可选
            $project_id =isset($_REQUEST['project_id'])?$_REQUEST['project_id']:0;
            if(preg_match("/[^\d-., ]/",$project_id) || empty($project_id)){
            	$project_id=0;
            }
            //获取我收到投资者的名片信息列表
            $return_arr=$card_service->searchReceiveCardInfo($search,$userid,$project_id);
            $per_service = new Service_User_Person_User();
            $inv_ser = new Service_Platform_SearchInvestor();
            foreach($return_arr['list'] as $k=>$v){
                //获得个人意向投资地区
                $return_arr['list'][$k]['per_area'] = $per_service->getPersonalArea($v['per_user_id']);
                //获取个人所在地，只获取省份
                $return_arr['list'][$k]['locate_per_area'] = $per_service->getPerasonalAreaStringOnlyPro($v['per_user_id']);
                //活跃度
                $return_arr['list'][$k]['huoyuedu'] = $inv_ser->getAllScore($v['per_user_id']);//活跃度
            }
            $content->list_industry2=$list_industry2;
            $content->list=$return_arr['list'];
            $content->page= $return_arr['page'];
            $content->postlist=$search;
        }
        else{
            //view页面加载 未收到名片
            $content = View::factory("quickPublish/company/noreceivecard");
        }
        $this->content->rightcontent = $content;
    }


    /**
	 * 更改我收到的名片为已删除状态
	 * @author 钟涛
	 */
	public function action_updateReceiveDelStatus(){
		$get = Arr::map("HTML::chars", $this->request->query());
		$card_service=new Service_QuickPublish_Card();
		$result = $card_service->updateReceiveDelStatus(arr::get($get,'id'));
		if($result){
			self::redirect("/company/quick/card/receiveCard");
		}
	}
	
	/**
	 * 批量更改我收到的名片为已删除状态
	 * @author 钟涛
	 */
	public function action_updateBatchReceiveDelStatus(){
		$postdata=Arr::map("HTML::chars", $this->request->query());
		$card_service=new  Service_QuickPublish_Card();
		$cardidarr=array();
		//名片id数组
		$cardidarr= explode(",", $postdata['cardidarr']);
		//更改我收到的名片未已删除状态
		$card_service->updateBatchReceiveDelStatus($cardidarr);
		self::redirect("/company/quick/card/receiveCard");
	}
   
}