<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 项目官网 Service
 * @author 郁政
 */
class Service_Platform_ExhbProject {

    /**
     * 项目官网 项目信息
     * @author 郁政
     */
    public function getProjectInfo($project_id) {
        $res = array();
        $service = new Service_User_Company_Exhb();
        $proInfo = $service->getProject($project_id);
        $exhb_id = $proInfo['exhibition_id'];
        $exhbInfo = $service->exhbInfoById($exhb_id);
        $res = $service->getExhbProjectMore($project_id, 2);
        $res['outside_id'] = isset($proInfo['outside_id']) ? $proInfo['outside_id'] : 0;
        $res['project_brand_name'] = isset($proInfo['project_brand_name']) ? $proInfo['project_brand_name'] : '';
        $res['company_name'] = $this->getCompanyNameByComId($proInfo['com_id']);
        $res['exhibition_name'] = isset($exhbInfo['exhibition_name']) ? $exhbInfo['exhibition_name'] : '';
        $res['exhibition_end'] = isset($exhbInfo['exhibition_end']) ? date('Y/m/d,H:i:s', $exhbInfo['exhibition_end']) : 0;
        //取得招商地区
        $res['area'] = $service->getAreaIdName($project_id);
        //取得行业	
        $res['pro_industry'] = $service->getIndustryIdByPid($project_id, 2);
        //取得招商形式
        $res['project_model'] = $service->getProjectCoModel($project_id);
        $res['project_amount_type'] = isset($proInfo['project_amount_type']) ? $proInfo['project_amount_type'] : 0;
        $res['expected_return'] = isset($proInfo['expected_return']) ? $proInfo['expected_return'] : '';
        $res['expected_return_img'] = isset($proInfo['expected_return_img']) ? $proInfo['expected_return_img'] : '';
        $res['preferential_policy'] = isset($proInfo['preferential_policy']) ? $proInfo['preferential_policy'] : '';
        $res['preferential_policy_img'] = isset($proInfo['preferential_policy_img']) ? $proInfo['preferential_policy_img'] : '';
        $res['company_strength'] = isset($proInfo['company_strength']) ? $proInfo['company_strength'] : '';
        $res['company_strength_img'] = isset($proInfo['company_strength_img']) ? $proInfo['company_strength_img'] : '';
        $res['project_video'] = isset($proInfo['project_video']) ? $proInfo['project_video'] : '';
        return $res;
    }

    /**
     * 根据公司id返回公司名称
     * @author 郁政
     */
    public function getCompanyNameByComId($com_id) {
        $com_name = '';
        $comInfo = ORM::factory('Companyinfo', $com_id);
        if ($comInfo->loaded()) {
            $com_name = $comInfo->com_name;
        }
        return $com_name;
    }

    /**
     * 网络展会项目官网推荐项目
     * @author 郁政
     */
    public function getTuiJianPro($project_id, $limit) {
        $res = array();
        $project_ids = array();
        $service = new Service_User_Company_Exhb;
        $cache = Cache::instance('memcache');
        $date = $cache->get('exhb_tuijian_project'.$project_id);
        if($date){
        	$res = $date;
        }else{
	        $arr = $service->getIndustryIdByPid($project_id, 2);
	        if ($arr) {
	            $num = 0;
	            $offset = 0;
	            $count = 0;
	            $industry_id = isset($arr['industry_id2']) ? $arr['industry_id2'] : $arr['industry_id1'];
	            $count = ORM::factory('ExhbProjectindustry')->where('industry_id', '=', $industry_id)->where('status', '=', 1)->where('project_id', '<>', $project_id)->count_all();
	            if ($count >= $limit) {
	            	$count = ($count <= 50) ? $count : 50;
	                $offset = intval($count / $limit);
	                $num = $offset ? rand(0, $offset - 1) : 0;
	                $exhbProIndustry = ORM::factory('ExhbProjectindustry')->where('industry_id', '=', $industry_id)->where('status', '=', 1)->where('project_id', '<>', $project_id)->order_by('pi_id','desc')->limit($limit)->offset($num)->find_all()->as_array();
	                if (count($exhbProIndustry) > 0) {
	                    foreach ($exhbProIndustry as $v) {
	                        $project_ids[] = $v->project_id;
	                    }
	                }
	            } else {
	                $arr1 = ORM::factory('ExhbProjectindustry')->where('industry_id', '=', $industry_id)->where('status', '=', 1)->where('project_id', '<>', $project_id)->find_all()->as_array();
	                $projects_1 = array();
	                if (count($arr1) > 0) {
	                    foreach ($arr1 as $v) {
	                        $projects_1[] = $v->project_id;
	                    }
	                }
	                $projects_1 = count($projects_1) ? $projects_1 : array(0);
	                $count1 = ORM::factory('ExhbProjectindustry')->where('status', '=', 1)->where('project_id', 'not in', $projects_1)->where('project_id', '<>', $project_id)->group_by('project_id')->count_all();
	                $limit1 = $limit - $count;
	                $offset = intval($count1 / $limit1);
	                $num = $offset ? rand(0, $offset - 1) : 0;
	                $arr2 = ORM::factory('ExhbProjectindustry')->where('status', '=', 1)->where('project_id', 'not in', $projects_1)->where('project_id', '<>', $project_id)->group_by('project_id')->limit($limit1)->offset($num)->find_all()->as_array();
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
	                    $res[] = $service->getProject($v);
	                }
	            }
	        }
	        $cache->set('exhb_tuijian_project'.$project_id, $res,86400);
        }        
        return $res;
    }

    /**
     * 红包信息
     * @author 郁政
     */
    public function getHongBaoInfo($exhb_id) {
        $res = array();
        $service = new Service_User_Company_Exhb();
        $exhbInfo = $service->exhbInfoById($exhb_id);
        $res['exhibition_hongbao'] = isset($exhbInfo['exhibition_hongbao']) ? $exhbInfo['exhibition_hongbao'] : 0;
        $res['hongbao_num'] = isset($exhbInfo['hongbao_num']) ? $exhbInfo['hongbao_num'] : 0;
        $count = ORM::factory('ExhbHongBao')->where('exhibition_id', '=', $exhb_id)->count_all();
        $res['shengyu'] = (($res['hongbao_num'] - $count) > 0) ? ($res['hongbao_num'] - $count) : 0;
        return $res;
    }

    /**
     * 项目优惠劵信息
     * @author 郁政
     */
    public function getYouHuiJuanInfo($project_id) {
        $res = array();
        $service = new Service_User_Company_Exhb();
        $proInfo = $service->getProject($project_id);
        $exhb_id = isset($proInfo['exhibition_id']) ? $proInfo['exhibition_id'] : 0;
        $res['project_coupon'] = isset($proInfo['project_coupon']) ? $proInfo['project_coupon'] : 0;
        $res['coupon_num'] = isset($proInfo['coupon_num']) ? $proInfo['coupon_num'] : 0;
        $count = ORM::factory('ExhbCoupon')->where('project_id', '=', $project_id)->where('exhibition_id', '=', $exhb_id)->count_all();
        $res['shengyu'] = (($res['coupon_num'] - $count) > 0) ? ($res['coupon_num'] - $count) : 0;
        return $res;
    }

    /**
     * 领取项目优惠劵
     * @author 郁政
     */
    public function getYouHuiQuan($project_id, $user_id) {
        $res = array();
        $res['status'] = '203';
        $res['num'] = 0;
        $service = new Service_User_Company_Exhb();
        $proInfo = $service->getProject($project_id);
        $exhb_id = isset($proInfo['exhibition_id']) ? $proInfo['exhibition_id'] : 0;
        $exhbInfo = $service->exhbInfoById($exhb_id);
        if (isset($exhbInfo['exhibition_end']) && $exhbInfo['exhibition_end'] < time()) {
            $res['status'] = '203';
        } else {
            $youhuiquanInfo = $this->getYouHuiJuanInfo($project_id);
            $num = isset($youhuiquanInfo['project_coupon']) ? $youhuiquanInfo['project_coupon'] : 0;
            $date = isset($proInfo['coupon_deadline']) ? $proInfo['coupon_deadline'] : time();
            $amount = isset($youhuiquanInfo['coupon_num']) ? $youhuiquanInfo['coupon_num'] : 0;
            $shengyu = isset($youhuiquanInfo['shengyu']) ? $youhuiquanInfo['shengyu'] : 0;
            $count = ORM::factory('ExhbCoupon')->where('project_id', '=', $project_id)->where('exhibition_id', '=', $exhb_id)->count_all();
            if (($amount - $count) > 0) {
                $coupon = ORM::factory('ExhbCoupon')->where('project_id', '=', $project_id)->where('person_id', '=', $user_id)->where('exhibition_id', '=', $exhb_id)->count_all();
                if ($coupon) {
                    $res['status'] = '205';
                } else {
                    $om = ORM::factory('ExhbCoupon');
                    $om->person_id = $user_id;
                    $om->fetch_time = time();
                    $om->project_id = $project_id;
                    $om->exhibition_id = $exhb_id;
                    $suc = $om->create();
                    if ($suc->fetch_id) {
                        $res['status'] = '200';
                        $res['num'] = $num;
                        $res['date'] = date('Y-m-d', $date);
                        $res['shengyu'] = $shengyu ? ($shengyu - 1) : 0;
                        $postdata = array(
                            'projectid' => $proInfo['outside_id'],
                            'type' => 1,
                            'content' => '你们的项目很好，请速速联系我详谈'
                        );
                        $pser = new Service_Platform_Project();
                        $to_user_id = $pser->getUseridByProjectID($proInfo['outside_id']);
                        if ($to_user_id) {
                            $postdata['to_user_id'] = $to_user_id;
                        } else {
                            $postdata['to_user_id'] = 0;
                        }
                        $perservice2 = new Service_User_Company_Card();
                        $ret = $perservice2->addOutCardByPerson($user_id, $postdata);
                        if($ret){
                        	$this->addProStatistics($project_id,$user_id,3);
                        }
                    }
                }
            } else {
                $res['status'] = '204';
            }
        }
        return $res;
    }

    /**
     * 领取红包
     * @author 郁政
     */
    public function getHongBao($exhb_id, $user_id) {
        $res = array();
        $res['status'] = '203';
        $res['num'] = 0;
        $service = new Service_User_Company_Exhb();
        $exhbInfo = $service->exhbInfoById($exhb_id);
        if (isset($exhbInfo['exhibition_end']) && $exhbInfo['exhibition_end'] < time()) {
            $res['status'] = '203';
        } else {
            $hongbao = $this->getHongBaoInfo($exhb_id);
            $num = isset($hongbao['exhibition_hongbao']) ? $hongbao['exhibition_hongbao'] : 0;
            $start_date = isset($exhbInfo['exhibition_start']) ? $exhbInfo['exhibition_start'] : time();
            $end_date = isset($exhbInfo['exhibition_end']) ? $exhbInfo['exhibition_end'] : time();
            $amount = isset($hongbao['hongbao_num']) ? $hongbao['hongbao_num'] : 0;
            $shengyu = isset($hongbao['shengyu']) ? $hongbao['shengyu'] : 0;
            $count = ORM::factory('ExhbHongBao')->where('exhibition_id', '=', $exhb_id)->count_all();
            if (($amount - $count) > 0) {
                $hongbao = ORM::factory('ExhbHongBao')->where('person_id', '=', $user_id)->where('exhibition_id', '=', $exhb_id)->count_all();
                if ($hongbao) {
                    $res['status'] = '205';
                } else {
                    $om = ORM::factory('ExhbHongBao');
                    $om->person_id = $user_id;
                    $om->fetch_time = time();
                    $om->exhibition_id = $exhb_id;
                    $suc = $om->create();
                    if ($suc->fetch_id) {
                        $res['status'] = '200';
                        $res['num'] = $num;
                        $res['date'] = date('Y-m-d', $start_date) . '至' . date('Y-m-d', $end_date);
                        $res['shengyu'] = $shengyu ? ($shengyu - 1) : 0;
                    }
                }
            } else {
                $res['status'] = '204';
            }
        }
        return $res;
    }
    /**
     * 
     * @param type $project_id
     * @param type $user_id
     * @param type $type
     * @return boolean
     * @author 我连author都不想写 我是鸡爷  我为自己代言
     */
    public function addProStatistics($project_id, $user_id = 0, $type = 1) {
        $redis = Cache::instance ('redis');
        $key = 'exhb_'.$project_id."_statistics_type_".$type;
        $project_id = intval($project_id);
        if(!$project_id) return FALSE;
        $orm = ORM::factory('ExhbProjectstatistics');
        $orm->project_id = $project_id;
        $orm->ip = ip2long(Request::$client_ip);
        $orm->user_id = $user_id;
        $orm->page_type = $type;
        $orm->insert_time = time();
        $suc = $orm->create();
        $int_project_redis_count = $redis->get($key);
        $arrParam = array('1' => 'project_pv_cout', '2' => 'project_communication_count', '3' => 'project_cart_cout');
        #如果存在  缓存加1  数据库更新
        if($suc->id > 0){
	        if($int_project_redis_count){
	        	#存入缓存
	        	$redis->set($key, $int_project_redis_count + 1,60*20);
	        }else{
                    $count = $this->getProStatistics($project_id, $type);
                    $this->addCount($project_id, arr::get($arrParam, $type, 'project_pv_cout'), $count);
                }
	        return true;
        }
    }

    /**
     * 
     * @param type $project_id
     * @param type $type
     * @return int
     * @author 我连author都不想写 我是鸡爷  我为自己代言
     */
    public function getProStatistics($project_id, $type = 1) {
        $redis = Cache::instance ('redis');
        $key = 'exhb_'.$project_id."_statistics_type_".$type;
        $project_id = intval($project_id);
        if(!$project_id) return 0;
        $int_project_redis_count = $redis->get($key);
        if($int_project_redis_count) 
            return $int_project_redis_count;
        $orm = ORM::factory('ExhbProjectstatistics')->where('project_id', '=', $project_id)->where('page_type', '=', $type)->count_all();
        if($orm) 
            $redis->set($key, $orm,60*20);
        return $orm;
    }

    
    /**
     * 多场展会动态数据播报
     * @author 郁政
     */
    public function getDuoChangBoBao(){
    	$res = array();
    	$cache = Cache::instance('memcache');
    	$data = $cache->get('exhb_duochangbobao');
    	$client = Service_Sso_Client::instance();  
    	$exhb_service = new Service_User_Company_Exhb();
    	if($data){
    		$res = $data;
    	}else{  
	    	$str6 = "";
	    	$str1 = "";
	    	$om1 = DB::select('exhb_project_statistics.project_id','exhb_project_statistics.user_id','exhb_project.project_brand_name')
	    			->from('exhb_project_statistics')
	    			->join('exhb_project','left')
	    			->on('exhb_project_statistics.project_id','=','exhb_project.project_id') 
	    			->where('exhb_project_statistics.page_type','=',intval(2))
	    			->order_by('exhb_project_statistics.insert_time','desc')
	    			->limit(1)
	    			->execute()
	    			->as_array();	
	    	if($om1){
	    		$userperson = $client->getUserInfoById($om1[0]['user_id']);	
	    		$str1 = "<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>刚刚对<a class='blue' target='_blank' href='".urlbuilder::exhbProject($om1[0]['project_id'])."'>".$om1[0]['project_brand_name']."</a>项目进行了在线沟通；";
	    	}	
	    	$res[] = $str1;
	    	$str2 = "";
	    	$om2 = DB::select('exhb_project_statistics.project_id','exhb_project_statistics.user_id','exhb_project.project_brand_name')
	    			->from('exhb_project_statistics')
	    			->join('exhb_project','left')
	    			->on('exhb_project_statistics.project_id','=','exhb_project.project_id') 
	    			->where('exhb_project_statistics.page_type','=',intval(3))
	    			->order_by('exhb_project_statistics.insert_time','desc')
	    			->limit(1)
	    			->execute()
	    			->as_array();	
	    	if($om2){    		
	    		$userperson = $client->getUserInfoById($om2[0]['user_id']);		    		
	    		$str2 = "<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>刚刚对<a class='blue' target='_blank' href='".urlbuilder::exhbProject($om2[0]['project_id'])."'>".$om2[0]['project_brand_name']."</a>项目进行了意向咨询；";
	    	}			
	    	$res[] = $str2;
	    	$str3 = "";
	    	$exhb = ORM::factory('Exhibition')
	    			->where('exhibition_status','=',intval(1))
	    			->where('exhibition_end','>=',time())
	    			->find_all()
	    			->as_array();
	    	$exhb_ids = array();
	    	if(count($exhb) > 0){
	    		foreach($exhb as $v){
	    			$exhb_ids[] = $v->exhibition_id;
	    		}
	    	}
	    	if($exhb_ids){
	    		$people_count_1 = ORM::factory('ExhbProjectstatistics')
		    				->join('exhb_project','left')
		    				->on('exhbprojectstatistics.project_id','=','exhb_project.project_id')
		    				->where('exhb_project.exhibition_id','in',$exhb_ids)
		    				->where('exhbprojectstatistics.page_type','=',intval(3))
		    				->where('exhb_project.project_status','=',intval(1))
		    				->count_all();		    				
		    	$people_count_2 = ORM::factory('ExhbCoupon')
		    				->where('exhibition_id','in',$exhb_ids)
		    				->count_all();		    				
		    	$people_count = $people_count_1 + $people_count_2;
		    	$projects_1 = array();
		    	$pro1 = ORM::factory('ExhbProjectstatistics')
		    				->join('exhb_project','left')
		    				->on('exhbprojectstatistics.project_id','=','exhb_project.project_id')
		    				->where('exhb_project.exhibition_id','in',$exhb_ids)
		    				->where('exhbprojectstatistics.page_type','=',intval(3))
		    				->where('exhb_project.project_status','=',intval(1))
		    				->find_all()
		    				->as_array();
		    	if(count($pro1) > 0){
		    		foreach($pro1 as $v){
		    			$projects_1[] = $v->project_id;
		    		}		    		
		    	}		
		    	$projects_2 = array(); 
		    	$pro2 = ORM::factory('ExhbCoupon')
		    				->where('exhibition_id','in',$exhb_ids)
		    				->find_all()
		    				->as_array();
		    	if(count($pro2) > 0){
		    		foreach($pro2 as $v){
		    			$projects_2[] = $v->project_id;
		    		}		    		
		    	}
		    	$projects = array_unique(array_merge($projects_1,$projects_2));	
		    	$project_num = count($projects);					    	
		    	$str3 = "截至目前，已有<span class='rednum'>".$people_count."</span>人对展会中的<span class='rednum'>".$project_num."</span>个项目达成了意向签约；";
		    	$project_count = ORM::factory('ExhbProject')
		    					->where('exhibition_id','in',$exhb_ids)
		    					->where('project_status','=',intval(1))
		    					->count_all();
		    	$str6 = "截至目前，已有<span class='rednum'>".$project_count."</span>个项目加入本次展会；";
	    	}
	    	$res[] = $str3;
	    	$str4 = "";
	    	$om4 = DB::select('person_id','exhibition_id')
	    			->from('exhb_hongbao_fetch')
	    			->order_by('fetch_time','desc')
	    			->limit(1)
	    			->execute()
	    			->as_array();
	    	if($om4){    		
	    		$userperson = $client->getUserInfoById($om4[0]['person_id']);	
	    		$exhb_info = $exhb_service->exhbInfoById($om4[0]['exhibition_id']);
	    		$str4 = "<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>刚刚领取了价值<span class='rednum'>".(isset($exhb_info['exhibition_hongbao']) ? $exhb_info['exhibition_hongbao'] : '')."</span>元的开业红包；";
	    	}
	    	$res[] = $str4;	
	    	$str5 = "";	
	    	$om5 = DB::select('person_id','project_id')
	    			->from('exhb_coupon_fetch')
	    			->order_by('fetch_time','desc')
	    			->limit(1)
	    			->execute()
	    			->as_array();
	    	if($om5){
	    		$userperson = $client->getUserInfoById($om5[0]['person_id']);		    		
	    		$project_Info = $exhb_service->getProject($om5[0]['project_id']);
	    		$str5 = "<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>刚刚领取了价值<span class='rednum'>".(isset($project_Info['project_coupon']) ? $project_Info['project_coupon'] : '')."</span>元的<a class='blue' target='_blank' href='".urlbuilder::exhbProject($om5[0]['project_id'])."'>".(isset($project_Info['project_brand_name']) ? $project_Info['project_brand_name'] : '')."</a>项目优惠券；";
	    	}
	    	$res[] = $str5;	
	    	$res[] = $str6;
	    	$str7 = "";	
	    	$count = ORM::factory('ExhbProjectstatistics')->count_all();
	    	$str7 = "截至目前，已有<span class='rednum'>".$count."</span>位用户参加了本次展会；";
	    	$res[] = $str7;
	    	$cache->set('exhb_duochangbobao', $res,360);
    	}    	
//    	echo "<pre>";print_r($res);exit;
    	return $res;
    }
    
     /**
     * 单场展会、栏目页动态数据播报
     * @author 郁政
     */
    public function getDanChangBoBao($exhb_id){
    	$res = array();
    	$cache = Cache::instance('memcache');
    	$data = $cache->get('exhb_danchangbobao'.$exhb_id);
    	$client = Service_Sso_Client::instance();
    	$exhb_service = new Service_User_Company_Exhb();    
    	if($data){
    		$res = $data;
    	}else{  
	    	$str1 = "";
	    	$om1 = DB::select('exhb_project_statistics.project_id','exhb_project_statistics.user_id','exhb_project.project_brand_name')
	    			->from('exhb_project_statistics')
	    			->join('exhb_project','left')
	    			->on('exhb_project_statistics.project_id','=','exhb_project.project_id') 
	    			->where('exhb_project_statistics.page_type','=',intval(2))
	    			->order_by('exhb_project_statistics.insert_time','desc')
	    			->limit(1)
	    			->execute()
	    			->as_array();	
	    	if($om1){	    		
	    		$userperson = $client->getUserInfoById($om1[0]['user_id']);	    		
	    		$str1 = "<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>刚刚对<a class='blue' target='_blank' href='".urlbuilder::exhbProject($om1[0]['project_id'])."'>".$om1[0]['project_brand_name']."</a>项目进行了在线沟通；";
	    	}	
	    	$res[] = $str1;
	    	$str2 = "";
	    	$om2 = DB::select('exhb_project_statistics.project_id','exhb_project_statistics.user_id','exhb_project.project_brand_name')
	    			->from('exhb_project_statistics')
	    			->join('exhb_project','left')
	    			->on('exhb_project_statistics.project_id','=','exhb_project.project_id') 
	    			->where('exhb_project_statistics.page_type','=',intval(3))
	    			->order_by('exhb_project_statistics.insert_time','desc')
	    			->limit(1)
	    			->execute()
	    			->as_array();	
	    	if($om2){
	    		$userperson = $client->getUserInfoById($om2[0]['user_id']);	
	    		$str2 = "<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>刚刚对<a class='blue' target='_blank' href='".urlbuilder::exhbProject($om2[0]['project_id'])."'>".$om2[0]['project_brand_name']."</a>项目进行了意向咨询；";
	    	}			
	    	$res[] = $str2;
	    	$str3 = "";
	    	$people_count_1 = ORM::factory('ExhbProjectstatistics')
		    				->join('exhb_project','left')
		    				->on('exhbprojectstatistics.project_id','=','exhb_project.project_id')
		    				->where('exhb_project.exhibition_id','=',$exhb_id)
		    				->where('exhbprojectstatistics.page_type','=',intval(3))
		    				->where('exhb_project.project_status','=',intval(1))
		    				->count_all();
		    $people_count_2 = ORM::factory('ExhbCoupon')
		    				->where('exhibition_id','=',$exhb_id)
		    				->count_all();
		    $people_count = $people_count_1 + $people_count_2;
		    $str3 = "截至目前，已有<span class='rednum'>".$people_count."</span>人对本期展会中的项目达成了意向签约；";
		    $res[] = $str3;
		    $str4 = "";
	    	$om4 = DB::select('person_id','exhibition_id')
	    			->from('exhb_hongbao_fetch')
	    			->where('exhibition_id','=',$exhb_id)
	    			->order_by('fetch_time','desc')
	    			->limit(1)
	    			->execute()
	    			->as_array();
	    	if($om4){    		
	    		$userperson = $client->getUserInfoById($om4[0]['person_id']);	
	    		$exhb_info = $exhb_service->exhbInfoById($om4[0]['exhibition_id']);
	    		$str4 = "<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>刚刚领取了价值<span class='rednum'>".(isset($exhb_info['exhibition_hongbao']) ? $exhb_info['exhibition_hongbao'] : '')."</span>元的开业红包；";
	    	}
	    	$res[] = $str4;	
	    	$str5 = "";	
	    	$om5 = DB::select('person_id','project_id')
	    			->from('exhb_coupon_fetch')
	    			->where('exhibition_id','=',$exhb_id)
	    			->order_by('fetch_time','desc')
	    			->limit(1)
	    			->execute()
	    			->as_array();
	    	if($om5){
	    		$userperson = $client->getUserInfoById($om5[0]['person_id']);
	    		$project_Info = $exhb_service->getProject($om5[0]['project_id']);
	    		$str5 = "<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>刚刚领取了价值<span class='rednum'>".(isset($project_Info['project_coupon']) ? $project_Info['project_coupon'] : '')."</span>元的<a class='blue' target='_blank' href='".urlbuilder::exhbProject($om5[0]['project_id'])."'>".(isset($project_Info['project_brand_name']) ? $project_Info['project_brand_name'] : '')."</a>项目优惠券；";
	    	}
	    	$res[] = $str5;
	    	$cache->set('exhb_danchangbobao'.$exhb_id, $res,360);
	    }    		
    	return $res;
    }
    
    /**
     * 项目详情页动态数据播报
     * @author 郁政
     */
    public function getXiangQingBoBao($project_id){
    	$res = array();
    	$cache = Cache::instance('memcache');
    	$data = $cache->get('exhb_xiangqingbobao'.$project_id);
    	$client = Service_Sso_Client::instance();
    	$exhb_service = new Service_User_Company_Exhb();   
    	if($data){
    		$res = $data;
    	}else{ 
	    	$str1 = "";
	    	$om1 = DB::select('exhb_project_statistics.project_id','exhb_project_statistics.user_id','exhb_project.project_brand_name')
	    			->from('exhb_project_statistics')
	    			->join('exhb_project','left')
	    			->on('exhb_project_statistics.project_id','=','exhb_project.project_id') 
	    			->where('exhb_project_statistics.page_type','=',intval(2))
	    			->where('exhb_project_statistics.project_id','=',$project_id)
	    			->order_by('exhb_project_statistics.insert_time','desc')
	    			->limit(1)
	    			->execute()
	    			->as_array();	
	    	if($om1){
	    		$userperson = $client->getUserInfoById($om1[0]['user_id']);
	    		$str1 = "<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>刚刚对本项目进行了在线沟通；";
	    	}	
	    	$res[] = $str1;
	    	$str2 = "";
	    	$om2 = DB::select('exhb_project_statistics.project_id','exhb_project_statistics.user_id','exhb_project.project_brand_name')
	    			->from('exhb_project_statistics')
	    			->join('exhb_project','left')
	    			->on('exhb_project_statistics.project_id','=','exhb_project.project_id') 
	    			->where('exhb_project_statistics.page_type','=',intval(3))
	    			->where('exhb_project_statistics.project_id','=',$project_id)
	    			->order_by('exhb_project_statistics.insert_time','desc')
	    			->limit(1)
	    			->execute()
	    			->as_array();	
	    	if($om2){    		
	    		$userperson = $client->getUserInfoById($om2[0]['user_id']);
	    		$str2 = "<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>刚刚对本项目进行了意向咨询；";
	    	}			
	    	$res[] = $str2;
	    	$str3 = "";	
	    	$om3 = DB::select('person_id','project_id')
	    			->from('exhb_coupon_fetch')
	    			->where('project_id','=',$project_id)
	    			->order_by('fetch_time','desc')
	    			->limit(1)
	    			->execute()
	    			->as_array();
	    	if($om3){
	    		$userperson = $client->getUserInfoById($om3[0]['person_id']);
	    		$project_Info = $exhb_service->getProject($om3[0]['project_id']);
	    		$str3 = "<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>刚刚领取了本项目价值<span class='rednum'>".(isset($project_Info['project_coupon']) ? $project_Info['project_coupon'] : '')."</span>元的项目优惠券；";
	    	}
	    	$res[] = $str3;	
	    	$str4 = "";
	    	$people_count = ORM::factory('ExhbProjectstatistics')
		    				->join('exhb_project','left')
		    				->on('exhbprojectstatistics.project_id','=','exhb_project.project_id')
		    				->where('exhb_project.project_id','=',$project_id)
		    				->where('exhbprojectstatistics.page_type','=',intval(3))
		    				->where('exhb_project.project_status','=',intval(1))
		    				->count_all();
		    $str4 = "截至目前，已有<span class='rednum'>".$people_count."</span>人对本项目达成了意向签约；";
		    $res[] = $str4;
		    $cache->set('exhb_xiangqingbobao'.$project_id, $res,360);
    	}    	
    	return $res;
    }
    
    /**
     * 预告页动态数据播报
     * @author 郁政
     */
    public function getYuGaoBoBao($exhb_id){
    	$res = array();
    	$cache = Cache::instance('memcache');
    	$data = $cache->get('exhb_yugaobobao'.$exhb_id);
    	$client = Service_Sso_Client::instance();    	
	    $exhb_service = new Service_User_Company_Exhb();   
    	if($data){
    		$res = $data;
    	}else{    		
	    	$str1 = "";
	    	$om1 = ORM::factory('ExhbProject')
	    		->where('exhibition_id','=',$exhb_id)
	    		->where('project_status','=',intval(1))
	    		->order_by('project_addtime','desc')
	    		->find();
	    	if(isset($om1->project_id) && $om1->project_id){
	    		$str1 = "<a class='blue' target='_blank' href='".urlbuilder::exhbProject($om1->project_id)."'>".$om1->project_brand_name."</a>项目刚刚加入了本展会；";
	    	}
	    	$res[] = $str1;
	    	$str2 = "";
	    	$om2 = DB::select('person_id','exhibition_id')
	    			->from('exhb_hongbao_fetch')
	    			->where('exhibition_id','=',$exhb_id)
	    			->order_by('fetch_time','desc')
	    			->limit(1)
	    			->execute()
	    			->as_array();
	    	if($om2){    		
	    		$userperson = $client->getUserInfoById($om2[0]['person_id']);
	    		$exhb_info = $exhb_service->exhbInfoById($om2[0]['exhibition_id']);
	    		$str2 = "<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>刚刚领取了价值<span class='rednum'>".(isset($exhb_info['exhibition_hongbao']) ? $exhb_info['exhibition_hongbao'] : '')."</span>元的开业红包；";
	    	}
	    	$res[] = $str2;
	    	$cache->set('exhb_yugaobobao'.$exhb_id, $res,360);
    	}    		
    	return $res;
    }
    
    /**
     * 已过期页动态数据播报
     * @author 郁政
     */
    public function getLiShiBoBao($exhb_id){
    	$res = array();
    	$cache = Cache::instance('memcache');
    	$data = $cache->get('exhb_lishibobao'.$exhb_id);
    	$client = Service_Sso_Client::instance();
    	$exhb_service = new Service_User_Company_Exhb();  
    	$data = array();
    	if($data){
    		$res = $data;
    	}else{    		
	    	 
	    	$str1 = "";	
	    	$num1 = 0;
	    	$offset1 = 0;
	    	$count1 = 0;
	    	$count1 = ORM::factory('ExhbCoupon')
	    			->where('exhibition_id','=',$exhb_id)
	    			->count_all();
	    	$offset1 = $count1;
	    	$num1 = $offset1 ? rand(0, $offset1-1) : 0;
	    	$om1 = DB::select('person_id','project_id')
	    			->from('exhb_coupon_fetch')
	    			->where('exhibition_id','=',$exhb_id)
	    			->order_by('fetch_time','desc')
	    			->limit(1)
	    			->offset($num1)
	    			->execute()
	    			->as_array();
	    	if($om1){
	    		$userperson = $client->getUserInfoById($om1[0]['person_id']);
	    		$project_Info = $exhb_service->getProject($om1[0]['project_id']);
	    		$str1 = "恭喜<span class='blue'>".($userperson->user_name ? $userperson->user_name : '佚名')."</span>成功签约了<span class='blue'>".(isset($project_Info['project_brand_name']) ? $project_Info['project_brand_name'] : '')."</span>项目；";
	    	}
	    	$res[] = $str1;	
	    	$str2 = "";	
	    	$num2 = 0;
	    	$offset2 = 0;
	    	$count2 = 0;
	    	$arr2 = ORM::factory('ExhbCoupon')
	    			->where('exhibition_id','=',$exhb_id)	    			
	    			->find_all()
	    			->as_array();
	    	$sucArr = array();
	    	if(count($arr2) > 0){
	    		foreach($arr2 as $v){
	    			$sucArr[] = $v->project_id;
	    		}	    		
	    	}
	    	$count2 = count(array_unique($sucArr));
	    	$offset2 = $count2;	    	
	    	$num2 = $offset2 ? rand(0, $offset2-1) : 0;
	    	$om2 = DB::select('project_id','person_id',array(DB::expr('COUNT(project_id)'),'amount'))
	    			->from('exhb_coupon_fetch')
	    			->where('exhibition_id','=',$exhb_id)
	    			->group_by('project_id')
	    			->order_by('amount')
	    			->limit(1)    
	    			->offset($num2)			
	    			->execute()
	    			->as_array();
	    	if($om2){	    		
	    		$project_Info = $exhb_service->getProject($om2[0]['project_id']);
	    		$str2 = "恭喜<span class='blue'>".(isset($project_Info['project_brand_name']) ? $project_Info['project_brand_name'] : '')."</span>项目成功签约达<span class='rednum'>".$om2[0]['amount']."</span>人次；";
	    	}
	    	$res[] = $str2;	
	    	$cache->set('exhb_lishibobao'.$exhb_id, $res,360);
    	}    	
    	return $res;
    }

    /**
     * 
     * @param type $project_id
     * @param type $param
     * @param type $count
     * @return boolean
     * @author 我连author都不想写 我是鸡爷  我为自己代言
     */
    public function addCount($project_id, $param, $count) {
        $project_id = intval($project_id);
        if(!$param || !$count) 
                return false;
        $exhbPro = ORM::factory('ExhbProject',$project_id);  
    	if($exhbPro->loaded()){
	    	$exhbPro->$param = $count;
	    	$res = $exhbPro->update(); 
        }
        return $res;
    }
    
    /**
     * 修改优惠劵数量
     * @author 郁政
     */
    public function updateCouponNum($project_id,$add){
    	if($add){
	    	$om = ORM::factory('ExhbProject')->where('project_id','=',$project_id)->find();
	    	if($om->loaded()){
	    		$om->coupon_num = $om->coupon_num + intval($add);
	    		$om->update();
	    	}
	    	return true;
    	}
    	return false;
    }
    
    /**
     * 成功签约项目数
     * @author 郁政
     */
    public function getSucProjectNum($exhb_id){
    	$num = 0;
    	$res = array();
    	$arr1 = array();
    	$arr2 = array();
    	$om1 = ORM::factory('ExhbCoupon')
    		->where('exhibition_id','=',$exhb_id)
    		->group_by('project_id')
    		->find_all()
    		->as_array();
    	if(count($om1) > 0){
    		foreach($om1 as $v){
    			$arr1[] = $v->project_id;
    		}
    	}
    	$om2 = ORM::factory('ExhbProjectstatistics')
    		->join('exhb_project', 'left')
    		->on('exhbprojectstatistics.project_id','=','exhb_project.project_id')
    		->where('exhb_project.exhibition_id','=',$exhb_id)
    		->where('exhbprojectstatistics.page_type','=',intval(3))
    		->find_all()
    		->as_array();
    	if(count($om2) > 0){
    		foreach($om2 as $v){
    			$arr2[] = $v->project_id;
    		}
    	}
    	$res = array_unique(array_merge($arr1,$arr2));
    	$num = count($res);
    	return $num;
    }
    
    /**
     * 成功签约人次
     * @author 郁政
     */
    public function getSucPeopleNum($exhb_id){
    	$num = 0;
    	$num1 = 0;
    	$num2 = 0;
    	$num1 = ORM::factory('ExhbCoupon')
    		->where('exhibition_id','=',$exhb_id)    		
    		->count_all();
    	$num2 = ORM::factory('ExhbProjectstatistics')
    		->join('exhb_project', 'left')
    		->on('exhbprojectstatistics.project_id','=','exhb_project.project_id')
    		->where('exhb_project.exhibition_id','=',$exhb_id)
    		->where('exhbprojectstatistics.page_type','=',intval(3))
    		->count_all();
    	$num = $num1 + $num2;
    	return $num;
    }
    
	/**
     * 意向加盟人数
     * @author 郁政
     */
    public function getYiXiangPeopleNum($project_id){
    	$count = 0;
    	$count1 = 0;
    	$count2 = 0;
    	$count1 = ORM::factory('ExhbCoupon')
    			->where('project_id','=',$project_id)
    			->count_all();
    	$count2 = ORM::factory('ExhbProjectstatistics')
    			->where('project_id','=',$project_id)
    			->where('page_type','=',intval(3))
    			->count_all();
    	$count = $count1 + $count2;
    	return $count;
    }
    
    /**
     *客服唯一性
     */
    public function CheckKefu($str_name){
    	$int = 2;
    	$count = ORM::factory("Customerinfo")->where("customer_account","=",trim($str_name))->count_all();
    	if($count == 0){
    		$int = 1;
    	}
    	return $int;
    }
    public function daoruExhbPro($exhbId, $catalog_id = 0, $page = 0) {
        $limit = 100;
        $ormList = ORM::factory('Project')->where('com_id', '>', 0)->where('project_status', '>' , '1')->limit($limit)->offset($page)->find_all();
        $list = array();
        foreach($ormList as $val) {
                $orm = ORM::factory('ExhbProject');
                $orm->outside_id = $val->project_id;
                $orm->project_source = 1;
                $orm->project_brand_name = $val->project_brand_name;
                $orm->catalog_id = $catalog_id;
                $orm->exhibition_id = $exhbId;
                $orm->com_id = $val->com_id;
                $orm->project_status = 1;
                $orm->project_amount_type = $val->project_amount_type;
                $orm->project_coupon = 100;
                $orm->coupon_num = 100;
                $orm->project_addtime = time();
                $orm->project_updatetime = time();
                $orm->project_passtime = time();
                $orm->project_logo = $val->project_logo;
                $suc = $orm->create();
                echo 'add '.$suc->project_id.'project<br/>';
                $ormInList = ORM::factory('Projectindustry')->where('project_id', '=', $val->project_id)->find_all();
                foreach($ormInList as $valT) {
                    $ormIn = ORM::factory('ExhbProjectindustry');
                    $ormIn->project_id = $suc->project_id;
                    $ormIn->industry_id = $valT->industry_id;
                    $ormIn->status = 2;
                    $suc = $ormIn->create();
                    echo 'add '.$suc->project_id.'industry<br/>';
                }
                
                $ormInList = ORM::factory('Projectarea')->where('project_id', '=', $val->project_id)->find_all();
                foreach($ormInList as $valT) {
                    $ormIn = ORM::factory('ExhbProjectarea');
                    $ormIn->project_id = $suc->project_id;
                    $ormIn->area_id = $valT->area_id;
                    $ormIn->pro_id = $valT->pro_id;
                    $ormIn->status = 2;
                    $suc = $ormIn->create();
                    echo 'add '.$suc->project_id.'area<br/>';
                }
                
                $ormInList = ORM::factory('Projectmodel')->where('project_id', '=', $val->project_id)->find_all();
                foreach($ormInList as $valT) {
                    $ormIn = ORM::factory('ExhbProjectmodel');
                    $ormIn->project_id = $suc->project_id;
                    $ormIn->type_id = $valT->type_id;
                    $ormIn->status = 2;
                    $suc = $ormIn->create();
                    echo 'add '.$suc->project_id.'model<br/>';
                }
                
                $ormInList = ORM::factory('Projectcerts')->where('project_id', '=', $val->project_id)->find_all();
                foreach($ormInList as $valT) {
                    $ormIn = ORM::factory('ExhbProjectcerts');
                    $ormIn->project_id = $suc->project_id;
                    $ormIn->project_type = $valT->project_type;
                    $ormIn->project_img = $valT->project_img;
                    $ormIn->project_imgname = $valT->project_imgname;
                    $ormIn->project_order = $valT->project_order;
                    $ormIn->project_addtime = time();
                    $suc = $ormIn->create();
                    echo 'add '.$suc->project_id.'certs<br/>';
                }
        }
    }
}

?>