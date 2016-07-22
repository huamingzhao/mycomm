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
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("zhanhui.css")?>
<?php echo URL::webcss("common_plugIn.css")?>
<?php echo URL::webjs("jquery-1.4.2.min.js")?>
<?php echo URL::webjs("jquery.validate.js")?>
<?php echo URL::webjs("commom_global_zhanhui.js")?>
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
                <div class="timecontent">
                    <h1><?=arr::get($exhibitionInfo, 'exhibition_name', '')?></h1>
                    <div class="clearfix conwrap">
                    <img class="fl" src="<?php echo URL::imgurl(arr::get($exhibitionInfo, 'exhibition_logo_second', ''))?>" width="150" height="110" alt="<?=arr::get($exhibitionInfo, 'exhibition_name', '')?>">
                    <div class="fl confont">
                        <p>参展项目:<span><?=$projectCount?></span>个</p>
                        <p>参展人数:<span><?=$exhibitionInfo['exhibition_czrs']?></span>人</p>
                    </div>
                    </div>
                    <div class="countDown">
                        倒计时：<?=$exhibitionInfo['exhibition_countdown']?>
                    </div>
                    <input id="countDown_time" type="hidden" value="<?php echo date('Y/m/d,H:i:s', $exhibitionInfo['exhibition_end']);?>" />
                </div>
            </div>
            <p class="numhongbao">本期展会将派送<span><?=arr::get($exhibitionInfo, 'hongbao_num', '')?></span>个开业红包，目前现在还剩<span class="shengyu"><?=arr::get(arr::get($exhibitionInfo, 'hb_info', ''), 'shengyu', 0)?></span>个</p>
            <a href="javascript:;" class="mygethong">领取红包</a>
            <input id="hongbao" type="hidden" value="1" />
            <input id="exhb_id" type="hidden" value="<?=arr::get($exhibitionInfo, "exhibition_id"); ?>" />
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
        <h4 class="h4title">知名项目</h4>
            <ul class="zmtable clearfix">
            <?if($famousProject) {foreach($famousProject as $val){?>
            <li><a target="_blank" href="<?=urlbuilder::exhbProject($val['project_id'])?>"><img width="131" height="105" onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?=URL::imgurl($val['project_logo'])?>" alt="<?=$val['project_brand_name']?>"></a></li>
            <?}}?>
           </ul>
		
        <h4 class="h4title">火热咨询中</h4>
        <ul class="zixunlist clearfix">
            <?if($consultingProject) {foreach($consultingProject as $val){?>
            <li><div class="clearfix">
                    <a target="_blank" href="<?=urlbuilder::exhbProject($val['project_id'])?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?=URL::imgurl($val['project_logo'])?>" class="fl" width="138" height="110" alt="<?=$val['project_brand_name']?>"></a>
                    <div class="fl imgright">
                        <p><a href="javascript:;" class="consult" id="zx_<?=$val['outside_id']?>_<?=$val['com_id']?>_1" >我要咨询<span class="colred">></span></a></p>
                       <?if($val['isHavaGroupId']) {?> <p><a href="<?=urlbuilder::exhbProject($val['project_id'])?>?open=1" target="_blank">在线沟通<span class="colred">></span></a></p><?}?>
                        <p class="mt10" style="color: #666;">咨询量</p>
                        <p style="color: red;"><?=arr::get($val, 'project_cart_cout', 0)?></p>
                    </div>
                </div>
                <div class="pbox mt10">
                    <p><a target="_blank" style="font-weight: bold;" href="<?=urlbuilder::exhbProject($val['project_id'])?>"><?=$val['project_brand_name']?></a></p>
                    <p><span class="colred"><?=$val['advertisement']?></span></p>
                </div>
                </li>
            <?}}?>
            

        </ul>
        <h4 class="h4title">最新参展项目</h4>
        <ul class="zxlist clearfix">
             <?if($newProject) {
                 foreach($newProject as $val) {?>
            <li>
                <a href="<?=urlbuilder::exhbProject($val['project_id'])?>"><img width="180" height="145"  onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?php echo URL::imgurl($val['project_logo'])?>" alt="<?=$val['project_brand_name']?>"></a>
                <h3><a href="<?=urlbuilder::exhbProject($val['project_id'])?>"><?=$val['project_brand_name']?></a></h3>
                <a href="<?=urlbuilder::exhbProject($val['project_id'])?>"><p class="colred"><?=$val['advertisement']?></p></a>
            </li>
             <?}}?>
            
        </ul>
        <h4 class="h4title">下期预告</h4>
        <?php if(count($to_show)>2){?>
    <ul class="opensoon clearfix" id="opensoon1">
        <?php foreach ($to_show as $k => $v){?>
        <li style="<?if($k ==0 ) {echo ' margin-left:0 ';}?>">
            <a target="_blank" href="<?=  urlbuilder::exhbInfo($v['exhibition_id'])?>" class="soonalink"><img src="<?php echo URL::imgurl($v['exhibition_logo_second'])?>"  onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')"width="310px" height="200px" alt="<?=$v['exhibition_name']?>"></a>
            <h3><a target="_blank" href="<?=  urlbuilder::exhbInfo($v['exhibition_id'])?>" class="soonalink"><?=$v['exhibition_name']?></a></h3>
            <p class="redp"><?=$v['exhibition_advert']?></p>
            <p class="timer"><?=$v['exhibition_start_end']?></p>
        </li>
        <?php }?>
    </ul>
    <?php }else{
         foreach ($to_show as $v){?>
            <div class="speciallistbox clearfix">
                <div class="fl speciallist">
                    <h3><a href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" target="_blank"><?=$v['exhibition_name']?></a></h3>
                    <p class="timerp">开始时间：<span><?=$v['exhibition_start_end']?></span></p>
                    <p class="titlep"><?=$v['exhibition_advert']?></p>
                    <a target="_blank" href="<?=  urlbuilder::exhbInfo($v['exhibition_id'])?>" class="red redbtn redbtn2">查看详情</a>
                </div>
                <a href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" target="_blank"><img class="fl" src="<?php echo URL::imgurl($v['exhibition_logo'])?>" width="620px" height="200px" alt="<?=$v['exhibition_name']?>"></a>
                <div class="clear"></div>
                <div class="bobaocontent" style="display:block"><?=$v['exhibition_explain']?></div>
            </div>
        <?php }
     }?>
<?if($showed) {?>
        <h4 class="h4title">历史展会</h4>
       <?php if(count($showed)>2){?>
    <ul class="opensoon clearfix">
        <?php foreach ($showed as $k => $v){?>
        <li style="<?if($k ==0 ) {echo ' margin-left:0; ';}?>padding-bottom:0">
            <a target="_blank" href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>"><img src="<?php echo URL::imgurl($v['exhibition_logo_second'])?>" width="310" height="200" alt="<?=$v['exhibition_name']?>"></a>
            
            <h3><a target="_blank" href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" class="soonalink"><?=$v['exhibition_name']?></a></h3>
        <?php }?>
    </ul>
    <?php }else{
        foreach ($showed as $v){?>
            <div class="speciallistbox clearfix">
                <div class="fl speciallist">
                    <h3><a href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" target="_blank"><?=$v['exhibition_name']?></a></h3>
                    <p class="timerp">开始时间：<span><?=$v['exhibition_start_end']?></span></p>
                    <p class="titlep"><?=$v['exhibition_advert']?></p>
                    <a href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" target="_blank"><a target="_blank" href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" class="red redbtn redbtn2">查看详情</a></a>
                </div>
                <img class="fl" src="<?php echo URL::imgurl($v['exhibition_logo'])?>" width="620px" height="200px" alt="<?=$v['exhibition_name']?>">
                <div class="clear"></div>
                <div class="bobaocontent" style="display:block"><?=$v['exhibition_explain']?></div>
            </div>
        <?php }
    }?>

<?}?>
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
<?php echo URL::webjs("platform/getmessage_company.js")?>