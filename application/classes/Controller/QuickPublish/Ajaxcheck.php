<?php

defined('SYSPATH') OR die('No direct script access.');

class Controller_QuickPublish_Ajaxcheck extends Controller {

    public function before() {
        parent::before();
    }

	/**
     * ajax 根据用户ID得到手机号码
     * author: 兔毛  2014-05-22
     */
	public function action_getusermobile()
	{
		$mobile='';
		$user_id =isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;  //84667
		if(preg_match("/[^\d-., ]/",$user_id) || empty($user_id)){
    		$this->jsonEnArr('501', '用户id格式不正确');
    	}
		$user_info=Service_Sso_Client::instance()->getUserInfoById($user_id);
		$mobile=isset($user_info->mobile)? $user_info->mobile:'';
		$this->jsonEnArr ( '200', $mobile );
	}
	
	/**
	 * 用户X+项目id，收到的名片数
	 * @param unknown_type $project_id：项目id
	 * author: 兔毛  2014-05-29
	 */
	public function action_getLetterByProjectid()
	{
		$count=0;
		$is_login=$this->isLogin();
		if(!$is_login){
			return $this->jsonEnArr ( '500', '未登录');
		}
		$user_id = $this->userInfo()->user_id; // 获取登录user_id
		$project_id =isset($_REQUEST['project_id'])?$_REQUEST['project_id']:0;  //84667
		if(preg_match("/[^\d-., ]/",$project_id) || empty($project_id)){
			$this->jsonEnArr('501', '项目id格式不正确');
		}
		$card_obj=new Service_QuickPublish_Card();
		$count=$card_obj->get_to_user_card_count($user_id,$project_id);
		$this->jsonEnArr ( '200', $count );
	}
    
    /**
     * ajax 新增项目投诉举报信息
     * author: 兔毛  2014-05-15
     */
    public function action_addProjectComplaint()
    {
    		$is_login=$this->isLogin();
            if(!$is_login){
                return $this->jsonEnArr ( '500', '未登录');
            }
    		$user_id = $this->userInfo()->user_id; // 获取登录user_id
    		$postdata = $this->request->post();
    		$project_publish_user_id = Arr::get ($postdata, "project_publish_user_id",0); //项目发布人
    		if($user_id==$project_publish_user_id) $this->jsonEnArr('505', '不能自己举报自己');
    		$userInfo=Service_Sso_Client::instance()->getUserInfoById($user_id);
    		$is_valid_mobile=isset($userInfo->valid_mobile)?$userInfo->valid_mobile:0;
    		if($is_valid_mobile!=1)
    			$this->jsonEnArr ( '503', '手机号码未验证');
    		else
    		{
    			$complaint_type = Arr::get ($postdata, "complaint_type"); //50个字符以内；投诉举报类型，如：虚拟信息、违法信息、其他等
    			$valid = new Validation ($postdata);
    			$valid->rule ('complaint_type', 'not_empty');
    			if (!$valid->check ()) {
    				$this->jsonEnArr('501', '投诉举报类型不能为空');
    			}
    			$postdata['complaint_type']=$complaint_type;
    			$postdata['complaint_contents'] = Arr::get ($postdata, 'complaint_contents');
    			$postdata['project_id'] = Arr::get ($postdata, 'project_id');
    			/* $postdata['project_id']=35801;
    			 $postdata['complaint_type']='虚拟信息';
    			$postdata['complaint_contents']='test';*/
    			$client= new Service_QuickPublish_ProjectComplaint();
    			$ishas=$client->ishas_by_user_and_pro_id($user_id,$postdata['project_id']);
    			if($ishas>0)
    			{
    				$this->jsonEnArr('504', '您已经举报过该招商加盟信息，无须再重复举报');
    			}
    			if($client->addProjectComplaint($user_id,$postdata)){
    				$this->jsonEnArr ( '200', 'ok' );
    			}
    			else
    				$this->jsonEnArr ( '502', '数据库操作异常');
    		}
    }
    
    
    /**
     * 新增发送名片记录
     * author: 兔毛  2014-05-21
     */
    public function action_addCardInfo()
    {
    	$is_login=$this->isLogin();
    	if(!$is_login){
    		return $this->jsonEnArr ( '500', '未登录');
    	}
    	$postdata = $this->request->post();
    	//print_r($postdata);exit;
    	$from_user_id = Arr::get ($postdata, "from_user_id");
    	
    	
    	if(!isset($from_user_id)) $this->jsonEnArr('501', '发送用户id不能为空'); 
    	$to_user_id = Arr::get ($postdata, "to_user_id");
    	if(!isset($to_user_id)) $this->jsonEnArr('502', '接收用户id不能为空');
    	if($from_user_id==$to_user_id) $this->jsonEnArr('503', '请不要给自己的生意信息留言。');
    	$content= Arr::get ($postdata, "message");
    	if(!isset($content)) $this->jsonEnArr('504', '咨询内容不能为空');
		$ip=ip2long(Request::$client_ip);//Arr::get($postdata,"ip",0);
		$to_project_id=Arr::get($postdata,"to_project_id",0);
		//判断这个人一天只能留言一次
		$Service_QuickPublish_User =  new Service_QuickPublish_User();
		$int_num = $Service_QuickPublish_User->getMessageCountByFromUserId($from_user_id,arr::get($postdata,"projectid"));
		if($int_num >0){
			$this->jsonEnArr('504', "该生意信息今天你已成功留言，无需重复留言。");exit;
		}
		//判断验证码是否正确
		$servicesend = new Service_User_MobileCodeLog();
		if(arr::get($postdata,"mobile") != arr::get($postdata,"old_mobile")){
			if(arr::get($postdata,"code") == ""){
				$this->jsonEnArr('505', "请填写手机验证码");exit;
			}
		}
		if(arr::get($postdata,"code")){
			if(!$servicesend->getCodeEof(arr::get($postdata, "mobile"),arr::get($postdata,"code"))){
				$this->jsonEnArr('500', "手机验证码错误");exit;
			}
		}
		//exit;
    	//测试：$from_user_id=644;$to_user_id=76957;$content='咨询内容测试';
    	$card_obj=new Service_QuickPublish_Card();
    	/*
    	$info=$card_obj->getCardInfo($from_user_id,$to_user_id);
    	$send_count=isset($info['send_count'])?$info['send_count']*1+1:1;
    	if($send_count==1) //证明A没有向B发送过名片，进行Insert操作
		{
			$is_exchange_count=0;    	
			$is_exchange_count=$card_obj->is_exchange($to_user_id,$from_user_id); //判断A是否向B发过名片
			$postdata['send_count']=$send_count;  //发送次数
			$postdata['exchange_status']=$is_exchange_count==0 ? 0:1;  //记录名片是否已交换（默认0未交换，1代表已交换）
			$postdata['to_read_status']=Arr::get($postdata,"to_read_status",0);    //记录我收到的名片阅读状态（默认0未阅读，1代表已读）
			$postdata['to_read_time']=Arr::get($postdata,"to_read_time",NULL);     //记录我收到的名片阅读时间
			$postdata['from_read_status']=Arr::get($postdata,"from_read_status",1);//记录我递出的名片阅读状态（默认递出时为1：已读的状态，但接收者点击交换或者给我发送名片时，修改为未读状态）
			$postdata['from_read_time']=Arr::get($postdata,"from_read_time",NULL); //记录我递出的名片阅读时间
			$postdata['to_del_status']=Arr::get($postdata,"to_del_status",0);      //记录接收者删除名片记录（默认0未删除，1为已删除）
			$postdata['to_del_time']=Arr::get($postdata,"to_del_time",NULL);       //记录接收者删除名片时间
			$postdata['from_del_status']=Arr::get($postdata,"from_del_status",0);  //记录发送者删除名片记录（默认0未删除，1为已删除）
			$postdata['from_del_time']=Arr::get($postdata,"from_del_time",NULL);   //记录发送者删除名片时间
			$postdata['to_project_id']=$to_project_id;      //记录项目id[个人留言]
			$postdata['ip']=$ip;  //留言ip
			$postdata['card_type']=Arr::get($postdata,"card_type",0);  //记录名片类型默认0：一句话pc端，1为手机端递送名片
			$is_oper_card_info=$card_obj->addCardInfo($from_user_id,$to_user_id,$postdata);
		}
		else  //A已经向B发送过名片了，所以进行Update
		{
			$card_id=$info['card_id'];
			$is_oper_card_info=$card_obj->updateCardInfo($card_id,$send_count,$postdata);
		}
		
		$postdata['content']=$content;  //发信内容
		//$postdata['user_type']=Arr::get($postdata,"user_type",1);  //发送用户类型【默认1：企业用户,2:个人用户】
		//$postdata['letter_status']=Arr::get($postdata,"letter_status",1);  //信息状态【默认1：启用,2:已删除】
		//$postdata['letter_type']=Arr::get($postdata,"letter_type",1);   //个人用户留言类型1:我要咨询；2:索要资料；3:发送意向
		$postdata['to_project_id']=$to_project_id;      //记录项目id[个人留言]
		$postdata['letter_from_type']=Arr::get($postdata,"letter_from_type",0);  //记录类型默认0：一句话pc端，1为手机端递送名片
		$postdata['letter_ip']=$ip;      //留言ip
		//['fromdomain']=Arr::get($postdata,"fromdomain",'');      //入口域名
		$postdata['user_name'] = arr::get($postdata,"user_name");
		$postdata['email'] = arr::get($postdata,"email");
		$postdata['mobile'] = arr::get($postdata,"mobile");
		*/
		$arr_data = array();
		$arr_data['user_name'] = arr::get($postdata,"name");
		$arr_data['email'] = arr::get($postdata,"email");
		$arr_data['content'] = arr::get($postdata,"message");
		$arr_data['to_project_id'] = arr::get($postdata,"projectid");
		$arr_data['mobile'] = arr::get($postdata,"mobile");
		$arr_data['status'] = 1;
		
		$is_oper_user_letter_info=$card_obj->addUserLetterInfo($from_user_id,$to_user_id,$arr_data);
		/*if(($is_oper_card_info) && ($is_oper_user_letter_info)){
			$this->jsonEnArr ( '200', 'ok' );
		}*/
		if($is_oper_user_letter_info){
			$this->jsonEnArr ( '200', 'ok' );
		}
		else
			$this->jsonEnArr ( '502', '数据库操作异常');	
    }
    


	/**
     * 用户快速注册 （略有修改）
     * 参考：Controller_User_Person_Ajaxcheck中的action_quickRegister()
     * @author 兔毛 2014-05-23
     */
    public function action_quickRegister(){
        if($this->request->is_ajax()){
            $post = Arr::map("HTML::chars", $this->request->post());
         //   print_r($post);exit;
            $service = new Service_User_Person_User();
            $card_obj=new Service_QuickPublish_Card();
            //组装表单数据
            $form = $postdata = array();
            $form['email'] = Arr::get($post,"email");
            $form['mobile'] = Arr::get($post,"mobile");
            $form['user_name'] = Arr::get($post,"user_name");
            $form['area_id'] = Arr::get($post,"per_area");
            $form['city_id'] = Arr::get($post,"area_id");
            $form['check_code'] = Arr::get($post,"check_code");
			$postdata['to_project_id']=Arr::get($post,"projectid",0);
			$postdata['content']= arr::get($post,'leave_word','');
			$postdata['letter_type']=arr::get($post,'type',1);  //个人用户留言类型1:我要咨询；2:索要资料；3:发送意向
			$postdata['user_name'] = arr::get($post,"user_name");
			$postdata['email'] = arr::get($post,"email");
			$postdata['mobile'] = arr::get($post,"mobile");
            //判断验证码是否正确
            $servicesend = new Service_User_MobileCodeLog();
            if($servicesend->getCodeEof($form['mobile'], $form['check_code'])){
            	$client = Service_Sso_Client::instance();
            	//验证码是正确的，那第一步判断手机是否已经是注册的
            	$servicemobile = new Service_User ();
            	if ($servicemobile->isMobileBinded ( $form['mobile'] )) {//手机号码已注册
            		//获取该用户基本信息
            		$result_user = $client->getUserInfoByMobile($form['mobile']);
            		if($result_user && $result_user->id ){
            			if($result_user->user_type==2){//个人用户
	            			if(!$result_user->valid_mobile){//判断手机是否已经验证，如果没验证改为已验证
	            				$client->updateMobileInfoById( $result_user->id,array( 'valid_status'=>'1','valid_time'=>time() ) );
	            			}
	            			if(!$result_user->email){//判断邮箱是否为空，如果为空，则填写邮箱
	            				$client->updateEmailInfoById( $result_user->id,array( 'email'=>$form['email'] ) );
	            			}
	            			$cardser=new Service_Card();
	            			$proser=new Service_Platform_Project();
	            			$card_post=array();
	            			$com_user_id=$proser->getUseridByProjectID(arr::get($post,'projectid',0));
	            			if(!$com_user_id){
	            				$com_user_id= arr::get($post,"to_user_id");
	            			}
	            			//发送名片+发送咨询
	            			$card_obj->addOutCardQuickRegister($result_user->id,$com_user_id, $postdata); 
	            			$this->jsonEnArr('200', "注册成功");
            			}else{
            				$this->jsonEnArr('500', "您的手机已被企业用户已注册，发送名片只能个人用户哦");
            			}
            		}
            	}elseif($servicemobile->forgetPasswordEmail($form['email'])===true){//邮箱已经注册
            		$result_user = $client->getUserInfoByEmail($form['email']);
            		if($result_user->user_type==2){//个人用户
	            		if($result_user && $result_user->id ){
	            			if($result_user->mobile){//修改手机
	            				$client->updateMobileInfoById( $result_user->id,array('mobile'=>$form['mobile'],'valid_status'=>'1','valid_time'=>time()) );
	            			}else{//添加手机
	            				$client->setUserMobileById($result_user->id,$form['mobile'],1);
	            			}
	            			$cardser=new Service_Card();
	            			$proser=new Service_Platform_Project();
	            			$card_post=array();
	            			$com_user_id=$proser->getUseridByProjectID(arr::get($post,'projectid',0));
	            			if(!$com_user_id){
	            				$com_user_id=  arr::get($post,"to_user_id");
	            			}
	            			//发送名片+发送咨询
	            			$card_obj->addOutCardQuickRegister($result_user->id,$com_user_id, $postdata);
	            			$this->jsonEnArr('200', "注册成功");
	            		}
            		}else{
            			$this->jsonEnArr('500', "您的邮箱已被企业用户已注册，发送名片只能个人用户哦");
            		}
            	}else{
	                //生成密码,并发送到手机
	              
	                if(arr::get($form, "email")){
	                	$form['password'] = "yijuhua".mt_rand(100000, 999999);
	                	$user = $service->personQuickReg($form);
	                	if(is_object($user)){
	                		//发送密码到手机
	                		$result = common::send_message($form['mobile'], "您的登陆用户名为".$form['mobile']."，登陆密码为".$form['password'], 'online');
	                		$userser=new Service_User();
	                		//手机号码部分隐藏，格式如139****9476
	                		if($form['mobile']){
	                			$t_mobile=mb_substr($form['mobile'],0,3,'UTF-8').'****'.mb_substr($form['mobile'],7,11,'UTF-8');
	                		}else{
	                			$t_mobile='';
	                		}
	                		if($result->retCode!==0){
	                			$this->jsonEnArr("300", "短信发送失败",1);
	                			//回滚操作
	                			$user->user_person->delete();
	                			$user->delete();
	                			//消息发送失败log
	                			$userser->messageLog($form['mobile'],$user->user_id,5,"您的登陆用户名为".$t_mobile."，登陆密码为".$form['password'],0);
	                		}else{//消息发送成功log
	                			$userser->messageLog($form['mobile'],$user->user_id,5,"您的登陆用户名为".$t_mobile."，登陆密码为".$form['password'],1);
	                		}
	                		//$this->jsonEnArr('200', "注册成功",1);
	                		//增加用户注册统计
	                		$stat_service = new Service_Api_Stat();
	                		$stat_service->setUserRegStat($user->user_id,$user->user_type, $user->reg_time, arr::get($_COOKIE, 'Hm_lvqtz_refer'),$user->sid );
	                		//发信
	                		$cardser=new Service_Card();
	                		$proser=new Service_Platform_Project();
	                		$card_post=array();
	                		$com_user_id=$proser->getUseridByProjectID(arr::get($post,'projectid',0));
	                		if(!$com_user_id){
	                			$com_user_id=  arr::get($post,"to_user_id");
	                		}
	                		//发送名片+发送咨询
	                		$card_obj->addOutCardQuickRegister($user->user_id,$com_user_id, $postdata);
	                		//判断用户姓名是否为空，如果为空更新姓名
	                		$updateper = ORM::factory("Personinfo")->where('per_user_id','=',$user->user_id)->find();
	                		if(!$updateper->per_realname && $form['user_name']){
	                			if($updateper->loaded()){
	                				$updateper->per_realname=$form['user_name'];
	                				if(!$updateper->per_photo){
	                					//默认头像
	                					$per_photo1 = "/user_icon/plant/default_icon_" . rand ( 1, 25 ) . ".jpg";
	                					$per_photo=common::getImgUrl($per_photo1);
	                					$updateper->per_photo=$per_photo;
	                				}
	                				$updateper->update();
	                			}else{
	                				$updateper->per_user_id=$user->user_id;
	                				//默认头像
	                				$per_photo1 = "/user_icon/plant/default_icon_" . rand ( 1, 25 ) . ".jpg";
	                				$per_photo=common::getImgUrl($per_photo1);
	                				$updateper->per_photo=$per_photo;
	                				$updateper->per_realname=$form['user_name'];
	                				$updateper->create();
	                			}
	                		}
	                		//发送验证邮件by周进
	                		//$mailservice = new Service_User_Company_User();
	                		//$mailservice->updateCheckValidEmail($user->user_id,$user->email);
	                		$this->jsonEnArr('200', "注册成功");
	                	}else{
	                		$this->jsonEnArr('400', "数据写入失败");
	                	}
	                }else{
	                	$new_service =  new Service_QuickPublish_FastReleaseProject();
	                	$user = $new_service->QuickRegistered(arr::get($form,"mobile"),$form['user_name'],true);
	                	//var_dump($user);
	                	$arr_data = Service_Sso_Client::instance()->userLoginByMobile(arr::get($form,"mobile"),arr::get($form,"check_code"));
	                //	$com_user_id=$proser->getUseridByProjectID(arr::get($post,'projectid',0));
	                	//echo  Cookie::get('user_id');exit;
	                	$card_obj->addUserLetterInfo($user->id,arr::get($postdata,"to_user_id"), $postdata);
	                	$this->jsonEnArr('200', "注册成功");
	                }
            	}
            }
            else{
                $this->jsonEnArr('500', "手机验证码错误");
            }
        }
    }
    
    
    /**
     * 用户id：X发出/收到的名片总数
     * author: 兔毛  2014-05-21
     */
    public function action_getFromOrToCardCount()
    {
    	$postdata = $this->request->post();
    	$user_id = Arr::get ($postdata, "user_id");
    	$valid = new Validation ($postdata);    	
    	$valid->rule ('user_id', 'not_empty');
    	if (!$valid->check ()) {
    		$this->jsonEnArr('501', '用户id不能为空');
    	}
    	$from_or_to = Arr::get ($postdata, "from_or_to");
    	$valid->rule ('from_or_to', 'not_empty');
    	if (!$valid->check ()) {
    		$this->jsonEnArr('502', '请输入from或者to，来区分是发送还是接收');
    	}
    	$card_obj=new Service_QuickPublish_Card();
    	$count=$card_obj->getFromOrToCardCount($user_id,$from_or_to);
    	$this->jsonEnArr ('200', $count);
    }
    
    

    
    /**
     * 快速发布项目的 赞
     * @author stone shi
     */
    public function action_addApproving(){
        $result = array();
        $post = $this->request->post();
        $loginStatus = $this->isLogins();
        $user_id = $loginStatus ? $this->userInfo()->user_id : 0;
        if(!isset($post['project_id'])) $this->jsonEnArr(500, 'project empty'); 
        $project_id = intval($post['project_id']);
        $service = new Service_QuickPublish_ProjectComplaint();
        $res = $service->addApproving($user_id, $project_id);
        if($res){
            $result['status'] = 1;
        }else{
            $result['status'] = 0;
        }
        echo json_encode($result);exit;
    }
    
    /**
         * 统计项目pv
         * @author 嵇烨
         * date 2013/11/8
         * return true or false
         */
        public function action_TongJiProjectPv(){
        	if($this->request->post()){
        		$post = Arr::map("HTML::chars", $this->request->post());
        		$service = new Service_QuickPublish_ProjectComplaint();
                        $loginStatus = $this->isLogins();
                        $user_id = $loginStatus ? $this->userInfo()->user_id : 0;
        		$return_data = array();
        		$return_data['status'] = $service->insertProjectStatistics(arr::get($post, "project_id"),arr::get($post, "type"), $user_id);
        		echo json_encode($return_data);exit;
        	}
        }
    /**
     * 返回ajax状态
     *
     * @author 施磊
     * @param int $code
     *        	状态码
     * @param
     *        	string or array $msg 提示信息
     * @param int $type
     *        	0 为 直接echo 1 是return
     * @return json
     */
    private function jsonEnArr($code, $msg, $type = 0) {
        $arr = array (
                'code' => $code,
                'msg' => $msg,
                'date' => time ()
        );
        $return = json_encode ( $arr );
        if ($type) {
            return $return;
        } else {
            echo $return;
            exit ();
        }
    }

    
    /**
     * 更新发布时间  （快速发布）
     * @author 郁政
     */
    public function action_updateQuickProPublishTime(){
    	if($this->request->is_ajax()){    	
    		$result = array();
    		$post = Arr::map("HTML::chars", $this->request->post());
    		$project_id = Arr::get($post, 'project_id');
    		$user_id = $this->userInfo()->user_id;
    		$service = new Service_QuickPublish_Project();
    		if($service->isBelongToUser($project_id, $user_id)){
    			$result = $service->updateQuickProPublishTime($project_id);
    		}else{
    			$result['status'] = 0;
    		}    		
    		echo json_encode($result);exit;
    	}
    }
    

    /**
     * 删除项目  （快速发布）
     * @author 郁政
     */
    public function action_delQuickPro(){
    	if($this->request->is_ajax()){
    		$result = array();
    		$post = Arr::map("HTML::chars", $this->request->post());
    		$project_id = Arr::get($post, 'project_id');
    		$service = new Service_QuickPublish_Project();
    		$user_id = $this->userInfo()->user_id;
    		if($service->isBelongToUser($project_id, $user_id)){
	    		$suc = $service->delQuickPro($project_id);
		    	if($suc){
		            $result['status'] = 1;
		        }else{
		            $result['status'] = 0;
		        }
    		}else{
    			 $result['status'] = 0;
    		}    		
    		echo json_encode($result);exit;
    	}
    }  
    
    
    /**
     * 检查手机是否已被注册
     * @author Smile(jiye)
     * @param
     * @create_time  2014-5-15
     * @return int/bool/object/array
     */
    
    public function action_checkUserMobile(){
    	if($this->request->is_ajax()){
    		$post = Arr::map("HTML::chars",$this->request->post());
    		//var_dump($post);exit;
    		$service = new Service_User();
    		$data = Service_Sso_Client::instance()->getUserInfoByMobile(arr::get($post,"mobile"));
    		//echo "<pre>"; var_dump($data);exit;
    		$return_data = array();
    		if($data == false ||  (isset($data->avlid_mobile) && $data->avildis_mobile !=1)){
    			//可以直接发布
    			$return_data['code'] = true;
    			$return_data['msg'] = true;
    		}else{
    			//获取验证码发布
    			$return_data['code'] = true;
    			$return_data['msg'] = false;
    		}
    		echo json_encode($return_data);
    	}
    }
    
    
    /**
    * 判断手机号码是不是禁止了
    * @author Smile(jiye)
    * @param 
    * @create_time  2014-5-19
    * @return int/bool/object/array
    */
    public function action_IsReleaseProject(){
    	if($this->request->is_ajax()){
    		$service = new Service_QuickPublish_FastReleaseProject();
    		$post = Arr::map("HTML::chars",$this->request->post());
    	//	echo "<pre>"; print_r($post);exit;
    		$arr_data = $service->IsReleaseProject(arr::get($post,"mobile"));
    		$arr_data['mobile_status'] = arr::get($arr_data,"mobile_status",1);
    		$arr_data['mobile_id'] = arr::get($arr_data,"mobile_id","");
    		$arr_data['not_through_reason'] = arr::get($arr_data,"not_through_reason","");
    		$arr_data['user_id'] = arr::get($arr_data,"user_id","");
    		//判断还能不能发布项目
    		$int = $service->haveReleaseCount(arr::get($post,"mobile"));
    		if($int !=0){
    			$arr_data['mobile_status'] = $int;
    			echo json_encode($arr_data);exit;
    		}
    		//判断项目名称是不是一样
    		if(arr::get($post,"project_title")){
    			$int_num = $service->isHaveSameProject(array("key"=>"project_brand_name","val"=>arr::get($post, "project_brand_name")));
    			//var_dump($int_num);exit;
    			if($int_num > 0){
    				//有相同的标题
    				$arr_data['mobile_status'] = 9;
    				echo json_encode($arr_data);exit;
    			}
    		}
    		
    		//判断标题是不是一样
    		if(arr::get($post,"project_title")){
    			$int_num = $service->isHaveSameProjectTitle(arr::get($post,"project_title"));
    			if($int_num > 0){
    				//有相同的标题
    				$arr_data['mobile_status'] = 8;
    				echo json_encode($arr_data);exit;
    			}
    			
    		}
    		
    		//判断一句话介绍是不是一样
    		if(arr::get($post,"project_introduction") !=""){
    			$int_num = $service->isHaveSameProject(array("key"=>"project_introduction","val"=>arr::get($post, "project_introduction")));
    			if($int_num > 0){
    				//有相同的标题
    				$arr_data['mobile_status'] = 10;
    				echo json_encode($arr_data);exit;
    			}
    		}
    		//
    		//echo "<pre>"; print_r($arr_data);exit;
    		echo json_encode($arr_data);
    	}
    }
    
    /**
    * 快速发布登录
    * @author Smile(jiye)
    * @param 
    * @create_time  2014-5-20
    * @return int/bool/object/array
    */
    public function action_QuickLogin(){     	   	
    	$post = $this->request->post();
    	$get = $this->request->query();
        $callback = arr::get ( $get, 'callback' );
    //	print_r($post);exit;
    	$service = new Service_QuickPublish_FastReleaseProject();
    	if($callback){
    		$bool = $service->QuickLogin(arr::get($get,"mobile"),arr::get($get,"validation"));
    		echo $callback.'('.json_encode($bool).')';exit;     		
    	}else{
    		$bool = $service->QuickLogin(arr::get($post,"mobile"),arr::get($post,"validation"));
    		echo json_encode($bool);exit; 
    	}    	   	
    }
    
    /**
    * 快速发布项目   理由申诉
    * @author Smile(jiye)
    * @param 
    * @create_time  2014-5-20
    * @return int/bool/object/array
    */
    public function action_UpdateMobileComplaintContent(){
    	if($this->request->is_ajax()){
    		$post = $this->request->post();
    		$service = new Service_QuickPublish_FastReleaseProject();
    		$arr_data = array("complaintcontent"=>arr::get($post,"complaintcontent"),
    							"complaint_time"=>time(),
    							"mobile_status"=>3);
    		$bool = $service->DoUpdateMobileAccount(arr::get($post,"mobile_id"),$arr_data);
    		echo json_encode($bool);
    	}
    }
    
    /**
    * 判断手机用户能发多少条数据
    * @author Smile(jiye)
    * @param 
    * @create_time  2014-5-27
    * @return int/bool/object/array
    */
   public function action_haveReleaseCount(){
	   	if($this->request->is_ajax()){
	   		$post = $this->request->post();
	   		$service = new Service_QuickPublish_FastReleaseProject();
	   		if(arr::get($post,"mobile")){
	   			$bool = $service->haveReleaseCount(arr::get($post,"mobile_id"));
	   		}else{
	   			$this->jsonEnArr('501', '手机号码不正确');
	   		}
	   		
	   	}
   }
   
    /**
     * 判断title是否存在 （快速发布）
     * @author 郁政
     */
	public function action_isExistTitle(){
		if($this->request->is_ajax()){
			$post = Arr::map("HTML::chars", $this->request->post());
    		$project_id = Arr::get($post, 'project_id');
    		$title = Arr::get($post, 'title');
    		$service = new Service_QuickPublish_Project();
    		$res = $service->isExistTitle($project_id, $title);
    		echo json_encode($res);
		}   	
	}
	/**
	* 首页静态化 获取数据
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-10
	* @return int/bool/object/array
	*/
	public function action_indexLoading(){
		if($this->request->is_ajax()){
			$obj_service =  new Service_QuickPublish_ProjectComplaint();
			$arr_data = $obj_service->indexLoading();
			//最新发布的项目
			if(arr::get($arr_data,"QuickProjectList")){
				$str = '';
				foreach ($arr_data['QuickProjectList'] as $key=>$val){$key++;if($key > 5){break;};
					$str .= '<li class="clearfix">';
					$str .="<div class='fl'><a target='_blank' href='".urlbuilder::qucikProHome(arr::get($val, "project_id"))."' class='img'><img alt='".arr::get($val, "project_brand_name")."' src='".URL::imgurl(arr::get($val,'project_logo'))."' onerror=\"$(this).attr('src', '".URL::webstatic('/images/quickrelease/company_default.png')."')\"></a></div>";
					$str .='<a target="_blank" href="'.urlbuilder::qucikProHome(arr::get($val, "project_id")).'" class="title">'.mb_substr(arr::get($val,"project_title") ? arr::get($val,"project_title") : arr::get($val,"project_brand_name"), 0,15,'UTF-8').'</a>';
					$str .="<span>";
					$str .='<em class="tag_money"><i class="icon_money"><var class="tishivar">投资金额<b></b></var></i>';
					foreach (common::moneyArr() as $k=>$v){
						if($k == arr::get($val,"project_amount_type")){
							$str .= $v;
						}
					}
					$str.="</em>";
					$str.='<em class="tag_view"><i class="icon_view"><var class="tishivar">浏览量<b></b></var></i>'.arr::get($val,"project_pv_count",0).'</em>';
					$str.='<em class="tag_time"><i class="icon_time"><var class="tishivar">发布时间<b></b></var></i>'.date('m-d',arr::get($val,"project_passtime",time())).'</em>';
					$str .="</span>";
					$str .="</li>";
				}
				$arr_data['QuickProjectList'] = $str;
				//arr::get($arr_data,"QuickProjectList") = $str;
			}
			//最新被关注的项目
			//echo "<pre>"; print_r($arr_data['QuickProjectPvList']);exit;
			if(arr::get($arr_data,"QuickProjectPvList")){
				$num = 0;
				$str = '';
				foreach ($arr_data['QuickProjectPvList'] as $key=>$val){$num++;if($num > 5){break;};
					$str .='<li class="clearfix">';
					$str .="<div class='fl'><a target='_blank' href='".urlbuilder::qucikProHome(arr::get($val, "project_id"))."' class='img'><img alt='".arr::get($val, "project_brand_name")."' src='".URL::imgurl(arr::get($val,'project_logo'))."' onerror=\"$(this).attr('src', '".URL::webstatic('/images/quickrelease/company_default.png')."')\"></a></div>";
					if(arr::get($val,"user_name")){
						$str .="<span>";
						$str .="<font>".mb_substr(arr::get($val, "user_name"),0,11,'UTF8')."</font>";
						if(arr::get($val,"insert_time")){
							$str .="<em>".arr::get($val,"insert_time")."分钟前</em>关注了";
						}else{
							$str .="刚刚关注了";
						}
						$str .="</span>";
						$str .='<a target="_blank" href="'.urlbuilder::qucikProHome(arr::get($val, "project_id")).'" class="title">'.mb_substr(arr::get($val,"project_title") ? arr::get($val,"project_title") : arr::get($val,"project_brand_name"), 0,15,"UTF-8").'</a>';		
					}else{
						$str.='<a target="_blank" href="'.urlbuilder::qucikProHome(arr::get($val, "project_id")).'" class="title">'.mb_substr(arr::get($val,"project_title") ? arr::get($val,"project_title") : arr::get($val,"project_brand_name"), 0,15,'UTF-8').'</a>';
						$str.='<span>';
						if(arr::get($val,"insert_time")){
							$str.='<em style="padding-left:0;">'.arr::get($val,"insert_time").'分钟前</em>被关注了';
						}else{
							$str.="刚刚被关注了";
						}
						$str.='</span>';
					}
					$str .="</li>";
				}
				$arr_data['QuickProjectPvList'] = $str;
			}
			//最新加入会员的数据
			if(arr::get($arr_data,"NewUserList")){
				$str ="";
				foreach ($arr_data['NewUserList'] as $key=>$val){$key++;if($key > 5){break;};
					$str .='<li class="clearfix">';
					if(arr::get($val,"user_gender") == 0){
						$image = URL::webstatic("/images/find_invester/photo_man.jpg");
					}else{
						$image = URL::webstatic("/images/find_invester/photo_woman.jpg");
					}
					$str.="<div class='fl'><a class='img'> <img src='".URL::imgurl(arr::get($val, "user_portrait"))."' alt='".arr::get($val,"user_name")."' onerror=\"$(this).attr('src', '".$image."')\"></a></div>";
					$str.='<span>';
					$str.='<font>'.arr::get($val, "newmobile").'</font>';
					if(arr::get($val,"zhuceshijian")){
						$str.='<em>'.arr::get($val,"zhuceshijian").'</em>';
					}else{
						$str.="<em></em>";
					}
					$str.='</span>';
					$str.="<span>加入一句话生意网</span>";
					$str.='</li>';
				}
				//echo "<pre>"; echo $str;exit;
				$arr_data['NewUserList'] = $str;
			}
			//一周热门生意推荐
			//一周热榜  1万大洋
			if(arr::get($arr_data,"HotList5")){
				$arr_data['HotList5'] = $this->WorkTop(arr::get($arr_data,"HotList5"));
			}
			//一周热榜  1-2万 大洋
			if(arr::get($arr_data,"HotList5To10")){
				$arr_data['HotList5To10'] = $this->WorkTop(arr::get($arr_data,"HotList5To10"));
			}
			//一周热榜  2-5万 大洋
			if(arr::get($arr_data,"HotList10To20")){
				$arr_data['HotList10To20'] = $this->WorkTop(arr::get($arr_data,"HotList10To20"));
			}
			//一周热榜  5-10万 大洋
			if(arr::get($arr_data,"HotList20To50")){
				$arr_data['HotList20To50'] = $this->WorkTop(arr::get($arr_data,"HotList20To50"));
			}
			//一周热榜  10万 大洋
			if(arr::get($arr_data,"HotList50")){
				$arr_data['HotList50'] = $this->WorkTop(arr::get($arr_data,"HotList50"));
			}
			echo json_encode($arr_data);
		}
	}
	/**
	* 一周热门生意推荐
	* @author Smile(jiye)
	* @param 
	* @create_time  2014-6-10
	* @return int/bool/object/array
	*/
	public function WorkTop($arr_data){
		$str ="";
		if($arr_data){
			foreach ($arr_data as $key=>$val){ $key++;if($key >20){break;}
				
				if($key%4 == 0){
					$str.='<li class="last">';
				}else{
					$str.='<li>';
				}
				$str.='<a target="_blank" href="'.urlbuilder::qucikProHome(arr::get($val, "project_id")).'" class="title">'.mb_substr(arr::get($val,"project_title") ? arr::get($val,"project_title") : arr::get($val,"project_brand_name"), 0,15,'UTF-8').'</a>';
				$str.="<span>";
				$str.='<a target="_blank" href="'.urlbuilder::qucikProHome(arr::get($val, "project_id")).'" class="tag_view"><i class="icon_view"><var class="tishivar">浏览量<b></b></var></i>'.arr::get($val,"project_pv_count").'</a>';
				$str.='<a target="_blank" href="'.urlbuilder::qucikProHome(arr::get($val, "project_id")).'" class="tag_time"><i class="icon_time"><var class="tishivar">发布时间<b></b></var></i>'.date("m-d",arr::get($val, "project_passtime",time())).'</a>';
				$str.="</span>";
				$str.="</li>";
			}
			$str.="</ul>";
		}
		return $str;
	}
        
        /**
     * 快速发布广告跳转页
     * @author stone shi
     */
    public function action_advert() {
        $id = intval(arr::get($_GET, 'id', 0));
        $service = new Service_QuickPublish_Project();
        $info = $service->getQuickAdvertInfo($id);
        if(!arr::get($info, 'id', 0)) {
            self::redirect(URL::website('/'));
        }
        $this->template = View::factory("quickPublish/advert");
        $this->templateid = $id;
        $this->template->info = $info;
        echo $this->template;
    }
    
	/**
     * 设置手机防骚扰
     * @author 郁政
     */
	public function action_setMobileSaoRao(){
		if($this->request->is_ajax()){
			$res = array();
			$post = Arr::map("HTML::chars", $this->request->post());
			$is_login=$this->isLogins();
	    	$user_id = $is_login ? $this->userId() : 0; 
			$mobile = Arr::get($post, 'mobile');
			$type = Arr::get($post, 'type');
			$day = Arr::get($post, 'day');
			$service = new Service_QuickPublish_Project();
			$res = $service->setMobileSaoRao($user_id,$mobile, $type, $day,intval(1));
			echo json_encode($res);exit;
		}
	}
	
	/**
     * 取消手机防骚扰
     * @author 郁政
     */
	public function action_cancelMobileSaoRao(){
		if($this->request->is_ajax()){
			$res = array();
			$post = Arr::map("HTML::chars", $this->request->post());
			$is_login=$this->isLogins();
			$user_id = $is_login ? $this->userId() : 0; 
			$mobile = Arr::get($post, 'mobile');
			$type = Arr::get($post, 'type');
			$day = Arr::get($post, 'day');
			$service = new Service_QuickPublish_Project();
			$res = $service->setMobileSaoRao($user_id,$mobile, $type, $day,intval(0));
			echo json_encode($res);exit;
		}
	}
	
	/**
     * 查找该手机号是否设置过防骚扰
     * @author 郁政
     */
	public function action_isMobileSaoRao(){
		if($this->request->is_ajax()){
			$service = new Service_QuickPublish_Project();
			$post = Arr::map("HTML::chars", $this->request->post());
			$mobile = Arr::get($post, 'mobile');
			$res = $service->isMobileSaoRao($mobile);
			echo json_encode($res);exit;
		}
	}
}