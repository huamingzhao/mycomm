<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 好项目你评选活动service
 * @author 郁政
 */
class Service_Platform_HuoDong5{
	/**
	 * 最佳创业列表
	 * @author 郁政
	 */
	public function getZuiJiaChuangYeList($limit){
		$res = array();
		$service = new Service_Platform_Search();
		$project_ids = common::getZuiJiaChuangYePro();
		$res = array_rand($project_ids,$limit);
		if($res){
			$res = $service->_getProjectInfoByarr($res);
		}
		return $res;
	}
	
	/**
	 * 创业首选列表
	 * @author 郁政
	 */
	public function getChuangYeShouXuanList($limit){
		$res = array();
		$service = new Service_Platform_Search();
		$project_ids = common::getChuangYeShouXuanPro();
		$res = array_rand($project_ids,$limit);
		if($res){
			$res = $service->_getProjectInfoByarr($res);
		}
		return $res;
	}
	
	/**
	 * 最新商机列表
	 * @author 郁政
	 */
	public function getZuiXinShangJiList($limit){
		$res = array();
		$service = new Service_Platform_Search();
		$project_ids = common::getZuiXinShangJiPro();
		$res = array_rand($project_ids,$limit);
		if($res){
			$res = $service->_getProjectInfoByarr($res);
		}
		return $res;
	}
	
	/**
	 * 精品推荐列表
	 * @author 郁政
	 */
	public function getJingPinTuiJianList($limit){
		$res = array();
		$service = new Service_Platform_Search();
		$project_ids = common::getJingPinTuiJianPro();
		$res = array_rand($project_ids,$limit);
		if($res){
			$res = $service->_getProjectInfoByarr($res);
		}
		return $res;
	}
	
	/**
	 * 知名品牌列表
	 * @author 郁政
	 */
	public function getZhiMingPinPaiList($limit){
		$res = array();
		$service = new Service_Platform_Search();
		$project_ids = common::getZhiMingPinPaiPro();
		$res = array_rand($project_ids,$limit);
		if($res){
			$res = $service->_getProjectInfoByarr($res);
		}
		return $res;
	}
	
	/**
	 * 商家推荐列表
	 * @author 郁政
	 */
	public function getShangJiaTuiJianList($limit){
		$res = array();
		$service = new Service_Platform_Search();
		$project_ids = common::getShangJiaTuiJianPro();
		$res = array_rand($project_ids,$limit);
		if($res){
			$res = $service->_getProjectInfoByarr($res);
		}
		return $res;
	}
}
?>