<!DOCTYPE html>
<html>
<!-- Copyright 2013 The Chromium Authors. All rights reserved.
     Use of this source code is governed by a BSD-style license that can be
     found in the LICENSE file. -->
<head>
	<title><?php echo $title!="" ? $title : "一句话|投资赚钱好项目，一句话的事。";?></title>
	<meta name="Keywords" content="<?php echo $keywords!="" ? $keywords : "投资，赚钱，好项目，一句话，投资赚钱";?>" />
	<meta name="description" content="<?php echo $description!="" ? $description : "一句话平台专业、快速、精准匹配帮助加盟商与投资者达成真实、诚信的招商投资互动。投资好项目赚钱一句话的事。";?>" />
  	<meta charset="utf-8">
</head>
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("zhanhui.css")?>
<?php echo URL::webcss("common_plugIn.css")?>
<?php echo URL::webjs("jquery-1.4.2.min.js")?>
<?php echo URL::webjs("commom_global.js")?>
<?php echo URL::webjs("zhanhui.js")?>
<?php echo URL::webjs("platform/home/login_fu.js")?>
<body>
<!--     <div class="headerwrap">
    <div class="header">
        <a href="javascript:;" class="returnyjh"><img src="http://static.myczzs.com/images/zhanhui/returnyjh.png"></a>
    </div>
</div> -->
    <div class="headerwrap1">
        <div class="header1">
            <div class="timewrap">
                <a href="javascript:;" class="mycz">
                    <img src="<?php echo URL::webstatic('images/zhanhui/mycz.png')?>">
                </a>
                <input id="user_type" type="hidden" value="<?php echo $user_type;?>" />
                <div class="timecontent">
                    <h1><?=arr::get($exhibitionInfo, 'exhibition_name', '')?></h1>
                    <div class="clearfix conwrap">
                    <img class="fl" src="<?php echo URL::imgurl(arr::get($exhibitionInfo, 'exhibition_logo_second', ''))?>" width="150" height="110" alt="<?=arr::get($exhibitionInfo, 'exhibition_name', '')?>">
                    <div class="fl confont">
                        <p>参展项目:<span><?=$projectCount?></span>个</p>
                        <p>参展人数:<span><?=$exhibitionInfo['exhibition_czrs']?></span>人</p>
                        <!--<p class="exhbend">展会圆满结束</p>-->
                    </div>
                    </div>
                    <input id="countDown_time" type="hidden" value="<?php echo date('Y/m/d,H:i:s', $exhibitionInfo['exhibition_end']);?>" />
                    <p class="timeicon redtimeicon"><?=$exhibitionInfo['exhibition_start_end']?></p>
                </div>
            </div>
            <input id="to_url" type="hidden" value="<?php echo $to_url;?>" />
            <p class="numhongbao">本期展会将派送<span><?=arr::get($exhibitionInfo, 'hongbao_num', '')?></span>个开业红包，目前现在还剩<span class="shengyu"><?=arr::get(arr::get($exhibitionInfo, 'hb_info', ''), 'shengyu', 0)?></span>个</p>
            <a href="javascript:;" class="mygethong">领取红包</a>
            <input id="hongbao" type="hidden" value="1" />
            <input id="exhb_id" type="hidden" value="<?=arr::get($exhibitionInfo, "exhibition_id"); ?>"/>
            <div class="main">
                <a href="javascript:;" class="on" >展会页</a>
                <?if($exhibitionCatalog){
                    foreach($exhibitionCatalog as $val) {   
                    ?>
                <a href="<?=  urlbuilder::catalogid($exhibitionInfo['exhibition_id'], $val['catalog_id'],'',1)?>"><?=$val['catalog_name']?></a>
                <?}}?>
            </div>
        </div>
        <a href="<?=url::website('/');?>" class="returnyjh"><img src="<?php echo URL::webstatic('images/zhanhui/returnyjh.png')?>"></a>
    </div>
	<div class="content">
        <input type="hidden" value="<?php echo $reg_fu_platform_num?>" id="reg_fu_platform_num_id">
    <input type="hidden" value="<?php echo $reg_fu_user_num?>" id="reg_fu_user_num_id">
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
        <h4 class="h4title">知名项目</h4>
        <ul class="zmtable clearfix">
            <?if($famousProject) {foreach($famousProject as $val){?>
            <li><a href="<?=urlbuilder::exhbProject($val['project_id'])?>"><img width="131" height="105" onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?=URL::imgurl($val['project_logo'])?>" alt="<?=$val['project_brand_name']?>"></a></li>
            <?}}?>
           </ul>
        <h4 class="h4title">火热开展中</h4>
        <?if($now_show) {
                foreach($now_show as $val) {?>
        <div class="speciallistbox clearfix ">
        	<div class="fl speciallist">
        		<h3><a href="<?=urlbuilder::exhbInfo($val['exhibition_id'])?>" target="_blank"><?=$val['exhibition_name']?></a></h3>
                        <p class="timerp">开展时间：<span><?php echo $val['exhibition_start_end'];?></span></p>
        		<p class="titlep"><?=$val['exhibition_advert']?></p>
                        <a target="_blank" href="<?=  urlbuilder::exhbInfo($val['exhibition_id'])?>" class="red redbtn redbtn2">查看详情</a>
        	</div>
        	<a href="<?=urlbuilder::exhbInfo($val['exhibition_id'])?>" target="_blank"><img class="fl" src="<?php echo URL::imgurl($val['exhibition_logo'])?>" alt="<?=$val['exhibition_name']?>"></a>
        	<div class="clear"></div>
        	<div class="bobaocontent" style="display:block"><?=$val['exhibition_explain']?></div>
        </div>
            <?}}?>
    </div>
</body>
<script type="text/javascript">
	$(function(){
		$(".speciallistbox").mouseover(function(){
			$(this).addClass('bobaocontenton').siblings().removeClass('bobaocontenton');
			$(this).find('.bobaocontent').show().end().siblings().find('.bobaocontent').hide();
		})
	})
</script>
</html>
