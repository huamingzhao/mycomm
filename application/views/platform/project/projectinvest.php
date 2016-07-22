<?php echo URL::webjs("platform/template/yellow.js");?>
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
        console.log(msg)
       }
  	});
    //调用google地图
    initialize();
    codeAddress("<?=$invest['investment_address']?>");
})
</script>
<!--中部开始-->
<style>
#yellow #center-a .contant .lt {position: absolute;}
#yellow #center-a .contant .lt .text {word-break: break-all; display:block; overflow:hidden;}
#yellow #center-a .contant .lt .lt_top .b {width: 180px;}
#yellow #center-a .contant .lt .text,#yellow #center-a .contant .rt .rt_top,#yellow #center-a .contant .lt .lt_top .b p{word-break: break-all;}
.contant .lt .text h3 a,.rt .rt_top h3 a{color:#000; float:right;color:#000; font-weight: normal;font-size: 12px;}
#yellow #center-a #title{background:#efefef url("<?php echo URL::webstatic("images/platform/yellow/meeting.png")?>") no-repeat right 40px;}
#yellow #center-a {height:auto;}
</style>

<div id="yellow">
    <div id="center-a">

        <div id="title">
            <h1><?=$invest['investment_name']?></h1>
            <p>时间 : <?php if($start==$end){?><?=date('Y.m.d',$start)?><?php }else{?><?=date('Y.m.d',$start)?>-<?=date('Y.m.d',$end)?><?php }?>&nbsp;&nbsp;&nbsp;&nbsp;地点 : <?php if($invest['spantime']<0){?><?=$invest['investment_address']?><?php }else{?><?=$city?><?php }?></p>
            <img alt="一句话项目图片" src="<?php echo URL::webstatic("images/platform/yellow/meeting.png");?>" class="sign"/>
        </div>
        <div class="contant">
            <div class="lt">
                <div class="lt_top">
                    <div class="a"><img alt="一句话项目logo" src="<?php echo URL::imgurl($invest['investment_logo']);?>" /></div>
                    <div class="b">
                        <p><span class="clock"></span>报名倒计时：<span class="book"><?=$invest['spantime']?></span> 天</p>
                        <p><b>现场优惠： 到场有惊喜！</b></p>
                        <p>招商经理：<?=$invest['com_name']?></p>
                        <p style="height:25px;width:180px; display:block; overflow:hidden;">联系电话：<?=$invest['com_phone']?></p>
                        <?php if(!isset($isapply)||$isapply==1){?><button></button><?php }elseif($isapply == 0){?><button style="  background: url(<?php echo URL::webstatic("images/platform/yellow/applyed.png");?>) no-repeat scroll 0 0 transparent;border: medium none;cursor: pointer;height: 29px;margin-top: 5px;width: 127px;"></button><?php }?>
                    </div>
                </div>
                <div class="text">
                    <h3>投资考察会详情：<?php  if(mb_strlen(strip_tags(htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_details'), 0)))))>200){ ?><a title="投资考察会详情" href="" class="more_td">更多</a><?php } ?></h3>
                    <?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_details'), 0)));echo Text::limit_chars(strip_tags($string),200);?>
                </div>
            </div>
            <div class="rt" style="float:left;padding-left: 496px;">
                <div class="rt_top" style="padding-right:35px;">
                    <h3>参会流程：<?php  if(mb_strlen(strip_tags(htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_agenda'), 0)))))>200){ ?><a title="参会流程" href="" class="more_js">更多</a><?php } ?></h3>
                    <?$agenda = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_agenda'), 0)));echo Text::limit_chars(strip_tags($agenda),200);?>
                </div>
                <div class="rt_bottom" style="padding-right:35px;padding-bottom:10px;">
                    <h3>会议地点：</h3>
                   <div id="map_canvas" style="width:402px;height:209px;margin-top:10px;"></div>
                </div>
            </div>

            <div class="clear"></div>
        </div>
          <?php if(count($historyInvest)>0){?>
            <div class="zsh_hist_will_meet">
                <h3>历史投资考察会：</h3>
                <ul>
                <?php foreach ($historyInvest as $will){?>
                <li>
                <label><a title="一句话项目logo" href="<?php echo urlbuilder::projectInvest($project_id).'?investid='.$will['investment_id'];?>"><img alt="一句话项目logo" src="<?=$will['investment_logo']?>"/></a></label>
                <p>
                  <a title="" href="<?php echo urlbuilder::projectInvest($project_id).'?investid='.$will['investment_id'];?>"><?=$will['investment_name']?></a>
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

        <div id="right_nav">
            <ul>
                <!-- <li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">封面</a></li>-->
                <li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">项目</a></li>
                <?php if(isset($ispage) && $ispage){?><li><a href="<?php echo urlbuilder::projectPoster($projectinfo->project_id);?>">海报</a></li><?php }?>
                <?php if(isset($is_has_image) && $is_has_image){?><li><a href="<?php echo urlbuilder::projectImages($projectinfo->project_id);?>">产品</a></li><?php }?>
                <?php if(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){?><li><a href="<?php echo urlbuilder::projectCompany($projectinfo->project_id);?>">公司</a></li><?php }?>
                <?php if($isCerts){?><li><a href="<?php echo urlbuilder::projectCerts($projectinfo->project_id);?>">资质</a></li><?php }?>
                <li><a href="#" class="current three" style="padding-top:7px;height:49px;">招商会</a></li>
                <!-- <li><a href="<?php echo urlbuilder::projectEnd($projectinfo->project_id);?>">封底</a></li>-->
            </ul>
        </div>

        <div class="clear"></div>

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
    <?php if(isset($user_type)&&$user_type==2){?>
      <!--弹出框开始-->
     <div id="yellow-a-box">
        <a title="close" href="#" class="close">close</a>
        <div class="yellow-a-box_top"></div>
        <div class="clear"></div>
        <div class="yellow-a-box_center">
        <h1>参会邀请函</h1>
        <p class="tishi">如果您对此项目有兴趣，请花上1分钟的时间填写邀请函</p>
        <form action="<?php echo URL::site('/platform/project/applyInvest');?>" method="post" id='applyinvest'>
            <div>
                <p>姓　　名 ：<input type="text" class="text" name="apply_name" value="<?=$user->per_realname?>" id="apply_realname"/><span style="color: red;padding-left:15px;visibility:hidden;" id="tishi7">姓名不能为空</span></p>
                <p>性　　别 ：<input type="radio" name="apply_sex" class="radio" value="1" <?php if($user->per_gender==1||$user->per_gender==0){echo "checked";}?>/>男<input type="radio" name="apply_sex" class="radio1" value="2" <?php if($user->per_gender==2){echo "checked";}?>/>女</p>
                <p>手　　机 ：<input type="text" class="text" name="apply_mobile" value="<?=$mobile?>" id="apply_moblie"/><span style="color: red;padding-left:15px;visibility:hidden;" id="tishi8">手机不能为空</span></p>
                  <p class="add_text">
                <b>招商场次 ：</b>
                <em>
                <?php foreach ($invest_array as $v){ ?>
                <span><input type="radio" name="invest_id" class="radio add_radio" value="<?=$v['investment_id']?>" <?php if($v['num']>0){?>disabled<?php }?>/><?php echo date('Y.m.d',$v['investment_start']);?> - <?php echo date('Y.m.d',$v['investment_end']);?><?php if($v['num']>0){?>(已报名)<?php }?><br/>&nbsp;&nbsp;&nbsp;<?=$v['investment_address']?></span>
                <?php }?>
                </em>
                </p>
                <div class="clear"></div>
                <p style="padding-left:80px;color:#f00;display:none;" id="changci">请选择招商会场次！</p>
                <input type="hidden" name="projectid" value="<?=$project_id?>">
                <input type="hidden" name="user_id" value="<?=$user_id?>">
                <p><b style="width:150px;">是否需要公司统一安排酒店:</b><em><input type="radio" name="is_hotel" class="yes" value="1" checked='checked'/>是<input type="radio" name="is_hotel" class="yes1" value="2"/>否</em></p>
                <?php if(!isset($isapply)||$isapply==1){?><p class="submit"><input type="image" src="<?php echo URL::webstatic("/images/platform/yellow/confirm.png");?>" class="button"></p><?php }?>
            </div>
        </form>
        <div class="clear"></div>
        </div>
        <div class="clear"></div>
        <div class="yellow-a-box_bot"></div>
    </div>
    <!--弹出框结束-->
    <?php }elseif(empty($user)){?>
    <?php echo URL::webjs("platform/login/plat_login.js")?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#changeCodeImg').click(function() {
                        var url = '/captcha';
                            url = url+'?'+RndNum(8);
                            $("#vfCodeImg1").attr('src',url);
                    });
            });
         </script>
    <?php }else{?>
    <!--提示-弹出框开始-->
    <div id="yellow-c-box">
        <a title="close" href="#" class="close">close</a>
        <span>很报歉，只能个人用户才能报名参会哦！</span>
        <p><input name="" type="button" class="btn_close"></p>
    </div>
    <!--提示-弹出框结束-->
    <?php }?>
</div>
<!--透明背景开始-->
<div id="opacity"></div>
<!--透明背景结束-->

<!--递出名片层开始-->
<div id="send_box" style="z-index:999">
    <a title="关闭" href="#" class="close">关闭</a>
    <div id="msgcontent" class="btn">
    </div>
</div>
<!--递出名片层结束-->
<!--更多文字开始-->
<div id="opacity_box"></div>
<div id="yellow_xq_box" class="aa clo">
    <a title="close" href="#" class="close"></a>
    <div style="width:690px;height:610px; overflow:auto;">
        <h3>投资考察会详情</h3>
        <div class="p"><?php $string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_details'), 0)));echo preg_replace("/<script[^>]*?>.*?<\/script>/si",' ',$string);?></div>
    </div>
</div>
<div id="yellow_xq_box" class="bb clo">
    <a title="close" href="#" class="close"></a>
    <div style="width:690px;height:610px; overflow:auto;">
        <h3>参会流程</h3>
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
}
 });
}
</script>
