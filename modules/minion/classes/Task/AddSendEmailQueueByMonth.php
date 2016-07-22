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
class Task_AddSendEmailQueueByMonth extends Minion_Task
{
	 /**
     * 一个月未登录入库[个人用户]
     * @author 钟涛
     */
	protected function _execute(array $params){
		exit;//暂不使用
		#php D:\webserver\lnvestment-platform\trunk\modules\minion\minion --task=AddSendEmailQueueByMonth
        try {
            //获取所有符合的用户
            $userinfo=$this->_getDataInfoByUserTable();
            foreach($userinfo as $v){
            	$ssoresult = Service_Sso_Client::instance()->getUserInfoById($v->user_id);
            	//个人用户 邮箱已经通过验证
            	if($v->user_id && $ssoresult->email && $ssoresult->valid_email==1 && $ssoresult->user_type==2 && $ssoresult->user_status==1){
	            	//log表是否已经存在，存在即不添加
	            	$ormlog= ORM::factory('EmailSendQueueLog')
	            			->where('user_id','=',$v->user_id)
	            			->where('send_type','=',2)//超过30天邮件提醒
	            			->find();
	            	if(!$ormlog->loaded()){//没有记录再添加
	            		$orm= ORM::factory('EmailSendQueue')
		            		->where('user_id','=',$v->user_id)
		            		->where('send_type','=',2)//超过30天邮件提醒
		            		->find();
	            		if(!$orm->loaded()){//不能出现重复数据
		            		$orm->user_id= $v->user_id;
		            		$orm->email= $ssoresult->email;
		            		$orm->send_type= 2;//超过30天邮件提醒
		            		$orm->user_type= 2;//个人用户
		            		$orm->add_time=time();
		            		$orm->create();
	            		}
	            	}
            	}
            	usleep(1000);
            }
            echo 'success!';
        } catch (Exception $e) {
        	//导入数据失败 SQL错误
        	common::sendemail('未登录用户邮件发送队列库时SQL报错', 'service@yijuhua.net', 'zhongtao@tonglukuaijian.com', $e);
        }
	}
	
	/**
	 * 获取数据：个人用户登录时间[user表]超过30天没有登录的
	 * @author 钟涛
	 */
	protected function _getDataInfoByUserTable (){
		$model= ORM::factory('User');//用户登录日志
		$result=$model->where('last_logintime','>',0)//曾经登录过的
					->where('last_logintime','<',time()-2592000)//超过30天未登录的
					->order_by('last_logintime', 'DESC')->find_all();
		return $result;
	}
	
}
?>