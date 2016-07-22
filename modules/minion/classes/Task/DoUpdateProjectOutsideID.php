<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Help task to display general instructons and list all tasks
 *
 * @package    Kohana
 * @category   获取875数据  并入库
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */

class Task_DoUpdateProjectOutsideID extends Minion_Task{

	protected function _execute(array $params){
		#php shell php minion --task=DoUpdateProjectOutsideID
		 self::DoUpdateProjectOutsideID();
	
	}
	protected  function DoUpdateProjectOutsideID(){
		$obj_data = ORM::factory("Project")->where("outside_id","=",0)->where_open()->where("project_source","=",2)->or_where("project_source","=",6)->where_close()->find_all();
		$arr_data = array();
		$Service_Api_Basic =  new Service_Api_Basic();
		if(count($obj_data) > 0){
			foreach ($obj_data as $key=>$val){
				$arr_data[$key]['project_brand_name'] = $val->project_brand_name;
				$arr_data[$key]['project_id'] = $val->project_id;
			}
		}
		//echo  "<pre>"; print_r($arr_data);exit;
		try {
			if(count($arr_data) > 0){
				foreach ($arr_data as $key=>$val){
					if($key > 100){
						echo "停一下\n"; exit;
					}
					$json_data = $Service_Api_Basic->getApiReturn("http://man.875.cn/rest_project/postProjectExactList",array("pro_name"=>arr::get($val,"project_brand_name")));
					//echo "<pre>"; print_r($json_data);exit;
					if(arr::get($json_data,'msg')){
						$bool = $this->UpdateProject(arr::get($val,"project_id"),arr::get($json_data['msg'][0],'pro_id'));
						if($bool == true){
							//入库日志	#入库到log开始
							$arr_Projectoutside_data = array('outside_project_id'=>arr::get($val,"project_id"),
									'outside_project_name'=>arr::get($val,"project_brand_name"),
									'status'=>20,
									'addtime'=>time(),
									'project_type'=>20
							);
							$bool_ruku = self::insert_project_outside($arr_Projectoutside_data);
							if($bool_ruku == true){
								echo  "项目名称 / ".arr::get($val,"project_brand_name")."/ 修改成功\n";
							}
						}
					}
				}
			}
		}catch (Exception $e){
				
		}
	}
	
	//修改主表项目
	public function UpdateProject($project_id,$outside_id){
		//	echo "<pre>"; print_r($outside_id);exit;
		try {
			if($project_id && $outside_id){
				$model = ORM::factory("Project",intval($project_id));
				if($model->loaded()){
					if($model->outside_id =="" || $model->outside_id == 0)
						$model->outside_id = $outside_id;
						$model->update();
						$model->clear();
						return  true;
				}
			}
			return false;
		}catch (Exception $e){
			return false;
		}
	}
	
	
	
	#入库到项目log
	protected function insert_project_outside($arr_data){
		$model_Projectoutside = ORM::factory("Projectoutside");
			if($arr_data){
				foreach ($arr_data as $key=>$val){
				$model_Projectoutside->$key = $val;
		}
		$model_Projectoutside->save();
		$model_Projectoutside->clear();
		return true;
		}
		return false;
	}
}