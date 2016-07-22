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
class Task_UpdateInvestment extends Minion_Task
{
	/**
     * 更新项目的招商会状态
     * @author 郁政     
     */
	protected function _execute(array $params){
		#php shell php minion --task=UpdateInvestment
		$service = new Service_Platform_Project();
		$res = $service->updateInvestmentStatus();    
		echo $res;     
	}
        
}
?>