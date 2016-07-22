<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 网络展会
 * @author 郁政
 */
class Controller_User_Person_Exhb extends Controller_User_Person_Template{
	/**
	 * 我的红包
	 * @author 郁政
	 */
	public function action_myHongBao(){
		$content = View::factory("user/person/myhongbao");
    	$this->content->rightcontent = $content;
    	#实例化
        $service = new Service_User_Person_Exhb();
        //获取登录user_id
        $user_id = $this->userid();        
        if($this->request->method() == HTTP_Request::GET) {  
        	$get = Arr::map("HTML::chars", $this->request->query());
        	$type = Arr::get($get, 'type',1);
        	$name = Arr::get($get, 'name','');
        	$status = Arr::get($get, 'status',0);
        	if($type == 1 && $name == '' && $status == 0){
        		$cond = array();
        	}else{
        		$cond = array(
	        		'type' => $type,
	        		'name' => $name,
	        		'status' => $status
	        	);
        	}        	
        	$res = $service->getHongBaoList($user_id,1,$cond);
        }      
        //echo "<pre>";print_r($res);exit;        
        $content->forms = $res['list'];
        $content->page = $res['page'];
        $content->type = $type;
        $content->name = $name;
        $content->status = $status;
	}
}

?>