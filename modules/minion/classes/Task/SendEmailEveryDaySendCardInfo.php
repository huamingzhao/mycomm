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
class Task_SendEmailEveryDaySendCardInfo extends Minion_Task
{
	 /**
     * 定时每天发送个人递送名片详情
     * @author 钟涛
     */
	protected function _execute(array $params){
		#php D:\webserver\lnvestment-platform\trunk\modules\minion\minion --task=SendEmailEveryDaySendCardInfo
        try {
        	$starttime=strtotime(date('Y-m-d 00:00:00', time()-86400));//昨天开始时间
        	$endtime=strtotime(date('Y-m-d 00:00:00', time()));//昨天结束时间
        	//当天递送名片数据
            $userinfo=$this->_getDataInfo($starttime,$endtime);
            // 项目来源
//             $projectsourcearr = array (
//             		'1' => '<b style="color:#a600ca">本站免费项目</b>',
//             		'2' => '<b style="color:#FF0000">招商外包项目</b>',
//             		'3' =>'<b style="color:#FF0000">招商外包项目</b>',
//             		'4' =>'未认领外采项目',
//             		'5' =>'未认领外采项目',
//             		'6' =>'<b style="color:#2400c0">广告项目</b>',
//             		'7' => '<b style="color:#089b01">本站付费项目</b>'
//             );
            //项目类型
            $project_real_sourcearr = array (
            		'0.5' => '<b style="color:#FF0000">一句话招商外包项目</b>',
            		'1.0' => '<b style="color:#FF0000">在推招商外包项目</b>',
            		'2.0' => '<b style="color:#a600ca">付费广告项目</b>',
            		'3.0' =>'<b style="color:#2400c0">非付费广告项目</b>',
            		'4.0' => '<b style="color:#089b01">普通项目</b>',
            		'5.0' =>'<b style="color:#0286a2">停推招商外包项目</b>',
            		'6.0' =>'外采项目'
            );
            $allcount=self::getAllPorjectSendCount($starttime,$endtime);
            $oneinfo=date("Y-m-d",strtotime("-1 day")).' 一句话商机速配平台共有<b style="color:red">'.$allcount.'</b>条名片信息；<br>';
            if($allcount){//有留言
	            $table= "<table width=\"60%\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\">";
	            $table.= "<thead><tr><th colspan='5' nowrap>".date("Y-m-d",strtotime("-1 day"))."个人递送名片详情统计</th></tr><tr><th nowrap>项目id</th><th nowrap>项目名称</th><th nowrap>项目类型</th><th nowrap>公司名称</th><th nowrap>个人递送名片总数</th></tr>";
	            $thisarray=array();
	            foreach($userinfo as $k=>$v){
	            	$projectinfo=ORM::factory('Project',$v->project_id);
	            	$projectid=$v->project_id;
	            	$name=$projectinfo->project_brand_name;
	            	if(!$name){
	            		$name='无';
	            	}
	            	$projectname=$name;
	            	//$projectsource=arr::get($projectsourcearr,$projectinfo->project_source,'');
	            	//项目类型
	            	$project_real_source=arr::get($project_real_sourcearr,$projectinfo->project_real_order,'');
	            	$comobject=ORM::factory('Companyinfo',$projectinfo->com_id);
	            	$comname=$comobject->com_name;
	            	if(!$comname){
	            		if($projectinfo->project_source ==2 || $projectinfo->project_source ==3 ||$projectinfo->project_source ==6){
	            			if($projectinfo->outside_com_name ){
	            				$comname=$projectinfo->outside_com_name;
	            			}
	            		}
	            		if(!$comname){
	            			$comname='无';
	            		}
	            	}
	            	//是招商通会员
// 	            	if($projectinfo->project_source==1 && $comobject->platform_service_fee_status==1){//如果等于1判断下该企业是否有钱
// 	            		$projectsource='本站付费项目';
// 	            		$projectinfo->project_source=7;
// 	            	}else{
// 	            		$projectsource=arr::get($projectsourcearr,$projectinfo->project_source,'');
// 	            	}
	            	
	            	$totalcount=self::getPorjectSendCount($starttime,$endtime,$v->project_id);
	            	$thisarray[$k]['project_id']=$v->project_id;
	            	$thisarray[$k]['project_brand_name']=$projectname;
	            	//$thisarray[$k]['projectsource']=$projectsource;
	            	//$thisarray[$k]['projectsource_id']=$projectinfo->project_source;
	            	//项目类型
	            	$thisarray[$k]['project_real_order']=$project_real_source;
	            	$thisarray[$k]['project_real_order_id']=$projectinfo->project_real_order;
	            	
	            	$thisarray[$k]['comname']=$comname;
	            	$thisarray[$k]['totalcount']=$totalcount;
	            	usleep(10000);
	            }
	            //数组按照留言总数进行从高到低的排序
	            $thisarray2=common::multi_array_sort2($thisarray,'project_real_order_id',SORT_ASC,'totalcount',SORT_DESC);
	            usleep(1000);
	            $zhaoshangcount=0;
	            $fufeicount=0;
	            $mianfeicount=0;
	            $putongcount=0;
	            $tingtuicount=0;
	            $waicaicount=0;
	            $yijuhuacount=0;
	            foreach($thisarray2 as $k2=>$v2){
	            	if($v2['project_real_order_id']==1){//招商外包项目
	            		$zhaoshangcount +=$v2['totalcount'];
	            	}elseif($v2['project_real_order_id']==2){
	            		$fufeicount +=$v2['totalcount'];
	            	}elseif($v2['project_real_order_id']==3){
	            		$mianfeicount +=$v2['totalcount'];
	            	}elseif($v2['project_real_order_id']==4){
	            		$putongcount +=$v2['totalcount'];
	            	}elseif($v2['project_real_order_id']==5){
	            		$tingtuicount += $v2['totalcount'];
	            	}elseif($v2['project_real_order_id']==6){
	            		$waicaicount += $v2['totalcount'];
	            	}
	            	elseif($v2['project_real_order_id']=='0.5'){
	            		$yijuhuacount += $v2['totalcount'];
	            	}else{	}
	            	$table.= "<TR>";
	            	$table.= "<td align=\"center\" nowrap>".$v2['project_id']."</td>";
	            	$table.= "<td align=\"center\" nowrap>".$v2['project_brand_name']."</td>";
	            	$table.= "<td align=\"center\" nowrap>".$v2['project_real_order']."</td>";
	            	$table.= "<td align=\"center\" nowrap>".$v2['comname']."</td>";
	            	$color= "#FF0000";
	            	$table.= "<td style='color:".$color.";' align=\"center\" nowrap>".$v2['totalcount']."</td>";
	            	$table.= "</TR>";
	            }
	            //算比例
	            if($zhaoshangcount){//招商项目比例
	            	$tatal1= $zhaoshangcount/$allcount;
	            	$tatal2=number_format($tatal1, 2, ".", "");
	            	$zhaoshangcount_bili= $tatal2*100;
	            }else{
	            	$zhaoshangcount_bili='0';
	            }
	            if($fufeicount){//付费广告项目比例
	            	$tatal1= $fufeicount/$allcount;
	            	$tatal2=number_format($tatal1, 2, ".", "");
	            	$fufeicount_bili= $tatal2*100;
	            }else{
	            	$fufeicount_bili='0';
	            }
	            if($mianfeicount){//免费项目比例
	            	$tatal1= $mianfeicount/$allcount;
	            	$tatal2=number_format($tatal1, 2, ".", "");
	            	$mianfeicount_bili= $tatal2*100;
	            }else{
	            	$mianfeicount_bili='0';
	            }
	            if($putongcount){//普通项目比例
	            	$tatal1= $putongcount/$allcount;
	            	$tatal2=number_format($tatal1, 2, ".", "");
	            	$putongcount_bili= $tatal2*100;
	            }else{
	            	$putongcount_bili='0';
	            }
	            if($tingtuicount){//停推项目比例
	            	$tatal1= $tingtuicount/$allcount;
	            	$tatal2=number_format($tatal1, 2, ".", "");
	            	$tingtuicount_bili= $tatal2*100;
	            }else{
	            	$tingtuicount_bili='0';
	            }
	            if($waicaicount){//外采项目比例
	            	$tatal1= $waicaicount/$allcount;
	            	$tatal2=number_format($tatal1, 2, ".", "");
	            	$waicaicount_bili= $tatal2*100;
	            }else{
	            	$waicaicount_bili='0';
	            }
	            $twoinfo ='在推招商外包项目总数：<b style="color:#2400c0">'.$zhaoshangcount.'</b>;占总名片数量比例：<b style="color:#2400c0">'.$zhaoshangcount_bili.'%</b>;<br>';
	            $twoinfo .='付费广告项目总数：<b style="color:#2400c0">'.$fufeicount.'</b>;占总名片数量比例：<b style="color:#2400c0">'.$fufeicount_bili.'%</b>;<br>';
	            $twoinfo .='非付费广告项目总数：<b style="color:#2400c0">'.$mianfeicount.'</b>;占总名片数量比例：<b style="color:#2400c0">'.$mianfeicount_bili.'%</b>;<br>';
	            $twoinfo .='普通项目总数：<b style="color:#2400c0">'.$putongcount.'</b>;占总名片数量比例：<b style="color:#2400c0">'.$putongcount_bili.'%</b>;<br>';
	            $twoinfo .='停推招商外包项目总数：<b style="color:#2400c0">'.$tingtuicount.'</b>;占总名片数量比例：<b style="color:#2400c0">'.$tingtuicount_bili.'%</b>;<br>';
	            $twoinfo .='外采项目总数：<b style="color:#2400c0">'.$waicaicount.'</b>;占总名片数量比例：<b style="color:#2400c0">'.$waicaicount_bili.'%</b>;';
	            $table.= "</thead></table>";
	            $oneinfo1=$oneinfo.$twoinfo;
	            $table =$oneinfo1.$table;
            }else{
            	$table=$oneinfo;
            }
            $table.= "<br>";
            $table.= "<br>";
            //留言标题
            $liuyan_title=date("Y-m-d",strtotime("-1 day")).'一句话商机速配平台个人递送名片统计详情';
            //留言内容
            $contenthead='<!DOCTYPE html>
	        <html>
	        <head>
	        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	        </head><body>';
            $liuyan_content=$contenthead.'<p>Hi all</p><br>'.$table.'<br> <p>此为系统邮件，请勿回复。</p>
            <p>如有任何疑问，可联系我们技术人员：钟涛 ; Tel：13917909476 ; Email: zhongtao@tonglukuaijian.com</p><br><br>谢谢~</body></html>';
			//开始发送邮件
            usleep(10000);//钟涛
  			common::sendemail($liuyan_title, 'service@yijuhua.net', 'zhongtao@tonglukuaijian.com', $liuyan_content);
            usleep(10000);//沈鹏飞
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'shenpengfeic387@tonglukuaijian.com', $liuyan_content);
            usleep(10000);//郑书发
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'zhengshufa@tonglukuaijian.com', $liuyan_content);
            usleep(10000);//陈飞
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'chenfei@tonglukuaijian.com', $liuyan_content);
            usleep(10000);//周希田
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'zhouxitian@tonglukuaijian.com', $liuyan_content);
            usleep(10000);//张贵云
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'zhangguiyunD850@tonglukuaijian.com', $liuyan_content);
            usleep(10000);//董学华
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'dongxuehua@tonglukuaijian.com', $liuyan_content);
            usleep(10000);//陈洁
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'chenjie@tonglukuaijian.com', $liuyan_content);
            usleep(10000);//云姐
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'zhangyun@tonglukuaijian.com', $liuyan_content);
            usleep(10000);//张惠
            common::sendemail($liuyan_title, 'service@yijuhua.net', 'zhanghui@tonglukuaijian.com', $liuyan_content);
        } catch (Exception $e) {
        	//导入数据失败 SQL错误
        	common::sendemail('定时每天发送个人递送名片详情发送失败', 'service@yijuhua.net', 'zhongtao@tonglukuaijian.com', $e);
        }
	}
	
	/**
	 * 获取数据
	 * @author 钟涛
	 */
	protected function _getDataInfo ($starttime,$endtime){
		//个人递送名片统计
		$model= ORM::factory('Cardinfobehaviour')
				->where('project_id','>',0)
				->group_by('project_id')
				->where('user_type','=',2)
				->where('status','=',2)
				->where('add_time','>=',$starttime)
				->where('add_time','<',$endtime)
				->find_all();
		return $model;
	}
	
	/**
	 * 获取每个项目递送名片的数量
	 * @author 钟涛
	 */
	protected function getPorjectSendCount ($starttime,$endtime,$projectid){
		$modelcount= ORM::factory('Cardinfobehaviour')
		->where('project_id','=',$projectid)
		->where('user_type','=',2)
		->where('status','=',2)
		->where('add_time','>=',$starttime)
		->where('add_time','<',$endtime)
		->count_all();
		return $modelcount;
	}
	
	/**
	 * 递送名片的数量all
	 * @author 钟涛
	 */
	protected function getAllPorjectSendCount ($starttime,$endtime){
		$modelcount= ORM::factory('Cardinfobehaviour')
		->where('project_id','>',0)
		->where('user_type','=',2)
		->where('status','=',2)
		->where('add_time','>=',$starttime)
		->where('add_time','<',$endtime)
		->count_all();
		return $modelcount;
	}
	
}
?>