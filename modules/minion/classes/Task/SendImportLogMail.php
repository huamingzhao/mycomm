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
class Task_SendImportLogMail extends Minion_Task
{
	 /**
     * 每天从875同步的项目和招商会信息邮件给后台管理员
     * @author 花文刚
     */
	protected function _execute(array $params){
		#php shell php minion --task=SendImportLogMail
        try {
            $invest = new Service_Platform_Invest();
            $content_invest = $invest->getMailContentOfImportLog();

            $to_mail = array('shenpengfei@yijuhua.net','huawengang@yijuhua.net');
            foreach($to_mail as $to){
                common::sendemail('875投资考察会同步日志-'.date("Y年m月d日"), 'service@yijuhua.com', $to, $content_invest);
            }


        } catch (Exception $e) {

        }
	}
}
?>