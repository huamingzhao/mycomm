<?php echo URL::webjs("qiye_center.js");?>
<?php echo URL::webjs("getcards.js");?>
<?php echo URL::webjs("module.js")?>
<?php echo URL::webjs("zhaos_list.js")?>

<?php echo URL::webcss("company_center.css")?>
<?php echo URL::webjs("message_close.js")?>
<?php echo URL::webjs("header.js")?>
<?php echo URL::webjs("cms/ajaxfileupload.js") ?>
<?php echo URL::webjs("platform/AC_RunActiveContent.js") ?>


<style>
.card_case_cont dl dd {
	display: block;
	float: right;
	height: 100px;
	line-height: 25px;
	overflow: hidden;
	width: 255px;
}
.card_case_cont dl dd span{ height:25px;overflow: hidden;display: block;}
</style>
<!--右侧开始-->
<div class="right">
	<div class="user_info">
		<div class="user_logo">
			<a href="javascript:void(0)"><img width="150" height="138" src="<?php if( $com_logo=="" ){ echo URL::webstatic('images/getcards/photo.png'); }else{ echo URL::imgurl($com_logo); }?>" id="comLogo" alt="头像"></a>
			<div id="upload_icon_btn" class="upload">修改头像</div>
			<div class="upload_icon_btn_shelter"></div>
		</div>
		<script>
			$(".user_info .user_logo").hover(function(){
				$(".user_logo .upload").animate({"bottom":"5px"});
			},function(){
				$(".user_logo .upload").animate({"bottom":"-25px"});
			});
		</script>
		<div class="user_base_info">
			<h2><span class="name"><?=$userName?></span>你好，欢迎进入企业管理中心！<font class="ryl_login_time">上次登录时间：<? if(isset($loginLog['log_time'])){echo date('Y-m-d H:i:s', Arr::get($loginLog, 'log_time', 0));}else{echo "首次登陆";}?></font></h2>
			<p class="line">
				<span class="yanzheng">
					<?php if($user->valid_mobile){?>
						<img class="icon_posi" src="<?php echo URL::webstatic("images/company_center/icon_ok_03.png")?>" />
						<a class="ok" href="<?php echo URL::website("../company/member/valid/mobile?to=change")?>">手机已验证</a>
					<?php }else{?>
						<img class="icon_posi" src="<?php echo URL::webstatic("images/company_center/icon_06.png")?>" />
						<a class="no" href="<?php echo URL::website("../company/member/valid/mobile")?>">手机未验证</a>
					<?php }?>
				</span>
				<?php if( $user->valid_email=='1' ){?>
					<span class="yanzheng">
						<img class="icon_posi" src="<?php echo URL::webstatic("images/company_center/icon_03.png")?>" />
						<a class="ok" href="<?php echo URL::website("/company/member/basic/editMail")?>">邮箱已验证</a>
					</span>
				<?php }else{?>
					<span class="yanzheng">
						<img class="icon_posi" src="<?php echo URL::webstatic("images/company_center/icon_04.png")?>" />
						<a class="no" href="<?php echo URL::website("/company/member/basic/setEmail")?>">邮箱未验证</a>
					</span>
				<?php }?>
				<span class="yanzheng">
					<?php if($validCerts==3){?>
						<img src="<?php echo URL::webstatic("images/company_center/icon_09.png")?>" class="icon_posi"><a class="no" href="<?php echo URL::website("/company/member/basic/comCertification")?>">资质未认证</a>
					<?php }elseif($validCerts==0){?>
						<img src="<?php echo URL::webstatic("images/company_center/icon_09.png")?>" class="icon_posi"><a class="no" href="<?php echo URL::website("/company/member/basic/comCertification")?>">资质验证中</a>
					<?php }elseif($validCerts==2){?>
						<img src="<?php echo URL::webstatic("images/company_center/icon_09.png")?>" class="icon_posi"><a class="no" href="<?php echo URL::website("/company/member/basic/comCertification")?>">资质验证失败</a>
					<?php }else{?>
						<img class="icon_posi" src="<?php echo URL::webstatic("images/company_center/icon_ok_06.png")?>" /><a class="ok" href="<?php echo URL::website("/company/member/basic/comCertification")?>">资质已认证</a>
					<?php }?>
				</span>
			</p>
			<ul>
				<li>
					<span class="dengji_left">我的诚信等级：<a href="<?php echo urlbuilder::help("qzhishuji")."#level";?>" target="_blank" style="padding-left:0;float:none;"><img src="<?php echo URL::webstatic("images/integrity/{$ity_level['level']}.png")?>" /></a></span>
				</li>
				<li>
					<span class="dengji_right">诚信指数：<b class="red"><a href="<?php echo URL::website("company/member/basic/integrity")?>" style="padding-left:0;float:none;color:red;font-size:12px;"><?php echo $ity_count?></a></b>点</span>
				</li>
				<li>
					<span>账户余额：<b class="red"><?php echo $account!=""?$account:'0.00';?></b>元</span><a href="<?php echo URL::website("company/member/account/accountindex")?>">充值</a>
				</li>
				<li>
					<span>基本信息：<?php if($is_complete_basic){?>已完善</span>
						<a class="icon02_modify" href="<?php echo URL::website("company/member/basic/editCompany").'?type=1';?>">修改</a>
					<?php }else{?>未完善</span>
						<a href="<?php echo URL::website("company/member/basic/editCompany").'?type=1' ;?>">完善</a>
					<?php }?>
				</li>
				<li>
					<span>招商项目：
					<?php if($project_count){?>已发布</span>
						<a href="<?php echo URL::website("company/member/project/showproject");?>" class="num"><?=$project_count?>个</a>项目
					<?php }else{?>
						未发布</span><a href="<?php echo URL::website("company/member/project/addproject");?>">发布</a>
					<?php }?>
				</li>
				<li>
					<span>企业名片：<?php if(!$is_complete_basic){ //企业基本信息未完成，名片未生成?>
						未生成</span><a href="<?php echo URL::website("company/member/basic/company");?>">去生成</a>
					<?php }elseif(!$is_card_complete){?>
						未完善</span><a href="<?php echo URL::website("company/member/card/completecard");?>">去完善</a>
					<?php }else{?>
						</span><img class="icon_posi" src="<?php echo URL::webstatic("images/qiye_center/icon4.png")?>" />已完善
				<?php }?>
				</li>
				<div class="clear"></div>
			</ul>
			<p class="company_info_invest_security">
				<label>保障服务：</label>
				<span class="<?php if( $bz_status_arr['base']=="1" ){?>company_info_bz_01<?php }else{?>company_info_bz_01_gray<?php }?>"><a href="/company/member/guard"></a><em>基础保障</em></span>
				<span class="<?php if( $bz_status_arr['quality']=="1" ){?>company_info_bz_02<?php }else{?>company_info_bz_02_gray<?php }?>"><a href="/company/member/guard/#2"></a>品质保障</em></span>
				<span class="<?php if( $bz_status_arr['safe']=="1" ){?>company_info_bz_03<?php }else{?>company_info_bz_03_gray<?php }?>"><a href="/company/member/guard/#3"></a><em>安全保障</em></span>
				<span class="<?php if( $bz_status_arr['server']=="1" ){?>company_info_bz_04<?php }else{?>company_info_bz_04_gray<?php }?>"><a href="/company/member/guard/#4"></a><em>服务保障</em></span>
			</p>
		</div>
		<div class="clear"></div>
	</div>
	<div class="com_index per_index">
		<!-- 名片管理 项目管理 开始 -->
		<h3 class="user_center_tap_title"><span>名片管理</span></h3>
		<!-- 名片管理 开始 -->			
		<div class="company_inforlist pro_card_div_cxy tabcom" style="margin-top:-1px;">
		<div id="user_center_card_div" class="list_1 tabcom" style="display: block;">
	            <p class="inf">
	            	<span style="padding-right:40px;">收到名片共<a style="color:#FF1D00" href="<?php echo URL::website("company/member/card/receivecard");?>"><?php echo $receive_card_count;?></a>张</span>距离上次登录收到名片<a style="color:#FF1D00" href="<?php echo URL::website("company/member/card/receivecard");?>"><?php echo $receive_card_lastlogin;?></a>张<a href="<?php echo URL::website("company/member/card/receivecard");?>" style="color:#005ea7; margin-left:30px;">查看更多>></a>
	            </p>
	            <p class="ryl_no_card" style="margin-bottom:5px;"><span>名片是与投资者联系的桥梁，是了解投资者的介质！</span>
	            <!-- 
	            <a class="ryl_btn02" href="<?php echo URL::website("company/member/investor/search");?>"><img src="<?php echo URL::webstatic('images/qiye_center/btn1.png');?>"></a>
	            <a href="<?php echo URL::website("company/member/investor/searchConditionsList");?>" class="ryl_btn03" style="font-weight:normal;">查看历史搜索记录</a></p>
	             -->
				<?php
	            	if($card_list){$i=0;
	            ?>
	            <?php foreach($card_list as $card){?>
	            <div class="pro_a">
		            <div class="lf">
		            	<a href="javascript:;"><img src="<?php echo URL::imgurl($card->user->user_person->per_photo);?>"></a>
		            </div>
		            <div class="rt">
			            <p><span style="color: #0b73bb; font-size:16px;"><?php $per_service = new Service_User_Person_User();$thisname=$per_service->getPerson($card->from_user_id);echo $thisname->per_realname;if($thisname->per_gender==2){ echo '  女士';}else{echo '  先生';}?></span></p>			             
			            <p><?php echo mb_substr($per_service->getPersonalIndustryString($card->from_user_id),0,4,'UTF-8');?>，<?php if(isset($money_list[$card->user->user_person->per_amount])) echo $money_list[$card->user->user_person->per_amount];?>，<?php echo mb_substr($per_service->getPerasonalAreaStringOnlyPro($card->from_user_id),0,4,'UTF-8');?> </p>
			            <p style="color:#ccc;"><?php echo getTimeSection(time()-$card->send_time);?>收到</p>
			            <a href="javascript:void(0)" id="getonecard_<?php echo  $card->from_user_id.'_'.$card->card_id;?>"  class="viewcard_person card_view viewcard">查看名片</a>
		            </div>
	            </div>
	            <?php }}?>	
	            <div class="clearfix"></div>            
       </div>
			<!-- 名片管理结束 -->
			<!-- 项目管理 开始 -->
			<h3 class="user_center_tap_title" style="margin-top:30px;"><span>项目管理</span></h3>
		<div id="user_center_project_div" style="display: block;">
				<p class="ryl_no_card"><span class="ryl_card_publish">已发布项目：<a href="<?php echo URL::website("company/member/project/showproject");?>" class="num"><b><?=$project_count?>个</b></a></span><span><b><?php if($project_list){?>发布招商项目，坐等投资者联系！<?php }else{?>您还未发布招商项目，快去发布招商项目吧，坐等投资者联系！<?php }?></b></span><a href="<?php echo URL::website("company/member/project/addproject");?>"><img src="<?php echo URL::webstatic("images/qiye_center/btn2.png")?>" /></a></p>
				<ul>
					<?php if($project_list){
						$i=0;
						foreach($project_list as $value){
						$i++;
						if($i>3){ ?>
						<li style=" padding-left:none; background:none; border:none;"><p class="ryl_card_view_more"><a href="<?php echo URL::website('/company/member/project/showproject')?>">更多项目>></a></p></li>
						<?php
							break;
						}?>
					<li>
					<span>
						<a href="<?php echo urlbuilder::project($value['project_id'])?>" target="_blank"  ><?=$value['project_brand_name'];?></a>
					</span>
					<a target="_blank"  href="<?php echo urlbuilder::project($value['project_id'])?>" class="view_web">浏览项目官网</a>
					<a href="/company/member/project/showProjectDetail?project_id=<?=$value['project_id'];?>" class="modify_mess">修改信息</a>
					<div  class="shenhe_kuang">
						<?php if($value['project_status']==1){?>
							<a href="#" class="icon_shenhe_now" onmouseout="$(this).next('.ryl_shenhebg').hide();return false;" onmouseover="$(this).next('.ryl_shenhebg').show();return false;">项目正在审核中...</a>
							<!--未通过审核 开始-->
							<div class="ryl_shenhebg" id="ryl_shenhebg_now">
								<div class="ryl_shenhebg_top"></div>
								<div class="ryl_shenhebg_center">
								您的项目还在审核中，审核通过后投资者可以查看您的项目官网。
									<div class="clear"></div>
								</div>
								<div class="ryl_shenhebg_bot"></div>
							</div>
							<!--未通过审核 结束-->
						<?}?>
						<?php if($value['project_status']==2){?><a href="#" class="icon_shenhe_has">项目已通过审核</a><?}?>
						<?php if($value['project_status']==0){?>
							<a href="#" class="icon_publish_fail">项目发布失败</a>
							<a href="/company/member/project/updateproject?project_id=<?=$value['project_id']?>" class="icon_go_perfect">继续完善我的项目</a>
							<a href="/company/member/project/submitProject?project_id=<?=$value['project_id']?>" class="icon_publish">发布我的项目</a>
						<?}?>
						<?php if($value['project_status']==3){?>
							<a href="#" class="icon_shenhe_no" onmouseout="$(this).next('.ryl_shenhebg').hide();return false;" onmouseover="$(this).next('.ryl_shenhebg').show();return false;">项目未通过审核</a>
							<!--未通过审核 开始-->
							<div class="ryl_shenhebg" id="ryl_shenhebg_no">
							<p class="ryl_shenhebg_top"></p>
							<p class="ryl_shenhebg_center">您的项目未通过审核，审核通过后投资者才可以查看您的项目官网。
								<div class="clear"></div>
							</p>
							<p class="ryl_shenhebg_bot"></p>
							</div>
							<!--未通过审核 结束-->
						<?}?>
					</div>
					</li>
					<?php }  } ?>
				</ul>
		
		</div>
		 </div>
		<!-- 名片管理 项目管理 结束-->

		<div class="company_inforlist" style="margin-bottom:10px;border:1px solid #eee; border-top: none;">
			<h4 style="margin-bottom:0;border-left:none;">
				<span class="fc" style="background:#fff; color:#D20000">账户信息</span>
				<div class="clear"></div>
			</h4>
			<div class="clear"></div>
			<?php
			if (count($accountlist)==0){?>
				<p class="ryl_login_time" style="margin-top: 5px;">您没有任何收入/支出信息，您可以去<a href="<?php echo URL::website('/company/member/account/accountindex')?>">充值>></a> 以便享受一句话提供的优质服务！</p>
				<?php }else{?>
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<th width="6" height="34">&nbsp;</th>
						<th width="154" align="left">充值/支付时间</th>
						<th width="189" align="left">内容明细</th>
						<th width="84">充值金额</td>
						<th width="96">支付金额</th>
						<th width="112">账户余额 </th>
						<th width="103" align="left">赠送金额</th>
					</tr>
					<?php foreach ($accountlist as $k=>$v){if($k<2){
					//获取赠送金额
					$costfreee=ORM::factory('Accountlog')->where('account_comments_type', '=', 14)->where('account_type_id', '=', $v->account_type_id)->where('account_user_id', '=', $v->account_user_id)->find()->account_change_amount;?>
					<tr>
						<td height="34" class="gray">&nbsp;</td>
						<td class="gray"><?php echo date('Y-m-d H:i',$v->account_log_time);?></td>
						<td class="gray"><?php echo mb_substr($v->account_note,0,12,'UTF-8');?></td>
						<td align="center" class="gray"><b class="green"><?php if($v->account_class==1){echo "+".$v->account_change_amount;}?>&nbsp;</b></td>
						<td align="center" class="gray"><b class="red"><?php if($v->account_class==2){echo "-".$v->account_change_amount;}?>&nbsp;</b></td>
						<td align="center" class="gray"><?php if($costfreee){echo number_format($v->account_amount+$costfreee, 2, '.', '');}else{echo  $v->account_amount;}?></td>
						<td class="gray"><?php if($costfreee){echo number_format($costfreee, 2, '.', '');}else{echo '--';}?></td>
					</tr>
					<?php }}?>
				</table>
				<p class="ryl_card_view_more"><a href="<?php echo URL::website('/company/member/account/accountlist')?>">更多交易明细>></a></p>
			<?php }?>
		</div>
		<?php /*?>
		<div class="recommend">
			<?php if(count($getRecommendPerson) && count($getGuessPerson)){//4个都有?>
				<div class="recommendtab clearfix">
					<a href="javascript:;" class="on recommend1">为你推荐<span></span></a>
					<a href="javascript:;" class="recommend2">猜你喜欢<span></span></a>
					<a href="javascript:;" class="recommend3">最活跃会员<span></span></a>
					<a href="javascript:;" class="recommend4">新加入会员<span></span></a>
				</div>
			<?php }elseif(count($getRecommendPerson) || count($getGuessPerson)){//有3个?>
				<div class="recommendtab recommendtab2 clearfix">
					<a href="javascript:;" class="on <?php if(count($getRecommendPerson)){echo 'recommend1';}else{echo 'recommend2';}?>"><?php if(count($getRecommendPerson)){echo '为你推荐';}else{echo '猜你喜欢';}?><span></span></a>
					<a href="javascript:;" class="recommend3">最活跃会员<span></span></a>
					<a href="javascript:;" class="recommend4">新加入会员<span></span></a>
				</div>
			<?php }else{//仅有两个?>
				<div class="recommendtab recommendtab1 clearfix">
					<a href="javascript:;" class="on recommend3">最活跃会员<span></span></a>
					<a href="javascript:;" class="recommend4">新加入会员<span></span></a>
				</div>
			<?php }?>
			<div class="recommendtabbox">
				<!--为你推荐-->
				<div class="recommendconbox <?php if(count($getRecommendPerson)){echo 'on';}?> recommendconbox1">
				<a href="javascript:;" class="recommendleft"></a>
				<div class="recommendcon">
				<div class="ulbox">
				<ul class="browse_list on clearfix">
				<?php if(count($getRecommendPerson)){?>
				<?php foreach ($getRecommendPerson as $v){?>
				<li class="first">
				<span class="pb10">
				<!-- 推荐头像处理开始 -->
				<?php if($v['per_photo']){//有头像?>
				<?php echo '<p class="img"><span><a target="_blank" title="'.$v['per_realname'].'" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$v['user_id'].'"><img alt="投资者'.mb_substr($v['per_realname'],0,5,'UTF-8').'" src="'.URL::imgurl($v['per_photo']).'"></a></span></p>';?>
				<?php }elseif($v['per_gender']==2){//性别女 默认头像?>
				<a class="viewperinfo invester_woman" target="_blank" type="2" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['user_id'];?>"></a>
				<?php }else{//性别男 默认头像?>
				<a class="viewperinfo invester_man" target="_blank" type="2" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['user_id'];?>"></a>
				<?php }?>
				</span>
				<div class="invester_r_name">
				<!-- 推荐名字处理开始 -->
				<a target="_blank" class="viewperinfo" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['user_id'];?>" type="2"><?php echo mb_substr($v['per_realname'],0,3,'UTF-8');?></a>
				<em><?php echo mb_substr($v['this_per_area'],0,2,'UTF-8');?></em>
				<div class="clear"></div>
				</div>
				<p class="invester_r_active"><?php echo $v['this_huoyuedu'];?></p>
				<p class="invester_r_choose"><a style="cursor: none" ><?php echo $v['this_per_industry'];?></a>&nbsp;，<a style="cursor: none" ><?php echo $v['per_amount'];?></a></p>
				</li>
				<?php }}?>
				</ul>
				</div>
				</div>
				<a href="javascript:;" class="recommendright"></a>
				</div>
				<!--猜你喜欢-->
				<div class="recommendconbox <?php if(count($getRecommendPerson)==0 && count($getGuessPerson)){echo 'on';}?> recommendconbox2">
					<a href="javascript:;" class="recommendleft"></a>
					<div class="recommendcon">
						<div class="ulbox">
							<ul class="browse_list on clearfix">
								<?php if(count($getGuessPerson)){?>
									<?php foreach ($getGuessPerson as $v){?>
										<li class="first">
											<span class="pb10">
											<!-- 推荐头像处理开始 -->
												<?php if($v['per_photo']){//有头像?>
													<?php echo '<p class="img"><span><a target="_blank" title="'.$v['per_realname'].'" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$v['user_id'].'"><img alt="投资者'.mb_substr($v['per_realname'],0,5,'UTF-8').'" src="'.URL::imgurl($v['per_photo']).'"></a></span></p>';?>
												<?php }elseif($v['per_gender']==2){//性别女 默认头像?>
													<a class="viewperinfo invester_woman" target="_blank" type="2" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['user_id'];?>"></a>
												<?php }else{//性别男 默认头像?>
													<a class="viewperinfo invester_man" target="_blank" type="2" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['user_id'];?>"></a>
												<?php }?>
											</span>
											<div class="invester_r_name">
												<!-- 推荐名字处理开始 -->
												<a target="_blank" class="viewperinfo" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['user_id'];?>" type="2"><?php echo mb_substr($v['per_realname'],0,3,'UTF-8');?></a>
												<em><?php echo mb_substr($v['this_per_area'],0,2,'UTF-8');?></em>
												<div class="clear"></div>
											</div>
											<p class="invester_r_active"><?php echo $v['this_huoyuedu'];?></p>
											<p class="invester_r_choose"><a style="cursor: none" ><?php echo $v['this_per_industry'];?></a>&nbsp;，<a style="cursor: none" ><?php echo $v['per_amount'];?></a></p>
										</li>
								<?php }}?>
							</ul>
						</div>
					</div>
					<a href="javascript:;" class="recommendright"></a>
				</div>
				<!--最活跃会员 -->
				<div class="recommendconbox <?php if(count($getRecommendPerson)==0 && count($getGuessPerson)==0){echo 'on';}?> recommendconbox3">
					<a href="javascript:;" class="recommendleft"></a>
					<div class="recommendcon">
						<div class="ulbox">
							<ul class="browse_list on clearfix">
								<?php foreach ($getHuoyueduPerson as $v){?>
									<li class="first">
										<span class="pb10">
										<?php if($v['per_photo']){//有头像?>
											<?php echo '<p class="img"><span><a target="_blank" title="'.$v['per_realname'].'" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$v['user_id'].'"><img alt="投资者'.mb_substr($v['per_realname'],0,5,'UTF-8').'" src="'.URL::imgurl($v['per_photo']).'"></a></span></p>';?>
										<?php }elseif($v['per_gender']==2){//性别女 默认头像?>
											<a class="viewperinfo invester_woman" target="_blank" type="2" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['user_id'];?>"></a>
										<?php }else{//性别男 默认头像?>
											<a class="viewperinfo invester_man" target="_blank" type="2" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['user_id'];?>"></a>
										<?php }?>
										</span>
										<div class="invester_r_name">
											<!-- 推荐名字处理开始 -->
											<a target="_blank" class="viewperinfo" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['user_id'];?>" type="2"><?php echo mb_substr($v['per_realname'],0,3,'UTF-8');?></a>
											<em><?php echo mb_substr($v['this_per_area'],0,2,'UTF-8');?></em>
											<div class="clear"></div>
										</div>
										<p class="invester_r_active"><?php echo $v['this_huoyuedu'];?></p>
										<p class="invester_r_choose"><a style="cursor: none" ><?php echo $v['this_per_industry'];?></a>&nbsp;，<a style="cursor: none" ><?php echo $v['per_amount'];?></a></p>
									</li>
								<?php }?>
							</ul>
						</div>
					</div>
					<a href="javascript:;" class="recommendright"></a>
				</div>
				<!-- 新加入会员 -->
				<div class="recommendconbox recommendconbox4">
					<a href="javascript:;" class="recommendleft"></a>
					<div class="recommendcon">
						<div class="ulbox">
							<ul class="browse_list on clearfix">
								<?php foreach ($getNewPerson as $v){?>
									<li class="first">
										<span class="pb10">
											<!-- 推荐头像处理开始 -->
											<?php if($v['per_photo']){//有头像?>
												<?php echo '<p class="img"><span><a target="_blank" title="'.$v['per_realname'].'" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$v['user_id'].'"><img alt="投资者'.mb_substr($v['per_realname'],0,5,'UTF-8').'" src="'.URL::imgurl($v['per_photo']).'"></a></span></p>';?>
											<?php }elseif($v['per_gender']==2){//性别女 默认头像?>
												<a class="viewperinfo invester_woman" target="_blank" type="2" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['user_id'];?>"></a>
											<?php }else{//性别男 默认头像?>
												<a class="viewperinfo invester_man" target="_blank" type="2" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['user_id'];?>"></a>
											<?php }?>
										</span>
										<div class="invester_r_name">
											<!-- 推荐名字处理开始 -->
											<a target="_blank" class="viewperinfo" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['user_id'];?>" type="2"><?php echo mb_substr($v['per_realname'],0,3,'UTF-8');?></a>
											<em><?php echo mb_substr($v['this_per_area'],0,2,'UTF-8');?></em>
											<div class="clear"></div>
										</div>
										<p class="invester_r_active"><?php echo $v['this_huoyuedu'];?></p>
										<p class="invester_r_choose"><a style="cursor: none" ><?php echo $v['this_per_industry'];?></a>&nbsp;，<a style="cursor: none" ><?php echo $v['per_amount'];?></a></p>
									</li>
								<?php }?>
							</ul>
						</div>
					</div>
					<a href="javascript:;" class="recommendright"></a>
				</div>
			</div>
		</div>
		<?php */?>
	</div>
</div>
<?php 
	/**
	 * 获取用户最后登录时间 返回时间区间
	 * $time_section 时间差值
	 */
	function getTimeSection($time_section){
		switch ($time_section){
			case $time_section < 60:
				return $time_section.' 秒前';
				break;
			case $time_section < 3600:
				return floor($time_section/60).'分钟前';
				break;
			case $time_section < 86400:
				return floor($time_section/3600).'小时前';
				break;
			case $time_section < 604800:
				return floor($time_section/86400).'天前';
				break;
			case $time_section <2592000:
				return floor($time_section/604800).'周前';
				break;
			case $time_section <31536000:
				return floor($time_section/2592000).'个月前';
				break;
			case $time_section <3*31536000:
				return floor($time_section/31536000).'年前';
				break;
			default:
				return date("Y-m-j H:i",$time_section);
		}
	}
?>
<!--mycardtype记录当前页面为我收到的名片-->
<a id="mycardtype" style="display:none">1</a>
<!--右侧结束-->

<!--透明背景开始-->
<div id="getcards_opacity"></div>
<div id="getcards_opacity2"></div>
<!--透明背景结束-->
<!--查看名片开始-->
<div id="getcards_view" class="getcards_view2">
    <a href="#" class="close">关闭</a>
    <div id="getcards_view_content">
    </div>
</div>
<!--查看名片结束-->
<!--从业经验弹出框开始-->
<div class="my_cyjy_view_box" style="z-index:99999">
    <a href="#" class="close">关闭</a>
    <h3>从业经验</h3>
    <div id="cyjy_content">
    </div>
</div>
<!--从业经验弹出框结束-->
<!--交换名片开始-->
<div id="getcards_change">
    <a href="#" class="close">关闭</a>
    <div class="getcards_change_suc">
    </div>
</div>
<!--交换名片结束-->
<!--删除项目开始-->
<div id="getcards_delete">
    <a href="#" class="close">关闭</a>
    <div class="btn">
        <p>确定要删除此项目吗?一旦删除,将不可找回。</p>
        <!--<p>一旦删除，将无法取回。您确定要删除此名片吗？</p>-->
        <!--<p>您还没选择需要删除的名片，请先选择后操作。</p>-->
        <p><a href="#" class="ensure" id="deleteProject"><img src="<?php echo URL::webstatic("images/getcards/ensure1.jpg")?>" /></a>　<a href="#" class="cancel"><img src="<?php echo URL::webstatic("images/getcards/cancel1.jpg")?>" /></a></p>
    </div>
</div>
<!--删除项目结束-->
<!--名片服务消息提示框开始-->
<div id="getcards_deletebox">
    <a href="#" class="close">关闭</a>
        <div class="text" style="background:none;padding-left:0px;text-align: left;color: #000;">
        <p></p>
        <p id="this_content2" style="width:270px; margin:0 auto;"><a href="#" class="ensure"><img src="<?php echo URL::webstatic("/images/getcards/ensure1.jpg") ?>" /></a>
           <a href="#" class="cancel"><img src="<?php echo URL::webstatic("/images/getcards/cancel1.jpg") ?>" /></a>
        </p>
        <input id="getcards_deletebox_hid" type="hidden" value="0"></input>
    </div>
</div>
<!--名片服务消息提示框结束-->
<!--更换头像弹框开始-->
<?php echo URL::webjs("upload_icon.js")?>
<script type="text/javascript">
var icon_images = {
<?
    $icon_type = array("plant"=>array("植物", 25), "animal"=>array("动物", 21), "coin"=>array("钱币", 4));
    foreach($icon_type as $key => $value){
        echo '"'.$key.'":["'.$value[0].'",';
        for($i=1; $i<$value[1]; $i++){
            echo '"'.URL::webstatic("/images/common/user_icon/".$key."/default_icon_".$i.".jpg").'",';
        }
        echo '"'.URL::webstatic("/images/common/user_icon/".$key."/default_icon_".$i.".jpg").'"';
        echo '],';
    }
?>
    "end":null
};
new upload_icon($("#upload_icon_btn"), icon_images, "change_icon_04.png", "/company/member/ImageAjaxcheck/uploadComLogo");

// upload_icon(obj ,images, result, callback, text)
// 修改头像弹框
// obj为触发弹框的元素
// images为弹框中默认头像的图片路径
// result为接收选择结果（图片路径）的input表单
// callback为回调函数
// text为自定义头像显示的文字
</script>
<!--更换头像弹框结束-->
<script type="text/javascript">
                            $(".user_center_tap_title span").click(function(){
                                var $index=$(this).index();
                                $(this).addClass('on').siblings().removeClass('on');
                                if($index==0){
                                    $("#user_center_card_div").show();
                                    $("#user_center_project_div").hide();
                                }
                                if($index==1){
                                    $("#user_center_card_div").hide();
                                    $("#user_center_project_div").show();
                                }
                            })
                        </script>