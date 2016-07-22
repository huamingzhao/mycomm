<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 快速发布项目
 * @author TIM (JIYE)
 */
class Controller_QuickPublish_FastReleaseProject extends Controller_QuickPublish_Basic{

	/**
	 * 显示快速添加发布项目
	 * @author Tim(jiye)
	 * @param
	 * @create_time  2014-5-8
	 * @return int/bool/object/array
	 */
	public function action_ShowAddFastReleaseProject(){
	   $get = Arr::map("HTML::chars", $this->request->query());
		$content = View::factory('quickPublish/fastreleaseproject');
		//var_dump($this->userId());exit;
		//是否登录
		$user_id = Cookie::get("user_id");
                $token = Cookie::set('publish', md5(md5(date('Y-m-d', time()).'shilei')));
		$data = Service_Sso_Client::instance()->getUserInfoById($user_id);
		$session = Session::instance();
		//地区
		$area = array('pro_id' => 0);
		$content->areas = common::arrArea($area)	;	
		//品牌历史
		$content->arr_project_history = common::projectHistory();
		$service = new Service_QuickPublish_ProjectComplaint();
		$content->type = arr::get($get,"type",1);
		$content->userinfo = $data ? @(array)$data:array();
		$content->project_title = htmlspecialchars_decode("免费发布生意");
		$content->mobile_num = $session->get("mobile_num")?$session->get("mobile_num"):0;
		$session->set("mobile_num","");
		$content->user_id = Cookie::get("user_id");
		$this->template->whereAreYou = array($content->project_title => '');
		$this->template->content = $content;
		$content->user_status = arr::get($get,"status",0);
		$content->isHaveFaBuProject = $service->isHaveFaBuProject();
		//var_dump($service->isHaveFaBuProject());
	}
	
	/**
	 * 执行快速添加
	 * @author Tim(jiye)
	 * @param
	 * @create_time  2014-5-8
	 * @return int/bool/object/array
	 */
	
	public function action_DoAddFastReleaseProject(){
		$post = Arr::map("HTML::chars", $this->request->post());
		//echo "<pre>"; print_r($post);exit;
		if(arr::get($post,"project_brand_name","") == "" || arr::get($post,"merchants_area_first_id","") == "" || arr::get($post, "first_industry_id","") == "" || arr::get($post, "project_amount_type","") == "" || (isset($post['project_city']) and count($post['project_city']) == 0) || (isset($post['project_model_type']) and count($post['project_model_type']) == 0) || arr::get($post,"project_title","") == "" || arr::get($post,"project_summary","") == "" || arr::get($post,"mobile_num","") == ""){
			self::redirect("/quick/FastReleaseProject/ShowAddFastReleaseProject");exit;
		}
		$Fastreleaseproject_service = new Service_QuickPublish_FastReleaseProject();
		$arr_project_data = array();
		$type = 1;
		//执行添加
		$add_time = Cookie::get("project_addtime");
		$int_now_time = time();
		if($add_time){
			$int_new_time =  date("i",($int_now_time - $add_time));
			if($int_new_time <= '05'){
				self::redirect("/quick/FastReleaseProject/ShowAddFastReleaseProject");exit;
			}
		}
                
                if(!intval(arr::get($post, 'mobile_num' ,0))) {
                    self::redirect("/quick/FastReleaseProject/ShowAddFastReleaseProject");exit;
                }
                $token = Cookie::get("publish");
                $publish =  md5(md5(date('Y-m-d', time()).'shilei'));
                
                #加一道防护
                if($token != $publish) {
                    self::redirect("/quick/FastReleaseProject/ShowAddFastReleaseProject");exit;
                }
                
		$bool = $Fastreleaseproject_service->DoFastProject($post);
		if($bool == true){
			$type = 2;
			$session = Session::instance();
			$session->set("mobile_num", arr::get($post,"mobile_num"));
			Cookie::set("project_addtime", time());
		}
		if(arr::get($post,"project_status") == 2){
			self::redirect("/quick/FastReleaseProject/ShowAddFastReleaseProject?type=".$type."&status=".arr::get($post,"project_status"));exit;
		}else{
			self::redirect("/quick/FastReleaseProject/ShowAddFastReleaseProject");exit;
		}
	}
	/**
	* 版规说明
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-11
	* @return int/bool/object/array
	*/
	public function action_ruleDescription(){
		$content = View::factory('quickPublish/ruledescription');
		$this->template->content = $content;
	}
}