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
class Task_UpdateIndustryNumBydate extends Minion_Task
{
     /**
     *招商会左侧日历数据更新
     * @author 许晟玮
     */
    protected function _execute(array $params){
        #php shell php minion --task=UpdateIndustryNumBydate
        try {
            $invest = new Service_Platform_Invest();
            $invest->updateIndustryNumBydate();
        } catch (Exception $e) {
            //发送邮件
            common::sendemail('招商会左侧日历数据更新', 'akirametero@live.com', 'akirametero@gmail.com', '招商会左侧日历数据更新脚本执行错误');
        }


    }

}
?>