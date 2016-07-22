<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Help task to display general instructons and list all tasks
 *
 * @package    Kohana
 * @category   Helpers
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Task_UpdateApproingCount extends Minion_Task
{
	 /**
     * 更新项目的赞数和点击数
     * @author 郁政     
     */
	protected function _execute(array $params){
		#php shell php minion --task=UpdateApproingCount
		$service = new Service_Platform_Project();
    	$res = $service->updataApproingCount();
    	echo $res;		           
	}       
}
?>