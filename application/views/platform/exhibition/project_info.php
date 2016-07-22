<title><?php echo $title!="" ? $title : "一句话|投资赚钱好项目，一句话的事。";?></title>
<meta name="Keywords" content="<?php echo $keywords!="" ? $keywords : "投资，赚钱，好项目，一句话，投资赚钱";?>" />
<meta name="description" content="<?php echo $description!="" ? $description : "一句话平台专业、快速、精准匹配帮助加盟商与投资者达成真实、诚信的招商投资互动。投资好项目赚钱一句话的事。";?>" />
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("zhanhui.css")?>
<?php echo URL::webcss("common_plugIn.css")?>
<?php echo URL::webjs("jquery.validate.js")?>
<?php echo URL::webjs("commom_global_zhanhui.js")?>
<?php echo URL::webjs("zhanhui.js")?>
<?php echo URL::webjs("platform/home/login_fu.js")?>
</head>
<body>

<div class="headerwrap">
        <div class="header header3">
            <h1>千元开业红包太少，怎么办？？？</h1>
            <p class="fontp">价值<span><?php echo isset($yhjInfo['project_coupon']) ? $yhjInfo['project_coupon'] : 0?></span>元的项目优惠券赠送活动开始了！</p>
            <p class="fontp fontp1">本期展会该项目派送<span><?php echo isset($yhjInfo['coupon_num']) ? $yhjInfo['coupon_num'] : 0?></span>个，目前还剩<span class="shengyu"><?php echo isset($yhjInfo['shengyu']) ? $yhjInfo['shengyu'] : 0?></span>个</p>
            <a href="javascript:;" class="lijibtn myget">立即领取</a>
        </div>
        <input type="hidden" value="<?php echo $project_id; ?>" id="project_id" />
        <input id="hongbao" type="hidden" value="2" />
        <input id="exhb_card" type="hidden" value="1" />
        <a href="<?php echo URL::website('');?>" class="returnyjh"><img src="<?php echo URL::webstatic('/images/zhanhui/returnyjh.png')?>"></a>
</div>

<div class="content projectDetail">
	<input type="hidden" value="<?php echo $reg_fu_platform_num?>" id="reg_fu_platform_num_id">
    <input type="hidden" value="<?php echo $reg_fu_user_num?>" id="reg_fu_user_num_id">
    <input type="hidden" value="<?php echo $reg_fu_platform_num?>" id="platform_num">
    <input type="hidden" value="<?php echo $reg_fu_user_num?>" id="user_num">
	<div class="bobaobox clearfix" >
        <?php if($bobao){?>
        <p class="bobao fl">成果播报：</p>
        <div class="bobaobox2" >
            <ul class="bobaolist fl" data-direction="horizontal" id="searchNews1" style="width:10000px">            	
            	<?php foreach($bobao as $v){?>
                <li><?php echo $v;?></li> 
                <?php }?>   
            </ul>
        </div>
        <?php }?>
    </div>
  	<div class="briefIntro jsbriefIntro clearfix">
		<div class="briefIntroLeft">
     		<h2><?php echo $forms['project_brand_name'];?></h2>
         	<p class="company"><?php echo $forms['company_name'];?> </p>
          	<p class="countDown">倒计时：</p>
          	<input id="countDown_time" type="hidden" value="<?php echo $forms['exhibition_end'];?>" />
       		<p class="exhibition">所属展会：<?php echo $forms['exhibition_name'];?></p>
          	<?php if(isset($forms['project_video']) && $forms['project_video']){?>
          	<div>
          		<p class="tip"><span>招商地区</span>
					<?php if(isset($forms['area']) && $forms['area']){                   			
                            $area='';
                            foreach ($forms['area'] as $v){
                                $area .= $v['pro_name'];
                                $area = $area.',';
                            }
                            $area = substr($area, 0,strlen($area)-1);
                            echo $area;                           
                   		}
                    ?>

				</p>
	         	<p class="tip"><span>投资金额</span><i><?php $monarr= common::moneyArr1(); echo arr::get($forms, 'project_amount_type') == 0 ? '无': $monarr[arr::get($forms, 'project_amount_type')];?></i><?php if($forms['project_amount_type'] == 1){echo '万以下';}elseif($forms['project_amount_type'] == 5){echo '万以上';}else{echo '万';}?><span class="second">意向加盟</span><i style="font-size: 36px; vertical-align: middle"><?php echo isset($yixiangNum) ? $yixiangNum : 0;?></i>人</p>
	           	<p class="contact">
	        		<span class="cellPhone">400 1015 908</span>
	           		<?php if(arr::get($arr_kefu_data, "customer_status",0)==2 && arr::get($arr_kefu_data,"customer_group_id")){?>
          			<a href="javascript:void(0)" class="buttonYellow talk" >在线交流</a>
          		<?php }?>

	           		<a id="zx_<?php echo $forms['outside_id']?>_<?php echo $com_user_id;?>_1" href="javascript:void(0)" class="buttonRed consult" rel="nofollow">我要咨询</a>
	          	</p>
	          	<p class="video">
	          	         		<span class="videoMain">
	          	            	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="307" height="194" id="flashrek2">
						       		<param name="movie" value="<?php echo URL::webstatic('flash/videoplayer.swf')?>">        
							       	<param name="quality" value="high">        
							      	<param name="wmode" value="transparent">        
							      	<param name="allowScriptAccess" value="always">
							      	<param name="allowFullScreen" value="true" />							      	        
							     	<embed src="<?php echo URL::webstatic('flash/videoplayer.swf?url='.URL::movieurl($forms['project_video']))?>" allowFullScreen="true" allowscriptaccess="always" id="fileId4" wmode="transparent" width="307" height="194" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash">
						      	</object>
	          	            	</span>
	          	            	<span class="moreInfo">关于项目其他信息，去<a href="<?$url = urlbuilder::project($forms['outside_id']); echo $url;?>" target="_blank">项目官网>></a></span>
	          	</p>
          	</div>
          	<?php }else{?>
          	<div>
          		<p class="tip"><span>投资金额</span><i><?php $monarr= common::moneyArr1(); echo arr::get($forms, 'project_amount_type') == 0 ? '无': $monarr[arr::get($forms, 'project_amount_type')];?></i><?php if($forms['project_amount_type'] == 1){echo '万以下';}elseif($forms['project_amount_type'] == 5){echo '万以上';}else{echo '万';}?></p>
          		
          		<p class="tip"><span>招商地区</span>
					<?php if(isset($forms['area']) && $forms['area']){                   			
                            $area='';
                            foreach ($forms['area'] as $v){
                                $area .= $v['pro_name'];
                                $area = $area.',';
                            }
                            $area = substr($area, 0,strlen($area)-1);
                            echo $area;                           
                   		}
                    ?>
				</p>
				<p class="tip"><span>所属行业</span><?php if(isset($forms['pro_industry']['industry_name'])){echo $forms['pro_industry']['industry_name'];}else{echo "暂无";}?></p>
				<p class="tip"><span>招商形式</span>
					<?php if(isset($forms['project_model']) && $forms['project_model']){
                   		$lst = common::businessForm();
		                if(count($forms['project_model'])){
		                    $comodel_text='';
		                    foreach ($forms['project_model'] as $v){
		                        $comodel_text=$comodel_text.$lst[$v].',';
		                    }
		                    $xingshi= substr($comodel_text,0,-1);
		
		                        echo $xingshi;
		
		                }else{echo '暂无';}}?>
				</p>
				<p class="tip"><span>意向加盟</span><var><?php echo isset($yixiangNum) ? $yixiangNum : 0;?></var><em style="margin-left:5px;">人</em></p>
	           	<p class="contact" style="margin-top: 25px">
	        		<span class="cellPhone">400 1015 908</span>
	           		
	          	</p>
	          	<p style="margin-top:10px;"><?php if(arr::get($arr_kefu_data, "customer_status",0)==2 && arr::get($arr_kefu_data,"customer_group_id")){?>
          			<a href="javascript:void(0)" class="buttonYellow talk" >在线交流</a>
          		<?php }?><a id="zx_<?php echo $forms['outside_id']?>_<?php echo $com_user_id;?>_1" href="javascript:void(0)" class="buttonRed consult" rel="nofollow" style="margin-left: 10px;">我要咨询</a></p>
	          	<p style="margin-top:35px;"><span class="moreInfo">关于项目其他信息，去<a href="<?$url = urlbuilder::project($forms['outside_id']); echo $url;?>" target="_blank">项目官网>></a></span></p>
          	</div>
          	<?php }?>
      	</div>  
      	<div class="briefIntroRight">
      		<p class="contact" style="text-align:right;">
          		<span class="cellPhone">400 1015 908</span>
          		<?php  if(arr::get($arr_kefu_data, "customer_status",0)==2 && arr::get($arr_kefu_data,"customer_group_id")){?>
          			<a href="javascript:void(0)" class="buttonYellow talk" >在线交流</a>
          		<?php  }?>
           		
        		<a id="zx_<?php echo $forms['outside_id']?>_<?php echo $com_user_id;?>_1" href="javascript:void(0)" class="buttonRed consult" rel="nofollow">我要咨询</a>
         	</p>
         	<div class="slide">
         		<div class="slideMain">            		
            		<ul class="clearfix">
            			<?php if(isset($forms['project_zhanshi']) && $forms['project_zhanshi']){?>
            			<?php foreach($forms['project_zhanshi'] as $k => $v){?>
	            		<li <?php if($k != 0){?>style="display:none;"<?php }?>>
	            			<h4><?=isset($v['project_zhanshi_pic_name']) ? $v['project_zhanshi_pic_name'] : $forms['project_brand_name'].'产品图' ?></h4>
		            		<a href="#">
	            				<img src="<?=isset($v['project_zhanshi_pic']) ? URL::imgurl(str_replace('s_','b_',$v['project_zhanshi_pic'])) : '';?>" alt="<?php echo $forms['project_brand_name'];?>" width="404" height="325" onerror="this.src='<?=isset($v['project_zhanshi_pic']) ? URL::imgurl($v['project_zhanshi_pic']) : '';?>'">
	            				<?php if(isset($v['project_zhanshi_shuoming']) && $v['project_zhanshi_shuoming']){?>
	            				<span><?php echo $v['project_zhanshi_shuoming'];?></span>
	            				<span class="bg"></span>
	            				<?php }?>
	            			</a>
	            			<div class="imgleft" style="cursor: URL('<?php echo URL::webstatic('images/platform/project_home/ph_products_mouse_ico_2.cur');?>'),auto;"></div>
	            			<div class="imgright" style="cursor: URL('<?php echo URL::webstatic('images/platform/project_home/ph_products_mouse_ico.cur');?>'),auto;"></div>
            			</li>
            			<?php }?>
            			<?php }?>
            		</ul>
            	</div>
            	<div class="sldieTool clearfix">
            		<div class="leftBtn"></div>
                  	<div class="thumb">
            			<ul>
            				<?php if(isset($forms['project_zhanshi']) && $forms['project_zhanshi']){?>
            				<?php foreach($forms['project_zhanshi'] as $k => $v){?>            				
            				<li <?php if($k == 0){?>class="fc"<?php }?>><img src="<?=isset($v['project_zhanshi_pic']) ? URL::imgurl($v['project_zhanshi_pic']) : '';?>" style="width:100px; height:80px;" alt="<?php echo $forms['project_brand_name'];?>"></li>                          	
            				<?php }?>
            				<?php }?>
            			</ul>
                   	</div>
            		<div class="rightBtn"></div>
           		</div>           		
         	</div>
      	</div>			
  	</div>  	
  	<div class="contentMain" id="content1">
  		<ul class="navgation">
  			<?php if($forms['project_advantage'] && (isset($forms['project_advantage_img']) && $forms['project_advantage_img'])){?>
      		<li class="fc"><a href="#content1">项目优势</a></li>
      		<?php }?>
      		<?php if($forms['project_running'] || (isset($forms['project_running_img']) && $forms['project_running_img'])){?>
          	<li><a href="#content2">运营操作</a></li>
          	<?php }?>
          	<?php if($forms['expected_return'] && (isset($forms['expected_return_img']) && $forms['expected_return_img'])){?>
          	<li><a href="#content3">预期收益</a></li>
          	<?php }?>
          	<?php if($forms['preferential_policy'] && (isset($forms['preferential_policy_img']) && $forms['preferential_policy_img'])){?>
          	<li><a href="#content4">优惠政策</a></li>
          	<?php }?>
          	<?php if($forms['company_strength'] && (isset($forms['company_strength_img']) && $forms['company_strength_img'])){?>
          	<li><a href="#content5">公司实力</a></li>
          	<?php }?>
      	</ul>
		
		<?php if($forms['project_advantage'] && (isset($forms['project_advantage_img']) && $forms['project_advantage_img'])){?>
     	<dl class="contentText" >
      		<dd><?php echo $forms['project_advantage'];?></dd>
         	<dd class="img"><?php if(isset($forms['project_advantage_img']) && $forms['project_advantage_img']){?><img src="<?=(isset($forms['project_advantage_img']) && $forms['project_advantage_img']) ? URL::imgurl(str_replace('s_','b_',$forms['project_advantage_img'])) : '';?>" onload="javascript:DrawImage(this,690,417)"/><?php }?></dd>
       	</dl>
       	<?php }?>
       	<?php if($forms['project_running'] || (isset($forms['project_running_img']) && $forms['project_running_img'])){?>
       	<dl class="contentText" id="content2">
       		<dt>运营操作</dt>
           	<dd><?php echo $forms['project_running'];?></dd>
           	<dd class="img"><?php if(isset($forms['project_running_img']) && $forms['project_running_img']){?><img src="<?=(isset($forms['project_running_img']) && $forms['project_running_img']) ? URL::imgurl(str_replace('s_','b_',$forms['project_running_img'])) : '';?>" onload="javascript:DrawImage(this,690,417)"/><?php }?></dd>
      	</dl>
      	<?php }?>
      	<?php if($forms['expected_return'] && (isset($forms['expected_return_img']) && $forms['expected_return_img'])){?>
       	<dl class="contentText" id="content3">
      		<dt>预期收益</dt>
          	<dd><?php echo $forms['expected_return'];?></dd>
         	<dd class="img"><?php if(isset($forms['expected_return_img']) && $forms['expected_return_img']){?><img src="<?=(isset($forms['expected_return_img']) && $forms['expected_return_img']) ? URL::imgurl(str_replace('s_','b_',$forms['expected_return_img'])) : '';?>" onload="javascript:DrawImage(this,690,417)"/><?php }?></dd>
      	</dl>
      	<?php }?>
      	<?php if($forms['preferential_policy'] && (isset($forms['preferential_policy_img']) && $forms['preferential_policy_img'])){?>
       	<dl class="contentText" id="content4">
       		<dt>优惠政策</dt>
        	<dd><?php echo $forms['preferential_policy'];?></dd>
         	<dd class="img"><?php if(isset($forms['preferential_policy_img']) && $forms['preferential_policy_img']){?><img src="<?=(isset($forms['preferential_policy_img']) && $forms['preferential_policy_img']) ? URL::imgurl(str_replace('s_','b_',$forms['preferential_policy_img'])) : '';?>" onload="javascript:DrawImage(this,690,417)"/><?php }?></dd>
      	</dl>
      	<?php }?>
      	<?php if($forms['company_strength'] && (isset($forms['company_strength_img']) && $forms['company_strength_img'])){?>
       	<dl class="contentText" id="content5">
       		<dt>公司实力</dt>
          	<dd><?php echo $forms['company_strength'];?></dd>
         	<dd class="img"><?php if(isset($forms['company_strength_img']) && $forms['company_strength_img']){?><img src="<?=(isset($forms['company_strength_img']) && $forms['company_strength_img']) ? URL::imgurl(str_replace('s_','b_',$forms['company_strength_img'])) : '';?>" onload="javascript:DrawImage(this,690,417)"/><?php }?></dd>
       	</dl>
       	<?php }?>
       	<p class="contact" style="text-align: center">
      		<span class="cellPhone">400 1015 908</span>
       		<?php if(arr::get($arr_kefu_data, "customer_status",0)==2 && arr::get($arr_kefu_data,"customer_group_id")){?>
          			<a href="javascript:void(0)" class="buttonYellow talk" >在线交流</a>
          		<?php }?>
         	<a id="zx_<?php echo $forms['outside_id']?>_<?php echo $com_user_id;?>_1" href="javascript:void(0)" class="buttonRed consult" rel="nofollow">我要咨询</a>
       	</p>
      	<h2 class="h4title">查看此项目的人还查看了</h2>
       	<ul class="moreProjectList clearfix">
       		<?php if(isset($tuijian) && $tuijian){?>
       		<?php foreach($tuijian as $k => $v){?>
       		<li <?php if($k == 0){?>class="first"<?php }?>>
       			<a href="<?$url = urlbuilder::exhbProject($v['project_id']); echo $url;?>">
              		<img width="150" height="120" src="<?php echo isset($v['project_logo']) ? URL::imgurl($v['project_logo']) : '';?>" onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo isset($v['project_brand_name']) ? URL::imgurl($v['project_brand_name']) : '';?>">
                	<h4><?php echo isset($v['project_brand_name']) ? $v['project_brand_name'] : '';?></h4>
               		<span><?php echo isset($v['advertisement']) ? $v['advertisement'] : '';?></span>
              	</a>
          	</li>
          	<?php }?>
          	<?php }?>          	
      	</ul>
  	</div>
</div>
<div class="poperbox projectDetail jsfuceng">
	<div class="briefIntro fuceng clearfix">
	<h2 class="fl"><?php echo $forms['project_brand_name'];?></h2>
	<p class="contact fl" style="text-align: right; width:310px;">
  		<span class="cellPhone">400 1015 908</span>
   		<?php if(arr::get($arr_kefu_data, "customer_status",0)==2 && arr::get($arr_kefu_data,"customer_group_id")){?>
          			<a href="javascript:void(0)" class="buttonYellow talk" >在线交流</a>
          		<?php  }?>
		<a id="zx_<?php echo $forms['outside_id']?>_<?php echo $com_user_id;?>_1" href="javascript:void(0)" class="buttonRed consult" rel="nofollow">我要咨询</a>
	</p>
	</div>
</div>
<input type="hidden" value="<?=isset($open) ? $open : 0;?>" id="open"/>


<script type="text/javascript">
  $.ajaxsubmit("/platform/ExhbProject/exhbProClick",{"project_id":$("#project_id").val()})

	/*
	图片轮播
	*/
	var outTime=4000;
	var interval;
	var num = 0;
	var currentindex=0;
	var cur_page = 1
	
	var page_num = 3
	var length = $(".thumb ul li").length;
	var total_page = Math.ceil(length/page_num)
	
	//init()
	gotoSlide(0)
	
	function init()
	{
		if(length <= page_num) 
		{
			$(".leftBtn").css("display","none");
			$(".rightBtn").css("display","none");
		}
	}
	interval=setInterval(interFunc,outTime);
	$(".thumb ul li").click(function()
	{
		var _index = $(".thumb li").index(this)
		
		gotoSlide(_index)
		initInterval()
	})
	
	$(".imgleft,.leftBtn").click(function()
	{
		
		currentindex -= 1
		if(currentindex < 0) 
		{
			currentindex = length-1
			
		}
		
		gotoSlide(currentindex)
		initInterval()
	})
	$(".imgright,.rightBtn").click(function()
	{
		currentindex += 1
		if(currentindex > length-1)
		{
			currentindex = 0
		}
		
		gotoSlide(currentindex)
		initInterval()
	})
	
	function initInterval()
	{
		
		clearInterval(interval);
		interval=setInterval(interFunc,outTime)
		
	}
	function interFunc()
	{
		currentindex += 1
		
		if(currentindex >= length) currentindex = 0
		
		gotoSlide(currentindex)
	}
	function gotoPage(_page)
	{
		
		var margin = -354*(_page-1)+"px"
		//$(".ph_products_img_tool_center ul").css("margin-top", margin)
		$(".thumb ul").animate({"margin-left":margin})
	}
	
	function gotoSlide(i) {
		
		currentindex = i
		cur_page = Math.floor(currentindex/page_num)+1
		
		gotoPage(cur_page)
		
		for (j=0; j< length; j++){
			if (j == currentindex)
			{
				
				$(".thumb li:eq("+j+")").attr("class","fc");
				$(".slideMain li:eq("+j+")").fadeIn()
				
				//$(".ph_products_img_container div img").eq(j).css("display","block");
			}
			else
			{
				$(".thumb li:eq("+j+")").attr("class","");
				$(".slideMain li:eq("+j+")").css("display","none");
				
				//$(".ph_products_img_container div img").eq(j).css("display","none");
			}
		}
	}
	
	
	function DrawImage(ImgD,FitWidth,FitHeight){  
		 var image=new Image();  
		 image.src=ImgD.src;  
		 if(image.width>0 && image.height>0){  
			 if(image.width/image.height >= FitWidth/FitHeight){  
				 if(image.width>FitWidth){  
					 ImgD.width=FitWidth;  
					 ImgD.height=(image.height*FitWidth)/image.width;  
				 }else{  
					 ImgD.width=image.width;   
					ImgD.height=image.height;  
				 }  
			 } else{  
				 if(image.height>FitHeight){  
					 ImgD.height=FitHeight;  
					 ImgD.width=(image.width*FitHeight)/image.height;  
				 }else{  
					 ImgD.width=image.width;   
					ImgD.height=image.height;  
				 }   
			}  
		  }  
 	}  
	
</script>
<?php /*?>
<script type="text/javascript" src="http://res.webim-aone.tonglukuaijian.com/webim/js/panel.js" charset="utf-8"></script>
<?php  */?>
<script type="text/javascript" src="http://10.200.40.13/webim/js/panel.js" charset="utf-8"></script>
<?php if($guoqi){?>
<script type="text/javascript">
//展会过期弹层
$(function(){
window.MessageBox({
    title:"生意街网站提示您",
    content:"<p>本项目的展会已过期</p>",
    btn:"ok",
    width:450,
    target:"new",
    callback:function(){
			window.location.href=window.$config.siteurl+"zhanhui/";
       }
	});
})
</script>
<?php }?>
<script type="text/javascript">
  //招商热线 
  var phoneNum = ""; 
  var pnNum = ""; 
  //var show_Way="useButton";
  <?php /*?>
  <?=arr::get($arr_kefu_data, "customer_group_id")?>
  <?php */?>
 var show_OrNot="not"; 
  var webim_tonglu_pri = null; 
  if(webim_tonglu_pri==null){ 
    var webim_tonglu_pri = { 
    "cid" : "T1UB3uDaNuNv", 
    "ckey" : "4e981c702b95155a7c2f643e1f69cf83", 
    "timestamp" : "20130822132524", 
    "groupId" : "<?=arr::get($arr_kefu_data, "customer_group_id")?>" 
    }; 
  }; 

var custom_param = null; 

$(function(){
  var open = $("#open").val();
  if(open == 1){
    setTimeout("btnClick()",5000);
  }
})
//客服开始
$(".talk").live("click",function(){
  //btnClick();
 // alert(11);
	setTimeout("btnClick()",1000);
  var project_id  = $("#project_id").val();
  $.post("/platform/ajaxcheck/ExhbTj",{"project_id":project_id,"type":2},function(){})
})

//客服结束
  $(window).scroll(function(){
    if($(this).scrollTop()>=$(".jsbriefIntro").offset().top){
      $(".jsfuceng").show();
    }
    else{
      $(".jsfuceng").hide();
    }
  });
//改变锚点的高度
$(".navgation li a").click(function(){
  setTimeout(function(){
    $(window).scrollTop($(window).scrollTop()-55)
},30)
  
})  

</script>

