<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 项目官网controller
 * @author 郁政
 *
 */
class Controller_Platform_ExhbProject extends Controller_Platform_Template{
	/**
	 * 项目官网详情页面
	 * @author 郁政
	 *
	 */
	public function action_index(){
		$get = Arr::map("HTML::chars", $this->request->query());
    	$project_id = Arr::get($get, 'project_id',0);
    	#实例化
    	$service = new Service_Platform_ExhbProject();    	
        $serviceExhb = new Service_User_Company_Exhb();
        $proInfo = $serviceExhb->getProject($project_id);  
        $exhb_id = $proInfo['exhibition_id'];
		$exhbInfo = $serviceExhb->exhbInfoById($exhb_id);      
        $status = isset($proInfo['project_status']) ? $proInfo['project_status'] : 0;
        $service_User_Company = new Service_User_Company_Exhb();
        //获取登录user_id
	    $user_id = $this->loginUserId();
	    //企业基本信息
		$servicePro = new Service_Platform_Project();
    	$companyinfo = $servicePro->getCompanyByProjectID2($proInfo['outside_id']);
     	$com_user_id = isset($companyinfo->com_user_id) ? $companyinfo->com_user_id : 0;
        if($status == 1 || ($status != 1 && $user_id == $com_user_id)){
        	$content = View::factory('platform/exhibition/project_info');
			$this->content->maincontent = $content;
			//展会是否过期
			$guoqi = false;
			$guoqi = (isset($exhbInfo['exhibition_end']) && $exhbInfo['exhibition_end'] < time()) ? true : false; 
			//获取客服信息
			$arr_kefu_data = $service_User_Company->getCommunication(arr::get($proInfo, "com_id"));		
			//echo "<pre>"; print_R($arr_kefu_data);exit;
			$res = $service->getProjectInfo($project_id);
			$tuijian = array();
			$tuijian = $service->getTuiJianPro($project_id,5);
			$youhuijuan = $service->getYouHuiJuanInfo($project_id);
			//echo "<pre>";print_r($tuijian);exit;
			//echo "<pre>";print_r($res);exit;	
			
            $content->arr_kefu_data = $arr_kefu_data;
			$content->forms = $res;
			$content->tuijian = $tuijian;
			$content->exhb_id = $exhb_id;
			$content->project_id = $project_id;
			$content->yhjInfo = $youhuijuan;
			$content->com_user_id = $com_user_id;
			$content->guoqi = $guoqi;
			//意向加盟人数
			$content->yixiangNum = $service->getYiXiangPeopleNum($project_id);
			//播报
			//echo "<pre>";print_r($service->getXiangQingBoBao($project_id));exit;
            $content->bobao = $service->getXiangQingBoBao($project_id);  
            $content->open = arr::get($get,"open",0);            
			//seo
			$pro_name = arr::get($proInfo, 'project_brand_name', '');
	        $content->title = $pro_name."招商加盟详情_一句话招商加盟展会网";
	      	$content->keywords = $pro_name."招商加盟详情，招商加盟展会，一句话招商展会网";
	      	$content->description = "一句话招商展会网".$pro_name."招商加盟详情介绍。";
        }else{      	
            self::redirect("platform/index/error404");
        }
	}
	
	/**
	 * 领取项目优惠劵
	 * status表示状态码,201表示未登陆，202表示非个人用户，203表示项目已过期，204表示优惠劵已领完
	 * 205表示该用户已领过,200表示领取成功
	 *@author 郁政
	 */
	public function action_getYouHuiQuan(){
		if($this->request->is_ajax()){
			$res = array();
			$res['num'] = 0;
			$res['date'] = '';
			$post = Arr::map("HTML::chars", $this->request->post());
			$project_id = Arr::get($post, 'project_id');
			if($this->loginUser()){
				#实例化
	    		$service = new Service_Platform_ExhbProject();
				$service_user = new Service_User();
				//获取登录user_id
	        	$user_id = $this->userid();  
	        	$userinfo = $service_user->getUserInfoById($user_id); 
	        	if($userinfo->user_type != 2){
	        		$res['status'] = '202';
	        	}else{
	        		$res = $service->getYouHuiQuan($project_id, $user_id);
	        	}
			}else{
				$res['status'] = '201';
			}
			echo json_encode($res);	exit;
		}			
	}
	
	/**
	 * 领取项目红包
	 * status表示状态码,201表示未登陆，202表示非个人用户，203表示展会已过期，204表示红包已领完
	 * 205表示该用户已领过,200表示领取成功
	 *@author 郁政
	 */
	public function action_getHongBao(){
		if($this->request->is_ajax()){
			$res = array();
			$res['num'] = 0;
			$res['date'] = '';
			$post = Arr::map("HTML::chars", $this->request->post());
			$exhb_id = Arr::get($post, 'exhb_id');
			if($this->loginUser()){
				#实例化
	    		$service = new Service_Platform_ExhbProject();
				$service_user = new Service_User();
				//获取登录user_id
	        	$user_id = $this->userid();  
	        	$userinfo = $service_user->getUserInfoById($user_id); 
	        	if($userinfo->user_type != 2){
	        		$res['status'] = '202';
	        	}else{
	        		$res = $service->getHongBao($exhb_id, $user_id);
	        	}
			}else{
				$res['status'] = '201';
			}
			echo json_encode($res);	exit;	
		}		
	}
	
	/**
	 * 项目官网点击记录
	 * @author 郁政
	 *
	 */
	public function action_exhbProClick(){
		if($this->request->is_ajax()){
			$bool = false;
			$post = Arr::map("HTML::chars", $this->request->post());
			$project_id = Arr::get($post, 'project_id');
			if($project_id){
				#实例化
		    	$service = new Service_Platform_ExhbProject();
		    	//获取登录user_id
		    	if($this->loginUser()){
		    		$user_id = $this->userid();  
		    	}else{
		    		$user_id = 0;
		    	}
		        $service->addProStatistics($project_id,$user_id,1);
		        $bool = true;
			}	
			echo json_encode($bool);	
	        exit;
		}
	}
}
?>