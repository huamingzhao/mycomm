<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 项目向导
 * @author 施磊
 *
 */
class Service_Platform_ProjectGuide {
    private $set_time = 7200;
    /**
     * 最佳口碑项目排名
     * @author 郁政
     * @param  $top 排名前X个,$time 多少天排行
     */
    public function getApproingRanking($top, $time = 30 ,$type = 1) {  
        $approing = array();        
        $date_start = time() - $time * 86400;
        $date_end = time();    
        $approing = array(); 
        if($type == 1){
	        try {
	            $approing = DB::select('project_id',array(DB::expr('COUNT(project_id)'),'amount'))->from("user_approing_logs")->where('project_id','<>',0)->where('log_time','>',$date_start)->where('log_time','<',$date_end)->order_by('amount', 'desc')->group_by('project_id')->limit($top)->offset(0)->execute()->as_array();
	        } catch (Exception $e) {
	            return false;
	        }
        }elseif($type == 3){
        	$approing = DB::select('user_approing_logs.project_id',array(DB::expr('COUNT(czzs_user_approing_logs.project_id)'),'amount'))
		        	->from("user_approing_logs")
		        	->join('project','left')
		        	->on('user_approing_logs.project_id','=','project.project_id')
		        	->where('project.project_real_order','<>',intval(5))
		        	->where('project.project_real_order','<>',intval(6))
		        	->where('project.project_status','=',intval(2))
		        	->where('user_approing_logs.project_id','<>',0)
		        	->where('user_approing_logs.log_time','>',$date_start)
		        	->where('user_approing_logs.log_time','<',$date_end)
		        	->group_by('user_approing_logs.project_id')
		        	->order_by('amount', 'desc')
		        	->limit($top)
		        	->execute()
		        	->as_array();
        }        
        return $approing;
    }

    /**
     * 获取项目排名需要的数据
     * @author 郁政
     * @param  $list 项目排名列表
     */
    public function showNeedRanking($list) {
    	$res = array();
        if (!empty($list) && $list) {
            foreach ($list as $k => $v) {            	
                $project_id = intval($v['project_id']);
                $project = ORM::factory("Project")->where('project_id', '=', $project_id)->find()->as_array();
                if (isset($project['project_id']) && $project['project_id']) {
                	$res[$k]['project_id'] = $project['project_id'];
                    $res[$k]['project_brand_name'] = $project['project_brand_name'];
                    $res[$k]['project_logo'] = $project['project_logo'];
                    $res[$k]['product_features'] = $project['product_features'];
                    $res[$k]['project_summary'] = $project['project_summary'];
                    $res[$k]['project_source'] = $project['project_source'];
                    $res[$k]['project_amount_type'] = $project['project_amount_type'];
                    $res[$k]['outside_id'] = $project['outside_id'];
                    $res[$k]['project_vip_set'] = $project['project_vip_set'];
                    $res[$k]['project_advert'] = $project['project_advert'];
                    $res[$k]['project_pv_count'] = $project['project_pv_count'];
                    $res[$k]['amount'] = isset($v['amount']) ? $v['amount'] : "";
                    $res[$k]['project_real_order'] = isset($v['project_real_order']) ? $v['project_real_order'] : 0;
                }
            }
        }
        $res = array_merge($res,array());
        //echo "<pre>";print_r($res);exit;
        return $res;
    }
	
	
	  public function showNeedRanking111($list) {
	  $arr_data = array();
        if (!empty($list) && $list) {
		$num = 0;
            foreach ($list as $k => $v) {
                $project_id = intval($v['project_id']);
                $project = ORM::factory("Project")->where('project_id', '=', $project_id)->find()->as_array();
                if (!empty($project) && $project['project_id']) {
				//if(count($arr_data) > 10){break;}
					$arr_data[$num]['project_id'] = $project['project_id'];
                    $arr_dataa[$num]['project_brand_name'] = $project['project_brand_name'];
                    $arr_data[$num]['project_logo'] = $project['project_logo'];
                    $arr_data[$num]['product_features'] = $project['product_features'];
                    $arr_data[$num]['project_summary'] = $project['project_summary'];
                    $arr_data[$num]['project_source'] = $project['project_source'];
                    $arr_data[$num]['project_amount_type'] = $project['project_amount_type'];
                    $arr_data[$num]['outside_id'] = $project['outside_id'];
                    $arr_data[$num]['project_vip_set'] = $project['project_vip_set'];
                    $arr_data[$num]['project_advert'] = $project['project_advert'];
                    $arr_data[$num]['project_pv_count'] = $project['project_pv_count'];
                }
            }
        }
        return $arr_data;
    }
 /**
     * 获得最受关注项目排名
     * @author 施磊
     */
    public function getStatisticsAll($top = 5, $num = 5,$type = 1) {
        $memcache = Cache::instance ( 'memcache' );
        $xuanchuanModel = new Service_Platform_Search();
        $top = intval($top);
        $statistics = array();
        $projects = array();
        $arr = array();
        $arr_statistics_list = array();
        $arr_statistics_list = $memcache->get("getStatisticsAll");
        if(empty($arr_statistics_list)){
            $mod = new Service_Platform_Search();
            try {
            	#获取招商外包项目
            	$model = ORM::factory("Project")->where("project_status","=",2)->where("project_real_order",'=',1)->limit(80)->find_all()->as_array();
            	 foreach ($model as $key=>$val){
            	 	$arr_statistics_list[] =  array('project_id'=>$val->project_id,"project_brand_name"=>$val->project_brand_name,"project_logo"=>$val->project_logo,"project_source"=>$val->project_real_order,"outside_id"=>$val->outside_id);
            	 }
            	 //随机取数据数据
            	 if(count($arr_statistics_list) >= $top){
            	 	$arr_statistics_list = $this->getRandArray($arr_statistics_list,$top);
            	 }else{
            	 	$arr_statistics_list = $this->getRandArray($arr_statistics_list,count($arr_statistics_list));
            	 }
            	 //获取临时文件
            	 $arr_project_id = $this->getImpAdProByConfig($num);
            	 $project_model = ORM::factory("Project")->where("project_status","=",2)->where("project_id","in",$arr_project_id)->find_all();
            	 foreach ($project_model as $key=>$val){
            	 	$arr_statistics_list[] =  array('project_id'=>$val->project_id,"project_brand_name"=>$val->project_brand_name,"project_logo"=>$val->project_logo,"project_source"=>$val->project_real_order,"outside_id"=>$val->outside_id);
            	 }
            	 //判断是不是满足了18条数据
            	 if(count($arr_statistics_list) < 18){
            	 	$top = 18 - count($arr_statistics_list);
            	 	$project_models = ORM::factory("Project")->where("project_status","=",2)->where("project_real_order",'=',1)->limit($top)->find_all()->as_array();
            	 	foreach ($project_models as $key=>$val){
            	 		$arr_statistics_list[] = array('project_id'=>$val->project_id,"project_brand_name"=>$val->project_brand_name,"project_logo"=>$val->project_logo,"project_source"=>$val->project_source,"outside_id"=>$val->outside_id);
            	 	}
            	 } 
            } catch ( Exception $e ) {
               return array();
            }
            if($arr_statistics_list) {            
	            foreach ($arr_statistics_list as $key => $val){
	            	$xuanchuanimage = $xuanchuanModel->getProjectXuanChuanImage($val['project_id'],intval(5));
	            	if($xuanchuanimage){ $arr_statistics_list[$key]['project_logo'] = $xuanchuanimage;}
	            	$statistics [] = $val;
	            }
            }
            if($type==1){
                $arr_statistics_list = $mod->pushProjectInfo($statistics);
            }
            $memcache->set("getStatisticsAll", $statistics,86400);
        }
        
        return $arr_statistics_list;
    }
    /**
     * 最近入驻好项目 (二逼需求)
     * @author 嵇烨
     */
    public function getNewProjectList($wai_num = 5,$zhong_num = 5, $pu_num = 5,$type = 1) {
        $memcache = Cache::instance ( 'memcache' );
        $xuanchuanModel = new Service_Platform_Search();
        $arr_project_list = array();
        $projectList = array();
        $arr_project_list = $memcache->get("getNewProjectList");
        if($arr_project_list){
            return $arr_project_list;die;
        }
        $mod = new Service_Platform_Search();
		$model = ORM::factory("Project");
		try {
			//获取招商外包项目
			$arr_zhao = $model->where("project_real_order","=",1)->where("project_status","=",2)->limit($wai_num)->order_by("project_passtime","DESC")->find_all();
			foreach ($arr_zhao as $key=>$val){
				$arr_project_list[] =array('project_id'=>$val->project_id,"project_brand_name"=>$val->project_brand_name,"project_logo"=>$val->project_logo,"project_source"=>$val->project_real_order,"outside_id"=>$val->outside_id);
			}
			//获取重点广告项
			$arr_zhong_id = $this->getImpAdProByConfig($zhong_num);
			//获取重点广告项信息
			$arr_zhong = $model->where("project_id","in",$arr_zhong_id)->where('project_status',"=",2)->order_by("project_passtime","DESC")->find_all();
			foreach ($arr_zhong as $key=>$val){
				$arr_project_list[] =array('project_id'=>$val->project_id,"project_brand_name"=>$val->project_brand_name,"project_logo"=>$val->project_logo,"project_source"=>$val->project_real_order,"outside_id"=>$val->outside_id);
			}
			//获取企业发布的项目
			$arr_pu = $model->where('project_real_order',"=",4)->where('project_status',"=",2)->limit($pu_num)->order_by("project_passtime","DESC")->find_all();
			foreach ($arr_pu as $key=>$val){
				$arr_project_list[] = array('project_id'=>$val->project_id,"project_brand_name"=>$val->project_brand_name,"project_logo"=>$val->project_logo,"project_source"=>$val->project_real_order,"outside_id"=>$val->outside_id);
			}
			//判断是福满足18条数据
			if(count($arr_project_list) < 18){
				$num = 18 - count($arr_project_list);
				$list = $model->where('project_real_order',"=",2)->where('project_status',"=",2)->limit($num)->order_by("project_passtime","DESC")->find_all();
				foreach ($list as $key=>$val){
					$arr_project_list[] = array('project_id'=>$val->project_id,"project_brand_name"=>$val->project_brand_name,"project_logo"=>$val->project_logo,"project_source"=>$val->project_real_order,"outside_id"=>$val->outside_id);
				}
			}
		}catch (ErrorException $e){
			return array();
		}
        foreach($arr_project_list as $key=>$val) {
        	$xuanchuanimage = $xuanchuanModel->getProjectXuanChuanImage($val['project_id'],intval(5));
        	if($xuanchuanimage){ $arr_project_list[$key]['project_logo'] = $xuanchuanimage;}
        }
        if($type==1){
            $arr_project_list = $mod->pushProjectInfo($arr_project_list);
        }
       $memcache->set("getNewProjectList",$arr_project_list,86400);
        return $arr_project_list;
    }
    /**
     * 最受关注项目排名
     * @author 郁政
     * @param  $top 排名前X个,$time 多少天排行
     */
    public function getWatchRanking($top, $time = 30,$type = 1) {        
        $date_start = time() - $time * 86400;
        $date_end = time();
        $watch = array();
        if($type == 1){
	        try {
	             $watch = DB::select(array('watch_project_id', 'project_id'),array(DB::expr('COUNT(watch_project_id)'),'amount'))->from("project_watch")->where('watch_status','=',1)->where('watch_update_time','>',$date_start)->where('watch_update_time','<',$date_end)->group_by('watch_project_id')->order_by('amount', 'desc')->limit($top)->offset(0)->execute()->as_array();
	        } catch (Exception $e) {
	            return array();
	        }
        }elseif($type == 3){
        	$watch = DB::select(array('project_watch.watch_project_id', 'project_id'),array(DB::expr('COUNT(czzs_project_watch.watch_project_id)'),'amount'))
        	->from("project_watch")
        	->join('project','left')
        	->on('project_watch.watch_project_id','=','project.project_id')
        	->where('project.project_real_order','<>',intval(5))
        	->where('project.project_real_order','<>',intval(6))
        	->where('project.project_status','=',intval(2))
        	->where('project_watch.watch_status','=',1)
        	->where('project_watch.watch_update_time','>',$date_start)
        	->where('project_watch.watch_update_time','<',$date_end)
        	->group_by('project_watch.watch_project_id')
        	->order_by('amount', 'desc') 
        	->limit($top)
        	->execute()
        	->as_array();
        }        
        return $watch;
    }


    /**
     * 最热项目排名
     * @author 郁政
     * @param  $top 排名前X个,$time 多少天排行
     */
    public function getClickRanking($top, $time = 30 , $type = 0){
    	$memcache = Cache::instance('memcache');
    	$cacheKey = "getClickRanking_".$top.'_'.$time.'_'.$type;
    	$statistics = $memcache->get($cacheKey);
    	if($statistics) return $statistics;
    	$statistics = array();
    	$date_start = time() - $time * 86400;
        $date_end = time();
    	if($type == 1){    		
    		$statistics = DB::select('project.project_id',array(DB::expr('COUNT(czzs_project_statistics.project_id)'),'amount'))
    		->from('project')
    		->join('project_statistics','left')
    		->on('project.project_id','=','project_statistics.project_id')
    		->where('project.project_status','=',2)
    		->where('project.project_source','=',2)
    		->where('project_statistics.insert_time','>',$date_start)
    		->where('project_statistics.insert_time','<',$date_end)
    		->group_by('project_statistics.project_id')
    		->order_by('amount', 'desc')
    		->limit($top)
    		->execute()
    		->as_array();
    	}elseif($type == 3){
    		$statistics = DB::select('project.project_id',array(DB::expr('COUNT(czzs_project_statistics.project_id)'),'amount'))
    		->from('project')
    		->join('project_statistics','left')
    		->on('project.project_id','=','project_statistics.project_id')
    		->where('project.project_status','=',2)
    		->where('project.project_real_order','<>',intval(5))
        	->where('project.project_real_order','<>',intval(6))
    		->where('project_statistics.insert_time','>',$date_start)
    		->where('project_statistics.insert_time','<',$date_end)
    		->group_by('project_statistics.project_id')
    		->order_by('amount', 'desc')
    		->limit($top)
    		->execute()
    		->as_array();
    	}else{
    		$statistics = DB::select('project.project_id',array(DB::expr('COUNT(czzs_project_statistics.project_id)'),'amount'))
    		->from('project')
    		->join('project_statistics','left')
    		->on('project.project_id','=','project_statistics.project_id')
    		->where('project.project_status','=',2)    		
    		->where('project_statistics.insert_time','>',$date_start)
    		->where('project_statistics.insert_time','<',$date_end)
    		->group_by('project_statistics.project_id')
    		->order_by('amount', 'desc')
    		->limit($top)
    		->execute()
    		->as_array();
    	}  
        $memcache->set($cacheKey, $statistics, 7200);
        return $statistics;
    }


    /**
     * 通过人群找项目
     * @author 嵇烨
     * @param  人群的id ,$num  显示的条数（默认是5条）$user_id
     */
    public function getProjectListByCrowdId($tag_id, $num = 10) {
        $service = new Service_Platform_Project();
        $array = array();
        $project_id = array();
        $model = ORM::factory("Projectcrowd")->where("tag_id", "=", $tag_id)->find_all();
        $project_id = array();
        foreach ($model as $key => $val) {
            $project_id[] = $val->project_id;
        }	
        if(empty($project_id)){
            return false;
        }
        $project_Models = ORM::factory("Project");
            $count = $project_Models->where("project_id", "in", array_unique($project_id))->where("project_status", "=", 2)->count_all();
            $page = Pagination::factory(array(
            		'total_items' => $count,
            		'items_per_page' => $num,
            		'view' => 'pagination/Simple',
            		'current_page' => array('source' => 'people', 'key' => 'page')
            ));
            $list = $project_Models->where("project_id", "in", array_unique($project_id))->where("project_status", "=", 2)->limit($page->items_per_page)->offset($page->offset)->order_by('project_real_order', 'ASC')->find_all();
            $tempList = array();
            $i=0;
            foreach ($list as $val) {$i++;
            	if($i > $num){break;}
                $tempList[] = $val->as_array();
            }
            $search = new Service_Platform_Search();
            $array['list'] = $search->pushProjectInfo($tempList);
            $array['page'] = $page;
        return $array;
    }

    /**
     * 根据id获得project数据
     * @author 施磊
     */
    public function getProjectById($project_id = array(), $user_id = 0) {

        return $return;
    }

    /**
     * 最新项目排名
     * @author 郁政
     * @param  $top 排名前X个
     */
    public function getNewProjectRanking($top){
        $list = array();
        try {
            $newproject = ORM::factory("Project")->where('project_status','=',2)->order_by('project_addtime','desc')->limit($top)->find_all();
            if (!empty($newproject)) {
                foreach ($newproject as $v){
                    $list[]['project_id'] = $v->project_id;
                }
            }
        }catch (Exception $e){
            return false;
        }
        return $list;
    }
    /**
     * 通过地区找项目
     * @author 嵇烨
     * @param $area_id(地区id) $nums(要显示的条数)
     */
    public function getProjectListByCityId($area_id,$nums = 5,$user_id = 0,$showNum = 0){
        $service = new Service_Platform_Project();
        $array = array('list' => array());
        $project_id = array();
        //获取省级名称
        $str_provincName = ORM::factory("City")->where("cit_id","=",intval($area_id))->find()->cit_name;
      	$str_provincName = $str_provincName ? $str_provincName :"全国";
        //获取数据
        if($str_provincName == '全国'){
        	$count = ORM::factory("Project")->where("project_status", "=", 2)->count_all();
        }else{
        	$count = ORM::factory("Project")->where("project_status", "=", 2)->where("project_brand_birthplace","like",$str_provincName."%")->count_all();
        }
        $page = Pagination::factory(array(
        		'total_items' => $count,
        		'items_per_page' => $nums,
        		'view' => 'pagination/Simple',
        		'current_page' => array('source' => 'diqu', 'key' => 'page')
        ));
        if($str_provincName == '全国'){
        	$list = ORM::factory("Project")
        			->where("project_status", "=", 2)
        			->limit($page->items_per_page)
        			->offset($page->offset)
        			->order_by('project_real_source', 'ASC')
        			->find_all();
        }else{
        	$list = ORM::factory("Project")
        			->where("project_status", "=", 2)
        			->where("project_brand_birthplace","like",$str_provincName."%")
        			->order_by('project_real_source', 'ASC')
        			->limit($page->items_per_page)
        			->offset($page->offset)
        			->order_by('project_real_source', 'ASC')
        			->find_all();
        }
        $tempList = array();
        foreach ($list as $val) {
        	$tempList[] = $val->as_array();
        }
        $search = new Service_Platform_Search();
        $array ['list'] = $search->pushProjectInfo($tempList,$user_id);
        $array ['page'] = $page;
        $array['total_count'] = $count;
      	
      	
      	
      	
      	/*
        if($area_id <= 35){
            $model = ORM::factory("Projectarea")->where("pro_id", "=", intval($area_id))->find_all();
        }elseif ($area_id == 88){
            $model = ORM::factory("Projectarea")->where("area_id", "=", intval($area_id))->find_all();
        }else{
            $model = ORM::factory("Projectarea")->where("area_id", "=", intval($area_id))->find_all();
        }
        $project_id[1] = array();
        foreach ($model as $key => $val) {
            $project_id[1][] = $val->project_id;
        }
        $project_id = $service->getArrayIntersectProject($project_id);
        #如果没有返回 false
        if(empty($project_id)){
            return  false;
        }
        #推荐全国前十条
        if($showNum == 1){
            $num = 0;
            foreach ($project_id as $key=>$val){
                if($num > 10){
                    continue;
                }else{
                    $project_ids[] = $val;
                } $num++;
            }
            $project_id = $project_ids;
        }
        $project_Models = ORM::factory("Project");
        $projectList = $project_Models->where("project_id", "in", $project_id)->where("project_status", "=", 2)->count_all();
        $count = 0;
        if ($project_id) {
            $count = $project_Models->where("project_id", "in", array_unique($project_id))->where("project_status", "=", 2)->count_all();
        }
        if ($count > 0) {
            $page = Pagination::factory(array(
                    'total_items' => $count,
                    'items_per_page' => $nums,
            		'view' => 'pagination/Simple',
            		'current_page' => array('source' => 'diqu', 'key' => 'page')
            ));
            $list = $project_Models->where("project_id", "in", array_unique($project_id))->where("project_status", "=", 2)->limit($page->items_per_page)->offset($page->offset)->order_by('project_updatetime', 'desc')->find_all();
            $tempList = array();
            foreach ($list as $val) {
                $tempList[] = $val->as_array();
            }
            $search = new Service_Platform_Search();
            $array ['list'] = $search->pushProjectInfo($tempList,$user_id);
            $array ['page'] = $page;
        }
        $array['total_count'] = $count;
        */
        return $array;
    }

    /**
     * 返回总数排行
     * @author 郁政
     */
    public function getMultipleList($top,$rankingtype,$daytype){
        $multipletype = "";
        $day = 0;
        $list = array();
        if($daytype == 1){
            $multipletype = "multiple_30";
            $day = 30;
        }elseif($daytype == 2){
            $multipletype = "multiple_7";
            $day = 7;
        }
        if($rankingtype == 1){
            $multiplelist = DB::select('user_approing_logs.project_id',array(DB::expr('COUNT(czzs_user_approing_logs.project_id)*'.$multipletype),'amount'))
                            ->from('user_approing_logs')
                            ->join('project_ranking_list')
                            ->on('user_approing_logs.project_id','=','project_ranking_list.project_id')
                            ->join('project','left')
				        	->on('user_approing_logs.project_id','=','project.project_id')
				        	->where('project.project_real_order','<>',intval(5))
				        	->where('project.project_real_order','<>',intval(6))
                            ->where('log_time','>',time()-($day*86400))
                            ->where('log_time','<',time())
                            ->where('type','=',$rankingtype)
                            ->group_by('user_approing_logs.project_id')
                            ->order_by('amount','desc')
                            ->limit($top)
                            ->offset(0)
                            ->execute()
                            ->as_array();
            $list = $multiplelist;
        }elseif($rankingtype == 2){
            $multiplelist = DB::select(array('project_watch.watch_project_id','project_id'),array(DB::expr('COUNT(czzs_project_watch.watch_project_id)*'.$multipletype),'amount'))
                            ->from('project_watch')
                            ->join('project_ranking_list')
                            ->on('project_watch.watch_project_id','=','project_ranking_list.project_id')
                            ->join('project','left')
				        	->on('project_watch.watch_project_id','=','project.project_id')
				        	->where('project.project_real_order','<>',intval(5))
				        	->where('project.project_real_order','<>',intval(6))
                            ->where('watch_update_time','>',time()-($day*86400))
                            ->where('watch_update_time','<',time())
                            ->where('type','=',$rankingtype)
                            ->where('watch_status','=',1)
                            ->group_by('project_watch.watch_project_id')
                            ->order_by('amount','desc')
                            ->limit($top)
                            ->offset(0)
                            ->execute()
                            ->as_array();
            $list = $multiplelist;
        }elseif($rankingtype == 3){
            $multiplelist = DB::select('project_statistics.project_id',array(DB::expr('COUNT(czzs_project_statistics.project_id)*'.$multipletype),'amount'))
                            ->from('project_statistics')
                            ->join('project_ranking_list')
                            ->on('project_statistics.project_id','=','project_ranking_list.project_id')
                            ->join('project','left')
				        	->on('project_statistics.project_id','=','project.project_id')
				        	->where('project.project_real_order','<>',intval(5))
				        	->where('project.project_real_order','<>',intval(6))
                            ->where('insert_time','>',time()-($day*86400))
                            ->where('insert_time','<',time())
                            ->where('type','=',$rankingtype)
                            ->group_by('project_statistics.project_id')
                            ->order_by('amount','desc')
                            ->limit($top)
                            ->offset(0)
                            ->execute()
                            ->as_array();
            $list = $multiplelist;
        }
        return $list;
    }

    /**
     * 获得最新项目排名列表信息
     * @author 郁政
     */
    public function getNewProList($top){
        $list1 = array();
        $list2 = array();
        $list3 = array();
        $list = array();
        $lock = array();
        $newlist = ORM::factory('ProjectNewList')->where('project_id','<>',0)->find_all();
        $newproject = ORM::factory('Project')
		        ->where('project_status','=',2)
		        ->where('project.project_real_order','<>',intval(5))
        		->where('project.project_real_order','<>',intval(6))
		        ->order_by('project_addtime','desc')
		        ->limit($top)
		        ->find_all();
        if(!empty($newlist)){
            foreach($newlist as $v){
                $list1[$v->id-1] = $v->project_id;
                $lock[] = $v->id;
            }
            $list['lock'] = $lock;
            foreach($newproject as $k => $v){
                $list2[$k] = $v->project_id;
            }
            foreach($list2 as $k => $v){
                if(in_array($v, $list1)){
                    unset($list2[$k]);
                }
            }
            $list2 = array_values($list2);
            foreach($list1 as $k => $v){
                array_splice($list2, $k,0,$v);
            }
        }else{
            $list2 = $newproject;
        }
        if(!empty($list2)){
            $list3 = array_slice($list2, 0,$top);
            foreach($list3 as $k => $v){
                $project = ORM::factory('Project')->where('project_id','=',$v)->find();
                $list['data'][$k]['project_id'] = $project->project_id;
                $list['data'][$k]['project_brand_name'] = $project->project_brand_name;
                $list['data'][$k]['product_features'] = $project->product_features;
                $list['data'][$k]['project_addtime'] = date('Y-m-d',$project->project_addtime);
            }
        }
        return $list;
    }



    /**
     * 获取推荐项目
     *
     * @author 嵇烨
     */
    public function getProjectRecommend($tag_id) {
        $arr_projectInfo = array ();
        if ($tag_id) {
            $project_model = new Service_User_Company_Project ();
            $project_id = array ();
            $model = ORM::factory ( "Projectrecommend" )->where ( "multitude_type", "=", $tag_id )->order_by ( "order_nums", "DESC" )->find_all ();
            foreach ( $model as $key => $val ) {
                $projectlist = $project_model->getProjectData ( $val->project_id );
                $project_id ['project_id'] = $projectlist->project_id;
                $project_id ['project_brand_name'] = $projectlist->project_brand_name;
                $project_id ['product_features'] = $val->project_introduction?$val->project_introduction:$projectlist->product_features;
                $arr_projectInfo [$val->order_nums] = $project_id;
            }
            $nums = count ( $arr_projectInfo );
            $array = array (
                    'list' => array ()
            );
            // 项目小于5的时候要追加 pv总值的前几个项目
            if ($nums < 5) {
                $arr_newprojectInfo = $this->getProjectListByCrowdId ( $tag_id, 5 );
                // 理数组的key重新排序
                $arr_projecList = array ();
                $num = 5;
                foreach ( $arr_newprojectInfo ['list'] as $key => $val ) {
                    $arr_newprojecList [$num --] = $val;
                    $arr_projecList = $arr_newprojecList;
                }
                // 理数组替换
                foreach ( $arr_projecList as $key => $val ) {
                    foreach ( $arr_projectInfo as $k => $v ) {
                        if ($key == $k) {
                            $arr_projecList [$key] = $v;
                        }
                    }
                }
                // 出项目的信息
                foreach ( $arr_projecList as $key => $val ) {
                    $model = ORM::factory ( "Projectrecommend" )->where ( "project_id", "=", $val ['project_id'] )->where ( "multitude_type", "=", $tag_id )->find ()->as_array ();
                    $project_model = ORM::factory ( "Project", $val ['project_id'] )->as_array ();
                    if ($model ['id']) {
                        $project_model ['product_features'] = $model ['project_introduction'] ? $model ['project_introduction'] : $project_model ['product_features'];
                    }
                    $tempList [] = $project_model;
                }
                $search = new Service_Platform_Search ();
                $array ['list'] = $search->pushProjectInfo ( $tempList, null );
            }
            return $array;
        }
    }
    /**
     * 根据行业的id来获取项目
     *
     * @author 嵇烨
     */
    public function getProjectByIndustryId($industry_id, $pagenum) {
        if ($industry_id) {
            $project_model = new Service_User_Company_Project ();
            $arr_projectInfo = array ();
            // 去行业推荐表拿取数据
            $model_hot_Industry = ORM::factory ( "Projecthotindustry" )->where ( "industry_id", "=", intval ( $industry_id ) )->order_by ( "order_nums", "DESC" )->find_all ();
            foreach ( $model_hot_Industry as $key => $val ) {
                $projectlist = $project_model->getProjectData ( $val->project_id );
                $project_id ['project_id'] = $projectlist->project_id;
                $project_id ['project_brand_name'] = $projectlist->project_brand_name;
                $project_id ['status'] = $val->status;
                $arr_projectInfo [$val->order_nums] = $project_id;
            }
            $nums = count ( $arr_projectInfo );
            // 项目小于5的时候要追加 pv总值的前几个项目
            if ($nums < 6) {
                $arr_newprojectInfo = $this->getProjectListByIndustryID ( $industry_id, $pagenum );
                // 理数组的key重新排序
                $arr_projecList = array ();
                $num = 6;
                foreach ( $arr_newprojectInfo as $key => $val ) {
                    $arr_newprojectInfo [$key] ['status'] = 1;
                    $arr_newprojecList [$num --] = $arr_newprojectInfo [$key];
                    $arr_projecList = $arr_newprojecList;
                }
                // 理数组替换
                foreach ( $arr_projecList as $key => $val ) {
                    foreach ( $arr_projectInfo as $k => $v ) {
                        if ($key == $k) {
                            $arr_projecList [$key] = $v;
                        }
                    }
                }
                return $arr_projecList;
            } else {
                return $arr_projectInfo;
            }
        }
    }
    /**
     * 根据行业的id 还获取项目的信息
     *
     * @author 嵇烨
     */
    public function getProjectListByIndustryID($industry_id, $num = 6) {
        $tempList = array ();
        $projectList = 0;
        $arr_project_id = array();
        if ($industry_id) {
            // 是全部的情况下
            if ($industry_id != 8) {
                // 取本行业的项目id
                $model_industry = ORM::factory ( "Projectindustry" )->where ( "industry_id", "=", intval ( $industry_id ) )->where ( "status", "=", "2" )->find_all ();
                if(count($model_industry) > 0){
                    // 环 获取项目的id
                    foreach ( $model_industry as $key => $val ) {
                        $arr_project_id [] = $val->project_id;
                    }
                    // 除重复
                    array_unique ( $arr_project_id );
                    $project_Models = ORM::factory ( "Project" );
                    $projectList = $project_Models->where ( "project_id", "in", $arr_project_id )->where ( "project_status", "=", 2 )->count_all ();
                }
            } else {
                // 时全部的情况下
                $project_Models = ORM::factory ( "Project" );
                // 目审核通过的数量
                $projectList = $project_Models->where ( "project_status", "=", 2 )->order_by ( 'project_pv_count', 'desc' )->count_all ();
                // 取数据
                $arr_project = $project_Models->where ( "project_status", "=", 2 )->order_by ( 'project_pv_count', 'desc' )->find_all ();
                // 装数组
                foreach ( $arr_project as $key => $val ) {
                    $arr_project_id [] = $val->project_id;
                }
            }
            if(count($projectList) > 0  && !empty($arr_project_id)){
                $page = Pagination::factory ( array (
                        'total_items' => $projectList,
                        'items_per_page' => $num
                ) );
                $list = $project_Models->where ( "project_id", "in", $arr_project_id )->where ( "project_status", "=", 2 )->limit ( $page->items_per_page )->offset ( $page->offset )->order_by ( 'project_pv_count', 'desc' )->find_all ();
                $tempList = array ();
                foreach ( $list as $val ) {
                    $tempList [] = $val->as_array ();
                }
            }
        }
        return $tempList;
    }
    /**
     * 处理要显示的数据
     *
     * @author 嵇烨
     */
    public function disposeProjectIndustry($arr_data,$cache_key) {
        $arr_return_data = array ();
        if (is_array ( $arr_data ) && !empty($arr_data)) {
            foreach ( $arr_data as $key => $val ) {
                $array ['project_id'] = $val ['project_id'];
                $array ['project_brand_name'] = $val ['project_brand_name'];
                $array ['status'] = $val ['status'];
                $arr_return_data [] = $array;
            }
            $memcache = Cache::instance('memcache');
            $memcache->set($cache_key, $arr_return_data, 86400);
        }
        return $arr_return_data;
    }

    /**
     * 排行榜数组合并，去重，排序
     * @author 郁政
     */
    public function getDisposeArr($multipletype,$ranking,$top){
        //合并去重
        $arr1 = array();
        $arr2 = array_merge($multipletype,$ranking);
        $arr3 = array();
        foreach ($arr2 as $k=>$v){
            if (!in_array($v['project_id'], $arr1)){
                $arr1[]=$v['project_id'];
                array_push($arr3,$v);
            }
        }
         //排序
        if(!empty($arr3)){
             foreach($arr3 as $k => $v){
                $amount[$k] = $v['amount'];
            }
            array_multisort($amount,SORT_DESC,$arr3);
            $arr3 = array_slice($arr3, 0,$top);
         }
        return $arr3;
    }
    
    /**
     * 取项目前6条数据(使用memcahe)
     * @author 嵇烨
     */
    public function getNewProjectListNByRandom($top = 0 , $type=1 , $list_type = 1) {
    	$arr_project = array();
    	$memcache = Cache::instance('memcache');
    	$xuanchuanModel = new Service_Platform_Search();
    	$cacheKeyCount = 'getNewProjectListNByRandomCount';
    		$int_num = 0;
    		$int_num = $memcache->get($cacheKeyCount);
    		if(!$int_num) {
    			$int_num = ORM::factory ( "Project" )->select("project_id")->where( 'project_status', '=', 2 )->where_open()->where("project_source","=",intval(2))->or_where("project_source","=",intval(1))->where_close()->count_all();	
    			$memcache->set($cacheKeyCount, $int_num, 86400);
    		}
    		$randNum = intval($int_num/6);
    		$top = 6;
    		$offset = rand(0, $randNum);
    		if($type == 1){
    			#获取项目前6条数据
    			$obj_project = ORM::factory ( "Project" )->select("project_id")->where_open()->where("project_source","=",2)->or_where("project_source","=",1)->where_close()->where ( 'project_status', '=', 2 )->order_by('project_passtime','desc')->limit(intval($top))->offset(intval($offset))->find_all ();
    			#合并数组
    			foreach ($obj_project as $key=>$val){
    				$arr_project [] = $val->as_array();
    			}
    		}else{ 
    			if($list_type == 1){
    				#拿取数据
    				$arr_project = $memcache->get("projectTop6");
    				//var_dump($arr_project);exit;
    				if(empty($arr_project)){
    					#获取项目前六条数据
    					$obj_project = ORM::factory ( "Project" )->select("project_id")->where_open()->where("project_source","=",2)->or_where("project_source","=",1)->where_close()->where ( 'project_status', '=', 2 )->order_by('project_passtime','desc')->limit(intval($top))->offset(intval($offset))->find_all ();
    				}else{
    					return $arr_project;
    				}
    			}else{
    				#拿取数据
    				$arr_project = $memcache->get("projectNewTop6");
    				if(empty($arr_project)){
    					if(count(common::projectIdListIndex()) >=6){
    						$rand_num = 6;
    					}else{
    						$rand_num = count(common::projectIdListIndex());
    					}
	    				$arr_project_id_list = array_rand(common::projectIdListIndex(),$rand_num);    				
	    				$arr875 = $this->getBaQiWu(3);     	    						
	    				$arr_project_id_list = array_merge($arr875,$arr_project_id_list);  
    					if($arr_project_id_list){
    						//echo "<pre>";print_r($arr_project_id_list);exit;
	    					foreach($arr_project_id_list as $v){
	    						$om = ORM::factory ( "Project" )->where ( 'project_status', '=', 2 )->where("project_id","=",$v)->find();
	    						if(isset($om->project_id) && $om->project_id){
	    							$obj_project[] = $om;
	    						}	    						
	    					}
	    				}
    				}else {
    					return $arr_project;
    				}
    			}
    			#合并数组
    			foreach ($obj_project as $key=>$val){
    				$arr_project [] = $val->as_array();
    			}    			
    			#合并数组
    			if($arr_project){
    				foreach ($arr_project as $key=>$val){
    					if(($val['project_source'] == 5 || $val['project_source'] == 4) && $val['project_logo']) {
    						$arr_project[$key]['project_logo']= str_replace("poster/html/ps_{$val['outside_id']}/project_logo/", "poster/html/ps_{$val['outside_id']}/project_logo/150_120/", $val['project_logo']);
    					}
    					$xuanchuanimage = $xuanchuanModel->getProjectXuanChuanImage($val['project_id'],intval(5));
    					if($xuanchuanimage){$arr_project[$key]['project_logo'] = $xuanchuanimage ;}
    				}
    				if($list_type == 2){
    					$memcache->set("projectNewTop6", $arr_project,$this->set_time);
    				}else{
    					$memcache->set("projectTop6", $arr_project,$this->set_time);
    				}
    			}
    	}
        return $arr_project;

    }
    
    /**
     * 你可能喜欢的创业项目(二逼需求)
     * @author 嵇烨
     * @create 2014/3/14
     */
    public function GetYouMayLikeProject($num = 6){
    	$arr_return = array();
    	$memcache = Cache::instance('memcache');
    	$arr_return = $memcache->get("YouMayLikeProject");
    	if(empty($arr_return)){
    		//获取重点难推的项目
    		$arr_project_id_list = $this->getImpAdProByConfig($num);
    		$arr_return = $this->GetProjectByArr($arr_project_id_list);
    		if(count($arr_return) < 6){
    			//彩蛋程序(麻痹的,填充数据)
    			$arr_linshi_id = $this->getSpecialPro((6-count($arr_return)),1);
    			$arr_linshi_list = $this->GetProjectByArr($arr_linshi_id);
    			if($arr_return){
    				$arr_return = @array_merge($arr_return,$arr_linshi_list);
    			}else{
    				$arr_return = $arr_linshi_list;
    			}
    		}
    		$memcache->set("YouMayLikeProject",$arr_return,7200);
    	}
    	return $arr_return;
    	
    }
    /**
     * 大家都喜欢的创业项目(二逼需求)
     * @author 嵇烨
     * @create 2014/3/14
     */
    
    public function GetEveryOneMayLikeProject($top = 5,$zhong_num = 5,$page_type = 1){
    	$arr_return = array();
    	$memcache = Cache::instance('memcache');
    	$xuanchuanModel = new Service_Platform_Search();
    	if($page_type == 1){
    		$arr_return = $memcache->get("EveryOneMayLikeProject");
    		//$arr_return = "";
    	}else{
    		$arr_return = $memcache->get("EveryOneMayLikeProjectZhao");
    		//$arr_return = "";
    	}
    	if($arr_return){
    		return $arr_return;exit;
    	}
    	try{
    		$arr_id_data = array();
    		//4top
    		$arr_top_id_data = $this->getHardProByConfig($top);
    		$arr_id_data = @array_merge($arr_id_data,$arr_top_id_data);
    		//重点广告项目
    		$arr_zong_id_data = $this->getImpAdProByConfig($zhong_num+1);
    		$arr_id_data = @array_merge($arr_id_data,$arr_zong_id_data);
    		$arr_return = $this->GetProjectByArr($arr_id_data);
    		//获取招商外包的项目7天pv数
//     		$arr_project_id_zhao = $this->getProjectByPv(1,strtotime(date('Y-m-d',strtotime('-7 day'))),strtotime(date("Y-m-d")),$zhao_num);
    		
//     		if(count($arr_project_id_zhao) >= $zhao_num) {
//     			$arr_zhao_id = $this->DoArr($arr_project_id_zhao,"project_id");
//     			$arr_return = $this->GetProjectByArr($arr_zhao_id);
//     		}else{
//     			$arr_zhao_id = $this->DoArr($arr_project_id_zhao,"project_id");
//     			$int_zhao_num = $zhao_num - count($arr_project_id_zhao);
//     			//不足时候
//     			if($arr_project_id_zhao){
//     				$arr_project_id = DB::select('project_id')
//     				->from('project')
//     				->where("project_real_order","=",1)
//     				->where("project_id","not in",$arr_zhao_id)
//     				->limit(20)
//     				->execute()
//     				->as_array();
//     			}else{
//     				$arr_project_id = DB::select('project_id')
//     				->from('project')
//     				->where("project_real_order","=",1)
//     				->limit(20)
//     				->execute()
//     				->as_array();
//     			}
//     			$arr_project_id_news = @$this->getRandArray($this->DoArr($arr_project_id,"project_id"),$int_zhao_num);
//     			$arr_zhao_id = @array_merge($arr_zhao_id,$arr_project_id_news);
//     			$arr_return = $this->GetProjectByArr($arr_zhao_id);
//     		}
//     		//获取付费广告项目
//     		$arr_project_id_fu = $this->getProjectByPv(2,strtotime(date('Y-m-d',strtotime('-7 day'))),strtotime(date("Y-m-d")),$fu_num);
//     		if(count($arr_project_id_fu) >= $fu_num){
//     			$arr_fu_id = $this->DoArr($arr_project_id_fu,"project_id");
//     			$arr_return = array_merge($arr_return, $this->GetProjectByArr($arr_fu_id));
//     		}else{
//     			$arr_fu_id = $this->DoArr($arr_project_id_fu,"project_id");
//     			$int_fu_num = $fu_num - count($arr_project_id_fu);
//     			if($arr_project_id_fu){
//     				$arr_fu_project_id = DB::select('project_id')
//     				->from('project')
//     				->where("project_real_order","=",2)
//     				->where("project_id","not in",$arr_fu_id)
//     				->limit(20)
//     				->execute()
//     				->as_array();
//     			}else{
//     				$arr_fu_project_id = DB::select('project_id')
//     				->from('project')
//     				->where("project_real_order","=",2)
//     				->limit(20)
//     				->execute()
//     				->as_array();
//     			}
//     			$arr_fu_id_new = array();
//     			$arr_fu_id_new = @$this->getRandArray($this->DoArr($arr_fu_project_id,"project_id"),$int_fu_num);
//     			$arr_fu_id = @array_merge($arr_fu_id,$arr_fu_id_new); 
//     			$arr_return = array_merge($arr_return, $this->GetProjectByArr($arr_fu_id));
//     		}
//     		//普通项目
//     		$arr_project_id_zi = $this->getProjectByPv(4,strtotime(date('Y-m-d',strtotime('-7 day'))),strtotime(date("Y-m-d")),$zi_num);
//     		if(count($arr_project_id_zi) >=$zi_num){
//     			$arr_zi_id = $this->DoArr($arr_project_id_zi,"project_id");
//     			$arr_return = array_merge($arr_return, $this->GetProjectByArr($arr_zi_id));
//     		}else{
//     			$arr_zi_id = $this->DoArr($arr_project_id_zi,"project_id");
//     			$int_zi_num = $zi_num - count($arr_project_id_zi);
//     			if($arr_project_id_zi){
//     				$arr_zi_project_id = DB::select('project_id')
//     				->from('project')
//     				->where("project_real_order","=",4)
//     				->where("project_id","not in",$arr_zi_id)
//     				->limit(20)
//     				->execute()
//     				->as_array();
//     			}else{
//     				$arr_zi_project_id = DB::select('project_id')
//     				->from('project')
//     				->where("project_real_order","=",4)
//     				->limit(20)
//     				->execute()
//     				->as_array();
//     			}
//     			$arr_zi_id_new = array();
//     			$arr_zi_id_new = @$this->getRandArray($this->DoArr($arr_zi_project_id,"project_id"),$int_zi_num);
//     			$arr_zi_id = @array_merge($arr_zi_id,$arr_zi_id_new);
//     			$arr_return = array_merge($arr_return, $this->GetProjectByArr($arr_zi_id));
//     		}
    		//处理一下图片
    		if($arr_return){
    			foreach ($arr_return as $key=>$val){
    				//外采图片
    				if(arr::get($val,"project_real_order") == 6 && arr::get($val, "project_logo")){
    					$logo = $this->DoOutsidePic(arr::get($val, "project_logo"), arr::get($val, "outside_id"));
    					$arr_return[$key]['project_logo'] = $logo ? $logo:arr::get($val, "project_logo");
    				}
    				//推广小图
	    			$xuanchuanimage = $xuanchuanModel->getProjectXuanChuanImage($val['project_id'],intval(5));
	   				if($xuanchuanimage ){$arr_return[$key]['project_logo'] = $xuanchuanimage ;}
    			}
    		}
    		if($page_type == 1){
    			$memcache->set("EveryOneMayLikeProject", $arr_return,7200);
    		}else{
    			$memcache->set("EveryOneMayLikeProjectZhao", $arr_return,7200);
    		}
    		
    	}catch (ErrorException $e){
    		return array();
    	}
    	
    	return $arr_return;
    }
    /**
     * 处理图片
     * @author jiye
     * @creta time 2014/3/14
     */
    public function DoOutsidePic($logo,$outside_id){
    	try{
    		if($logo && $outside_id){
    			return str_replace("poster/html/ps_{$outside_id}/project_logo/", "poster/html/ps_{$outside_id}/project_logo/150_120/",$logo);
    		}
    		return "";
    	}catch (ErrorException $e){
    		return "";
    	}
    }
    /**
     * 处理数组
     * @author jiye
     * @creta time 2014、3、14
     */
    public function DoArr($arr_data,$field = "project_id"){
    	$arr_return = array();
    	try{
    		foreach ($arr_data as $key=>$val){
    			$arr_return[]= arr::get($val,$field) ?  arr::get($val,$field) : $val;
    		}
    		return $arr_return;
    	}catch (ErrorException $e){
    		return array();
    	}
    }
    
    /**
     * 获取项目信息
     * @author jiye
     * @create time 2014/3/14
     */
    public function GetProjectByArr($data = array()){
    	$return_data = array();
    	try{
    		if($data){
    			foreach ($data as $key=>$val){
    				$arr_list = ORM::factory("Project",intval($val))->as_array();
    				if(arr::get($arr_list, "project_id") && arr::get($arr_list, "project_id") > 0){
    					$data = array();
    					$data['project_id'] = arr::get($arr_list, "project_id");
    					$data['project_amount_type'] = arr::get($arr_list, "project_amount_type");
    					$data['project_source'] = arr::get($arr_list, "project_source");
    					$data['com_id'] = arr::get($arr_list, "com_id");
    					$data['outside_id'] = arr::get($arr_list, "outside_id");
    					$data['project_brand_name'] = arr::get($arr_list, "project_brand_name");
    					$data['project_logo'] = arr::get($arr_list, "project_logo");
    					$data['project_real_order'] = arr::get($arr_list, "project_real_order");
    					$data['project_advert'] = arr::get($arr_list, "project_advert");
    					$data['project_brand_birthplace'] = arr::get($arr_list, "project_brand_birthplace");
    					$data['project_pv_count'] = arr::get($arr_list, "project_pv_count");
    					$return_data[] = $data;
    				}
    			}
    		}
    	}catch (Kohana_Exception $e){
    		return array();
    	}
    	return $return_data;
    }
    /**
     * 链表获取7天pv项目
     * @author jiye
     * @create time 2014/3/14
     */
    public function getProjectByPv($project_real_order=1,$date_start,$date_end,$top = 5){
    	try{
    		if($date_start && $date_end){
    			return DB::select('project.project_id',array(DB::expr('COUNT(czzs_project_statistics.project_id)'),'amount'))
    			->from('project')
    			->join('project_statistics','left')
    			->on('project.project_id','=','project_statistics.project_id')
    			->where('project.project_status','=',2)
    			->where('project.project_real_order','=',$project_real_order)
    			->where('project_statistics.insert_time','>=',$date_start)
    			->where('project_statistics.insert_time','<=',$date_end)
    			->group_by('project_statistics.project_id')
    			->order_by('amount', 'desc')
    			->limit($top)
    			->execute()
    			->as_array();
    			
    		}else{
    			return DB::select('project.project_id',array(DB::expr('COUNT(czzs_project_statistics.project_id)'),'amount'))
    			->from('project')
    			->join('project_statistics','left')
    			->on('project.project_id','=','project_statistics.project_id')
    			->where('project.project_status','=',2)
    			->where('project.project_real_order','=',$project_real_order)
    			->group_by('project_statistics.project_id')
    			->order_by('amount', 'desc')
    			->limit($top)
    			->execute()
    			->as_array();
    		}
    	}catch (ErrorException $e){
    		return array();
    	}
    	
    }
    
    
    
    /**
     * 首页大家都喜欢的好项目前六个
     * @author 嵇烨
     */
   public  function getMaxProjectListPv($top = 6){
   	$project_ids = array();
   	$arr_project = array();
   	$memcache = Cache::instance('memcache');
   	$xuanchuanModel = new Service_Platform_Search();
   	#拿取数据
   	$arr_project = $memcache->get("projectTopPv6");
   	if(empty($arr_project)){
   		
   		if(count(common::projectListLin()) >=6){
   			$project_ids = array_rand(common::projectListLin(),6);
   		}else{
   			$project_ids = common::projectListLin();
   		}
   		$arr_project = array();
   		foreach($project_ids as $v){
   			$arr_new_project = ORM::factory ('Project')->where ('project_status', '=', 2)->where('project_id','=',$v)->find()->as_array();
   			if($arr_new_project["project_id"] > 0){
   				$arr_project[] = $arr_new_project;
   			}
   		}
   		#合并数组
   		if($arr_project){
   			foreach ($arr_project as $key=>$val){
   				#找去项目小图
   				if(($val['project_source'] == 5 || $val['project_source'] == 4) && $val['project_logo']) {
   					$project_logo = str_replace("poster/html/ps_{$val['outside_id']}/project_logo/", "poster/html/ps_{$val['outside_id']}/project_logo/150_120/", $val['project_logo']);
   					$arr_project[$key]['project_logo'] = $project_logo ;
   				}
   				#是否有宣传图片
   				$xuanchuanimage = $xuanchuanModel->getProjectXuanChuanImage($val['project_id'],intval(5));
   				if($xuanchuanimage ){$arr_project[$key]['project_logo'] = $xuanchuanimage ;}
   				}
   				$memcache->set("projectTopPv6", $arr_project,60*20);
   		}
   	}
   	return $arr_project;
   }
   
   /**
     * 按行业，人群，地区，推荐热门项目
     * @author 郁政
     */
	public function getHotListByOther($limit,$type,$search){		
    	$type = intval($type);
    	$statistics = array();
    	$memcache = Cache::instance ( 'memcache' );
    	$num = 0;
    	$offset = 0;
    	$count = 0;
    	$countKey = 'cache_hotlist_'.$type.'_'.$search.'_count';
    	$listKey = 'cache_hotlist_'.$type.'_'.$search.'_list';
    	$count = $memcache->get($countKey);
    	$statistics = $memcache->get($listKey);
		if($type == 1){//按行业			
			if(!$count){
				$count = ORM::factory('Project')->join('project_industry','left')->on('project.project_id','=','project_industry.project_id')->where('project.project_status','=',2)->where('project.project_source','=',2)->where('project_industry.industry_id','=',$search)->count_all();
				$memcache->set($countKey, $count, 86400);		
			}		
			if(!$statistics){
				$offset = intval($count/$limit);
				$num = $offset ? rand(0, $offset-1) : 0;
				$statistics = DB::select('project.project_id')->from('project')->join('project_industry','left')->on('project.project_id','=','project_industry.project_id')->where('project.project_status','=',2)->where('project.project_source','=',2)->where('project_industry.industry_id','=',$search)->limit($limit)->offset($num)->execute()->as_array();
				$memcache->set($listKey, $statistics, 7200);
			}	       
		}elseif($type == 2){//按人群	
			if(!$count){
				$count = ORM::factory('Project')->join('project_crowd','left')->on('project.project_id','=','project_crowd.project_id')->where('project.project_status','=',2)->where('project.project_source','=',2)->where('project_crowd.tag_id','=',$search)->count_all();
				$memcache->set($countKey, $count, 86400);
			}		
			if(!$statistics){
				$offset = intval($count/$limit);
				$num = $offset ? rand(0, $offset-1) : 0;		
				$statistics = DB::select('project.project_id')->from("project")->join('project_crowd','left')->on('project.project_id','=','project_crowd.project_id')->where('project.project_status','=',2)->where('project.project_source','=',2)->where('project_crowd.tag_id','=',$search)->limit($limit)->offset($num)->execute()->as_array();
				$memcache->set($listKey,$statistics, 7200);
			}			
		}elseif($type == 3){//按地区
			if(!$count){
				$count = ORM::factory('Project')->join('project_area','left')->on('project.project_id','=','project_area.project_id')->where('project.project_status','=',2)->where('project.project_source','=',2)->where('project_area.pro_id','=',$search)->count_all();
				$memcache->set($countKey, $count,86400);
			}
			if(!$statistics){
				$offset = intval($count/$limit);
				$num = $offset ? rand(0, $offset-1) : 0;		
				$statistics = DB::select('project.project_id')->from("project")->join('project_area','left')->on('project.project_id','=','project_area.project_id')->where('project.project_status','=',2)->where('project.project_source','=',2)->where('project_area.pro_id','=',$search)->limit($limit)->offset($num)->execute()->as_array();
				$memcache->set($listKey, $statistics, 7200);
			}			
		}
		$arr875 = $this->getBaQiWu(3);
		$arrN = array();
		if($arr875){
			foreach ($arr875 as $k => $v){
				$arrN[$k]['project_id'] = $v;
			}
		}
        $statistics = array_slice(array_merge($arrN,$statistics),0,5);		
		return $statistics;
	}
	
	/**
     * 推荐热门项目需要的内容
     * @author 郁政
     */
	public function getHotListNeed($arr = array()){	
		if(!empty($arr)){
			$xuanchuanModel = new Service_Platform_Search();
			foreach($arr as $k => $v){
				$project = ORM::factory('Project')->where('project_id','=',$v['project_id'])->find();
				if(isset($project->project_id) && $project->project_id != ''){
					
					if(($project->project_source == 5 || $project->project_source == 4) && $project->project_logo) {
						$project_logo = str_replace("poster/html/ps_{$project->outside_id}/project_logo/", "poster/html/ps_{$project->outside_id}/project_logo/150_120/", $project->project_logo);
						$arr[$k]['project_logo'] = $project_logo ;
					}else{
						$arr[$k]['project_logo'] = $project->project_logo;;
					}
					#判断有没有小图
					$xuanchuanimage = $xuanchuanModel->getProjectXuanChuanImage($v['project_id'],intval(5));
					if($xuanchuanimage){ $arr[$k]['project_logo'] = $xuanchuanimage;}else{ $arr[$k]['project_logo'] = $project->project_logo;};
					$arr[$k]['project_source'] = $project->project_source;
					$arr[$k]['project_brand_name'] = $project->project_brand_name;
					$arr[$k]['project_advert'] = $project->project_advert;
				}
			}
		}
		return $arr;
	}
	
	/**
     * 根据行业id返回行业名称
     * @author 郁政
     */
	public function getIndustryNameById($industry_id){
		$industry_id = intval($industry_id);
		$industry_name = "";
		$industry = ORM::factory('Industry')->where('industry_id','=',$industry_id)->find();
		if(isset($industry->industry_id) && $industry->industry_id != ""){
			$industry_name = $industry->industry_name;
		}
		return $industry_name;
	}
	
	/**
     * 根据地区id返回一级城市id
     * @author 郁政
     */
	public function getAreaIdByCityId($area_id){
		$area_id = intval($area_id);
		$aid = 0;
		$area = ORM::factory('Projectarea')->where('area_id','=',$area_id)->find();
		if(isset($area->project_area_id) && $area->project_area_id != ''){
			$aid = $area->pro_id;
		}
		return $aid;
	}
	
	/**
     * 随机875项目
     * @author 郁政
     */
	public function getBaQiWu($limit){
		$res = array();
		$cache = Cache::instance ( 'memcache' );
		$pro_875 = $cache->get('random_875');
		$num = 0;
		$count = 0;
		$offset = 0;
		if($pro_875){
			return $pro_875;
		}else{
			$count = ORM::factory('Project')->where('project_status','=',2)->where('project_source','=',2)->count_all();
			$offset = intval($count/$limit);
			$num = $offset ? rand(0, $offset-1) : 0;
			$project = ORM::factory('Project')->where('project_status','=',2)->where('project_source','=',2)->limit($limit)->offset($num)->find_all()->as_array();
			if(count($project)>0){
				foreach($project as $v){
					$res[] = $v->project_id;
				}
			}
			$cache->set('random_875', $res,$this->set_time);
			return $res;	
		}		
	}
	/**
	 *封装 array_rand
	 * @param unknown $array
	 * @param unknown $num
	 * @return multitype:unknown
	 */
	function getRandArray ($array,$num){
		if($num == 1){
			$key[] = array_rand($array,$num);
		}else{
			$key = array_rand($array,$num);
		}
		$new=array();
		foreach($key as $v){
			$new[]=$array[$v];
		}
		return $new;
	}
	
	/**
     * 按不同类型随机项目
     * @author 郁政
     */
	public function getSpecialPro($limit,$type){
		$res = array();
		$num = 0;
		$count = 0;
		$offset = 0;
		$count = ORM::factory('Project')->where('project_status','=',2)->where('project_real_order','=',$type)->count_all();
		$offset = intval($count/$limit);
		$num = $offset ? rand(0, $offset-1) : 0;
		$project = ORM::factory('Project')->where('project_status','=',2)->where('project_real_order','=',$type)->limit($limit)->offset($num)->find_all()->as_array();
		if(count($project)>0){
			foreach($project as $v){
				$res[] = $v->project_id;
			}
		}
		return $res;
	}
	
	/**
     * 最新商机推荐（个人中心）
     * @author 郁政
     */
	public function getPersonNewTuiJian($limit,$type){
		$res = array();
		$project = ORM::factory('Project')->where('project_status','=',2)->where('project_real_order','=',$type)->order_by('project_addtime','desc')->limit($limit)->find_all()->as_array();
		if(count($project)>0){
			foreach($project as $v){
				$res[] = $v->project_id;
			}
		}
		return $res;
	}
	
	/**
     * 项目向导按行业推荐
     * @author 郁政
     */
	public function getProForIndustry($limit,$industry_id){
		$res = array();
		$num = 0;
    	$offset = 0;
    	$count = 0;
    	$count = ORM::factory('Project')->join('project_industry','left')->on('project.project_id','=','project_industry.project_id')->where('project.project_status','=',2)->where('project_real_order','=',1)->where('project_industry.industry_id','=',$industry_id)->count_all();
    	$offset = intval($count/$limit);
		$num = $offset ? rand(0, $offset-1) : 0;
		$project = ORM::factory('Project')->join('project_industry','left')->on('project.project_id','=','project_industry.project_id')->where('project.project_status','=',2)->where('project_real_order','=',1)->where('project_industry.industry_id','=',$industry_id)->limit($limit)->offset($num)->find_all()->as_array();
		if(count($project)>0){
			foreach($project as $v){
				$res[] = $v->project_id;
			}
		}
		if(count($res) < $limit){
			$limit1 = $limit - count($res);
			$num = 0;
	    	$offset = 0;
	    	$count = 0;
	    	$count = ORM::factory('Project')->join('project_industry','left')->on('project.project_id','=','project_industry.project_id')->where('project.project_status','=',2)->where('project_real_order','=',2)->where('project_industry.industry_id','=',$industry_id)->count_all();
	    	$offset = intval($count/$limit1);
			$num = $offset ? rand(0, $offset-1) : 0;
			$project = ORM::factory('Project')->join('project_industry','left')->on('project.project_id','=','project_industry.project_id')->where('project.project_status','=',2)->where('project_real_order','=',2)->where('project_industry.industry_id','=',$industry_id)->limit($limit1)->offset($num)->find_all()->as_array();
			if(count($project)>0){
				foreach($project as $v){
					$res[] = $v->project_id;
				}
			}
			if(count($res) < $limit){
				$limit2 = $limit - count($res);
				$num = 0;
		    	$offset = 0;
		    	$count = 0;
		    	$count = ORM::factory('Project')->join('project_industry','left')->on('project.project_id','=','project_industry.project_id')->where('project.project_status','=',3)->where('project_real_order','=',2)->where('project_industry.industry_id','=',$industry_id)->count_all();
				$offset = intval($count/$limit2);
				$num = $offset ? rand(0, $offset-1) : 0;
				$project = ORM::factory('Project')->join('project_industry','left')->on('project.project_id','=','project_industry.project_id')->where('project.project_status','=',3)->where('project_real_order','=',2)->where('project_industry.industry_id','=',$industry_id)->limit($limit2)->offset($num)->find_all()->as_array();
				if(count($project)>0){
					foreach($project as $v){
						$res[] = $v->project_id;
					}
				}
			}
		}
		return $res;
	}
	
	/**
     * 难推的招商外包项目
     * @author 郁政
     */
	public function getHardProByConfig($limit){
		$res = array();
		$project_ids = common::projectListHard();
		$res = array_rand($project_ids,$limit);
		return $res;
	}
	
	/**
     * 重点广告项目
     * @author 郁政
     */
	public function getImpAdProByConfig($limit){
		$res = array();
		$project_ids = common::projectListImpAd();
		$res = array_rand($project_ids,$limit);
		return $res;
	}
	
	/**
	 * 推荐热门项目需要的内容(终极版)
	 * @author 郁政
	 */
	public function getNewHotListNeed($arr = array()){
		$res = array();
		if(!empty($arr)){
			$xuanchuanModel = new Service_Platform_Search();
			foreach($arr as $k => $v){
				$project = ORM::factory('Project')->where('project_id','=',$v)->find();
				if(isset($project->project_id) && $project->project_id != ''){
					if(($project->project_source == 5 || $project->project_source == 4) && $project->project_logo) {
						$project_logo = str_replace("poster/html/ps_{$project->outside_id}/project_logo/", "poster/html/ps_{$project->outside_id}/project_logo/150_120/", $project->project_logo);
						$res[$k]['project_logo'] = $project_logo ;
					}else{
						$res[$k]['project_logo'] = $project->project_logo;;
					}
					#判断有没有小图
					$xuanchuanimage = $xuanchuanModel->getProjectXuanChuanImage($v,intval(5));
					if($xuanchuanimage){ $res[$k]['project_logo'] = $xuanchuanimage;}else{ $res[$k]['project_logo'] = $project->project_logo;};
					$res[$k]['project_source'] = $project->project_source;
					$res[$k]['project_brand_name'] = $project->project_brand_name;
					$res[$k]['project_advert'] = $project->project_advert;
					$res[$k]['project_id'] = $v;
				}
			}
		}
		return $res;
	}
}


