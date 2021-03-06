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
class Task_SendEmailQueueByWeek extends Minion_Task
{
	 /**
     * 定时对队列的用户发送邮件【7天未登录的个人用户】
     * @author 钟涛
     */
	protected function _execute(array $params){
		exit;//暂不使用
		#php D:\webserver\lnvestment-platform\trunk\modules\minion\minion --task=SendEmailQueueByWeek
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
            	//第一步发送邮件
//             	$smsg = Smsg::instance();
//             	//【no.3-发送邮件】
//             	$smsg->register(
//             			'email_person_edm_week',
//             			Smsg::EMAIL,//类型
//             			array(
//             					"to_email"=> $v->email,
//             					"subject"=>'一句话商机速配期刊第1期：辞职后，我偷偷的一年赚30万'
//             			),
//             			array(
//             			)
//             	);

                $sendresult=common::sendemail('一句话商机速配期刊第1期：辞职后，我偷偷的一年赚30万', 'service@yijuhua.net', $v->email, $content);
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
		$result=$model->where('user_type','=',2)->where('send_type','=',1)//1周未登录的
			->limit("100")->order_by('add_time', 'DESC')->find_all();
		return $result;
	}
	
	/**
	 * 获取发送内容
	 * @author 钟涛
	 */
	protected function getSendCount (){
		return ' <!DOCTYPE html>
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
			<a href="'.URL::website('').'/xiangdao/?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color:#0b73bb; font-family:\'微软雅黑\'; font-size: 12px;text-decoration: none;margin-left: 5px;">找项目</a>
			<a href="'.URL::website('').'/zixun/?sid=9c0c170c23005b316e2c5c614c40c1a0_223
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
				<span style="font-size: 14px; color: #000;">您已经超过7天没登录<a href="'.URL::website('').'?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color: #ff0000; font-weight: bold; text-decoration: none;">一句话商机速配平台</a></span><br>
				<span style=" display: inline-block; margin-top: 10px; font-family:\'微软雅黑\'; font-weight: bold; color: #3b3b3b;">7天，已经有<font style="line-height:28px;font-family:\'Georgia\'; font-size: 24px; color: #ff0000; ">45500</font>人登录我们网站；</span><br>
				<span style="font-family:\'微软雅黑\'; font-weight: bold; color: #3b3b3b;">7天，已经有<font style="line-height:28px;font-family:\'Georgia\'; font-size: 24px; color: #ff0000; ">2238</font>人选择创业项目；</span><br>
				<span style="font-family:\'微软雅黑\'; font-weight: bold; color: #3b3b3b;">7天，已经新增<font style="line-height:28px;font-family:\'Georgia\'; font-size: 24px; color: #ff0000; ">1629</font>个创业项目；</span><br>
				<span style="font-family:\'微软雅黑\'; font-weight: bold; color: #3b3b3b;">7天，已经新增<font style="line-height:28px;font-family:\'Georgia\'; font-size: 24px; color: #ff0000; ">1150</font>篇创业资讯文章；</span><br>
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
				<a href="'.URL::website('').'/zixun/201311/8101.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">开网店与客户沟通的关键在态度</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">
					古人云“和气生财”，这个做生意的黄金法则在什么时候都是适用的。对于开网店的人来说也不例外，开网店与客户沟通的关键在态度，态度好的网店，生意自然不会差。<a href="'.URL::website('').'/zixun/201311/8101.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color: #0042ff;">阅读更多>></a>
				</span>
			</div>
		</td>
	</tr>
	<tr style="font-size: 12px; font-family: \'微软雅黑\'; color: #333; line-height: 25px;">
		<td colspan="2">
			<div style="width:600px; margin:10px auto 0;">
				<a href="'.URL::website('').'/zixun/201311/8095.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="width:280px; display: inline-block; color: #333; text-decoration: none;">• 一句话网为你解析顾客购买行为的特点</a>
				<a href="'.URL::website('').'/zixun/201311/8029.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="padding-left: 40px; color: #333; text-decoration: none;">• 影响电商顾客满意度的五要素</a>
			</div>
		</td>
	</tr>
	<tr style="font-size: 12px; font-family: \'微软雅黑\'; color: #333; line-height: 25px;">
		<td colspan="2">
			<div style="width:600px; margin:0 auto;">
				<a href="'.URL::website('').'/zixun/201311/7727.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="width:280px; display: inline-block; color: #333; text-decoration: none;">▪ 做好精细化促销，让促销拥有实效</a>
				<a href="'.URL::website('').'/zixun/201311/7911.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="padding-left: 40px; color: #333; text-decoration: none;">▪ 提高服装店销售额，这些细节你关注了吗？</a>
			</div>
		</td>
	</tr>
	<tr style="font-size: 12px; font-family: \'微软雅黑\'; color: #333; line-height: 25px;">
		<td colspan="2">
			<div style="width:600px; margin:0 auto;">
				<a href="'.URL::website('').'/zixun/201309/5389.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="width:280px; display: inline-block;color: #333; text-decoration: none;">▪ 乌龙茶加盟店营业额如何提升</a>
				<a href="'.URL::website('').'/zixun/201311/8031.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="padding-left: 40px; color: #333; text-decoration: none;">▪ 多角度为顾客考虑帮助服装店提高销量</a>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="font-size: 18px; font-family:\'微软雅黑\'; color:#dc1b14; font-weight: bold;padding-top: 20px;"><div style="margin: 0 auto; width:600px; border-bottom:3px solid #d91b14; ">创业故事</div></td>
	</tr>
	<tr>
		<td colspan="2">
			<div style="margin:0 auto; width: 600px; border-bottom:1px dashed #bbb;padding: 15px 0;">
				<a href="'.URL::website('').'/zixun/201311/8111.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">辞职后，我偷偷的一年赚30万</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">
					真不敢相信，今年我竟然赚了30万！犹记得，一年前的我还在为生计奔波，还在为欠款担忧，现在债务还了，还拥有自己的存款，一家生活其乐融融。是创业给了我希望，是创业给了我光明的未来。<a href="'.URL::website('').'/zixun/201311/8111.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color: #0042ff;">阅读更多>></a>
				</span>
			</div>
		</td>
	</tr>
	<tr style="font-size: 12px; font-family: \'微软雅黑\'; color: #333; line-height: 25px;">
		<td colspan="2">
			<div style="width:600px; margin:10px auto 0;">
				<a href="'.URL::website('').'/zixun/201311/7985.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="width:280px; display: inline-block; color: #333; text-decoration: none;">• 牛根生：我们永远都在创业，永不停息</a>
				<a href="'.URL::website('').'/zixun/201311/7903.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="padding-left: 40px; color: #333; text-decoration: none;">• 四个励志小故事，提升创业价值
</a>
			</div>
		</td>
	</tr>
	<tr style="font-size: 12px; font-family: \'微软雅黑\'; color: #333; line-height: 25px;">
		<td colspan="2">
			<div style="width:600px; margin:0 auto;">
				<a href="'.URL::website('').'/zixun/201311/7749.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="width:280px; display: inline-block; color: #333; text-decoration: none;">▪ 创业者感悟：宽窄决定创业的成败</a>
				<a href="'.URL::website('').'/zixun/201311/7269.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="padding-left: 40px; color: #333; text-decoration: none;">▪ 马云：分享是创业者最大的快乐
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
				<span style="display:inline-block; width:280px; vertical-align: top;"><a href="'.URL::website('').'/zixun/201311/8089.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">鸡尾酒暴利真相大揭密！</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">随着国内洋酒市场的快速发展，国内消费者对于洋酒的接受程度也在日益提升，鸡尾酒因为绚丽的色彩、甜酸宜人的口感，在酒吧的点单率越来越高。</span><a href="'.URL::website('').'/zixun/201311/8089.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
				<span style="display:inline-block; width:280px;padding-left: 30px;"><a href="'.URL::website('').'/zixun/201311/7809.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">聚焦国内餐饮行业的发展趋势</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">餐饮行业算的业了，是经久不衰的行业。餐饮行业拥有强大的市场不是偶然。所谓人们生活第一要素就是饮食。</span><a href="'.URL::website('').'/zixun/201311/7809.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top: 20px;" valign="top">
			<div style="width:600px; margin: 0 auto;">
				<span style="display:inline-block; width:280px;"><a href="'.URL::website('').'/zixun/201311/7991.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">看未来纺织产业的新型科技趋势</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">纺织产业成为解决就业问题的主力军，纺织产业出口占全国总量的30%左右，其健康可持续发展对国家经济有着举足轻重的影响。再从具体的纤维耗用量来看，中国纺织产业占全球的57%，纺织品服装出口占全球的36%。
</span><a href="'.URL::website('').'/zixun/201311/7991.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
				
				<span style="vertical-align: top;display:inline-block;padding-left:30px; width:280px;"><a href="'.URL::website('').'/zixun/201310/6537.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">茶饮料利用科技力量走创新之路</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">目前人们的生活观念改变，普通消费者对于营养健康的需求与日俱增。随着年轻一代消费者的观念改变，消费市场对简单、便捷、安全、健康的产品越来越青睐。</span><a href="'.URL::website('').'/zixun/201310/6537.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
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
				<span style="display:inline-block; width:280px; vertical-align: top;"><a href="'.URL::website('').'/zixun/201311/7735.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">投资者们，风险不是承担而是管理</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">想创业，就必定会遇到创业风险，它与创业是相辅相成的，可是对于很多投资者来说，风险恰恰是他们最忌惮的，甚至有时碍于风险而放弃创业。
</span><a href="'.URL::website('').'/zixun/201311/7735.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
				<span style="display:inline-block;padding-left: 30px; width:280px;"><a href="'.URL::website('').'/zixun/201310/6769.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">加盟商成功招商广告的四大要素</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">好的产品缺乏完美的宣传，这个产品的价值将会因为知晓的人少，而降低身份。因此，如何做好招商广告，最大化得吸引目标顾客，是投资者成功的重要一步。</span><a href="'.URL::website('').'/zixun/201310/6769.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="padding-top: 20px;" valign="top">
			<div style="width:600px; margin: 0 auto 30px;">
				<span style="display:inline-block; width:280px;"><a href="'.URL::website('').'/zixun/201310/6895.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">加盟开店选址要选择好的开店地段</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">创业者想开加盟店创业，如何做好加盟店呢?开加盟店除了要选择一个好项目以外，最重要的就是加盟店选址问题了，如何做好加盟店?</span><a href="'.URL::website('').'/zixun/201310/6895.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="color: #0042ff; font-size: 12px;">阅读更多>></a>
				</span>
				<span style="display:inline-block;padding-left:30px; width:280px;"><a href="'.URL::website('').'/zixun/201311/7551.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" style="font-family: \'微软雅黑\'; font-size: 14px; color: #000; font-weight: bold; text-decoration: none;">如何激发消费者情感，做好促销活动?</a><br>
				<span style="font-size: 12px; color: #333; line-height: 25px;">消费者进行购物和参加促销活动时，往往对产品和品牌有一定的认知。好口碑好商品的促销活动，以往都可以吸引众多的消费者参与其中。</span><a href="'.URL::website('').'/zixun/201311/7551.shtml?sid=9c0c170c23005b316e2c5c614c40c1a0_223
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
		<td style="padding-left: 14px;"><a href="'.URL::website('').'?sid=9c0c170c23005b316e2c5c614c40c1a0_223
"><img style="border:0 none;" src="'.URL::webstatic('').'/images/edm/derutixing/images2.png"></a></td>
		<td style="padding-left: 14px;"><a href="'.URL::webwen('').'?sid=9c0c170c23005b316e2c5c614c40c1a0_223
"><img style="border:0 none;" src="'.URL::webstatic('').'/images/edm/derutixing/images3.png"></a></td>
		<td style="padding-left: 14px;"><a href="'.URL::website('').'/zixun/?sid=9c0c170c23005b316e2c5c614c40c1a0_223
"><img style="border:0 none;" src="'.URL::webstatic('').'/images/edm/derutixing/images4.png"></a></td>
	</tr>
</table>
<table cellspacing="0" cellpadding="0" style="width:700px; margin:30px auto 0; font-size: 12px; color: #666; line-height: 20px;">
	<tr>
		<td>您收到电邮因为您是一句话商机速配网注册会员</td>
	</tr>
	<tr>
		<td><a href="'.URL::website('').'/help/yinsi.html?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" >隐私条款</a> 和 <a href="'.URL::website('').'/help/mianze.html?sid=9c0c170c23005b316e2c5c614c40c1a0_223
" >用户协议</a></td>
	</tr>
	<tr>
		<td>上海通路快建网络服务外包有限公司</td>
	</tr>
	<tr>
		<td>上海市闵行区联航路1188号浦江智谷9号楼</td>
	</tr>
</table></body></html>';
	}
}
?>