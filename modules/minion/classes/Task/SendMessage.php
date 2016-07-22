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
class Task_SendMessage extends Minion_Task
{
    private $_message= '由于2014.04.13公司短信接口出现异常，对您在一句话商机速配平台（ www.yjh.com）注册带来了不便，感到非常抱歉！现短信接口已恢复正常，快去一句话商机速配平台注册吧！祝您在投资的道路钱程似锦。';

     /**
     * 发送短信
     * @author 郁政
     */
    protected function _execute(array $params){
        $orm= ORM::factory('MobileCodeLog');
        $orm->where('datetime', '>=', strtotime('2014-04-12 00:00:00'));
        $orm->where('datetime', '<=', strtotime('2014-04-14 09:11:00'));
        $orm->group_by('mobile');
        $result= $orm->find_all()->as_array();
        foreach( $result as $vss ){
            $mobile= $vss->mobile;
            //发送短信
            common::send_message($mobile, $this->_message, 'online');

        }
        Minion_CLI::write('ok');
    }

}
?>