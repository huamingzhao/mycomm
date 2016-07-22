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
class Task_AddSendEmailQueueAll extends Minion_Task
{
	 /**
     * 对个人+企业 7天+30天未登录的 
     * @author 钟涛
     */
	protected function _execute(array $params){
		#cd D:\softserver\php-5.3.18 #php D:\webserver\lnvestment-platform\trunk\modules\minion\minion --task=AddSendEmailQueueAll
        try {
            //获取所有符合的用户
            $userinfo=$this->_getDataInfoByUserTable();
            $ii=0;
            foreach($userinfo as $v){
            	//看看是不是已经点击退订了的，如果已经退订了，就别发咯
            	$unsubscribe1 = ORM::factory("UserUnsubscribe")->where('user_id','=',$v->user_id)
            	->where('unsubscribe_type','=',1)//邮件
            	->where('unsubscribe_sec_type','=',1)->find();//未登录提醒退订
            	if(!$unsubscribe1->loaded()){
	            	$ssoresult = Service_Sso_Client::instance()->getUserInfoById($v->user_id);
	            	//TODO企业用户暂时不入库 后面再加上
	            	if($ssoresult->user_type==2 &&  $ssoresult->valid_email==1  && $ssoresult->email  && $ssoresult->user_status==1){
// 		            	if($ssoresult->user_type==1 || $ssoresult->user_type==2){//企业用户
// 		            		$usertype=$ssoresult->user_type;
// 		            	}else{
// 		            		$usertype='';
// 		            	}
	            		$usertype=2;
		            	if($usertype){//只对企业和个人发送
			            	//log表是否已经存在，存在即不添加
			            	$ormlog= ORM::factory('EmailSendQueueLog')
			            			->where('user_id','=',$v->user_id)
			            			->where('send_type','=',1)//超过7天邮件提醒
			            			->find();
			            	if(!$ormlog->loaded()){//没有超过7天记录再添加
			            		$orm= ORM::factory('EmailSendQueue')
				            		->where('user_id','=',$v->user_id)
				            		->where('send_type','=',1)//超过7天邮件提醒
				            		->find();
			            		if(!$orm->loaded()){//不能出现重复数据
				            		$orm->user_id= $v->user_id;
				            		$orm->email= $ssoresult->email;
				            		$orm->send_type= 1;//超过7天邮件提醒
				            		$orm->user_type= $usertype;//企业用户+个人用户
				            		$orm->add_time=time();
				            		$orm->create();
				            		$ii++;
			            		}
			            	}else{//有超过7天的了，那再判断是否有30天的
			            		if($ormlog->send_time<time()-2592000){//7天的通知邮件已发并且超过了30天
			            			$ormlog2= ORM::factory('EmailSendQueueLog')
			            			->where('user_id','=',$v->user_id)
			            			->where('send_type','=',2)//超过30天邮件提醒
			            			->find();
			            			if(!$ormlog2->loaded()){//没有超过30天记录再添加
			            				$orm= ORM::factory('EmailSendQueue')
				            				->where('user_id','=',$v->user_id)
				            				->where('send_type','=',2)//超过30天邮件提醒
				            				->find();
			            				if(!$orm->loaded()){//不能出现重复数据
			            					$orm->user_id= $v->user_id;
			            					$orm->email= $ssoresult->email;
			            					$orm->send_type= 2;//超过30天邮件提醒
			            					$orm->user_type= $usertype;//企业用户+个人用户
			            					$orm->add_time=time();
			            					$orm->create();
			            					$ii++;
			            				}
			            			}
			            		}
			            	}
	            		}
	            	}
            	}
            	usleep(1000);
            }//foreach循环结束
            echo 'success_totalcount: '.$ii;
        } catch (Exception $e) {
        	//导入数据失败 SQL错误
        	common::sendemail('未登录用户邮件发送队列库时SQL报错', 'service@yijuhua.net', 'zhongtao@tonglukuaijian.com', $e);
        }
	}
	
	/**
	 * 获取数据超过7天没有登录的
	 * @author 钟涛
	 */
	protected function _getDataInfoByUserTable (){
		$model= ORM::factory('User');//用户登录日志
		$result=$model->where('last_logintime','>',0)//已经登录过了的
				->where('last_logintime','<',time()-604800)//超过7天未登录的
		->order_by('last_logintime', 'DESC')->find_all();
		return $result;
	}
	
}
?>