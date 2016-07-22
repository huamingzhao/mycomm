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
class Task_AddSendEmailQueue extends Minion_Task
{
	 /**
     * 7天未登录入库[个人用户]
     * @author 钟涛
     */
	protected function _execute(array $params){
		exit;//暂不使用
		#php D:\webserver\lnvestment-platform\trunk\modules\minion\minion --task=AddSendEmailQueue
        try {
            //获取所有符合的用户
            $userinfo=$this->_getDataInfoByUserTable();
            //$ser=new Service_User_Person_User();
            foreach($userinfo as $v){
            	//$userinfo=$ser->getUserById($v->user_id);
            	//获取最后登录时间
            	//$lastlogin=$this->_getLastLogin($v->user_id);
            	//log表是否已经存在，存在即不添加
            	$ormlog= ORM::factory('EmailSendQueueLog')
            			->where('user_id','=',$v->user_id)
            			->where('send_type','=',1)//超过7天邮件提醒
            			->find();
            	if(!$ormlog->loaded()){//没有记录再添加
            		$orm= ORM::factory('EmailSendQueue')
	            		->where('user_id','=',$v->user_id)
	            		->where('send_type','=',1)//超过7天邮件提醒
	            		->find();
            		if(!$orm->loaded()){//不能出现重复数据
	            		$orm->user_id= $v->user_id;
	            		$orm->email= $v->email;
	            		$orm->send_type= 1;
	            		$orm->user_type= 2;
	            		$orm->add_time=time();
	            		$orm->create();
            		}
            	}
            	usleep(100);
            }
            echo 'success!';
        } catch (Exception $e) {
        	//导入数据失败 SQL错误
        	common::sendemail('未登录用户邮件发送队列库时SQL报错', 'service@yijuhua.net', 'zhongtao@tonglukuaijian.com', $e);
        }
	}
	
	/**
	 * 获取数据：个人用户登录时间[user表]
	 * @author 钟涛
	 */
	protected function _getDataInfoByUserTable (){
		$sevenday=604800;
		$model= ORM::factory('User');//用户登录日志
		$result=$model->where('user_type','=',2)//个人用户
					->where('valid_email','=',1)//邮箱已经通过验证的
					->where('last_logintime','>',0)//曾经登录过的
					->where('last_logintime','<',time()-604800)//超过7天未登录的
					->where('user_status','=',1)//启用状态
					->order_by('last_logintime', 'DESC')->find_all();
		return $result;
	}
	
	/**
	 * 获取数据：个人用户登录时间
	 * @author 钟涛
	 */
	protected function _getDataInfo (){
		$model= ORM::factory('UserLoginLog');//用户登录日志
		$result=$model->where('user_type','=',2)//个人用户
			->group_by('user_id')//去重
			->select("*")->order_by('log_time', 'DESC')->find_all();
		return $result;
	}
	
	/**
	 * 获取最后登录时间
	 * @author 钟涛
	 */
	protected function _getLastLogin ($userid){
		$model= ORM::factory('UserLoginLog');//用户登录日志
		$result=$model->where('user_id','=',$userid)//个人用户
		->select("*")->order_by('log_time', 'DESC')->find();
		return $result;
	}
}
?>