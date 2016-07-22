 <!--主体部分开始-->
<div class="right">
	<h2 class="user_right_title"><span>我的奖励</span><div class="clear"></div></h2>
	
	<?php if(isset($ishaving) && $ishaving){?>
	<div class="myrewards clearfix">
		<img class="floleft" src="<?php echo URL::webstatic('');?>images/huodong/img10.png">
		<p  class="floleft">
			<span>
				恭喜您获得<font>10</font>元话费奖励'
			</span>
			系统将在活动结束时自动对已投递名片的用户验证的手机进行充值，请勿在活动期间取消、更换验证手机号码，否则自动取消话费充值机会！本次最终解释权归一句话商机速配网所有。
		</p>
	</div>	
	<?php }?>
	<div class="rewardsfont">
		<p>活动时间内（<?php echo date('m.d',$setting['start_time']);?>—<?php echo date('m.d',$setting['end_time']);?>）新注册用户只要给带<var></var>标识的项目投递名片，将获得10元话费奖励！</p>
		<p class="mt10"><a href="<?php echo URL::website('/zt5/index.shtml');?>">查看详情>></a></p>
	</div>
</div>            
<!--主体部分结束-->