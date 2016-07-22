<?php defined('SYSPATH') OR die('No direct script access.');


class Controller_Business_Basic extends Controller_Template{
    public function before(){
        $isLogin = $this->isLogins();
        $this->template = '/business/template';
        parent::before();
        $this->template->isLogin = $isLogin;
        $is_null='';
        $result=null;
        $login_token = cookie::get ( 'authautologin' );//'b8ed9f810f2d526cfe2788f42fff2a213bd229d6c711940a23d1745072f1730f';//
  		if(!empty($login_token)) {  
  			$result=$this->get_user_info($login_token);
  		}
  		$get = Arr::map("HTML::chars", $this->request->query());
  		$word = secure::secureInput(secure::secureUTF(arr::get($get, 'w', '')));
  		$this->template->search_word=$word==false ? '' :$word;
        $this->template->login_user_id=isset($result['user_id']) ? $result['user_id'] : 0;
        $this->template->login_user_name=isset($result['user_name']) ? $result['user_name'] : $is_null;
        $this->template->login_user_type=isset($result['user_type']) ? $result['user_type'] : 2; //默认，个人用户
        $this->template->login_user_photo=isset($result['user_photo']) ? $result['user_photo'] : $is_null;  
    }
    
    /**
     * 登录用户通过token获取信息
     * @param unknown_type $login_token：登录用户的token
     */
    public function get_user_info($login_token)
    {
    	$config_obj = Kohana::$config->load('cache_name');
    	$redis = Cache::instance('redis');
    	
    	$cache_key=sprintf($config_obj->login_user_info_by_token,$login_token);
    	$result=$redis->get($cache_key);
    	if(empty($result))
    	{
           $user_result=Service_Sso_Client::instance()->getUserInfo($login_token);
           if($user_result)
           {
	           	$user_name='';
	           	if(!empty($user_result->user_name)) $user_name=$user_result->user_name;
	           	if(empty($user_result->user_name) && !empty($user_result->email)) $user_name=$user_result->email;
	           	if(empty($user_result->user_name) && empty($user_result->email) && !empty($user_result->mobile)) $user_name=$user_result->mobile;
	           	if(empty($user_result->user_name) && empty($user_result->email) && empty($user_result->mobile)) $user_name='尊敬的会员您好';
	           	$result['user_id']=$user_result->id;
	           	$result['user_name']=$user_name;
	           	$result['user_type']=$user_result->user_type;
	           	$result['user_photo']=$user_result->user_portrait ? URL::imgurl($user_result->user_portrait) :'';
           }
    	   if($result) $redis->set($cache_key,$result,$config_obj->five_minutes_time);
		}
		return $result;
    	
    }
    
    
    /* 测试：有没有获取到用户数据  */
    public function action_test()
    {
    	echo 1;
    	$result=null;
    	$login_token = cookie::get ( 'authautologin' );//'b8ed9f810f2d526cfe2788f42fff2a213bd229d6c711940a23d1745072f1730f';//
  		if(!empty($login_token)) {  
  			$result=$this->get_user_info($login_token);
  		}
  		print_r($result);
    }
}