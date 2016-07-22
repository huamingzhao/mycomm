<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 网络展会 Service
 * @author 花文刚
 */
class Service_Platform_Exhibition {

    /**
     * 获取各种类型的展会
     * $type:1-开展中; 2-即将开始; 3-已经结束
     * $limit:是否限制显示个数(3个) 1-限制 0-不限制
     * @author 花文刚
     */
    public function getExhibitionShow($type, $limit = 1) {
        $model = ORM::factory("Exhibition");
        $array = array();
        $model->where('exhibition_status', '=', 1);
        if ($type == 1) {
            $model->where('exhibition_start', '<=', time());
            $model->where('exhibition_end', '>=', time());
            $model->order_by('exhibition_end', 'asc');
        }
        if ($type == 2) {
            $model->where('exhibition_start', '>', time());
            $model->order_by('exhibition_end', 'desc');
        }
        if ($type == 3) {
            $model->where('exhibition_end', '<', time());
            $model->order_by('exhibition_end', 'desc');
        }

        $num = 3; //暂定3个，后面可能会有改动
        if (($type == 2 || $type == 3) && $limit) {
            $model->limit($num);
        }

        $list = $model->find_all();
        $sev = new Service_Public();
        $ser = new Service_Platform_ExhbProject();
        foreach ($list as $k => $v) {
            $array[$k]['exhibition_id'] = $v->exhibition_id;
            $array[$k]['exhibition_name'] = $v->exhibition_name;
            $array[$k]['hongbao_num'] = $v->hongbao_num;
            $array[$k]['exhibition_advert'] = $v->exhibition_advert;
            $array[$k]['exhibition_countdown'] = $sev->getCountdown($v->exhibition_end - time(),'<span> ', '</span>');
            $array[$k]['exhibition_explain'] = $v->exhibition_explain;
            $array[$k]['exhibition_logo'] = $v->exhibition_logo;
            $array[$k]['exhibition_logo_second'] = $v->exhibition_logo_second;
            $array[$k]['hb_info'] = $ser->getHongBaoInfo($v->exhibition_id);
            $array[$k]['exhibition_start_end'] = date('m月d日', $v->exhibition_start) . '-' . date('m月d日', $v->exhibition_end);
        }

        return $array;
    }
    /**
     * 
     * @return type
     */
    public function getExhbHbCount() {
        $model = ORM::factory("Exhibition")
                ->where('exhibition_end', '>=', time())
                ->where('exhibition_status', '=', 1)
                ->find_all();
        $list = array();
        $num1 = 0;
        $num2 = 0;
        $ser = new Service_Platform_ExhbProject();
        foreach($model as $val) {
            $num1 = $num1 + $val->hongbao_num;
            $hb = $ser->getHongBaoInfo($val->exhibition_id);
            $num2 = $num2 + arr::get($hb, 'shengyu', 0);
        }
        $return = array('num1' => $num1, 'num2' => $num2);
        return $return;
    }
    
    public function getConsultingProject($exhibition_id) {
       $exhibition_id = intval($exhibition_id);
        if (!$exhibition_id)
            return array();

        $memcache = Cache::instance('memcache');
        $key = 'getConsultingProject' . $exhibition_id;
        $list = $memcache->get($key);
        $limit = 8;
        if ($list)
            return $list;
        $listMod = ORM::factory("ExhbProject")->where('project_status', '=', 1)->where('exhibition_id', '=', $exhibition_id)->order_by('project_cart_cout', 'desc')->limit($limit)->offset(0)->find_all();
        
        $list = array();
        foreach ($listMod  as $key => $val) {
            $list[$key] = $val->as_array();
            $list[$key]['isHavaGroupId'] = $this->isHavaGroupId($val->com_id);
        }
        $memcache->set($key, $list, 600);
        return $list; 
    }
    /**
     * 获取各种类型的展会
     * $type:1-开展中; 2-即将开始; 3-已经结束
     * $num:显示几个
     * @author 花文刚
     */
    public function getExhibitionByID($exhibition_id) {
        $model = ORM::factory("Exhibition");
        $info = $model->where('exhibition_id', '=', $exhibition_id)->find()->as_array();
        if($info) {
            $sev = new Service_Public();
            $ser = new Service_Platform_ExhbProject();
            $info['exhibition_start_end'] = date('m月d日', $info['exhibition_start']) . '-' . date('m月d日', $info['exhibition_end']);
            $info['exhibition_countdown'] = $sev->getCountdown($info['exhibition_end'] - time());
            $info['exhibition_czrs'] = $this->getExhbPerNum($exhibition_id);
            $info['exhibition_SigningCount'] = $this->getExhbSigningCount($exhibition_id, $info['exhibition_czrs']);
            $info['hb_info'] = $ser->getHongBaoInfo($exhibition_id);
        }
        return $info;
    }

    /** 
     * 获得展会分组
     * @author stone shi 
     * 
     */

    public function getExhibitionCatalog($exhibition_id) {
        $exhibition_id = intval($exhibition_id);
        if (!$exhibition_id)
            return array();
        $model = ORM::factory("ExhbProjectCatalog")->where('exhibition_id', '=', $exhibition_id)->where('catalog_status', '=', 1)->find_all();
        $return = array();
        foreach ($model as $val) {
            $return[] = $val->as_array();
        }
        return $return;
    }

    /**
     * 推荐知名项目
     * @author stone shi
     */
    public function getFamousProject($exhibition_id) {
        $exhibition_id = intval($exhibition_id);
        if (!$exhibition_id)
            return array();

        $memcache = Cache::instance('memcache');
        $key = 'getFamousProject' . $exhibition_id;
        $list = $memcache->get($key);
        $limit = 28;
        if ($list)
            return $list;
        $Count = ORM::factory("ExhbProject")->where('project_status', '=', 1)->where('exhibition_id', '=', $exhibition_id)->count_all();
        $rand = 0;
        $offset = 0;
        if ($Count > $limit) {
            $offset = rand(0, $Count - $limit);
            $listMod = ORM::factory("ExhbProject")->where('project_status', '=', 1)->where('exhibition_id', '=', $exhibition_id)->limit($limit)->offset($offset)->find_all();
        } else {
            $listMod = ORM::factory("ExhbProject")->where('project_status', '=', 1)->where('exhibition_id', '=', $exhibition_id)->limit($limit)->offset(0)->find_all();
        }
        $list = array();
        foreach ($listMod as $val) {
            $list[] = $val->as_array();
        }
        $memcache->set($key, $list, 600);
        return $list;
    }

    /**
     * @author stone  shi
     * 获得最新的项目
     */
    public function getNewProject($exhibition_id) {
        $exhibition_id = intval($exhibition_id);
        if (!$exhibition_id)
            return array();
        $memcache = Cache::instance('memcache');
        $key = 'getNewProject' . $exhibition_id;
        $list = $memcache->get($key);
        if ($list)
            return $list;
        $listMod = ORM::factory("ExhbProject")->where('project_status', '=', 1)->where('exhibition_id', '=', $exhibition_id)->limit(5)->offset(0)->order_by('project_id', 'DESC')->find_all();
        $list = array();
        foreach ($listMod as $val) {
            $list[] = $val->as_array();
        }
        $memcache->set($key, $list, 600);
        return $list;
    }
    /**
     * 获取参展项目的数量
     * @author jiye
     */
    public function get_exhb_project($exhibition_id){
    	return  ORM::factory("ExhbProject")->where('exhibition_id', '=', $exhibition_id)->where("project_status","=",1)->count_all();
    }
    
    /**
     * 获取红包数量
     */
    public function get_hongbao_num($exhibition_id){
    	return ORM::factory("ExhbHongBao")->where('exhibition_id', '=', intval($exhibition_id))->count_all();
    }
    /**
     * 获取参展的人数
     * @author jiye
     */
    public function  get_Exhibition_people($exhibition_id){ 
    	return ORM::factory("ExhbCoupon")->where('exhibition_id', '=', intval($exhibition_id))->count_all();
    }
    /**
     * 获取数据
     * @author jiye
     */
    public  function getList($exhibition_id,$int_type,$int_page){
    	$arr_return_data = array();
    	$int_page = intval($int_page);
    	$model = ORM::factory("ExhbProject");
    	if($int_type == 0){
    		//直接调取数据
    		$count = $model->where("exhibition_id","=",intval($exhibition_id))->where("project_status","=",intval(1))->count_all();
    	}else{
    		//连表查询 (可是没有表)
    		//在线沟通总量
    		if($int_type == 1){
    			$count = $model->where("exhibition_id","=",intval($exhibition_id))->where("project_status","=",intval(1))->count_all();
    		}elseif($int_type == 2){
    			//意向加盟(名片数量)
    			$count = $model->where("exhibition_id","=",intval($exhibition_id))->where("project_status","=",intval(1))->count_all();
    		}
    	}
    	$arr_return_data['count'] = $count;
    	if($int_page == 1 || $int_page == 0){
    		$num = 0;
    	}else{
    		$num = ($int_page*60) - 60;
    	}
    	$arr_return_data['offset_num'] =  $num;
    	$page = Pagination::factory ( array (
    			'total_items' => $count,
    			'items_per_page' => 60,
    			'current_page' => array('source' => 'waterfallorder', 'key' => 'page')
    	) );
    	$arr_return_data['page'] = $page;
    	if($int_type == 0){
    		$arr_return_data['list'] = $model->where("exhibition_id","=",intval($exhibition_id))->where("project_status","=",intval(1))->limit( 20 )->offset ($num)->order_by("project_passtime","DESC")->find_all();
    	}else{
	    	////在线沟通总量
	    	if($int_type == 1){
	    	  $arr_return_data['list'] = $model->where("exhibition_id","=",intval($exhibition_id))->where("project_status","=",intval(1))->limit(20)->offset ($num)->order_by("project_id","DESC")->find_all();
	    	}elseif($int_type == 2){
	    	 //意向加盟(名片数量)
	    		$arr_return_data['list'] = $model->where("exhibition_id","=",intval($exhibition_id))->where("project_status","=",intval(1))->limit(20)->offset ($num)->order_by("project_id","asc")->find_all();
	    	}
    	}
    	return $arr_return_data;
    }
    
    
    /**
     * 展会子类
     * @author jiye
     */
    public function getExhbProjectByCatalogId($catalog_id,$int_type,$int_page){
    	$arr_return_data = array();
    	$int_page = intval($int_page);
    	$model = ORM::factory("ExhbProject");
    	if($int_type == 0){
    		$count = $model->where("catalog_id","=",intval($catalog_id))->where("project_status","=",intval(1))->count_all();	
    	}else{
    		//连表查询 (可是没有表)
    		//在线沟通总量
    		if($int_type == 1){
    			$count = $model->where("catalog_id","=",intval($catalog_id))->where("project_status","=",intval(1))->count_all();
    		}elseif($int_type == 2){
    			//意向加盟(名片数量)
    			$count = $model->where("catalog_id","=",intval($catalog_id))->where("project_status","=",intval(1))->count_all();
    		}
    	}
    	$arr_return_data['count'] = $count;
    	if($int_page == 1 || $int_page == 0){
    		$num = 0;
    	}else{
    		$num = ($int_page*60) - 60;
    	}
    	$arr_return_data['offset_num'] =  $num;
    	$page = Pagination::factory ( array (
    			'total_items' => $count,
    			'items_per_page' => 60,
    			'current_page' => array('source' => 'waterfallorder', 'key' => 'page')
    	) );
    	$arr_return_data ['page'] = $page;
    	if($int_type == 0){
    		$arr_return_data['list'] = $model->where("catalog_id","=",intval($catalog_id))->where("project_status","=",intval(1))->limit( 20 )->offset ($num)->order_by("project_passtime","DESC")->find_all();
    	}elseif($int_type == 1){
    		//在线沟通 
    		$arr_return_data['list'] = $model->where("catalog_id","=",intval($catalog_id))->where("project_status","=",intval(1))->limit( 20 )->offset ($num)->order_by("project_communication_count","DESC")->find_all();
    	}elseif($int_type == 2){
    		//名片
    		$arr_return_data['list'] = $model->where("catalog_id","=",intval($catalog_id))->where("project_status","=",intval(1))->limit( 20 )->offset ($num)->order_by("project_cart_cout","DESC")->find_all();
    	}
    	return $arr_return_data;
    }
    
    /**
     * 处理数据
     * @author jiye
     */
    public function do_list($data){
    	$return = array();
    	if($data){
    		foreach ($data as $key=>$val){
    			$arr_data['project_id'] = $val->project_id;
    			$arr_data['outside_id'] = $val->outside_id;
    			$arr_data['project_source'] = $val->project_source;
    			$arr_data['project_brand_name'] = mb_substr($val->project_brand_name, 0,15,"UTF-8")."";
    			$arr_data['catalog_id'] = $val->catalog_id;
    			$arr_data['exhibition_id'] = $val->exhibition_id;
    			$arr_data['com_id'] = $val->com_id;
    			$arr_data['project_logo'] = URL::imgurl($val->project_logo);
    			$arr_data['advertisement'] = mb_substr($val->advertisement, 0,15,"UTF-8")."";
    			$arr_data['project_advantage'] = $val->project_advantage;
    			$arr_data['project_introduction'] =mb_substr($val->project_introduction, 0,50,"UTF-8")."";
    			$arr_data['inquiries'] = rand(20,100);
    			$return [] = $arr_data;
    		}
    	}
    	return $return;
    }
    
    /**
     * @author stone shi
     * 获得最火项目
     */
    
    /**
     * 获得展会全部项目总数
     * @author stone shi
     */
    public function getProjectCount($exhibition_id) {
        $exhibition_id = intval($exhibition_id);
        if (!$exhibition_id)
            return 0;
        $Count = ORM::factory("ExhbProject")->where('project_status', '=', 1)->where('exhibition_id', '=', $exhibition_id)->count_all();
        return $Count;
    }
    
    /**
     * 获取瀑布流数据
     * @author jiye
     */
    public function getWaterfallList($data){
    	$int_exhibition_id = arr::get($data,"exhibition_id");
    	$int_catalog_id = arr::get($data,"catalog_id");
    	$int_offset = intval(arr::get($data, "offset_num")+20);
    	$int_type = arr::get($data,"type","");
    	$model = ORM::factory("ExhbProject");
    	$data_return = array();
    	$data_return['offset_num'] =$int_offset;
    	if($int_exhibition_id){
    		//展会
    		if(empty($int_type)){
    			$count = $model->where("exhibition_id","=",intval($int_exhibition_id))->where("project_status","=",intval(1))->count_all();
    		}else{
    			//在线沟通总量 
    			if($int_type == 1){
    				$count = $model->where("exhibition_id","=",intval($int_exhibition_id))->where("project_status","=",intval(1))->count_all();
    			}elseif($int_type == 2){
    				//意向加盟(名片数量)
    				$count = $model->where("exhibition_id","=",intval($int_exhibition_id))->where("project_status","=",intval(1))->count_all();
    			}
    		}
    	}
    	if($int_catalog_id){  
    		if(empty($int_type)){
    			//类型
    			$count = $model->where("catalog_id","=",intval($int_catalog_id))->where("project_status","=",intval(1))->count_all();
    		}else{
    			//在线沟通总量 
    			if($int_type == 1){
    				$count = $model->where("catalog_id","=",intval($int_catalog_id))->where("project_status","=",intval(1))->count_all();
    			}elseif ($int_type == 2){
    			 //意向加盟(名片数量)
    				$count = $model->where("catalog_id","=",intval($int_catalog_id))->where("project_status","=",intval(1))->count_all();
    			}
    		}
    		
    	}
    	$data_return['count'] = $count;
    	if($int_type){
    		$current_page = array('source' => 'waterfallorder', 'key' => 'page');
    	}else{
    		$current_page = array('source' => 'waterfall', 'key' => 'page');
    	}
    	$page = Pagination::factory ( array (
    			'total_items' => $count,
    			'items_per_page' => 60,
    			 'current_page' => $current_page
    	) );
    	$data_return['page'] =$page;
    	if($int_exhibition_id){
    		//展会 
    		if(empty($int_type)){
    			//默认数据
    			$obj_data  = $model->where("exhibition_id","=",intval($int_exhibition_id))->where("project_status","=",intval(1))->limit(20)->offset ($int_offset)->order_by("project_passtime","DESC")->find_all();
    		}elseif ($int_type == 1){ 
    			//在线沟通总量
    			$obj_data  = $model->where("exhibition_id","=",intval($int_exhibition_id))->where("project_status","=",intval(1))->limit(20)->offset ($int_offset)->order_by("project_communication_count","DESC")->find_all();
    		}elseif ($int_type == 2){
    			//意向加盟(名片数量)
    			$obj_data  = $model->where("exhibition_id","=",intval($int_exhibition_id))->where("project_status","=",intval(1))->limit(20)->offset ($int_offset)->order_by("project_cart_cout","DESC")->find_all();
    		}
    		$data_return['list'] = $this->do_list($obj_data);
    	}
    	if($int_catalog_id){
    		if(empty($int_type)){
    			//展会
    			$obj_data  = $model->where("catalog_id","=",intval($int_catalog_id))->where("project_status","=",intval(1))->limit(20)->offset ($int_offset)->order_by("project_passtime","DESC")->find_all();
    		}elseif ($int_type == 1){
    			//在线沟通总量
    			$obj_data  = $model->where("catalog_id","=",intval($int_catalog_id))->where("project_status","=",intval(1))->limit(20)->offset ($int_offset)->order_by("project_communication_count","asc")->find_all();
    		}elseif ($int_type == 2){
    			//意向加盟(名片数量)
    			$obj_data  = $model->where("catalog_id","=",intval($int_catalog_id))->where("project_status","=",intval(1))->limit(20)->offset ($int_offset)->order_by("project_cart_cout","DESC")->find_all();
    		}
    		
    		$data_return['list'] = $this->do_list($obj_data);
    	}
    	return $data_return;
    }
        /**
     * 获得参展人数
     */
    public function getExhbPerNum($exhb_id) {
        $exhb_id = intval($exhb_id);
        if(!$exhb_id) return 0;
        $return = 0;
        $ser = new Service_Platform_ExhbProject();
        $hbInfo = $ser->getHongBaoInfo($exhb_id);
        $proCount = $this->getProjectCount($exhb_id);
        $hbCount = arr::get($hbInfo, 'hongbao_num', 0)-arr::get($hbInfo, 'shengyu', 0);
        $allCount = DB::select(array(DB::expr('sum(project_communication_count)'),'project_communication_count'), array(DB::expr('sum(project_cart_cout)'),'project_cart_cout'))->from('exhb_project')->where('exhibition_id', '=', $exhb_id)->where('project_status', '=', 1)->execute()->as_array();
        $return = $proCount+$hbCount+arr::get($allCount, 'project_communication_count', 0)+arr::get($allCount, 'project_cart_cout', 0);
        return $return;
    }
    
    /**
     * 获得签约什么的数据
     * @author 我真的不想写啊啊啊啊！！！
     */
    public function getExhbSigningCount($exhb_id, $exhbPerNum) {
        $exhb_id = intval($exhb_id);
        if(!$exhb_id) return 0;
        $proCount = $this->getProjectCount($exhb_id);
        $exhbPerNum = intval($exhbPerNum);
        $service = new Service_Platform_ExhbProject();
        $sucProjectNum = $service->getSucProjectNum($exhb_id);
        $sucProjectPeople = $service->getSucPeopleNum($exhb_id);
        $signatureRate = $proCount ? intval(($sucProjectNum/$proCount)*100) : 0;
        $return = array('sucProjectNum' => $sucProjectNum, 'sucProjectPeople' => $sucProjectPeople, 'signatureRate' => $signatureRate);
        return $return;
    }
    /**
     * 判断是不是有客服id
     * @author操蛋
     * 
     */
    public function isHavaGroupId($int_com_id){
    	$bool = false;
    	if($int_com_id){
    		$data = ORM::factory("Customer")->where("com_id","=",intval($int_com_id))->find()->as_array();
    		if(arr::get($data,"customer_status",0) == 2 && arr::get($data, "customer_group_id","") !=""){
    			$bool = true;
    		}
    	}
    	return $bool;
    }
}



?>
