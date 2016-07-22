<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * TODO cookie的时间和日期保存为配置形式
 * 首页搜索和精准搜索服务
 * @author 龚湧
 *
 */
class Service_QuickPublish_Search {
    /**
     * 快速搜索
     * @author stone shi 
     * @param string $word
     * @return array
     */
    public function getWordSearch($word) {
        $result = quicksearchservice::getInstance()->searchWordBySolr($word);
        return $result;
    }
    /**
     * 快速搜索 条件
     * @author stone shi
     */
    public function getCondSearch($cond) {
        $result = quicksearchservice::getInstance()->searchCondBySolr($cond);
        return $result;
    }
    /**
     * 搜索项目 没有搜索到的推荐
     * @author stone shi
     */
    public function getListOrderPv() {
        $limit = 30;
        $listOrm = ORM::factory('Quickproject')
                ->where('project_status', '=', 2)
                ->order_by('project_updatetime', 'DESC') 
                ->limit($limit)->offset(0)
                ->find_all();
        $list['list'] = array();
        foreach($listOrm as $val) {
            $list['list'][] = $this->pushQuickProject($val);
        }
        return $list;
    }
    /**
     * 搜索项目 获取全部 首页
     * @author stone shi
     */
    public function getListOrderTime() {
        $limit = 30;
        $count = ORM::factory('Quickproject')
                ->where('project_status', '=', 2)
                ->count_all();
        $page = Pagination::factory(array(
	                            'total_items' => $count,
	                            'items_per_page' => 30,
	                            'view' => 'pagination/Simple',
	                            'current_page' => array('source' => 'quicksearch', 'key' => 'page')
	                ));
        $listOrm = ORM::factory('Quickproject')
                ->where('project_status', '=', 2)
                ->order_by('project_updatetime', 'DESC')
                ->limit($page->items_per_page)->offset($page->offset) 
                ->find_all();
        $list['list'] = array();
        $list['page'] = $page;
        foreach($listOrm as $val) {
            $list['list'][] = $this->pushQuickProject($val);
        }
        return $list;
    }
    
    
    /**
     *  搜索结果
     * @author stone shi
     */
    public function getSearchPro($match, $matchCount, $type = 1) {
                //最多500条
                if ($matchCount > 500) {
                        $matchCount = 500;
                }
                //分页条件
                if($type == 1) {
	                $page = Pagination::factory(array(
	                            'total_items' => $matchCount,
	                            'items_per_page' => 30,
	                            'view' => 'pagination/Simple',
	                            'current_page' => array('source' => 'quickxiangdao', 'key' => 'page')
	                ));	          
                }else{
                	$page = Pagination::factory(array(
	                            'total_items' => $matchCount,
	                            'items_per_page' => 30,
	                            'view' => 'pagination/Simple',
	                            'current_page' => array('source' => 'quicksearch', 'key' => 'page')
	                ));
                }
                $return['page'] = $page;
                $return['list'] = array();
                $ids = array();
                if($match) {
                   foreach($match as $key => $val) {
                       $ids[] = $key;
                   }
                }
                if($ids) {
                    if($type) {
                        $listOrm = ORM::factory('Quickproject')
                        ->where('project_status', '=', 2)
                        ->where('project_id', 'in', $ids)
                        ->order_by('project_updatetime', 'DESC')     
                        ->find_all();
                    }else {
                        $listOrm = ORM::factory('Quickproject')
                        ->where('project_status', '=', 2)
                        ->where('project_id', 'in', $ids)     
                        ->find_all();
                    }
                }
        $return['list'] = array();
        foreach($listOrm as $val) {
            $return['list'][] = $this->pushQuickProject($val, $match);
        }
        return $return;
    }
    /**
	 * 快速发布项目列表所需数据
	 * @author 郁政
	 *
	 */
	public function pushQuickProject($val, $match = array()){
                $project_id = $val->project_id;
                $updateTime = $val->project_updatetime ? $val->project_updatetime : $val->project_addtime;
		$mobile_id = $val->mobile_id;
                $com_id = $val->com_id;
                $res = array();
		$service = new Service_QuickPublish_ProjectComplaint();	
                $proService = new Service_QuickPublish_Company();
		$res = $val->as_array();	
		//取得行业	
		$res['industry_name'] = $service->getIndustryNameById($project_id);
		//取得招商地区
		$res['area'] = $service->getAreaIdName($project_id);
                 //更新时间
                $res['upTime'] = $this->jishuanTime($updateTime);
                $res['imgStatus'] = $service->getProjectImagesCount($project_id);
                $res['match'] = arr::get($match, $project_id, array());
                $res['userStatsus'] = $this->checkUser($mobile_id);
                $res['com_name'] = $com_id ? arr::get($proService->getCompanyInfo($com_id), 'com_name') : '';
                
		return $res;
	}
        
        public function checkUser($mobile_id) {
            if(!$mobile_id) return false;
            $model = ORM::factory("MobileAccount", $mobile_id);
            if(!$model->user_id) return false;
            $client = Service_Sso_Client::instance();
            $userinfo = $client->getUserInfoById($model->user_id);
            $type = isset($userinfo->user_type) ? $userinfo->user_type : 1;
            if($type == 1) {
                $service = new Service_User_Company_User();
                $count = $service->getCompanyAuthCount($model->user_id);
                return !$count ? 0 : 1;
            }else {
                $service = new Service_User();
                $userinfo = ORM::factory('User', $model->user_id);
	        //身份证验证是否通过审核v
        	$count = $userinfo->user_person->per_auth_status;
                return $count != 2 ? 0 : 2; 
            }
        }
        public function jishuanTime($updateTime,$bool = false) {
            $updateTime = intval($updateTime);
            if(!$updateTime) return $updateTime;
            $time = time();
          	if(($time - $updateTime) <= 60*60 ) {
                $return = floor(($time - $updateTime)/60);
                $return = $return ? $return : 1;
                return $return.'分钟前';
            }elseif(($time - $updateTime) <= 24*60*60) {
                $return = floor(($time - $updateTime)/(60*60));
                $return = $return ? $return : 1;
                return $return.'小时前';
            }elseif(($time - $updateTime) <= 24*60*60*3) {
                $return = floor(($time - $updateTime)/(60*60*24));
                $return = $return ? $return : 1;
                return $return.'天前';
            }else{ 
            	if($bool == true){
            		$str = "";
            		$str = date('n', $updateTime)."月".date('d', $updateTime)."日";
            		return $str;
            	}else{
            		return date('m-d', $updateTime);
            	}
                
            }
        }
}