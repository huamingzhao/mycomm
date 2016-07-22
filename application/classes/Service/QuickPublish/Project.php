<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * 快速发布
 * @author 郁政
 *
 */
class Service_QuickPublish_Project {
	/**
	 * 获取该用户所有快速发布的项目
	 * @author 郁政
	 *
	 */
	public function getQuickProjectList($user_id){
		$return = array();		
		if($user_id){
			$mobile_account = ORM::factory('MobileAccount')->where('user_id','=',$user_id)->find();
			if($mobile_account->loaded()){
				$mobile_id = $mobile_account->mobile_id;			
				$count = ORM::factory('Quickproject')->where('mobile_id','=',$mobile_id)->where('project_status','<>',intval(4))->order_by('project_updatetime','desc')->count_all();
				$page = Pagination::factory(array(
	                'total_items'    => $count,
	                'items_per_page' => 5,
					'view' => 'pagination/Simple',	                
        		));
        		$quick_pro = ORM::factory('Quickproject')->where('mobile_id','=',$mobile_id)->where('project_status','<>',intval(4))->order_by('project_updatetime','desc')->limit($page->items_per_page)->offset($page->offset)->find_all()->as_array();
        		if(count($quick_pro) > 0){
        			foreach($quick_pro as $k => $v){
        				$list[] = $this->pushQuickProject($v->project_id,$user_id);
        			}
        			$return['list'] = $list;
        			$return['page'] = $page;   
        		}          		  		
			}			
		}		
		return $return;
	}
	
	/**
	 * 快速发布项目列表所需数据
	 * @author 郁政
	 *
	 */
	public function pushQuickProject($project_id,$user_id){
		$res = array();
		$service = new Service_QuickPublish_ProjectComplaint();		
		$service_card = new Service_QuickPublish_User();
		$res = $service->getProjectInfoByID($project_id);	
		//取得行业	
		$res['industry_name'] = $service->getIndustryNameById($project_id);
		//取得招商地区
		$res['area'] = $service->getAreaIdName($project_id);
		//获取留言数
		$res['liuyan_count'] = $service_card->getProjectMessageCout($project_id);
		return $res;
	}
	
	/**
     * 更新发布时间  （快速发布）
     * @author 郁政
     */
    public function updateQuickProPublishTime($project_id){
    	$res = array();
    	$res['status'] = 0;
    	$res['date'] = time();
    	$service = new Service_Api_Search();
    	$quick_pro = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
    	if($quick_pro->loaded()){
    		$update_time = $quick_pro->project_updatetime;
    		$update_count = $quick_pro->project_update_count;
    		$start_time = strtotime(date('Y-m-d',time()));
    		$end_time = strtotime(date('Y-m-d',strtotime('+1 day')));
    		if($update_time >= $start_time && $update_time <= $end_time && $update_count >= 1){
    			$res['status'] = 2;    			
    		}elseif($update_time >= $start_time && $update_time <= $end_time && $update_count == 0){
    			$quick_pro->project_updatetime = $res['date'];
    			$quick_pro->project_update_count = $quick_pro->project_update_count + intval(1);
    			$suc = $quick_pro->update();
	    		if($suc->project_id > 0){	
	    			$this->addQuickProjectOperateLog($project_id, 1);    					
	    			$res['status'] = 1;
	    		}
    		}else{
    			$quick_pro->project_updatetime = $res['date'];
    			$quick_pro->project_update_count = 1;
    			$suc = $quick_pro->update();
	    		if($suc->project_id > 0){	 
	    			$this->addQuickProjectOperateLog($project_id, 1);   			
	    			$res['status'] = 1;
	    		}
    		}
    	}
    	$res['date'] = date('Y-m-d H:i:s',$res['date']);
    	return $res; 
    }
    
    /**
     * 删除项目  （快速发布）
     * @author 郁政
     */
    public function delQuickPro($project_id){
    	$bool = false;
    	if($project_id){
    		$quick_pro = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
    		if($quick_pro->loaded()){
    			$quick_pro->project_status = 4;
    			$suc = $quick_pro->update();    			
	    		if($suc->project_id > 0){
	    			$arr_data = array('status' => 4);				
	    			//更新商家所在地状态
	    			$this->ChangeProjectMerchantsArea($suc->project_id, $arr_data);
	    			//更新招商形式状态	
	    			$this->ChangeProjectModel($suc->project_id, $arr_data);
	    			//更新招商行业状态
	    			$this->ChangeProjectIndustry($suc->project_id, $arr_data);
	    			//更新招商地区状态
	    			$this->ChangeQuickProjectArea($suc->project_id, $arr_data);
	    			//更新品牌发源地状态
	    			$this->ChangeProjectBirthplaceArea($suc->project_id, $arr_data);
	    			//删除索引
	        		$service_api = new Service_Api_Search();
					$service_api->delSearchIndex($suc->project_id);	
	    			$bool = true;
	    		}
    		}
    	}
    	return $bool;
    }
    
    
    
    /**
     * 判断该项目是否属于该项目  （快速发布）
     * @author 郁政
     */
    public function isBelongToUser($project_id,$user_id){
    	$bool = false;
    	$mobile_account = ORM::factory('MobileAccount')->where('user_id','=',$user_id)->find();
    	if($mobile_account->loaded()){
			$mobile_id = $mobile_account->mobile_id;	
			$count = ORM::factory('Quickproject')->where('project_id','=',$project_id)->where('mobile_id','=',$mobile_id)->count_all();
	    	if($count > 0){
	    		$bool = true;
	    	}
    	}
    	return $bool;
    }
    
    /**
     * 项目详细信息  （快速发布）
     * @author 郁政
     */
    public function proDetailInfo($project_id){
    	$res = array();
    	$service = new Service_QuickPublish_ProjectComplaint();	
    	if($project_id){
    		$om = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
    		if($om->loaded()){
    			$res['project_id'] = $om->project_id;
    			$res['project_brand_name'] = $om->project_brand_name;
    			$res['project_logo'] = $om->project_logo;
    			$res['project_brand_birthplace_name'] = $this->getBirthplaceCityNameById($om->project_brand_birthplace_id);
    			$res['project_history'] = $om->project_history;    
    			$res['merchants_area_name'] = $this->getLocalCityNameById($project_id);		
    			$res['industry_name'] = $service->getIndustryNameById($project_id);
    			$res['project_amount_type'] = $om->project_amount_type;  
    			$res['project_joining_fee'] = $om->project_joining_fee;  
    			$res['project_security_deposit'] = $om->project_security_deposit;  
    			$res['area'] = $service->getAreaIdName($project_id);
    			$res['project_joining_fee'] = $om->project_joining_fee;      			
        		$res['project_model'] = $this->getProjectCoModel($project_id);    
        		$res['project_title'] = $om->project_title;      
        		$res['project_introduction'] = $om->project_introduction;      
        		$res['project_summary'] = $om->project_summary;      
        		$res['project_zhanshi'] = $this->getZhanShiImg($project_id); 
        		$res['project_contact_people'] = $om->project_contact_people;    
        		$res['mobile'] = $om->mobile;      
        		$res['project_phone'] = str_replace('/', '-', $om->project_phone);         		
    		}
    	}
    	return $res;
    }
    
         
    /**
     * 根据品牌发源地id获取发源地（快速发布）
     * @author 郁政
     */
    public function getBirthplaceCityNameById($project_brand_birthplace_id,$type = 1){
    	$cityName = '';
    	$cityName1 = '';
    	$cityName2 = '';
    	if($project_brand_birthplace_id){
    		$om = ORM::factory('Quickprojectbirthplacearea')->where('project_brand_birthplace_id','=',$project_brand_birthplace_id)->find();
    		if($om->loaded()){
    			if($type == 1){
	    			$area_id = $om->area_id;
	    			$city = ORM::factory('City',$area_id);
	    			if($city->loaded()){
	    				$cityName = $city->cit_name;
	    			}
    			}else{
    				$area_id = $om->area_id;
	    			$city1 = ORM::factory('City',$area_id);
	    			if($city1->loaded()){
	    				$cityName1 = $city1->cit_name;
	    			}
	    			$pro_id = $om->pro_id;
	    			$city2 = ORM::factory('City',$pro_id);
    				if($city2->loaded()){
	    				$cityName2 = $city2->cit_name;
	    			}
	    			$cityName = ($cityName1 && $cityName2) ? $cityName2.' - '.$cityName1 : '';
    			}    			
    		}
    	}
    	return $cityName;
    }
    
    /**
     * 根据品牌发源地id获取发源地信息（快速发布）
     * @author 郁政
     */
    public function getBirthplaceId($project_brand_birthplace_id){
    	$res = array();
    	$om = ORM::factory('Quickprojectbirthplacearea')->where('project_brand_birthplace_id','=',$project_brand_birthplace_id)->find();
    	if($om->loaded()){
    		$res['area_id'] = $om->area_id;
    		$res['pro_id'] = $om->pro_id;
    	}
    	return $res;
    }
    
	/**
     * 根据项目id获取所在区域（快速发布）
     * @author 郁政
     */
    public function getLocalCityNameById($project_id,$type = 1){
    	$cityName = '';
    	$cityName1 = '';
    	$cityName2 = '';
    	if($project_id){
    		$om = ORM::factory('Merchantsarea')->where('project_id','=',$project_id)->order_by('area_id','desc')->limit(1)->find();
    		if(count($om) > 0){
    			if($type == 1){
	    			$area_id = $om->area_id;
		    		$city = ORM::factory('City')->where('cit_id','=',$area_id)->find();
		    		if($city->loaded()){
		    			$cityName = $city->cit_name;
		    		}
    			}else{
    				$area_id = $om->area_id;
		    		$city1 = ORM::factory('City')->where('cit_id','=',$area_id)->find();
		    		if($city1->loaded()){
		    			$cityName1 = $city1->cit_name;
		    		}	
		    		$pro_id = $om->pro_id;
	    			$city2 = ORM::factory('City',$pro_id);
    				if($city2->loaded()){
	    				$cityName2 = $city2->cit_name;
	    			}
	    			if($area_id == $pro_id){
	    				$cityName = $cityName1;
	    			}else{
	    				$cityName = ($cityName1 && $cityName2) ? $cityName2.' - '.$cityName1 : '';
	    			}	    			
    			}    			
    		}
    	}
    	return $cityName;
    }
    
    /**
     * 根据项目id获取所在区域id（快速发布）
     * @author 郁政
     */
    public function getLocalAreaId($project_id){
    	$res = array();
    	if($project_id){
    		$om = ORM::factory('Merchantsarea')->where('project_id','=',$project_id)->order_by('area_id','desc')->limit(1)->find();
    		if($om->loaded()){
	    		$res['merchants_area_first_id'] = $om->pro_id;
	    		$res['merchants_area_second_id'] = $om->area_id;
	    	}	    	
    	}
    	return $res;
    }
    
    /**
     * 根据项目id获取行业id（快速发布）
     * @author 郁政
     */
    public function getIndustryId($project_id){
    	$res = array();
    	if($project_id){
    		$om = ORM::factory ( "Quickprojectindustry" )->where ( "project_id", "=", $project_id )->find();
    		if($om->loaded()){
	    		$res['first_industry_id'] = $om->first_industry_id;
	    		$res['second_industry_id'] = $om->second_industry_id;
	    	}	    	
    	}
    	return $res;
    }
    
	/**
     * 根据项目ID获取招商形式从数据库中取出 （快速发布）
     * @author 郁政
     */
    public function getProjectCoModel($project_id) {
          $project_model = ORM::factory('Quickprojectmodel')->where("project_id", "=", $project_id)->find_all();
            if(count($project_model) > 0){
                foreach ($project_model as $ke=>$ve){
                    $project_co_model[$ve->type_id] = $ve->type_id;
                }
                return $project_co_model;
            }else{
                return array();
            }
    }
    
	/**
     * 取得展示图信息   （快速发布）
     * @author 郁政
     */
    public function getZhanShiImg($project_id){
    	$res = array();
    	$project_img = ORM::factory('Quickprojectimages')->where('project_id','=',$project_id)->where('project_type','=',1)->find_all()->as_array();   		
    	if(count($project_img)>0){
    		foreach($project_img as $k => $v){
    			$res[$k]['project_zhanshi_pic'] = $v->project_img;    			
    		}    			
    	}	
    	return $res;    	
    }
    
    /**
     * 更新展示图信息   （快速发布）
     * @author 郁政
     */
    public function updateZhanShiImg($project_id,$date){
    	$service = new Service_QuickPublish_FastReleaseProject();
    	$om = ORM::factory('Quickprojectimages')->where('project_id','=',$project_id)->where('project_type','=',1)->find_all()->as_array();
    	if(count($om) > 0){
    		foreach($om as $v){    			
    			$this->delZhanShiImg($v->project_image_id);    			
    		}    		
    	}
		//添加图片
        if(arr::get($date,"project_img_url")){
        	$arr_image = explode(",", arr::get($date,"project_img_url"));
        	if(count($arr_image) > 0){
        		foreach ($arr_image as $key=>$val){
        			$arr_linshi = array("project_id"=>$project_id,
        								"project_type"=>1,
        								"project_img"=>trim(common::getImgUrl($val)),
        								"project_addtime"=>time());
        			$service->AddQuickProjectImages($arr_linshi);
        		}	        			
        	}
        }	
    }
    
    /**
     * 删除展示图信息   （快速发布）
     * @author 郁政
     */
    public function delZhanShiImg($project_image_id){
    	$om = ORM::factory('Quickprojectimages');
        $result = $om->where("project_image_id", "=",$project_image_id)->find();
        if($result->loaded()){
            $om->delete($project_image_id);
            return true;
        }
        return false;
    }
    
    /**
     * 快速发布项目基本信息页
     * @author 郁政
     */
	public function showQuickBasic($project_id){
		$res = array();
		$service = new Service_QuickPublish_ProjectComplaint();	
		if($project_id){
			$om = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
			if($om->loaded()){
				$res['project_id'] = $om->project_id;
    			$res['project_brand_name'] = $om->project_brand_name;
    			$res['project_brand_birthplace_name'] = $this->getBirthplaceCityNameById($om->project_brand_birthplace_id,2);
    			$res['project_brand_birthplace'] = $this->getBirthplaceId($om->project_brand_birthplace_id);
    			$res['project_history'] = $om->project_history;    
    			$res['merchants_area_name'] = $this->getLocalCityNameById($project_id,2);		
    			$res['merchants_area_ids'] = $this->getLocalAreaId($project_id);
    			$res['industry_name'] = $service->getIndustryNameById($project_id,' - ');
				$res['industry_id'] = $this->getIndustryId($project_id);
			}
		}
		return $res;
	}
	
	/**
     * 修改项目基本信息
     * @author 郁政
     */
	public function editQuickBasic($date){//echo "<pre>";print_r($date);exit;		
		if(!(Arr::get($date, 'project_brand_name','') || Arr::get($date, 'merchants_area_first_id',0) || Arr::get($date, 'merchants_area_second_id',0) || Arr::get($date, 'first_industry_id',0) || Arr::get($date, 'second_industry_id',0))){
			return false;
		}
		$bool = false;
		$project_id = Arr::get($date, 'project_id');	
		$service = new Service_Api_Search();	
		$quick_pro = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
		if($quick_pro->loaded()){			
			$valite = array();
			$valite['project_brand_name'] = Arr::get($date, 'project_brand_name','');			
			$isMinGan = $this->hasMinGanWords($valite);
			if($isMinGan){
				$quick_pro->project_status = 3;
				$quick_pro->project_reason = '很抱歉，您的项目中存在敏感词!';
			}
			$quick_pro->project_brand_name = Arr::get($date, 'project_brand_name','');
			$quick_pro->project_history = Arr::get($date, 'project_history',0);
			$quick_pro->project_updatetime = time();
			$res = $quick_pro->update();
			if($res->project_id){				
				//更新品牌发源地
				$this->updateProjectBrandBirthplace($res->project_id,Arr::get($date, 'birthplace_area_id',0),Arr::get($date, 'birthplace_pro_id',0));
				//更新所在区域
				$this->updateLocalArea($res->project_id,Arr::get($date, 'merchants_area_second_id',0),Arr::get($date, 'merchants_area_first_id',0));
				//行业分类
				$this->updateIndutryInfo($res->project_id,Arr::get($date, 'first_industry_id',0),Arr::get($date, 'second_industry_id',0));
				if($isMinGan){
					$arr_data = array('status' => 3);
					//更新商家所在地状态
	    			$this->ChangeProjectMerchantsArea($res->project_id, $arr_data);
	    			//更新招商形式状态	
	    			$this->ChangeProjectModel($res->project_id, $arr_data);
	    			//更新招商行业状态
	    			$this->ChangeProjectIndustry($res->project_id, $arr_data);
	    			//更新招商地区状态
	    			$this->ChangeQuickProjectArea($res->project_id, $arr_data);
	    			//更新品牌发源地状态
	    			$this->ChangeProjectBirthplaceArea($res->project_id, $arr_data);
				}
				if($res->project_status == 2){
					//更新索引
					$service->reflashIndex($project_id);
				}
				$bool = true;
			}
		}
		return $bool;
	}
	
	/**
     * 快速发布项目加盟信息页
     * @author 郁政
     */
	public function showQuickJiaMeng($project_id){
		$res = array();			
		if($project_id){
			$om = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
			if($om->loaded()){
				$res['project_id'] = $om->project_id;
				$res['project_amount_type'] = $om->project_amount_type;
				$res['project_joining_fee'] = $om->project_joining_fee;
				$res['project_security_deposit'] = $om->project_security_deposit;
				$res['project_security_deposit'] = $om->project_security_deposit;
				//取得招商地区
	    		$res['area'] = $this->getAreaIdName($om->project_id);
	    		//取得招商形式
	    		$res['project_model'] = $this->getProjectCoModel($om->project_id);
			}
		}
		return $res;
	}
	
	/**
     * 修改加盟信息
     * @author 郁政
     */
	public function editQuickJiaMeng($date){//echo "<pre>";print_r($date);exit;	
		if(!(Arr::get($date, 'project_amount_type',0) || Arr::get($date, 'project_city',array()) || Arr::get($date, 'project_model_type',array()))){
			return false;
		}	
		$bool = false;
		$project_id = Arr::get($date, 'project_id');	
		$service = new Service_Api_Search();	
		$quick_pro = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
		if($quick_pro->loaded()){			
			$quick_pro->project_amount_type = Arr::get($date, 'project_amount_type',0);
			$quick_pro->project_joining_fee = Arr::get($date, 'project_joining_fee',0);
			$quick_pro->project_security_deposit = Arr::get($date, 'project_security_deposit',0);
			$quick_pro->project_updatetime = time();
			$res = $quick_pro->update();
			if($res->project_id){
				//更新招商地区
				$this->updateProjectArea($res->project_id,$date['project_city']);
				//更新招商形式
	    		$this->updateProjectModel($res->project_id,isset($date['project_model_type']) ? $date['project_model_type'] : array());
	    		if($res->project_status == 2){
	    			//更新索引
					$service->reflashIndex($project_id);
	    		}	    		
	    		$bool = true;
			}
		}
		return $bool;
	}
	
	/**
     * 快速发布项目推广信息页
     * @author 郁政
     */
	public function showQuickTuiGuang($project_id){
		$res = array();		
		if($project_id){
			$om = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
			if($om->loaded()){
				$res['project_id'] = $om->project_id;
				$res['project_logo'] = $om->project_logo;
				$res['project_title'] = $om->project_title;
				$res['project_introduction'] = $om->project_introduction;
				$res['project_summary'] = $om->project_summary;
				$res['project_zhanshi'] = $this->getZhanShiImg($project_id); 				
			}	
		}
		return $res;		
	}
	
	/**
     * 修改推广信息
     * @author 郁政
     */
	public function editQuickTuiGuang($date){//echo "<pre>";print_r($date);exit;		
		if(!(Arr::get($date, 'project_title','') || Arr::get($date, 'project_summary',''))){
			return false;
		}
		$bool = false;
		$project_id = Arr::get($date, 'project_id');
		$service = new Service_Api_Search();		
		$quick_pro = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
		$quick_pro->project_updatetime = time();
		if($quick_pro->loaded()){			
			$valite = array();
			$valite['project_title'] = Arr::get($date, 'project_title','');	
			$valite['project_introduction'] = Arr::get($date, 'project_introduction','');
			$valite['project_summary'] = Arr::get($date, 'project_summary','');		
			$isMinGan = $this->hasMinGanWords($valite);
			if($isMinGan){
				$quick_pro->project_status = 3;
				$quick_pro->project_reason = '很抱歉，您的项目中存在敏感词!';
			}
			$quick_pro->project_title = Arr::get($date, 'project_title','');
			$quick_pro->project_introduction = Arr::get($date, 'project_introduction','');
			$quick_pro->project_summary = Arr::get($date, 'project_summary','');	
			$quick_pro->project_logo = common::getImgUrl(Arr::get($date, 'project_imgname',''));
			$res = $quick_pro->update();
			if($res->project_id){
				$this->updateZhanShiImg($res->project_id,$date);
				if($isMinGan){
					$arr_data = array('status' => 3);
					//更新商家所在地状态
	    			$this->ChangeProjectMerchantsArea($res->project_id, $arr_data);
	    			//更新招商形式状态	
	    			$this->ChangeProjectModel($res->project_id, $arr_data);
	    			//更新招商行业状态
	    			$this->ChangeProjectIndustry($res->project_id, $arr_data);
	    			//更新招商地区状态
	    			$this->ChangeQuickProjectArea($res->project_id, $arr_data);
	    			//更新品牌发源地状态
	    			$this->ChangeProjectBirthplaceArea($res->project_id, $arr_data);
				}
				if($res->project_status == 2){
					//更新索引
					$service->reflashIndex($project_id);	
				}				
				$bool = true;			
			}
			
		}
		return $bool;
	}
	
	/**
     * 快速发布项目联系人信息页
     * @author 郁政
     */
	public function showQuickLianXiRen($project_id){
		$res = array();		
		if($project_id){
			$om = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
			if($om->loaded()){
				$res['project_id'] = $om->project_id;
				$res['project_contact_people'] = $om->project_contact_people;
				$res['mobile'] = $om->mobile;
				$res['project_phone'] = $om->project_phone;
				if($res['project_phone']){
					$phone = explode('/', $res['project_phone']);					
					$res['quhao'] = isset($phone[0]) && $phone[0] ? $phone[0] : '';
					$res['haoma'] = isset($phone[1]) && $phone[1] ? $phone[1] : '';
					$res['fenjihao'] = isset($phone[2]) && $phone[2] ? $phone[2] : '';
				}
			}
		}
		return $res;
	}
	
	/**
     * 修改联系人信息
     * @author 郁政
     */
	public function editQuickLianXiRen($date){//echo "<pre>";print_r($date);exit;		
		$bool = false;
		$project_id = Arr::get($date, 'project_id');	
		$service = new Service_Api_Search();	
		$quick_pro = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
		if($quick_pro->loaded()){
			$project_phone = (Arr::get($date, 'quhao','') == '' && Arr::get($date, 'haoma','') == '' && Arr::get($date, 'fenjihao','') == '') ? '' : Arr::get($date, 'quhao','').'/'.Arr::get($date, 'haoma','').'/'.Arr::get($date, 'fenjihao','');
			$quick_pro->project_phone = $project_phone;
			$quick_pro->project_updatetime = time();
			$res = $quick_pro->update();
			if($res->project_id){
				if($res->project_status == 2){
					//更新索引
					$service->reflashIndex($project_id);
				}				
				$bool = true;
			}else{
				$bool = false;
			}			
		}
		return $bool;
	}
	
	/**
     * 更新品牌发源地
     * @author 郁政
     */
	public function updateProjectBrandBirthplace($project_id,$area_id,$pro_id){
		$om = ORM::factory('Quickprojectbirthplacearea')->where('project_id','=',$project_id)->find_all()->as_array();			
		$status = 1;
		if(count($om) > 0){			
			foreach($om as $v){
				$status = $v->status;
				$this->delProjectBrandBirthplace($v->project_brand_birthplace_id);			
			}			
		}
		$om2 = ORM::factory('Quickprojectbirthplacearea');
		$om2->project_id = $project_id;
		$om2->area_id = $pro_id;
		$om2->pro_id = $area_id;
		$om2->status = $status;
		$id = $om2->create();
		$om3 = ORM::factory('Quickproject')->where('project_id','=',$project_id)->find();
		if($om3->loaded()){
			$om3->project_brand_birthplace_id = $id;
			$om3->update();
		}
		$om4 = ORM::factory('Quickprojectbirthplacearea');
		$om4->project_id = $project_id;
		$om4->area_id = $area_id;
		$om4->pro_id = $area_id;
		$om4->status = $status;
		$om4->create();		
	}
	
	/**
     * 删除品牌发源地
     * @author 郁政
     */
	public function delProjectBrandBirthplace($project_brand_birthplace_id){
		$om = ORM::factory('Quickprojectbirthplacearea');
        $result = $om->where("project_brand_birthplace_id", "=",$project_brand_birthplace_id)->find();
        if(!empty($result->project_brand_birthplace_id)){
            $om->delete($project_brand_birthplace_id);
            return true;
        }
        return false;
	}
	
	/**
     * 更新所在区域
     * @author 郁政
     */
	public function updateLocalArea($project_id,$area_id,$pro_id){
		$om = ORM::factory('Merchantsarea')->where('project_id','=',$project_id)->find_all()->as_array();	
		$status = 1;
		if(count($om) > 0){			
			foreach($om as $v){
				$status = $v->status;
				$om1 = ORM::factory('Merchantsarea');
				$this->delLocalArea($v->merchants_area_id);				
			}			
		}
		$om2 = ORM::factory('Merchantsarea');
		$om2->project_id = $project_id;
		$om2->area_id = $area_id;
		$om2->pro_id = $pro_id;
		$om2->status = $status;
		$om2->create();
		$om3 = ORM::factory('Merchantsarea');
		$om3->project_id = $project_id;
		$om3->area_id = $pro_id;
		$om3->pro_id = $pro_id;
		$om3->status = $status;
		$om3->create();
	}
	
	/**
     * 删除所在区域
     * @author 郁政
     */
	public function delLocalArea($merchants_area_id){
		$om = ORM::factory('Merchantsarea');
        $result = $om->where("merchants_area_id", "=",$merchants_area_id)->find();
        if(!empty($result->merchants_area_id)){
            $om->delete($merchants_area_id);
            return true;
        }
        return false;
	}
	
	/**
     * 更新行业分类
     * @author 郁政
     */
	public function updateIndutryInfo($project_id,$first_industry_id,$second_industry_id){
		$om = ORM::factory('Quickprojectindustry')->where('project_id','=',$project_id)->find();
		if($om->loaded()){
			$om->first_industry_id = $first_industry_id;
			$om->second_industry_id = $second_industry_id;
			$om->update();
		}
	}
	
	/**
     * 根据项目ID获取对应地区信息 （快速发布）
     * @author 郁政
     */
    public function getProjectArea($project_id) {
        $areaIdName = $this->getAreaIdName ( $project_id );
        if (count ( $areaIdName ) > 0) {
            foreach ( $areaIdName as $key => $value ) {
                $area_city = $value ['pro_name'] . ":";
                if (isset ( $value ['data'] ) && count ( $value ['data'] ) > 0) {
                    foreach ( $value ['data'] as $ke => $va ) :
                        $area_city .= $va ['area_name'] . "+";
                    endforeach
                    ;
                    $area_city = substr ( $area_city, 0, - 1 );
                } else {
                    $area_city = $value ['pro_name'];
                }
                $area [$key] = $area_city;
            }
            return $area;
        } else {
            return "全国";
        }
    }
    
	/**
     * 读取地区id和名称 （快速发布）
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
     * 更新指定项目的地区信息（快速发布）
     * @author 郁政
     */
    public function updateProjectArea($project_id,$data){
        $project_area = ORM::factory('Quickprojectarea')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_area) > 0){
            foreach ($project_area as $k => $v){
                $this->deleteProjectArea($v->project_area_id);
            }
        }
        //添加项目地区信息
        $this->addProjectArea($project_id,$data);
        return true;
    }
    
	/**
     * 删除项目地区信息（快速发布）
     * @author 郁政
     */
    public function deleteProjectArea($project_area_id){
        $project_area = ORM::factory('Quickprojectarea');
        $result = $project_area->where("project_area_id", "=",$project_area_id)->find();
        if(!empty($result->project_area_id)){
            $project_area->delete($project_area_id);
            return true;
        }
        return false;
    }
    
	/**
     * 添加项目地区信息（快速发布）
     * @author 郁政
    */
    public function addProjectArea($project_id,$data){
        $projectInfo = $this->getProjectData($project_id);
        foreach ($data as $v){
            $project_area = ORM::factory('Quickprojectarea');
            $project_area->project_id = $project_id;
            $city = ORM::factory('city',intval($v));
            $project_area->area_id = intval($v);
            if($projectInfo->project_status == 2){
                $project_area->status = $projectInfo->project_status;
            }
            if(intval($v) > 35){//只写入市级信息
                $project_area->pro_id = intval($city->pro_id);
            }else{
                $project_area->pro_id = intval($v);
            }
            $project_area->save();
        }
        return true;
    }
    
	/**
     * 更新招商形式信息（快速发布）
     * @author 郁政
     */
    public function updateProjectModel($project_id,$data){
        $project_model = ORM::factory('Quickprojectmodel')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_model) > 0){
            foreach ($project_model as $k => $v){
                $this->deleteProjectModel($v->project_model_id);
            }
        }
        //添加招商形式信息
        $this->addProjectModel($project_id,$data);
        return true;
    }
    
	/**
     * 删除招商形式信息（快速发布）
     * @author 郁政
     */
    public function deleteProjectModel($project_model_id){
        $project_model = ORM::factory('Quickprojectmodel');
        $result = $project_model->where("project_model_id", "=",$project_model_id)->find();
        if(!empty($result->project_model_id)){
            $project_model->delete($project_model_id);
            return true;
        }
        return false;
    }
    
	/**
     * 添加招商形式信息（快速发布）
     * @author 郁政
     */
    public function addProjectModel($project_id,$data){
        if(count($data) > 0){
            $projectInfo = $this->getProjectData($project_id);
            foreach ($data as $v){
                $project_model = ORM::factory('Quickprojectmodel');
                $project_model->project_id = $project_id;
                $project_model->type_id = intval($v);
                if($projectInfo->project_status == 2){
                    $project_model->status = $projectInfo->project_status;
                }
                $project_model->save();
            }
            return true;
        }else{
            return false;
        }

    }
    
	/**
     * 项目详情 （快速发布）
     * @author 郁政
     */
    public function getProjectData($project_id){
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
     * 快速发布项目官网推荐项目
     * @author 郁政
     */
    public function getTuiJianPro($project_id, $limit){
    	$res = array();
        $project_ids = array();        
        $cache = Cache::instance('memcache');
        $date = $cache->get('quick_guanwang_tuijian_project'.$project_id);
        if($date){
        	$res = $date;
        }else{
        	$model = ORM::factory('Quickprojectindustry')->where('project_id','=',$project_id)->find();
        	if($model->loaded()){
        		$num = 0;
	            $offset = 0;
	            $count = 0;
	            $industry_id = $model->second_industry_id ? $model->second_industry_id : $model->first_industry_id;
	            $count = ORM::factory('Quickprojectindustry')->where('second_industry_id', '=', $industry_id)->where('status', '=', 2)->where('project_id', '<>', $project_id)->count_all();
	            if ($count >= $limit) {
	            	$count = ($count <= 50) ? $count : 50;
	                $offset = intval($count / $limit);
	                $num = $offset ? rand(0, $offset - 1) : 0;
	                $exhbProIndustry = ORM::factory('Quickprojectindustry')->where('second_industry_id', '=', $industry_id)->where('status', '=', 2)->where('project_id', '<>', $project_id)->order_by('pi_id','desc')->limit($limit)->offset($num)->find_all()->as_array();
	                if (count($exhbProIndustry) > 0) {
	                    foreach ($exhbProIndustry as $v) {
	                        $project_ids[] = $v->project_id;
	                    }
	                }
	            } else {
	                $arr1 = ORM::factory('Quickprojectindustry')->where('second_industry_id', '=', $industry_id)->where('status', '=', 2)->where('project_id', '<>', $project_id)->find_all()->as_array();
	                $projects_1 = array();
	                if (count($arr1) > 0) {
	                    foreach ($arr1 as $v) {
	                        $projects_1[] = $v->project_id;
	                    }
	                }
	                $projects_1 = count($projects_1) ? $projects_1 : array(0);
	                $count1 = ORM::factory('Quickprojectindustry')->where('status', '=', 2)->where('project_id', 'not in', $projects_1)->where('project_id', '<>', $project_id)->count_all();
	                $limit1 = $limit - $count;
	                $offset = intval($count1 / $limit1);
	                $num = $offset ? rand(0, $offset - 1) : 0;
	                $arr2 = ORM::factory('Quickprojectindustry')->where('status', '=', 2)->where('project_id', 'not in', $projects_1)->where('project_id', '<>', $project_id)->limit($limit1)->offset($num)->find_all()->as_array();
	                $projects_2 = array();
	                if (count($arr2)) {
	                    foreach ($arr2 as $v) {
	                        $projects_2[] = $v->project_id;
	                    }
	                }
	                $project_ids = array_merge($projects_1, $projects_2);
	                foreach ($project_ids as $k => $v) {
	                    if ($v == 0) {
	                        unset($project_ids[$k]);
	                    }
	                }
	            }
	            if ($project_ids) {
	                foreach ($project_ids as $v) {
	                    $res[] = $this->getProject($v);
	                }
	            }
        	}
        	
	        $cache->set('quick_guanwang_tuijian_project'.$project_id, $res,86400);
        }        
        return $res;
    }
    
	/**
     * 取得快速发布单个项目信息
     * @author 郁政
     */
    public function getProject($id){
    	$result = ORM::factory('Quickproject',$id)->as_array(); 
        return $result;
    }
    
    /**
     * 根据项目id获取招商地区名称
     * @author 郁政
     */
    public function getAreaNameById($project_id,$type = 1){
    	if($type == 1){
    		$res = '全国';
    	}else{
    		$res['area_name'] = '全国';
    		$res['area_id'] = '88';
    	}
    	
    	$om = ORM::factory('Quickprojectarea')->where('project_id','=',$project_id)->limit(1)->find();
    	if($om->loaded()){
    		$area_id = $om->pro_id;
    		$city = ORM::factory('City')->where('cit_id','=',$area_id)->find();
    		if($city->loaded()){
    			if($type == 1){
    				$res = $city->cit_name;
    			}else{
    				$res['area_name'] = $city->cit_name;
    				$res['area_id'] = $city->cit_id;
    			}
    		}
    	}
    	return $res;
    }
    
    /**
     * 根据项目id获取行业名称
     * @author 郁政
     */
    public function getIndustryNameById($project_id,$type = 1){
    	if($type == 1){
    		$res = '';
    	}else{
    		$res = array();
    	}
    	
    	$om = ORM::factory('Quickprojectindustry')->where('project_id','=',$project_id)->limit(1)->find();
    	if($om->loaded()){
    		$industry_id = $om->second_industry_id ? $om->second_industry_id : $om->first_industry_id;
    		$industry = ORM::factory('Industry')->where('industry_id','=',$industry_id)->find();
    		if($industry->loaded()){
    		if($type == 1){
	    		$res = $industry->industry_name;
	    	}else{
	    		$res ['industry_name']= $industry->industry_name;
	    		$res ['industry_id']= $industry->industry_id;
	    	}
    		}
    	}
    	return $res;
    }
    
	/**
     * 根据行业id返回父id
     * @author 郁政
     * @param  $industry_id 行业id
     */
    public function getIndustryPid($industry_id){
        $res = 0;
        $industry_id = intval($industry_id);
        $industry = ORM::factory("Industry")->where('industry_id','=',$industry_id)->find()->as_array();
        if($industry['industry_id'] != ""){
            $res = $industry['parent_id'];
            return $res;
        }else{
            return false;
        }
    }
    
    /**
     * 热门城市(官网)
     * @author 郁政
     */
    public function getHotCity($project_id,$top){
    	$res = array();
    	$cache = Cache::instance('memcache');
    	$res = $cache->get('hotcity_'.$project_id);
    	if(!$res){
    		$om = ORM::factory('Quickprojectindustry')->where('project_id','=',$project_id)->limit(1)->find();
	    	if($om->loaded()){
	    		$industry_id = $om->second_industry_id ? $om->second_industry_id : $om->first_industry_id;
	    		$industry = ORM::factory('Industry',$industry_id);
	    		if($industry->loaded()){
	    			$industry_name = $industry->industry_name;
	    		}else{
	    			$industry_name = '';
	    		}
	    		for($i = 1;$i <= $top; $i++){
	    			$area = ORM::factory('City',intval($i));
	    			if($area->loaded()){
	    				$area_name = $area->cit_name;
	    			}else{
	    				$area_name = '';
	    			}
	    			$res[$i-1]['industry_id'] = $industry_id;
	    			$res[$i-1]['area_id'] = intval($i);
	    			$res[$i-1]['name'] = str_replace('市', '', $area_name).$industry_name.'加盟';
	    		}
	    	}   
	    	$cache->set('hotcity_'.$project_id, $res,86400); 
    	}    		
    	return $res;
    }
    
    /**
     * 热门类目(官网)
     * @author 郁政
     */
    public function getHotIndustry($project_id,$top){
    	$res = array();
    	$cache = Cache::instance('memcache');
    	$res = $cache->get('hotindustry'.$project_id);
    	if(!$res){
	    	$om = ORM::factory('Merchantsarea')->where('project_id','=',$project_id)->order_by('area_id','desc')->limit(1)->find();
	    	if($om->loaded()){
	    		$area_id = $om->area_id;
	    		$area = ORM::factory('City',$area_id);
	    		if($area->loaded()){
	    			$area_name = $area->cit_name;
	    		}else{
	    			$area_name = '';
	    		}
	    	}
	    	$om2 = ORM::factory('Quickprojectindustry')->where('project_id','=',$project_id)->limit(1)->find();
	    	if($om2->loaded()){
	    		$industry_id = $om2->first_industry_id;
	    		$industry = ORM::factory('Industry')->where('parent_id','=',$industry_id)->limit($top)->find_all()->as_array();
	    		if(count($industry)){
	    			foreach($industry as $k => $v){
	    				$res[$k]['industry_id'] = $v->industry_id;
	    				$res[$k]['area_id'] = $area_id;
	    				$res[$k]['name'] = str_replace('市', '', $area_name).$v->industry_name.'加盟';
	    			}
	    		}
	    	}
	    	$cache->set('hotindustry'.$project_id, $res,86400); 
    	}    	
    	return $res;
    }
    
    /**
     * 热门城市(向导首页)
     * @author 郁政
     */
    public function getHotCityByIndex(){
    	$res = array();
    	$cache = Cache::instance('memcache');
    	$res = $cache->get('hotcitybyindex');
    	if(!$res){
	    	$arr = common::getXiangDaoCityConfig();
	    	if($arr){
	    		foreach($arr as $k => $v){
	    			$arr2 = array();
	    			$arr2['area_id'] = $k;
	    			$arr2['name'] = $v.'招商加盟';
	    			$res[] = $arr2;
	    		}
	    	}
	    	$cache->set('hotcitybyindex', $res,86400); 
    	}    	
    	return $res;
    }
    
    /**
     * 热门城市,热门类目(向导页有选择)
     * @author 郁政
     */
    public function getHotTj($areaid,$inid,$atype,$pmodel){
    	$res = array();
    	$cache = Cache::instance('memcache');
    	$res = $cache->get('hottj_'.$areaid.'_'.$inid.'_'.$atype.'_'.$pmodel);
    	if(!$res){
    		$modProject = new Service_Platform_Project ();
	    	$cityName = '';
	    	$hangye = '';
	    	$touzi = '';
	    	$xingshi = '';
	    	$arr = common::puickProjectModel();
	    	if($areaid){
	      		$cityName = $modProject->getQuestCont(2, $areaid);
	    	}
	    	if($inid){
	  			$hangye = $modProject->getQuestCont(6,$inid);
	  		}
	     	if($atype){
	     		$touzi = $modProject->getQuestCont(7, $atype);
	       	}
	    	if($pmodel){
	    		$xingshi = $arr[$pmodel];	       		
	    	}    	
	    	$arr = common::getXiangDaoCityConfig();
	    	if($arr){
	    		foreach($arr as $k => $v){
	    			$arr2 = array();
	    			$arr2['area_id'] = $k;
	    			$arr2['industry_id'] = $inid;
	    			$arr2['atype'] = $atype;
	    			$arr2['pmodel'] = $pmodel;
	    			$arr2['name'] = $v.$hangye.$touzi.$xingshi.'加盟';
	    			$res[] = $arr2;
	    		}
	    	}   
	    	$cache->set('hottj_'.$areaid.'_'.$inid.'_'.$atype.'_'.$pmodel, $res,86400); 
    	}    	 	
    	return $res;
    }
    
    /**
     * 根据用户id返回升级成企业审核状态
     * @author 郁政
     */
    public function getQiYeUpdateStatus($user_id){
    	$res = 0;
    	$om = ORM::factory('QuickUserCompany')->where('com_user_id','=',$user_id)->find();
    	if($om->loaded()){
    		$res = $om->com_auth_status;
    	}
    	return $res;
    }
    
    /**
     * 判断title是否存在 （快速发布）
     * @author 郁政
     */
	public function isExistTitle($project_id,$title){
		$res = array();
		$res['status'] = 0;
		$om = ORM::factory('Quickproject')->where('project_title','=',$title)->where('project_id','<>',$project_id)->find();
		if($om->loaded()){
			$res['status'] = 1;
		}
		return $res;
	}

	
	/**
     * 判断内容中是否存在敏感词 （快速发布）
     * @author 郁政
     */
	public function hasMinGanWords($date,$type = 0){
		$bool = false;
		$res = array();
		$service = new Service_Api_Quickpublish();
		$res = $service->hasMinGanWords($date);
		if(isset($res['result']) && $res['result'] == 1){
			$bool = true;
		}
		if($type == 1){
			return $res;
		}
		return $bool;
	}

        
        /**
         * 获得广告位
         * @author stone shi
         */
        public function getQuickAdvertOne($industry_id = 0, $limit = 6) {
            $industry_id = intval($industry_id);
            $key = $industry_id.'_'.$limit;
            $cache = Cache::instance('memcache');
            $return = $cache->get($key);
            if(!$return) {
                $ormcount = ORM::factory('QuickAdvert')->where('industry_id', '=', $industry_id)->where('status', '=', 1)->count_all();
                if($ormcount) {
                    $offset = ($ormcount > 6 ) ? rand(0, $ormcount-6) : 0;
                    $ormModel = ORM::factory('QuickAdvert')->where('industry_id', '=', $industry_id)->where('status', '=', 1)->limit($limit)->offset($offset)->find_all();
                }else{
                    $ormcount = ORM::factory('QuickAdvert')->where('industry_id', '=', 0)->where('status', '=', 1)->count_all();
                    $offset = ($ormcount > 6 ) ? rand(0, $ormcount-6) : 0;
                    $ormModel = ORM::factory('QuickAdvert')->where('industry_id', '=', 0)->where('status', '=', 1)->limit($limit)->offset($offset)->find_all(); 
                }
                foreach($ormModel as $val) {
                    $return[] = $val->as_array();
                }
                 $cache->set($key, $return, 600);
            }
            return $return;
        }  
        public function getQuickAdvertInfo($id) {
            $key = 'QuickAdvertInfo'.'_'.$id;
            $cache = Cache::instance('memcache');
            $return = $cache->get($key);
            if(!$return) {
                $return = ORM::factory('QuickAdvert')->where('id', '=', $id)->where('status', '=', 1)->find()->as_array();
                $cache->set($key, $return, 86400);
            }
            return $return;
        }
        
	/**
	 * 商家所在地
	 * @author Smile(jiye)
	 * @param
	 * @create_time  2014-5-18
	 * @return int/bool/object/array
	 */
	public function ChangeProjectMerchantsArea($project_id,$arr_data,$return_type = 1){
		try {
			if($project_id && $arr_data){
				$model = ORM::factory("Merchantsarea")->where("project_id","=",intval($project_id))->find_all();
				if (count ( $model ) > 0) {
					foreach ($model as $key=>$val ) {
						$models = ORM::factory ( "Merchantsarea", $val->merchants_area_id);
						if ($models->loaded ()) {
							foreach ($arr_data as $k=>$v){
								$models->$k =$v;
							}
							$obj_data = $models->update();
						}
					}
					if($return_type == 1){
						return true;
					}else{
						return $obj_data;
					}
				}
			}
			return false;
		}catch (Kohana_Exception $e){
			return false;
		}
	}
	
	/**
	 * 修改招商形式表
	 * @author Smile(jiye)
	 * @param
	 * @create_time  2014-5-18
	 * @return int/bool/object/array
	 */
	
	public function ChangeProjectModel($project_id,$arr_data,$return_type = 1){
		try {
			if($project_id && $arr_data){
				$model = ORM::factory("Quickprojectmodel")->where("project_id","=",intval($project_id))->find_all();
				if (count ( $model ) > 0) {
					foreach ($model as $key=>$val ) {
						$models = ORM::factory ( "Quickprojectmodel", $val->project_model_id);
						if ($models->loaded ()) {
							foreach ($arr_data as $k=>$v){
								$models->$k =$v;
							}
							$obj_data = $models->update();
						}
					}
					if($return_type == 1){
						return true;
					}else{
						return $obj_data;
					}
				}
			}
			return false;
		}catch (Kohana_Exception $e){
			return false;
		}
	}
	
	/**
	 * 修改招商行业表
	 * @author Smile(jiye)
	 * @param
	 * @create_time  2014-5-18
	 * @return int/bool/object/array
	 */
	public function ChangeProjectIndustry($project_id,$arr_data,$return_type = 1){
		try {
			if($project_id && $arr_data){
				$model = ORM::factory("Quickprojectindustry")->where("project_id","=",intval($project_id))->find();
				if($model->loaded()){
					foreach ($arr_data as $k=>$v){
						$model->$k =$v;
					}
					$obj_data = $model->update();
					if($return_type == 1){
						return true;
					}else{
						return $obj_data;
					}
				}
			}
			return false;
		}catch (Kohana_Exception $e){
			return false;
		}
	}
	
	/**
	* 修改招商地区表
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-18
	* @return int/bool/object/array
	*/
	public function ChangeQuickProjectArea($project_id,$arr_data,$return_type = 1){
		try {
			if($project_id && $arr_data){
				$model = ORM::factory("Quickprojectarea")->where("project_id","=",intval($project_id))->find_all();
				if (count ( $model ) > 0) {
					foreach ($model as $key=>$val ) {
						$models = ORM::factory ( "Quickprojectarea", $val->project_area_id);
						if ($models->loaded ()) {
							foreach ($arr_data as $k=>$v){
								$models->$k =$v;
							}
							$obj_data = $models->update();
						}
					}
					if($return_type == 1){
						return true;
					}else{
						return $obj_data;
					}
				}
			}
			return false;
		}catch (Kohana_Exception $e){
			return false;
		}
	}
	
	
	/**
	* 修改品牌发源地表
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-18
	* @return int/bool/object/array
	*/
	public function ChangeProjectBirthplaceArea($project_id,$arr_data,$return_type = 1){
		try {
			if($project_id && $arr_data){
				$model = ORM::factory("Quickprojectbirthplacearea")->where("project_id","=",intval($project_id))->find_all();
				if (count ( $model ) > 0) {
					foreach ($model as $key=>$val ) {
	                    $models = ORM::factory ( "Quickprojectbirthplacearea", $val->project_brand_birthplace_id);
	                    if ($models->loaded ()) {
	                       foreach ($arr_data as $k=>$v){
	                       		$models->$k =$v;
	                       }
	                      $obj_data = $models->update();
	                    }
	                }
	                if($return_type == 1){
	                	return true;
	                }else{
	                	return $obj_data;
	                }
				}
			}
			return false;
		}catch (Kohana_Exception $e){
			return false;
		}
	}
	
	/**
     * bi统计当天多少手机号发布项目 
     * @author 郁政
     */
	public function getPhoneCountByDay($date){	
		$count = 0;	
		$begin_time	= strtotime( $date." 00:00:00" );
        $end_time	= strtotime( $date." 23:59:59" );		
    	$om = ORM::factory('Quickproject')
    			->where('project_addtime','>=',$begin_time)
    			->where('project_addtime','<=',$end_time)
    			->where('project_source_type','=',intval(1))
    			->group_by('mobile')->find_all();
    	return count($om);
	}
	
	/**
     * bi统计根据sid等时间段发布的项目
     * @author 郁政
     */
	public function getQuickProForBi($date){
		$res = array();
		$date_cy	= (strtotime( $date['end'] )-strtotime( $date['begin'] ))/86400;
        for ( $i=0;$i<=$date_cy;$i++ ){
            $begin_time	= strtotime( $date['begin']." 00:00:00" )+$i*86400;
            $end_time	= strtotime( $date['begin']." 23:59:59" )+$i*86400;
            $om1 = ORM::factory('Quickproject')
           		->where('project_source_type','=',intval(1))
        		->where('project_addtime','>=',$begin_time)
    			->where('project_addtime','<=',$end_time);
    		$om2 = ORM::factory('Quickproject')
    			->where('project_source_type','=',intval(1))
        		->where('project_addtime','>=',$begin_time)
    			->where('project_addtime','<=',$end_time);	
    		$om3 = ORM::factory('Quickproject')
    			->where('project_source_type','=',intval(1))
        		->where('project_addtime','>=',$begin_time)
    			->where('project_addtime','<=',$end_time);	
    		$om4 = ORM::factory('Quickproject')
    			->where('project_source_type','=',intval(1))
        		->where('project_addtime','>=',$begin_time)
    			->where('project_addtime','<=',$end_time);	
	    	if(isset($date['campaignid']) && $date['campaignid']){
	    		$om1 = $om1->where('campaignid','=',$date['campaignid']);
	    		$om2 = $om2->where('campaignid','=',$date['campaignid']);
	    		$om3 = $om3->where('campaignid','=',$date['campaignid']);
	    		$om4 = $om4->where('campaignid','=',$date['campaignid']);
	    	}
			if(isset($date['adgroupid']) && $date['adgroupid']){
	    		$om1 = $om1->where('adgroupid','=',$date['adgroupid']);
	    		$om2 = $om2->where('adgroupid','=',$date['adgroupid']);
	    		$om3 = $om3->where('adgroupid','=',$date['adgroupid']);
	    		$om4 = $om4->where('adgroupid','=',$date['adgroupid']);
	    	}
			if(isset($date['keywordid']) && $date['keywordid']){
	    		$om1 = $om1->where('keywordid','=',$date['keywordid']);
	    		$om2 = $om2->where('keywordid','=',$date['keywordid']);
	    		$om3 = $om3->where('keywordid','=',$date['keywordid']);
	    		$om4 = $om4->where('keywordid','=',$date['keywordid']);
	    	}
	    	if(isset($date['sid']) && is_array($date['sid'])){
	    		$om1 = $om1->where('sid','in',$date['sid']);
	    		$om2 = $om2->where('sid','in',$date['sid']);
	    		$om3 = $om3->where('sid','in',$date['sid']);
	    		$om4 = $om4->where('sid','in',$date['sid']);
	    	}elseif(isset($date['sid']) && is_string($date['sid'])){
	    		$om1 = $om1->where('sid','=',$date['sid']);
	    		$om2 = $om2->where('sid','=',$date['sid']);
	    		$om3 = $om3->where('sid','=',$date['sid']);
	    		$om4 = $om4->where('sid','=',$date['sid']);
	    	}
	    	$om1 = $om1->where('project_status','=',intval(1))->count_all();
	    	$om2 = $om2->where('project_status','=',intval(2))->count_all();
	    	$om3 = $om3->where('project_status','=',intval(3))->count_all();
	    	$om4 = $om4->where('project_status','=',intval(4))->count_all();
	    	$update_count = $this->getQuickProjectUpdateCount($begin_time,$end_time);
	    	$res[date( "Y-m-d",$begin_time )] = array($om1,$om2,$om3,$om4,$update_count);//待审核、审核通过、拒绝审核、删除	    	
		}		
		return $res;		
	}
	
	/**
     * 往快速发布项目操作日志表中添加记录
     * @author 郁政
     */
	public function addQuickProjectOperateLog($project_id,$type){
		$bool = false;
		$om = ORM::factory('QuickProjectOperateLog');
		$om->project_id = $project_id;
		$om->operate_time = time();
		$om->type = $type;
		$res = $om->create();
		if(isset($res->id) && $res->id > 0){
			$bool = true;
		}
		return $bool;
	}
	
	/**
	 * 快速发布项目更新次数
	 * @author 郁政
	 *
	 */
	public function getQuickProjectUpdateCount($begin_time,$end_time){
		$count = 0;		
		$count = ORM::factory('QuickProjectOperateLog')				
				->where('type','=',intval(1))
				->where('operate_time','>=',$begin_time)
				->where('operate_time','<=',$end_time)
				->count_all();
		return $count;
	}
	
	/**
	 * 根据城市名返回城市id与城市名
	 * @author 郁政
	 *
	 */
	public function getCityInfoByCityName($city_name){
		$res = array();
		if($city_name){
			$om = ORM::factory('City')->where('cit_name','=',$city_name)->find();
			if($om->loaded()){
				$res['cit_id'] = $om->cit_id;
				$res['cit_name'] = $om->cit_name;
			}
		}
		return $res;
	}
	
	/**
	 * 取所有城市名并过滤（锁定IP地址用）
	 * @author 郁政
	 *
	 */
	public function getCityIdByName($local){
		$res = array();
		$cache = Cache::instance('redis');		
		$city_all = $cache->get('city_arr_all');
		if(!$city_all){
			$om = ORM::factory('City')->find_all()->as_array();
			$city_all = array();
			if(count($om) > 0){
				foreach($om as $k => $v){
					$city_all[$k]['cit_id'] = $v->cit_id;
					$city_all[$k]['cit_name'] = $v->cit_name;
					$city_all[$k]['pro_id'] = $v->pro_id;
				}
			}
			$cache->set('city_arr_all', $city_all);
		}			
		$city_new = $cache->get('city_arr_new');
		if(!$city_new){
			$city_new = array();
			if($city_all){				
				foreach($city_all as $k => $v){
					$city_new[$k]['cit_id'] = $v['cit_id'];
					$str = str_replace('市', '', $v['cit_name']);
					$str = str_replace('区', '', $str);
					$str = str_replace('县', '', $str);
					$city_new[$k]['cit_name'] = $str;	
					$city_new[$k]['pro_id'] = $v['pro_id'];				
				}
			}			
			$cache->set('city_arr_new', $city_new);
		}
		if($city_new){
			foreach($city_new as $k => $v){
				if($local == $v['cit_name']){
					$cit_id = $v['pro_id'] ? $v['pro_id'] : $v['cit_id'];
					$city = ORM::factory('City',$cit_id);
					if($city->loaded()){
						$res['cit_id'] = $city->cit_id;
						$res['cit_name'] = $city->cit_name;
						return $res;
					}	
				}				
			}
			foreach($city_new as $k=> $v){
				if(strpos($local, $v['cit_name']) !== false){
					$cit_id = $v['pro_id'] ? $v['pro_id'] : $v['cit_id'];
					$city = ORM::factory('City',$cit_id);
					if($city->loaded()){
						$res['cit_id'] = $city->cit_id;
						$res['cit_name'] = $city->cit_name;
						return $res;
					}		
				}
			}
		}
		return $res;
	}
	
	/**
     * 设置/取消手机防骚扰
     * @author 郁政
     */
	public function setMobileSaoRao($user_id,$mobile,$type = 1,$day = 1,$status){
		$res = array();
		$res['status'] = 0;		
		$client = Service_Sso_Client::instance();
		try {
			$userInfo = $client->getUserInfoById($user_id);
			if($userInfo && isset($userInfo->mobile) && $userInfo->mobile){
				$mobile = $userInfo->mobile;					
			}
			if($status){
				$om = ORM::factory('MobileSaorao')->where('mobile','=',$mobile)->find();
				if($om->loaded()){										
					$time = time();
					$om->start_time = $time;
					if($type == 1){
						$om->end_time = $time + $day * 3600 * 24;
					}
					$om->status = $status;
					$om->update();					
				}else{
					$msr = ORM::factory('MobileSaorao');
					$msr->user_id = $user_id;
					$msr->mobile = $mobile;
					$time = time();
					$msr->start_time = $time;
					if($type == 1){
						$msr->end_time = $time + $day * 3600 * 24;
					}
					$msr->status = $status;
					$msr->create();
				}
				$res['status'] = 1;
			}else{
				$om = ORM::factory('MobileSaorao')->where('mobile','=',$mobile)->where('status','=',intval(1))->find();
				if($om->loaded()){	
					$om->status = $status;
					$om->update();	
					$res['status'] = 2;	
				}else{
					$res['status'] = 0;
				}
			}	
		}catch(Exception $e){
			$res['status'] = 0;
		}
		return $res;
	}
	
	/**
	 * 查找该手机号是否设置过防骚扰
	 * @auther 郁政
	 */
	public function isMobileSaoRao($mobile){
		$bool = false;
		$time = time();
		$om = ORM::factory('MobileSaorao')
			->where('mobile','=',$mobile)
			->where('status','=',intval(1))
			->where('start_time','<=',$time)
			->where_open()			
			->where('end_time','>=',$time)			
			->or_where('end_time','is',null)
			->where_close()
			->find();
		if($om->loaded()){
			$bool = true;
		}
		return $bool;
	}
}
?>