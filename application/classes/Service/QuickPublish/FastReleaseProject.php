<?php

defined('SYSPATH') or die('No direct script access.');
	/**
	* 快速发布类
	* @author Tim(jiye)
	* @create_time  2014-5-8
	*/
class Service_QuickPublish_FastReleaseProject {
	/**
	* 快速发布项目登录
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-20
	* @return int/bool/object/array
	*/
	public function QuickLogin($str_mobile,$code){
		try{
			if($str_mobile && $code){
				//判断是不是有这个会员   如果有 不用注册   没有就注册一个
				$data = Service_Sso_Client::instance()->getMobileEof(trim($str_mobile));
				if($data === false){
					//注册一个手机号码
					$this->QuickRegistered(trim($str_mobile));
				}
				
				//手机处理
				$obj_data = $this->DoMobileAccount(trim($str_mobile));
				//写入cookie
				$arr_data = Service_Sso_Client::instance()->userLoginByMobile(trim($str_mobile),trim($code));
				$user_info = Service_Sso_Client::instance()->getUserInfoByMobile(trim($str_mobile));
				
				if( arr::get($arr_data, "result")){
					$info_service = new Service_User_Company_User();
					$icf= $info_service->getCompanyInfo($user_info->id);
					$this->RegisteredPersonInfo($user_info->id);
					if( $icf->com_id !='' ){
						if( $icf->com_name != $user_info->user_name ){
							Service_Sso_Client::instance()->updateBasicInfoById($user_info->id,array('user_name'=>$icf->com_name));
						}
					}
					
				}
				//return false;
				return arr::get($arr_data, "result");
				//return $arr_data;
			}
			return false;
		}catch (Kohana_Exception $e){return false;}
		
	}
	
	/**
	* 手机号码注册
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-20
	* @return int/bool/object/array
	*/
	public function QuickRegistered($str_mobile,$name="",$return_type = false){
	//通过手机号码注册一个手机账号
		$str_mobile = trim($str_mobile);
			if($str_mobile){	
				$int_data  = rand(100000,999999);
				$str = "您正在使用一句话商机网，您的初始登录密码为：".$int_data."，请妥善保管；如果丢失，您也可以通过手机验证码找回。";
				$userinfo = array(
						'mobile' => $str_mobile,
						'user_name' => $name ? $name : $str_mobile,
						'password' => $int_data,
						'user_type' => 2,
				);
				$client = Service_Sso_Client::instance();
				$result = $client->appRegister($userinfo,"MOBILE");
				if(Arr::get($result,"error") === false){
					common::send_message($str_mobile,$str, 'online');			
					$user = Arr::get($result,"return");
					$user_id = $user['id'];
					$servicemobile = new Service_User ();
					$servicemobile->messageLog($str_mobile,$user_id,5,$str,1);
					//初始化 user==
					try{
						$basic = ORM::factory("User");
						$basic->user_id = $user_id;
						$basic->last_logintime = time();
						$basic->last_login_ip = ip2long(Request::$client_ip);
						$sid_md5 = arr::get($_COOKIE, 'Hm_lvqtz_sid');
						$basic->sid = $sid_md5;
						$aid= Cookie::get('cpa_aid','');
						if( $aid!='' ){
							$basic->aid = $aid;
						}
						$rbs= $basic->create();
						
					}catch(Kohana_Exception $e){
						//throw $e;
						//抛出错误代码
					}
					//初始化本地结束==
					//自动登录
					
					//绑定手机号。否则无法完成登录操作
					$client= Service_Sso_Client::instance();
					$client->updateMobileInfoById( $user_id,array( 'valid_status'=>'1','valid_time'=>time() ) );
					// 增加用户注册统计
					$stat_service = new Service_Api_Stat ();
					$sid_md5 = arr::get ( $_COOKIE, 'Hm_lvqtz_sid' );
					$stat_service->setUserRegStat ( $user_id, 2,time(), arr::get ( $_COOKIE, 'Hm_lvqtz_refer' ), $sid_md5 );
						
					
					//返回用户信息
					if($return_type == true){
						return $client->getUserInfoById($user_id);
					}else{
						return true;
					}
					//return $client->getUserInfoById($user_id);
					
				}else{
					$error= Arr::get($result,"error");
					return $error;
				}	
			}
	}
	/**
	 * 手机账户添加 或者修改
	 * @author Smile(jiye)
	 * @param
	 * @create_time  2014-5-19
	 * @return int/bool/object/array
	 */
	public function DoMobileAccount($str_mobile){
		try{
			$str_mobile = trim($str_mobile);
			$model = ORM::factory("MobileAccount");
			$arr_data = $model->where("mobile","=",$str_mobile)->find();
			$service = new Service_User();
			$arr_mobile_account = array();
			//获取用户信息
			$data = Service_Sso_Client::instance()->getUserInfoByMobile($str_mobile);
			$int_user_id = 0;
			$int_user_type = 1;
			$type = 1;
			if($data != false){
				//用户ID
				$int_user_id =  Cookie::get("user_id") ?  Cookie::get("user_id") : $data->id;
				//企业用户
				if((isset($data->user_type) and $data->user_type == 1)){
					$int_user_type = 2;
				}
			}else{
				$int_user_id =  Cookie::get("user_id");
			}
			//echo $int_user_id;exit;
			if(isset($arr_data->mobile_id) && $arr_data->mobile_id > 0){
				if($int_user_id > 0){
					// user_id修改
					$new_modle = ORM::factory("MobileAccount")->where("mobile_id","=",intval($arr_data->mobile_id))->find();
					if($new_modle->loaded()){
						$new_modle->user_id = $int_user_id;
						return $new_modle->update();
					}
				}
				//不错任何 操纵
				return $arr_data;
			}else{
				//实行添加
				$arr_mobile_account = array("mobile"=>$str_mobile,
						"user_id"=>$int_user_id,
						"account_type" =>$int_user_type,
						"mobile_status"=>1,
						"addtime"=>time(),
						"starttime"=>time());
				if(count($arr_mobile_account) > 0){
					foreach ($arr_mobile_account as $key=>$val){
						$model->$key =$val;
					}
					return $model->save();
				}
			}
		}catch (Kohana_Exception $e){
			return false;
		}
	}
	
	/**
	* Enter descrīption here…
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-26
	* @return int/bool/object/array
	*/
	public function getComid($int_user_id){
		if($int_user_id !=0){
			return ORM::factory('Companyinfo')->where("com_user_id","=",$int_user_id)->find()->com_id;
		}
		return 0;
	}
	/**
	* 快速发布总方法
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-16
	* @return int/bool/object/array
	*/
	public function DoFastProject($arr_data = array()){
		//echo "<pre>"; print_r($arr_data);exit;
		//事务开启
		$db = Database::instance();
		try {
			$arr_ProjectBirthplaceArea = array();
			//项目名称
			$arr_project_info['project_brand_name'] =  arr::get($arr_data,"project_brand_name","");
			//项目LOGO
			$arr_project_info['project_logo'] =  arr::get($arr_data,"project_imgname") ? common::getImgUrl(arr::get($arr_data,"project_imgname")) : 'images/quickrelease/company_default.png';
			//历史
			$arr_project_info['project_history'] =  arr::get($arr_data,"project_history",0);
			//投资金额
			$arr_project_info['project_amount_type'] =  arr::get($arr_data,"project_amount_type",0);
			//加盟费
			if(arr::get($arr_data,"project_joining_fee")){
				$arr_project_info['project_joining_fee'] =  arr::get($arr_data,"project_joining_fee",0);
			}
			//保证金
			if(arr::get($arr_data,"project_security_deposit")){
				$arr_project_info['project_security_deposit'] =  arr::get($arr_data,"project_security_deposit",0);
			}
			
			//小许bi要加的
			$arr_project_info['sid'] = Arr::get($_COOKIE, 'Hm_lvqtz_sid',10000);
			$arr_project_info['aid'] = Cookie::get('cpa_aid','');
			$arr_project_info['campaignid'] = Arr::get($_COOKIE, 'campaignid');
			$arr_project_info['adgroupid'] = Arr::get($_COOKIE, 'adgroupid');
			$arr_project_info['keywordid'] = Arr::get($_COOKIE, 'keywordid');
			
			//判断是否有敏感词
			$service = new Service_QuickPublish_Project();
			$valite = array();
			$valite['project_brand_name'] = arr::get($arr_data,"project_brand_name","");
			$valite['project_title'] = Arr::get($arr_data, 'project_title','');	
			$valite['project_introduction'] = Arr::get($arr_data, 'project_introduction','');
			$valite['project_summary'] = Arr::get($arr_data, 'project_summary','');		
			$isMinGan = $service->hasMinGanWords($valite);
			
			//标题
			$arr_project_info['project_title'] =  arr::get($arr_data,"project_title","");
			//一句话介绍
			$arr_project_info['project_introduction'] =  arr::get($arr_data,"project_introduction","");
			//项目简介
			$arr_project_info['project_summary'] =  arr::get($arr_data,"project_summary","");
			//联系人
			$arr_project_info['project_contact_people'] =  arr::get($arr_data,"project_contact_people","");
			//手机号码
			$arr_project_info['mobile']  = $mobile =  arr::get($arr_data,"mobile_num","");
			$arr_project_info['project_pinyin'] =   pinyin::getinitial(arr::get($arr_data,"project_brand_name"));
			$arr_project_info['project_addtime'] =  time();
			$arr_project_info['project_updatetime'] =  time();
			if(arr::get($arr_data,"project_status","1") == 2){
				//$arr_project_info['project_passtime'] =  time();
			}
			//招商电话
			$str = "";
			if(arr::get($arr_data,"quhao") && arr::get($arr_data,"quhao") !="区号"){
				$str.=arr::get($arr_data,"quhao");
			}
			if(arr::get($arr_data,"haoma") && arr::get($arr_data,"haoma") !="号码"){
				$str.="/".arr::get($arr_data,"haoma");
			}
			if(arr::get($arr_data,"fenjihao") && arr::get($arr_data,"haoma") !="分机号"){
				$str.="/".arr::get($arr_data,"fenjihao");
			}
			$arr_project_info['project_phone'] = $str;
			if(arr::get($arr_data,"mobile_num")){
				$image = project::creatPhoneImg(arr::get($arr_data,"mobile_num"));
				$new_image = "";
				if(isset($image['path']) && $image['path']){
					$new_image = common::getImgUrl(arr::get($image,"path")); 
					$arr_project_info['mobile_image'] = $new_image;
				}
			}
			$user_id = Cookie::get("user_id") ? Cookie::get("user_id") : 0;
 			if(arr::get($arr_data,"project_status") == 2 && Cookie::get("user_id") ==""){
// 			 //注册一个会员 通过手机
 				$bool_QuickRegistered = $this->QuickRegistered(arr::get($arr_data,"mobile_num"),null,true);
 				Service_Sso_Client::instance()->userLoginByMobile(arr::get($arr_data,"mobile_num"),trim(arr::get($arr_data,"mobile")));
 				//var_dump($ook);exit;
 				//注册一个个人用户信息
 				$user_info = Service_Sso_Client::instance()->getUserInfoByMobile(arr::get($arr_data,"mobile_num"));
 				$user_id = (isset($user_info->id) and $user_info->id) ? $user_info->id : $user_id;
 				//var_dump($user_info);exit;
 				$this->RegisteredPersonInfo(isset($bool_QuickRegistered->id) ? $bool_QuickRegistered->id : $user_info->id);
 			}
			//手机号码入库 或者修改
			$obj_MobileAccount = $this->DoMobileAccount(arr::get($arr_data,"mobile_num"));
			//echo "<pre>"; print_r($obj_MobileAccount);exit;
			$arr_project_info['mobile_id'] = isset($obj_MobileAccount->mobile_id) ?$obj_MobileAccount->mobile_id :0 ;
			//echo "<pre>"; print_r($arr_project_info);exit;
			//com_id
			$arr_project_info['com_id'] = $this->getComid(isset($obj_MobileAccount->user_id) ?$obj_MobileAccount->user_id :0);
			//echo "<pre>"; print_r($arr_project_info);exit;
			if($isMinGan){
				$arr_project_info['project_status'] = 3;
				$arr_project_info['project_reason'] = '很抱歉，您的项目中存在敏感词!';
			}else{
				//项目状态  暂时   为待审核的状态
				//$arr_project_info['project_status'] = arr::get($arr_data,"project_status","1");
				$arr_project_info['project_status'] = 1;
			}
			$model = ORM::factory("Quickproject");
			foreach ($arr_project_info as $key=>$val){
				$model->$key = $val;
			}
	        $db->begin();
	        //临时处理一下   全部是待审核 状态
	        $arr_data['project_status'] = 1;
	        $obj_quick_project_data = $model->create();
	        if(isset($obj_quick_project_data->project_id) && $obj_quick_project_data->project_id > 0){
	        	//品牌发源地
	        	//echo "<pre>"; print_r($arr_data);exit;
	        	if(arr::get($arr_data,"birthplace_area_id") && arr::get($arr_data,"birthplace_pro_id")){
	        		//echo 22;exit;
		        	if(arr::get($arr_data,"birthplace_area_id") != arr::get($arr_data,"birthplace_pro_id")){
		        		if(arr::get($arr_data,"birthplace_area_id")){
		        			$arr_ProjectBirthplaceArea['project_id'] = $obj_quick_project_data->project_id;
		        			//地区ID
		        			$arr_ProjectBirthplaceArea['area_id'] = arr::get($arr_data,"birthplace_area_id");
		        			//一级地区
		        			$arr_ProjectBirthplaceArea['pro_id'] = arr::get($arr_data,"birthplace_area_id");
		        			$arr_ProjectBirthplaceArea['status'] = arr::get($arr_data,"project_status","1");
		        			$bool_ProjectBirthplaceArea = $this->AddProjectBirthplaceArea($arr_ProjectBirthplaceArea);
		        		}
		        		if(arr::get($arr_data,"birthplace_pro_id")){
		        			$arr_ProjectBirthplaceArea['project_id'] = $obj_quick_project_data->project_id;
		        			//地区ID
		        			$arr_ProjectBirthplaceArea['area_id'] = arr::get($arr_data,"birthplace_pro_id");
		        			//一级地区
		        			$arr_ProjectBirthplaceArea['pro_id'] = arr::get($arr_data,"birthplace_area_id");
		        			$arr_ProjectBirthplaceArea['status'] = arr::get($arr_data,"project_status","1");
		        			$bool_ProjectBirthplaceArea = $this->AddProjectBirthplaceArea($arr_ProjectBirthplaceArea);
		        		}
		        	}else{
		        		if(arr::get($arr_data,"birthplace_area_id")){
		        			$arr_ProjectBirthplaceArea['project_id'] = $obj_quick_project_data->project_id;
		        			//地区ID
		        			$arr_ProjectBirthplaceArea['area_id'] = arr::get($arr_data,"birthplace_area_id");
		        			//一级地区
		        			$arr_ProjectBirthplaceArea['pro_id'] = arr::get($arr_data,"birthplace_area_id");
		        			$arr_ProjectBirthplaceArea['status'] = arr::get($arr_data,"project_status","1");
		        			$bool_ProjectBirthplaceArea = $this->AddProjectBirthplaceArea($arr_ProjectBirthplaceArea);
		        		}
		        	}
	        	}
	        	
	        	//商家所在地区
	        	$arr_merchants_area = array();
 	        	if(arr::get($arr_data,"merchants_area_first_id") != arr::get($arr_data,"merchants_area_second_id")){
	        		if(arr::get($arr_data,"merchants_area_first_id")){
	        			$arr_merchants_area['project_id'] = $obj_quick_project_data->project_id;
	        			//地区ID
	        			$arr_merchants_area['area_id'] = arr::get($arr_data,"merchants_area_first_id");
	        			//一级地区
	        			$arr_merchants_area['pro_id'] = arr::get($arr_data,"merchants_area_first_id");
	        			$arr_merchants_area['status'] = arr::get($arr_data,"project_status","1");
	        			$bool_ProjectMerchantsArea = $this->AddProjectMerchantsArea($arr_merchants_area);
	        		}
	        	
	        		if(arr::get($arr_data,"merchants_area_second_id")){
	        			$arr_merchants_area['project_id'] = $obj_quick_project_data->project_id;
	        			//地区ID
	        			$arr_merchants_area['area_id'] = arr::get($arr_data,"merchants_area_second_id");
	        			//一级地区
	        			$arr_merchants_area['pro_id'] = arr::get($arr_data,"merchants_area_first_id");
	        			$arr_merchants_area['status'] = arr::get($arr_data,"project_status","1");
	        			$bool_ProjectMerchantsArea = $this->AddProjectMerchantsArea($arr_merchants_area);
	        		}
	        	}else{
	        		if(arr::get($arr_data,"merchants_area_first_id")){
	        			$arr_merchants_area['project_id'] = $obj_quick_project_data->project_id;
	        			//地区ID
	        			$arr_merchants_area['area_id'] = arr::get($arr_data,"merchants_area_first_id");
	        			//一级地区
	        			$arr_merchants_area['pro_id'] = arr::get($arr_data,"merchants_area_first_id");
	        			$arr_merchants_area['status'] = arr::get($arr_data,"project_status","1");
	        			$bool_ProjectMerchantsArea = $this->AddProjectMerchantsArea($arr_merchants_area);
	        		}
	        	}
	        	
	        	//行业 添加
	        	$arr_quick_project_industry = array();
	        	if(arr::get($arr_data,"first_industry_id") != arr::get($arr_data,"second_industry_id")){
	        		$arr_quick_project_industry['first_industry_id'] = arr::get($arr_data,"first_industry_id");
	        		$arr_quick_project_industry['second_industry_id'] = arr::get($arr_data,"second_industry_id");
	        	}else{
	        		$arr_quick_project_industry['first_industry_id'] = arr::get($arr_data,"first_industry_id");
	        		$arr_quick_project_industry['second_industry_id'] = 0;
	        	}
	        	$arr_quick_project_industry['project_id'] = $obj_quick_project_data->project_id;
	        	$arr_quick_project_industry['status'] = arr::get($arr_data,"project_status","1");
	        	//echo "<pre>"; print_r($arr_quick_project_industry);exit;
	        	$bool_Quickprojectindustry = $this->AddQuickprojectindustry($arr_quick_project_industry);
	        	//招商地区
	        	$bool_QuickprojectArea = false;
	        	//echo "<pre>"; print_r($arr_data);exit;
	        	if(arr::get($arr_data,"project_city")){
	        		$bool_QuickprojectArea = $this->AddQuickprojectArea($obj_quick_project_data->project_id,arr::get($arr_data,"project_city"),arr::get($arr_data,"project_status","1"));
	        	}
	        	//var_dump($bool_QuickprojectArea);exit;
	        	//经营模式
	        	$arr_Quickprojectmodel = arr::get($arr_data,"project_model_type",array());
	        	
	        	if(count($arr_Quickprojectmodel)){
	        		foreach ($arr_Quickprojectmodel as $key =>$val){
	        			$arr_linshi = array();
	        			$arr_linshi['project_id'] =  $obj_quick_project_data->project_id;
	        			$arr_linshi['type_id'] = intval($val);
	        			$arr_linshi['status'] = arr::get($arr_data,"project_status","1");
	        			$this->AddQuickprojectmodel($arr_linshi);
	        		}
	        	}
	        	//echo "<pre>"; print_r($arr_data);exit;
	        	//添加图片
	        	if(arr::get($arr_data,"project_img_url")){
	        		$arr_image = explode(",", arr::get($arr_data,"project_img_url"));
	        		if(count($arr_image) > 0){
	        			foreach ($arr_image as $key=>$val){
	        				$arr_linshi = array("project_id"=>$obj_quick_project_data->project_id,
	        									"project_type"=>1,
	        									"project_img"=>common::getImgUrl($val),
	        									"project_addtime"=>time());
	        				$this->AddQuickProjectImages($arr_linshi);
	        			}
	        		}
	        	}
	        	if($obj_quick_project_data->project_id > 0 && $bool_ProjectMerchantsArea == true && $bool_Quickprojectindustry == true && $bool_QuickprojectArea == true){
	        		$db->commit();
	        		//项目统计
	        		$service_stat = new Service_Platform_Project();
	        		$pro_stat = array('project_id' => $obj_quick_project_data->project_id,'user_id' => $user_id);
	        		$sps = $service_stat->setProjectStat($pro_stat);
	        		if(!$sps){
	        			common::sendemail('免费发布生意日志写入错误_'.$obj_quick_project_data->project_id, '', 'akirametero@gmail.com', 'Hello World!');
	        		}
	        		//发送短信
	        		$str = "【一句话生意网】恭喜您的生意信息已发布成功，您可以随时登录网站查看。";
	        		common::send_message($mobile,$str,'online');
	        		$servicemobile = new Service_User ();
	        	//	if($user_id){
	        			$servicemobile->messageLog($mobile,$user_id,5,$str,1);
	        		//}
	        		
	        		if($isMinGan){
	        			$service_pro = new Service_QuickPublish_Project();
	        			$arr_data = array('status' => 3);
						//更新商家所在地状态
		    			$service_pro->ChangeProjectMerchantsArea($obj_quick_project_data->project_id, $arr_data);
		    			//更新招商形式状态	
		    			$service_pro->ChangeProjectModel($obj_quick_project_data->project_id, $arr_data);
		    			//更新招商行业状态
		    			$service_pro->ChangeProjectIndustry($obj_quick_project_data->project_id, $arr_data);
		    			//更新招商地区状态
		    			$service_pro->ChangeQuickProjectArea($obj_quick_project_data->project_id, $arr_data);
		    			//更新品牌发源地状态
		    			$service_pro->ChangeProjectBirthplaceArea($obj_quick_project_data->project_id, $arr_data);
	        		}
	        		if($obj_quick_project_data->project_status == 2){
		        		//更新索引
		        		$service_api = new Service_Api_Search();
						$service_api->reflashIndex($obj_quick_project_data->project_id);	
	        		}        		
					return true;
	        	}else{ 
	        		$db->rollback();
	        		return false;
	        	}
	        }else{
	        	$db->rollback();
	        	return false;
	        }
	        
		}catch (Kohana_Exception $e){$db->rollback(); return  false;}
	}
	
	/**
	* Enter descrīption here…
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-5
	* @return int/bool/object/array
	*/
	public function RegisteredPersonInfo($user_id){
		//echo $user_id;;exit;
		$model = ORM::factory("Personinfo");
		$count = $model->where("per_user_id","=",$user_id)->count_all();
		//echo $count."////".$user_id;exit;
		if($count == 0){
			try {
				$model_new = ORM::factory("Personinfo");
				$model_new->per_user_id = $user_id;
				$model_new->per_gender = 1;
				$model_new->per_open_stutas = 1;
				$model_new->create();
				return true;
			} catch (Kohana_Exception $e) {
			}
			
		}
	}
	
	
	
	
	/**
	* 添加图片
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-19
	* @return int/bool/object/array
	*/ 
	public function AddQuickProjectImages($arr_data){
		try{
			$model = ORM::factory("Quickprojectimages");
			IF(COUNT($arr_data) > 0){
				foreach ($arr_data as $key=>$val){
					$model->$key = $val;
				}
				$model->save();
				return true;
			}
			return false;
			
		}catch (Kohana_Exception $e){return false;}
	}
	/**
	 * 项目详情
	 * @author 嵇烨
	 */
	public function  getProjectData($project_id){
		if($project_id){
			$projects = ORM::factory('Quickproject',$project_id);
			if($projects->loaded()){
				return $projects;
			}
			return false;
		}
		return false;
	}
	/**
	* 招商地区
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-19
	* @return int/bool/object/array
	*/
	public function AddQuickprojectArea($project_id,$arr_data,$int_status = 1){
		try {
			$projectInfo = $this->getProjectData($project_id);
			foreach ($arr_data as $v){
				$project_area = ORM::factory('Quickprojectarea');
				$project_area->project_id = $project_id;
				$city = ORM::factory('city',intval($v));
				$project_area->area_id = intval($v);
				if(intval($v) > 35){//只写入市级信息
					$project_area->pro_id = intval($city->pro_id);
				}else{
					$project_area->pro_id = intval($v);
				}
				$project_area->status = $int_status;
				$project_area->save();
			}
			return true;
		}catch (Kohana_Exception $e){
			return false;
		}
	}
	
	
	/**
	* 添经营模式
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-19
	* @return int/bool/object/array
	*/
	public function AddQuickprojectmodel($arr_data){
		try {
			if(count($arr_data)){
				$project_area = ORM::factory('Quickprojectmodel');
				foreach ($arr_data as $key=>$v){
					$project_area->$key = $v;
				}
				$project_area->save();
				return  true;
			}
			return false;
		}catch (Kohana_Exception $e){
			return false;
		}
	}
	
	/**
	 * 添加行业
	 * @author Smile(jiye)
	 * @param $arr_data = array();
	 * @create_time  2014-5-16
	 * @return int/bool/object/array
	 */
	public function AddQuickprojectindustry($arr_data){
		try {
			if(count($arr_data)){
				$project_area = ORM::factory('Quickprojectindustry');
				foreach ($arr_data as $key => $v){
					$project_area->$key = $v;
				}
				$project_area->save();
				return  true;
			}
			return false;
		}catch (Kohana_Exception $e){
			return false;
		}
	}
	/**
	* 添加数据
	* @author Tim(jiye)
	* @param $str_tabel_name / $arr_data 
	* @create_time  2014-5-8
	* @return int/bool/object/array
	*/
	public function AddDatalist($str_tabel_name,$arr_data){
		try {
			if($str_tabel_name && is_array($arr_data) && count($arr_data) > 0){
				$model = ORM::factory($str_tabel_name);
				foreach ($arr_data as $key=>$val){
					$model->$key = $val;
				}
				return (array)$model->create();
			}
			
		}catch (Kohana_Exception $e){
			return array();
		}
	}
	/**
	* 添加品牌发源地
	* @author Smile(jiye)
	* @param $arr_data = array();
	* @create_time  2014-5-16
	* @return int/bool/object/array
	*/
	public function AddProjectBirthplaceArea($arr_data){
		try {
			if(count($arr_data)){
				$project_area = ORM::factory('Quickprojectbirthplacearea');
				foreach ($arr_data as $key=>$v){
					$project_area->$key = $v;
				}
				$project_area->save();
				return  true;
			}
			return false;
		}catch (Kohana_Exception $e){
			return false;
		}
	}
	
	
	/**
	 * 添加商家地区
	 * @author Smile(jiye)
	 * @param $arr_data = array();
	 * @create_time  2014-5-16
	 * @return int/bool/object/array
	 */
	public function AddProjectMerchantsArea($arr_data){
		try {
			if(count($arr_data) > 0){
				$project_area = ORM::factory('Merchantsarea');
				foreach ($arr_data as $key=>$v){
					$project_area->$key = $v;
				}
				$project_area->save();
				return  true;
			}
			return false;
		}catch (Kohana_Exception $e){
			return false;
		}
	}
	
	/**
	* 验证手机账户是不是被禁止了
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-19
	* @return int/bool/object/array
	*/
	public function IsReleaseProject($str_mobile){
		try{
			$arr_data = ORM::factory("MobileAccount")->where("mobile","=",trim($str_mobile))->find()->as_array();
			if(arr::get($arr_data,"mobile_id")  > 0 && arr::get($arr_data,"mobile_id")){
				return $arr_data;
			}
			return array();
		}catch (Kohana_Exception $e){
			return 500;
		}
	}
	/**
	* 修改手机账户表
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-20
	* @return int/bool/object/array
	*/
	public function DoUpdateMobileAccount($int_mobile_id , $arr_data){
		try{
			if($int_mobile_id && $arr_data){
				$model = ORM::factory("MobileAccount",intval($int_mobile_id));
				foreach ($arr_data as $key=>$val){
					$model->$key = $val;
				}
				$model->update();
				return true;
			}
			
			return false;
		}catch (Kohana_Exception $e){
			return false;
		}
	}
	/**
	*判断手机是否还能发布项目
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-27
	* @return int/bool/object/array
	*/
	public function haveReleaseCount($str_mobile){
		$str_mobile = trim($str_mobile);
		if($str_mobile){
			$user_id = Cookie::get("user_id");
			//登录的手机号码  必定是验证通过的手机号码  可以发5个项目
			if($user_id){
				$int_mobile_id = ORM::factory('MobileAccount')->where("user_id","=",intval($user_id))->find()->mobile_id;
				if($int_mobile_id){
					$count = ORM::factory('Quickproject')->where("mobile_id","=",$int_mobile_id)->where("project_status","<=",3)->count_all();
					//已近发布了5条
					$num = 5 - $count;
					if($num <= 0){
						return 6;
					}
				}
			}else{
				//可以发布3条
				$int_mobile_id = ORM::factory('MobileAccount')->where("mobile","=",$str_mobile)->find()->mobile_id;
				if($int_mobile_id){
					$count = ORM::factory('Quickproject')->where("mobile_id","=",$int_mobile_id)->where("project_status","<=",3)->count_all();
					//已经发布了3条
					$arr_data['count'] = 3 - $count;
					$num = 3 - $count;
					if($num <= 0){
						return 7;
					}
				}
			}
			return 0;
		}
		return false;
	}
	
	
	/**
	* 判断是否有项目的项目标题
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-28
	* @return int/bool/object/array
	*/
	public function isHaveSameProjectTitle($str_project_title){
		$str_project_title = trim($str_project_title);
		if($str_project_title){
		 	return ORM::factory('Quickproject')->where("project_title","=",$str_project_title)->count_all();
		}
		return 0;
	}
	
	
	
	/**
	 * 判断是否有相同的项目
	 * @author Smile(jiye)
	 * @param
	 * @create_time  2014-5-28
	 * @return int/bool/object/array
	 */
	public function isHaveSameProject($arr_data=array()){
		//var_dump($arr_data);exit;
		if($arr_data){
			return ORM::factory('Quickproject')->where("".arr::get($arr_data,"key")."","=",arr::get($arr_data,"val"))->count_all();
		}
		return 0;
	}
	
	
	
}