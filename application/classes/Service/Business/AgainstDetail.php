<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生意帮-统计踩/反对-详情
 * @author 兔毛 2014-06-11
 */
class Service_Business_AgainstDetail{
	protected static $_instance=null;
	protected static $against_detail_model=null;
	protected static $config_obj = null;  //缓存配置文件
	protected static $redis = null;       //redis对象
	protected static $_obj = null;
	
	//单列模式
	public static function getInstance ()
	{
		if ( !( self::$_obj instanceof self ) )
		{
			self::$_obj = new self ();
		}
		return self::$_obj;
	}
	
	//单例模式
	public static function getInstance() {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
			self::$against_detail_model = ORM::factory("Business_AgainstDetail");
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
		$count=self::$against_detail_model->where('question_id','=',$question_id)
											->where('user_id','=',$user_id)
											->where('answer_id','=',$answer_id)
											->count_all();
		return $count;
	}
	
	/**
	 * 踩/反对详情
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $answer_id：解答问题id（数组）
	 */
	public function get_against_detail_info($question_id,$user_id,$array_answer_ids=0)
	{
		self::$against_detail_model->where('question_id','=',$question_id)->where('user_id','=',$user_id);
		if(!empty($array_answer_ids)) self::$against_detail_model->where('answer_id','in',$array_answer_ids);
		$result = self::$against_detail_model->find_all();
	    return $result;
	}
	
	//PS: 0-未踩，1-已踩；

    /**
     * X问题是否被某用户 踩/反对
     * @param unknown_type $question_id：问题id
     * @param unknown_type $user_id：用户id
     */
	public function is_against_question($question_id,$user_id)
	{
		$is_against=0;
		$count=$this->is_has_record($question_id,$user_id,0);
		if($count>0)
	    	$is_against=1;
		return $is_against;
	}
	
	
	/**
	 * X回复是否被某用户  踩/反对
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $array_answer_ids：解答问题id（数组）
	 * @param unknown_type $user_id：用户id
	 */
	public function is_against_answer($question_id,$array_answer_ids,$user_id)
	{
		$result=null;
		$data_result=$this->get_against_detail_info($question_id,$user_id,$array_answer_ids);
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
	 * X用户对问题、答案，点踩、取消踩的操作
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $answer_id：解答问题id（数组）
	 * @param unknown_type $talk_id：话题id
	 */
	public function oper_against_question_and_answer($question_id,$user_id,$answer_id=0,$talk_id=0)
	{
		$field_name='against_count';
		$table_name='business_against_detail';
		$count=$this->is_has_record($question_id,$user_id,$answer_id);
		$is_against=0;
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
			self::$against_detail_model->question_id=$question_id;
			self::$against_detail_model->answer_id=$answer_id;
			self::$against_detail_model->user_id=$user_id;
			self::$against_detail_model->is_delete=0;
			self::$against_detail_model->ip=ip2long(Request::$client_ip); 
			self::$against_detail_model->create_time=time();
			self::$against_detail_model->update_time=time();
			self::$against_detail_model->save();
			//问题、回复点踩数+1
			Service_Business_Stat::getInstance()->oper_stat($question_id,$answer_id,$talk_id,$field_name,'jia'); 	
			Service_Business_UserStat::getInstance()->oper_user_stat($user_id,$field_name,'jia'); //用户点踩数+1
			$is_against=1; //保存记录成功，则表示用户做了踩的动作
		}
		$result=array('question_id'=>$question_id,
					  'answer_id'=>$answer_id,		
					  'is_against'=>$is_against
		);
		return $result;
	}
}
?>
