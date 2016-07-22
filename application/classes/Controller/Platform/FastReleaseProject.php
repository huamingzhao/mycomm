<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 快速发布项目
 * @author TIM (JIYE)
 */
class Controller_Platform_FastReleaseProject extends Controller_Platform_Template{
	
	/**
	* 显示快速添加发布项目
	* @author Tim(jiye)
	* @param 
	* @create_time  2014-5-8
	* @return int/bool/object/array
	*/
	public function action_ShowAddFastReleaseProject(){
		$content = View::factory('platform/fastreleaseproject/fastreleaseproject');
		$this->content->maincontent = $content;
		//地区
		$area = array('pro_id' => 0);
		$content->areas = common::arrArea($area);
		//品牌历史
		//$content->arr_project_history = common::projectHistory();
		
	}
	
	/**
	* 执行快速添加
	* @author Tim(jiye)
	* @param 
	* @create_time  2014-5-8
	* @return int/bool/object/array
	*/
	
	public function action_DoAddFastReleaseProject(){
		$Fastreleaseproject_service = new Service_Platform_Fastreleaseproject();
		$arr_project_data = array();
		//执行添加
		$Fastreleaseproject_service->AddDatalist("Project", $arr_project_data);
	}
}