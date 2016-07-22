<?php defined('SYSPATH') or die('No direct script access.');
/**
 *  活动前期宣传
 * @author 郁政
 */
class Controller_Platform_JiangPin extends Controller_Platform_Template{
	/**
 	* 显示第三期宣传页面
 	* @author 郁政
 	*/
	public function action_showJiangPin(){
		$content = View::factory('platform/huodong/jiangpin');
		$this->content->maincontent = $content;
		$service = new Service_Platform_JiangPin();
		//获取第二期中实物奖的名单
		$big_prize_list_2 = $service->getBigPrizeBefore(2);
		//获取第一期中实物奖的名单
		$big_prize_list_1 = $service->getBigPrizeBefore(1);
		$content->big_prize_list_2 = $big_prize_list_2;
		$content->big_prize_list_1 = $big_prize_list_1;
	}
	
	/**
 	* 显示第四期宣传页面
 	* @author 郁政
 	*/
	public function action_showJiangPin4(){
		$content = View::factory('platform/huodong/jiangpin4');
		$this->content->maincontent = $content;
		$query = Arr::map("HTML::chars", $this->request->query());
//		if(arr::get($query, 'inviter_type') && arr::get($query, 'inviter_user_id')) {
//      		Cookie::set('cookie_user_inviter_id', arr::get($query, 'inviter_user_id'), time()+86400*60);
//      		Cookie::set('cookie_user_inviter_type', arr::get($query, 'inviter_type'), time()+86400*60);
//      	}		
		#获取用户user_id
	    $user_id = Cookie::get("user_id")?Cookie::get("user_id"):0;
		//您可能喜欢的项目
		$service_ps = new Service_Platform_Search();
		$service_pg = new Service_Platform_ProjectGuide();
		$guess_like_list = array();
		$arr_data = array();
		$ip = ip2long(Request::$client_ip);
		$arr_data = $service_ps->_getVistedLog($user_id, $ip, 1);
		if(count($arr_data) == 0){
			$guess_like_list = $service_pg->getNewProjectListNByRandom(6,2,2);
		}else{
			$return_data = array();
			$return_data = isset($arr_data[0]) ? $arr_data[0] : array();
			$guess_like_list = $service_ps->_getOtherProject($return_data,$ip,6);
			$guess_like_list = $service_ps->_getProjectInfoByarr($guess_like_list);			
		}	
		$content->guess_like_list = $guess_like_list;
		$this->template->title = "一句话商机速配平台祝全体网友2014年马年快乐，马年有好礼，第四期抽奖活动预告";
		$this->template->keywords = "一句话商机速配平台,马年有好礼";
		$this->template->description = "一句话商机速配平台祝全体网友2014年马年快乐，马年有好礼，第四期抽奖活动预告，第四期抽奖活动我们为您准备了各种心动的大奖，请随时关注我们的活动吧。";
	}
}
?>