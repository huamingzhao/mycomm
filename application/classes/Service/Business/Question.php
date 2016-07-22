<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生意帮-发起问题
 * @author 兔毛 2014-06-09
 */
class Service_Business_Question{
	protected  static $_instance = null;
	protected  static $talk=null;
	protected  static  $answer_obj=null;
	protected  static  $question_model=null;
	protected  static  $config_obj=null;  //缓存配置文件
	protected  static  $redis=null;       //redis对象
	
	//单例模式
	public static function getInstance() {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
			self::$talk=new Service_Business_Talk();
			self::$answer_obj=Service_Business_Answer::getInstance();
			self::$question_model = ORM::factory("Business_Question");
			self::$config_obj = Kohana::$config->load('cache_name');
			self::$redis = Cache::instance('redis');
		}
		return self::$_instance;
	}
	
	
	/**
	 * 新增：生意帮-发起问题
	 * @param unknown_type $array_data：数组
	 * @param unknown_type $user_id：用户id
	 */
	public function insert($array_data,$user_id)
	{
		try{
			 self::$question_model->title=$array_data['title'];
			 self::$question_model->content=$array_data['content'];
			 self::$question_model->talk_id=$array_data['talk_id'];
			 self::$question_model->parent_id=isset($array_data['parent_id']) ? $array_data['parent_id'] : 0; //父节点
			 self::$question_model->root_id=isset($array_data['root_id']) ? $array_data['root_id'] : 0;  //根节点
			 self::$question_model->user_id=$user_id;
			 self::$question_model->is_delete=isset($array_data['is_delete']) ? $array_data['is_delete'] : 0;
			 self::$question_model->is_hidden=isset($array_data['is_hidden']) ? $array_data['is_hidden'] : 0;  //是否匿名：0-实际用户，1-匿名
			 self::$question_model->ip=ip2long(Request::$client_ip);      //ip
			 self::$question_model->create_time=time();
			 self::$question_model->update_time=time();
			 self::$question_model->create();
			 $question_id=self::$question_model->id;
			 if($question_id>0)
			 {
			 	Service_Business_UserStat::getInstance()->oper_user_stat($user_id,'question_count');  //用户问题数+1
			 	$search_obj = new Service_Api_Search();
			 	$search_obj->updateWenIndex($question_id);	 //对发布的问题，列入搜索中
			 }
			 return true;
		}catch(Kohana_Exception $e){
			return false;
		}
	}
	
	/**
	 * X问题信息
	 * @param unknown_type $question_id：问题id
	 * @return unknown：数组
	 */
	public function get_one_question($question_id)
	{
		$result=null;
		$cache_key= sprintf(self::$config_obj->x_question_info,$question_id);
		self::$redis->delete($cache_key);
		$result=self::$redis->get($cache_key);
		if(empty($result))
		{
			$data_result=ORM::factory("Business_Question")->where('id','=',$question_id)->find();
			if($data_result->loaded())
			{
				$result=$data_result->as_array();
				$talk_info=self::$talk->get_talk_list();
				$result['talk_name']=$talk_info[$result['talk_id']]['name'];
				self::$redis->set($cache_key,$result,self::$config_obj->a_week_time);
			}			
		}
		return $result;
	}
	
	
	/**
	 * 根据话题id，获取问题总数
	 * @param unknown_type $talk_id：话题id
	 */
	public function get_talk_question_count($talk_id)
	{
		$cache_key= sprintf(self::$config_obj->talk_question_count,$talk_id);
		$count=self::$redis->get($cache_key);
		if(empty($count))
		{
		    $count=ORM::factory("Business_Question")->where('talk_id','=',$talk_id)->count_all();
		    self::$redis->set($cache_key,$count,self::$config_obj->two_minutes_time);
		}
		return $count;
	}
	
	
	/**
	 * 首页-问题列表
	 * @param unknown_type $talk_id：默认0-全部；
	 * @param unknown_type $type：默认new：最新； hot:热门; wait:等待回复
	 * @param unknown_type $limit: 获取几条
	 * @param unknown_type $offset：从X条开始
	 */
	public function get_question_list($talk_id=0,$type='new',$limit,$offset=0)
	{
		$question_info=$array_question_ids=$result=null;  //$array_question_ids,批量获取问题的 浏览数+回复数
		$count=0;
		$take=$limit+1;
		if($type=='new' ||  $type=='wait')
		{
		   $answer_question_ids=null;
           if($type=='wait')
           {
				$data_result = DB::select('question_id')->from('business_answer')
				->distinct('question_id')
				->execute()
				->as_array();
				foreach ($data_result as $key=>$value)
				{
					$answer_question_ids[$key]=$value['question_id'];
				}
				//d($answer_question_ids);
           }
			if(!empty($answer_question_ids))  self::$question_model->where('id','not in',$answer_question_ids);
			if(!empty($talk_id)) self::$question_model->where('talk_id','=',$talk_id);
			$data_result=self::$question_model->order_by('update_time','desc')->limit($take)->offset($offset)->find_all();
			$count=count($data_result);
			if($count>0)
				$question_info=$data_result;
		}
		if($type=='hot')
		{
			/* SELECT q.*,s.* from czzs_business_question as q LEFT JOIN czzs_business_stat as s on q.id=s.question_id and s.answer_id=0
			   ORDER BY s.answer_count desc, q.update_time desc*/
			self::$question_model ->select('*')->join('business_stat', 'LEFT')
			             ->on('business_question.id', '=', 'business_stat.question_id')
			             ->on('business_stat.answer_id', '=', 'business_question.root_id');      // 其实这里的条件是s.answer_id=0，但是=0没有对应的字段 
			if(!empty($talk_id)) self::$question_model->where('business_question.talk_id','=',$talk_id);
			$data_result=self::$question_model->order_by('business_stat.answer_count', 'DESC')
						 ->order_by('business_question.update_time', 'DESC')
				         ->limit($take)->offset($offset) 
			             ->find_all();
            $count=count($data_result);
			if($count>0)
				$question_info=$data_result;
		}		
		if(count($question_info)>0)
		{
			$result=$this->common_get_question_info($question_info,$limit);
		}
		return array('count'=>count($result),'has_next_page'=>$count>$limit ? 1:0,'data'=>$result);
	}
	
	
	/**
	 * 用户主页-我的提问列表
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $limit: 获取几条
	 * @param unknown_type $offset：从X条开始
	 */
	public function get_my_question_list($user_id,$limit,$offset=0)
	{
		$result=null;
		$count=$question_count=0;
		$take=$limit+1;
		//我的提问总数
		$question_count=self::$question_model->where('user_id','=',$user_id)->count_all();
		$question_info=self::$question_model->where('user_id','=',$user_id)->order_by('update_time','desc')
		               ->limit($take)->offset($offset)->find_all();
		$count=count($question_info); //分页得到的数据总数，用于判断是否存在下一页
		if(count($question_info)>0)
		{
			$result=$this->common_get_question_info($question_info,$limit);
		}
		$result=array('question_count'=>$question_count,'count'=>count($result),'has_next_page'=>$count>$limit ? 1:0,'data'=>$result);
		return $result;
	}
	
	/**
	 * 通用：问题列表数据的返回
	 * @param unknown_type $question_info：问题数据对象
	 */
	public function common_get_question_info($question_info,$limit)
	{
		$result=null;
		$time_obj=new Service_QuickPublish_Search(); //显示时间
		$talk_info=self::$talk->get_talk_list();
		$userstat_obj=Service_Business_UserStat::getInstance();
		$count=count($question_info);
		//若是总数>limit,则-1，是因为在判断是否有下一页的时候，多limit了1条数据
		if($count>$limit)
			$count=count($question_info)-1;
		for($item=0;$item<$count;$item++) 
		{
			$data_info=$question_info[$item]->as_array();
			$question_id=$data_info['id'];
			$user_id=$data_info['user_id'];
			$user_result=$userstat_obj->get_user_info($user_id);
			$last_answer_user=self::$answer_obj->get_last_answer_user($question_id); //最新回复X问题的用户信息
			$talk_id=$data_info['talk_id'];
			$array_question_ids[$item]=$question_id;
			$question_result[$question_id]=array('question_id'=>$question_id,
						'title'=>$data_info['title'],
						'pub_time'=>$time_obj->jishuanTime($data_info['update_time']),
						'user_id'=>$user_id,
						'user_name'=>$user_result['user_name'],
						'user_photo'=>$user_result['user_photo'],
						'talk_id'=>$talk_id,
						'talk_name'=>$talk_info[$talk_id]['name'],
						'answer_user_id'=>$last_answer_user['user_id'],
						'answer_user_name'=>$last_answer_user['user_name']
				);
		}
		if(!empty($array_question_ids))
		{
		    //批量获取问题的 浏览数+回复数
			$stat_info=Service_Business_Stat::getInstance()->get_question_stat($array_question_ids);
			//d($stat_info);
			foreach($stat_info as $key=>$value)
			{
			  $result[$key]=array_merge($question_result[$key],$stat_info[$key]);
			}
		}
		$result=array_values($result);
		return $result;			
	}
	
	/**
	 * 热门话题
	 */
	public function get_hot_talk_question($limit=10)
	{
		//select count(0),talk_id from  czzs_business_question GROUP BY talk_id ORDER BY talk_id desc
		$result=null;
		$data_result=DB::select('talk_id',array(DB::expr('COUNT(0)'),'count'))->from("business_question")
		->group_by('talk_id')
		->order_by('count','desc')
		->limit($limit)
		->execute()
		->as_array();
		//print_r($data_result);
		if(count($data_result)>0)
		{
			$talk_info=self::$talk->get_talk_list();
			foreach ($data_result as $key=>$value)
			{
				$result[$key]=array_merge($value,array('talk_name'=>$talk_info[$value['talk_id']]['name']));
			}
		}
		return $result;
	}
	
	/**
	 * 查看问题，添加浏览数
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $talk_id：话题id
	 */
	public function insert_view_count($question_id,$talk_id=0)
	{
		Service_Business_Stat::getInstance()->oper_stat($question_id,0,$talk_id,'view_count','jia');  //问题回复数+1
	}
	
	
	/**
	 * X问题详情页面
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $user_id：当前登录用户id
	 */
	public function get_question_detail($question_id,$user_id)
	{
		$question_info=$this->get_one_question($question_id);
		if($question_info)
		{
			$talk_id=$question_info['talk_id'];
			$time_obj=new Service_QuickPublish_Search(); //显示时间
			$question_info['pub_time']=$time_obj->jishuanTime($question_info['update_time']);
			$stat=Service_Business_Stat::getInstance()->get_question_nice_against_stat($question_id);
			$question_info=array_merge($question_info,$stat[$question_id]); //问题的点赞数统计
			if(empty($user_id))
			{
				$question_info['is_nice']=0;
				$question_info['is_against']=0;
			}
			else
			{
				$question_info['is_nice']=Service_Business_NiceDetail::getInstance()->is_nice_question($question_id,$user_id);
				$question_info['is_against']=Service_Business_AgainstDetail::getInstance()->is_against_question($question_id,$user_id);
			}
			$last_update_time=Service_Business_Stat::getInstance()->get_last_update_time($question_id);
			//X问题的最近活跃时间
			$question_info['last_update_time']=empty($last_update_time) ? $question_info['pub_time'] : $last_update_time;
		}
		return $question_info;
	}

	/**
	 * 相关问题 
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $talk_id：话题id
	 * @param unknown_type $question_id: 问题id
	 * @param unknown_type $limit：取几条
	 */
	public function get_the_same_question($user_id,$talk_id,$question_id,$limit=5)
	{	
		//逻辑如下【总数5条】：
		//1. 此用户发表的最新（去除目前问题id）
		//2. 不足，+ 此话题类下的回复数最多的5条
		//3. 不足，最新（去除目前问题id）
		$cache_key= sprintf(self::$config_obj->the_same_question,$question_id);
		$result=self::$redis->get($cache_key);
		//self::$redis->delete($cache_key);
		if(empty($result))
		{
			//echo $user_id.'---'.$talk_id.'--'.$question_id;
			$data_result=null;
			//$talk_id=3;
			//$user_id=644;
			$first_result=DB::select('id','title')->from("business_question")
						->where('user_id','=',$user_id)->where('id','not in',array($question_id))
						->order_by('update_time','desc')
						->limit($limit)
						->execute()
						->as_array();
			$first_count=count($first_result);
			$also_count=$limit-$first_count;
			if($also_count==0)  //5条已满,直接返回数据
				$data_result=$first_result;
			else
			{
				//反之，进行逻辑2：话题下的最新问题
				$array_question_ids=array($question_id);  //初始：排除现问题id
				if($first_count>0)
				{
					foreach($first_result as $key=>$value)
					{
						$array_question_ids[$key+1]=$value['id'];  //排除逻辑1中，得到的问题id
					}
					$array_question_ids=array_values($array_question_ids);
				}
				//d($array_question_ids,false);
				/*SELECT DISTINCT `czzs_business_question`.`id`, `czzs_business_question`.`title` FROM `czzs_business_question` LEFT JOIN `czzs_business_stat` ON (`czzs_business_question`.`id` = `czzs_business_stat`.`question_id`)
				WHERE `czzs_business_stat`.`talk_id` = '5' AND `czzs_business_stat`.`question_id` NOT IN (2) and `czzs_business_stat`.`answer_id` =0
				and `czzs_business_stat`.`answer_count` >0
			    ORDER BY `czzs_business_stat`.`answer_count` DESC LIMIT 10*/
				$two_result=DB::select('business_question.id','business_question.title')->from("business_question")
						    ->join('business_stat','left')
						    ->on('business_question.id', '=', 'business_stat.question_id')
					        ->distinct('business_question.id')  
							->where('business_stat.talk_id','=',$talk_id)->where('business_stat.question_id','not in',$array_question_ids)
							->where('business_stat.answer_count','>',0)
							->where('business_stat.answer_id','=',0)
							->order_by('business_stat.answer_count', 'DESC')
							->limit($also_count)
							->execute()
							->as_array();
				//d($two_result);
				$two_count=count($two_result);
				if($first_count>0 && $two_count==0) $data_result=$first_result;
				if($first_count==0 && $two_count>0) $data_result=$two_result;
				if($first_count>0 && $two_count>0)  $data_result=array_merge($first_result,$two_result);
				$also_count=$limit-count($data_result);
				$now_data_count=count($data_result); //逻辑1+2,数据合并后，现数据总数
	            if($also_count>0) //还没有达到limit的值，则进行逻辑3：最新问题
	            {
	            	$array_question_ids=array($question_id); //初始：排除现问题id
	            	if($now_data_count>0)
	            	{
	            		foreach($data_result as $key=>$value)
	            		{
	            			$array_question_ids[$key+1]=$value['id']; //逻辑1+2,数据合并后，排除id值
	            		}
						$array_question_ids=array_values($array_question_ids);
	            	}
	            	$three_result=DB::select('id','title')->from("business_question")
					            	->where('id','not in',$array_question_ids)
					            	->order_by('update_time', 'DESC')
					            	->limit($also_count)
					            	->execute()
					            	->as_array();
	            	$three_count=count($three_result);
	            	if($three_count>0 && $now_data_count==0)  $data_result=$three_result;
	            	if($three_count>0 && $now_data_count>0)   $data_result=array_merge($data_result,$three_result);
	            } 
			}
			$count=count($data_result);
			$result=array('count'=>$count,'data'=>$data_result);
			if($result) self::$redis->set($cache_key,$result,self::$config_obj->two_minutes_time);
		}
		return $result;
	}
	
}
?>
