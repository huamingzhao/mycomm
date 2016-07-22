<?php echo URL::webjs("platform/filter/swfobject.js");?>
<?php echo URL::webjs("platform/template/yellow.js");?>
<?php if($bobao['bobao_status']==2){echo URL::webjs("platform/template/bobao_flash.js");}?>
<?php echo URL::webcss("zsh_history.css"); ?>
<script type="text/javascript">
$(function(){
	 //用户访问招商会的记录
	 var investmentid = <?php echo $invest['investment_id'];?>;
	 $.ajax({
	     type: "post",
	     dataType: "json",
	     url: "/platform/ajaxcheck/addPersonAboutInv",
	     data: "investmentid="+investmentid,
	     complete :function(){
	     },
	     success: function(msg){
	     }
	});
    //调用google地图
    initialize();
    codeAddress("<?=$invest['investment_address']?>");
})
</script>
<!--中部开始-->
<style>
#yellow #center-a .contant .lt { position: absolute;}
#yellow #center-a{ height:auto;}
#yellow #center-a .contant .lt .text {word-break: break-all; display:block; overflow:hidden;}
#yellow #center-a .contant .lt .lt_top .b {width: 180px;}
#yellow #center-a .contant .lt .text,#yellow #center-a .contant .rt .rt_top,#yellow #center-a .contant .lt .lt_top .b p{word-break: break-all;}
#yellow #center-a .contant .lt .text h3 a,#yellow #center-a .contant .rt .rt_top h3 a{color:#000; float:right;font-weight: normal;font-size: 12px;}
/*#yellow #center-a .contant .lt .text h3, .#yellow #center-a .contant .rt h3{font-weight: normal;} 招商会标题加粗cxy-20130715*/
#yellow #center-a .contant .rt {padding-left: 495px; width: 482px;}
#yellow #center-a #title{background:#efefef url("<?php echo URL::webstatic("images/platform/yellow/meeting.png")?>") no-repeat right 40px;}
#cardandproject button{display: block;height: 31px;width: 94px;padding-bottom:3px;}
#yellow #center-a .contant .lt .text p {line-height: auto;padding-top: 0px;text-indent: 0px;}
</style>

<div id="yellow">
    <div id="center-a">

        <div id="title">
            <div class="ryl_zsh_title">
               <h1><?=$invest['investment_name']?></h1>
               <p>时间 : <?php if($start==$end){?><?=date('Y.m.d',$start)?><?php }else{?><?=date('Y.m.d',$start)?>-<?=date('Y.m.d',$end)?><?php }?>&nbsp;&nbsp;&nbsp;&nbsp;地点 : <?php if($invest['spantime']<0){?><?=$invest['investment_address']?><?php }else{?><?=$city?><?php }?></p>
            </div>
            <div class="zsh_deliver_card name" id="cardandproject">
            	<?php if($card){?>
                   <button>已递送</button>
                 <?php } else{?>
                 <button id="button_a" class="sendcard_<?php echo $userid."_".$com_user_id?>">递送名片</button>
                 <?php } ?>
            </div>
            <?php if($bobao['bobao_status']==2){?><div class="zsh_sign_rate "><?php echo floor(arr::get($bobao,'bobao_sign')/arr::get($bobao,'bobao_num')*100);?><em>%</em></div><?php }?>
        </div>
        <div class="contant">
            <div class="lt" <?php if($bobao['bobao_status']==2){?>style="height:922px;"<?php }?>>
                <div class="lt_top">
                    <div class="a"><img src="<?php echo URL::imgurl($invest['investment_logo']);?>" /></div>
                    <div class="b">
                        <p><span class="clock"></span>报名倒计时：<?php if($invest['spantime']<0){?><b class="zsh_hist_had_over">已结束</b><?php }else{?><span class="book"><?=$invest['spantime']?></span> 天<?php }?></p>
                        <?php if($bobao['bobao_status']==2){?><p><b>签约人数：<?=$bobao['bobao_sign']?>人</b></p><?php }?>
                        <p>招商经理：<?=$invest['com_name']?></p>
                        <p style="height:25px;width:180px; display:block; overflow:hidden;">联系电话：<?=$invest['com_phone']?></p>
                        <p class="zsh_hist_sign">报名人数:  <?php if($bobao['bobao_status']==2){?>
                        <?php if(isset($ismy)){?><a href="<?php echo URL::website('/company/member/project/myApplyInvest').'?invest_id='.$invest['investment_id'];?>"><b><? echo arr::get($bobao,'bobao_num');?></b></a>人
                        <?php }else{?><b><? echo arr::get($bobao,'bobao_num');?></b>人
                        <?php }}elseif(isset($ismy)){?><a href="<?php echo URL::website('/company/member/project/myApplyInvest').'?invest_id='.$invest['investment_id'];?>"><b><? echo arr::get($invest,'in_num');?></b></a>人
                        <?php }else{?><b><? echo arr::get($invest,'in_num');?></b>人<?php }?></p>
                    </div>
                </div>
                <div class="text" <?php if($bobao['bobao_status']==2){?>style="height:695px;"<?php }?>>
                <?php if($bobao['bobao_status']==2){?>
                <h3>投资考察会详情：<?php  if(mb_strlen(strip_tags(htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_details'), 0)))))>1300){ ?><a href="" class="more_td">更多</a><?php } ?></h3>
                    <?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_details'), 0)));echo Text::limit_chars(strip_tags($string),1300);?>
                <?php }else{?>
                    <h3>投资考察会详情：<?php  if(mb_strlen(strip_tags(htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_details'), 0)))))>500){ ?><a href="" class="more_td">更多</a><?php } ?></h3>
                    <?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_details'), 0)));echo Text::limit_chars(strip_tags($string),500);?>
                <?php }?>
                </div>
            </div>
            <div class="rt">
                <div class="rt_top" style="padding-right:35px;">
                    <h3>参会流程：<?php  if(mb_strlen(strip_tags(htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_agenda'), 0)))))>200){ ?><a href="" class="more_js">更多</a><?php } ?></h3>
                    <?$agenda = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_agenda'), 0)));echo Text::limit_chars(strip_tags($agenda),200);?>
                </div>
                <div class="rt_bottom" style="padding-right:35px;padding-bottom:10px;">
                    <h3>会议地点：</h3>
                   <div id="map_canvas" style="width:402px;height:209px;margin-top:10px;"></div>
                </div>
            </div>
            <div class="clear"></div>
            <?php if($bobao['bobao_status']==2){?>
            <div class="zsh_hist_will_meet">
                <div class="zsh_hist_video"><img src="<?php echo URL::webstatic("images/zsh_history/video_bg.jpg");?>"/></div>
                <div class="zsh_hist_photolist">
                   <p>现场影集：</p>
                   <div class="zsh_hist_jj">
                      <div id="imagewalls"></div>
            		  <input type="hidden" name="hid" value="<?php echo arr::get($invest, 'investment_id');?>" id="hids">
                   </div>
                   <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <?php }?>
            <?php if(count($willInvest)>0){?>
            <div class="zsh_hist_will_meet">
                <h1>即将召开的投资考察会：</h1>
                <ul>
                <?php foreach ($willInvest as $will){?>
                <li>
                <label><a href="<?php echo urlbuilder::projectInvest($will['project_id']);?>"><img src="<?=$will['investment_logo']?>"/></a></label>
                <p>
                  <a href="<?php echo urlbuilder::projectInvest($will['project_id']);?>"><?=$will['investment_name']?></a>
                  <span>召开时间：<?php if($will['investment_start']==$will['investment_end']){echo date('Y/m/d',$will['investment_start']);}else{echo date('Y/m/d',$will['investment_start']).'-'.date('Y/m/d',$will['investment_end']);}?></span>
                  <span>召开地址：<?=$will['investment_address']?></span>
                  <div class="clear"></div>
                </p>
                <div class="clear"></div>
                </li>
                <?php }?>
                <div class="clear"></div>
                </ul>
                <div class="clear"></div>
            </div>
            <?php }?>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
<!--        <div class="corner"></div>-->
        <div id="right_nav">
            <ul>
                <!--<li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">封面</a></li>-->
                <li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">项目</a></li>
                <?php if(isset($ispage) && $ispage){?><li><a href="<?php echo urlbuilder::projectPoster($projectinfo->project_id);?>">海报</a></li><?php }?>
                <?php if(isset($is_has_image) && $is_has_image){?><li><a href="<?php echo urlbuilder::projectImages($projectinfo->project_id);?>">产品</a></li><?php }?>
                <?php if(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){?><li><a href="<?php echo urlbuilder::projectCompany($projectinfo->project_id);?>">公司</a></li><?php }?>
                <?php if($isCerts){?><li><a href="<?php echo urlbuilder::projectCerts($projectinfo->project_id);?>">资质</a></li><?php }?>
                <li><a href="#" class="current three" style="padding-top:7px;height:49px;">招商会</a></li>
                <!--<li><a href="<?php echo urlbuilder::projectEnd($projectinfo->project_id);?>">封底</a></li> -->
            </ul>
        </div>
    </div>


    <div class="clear"></div>
    <div class="ryl_project_bg">
    <p class="ryl_project_page">
      <!-- 上一页 -->
      <a href="<?php if(isset($isCerts) && $isCerts){
                        echo urlbuilder::projectCerts($projectinfo->project_id);
                    }elseif(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){
                        echo urlbuilder::projectCompany($projectinfo->project_id);
                    }elseif(isset($is_has_image) && $is_has_image){
                        echo urlbuilder::projectImages($projectinfo->project_id);
                    }elseif(isset($ispage) && $ispage){
                        echo urlbuilder::projectPoster($projectinfo->project_id);
                    }else{
                        echo urlbuilder::project($projectinfo->project_id);
                    }
            ?>" class="ryl_prev_page"></a>
      <!-- 下一页
      <a href="<?php echo urlbuilder::projectEnd($projectinfo->project_id); ?>" class="ryl_next_page"></a>
      -->
    </p>
    </div>
    <div class="clear"></div>
</div>
<!--透明背景开始-->
<div id="opacity"></div>
<!--透明背景结束-->

<!--递出名片层开始-->
<div id="send_box" style="z-index:999">
    <a href="#" class="close">关闭</a>
    <div id="msgcontent" class="btn">
    </div>
</div>
<!--递出名片层结束-->
<!--更多文字开始-->
<div id="opacity_box"></div>
<div id="yellow_xq_box" class="aa clo">
    <a href="#" class="close"></a>
    <div style="width:690px;height:610px; overflow:auto;">
        <h2>投资考察会详情</h2>
        <div class="p"><?php $string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_details'), 0)));echo preg_replace("/<script[^>]*?>.*?<\/script>/si",' ',$string);?></div>
    </div>
</div>
<div id="yellow_xq_box" class="bb clo">
    <a href="#" class="close"></a>
    <div style="width:690px;height:610px; overflow:auto;">
        <h2>参会流程</h2>
        <div class="p"><?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_agenda'), 0)));echo preg_replace("/<script[^>]*?>.*?<\/script>/si",' ',$string);?></div>
    </div>
</div>
<script type="text/javascript">
    var tj_type_id = 2;
    var tj_pn_id = <?=$invest['investment_id']?>;
</script>
<!--更多文字结束-->
<!--中部结束-->
<link href="http://code.google.com/apis/maps/documentation/javascript/examples/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
   var geocoder;
   var map;
   function initialize() {
     geocoder = new google.maps.Geocoder();
     var latlng = new google.maps.LatLng(31.23, 121.47);
     var myOptions = {
     zoom: 12,
     center: latlng,
     mapTypeId: google.maps.MapTypeId.ROADMAP
      }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
}
function codeAddress(address) {
    geocoder.geocode({ 'address': address },
function (results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        var lat = results[0].geometry.location.lat();
        var lng = results[0].geometry.location.lng();
        map.setCenter(results[0].geometry.location);
        this.marker = new google.maps.Marker({
       title: address,
        map: map,
        position: results[0].geometry.location
});
 var infowindow = new google.maps.InfoWindow({
 content: '<strong>' + address
});
     infowindow.open(map, marker);
} else {
    alert("Geocode was not successful for the following reason: " + status);
    }
 });
}
</script>