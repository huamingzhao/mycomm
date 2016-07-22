<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生意帮-统计、生意帮-统计-详情
 * @author 兔毛 2014-06-09
 */
class Service_Business_Stat{
	protected static $_instance = null;
	protected static $config_obj = null;  //缓存配置文件
	protected static $redis = null;       //redis对象
	
	//单例模式
// 	public static function getInstance() {
// 		if (!(self::$_instance instanceof self)) {
// 			self::$_instance = new self();
// 			self::$config_obj = Kohana::$config->load('cache_name');
// 			self::$redis = Cache::instance('redis');
// 		}
// 		return self::$_instance;
// 	}

	public function getInstance () {
		if(!(self::$_instance instanceof  self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	
	// PS：$field_name参数，如下：
	// view_count: 浏览数, answer_count  解答回复数 ,nice_count: 点赞数, against_count 反对数
	/**
	 *  生意帮-统计：是否存在用户数据
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $answer_id：回复id
	 * @param unknown_type $talk_id：话题id
	 * @param unknown_type $field_name：字段名称
	 */
	public  function get_stat_info($question_id,$answer_id=0,$talk_id=0,$field_name='')
	{
		$stat_count=$this->get_stat_cache($question_id,$answer_id,$field_name);
		if($stat_count!=-1)  //-1代表没有缓存
			return $stat_count;
		$stat_model=ORM::factory("Business_Stat");
		if(!empty($question_id)) $stat_model->where('question_id','=',$question_id);
		if(!empty($answer_id)) $stat_model->where('answer_id','=',$answer_id);
		if(!empty($talk_id)) $stat_model->where('talk_id','=',$talk_id);
		$result=  $stat_model->find()->as_array();
		$is_null=0;
        $view_count=isset($result['view_count'])? $result['view_count'] :$is_null;
        $answer_count=isset($result['answer_count'])? $result['answer_count'] :$is_null;
        $nice_count=isset($result['nice_count'])? $result['nice_count'] :$is_null;
        $against_count=isset($result['against_count'])? $result['against_count'] :$is_null;
        $this->set_stat_cache($question_id,$answer_id, 'view_count', $view_count);
		$this->set_stat_cache($question_id,$answer_id, 'answer_count', $answer_count);
		$this->set_stat_cache($question_id,$answer_id, 'nice_count', $nice_count);
		$this->set_stat_cache($question_id,$answer_id, 'against_count', $against_count);
		if(empty($field_name)) return $result;
		if($field_name=='view_count') return $view_count;
		if($field_name=='answer_count') return $answer_count;
		if($field_name=='nice_count') return $nice_count;
		if($field_name=='against_count') return $against_count;
	}
	
	/**
	 * 新增/修改 生意帮-统计 数据
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $answer_id：回复id
	 * @param unknown_type $talk_id：话题id
	 * @param unknown_type $field_name：字段名称
	 * @param unknown_type $jia_or_jian：数据+/1,默认：jia等于+; jian等于-
	 */
	public  function oper_stat($question_id,$answer_id,$talk_id,$field_name,$jia_or_jian='jia')
	{
		$stat_model=ORM::factory("Business_Stat");
		$result=$stat_model->where('question_id','=',$question_id)->where('answer_id','=',$answer_id)->find();
		if($result->loaded())
		{
			$data_result=$result->as_array();
			$value=$data_result[$field_name];
			if($jia_or_jian=='jia')  //加+1
				$field_value=$value+1;
			if($jia_or_jian=='jian') //减-1
			{
				if($value>=1)
					$field_value=$value-1;
			}
			$result->$field_name=$field_value;
			/* 浏览数 进行操作时，不对时间进行更新 Start */
			if($field_name!='view_count')  { 
				$result->update_time=time();
			}
			/* 浏览数 进行操作时，不对时间进行更新 End */
			$result->update();
		}
		else
		{
			$field_value=1;
			$stat_model->question_id=$question_id;
			$stat_model->answer_id=$answer_id;
			$stat_model->talk_id=$talk_id;
			$stat_model->update_time=time();
			$stat_model->$field_name=$field_value;
			$stat_model->create();
		}
		//$oper_id=$stat_model->id;  //返回所需要的字段值
		$this->set_stat_cache($question_id,$answer_id, $field_name, $field_value);
	}
	
	/**
	 * (通用）设置 生意帮-统计 缓存
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $answer_id：回复id
	 * @param unknown_type $field_name：字段名称
	 * @param unknown_type $field_value：字段值
	 */
	public  function set_stat_cache($question_id,$answer_id,$field_name,$field_value)
	{
		$cache_time=self::$config_obj->five_minutes_time;
		if(!empty($question_id))  //问题的统计数
		{
			$cache_key='q_'.$field_name;
			$cache_key=sprintf(self::$config_obj->$cache_key,$question_id);
			self::$redis->set($cache_key,$field_value,$cache_time);
		}
		if(!empty($answer_id))  //解答回复的统计数
		{
			$cache_key='a_'.$field_name;
			$cache_key=sprintf(self::$config_obj->$cache_key,$answer_id);
			self::$redis->set($cache_key,$field_value,$cache_time);
		}
		if(!empty($question_id) && !empty($answer_id)) //问题+解答回复-统计数
		{
			$cache_key='qa_'.$field_name;
			$cache_key=sprintf(self::$config_obj->$cache_key,$question_id.'_'.$answer_id);
			self::$redis->set($cache_key,$field_value,$cache_time);
		}
	}
	
	/**
	 * (通用）获取 生意帮-统计 缓存
	 * @param unknown_type $question_id：问题id
	 * @param unknown_type $answer_id：回复id
	 * @param unknown_type $field_name：字段名称
	 * @param unknown_type $field_value：字段值
	 */
	public  function get_stat_cache($question_id,$answer_id,$field_name)
	{
		if($question_id>0)  //问题的统计数
		{
			$cache_key='q_'.$field_name;
			$cache_key=sprintf(self::$config_obj->$cache_key,$question_id);
			$stat_count=self::$redis->get($cache_key);
		}
		if($answer_id>0)  //解答回复的统计数
		{
			$cache_key='a_'.$field_name;
			$cache_key=sprintf(self::$config_obj->$cache_key,$answer_id);
			$stat_count=self::$redis->get($cache_key);
		}
		if($question_id>0 && $answer_id>0) //问题+解答回复-统计数
		{
			$cache_key='qa_'.$field_name;
			$cache_key=sprintf(self::$config_obj->$cache_key,$question_id.'_'.$answer_id);
			$stat_count=self::$redis->get($cache_key);
		}
		//echo '--<br/>'.$stat_count;
		$stat_count=$stat_count==null? -1 : $stat_count;  //-1代表没有缓存
		return $stat_count;
	}
	
	
	/**
	 * X问题的回复数、浏览数【如：1 个回复 • 48 次浏览】
	 * @param unknown_type $array_question_ids：问题ids（数组）
	 */
	public function get_question_stat($array_question_ids)
	{
		$result=null;
		foreach ($array_question_ids as $key=>$value)
		{
			$question_id=$value;
			$view_count=$this->get_stat_info($question_id,0,0,'view_count');
			$answer_count=$this->get_stat_info($question_id,0,0,'answer_count');
			$result[$question_id]=array('view_count'=>$view_count,'answer_count'=>$answer_count);		
		}
		return $result;
	}
	
	
	/**
	 * 获取问题的点赞、踩 统计【如：3 个赞 • 8 个踩】
	 * @param unknown_type $array_question_ids：问题id（数组或单个字符串）
	 */
    public function get_question_nice_against_stat($array_question_ids)
	{
		$result=null;
		if(is_array($array_question_ids))
		{
			foreach ($array_question_ids as $key=>$value)
			{
				$question_id=$value;
				$nice_count=$this->get_stat_info($question_id,0,0,'nice_count');
				$against_count=$this->get_stat_info($question_id,0,0,'against_count');
				$result[$question_id]=array('nice_count'=>$nice_count,'against_count'=>$against_count);		
			}
		}
		else
		{
			$question_id=$array_question_ids;
			$view_count=$this->get_stat_info($question_id,0,0,'view_count');
			$nice_result=ORM::factory("Business_Stat")->where('question_id','=',$question_id)->where('answer_id','=',0)->find()->as_array();
			$nice_count=isset($nice_result['nice_count'])? $nice_result['nice_count'] :0;
			//$nice_count=$this->get_stat_info($question_id,0,0,'nice_count');
			$against_count=$this->get_stat_info($question_id,0,0,'against_count');
			$answer_count=$this->get_stat_info($question_id,0,0,'answer_count');
			$result[$question_id]=array('view_count'=>$view_count,  //X问题详情页，需要显示浏览数
										'nice_count'=>$nice_count,
										'against_count'=>$against_count,
					                    'answer_count'=>$answer_count);
		}
		return $result;
	}
	
	
	/**
	 * X问题的最近活跃时间
	 * @param unknown_type $question_id：问题id
	 */
	public function get_last_update_time($question_id)
	{
		$last_update_time='';
		$data_result = DB::select('update_time')->from('business_stat')
		->where('question_id','=',$question_id)
		->order_by('update_time','desc')
		->limit(1)
		->execute()
		->as_array();
		foreach ($data_result as $key=>$value)
		{
			$time_obj=new Service_QuickPublish_Search(); //显示时间
			$last_update_time=$time_obj->jishuanTime($value['update_time']);
		}
		return $last_update_time;
	}
}
?>
