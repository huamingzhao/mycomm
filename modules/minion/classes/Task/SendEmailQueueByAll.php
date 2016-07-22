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
class Task_SendEmailQueueByAll extends Minion_Task
{
	 /**
     * 针对个人和企业7天+30天内未登录的用户 发送邮件
     * @author 钟涛
     */
	protected function _execute(array $params){
		#cd D:\softserver\php-5.3.18 #php D:\webserver\lnvestment-platform\trunk\modules\minion\minion --task=SendEmailQueueByAll
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
            	$name='用户';//用户名
            	$sendcontent='';
                //退订url
                $code = $v->user_id+1;
                $validcode=substr(md5($code),0,6);
                $tuiding_url=URL::website('regularscript/cancelSendEmailByCompany').'?code='.$validcode.'&key='.$v->user_id;
                
                if($v->user_type==2){//企业用户
                	$companyinfo=ORM::factory('Companyinfo')->where('com_user_id', '=', $v->user_id)->find();
                	if($companyinfo->com_name){
                		$name=$companyinfo->com_name;//用户名
                		//邮件标题
                		$title=$name.'，您好，最近没来一句话，您错过了很多投资者哦！';
                	}else{
                		//邮件标题
                		$title='您好，最近没来一句话，您错过了很多投资者哦！';
                	}
                }else{//个人用户
                	$personinfo=ORM::factory("Personinfo")->where('per_user_id','=',$v->user_id)->find();
                	if($personinfo->per_realname){
                		$name=$personinfo->per_realname;
                		$title=$personinfo->per_realname.'，欢迎回来，你不在，一句话商机速配顾问很想念！';
                	}else{
                		$title='您好，欢迎回来，你不在，一句话商机速配顾问很想念！';
                	}
                	if($v->send_type==1){//7天未登录
                		$denglu_conut=5855;//登录人数
                		$chuangye_conut=2802;//创业人数
                		$addproject_conut=1020;//新增的创业项目数
                		$zixun_conut=1500;//新增的资讯数量
                	}elseif($v->send_type==2){//30天未登录
                		$denglu_conut=5855*4;//登录人数
                		$chuangye_conut=2802*4;//创业人数
                		$addproject_conut=1020*4;//新增的创业项目数
                		$zixun_conut=1500*4;//新增的资讯数量
                	}else{//待续
                		 
                	}
                	$sendcontent='';//获取发送内容
                	
                }
                
                if($sendcontent){
                	usleep(1000);
                	$sendresult=common::sendemail($title, 'service@yijuhua.net', $v->email, $sendcontent);
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
		$result=$model->where('user_type','=',1)
				->limit("200")->order_by('add_time', 'DESC')->find_all();
		return $result;
	}
	
	/**
	 * 获取个人7天未登录邮件提醒内容
	 * @author 钟涛
	 */
	protected function _getPesronContent7 (){
		$content='	<table cellspacing="0" cellpadding="0" width="700" style="margin:0 auto; text-align: left;">
		<tbody width="700">
			<tr>
				<td colspan="2" width="100%">
					<a href="#" style="font-size:0;"><img style="border:none;" src="'.URL::webstatic("images/edm/remind7day_02.png").'" alt="生意街旗下，一句话商机速配平台"/></a>
					<img style="border:none;" src="'.URL::webstatic("images/edm/remind7day_03.png").'" alt="投资创业，找项目，学开店，只是一句话的事。"/>
				</td>
			</tr>
			<tr>
				<td colspan="2" width="100%" height="38" style=" background: url('.URL::webstatic("images/edm/qiyetuijian/remind7day_07.png").') bottom left repeat-x #0a72c1; border: 1px solid #d9d9d9; border-top: none; border-bottom: none;">
					<table cellspacing="0" cellpadding="0" width="100%;">
						<tbody>
							<tr>
								<td width="14%" align="center"><a href="#" style=" font-size: 12px; color: #ffffff; text-decoration: none; text-align: center;">去个人中心</a></td>
								<td width="14%" align="center"><a href="#" style=" font-size: 12px; color: #ffffff; text-decoration: none; text-align: center;">首页</a></td>
								<td width="14%" align="center"><a href="#" style=" font-size: 12px; color: #ffffff; text-decoration: none; text-align: center;">项目向导</a></td>
								<td width="14%" align="center"><a href="#" style=" font-size: 12px; color: #ffffff; text-decoration: none; text-align: center;">投资考察</a></td>
								<td width="14%" align="center"><a href="#" style=" font-size: 12px; color: #ffffff; text-decoration: none; text-align: center;">学做生意</a></td>
								<td width="14%" align="center"><a href="#" style=" font-size: 12px; color: #ffffff; text-decoration: none; text-align: center;">好友邀请</a></td>
								<td width="auto" align="center"><a href="#" style=" font-size: 12px; color: #ffffff; text-decoration: none; text-align: center;">帮助中心</a></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" width="100%" style="background: url('.URL::webstatic("images/edm/remind7day_14.png").') bottom right no-repeat #3198e7; border: 1px solid #d9d9d9; border-top: none; border-bottom: none;">
					<p style=" padding: 30px 36px 20px; margin: 0; font-size: 16px; color: #ffffff;">亲爱的张贤胜，您已经超过7天未登录一句话商机速配平台，错过了很多好项目哦！</p>
					<p style=" width: 383px; height: 210px; padding: 43px 0px 0px 45px; margin: 0; margin-left: 20px; line-height: 25px; font-size: 14px; color: #24a3db; background: url('.URL::webstatic("images/edm/remind7day_11.png").') no-repeat;">
7天，已经有<font style="display: inline-block; margin: 0 5px; font-size: 24px; color: #ff0000;">5000</font>人登录我们网站；<br/>
7天，已经有<font style="display: inline-block; margin: 0 5px; font-size: 24px; color: #ff0000;">2000</font>人选择创业项目；<br/>
7天，已经新增<font style="display: inline-block; margin: 0 5px; font-size: 24px; color: #ff0000;">1500</font>个创业项目；<br/>
7天，已经新增<font style="display: inline-block; margin: 0 5px; font-size: 24px; color: #ff0000;">1000</font>篇创业资讯文章；
					</p>
				</td>
			</tr>
			<tr>
				<td cellspacing="0" cellpadding="0" colspan="1" width="534" valign="top" style="padding-top: 10px; border: 1px solid #d9d9d9; border-top: none; border-right: none;">
					<table cellspacing="0" cellpadding="0" width="492" style="margin: 0 21px;">
						<tbody>
							<tr>
								<td colspan="2" width="100%" height="45" style=" padding-top: 5px; font-size: 18px; text-indent: 21px; color: #e4393c; background: url('.URL::webstatic("images/edm/remind7day_25.png").') bottom left no-repeat;">投资前沿</td>
							</tr>
							<tr>
								<td colspan="2" width="100%" style=" height: 35px; padding-top: 15px; line-height:35px; font-size: 18px;"><a href="#" style=" color:#0b73bb; text-decoration: none;">2013年中国消费趋势前瞻</a></td>
							</tr>
							<tr>
								<td colspan="1" width="350">
									<span style=" font-size:12px; color:#999999;">2013年04月07日 11:14更新    标签：消费趋势；前瞻报告</span>
									<p style=" padding: 15px 0px; margin: 0; line-height: 20px; font-size:12px; color:#666666;">近日，尚扬媒介与CIC联合发布了《2013年中国消费趋势前瞻》报告，意在以全新的视角揭示和解读2013年的消费趋势和变化近日，趋势前瞻》...</p>
								</td>
								<td colspan="1" width="142" valign="top" align="right">
									<a href="#"><img src="'.URL::webstatic("images/edm/remind7day_35.png").'" style="display: block; border: none;" alt="2013年中国消费趋势前瞻"/></a>
								</td>
							</tr>
							<tr>
								<td colspan="2" width="100%" style=" line-height: 24px; font-size:6px; color:#2f7fc0;">
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a>
								</td>
							</tr>
							<tr>
								<td colspan="2" width="100%" height="45" style=" padding-top: 5px; font-size: 18px; text-indent: 21px; color: #e4393c; background: url('.URL::webstatic("images/remind7day_25.png").') bottom left no-repeat;">投资前沿</td>
							</tr>
							<tr>
								<td colspan="2" width="100%" style=" height: 35px; padding-top: 15px; line-height:35px; font-size: 18px;"><a href="#" style=" color:#0b73bb; text-decoration: none;">2013年中国消费趋势前瞻</a></td>
							</tr>
							<tr>
								<td colspan="1" width="350">
									<span style=" font-size:12px; color:#999999;">2013年04月07日 11:14更新    标签：消费趋势；前瞻报告</span>
									<p style=" padding: 15px 0px; margin: 0; line-height: 20px; font-size:12px; color:#666666;">近日，尚扬媒介与CIC联合发布了《2013年中国消费趋势前瞻》报告，意在以全新的视角揭示和解读2013年的消费趋势和变化近日，趋势前瞻》...</p>
								</td>
								<td colspan="1" width="142" valign="top" align="right">
									<a href="#"><img src="'.URL::webstatic("images/edm/remind7day_35.png").'" style="display: block; border: none;" alt="2013年中国消费趋势前瞻"/></a>
								</td>
							</tr>
							<tr>
								<td colspan="2" width="100%" style=" line-height: 24px; font-size:6px; color:#2f7fc0;">
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a>
								</td>
							</tr>
							<tr>
								<td colspan="2" width="100%" height="45" style=" padding-top: 5px; font-size: 18px; text-indent: 21px; color: #e4393c; background: url('.URL::webstatic("images/remind7day_25.png").') bottom left no-repeat;">投资前沿</td>
							</tr>
							<tr>
								<td colspan="2" width="100%" style=" height: 35px; padding-top: 15px; line-height:35px; font-size: 18px;"><a href="#" style=" color:#0b73bb; text-decoration: none;">2013年中国消费趋势前瞻</a></td>
							</tr>
							<tr>
								<td colspan="1" width="350">
									<span style=" font-size:12px; color:#999999;">2013年04月07日 11:14更新    标签：消费趋势；前瞻报告</span>
									<p style=" padding: 15px 0px; margin: 0; line-height: 20px; font-size:12px; color:#666666;">近日，尚扬媒介与CIC联合发布了《2013年中国消费趋势前瞻》报告，意在以全新的视角揭示和解读2013年的消费趋势和变化近日，趋势前瞻》...</p>
								</td>
								<td colspan="1" width="142" valign="top" align="right">
									<a href="#"><img src="'.URL::webstatic("images/edm/remind7day_35.png").'" style="display: block; border: none;" alt="2013年中国消费趋势前瞻"/></a>
								</td>
							</tr>
							<tr>
								<td colspan="2" width="100%" style=" line-height: 24px; font-size:6px; color:#2f7fc0;">
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a>
								</td>
							</tr>
							<tr>
								<td colspan="2" width="100%" height="45" style=" padding-top: 5px; font-size: 18px; text-indent: 21px; color: #e4393c; background: url('.URL::webstatic("images/remind7day_25.png").') bottom left no-repeat;">投资前沿</td>
							</tr>
							<tr>
								<td colspan="2" width="100%" style=" height: 35px; padding-top: 15px; line-height:35px; font-size: 18px;"><a href="#" style=" color:#0b73bb; text-decoration: none;">2013年中国消费趋势前瞻</a></td>
							</tr>
							<tr>
								<td colspan="1" width="350">
									<span style=" font-size:12px; color:#999999;">2013年04月07日 11:14更新    标签：消费趋势；前瞻报告</span>
									<p style=" padding: 15px 0px; margin: 0; line-height: 20px; font-size:12px; color:#666666;">近日，尚扬媒介与CIC联合发布了《2013年中国消费趋势前瞻》报告，意在以全新的视角揭示和解读2013年的消费趋势和变化近日，趋势前瞻》...</p>
								</td>
								<td colspan="1" width="142" valign="top" align="right">
									<a href="#"><img src="'.URL::webstatic("images/edm/remind7day_35.png").'" style="display: block; border: none;" alt="2013年中国消费趋势前瞻"/></a>
								</td>
							</tr>
							<tr>
								<td colspan="2" width="100%" style=" line-height: 24px; font-size:6px; color:#2f7fc0;">
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a><br>
									<img src="'.URL::webstatic("images/edm/remind7DayPoint_07.png").'" style="vertical-align: middle;"><a href="" style=" margin-left: 10px; font-size:12px; color:#2f7fc0; text-decoration: none;">2014农村最新创业项目：利润高 赚钱快</a>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td colspan="1" width="165" valign="top" align="center" style="padding-top: 6px; border: 1px solid #d9d9d9; border-top: none; border-left: none;">
					<table cellspacing="0" cellpadding="0" style=" padding-top: 17px;  background: #f2f5f6;">
						<tbody>
							<tr>
								<td align="left">
									<a href="#" style="float: left; margin: 0 6px 0px 8px;"><img style="display: block; border: none;" src="'.URL::webstatic("images/edm/remind7day_19.png").'" alt="一句话logo"></a>
									<img style="margin-bottom:5px; border: none;" src="'.URL::webstatic("images/edm/remind7day_21.png").'" alt="一句话网">
									<a href="#"><img style=" border: none;" src="'.URL::webstatic("images/edm/remind7day_27.png").'" alt="加关注"></a>
								</td>
							</tr>
							<tr>
								<td align="center" style="padding-top: 18px;">
									<a href="#"><img style=" border: none;" src="'.URL::webstatic("images/edm/remind7day_33.png").'" alt="创业问答"/></a>
								</td>
							</tr>
							<tr>
								<td align="center" style="padding: 15px 0px 16px;">
									<a href="#"><img style=" border: none;" src="'.URL::webstatic("images/edm/remind7day_39.png").'" alt="诚信好项目，投资有保障。"/></a>
								</td>
							</tr>
							<tr>
								<td style="padding: 16px 0px; border-top: 1px solid #e1e1e1;" align="left">
									<h4 style=" margin: 0px 0px 10px; font-size: 14px; margin-left: 15px; font-weight: normal; color: #333333;">最佳口碑项目排行</h4>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">1. 渔珺传奇</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">2. 凯诗芬内衣连锁</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">3. 革新舍</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">4. 360网贷系统</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">5. 火星人</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">6. 易建宝</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">7. 甲米府泰国餐厅</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">8. 未来智能</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">9. 林老大饮料</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">10. 宝迪服饰</a>
								</td>
							</tr>
							<tr>
								<td style="padding: 16px 0px; border-top: 1px solid #e1e1e1;" align="left">
									<h4 style=" margin: 0px 0px 10px; font-size: 14px; margin-left: 15px; font-weight: normal; color: #333333;">最受关注项目排行</h4>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">1. 渔珺传奇</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">2. 凯诗芬内衣连锁</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">3. 革新舍</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">4. 360网贷系统</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">5. 火星人</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">6. 易建宝</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">7. 甲米府泰国餐厅</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">8. 未来智能</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">9. 林老大饮料</a>
									<a href="#" style=" display: block; height: 24px; line-height: 24px; font-size: 12px; margin-left: 19px; color: #0b73bb;">10. 宝迪服饰</a>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" width="100%">
					<table width="100%" cellspacing="0" cellpadding="0" style="padding-top: 20px;">
						<tbody>
							<tr>
								<td width="20%" align="left"><a href="#" style=""><img style=" border: none;" src="'.URL::webstatic("images/edm/remind7day_41.png").'" alt="项目向导"></a></td>
								<td width="20%"><a href="#" style="margin-left: 4px;"><img style=" border: none;" src="'.URL::webstatic("images/edm/remind7day_43.png").'" alt="投资考察"></a></td>
								<td width="20%"><a href="#" style="margin-left: 8px;"><img style=" border: none;" src="'.URL::webstatic("images/edm/remind7day_45.png").'" alt="找投资者"></a></td>
								<td width="20%"><a href="#" style="margin-left: 12px;"><img style=" border: none;" src="'.URL::webstatic("images/edm/remind7day_47.png").'" alt="学做生意"></a></td>
								<td width="20%" align="right"><a href="#" style=""><img style=" border: none;" src="'.URL::webstatic("images/edm/remind7day_49.png").'" alt="创业问答"></a></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" width="100%" style=" padding: 30px 0px; color:#666666;" align="center">
					您收到邮箱因为您是一句话商机速配平台注册会员如果您不希望收到此类邮件，请<a href="#" style="font-size: 12px; color:#002eff;">点击此处</a>退订
				</td>
			</tr>
		</tbody>
	</table>';
		return $content;
	}
}
?>