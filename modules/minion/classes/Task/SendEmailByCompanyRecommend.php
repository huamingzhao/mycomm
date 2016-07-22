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
class Task_SendEmailByCompanyRecommend extends Minion_Task
{
	 /**
     * 企业会员推荐机制
     * @author 钟涛
     */
	protected function _execute(array $params){
		exit;//暂停推送
		#cd D:\softserver\php-5.3.18 php D:\webserver\lnvestment-platform\trunk\modules\minion\minion --task=SendEmailByCompanyRecommend
        try {
            $projectinfo=$this->_getDataInfoByUserTable();//获取所有符合的项目
            foreach($projectinfo as $v){
            	//获取企业基本信息
            	$com_user = ORM::factory('Companyinfo',$v->com_id);
            	$userid=$com_user->com_user_id;
            	$platform_service_fee_status=$com_user->platform_service_fee_status;
            	if($userid){
            		//看看是不是已经点击退订了的，如果已经退订了，就别发咯
            		$unsubscribe1 = ORM::factory("UserUnsubscribe")->where('user_id','=',$userid)
            					->where('unsubscribe_type','=',1)
            					->where('unsubscribe_sec_type','=',3)->find();
            		if(!$unsubscribe1->loaded()){
            			//退订url
            			$code = $userid+1;
            			$validcode=substr(md5($code),0,6);
            			$tuiding_url=URL::website('regularscript/cancelSendEmailByCompanyTuiJian').'?code='.$validcode.'&key='.$userid;
            			$comname='';
            			if($com_user->com_name){
            				$comname=mb_substr($com_user->com_name,0,18,'UTF-8');
            			}
            			$ssoresult = Service_Sso_Client::instance()->getUserInfoById($userid);
            			//邮箱已验证【开始发邮件】
            			if($ssoresult->email && $ssoresult->valid_email==1 && $ssoresult->user_status==1){
            				$projectservice = new Service_User_Company_Project();
            				//项目的金额
            				$project_amount_type=$v->project_amount_type;
            				//项目的行业
            				$inarr=$projectservice->getProjectindustryAndId($v->project_id);
            				$industryid='';
            				if(isset($inarr['one_id'])){
            					$industryid=$inarr['one_id'];
            				}
            				$tuijiancount=80;$disongusername='';
            				if($platform_service_fee_status==1){//已付钱用户
            					$tuijiancount=2000+rand(1,999);//投资者人数
            					if($industryid){//开始推荐6个人[按行业来]
            						$personinfo=$this->_getPeopleIndustry($industryid);
            						if(count($personinfo)!=6){//默认的
            							$personinfo=$this->_getPeopleDefault();
            						}
            					}else{
            						$personinfo=$this->_getPeopleDefault();
            					}
            					$emailcontent=$this->_getIsPayMoneyEmailContent($tuijiancount,$personinfo,$comname,$tuiding_url);
            				}else{//未付钱用户
            					//开始推荐2个孩子
            					$twoarray=array();
            					if($industryid){//先获取2个给递送名片
            						$perinfotuijian=$this->_getPeopleIndustry2($industryid);
            						$total=count($perinfotuijian);
            						if($total>=2){
            							$ii=rand(1,floor($total/2));
            							$iii=rand(floor($total/2)+1,$total-1);
            							$twoarray[0]=$perinfotuijian[$ii];$twoarray[1]=$perinfotuijian[$iii];
            						}else{//默认推荐2个
            							$data=$this->_getPeopleDefault2();
            							$ii=rand(1,10);$iii=rand(11,19);
            							$twoarray[0]=$data[$ii];$twoarray[1]=$data[$iii];
            						}
            					}else{
            						$data=$this->_getPeopleDefault2();$ii=rand(1,10);$iii=rand(11,19);
            						$twoarray[0]=$data[$ii];$twoarray[1]=$data[$iii];
            					}
            					//开始推荐4个孩子
            					$fourarray=array();
            					$ser2=new Service_User();
            					//企业中心【最活跃会员】20位投资者
            					$fourarray=$ser2->getHuoyueduPerson();
            					$emailcontent=$this->_getNotPayMoneyEmailContent($twoarray,$fourarray,$comname,$tuiding_url);
            					//开始对2个孩子进行递送名片
            					foreach($twoarray as $twoa){
            						$disongusername=$twoa['user_name'];
            						$this->_sendcard($twoa['user_id'],$userid,$v->project_id);
            					}
            				}
            				//好了 开始发邮件
            				$liuyan_title=$comname.'您好，有投资者对您的项目感兴趣，快去看看吧！';
            				$sendresult=common::sendemail($liuyan_title, 'service@yijuhua.net', $ssoresult->email, $emailcontent);
            				$ormlog= ORM::factory('EmailSendQueueLog');
            				if($sendresult==1){
            					$ormlog->send_result_status=1;//成功
            				}else{
            					$ormlog->send_result_status=0;//失败
            				}	
            				$ormlog->user_id=$userid;
            				$ormlog->user_type=1;
            				$ormlog->email=$ssoresult->email;
            				$ormlog->send_type=6; //企业会员推荐EDM
            				$ormlog->send_time=time();
            				$ormlog->create();
            			}
            			//用户 短信已经通过验证【开始发短信】
            			if($ssoresult->mobile && $ssoresult->valid_mobile && $ssoresult->user_status==1){
            				if($platform_service_fee_status==1){//已付钱用户
            					$sendcontents='亲爱的'.$comname.'，一句话平台（www.yjh.com）为您匹配了'.$tuijiancount.'位适合您项目的投资者，赶快去看看吧，可以加快您的招商速度哟！';
            				}else{//未付钱用户
            					$sendcontents='亲爱的'.$comname.'，'.$disongusername.'投资者向您递送了名片，请登录www.yjh.com查看吧！';
            				}
            				//开始发短信
            				$resultd = common::send_message($ssoresult->mobile,$sendcontents, 'online');
            				$userser=new Service_User();
            				if($resultd->retCode!==0){//短信发送失败
            					//消息发送失败log
            					$userser->messageLog($ssoresult->mobile,$userid,15,$sendcontents,0);
            				}else{//消息发送成功log
            					$userser->messageLog($ssoresult->mobile,$userid,15,$sendcontents,1);
            				}
            			}//发短信end
            			usleep(10000);
            		}//没有退订的开始发邮件和手机短信 end
            	}//存在这个用户 end
            }//最外层 循环end
            echo 'success';
        } catch (Exception $e) {
        	//数据失败 SQL错误-邮件告诉我，我来查查原因
        	common::sendemail('企业会员推荐机制时SQL报错', 'service@yijuhua.net', 'zhongtao@tonglukuaijian.com', $e);
        }
	}
	
	/**
	 * 获取数据 付费广告项目或者普通项目
	 * @author 钟涛
	 */
	protected function _getDataInfoByUserTable (){
		$projectinfo=ORM::factory('Project')
					->where('com_id','>',0)
					->where('project_status','=',2)
					->where('project_real_order','in',array(2,4))
					->group_by('com_id')
					->find_all();
		return $projectinfo;
	}
	
	/**
	 * 递送名片
	 * @author 钟涛
	 */
	protected function _sendcard ($user_id,$touserid,$projectid){
		$ser=new Service_Card();
		$model = ORM::factory('Cardinfo');
		//我收到的名片
		$receivedCard = $ser->getReceiveNew($touserid,$user_id,$projectid);
		//发出的名片
		$outCard = $ser->getReceiveNew($user_id,$touserid,$projectid);
		if ($receivedCard->card_id || $outCard->card_id) {
			if ($outCard->card_id) {//我已经递出--走下面
				//重复递出名片
				$outCard->send_count=$outCard->send_count+1;
				$outCard->ip=ip2long( Request::$client_ip );//只记录个人用户ip
				$outCard->send_time=time()-rand(1,172800);//随机时间，不能固定时间，不然太假了
				$outCard->from_del_status=0;
				$outCard->to_del_status=0;
				$outCard->to_project_id = $projectid;
				$resultdata=$outCard->update();
			}elseif($receivedCard->card_id) {//我已经收到--走下面
				//交换名片
				$receivedCard->exchange_status=1;
				$receivedCard->exchange_time=time()-rand(1,172800);//随机时间，不能固定时间，不然太假了
				$receivedCard->to_del_status=0;
				$receivedCard->from_del_status=0;
				$receivedCard->to_project_id = $projectid;
				$receivedCard->ip=ip2long( Request::$client_ip );//只记录个人用户ip
				$resultdata=$receivedCard->update();
			}else{	}
		}else {//我没收到也没有递出--走下面
			if($user_id ){
				$cardinfo = array(
						'from_user_id' => $user_id,
						'to_user_id' => $touserid,
						'to_project_id' => $projectid,
						'send_time' => time()-rand(1,172800),//随机时间，不能固定时间，不然太假了
						'exchange_status' =>0,
						'ip' =>ip2long( Request::$client_ip ),
						'exchange_time' =>time()
				);
				try{
					$model->values($cardinfo)->create();
				}
				catch(Kohana_Exception $e){
				}
			}
		}
	}
	
	/**
	 * 根据所属行业获6个人
	 * @author 钟涛
	 */
	protected function _getPeopleIndustry ($indrsid){
		$memcache = Cache::instance ( 'memcache' );
		$msg_cache = $memcache->get('SendEmailByCompanyRecommend_BY'.$indrsid.'indrsid');
		if($msg_cache){
			return  $msg_cache;
		}else{
			//获取最新完善基本信息的6位个人用户
			$personinfo = ORM::factory('UserPerIndustry')
				->where('industry_id','=', $indrsid)
				->group_by('user_id')
				->order_by('user_id','desc')
				->limit(6)//排序
				->find_all();
			$returnarr=array();
			$perser=new Service_Platform_SearchInvestor();
			$per_service = new Service_User_Person_User();
			$monarr= common::moneyArr();
			$i=0;
			foreach($personinfo as $v){
				$perinfo = ORM::factory('Personinfo')->where('per_user_id','=',$v->user_id)->find();
				$returnarr[$i]['user_id']=$v->user_id;//用户id
				$returnarr[$i]['user_name']=$perinfo->per_realname?$perinfo->per_realname:'匿名';//姓名
				$returnarr[$i]['per_photo']=$perinfo->per_photo?URL::imgurl($perinfo->per_photo):'http://pic.yjh.com/user_icon/plant/default_icon_16.jpg';//头像
				$returnarr[$i]['link_url']=URL::website('platform/SearchInvestor/showInvestorProfile').'?userid='.$v->user_id.'&sid=460d1b7006858ba445b9ab460f3b04f2_325';//链接地址
				//活跃度
				$returnarr[$i]['huoyuedu']=$perser->getAllScore($v->user_id);
				//添加行业信息
				$returnarr[$i]['this_per_industry']=ORM::factory('Industry',$v->parent_id)->industry_name;
				//获得个人意向投资地
				$arr= $per_service->getPerasonalAreaStringOnlyPro($v->user_id);
				$returnarr[$i]['this_per_area'] =$arr?$arr:'全国';
				//行业
				$returnarr[$i]['per_amount']= arr::get($monarr,$perinfo->per_amount,'5-10万');
				$i++;
			}
			$memcache->set('SendEmailByCompanyRecommend_default',$returnarr,86400);
		}
		return $returnarr;
	}
	
	/**
	 * 根据所属行业获20个人
	 * @author 钟涛
	 */
	protected function _getPeopleIndustry2 ($indrsid){
		$memcache = Cache::instance ( 'memcache' );
		$msg_cache = $memcache->get('SendEmailByCompanyRecommend_BY2'.$indrsid.'indrsid2');
		if($msg_cache){
			return  $msg_cache;
		}else{
			//获取最新完善基本信息的6位个人用户
			$personinfo = ORM::factory('UserPerIndustry')
			->where('industry_id','=', $indrsid)
			->group_by('user_id')
			->order_by('user_id','desc')
			->limit(20)//排序
			->find_all();
			$returnarr=array();
			$perser=new Service_Platform_SearchInvestor();
			$per_service = new Service_User_Person_User();
			$monarr= common::moneyArr();
			$i=0;
			foreach($personinfo as $v){
				$perinfo = ORM::factory('Personinfo')->where('per_user_id','=',$v->user_id)->find();
				$returnarr[$i]['user_id']=$v->user_id;//用户id
				$returnarr[$i]['user_name']=$perinfo->per_realname?$perinfo->per_realname:'匿名';//姓名
				$returnarr[$i]['per_photo']=$perinfo->per_photo?URL::imgurl($perinfo->per_photo):'http://pic.yjh.com/user_icon/plant/default_icon_16.jpg';//头像
				$returnarr[$i]['link_url']=URL::website('platform/SearchInvestor/showInvestorProfile').'?userid='.$v->user_id.'&sid=460d1b7006858ba445b9ab460f3b04f2_325';//链接地址
				//活跃度
				$returnarr[$i]['huoyuedu']=$perser->getAllScore($v->user_id);
				//添加行业信息
				$returnarr[$i]['this_per_industry']=ORM::factory('Industry',$v->parent_id)->industry_name;
				//获得个人意向投资地
				$arr= $per_service->getPerasonalAreaStringOnlyPro($v->user_id);
				$returnarr[$i]['this_per_area'] =$arr?$arr:'全国';
				//行业
				$returnarr[$i]['per_amount']= arr::get($monarr,$perinfo->per_amount,'5-10万');
				$i++;
			}
			$memcache->set('SendEmailByCompanyRecommend_default',$returnarr,86400);
		}
		return $returnarr;
	}
	/**
	 * 获取默认的 6个人
	 * @author 钟涛
	 */
	protected function _getPeopleDefault (){
		$memcache = Cache::instance ( 'memcache' );
		$msg_cache = $memcache->get('SendEmailByCompanyRecommend_default');
		if($msg_cache){
			return  $msg_cache;
		}else{
			//获取最新完善基本信息的6位个人用户
			$personinfo = ORM::factory('Personinfo')
				->where('per_realname','!=', '')
				->where('per_photo','!=', null)
				->where('per_open_stutas','!=', 3)
				->order_by('per_createtime', 'desc')//排序
				->limit(6)//排序
				->find_all();
			$returnarr=array();
			$perser=new Service_Platform_SearchInvestor();
			$per_service = new Service_User_Person_User();
			$monarr= common::moneyArr();
			$i=0;
			foreach($personinfo as $v){
				$returnarr[$i]['user_id']=$v->per_user_id;//用户id
				$returnarr[$i]['user_name']=$v->per_realname;//姓名
				$returnarr[$i]['per_photo']=URL::imgurl($v->per_photo);//头像
				$returnarr[$i]['link_url']=URL::website('platform/SearchInvestor/showInvestorProfile').'?userid='.$v->per_user_id.'&sid=460d1b7006858ba445b9ab460f3b04f2_325';//链接地址
				//活跃度
				$returnarr[$i]['huoyuedu']=$perser->getAllScore($v->per_user_id);
				//添加行业信息
				$this_per_industry=$per_service->getPerasonalParentIndustry($v->per_user_id);
				if($this_per_industry){
					foreach($this_per_industry as $keyIarr => $valIarr){
						if($valIarr){
							$returnarr[$i]['this_per_industry']=$valIarr;
						}else{
							$returnarr[$i]['this_per_industry']='无';
						}
					}
				}
				else{
					$returnarr[$i]['this_per_industry']= "无";
				}
				//获得个人意向投资地
				$arr= $per_service->getPerasonalAreaStringOnlyPro($v->per_user_id);
				$returnarr[$i]['this_per_area'] =$arr?$arr:'全国';
				//行业
				$returnarr[$i]['per_amount']= arr::get($monarr,$v->per_amount,'5-10万');
				$i++;
			}
			$memcache->set('SendEmailByCompanyRecommend_default',$returnarr,86400);
		}
		return $returnarr;
	}
	
	
	/**
	 * 获取默认的 20个人
	 * @author 钟涛
	 */
	protected function _getPeopleDefault2 (){
		$memcache = Cache::instance ( 'memcache' );
		$msg_cache = $memcache->get('SendEmailByCompanyRecommend20_default20');
		if($msg_cache){
			return  $msg_cache;
		}else{
			//获取最新完善基本信息的6位个人用户
			$personinfo = ORM::factory('Personinfo')
			->where('per_realname','!=', '')
			->where('per_photo','!=', null)
			->where('per_open_stutas','!=', 3)
			->order_by('per_createtime', 'desc')//排序
			->limit(20)//排序
			->find_all();
			$returnarr=array();
			$perser=new Service_Platform_SearchInvestor();
			$per_service = new Service_User_Person_User();
			$monarr= common::moneyArr();
			$i=0;
			foreach($personinfo as $v){
				$returnarr[$i]['user_id']=$v->per_user_id;//用户id
				$returnarr[$i]['user_name']=$v->per_realname;//姓名
				$returnarr[$i]['per_photo']=URL::imgurl($v->per_photo);//头像
				$returnarr[$i]['link_url']=URL::website('platform/SearchInvestor/showInvestorProfile').'?userid='.$v->per_user_id.'&sid=460d1b7006858ba445b9ab460f3b04f2_325';//链接地址
				//活跃度
				$returnarr[$i]['huoyuedu']=$perser->getAllScore($v->per_user_id);
				//添加行业信息
				$this_per_industry=$per_service->getPerasonalParentIndustry($v->per_user_id);
				if($this_per_industry){
					foreach($this_per_industry as $keyIarr => $valIarr){
						if($valIarr){
							$returnarr[$i]['this_per_industry']=$valIarr;
						}else{
							$returnarr[$i]['this_per_industry']='无';
						}
					}
				}
				else{
					$returnarr[$i]['this_per_industry']= "无";
				}
				//获得个人意向投资地
				$arr= $per_service->getPerasonalAreaStringOnlyPro($v->per_user_id);
				$returnarr[$i]['this_per_area'] =$arr?$arr:'全国';
				//行业
				$returnarr[$i]['per_amount']= arr::get($monarr,$v->per_amount,'5-10万');
				$i++;
			}
			$memcache->set('SendEmailByCompanyRecommend_default',$returnarr,86400);
		}
		return $returnarr;
	}
	
	/**
	 * 获取已经付钱的企业邮件内容\'微软雅黑\'
	 * @author 钟涛
	 */
	protected function _getIsPayMoneyEmailContent ($count,$personinfo=array(),$comname='',$tuiding_url=''){
		$content='';
		$title= '
<table cellspacing="0" cellpadding="0" style="color: #333; font-family:\'微软雅黑\'; width: 700px; margin:0 auto;">
    <tr>
      <td colspan=5><a href="http://www.yjh.com?sid=460d1b7006858ba445b9ab460f3b04f2_325"><img style="border:none;" src="'.URL::webstatic("images/edm/qiyeyaoqing/logo.png").'"></a></td>
    </tr>
</table>
<table cellspacing="0" cellpadding="0" style="color: #333; font-family:\'微软雅黑\'; width: 700px; margin:0 auto; border:1px solid #d9d9d9;">
    <tr>
      <td colspan=2>
        <div style="background:url('.URL::webstatic("images/edm/qiyetuijian/tablebg.png").') no-repeat;height: 265px;">
          <div style="font-weight: bold; color:#fff;font-size: 30px;padding-top: 65px;margin-left: 400px;">'.$comname.'</div>
          <div style="font-weight: bold; color:#fff;font-size:16px;margin-top:20px;margin-left: 400px;word-break:break-all;width:290px; line-height:26px;">一句话平台(<a href="http://www.yjh.com?sid=460d1b7006858ba445b9ab460f3b04f2_325" style="color:#0036ff;text-decoration: none;">www.yjh.com</a>)<br>为您匹配了<b style="color:red;"> '.$count.' </b>位适合您项目的投资者，<a href="http://www.yjh.com?sid=460d1b7006858ba445b9ab460f3b04f2_325" style="color:#0036ff;text-decoration: none;">快去看看吧！</a></div>
        </div>
      </td>
    </tr>';
		for ($e=0;$e<3;$e++){
			if($e==0){
				$ee=0;$eee=1;
			}elseif($e==1){
				$ee=2;$eee=3;
			}else{
				$ee=4;$eee=5;
			}
			$content.='<tr>
      <td>
        <div style="height:101px; padding: 20px 0; margin-left: 20px; border-bottom:1px dashed #d9d9d9;">
          <a href="http://www.yjh.com/platform/SearchInvestor/showInvestorProfile?userid='.$personinfo[$ee]['user_id'].'&sid=460d1b7006858ba445b9ab460f3b04f2_325" style="float: left;margin-right:15px; width:110px; height:100px; font-size:0; *font-size:78px; text-align:center; display: block;"><img style="vertical-align: middle;max-height: 100px;max-width: 110px;border: none;" src="'.$personinfo[$ee]['per_photo'].'"></a>
        <div style="float:left;" class="invester_infor_name">
          <div style="height:25px;"><a href="http://www.yjh.com/platform/SearchInvestor/showInvestorProfile?userid='.$personinfo[$ee]['user_id'].'&sid=460d1b7006858ba445b9ab460f3b04f2_325" style="color: #0b73bb;font-size: 18px;float: left; text-decoration:none;">'.$personinfo[$ee]['user_name'].'</a></div>
          <div style="clear:both;height: 18px;color: #fe6500;padding-left: 52px;font: 18px/18px Georgia;background: url('.URL::webstatic("images/find_invester/active_num.jpg").') no-repeat 0 0;margin-top: 5px">'.$personinfo[$ee]['huoyuedu'].'</div>
          <div style="color:#999"> <a style="font-size: 12px; color: #999999; text-decoration: none;" target="_blank" href="http://www.yjh.com/xiangdao/fenlei/?sid=460d1b7006858ba445b9ab460f3b04f2_325">'.$personinfo[$ee]['this_per_industry'].'</a>，<a style="font-size: 12px; color: #999999; text-decoration: none;" target="_blank" href="http://www.yjh.com/xiangdao/fenlei/?sid=460d1b7006858ba445b9ab460f3b04f2_325">'.$personinfo[$ee]['per_amount'].'</a>，<a style="font-size: 12px; color: #999999; text-decoration: none;" target="_blank" href="http://www.yjh.com/xiangdao/fenlei/?sid=460d1b7006858ba445b9ab460f3b04f2_325">'.$personinfo[$ee]['this_per_area'].'</a></div>
          <div><a href="http://www.yjh.com/platform/SearchInvestor/showInvestorProfile?userid='.$personinfo[$ee]['user_id'].'&sid=460d1b7006858ba445b9ab460f3b04f2_325" style="margin-top:3px; cursor: pointer;display: inline-block;border-radius: 5px;width: 65px;height: 25px;line-height: 25px;text-align: center;text-decoration:none;box-shadow: 0px 1px 2px #999, 0px 1px 1px #e9514a inset;background-color: #FE9807;background: -webkit-gradient(linear, 0 0, 0 100%, from(#FD9706), to(#FD8900));
background: -webkit-linear-gradient(#FD9706, #FD8900);background: -moz-linear-gradient(#FD9706, #FD8900);background: -o-linear-gradient(#FD9706, #FD8900);background: linear-gradient(#FD9706, #FD8900);border: 1px solid #FE9807;color: #ffffff;font-size: 12px;" rel="nofollow">去看看</a></div>
        </div>
        </div>
      </td>
      <td>
        <div style="height:101px; padding: 20px 0; margin-right: 20px; border-bottom:1px dashed #d9d9d9;">
          <a href="http://www.yjh.com/platform/SearchInvestor/showInvestorProfile?userid='.$personinfo[$eee]['user_id'].'&sid=460d1b7006858ba445b9ab460f3b04f2_325" style="float: left;margin-right:15px; width:110px; height:100px; font-size:0; *font-size:78px; text-align:center; display: block;"><img style="vertical-align: middle;max-height: 100px;max-width: 110px;border: none;" src="'.$personinfo[$eee]['per_photo'].'"></a>
        <div style="float:left;" class="invester_infor_name">
          <div style="height:25px;"><a href="http://www.yjh.com/platform/SearchInvestor/showInvestorProfile?userid='.$personinfo[$eee]['user_id'].'&sid=460d1b7006858ba445b9ab460f3b04f2_325" style="color: #0b73bb;font-size: 18px;float: left; text-decoration:none;">'.$personinfo[$eee]['user_name'].'</a></div>
          <div style="height: 18px;color: #fe6500;padding-left: 52px;font: 18px/18px Georgia;background: url(http://static.myczzs.com/images/find_invester/active_num.jpg) no-repeat 0 0;margin-top: 5px;clear:both">'.$personinfo[$eee]['huoyuedu'].'</div>
          <div style="color:#999"> <a style="font-size: 12px; color: #999999; text-decoration: none;" target="_blank" href="http://www.yjh.com/xiangdao/fenlei/?sid=460d1b7006858ba445b9ab460f3b04f2_325">'.$personinfo[$eee]['this_per_industry'].'</a>，<a style="font-size: 12px; color: #999999; text-decoration: none;" target="_blank" href="http://www.yjh.com/xiangdao/fenlei/?sid=460d1b7006858ba445b9ab460f3b04f2_325">'.$personinfo[$eee]['per_amount'].'</a>，<a style="font-size: 12px; color: #999999; text-decoration: none;" target="_blank" href="http://www.yjh.com/xiangdao/fenlei/?sid=460d1b7006858ba445b9ab460f3b04f2_325">'.$personinfo[$eee]['this_per_area'].'</a></div>
          <div>
            <a href="http://www.yjh.com/platform/SearchInvestor/showInvestorProfile?userid='.$personinfo[$eee]['user_id'].'&sid=460d1b7006858ba445b9ab460f3b04f2_325" style="margin-top:3px; cursor: pointer;display: inline-block;border-radius: 5px;width: 65px;height: 25px;line-height: 25px;text-align: center;text-decoration:none;box-shadow: 0px 1px 2px #999, 0px 1px 1px #e9514a inset;background-color: #FE9807;background: -webkit-gradient(linear, 0 0, 0 100%, from(#FD9706), to(#FD8900));
background: -webkit-linear-gradient(#FD9706, #FD8900);background: -moz-linear-gradient(#FD9706, #FD8900);background: -o-linear-gradient(#FD9706, #FD8900);background: linear-gradient(#FD9706, #FD8900);border: 1px solid #FE9807;color: #ffffff;font-size: 12px;" rel="nofollow">去看看</a>
        </div>
        </div>
        </div>
      </td>
    </tr>';
		}
		$end='<tr colspan=2>
        <td style="font-size: 12px;padding-left:15px;height: 50px;">还有更多适合您的投资者，敬请期待您的关注......</td>
    </tr>
</table>
<table cellspacing="0" cellpadding="0" style="color: #333; font-family:\'微软雅黑\'; width: 700px; margin:0 auto;font-size: 12px; text-align: center;padding-top: 30px;">
  <tr>
    <td>您收到邮箱因为您是一句话创业网注册会员，如果您不希望收到此类邮件，请<a href="'.$tuiding_url.'">点击此处</a>退订</td>
  </tr>
  <tr>
    <td style="padding-top:15px;">上海通路快建网络服务外包有限公司</td>
  </tr>
</table>';
		return $title.$content.$end;
	}
	

	/**
	 * 获取没付钱的企业邮件内容
	 * @author 钟涛
	 */
	function _getNotPayMoneyEmailContent ($personinfo=array(),$personinfo2=array(),$comname='',$tuiding_url=''){
		$content1='';$content2='';$content3='';
		$title= '<table cellspacing="0" cellpadding="0" style="color: #333; font-family:\'微软雅黑\'; width: 700px; margin:0 auto;">
    <tr>
      <td colspan=5><a href="http://www.yjh.com?sid=460d1b7006858ba445b9ab460f3b04f2_325"><img style="border:none;" src="'.URL::webstatic("images/edm/qiyeyaoqing/logo.png").'"></a></td>
    </tr>
</table>
<table cellspacing="0" cellpadding="0" style="color: #333; font-family:\'微软雅黑\'; width: 700px; margin:0 auto; border:1px solid #d9d9d9;">
    <tr>
      <td colspan=4>
        <div style="background:url('.URL::webstatic("images/edm/qiyetuijian/tablebg.png").') no-repeat;height: 265px;">
          <div style="font-weight: bold; color:#fff;font-size: 30px;padding-top: 65px;margin-left: 400px;">'.$comname.'</div>
          <div style="font-weight: bold; color:#fff;font-size:16px;margin-top:20px;margin-left: 400px;word-break:break-all;width:290px; line-height:26px;">有新的投资者向您递送名片哦！<br>快去一句话平台（<a href="http://www.yjh.com?sid=460d1b7006858ba445b9ab460f3b04f2_325" style="color:#0036ff;text-decoration: none; cursor:pointer;">www.yjh.com</a>）看看吧！</div>
        </div>
      </td>
    </tr>
    <tr>';
		foreach($personinfo as $pe1){
			$content1.='<td  colspan=2>
        <div style="border-bottom:1px dashed #d9d9d9; padding: 20px 0; margin-left:30px; height:101px;">
          <a href="http://www.yjh.com/platform/SearchInvestor/showInvestorProfile?userid='.$pe1['user_id'].'&sid=460d1b7006858ba445b9ab460f3b04f2_325" style="float: left;margin-right:15px; width:110px; height:100px; font-size:0; *font-size:78px; text-align:center; display: block;"><img style="vertical-align:middle; max-height: 100px;max-width: 110px; border: none;" src="'.$pe1['per_photo'].'"></a>
        <div style="float:left;" class="invester_infor_name">
          <div style="height:25px;"><a href="javascript:void(0)" style="color: #0b73bb;font-size: 18px;float: left;text-decoration: none;">'.$pe1['user_name'].'</a></div>
          <div style="height: 18px;color: #fe6500;padding-left: 52px;font: 18px/18px Georgia;background: url('.URL::webstatic("images/find_invester/active_num.jpg").') no-repeat 0 0;margin-top: 5px;clear:both">'.$pe1['huoyuedu'].'</div>
          <div style="text-align: center;"> <a style="font-size: 12px; color: #999999; text-decoration: none;text-align: center;" target="_blank" href="http://www.yjh.com/xiangdao/fenlei/?sid=460d1b7006858ba445b9ab460f3b04f2_325">'.$pe1['this_per_industry'].'</a>，<a style="font-size: 12px; color: #999999; text-decoration: none;text-align: center;" target="_blank" href="http://www.yjh.com/xiangdao/fenlei/?sid=460d1b7006858ba445b9ab460f3b04f2_325">'.$pe1['per_amount'].'</a>，<a style="font-size: 12px; color: #999999; text-decoration: none;text-align: center;" target="_blank" href="http://www.yjh.com/xiangdao/fenlei/?sid=460d1b7006858ba445b9ab460f3b04f2_325">，'.$pe1['this_per_area'].'</a></div>
          <div><a href="http://www.yjh.com/platform/SearchInvestor/showInvestorProfile?userid='.$pe1['user_id'].'&sid=111" style="margin-top:3px; cursor: pointer;display: inline-block;border-radius: 5px;width: 65px;height: 25px;line-height: 25px;text-align: center;text-decoration:none;box-shadow: 0px 1px 2px #999, 0px 1px 1px #e9514a inset;background-color: #FE9807;background: -webkit-gradient(linear, 0 0, 0 100%, from(#FD9706), to(#FD8900));
background: -webkit-linear-gradient(#FD9706, #FD8900);background: -moz-linear-gradient(#FD9706, #FD8900);background: -o-linear-gradient(#FD9706, #FD8900);background: linear-gradient(#FD9706, #FD8900);border: 1px solid #FE9807;color: #ffffff;font-size: 12px;" rel="nofollow">去看看</a></div>
        </div>
        </div>
            </td>';
		}
	
		$content2='</tr>
    <tr colspan=4>
      <td style="font-size: 18px; color: #dc1b14; font-weight: bold;padding: 40px 0 20px 30px;">猜你喜欢的</td>
    </tr>
    <tr>';
		$p3=0;
		foreach($personinfo2 as $pe3){
			if($p3>=4){break;}else{
				$content3.='<td style="padding-bottom:30px;">
        <div class="clearfix"><a href="http://www.yjh.com/platform/SearchInvestor/showInvestorProfile?userid='.$pe3['user_id'].'&sid=460d1b7006858ba445b9ab460f3b04f2_325" style="margin-right:15px; text-align:center; display: block;float: none;margin: 0 auto; width:120px; height:85px; font-size:0; *font-size:94px;"><img style="vertical-align:middle; width:120px; height:85px; border: none;" src="'.$pe3['per_photo'].'"></a></div>
        <div class="clearfix" style="text-align: center; height: 45px;line-height: 45px;"><a href="http://www.yjh.com/platform/SearchInvestor/showInvestorProfile?userid='.$pe3['user_id'].'&sid=460d1b7006858ba445b9ab460f3b04f2_325" style="color: #0b73bb;font-size: 18px;text-decoration: none;">'.$pe3['per_realname'].'</a></div>
        <div style="color:#999; text-align:center;"> <a style="font-size: 12px; color: #999999; text-decoration: none;" target="_blank" href="http://www.yjh.com/xiangdao/fenlei/?sid=460d1b7006858ba445b9ab460f3b04f2_325">'.$pe3['this_per_industry'].'</a>，<a style="font-size: 12px; color: #999999; text-decoration: none;" target="_blank" href="http://www.yjh.com/xiangdao/fenlei/?sid=460d1b7006858ba445b9ab460f3b04f2_325">'.$pe3['per_amount'].'</a>，<a style="font-size: 12px; color: #999999; text-decoration: none;" target="_blank" href="http://www.yjh.com/xiangdao/fenlei/?sid=460d1b7006858ba445b9ab460f3b04f2_325">'.$pe3['this_per_area'].'</a></div>
      </td>';
			}
			$p3++;
		}
	
		$end='</tr>
</table>
<table cellspacing="0" cellpadding="0" style="color: #333; font-family:\'微软雅黑\'; width: 700px; margin:0 auto;font-size: 12px; text-align: center;padding-top: 30px;">
  <tr>
    <td>您收到邮箱因为您是一句话创业网注册会员,如果您不希望收到此类邮件，请<a href="'.$tuiding_url.'">点击此处</a>退订</td>
  </tr>
  <tr>
    <td style="padding-top:15px;">上海通路快建网络服务外包有限公司</td>
  </tr>
</table>';
		return $title.$content1.$content2.$content3.$end;
	}
}
?>