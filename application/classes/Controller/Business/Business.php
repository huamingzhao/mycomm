<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生意帮
 * @author：兔毛 2014-06-09
 */
class Controller_Business_Business extends Controller_Business_Basic{
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
	
	//测试
	public function action_test()
	{
		/*$question_id=validations::get_param_value('question_id', 0,'int');
		$question_info=$this->question_obj->get_question_detail($question_id,0);
		echo '<pre>';print_r($question_info);echo '</pre>';*/
		$user_id=isset($_REQUEST['user_id'])?$_REQUEST['user_id']:185171;
		print_r($this->userstat_obj->get_user_info($user_id));
	}
	
	/* 通用：获取登录用户信息 */
	public function common_login_user_info($content)
	{
		$content->login_user_id=$this->template->login_user_id;
		$content->login_user_name=$this->template->login_user_name;
		$content->login_user_photo=$this->template->login_user_photo;
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
	
	/* 通用： 首页-左侧列表 */
	public function common_home_left_list($talk_id,$type,$offset)
	{
		$result=$this->question_obj->get_question_list($talk_id,$type,$this->limit,$offset); //首页-问题列表
		return $result;
	}
	
	/* 首页 */
	public function action_index()
	{
		$content = View::factory("business/index");
		$this->template->content = $content;
		$talk_list=$this->talk_obj->get_talk_list();  //话题列表
		$content->talk_list=$talk_list;
		/* 左侧列表  new：最新； hot：热门；wait： 等待回复 Start */
		$talk_id=isset($_REQUEST['talk_id'])?$_REQUEST['talk_id']:0;
		$content->now_talk_id=$talk_id;
		$new_question_info=$this->common_home_left_list($talk_id,'new',0);
		$hot_question_info=$this->common_home_left_list($talk_id,'hot',0);
		$wait_question_info=$this->common_home_left_list($talk_id,'wait',0);
		$content->new_question_info=$new_question_info;
		$content->hot_question_info=$hot_question_info;
		$content->wait_question_info=$wait_question_info;
		/* 左侧列表  new：最新； hot：热门；wait： 等待回复 End */
		/* 右侧列表  热门话题、热门用户  Start */
		$hot_talk_question=$this->question_obj->get_hot_talk_question(5); 	//热门话题
		$hot_user=$this->userstat_obj->get_hot_user(5);  //热门用户
		$content->hot_talk_question=$hot_talk_question;
		$content->hot_user=$hot_user;
		/* 右侧列表  热门话题、热门用户  End */		
		if($talk_id){
			$this->template->title = isset($talk_list[$talk_id]['name']) ? $talk_list[$talk_id]['name'].'问答-生意帮在线问答' : '';
			$this->template->keywords ='';
			$this->template->description ='';
		}else{
			$this->template->title = '生意帮在线问答';
			$this->template->keywords ='';
			$this->template->description ='';
		}		
		$content->talk_id=$talk_id;
	}
	
  
	
	/* 通用： 问题详情页-解答回复列表 */
	public function common_detail_answer_list($question_id,$type,$offset)
	{
		$user_id = $this->userInfo()? $this->userInfo()->user_id:0;
		$result=$this->answer_obj->get_answer_list($question_id,$type,$this->limit,$offset,$user_id); //首页-问题列表
		return $result;
	}
	
	/* 问题详情 */
	public function action_detail()
	{
		$content = View::factory("business/detail");
		$this->template->content = $content;
		$question_id=validations::get_param_value('question_id', 0,'int');
		if(empty($question_id))
			$this->common_error();
		else
		{
			$login_user_id=$this->isLogins() ?  $this->userInfo()->user_id:0;
			$question_info=$this->question_obj->get_question_detail($question_id,$login_user_id);
			$login_user_sign='';
			if(!empty($login_user_id))
			{
				$login_user_info=$this->userstat_obj->get_user_info($login_user_id);
				$login_user_sign=$login_user_info['user_sign'];
			}
			$content->login_user_sign=$login_user_sign;
			if($question_info)
			{
				//print_r($question_info);
				$question_user_id=$question_info['user_id'];
				$question_user_info=$this->userstat_obj->get_user_info($question_user_id);
				$talk_id=$question_info['talk_id'];
				$the_same_question=$this->question_obj->get_the_same_question($question_user_id,$talk_id,$question_id);//相关问题 
				$count_answer_list=$this->common_detail_answer_list($question_id,'count',0); //票数列表
				$new_answer_list=$this->common_detail_answer_list($question_id,'new',0); //最新列表
				//$question_info['is_nice']=1;
				//d($the_same_question,false);
				$content->question_id=$question_id;
				$content->talk_id=$talk_id;
				$content->question_info=$question_info;
				$content->question_user_info=$question_user_info;
				$content->count_answer_list=$count_answer_list;
				$content->new_answer_list=$new_answer_list;
				$content->the_same_question=$the_same_question;
				$this->common_login_user_info($content); //通用：获取登录用户信息
				$this->question_obj->insert_view_count($question_id,$question_info['talk_id']); //查看问题详情，添加浏览数
				$this->template->title = isset($question_info['title']) ? mb_substr($question_info['title'], 0,32) : '';
				$this->template->keywords = isset($question_info['title']) ? mb_substr($question_info['title'], 0,32) : '';
				$this->template->description = isset($count_answer_list['data'][0]['content']) ? mb_substr($count_answer_list['data'][0]['content'],0,80) : '';
			}
			else
				$this->common_error();
		}
	}
	
	
	
    /* 用户主页 */
	public function action_userinfo()
	{
		$content = View::factory("business/userinfo");
		$this->template->content = $content;
		/* 初始变量 Start */
		$question_info=$user_info=$answer_info=null;
		$last_update_time=''; //X用户的最近活跃时间
		$user_id=isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
		$login_user_id=$this->isLogins() ?  $this->userInfo()->user_id:0;
		$content->login_user_id=$login_user_id; //此处的用户id，是用于判断，是否显示 “修改资料”
		/* 初始变量 End */
		if(!empty($user_id))
		{
			$question_info=$this->question_obj->get_my_question_list($user_id,$this->limit,0); //用户主页-我的提问
			$answer_info=$this->answer_obj->get_my_answer_list($user_id,$this->limit,0); //用户主页-我的解答列表
			$last_update_time=$this->userstat_obj->get_last_update_time($user_id); //X用户的最近活跃时间
			$user_info=$this->userstat_obj->get_user_info($user_id);	
		}
		$content->question_info=$question_info;
		$content->last_update_time=$last_update_time;
		$content->user_info=$user_info;
		$content->answer_info=$answer_info;
		$content->user_id=$user_id;
		$this->template->title = '用户昵称（'.$user_info['user_name'].'）生意帮在线问答';	
		//d($content->answer_info);
	}  
        
        /**
         * 搜索页
         */
        public function action_search() {
            $get = Arr::map("HTML::chars", $this->request->query());
            $word = secure::secureInput(secure::secureUTF(arr::get($get, 'w', '')));
            $new = new Service_Business_Search();
            $searchUser = $new->searchUser($word);
            $searchZixun = $new->searchZixun($word);
            $searchWen = $new->searchWen($word);
            $searchTag = $new->searchTag($word);
            $content = View::factory("business/search");
            $content->searchUser = $searchUser;
            $content->searchZixun = $searchZixun;
            $content->searchWen = $searchWen;
            $content->searchTag = $searchTag;
            $content->word = $word;
            $this->template->content = $content;
            $this->template->title = '有关'.$word.'的搜索结果页-生意帮在线问答';            
        }
    
}