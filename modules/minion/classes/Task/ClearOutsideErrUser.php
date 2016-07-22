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
class Task_ClearOutsideErrUser extends Minion_Task
{
    /**
     * 清理外采冗余的用户
     * @author 施磊     
     */
	protected function _execute(array $params){
            #php shell php minion --task=ClearOutsideErrUser
            $count = DB::select()->from("outuser_account")->join('user', 'INNER')->on('user.email','=','outuser_account.email')->where('user_status', '=', 1)->execute()->count();
            if ($count) {
                for ($i = 0; $i < $count/100; $i++) {
                    $arr  = DB::select()->from("outuser_account")->join('user', 'INNER')->on('user.email','=','outuser_account.email')->join('user_company')->on('user.user_id','=','com_user_id')->where('user_status', '=', 1)->limit(100)->offset($i)->execute()->as_array();
                    if($arr) {
                        foreach($arr as $val) {
                            $userId = $val['user_id'];
                            $comId = $val['com_id'];
                            #检查是否登陆过
                            $arrlog = DB::select()->from('user_login_log')->where('user_id', '=', $userId)->limit(1)->offset(0)->execute()->as_array();
                            if($arrlog) {
                                continue;
                            }
                            $arrPro = DB::select()->from('project')->where('com_id', '=', $comId)->limit(1)->offset(0)->execute()->as_array();
                            if(!$arrPro && !$arrlog) {
                                DB::delete('user')->where('user_id', '=', $userId)->execute();
                                DB::delete('user_company')->where('com_id', '=', $comId)->execute();
                                echo 'id:'.$userId;echo " comid:".$comId;
                                continue;
                            }
                        }
                    }
                }
            }
	}
}