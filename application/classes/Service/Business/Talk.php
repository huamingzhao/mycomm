<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生意帮-类型信息表（话题）
 * @author 兔毛 2014-06-09
 */
class Service_Business_Talk{
	private $talk_model=null;  
	private $config_obj=null;  //缓存配置文件
	private $redis=null;       //redis对象
		
	public function __construct() {
		$this->talk_model =  ORM::factory("Business_Talk");
		$this->config_obj=Kohana::$config->load('cache_name');
		$this->redis = Cache::instance('redis');
	}
	
    /**
     * 获取：生意帮-类型信息表（话题）列表
     */
	public function get_talk_list()
	{
		$result=null;
		$cache_key=$this->config_obj->talk_list;
		$result=$this->redis->get($cache_key);
		if(empty($result))
		{
			$this->talk_model->order_by('update_time','desc');
			$data_result=$this->talk_model->find_all();
			foreach ($data_result as $key=>$value){
				$data_info=$value->as_array();
				$id=$data_info['id'];
				$result[$id] = array('id'=>$id,'name'=>$data_info['name']);
			}
 			$this->redis->set($cache_key,$result,$this->config_obj->a_hour_time);
		}
		return $result;	
	}
	
	
	

}
?>
