<?php echo URL::webcss("personcenter.css")?>
<?php echo URL::webjs("qiye_center.js")?>
<?php echo URL::webjs("person_getcard.js") ?>
<?php echo URL::webcss("platform/nambo_box.css")?>
<?php echo URL::webjs("header.js") ?>
<?php echo URL::webjs("message_close.js") ?>
<?php echo URL::webjs("cms/ajaxfileupload.js") ?>
<?php echo URL::webjs("platform/AC_RunActiveContent.js") ?>
<style>
.tp p span{ float:left;}
.tp p span .icon_posi {display: inline;margin-bottom: -3px;padding-right: 5px;}
.per_index .list_1 .pro_a .rt {display: block;float: left;height: 100px;overflow: hidden; padding-left: 12px;width: 255px;}
.per_index .list_1 .pro_a .rt p { display: block;height: 25px;line-height: 25px;overflow: hidden; word-break: break-all;}
.per_index .list_2 .pro_b p {height: 22px;line-height: 22px;overflow: hidden;word-break: break-all;}
.per_index .list_2 .pro_b .rt {float: left;height: 88px;overflow: hidden;padding-left: 12px; width: 240px;}
</style>

<?php if( $oauinfo['bind_user_id']==null || $oauinfo['bind_user_id']==''   ){?>
<div class="loginpoper">
	<div class="clearfix login_top">
	<?php if( Session::instance()->get('oauth')==2 ){?>
	<img src="<?php echo URL::webstatic("images/login_optimization/weibo.jpg")?>" class="floleft">
	<?php }else{?>
	<img src="<?php echo URL::webstatic("images/login_optimization/zhifubao.jpg")?>" class="floleft">
	<?php }?>
	<div class="floleft logins_info">
	<p class="p1"><span><?php echo $userName?></span><?php if( $userName!=='' ){?>，<?php }?>欢迎您！</p>
	<p class="p2">立即绑定一个账号，以后就可以直接登录一句话商机速配网站喽！</p>
	</div>
	<a href="<?php echo URL::website('/person/member/basic/oauthUserBinding')?>" class="login_btna mt15 ml15">立即绑定</a>
	</div>
	<p class="login_tishi">您还没进行邮箱和手机的验证哟，现在去验证吧！</p>
	<p class="mt15"><a href="/person/member/basic/setEmail?type=oauth" class="login_btna ptb4">邮箱验证</a>有助于您忘记密码时，找回密码！增加您的个人信誉！还有助于您与招商者的联系哟！</p>
	<p class="mt15"><a href="/person/member/valid/mobile?type=oauth"class="login_btna ptb4 yel">手机验证</a>有助于招商者主动联系您，加快您投资项目的速度哟！心动不如行动！</p>
	<div class="linepoperbg"></div>
	<a href="javascript:;" class="login_btn"></a>
</div>
<script type="text/javascript">
	$(".login_btn").click(function(){
	$(this).parent().slideUp();
	$("#getcards_opacity").hide();
	})
</script>
<?php }?>

<!--右侧开始-->
<div class="right"  style="height:auto !important;height:620px;min-height:620px; z-index:inherit;">
	<div class="user_info">
		<div class="user_logo">
			<a href="javascript:void(0)"><img width="150" height="138" src="<?php if( $user_photo=="" ){ echo URL::webstatic('images/getcards/photo.png'); }else{ echo URL::imgurl($user_photo); }?>" alt="头像" id="comLogo" /></a>
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
			<h2>
				<span class="name"><?=$userName?><?php if($is_complete_basic){if($sex==1){echo "先生";}elseif($sex==2){echo "女士";}}else{echo "先生/女士";}?></span>，欢迎您！				
                <!-- 给标志项目发送名片 结果标志开始 -->
                <!-- 金jinspan  银yinspan 铜tongspan -->
                <?php
                	if( time()>$setting['end_time'] && isset($specific_users) && is_array($specific_users)){
               			if(array_key_exists($user->user_id, $specific_users)){
                			echo "<i class='jypspan ".$setting['medal_class'][$specific_users[$user->user_id]]."'></i>";
                		}
                	}
                ?>
                <!-- 给标志项目发送名片 结果标志开始 结束-->
                
				<font class="ryl_login_time">上次登录时间：<?if(isset($loginLog['log_time'])){echo date('Y-m-d H:i:s', Arr::get($loginLog, 'log_time', 0));}else{echo "首次登陆";}?></font>
				
			</h2>
			<p class="line">
				<span class="yanzheng">
					<?php if($user->valid_mobile){?>
					<img class="icon_posi" src="<?php echo URL::webstatic("images/company_center/icon_ok_03.png")?>" /><a class="ok" title="修改手机号" href="<?php echo URL::website("/person/member/valid/mobile?to=change");?>">手机已验证</a>
					<?php }else{?>
					<img class="icon_posi" src="<?php echo URL::webstatic("images/company_center/icon_06.png")?>" /><a class="no" href="<?php echo URL::website("/person/member/valid/mobile");?>">手机未验证</a>
					<?php }?>
				</span>
				<?php if( $user->valid_email=='1' ){?>
				<!-- 邮箱已验证 -->
				<span class="yanzheng">
					<img class="icon_posi" src="<?php echo URL::webstatic("images/company_center/icon_03.png")?>" />
					<a class="ok" href="<?php echo URL::website("/person/member/basic/editMail")?>">邮箱已验证</a>
				</span>
				<?php }else{?>
				<!-- 邮箱未验证 -->
				<span class="yanzheng">
					<img class="icon_posi" src="<?php echo URL::webstatic("images/company_center/icon_04.png")?>" />
					<a class="no" href="<?php echo URL::website("/person/member/basic/setEmail")?>">邮箱未验证</a>
				</span>
				<?php }?>
				<span class="yanzheng">
					<?php if($identificationCard_status==0){?>
					<img src="<?php echo URL::webstatic("images/company_center/icon_09.png")?>" class="icon_posi">
					<a href="<?php echo URL::website("/person/member/basic/identificationcard");?>">实名未认证</a>
					<?php }elseif($identificationCard_status==1){?>
					<img src="<?php echo URL::webstatic("images/company_center/icon_09.png")?>" class="icon_posi"><a class="no" href="<?php echo URL::website("/person/member/basic/identificationcard");?>">实名认证中</a>
					<?php }elseif($identificationCard_status==2){?>
					<img class="icon_posi" src="<?php echo URL::webstatic("images/company_center/icon_ok_06.png")?>" /><a href="<?php echo URL::website("/person/member/basic/identificationcard");?>">实名已认证</a>
					<?php }elseif($identificationCard_status==3){?>
					<img src="<?php echo URL::webstatic("images/company_center/icon_09.png")?>" class="icon_posi"><a class="no" title="重新认证" href="<?php echo URL::website("/person/member/basic/identificationcard?status=-1");?>">未通过实名认证</a>
					<?php }?>
				</span>
			</p>
			<ul>
				<li>
					<span class="dengji_left">我的总活跃度等级：<a target="_blank" href="<?php echo URL::website('help/huoyuedu.html');?>"><img src="<?php echo URL::webstatic("images/integrity/{$level['level']}.png")?>" /></a></span>
				</li>
				<li>
					<span class="dengji_right">总活跃度指数：
					<a href="<?php echo URL::website('person/member/basic/itylist?type=1');?>" style="color: #FF1D00;font-size: 12px;"><?php echo  $level['total_score'];?></a>&nbsp;点</span>
				</li>
				<li>
					<span class="dengji_right">总创业币数：
					<a href="<?php echo URL::website("person/member/basic/itylist?type=2");?>" style="color: #FF1D00;font-size: 12px;"><?php echo $chuangyebiCount;?></a>&nbsp;元</span>
				</li>
				<li>
					<span>
						基本信息：
						<?php if(!$is_complete_basic){?>未完善
					</span>
					<a href="<?php echo URL::website("/person/member/basic/personupdate") ;?>">完善</a>
					<?php }else{?>已完善</span>
					<a href="<?php echo URL::website("/person/member/basic/personupdate");?>">修改</a>
					<?php }?>
				</li>
				<li>
					<span>名片公开度：
					<?php if($per_open_stutas == 1):?>允许所有企业查看</span>
					<?php elseif($per_open_stutas == 2):?>允许意向行业查看</span>
					<?php elseif($per_open_stutas == 3):?>不允许所有企业查看</span>
					<?php elseif($per_open_stutas == 4):?>允许VIP企业查看我的名片</span>
					<?php else:?>不允许所有企业查看</span><?php endif;?>
					<a href="<?php echo URL::website('/person/member/card/mycard')?>">修改</a>
				</li>
				<li>
					<span>个人从业经验：
					<?php if($experiences==0){?>未添加
					</span>
					<a href="<?php echo URL::website("/person/member/basic/experience");?>">添加</a>
					<?php }else{?>已添加</span>
					<?php }?>
				</li>
				<div class="clear"></div>
			</ul>
		</div>
		<div class="clear"></div>
	</div>
	<!-- 名片管理 收藏项目 开始 -->
	<div class="per_index">
	<!-- 我的咨询 -->
	<h3 class="h3tab">
		<span class="on">名片管理</span><span class="last">我咨询的</span>
		<div class="clear"></div>
	</h3>                        
	<div class="list_lbox">
		<div class="list_1" style="display:block;">
			<!-- 收到名片显示开始 -->			
			<div class="pro_card_div_cxy" <?php if(empty($card_list)){echo 'style="margin-top:-4px; border-top:none; padding-top: 0;"';}?>>
			<p class="inf">
				<span style="padding-right:40px;">收到名片共<a style="color:#FF1D00" href="<?php echo URL::website("/person/member/card/receivecard");?>"><?php echo $receive_card_count;?></a>张</span>距离上次登录收到名片<a style="color:#FF1D00" href="<?php echo URL::website("/person/member/card/receivecard");?>"><?php echo $receive_card_lastlogin;?></a>张<a href="<?php echo URL::website("/person/member/card/receivecard");?>" target="_self" style="color:#005ea7; margin-left:30px;">查看更多>></a>
			</p>
				<?php
					if(!empty($card_list)){
					$card_list_slice = count($card_list) > 2 ? array_slice($card_list, 0, 2,true):$card_list;
					foreach($card_list_slice as $v){
				?>	
			 	<div class="pro_a">
	                <div class="lf">
	                	<a href="javascript:;"><img src="<?php  if(!empty($v['com_logo'])) { echo URL::imgurl($v['com_logo']);} else{echo URL::webstatic('/images/getcards/photo.png') ;}?>"></a> 
		               
	                </div>
	                <div class="rt">
	               		<p style="color: #0b73bb; font-size:16px;"><?php echo $v['com_name']; ?></p>
		                <!-- 旗下项目 需要做字符截取  -->
		                <p>旗下项目：<?php if($v['project_brand_name']!=''){echo $v['project_brand_name'];}else{ echo '暂无';}?></p>		               
		                <p style=" color:#ccc;"><?php echo getTimeSection(time()-$v['send_time']);?>收到</p>
	               		<a href="#" class="viewcard_person" id="getonecard_<?php echo  $v['com_user_id'].'_'.$v['card_id'];?>">查看名片</a>
	                </div>
                </div>
				<?php }}else{?>	
					<p style="padding-top:35px;">您还没有收到招商者递送的名片，您可以主动搜索项目向企业咨询哟！<a href="<?php echo URL::website('/xiangdao/fenlei/');?>" class="yellow zxmbtn">找项目</a></p>
				<?php }?>			
				<div class="clear"></div>
			</div>
			<!-- 收到名片显示结束 -->			
		</div>
		<div class="list_1">
			
			<!-- 我咨询的名片开始 -->
			<div class="pro_card_div_cxy" <?php if(empty($consult_list)){echo 'style="margin-top:-4px; border-top:none; padding-top: 0;"';}?>>
			<p class="inf">
				<span style="padding-right:40px;">收到名片共<a style="color:#FF1D00" href="<?php echo URL::website("/person/member/card/receivecard");?>"><?php echo $receive_card_count;?></a>张</span>距离上次登录收到名片<a style="color:#FF1D00" href="<?php echo URL::website("/person/member/card/receivecard");?>"><?php echo $receive_card_lastlogin;?></a>张<a href="<?php echo URL::website("/person/member/card/receivecard");?>" target="_self" style="color:#005ea7; margin-left:30px;">查看更多>></a>
			</p>
				<?php 
					if(!empty($consult_list)){
					foreach($consult_list as $ck=>$cv){
				?>				
				<div class="pro_a">
					<div class="lf">
						<a href="javascript:;"><img src="<?php echo $consult_list[$ck][2];?>"></a>
					</div>
					<div class="rt">
						<p><a style="color: #0b73bb; font-size:16px;" target="_blank" href="<?php echo urlbuilder::project($consult_list[$ck][0]);?>"><?php echo $consult_list[$ck][3];?></a></p>
						<p>招商金额：<?php echo $consult_list[$ck][4];?></p>
						<p>招商地区：<?php echo $consult_list[$ck][1];?></p>
						<a href="<?php echo urlbuilder::project($consult_list[$ck][0]);?>"  target="_blank" class="viewcard_person">查看详情</a>
					</div>
				</div>
				<?php }}else{?>
					<p style="padding-top:35px;">您还没有咨询过项目，主动搜索项目进行咨询可以加快您投资的速度哟！<a href="<?php echo URL::website('/xiangdao/fenlei/');?>" class="yellow zxmbtn">找项目</a></p>
				<?php }?>
				<div class="clear"></div>
			</div>
			<!-- 我咨询的名片结束-->
		</div>
	</div>
	<!-- 我收藏的项目 -->
	<h3 style="margin-bottom: 0;border-bottom: none;">
		<span style="background: #fff;height: 32px;position: relative;top: 3px;">我收藏的项目</span>
		<span style="font-size:12px; border-right: none;">
			我收藏的项目：<a href="<?php echo URL::website('person/member/project/watchProject')?>" style="color:#FF1D00"><?php echo $project_list_count;?></a>个<i>您还可搜索更多心仪的项目！</i>
		</span>
		<a href="<?php echo URL::website('/xiangdao/')?>" class="search_btn"><img src="<?php echo URL::webstatic('images/per_index/search_img.png')?>" /></a>
		<div class="clear"></div>
	</h3>
	<div class="list_2" style="border-top:none;">
		<div style="margin-top:0" class="pro_card_div_cxy" <?php if(!count($project_list)){echo 'style="margin-top:-4px; border-top:0"';}?>>
			<div class="pro_b">
			<?php 
				if(count($project_list)){
				foreach($project_list as $k=>$value){
			?>			
				<div class="lf" <?php if($k == 0){echo 'style="margin-left: 0"';}?>>
					<a href="<?php echo urlbuilder::project($value['watch_project_id']);?>" target="_blank"><img src="<?php  if($value["project_source"] != 1) { $purl=project::conversionProjectImg( $value["project_source"], 'logo', $value);if(project::checkProLogo($purl)){echo $purl;}else{ echo URL::webstatic('images/common/company_default.jpg');};} else { $purl = URL::imgurl($value['project_logo']);if(project::checkProLogo($purl)){echo $purl;}else{echo URL::webstatic('images/common/company_default.jpg');} ;}?>"/></a>
					<p style="font-size: 14px;"><a class="col333" href="<?php echo urlbuilder::project($value['watch_project_id']);?>" target="_blank"><?php echo $value['project_name'];?></a></p>
					<p>上海，<span style="color:#FF1D00"><?php $list=common::moneyArr(); echo Arr::get($list,$value['project_amount_type'],0);?></span></p>
				</div>						
			<?php }}else{?>
			<p style="padding-top:35px;">您还没有收藏项目，快去搜索您心仪的项目进行收藏吧 ！<a href="<?php echo URL::website('/xiangdao/fenlei/')?>" class="yellow zxmbtn">找项目</a></p>
			<?php }?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="view_more">
		<a href="<?php echo URL::website('person/member/project/watchProject')?>">查看更多的收藏项目>></a>
	</div>
	</div>
	<!-- 名片管理 收藏项目 结束 -->
	<!-- 推荐开始 -->
	<div class="recommend">
		<?php if($tuijian && $xihuan && $zuire && $zuixin){?>
		<div class="recommendtab clearfix">
			<a href="javascript:;" class="on">为你推荐<span></span></a><a href="javascript:;">猜你喜欢<span></span></a><a href="javascript:;">最热项目<span></span></a><a href="javascript:;">最新商机<span></span></a>
		</div>
		<?php }elseif($tuijian && $zuire && $zuixin){?>
		<div class="recommendtab recommendtab2 clearfix">
			<a href="javascript:;" class="on">为你推荐<span></span></a><a href="javascript:;">最热项目<span></span></a><a href="javascript:;">最新商机<span></span></a>
		</div>
		<?php }elseif($xihuan && $zuire && $zuixin){?>
		<div class="recommendtab recommendtab2 clearfix">
			<a href="javascript:;" class="on">猜你喜欢<span></span></a><a href="javascript:;">最热项目<span></span></a><a href="javascript:;">最新商机<span></span></a>
		</div>
		<?php }else{?>
		<div class="recommendtab recommendtab1 clearfix">
			<a href="javascript:;" class="on">最热项目<span></span></a><a href="javascript:;">最新商机<span></span></a>
		</div>
		<?php }?>
		<div class="recommendtabbox">
			<?php if($tuijian){?>
			<div class="recommendconbox on recommendconbox1">
				<a href="javascript:;" class="recommendleft"></a>
				<div class="recommendcon">
					<div class="ulbox">
						<ul class="browse_list on clearfix">
							<?php foreach($tuijian as $val){?>
							<li>
								<p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>" src="<?=URL::imgurl($val['project_logo'])?>" /></a></label></p>
								<span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,16,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,16,'UTF-8')."";};?></a></span>
								<span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
								<div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?></a></p></div>
							</li>
							<?php }?>
						</ul>
					</div>
				</div>
				<a href="javascript:;" class="recommendright"></a>
			</div>
			<?php }?>
			<?php if($xihuan){?>
			<div class="recommendconbox <?php if(!$tuijian){?>on<?php }?> recommendconbox2">
				<a href="javascript:;" class="recommendleft"></a>
				<div class="recommendcon">
					<div class="ulbox">
						<ul class="browse_list on clearfix">
							<?php foreach($xihuan as $val){?>
							<li>
								<p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>" src="<?=URL::imgurl($val['project_logo'])?>" /></a></label></p>
								<span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,16,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,16,'UTF-8')."";};?></a></span>
								<span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
								<div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?></a></p></div>
							</li>
						<?php }?>
						</ul>
					</div>
				</div>
				<a href="javascript:;" class="recommendright"></a>
			</div>
			<?php }?>
			<?php if($zuire){?>
			<div class="recommendconbox <?php if(!$tuijian && !$xihuan){?>on<?php }?> recommendconbox3">
				<a href="javascript:;" class="recommendleft"></a>
				<div class="recommendcon">
					<div class="ulbox">
						<ul class="browse_list on clearfix">
							<?php foreach($zuire as $val){?>
							<li>
								<p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>" src="<?=URL::imgurl($val['project_logo'])?>" /></a></label></p>
								<span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,16,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,16,'UTF-8')."";};?></a></span>
								<span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
								<div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?></a></p></div>
							</li>
							<?php }?>
						</ul>
					</div>
				</div>
				<a href="javascript:;" class="recommendright"></a>
			</div>
			<?php }?>
			<?php if($zuixin){?>
			<div class="recommendconbox recommendconbox4">
				<a href="javascript:;" class="recommendleft"></a>
				<div class="recommendcon">
					<div class="ulbox">
						<ul class="browse_list on clearfix">
							<?php foreach($zuixin as $val){?>
							<li>
								<p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>" src="<?=URL::imgurl($val['project_logo'])?>" /></a></label></p>
								<span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,16,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,16,'UTF-8')."";};?></a></span>
								<span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
								<div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?></a></p></div>
							</li>
							<?php }?>
						</ul>
					</div>
				</div>
				<a href="javascript:;" class="recommendright"></a>
			</div>
			<?php }?>
		</div>
	</div>
	<!-- 推荐结束 -->
</div>
<!--右侧结束-->
<!--透明背景开始-->
<div id="getcards_opacity" style="display:<?php if( $oauinfo['bind_user_id']==null || $oauinfo['bind_user_id']=='' ){?>block;<?php }else{ ?>none;<?php }?>"></div>
<!--透明背景结束-->

<!--查看名片开始-->
<div id="person_viewcard">
    <div id="person_layout">
        <a href="#" class="close">关闭</a>
        <div  id="layout_compan">
        </div>
    </div>
</div>
<!--查看名片结束-->

<!--个人中心弹出框 start-->
<div class="person_safe_email_box" style="display:none;">
	<FORM action="/person/member/basic/editUserEmail" method="post">
		<h4>欢迎您，<b><?php echo $userName?>！</b></h4>
		<p>系统检测到您的邮箱尚未完善，为了更好的提升为您服务的目的，请您完善</p>
		<p>
			<label><font>*</font>您的邮箱：</label>
			<input name="email" class="email" type="text" value="请输入您的邮箱"/>
			<i id="email_msg" class="email_box_msg"><?php if( $error=='email' ){ echo '邮箱已存在'; }if( $error=='emr' ){ echo '邮箱错误'; }?></i>
		</p>
		<p>系统检查到您的密码是弱密码，为了保障您的数据安全，请您修改</p>
		<p>
			<label>新密码：</label>
			<input name='psd' class="password" type="password"/>
			<i id="password_msg" class="email_box_msg"></i>
		</p>
		<p>
			<label>请再次输入新密码：</label>
			<input name='psd2' class="password_again" type="password"/>
			<i id="passwordagain_msg" class="email_box_msg"></i>
		</p>
		<p class="person_safe_email_box_btn">
			<input type="checkbox" id="not_again"/>
			<label for="not_again">不再弹出此消息</label>
			<input type="submit" value="修改"/>
		</p>
	</FORM>
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
<!--个人中心弹出框 end-->
<?php echo URL::webjs("platform/project_home/nambo_box.js") ?>
<?php if( $showemailwindow=='0' ){?>
<?php if( $inuser=='1' && $eof=='0' ){?>
    <script type="text/javascript">usercenter_email_box_init();</script>
<?php }}?>
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
// 更换头像弹出框
/*upload_icon(obj ,images, result, callback, text)
修改头像弹框
obj为触发弹框的元素
images为弹框中默认头像的图片路径
result为接收选择结果（图片路径）的input表单
callback为回调函数
text为自定义头像显示的文字
*/

new upload_icon($("#upload_icon_btn"), icon_images, "change_icon_05.png", "/person/member/ajaxcheck/uploadPerLogo", null, null, "上传头像可以吸引更多的企业关注您，有利于您快速的找到心仪的项目！");

// 名片管理 我的咨询 切换
$(function(){
	$(".per_index h3.h3tab span").click(function(){
		var $index=$(this).index();
		$(".list_lbox .list_1").eq($index).show().siblings().hide();
		$(this).addClass('on').siblings().removeClass('on')
	})
})
</script>