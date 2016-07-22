<?php echo URL::webjs("jquery-1.4.2.min.js")?>
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("common.css")?>

	
	<div class="zhanhucontent">
		<div class="statebox">
			<span class="statespan">账户状态：</span>
			<?php if($isValidMobile){?>
			<span class="yanzheng"><img class="icon_posi" src="<?php echo URL::webstatic('/images/company_center/icon_ok_03.png')?>">手机已验证</span>
			<?php }else{?>
			<span class="yanzheng"><img class="icon_posi" src="<?php echo URL::webstatic('/images/company_center/icon_06.png')?>"><a href="<?php if($isCompany){echo URL::website('../company/member/valid/mobile');}else{echo URL::website('../person/member/valid/mobile');}?>">手机未验证</a></span>
			<?php }?>
			<?php if($isValidMail){?>
			<span class="yanzheng"><img class="icon_posi" src="<?php echo URL::webstatic('/images/company_center/icon_03.png')?>">邮箱已验证</span>
			<?php }else{?>
			<span class="yanzheng"><img class="icon_posi" src="<?php echo URL::webstatic('/images/company_center/icon_04.png')?>"><a href="<?php if($isCompany){echo URL::website('/company/member/basic/setEmail');}else{echo URL::website('/person/member/basic/setEmail');}?>">邮箱未验证</a></span>
			<?php }?>
			<?php if($user_type == 1){?>
					<?php if($validCerts==3){?>
						<span class="yanzheng"><img src="<?php echo URL::webstatic("images/company_center/icon_09.png")?>" class="icon_posi"></span><a class="no" href="<?php echo URL::website("/company/member/basic/comCertification")?>">资质未认证</a>
					<?php }elseif($validCerts==0){?>
						<span class="yanzheng"><img src="<?php echo URL::webstatic("images/company_center/icon_09.png")?>" class="icon_posi"></span><a class="no" href="<?php echo URL::website("/company/member/basic/comCertification")?>">资质验证中</a>
					<?php }elseif($validCerts==2){?>
						<span class="yanzheng"><img src="<?php echo URL::webstatic("images/company_center/icon_09.png")?>" class="icon_posi"></span><a class="no" href="<?php echo URL::website("/company/member/basic/comCertification")?>">资质验证失败</a>
					<?php }else{?>
						<span class="yanzheng"><img class="icon_posi" src="<?php echo URL::webstatic("images/company_center/icon_ok_06.png")?>" /></span>资质已认证
					<?php }?>
			<?php }elseif($user_type == 2){?>
					<?php if($identificationCard_status==0){?>
					<span class="yanzheng"><img src="<?php echo URL::webstatic("images/company_center/icon_09.png")?>" class="icon_posi"></span>
					<a href="<?php echo URL::website("/person/member/basic/identificationcard");?>">实名未认证</a>
					<?php }elseif($identificationCard_status==1){?>
					<span class="yanzheng"><img src="<?php echo URL::webstatic("images/company_center/icon_09.png")?>" class="icon_posi"></span><a href="<?php echo URL::website("/person/member/basic/identificationcard");?>">实名认证中</a>
					<?php }elseif($identificationCard_status==2){?>
					<span class="yanzheng"><img class="icon_posi" src="<?php echo URL::webstatic("images/company_center/icon_ok_06.png")?>" /></span>实名已认证
					<?php }elseif($identificationCard_status==3){?>
					<span class="yanzheng"><img src="<?php echo URL::webstatic("images/company_center/icon_09.png")?>" class="icon_posi"></span><a class="no" title="重新认证" href="<?php echo URL::website("/person/member/basic/identificationcard?status=-1");?>">未通过实名认证</a>
					<?php }?>
			<?php }?>
			<?php if($user_type == 2 && $renzheng_status != 1 && $renzheng_status != 2){?>
			<a href="<?php echo URL::website('/quick/User/UserUpgrade');?>" class="red fr qyrz">去企业认证</a>
			<?php }elseif($user_type == 2 && $renzheng_status == 1){?>
			<a class="jhuise fr qyrz" style="margin-top:0px;">企业认证审核中</a>
			<?php }?>
		</div>
		
		<?php if($list){?>
		<ul class="projectlist">
			
			<?php foreach($list as $v){?>
			<li>
				<input type="hidden" class="project_id" value="<?php echo $v['project_id'];?>"/>
				<input type="hidden" class="project_reason" value="<?php echo $v['project_reason'] ? $v['project_reason'] : '管理员禁止';?>" />
				<p class="ptitle"><a href="javascript:;" class="yellow updatetitme fr">更新发布时间</a>更新时间：<i class="mr50"><?php echo date('Y-m-d H:i:s',$v['project_updatetime'] ? $v['project_updatetime'] : $v['project_addtime']);?></i> 浏览次数：<i><b><?php echo $v['project_pv_count'];?></b></i></p>
				<div class="clearfix">
					<a href="<?php echo urlbuilder::qucikProHome($v['project_id']);?>" class="fl projectlistimgbox">
						<img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?php echo $v['project_brand_name'];?>" title="<?php echo $v['project_brand_name'];?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')">
					</a>
					<div class="fl listcontent">
						<h3><a href="<?php echo urlbuilder::qucikProHome($v['project_id']);?>"><?php echo mb_substr($v['project_title'],0,18);?></a></h3>
						<p class="titleauxiliary"><?php echo mb_substr($v['project_introduction'],0,25);?></p>
						<p class="industrybox mt15"><span>行业分类：</span><?php echo $v['industry_name'];?><span class="ml40">投资金额：</span><?php $monarr= common::moneyArr(); echo arr::get($v, 'project_amount_type') == 0 ? '无': $monarr[arr::get($v, 'project_amount_type')];?> </p>
						<p class="industrybox"><span>招商地区：</span>
						<?php if(isset($v['area']) && $v['area']){                   			
                            $area='';
                            foreach ($v['area'] as $val){
                                $area .= $val['pro_name'];
                                $area = $area.',';
                            }
                            $area = substr($area, 0,strlen($area)-1);
                            echo $area;                           
                   		}
                        ?>
                        </p>
					</div>
				<?php if($v['project_status'] == 1){?>
				<div class="fl projectstate">状态【<var>审核中</var>】<p>留言数：<b style="color:red;font-weight:bold;"><a href="<?=urlbuilder::QuickMessage(arr::get($v, "project_id"))?>" style="color:red;font-weight:bold;"><?php echo $v['liuyan_count'];?></a></b>&nbsp;&nbsp;&nbsp;<span style="color:#005AFF;"><a href="<?=urlbuilder::QuickMessage(arr::get($v, "project_id"))?>">去查看>></a></span></p></div>
				<?php }elseif($v['project_status'] == 2){?>
				<div class="fl projectstate">状态【<var>已审核</var>】<p>留言数：<b style="color:red;font-weight:bold;"><a href="<?=urlbuilder::QuickMessage(arr::get($v, "project_id"))?>" style="color:red;font-weight:bold;"><?php echo $v['liuyan_count'];?></a></b>&nbsp;&nbsp;&nbsp;<span style="color:#005AFF;"><a href="<?=urlbuilder::QuickMessage(arr::get($v, "project_id"))?>">去查看>></a></span></p></div>
				<?php }elseif($v['project_status'] == 3){?>
				<div class="fl projectstate">状态【<var class="redfont">审核不通过</var>】<p class="mt10"><a href="javascript:;" class="checkreason" date-msg="未通过原因">查看原因>></a></p><p>留言数：<b style="color:red;font-weight:bold;"><a href="<?=urlbuilder::QuickMessage(arr::get($v, "project_id"))?>" style="color:red;font-weight:bold;"><?php echo $v['liuyan_count'];?></a></b>&nbsp;&nbsp;&nbsp;<span style="color:#005AFF;"><a href="<?=urlbuilder::QuickMessage(arr::get($v, "project_id"))?>">去查看>></a></span></p></div>
				<?php }?>				
				<div class="fl afterdelbox">
				<?php if($v['project_status'] != 2){?>	
				<a class="afterabtn" href="<?php echo urlbuilder::qucikEditPro($v['project_id']);?>">修改</a>
				<?php }?>
				<a class="delabtn" href="javascript:;">删除</a></div>
				</div>
			</li>	            
			<?php }?>					
		</ul>
		<div class="ryl_search_result_page"><?=$page?></div>
		<?php }else{?>	
		<div class="noshenyi">你还没有发布过生意信息，快去发布属于你的生意吧！</div>
		<a href="<?php echo urlbuilder::qucikAddPro();?>" class="yellow noshenyia">免费发布生意</a>
		<?php }?>
	</div>
	<div class="projectexplain">
			<h3>说明：</h3>
			<p class="mt15">1、更新发布时间：相当于新发一条信息，在按时间排序的情况下信息将靠前显示；</p>			
		</div>

<script type="text/javascript">
	
	// 删除项目
	$(".delabtn").live("click",function(){
		
		_this = $(this)
		var index=$(this).parents("li").index();
		//_this.parent().parent().parent().remove();
		window.MessageBox({
			title:"一句话网站提示您",
			content:"<p>您确定要删除吗？</p>",
			btn:"ok cancel",
			callback:function(){
				//alert("调用了回调函数");
				$.ajax({
					type: "post",
					dataType: "json",
					url: $config.siteurl+"quick/ajaxcheck/delQuickPro",
					data: 'project_id='+$(".projectlist li").eq(index).find(".project_id").val(),
					complete :function(){
						
					},
					success: function(msg){
						
						if(msg['status'] == 1)
						{
							_this.parent().parent().parent().remove();
							
						}else{
							alert("删除失败，请重新尝试！")
						}
					}
				});
				
				return false;
			},
			onclose:function(){
			  	return true;
			},
			target:"new"
		});
		
		return false;
	})
	
	
	//更新发布时间
	var _this 
	$(".updatetitme").live("click",function(){
		_this = $(this)
		var index=$(this).parents("li").index();
		
		$.ajax({
			type: "post",
			dataType: "json",
			url: $config.siteurl+"quick/Ajaxcheck/updateQuickProPublishTime",
			data: 'project_id='+$(".projectlist li").eq(index).find(".project_id").val(),
			complete :function(){
				
			},
			success: function(msg){
				var msg_tip = "更新失败！";
				
				
				if(msg['status'] == 1)
				{
					_this.next().html(msg['date'])
					msg_tip = "恭喜您今日更新成功!"
					
				}else if(msg['status'] == 2)
				{
					msg_tip = "一个招商加盟信息一天只有<font color='red'>1</font>次更新机会，请明天再来操作。"
				}
				 window.MessageBox({
					title:"一句话网站提示您",
					content:"<p>"+msg_tip+"</p>",
					btn:"ok",
					width:400,
					target:"new"
				}); 
			}
		});
		
		return false;
	})
	
	//未审核通过原因
	$(".checkreason").live("click",function(){
		//ajax   返回值
		var index=$(this).parents("li").index();
		
		var msg=$(".projectlist li").eq(index).find(".project_reason").val()
		
		    window.MessageBox({
			    title:"查看项目未通过原因",
			    content:"<p>"+msg+"</p>",
			    btn:"ok",
			    width:400,
			    target:"new"
			  });
	})
	
</script>
