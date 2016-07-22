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
class Task_UpdateInvestApply extends Minion_Task
{
	 /**
     * 投资考察过期报名人数为0的增加一个虚拟人数
     * @author 周进     
     */
	protected function _execute(array $params){
		#php shell php minion --task=UpdateIndustryApply
        try {
        	$result = ORM::factory('Projectinvest')->where('investment_start','<=',time())->and_where('investment_status','=','1')->order_by('investment_start','desc')->find_all();
			foreach ($result as $k=>$v){
				if ($v->investment_apply==0&&$v->investment_virtualapply==0){
					$apply = ORM::factory('Projectinvest',$v->investment_id);
					$apply->investment_virtualapply= rand(50, 150);
					$apply->update();
				}
			}        	
        } catch (Exception $e) {
            //发送邮件
            //common::sendemail('招商会左侧日历数据更新', 'akirametero@live.com', 'akirametero@gmail.com', '招商会左侧日历数据更新脚本执行错误');
        }	           
	}       
}
?>