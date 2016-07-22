<?php echo URL::webcss("platform/look_random.css")?>
<?php echo URL::webjs("platform/AC_RunActiveContent.js")?>
<!--主体-->
<div class="ryl_look">
    <div class="ryl_look_main">

    <!--主体left-->
        <div class="ryl_look_main_left">

<?php foreach ($industry_list as $k=>$v){ ?>
           <div class="rank_unit">
              <div class="title"><h2><?php echo $v['industry_name']; ?>月度热榜</h2></div>
              <div class="rank_unit_top"></div>
              <div class="rank_unit_cont">
                 <ul class="rank_unit_left">
                 <?php foreach ($v['detail'] as $ke=>$ve){ ?>
                 <?php if($ke == 1){ ?>
                    <li class="current">
                        <span><a href="<?php echo urlbuilder::project($ve['project_id']);?>"  target="_blank" title="<?php echo $ve['project_brand_name']; ?>"><?php echo $ke; ?>、<?php echo $ve['project_brand_name']; ?></a></span>
                        <p><a href="<?php echo urlbuilder::project($ve['project_id']);?>"  target="_blank"><img src="<?if($ve['project_source'] != 1) {echo project::conversionProjectImg($ve['project_source'], 'logo', $ve);} else {echo URL::imgurl($ve['project_logo']);}?>" alt="<?php echo $ve['project_brand_name']; ?>" /></a></p>
                    </li>
                    <?php }else{ ?>
                    <li><span><a href="<?php echo urlbuilder::project($ve['project_id']);?>"  target="_blank" title="<?php echo $ve['project_brand_name']; ?>"><?php echo $ke; ?>、<?php echo $ve['project_brand_name']; ?></a></span>
                    <p><a href="<?php echo urlbuilder::project($ve['project_id']);?>"  target="_blank"><img src="<?if($ve['project_source'] != 1) {echo project::conversionProjectImg($ve['project_source'], 'logo', $ve);} else {echo URL::imgurl($ve['project_logo']);}?>" alt="<?php echo $ve['project_brand_name']; ?>" /></a></p></li>
                    <?php }} ?>
                 </ul>

                 <div style="float:right;width: 554px;">
                <?php foreach ($v['detail'] as $ke=>$ve){ ?>
                 <div class="rank_unit_right01">
                   <span class="rank_unit_sanjiao"><img src="<?php echo URL::webstatic("images/platform/look/icon06.jpg") ?>" /></span>
                   <div class="rank_unit_r_cont">
                     <p class="reason">
                       <b>上榜理由：</b>
                       <span style="display:block; overflow: hidden; height:88px; width:510px;"><?php echo $ve['list_reasons']; ?></span>
                     </p>
                       <div class="rank_unit_r_shadow01"><img src="<?php echo URL::webstatic("images/platform/look/shadow01.jpg") ?>" /></div>
                       <div class="rank_unit_chart">
                         <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="500" height="180" id="flashrek2">
                           <param name="movie" value="<?php echo URL::webstatic('flash/graph.swf?projectid=').$ve['project_id'];?>" />
                           <param name="quality" value="high" />
                           <param name="allowScriptAccess" value="always" />
                           <embed src="<?php echo URL::webstatic('flash/graph.swf?projectid=').$ve['project_id'];?>" allowscriptaccess="always" id="fileId4" width="500" height="180" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                         </object>
                     </div>
                     <p class="require">
                       <b>加盟条件：</b>
                       <span style="display:block; overflow: hidden; height:44px; width:510px;"><?php echo $ve['join_conditions']; ?><a href="<?php echo urlbuilder::project($ve['project_id']);?>" target="_blank">更多>></a></span>

                     </p>
                       <div class="rank_unit_r_shadow01"><img src="<?php echo URL::webstatic("images/platform/look/shadow01.jpg") ?>" /></div>
                     <p  class="analyse">
                       <b>利润分析：</b>
                       <span style="display:block; overflow: hidden; height:22px; width:510px;"><?php echo $ve['profit_analysis']; ?><a href="<?php echo urlbuilder::project($ve['project_id']);?>" target="_blank">更多>></a></span>

                     </p>
                   </div>
                 </div>
                                     <?php } ?>
                 </div>

                 <div class="clear"></div>
              </div>
              <div class="rank_unit_bottom"></div>
           </div>
            <?php } ?>



        </div>
    <!--主体right-->
        <div class="ryl_look_main_right">
           <div class="ryl_random_item_title"><span>随机筛选</span></div>
           <div class="ryl_random_item">
              <div class="ryl_key_rotate">
                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4"  width="274" height="185"id="flashrek2">
                           <param name="movie" value="<?php echo URL::webstatic('flash/couldTag.swf');?>" />
                           <param name="quality" value="high" />
                           <param name="allowScriptAccess" value="always" />
                           <embed src="<?php echo URL::webstatic('flash/couldTag.swf');?>" allowscriptaccess="always" id="fileId4"  width="274" height="185" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
               </object>
              </div>
              <p class="ryl_random_condition" id="ryl_random_condition">随机条件：<b>请选择</b></p>
              <p class="ryl_random_result" id="ryl_random_result"><span>匹配项目：<em><?=$project_count;?></em>个</span></p>
              <p class="ryl_random_shadow"></p>
              <ul class="ryl_random_logolist" id="ryl_random_logolist">
              <?php foreach ($project_list as $k=>$v){ ?>
                <li><a href="<?php echo urlbuilder::project($v->project_id)?>" target="_blank"><img src="<?if($v->project_source != 1){echo project::conversionProjectImg($v->project_source, 'logo', $v->as_array());}else{ echo URL::imgurl($v->project_logo);}?>" alt="<?php echo $ve['project_brand_name']; ?>" /></a></li>
               <?php } ?>
               <div class="clear"></div>
              </ul>
              <div class="clear"></div>
              <!--隐藏精准匹配<p class="look_more_item" id="look_more_item"><a href="<?php echo urlbuilder::zhuntouzi();?>" target="_blank" ></a></p>-->
              <div class="clear"></div>
           </div>
        </div>
    </div>
</div>
<script type="text/javascript" >
function show(_str){
    var content = "随机条件：<b>" + _str + "</b>";
    $('#ryl_random_condition').html(content);
    $.ajax({
        type: "post",
        dataType: "json",
        url: "/platform/ajaxcheck/getrandomconditions?debug=1",
        data: "w="+_str,
        complete :function(){
        },
        success: function(msg){
            var count = "<span>匹配项目：<em>" + msg['total'] + '</em>个</span><a href="<?php echo urlbuilder::zhuntouzi();?>" target="_blank" id="showAllLink"></a>';
            $('#ryl_random_result').html(count);
            var contents = "";
            if(msg['matches'].length > 0){
                for(var i=0;i<msg['matches'].length;i++){
                    contents +='<li><a href="'+ msg["matches"][i]["link"] +'" target="_blank"><img src="' + msg["matches"][i]["logo"] + '" /></a></li>';

                }
            }

            var text='<ul class="ryl_random_logolist">' + contents + '<div class="clear"></div></ul>';
            $('#ryl_random_logolist').html(text);
            var url = '/platform/index/search?w='+encodeURIComponent(_str);
            $("#showAllLink").attr('href', url);
        }
    });

}
var tab_li = $(".rank_unit_cont li");
$(".rank_unit_right01").hide();
$(".rank_unit").each(function(){
    $(this).find(".rank_unit_right01").eq(0).show();
    $(this).find(".rank_unit_sanjiao").eq(0).css("padding-top","55px");
    $(this).find(".rank_unit_sanjiao").eq(1).css("padding-top","120px");
    $(this).find(".rank_unit_sanjiao").eq(2).css("padding-top","175px");
        $(this).find(".rank_unit_sanjiao").eq(3).css("padding-top","233px");
        $(this).find(".rank_unit_sanjiao").eq(4).css("padding-top","291px");
        $(this).find(".rank_unit_sanjiao").eq(5).css("padding-top","352px");
});
tab_li.hover(function(){
    var tab_li_index = tab_li.index(this);
    $(".rank_unit_right01").eq(tab_li_index).show().siblings().hide();
});
$(".rank_unit_left").each(function(){
    $(this).find("p").hide();
    $(this).find("p").eq(0).show();
    $(this).children("li").hover(function(){
        $(this).addClass("current").siblings("li").removeClass("current");
        $(this).find("p").show();
        $(this).siblings("li").find("p").hide();
    });
})
/*jQuery(window).load(function () {
            jQuery("#ryl_random_logolist li img").each(function () {
                DrawImage(this, 106, 86);
            });
        });*/
function DrawImage(ImgD, FitWidth, FitHeight) {
            var image = new Image();
            image.src = ImgD.src;
            if (image.width > 0 && image.height > 0) {

                if (image.width / image.height >= FitWidth / FitHeight) {
                    if (image.width > FitWidth) {
                        ImgD.width = FitWidth;
                        ImgD.height = (image.height * FitWidth) / image.width;
                        ImgD.style.paddingTop = "2px";
                    } else {
                        ImgD.width = image.width;
                        ImgD.height = image.height;
                        ImgD.style.paddingTop = (FitHeight-image.height)/2 + "px";
                        ImgD.style.paddingLeft = (FitWidth-image.width)/2 + "px";
                    }
                } else {
                    if (image.height > FitHeight) {
                        ImgD.height = FitHeight;
                        ImgD.width = (image.width * FitHeight) / image.height;
                        ImgD.style.paddingLeft = (FitWidth-(image.width * FitHeight) / image.height)/2 + "px";

                    } else {
                        ImgD.width = image.width;
                        ImgD.height = image.height;
                        ImgD.style.paddingTop = (FitHeight-image.height)/2 + "px";
                        ImgD.style.paddingLeft = (FitWidth-image.width)/2 + "px";
                    }
                }
            }
     }
</script>
