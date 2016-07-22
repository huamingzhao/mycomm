<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * 快速发布
 * @author 郁政
 *
 */
class Controller_QuickPublish_Project extends Controller_QuickPublish_Basic{
	
	public function before()
    {
        parent::before();
        $get = Arr::map("HTML::chars", $this->request->query());
        $post = Arr::map("HTML::chars", $this->request->post());
        $service = new Service_QuickPublish_Project();
        if(isset($get['project_id']) && $get['project_id']){
        	if($this->userInfo())
        	{
	        	$user_id = $this->userInfo()->user_id;
	        	if(!$service->isBelongToUser($get['project_id'], $user_id) && !isset($get['view'])){
	        		self::redirect("platform/index/error404");
	        	}
        	}
        }
    	if(isset($post['project_id']) && $post['project_id']){
    		  if($this->userInfo())
    		  {
	        	$user_id = $this->userInfo()->user_id;
	        	if(!$service->isBelongToUser($post['project_id'], $user_id) && !isset($get['view'])){
	        		self::redirect("platform/index/error404");
        	  }
    		}
        }
    }
    
    /**
     * 通用：404错误
     * author： 兔毛 2014-05-21
     */
    public function common_error()
    {
    	self::redirect("platform/index/error404");
    	$this->template->title = "您的访问出错了_404错误提示_一句话";
    	$this->template->keywords = "您的访问出错了,404";
    	$this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
    }
    
    
    /**
     * 通用：用户Email+Mobile是否验证了
     * param $user_id： 用户id
     * param $company_obj: service对象
     * author： 兔毛 2014-05-21
     */
    public function common_is_valid($user_id,$company_obj)
    {
    	$valid_info=null;
    	if(!empty($user_id))
    	{
	    	$user_info=Service_Sso_Client::instance()->getUserInfoById($user_id);//84667
	    	if($user_info)
	    	{
	    		$valid_info['valid_mobile']=$user_info->valid_mobile;
	    		$valid_info['valid_email']=$user_info->valid_email;
	    		$valid_info['user_photo']=$user_info->user_portrait;  //用户头像
	    		$valid_info['user_name']=$user_info->user_name;
	    		$user_gender='未知';
	    		if($user_info->user_gender==1)
	    			$user_gender="先生";
	    		if($user_info->user_gender==2)
	    			$user_gender="女士";
	    		$valid_info['user_gender']=$user_gender;
	    		$valid_info['valid_auth_status']=$company_obj->getUserAuthStatus($user_id); //身份认证
	    	}
    	}
    	return $valid_info;
    }
    
   
   
    
    /**
     * 项目官网
     * author: 兔毛  2014-05-20
     */
    public function action_projectinfo()
    {      	  	
    	$search = Arr::map("HTML::chars", $this->request->query());
    	$project_id=arr::get($search,'project_id');
    	if (!intval($project_id)) {
    		$this->common_error();
    	}
    	else
    	{
    		$project_status = arr::get($search,"status",2);
    		$key = arr::get($search, "key","");
    		if($project_status || $key){
    			if($key){
    				$time = strtotime(date("Y-m-d")).$project_id;
    				if(md5(md5($time)) != $key){
    					$this->common_error();exit;
    				}
    			}
    		}
    		$project_obj=new Service_QuickPublish_ProjectComplaint();
    		$is_has_project=$project_obj->is_has_project_info($project_id,$project_status);
    		if($is_has_project==0)
    		{
    			$this->common_error();
    		}
    		$content = View::factory('quickPublish/projectinfo');
	    	$this->template->content = $content;
	    	//后台预览敏感词标红
	    	$from = arr::get($search,'from',0);
	    	$project_info=$project_obj->getProjectInfo($project_id,$project_status,'暂无信息',$from);
	    	/*判断 是否登录 Start*/
	    	$login_user_id=$user_id=0;
	    	$company_info=$person_info=null;
	    	
	    	$is_login=$this->isLogins();
	    	if($is_login)
	    	{
	    		$login_user_id=$this->userId();
	    	}
	    	$isApprovings=$project_obj->isApprovings($project_id, $login_user_id); //是否已经点击了赞 true=点了，false=没点
	    	$content->is_print_zan=$isApprovings;
	    	$content->is_login=$is_login;
	    	$content->login_user_id=$login_user_id;
	    	/*判断 是否登录 End*/
	    	$content->project_title = htmlspecialchars_decode($project_info['project_title']);
	    	$this->template->whereAreYou = array($content->project_title => '');  //当前位置
	    	$project_img_info=$project_obj->getProjectImagesById($project_id,1);  //循环播放图片
	    	$content->project_img_info=$project_img_info;
	    	$content->project_info=$project_info;
	    	
	    	$company_obj=new Service_QuickPublish_Company();
	    	/*通过手机id,得到项目发布人的user_id Start*/
	    	$mobile_id=$project_info['mobile_id'];
	    	if(!empty($mobile_id))
	    	{
	    		$info=$company_obj->getMobileAccountInfo($mobile_id);  //手机授权的信息
	    		$user_id=empty($info['user_id'])?0:$info['user_id'];   //项目发布用户id
	    		$content->project_publish_user_id=$user_id;
	    	}
	    	//echo '登录用户id：'.$login_user_id.'; 项目发布用户id：'.$user_id;
	    	/*通过手机id,得到项目发布人的user_id End*/
	    	if(!empty($project_info['com_id']))  //企业发布的项目
	    	{
	    		$company_id=$project_info['com_id'];
	    		$info=$company_obj->getCompanyInfo($company_id);
	    		/*企业会员是否认证 Start*/
	    		$user_id=empty($info['com_user_id'])?0:$info['com_user_id'];
	    		$com_user_id=$user_id;
	    	    $valid_info=$this->common_is_valid($user_id,$company_obj); 
	    		if(!empty($valid_info))
	    		{
	    			$company_info=array_merge($info,$valid_info);
	    		}
	    		/*企业会员是否认证 End*/
	    	}
	    	else
	    	{
	    		//普通用户的信息
	    		$valid_info=$this->common_is_valid($user_id,$company_obj);
	    		if(!empty($valid_info))
	    			$person_info=$valid_info;
	    		$com_user_id=$user_id;
	    	}
	    	$content->company_info=$company_info;
	    	$content->person_info=$person_info;
	    	$content->project_id=$project_id;
	    	$content->com_user_id=$com_user_id;
	    	$tuijan_obj=new Service_QuickPublish_Project();
			$tuijian_info=$tuijan_obj->getTuiJianPro($project_id, 5);  //招商加盟推荐36048
			$content->tuijian_info=$tuijian_info;
			//print_r($project_info);    
			//SEO
			$this->template->title = '【荐】'.zixun::setContentReplace($project_info['project_title']).'_'.$project_info['project_area_name'].$project_info['project_industry_name'].'加盟_一句话商机网';
	    	$this->template->keywords = zixun::setContentReplace($project_info['project_title']);
	    	$this->template->description = zixun::setContentReplace($project_info['project_title']).'来自'.$project_info['project_brand_birthplace_city_name'].$project_info['project_industry_name'].'，有'.$project_info['project_history_name'].$project_info['project_model_name'].'历史，只需要'.$project_info['project_security_deposit'].'即可加盟。'.mb_substr(zixun::setContentReplace($project_info['project_summary']), 0,90);
    		//热门城市
   			$service_pro=new Service_QuickPublish_Project();
	        $hot_city = $service_pro->getHotCity($project_id, 20);
	        $content->hot_city=$hot_city;
	        //热门类目
			$hot_type= $service_pro->getHotIndustry($project_id, 10);
			$content->hot_type=$hot_type;
	        
	        //echo "<pre>";print_r($hot_city);exit;
    	}
    }
	
	/**
	 * 项目列表
	 * @author 郁政
	 *
	 */
    public function action_showProject(){
    	#获取用户id
		$user_id = $this->userId();
		if($user_id){			
			#加载模版
	        $content = View::factory("quickPublish/projectlist");
	        $this->template->content = $content;
	        $service = new Service_User();
	        //用户信息
	        $userinfo = $service->getUserInfoById($user_id);
	        $isValidMobile = (isset($userinfo->valid_mobile) && $userinfo->valid_mobile == 1) ? true : false;
	        $isValidMail = (isset($userinfo->valid_email) && $userinfo->valid_email == 1) ? true : false;
			$content->isValidMobile = $isValidMobile;
			$content->isValidMail = $isValidMail;
			$service_user_com = new Service_User_Company_User();
			//$com_zizhi = $service_user_com->getCompanyAuthCount($user_id) ? true : false;
			$isCompany = (isset($userinfo->user_type) && $userinfo->user_type == 1) ? true : false;
			//用户类型
			$user_type = isset($userinfo->user_type) ? $userinfo->user_type : 0;			
			//$content->com_zizhi = $com_zizhi;
			$content->isCompany = $isCompany;
			$content->user_type = $user_type;
			if($user_type == 2){
				$user = $this->userInfo(true);
		        //身份证验证是否通过审核
	        	$content->identificationCard_status= isset($user->user_person->per_auth_status) ? $user->user_person->per_auth_status : 0;
			}	
			if($user_type == 1){
				$user = $this->userInfo(true);
				$service_com = new Service_User_Company_User();
				//企业资质认证
				$com_id = isset($user->user_company->com_id) ? $user->user_company->com_id : 0;
				$content->validCerts = $service_com->checkCompanyCertificationStatus($com_id);
			}		
			//获取项目信息
			$service_quick = new Service_QuickPublish_Project();
			$list = array();
			$list = $service_quick->getQuickProjectList($user_id);
			//echo "<pre>";print_r($list['page']);exit;			
			$content->list = Arr::get($list, 'list');
			$content->page = Arr::get($list, 'page');
			//企业认证状态
			$renzheng_status = $service_quick->getQiYeUpdateStatus($user_id);
			$content->renzheng_status = $renzheng_status;
			$this->template->whereAreYou = array('我的生意' => '');
		}
    }
    
    /**
	 * 项目详情
	 * @author 郁政
	 *
	 */
    public function action_showProjectDetail(){
    	#获取用户id
		$user_id = $this->userId();
		$get = Arr::map("HTML::chars", $this->request->query());
		$project_id = Arr::get($get, 'project_id');
		$service = new Service_QuickPublish_Project();
		if($service->isBelongToUser($project_id, $user_id)){
			#加载模版
	        $content = View::factory("quickPublish/showprojectdetail");
	        $this->template->content = $content;
	        $res = array();
	        $res = $service->proDetailInfo($project_id);
	        //echo "<pre>";print_r($res);exit;
	        $content->forms = $res;	
	        $this->template->whereAreYou = array('我的生意' => '/quick/project/showProject','修改生意信息' => '');        
		}else{
			self::redirect("/quick/project/showProject");
		}
    }
    
    /**
     * 快速发布项目基本信息页
     * @author 郁政
     */
	public function action_showQuickBasic(){
		$content = View::factory("quickPublish/showprojectbasic");
    	$this->template->content = $content;
    	$service = new Service_QuickPublish_Project();
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$project_id = Arr::get($get, 'project_id',0);   
    	//地区
		$area = array('pro_id' => 0);
		$content->areas = common::arrArea($area);
		//品牌历史
		$content->arr_project_history = common::projectHistory();
		$res = array();
        $res = $service->showQuickBasic($project_id);
        //echo "<pre>";print_r($res);exit;
        $content->forms = $res;		 
        $this->template->whereAreYou = array('我的生意' => '/quick/project/showProject','修改生意信息' => '/quick/project/showProjectDetail?project_id='.$project_id , '修改基本信息' => '');	  
	}
	
	/**
     * 修改项目基本信息
     * @author 郁政
     */
	public function action_editQuickBasic(){
		$form = Arr::map("HTML::chars", $this->request->post());
		$project_id =  Arr::get($form, 'project_id');
		$service = new Service_QuickPublish_Project();
		$res = $service->editQuickBasic($form);
		if($res){
			self::redirect("/quick/project/showProjectDetail?project_id=".$project_id);
		}
	}
	
	/**
     * 快速发布项目加盟信息页
     * @author 郁政
     */
	public function action_showQuickJiaMeng(){
		$content = View::factory("quickPublish/showprojectjiameng");
    	$this->template->content = $content;
    	$service = new Service_QuickPublish_Project();
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$project_id = Arr::get($get, 'project_id',0);   
    	//地区
		$area = array('pro_id' => 0);
		$content->areas = common::arrArea($area);
		$res = array();
        $res = $service->showQuickJiaMeng($project_id);
        //echo "<pre>";print_r($res);exit;
        $content->forms = $res;		 	  
        $this->template->whereAreYou = array('我的生意' => '/quick/project/showProject','修改生意信息' => '/quick/project/showProjectDetail?project_id='.$project_id , '修改加盟信息' => '');
	}
	
	/**
     * 修改加盟信息
     * @author 郁政
     */
	public function action_editQuickJiaMeng(){
		$form = Arr::map("HTML::chars", $this->request->post());
		$project_id =  Arr::get($form, 'project_id');
		$service = new Service_QuickPublish_Project();
		$res = $service->editQuickJiaMeng($form);
		if($res){
			self::redirect("/quick/project/showProjectDetail?project_id=".$project_id);
		}
	}
	
	/**
     * 快速发布项目推广信息页
     * @author 郁政
     */
	public function action_showQuickTuiGuang(){
		$content = View::factory("quickPublish/showprojecttuiguang");
    	$this->template->content = $content;
    	$service = new Service_QuickPublish_Project();
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$project_id = Arr::get($get, 'project_id',0);   
    	$res = array();
        $res = $service->showQuickTuiGuang($project_id);
        //echo "<pre>";print_r($res);exit;
        $content->forms = $res;	
        $this->template->whereAreYou = array('我的生意' => '/quick/project/showProject','修改生意信息' => '/quick/project/showProjectDetail?project_id='.$project_id , '修改推广信息' => '');	 	  
	}
	
	/**
     * 修改推广信息
     * @author 郁政
     */
	public function action_editQuickTuiGuang(){
		$form = Arr::map("HTML::chars", $this->request->post());
		$project_id =  Arr::get($form, 'project_id');
		$service = new Service_QuickPublish_Project();
		$res = $service->editQuickTuiGuang($form);
		if($res){
			self::redirect("/quick/project/showProjectDetail?project_id=".$project_id);
		}
	}
	
	/**
     * 快速发布项目联系人信息页
     * @author 郁政
     */
	public function action_showQuickLianXiRen(){
		$content = View::factory("quickPublish/showprojectlianxiren");
    	$this->template->content = $content;
    	$service = new Service_QuickPublish_Project();
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$project_id = Arr::get($get, 'project_id',0);   
    	$res = array();
        $res = $service->showQuickLianXiRen($project_id);
        //echo "<pre>";print_r($res);exit;
        $content->forms = $res;		 
        $this->template->whereAreYou = array('我的生意' => '/quick/project/showProject','修改生意信息' => '/quick/project/showProjectDetail?project_id='.$project_id , '修改联系人信息' => '');	  
	}
	
	/**
     * 修改联系人信息
     * @author 郁政
     */
	public function action_editQuickLianXiRen(){
		$form = Arr::map("HTML::chars", $this->request->post());
		$project_id =  Arr::get($form, 'project_id');
		$service = new Service_QuickPublish_Project();
		$res = $service->editQuickLianXiRen($form);
		if($res){
			self::redirect("/quick/project/showProjectDetail?project_id=".$project_id);
		}
	}
	
	/**
     * 推广指南页
     * @author 郁政
     */
	public function action_showTuiGuangGuide(){
		$content = View::factory("quickPublish/showtuiguangguide");
    	$this->template->content = $content;
	}
}
?>