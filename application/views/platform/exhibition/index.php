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
<?php echo URL::webjs("jquery.validate.js")?>
<?php echo URL::webjs("platform/home/login_fu.js")?>
<body>
<div class="headerwrap">
    <div class="header">
        
        <p class="numhongbao numhongbao1">本期展会将派送<span><?=arr::get($hongbaoAll, 'num1', 0)?></span>个开业红包，目前现在还剩<span>
           <?=arr::get($hongbaoAll, 'num2', 0)?> </span>个</p>
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
    <?php foreach ($showing as $v){?>
    <div class="speciallistbox clearfix">
        <div class="fl speciallist">
            <h3><a target="_blank" href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" ><?=$v['exhibition_name']?></a></h3>
            <p class="timerp">倒计时：<span><?=$v['exhibition_countdown']?></span></p>
            <p class="titlep"><?=$v['exhibition_advert']?></p>
            <a target="_blank" href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" class="red redbtn" style="margin-left:60px;">查看详情</a>
            <a href="javascript:;" class="yellow redbtn libtn" data-exhb="<?=$v['exhibition_id'];?>">领取红包</a>                     
        </div>
        <input id="hongbao" type="hidden" value="3" />   
        <a target="_blank" href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" ><img class="fl" src="<?php echo URL::imgurl($v['exhibition_logo'])?>" width="620px" height="200px" alt="<?=$v['exhibition_name']?>"></a>
        <div class="clear"></div>
        <div class="bobaocontent" style="display:block"><?=$v['exhibition_explain']?></div>
    </div>
    <?php }?>

    <h4 class="h4title">即将开展</h4>
    <?php if(count($to_show)>2){?>
    <ul class="opensoon clearfix" id="opensoon1">
        <?php foreach ($to_show as $k => $v){?>
        <li style="<?if($k ==0 ) {echo ' margin-left:0 ';}?>">
            <a href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" target="_blank"><img src="<?php echo URL::imgurl($v['exhibition_logo_second'])?>" width="310" height="200" alt="<?=$v['exhibition_name']?>"></a>
            <h3><a href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" target="_blank" class="soonalink"><?=$v['exhibition_name']?></a></h3>
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
                    <a target="_blank" href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" class="red redbtn redbtn2" style="margin-left:60px;">查看详情</a>
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
            
        </li>
        <?php }?>
    </ul>
    <?php }else{
        foreach ($showed as $v){?>
            <div class="speciallistbox clearfix">
                <div class="fl speciallist">
                    <h3><a href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" target="_blank"><?=$v['exhibition_name']?></a></h3>
                    <p class="timerp">开始时间：<span><?=$v['exhibition_start_end']?></span></p>
                    <p class="titlep"><?=$v['exhibition_advert']?></p>
                    <a target="_blank" href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" class="red redbtn redbtn2">查看详情</a>
                </div>
                <a href="<?=urlbuilder::exhbInfo($v['exhibition_id'])?>" target="_blank"><img class="fl" src="<?php echo URL::imgurl($v['exhibition_logo'])?>" width="620px" height="200px" alt="<?=$v['exhibition_name']?>"></a>
                <div class="clear"></div>
                <div class="bobaocontent" style="display:block"><?=$v['exhibition_explain']?></div>
            </div>
        <?php }
    }}?>

</div>

  <input type="hidden" value="<?php echo $to_url?>" id="to_url_id">
    <input type="hidden" value="<?php echo $reg_fu_platform_num?>" id="reg_fu_platform_num_id">
    <input type="hidden" value="<?php echo $reg_fu_user_num?>" id="reg_fu_user_num_id">
      <input type="hidden" id="loginHidden" value="0" />
      <input type="hidden" id="returnbtn"/>
      
</body>
</html>
