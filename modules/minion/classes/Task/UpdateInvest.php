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
class Task_UpdateInvest extends Minion_Task
{
	 /**
     * 招商会左侧日历数据更新 、875外采项目更新
     * @author 周进
     */
	protected function _execute(array $params){
		#php shell php minion --task=UpdateIndustryNumBydate
        try {
            //875外采投资考察
            $investapi = new Service_Api_Invest();
            $url = "http://man.875.cn/rest_meeting/postMeetingList";
            $investapi->getInvest($url, time(),150);

            //投资考察左侧,应该在导入之后执行 @花文刚
            $invest = new Service_Platform_Invest();
            $invest->updateIndustryNumBydate();

            echo "脚本运行结束。\n";
        } catch (Exception $e) {
            //发送邮件
            //common::sendemail('招商会左侧日历数据更新', 'akirametero@live.com', 'akirametero@gmail.com', '招商会左侧日历数据更新脚本执行错误');
        }
	}
}
?>