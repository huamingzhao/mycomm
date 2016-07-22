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
class Task_SendEmailQueueByMonth extends Minion_Task
{
	 /**
     * 定时对队列的用户发送邮件【1个月天未登录的个人用户】
     * @author 钟涛
     */
	protected function _execute(array $params){
		exit;//暂不使用
		#php D:\webserver\lnvestment-platform\trunk\modules\minion\minion --task=SendEmailQueueByMonth
        try {
        	//启动数据缓冲,采用ob_start将输出的信息及时显示
        	@ob_end_clean();
        	@ob_start();
        	//获取所有符合的用户
            $userinfo=$this->_getDataInfo();
            $content=$this->getSendCount();
            foreach($userinfo as $v){
            	//必须一起使用, 不然无法及时显示, PHP文档有说明
            	@ob_flush();
            	@flush();
            	$personinfo=ORM::factory("Personinfo")->where('per_user_id','=',$v->user_id)->find();
                if($personinfo->per_realname){
                	$title=$personinfo->per_realname.'，欢迎回来，你不在，一句话商机速配顾问很想念！';
                }else{
                	$title='您好，欢迎回来，你不在，一句话商机速配顾问很想念！';
                }
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
            	usleep(100000);
            }
           
        } catch (Exception $e) {
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
		$result=$model->where('user_type','=',2)->where('send_type','=',2)//1个月未登录的
			->limit("100")->order_by('add_time', 'DESC')->find_all();
		return $result;
	}
	
	/**
	 * 获取发送内容
	 * @author 钟涛
	 */
	protected function getSendCount (){
		return '<!DOCTYPE html>
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        </head><body>
		<table cellspacing="0" cellpadding="0" style="width:700px; margin: 0 auto;">
	<tr>
		<td>
			<a href="'.URL::website('').'?sid=9c0c170c23005b316e2c5c614c40c1a0_223
"><img style="border:0 none;" src="'.URL::webstatic('').'/images/edm/derutixing/logo.png"></a>
		</td>
		<td style="text-align: right;">
			<a href="http://www.yjh.com/xiangdao/?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color:#0b73bb; font-family:\'微软雅黑\'; font-size: 12px;text-decoration: none;margin-left: 5px;">找项目</a>
			<a href="http://www.yjh.com/zixun/?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color:#0b73bb; font-family:\'微软雅黑\'; font-size: 12px;text-decoration: none;margin-left: 5px;">学开店</a>
			<a href="'.URL::webwen('').'?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color:#0b73bb; font-family:\'微软雅黑\'; font-size: 12px;text-decoration: none;margin-left: 5px;">创业问答</a>
		</td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" style="width:700px; margin:15px auto 0; border: 1px solid #d9d9d9;">
	<tr>
		<td colspan="2" style="width:700px; height: 240px;">
			<div style="background:url('.URL::webstatic('').'/images/edm/derutixing/titlebg.png) no-repeat; width:600px; margin:30px auto 0; height: 240px;">
				<div style=" margin-left:330px; padding-top: 25px; font-size: 14px; line-height: 28px;">
				<span style="font-size: 14px; color: #000;">您已超过30天没登录<a href="'.URL::website('').'?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color: #ff0000; font-weight: bold; text-decoration: none;">一句话商机速配平台</a></span><br>
				<span style=" display: inline-block; margin-top: 10px; font-family:\'微软雅黑\'; font-weight: bold; color: #3b3b3b;">30天，已经有<font style="line-height:28px;font-family:\'Georgia\'; font-size: 24px; color: #ff0000; ">120万</font>人登录我们网站</span><br>
				<span style="font-family:\'微软雅黑\'; font-weight: bold; color: #3b3b3b;">30天，已经有<font style="line-height:28px;font-family:\'Georgia\'; font-size: 24px; color: #ff0000; ">25万</font>人选择创业项目</span><br>
				<span style="font-family:\'微软雅黑\'; font-weight: bold; color: #3b3b3b;">30天，已经新增<font style="line-height:28px;font-family:\'Georgia\'; font-size: 24px; color: #ff0000; ">6428</font>个创业项目</span><br>
				<span style="font-family:\'微软雅黑\'; font-weight: bold; color: #3b3b3b;">30天，已经新增<font style="line-height:28px;font-family:\'Georgia\'; font-size: 24px; color: #ff0000; ">5755</font>篇创业资讯文章</span><br>
				</div>
				<a href="http://weibo.com/yijuhua88?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="margin-left: 120px; display:block; width:62px; height:22px; *margin-top:12px;"><img style="border:0 none;" src="'.URL::webstatic('').'/images/edm/derutixing/weibo.png"></a>
			</div>
			
		</td>
	</tr>
	<tr>
		<td colspan="2" style="font-size: 18px; font-family:\'微软雅黑\'; color:#dc1b14; font-weight: bold;padding-top: 20px;"><div style="margin: 0 auto; width:600px; border-bottom:3px solid #d91b14; ">创业分享</div></td>
	</tr>
	<tr>
		<td colspan="2">
			<div style="margin:0 auto; width: 600px; border-bottom:1px dashed #bbb;padding: 15px 0;">
				<a href="http://www.yjh.com/zixun/201312/9485.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">连锁加盟店失败原因大总结
</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">
					很多创业者在创业的时候都会选择加盟连锁店的方式，这种方式虽然比较轻松但被骗的多，同时也有大批的连锁加盟店纷纷倒闭，如何深刻的意识到这一问题？<a href="http://www.yjh.com/zixun/201312/9485.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="color: #0042ff;">阅读更多>></a>
				</span>
			</div>
		</td>
	</tr>
	<tr style="font-size: 12px; font-family: \'微软雅黑\'; color: #333; line-height: 25px;">
		<td colspan="2">
			<div style="width:600px; margin:10px auto 0;">
				<a href="http://www.yjh.com/zixun/201312/9313.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="width:280px; display: inline-block; color: #333; text-decoration: none;">• 2014年发展势头强劲的九大行业早知道！</a>
				<a href="http://www.yjh.com/zixun/201312/9489.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="padding-left: 40px; color: #333; text-decoration: none;">• 奥特莱斯广场—成功品牌直销案例
</a>
			</div>
		</td>
	</tr>
	<tr style="font-size: 12px; font-family: \'微软雅黑\'; color: #333; line-height: 25px;">
		<td colspan="2">
			<div style="width:600px; margin:0 auto;">
				<a href="http://www.yjh.com/zixun/201312/9379.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="width:280px; display: inline-block; color: #333; text-decoration: none;">▪ 家居行业长远发展如何巧借电商O2O模式？</a>
				<a href="http://www.yjh.com/zixun/201312/9169.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="padding-left: 40px; color: #333; text-decoration: none;">▪ 国内母婴行业“孕”出健康新市场</a>
			</div>
		</td>
	</tr>
	<tr style="font-size: 12px; font-family: \'微软雅黑\'; color: #333; line-height: 25px;">
		<td colspan="2">
			<div style="width:600px; margin:0 auto;">
				<a href="http://www.yjh.com/zixun/201311/8675.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="width:280px; display: inline-block;color: #333; text-decoration: none;">▪ 楼梯行业市场投资前景依旧广阔！</a>
				<a href="http://www.yjh.com/zixun/201311/8469.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="padding-left: 40px; color: #333; text-decoration: none;">▪ 想知道成功创业的窍门？先来掌握基本法则！</a>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="font-size: 18px; font-family:\'微软雅黑\'; color:#dc1b14; font-weight: bold;padding-top: 20px;"><div style="margin: 0 auto; width:600px; border-bottom:3px solid #d91b14; ">创业故事</div></td>
	</tr>
	<tr>
		<td colspan="2">
			<div style="margin:0 auto; width: 600px; border-bottom:1px dashed #bbb;padding: 15px 0;">
				<a href="http://www.yjh.com/zixun/201312/9355.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">90后小年轻，如何经营翻糖蛋糕大生意？</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">
					出身于1990年的谢濮泽，如果按照家人设计的职业路线走，也许他现在正卖着玉器。<a href="http://www.yjh.com/zixun/201312/9355.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="color: #0042ff;">阅读更多>></a>
				</span>
			</div>
		</td>
	</tr>
	<tr style="font-size: 12px; font-family: \'微软雅黑\'; color: #333; line-height: 25px;">
		<td colspan="2">
			<div style="width:600px; margin:10px auto 0;">
				<a href="http://www.yjh.com/zixun/201312/9349.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="width:280px; display: inline-block; color: #333; text-decoration: none;">• 看创业奇葩故事，聊单身青年的虚拟恋爱！
</a>
				<a href="http://www.yjh.com/zixun/201312/8911.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="padding-left: 40px; color: #333; text-decoration: none;">• 三个“80”后小伙儿的3w！
</a>
			</div>
		</td>
	</tr>
	<tr style="font-size: 12px; font-family: \'微软雅黑\'; color: #333; line-height: 25px;">
		<td colspan="2">
			<div style="width:600px; margin:0 auto;">
				<a href="http://www.yjh.com/zixun/201312/8849.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="width:280px; display: inline-block; color: #333; text-decoration: none;">▪ “煎饼果子”摆摊到北京国贸，这就是时尚！</a>
				<a href="http://www.yjh.com/zixun/201312/8797.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="padding-left: 40px; color: #333; text-decoration: none;">▪ 佳丽和美酒，如何演绎了传奇浪漫故事？
</a>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="font-size: 18px; font-family:\'微软雅黑\'; color:#dc1b14; font-weight: bold;padding-top: 20px;"><div style="margin: 0 auto; width:600px; border-bottom:3px solid #d91b14; ">创业资讯</div></td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top: 20px;" valign="top">
			<div style="width:600px; margin: 0 auto;">
				<span style="display:inline-block; width:280px; vertical-align: top;"><a href="http://www.yjh.com/zixun/201312/9475.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">深度剖析外资企业退出国内市场真相
</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">最近有消息称，外资企业退出国内市场，转战其他国家。对此很多投资者都表示惊讶和好奇，外资企业退出国内市场了吗？
</span><a href="http://www.yjh.com/zixun/201312/9475.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
				<span style="display:inline-block; width:280px;padding-left: 30px;"><a href="http://www.yjh.com/zixun/201312/9367.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">团购新势力—酒店团购产业发展势头迅猛</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">随着互联网的发展，团购成为当下一大热门的购物方式，广受着消费者的好评。
</span><a href="http://www.yjh.com/zixun/201312/9367.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top: 20px;" valign="top">
			<div style="width:600px; margin: 0 auto;">
				<span style="display:inline-block; width:280px;"><a href="http://www.yjh.com/zixun/201312/8941.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">传统百货困局频频,如何免受困兽之斗?</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">今年淘宝双十一活动已经正式收官，以350亿的成交额领跑网络零售界，在国内稳固了其网络电商龙头老大的地位。</span><a href="http://www.yjh.com/zixun/201312/8941.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
				
				<span style="vertical-align: top;display:inline-block;padding-left:30px; width:280px;"><a href="http://www.yjh.com/zixun/201312/8871.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">网络零售行业能否独占天下?</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">双十一的购物狂潮刚有所停息，双十二的热潮又将拉开帷幕。而淘宝作为其中最大的电商平台，正促使网上零售一步一步走向高峰。
</span><a href="http://www.yjh.com/zixun/201312/8871.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
			</div>
		</td>
	</tr>
		<tr>
		<td colspan="2" style="font-size: 18px; font-family:\'微软雅黑\'; color:#dc1b14; font-weight: bold;padding-top: 20px;"><div style="margin: 0 auto; width:600px; border-bottom:3px solid #d91b14; ">创业热贴</div></td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top: 20px;" valign="top">
			<div style="width:600px; margin: 0 auto;">
				<span style="display:inline-block; width:280px; vertical-align: top;"><a href="http://www.yjh.com/zixun/201312/9379.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">家居行业长远发展如何巧借电商O2O模式？</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">淘宝作为国内最大的购物网站，一直致力于为零售商、制造商、渠道商提供长远发展的电商平台。淘宝负责人表示愿与所有品牌和商家合作。</span><a href="http://www.yjh.com/zixun/201312/9379.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
				<span style="display:inline-block;padding-left: 30px; width:280px;"><a href="http://www.yjh.com/zixun/201312/9197.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">创意年会构思—网络创业新卖点</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">农历春节即将临近，又到公司一年一度的年会时期。年会作为组织一年一度不可缺少的“家庭盛会”
</span><a href="http://www.yjh.com/zixun/201312/9197.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top: 20px;" valign="top">
			<div style="width:600px; margin: 0 auto 30px;">
				<span style="display:inline-block; width:280px;"><a href="http://www.yjh.com/zixun/201311/8023.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">创业投资者要当心的几大投资陷阱！
</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">巴菲特五分钟之内决定投资某家公司的故事被人津津乐道，其实那是建立在巴菲特对这家公司业务盈利模式充分了解，还有长期关注观察这家公司的经营状况的基础上的。</span><a href="http://www.yjh.com/zixun/201311/8023.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
				<span style="display:inline-block;padding-left:30px; width:280px;vertical-align:top;"><a href="http://www.yjh.com/zixun/201311/8579.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">创业者与资本如何演绎着情爱纷争？
?</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">创业者与资本其实好似一对情侣，上演的仿佛像男女之间的情爱纷争。资本犹如美女，身边不乏追求者，一直待价而沽；
</span><a href="http://www.yjh.com/zixun/201311/8579.shtml?sid=5815fb42c69efbe83aaae1e8ead4153b_253
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
			</div>
		</td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" style="width:700px; margin:20px auto 0;">
	<tr>
		<td><a href="'.URL::website('').'?sid=9c0c170c23005b316e2c5c614c40c1a0_223
"><img style="border:0 none;" src="'.URL::webstatic('').'/images/edm/derutixing/images1.png"></a></td>
		<td style="padding-left: 14px;"><a href="http://www.yjh.com?sid=9c0c170c23005b316e2c5c614c40c1a0_223
"><img style="border:0 none;" src="'.URL::webstatic('').'/images/edm/derutixing/images2.png"></a></td>
		<td style="padding-left: 14px;"><a href="'.URL::webwen('').'?sid=9c0c170c23005b316e2c5c614c40c1a0_223
"><img style="border:0 none;" src="'.URL::webstatic('').'/images/edm/derutixing/images3.png"></a></td>
		<td style="padding-left: 14px;"><a href="http://www.yjh.com/zixun/?sid=9c0c170c23005b316e2c5c614c40c1a0_223
"><img style="border:0 none;" src="'.URL::webstatic('').'/images/edm/derutixing/images4.png"></a></td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" style="width:700px; margin:30px auto 0; font-size: 12px; color: #666; line-height: 20px;">
	<tr>
		<td>您收到电邮因为您是一句话商机速配网注册会员</td>
	</tr>
	<tr>
		<td><a href="http://www.yjh.com/help/yinsi.html?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" >隐私条款</a> 和 <a href="http://www.yjh.com/help/mianze.html?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" >用户协议</a></td>
	</tr>
	<tr>
		<td>上海通路快建网络服务外包有限公司</td>
	</tr>
	<tr>
		<td>上海市闵行区联航路1188号浦江智谷9号楼</td>
	</tr>
</table></body></html>
';
	}
}
?>