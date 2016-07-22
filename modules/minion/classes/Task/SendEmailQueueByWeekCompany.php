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
class Task_SendEmailQueueByWeekCompany extends Minion_Task
{
	 /**
     * 【企业用户】定时对队列的用户发送邮件【7天未登录的企业用户】
     * @author 钟涛
     */
	protected function _execute(array $params){
		exit;//暂不使用
		#cd D:\softserver\php-5.3.18 #php D:\webserver\lnvestment-platform\trunk\modules\minion\minion --task=SendEmailQueueByWeekCompany
        try {
        	//启动数据缓冲,采用ob_start将输出的信息及时显示
        	@ob_end_clean();
        	@ob_start();
        	//获取所有符合的用户
            $userinfo=$this->_getDataInfo();
			$ii=0;
            foreach($userinfo as $v){
            	//必须一起使用, 不然无法及时显示, PHP文档有说明
            	@ob_flush();
            	@flush();
            	$companyinfo=ORM::factory('Companyinfo')->where('com_user_id', '=', $v->user_id)->find();
                if($companyinfo->com_name){
                	$com_name=$companyinfo->com_name;//用户名
                	//邮件标题
                	$title=$com_name.'，您好，最近没来一句话，您错过了很多投资者哦！';
                }else{
                	$com_name='用户';//用户名
                	//邮件标题
                	$title='您好，最近没来一句话，您错过了很多投资者哦！';
                }
                //退订url
                $code = $v->user_id+1;
                $validcode=substr(md5($code),0,6);
                $tuiding_url=URL::website('regularscript/cancelSendEmailByCompany').'?code='.$validcode.'&key='.$v->user_id;
                //个人信息
                $personinfoarr=$this->_getPersonInfo($companyinfo->com_id);
                usleep(1000);
                $content=$this->getSendCount($personinfoarr,$tuiding_url,$com_name);
                $sendresult=common::sendemail($title, 'service@yijuhua.net', $v->email, $content);
                //第二步对日志表添加数据
                $ormlog= ORM::factory('EmailSendQueueLog');
            	if($sendresult==1){
            		$ormlog->send_result_status=1;//成功
            	}else{
            		$ormlog->send_result_status=0;//失败
            	}

            	$ormlog->user_id=$v->user_id;
            	$ormlog->email=$v->email;
            	$ormlog->send_type=$v->send_type;
            	
            	$ormlog->send_time=time();
            	$ormlog->create();
            	
            	//第三步删除队列表数据
            	$v->delete();
            	usleep(10000);
            	$ii++;
            }
            echo 'success:'.$ii;
        } catch (Exception $e) {
        	echo 'error';
        	//导入数据失败 SQL错误
        	common::sendemail('对队列用户邮件发送失败', 'service@yijuhua.net', 'zhongtao@tonglukuaijian.com', $e);
        }
	}
	
	/**
	 * 获取数据
	 * @author 钟涛
	 */
	protected function _getDataInfo (){
		$model= ORM::factory('EmailSendQueue');//队列表
		$result=$model->where('user_type','=',1)->where('send_type','=',1)//7天未登录的
			->limit("100")->order_by('add_time', 'DESC')->find_all();
		return $result;
	}
	
	
	/**
	 * 获取给企业推荐的个人用户
	 * @author 钟涛
	 */
	protected function _getPersonInfo ($comid=0){
		$returnarr=array();
		$money=common::moneyArr();
		//找推荐的开始
		if($comid){
			$projectinfo= ORM::factory('Project')->where('com_id','=',$comid)->find();
			if($projectinfo->project_id){
				$pi= ORM::factory('Projectindustry')->where("project_id", "=",$projectinfo->project_id)->order_by('pi_id', 'ASC')->find_all();
				$oneid=0;$twoid=0;$onename='';$twoname='';
				if(count($pi) > 0){
					foreach ($pi as $key=>$value){
						$pc= ORM::factory("Industry",$value->industry_id);
						if($pc->loaded()){
							if($pc->parent_id !=0){//2级行业
								$twoid=$pc->industry_id;
								$twoname=$pc->industry_name;
							}else{//1级行业
								$oneid=$pc->industry_id;
								$onename=$pc->industry_name;
							}
						}
					}
				}
				if($oneid || $twoid){//这个项目连行业都没有，还推荐毛线
					$memcache = Cache::instance ( 'memcache' );
					$listKey1 = 'SendEmailQueueByWeekCompany_have'.$oneid.'one';
					$listKey2 = 'SendEmailQueueByWeekCompany_have'.$twoid.'two';
					$returnarrt2 = $memcache->get($listKey2);
					if($returnarrt2){
						return $returnarrt2;
					}
					$returnarrt1 = $memcache->get($listKey1);
					if($returnarrt1){
						return $returnarrt1;
					}
					$twohuan=false;$onehuan=false;
					if($twoid){//有2级行业
						$perindustrlist= ORM::factory('UserPerIndustry')->where('industry_id','=',$twoid)->group_by('user_id')->limit(5)->find_all( );
						if(count($perindustrlist)!=5){//2级行业不足5个再推1级行业5个
							if($oneid){
								$onehuan=true;
								$perindustrlist= ORM::factory('UserPerIndustry')->where('industry_id','=',$oneid)->group_by('user_id')->limit(5)->find_all( );
							}
						}else{
							$twohuan=true;
						}
					}elseif($oneid){//有1级行业
						$onehuan=true;
						$perindustrlist= ORM::factory('UserPerIndustry')->where('industry_id','=',$oneid)->group_by('user_id')->limit(5)->find_all( );
					}else{
						$perindustrlist=array();
					}
					if(count($perindustrlist)==5){//走到这步，终于找到合适的推荐的5位同学，不容易啊
						foreach($perindustrlist as $k1=>$v){
							$personinfo=ORM::factory("Personinfo")->where('per_user_id','=',$v->user_id)->find();
							$returnarr[$k1]['id']=$v->user_id;
							$returnarr[$k1]['username']=$personinfo->per_realname;
							if($personinfo->per_photo){
								$returnarr[$k1]['per_photo']= URL::imgurl($personinfo->per_photo);
							}else{
								$returnarr[$k1]['per_photo']= URL::webstatic('images/find_invester/photo_man.jpg');
							}
							if($twoname){
								$returnarr[$k1]['hangye']= mb_substr($twoname,0,2,'UTF-8');
							}elseif($onename){
								$returnarr[$k1]['hangye']= mb_substr($onename,0,2,'UTF-8');
							}else{
								$returnarr[$k1]['hangye']='无';
							}
							$returnarr[$k1]['money']= arr::get($money,$personinfo->per_amount,'5-10万');
							$ormModel = ORM::factory('PersonalArea')->where('per_id', '=', $v->user_id)->find();
							if($ormModel->area_id){
								$city = ORM::factory('city', intval($ormModel->area_id));
								$returnarr[$k1]['area']=  mb_substr($city->cit_name,0,2,'UTF-8');
							}else{
								$returnarr[$k1]['area']= '';
							}
							$returnarr[$k1]['url']= URL::website('platform/SearchInvestor/showInvestorProfile'). '?userid='.$v->user_id.'&sid=d87c6a5ef017de1aefcf4180f1be296a_297';
						}
						if($twohuan){
							$memcache->set($listKey2, $returnarr, 7200);
						}elseif($onehuan){
							$memcache->set($listKey1, $returnarr, 7200);
						}else{	}
					}
				}
			}
		}
		//找推荐的结束
		if(count($returnarr)!=5){//没推荐的，那我来默认推荐
			$memcache = Cache::instance ( 'memcache' );
			$listKey = 'SendEmailQueueByWeekCompany_moren';
			$returnarrt = $memcache->get($listKey);
			if($returnarrt){
				return $returnarrt;
			}
			$personinfo2=ORM::factory("Personinfo")
						->where('per_realname','!=','')
						->where('per_photo','!=','')
						->where('per_amount','>',0)
						->order_by('per_createtime','DESC')
						->limit('5')
						->find_all();
			foreach($personinfo2 as $k1=>$v){
				$returnarr[$k1]['id']=$v->per_user_id;
				$returnarr[$k1]['username']=$v->per_realname;
				if($v->per_photo){
					$returnarr[$k1]['per_photo']= URL::imgurl($v->per_photo);
				}else{
					$returnarr[$k1]['per_photo']= URL::webstatic('images/find_invester/photo_man.jpg');
				}
				//行业
				$per_ind_ser= ORM::factory('UserPerIndustry')->where('user_id','=',$v->per_user_id)->find();
				if($per_ind_ser->parent_id){
					$hangye=ORM::factory('Industry',$per_ind_ser->parent_id)->industry_name;
					$returnarr[$k1]['hangye']=mb_substr($hangye,0,2,'UTF-8');
				}else{
					$returnarr[$k1]['hangye']='无';
				}
				$returnarr[$k1]['money']= arr::get($money,$v->per_amount,'5-10万');
				$ormModel = ORM::factory('PersonalArea')->where('per_id', '=', $v->per_user_id)->find();
				if($ormModel->area_id){
					$city = ORM::factory('city', intval($ormModel->area_id));
					$returnarr[$k1]['area']=  mb_substr($city->cit_name,0,2,'UTF-8');
				}else{
					$returnarr[$k1]['area']= '';
				}
				$returnarr[$k1]['url']= URL::website('platform/SearchInvestor/showInvestorProfile'). '?userid='.$v->per_user_id.'&sid=d87c6a5ef017de1aefcf4180f1be296a_297';
			}
			$memcache->set($listKey, $returnarr, 7200);
		}
		return $returnarr;
	}
	
	
	/**
	 * 获取发送内容
	 * @author 钟涛
	 */
	protected function getSendCount ($personinfo=array(),$tuiding_url='',$comname='用户'){
		$personinfotext='';
		foreach($personinfo as $v){
			$personinfotext.='<td colspan=1 style="text-align: center;">
				<a href="'.$v['url'].'" style="display: block; font-size: 0; width:125px; height:95px; text-align:center;">
					<img style="border:none; max-width:125px; max-height:95px; _width: 125px; _height:95px;" src="'.$v['per_photo'].'">
				</a>
				<div><a href="'.$v['url'].'" style="font-size: 18px; color: #0b73bb; display: block; text-decoration: none;">'.$v['username'].'</a></div>
				<div style="color:#666;font-size: 12px;">'.$v['hangye'].'，'.$v['money'].'，'.$v['area'].'</div>
			</td>';
		}
		return '<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<title></title>
<body>
	<table cellspacing="0" cellpadding="0" style="color: #333; font-family:\'微软雅黑\'; width: 700px; margin:0 auto;">
		<tr>
			<td colspan=5><a href="http://www.yjh.com?sid=d87c6a5ef017de1aefcf4180f1be296a_297"><img style="border:none;" src="'.URL::webstatic('').'/images/edm/qiyeyaoqing/logo.png"></a></td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0" style="color: #333; font-family:\'微软雅黑\'; width: 700px; margin:0 auto; border:1px solid #eee; padding-bottom: 20px;">
		<tr style="background:url('.URL::webstatic('').'/images/edm/qiyeyaoqing/bannerbg.png) no-repeat; width:700px; height: 441px;">
			<td valign="top" colspan=5>
				<div style="padding-left:96px; margin-top:37px; color:#fff; font-size:16px;line-height:1.4;">亲爱的'.$comname.'</div>
				<div style="padding-left:96px; color:#fff;margin-top:5px; font-size:16px;line-height:1.4;  ">您已超过7天没登录一句话商机速配平台（<a href="http://www.yjh.com?sid=d87c6a5ef017de1aefcf4180f1be296a_297" style="color:#ff0000; text-decoration: none;">www.yjh.com</a>），错过了很多投资者哟！</div>
				<div style="font-weight: bold; font-size:18px;padding-left:96px; margin-top:60px; color: #24a3db;font-family:\'微软雅黑\';line-height:1.4;">7天，新增<span style="color:#ff0000; font-size: 20px;">11502</span>投资者，对您项目感兴趣的意向投资者新增<span style="color:#ff0000; font-size: 20px;">1106</span></div>
				<div style="font-size:18px;margin-top:8px; font-weight: bold; padding-left:96px;color: #24a3db;font-family:\'微软雅黑\'; line-height:1.4;">7天，已有<span style="color:#ff0000; font-size: 20px;">2168</span> 投资者向企业用户投递名片</div>
			</td>
		</tr>
		<tr>
			'.$personinfotext.'
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0" style="color: #666; font-family:\'微软雅黑\'; width: 700px; margin:20px auto 0;font-size: 12px; text-align: center">
		<tr>
			<td>您收到邮箱因为您是一句话创业网注册会员如果您不希望收到此类邮件，请 <a href="'.$tuiding_url.'">点击此处</a> 退订</td>
		</tr>
		<tr>
			<td>上海通路快建网络服务外包有限公司</td>
		</tr>
	</table>
</body>
</html>';
	}
}
?>