<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 友情链接 Service
 * @author 郁政
 */
class Service_Platform_FriendLink{
	/**
     * 获取对应页面的友情链接
     * @author 郁政
     */
	public function getFriendLinkList($name){		
		$arr = common::getPageName();
		$page_id = 0;
		$page_id = isset($arr[$name]) ? $arr[$name] : 0;
		$res = array();
		$link_id = array();	
		$pageLinks = ORM::factory('PageLinks')->where('page_id','=',$page_id)->find_all()->as_array();
		if(count($pageLinks) > 0){
			foreach($pageLinks as $v){
				$link_id[] = $v->link_id; 
			}
		}
		if(count($link_id) > 0){			
			$friend = ORM::factory('FriendLink')->where('id','in',$link_id)->order_by('order','asc')->find_all()->as_array();
			$res = $friend;			
		}
		return $res;
	}
}
?>
