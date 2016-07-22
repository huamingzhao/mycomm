<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生意帮-统计赞-详情
 * @author 兔毛 2014-06-11
 */
class Service_Business_NiceDetail{
	protected static $_instance=null;
	protected static $nice_detail_model=null;
	protected static $userstat_obj=null;
	protected static $config_obj = null;  //缓存配置文件
	protected static $redis = null;       //redis对象
	
	//单例模式
	public static function getInstance() {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
			self::$nice_detail_model = ORM::factory("Business_NiceDetail");
			self::$userstat_obj=Service_Business_UserStat::getInstance();
			self::$config_obj = Kohana::$config->load('cache_name');
			self::$redis = Cache::instance('redis');
		}
		return self::$_instance;
	}

	
	/**
	 * 是否存在记录
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $answer_id：解答问题id
	 */
	public function is_has_record($question_id,$user_id,$answer_id=0)
	{
		$count = self::$nice_detail_model->where('question_id','=',$question_id)
										->where('answer_id','=',$answer_id)
										->where('user_id','=',$user_id)
										->count_all();
		return $count;
	}
	
	
	/**
	 * 根据问题id+回复id，得到哪些用户进行了赞
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $answer_id：回复id
	 */
    public function get_nice_user_info($question_id,$answer_id)
	{
		$nice_user_info=null;
		//echo $question_id.'---'.$answer_id;
		$data_result = DB::select('user_id')->from('business_nice_detail')
		->distinct('user_id')
		->where('question_id','=',$question_id)
		->where('answer_id','=',$answer_id)
		->order_by('update_time','desc')
		->execute()
		//d($data_result);
		->as_array();
		foreach ($data_result as $key=>$value)
		{
			$user_id=$value['user_id'];
			$user_result=self::$userstat_obj->get_user_info($user_id);
			if(!empty($user_result))
			{
				$nice_user_info[$user_id]=$user_result;
			}
		}
		return $nice_user_info;
	}
	
	
	/**
	 * 点赞详情
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $answer_id：解答问题id（数组）
	 */
	public function get_nice_detail_info($question_id,$user_id,$array_answer_ids=0)
	{
		self::$nice_detail_model->where('question_id','=',$question_id);
		if(!empty($array_answer_ids)) self::$nice_detail_model->where('answer_id','in',$array_answer_ids);
		$result = self::$nice_detail_model->where('user_id','=',$user_id)->find_all();
	    return $result;
	}
	
	
	//PS: 0-未赞，1-已赞；
    /**
     * X问题是否被某用户 赞
     * @param unknown_type $question_id：问题id
     * @param unknown_type $user_id：用户id
     */
	public function is_nice_question($question_id,$user_id)
	{
		$is_nice=0;
		$count=$this->is_has_record($question_id,$user_id,0);
		if($count>0)
	    	$is_nice=1;
		return $is_nice;
	}
	
	
	/**
	 * X回复是否被某用户 赞
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $array_answer_ids：解答问题id（数组）
	 * @param unknown_type $user_id：用户id
	 */
	public function is_nice_answer($question_id,$array_answer_ids,$user_id)
	{
		$result=null;
		$data_result=$this->get_nice_detail_info($question_id,$user_id,$array_answer_ids);
		$count=count($data_result);
		if($count>0)
		{
			foreach ($data_result as $key=>$value){
				$data_info=$value->as_array();
				$result[$value->answer_id]=1;
			}
		}
		return $result;
	}
	
	
    /**
	 * X用户对问题、答案，点赞、取消赞的操作
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $answer_id：解答问题id（数组）
	 * @param unknown_type $talk_id：话题id
	 */
	public function oper_nice_question_and_answer($question_id,$user_id,$answer_id=0,$talk_id=0)
	{
		$field_name='nice_count';
		$table_name='business_nice_detail';
		$result=$nice_user_info=null;
		$is_nice=0;
		$count=$this->is_has_record($question_id,$user_id,$answer_id);
		if($count>0)
		{
			if(empty($answer_id))
				$is_delete=DB::delete($table_name)->where('question_id','=',$question_id)->where('user_id','=',$user_id)->execute();
		    else 
		    	$is_delete=DB::delete($table_name)->where('question_id','=',$question_id)->where('user_id','=',$user_id)->where('answer_id','=',$answer_id)->execute();
		    //问题、回复点踩数-1
		    Service_Business_Stat::getInstance()->oper_stat($question_id,$answer_id,$talk_id,$field_name,'jian'); 
		    Service_Business_UserStat::getInstance()->oper_user_stat($user_id,$field_name,'jian'); //用户点踩数+1
		}
		else
		{
			self::$nice_detail_model->question_id=$question_id;
			self::$nice_detail_model->answer_id=$answer_id;
			self::$nice_detail_model->user_id=$user_id;
			self::$nice_detail_model->is_delete=0;
			self::$nice_detail_model->ip=ip2long(Request::$client_ip); 
			self::$nice_detail_model->create_time=time();
			self::$nice_detail_model->update_time=time();
			self::$nice_detail_model->save();
			//问题、回复点踩数+1
			Service_Business_Stat::getInstance()->oper_stat($question_id,$answer_id,$talk_id,$field_name,'jia'); 	
			Service_Business_UserStat::getInstance()->oper_user_stat($user_id,$field_name,'jia'); //用户点踩数+1
			$is_nice=1;  //保存记录成功，则表示用户做了赞的动作
		}
		if(!empty($answer_id))
		{
			//根据问题id+回复id，得到哪些用户进行了赞
			$nice_user_info=Service_Business_NiceDetail::getInstance()->get_nice_user_info($question_id,$answer_id);	
			if(!empty($nice_user_info)){
				$nice_user_info=array_values($nice_user_info);
			}

		}
		$nice_count=Service_Business_Stat::getInstance()->get_stat_info($question_id,$answer_id,0,'nice_count'); //获取问题的赞统计
		$result=array('question_id'=>$question_id,
					  'answer_id'=>$answer_id,
					  'nice_user_info'=>$nice_user_info,
					  'is_nice'=>$is_nice,
					  'nice_count'=>$nice_count
					);
		return $result;	
	}
}
?>
