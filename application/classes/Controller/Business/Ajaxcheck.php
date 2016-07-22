<?php

defined('SYSPATH') OR die('No direct script access.');

class Controller_Business_Ajaxcheck extends Controller {
	private $talk_obj=null;
	private $question_obj=null;
	private $answer_obj=null;
	private $userstat_obj=null;
	private $stat_obj=null;
	private $nice_obj=null;
	private $against_obj=null;
	private $limit=30; //默认：加载30条
	
	public function before() {
		$this->talk_obj=new Service_Business_Talk();
		$this->question_obj=Service_Business_Question::getInstance();
		$this->answer_obj=Service_Business_Answer::getInstance();
		$this->userstat_obj=Service_Business_UserStat::getInstance();
		$this->stat_obj=Service_Business_Stat::getInstance();
		$this->nice_obj=Service_Business_NiceDetail::getInstance();
		$this->against_obj=Service_Business_AgainstDetail::getInstance();
		parent::before();
	}
	
    /* 通用：404错误 */
    public function common_error()
    {
    	self::redirect("platform/index/error404");
    	$this->template->title = "您的访问出错了_404错误提示_一句话";
    	$this->template->keywords = "您的访问出错了,404";
    	$this->template->description = "一句话404错误提示您：您的访问出错了，请根据我们的提示重新进入网站浏览您想看的信息。";
    }
    
    /* 通用：未登录 */
    public function common_not_login()
    {
    	//$this->isLogins()
    	if($this->userInfo()==false){
    		echo json_encode(array('code'=> 503,'msg'=>'未登录')); exit;
    	}
    }
    
    /* AJAX-获取话题列表 */
    public function action_get_talk_list()
    {
    	$talk_list=$this->talk_obj->get_talk_list();  //话题列表
    	echo json_encode(array_values($talk_list));
    	exit;
    }
    
    /* AJAX-首页-左侧列表 */
    public function action_get_home_left_list($talk_id=0,$type='new',$page=0)
    {
    	$talk_id=isset($_REQUEST['talk_id'])?$_REQUEST['talk_id']:$talk_id;
    	$type=isset($_REQUEST['type'])?$_REQUEST['type']:$type;
    	$offset=isset($_REQUEST['page'])?$_REQUEST['page']:$page;
    	if($offset>0)  $offset=($offset-1)*$this->limit;
    	$result=$this->question_obj->get_question_list($talk_id,$type,$this->limit,$offset); //首页-问题列表
    	echo json_encode($result);
    	exit;
    }
    
    /* AJAX-发起问题  */
    public function action_insert_question()
    {
    	$this->common_not_login();
    	$error_msg='';
    	/* $title='测试1：<script type="text/javascript">alert(1112);</script><div class="guideserchbox">敏感词能入库么？</div>';
    		$title=validations::strip_html_conent($title);*/
    	$check_param = array('title');
    	$error_msg=validations::check_param_name($check_param);
    	if(empty($error_msg))
    	{
    		$user_id = $this->userInfo()->user_id; // 获取登录user_id
    		$check_param = array('title'=>validations::get_param_value('title', ''));
    		$error_msg=validations::check_value_set($check_param);
    		if(empty($error_msg))
    		{
    			$content=validations::get_param_value('content','');
    			$array_data=array('title'=>validations::strip_html_conent($check_param['title']),
    					'content'=>validations::strip_html_conent($content),
    					'talk_id'=>validations::get_param_value('talk_id', 1,'int'),
    					'is_hidden'=>validations::get_param_value('is_hidden', 0,'int'));
    			$is_oper=$this->question_obj->insert($array_data, $user_id);
    			echo json_encode(array('code'=> 200)); exit;
    		}
    		else {
    			echo json_encode(array('code'=> 501,'msg'=>$error_msg)); exit;
    		}
    	}
    	echo json_encode(array('code'=> 500,'msg'=>$error_msg)); exit;
    }
    
    
    /* AJAX-问题详细页-回复列表 */
    public function action_get_answer_list()
    {
    	$question_id=validations::get_param_value('question_id', 0,'int');
    	if(empty($question_id))
    	{
    		echo json_encode(array('code'=> 501,'msg'=>'参数question_id不能为空; ')); exit;
    	}
    	else
    	{
    		$type=validations::get_param_value('type','count');
    		$offset=validations::get_param_value('page', 0,'int');
    		if($offset>0)  $offset=($offset-1)*$this->limit;
    		$user_id = $this->userInfo()? $this->userInfo()->user_id:0;
    		$result=$this->answer_obj->get_answer_list($question_id,$type,$this->limit,$offset,$user_id); //首页-问题列表
    		$result['code']=200;
    		echo json_encode($result);
    		exit;
    	}
    }
    
    
    /* AJAX-X用户对问题、答案，点赞、取消赞的操作 */
    public function action_oper_nice_question_and_answer()
    {
    	$this->common_not_login();
    	$error_msg='';
    	$question_id=validations::get_param_value('question_id', 0,'int');
    	$talk_id=validations::get_param_value('talk_id', 0,'int');
    	if(empty($question_id)) $error_msg.= "参数question_id不能为空; ";
    	if(empty($talk_id)) $error_msg.= "参数talk_id不能为空; ";
    	if(empty($error_msg))
    	{
    		$answer_id=validations::get_param_value('answer_id', 0,'int');
    		$user_id=$this->userInfo()->user_id;
    		$result=$this->nice_obj->oper_nice_question_and_answer($question_id,$user_id,$answer_id,$talk_id);
    		echo json_encode(array('code'=> 200,'data'=>$result)); exit;
    	}
    	else
    		echo json_encode(array('code'=> 501,'msg'=>$error_msg)); exit;
    }
    
    
    /* AJAX-X用户对问题、答案，点踩、取消踩的操作 */
    public function action_oper_against_question_and_answer()
    {
    	$this->common_not_login();
    	$error_msg='';
    	$question_id=validations::get_param_value('question_id', 0,'int');
    	$talk_id=validations::get_param_value('talk_id', 0,'int');
    	if(empty($question_id)) $error_msg.= "参数question_id不能为空; ";
    	if(empty($talk_id)) $error_msg.= "参数talk_id不能为空; ";
    	if(empty($error_msg))
    	{
    		$answer_id=validations::get_param_value('answer_id', 0,'int');
    		$user_id=$this->userInfo()->user_id;
    		$result=$this->against_obj->oper_against_question_and_answer($question_id,$user_id,$answer_id,$talk_id);
    		echo json_encode(array('code'=> 200,'data'=>$result)); exit;
    	}
    	else
    		echo json_encode(array('code'=> 501,'msg'=>$error_msg)); exit;
    }
    
    
    /* AJAX-解答回复问题  */
    public function action_insert_answer()
    {
    	$this->common_not_login();
    	$error_msg='';
    	$check_param = array('question_id','content');
        $post = Arr::map("HTML::chars", $this->request->post());
    	$error_msg= validations::check_param_name($check_param);
    	if(empty($error_msg))
    	{
    		$user_id = $this->userInfo()->user_id; // 获取登录user_id
    		$question_id=validations::get_param_value('question_id', 0,'int');
    		$content=arr::get($post, 'content', '');
    		if(empty($question_id)) $error_msg.= "参数question_id不能为空; ";
    		if(empty($content)) 	$error_msg.= "参数content不能为空; ";
    		if(empty($error_msg))
    		{
    			$question_info=$this->question_obj->get_one_question($question_id);
    			if($question_info)
    			{
    				$array_data=array('content'=>$content,
    						'talk_id'=>$question_info['talk_id'],
    						'is_hidden'=>validations::get_param_value('is_hidden', 0,'int'));
    				$answer_id=Service_Business_Answer::getInstance()->insert($array_data,$user_id,$question_id);
    				//d($answer_id,false);
    				if($answer_id>0)
    				{
    					$result=$this->answer_obj->get_answer_list($question_id,'count',1,0,$user_id,$answer_id);
    					$result['code']=200;
    					echo json_encode($result); exit;
    				}
    				else {
    					echo json_encode(array('code'=> 300,'msg'=>'回复解答失败; ')); exit;
    				}
    			}
    			else{
    				echo json_encode(array('code'=> 502,'msg'=>'此问题不存在; ')); exit;
    			}
    		}
    		else {
    			echo json_encode(array('code'=> 501,'msg'=>$error_msg)); exit;
    		}
    	}
    	echo json_encode(array('code'=> 500,'msg'=>$error_msg)); exit;
    }
    
    
    /* AJAX-用户主页-我的提问 列表 */
    public function action_get_my_question_list()
    {
    	$result=null;
    	$user_id=isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
    	$offset=isset($_REQUEST['page'])?$_REQUEST['page']:0;
    	if($offset>0)  $offset=($offset-1)*$this->limit;
    	if(!empty($user_id))
    	{
    		$result=$this->question_obj->get_my_question_list($user_id,$this->limit,$offset); //用户主页-我的提问
    		$result['code']=200;
    	}
    	else 
    	{
    		echo json_encode(array('code'=> 501,'msg'=>'参数user_id不能为空; ')); exit;
    	}
    	echo json_encode($result);
    	exit;
    }
    
    /* AJAX-用户主页-我的解答列表*/
    public function action_get_my_answer_list()
    {
    	$result=null;
    	$user_id=isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
    	$offset=isset($_REQUEST['page'])?$_REQUEST['page']:0;
    	if($offset>0)  $offset=($offset-1)*$this->limit;
    	if(!empty($user_id))
    	{
    		$result=$this->answer_obj->get_my_answer_list($user_id,$this->limit,$offset); //用户主页-我的提问
    		$result['code']=200;
    	}
    	else
    	{
    		echo json_encode(array('code'=> 501,'msg'=>'参数user_id不能为空; ')); exit;
    	}
    	echo json_encode($result);
    	exit;
    }
    
    /*AJAX-新增搜索记录*/
    public function action_insert_search()
    {
    	$content=isset($_REQUEST['content'])?$_REQUEST['content']:'';
    	$user_id =$this->isLogins() ? $this->userInfo()->user_id : 0; // 获取登录user_id
    	if(!empty($content))
    	{
    		$search_obj = new Service_Business_Search();
    		$id=$search_obj->insert($content,$user_id);
    		echo json_encode(array('code'=> 200));exit;
    	}
    }

    /*AJAX-修改用户签名*/
    public function action_update_user_sign()
    {
    	$this->common_not_login();
    	$sign=isset($_REQUEST['sign'])?$_REQUEST['sign']:'';
    	$user_id = $this->isLogins() ? $this->userInfo()->user_id : 0; // 获取登录user_id
    	if(!empty($sign)){
    		$sign=validations::truncateStr($sign,50);
    	}
    	$sign_obj=new Service_Business_Search();
    	$is_sucess=$sign_obj->updateUserSignature($user_id, $sign);
    	$this->userstat_obj->del_cache_user_info($user_id);
    	echo json_encode(array('code'=> 200,'msg'=>$is_sucess));exit;
    }
    
    
    
    
    public function action_searchBusiness() {
        $post = Arr::map("HTML::chars", $this->request->post());
        $word = secure::secureInput(secure::secureUTF(arr::get($post, 'word', '')));
        $test = new Service_Business_Search();
        $return = $test->getWordSearch($word);
        echo json_encode($return);
    	exit;
    }
    
    public function action_searchProject() {
        $post = Arr::map("HTML::chars", $this->request->post());
        $word = secure::secureInput(secure::secureUTF(arr::get($post, 'word', '')));
        $new = new Service_Business_Search();
        $return = $new->searchPro($word);
        echo json_encode($return);
    	exit;
    }
    
    public function action_getSearchAjax() {
        $post = Arr::map("HTML::chars", $this->request->post());
        $word = secure::secureInput(secure::secureUTF(arr::get($post, 'word', '')));
        $type = secure::secureInput(secure::secureUTF(arr::get($post, 'type', '')));
        $page = secure::secureInput(secure::secureUTF(arr::get($post, 'page', 2)));
        $new = new Service_Business_Search();
        $return = array();
        if($word) {
        switch ($type)
            {
            case 1:
              $return = $new->searchZixun($word, $page);
              break;
            case 2:
              $return = $new->searchWen($word, $page);
              break;
            case 3:
              $return = $new->searchTag($word, $page);
              break;
            case 3:
              $return = $new->searchUser($word, $page);
              break;
            default:
              $return = $new->searchWen($word, $page);
            }
        }
            $return = array('data' => $return);
        echo json_encode($return);
    	exit;
    }
    
    public function action_captcha($group = 'default') {
        Captcha::instance($group)->render(FALSE);
        Captcha::instance()->update_response_session();
    }
}