<!DOCTYPE html>

<!DOCTYPE html>
<html>
<!-- Copyright 2013 The Chromium Authors. All rights reserved.
     Use of this source code is governed by a BSD-style license that can be
     found in the LICENSE file. -->
<head>
    <meta charset="utf-8">
</head>
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("zhanhui.css")?>
<?php echo URL::webjs("jquery-1.4.2.min.js")?>
<?php echo URL::webjs("zhanhui.js")?>
<body>
<div class="headerwrap">
    <div class="header">
        <a href="javascript:;" class="returnyjh"><img src="<?php echo URL::webstatic('images/zhanhui/returnyjh.png')?>"></a>
    </div>
</div>
<div class="content">
    <div class="bobaobox clearfix" >
        <p class="bobao fl">成果播报：</p>
        <div class="bobaobox2" >
            <ul class="bobaolist fl" data-direction="horizontal" id="searchNews1" style="width:10000px">
                <li><span class="blue">王冰1</span>刚刚在线沟通了<a class="blue" href="javascript:;">男人的衣厨——海澜之家</a>项目；<span class="blue">王冰</span>刚刚在线沟通了<a class="blue" href="javascript:;">男人的衣厨——海澜之家</a>项目；</li>
                <li><span class="blue">王冰2</span>刚刚在线沟通了<a class="blue" href="javascript:;">男人的衣厨——海澜之家</a>项目；<span class="blue">王冰</span>刚刚在线沟通了<a class="blue" href="javascript:;">男人的衣厨——海澜之家</a>项目；</li>
                <li><span class="blue">王冰3</span>刚刚在线沟通了<a class="blue" href="javascript:;">男人的衣厨——海澜之家</a>项目；<span class="blue">王冰</span>刚刚在线沟通了<a class="blue" href="javascript:;">男人的衣厨——海澜之家</a>项目；</li>
            </ul>
        </div>
    </div>
    <?php foreach ($showing as $v){?>
    <div class="speciallistbox clearfix">
        <div class="fl speciallist">
            <h3><?=$v['exhibition_name']?></h3>
            <p class="timerp">倒计时：<span><?=$v['exhibition_countdown']?></span></p>
            <p class="titlep"><?=$v['exhibition_advert']?></p>
            <a href="javascript:;" class="red redbtn">入场观看</a>
        </div>
        <img class="fl" src="<?php echo URL::imgurl($v['exhibition_logo'])?>" width="620px" height="200px">
        <div class="clear"></div>
        <div class="bobaocontent" style="display:block"><?=$v['exhibition_explain']?></div>
    </div>
    <?php }?>

    <h4 class="h4title">即将开展</h4>
    <?php if(count($to_show)>2){?>
    <ul class="opensoon">
        <?php foreach ($to_show as $v){?>
        <li style="margin-left:0">
            <a href="javascript:;" class="soonalink"><img src="<?php echo URL::imgurl($v['exhibition_logo_second'])?>" width="310px" height="200px"></a>
            <h3><a href="javascript:;" class="soonalink"><?=$v['exhibition_name']?></h3></p>
            <p class="redp"><?=$v['exhibition_advert']?></p>
            <p class="timer"><?=$v['exhibition_start_end']?></p>
        </li>
        <?php }?>
    </ul>
    <?php }else{
         foreach ($to_show as $v){?>
            <div class="speciallistbox clearfix">
                <div class="fl speciallist">
                    <h3><?=$v['exhibition_name']?></h3>
                    <p class="timerp">开始时间：<span><?=$v['exhibition_start_end']?></span></p>
                    <p class="titlep"><?=$v['exhibition_advert']?></p>
                    <a href="javascript:;" class="red redbtn">入场观看</a>
                </div>
                <img class="fl" src="<?php echo URL::imgurl($v['exhibition_logo'])?>" width="620px" height="200px">
                <div class="clear"></div>
                <div class="bobaocontent" style="display:block"><?=$v['exhibition_explain']?></div>
            </div>
        <?php }
     }?>

    <h4 class="h4title">历史展会</h4>
    <?php if(count($showed)>2){?>
    <ul class="opensoon">
        <?php foreach ($showed as $v){?>
        <li style="margin-left:0">
            <a href="javascript:;" class="soonalink"><img src="<?php echo URL::imgurl($v['exhibition_logo_second'])?>" width="310px" height="200px"></a>
            <h3><a href="javascript:;" class="soonalink"><?=$v['exhibition_name']?></a></h3>
            <p class="redp"><?=$v['exhibition_advert']?></p>
            <p class="timer"><?=$v['exhibition_start_end']?></p>
        </li>
        <?php }?>
    </ul>
    <?php }else{
        foreach ($showed as $v){?>
            <div class="speciallistbox clearfix">
                <div class="fl speciallist">
                    <h3><?=$v['exhibition_name']?></h3>
                    <p class="timerp">开始时间：<span><?=$v['exhibition_start_end']?></span></p>
                    <p class="titlep"><?=$v['exhibition_advert']?></p>
                    <a href="javascript:;" class="red redbtn">入场观看</a>
                </div>
                <img class="fl" src="<?php echo URL::imgurl($v['exhibition_logo'])?>" width="620px" height="200px">
                <div class="clear"></div>
                <div class="bobaocontent" style="display:block"><?=$v['exhibition_explain']?></div>
            </div>
        <?php }
    }?>

</div>
</body>
<script type="text/javascript">
    
</script>
</html>
