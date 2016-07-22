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
class Task_Test extends Minion_Task
{
	/**
	 * Generates a help list for all tasks
	 *  @author 施磊
	 * @return null
	 */
	protected function _execute(array $params){
		#php shell php minion --task=test
            echo 123;
            
	}
        
}
