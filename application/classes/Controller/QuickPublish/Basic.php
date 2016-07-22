<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_QuickPublish_Basic extends Controller_Template{
    public function before(){
        $isLogin = $this->isLogins();
        $this->template = '/quickPublish/template';
        
        parent::before();
        $this->template->isLogin = $isLogin;
    }
    
    public function action_index() {
        $content = View::factory('quickPublish/index');
        $this->template->content = $content;
       	$obj_service =  new Service_QuickPublish_ProjectComplaint();
       	$arr_data = $obj_service->IndexList();
       //echo "<pre>"; print_r($arr_data);exit;
       	$memcache = Cache::instance ('memcache');
       	$friend_link = $memcache->get('friend_cache_index');
       	if(empty($friend_link)){
       		$f_service = new Service_Platform_FriendLink();
       		$friend_link = $f_service->getFriendLinkList('index');
       		$memcache->set('friend_cache_index', $friend_link,604800);
       	}
       	$this->template->friend_link = $friend_link;
       	$content->arr_data = $arr_data;
       	$this->template->title = "【一句话生意网】招商加盟_创业项目商机搜索、商机速配网站";
       	$this->template->keywords = "一句话生意网，商机速配，一句话商机网，商机搜索,一句话";
       	$this->template->description = "一句话生意网是生意街旗下专注于投资者服务的平台，一句话商机速配咨询电话【400-1015-908】,含一句话商机搜索、商机速配、创业加盟项目、品牌代理、创业指导、开店创业、品牌加盟等。商机搜索、寻找创业项目、找加盟商机、好的投资项目上一句话商机网，最专业的招商加盟、创业投资速配网。";
    }
}