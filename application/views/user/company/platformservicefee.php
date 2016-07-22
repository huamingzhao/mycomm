<div class="right">
	<div class="user_right_title">
		<span>申请招商通会员</span>
		<div class="clear"></div>
	</div>
	<div class="user_right_content">
		<div class="apply_zhaoshangtong">
			<?php if ($platform_service_fee_status){?>
			<p class="had_applied">恭喜您，已经成为招商通会员了！<a href="/company/member/account/accountindex">去充值</a>，享受充值服务吧！</p>
			<p>
			<?php }?>
				来成为《一句话》招商通会员吧！成为会员，即可免费发布项目详情，投资考察会信息，招商新闻动态等企业服务；充值立享“赠送金额”优惠政策，充值后可查看投资者详细信息，并向投资者递送企业名片，达成项目的合作洽谈。具体操作流程请<a href="<?php echo URL::website('/company/member/account/platformAccountAbout')?>">查看充值及服务</a>。
			</p>
			<h4>申请招商通会员指南：</h4>
			<p>
1. 首次申请招商通会员，需与我们签订《一句话平台服务协议》，此协议具有法律效力。<br />
2.首次申请招商通会员的企业，只需一次性支付1500元平台服务费，便可终身享用一句话平台服务账户管理、技术维护及线下维护服务等。<br />
3.成为招商通会员即日起，企业每次为账号充值可获得“赠送金额”的优惠政策。<br />
4.为彰显企业实力，获得更多投资者亲睐，首次申请招商通会员的企业建议充值金额最少不低于6500元，扣除终身会员1500元平台服务费后，余额5000元为会员账户预留金，供您下次消费使用。<br />
5.更多其他服务和会员享受特权，请查看充值及服务。
			</p>
			<?php if (!$platform_service_fee_status){?>
			<p class="btn"><a href="<?php echo URL::website('/company/member/account/accountindex')?>">立即申请招商通会员</a></p>
			<?php }?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
</div>