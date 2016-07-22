<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 网络展会
 * @author stone shi
 *
 */
class Service_User_Company_Exhb{
    
    /**
     * 获得该用户所有的展会项目
     * @author stone shi
     */
    public function getExhbProjectList($com_id) {
        $com_id = intval($com_id);
        if(!$com_id) return array();
        $model = ORM::factory('ExhbProject');
        $count = $model->where('com_id', '=', $com_id)->count_all();
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 5,
        ));
        $array = array();
        $list = array();
        $return = $model->where('com_id', '=', $com_id)->limit($page->items_per_page)->offset($page->offset)->order_by('project_addtime', 'DESC')->find_all();
        foreach($return as $key=>$val) {
        	$project_reason = "";
            $list[] = $this->pushExhbProList($val->as_array());
            $json_project_list = (array)json_decode($list[$key]['project_reason']);
            //echo "<pre>"; print_R($json_project_list);exit;
            if(arr::get($json_project_list,"a")){
            	$project_reason .= arr::get($json_project_list, "a")."</br>";
            }
            if(arr::get($json_project_list,"b")){
            	$project_reason .= arr::get($json_project_list, "b")."</br>";
            }
            if(arr::get($json_project_list,"c")){
            	$project_reason .= arr::get($json_project_list, "c")."</br>";
            }
            if(arr::get($json_project_list,"d")){
            	$project_reason .= arr::get($json_project_list, "d")."</br>";
            }
            $list[$key]['project_reason'] =$project_reason ? $project_reason :$list[$key]['project_reason'];
        }
        $array['list'] = $list;        
        $array['page']= $page;        
        return $array;
        
    }
    
    /**
     * 获得用户所有展会数目
     * @author stone shi
     */
    public function getExhbProCount($com_id) {
        $com_id = intval($com_id);
        if(!$com_id) return FALSE;
        $model = ORM::factory('ExhbProject');
        $count = $model->where('com_id', '=', $com_id)->count_all();
        return $count;
    }
    
    /**
     * 补全项目列表
     * @author stone shi
     */
    public function pushExhbProList($list) {    	
        if(!$list) return array();
        #展会信息
        $list['exhbInfo'] = $this->exhbInfoById($list['exhibition_id']);
        #展会栏目
        $list['catalogName'] = $this->exhbCatalogName($list['exhibition_id'], $list['catalog_id']);
        #招商地区
        $list['cityArr'] = $this->getProjectArea($list['project_id']);
        #项目百分比
        $list['infoComplete'] = $this->checkProInfoComplete($list);
        #招商地区
        $list['area'] = $this->getAreaIdName($list['project_id']);
        #项目浏览量
        $service = new Service_Platform_ExhbProject();
        $list['liulan'] = $service->getProStatistics($list['project_id'], 1);
        #名片量
        $list['mpian'] = $service->getYiXiangPeopleNum($list['project_id']);
        #红包剩余数
        $tempHongbao = $service->getYouHuiJuanInfo($list['project_id']);
        $list['hongbao'] = arr::get($tempHongbao, 'shengyu', 0);
        return $list;
    }
    
    
    
    /**
     * 根据 展会id 获取展会信息
     * @author stone shi
     */
    public function exhbInfoById($exhb_id) {
        $exhb_id = intval($exhb_id);
        if(!$exhb_id) return array();
        $model = ORM::factory('Exhibition', $exhb_id)->as_array();
        return $model;
    }
    
    /**
     * 获得项目分类
     * @author stone shi
     */
    public function exhbCatalogName($exhb_id, $catalog_id) {
        $exhb_id = intval($exhb_id);
        $catalog_id = intval($catalog_id);
        if(!$exhb_id || $catalog_id) return FALSE;
        $model = ORM::factory('ExhbProjectCatalog')
                ->where('catalog_id', '=', $catalog_id)
                ->where('exhibition_id', '=', $exhb_id)
                ->where('catalog_status', '=', 1)
                ->find()
                ->as_array();
        return arr::get($model, 'catatlog_name', '');
    }
    
    /**
     * 获取企业在一句话平台发布的项目
     * @author 郁政
     */
    public function getYiJuHuaProjectByCom($com_id){
    	$res = array();
    	$project = DB::select('project_id','project_brand_name')->from('project')->where('com_id','=',$com_id)->where('project_status','in',array(1,2))->execute()->as_array();
    	if($project){
    		foreach ($project as $k => $v){
    			$res[$k]['project_id'] = $v['project_id'];
    			$res[$k]['project_brand_name'] = $v['project_brand_name'];
    		}
    	}//echo "<pre>";print_r($res);exit;
    	return $res;
    }
    
    /**
     * 获取历史展会参展项目
     * @author 郁政
     */
    public function getHistoryExhbProByCom($com_id){
    	$res = array();
    	$exhbProject = DB::select('project_id','project_brand_name')->from('exhb_project')->where('com_id','=',$com_id)->execute()->as_array();
    	if($exhbProject){
    		foreach ($exhbProject as $k => $v){
    			$res[$k]['project_id'] = $v['project_id'];
    			$res[$k]['project_brand_name'] = $v['project_brand_name'];
    		}
    	}
    	return $res;
    }
           
    /**
     * 取得网络展会参展单个项目基本信息
     * @author 郁政
     */
    public function getExhbProjectBasic($project_id,$type = 1){
    	$res = array();
    	$list = array();
    	$res['project_id'] = $project_id;
    	if($type == 1){
    		$service = new Service_User_Company_Project();
	    	$list = $service->getProject($project_id);
	    	if(isset($list['project_id']) && $list['project_id']){    		
	    		//取得项目名
	    		$res['project_name'] = $list['project_brand_name']; 
	    		//取得项目宣传图	    		
	        	$arr_xuanchuan_image = $service->getXuanChuanPic($project_id);
	        	if(is_array($arr_xuanchuan_image) && !empty($arr_xuanchuan_image)){
	        		foreach ($arr_xuanchuan_image as $key=>$val){
	        			if($val['project_type'] == intval(5)){
	        				$res['project_logo'] = $val['project_img'];
	        			}
	        		}
	        	}else{
	        		$res['project_logo'] = $list['project_logo'];
	        	}  
	    		//取得投资金额
	    		$res['project_amount_type'] = $list['project_amount_type'];
	    		//取得招商形式
	    		$res['project_model'] = $service->getProjectCoModel($project_id);
	    		//取得招商地区
	    		$res['area'] = $service->getAreaIdName($project_id);
	    	}
    	}else{
    		$list = $this->getProject($project_id); 	
	    	if(isset($list['project_id']) && $list['project_id']){    		
	    		//取得项目名
	    		$res['project_name'] = $list['project_brand_name'];
	    		//取得项目宣传图
	    		$res['project_logo'] = $list['project_logo'];	    		
	    		//取得投资金额
	    		$res['project_amount_type'] = $list['project_amount_type'];
	    		//取得招商形式
	    		$res['project_model'] = $this->getProjectCoModel($project_id);
	    		//取得招商地区
	    		$res['area'] = $this->getAreaIdName($project_id);
	    		
	    	} 
    	}     	
    	//取得行业	
	    $res['pro_industry'] = $this->getIndustryIdByPid($project_id,$type); 
    	return $res;
    }
    
    /**
     * 取得网络展会单个项目的项目推广信息
     * @author 郁政
     */
    public function getExhbTuiGuangInfo($project_id,$type=1){
    	$res = array();
    	if($type == 1){
    		$res['advertisement'] = "";
    		$res['project_coupon'] = "";
    		$res['coupon_num'] = "";
    		$res['coupon_deadline'] = "";
    		$res['project_introduction'] = "";
    	}else{
	    	$list = $this->getProject($project_id);
	    	if(isset($list['project_id']) && $list['project_id']){   
		    	$res['advertisement'] = $list['advertisement'];
		    	$res['project_coupon'] = $list['project_coupon'];
		    	$res['coupon_num'] = $list['coupon_num'];
		    	$res['coupon_deadline'] = $list['coupon_deadline'];
		    	$res['project_introduction'] = $list['project_introduction'];
	    	}
    	}    	
    	return $res;
    }
    
    /**
     * 取得网络展会单个项目的项目详情信息
     * @author 郁政
     */
    public function getExhbProjectMore($project_id,$type = 1){
    	$res = array();
    	if($type == 1){
    		$project_certs = ORM::factory('Projectcerts')->where('project_id','=',$project_id)->where('project_type','=',1)->find_all()->as_array();
    		if(count($project_certs)>0){
    			foreach($project_certs as $k => $v){
    				$res['project_zhanshi'][$k]['project_zhanshi_pic'] = $v->project_img;
    				$res['project_zhanshi'][$k]['project_zhanshi_pic_name'] = $v->project_imgname;
    				$res['project_zhanshi'][$k]['project_zhanshi_shuoming'] = "";
    			}
    		}
    		$service = new Service_User_Company_Project();  
    		$list = $service->getProject($project_id);
    		if(isset($list['project_id']) && $list['project_id']){   
	    		$res['project_advantage_img'] = "";
	    		$res['project_advantage'] = strip_tags($list['project_summary']);
	    		$res['project_running_img'] = "";
	    		$res['project_running'] = strip_tags($list['project_join_conditions']);		    	
    		}
    	}else{
    		$project_exhb_certs = ORM::factory('ExhbProjectcerts')->where('project_id','=',$project_id)->where('project_type','=',1)->find_all()->as_array();   		
    		if(count($project_exhb_certs)>0){
    			foreach($project_exhb_certs as $k => $v){
    				$res['project_zhanshi'][$k]['project_zhanshi_pic'] = $v->project_img;
    				$res['project_zhanshi'][$k]['project_zhanshi_pic_name'] = $v->project_imgname;
    				$res['project_zhanshi'][$k]['project_zhanshi_shuoming'] = $v->project_describe;
    			}    			
    		}
	    	$list = $this->getProject($project_id);
	    	if(isset($list['project_id']) && $list['project_id']){   
		    	$res['project_advantage_img'] = $list['project_advantage_img'];
		    	$res['project_advantage'] = strip_tags($list['project_advantage']);
		    	$res['project_running_img'] = $list['project_running_img'];
		    	$res['project_running'] = $list['project_running'];
		    	$res['project_video'] = $list['project_video'];
		    	$res['project_temp_video'] = $list['project_temp_video'];
	    	}
    	}    	
    	return $res;    	
    }
    
    /**
     * 取得网络展会单个项目的项目其他信息
     * @author 郁政
     */
    public function getExhbProjectOtherInfo($project_id,$com_id,$type=1){
    	$res = array();
    	if($type == 1){
    		$res['company_strength_img'] = "";
    		$service = new Service_User_Company_User();
    		$comInfo = $service->getCompanyInfoByComId($com_id);    		
    		$res['company_strength'] = isset($comInfo['com_desc']) ? strip_tags($comInfo['com_desc']) : "";
    		$res['expected_return_img'] = "";
    		$res['expected_return'] = "";
    		$res['preferential_policy_img'] = "";
    		$res['preferential_policy'] = "";
    	}else{
	    	$list = $this->getProject($project_id);
	    	if(isset($list['project_id']) && $list['project_id']){   
		    	$res['company_strength_img'] = $list['company_strength_img'];
		    	$res['company_strength'] = $list['company_strength'];
		    	$res['expected_return_img'] = $list['expected_return_img'];
		    	$res['expected_return'] = $list['expected_return'];
		    	$res['preferential_policy_img'] = $list['preferential_policy_img'];
		    	$res['preferential_policy'] = $list['preferential_policy'];
	    	}
    	}    	
    	return $res;
    }
    
    /**
     * 取得网络展会单个项目信息
     * @author 郁政
     */
    public function getProject($id){
    	$result = ORM::factory('ExhbProject',$id)->as_array(); 
        return $result;
    }
    
	/**
     * 根据项目ID获取对应行业信息（网络展会）
     * @author 郁政
     */
    public function getProjectindustry($project_id){
        $pi= ORM::factory('ExhbProjectindustry')->where("project_id", "=",$project_id)->order_by('pi_id', 'ASC')->find_all();
        if(count($pi) > 0){
            $industry_name = "";
            foreach ($pi as $key=>$value){
                $pc= ORM::factory("industry")->where("industry_id", "=",$value->industry_id)->find();
                $industry_name .= $pc->industry_name.",";
            }
            return substr($industry_name,0,-1);
        }else{
           return "";
        }
    }
    
    /**
     * 根据项目ID获取一级行业id，二级行业id，需要显示的行业名称
     * @author 郁政
     */
    public function getIndustryIdByPid($project_id,$type=1){
    	$res = array();
    	if($type == 1){
    		$model = ORM::factory('Projectindustry')->where('project_id','=',$project_id)->order_by('industry_id','asc')->find_all()->as_array();
    		if(count($model)){
    			foreach($model as $k => $v){
    				$res['industry_id'.($k+1)] = $v->industry_id; 
    			} 
    			$industry_id = isset($res['industry_id2']) ? $res['industry_id2'] : $res['industry_id1'];
    			$pi = ORM::factory('Industry')->where('industry_id','=',$industry_id)->find();
    			$res['industry_name'] =	$pi->industry_name;		
    		}
    	}else{
    		$model = ORM::factory('ExhbProjectindustry')->where('project_id','=',$project_id)->order_by('industry_id','asc')->find_all()->as_array();
    		if(count($model)){
    			foreach($model as $k => $v){
    				$res['industry_id'.($k+1)] = $v->industry_id; 
    			} 
    			$industry_id = isset($res['industry_id2']) ? $res['industry_id2'] : $res['industry_id1'];
    			$pi = ORM::factory('Industry')->where('industry_id','=',$industry_id)->find();
    			$res['industry_name'] =	$pi->industry_name;		
    		}
    	}
    	return $res;
    }
    
	/**
     * 根据项目ID获取招商形式从数据库中取出 （网络展会）
     * @author 郁政
     */
    public function getProjectCoModel($project_id) {
          $project_model = ORM::factory('ExhbProjectmodel')->where("project_id", "=", $project_id)->find_all();
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
     * 根据项目ID获取对应地区信息 （网络展会）
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
     * 读取地区id和名称 （网络展会）
     * @author 郁政
     */
    public function getAreaIdName($project_id){
        $res = ORM::factory('ExhbProjectarea')->where("project_id", "=",$project_id)->find_all();
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
   * 判断展会项目的完整度
   * @author stone shi
   * 
   */  
    public function  checkProInfoComplete($pro_obj) {
        $return = array('basicStatus' => TRUE, 'expectedReturn' => FALSE,'moreAllStatus' => TRUE, 'contactStatus' => TRUE, 'percentage' => 100);
        
        #检查推广信息
        $spreadFiled = array('advertisement', 'project_introduction');
        $tempPercentage = $this->_justCheckPercentage($pro_obj, $spreadFiled, $return['percentage'], 3);
        if($tempPercentage != $return['percentage'])
            $return['percentage'] = $tempPercentage;
        
        #项目详情信息
        $contactFiled = array('project_video');
        $tempPercentage = $this->_justCheckPercentage($pro_obj, $contactFiled, $return['percentage'], 5);
        if($tempPercentage != $return['percentage'])
            $return['percentage'] = $tempPercentage;
        $contactFiled = array('project_advantage_img', 'project_running_img');
        $tempPercentage = $this->_justCheckPercentage($pro_obj, $contactFiled, $return['percentage'], 2);
        if($tempPercentage != $return['percentage'])
            $return['percentage'] = $tempPercentage;
        
        #检查更多项目信息
        $moreFiled = array('expected_return', 'preferential_policy');
        $tempPercentage = $this->_justCheckPercentage($pro_obj, $moreFiled, $return['percentage'], 5);
        if($tempPercentage != $return['percentage'])
            $return['percentage'] = $tempPercentage;
        $moreFiled = array('company_strength_img', 'expected_return_img', 'preferential_policy_img');
        $tempPercentage = $this->_justCheckPercentage($pro_obj, $moreFiled, $return['percentage'], 2);
        if($tempPercentage != $return['percentage'])
            $return['percentage'] = $tempPercentage;
        return $return['percentage'];
    }
    
    /*
     * 我有一头CNM重来也不骑
     * @author 程序员之死
     * 私有的。你们没用 只是一个辅助函数
     */
    private function _justCheckPercentage($pro_obj, $filed, $nowPercentage, $percentage = 0) {
        if($filed) {
            foreach($filed as $val) {
                if(!arr::get($pro_obj, $val, false)) $nowPercentage = $nowPercentage - $percentage;
            }
        }
        return $nowPercentage;
    }
    
    /**
     * 网络展会参展项目添加（项目表）
     * @author 郁政
     */
    public function addProject($form){
    	$type = Arr::get($form, 'type',1);    
    	if($type == 1){
    		$outside_id = Arr::get($form, 'project_id');
    	}elseif($type == 2){
    		$om = ORM::factory('ExhbProject')->where('project_id','=',Arr::get($form, 'project_id'))->find();
    		$outside_id = $om->outside_id;
    	} 
    	$project_source = Arr::get($form, 'pro_type',1);
    	$project_brand_name = Arr::get($form, 'project_name');
    	$catalog_id = Arr::get($form, 'catalog_id');
    	$exhibition_id = Arr::get($form, 'exhb_id');
    	$com_id = Arr::get($form, 'com_id');
    	$project_status = 0;
    	$project_amount_type = Arr::get($form, 'project_amount_type');
    	$project_coupon = Arr::get($form, 'project_coupon');
    	$coupon_num = Arr::get($form, 'coupon_num');
    	$coupon_deadline = Arr::get($form, 'coupon_deadline',0);
    	$project_addtime = time();  
    	$project_updatetime = 0;
    	$project_passtime = 0;
    	$project_logo = Arr::get($form, 'project_logo'); 
    	$advertisement = Arr::get($form, 'advertisement'); 
    	$project_introduction = Arr::get($form, 'project_introduction'); 
    	$project_temp_video = Arr::get($form, 'project_temp_video',''); 
    	$project_advantage_img = Arr::get($form, 'project_advantage_img'); 
    	$project_advantage = Arr::get($form, 'project_advantage'); 
    	$project_running_img = Arr::get($form, 'project_running_img'); 
    	$project_running = Arr::get($form, 'project_running');
    	$company_strength_img = Arr::get($form, 'company_strength_img'); 
    	$company_strength = Arr::get($form, 'company_strength'); 
    	$expected_return_img = Arr::get($form, 'expected_return_img'); 
    	$expected_return = Arr::get($form, 'expected_return'); 
    	$preferential_policy_img = Arr::get($form, 'preferential_policy_img'); 
    	$preferential_policy = Arr::get($form, 'preferential_policy');
    	$exhb_project = ORM::factory('ExhbProject');
    	$exhb_project->outside_id = $outside_id;
    	$exhb_project->project_source = $project_source;
    	$exhb_project->project_brand_name = $project_brand_name;
    	$exhb_project->catalog_id = $catalog_id;
    	$exhb_project->exhibition_id = $exhibition_id;
    	$exhb_project->com_id = $com_id;
    	$exhb_project->project_status = $project_status;
    	$exhb_project->project_amount_type = $project_amount_type;
    	$exhb_project->project_coupon = $project_coupon;
    	$exhb_project->coupon_num = $coupon_num;
    	$exhb_project->coupon_deadline = $coupon_deadline ? strtotime($coupon_deadline) : 0;
    	$exhb_project->project_addtime = $project_addtime;
    	$exhb_project->project_updatetime = $project_updatetime;
    	$exhb_project->project_passtime = $project_passtime;
    	$exhb_project->project_logo = common::getImgUrl($project_logo);
    	$exhb_project->advertisement = $advertisement;
    	$exhb_project->project_introduction = $project_introduction;
    	$exhb_project->project_temp_video = $project_temp_video;
    	$exhb_project->project_advantage_img = common::getImgUrl($project_advantage_img);
    	$exhb_project->project_advantage = zixun::setContentReplace($project_advantage);
    	$exhb_project->project_running_img = common::getImgUrl($project_running_img);
    	$exhb_project->project_running = zixun::setContentReplace($project_running);
    	$exhb_project->company_strength_img = common::getImgUrl($company_strength_img);
    	$exhb_project->company_strength = zixun::setContentReplace($company_strength);
    	$exhb_project->expected_return_img = common::getImgUrl($expected_return_img);
    	$exhb_project->expected_return = zixun::setContentReplace($expected_return);
    	$exhb_project->preferential_policy_img = common::getImgUrl($preferential_policy_img);
    	$exhb_project->preferential_policy = zixun::setContentReplace($preferential_policy);
    	$res=$exhb_project->create();    	 
    	if($res->project_id){
    		//添加地区
    		$this->addProjectArea($res->project_id,$form['project_city']);
    		//添加招商形式
    		$this->addProjectModel($res->project_id,$form['project_co_model']);
    		//添加招商行业
    		$this->addProjectIndustry($res->project_id,$form['project_industry_id']);
    		//添加产品展示图
    		$this->addProImgDisplay($res->project_id,$form['project_img_display']);
    		return true;
    	}else{
    		return false;
    	}
    		
    } 
        
    
    
    /**
     * 根据展会ID获得项目分类信息
     * @author 郁政
     */
    public function getCatalogIdByExhibitionId($exhibition_id){ 
    	$res = array();  	
    	$model = ORM::factory('ExhbProjectCatalog')->where('catalog_status','=',1)->where('exhibition_id','=',$exhibition_id)->find_all()->as_array();
    	if(count($model)>0){
    		foreach($model as $k => $v){
    			$res[$k]['catalog_id'] = $v->catalog_id;
    			$res[$k]['catalog_name'] = $v->catalog_name;
    		}
    	}
    	return $res;
    }
    
	/**
     * 添加项目地区信息（网络展会）
     * @author 郁政
    */
    public function addProjectArea($project_id,$data){
        $projectInfo = $this->getProjectData($project_id);
        foreach ($data as $v){
            $project_area = ORM::factory('ExhbProjectarea');
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
     * 添加招商形式信息（网络展会）
     * @author 郁政
     */
    public function addProjectModel($project_id,$data){
        if(count($data) > 0){
            $projectInfo = $this->getProjectData($project_id);
            foreach ($data as $v){
                $project_model = ORM::factory('ExhbProjectmodel');
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
     * 添加招商行业信息（网络展会）
     * @author 郁政
     */
    public function addProjectIndustry($project_id,$data){
        if(count($data) > 0){
            $projectInfo = $this->getProjectData($project_id);
            foreach ($data as $v){
                $project_model = ORM::factory('ExhbProjectindustry');
                $project_model->project_id = $project_id;
                $project_model->industry_id = intval($v);
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
     * 项目详情 （网络展会）
     * @author 郁政
     */
    public function  getProjectData($project_id){
        if($project_id){
            $projects = ORM::factory('ExhbProject',$project_id);
            if($projects->loaded()){
                return $projects;
            }
            return false;
        }
        return false;
    }
    
    /**
     * 添加产品展示图（网络展会）
     * @author 郁政
     */
    public function addProImgDisplay($project_id,$data){
    	if(count($data) > 0){
    		foreach ($data as $k => $v){
    			if(isset($v['project_img_url']) && $v['project_img_url'] != ""){
    				$project_model = ORM::factory('ExhbProjectcerts');
	    			$project_model->project_id = $project_id;
	    			$project_model->project_type = intval(1);
	    			$project_model->project_img = common::getImgUrl($v['project_img_url']);
	    			$project_model->project_imgname = $v['project_imgname'];
	    			$project_model->project_describe = $v['project_describe'];
	    			$project_model->project_order = $k+1;
	    			$project_model->project_addtime = time();
	    			$project_model->save();
    			}    			
    		}
    		return true;
    	}else{
            return false;
        }
    }

    /**
     * 处理项目详情页需要的数据（网络展会）
     * @author 郁政
     */
    public function showExhbProDetail($date){
    	$res = array();
    	$res = $date;
    	if(isset($date['project_id']) && $date['project_id']){
    		$exhb_pro = $this->getProject($date['project_id']);
    		$exhb_id = $exhb_pro['exhibition_id'];
    		$exhb_info = $this->exhbInfoById($exhb_id);
    		$res['exhibition_name'] = $exhb_info['exhibition_name'];
    		
    	}
    	//echo "<pre>";print_r($res);exit;
    	return $res;
    }
    
    /**
     * 是否存在某一网络展会项目
     * @author 郁政
     */
    public function isHaveExhPro($project_id,$com_id){
    	$count = 0;
    	$count = ORM::factory('ExhbProject')->where('project_id','=',$project_id)->where('com_id','=',$com_id)->count_all();
    	return $count;
    }
    
    /**
     * 网络展会参展修改项目基本信息
     * @author 郁政
     */
    public function editExhbBasic($date){
    	$project_id = Arr::get($date, 'project_id');
    	$exhbPro = ORM::factory('ExhbProject',$project_id);  
    	if($exhbPro->loaded()){
	    	$exhbPro->catalog_id = Arr::get($date, 'catalog_id');
	    	$exhbPro->project_brand_name = Arr::get($date, 'project_name');
	    	$exhbPro->project_logo = Arr::get($date, 'project_logo');
	    	$exhbPro->project_amount_type = Arr::get($date, 'project_amount_type');
	    	$exhbPro->project_status = 0;
	    	$res = $exhbPro->update();    	
	    	if($res->project_id){
	    		//更新地区
	    		$this->updateProjectArea($res->project_id,$date['project_city']);
	    		//更新招商形式
	    		$this->updateProjectModel($res->project_id,$date['project_co_model']);
	    		//更新招商行业
	    		$this->updateProjectIndustry($res->project_id,$date['project_industry_id']);
	    		return true;
	    	}else{
	    		return false;
	    	}
    	}  	
    	return false;
    }
    
    /**
     * 网络展会参展项目修改推广信息
     * @author 郁政
     */
    public function editExhbTuiGuang($date){
    	$project_id = Arr::get($date, 'project_id');
    	$exhbPro = ORM::factory('ExhbProject',$project_id); 
    	if($exhbPro->loaded()){
	    	$exhbPro->advertisement = Arr::get($date, 'advertisement');
	    	$exhbPro->project_coupon = Arr::get($date, 'project_coupon');
	    	$exhbPro->coupon_num = Arr::get($date, 'coupon_num');
	    	$exhbPro->coupon_deadline =Arr::get($date, 'coupon_deadline',0) ? strtotime(Arr::get($date, 'coupon_deadline')) : 0;
	    	$exhbPro->project_introduction = Arr::get($date, 'project_introduction');
	    	$exhbPro->project_status = 0;
	    	$res = $exhbPro->update();
	    	if($res->project_id){
	    		return true;
	    	}else{
	    		return false;
	    	}
    	}
    	return false;
    }
    
    /**
     * 网络展会参展项目修改更多信息
     * @author 郁政
     */
    public function editExhbMore($date){
    	$project_id = Arr::get($date, 'project_id');
    	$exhbPro = ORM::factory('ExhbProject',$project_id);
    	if($exhbPro->loaded()){
    		$exhbPro->project_temp_video = Arr::get($date, 'project_temp_video');
    		$exhbPro->project_advantage_img = common::getImgUrl(Arr::get($date, 'project_advantage_img'));
    		$exhbPro->project_advantage = zixun::setContentReplace(Arr::get($date, 'project_advantage'));
    		$exhbPro->project_running_img = common::getImgUrl(Arr::get($date, 'project_running_img'));
    		$exhbPro->project_running = zixun::setContentReplace(Arr::get($date, 'project_running'));	    	
    		$exhbPro->project_status = 0;
    		$res = $exhbPro->update();
	    	if($res->project_id){
	    		//更新产品展示图
    			$this->updateProImgDisplay($res->project_id,$date['project_img_display']);
	    		return true;
	    	}else{
	    		return false;
	    	}
    	}
    	return false;    	
    }
    
    /**
     * 网络展会参展项目修改其他信息
     * @author 郁政
     */
    public function editExhbOther($date){
    	$project_id = Arr::get($date, 'project_id');
    	$exhbPro = ORM::factory('ExhbProject',$project_id);
    	if($exhbPro->loaded()){
    		$exhbPro->company_strength_img = common::getImgUrl(Arr::get($date, 'company_strength_img'));
    		$exhbPro->company_strength = zixun::setContentReplace(Arr::get($date, 'company_strength'));
    		$exhbPro->expected_return_img = common::getImgUrl(Arr::get($date, 'expected_return_img'));
    		$exhbPro->expected_return = zixun::setContentReplace(Arr::get($date, 'expected_return'));
    		$exhbPro->preferential_policy_img = common::getImgUrl(Arr::get($date, 'preferential_policy_img'));
    		$exhbPro->preferential_policy = zixun::setContentReplace(Arr::get($date, 'preferential_policy'));
    		$exhbPro->project_status = 0;
    		$res = $exhbPro->update();
    		if($res->project_id){
    			return true;
    		}else{
    			return false;
    		}
    	}
    	return false;
    }
    
	/**
     * 更新招商行业信息（网络展会）
     * @author 郁政
     */
    public function updateProjectIndustry($project_id,$data){
        $project_model = ORM::factory('ExhbProjectindustry')->where("project_id", "=", $project_id)->find_all();
        //删除以前的数据
        if(count($project_model) > 0){
            foreach ($project_model as $k => $v){
                $this->deleteProjectIndustry($v->pi_id);
            }
        }
        //添加招商形式信息
        $this->addProjectIndustry($project_id,$data);
        return true;
    }
    
	/**
     * 删除招商行业信息（网络展会）
     * @author 郁政
     */
    public function deleteProjectIndustry($pi_id){
        $project_model = ORM::factory('ExhbProjectindustry');
        $result = $project_model->where("pi_id", "=",$pi_id)->find();
        if(!empty($result->pi_id)){
            $project_model->delete($result->pi_id);
            return true;
        }
        return false;
    }
    
	/**
     * 更新招商形式信息（网络展会）
     * @author 郁政
     */
    public function updateProjectModel($project_id,$data){
        $project_model = ORM::factory('ExhbProjectmodel')->where("project_id", "=", $project_id)->find_all();
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
     * 删除招商形式信息（网络展会）
     * @author 郁政
     */
    public function deleteProjectModel($project_model_id){
        $project_model = ORM::factory('ExhbProjectmodel');
        $result = $project_model->where("project_model_id", "=",$project_model_id)->find();
        if(!empty($result->project_model_id)){
            $project_model->delete($project_model_id);
            return true;
        }
        return false;
    }
    
	
	/**
     * 更新指定项目的地区信息（网络展会）
     * @author 郁政
     */
    public function updateProjectArea($project_id,$data){
        $project_area = ORM::factory('ExhbProjectarea')->where("project_id", "=", $project_id)->find_all();
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
     * 删除项目地区信息（网络展会）
     * @author 郁政
     */
    public function deleteProjectArea($project_area_id){
        $project_area = ORM::factory('ExhbProjectarea');
        $result = $project_area->where("project_area_id", "=",$project_area_id)->find();
        if(!empty($result->project_area_id)){
            $project_area->delete($project_area_id);
            return true;
        }
        return false;
    }
    
    /**
     * 更新产品展示图（网络展会）
     * @author 郁政
     */
	public function updateProImgDisplay($project_id,$data){
    	$project_certs = ORM::factory('ExhbProjectcerts')->where('project_id','=',$project_id)->find_all();
		//删除以前的数据
        if(count($project_certs) > 0){
            foreach ($project_certs as $k => $v){
                $this->deleteProImgDisplay($v->project_certs_id);
            }
        }
        //添加产品展示图
        $this->addProImgDisplay($project_id,$data);
        return true;
	}
	
	/**
     * 删除产品展示图（网络展会）
     * @author 郁政
     */
	public function deleteProImgDisplay($project_certs_id){
		$project_certs = ORM::factory('ExhbProjectcerts');
		$result = $project_certs->where("project_certs_id", "=",$project_certs_id)->find();        
		if(!empty($result->project_certs_id)){
            $project_certs->delete($project_certs_id);
            return true;
        }
        return false;
	}
	
	/**
     * 修改二次审核状态（网络展会）
     * @author 郁政
     */
	public function updateTempStatus($project_id){
		$exhbPro = ORM::factory('ExhbProject',$project_id);
		if($exhbPro->loaded()){
			$exhbPro->project_temp_status = 1;
			$exhbPro->update();
			return true;
		}	
		return false;
	}
	
	/**
     * 招商地区
     * @author 嵇烨
     * return array()
     */
    public function Do_arr_area_list($arr_data){
        $arr_return = array();
        if(is_array($arr_data) && count($arr_data) > 0){
            $arr = array();
            foreach ($arr_data as $key=>$val){
                $arr[$val]['pro_id'] = $val;
                $arr[$val]['pro_name'] = $this->getAreaNameByAreaId($val);
                $arr_return = $arr;
            }
        }
        return $arr_return;

    }
	
	/**
     * 获取地区名称
     * @author 嵇烨
     * return string
     */
    public function getAreaNameByAreaId($int_id){
        $str_data = "";
        if($int_id){
            $mdoel = ORM::factory("City",intval($int_id));
            if($mdoel->cit_id> 0){
                $str_data = $mdoel->cit_name;
            }
        }elseif($int_id == 0){
        	$str_data = '国外';
        }
        return $str_data;

    }
    
	/**
     * 根据行业id返回行业名称
     * @author 郁政
     */
    public function getIndustryNameByIndustryId($industry_id){
        $industry_id = intval($industry_id);
        $industry_name = "";
        $ormModel = ORM::factory('Industry',$industry_id);
        if($ormModel->industry_id != ''){
            $industry_name = $ormModel->industry_name;
        }
        return $industry_name;
    }
    
    /**
	 * 显示意向加盟列表
	 * @author 郁政
	 *
	 */
    public function getYiXiangList($com_id,$cond = array(),$to_user_id){
    	$return = array();
		$res = array();		
		$type = Arr::get($cond, 'type',1);
		$name = Arr::get($cond, 'name','');
		$status = Arr::get($cond, 'status',0);
		if($type == 1){	
			$c_om = ORM::factory('ExhbCoupon')			
			->join('exhb_project','left')
			->on('exhbcoupon.project_id','=','exhb_project.project_id')			
			->where('exhb_project.com_id','=',$com_id)
			->where('exhb_project.project_status','=',1);
			if($name == '' && $status == 0){
				$count = $c_om->count_all();
			}elseif($name == '' && $status == 1){
				$count = $c_om->where('exhb_project.coupon_deadline','>=',time())
						->count_all();
			}elseif($name == '' && $status == 2){
				$count = $c_om->where('exhb_project.coupon_deadline','<',time())
						->count_all();
			}elseif($name != '' && $status == 0){
				$count = $c_om->where('exhb_project.project_brand_name','=',$name)
						->count_all();
			}elseif($name != '' && $status == 1){
				$count = $c_om->where('exhb_project.project_brand_name','=',$name)
						->where('exhb_project.coupon_deadline','>=',time())
						->count_all();
			}elseif($name != '' && $status == 2){
				$count = $c_om->where('exhb_project.project_brand_name','=',$name)
						->where('exhb_project.coupon_deadline','<',time())
						->count_all();
			}
			$page = Pagination::factory ( array (
	        	'total_items' => $count,
	           	'items_per_page' => 12
	      	));      	
	      	$om = DB::select('exhb_project.outside_id','exhb_coupon_fetch.person_id','exhb_project.project_brand_name','exhb_project.coupon_deadline')
	      		->from('exhb_coupon_fetch')
	      		->join('exhb_project','left')
				->on('exhb_project.project_id','=','exhb_coupon_fetch.project_id')	
	      		->where('exhb_project.com_id','=',$com_id)
				->where('exhb_project.project_status','=',1);				
			if($name == '' && $status == 0){
				$exhbPro = $om->order_by('exhb_coupon_fetch.fetch_id', 'desc')
							->limit ( $page->items_per_page )
					       	->offset ( $page->offset )
					       	->execute()
					     	->as_array();
			}elseif($name == '' && $status == 1){
				$exhbPro = $om->where('exhb_project.coupon_deadline','>=',time())
							->order_by('exhb_coupon_fetch.fetch_time','desc')
							->limit ( $page->items_per_page )
					       	->offset ( $page->offset )
					       	->execute()
					     	->as_array();
			}elseif($name == '' && $status == 2){
				$exhbPro = $om->where('exhb_project.coupon_deadline','<',time())
							->order_by('exhb_coupon_fetch.fetch_time','desc')
							->limit ( $page->items_per_page )
					       	->offset ( $page->offset )
					       	->execute()
					     	->as_array();
			}elseif($name != '' && $status == 0){
				$exhbPro = $om->where('exhb_project.project_brand_name','=',$name)
							->order_by('exhb_coupon_fetch.fetch_time','desc')
							->limit ( $page->items_per_page )
					       	->offset ( $page->offset )
					       	->execute()
					     	->as_array();
			}elseif($name != '' && $status == 1){
				$exhbPro = $om->where('exhb_project.project_brand_name','=',$name)
							->where('exhb_project.coupon_deadline','>=',time())
							->order_by('exhb_coupon_fetch.fetch_time','desc')
							->limit ( $page->items_per_page )
					       	->offset ( $page->offset )
					       	->execute()
					     	->as_array();
			}elseif($name != '' && $status == 2){
				$exhbPro = $om->where('exhb_project.project_brand_name','=',$name)
							->where('exhb_project.coupon_deadline','<',time())
							->order_by('exhb_coupon_fetch.fetch_time','desc')
							->limit ( $page->items_per_page )
					       	->offset ( $page->offset )
					       	->execute()
					     	->as_array();
			}			
			if($exhbPro){
				$service = new Service_User();
				$person_service = new Service_User_Person_User();
				$client = Service_Sso_Client::instance();
				$project_ids = array();
				$user_ids = array();
				$project_names = array();
				$coupons = array();
				foreach($exhbPro as $v){
					$project_ids[] = $v['outside_id'];
					$user_ids[] = $v['person_id'];
					$project_names[] = $v['project_brand_name'];
					$coupons[] = $v['coupon_deadline'];
				}			
				$userInfo = $client->getUserInfoByMoreUserId($user_ids);
				if($userInfo){
					foreach($userInfo as $k => $v){
						$res[$k]['project_id'] = $project_ids[$k];
						$userperson = $person_service->getPerson($v['id']);
						$res[$k]['per_realname'] = $userperson->per_realname ? $userperson->per_realname : '佚名';
						$res[$k]['project_brand_name'] = $project_names[$k];
						$res[$k]['mobile'] = common::decodeUserMobile($v['mobile']);
						$res[$k]['status'] = (isset($coupons[$k]) && $coupons[$k] >= time()) || (isset($coupons[$k]) && $coupons[$k] == 0) ? '正常' : '已过期';
						$res[$k]['coupon'] = (isset($coupons[$k]) && $coupons[$k]) ? date('Y-m-d',$coupons[$k]) : '无';
						$res[$k]['user_id'] = $user_ids[$k];
					}
				}	        
			}	
		}elseif($type == 2){			
			$c_om = ORM::factory('ExhbProjectstatistics')
					->join('exhb_project','left')
					->on('exhbprojectstatistics.project_id','=','exhb_project.project_id')
					->where('exhb_project.com_id','=',$com_id)					
					->where('exhbprojectstatistics.page_type','=',intval(3));					
					if($name == '' && $status == 0){					
						$count = $c_om->count_all();
					}elseif($name == '' && $status == 1){
						$count = $c_om->where('exhb_project.coupon_deadline','>=',time())								
								->count_all();
					}elseif($name == '' && $status == 2){
						$count = $c_om->where('exhb_project.coupon_deadline','<',time())								
								->count_all();
					}elseif($name != '' && $status == 0){
						$count = $c_om->where('exhb_project.project_brand_name','=',$name)								
								->count_all();
					}elseif($name != '' && $status == 1){
						$count = $c_om->where('exhb_project.project_brand_name','=',$name)
								->where('exhb_project.coupon_deadline','>=',time())								
								->count_all();
					}elseif($name != '' && $status == 2){
						$count = $c_om->where('exhb_project.project_brand_name','=',$name)
								->where('exhb_project.coupon_deadline','<',time())								
								->count_all();
					}
					$page = Pagination::factory ( array (
			        	'total_items' => $count,
			           	'items_per_page' => 12
			      	));
			      	$om = DB::select('exhb_project.outside_id','exhb_project_statistics.user_id','exhb_project.project_brand_name','exhb_project.coupon_deadline')
			      		->from('exhb_project_statistics')
			      		->join('exhb_project','left')
						->on('exhb_project_statistics.project_id','=','exhb_project.project_id')						
						->where('exhb_project.com_id','=',$com_id)	
						->where('exhb_project_statistics.page_type','=',intval(3));
					if($name == '' && $status == 0){	
						$card_list = $om->where('exhb_project.project_id','!=','')									
									->order_by('exhb_project_statistics.insert_time', 'DESC')						
									->limit ( $page->items_per_page )
							       	->offset ( $page->offset )
							       	->execute()
							     	->as_array();
					}elseif($name == '' && $status == 1){
						$card_list = $om->where('exhb_project.project_id','!=','')
									->where('exhb_project.coupon_deadline','>=',time())									
									->order_by('exhb_project_statistics.insert_time', 'DESC')						
									->limit ( $page->items_per_page )
							       	->offset ( $page->offset )
							       	->execute()
							     	->as_array();
					}elseif($name == '' && $status == 2){
						$card_list = $om->where('exhb_project.project_id','!=','')
									->where('exhb_project.coupon_deadline','<',time())									
									->order_by('exhb_project_statistics.insert_time', 'DESC')						
									->limit ( $page->items_per_page )
							       	->offset ( $page->offset )
							       	->execute()
							     	->as_array();
					}elseif($name != '' && $status == 0){
						$card_list = $om->where('exhb_project.project_id','!=','')
									->where('exhb_project.project_brand_name','=',$name)									
									->order_by('exhb_project_statistics.insert_time', 'DESC')						
									->limit ( $page->items_per_page )
							       	->offset ( $page->offset )
							       	->execute()
							     	->as_array();
					}elseif($name != '' && $status == 1){
						$card_list = $om->where('exhb_project.project_id','!=','')
									->where('exhb_project.project_brand_name','=',$name)
									->where('exhb_project.coupon_deadline','>=',time())									
									->order_by('exhb_project_statistics.insert_time', 'DESC')						
									->limit ( $page->items_per_page )
							       	->offset ( $page->offset )
							       	->execute()
							     	->as_array();
					}elseif($name != '' && $status == 2){
						$card_list = $om->where('exhb_project.project_id','!=','')
									->where('exhb_project.project_brand_name','=',$name)
									->where('exhb_project.coupon_deadline','<',time())									
									->order_by('exhb_project_statistics.insert_time', 'DESC')						
									->limit ( $page->items_per_page )
							       	->offset ( $page->offset )
							       	->execute()
							     	->as_array();
					}
					$service = new Service_User();
					$person_service = new Service_User_Person_User();
					$client = Service_Sso_Client::instance();
					$project_ids = array();
					$user_ids = array();
					$project_names = array();
					$coupons = array();
					if($card_list){
						foreach($card_list as $v){
							$project_ids[] = $v['outside_id'];
							$user_ids[] = $v['user_id'];
							$project_names[] = $v['project_brand_name'];
							$coupons[] = $v['coupon_deadline'];
						}			
					}
					$userInfo = $client->getUserInfoByMoreUserId($user_ids);
					if($userInfo){
						foreach($userInfo as $k => $v){
							$res[$k]['project_id'] = $project_ids[$k];
							$userperson = $person_service->getPerson($v['id']);
							$res[$k]['per_realname'] = $userperson->per_realname ? $userperson->per_realname : '佚名';
							$res[$k]['project_brand_name'] = $project_names[$k];
							$res[$k]['mobile'] = common::decodeUserMobile($v['mobile']);
							$res[$k]['status'] = (isset($coupons[$k]) && $coupons[$k] >= time()) || (isset($coupons[$k]) && $coupons[$k] == 0) ? '正常' : '已过期';
							$res[$k]['coupon'] = (isset($coupons[$k]) && $coupons[$k]) ? date('Y-m-d',$coupons[$k]) : '无';
							$res[$k]['user_id'] = $user_ids[$k];
						}
					}	        
		}		
		//echo "<pre>";print_r($res);exit;	
		$return['list'] = $res;
		$return['page'] = $page;
		return $return;
    }
    
    /**
     * 通过com_id 获取参展项目数量
     * @author jiye
     */
    public function getExhbProjectCount($int_com_id){
    	$int_return = 0;
    	if($int_com_id){
    		$int_return = ORM::factory("ExhbProject")->where("com_id","=",intval($int_com_id))->where("project_status","<",3)->count_all();
    	}
    	return $int_return;
    }
    
    /**
     * 获取客服信息
     * @author jiye
     */
    
    public function getCommunication($int_com_id){
    	$arr_data = array();
    	if($int_com_id){
    		$arr_data = ORM::factory("Customer")->where("com_id","=",intval($int_com_id))->find()->as_array();
    	}
    	return $arr_data;
    }
    /**
     *处理数据
     *@author jiye
     */
    public function  Do_arr_list($obj_data){
    	$return_data =array();
    	if($obj_data){
    		foreach ($obj_data as $key=>$val){
    			$return_data[] = $val->as_array();
    		}
    	}
    	return $return_data;
    }
    
    
    /**
     * 添加表数据
     * @author jiye
     * 
     */
    
    public function addList($table_name,$arr_data){
    	//echo "<pre>"; print_R($arr_data);exit;
    	$bool = false;
    	try {
    		 if($table_name && $arr_data){
    		 $model = ORM::factory($table_name);
	    		 foreach ($arr_data as $key=>$val){
	    		 	$model->$key = $val;
	    		 }
	    		 return $model->create();
    		 }
    	}catch (Error $e){
    		return array();
    	}
    }
    /**
     * 添加客服
     * @author jiye
     */
    public function AddCommunication($arr_data){
    	//添加客服组表
    	$bool = false;
    	$int_com_id = arr::get($arr_data, "com_id");
    	unset( $arr_data["com_id"]);
    	//判断是不是这个企业有客服组
    	$obj_Customer = $this->gettablelist("Customer",array("content"=>"com_id","contentfiled"=>$int_com_id),false);
    	//echo "<pre>"; print_R($obj_Customer);exit;
    	if(!$obj_Customer->customer_id){
    		$obj_Customer = "";
	    	$arr_data_customer = array('com_id'=>$int_com_id,
	    					"customer_addtime"=>time(),
	    					"customer_status" =>1
	    	);
	    	$obj_Customer  = $this->addList("Customer",$arr_data_customer);
    	}else{
    		$model = ORM::factory("Customer",intval($obj_Customer->customer_id));
    		$model->customer_status = 1;
    		$model->update();
    		$model->clear();
    	}
    	if(isset($obj_Customer->customer_id) && $obj_Customer->customer_id > 0){
    		//添加客服表
    		foreach ($arr_data as $key=>$val){
    			$arr_data_customer_group = array();
    			$arr_data_customer_group['customer_id'] = $obj_Customer->customer_id;
    			$arr_data_customer_group['customer_account'] = arr::get($val, "exhb_customer_account");
    			$arr_data_customer_group['customer_password'] = arr::get($val, "exhb_customer_password");
    			$arr_data_customer_group['customer_name'] = arr::get($val, "exhb_customer_name");
    			$arr_data_customer_group['customer_nickname'] = arr::get($val, "exhb_customer_nickname");
    			$arr_data_customer_group['customer_tel'] = arr::get($val, "exhb_customer_tel");
    			$obj_data = $this->addList("Customerinfo",$arr_data_customer_group);
    			if(isset($obj_data->customer_info_id) && $obj_data->customer_info_id > 0){
    				$bool = true;
    			}
    		}
    	}
    	return $bool;
    }
    /**
     * 获取单个客服信息
     * @author jiye
     */
    public function getOneCommunication($int_exhb_customer_id){
    	if($int_exhb_customer_id){
    		return ORM::factory("Customerinfo",intval($int_exhb_customer_id))->as_array();
    	}
    }
    /**
     * 修改单个客服
     * @author jiye
     */
    public function UpdateOneCommunication($arr_data){
    	//echo "<pre>"; print_R($arr_data);exit;
    	$bool = false;
    	//修改客服表
    	if(arr::get($arr_data,"customer_info_id")){
    			$model_Customer_info = ORM::factory("Customerinfo",intval(arr::get($arr_data,"customer_info_id")));
    			$model_Customer_info->customer_account = arr::get($arr_data, "customer_info_account");
    			$model_Customer_info->customer_password = arr::get($arr_data, "customer_info_password");
    			$model_Customer_info->customer_name = arr::get($arr_data, "customer_info_name");
    			$model_Customer_info->customer_nickname = arr::get($arr_data, "customer_info_nickname");
    			$model_Customer_info->customer_tel = arr::get($arr_data, "customer_info_tel");
    			$obj_data = $model_Customer_info->update();
    			//echo "<pre>"; print_R($obj_data->customer_info_id);exit;
    			if($obj_data->customer_info_id > 0){
    				//修改客服组表
    				$model = ORM::factory("Customer",intval(arr::get($arr_data,"customer_id")));
    				$model->customer_updatetime = time();
    				$model->customer_status = 1;
    				$model->update();
    				$model->clear();
    				$bool = true;
    			}
    	}
    	return $bool;
    }
    /**
     * 判断是不是这个企业的客服
     * @author 嵇烨
     */
    public function  isComCommunication($int_com_id){
    	return  ORM::factory("Customer")->where("com_id","=",$int_com_id)->count_all();
    }
    /**
     * 获取客服组
     * @author jiye
     * 
     */
    public function getKeFuZu($customer_id){
    	return  ORM::factory("Customer",intval($customer_id))->as_array();
    }
    /**
     * 删除客服软删除
     * @author jiye
     */
    public function DeleteCommunication($int_customer_id,$customer_info_id){
    	$bool =false;
    	//先删除客服表
    	if($int_customer_id && $customer_info_id){
    		
    			//先删除客服表
    			$model_Customer_info = ORM::factory("Customerinfo",intval($customer_info_id));
    			if($model_Customer_info->loaded()){
    				$model_Customer_info->delete();
    				$bool = true;
    				$model_Customer_info->clear();
    			}
    			if($bool == true){
    				//查找一下 是不是客服
    				$count = ORM::factory("Customerinfo")->where("customer_id","=",intval($int_customer_id))->count_all();
    				if($count == 0){
    					//删除客服组表
    					$model_Customer = ORM::factory("Customer",intval($int_customer_id));
    					if($model_Customer->loaded()){
    						$model_Customer->delete();
    						$bool = true;
    					}
    				}
    			}
    	}
    	return $bool;
    }
    
    /**
     * 获取客服信息
     * @author jiye
     */
    public function gettablelist($table,$arr_data,$true = true){
    	if($table && $arr_data){
    		if($true == true){
    			//获取全部
    			$data  =  ORM::factory($table)->where("".arr::get($arr_data, 'content')."","=","".arr::get($arr_data, "contentfiled")."")->find_all();
    			return $this->Do_arr_list($data);
    		}else{
    			//获取单个的
    			return  ORM::factory($table)->where("".arr::get($arr_data, 'content')."","=","".arr::get($arr_data, "contentfiled")."")->find();
    		}
    	}
    }
    
     /**
     * 获得项目信息
     * 
     */
    public function getProjectBasicInfo($project_id) {
        $project_id = intval($project_id);
        if(!$project_id) return array();
        $return = array();
        $return = ORM::factory('ExhbProject', $project_id)->as_array();
        return $return;
    }
    
    /**
     * 获得项目浏览诗句
     * 
     */
    
    public function getProjectPv($project_id, $begin, $end) {
        $return['pro'] = array();
        $project_id = intval($project_id);
        if(!$project_id || !$begin || !$end) return $return['pro'];
        $beginTime = strtotime($begin);
        $endTime = strtotime($end);
        if($beginTime > $endTime) return $return['pro'];
        for($i = $beginTime;$i <= $endTime;) {
            $temp['date'] = date('Y-m-d', $i);
            $pv = ORM::factory('ExhbProjectstatistics')->where('project_id', '=', $project_id)->where('page_type', '=', 1)->where('insert_time', '>=', $i)->where('insert_time', '<', $i+86400)->count_all();
            $temp['pv'] = $pv;
            $return['pro'][] = $temp;
            $i = $i + 86400;
        }
        return $return;
    }
    
    /**
     * 检查项目是否属于
     * @author stone
     */
    public function checkProjectPermission($com_id, $project_id) {
        $com_id = intval($com_id);
        $project_id = intval($project_id);
        if(!$project_id || !$com_id) return false;
        $count = ORM::factory('ExhbProject')->where('project_id', '=', $project_id)->where('com_id', '=', $com_id)->count_all();
        $return = $count ? TRUE : FALSE;
        return $return;
    }
}
