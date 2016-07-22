<input type="hidden" value="<?php echo $reg_fu_platform_num?>" id="platform_num"/>
<input type="hidden" value="<?php echo $reg_fu_user_num?>" id="user_num"/>
<input type="hidden" value="<?php echo $reg_fu_platform_num?>" id="reg_fu_platform_num_id"/>
<input type="hidden" value="<?php echo "http://".$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" id="to_url_id"/>
<input type="hidden" value="<?php echo (isset($login_user_id) and $login_user_id) ? $login_user_id : 0;?>"  id="login_user_id"/>
<input type="hidden" value="<?php echo (isset($project_publish_user_id) and $project_publish_user_id) ? $project_publish_user_id : 0;?>" id="project_publish_user_id"/>
    <input type="hidden" value="<?php echo $reg_fu_user_num?>" id="reg_fu_user_num_id"/>
	<div class="quickrelease-content clearfix">
		<div class="project-left">
			<div class="berif-info clearfix">
				<h1 id="project_title"><?php echo $project_title;?></h1>
				<h2 style="color: #fe7f00; font-size: 16px;"><?php echo  htmlspecialchars_decode($project_info['project_introduction']); ?></h2>
				<div class="project-info">
				<!--  
					<span class="slogan"><?php echo  htmlspecialchars_decode($project_info['project_introduction']); ?></span>
					-->
					<span class="time" id="project_time"><?php echo   $project_info['project_update_time']; ?></span>
					<span class="view-count">浏览量：<?php echo $project_info['project_pv_count'];?></span>
					<span class="detail">品牌名称：<b><?php echo $project_info['project_brand_name'];?></b></span>
					<span class="detail">行业分类：<b><?php echo $project_info['project_industry_name'];?></b></span>
					<span class="detail">支持加盟地区：<b><?php echo $project_info['project_area_name'];?></b></span>
					<span class="detail">招商形式：<b><?php echo $project_info['project_model_name'];?></b></span>
					<span class="detail">投资金额：<em><?php echo $project_info['project_amount_type_name'];?></em></span>
					<?php  $mobile_image=empty($project_info['mobile_image']) ? '' :  URL::imgurl($project_info['mobile_image']);?>
					<span class="detail">联系电话：<font><img style="vertical-align: middle" src="<?php echo $mobile_image;?>"/></font></span>
					<?php  $phone=$project_info['project_phone'];
					   if(!empty($phone)) { ?>
					<span class="detail">其他电话：<font><?php echo $phone;?></font></span>
					<?php } ?>
					<span class="detail">
					<a href="javascript:;" class="btn btn-red mymessage" data-type="111" project_id="<?php echo $project_id;?>" login_user_id="<?php echo $login_user_id;?>" com_user_id="<?php echo $com_user_id;?>" >
						<i class="icon-conmment"></i>我要留言
					</a>
					</span>
					<span class="other">
						<span <?php if($is_print_zan==false) echo 'onclick="zan();"';?>  style="cursor: pointer;" id="projectZan" class="<?php if($is_print_zan) echo "yi";?>zan"><?php echo $project_info['project_approving_count'];?></span>
						<a class="share">+分享到</a>
						<?php $project_href=urlbuilder::qucikProHome($project_id);
						      $project_logo=$project_info['project_logo'];
						?>
						<div class="share_list">
							<a href="#" class="share_sina" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo $project_href;?>&amp;title=我在一句话生意网发现了一个叫{<?php echo $project_title;?>}的好招商加盟信息，很靠谱，你也来看看吧。创业投资不迷茫，海量招商加盟信息任你挑，找招商加盟信息就是一句话的事儿！&amp;appkey=1343713053&amp;pic=<?php if($project_logo) echo URL::imgurl($project_logo);?>');return false;">新浪微博</a>
							<a href="#" class="share_zone" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo $project_href;?>&amp;title=我在一句话生意网发现了一个叫{<?php echo $project_title;?>}的好招商加盟信息，很靠谱，你也来看看吧。创业投资不迷茫，海量招商加盟信息任你挑，找招商加盟信息就是一句话的事儿！&amp;pics=<?php if($project_logo) echo URL::imgurl($project_logo);?>');return false;">QQ空间</a>
							<a href="#" class="share_qqwei" onclick="{ var _t = '我在一句话生意网发现了一个叫{<?php echo $project_title;?>}的好招商加盟信息，很靠谱，你也来看看吧。创业投资不迷茫，海量招商加盟信息任你挑，找招商加盟信息就是一句话的事儿！';  var _url = '<?php echo $project_href;?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = '<?php if($project_logo) echo URL::imgurl($project_logo);?>'; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&amp;url='+_url+'&amp;appkey='+_appkey+'&amp;site='+_site+'&amp;pic='+_pic; window.open( _u,'转播到腾讯微博');  };">腾讯微博</a>
							<a href="#" class="share_ren" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo $project_href;?>&amp;description=我在一句话生意网发现了一个叫{<?php echo $project_title;?>}的好招商加盟信息，很靠谱，你也来看看吧。创业投资不迷茫，海量招商加盟信息任你挑，找招商加盟信息就是一句话的事儿！&amp;appkey=1343713053&amp;pic=<?php if($project_logo) echo URL::imgurl($project_logo);?>');return false;">人人网</a>
							<div class="clear"></div>
						</div>
					</span>
					<span class="yjh-remind">联系我时，请说明是在一句话生意网看到的，谢谢！</span>
				</div>
				<?php
				//print_r($project_img_info);
				$count=count($project_img_info);?>
				<?php if($count>0) {?>
				<div class="project-img">
					<div class="img-content">
						<ul class="clearfix">
							<?php for($item=0;$item<$count;$item++){
								$img_name=str_replace('s_','b_',$project_img_info[$item]['project_img']);			
							?>
							<li><div><img src="<?php  echo URL::imgurl($img_name);?>" alt="<?php echo $project_title;?>" /></div></li>
                            <?php }?>
						</ul>
						<div class="img-cursor">
							<div class="cursor-left"></div>
							<div class="cursor-right"></div>
						</div>
					</div>
					<div class="img-navagation">
						<a href="javascript:void(0);" class="img-left"></a>
						<div class="img-list-div">
							<ul class="img-list clearfix">
							<?php for($item=0;$item<$count;$item++){?>
								<li><div><img src="<?php  echo URL::imgurl($project_img_info[$item]['project_img']);?>" alt="<?php echo $project_title;?>" /></div></li>
							<?php }?>
							</ul>
						</div>
						<a href="javascript:void(0);" class="img-right"></a>
					</div>
				</div>
				<?php }?>
			</div>
				<ul class="project-nav clearfix">
					<li id="change_tab_1" class="fc" onclick="change_tab(1);"><a href="#t1">详情信息</a></li>
					<?php if(!empty($company_info)) {?>
					<li id="change_tab_2"><a href="#t2" onclick="change_tab(2);">公司信息</a></li>
					<?php }?>
				</ul>
				<a name="t1"></a>
				<div class="content-main">
					<ul class="info clearfix">
						<li>
							品牌发源地：<font><?php echo $project_info['project_brand_birthplace_city_name'];?></font><br>
							品牌历史：<font><?php echo $project_info['project_history_name'];?></font><br>
							所在区域：<font><?php echo $project_info['project_brand_birthplace_area_name'];?></font>
						</li>
						<li>							
							加盟费：<font><?php echo $project_info['project_joining_fee'];?></font><br>
							保证金：<font><?php echo $project_info['project_security_deposit'];?></font>
						</li>
					</ul>
					<p><?php echo str_replace('class=', 'classa=', htmlspecialchars_decode($project_info['project_summary']));?></p>
					<span class="yjh-remind">联系我时，请说明是在一句话生意网看到的，谢谢！</span>
					<?php $count=count($project_img_info);
					 if($count>0) {?>
					    <div id="show_big_pic">
						 <?php for($item=0;$item<$count;$item++)
						 {
						$img_name=str_replace('s_','b_',$project_img_info[$item]['project_img']);   ?>
							  <img style="max-width:515px; margin:0 auto; display: block" src="<?php  echo URL::imgurl($img_name);?>" alt="<?php echo $project_title;?>" />
					<?php } ?>
					    </div>
					<?php } ?>
					<p class="p_btn">
					<?php if ((isset($login_user_id) && !$login_user_id) || (isset($login_user_id) && isset($project_publish_user_id) && $login_user_id == $project_publish_user_id)) {  ?>
						<a href="<?php echo urlbuilder::qucikEditPro($project_id);?>" id="project_modified" class="modified">修改</a>
						<a href="javascript:void(0);" class="delete"  onclick="project_delete();">删除</a>
						<a href="javascript:void(0);" class="refresh" onclick="project_publish_time_refresh();">刷新</a>
						<?php  
							}  ?>
						<a href="javascript:void(0);" id="projectReport" class="report">举报</a>
					</p>
				</div>
				<?php if(!empty($company_info)) {?>
				<a name="t2"></a>
				<div class="content-company">
					<h4><span>公司信息</span></h4>
					<table cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td width="93" align="center">公司名称：</td>
							<td width="642" class="content-text"><?php echo $company_info['com_name'];?></td>
						</tr>
						<tr>
							<td align="center">公司网址：</td>
							<td class="content-text"><a href="<?php echo $company_info['com_site'];?>"><?php echo $company_info['com_site'];?></a></td>
						</tr>
						<tr>
							<td align="center">联系电话：</td>
							<td class="content-text contact"><?php echo str_replace('+','-',$company_info['com_phone']);?></td>
						</tr>
						<tr>
							<td align="center">商家认证：</td>
							<td class="content-text">
								<?php if(isset($company_info['valid_mobile'])){ 
								  if($company_info['valid_mobile']==1) {?><i class="icons icons-phone">手机已验证</i>
								<?php } else {?><i class="icons icons-phone-no">手机未验证</i>
								 <?php }
								  }?>
								<?php if(isset($company_info['valid_email'])){ 
								   if($company_info['valid_email']==1) {?><i class="icons icons-mail">邮箱已验证</i>
								<?php } else {?><i class="icons icons-mail-no">邮箱未验证</i>
								 <?php }
								   }?>
								<?php if($company_info['com_business_licence_status']==1) {?>
								 <i class="icons icons-card">营业执照已验证</i>
								 <?php } else {?>
								 <i class="icons icons-card-no">营业执照未验证</i>
								 <?php }?>
							</td>
						</tr>
						<tr>
							<td align="center">联系地址：</td>
							<td class="content-text"><?php echo $company_info['com_adress'];?></td>
						</tr>
					</table>
				</div>
				<?php }?>
		</div>
		<div class="project-right">
		<?php //var_dump($company_info);exit;?>
		<?php if(!empty($company_info)) {
		 $company_href=$company_info['com_site'];
			?>
			<div class="company-card">
				<a target="_blank" href="<?php echo $company_href;?>">
				  <img src="<?php echo URL::imgurl($company_info['com_logo']);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')"/>
				</a>
				<a target="_blank" href="<?php echo $company_href;?>" class="title"><?php echo $company_info['com_name'];?></a>
				<span>
				<?php $phone_css=$email_css=$card_css='-no';
				if(isset($company_info['valid_mobile'])) { if($company_info['valid_mobile']==1) $phone_css=''; }
				if(isset($company_info['valid_email'])) { if($company_info['valid_email']==1) $email_css=''; }
				if($company_info['com_business_licence_status']==1) { $card_css='';}
				?>
					<i class="icons icons-phone<?php echo $phone_css;?>" title="手机<?php echo $phone_css=='-no' ? '未' :'已' ?>验证"></i>
					<i class="icons icons-mail<?php echo $email_css;?>" title="邮箱<?php echo $email_css=='-no' ? '未' :'已' ?>验证"></i>
					<i class="icons icons-card<?php echo $card_css;?>" title="营业执照<?php echo $card_css=='-no' ? '未' :'已' ?>验证"></i>
				</span>
				<?php if(!empty($company_href)) { ?>
					<a target="_blank" href="<?php echo $company_href; ?>" class="btn btn-yellow">进入企业官网</a>
				<?php } ?>
			</div>
			<?php }?>
			<?php 
				if(!empty($person_info)) {?>
			<div class="company-card">
				<img src="<?php echo URL::imgurl($person_info['user_photo']);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')"/>
				<?php $user_gender=$person_info['user_gender']!='未知' ? $person_info['user_gender']: '';
				     echo (strpos($person_info['user_name'],'先生') || strpos($person_info['user_name'],'女士')) ? $person_info['user_name'] : $person_info['user_name'].$user_gender;?>
				<span>
				<?php $phone_css=$email_css=$card_css='-no';
				if(isset($person_info['valid_mobile'])) { if($person_info['valid_mobile']==1) $phone_css=''; }
				if(isset($person_info['valid_email'])) { if($person_info['valid_email']==1) $email_css=''; }
				if($person_info['valid_auth_status']==1) { $card_css='';} 
				?>
					<i class="icons icons-phone<?php echo $phone_css;?>" title="手机<?php echo $phone_css=='-no' ? '未' :'已' ?>验证"></i>
					<i class="icons icons-mail<?php echo $email_css;?>" title="邮箱<?php echo $email_css=='-no' ? '未' :'已' ?>验证"></i>
					<i class="icons icons-card<?php echo $card_css;?>" title="身份证认证<?php echo $card_css=='-no' ? '未' :'已' ?>验证"></i>
				</span>
			</div>
			<?php }?>
			
			<?php 
			$serviceProject = new Service_QuickPublish_Project();
            $advertList = $serviceProject->getQuickAdvertOne($project_info['project_first_industry_id']); 
              if(count($advertList)>0) {?>
              <?php if($company_info || $person_info) {?>
              	<dl class="project-list" style="margin-top:10px;">
              <?php }else{ ?>
              		<dl class="project-list">
              <?php }?>			
				<dt>招商加盟推荐</dt>
				<?foreach($advertList as $val) {?>
				<dd>
                    <a href="<?=urlbuilder::quickAdvert($val['id'])?>" target="_blank" class="img"><img src="<?=URL::imgurl($val['img'])?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')" ></a>
					<a href="<?=urlbuilder::quickAdvert($val['id'])?>" target="_blank" class="title"><?=$val['content']?></a>
				</dd>
                    <?}?>
			</dl>
			<?php }?>
            <input id="txt" type="hidden"/>
		</div>
		<dl class="project-list-main clearfix">
		<?php $count=count($tuijian_info); 
			 if($count>0) {?>
			<dt>你可能喜欢的</dt>
			<?php for($item=0;$item<$count;$item++)
						  {
							$tuijian_project_id=$tuijian_info[$item]['project_id'];
							$tuijian_img_path=URL::imgurl($tuijian_info[$item]['project_logo']);
							$tuijian_project_href=urlbuilder::qucikProHome($tuijian_project_id);
							$tuijian_project_title= common::truncateStr($tuijian_info[$item]['project_title'],14,'');
							$tuijian_project_name=$tuijian_info[$item]['project_brand_name'];
						?>
					<dd<?php if($item==0) echo ' style="margin-left:0;"'; ?>>
						<a href="<?php echo $tuijian_project_href;?>" class="img">
						   <img src="<?php echo $tuijian_img_path;?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')"  alt="<?php echo $tuijian_project_name;?>" title="<?php echo $tuijian_project_name;?>"/></a>
						<a href="<?php echo $tuijian_project_href;?>" class="title"><?php echo $tuijian_project_title;?></a>
					</dd>
					<?php }?>
				
				<?php }?>
				</dl>
			<dd class="more-list" style="display:none">
				<ul class="clearfix">
					<li><a href="#">贵州茅台集团核心战略 品牌茅台液</a></li>
					<li><a href="#">贵州茅台集团核心战略 品牌茅台液</a></li>
					<li class="last"><a href="#">贵州茅台集团核心战略 品牌茅台液</a></li>
					<li><a href="#">贵州茅台集团核心战略 品牌茅台液</a></li>
					<li><a href="#">贵州茅台集团核心战略 品牌茅台液</a></li>
					<li class="last"><a href="#">贵州茅台集团核心战略 品牌茅台液</a></li>
					<li><a href="#">贵州茅台集团核心战略 品牌茅台液</a></li>
					<li><a href="#">贵州茅台集团核心战略 品牌茅台液</a></li>
					<li class="last"><a href="#">贵州茅台集团核心战略 品牌茅台液</a></li>
				</ul>
			</dd>
		</dl>
		<div class="tag-list">
		<?php $count=count($hot_city);
		if($count>0) {
		?>
			<dl class="clearfix">
				<dt>热门城市</dt>
				<dd>
					<ul class="clearfix">
					<?php for($item=0;$item<$count;$item++) {
						$area=$hot_city[$item]['area_id'];
						$hy=$hot_city[$item]['industry_id'];
						$href=urlbuilder::quickSearchCond(array('area_id'=>$area,'industry_id'=>$hy,'atype'=>0,'pmodel'=>0),array('area_id'=>$area,'industry_id'=>$hy,'atype'=>0,'pmodel'=>0));  
			         ?>
						<li><a href="<?php echo $href; ?>"><?php echo $hot_city[$item]['name'];?></a></li>
					<?php } ?>
					</ul>
				</dd>
			</dl>
			<?php } ?>
			<!--<dl class="clearfix" >
				<dt>热门搜索</dt>
				<dd>
					<ul class="clearfix">
						<li><a href="#">全国招商加盟</a></li>
						<li><a href="#">全国招商加盟</a></li>
						<li><a href="#">全国招商加盟</a></li>
						<li><a href="#">全国招商加盟</a></li>
						<li><a href="#">全国招商加盟</a></li>
						<li><a href="#">全国招商加盟</a></li>
						<li><a href="#">全国招商加盟</a></li>
						<li><a href="#">全国招商加盟</a></li>
						<li><a href="#">全国招商加盟</a></li>
						<li><a href="#">全国招商加盟</a></li>
					</ul>
				</dd>
			</dl>-->
			<?php $count=count($hot_type);
			if($count>0) {
			?>
			<dl class="clearfix">
				<dt>热门类目</dt>
				<dd>
					<ul class="clearfix">
					<?php for($item=0;$item<$count;$item++) {
						$area=$hot_type[$item]['area_id'];
						$hy=$hot_type[$item]['industry_id'];
						$href=urlbuilder::quickSearchCond(array('area_id'=>$area,'industry_id'=>$hy,'atype'=>0,'pmodel'=>0),array('area_id'=>$area,'industry_id'=>$hy,'atype'=>0,'pmodel'=>0));  
			         ?>
						<li><a href="<?php echo $href; ?>"><?php echo $hot_type[$item]['name'];?></a></li>
					<?php } ?>
					</ul>
				</dd>
			</dl>
			<?php } ?>
		</div>
	</div>
	<input type="hidden" id="project_id" value="<?php echo $project_id;?>" />
	<input type="hidden" id="project_manage" value="<?php  echo urlbuilder::qucikProManage();?>"/>

<?php echo URL::webjs("quickRelease.js")?>
<?php echo URL::webjs("jquery.validate.js")?>
<?php echo URL::webjs("platform/home/login_fu.js")?>
<script type="text/javascript">
function change_tab(id)
{
	$("[id^=change_tab_]").removeClass('fc');
	$('#change_tab_'+id).addClass('fc');
}
var project_id=$("#project_id").val();
//登录并且是自己项目，可以刷新
//project_publish_time_refresh();
function project_publish_time_refresh()
{
	if($("#login_user_id").val() == "0"){
var to_url=window.location.href;
		var html='<div class="clearfix loginpuper"><a class="fl yiaccount" href="'+window.$config.siteurl+'member/login?to_url='+to_url+'""><span class="spantitle">已有帐号登录</span><span class="spaninfo">会员通过用户中心修改删除自己发布的信息</span> </a><a class="fl mobileaccount" href="javascript:;"><span class="spantitle">手机号码登陆</span><span class="spaninfo">电话被冒用或忘记密码可以使用此功能</span></a></div>';
		loginpuper=window.MessageBox({
		title:"请选择登录方式",
		content:html,
		width:790
		}); 
		return false;
	}
	$.ajax({
		url: '/quick/Ajaxcheck/updateQuickProPublishTime',
		type: 'POST',
		dataType: "json",
		data: 'project_id='+project_id,
		complete :function(){},
		success: function(data){
			var msg_tip='更新失败';
			if(data.status==1)
			{
				var project_update_time=data.date;
				if(typeof(project_update_time)!='undefined')
				{
					msg_tip='恭喜您今日更新成功';//+project_update_time;
					$("#project_time").html(project_update_time);
				}
			}
			if(data.status==2)
			{
				msg_tip="一个招商加盟信息一天只有<font color='red'>1</font>次更新机会，请明天再来操作。";
			}
			window.MessageBox({
			    title:"项目数据是否已更新",
			    content:"<p>"+msg_tip+"</p>",
			    btn:"ok",
			    width:400,
			    target:"new"
			  });
		}
	  });
}

//登录并且是自己项目，可以删除
function project_delete()
{ 
	if($("#login_user_id").val() == "0"){
		var to_url=window.location.href;
		var html='<div class="clearfix loginpuper"><a class="fl yiaccount" href="'+window.$config.siteurl+'member/login?to_url='+to_url+'""><span class="spantitle">已有帐号登录</span><span class="spaninfo">会员通过用户中心修改删除自己发布的信息</span> </a><a class="fl mobileaccount" href="javascript:;"><span class="spantitle">手机号码登陆</span><span class="spaninfo">电话被冒用或忘记密码可以使用此功能</span></a></div>';
loginpuper=window.MessageBox({
title:"请选择登录方式",
content:html,
width:790
}); 
		return false;
	}
	window.MessageBox({
		title:"一句话网站提示您",
		content:"<p>您是否确认删除当前招商加盟信息？</p>",
		btn:"ok cancel",
		callback:function(){
			$.ajax({
				contentType:"application/x-www-form-urlencoded;charset=UTF-8",
				url: '/quick/Ajaxcheck/delQuickPro',
				type: 'POST',
				dataType: "json",
				data: 'project_id='+project_id,
				complete :function(){},
				success: function(data){					
					if(data.status==1)
					{
						window.location.href=$("#project_manage").val();
					}
				}
			  });
		}
	});
}
//点赞
function zan()
{
	$.ajax({
	contentType:"application/x-www-form-urlencoded;charset=UTF-8",
	url: '/quick/Ajaxcheck/addApproving',
	type: 'POST',
	dataType: "json",
	data: 'project_id='+project_id,
	success: function(msg){
		//alert(msg.status);
		if(msg.status==1)
		{
			var zan=$("#projectZan").html();
			var now_zan=parseInt(zan)+1;
			$("#projectZan").html(now_zan);
			$("#projectZan").removeAttr("onclick");
			$("#projectZan").removeClass("zan");
			$("#projectZan").addClass("yizan");
		}
	}
  });
}
tongjipv(); //页面直接加载,统计PV
//统计PV
function tongjipv()
{
	$.ajax({
	type: "POST",
	contentType:"application/x-www-form-urlencoded;charset=UTF-8",
	url: '/quick/Ajaxcheck/TongJiProjectPv',
	type: 'POST',
	data: 'project_id='+project_id,
	success: function(msg){
	}
  });
}
</script>
<script type="text/javascript">
var mymsg= [ //弹出框模板
			'<div class="project_home_quick_register" style="display:block;">',
			'<div class="project_home_quick_register_div">',
			'<form id="myconsulte"><div class="quick_register_left">',
				'<ul>',
					'<li class="quick_register_label"><font>*</font>姓名:</li>',
					'<li class="quick_register_form">',
						'<input type="text" class="quick_register_width1 validate required" name="user_name" id="user_name" />',
					'</li>',
					'<li class="quick_register_label">邮箱:</li>',
					'<li class="quick_register_form">',
						'<input type="text" name="email" class="quick_register_width2" id="mpemail" />',
					'</li>',
					'<li class="quick_register_label"><font>*</font>手机号码:</li>',
					'<li class="quick_register_form validateerror">',
						'<input type="text"  name="mobile" class="quick_register_width3 validate required ismobile" id="mpphonenum"   maxlength="20"/>',
						'<input type="button" name="send_code" id="send_code" class="quick_register_mobile" value="发送验证码" />',
						'<p id="mobile_code_info">手机号码将做为您登录"一句话"网站的账号</p>',
					'</li>',
					'<li class="quick_register_label"><font>*</font>验证码:</li>',
					'<li class="quick_register_form">',
						'<input type="text" class="quick_register_width3 validate required" name="check_code" id="mpyanzheng"/>',
					'</li>',
					'<li class="quick_register_label"><font>*</font>留言:</li>',
					'<li class="quick_register_form quick_register_sent_message" style="margin-bottom:10px;">',
						'<textarea name="leave_word" id="lywords" class="validate required"  maxlength="150"></textarea>',
						'<ul class="quick_message_div">',
							'<img class="quick_message_div_ico" src="'+window.$config.staticurl+'images/platform/project_home/quick_register_ico_03.png"></img>',
							'<li><a href="javascript:void(0)">你们的项目很好，请速速联系我详谈。</a></li>',
							'<li><a href="javascript:void(0)">我想了解更多你们的项目，请给我相关的加盟资料</a></li>',
							'<li><a href="javascript:void(0)">加盟你们的项目需要多少费用？</a></li>',
							'<li><a href="javascript:void(0)">请问我所在的地区有加盟商了吗？</a></li>',
							'<li><a href="javascript:void(0)">我想了解你们项目后期有什么支持？</a></li>',
							'<li><a href="javascript:void(0)">我想了解你们项目的加盟电话？</a></li>',
							'<li><a href="javascript:void(0)">我想了解怎样/如何加盟你们项目？</a></li>',
							'<li><a href="javascript:void(0)">我想了解可以随时加盟吗？</a></li>',
							'<li><a href="javascript:void(0)">请问你们项目的加盟流程是怎么样的？</a></li>',						'</ul>',
						'<div class="clear"></div>',
					'</li>',
					'<div class="clear"></div>',
				'</ul>',
				'<p class="msg">您登录"一句话"网站的密码将发送至您的手机号码上，请注意查收</p>',
				'<p>',
					'<input type="submit" id="quickReg" class="quick_register_save_btn" value="保存并发送留言"/>',
				'</p>',
			'</div>',
			'</form><div class="quick_register_right">',
					'<span class="login_fc_freetext">已有帐号，请直接登录</span>',
					'<div class="clear"></div>',
					'<p class="login_lk_login">',
						'<input id="gologinbtn" type="image" class="login_lk_login_btn" src="'+window.$config.staticurl+'images/platform/login_new/btn_login.jpg">',
					'</p>',
					'<div class="clear"></div>',
					'<p class="login_new_attention_num0515">',
					  '<label>目前已有<b>'+$("#platform_num").val()+'</b>个项目，<b>'+$("#user_num").val()+'</b>个用户加入一句话</label>',
					  
					'</p>',
			'</div>',
			'<div class="clear"></div>',
			'</div>',
    		'<input type="hidden" id="hidprojectid"  value="'+this.projectid+'"/>',
    		'</div>',
			].join("");
var lyBox = new messageBox($(".mymessage")[0], {
	title:"我要留言",
	content:mymsg,
	width:760
});
var fsmp;
$(".mymessage").click(function(){
	$.ajax({
    	type: "post",
    	dataType: "json",
    	url: $config.siteurl+"platform/ajaxcheck/sendLetterByPer/",
    	data: 'to_user_id='+$(".mymessage").attr("com_user_id")+"&type="+1+"&projectid="+$(".mymessage").attr("project_id"),
    	complete :function(){
    	},
    	success: function(msg){

    	    if(msg['error']=='notlogin'){//如果没有登录
    	        $(".mymessage")[0].show();
    	    }else if(msg['error']!=''){//如果获取信息发送错误
    	        window.MessageBox({
    	        	title:"一句话网站提示您",
    	        	content:"<div class='mb_content send_box'>"+msg['error']+"</div>",
    	        	btn:"ok"
    	        });
    	    }else{//获取成功
    	    	
    	    	var html = [
			'<form id="leaveMsgForm" method="post"><ul class="leaveMessageBox">',
			'	<li>',
			'		<label ><font>*</font>姓名：</label>',
			'		<input type="text" name="name" value="'+msg.user_name+'"/>',
			'	</li>',
			'	<li>',
			'		<label >邮箱：</label>',
			'		<input type="text" class="length email" name="email" value="'+msg.email+'"/>',
			'	</li>',
			'	<li>',
			'		<label ><font>*</font>手机号码：</label>',
			'		<input type="text" id="phoneNum" name="mobile" data-phone="'+msg.mobile+'" value="'+msg.mobile+'"/>',
			'		<a id="codeBtn" class="codeBtn">发送验证码</a>',
			'<p id="mobile_code_info1" style="color:#666;"></p>',
			'	</li>',
			'	<li id="phoneCode" style="display:none;">',
			'		<label ><font>*</font>验证码：</label>',
			'		<input type="text" id="send_code" name="code" />',
			'	</li>',
			'	<li class="last">',
			'		<label ><font>*</font>留言：</label>',
			'		<textarea cols="30" rows="10" name="message"></textarea>',
			'	</li>',
			'	<li>',
			'		<span>目前已有<font>'+$("#platform_num").val()+'</font>个项目，<font>'+$("#user_num").val()+'</font>个用户加入一句话</span>',
			'		<a href="#" class="submitBtn" onclick="$(\'#leaveMsgForm\').submit()">保存并发送留言</a>',
			'	</li>',
			'</ul></form>'
		].join("");
		var myly=window.MessageBox({
			title:"我要留言",
			content:html
		});
		if($("#phoneNum").val() != $("#phoneNum").attr("data-phone")){
			$("#phoneCode").show();
			$("#codeBtn").css({"display":"inline-block"});
		}else{
			$("#phoneCode").hide();
			$("#codeBtn").css({"display":"none"});
		}
		$("#phoneNum").change(function(){
			if($("#phoneNum").val() != $("#phoneNum").attr("data-phone")){
			$("#phoneCode").show();
			$("#codeBtn").css({"display":"inline-block"});
			}else{
				$("#phoneCode").hide();
				$("#codeBtn").css({"display":"none"});
			}
		})

		$.validator.addMethod("phone", function(val, element) {       
	    	return this.optional(element) || /^1[3458]\d{9}$/.test(val);       
		});
		$.validator.addMethod("email", function(val, element) {       
	    	return this.optional(element) || /^([a-zA-Z0-9]+[_|\-|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/.test(val);       
		});
		$.validator.addMethod("code", function(val, element) {       
	    	return ($("#phoneNum").val() == $("#phoneNum").attr("data-phone")) || this.optional(element) || /^1[3458]\d{9}$/.test(val);       
		});

		$("#leaveMsgForm").validate({
			rules:{
				name:{
					required:true
				},
				email:{
					email:true
				},
				mobile:{
					required:true,
					phone:true
				},
				code:{
					required:true
				},
				message:{
					required:true
				}
			},
			messages:{
				name:"请输入您的姓名" ,
				email:"请输入正确的邮箱",
				mobile:"请输入手机号码！",
				code:"请输入验证码",
				email:"输入邮箱有误",
				message:"请填写留言"
			},
			submitHandler:function(){
				var param = $("#leaveMsgForm").serialize();
				//alert(url);return false;
				var url ="/quick/Ajaxcheck/addCardInfo";
				var tprojectId=$(".mymessage").attr("project_id");
				var to_user_id = $("#project_publish_user_id").val();
				var data=param+"&projectid="+tprojectId+"&type=1&to_user_id="+to_user_id+"&from_user_id="+$("#login_user_id").val()+"&old_mobile="+$("#phoneNum").attr("data-phone");
				var dt=$.ajaxsubmit(url,data)
				
				if(dt.msg =="ok"){
					myly.hide();
					window.MessageBox({
	                	title:"一句话网站提示您",
	                	content:"<p>您已把联系方式和留言信息递送给该生意信息所有者了，收到您的信息后将第一时间与您取得联系。</p>",
	                	btn:"ok",
	                	callback:function(){
	                		window.location.reload();
	                	}
	                });
				}else{
					myly.hide();
					window.MessageBox({
	                	title:"一句话网站提示您",
	                	content:"<div class='mb_content'>"+dt.msg+"</div>",
	                	btn:"ok",
	                	target:"new"
	                });
				}
			},
			errorPlacement:function(error, element){
				if($(element).attr("id") == "phoneNum"){
					$("#phoneCode").hide();
					$("#codeBtn").css({"display":"none"});
				}
				error.appendTo($(element).parent());
			}
		});
    	    }
    	}    
    });
})
$("#quickReg").live("click",function(){
	
	$("#myconsulte").validate({
	    onkeyup:false,
	    rules: {  
		   user_name: "required",
		   mobile:"required",
		   check_code:"required",
		   email:{
				email:true
			},
		   leave_word:"required"
		},  
		messages: {  
		   user_name: "请输入您的姓名" ,
		   mobile:"请输入手机号码",
		   email:"输入邮箱有误",
		   check_code:"请输入验证码",
		   leave_word:"请填写留言"
		},
		submitHandler:function(){
			var param = $("#myconsulte").serialize();
			   	var submit_msg = setInterval(function(){
			        var now_val = $("#quickReg").val();
			        switch(now_val){
			            case "保存并发送留言":now_val="Loading";break;
			            case "Loading":now_val="Loading.";break;
			            case "Loading.":now_val="Loading..";break;
			            case "Loading..":now_val="Loading...";break;
			            case "Loading...":now_val="Loading";break;
			            default :"Loading";break;
			        }
					$("#quickReg").val(now_val);
			    }, 200);
			   		var tprojectId=$(".mymessage").attr("project_id");
			   		$t_type=$("#hidseclecttype").val();
			   		var to_user_id = $("#project_publish_user_id").val();
			       $.ajax({
			        type: "post",
			        dataType: "json",
			        url:"/quick/Ajaxcheck/quickRegister",
			        data:param+"&projectid="+tprojectId+"&type=1&to_user_id="+to_user_id,
			        complete :function(){
						
			        },
			        success: function(msg){
						clearInterval(submit_msg);
			            $("#quickReg").attr("disabled",false);
			            $("#quickReg").val("保存并发送留言");
			            $("#quickReg").removeClass("quick_register_save_btn_load");

			            if(msg['code']=='200'){
			                window.MessageBox({
			                	title:"一句话网站提示您",
			                	content:"<p>您已把联系方式和留言信息递送给该生意信息所有者了，收到您的信息后将第一时间与您取得联系。</p>",
			                	btn:"ok",
			                	callback:function(){
			                		window.location.reload();
			                	}
			                });
							//fsmp.hide();
			            }else if(msg['code']=='500'){
			                window.MessageBox({
			                	title:"一句话网站提示您",
			                	content:"<div class='mb_content'>"+msg['msg']+"</div>",
			                	btn:"ok",
			                	target:"new"
			                });
			            }else{

			            }
			        }
			    });
		}
	});
})
$("#send_code").click(function(){
	//手机发发送验证码,检测手机号码是否可用
        	//TODO 检查手机号码合法性
        	var mobile = $('input[name="mobile"]').val();
        	if(mobile.length == 0)
        	{
        		if($("#send_code").parent().find("label.error").length<=0){
        			$("#send_code").parent().append('<label for="mpphonenum" class="error">请输入手机号码</label>');
        		}else{
        			$("#send_code").parent().find("label.error").css("display", "block");
        		}
	
        	}else{
	
        	    var send_code_msg = setInterval(function(){
        	           var now_val = $("#send_code").val();
        	           switch(now_val){
        	               case "发送验证码":now_val="Loading";break;
        	               case "Loading":now_val="Loading.";break;
        	               case "Loading.":now_val="Loading..";break;
        	               case "Loading..":now_val="Loading...";break;
        	               case "Loading...":now_val="Loading";break;
        	               default :"Loading";break;
        	           }
	
        	           $("#send_code").val(now_val);
        	    }, 200);
        	    $("#send_code").addClass("quick_register_mobile_loading");
        	    $("#send_code").attr("disabled",true);
        	    $("#send_code").addClass("quick_register_mobile_unuse");
	
        	    $.ajax({
        	        type: "post",
        	        dataType: "json",
        	        url:"/ajaxcheck/sendMobileCode",
        	        data:{'mobile':mobile, 'type' : 'mobile_card'},
        	        complete :function(){
        	        },
        	        success: function(msg){
        	            $("#mobile_code_info").html(msg.msg);
        	            if(msg.code==200){
        	                $("#mpphonenum").siblings(".tipin").css("color", "#999");
        	                var now_time = 30;
        	                var msg_interval = setInterval(function(){
        	                    $("#send_code").next().html((now_time--) +"秒后点击重新发送");
        	                    if(now_time == -1){
        	                        $("#send_code").attr("disabled",false);
        	                        $("#send_code").removeClass("quick_register_mobile_unuse");
        	                        $("#send_code").next().html("");
        	                        clearInterval(msg_interval);
        	                    }
        	                }, 1000);
        	            }
        	            else if(msg.code==400){
        	                $("#send_code").attr("disabled",false);
        	                $("#mobile_code_info").html("<a style='color:red'>!</a> 该手机号已注册，请换用其他手机号验证或用该手机号<a href='#' style='color:blue' onclick='javascript:logIn();'>登录</a>");
        	                $("#mpphonenum").siblings(".tipin").css("color", "#999");
        	                $("#send_code").removeClass("quick_register_mobile_unuse");
        	                $("#mpphonenum").siblings(".tipin").css("color", "#999")
        	                //$("#mpphonenum").siblings("p").hide();
        	            }
        	            else{
        	                $("#send_code").removeClass("quick_register_mobile_unuse");
        	            }
        	            clearInterval(send_code_msg);
        	            $("#send_code").removeClass("quick_register_mobile_loading");
        	            $("#send_code").val("发送验证码");
        	        }
        	    });
        	}
})
$("#codeBtn").live("click",function(){
	if($(this).attr("disabled")=="disabled"){
		return false;
	}

	//手机发发送验证码,检测手机号码是否可用
        	//TODO 检查手机号码合法性
        	var this_=$(this)

        	if($("#phoneNum").val().length == 0)
        	{
        		if($("#send_code").parent().find("label.error").length<=0){
        			$("#send_code").parent().append('<label for="mpphonenum" class="error">请输入手机号码</label>');
        		}else{
        			$("#send_code").parent().find("label.error").css("display", "block");
        		}
	
        	}else{
        	var send_code_msg = setInterval(function(){
        	           var now_val = $("#send_code").val();
        	           switch(now_val){
        	               case "发送验证码":now_val="Loading";break;
        	               case "Loading":now_val="Loading.";break;
        	               case "Loading.":now_val="Loading..";break;
        	               case "Loading..":now_val="Loading...";break;
        	               case "Loading...":now_val="Loading";break;
        	               default :"Loading";break;
        	           }
	
        	           $("#send_code").val(now_val);
        	    }, 200);
        	    this_.addClass("quick_register_mobile_loading");
        	    this_.attr("disabled",true);
        	    this_.addClass("quick_register_mobile_unuse");
				var mobile = $('#phoneNum').val();
        	    $.ajax({
        	        type: "post",
        	        dataType: "json",
        	        url:"/ajaxcheck/sendMobileCode",
        	        data:{'mobile':mobile, 'type' : 'mobile_card'},
        	        complete :function(){
        	        },
        	        success: function(msg){
        	            $("#mobile_code_info1").html(msg.msg);
						if(msg.code==200){
        	                $("#mpphonenum").siblings(".tipin").css("color", "#999");
        	                var now_time = 30;
        	                var msg_interval = setInterval(function(){
        	                    this_.addClass('huise').html((now_time--) +"秒后点击重新发送");
        	                    if(now_time == -1){
        	                        this_.attr("disabled",false);
        	                        this_.removeClass("quick_register_mobile_unuse");
        	                        this_.removeClass('huise').html("重新发送");
        	                        clearInterval(msg_interval);
        	                    }
        	                }, 1000);
        	            }
        	            else if(msg.code==400){
        	                $("#send_code").attr("disabled",false);
        	                $("#mobile_code_info").html("<a style='color:red'>!</a> 该手机号已注册，请换用其他手机号验证或用该手机号<a href='#' style='color:blue' onclick='javascript:logIn();'>登录</a>");
        	                $("#mpphonenum").siblings(".tipin").css("color", "#999");
        	                $("#send_code").removeClass("quick_register_mobile_unuse");
        	                $("#mpphonenum").siblings(".tipin").css("color", "#999")
        	                //$("#mpphonenum").siblings("p").hide();
        	            }
        	            else{
        	                $("#send_code").removeClass("quick_register_mobile_unuse");
        	            }
        	            clearInterval(send_code_msg);
        	            $("#send_code").removeClass("quick_register_mobile_loading");
        	            $("#send_code").val("发送验证码");
        	        }
        	    });
			}
})
$("#gologinbtn").click(function() {
	    lyBox.hide(lyBox);
	    setCaptcha();
	    $("input[type='text'],input[type='password']").placeholder();
	    $("#yellow-b-box").slideDown(500);
	    return false;
	});
$(".share").click(function(){
	$('.share_list').show();return false;
})



//右侧广告栏浮动

nambo_float($(".project-right"), 150, true);
	
	function nambo_float(obj_, bottom_, left_flag_){//设置浮动
		  var obj = obj_;
		  var bottom = bottom_;
		  var left_flag = left_flag_;
		  var float_top = obj.offsetTop;
		  var title_height = $(".quicknavwrap").height()
		  var guide_height = $(".quickrelease-header-wrap").height()
		 // var searchbox_height = $(".guideserchbox").height()
		  var footer_height =  100+ 30 + $(".tag-list").height()
		  
		  
	 // var float_left = obj.parentNode.offsetLeft?obj.parentNode.offsetLeft:0;
	 //365
	  window.onscroll = function(e){
		e = e?e:event;
		var scrollHeight = document.documentElement.scrollHeight||document.body.scrollHeight;
		var winHeight = document.documentElement.scrollTop || document.body.scrollTop;
		if(obj.height() > $(window).height())
		{
			var margintop = title_height + guide_height + 10 + 10 + obj.height()- $(window).height()
			var midtop = $(window).height()-obj.height() - 30
			var t = scrollHeight - footer_height  - $(window).height() - $(".tag-list").height() +16
			var newtop = t - winHeight + $(window).height()-obj.height() 
		}else{
			var margintop = title_height + guide_height + 10 
			var midtop = -10
			var t = scrollHeight - footer_height  - obj.height() - $(".tag-list").height() +16
			var newtop = scrollHeight - footer_height   - $(".tag-list").height() +16 - winHeight -obj.height() 
		}
		
		//var tep = $(".project-list-main").offset().top + $(".project-list-main").height()
		//var t = scrollHeight - footer_height  - $(window).height() - $(".tag-list").height() +16
	  // var newtop = t - winHeight + $(window).height()-obj.height() 
	  // $("#txt").text(scrollHeight+"||"+winHeight+"|"+margintop+"|"+$(".tag-list").height())
		   if($(".project-left").height() > obj.height())
			  {
				    
					   if(winHeight < margintop)
					   {
						   obj.css("position", "static")
						   obj.css("margin-left", "10px")
					   }
					   else if(winHeight >= margintop && winHeight<t)
					   {
						   obj.css("position", "fixed")
						   obj.css("top", midtop)
						   obj.css("margin-left", "790px")
					   }else {
						  
						   obj.css("position", "fixed")
						   obj.css("top", newtop)
						   obj.css("margin-left", "790px")
						 
					   }
					
			  }
		  }
	}
/*$("#show_big_pic img").each(function () {
		DrawImage(this, 515, 415);
	});*/

/*$("body").click(function(){
	$(".share_list").hide();
	return false;
})*/
</script>
