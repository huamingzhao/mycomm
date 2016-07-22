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
class Task_AddMessageToCRM extends Minion_Task
{
	 /**
     * 每日新平台875项目留言数据导入CRM系统
     * @author 钟涛
     */
	protected function _execute(array $params){
		exit;//暂停脚本
		#php D:\webserver\lnvestment-platform\trunk\modules\minion\minion --task=AddMessageToCRM
        try {
            //测试地址
            //$url='http://10.201.2.22:7070/investor/uniboard/YJH/add';
        	//本机
        	//$url='http://10.41.50.53:6060/heimdall.sitemessage.bundle/investor/uniboard/YJH/add';
            //线上地址
            $url='http://sitemessage-aone.tonglukuaijian.com/investor/uniboard/YJH/add';
            //获取前一天875项目的留言数据
            $userinfo=$this->_getDataInfo();
            $ser=new Service_Api_Basic();
            $moneylist = common::moneyArr();
            $per_service = new Service_User_Person_User();
            $starttime=strtotime(date('Y-m-d 00:00:00', time()-86400));//昨天开始时间
            $endtime=strtotime(date('Y-m-d 00:00:00', time()));//昨天结束时间
            $usercount=0;
            $ii=0;//记录成功
            $jj=0;//记录失败
            $userarr0=array();//存储 项目id=0的用户
            foreach($userinfo as $v){//开始循环插入数据啦
            	$post=array();
            	//获取875项目的id
            	$pro875=0;
            	if($v->outside_id){
            		$pro875=$v->outside_id;
            	}else{//如果不存在，再找找
            		$investmentlog =ORM::factory('Projectoutside')
		            		->where('status','=',1)
		            		->where('project_type','=',1)
		            		->where('outside_project_name','=',$v->project_brand_name)
		            		->find();
            		if($investmentlog->outside_project_id){
            			$pro875=$investmentlog->outside_project_id;
            		}
            	}
            	$post['platformCode']=base64_encode('YJH');
            	if($pro875 || !in_array($v->from_user_id, $userarr0)){//存在这个项目id  ok  继续->
            		$post['projectId']= base64_encode($pro875);//项目ID
	            	//$user=ORM::factory('User',$v->from_user_id);
	            	$user = Service_Sso_Client::instance()->getUserInfoById($v->from_user_id);
	            	$personinfo=ORM::factory('Personinfo')->where('per_user_id', '=', $v->from_user_id)->find();
	            	//个人有基本信息 ok 继续->
	            	if($user->user_type==2 && $user->mobile && $personinfo->per_realname){
	            		$usercount++;
	            		if($pro875==0){//说明875的id获取不到
	            			array_push($userarr0, $v->from_user_id);
	            		}
	            		//一定有的数据
	            		$post['visitorEmail']= base64_encode($user->email);//访客邮箱
	            		$post['visitorMobile']= base64_encode($user->mobile);//访客手机
	            		$post['visitorName']= base64_encode($personinfo->per_realname);//访客姓名
	            		$post['visitedTime']= base64_encode(date("Y-m-j H:i:s",$v->send_time));//时间
	            		$post['visitorGender']= base64_encode($personinfo->per_gender==2 ? '女' : '男');//性别
	            		if($v->ip){
	            			$post['visitorIP']= base64_encode(long2ip($v->ip));//ip
	            		}
	            		//可能会有的数据
	            		//1.留言内容
	            		$userletter=ORM::factory('UserLetter')
	            					->where('to_project_id','=',$v->to_project_id)
	            					->where('add_time','>=',$starttime)
	            					->where('add_time','<',$endtime)
	            					->where('letter_status','=',1)
	            					->where('user_type','=',2)
	            					->where('user_id','=',$v->from_user_id)
	            					->where('content','!=','')
	            					->find();
	            		if($userletter->content){
	            			$post['messageContent']= base64_encode($userletter->content);//留言信息
	            		}else{
	            			$post['messageContent']='';
	            		}
	            		//意向投资金额
	            		$post['investmentAmount']= base64_encode(arr::get($moneylist, $personinfo->per_amount,''));
	            		//意向投资地区
	            		$post['joinLocation']= base64_encode($per_service->getPersonalArea($v->from_user_id));//加盟地点
	            		$result=$ser->getApiReturn($url,$post,true);
	            		$code=arr::get($result,'code','-1');
	            		$msg=arr::get($result,'msg','未找到指定结果msg');
	            		
	            		if($code ==0){//成功
	            			$ii++;
	            		}else{
	            			$jj++;
	            		}
	            		//导入数据log记录
	            		$cardmodel=ORM::factory("CardCmsLog");
	            		$cardmodel->per_user_id = $v->from_user_id;
	            		$cardmodel->per_user_name = $personinfo->per_realname;
	            		$cardmodel->project_id = $v->to_project_id;
	            		$cardmodel->project_brand_name = $v->project_brand_name;
	            		$cardmodel->project_crm_id = $pro875;
	            		$cardmodel->card_id = $v->card_id;
	            		$cardmodel->send_time = $v->send_time;
	            		$cardmodel->content=$userletter->content ? $userletter->content : '';//留言内容
	            		$cardmodel->add_result_status = $code;//导入数据结果
	            		$cardmodel->causes_failure = $msg;
	            		$cardmodel->status = 0;
	            		$cardmodel->add_time = time();
	            		$cardmodel->create();
	            		usleep(100);
	            	}
            	}
            }
            //判断之前是否有导入失败的数据，导入失败的数据从新导入
            $cardcmsmodel=ORM::factory("CardCmsLog")
            			->where('add_result_status','!=',0)//导入失败的
            			->where('add_time','<',time()-7200)//导入时间大约为前一天的
            			->where('status','=','0')->find_all();//没有重新导入的
            $c_i=0;
            $c_j=0;
            $c_connt=count($cardcmsmodel);
            $c_content='';
            if($c_connt){//存在之前导入失败并且没有重新导入的
            	foreach($cardcmsmodel as $c_v){
            		//$user =ORM::factory('User',$c_v->per_user_id);
            		$user = Service_Sso_Client::instance()->getUserInfoById($c_v->per_user_id);
            		$personinfo=ORM::factory('Personinfo')->where('per_user_id', '=', $c_v->per_user_id)->find();
            		$post=array();
            		$post['projectId']= base64_encode($c_v->project_crm_id);//项目ID
            		$post['platformCode']=base64_encode('YJH');

            		$post['visitorEmail']= base64_encode($user->email);//访客邮箱
            		$post['visitorMobile']= base64_encode($user->mobile);//访客手机
            		$post['visitorName']= base64_encode($c_v->per_user_name);//访客姓名
            		$post['visitedTime']= base64_encode(date("Y-m-j H:i:s",$c_v->send_time));//时间
            		$post['visitorGender']= base64_encode($personinfo->per_gender==2 ? '女' : '男');//性别
            		$cardsmodel= ORM::factory('Cardinfo',$c_v->card_id);
            		if($cardsmodel->ip){
            			$post['visitorIP']= base64_encode(long2ip($cardsmodel->ip));//ip
            		}
            		if($c_v->content){
            			$post['messageContent']= base64_encode($c_v->content);//留言信息
            		}else{
            			$post['messageContent']='';
            		}
            		
            		//意向投资金额
            		$post['investmentAmount']= base64_encode(arr::get($moneylist, $personinfo->per_amount,''));
            		//意向投资地区
            		$post['joinLocation']= base64_encode($per_service->getPersonalArea($c_v->per_user_id));//加盟地点
            		$result=$ser->getApiReturn($url,$post,true);
            		$code=arr::get($result,'code','-1');
            		$msg=arr::get($result,'msg','未找到指定结果msg');
            		
            		if($code ==0){//成功
            			$c_i++;
            		}else{
            			$c_j++;
            		}

            		$c_orm=ORM::factory("CardCmsLog",$c_v->id);
            		$c_orm->status=1;
            		$c_orm->add_result_status=$code;
            		$c_orm->causes_failure = $msg;
            		$c_orm->add_time = time();
            		$c_orm->update(); 
            	}
            	$c_content='<p>【备注】:之前导入失败了<b style="color:red">'.$c_connt.'</b>条数据，今天再次重新导入，导入成功：<b style="color:red">'.$c_i.'</b>条，导入失败：<b style="color:red">'.$c_j.'</b>条（导入失败的数据只会再次导入一次）。</p>';
            }
            //留言发送完毕后 邮件通知
            usleep(100);
            if($ii && $usercount){//成功率
            	$tatal= $ii/$usercount;
            	$tatal2=number_format($tatal, 2, ".", "");
            	$results= $tatal2*100;
            }else{
            	$results='0';
            }
            //留言标题
            $liuyan_title=date('Y-m-d').'新平台招商外包项目留言导入统一留言系统日志';
            //留言内容
            $contenthead='
	        <!DOCTYPE html>
	        <html>
	        <head>
	        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	        </head><body>';
            $liuyan_content=$contenthead.'<p>Hi all</p><br>'.date("Y-m-d",strtotime("-1 day")).' 新平台招商外包项目和付费广告项目共有<b style="color:red">'.$usercount.'</b>条留言;<p>共成功导入统一留言系统<b style="color:red">'.$ii.'</b>条，导入失败<b style="color:red">'.$jj.'</b>条，成功率：<b style="color:red">'.$results.'%</b>。</p>'.$c_content.'<p>具体详情请详见CMS大后台导入日志。</p> <p>此为系统邮件，请勿回复。</p>
            <p>如有任何疑问，可联系我们技术人员：钟涛 ; Tel：13917909476 ; Email: zhongtao@tonglukuaijian.com</p><br><br>谢谢~</body></html>';
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'zhongtao@tonglukuaijian.com', $liuyan_content);
            usleep(10000);
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'shenpengfeic387@tonglukuaijian.com', $liuyan_content);
            usleep(10000);
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'zhengshufa@tonglukuaijian.com', $liuyan_content);
            usleep(10000);
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'chenfei@tonglukuaijian.com', $liuyan_content);
            usleep(10000);
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'liaojianyongd017@tonglukuaijian.com', $liuyan_content);
            usleep(10000);
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'zhangxiaochen@tonglukuaijian.com', $liuyan_content);
            usleep(10000);
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'zhaozhiquan@tonglukuaijian.com', $liuyan_content);
            usleep(10000);
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'huangfeid010@tonglukuaijian.com', $liuyan_content);
        } catch (Exception $e) {
        	//导入数据失败 SQL错误
        	common::sendemail('招商外包项目留言导入CRM系统log时SQL报错', 'service@yijuhua.net', 'zhongtao@tonglukuaijian.com', $e);
        }
	}
	
	/**
	 * 获取数据：昨天新平台875项目留言数据导入CRM系统
	 * @author 钟涛
	 */
	protected function _getDataInfo (){
		$starttime=strtotime(date('Y-m-d 00:00:00', time()-86400));//昨天开始时间
		$endtime=strtotime(date('Y-m-d 00:00:00', time()));//昨天结束时间
		$model= ORM::factory('Cardinfo');
		$result=$model->join('project','LEFT')->on('project_id','=','to_project_id')
			->where('to_project_id','>',0)//给项目留的言
			->where('project_real_order','in',array(1,2))//在推招商外包项目和付费广告项目
			->where('send_time','>=',$starttime)//昨天开始时间
			->where('send_time','<',$endtime)//昨天结束时间
			->group_by('from_user_id','to_project_id')//去重
			->select("*")->order_by('send_time', 'DESC')->find_all();
		return $result;
	}
	
}
?>