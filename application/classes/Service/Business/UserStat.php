<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生意帮-用户操作统计
 * @author 兔毛 2014-06-09
 */
class Service_Business_UserStat{
	protected static $_instance = null;
	protected static $userstat_model = null;
	protected static $config_obj = null;  //缓存配置文件
	protected static $redis = null;       //redis对象
	
	//单例模式
	public static function getInstance() {
		if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
			self::$userstat_model = ORM::factory("Business_UserStat");
			self::$config_obj = Kohana::$config->load('cache_name');
			self::$redis = Cache::instance('redis');
		}
		return self::$_instance;
	}
	// PS：$field_name参数，如下：
	// question_count: 用户问题数, answer_count  解答回复数 ,nice_count: 点赞数, against_count 反对数
	
	/**
	 * 生意帮-用户操作统计：是否存在用户数据
	 * @param unknown_type $user_id：用户id
	 */
	public  function get_user_stat_info($user_id,$field_name='')
	{
		$stat_count=$this->get_user_stat_cache($user_id,$field_name);
		if($stat_count!=-1)  //-1代表没有缓存
			return $stat_count;
		$result= self::$userstat_model->where('user_id','=',$user_id)->find()->as_array();
		$is_null=0;
		$question_count=isset($result['question_count'])? $result['question_count'] :$is_null;
		$answer_count=isset($result['answer_count'])? $result['answer_count'] :$is_null;
		$nice_count=isset($result['nice_count'])? $result['nice_count'] :$is_null;
		$against_count=isset($result['against_count'])? $result['against_count'] :$is_null;
		$this->set_user_stat_cache($user_id, 'question_count', $question_count);
		$this->set_user_stat_cache($user_id, 'answer_count', $answer_count);
		$this->set_user_stat_cache($user_id, 'nice_count', $nice_count);
		$this->set_user_stat_cache($user_id, 'against_count', $against_count);
		if(empty($field_name)) return $result;
		if($field_name=='question_count') return $question_count;
		if($field_name=='answer_count') return $answer_count;
		if($field_name=='nice_count') return $nice_count;
		if($field_name=='against_count') return $against_count;
		return $result;
	}
	
	/**
	 * 新增/修改 生意帮-用户操作统计 数据
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $field_name：字段名称
	 * @param unknown_type $jia_or_jian：数据+/1,默认：jia等于+; jian等于-
	 */
	public  function oper_user_stat($user_id,$field_name,$jia_or_jian='jia')
	{
		$result= self::$userstat_model->where('user_id','=',$user_id)->find();
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
			$result->update_time=time();
			$result->update();
		}
		else
		{
			$field_value=1;
			self::$userstat_model->user_id=$user_id;
			self::$userstat_model->update_time=time();
			self::$userstat_model->$field_name=$field_value;
			self::$userstat_model->create();
		}
		//d(self::$userstat_model->$field_name);  //返回所需要的字段值
		$this->set_user_stat_cache($user_id, $field_name, $field_value);
	}
	
	/**
	 * (通用）设置 生意帮-用户操作统计 缓存
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $field_name：字段名称
	 * @param unknown_type $field_value：字段值
	 */
	public  function set_user_stat_cache($user_id,$field_name,$field_value)
	{
		$cache_key='u_'.$field_name;
		$cache_key=sprintf(self::$config_obj->$cache_key,$user_id);
		self::$redis->set($cache_key,$field_value,self::$config_obj->five_minutes_time);
	}	
	
	/**
	 * (通用）获取 生意帮-用户操作统计 缓存
	 * @param unknown_type $user_id：用户id
	 * @param unknown_type $field_name：字段名称
	 * @param unknown_type $field_value：字段值
	 */
	public  function get_user_stat_cache($user_id,$field_name)
	{
		$cache_key='u_'.$field_name;
		$cache_key=sprintf(self::$config_obj->$cache_key,$user_id);
		$stat_count=self::$redis->get($cache_key);
		$stat_count=$stat_count==null? -1 : $stat_count;  //-1代表没有缓存
		return $stat_count;
	}
	
	/**
	 * 通用：获取用户信息
	 * @param unknown_type $user_id：用户id
	 * @return array: user_name、user_gender;
	 */
	public  function get_user_info($user_id)
	{
		$cache_key=sprintf(self::$config_obj->x_user_info,$user_id);
		$result=self::$redis->get($cache_key);
		//self::$redis->delete($cache_key);
		if(empty($result))
		{
			$user_result=Service_Sso_Client::instance()->getUserInfoById($user_id);//84667
			if($user_result)
			{
				$user_name='';
				if(!empty($user_result->user_name)) $user_name=$user_result->user_name;
				if(empty($user_result->user_name) && !empty($user_result->email)) $user_name=$user_result->email;
				if(empty($user_result->user_name) && empty($user_result->email) && !empty($user_result->mobile)) $user_name=$user_result->mobile;
				if(empty($user_result->user_name) && empty($user_result->email) && empty($user_result->mobile)) $user_name='尊敬的会员您好';
				$result['user_id']=$user_id;
				$result['user_name']=$user_name;
				$result['user_photo']=$user_result->user_portrait ? URL::imgurl($user_result->user_portrait) :'';
				$user_gender='';
				if($user_result->user_gender==1)   $user_gender="先生";
				if($user_result->user_gender==2)   $user_gender="女士";
				$result['user_gender']=$user_gender;
				$sign_obj=new Service_Business_Search();
				$result['user_sign']=$sign_obj->getUserSignatureById($user_id);
			}
			else{
				$is_null = "";
				$result['user_id']=$is_null;
				$result['user_name']=$is_null;
				$result['user_photo']=$is_null;
				$result['user_gender']=$is_null;
				$result['user_sign']=$is_null;
			}
			if($result) self::$redis->set($cache_key,$result,self::$config_obj->five_minutes_time);
		}
		return $result;
	}
	
	/**
	 * 通用：删除用户信息缓存
	 * @param unknown_type $user_id：用户id
	 */
	public  function del_cache_user_info($user_id)
	{
		$cache_key=sprintf(self::$config_obj->x_user_info,$user_id);
		self::$redis->delete($cache_key);
	}
	
	
	/**
	 * 热门用户
	 */
	public  function get_hot_user($limit=10)
	{
		//select answer_count,user_id,question_count from czzs_business_user_stat order by answer_count desc
		$result=null;
		$data_result=DB::select('answer_count','user_id','question_count')->from("business_user_stat")
		->order_by('answer_count','desc')
		->limit($limit)
		->execute()
		->as_array();
		if(count($data_result)>0)
		{
			foreach ($data_result as $key=>$value)
			{
				$user_info=$this->get_user_info($value['user_id']);
				$result[$key]=array_merge($value,$user_info);
			}
		}
		return $result;	
	}
	
	/**
	 * X用户的最近活跃时间
	 * @param unknown_type $user_id：用户id
	 */
	public function get_last_update_time($user_id)
	{
		$last_update_time='';
		$data_result = DB::select('update_time')->from('business_user_stat')
		->where('user_id','=',$user_id)
		->order_by('update_time','desc')
		->limit(1)
		->execute()
		->as_array();
		foreach ($data_result as $key=>$value)
		{
			/*$time_obj=new Service_QuickPublish_Search(); //显示时间
			$last_update_time=$time_obj->jishuanTime($value['update_time']);*/
			$last_update_time=date("Y-m-d H:i:s",$value['update_time']);
		}
		return $last_update_time;
	}
	
	/**
	 * X用户的回复数
	 * @param unknown_type $user_id：用户id
	 */
	public function get_user_answer_count($user_id)
	{
		$answer_count=$this->get_user_stat_info($user_id,'answer_count');
		return $answer_count;
	}
}
?>
