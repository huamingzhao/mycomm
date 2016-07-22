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
class Task_UpdateUserLetterRePly extends Minion_Task
{
	 /**
     * 更新以前企业自动回复内容
     * @author 钟涛
     */
	protected function _execute(array $params){
		#php D:\webserver\lnvestment-platform\trunk\modules\minion\minion --task=UpdateUserLetterRePly
        try {
            //获取符合的数据
            $date=$this->_getDataInfo();
            $userarr0=array();
            $totalcount=count($date);
            $sendletter_default_content=common::sendlettercontent();
            $monarr=common::moneyArr();
            echo 'all_total_count:'.$totalcount.PHP_EOL;
            foreach($date as $v){//开始修改很更新数据啦
            	$orm= ORM::factory('UserLetter',$v->letter_id);
            	$letter_type_this=$orm->letter_type;
            	$ser_letter_content=$orm->content;
            	$defalutcontent='';
            	$t_project=ORM::factory('Project',$orm->to_project_id);
            	if($t_project->project_brand_name){
            		$t_projectname=$t_project->project_brand_name;
            	}else{
            		$t_projectname='';
            	}
            	$project_amount=arr::get($monarr,$t_project->project_amount_type,'5-10万');
            	if($letter_type_this==1){//1:我要咨询
            		if($ser_letter_content==$sendletter_default_content['1']){
            			$defalutcontent='感谢您关注'.$t_projectname.'加盟，负责'.$t_projectname.'加盟的招商经理会在48小时内与您联系。';
            		}elseif($ser_letter_content==$sendletter_default_content['2']){
            			$defalutcontent='您好，感谢您对'.$t_projectname.'加盟的关注，我们会尽快把'.$t_projectname.'加盟的相关资料发到您的提供的邮箱里。';
            		}elseif($ser_letter_content==$sendletter_default_content['3']){
            			$defalutcontent='感谢您对'.$t_projectname.'加盟的关注，我们的'.$t_projectname.'加盟费用是'.$project_amount.'左右。';
            		}elseif($ser_letter_content==$sendletter_default_content['4']){
            			$defalutcontent='感谢您对'.$t_projectname.'加盟的关注，我们将尽快与您电话联系。';
            		}else{	}
            	}elseif($letter_type_this==2){//2:索要资料
            		if($ser_letter_content==$sendletter_default_content['5']){
            			$defalutcontent='感谢您关注'.$t_projectname.'加盟信息，我们会尽快将'.$t_projectname.'加盟资料发给您。';
            		}elseif($ser_letter_content==$sendletter_default_content['6']){
            			$defalutcontent='感谢您关注'.$t_projectname.'加盟信息，'.$t_projectname.'加盟费用在'.$project_amount.'左右。';
            		}elseif($ser_letter_content==$sendletter_default_content['7']){
            			$defalutcontent='感谢您对'.$t_projectname.'加盟的关注，正在招商的项目'.$t_projectname.'加盟后期有很多支持措施，从管理、技术、营销策略、货源等都给予充分的支持。';
            		}elseif($ser_letter_content==$sendletter_default_content['8']){
            			$defalutcontent='感谢您关注'.$t_projectname.'加盟信息，负责'.$t_projectname.'加盟的客服代表会尽快与您联系。';
            		}else{	}
            	}else{//3:发送意向
            		if($ser_letter_content==$sendletter_default_content['9']){
            			$defalutcontent='感谢您对'.$t_projectname.'加盟的关注，我们会尽快将'.$t_projectname.'加盟流程发到您提供的邮箱，我们会尽快与您联系。';
            		}elseif($ser_letter_content==$sendletter_default_content['10']){
            			$defalutcontent='感谢您关注'.$t_projectname.'加盟信息，加盟'.$t_projectname.'随时都可以的，谢谢关注。';
            		}elseif($ser_letter_content==$sendletter_default_content['11']){
            			$defalutcontent='感谢您对'.$t_projectname.'加盟的关注，我们会尽快将 '.$t_projectname.'加盟流程发到您提供的邮箱，以便您更好的了解'.$t_projectname.'加盟信息。';
            		}elseif($ser_letter_content==$sendletter_default_content['12']){
            			$defalutcontent='感谢您关注'.$t_projectname.'加盟信息，在上海可以加盟'.$t_projectname.'。';
            		}else{	}
            	}
            	if($defalutcontent){//更新数据
            		$v->content=$defalutcontent;
            		$v->update();
            		echo 'update_data_sussess:id='.$v->id.PHP_EOL;
            	}else{//删除数据
            		$v->content=$defalutcontent;
            		$id=$v->id;
            		$v->delete();
            		echo 'delete_data_sussess:id='.$id.PHP_EOL;
            	}
            }
        } catch (Exception $e) {
        	//导入数据失败 SQL错误
        	common::sendemail('更新企业自动回复内容时出错', 'service@yijuhua.net', 'zhongtao@tonglukuaijian.com', $e);
        }
	}
	
	/**
	 * 获取数据：企业自动回复的内容
	 * @author 钟涛
	 */
	protected function _getDataInfo (){
		$reply_letter = ORM::factory('UserLetterReply')->where('reply_type','=',1)->find_all();
		return $reply_letter;
	}
	
}
?>