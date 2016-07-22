<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 项目投诉举报信息
 * @author 兔毛  2014-05-15
 *
 */
class Service_QuickPublish_ProjectComplaint {
	
	
	private $memcache;
	
	
	public  function __construct(){
		$this->memcache = Cache::instance('memcache');
 	}
	/**
	*  首页数据
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-22
	* @return int/bool/object/array
	*/
	public function IndexList(){
		$arr_data = array();
		$this->memcache = Cache::instance('memcache');
		//项目总的数量
		$arr_data['QuickProjectCount'] = $this->getQucikProjectCount();
		//项目pv(关注了生意)
		$arr_data['QuickProjectPvCount'] = $this->getQucikProjectPv();
		//会员数量
		$arr_data['UserCount'] = $this->getUserCount();
		//获取最近发布项目的项目
		$arr_data['QuickProjectList'] = $this->getQuickProjectList(5);
		//获取最新被产看的项目
		$arr_data['QuickProjectPvList'] = $this->getQuickProjectPvList(5);
		//获取最新的注册的会员
		$arr_data['NewUserList'] =  $this->getNewUserList(5);
		//一周热榜  5万大洋
		$arr_data['HotList5'] =  $this->getHotListByTouziMoney(20,7,1);
		//一周热榜  5-10万 大洋
		$arr_data['HotList5To10'] =  $this->getHotListByTouziMoney(20,7,2);
		//一周热榜  210-20万 大洋
		$arr_data['HotList10To20'] =  $this->getHotListByTouziMoney(20,7,3);
		//一周热榜  20-50万 大洋
		$arr_data['HotList20To50'] =  $this->getHotListByTouziMoney(20,7,4);
		//一周热榜  50万 大洋
		$arr_data['HotList50'] =  $this->getHotListByTouziMoney(20,7,5);
		//echo "<pre>"; print_r($arr_data['HotList5']);exit;
		return $arr_data;
	}
	/**
	* 首页静态化 ajax 获取及时数据
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-10
	* @return int/bool/object/array
	*/
	public function indexLoading(){
		$this->memcache = Cache::instance('memcache');
		//项目总的数量
		$arr_data['QuickProjectCount'] = $this->getQucikProjectCount(true);
		//项目pv(关注了生意)
		$arr_data['QuickProjectPvCount'] = $this->getQucikProjectPv(true);
		//会员数量
		$arr_data['UserCount'] = $this->getUserCount(true);
		//获取最近发布项目的项目
		$arr_data['QuickProjectList'] = $this->getQuickProjectList(5,true);
		//获取最新被产看的项目
		$arr_data['QuickProjectPvList'] = $this->getQuickProjectPvList(5,true);
		//获取最新的注册的会员
		$arr_data['NewUserList'] =  $this->getNewUserList(5,true);
		//echo "<pre>"; print_r($arr_data['NewUserList']);exit;
		//一周热榜  1万大洋
		$arr_data['HotList5'] =  $this->getHotListByTouziMoney(20,7,1,true);
		//一周热榜  1-2万 大洋
		$arr_data['HotList5To10'] =  $this->getHotListByTouziMoney(20,7,2,true);
		//一周热榜  2-5万 大洋
		$arr_data['HotList10To20'] =  $this->getHotListByTouziMoney(20,7,3,true);
		//一周热榜  5-10万 大洋
		$arr_data['HotList20To50'] =  $this->getHotListByTouziMoney(20,7,4,true);
		//一周热榜  10万 大洋
		$arr_data['HotList50'] =  $this->getHotListByTouziMoney(20,7,5,true);
		return $arr_data;
	}
	
	/**
	* 一周热榜  根据  投资 金额
	* @author Smile(jiye)
	* @param $type 投资类型 $bool (是否获取及时数据)
	* @create_time  2014-5-22
	* @return int/bool/object/array
	*/
	public function getHotListByTouziMoney($top, $time = 30 , $type = 1,$bool = false){
		$statistics = array();
		$arr_return_data = array();
		$date_start = time() - $time * 86400;
		$date_end = time();
		$Service_QuickPublish_ProjectComplaint = new Service_QuickPublish_ProjectComplaint();
		try {
			$statistics = $this->memcache->get("HotList-".$type);
			//$statistics = array();
			if(!$statistics){
				$statistics = DB::select('quick_project.project_id',
						"quick_project.project_brand_name",
						"quick_project.project_title",
						"quick_project.project_pv_count",
						"quick_project.project_addtime",
						"quick_project.project_passtime",
						"quick_project.project_updatetime",
						"quick_project.project_title",
						array(DB::expr('COUNT(czzs_quick_project_statistics.project_id)'),'amount'))
						->from('quick_project')
						->join('quick_project_statistics','left')
						->on('quick_project.project_id','=','quick_project_statistics.project_id')
						->where('quick_project.project_status','=',2)
						->where('quick_project.project_amount_type','=',intval($type))
						->where('quick_project_statistics.insert_time','>',$date_start)
						->where('quick_project_statistics.insert_time','<',$date_end)
						->group_by('quick_project_statistics.project_id')
						->order_by('amount', 'desc')
						->limit($top)
						->execute()
						->as_array();
				if(count($statistics) > 0){
					$arr_data = array();
					$Service_QuickPublish_ProjectComplaint = new Service_QuickPublish_ProjectComplaint();
					foreach ($statistics as $key=>$val){
						$arr_data[$key] = $val;
						$arr_data[$key]['project_pv_count'] = $Service_QuickPublish_ProjectComplaint->getProjectStatistics(arr::get($val, "project_id"));
					}
					$arr_return_data = common::multi_array_sort($arr_data, "project_pv_count", SORT_DESC);
				}
				$this->memcache->set("HotList-".$type,$arr_return_data,3600*2);
				return $arr_return_data;exit;
				//$statistics = $arr_return_data;
			}else{
				foreach ($statistics as $key=>$val){
					$statistics[$key]['project_pv_count'] = $Service_QuickPublish_ProjectComplaint->getProjectStatistics(arr::get($val, "project_id"));
				}
				return common::multi_array_sort($statistics, "project_pv_count", SORT_DESC);
			}
		}catch (Kohana_Exception $e){
			return array();
		}
	}
	/**
	* 获取最新注册的会员
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-22
	* @return int/bool/object/array
	*/
	public function getNewUserList($limit = 5,$bool = false){
		$arr_return_data = array();
		try{
			$arr_return_data = $this->memcache->get("NewMobileUserList");
			$Service_QuickPublish_Search = new Service_QuickPublish_Search();
			if(!$arr_return_data || $bool == true){
				$arr_return_data = array();
				$data = Service_Sso_Client::instance()->getMobileUserInfoByNum($limit);
				if(arr::get($data,"return")){
					foreach ($data['return'] as $key=>$val){
						$str_mobile = "";
						$arr_return_data[$key] = $val;
						$arr_return_data[$key]['newmobile'] = "";
						if(arr::get($val,"mobile")){
							$arr_return_data[$key]['newmobile'] = substr(arr::get($val,"mobile"),0,3)."****".substr(arr::get($val,"mobile"),-4,4);
						}
						//$arr_return_data[$key]['zhuceshijian'] =  @preg_replace('/^0+/','',date('i',time()- arr::get($val,"reg_time")));
						$arr_return_data[$key]['zhuceshijian'] = $Service_QuickPublish_Search->jishuanTime(arr::get($val,"reg_time"),true);
					}
					//数组处理一下  按照   时间降序
					$arr_return_data = common::multi_array_sort($arr_return_data, "id", SORT_DESC);
					//echo "<pre>"; print_r($arr_return_data);exit;
					$this->memcache->set("NewMobileUserList",$arr_return_data,3600*2);
				}
			}
			//echo "<pre>"; print_r($arr_return_data);exit;
			return $arr_return_data;
		}catch (Kohana_Exception $e){
			return array();
		}
	}
	/**
	* 获取最新被产看的项目
	* @author Smile(jiye)
	* @param $bool (是否获取及时数据)
	* @create_time  2014-5-22
	* @return int/bool/object/array
	*/ 
	public function getQuickProjectPvList($limit = 5,$bool = false){
		$arr_return_data = array();
		try{
			$arr_return_data = $this->memcache->get("QuickProjectIndexPvList");
			if(!$arr_return_data || $bool == true){
				$arr_data = array();
				$arr_return_data = array();
				$arr_QuickProjectstatistics = array();
				$arr_QuickProjects = array();
				$arr_project_id = array();
				#获取最新发布项目的id
				$obj_data = ORM::factory("Quickproject")->where("project_status","=",intval(2))->order_by("project_passtime","DESC")->limit($limit)->offset(0)->find_all();
				if(count($obj_data) > 0){
					foreach ($obj_data as $key=>$val){
						$arr_project_id[$key] = $val->project_id;
					}
				}
				//echo "<pre>"; print_r($arr_project_id);exit;
				//查询统计表
				$arr_QuickProjectstatistics =   DB::select("quick_project.project_id",
															"quick_project.mobile_id",
															"quick_project.com_id",
															"quick_project.project_brand_name",
															"quick_project.project_logo",
															"quick_project.project_amount_type",
															"quick_project.project_introduction",
															"quick_project.project_title",
															"quick_project_statistics.insert_time")
															->from("quick_project")
															->join("quick_project_statistics")
															->on("quick_project.project_id","=","quick_project_statistics.project_id")
															->where("quick_project.project_status","=",2)
															->where("quick_project.project_id","NOT IN",$arr_project_id)								
															->group_by("quick_project_statistics.project_id")
															->order_by("quick_project_statistics.insert_time","DESC")
															->limit($limit)
															->offset(0)
															->execute()->as_array();
				//echo "<pre>"; print_r($arr_QuickProjectstatistics);exit;
				if(count($arr_QuickProjectstatistics) > 0){
					$num = 0;
					foreach ($arr_QuickProjectstatistics as $key=>$val){ $num ++;
					$arr_return_data[$num] = $val;
					//获取会员信息
					$int_user_id = ORM::factory("MobileAccount")->where("mobile_id", "=",arr::get($val,"mobile_id"))->find()->user_id;
					$data = Service_Sso_Client::instance()->getUserInfoById($int_user_id);
					$arr_return_data[$num]['insert_time'] = @preg_replace('/^0+/','',date("i",time() - (arr::get($val,"insert_time"))));
						if($data != false && isset($data->id) && $data->id >0){
							$str_mobile_name = $data->user_name ? $data->user_name : "";
							$arr_return_data[$num]['user_id'] = arr::get($val,"user_id");
							if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|18[29]{1}[0-9]{8}$/",$str_mobile_name)){
								$arr_return_data[$num]['user_name'] = substr($str_mobile_name,0,3)."****".substr($str_mobile_name,-4,4);
							}else{
								$arr_return_data[$num]['user_name'] = $data->user_name ? mb_substr($data->user_name,0,6,"UTF8") : "";
							}
							$arr_return_data[$num]['user_id'] = $data->id ? $data->id : 0;
						}
					}
				}
			}
				//数组处理一下  按照   时间降序
				//echo "<pre>"; print_r($arr_return_data);exit;
				$arr_return_data = common::multi_array_sort($arr_return_data, "insert_time", SORT_ASC);
				$this->memcache->set("QuickProjectIndexPvList",$arr_return_data,60*3);				
			return  $arr_return_data;
		}catch (Kohana_Exception $e){
			return $arr_return_data;
		}
	}
	
	/**
	* 获取最新发布的项目
	* @author Smile(jiye)
	* @param $limit 数量   $bool (是否获取及时数据)
	* @create_time  2014-5-22
	* @return int/bool/object/array
	*/
	public function getQuickProjectList($limit = 5,$bool = false){
		$arr_return_data = array();
		try{
			$arr_return_data = $this->memcache->get("QuickProjectList");
			//$arr_return_data= array();
			//echo "<pre>"; print_r($arr_return_data);exit;
			$Service_QuickPublish_ProjectComplaint = new Service_QuickPublish_ProjectComplaint();
			if(!$arr_return_data || $bool == true){
				$obj_data = ORM::factory("Quickproject")->where("project_status","=",intval(2))->order_by("project_passtime","DESC")->limit($limit)->offset(0)->find_all();
				foreach ($obj_data as $key=>$val){
					$arr_return_data[$key] = $val->as_array();
					$arr_return_data[$key]['project_pv_count'] = $Service_QuickPublish_ProjectComplaint->getProjectStatistics($val->project_id);
				}
				$this->memcache->set("QuickProjectList",$arr_return_data,3600*2);
			}else{
				foreach ($arr_return_data as $key =>$val){
					$arr_return_data[$key]['project_pv_count'] = $Service_QuickPublish_ProjectComplaint->getProjectStatistics($val['project_id']);
				}
			}
			return  $arr_return_data;
		}catch (Kohana_Exception $e){
			 return $arr_return_data; 
		}
	}
	/**
	* 获取会员数量
	* @author Smile(jiye)
	* @param $bool (是否获取及时数据)
	* @create_time  2014-5-22
	* @return int/bool/object/array
	*/
	public function getUserCount($bool = false){
		$user_num =0;
		try {
			$user_num  = $this->memcache->get('UserCount');
		}
		catch (Cache_Exception $e) {
			$user_num  = 0;
		}
		if( $user_num==0 || $bool == true){
			$service_user = new Service_User();
			$user_num = $service_user->getRegUserNum();
			$this->memcache->set('UserCount', $user_num,180);
		}else{
		}
		return $user_num;
	}
	/**
	* 获取关注的人(关注了生意)
	* @author Smile(jiye)
	* @param $bool (是否获取及时数据)
	* @create_time  2014-5-22
	* @return int/bool/object/array
	*/
	public function getQucikProjectPv($bool = false){
		$int_QuickProjectPvCount = $this->memcache->get("QuickProjectPvCount");
		if(!$int_QuickProjectPvCount || $bool == true){
			$int_QuickProjectPvCount = ORM::factory("QuickProjectstatistics")->count_all();
			//$int_QuickProjectPvCount = ORM::factory("Quickproject")->where("project_status","=",intval(2))->where("project_pv_count",">",0)->count_all();
			$this->memcache->set("QuickProjectPvCount",$int_QuickProjectPvCount,3600*2);
		}
		return $int_QuickProjectPvCount;
	}
	
	/**
	* 获取项目数量
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-22
	* @return int/bool/object/array
	*/
	public function getQucikProjectCount($bool = false){ 
		$int_QuickProjectCount = $this->memcache->get("QuickProjectCount");
		if(!$int_QuickProjectCount || $bool == true){
			$int_QuickProjectCount = ORM::factory("Quickproject")->where("project_status","=",intval(2))->count_all();
			$this->memcache->set("QuickProjectCount",$int_QuickProjectCount,3600*2);
		}
		return $int_QuickProjectCount;
	}
	
    /**
	 * 新增项目投诉举报信息
	 * author: 兔毛  2014-05-15
	 */
	public function addProjectComplaint($user_id,$postdata){
		try{
			$data=ORM::factory('Projectcomplaint');
			$data->project_id = $postdata['project_id'];
			$data->user_id = $user_id;
			$data->complaint_type = $postdata['complaint_type'];
			$data->complaint_contents=isset($postdata['complaint_contents'])?$postdata['complaint_contents']:'';
			$data->complaint_addtime = time();
			$data->complaint_updtime=time();
			$data->create();
			return true;
		}catch(Kohana_Exception $e){
			return false;
		}
	}
	
	/**
	 * 判断用户X是否已经举报过某项目了 (同一个项目同一个人举报只能一次)
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $project_id：项目id
	 * author: 兔毛  2014-05-21
	 */
	public function ishas_by_user_and_pro_id($user_id,$project_id)
	{
		return ORM::factory('Projectcomplaint')
		->where('user_id','=',$user_id)
		->where('project_id','=',$project_id)
		->count_all();
	}
    
	/**
	 * 审核项目投诉举报信息
	 * author: 兔毛  2014-05-15
	 */
	public function checkProjectComplaint($complaint_status,$project_arr,$complaint_remark)
	{
		try{
            foreach($project_arr as $projectid){
               $data=ORM::factory('Projectcomplaint');
               $result=$data->where('project_id','=',$projectid)->find_all();
               if(count($result)){
	               foreach($result as $vs){
	                   $vs->complaint_status = $complaint_status;
	                   $vs->complaint_updtime = time();
	                   $vs->complaint_remark=$complaint_remark;
	                   $vs->update();
	               }
               }
               return true;
            }
		}catch(Kohana_Exception $e){
			return false;
		}
	}
	
	/**
	 * 判断项目信息是否存在
	 * @param unknown_type $int_project_id：项目id
	 * @param unknown_type $int_project_status：项目状态
	 * author： 兔毛 2014-05-26
	 */
	public function is_has_project_info($int_project_id,$int_project_status){
		try {
			return ORM::factory('Quickproject')
				->where('project_id','=',$int_project_id)
				->where('project_status','=',$int_project_status)
				->count_all();
			
		}catch (Kohana_Exception $e){
			return 0;
		}
	
	}
	
	
	/**
	 * 项目基本信息
	 * @param unknown_type $int_project_id：项目id
	 * @param unknown_type $int_project_status: 项目状态（审核是否通过）,0为没有提交审核，1为提交审核中，2为通过审核,3为未通过审核,4为后台管理员静止此项目,5为前端用户自己软删除
	 * @param unknown_type $is_null：数据不存在，默认：空
	 * author: 兔毛  2014-05-16
	 */
	public function getProjectInfo($int_project_id,$int_project_status=0,$is_null='暂无信息',$from = 0)
	{
		$project_info=null;
		$project_info=$this->getProjectInfoByID($int_project_id,$int_project_status);
		$project_info['project_update_time']=$is_null;
		$project_info['project_brand_birthplace_city_name']=$is_null;  //品牌发源地
		$project_info['project_brand_birthplace_area_name']=$is_null;  //所在区域
		$project_info['project_joining_fee_name']=$is_null;
		$project_info['project_amount_type_name']=$is_null;
		$project_info['project_area_name']=$is_null;  //支持加盟地区
		$project_info['project_industry_name']=$is_null;
		$project_info['project_history_name']=$is_null;
		$project_info['project_model_name']=$is_null; //经营模式
		$project_info['project_brand_birthplace_city_name']=$is_null; //品牌发源地
		$project_info['project_brand_birthplace_area_name']=$is_null; //所在区域
		$project_info['project_pv_count']=$this->getProjectStatistics($int_project_id);
		
		if(!empty($project_info))
		{
			if(isset($project_info['project_update_time']))
			{
				$obj=new Service_QuickPublish_Search();
				$update_time=$obj->jishuanTime($project_info['project_updatetime']);
				$project_info['project_update_time']=empty($update_time) ? $is_null: $update_time;
			}
			$project_info['project_model_name']=$this->common_project_model($int_project_id,$is_null);
			$project_info['project_brand_name']=empty($project_info['project_brand_name'])?$is_null:$project_info['project_brand_name'];
			//$project_info['project_phone']=empty($project_info['project_phone'])? '' : str_replace("/", "-", $project_info['project_phone']);
			if(!empty($project_info['project_phone']))
			{
				$phone_array=explode('/', $project_info['project_phone']);
				$phone='';
				for($item=0;$item<count($phone_array);$item++)
				{
					$first=$phone_array[$item];
					$two=isset($phone_array[$item+1]) ? $phone_array[$item+1] : '';
					if(!empty($first)) $phone.=$first;
					if(!empty($first) && !empty($two))  $phone.='-';						
				}
				$project_info['project_phone']=$phone;
			}
			$project_info['project_security_deposit']=empty($project_info['project_security_deposit'])? $is_null : $project_info['project_security_deposit'].'元';
			$project_info['project_joining_fee']=empty($project_info['project_joining_fee'])? $is_null : $project_info['project_joining_fee'].'元';  //加盟费用
		    if(isset($project_info['project_brand_birthplace_id'])) //品牌发源地
			{
				$brith_place_info=$this->getProjectBirthplaceAreaByID($int_project_id);
				$brith_city_info=isset($brith_place_info[0])?$brith_place_info[0]:null;
				$brith_city_name=$brith_area_name=$is_null;
				if(!empty($brith_city_info))
				{
					$brith_city_name=$brith_area_name='';
					$brith_city_info=$this->getCityName($brith_city_info['area_id'],0,$is_null);
					$brith_city_name=$brith_city_info['area_name'];
				}
				$project_info['project_brand_birthplace_city_name']=$brith_city_name;  //品牌发源地
			}
			$load_obj=new Service_QuickPublish_Project();
			$load_area_name=$load_obj->getLocalCityNameById($int_project_id,2);
			$project_info['project_brand_birthplace_area_name']=empty($load_area_name) ? $is_null :$load_area_name;
			$money_arr= common::moneyArr();
			if(isset($project_info['project_amount_type'])) //投资金额类型+投资金额（中文）名称
			{
				$project_amount_type=$project_info['project_amount_type'];
				$project_info['project_amount_type_name'] = isset($money_arr[$project_amount_type]) ? $money_arr[$project_amount_type] :$is_null;
			}
			/*支持加盟地区 Start*/
			$project_area_info=$this->getProjectareaById($int_project_id,0);
			//d($project_area_info);
			$project_area_name=$is_null;
			$count=count($project_area_info);
			if($count>0)
			{
				$project_area_name='';
				for($item=0;$item<$count;$item++)
				{
					$city_info=$this->getCityName($project_area_info[$item]['area_id'],$project_area_info[$item]['pro_id'],$is_null);
					$project_area_name.=$city_info['area_name'];
					if($item<$count-1)
						$project_area_name.=",";
				}	
			}
			$project_info['project_area_name']=$project_area_name;  
			/*支持加盟地区 End*/
			$project_info['project_industry_name']=$this->getIndustryNameById($int_project_id); //行业分类
			$project_info['project_first_industry_id']=$this->getIndustryNameById($int_project_id,'',true); //一级行业的id
			/*品牌历史  Start*/
			$history_info=common::projectHistory(); 
			$project_info['project_history_name']=isset($history_info[$project_info['project_history']]) ? $history_info[$project_info['project_history']] :$is_null;
			/*品牌历史  End*/
		}
		if($from == 1){
			//判断是否有敏感词
			$service = new Service_QuickPublish_Project();
			$valite = array();
			$valite['project_brand_name'] = arr::get($project_info,"project_brand_name","");
			$valite['project_title'] = Arr::get($project_info, 'project_title','');	
			$valite['project_introduction'] = Arr::get($project_info, 'project_introduction','');
			$valite['project_summary'] = Arr::get($project_info, 'project_summary','');		
			$isMinGan = $service->hasMinGanWords($valite,1);
			if(isset($isMinGan['word']['project_brand_name'])){
				foreach($isMinGan['word']['project_brand_name'] as $v){
					$project_info['project_brand_name'] = str_replace($v, '<span style="color:red;">'.$v.'</span>', $project_info['project_brand_name']);
				}
			}
			if(isset($isMinGan['word']['project_introduction'])){
				foreach($isMinGan['word']['project_introduction'] as $v){
					$project_info['project_introduction'] = str_replace($v, '<span style="color:red;">'.$v.'</span>', $project_info['project_introduction']);
				}
			}
			if(isset($isMinGan['word']['project_title'])){
				foreach($isMinGan['word']['project_title'] as $v){
					$project_info['project_title'] = str_replace($v, '<span style="color:red;">'.$v.'</span>', $project_info['project_title']);
				}
			}
			if(isset($isMinGan['word']['project_summary'])){
				foreach($isMinGan['word']['project_summary'] as $v){
					$project_info['project_summary'] = str_replace($v, '<span style="color:red;">'.$v.'</span>', $project_info['project_summary']);
				}
			}
		}
		//echo "<pre>";print_r($isMinGan);exit;
		return $project_info;
	}
	
	/**
	 * 通用：获取项目的经营模式
	 * @param unknown_type $project_id：项目id
	 * author： 兔毛 2014-05-21
	 */
	public function common_project_model($project_id,$is_null='暂无信息')
	{
		$project_model=$is_null;
		$project_obj=new Service_QuickPublish_Project();
		$info=$project_obj->getProjectCoModel($project_id);
		$count=count($info);
		if($count>0)
		{
			$project_model='';
			$info=array_values($info);
			$model_array=common::puickProjectModel();
			for($item=0;$item<$count;$item++)
			{
				$project_model.=$model_array[$info[$item]];
				if($item<$count-1)
					$project_model.=",";
			}
		}
		return $project_model;
	}
	
	/**
	 * 通用：根据城市ID+一级城市ID，获取城市（中文）名称
	 * @param unknown_type $area_id：城市ID
	 * @param unknown_type $pro_id：一级城市ID
	 * @param unknown_type $is_null：数据不存在，默认：空
	 * @return multitype:unknown：数组
	 * author: 兔毛  2014-05-16
	 */	
	 public function getCityName($area_id=0,$pro_id=0,$is_null='暂无信息')
	{
		$area_name = !empty($area_id)? ORM::factory('city',$area_id)->cit_name : $is_null;
		$pro_name = !empty($pro_id)? ORM::factory('city',$pro_id)->cit_name : $is_null;
		$result=array("area_id"=>$area_id,'area_name'=>$area_name,"pro_id"=>$pro_id,'pro_name'=>$pro_name);
		return $result;
	}

	
	
	/**
	* 通过项目ID  获取项目信息
	* @author Smile(jiye)
	* @param $int_project_id/$int_project_status
	* @create_time  2014-5-16
	* @return array
	*/
	public function getProjectInfoByID($int_project_id,$int_project_status = 0){
		try {
			if($int_project_id){
				if($int_project_status != 0){
					return  ORM::factory("Quickproject")->where("project_id","=",intval($int_project_id))->where("project_status","=",intval($int_project_status))->find()->as_array();
				}else{
					return  ORM::factory("Quickproject",intval($int_project_id))->as_array();
				}
			}
			return array();
		}catch (Kohana_Exception $e){
			return array();
		}
		
	}
	
	/**
	 * 根据项目ID返回项目信息[所有的项目]
	 * @author: 兔毛  2014-05-23
	 */
	public function getProjectInfoByIDAllNew($project_id){
		$project=ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
		if($project->project_id != null){
			return $project;
		}
		return false;
	}
	
	/**
	* 通过项目ID 获取品牌发源
	* @author Smile(jiye)
	* @param $int_project_id
	* @create_time  2014-5-16
	* @return array
	*/
	public function getProjectBirthplaceAreaByID($int_project_id,$int_status = 0){
		$arr_return_data = array();
		try {
			if($int_project_id){
				//根据状态获取数据
				$model = ORM::factory("Quickprojectbirthplacearea");
				if($int_status !=0){
					$str_data = $model->where("project_id","=",intval($int_project_id))->where("status","=",intval($int_status))->find_all();
				}else{
					//获取全部的
					$str_data = $model->where("project_id","=",intval($int_project_id))->find_all();
				}
				if(count($str_data) > 0){
					foreach ($str_data as $key=>$val){
						$arr_return_data[$key] = $val->as_array();
					}
				}
			}
			return $arr_return_data;
		}catch (Kohana_Exception $e){
			return $arr_return_data;
		}
	}
	/**
	* 通过项目ID 获取招商地区
	* @author Smile(jiye)
	* @param $int_project_id,$int_status
	* @create_time  2014-5-16
	* @return int/bool/object/array
	*/
	public function getProjectareaById($int_project_id,$int_status = 0){
		$arr_return_data = array();
		try {
			if($int_project_id){
				//根据状态获取数据
				$model = ORM::factory("Quickprojectarea");
				if($int_status !=0){
					$str_data = $model->where("project_id","=",intval($int_project_id))->where("status","=",intval($int_status))->find_all();
				}else{
					//获取全部的
					$str_data = $model->where("project_id","=",intval($int_project_id))->order_by("pro_id","ASC")->order_by("area_id","ASC")->find_all();
				}
				if(count($str_data) > 0){
					foreach ($str_data as $key=>$val){
						$arr_return_data[$key] = $val->as_array();
					}
				}
			}
			return $arr_return_data;
		}catch (Kohana_Exception $e){
			return $arr_return_data;
		}
	}
	/**
	 * 通过项目ID 获取展示图片
	 * @author Smile(jiye)
	 * @param $int_project_id,$int_project_type
	 * @create_time  2014-5-16
	 * @return array
	 */
	public function getProjectImagesById($int_project_id,$int_project_type = 1){
		$arr_return_data = array();
		try {
			if($int_project_id){
				//根据状态获取数据
				$model = ORM::factory("Quickprojectimages");
				$str_data = $model->where("project_id","=",intval($int_project_id))->where("project_type","=",intval($int_project_type))->find_all();
				if(count($str_data) > 0){
					foreach ($str_data as $key=>$val){
						$arr_return_data[$key] = $val->as_array();
					}
				}
			}
			return $arr_return_data;
		}catch (Kohana_Exception $e){
			return $arr_return_data;
		}
	}
	/**
	 * 通过项目ID 获取项目图片
	 * @author stone
	 */
	public function getProjectImagesCount($int_project_id,$int_project_type = 1){
		$arr_return_data = false;
		try {
			if($int_project_id){
				//根据状态获取数据
				$model = ORM::factory("Quickprojectimages");
				$str_data = $model->where("project_id","=",intval($int_project_id))->where("project_type","=",intval($int_project_type))->count_all();
				$arr_return_data = $str_data > 0 ? true : false;
			}
			return $arr_return_data;
		}catch (Kohana_Exception $e){
			return $arr_return_data;
		}
	}
	/**
	 * 通过项目ID 获取行业
	 * @author Smile(jiye)
	 * @param $int_project_id,$int_status
	 * @create_time  2014-5-16
	 * @return array
	 */
	public function getProjectIndustryById($int_project_id,$int_status = 0){
		$arr_return_data = array();
		try {
			if($int_project_id){
				//根据状态获取数据
				$model = ORM::factory("Quickprojectindustry");
				if($int_status !=0){
					$str_data = $model->where("project_id","=",intval($int_project_id))->where("status","=",intval($int_status))->find_all();
				}else{
					//获取全部的
					$str_data = $model->where("project_id","=",intval($int_project_id))->find_all();
				}
				if(count($str_data) > 0){
					foreach ($str_data as $key=>$val){
						$arr_return_data[$key] = $val->as_array();
					}
				}
			}
			return $arr_return_data;
		}catch (Kohana_Exception $e){
			return $arr_return_data;
		}
	}
	
	/**
	 * 通过项目ID 获取招商形式
	 * @author Smile(jiye)
	 * @param $int_project_id,$int_status
	 * @create_time  2014-5-16
	 * @return array
	 */
	public function getProjectModelById($int_project_id,$int_status = 0){
		$arr_return_data = array();
		try {
			if($int_project_id){
				//根据状态获取数据
				$model = ORM::factory("Quickprojectmodel");
				if($int_status !=0){
					$str_data = $model->where("project_id","=",intval($int_project_id))->where("status","=",intval($int_status))->find_all();
				}else{
					//获取全部的
					$str_data = $model->where("project_id","=",intval($int_project_id))->find_all();
				}
				if(count($str_data) > 0){
					foreach ($str_data as $key=>$val){
						$arr_return_data[$key] = $val->as_array();
					}
				}
			}
			return $arr_return_data;
		}catch (Kohana_Exception $e){
			return $arr_return_data;
		}
	}
        /**
     * 判断某用户是否赞过项目
     * @author 郁政
     * @param  $user_id 用户id, $project_id 项目id
     */
    public function isApprovings($project_id, $user_id = 0){
        $result = array();
        $user_id = intval($user_id);
        if(!$user_id) return false;
            $approing = ORM::factory("QuickApproingLogs")->where('user_id','=',$user_id)->where('project_id','=', $project_id)->count_all();
            if($approing >= 1){
                return TRUE;
            }else{
                return FALSE;
            }
        

    }
        /**
     * 添加快速发布项目的 赞
     * @author 郁政
     * @param  $user_id 用户id,$project_id 项目id
     */
    public function addApproving($user_id,$project_id){
        $user_id = intval($user_id);
        $project_id = intval($project_id);
        $ip = ip2long(Request::$client_ip);
        $cache_key = 'quick_zan_num'.$project_id;        
        try {
            $approing = ORM::factory("QuickApproingLogs");
            $approing->user_id = $user_id;
            $approing->project_id = $project_id;
            $approing->log_time = time();
            $approing->ip = $ip;
            $res = $approing->save();
            if($res->id){
            	$cache = Cache::instance ( 'memcache' );        
		        $zan_cache = $cache->get($cache_key);
                        $log_count = ORM::factory("QuickApproingLogs")->where('project_id','=',$project_id)->count_all();
		        if($zan_cache){
		        	$zan_cache = $zan_cache >= $log_count ? $zan_cache+1 : $log_count; 	
		        	$cache->set($cache_key, $zan_cache,3600*24*30);		        		        	
		        }else{
		        	$cache->set($cache_key, $log_count,3600*24*30);
                                $project = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
                                if($project->loaded()){
                                        $project->project_approving_count = $log_count;
                                        $project->update();
                                }
		        }
		        
            }
        } catch (Kohana_Exception $e) {
            return false;
        }
        return true;
    }

    
	/**
     * 根据项目ID获取需要显示的行业名称
     * @author 郁政
     */
    public function getIndustryNameById($project_id,$split = ',',$is_return_first_id=false) {
		if($project_id){
			$str_name = "";
			$pcs = ORM::factory ( "Quickprojectindustry" )->where ( "project_id", "=", $project_id )->find()->as_array();			
			if($is_return_first_id) return arr::get($pcs,"first_industry_id");
			if(arr::get($pcs,"first_industry_id") != 0){
				$industry = ORM::factory ( "industry" )->where ( "industry_id", "=",arr::get($pcs,"first_industry_id"))->find ();
				$str_name .= $industry->industry_name;
			}
			if(arr::get($pcs,"second_industry_id") != 0 && arr::get($pcs,"first_industry_id") != arr::get($pcs,"second_industry_id")){
				$industry = ORM::factory ( "industry" )->where ( "industry_id", "=",arr::get($pcs,"second_industry_id"))->find ();
				$str_name .= $split.$industry->industry_name;
			}			
		}
		return $str_name;
	}
    
    
	/**
     * 读取地区id和名称 
     * @author 郁政
     */
    public function getAreaIdName($project_id){
        $res = ORM::factory('Quickprojectarea')->where("project_id", "=",$project_id)->find_all();
        if(count($res) > 0){
            //把市级地区id和名称以三维数组输出，省级放在二维里
            foreach ($res as $k=>$v){
                if($v->area_id != $v->pro_id){
                    $city = ORM::factory('city',$v->area_id);
                    $pro  = ORM::factory('city',$v->pro_id);
                    $re[$v->pro_id]['pro_id'] = $v->pro_id;
                    $re[$v->pro_id]['pro_name'] = $pro->cit_name;
                    $re[$v->pro_id]['data'][$k]['area_id'] = $v->area_id;
                    $re[$v->pro_id]['data'][$k]['pro_id'] = $v->pro_id;
                    $re[$v->pro_id]['data'][$k]['area_name'] = $city->cit_name;
                    $result = $re;
                }elseif($v->area_id == $v->pro_id && $v->pro_id == 88){
                    $re[$v->pro_id]['pro_id'] = 88;
                    $re[$v->pro_id]['pro_name'] = '全国';
                    $result = $re;
                }elseif($v->area_id == $v->pro_id && $v->pro_id < 36){
                    $pro  = ORM::factory('city',$v->area_id);
                    $re[$v->pro_id]['pro_id'] = $v->pro_id;
                    $re[$v->pro_id]['pro_name'] = $pro->cit_name;
                    $result = $re;
                }
            }
        }else{
            $result = array();
        }
        return $result;
    }
    
    
    /**
     * 获得地区字符串
     * @author 施磊
     */
    public function getProArea($project_id, $separator = '，') {
    	$areaIdName = $this->getAreaIdName ( $project_id );
    	$area_city = array();
    	if (count ( $areaIdName ) > 0) {
    		foreach ( $areaIdName as $key => $value ) {
    			if (isset ( $value ['data'] ) && count ( $value ['data'] ) > 0) {
    				foreach ( $value ['data'] as $ke => $va ) {
    					$area_city[] = $va ['area_name'];
    				}
    			}else{
    				$area_city[] = $value['pro_name'];
    			}
    		}
    		return implode($separator, $area_city);
    	}else {
    		return "全国";
    	}
    }
    
    /**
     * 项目访问量获取
     * @author stone shi
     */
    public function getProjectStatistics($project_id) {
        #开启缓存
    	$redis = Cache::instance ('redis');
        $redis_key = $project_id."_quick_project_count";
        $project_count_num = $redis->get($redis_key);
        if(!$project_count_num) { 
            $project_count_num = ORM::factory("QuickProjectstatistics")->where('project_id','=',$project_id)->count_all();
            $redis->set($redis_key, $project_count_num,60*20);
        }
        return $project_count_num;
    }
    /**
     * 项目访问量统计
     * @author stone shi 
     * @param $int_project_id(项目id)  $page_type(页面类型)
     */
    public function insertProjectStatistics($project_id,$page_type = 1, $user_id){
    	#开启缓存
    	$redis = Cache::instance ('redis');
        $redis_key = $project_id."_quick_project_count";
    	$service_user = new Service_User_Company_Project();
    	$service = new Service_Platform_Project();
        if(!isset($project_id) || !is_numeric($project_id)){
            return false;
        }
        $start_time = strtotime(date('Y-m-d 00:00:00', time()));
		$end_time = strtotime(date('Y-m-d 00:00:00', time()+86400));
		$count = ORM::factory('QuickProjectstatistics')
				->where('project_id','=',$project_id)
				->where('insert_time','>=',$start_time)
				->where('insert_time','<',$end_time)
				->count_all();
		if($count > intval(100))return false;
        $projectindustry=ORM::factory('Quickprojectindustry')->where('project_id', '=', $project_id)->find_all();
        if(count($projectindustry) > 0){
            foreach ($projectindustry as $v){
                    $industry_id = $v->first_industry_id;
            }
        }
        if(!isset($industry_id)) $industry_id = 1;
        $statistics=ORM::factory('QuickProjectstatistics');
        $statistics->project_id = $project_id;
        $statistics->industry_id = $industry_id;
        $statistics->ip = ip2long(Request::$client_ip);
        $statistics->page_type = intval($page_type);
        $statistics->insert_time = time();
         $statistics->user_id = $user_id;
        $obj_data = $statistics->create();
        $int_project_redis_count = $redis->get($redis_key);
        #如果存在  缓存加1  数据库更新
        if($obj_data->statistics_id > 0){
	        if($int_project_redis_count){
	        	#存入缓存
	        	$redis->set($redis_key, $int_project_redis_count + 1,60*20);
	        }else{
	        	$project_count_num = ORM::factory("QuickProjectstatistics")->where('project_id','=',$project_id)->count_all();
	        	#放入缓存
	        	$redis->set($redis_key, $project_count_num,60*20);
	        	#更新入库
	        	$project = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
                        if($project->loaded()){
                               $project->project_pv_count = $project_count_num;
                               $project->update();
                        }
                        }
	        return true;
        }
        return  false;
    }
    
   /**
   * 判断是否还能发布项目   5分钟之内
   * @author Smile(jiye)
   * @param 
   * @create_time  2014-6-13
   * @return int/bool/object/array
   */
    public function isHaveFaBuProject(){
    	$Int_user_id =Cookie::get("user_id");
    	$bool =  false;
    	//登录的情况下
    	if($Int_user_id){
    		//去找最后一个发布的项目时间
    		$model = ORM::factory("MobileAccount");
			$mobile_id = $model->where("user_id","=",$Int_user_id)->find()->mobile_id;
			if($mobile_id == ""){
				// 没有注册会员
				$bool = true;
			}else{
				$project_model = ORM::factory('Quickproject')->where("mobile_id","=",$mobile_id)->limit(1)->offset(0)->order_by("project_addtime","DESC")->find()->as_array();
				if(arr::get($project_model,"project_id") > 0 && arr::get($project_model,"project_id")){
					$int_passtime = arr::get($project_model,"project_addtime");
					$int_time = time();
					$Int_new_time = date("i",$int_time - $int_passtime);
					if($Int_new_time > '05'){
						$bool = true;
					}
				}else{
					//没有发布过项目可以发布
					$bool = true;
				}
			}
    	}else{
    		//没有登录
    		$int_time = Cookie::get("project_addtime");
    		if($int_time){
    			$int_new_time = date("i",time() - $int_time);
    			if($int_new_time > '05'){
    				$bool = true;
    			}
    		}else{
    			//没有发布项目
    			$bool = true;
    		}
    	}
    	return $bool;
    }
}
