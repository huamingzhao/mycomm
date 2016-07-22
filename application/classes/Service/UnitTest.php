<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 单元测试
 * @author 钟涛
 *
 */
class Service_UnitTest{
	
	/**
	 * 测试
	 * @author 钟涛
	 * @param int $one_param
	 * @param int $two_param
	 */
	public function testAdd($one_param,$two_param){
		//返回两个值相加
		return $one_param+$two_param;
	}//funtion end
	
	
	
	
	
	/**
	 * 更新当前用户关注项目数据的状态
	 * @author 钟涛
     * @param int $user_id 用户id
     * @param int $watch_status 状态：默认1表示启用；0表示禁用
	 */
	public function updateProjectWatchInfo($user_id,$watch_status=1){
		//找到当前id数据
		$data=ORM::factory('Projectwatch')->where('watch_user_id','=',$user_id)->find();
		
		$data->watch_status = $watch_status;//设置状态
		$data->watch_update_time = time();//更新时间
	
		$result=$data->update();//更新数据
		//返回数据状态
		return $result->watch_status;
	}//funtion end
	
	
	
	
	
	/**
	 * 判断企业用户是否有权限查看此名片(测试)
	 * @param int $user_id 企业用户id
	 * @param array $per_arr_one 个人用户1级意向投资行业
	 * @param array $per_arr_two 个人用户2级意向投资行业
	 * @retrun bool
	 * @author 钟涛
	 */
	public function isHasPower($user_id,$sec_userid){
		$permodel= ORM::factory('Personinfo')->where('per_user_id','=',$sec_userid)->find();
		//企业一级行业
		$arrProjectIndusty = ORM::factory('ProjectSearchCard')->where('user_id','=',$user_id)->where('project_status','=',2)->find_all();
		$returnProjectIndustyarr=array();//所有1级行业
		$returnProjectIndustyarr2=array();//所有2级行业

		//用户意向投资行业
		$this_perindustrymodel= ORM::factory('UserPerIndustry')->where('user_id','=',$sec_userid)->find_all();
		$per_i=array();
		//获取个人意向投资行业
		foreach ($this_perindustrymodel as $this_v){
			$per_i[]=$this_v->industry_id;
		}
		if(arr::get($per_i,'1',0)>arr::get($per_i,'2',0)){//有2级行业 获取较大的值为2级行业id
			$t_v=arr::get($per_i,'1',0);
		}elseif(arr::get($per_i,'1',0)<arr::get($per_i,'2',0)){//有2级行业 获取较大的值为2级行业id
			$t_v=arr::get($per_i,'2',0);
		}elseif(isset($per_i[1])){//没有2级行业
			$t_v=arr::get($per_i,'1',0);
		}else{$t_v=0;}
		//先判断2级
		if($t_v<8){//个人没有2级行业
			if($returnProjectIndustyarr && in_array($t_v, $returnProjectIndustyarr)){
				return true;
			}
		}else{//个人有2级行业
			if($returnProjectIndustyarr2 && in_array($t_v, $returnProjectIndustyarr2)){
				return true;
			}
		}
		return false;
	}//funtion end
	

}