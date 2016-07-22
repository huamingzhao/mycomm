<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生意帮-解答问题
 * @author 兔毛 2014-06-09
 */
class Service_Business_Answer{
	
	protected static $_instance = null;
	protected  static  $answer_model=null;
	protected  static $userstat_obj=null;
	protected  static  $config_obj=null;  //缓存配置文件
	protected  static  $redis=null;       //redis对象
	
	//单例模式
	public static function getInstance() {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
			self::$answer_model = ORM::factory("Business_Answer");
			self::$userstat_obj=Service_Business_UserStat::getInstance();
			self::$config_obj = Kohana::$config->load('cache_name');
			self::$redis = Cache::instance('redis');
		}
		return self::$_instance;
	}
	
	/**
	 * 新增：生意帮-解答问题
	 * @param unknown_type $array_data：数组
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $question_id：问题id
	 */
	public function insert($array_data,$user_id,$question_id)
	{
		try{		
			self::$answer_model->question_id=$question_id;
			self::$answer_model->content=$array_data['content'];
			self::$answer_model->talk_id=$array_data['talk_id'];
			self::$answer_model->user_id=$user_id;
			self::$answer_model->is_delete=isset($array_data['is_delete']) ? $array_data['is_delete'] : 0;
			self::$answer_model->is_hidden=isset($array_data['is_hidden']) ? $array_data['is_hidden'] : 0;  //是否匿名：0-实际用户，1-匿名
			self::$answer_model->ip=ip2long(Request::$client_ip);      //ip
			self::$answer_model->create_time=time();
			self::$answer_model->update_time=time();
			self::$answer_model->create();
			$answer_id=self::$answer_model->id;
			if($answer_id>0)
			{
				self::$userstat_obj->oper_user_stat($user_id,'answer_count'); //用户回复数+1
				Service_Business_Stat::getInstance()->oper_stat($question_id,0,$array_data['talk_id'],'answer_count');  //问题回复数+1
			}
			return $answer_id;
		}catch(Kohana_Exception $e){
			return false;
		}
	}

	
	/**
	 * 最新回复X问题的用户信息
	 * @param unknown_type $question_id：问题id
	 * @return NULL:string
	 */
	public function  get_last_answer_user($question_id)
	{
		$user_info=null;
		$user_id=0; $user_name='';
		$data_result=DB::select('user_id')->from("business_answer")->where('question_id','=',$question_id)
			->order_by('update_time','desc')->limit(1)
			->execute()
			->as_array();
		if(count($data_result)>0)
		{
			$user_id=$data_result[0]['user_id'];
			$user_result=self::$userstat_obj->get_user_info($user_id);	
			$user_name=isset($user_result['user_name'])? $user_result['user_name'] :'';
		}

		$user_info=array('user_id'=>$user_id,'user_name'=>$user_name);
		//print_r($user_info);
		return $user_info;
	}
	
	
	/**
	 * 获取某问题的回复列表
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $type：赞数、时间排序
	 * @param unknown_type $limit: 获取几条
	 * @param unknown_type $offset：从X条开始
	 * @param unknown_type $user_id: 登录用户id
	 * @param unknown_type $answer_id：回复问题id（解答成功后，返回数据）
	 */
	public function get_answer_list($question_id,$type='count',$limit,$offset=0,$user_id=0,$answer_id=0)
	{
		//echo $question_id.'--'.$type.'--'.$limit.'--'.$offset.'--'.$user_id.'---'.$answer_id;
		$result=$array_answer_ids=null;
		$count=0;
		$take=$limit+1;
		/*SELECT a.*,s.* from czzs_business_answer as a LEFT JOIN czzs_business_stat as s 
		  on a.id=s.answer_id where a.question_id=2 ORDER BY s.nice_count desc, a.update_time desc*/
		$answer_model=ORM::factory("Business_Answer");
		$answer_count=$answer_model->where('question_id','=',$question_id)->count_all();
		if(empty($answer_id))
		{
			$answer_model ->select('*')->join('business_stat', 'LEFT')
								->on('business_answer.id', '=', 'business_stat.answer_id')
								->where('business_answer.question_id', '=', $question_id);    
			if($type=='count')
			  $answer_model->order_by('business_stat.nice_count', 'DESC');
			else
			  $answer_model->order_by('business_answer.update_time', 'DESC');
			$data_result= $answer_model->limit($take)->offset($offset)->find_all();
		}
		else
		{
			$data_result=ORM::factory("Business_Answer")->select('*')->join('business_stat', 'LEFT')
							->on('business_answer.id', '=', 'business_stat.answer_id')
							->where('business_answer.question_id', '=', $question_id)
							->where('business_answer.id', '=', $answer_id)
							->find_all();
		}
		//d($data_result);
		$for_count=$count=count($data_result);
		if($count>0)
		{
			//若是总数>limit,则-1，是因为在判断是否有下一页的时候，多limit了1条数据
			if($count>$limit)
				$for_count=$for_count-1;
			$time_obj=new Service_QuickPublish_Search(); //显示时间
			foreach($data_result as $key=>$value)
			{
				if($key<$for_count)
				{
					$data_info=$value->as_array();
					$answer_id=$data_info['id'];
					$answer_user_id=$data_info['user_id'];
					$answer_user_result=self::$userstat_obj->get_user_info($answer_user_id);
					$result[$answer_id]=array('answer_id'=>$data_info['id'],
												'question_id'=>$data_info['question_id'],
												'content'=>$data_info['content'],
												'talk_id'=>$data_info['talk_id'],
												'answer_user_id'=>$answer_user_id,
												'answer_user_name'=>$answer_user_result['user_name'],
												'answer_user_photo'=>$answer_user_result['user_photo'],
												'pub_time'=>$time_obj->jishuanTime($data_info['update_time']),
												'nice_count'=>isset($data_info['nice_count']) ? $data_info['nice_count']:0,
												'against_count'=>isset($data_info['against_count'])?$data_info['against_count']:0
					);
					//根据问题id+回复id，得到哪些用户进行了赞
					$nice_user_info=Service_Business_NiceDetail::getInstance()->get_nice_user_info($question_id,$answer_id);
					$result[$answer_id]['is_nice']=0;  //初始，用户未-赞此回复
					$result[$answer_id]['is_against']=0;  //初始，用户未-踩此回复
					if(!empty($user_id))
					{
						if($nice_user_info)
						{
							if(array_key_exists($user_id, $nice_user_info)) //user_id,存在于赞过的列表中
							{
								$result[$answer_id]['is_nice']=1;  //所以已赞过
							}	
						}
						//此回复是否被X用户踩
						$result[$answer_id]['is_against']=Service_Business_AgainstDetail::getInstance()->is_has_record($question_id,$user_id,$answer_id)*1;
					}
					$result[$answer_id]['nice_user_info']=$nice_user_info ? array_values($nice_user_info) : null;
				}

			}
			$result=array_values($result);
		}
		//d($result);
		return array('answer_count'=>$answer_count,'count'=>count($result),'has_next_page'=>$count>$limit ? 1:0,'data'=>$result);
	}
	
	/**
	 * 用户主页-我的解答列表
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $limit: 获取几条
	 * @param unknown_type $offset：从X条开始
	 */
	public function get_my_answer_list($user_id,$limit,$offset)
	{
		$result=null;
		$count=$answer_count=0;
		$take=$limit+1;
		//我的提问总数
		$answer_count=self::$answer_model->where('user_id','=',$user_id)->count_all();
		$data_result=DB::select('business_answer.question_id','business_answer.id','content','nice_count','business_answer.update_time')
		             ->from('business_answer')
					 ->join('business_stat','left')
			         ->on('business_answer.id', '=', 'business_stat.answer_id')
					 ->where('business_answer.user_id', '=', $user_id)
					 ->order_by('business_answer.update_time', 'DESC')
					 ->limit($take)
			         ->offset($offset)
			         ->execute()
			         ->as_array();
		$for_count=$count=count($data_result);
		//d($data_result);
		if($count>0)
		{
			//若是总数>limit,则-1，是因为在判断是否有下一页的时候，多limit了1条数据
			if($count>$limit)
				$for_count=$for_count-1;
			$time_obj=new Service_QuickPublish_Search(); //显示时间
			$question_obj=Service_Business_Question::getInstance();
			foreach($data_result as $key=>$value)
			{
				if($key<=$for_count)
				{
					$question_id=$value['question_id'];
					$question_info=$question_obj->get_one_question($question_id);
					if(isset($question_info['title'])) //X问题的title存在，则显示数据
					{
						$answer_id=$value['id'];
						$content=$value['content'];
						$result[$key]=array('answer_id'=>$answer_id,
								'question_id'=>$question_id,
								'title'=> $question_info['title'],
								'content'=>$content,
								'split_content'=>validations::truncateStr($content,55,'……'),
								'pub_time'=>$time_obj->jishuanTime($value['update_time']),
								'nice_count'=>isset($value['nice_count']) ? $value['nice_count']:0
						);
					}
				}
			}
		}
		$result=array('answer_count'=>$answer_count,'count'=>count($result),'has_next_page'=>$count>$limit ? 1:0,'data'=>$result);
		return $result;
	}
}
?>
