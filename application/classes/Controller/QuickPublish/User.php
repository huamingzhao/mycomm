<?php defined('SYSPATH') or die('No direct script access.');
	/**
	* 用户中心
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-23
	* @return int/bool/object/array
	*/

class Controller_QuickPublish_User extends Controller_QuickPublish_Basic{
	
	protected  $user_id;
	/**
	* 个人升级成为企业项目
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-23
	* @return int/bool/object/array
	*/
	public function action_UserUpgrade(){
		//updateMobileInfoById
		$service = new Service_QuickPublish_User();
		$content = View::factory('quickPublish/userupgrade');
		$this->template->content = $content;
		$get = $this->request->query();
		$content->error ="";
		$session = Session::instance();
		
		if(arr::get($get,"type") == 1){
			$content->error = $session->get("error");
			$session->delete("error");
		}
		
		
		$user_info = array();
		//判断用户是否登录 
		$this->user_id  = $this->userId();
		//获取企业信息 
		
		$arr_comInfo = $service->getUserUpgradeComInfo($this->user_id);
		$user_info = Service_Sso_Client::instance()->getUserInfoById($this->user_id);
		if($user_info){
			$user_info = (array)$user_info;
		}
		//获取地域信息
		$invest= new Service_User_Person_Invest();
		$pro= $invest->getArea();
		$content->area = $pro;
		$content->user_info = $user_info;
		$content->companyinfo = $arr_comInfo;
		
		$pro_id = arr::get($arr_comInfo,"com_area");
		if($pro_id !='' && $pro_id !='88'){
			$area  = array('pro_id'=>$pro_id);
			$cityarea = common::arrArea($area);
		}else{
			$cityarea = array();
		}
		$content->areaIds =arr::get($arr_comInfo,"com_city");
		$content->cityarea = $cityarea;
		$content->pro_id = $pro_id;
		
		if(arr::get($arr_comInfo, "com_phone")){
			$com_phone=explode('+', arr::get($arr_comInfo,"com_phone"));
			if(!empty($com_phone[1])){//判断座机号码是否为空
				$content->com_phone = $com_phone[0];
				$content->branch_phone = $com_phone[1];
			}else{
				$content->com_phone = arr::get($arr_comInfo, "com_phone");
				$content->branch_phone = '';
			}
		}else{
			$content->com_phone = "";
			$content->branch_phone = '';
		}
		$content->com_founding_time_year     = UTF8::substr(arr::get($arr_comInfo,"com_founding_time"),0,4 );
		$content->com_founding_time_month     = UTF8::substr( arr::get($arr_comInfo,"com_founding_time"),4 );
		$content->project_title = htmlspecialchars_decode("个人升级企业用户");
		$this->template->whereAreYou = array($content->project_title => '');
		$content->showqiye =  $session->get("showqiye") ? $session->get("showqiye") : 'bukeyi';
		$session->set("showqiye","");
	}
	/**
	* 实行添加或者修改
	* @author Smile(jiye)
	* @param $Post
	* @create_time  2014-5-23
	* @return int/bool/object/array
	*/
	public function action_DoComInfo(){
		$service= new Service_QuickPublish_User();
		$session = Session::instance();
		$post = $this->request->post();
		foreach ( $post as $k => $v ){
			$data [$k] = trim ( $v );
		}
		//在后端对提交的数据做统一验证处理 add by gongyong && 许晟玮
		$valid_post = Validation::factory($data);
		$valid_post->rule("com_phone",
				function( Validation $valid_error, $field, $value ){
					if( !Valid::not_empty( $value ) || !Valid::min_length( $value,5 ) || !Valid::max_length( $value,20 ) ){
						$valid_error->error($field, '请输入正确的联系方式');
						return false;
					}
		
					if($value=="" or preg_match("/^[0-9-]*$/", $value) ){
						return true;
					}
					else{
						$valid_error->error($field, '请输入正确的联系方式');
						return false;
					}
				},
				array(':validation', ':field', ':value')
		)->rule("branch_phone",//座机电话必须是数字
						function(Validation $valid_error, $field, $value){
							if( !Valid::max_length($value,5) ){
								$valid_error->error($field, '分机号最多只能5位');
								return false;
							}
							if($value=="" or Valid::numeric($value) ){
								return true;
							}
							else{
								$valid_error->error($field, '分机号错误');
								return false;
							}
		
						},
						array(':validation', ':field', ':value')
				)->rule("mobile","not_empty")//手机号必填
				->rule("mobile",
						function(Validation $valid_error, $field, $value){
							//判断此手机号是否已经被绑定
		
							if( !Valid::exact_length($value,11) || !is_numeric($value) ){
								$valid_error->error($field, '请输入正确的手机号码');
								return false;
							}
							else
							{
								return true;
							}
		
						},
						array(':validation', ':field', ':value')
				)->rule("com_contact","not_empty")
				->rule("com_contact",
						function(Validation $valid_error, $field, $value){
							$rule       = "\x80-\xff";
							if( !preg_match("/[$rule]/",$value) && !preg_match('/^[a-zA-Z]+$/', $value) ){
								$valid_error->error($field, '请输入2-10个字符，由汉字、拼音组成');
								return false;
							}elseif ( !Valid::min_length( $value,2 ) || !Valid::max_length( $value,10 ) )
							{
								$valid_error->error($field, '请输入2-10个字符，由汉字、拼音组成');
								return false;
							}
							else
							{
								return true;
							}
		
						},
						array(':validation', ':field', ':value')
				)->rule("com_nature","not_empty")
				->rule("com_nature","numeric")
				->rule("com_adress",
						function(Validation $valid_error, $field, $value){
							if( !Valid::max_length( $value,30 ) || !Valid::not_empty( $value ) ){
								$valid_error->error($field, '请输入正确的公司地址');
								return false;
							}
							else{
								return true;
							}
						},
						array(':validation', ':field', ':value')
				)->rule("com_founding_time_year",//公司注册时间-年份
						function(Validation $valid_error, $field, $value){
		
		
							if( !Valid::exact_length( $value,4 ) || !Valid::not_empty( $value ) || !Valid::numeric( $value ) || ceil($value)>date("Y") ){
								$valid_error->error($field, '请输入正确的公司注册时间');
								return false;
							}
							else{
								return true;
							}
						},
						array(':validation', ':field', ':value')
		
				)->rule("com_founding_time_month",//公司注册时间-月份
						function(Validation $valid_error, $field, $value){
		
							if( !Valid::max_length( $value,2 ) || !Valid::not_empty( $value ) || !Valid::numeric( $value ) || ceil($value)>12 ){
								$valid_error->error($field, '请输入正确的公司注册时间');
								return false;
							}
							else{
								return true;
							}
						},
						array(':validation', ':field', ':value')
		
				)->rule("com_registered_capital", //公司注册资本
						function(Validation $valid_error, $field, $value){
							if( !Valid::max_length( $value,10 ) || !Valid::not_empty( $value ) || !Valid::numeric( $value ) ){
								$valid_error->error($field, '请输入正确的公司注册资本');
								return false;
							}
							else{
								return true;
							}
						},
						array(':validation', ':field', ':value')
		
				)->rule("com_registered_capital",
						function(Validation $valid_error, $field, $value){
							if( !Valid::max_length( $value,10 ) || !Valid::not_empty( $value ) || !Valid::numeric( $value ) ){
								$valid_error->error($field, '请输入正确的公司注册资本');
								return false;
							}
							else{
								return true;
							}
						},
						array(':validation', ':field', ':value')
		
				)->rule("com_desc",
						function(Validation $valid_error, $field, $value){
							if( !Valid::not_empty( $value ) ){
								$valid_error->error($field, '请输入公司简介');
								return false;
							}
							else{
								return true;
							}
						},
						array(':validation', ':field', ':value')
		
				);
			if(!$valid_post->check()){
				$result['error'] = $valid_post->errors();
				$result['status'] = -1;
				
				 $session->set("error", $result['error']);
				 self::redirect("/quick/User/UserUpgrade?type=1");
				 exit;
			}else{
				$session = Session::instance();
				$bool = $service->DoComInfo($this->userId(),$data);
				if($bool == true){
					$session->set("showqiye","keyi");
				}
				self::redirect("/quick/User/UserUpgrade");
				exit;
			}
	}
	
	/**
	* 手机号码
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-5-23
	* @return int/bool/object/array
	*/
	public function action_UpdataMobile(){
		$this->isLogin();
		$content = View::factory('quickPublish/updatemobile');
		$service_user = Service::factory("User");
	  if($this->request->method() == HTTP_Request::POST){
            //判断手机号码是否为绑定的号码
            $receiver = Arr::get($this->request->post(), "receiver");
            if($service_user->isMobileBinded($receiver)){
                $error = "{$receiver}已经被绑定";
            }
            //验证验证码  绑定号码和更新状态
            else{
                $check_code = Arr::get($this->request->post(),"check_code");
                if($service_user->bindMobile($user->user_id,$receiver,$check_code)){
                    //手机绑定通过，增加积分
                    $points = Service::factory("User_Company_Points");
                    $points->getPointsTimes($user->user_id,"valid_mobile");
                    //手机绑定成功，增加诚信点
                    $integrity = Service::factory("User_Company_Integrity");
                    if($integrity->getIntegrityOnce($user->user_id,"valid_mobile")){
                        //手机号码通过验证，发送消息提醒
                        //$msg_service = new Service_User_Ucmsg();
                        //$msg_service->pushMsg($user->user_id, "company_integrity", "您已经验证手机号码，增加60点诚信指数。",URL::website("company/member/basic/integrity"));

  //                      $smsg = Smsg::instance();
//                         //内部消息发送
//                         $smsg->register(
//                                 "tip_company_integrity",//我的诚信
//                                 Smsg::TIP,//类型
//                                 array(
//                                         "to_user_id"=>$user->user_id,
//                                         "msg_type_name"=>"company_integrity",
//                                         "to_url"=>URL::website("company/member/basic/integrity")
//                                 ),
//                                 array(
//                                         "code"=>"60",
//                                         "type"=>"tel"
//                                 )
//                         );
                    }
                    self::redirect("/quick/User/UpdataMobile");
                }
                else{
                    $content->mobile = Arr::get($this->request->post(),"receiver");
                    $error = "验证码错误";
                }
            }
            $content->error = $error;
        }
		$this->template->content = $content;
		$content->user_info =  (array)$this->userInfo();
	}
	
	/**
	* 用户留言页面
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-3
	* @return int/bool/object/array
	*/
	public function action_UserMessage(){
		$service= new Service_QuickPublish_User();
		$content = View::factory('quickPublish/usermessage');
		$get = $this->request->query();
		$this->isLogin();
		if(arr::get($get,"project_id")){
			$arr_data = $service->getUserMessageByProjectId(arr::get($get,"project_id"));
		}else{
			$arr_data = $service->getUserMessage();
		}
		
		//echo "<pre>"; print_r($arr_data);exit;
		$this->template->content = $content;
		$content->arr_data = $arr_data;
		$content->project_title = htmlspecialchars_decode("留言管理");
		$this->template->whereAreYou = array($content->project_title => '');
	}
}