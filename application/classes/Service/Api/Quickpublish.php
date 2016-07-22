<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 快速发布api
 * @author 郁政
 *
 */
class Service_Api_Quickpublish extends Service_Api_Basic{
	/**
     * 判断内容中是否存在敏感词 （快速发布）
     * @author 郁政
     */
	public function hasMinGanWords($date){
		$project_brand_name = Arr::get($date, 'project_brand_name','');
		$project_introduction = Arr::get($date, 'project_introduction','');
		$project_title = Arr::get($date, 'project_title','');
		$project_summary = Arr::get($date, 'project_summary','');
		$post = array('project_brand_name' => $project_brand_name , 'project_introduction' => $project_introduction , 'project_title' => $project_title , 'project_summary' => $project_summary);
        return $this->getApiReturn($this->apiUrl['processMingGanWords'], $post);
	}	
}
?>