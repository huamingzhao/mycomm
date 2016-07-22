    <?php echo URL::webcss("syrapp/index.css")?>
	<div class="syrapp">
		<div class="banner">
			<div class="bannerImg">
				<div class="bgImg bgImg1">生意人，让生意更好</div>
				<div class="bgImg bgImg2">生意人，中国第一个专注于生意人社交服务的APP。</div>
				<div class="bgImg bgImg3"></div>
				<div class="bgImg bgImg4"></div>
				<div class="bannerContentDiv">
				<div class="bannerContent">
					<div class="bannerPhone bannerIphone">
						<div class="bannerPhoneDiv">
							<ul class="clearfix">
								<li>
									<img src="<?php echo URL::webstatic('/images/syrapp/iphone1_03.png');?>" width="194" height="294" alt="生意头条"></li>
								<li>
									<img src="<?php echo URL::webstatic('/images/syrapp/iphone2_03.png');?>" width="194" height="294" alt="找生意"></li>
								<li>
									<img src="<?php echo URL::webstatic('/images/syrapp/iphone3_03.png');?>" width="194" height="294" alt="商友圈"></li>
								<li>
									<img src="<?php echo URL::webstatic('/images/syrapp/iphone4_03.png');?>" width="194" height="294" alt="聊天"></li>
							</ul>
						</div>
					</div>
					<a href="<?php echo URL::website('/app/businessman/android/businessman.apk');?>
						" style="top:382px; left: 550px;" target="_blank">
						<img src="<?php echo URL::webstatic('/images/syrapp/appImg_12.png');?>" width="221" height="68" alt="Android下载"></a>
					<ul id="bannerTool" class="bannerTool clearfix">
						<li class="fc"></li>
						<li></li>
						<li></li>
						<li></li>
					</ul>
				</div>
				</div>
			</div>
		</div>
		<ul class="pageContent clearfix">
			<li class="search"> <i class="icon_search"></i>
				<h4>找生意</h4>
				<p>快速寻找生意机会!</p>
			</li>
			<li class="news"> <i class="icon_news"></i>
				<h4>生意帮</h4>
				<p>生意有困难，找生意帮！</p>
			</li>
			<li class="group">
				<i class="icon_group"></i>
				<h4>商友圈</h4>
				<p>快速结识生意伙伴，交流生意经验！</p>
			</li>
			<li class="user">
				<i class="icon_user"></i>
				<h4>我的生意</h4>
				<p>宣传你的生意，快速找到你的客户！</p>
			</li>
		</ul>
	</div>
	<?php echo URL::webjs("jquery-1.4.2.min.js")?>
	<script type="text/javascript">
	
	function moveBannerImg(index){
		$(".bannerPhoneDiv").each(function(){
			$(this).find("ul").animate({"left":(-$(this).width()*index)+"px"}, 200);
		});
		$("#bannerTool li.fc").removeClass('fc');
		$("#bannerTool li:eq("+index+")").addClass('fc');
	}

	function autoPlay(){
		if($("#bannerTool").attr("onSwitch") != "true"){
			var index = $("#bannerTool li.fc").index() + 1;
			index = (index == $("#bannerTool li").length) ? 0 : index;
			moveBannerImg(index);
		}
		setTimeout(function(){
			autoPlay();
		}, 3000);
	}

	$(function(){
		$("#bannerTool li").click(function(){
			$("#bannerTool").attr("onSwitch", "true");
			setTimeout(function(){
				$("#bannerTool").attr("onSwitch", "false");
			}, 8000);
			moveBannerImg($(this).index());
		});
		autoPlay();
	});
	</script>