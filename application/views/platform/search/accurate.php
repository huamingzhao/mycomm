<?php echo URL::webjs("platform/filter/swfobject.js");?>
<?php echo URL::webjs("platform/filter/json2.js");?>
<?php echo URL::webjs("platform/filter/filter.js");?> 

<div id="filter">
    <div id="filter_bt">
        <div id="filter_tp">
            <div id="filter_rp">
                <h1>项目匹配系统</h1>
                <div id="accurate_page">
                    <p class="tishi">根据您回答的问题，将为您匹配出最适合您的项目，成为您做生意的首选。</p>
                    <div class="question">
                        <div class="left">
                            <div class="subject">
                                <div class="ask_a">
                                    <ul>
                                        <?php $sup = 1; /*计数器*/ foreach($qst as $qstid=>$q){?>
                                        <li id="li_<?=$qstid?>" class="li_<?=$sup?>">
                                            <h2><sup><?=$sup?></sup><?=$q['q']?><span>( <b><?=$sup?></b>/6 )</span></h2>

                                            <?php if($qstid ==6){?>
                                            <!-- 第六题 -->
                                            <div class="hangyWrap">
                                                <div class="hangy1">
                                                    <?php foreach ($q['attr'] as $hy_id=>$hy_name){?>
                                                        <span><a class="hy_sub" href="#" hy="<?=$hy_id?>"><?=$hy_name?></a></span> <?php if($hy_id != 7){?>|<?php }?>
                                                    <?php }?>
                                                </div>
                                                <div class="hangy2">
                                                <?php if(isset($sub_industry) and $sub_industry){ foreach($sub_industry as $sub_idy_id=>$sub_idy_name){?>
                                                    <span id="<?=$sub_idy_id?>"><a href="#"><?=$sub_idy_name?></a></span>
                                                <?php }}?>
                                                </div>
                                            </div>
                                            <!-- 第六题 -->

                                            <!-- 非第二期 属性-->
                                            <?php }elseif($qstid !=2){?>

                                            <?php if(!empty($q['attr'])){
                                            foreach($q['attr'] as $attr_id=>$attr){
                                            ?>
                                            <p>
                                                <input type="radio" name="question<?=$qstid?>"
                                                <?php if(Arr::get($qconfig,$qstid) == $attr_id){?>
                                                    checked="checked"
                                                <?php }?>
                                                value="<?=$attr_id?>" id="question<?=$qstid.$attr_id?>" q="<?=$qstid?>" />
                                                <label for="question<?=$qstid.$attr_id?>"><span><?=$attr?></span></label>
                                            </p>
                                            <?php }
                                            }
                                            ?>

                                            <!-- 第二期 属性-->
                                            <?php }else{?>
                                                <div class="shengWrap">
                                                    <div class="sheng" id="sheng">
                                                        <span class="quanguo"><em id="88">全国</em></span> |
                                                        <span><a title="广东省" href="#">广东省</a></span> |
                                                        <span id="02"><a title="北京市" href="#">北京市</a></span> |
                                                        <span id="03"><a title="天津市" href="#">天津市</a></span> |
                                                        <span><a title="河北省" href="#">河北省</a></span> |
                                                        <span><a title="山西省" href="#">山西省</a></span> |
                                                        <span><a title="内蒙古自治区" href="#">内蒙古自治区</a></span> |
                                                        <span><a title="内蒙古自治区" href="#">辽宁省</a></span> |
                                                        <span><a title="吉林省" href="#">吉林省</a></span> |
                                                        <span><a title="黑龙江省" href="#">黑龙江省</a></span> |
                                                        <span id="10"><a title="上海市" href="#">上海市</a></span> |
                                                        <span><a title="江苏省" href="#">江苏省</a></span> |
                                                        <span><a title="浙江省" href="#">浙江省</a></span> |
                                                        <span><a title="安徽省" href="#">安徽省</a></span> |
                                                        <span><a title="福建省" href="#">福建省</a></span> |
                                                        <span><a title="江西省" href="#">江西省</a></span> |
                                                        <span><a title="山东省" href="#">山东省</a></span> |
                                                        <span><a title="河南省" href="#">河南省</a></span> |
                                                        <span><a title="湖北省" href="#">湖北省</a></span> |
                                                        <span><a title="湖南省" href="#">湖南省</a></span> |
                                                        <span><a title="广西壮族" href="#">广西壮族</a></span> |
                                                        <span><a title="海南省" href="#">海南省</a></span> |
                                                        <span id="22"><a title="重庆市" href="#">重庆市</a></span> |
                                                        <span><a title="四川省" href="#">四川省</a></span> |
                                                        <span><a title="贵州省" href="#">贵州省</a></span> |
                                                        <span><a title="云南省" href="#">云南省</a></span> |
                                                        <span><a title="西藏" href="#">西藏</a></span> |
                                                        <span><a title="陕西省" href="#">陕西省</a></span> |
                                                        <span><a title="甘肃省" href="#">甘肃省</a></span> |
                                                        <span><a title="青海省" href="#">青海省</a></span> |
                                                        <span><a title="宁夏回族" href="#">宁夏回族</a></span> |
                                                        <span><a title="新疆维吾尔" href="#">新疆维吾尔</a></span> |
                                                        <span><a title="台湾省" href="#">台湾省</a></span> |
                                                        <span id="33"><a title="香港特别行政区" href="#">香港特别行政区</a></span> |
                                                        <span id="34"><a title="澳门特别行政区" href="#">澳门特别行政区</a></span>
                                                    </div>
                                                    <div class="city" id="city"></div>
                                                </div>
                                                <div class="hiddenDiv"></div>
                                            <?php }?>

                                        </li>
                                        <?php $sup++; }?>
                                        <!--<li id="li_11" class="li_7">
                                            <h3 class="aa">恭喜您完成了项目匹配！</h3>
                                            <div class="bb">
                                                <h3>抱歉，没有搜索到与您完全匹配的项目</h3>
                                                <div class="tj_project">
                                                    <div class="tj_tishi">根据您的回答，为您推荐的项目：</div>
                                                    <div>
                                                        <dl id="proj_dl">

                                                        </dl>
                                                        <div class="clear"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>-->
                                    </ul>
                                    <div class="clear"></div>
                                    <div class="result">
                                        <p class="oneword">您搜索的一句话为：
                                        <span id="one_sentence"></span>
                                        </p>

                                        <div style="height:25px;"><p class="li_last">根据您的一句话，现在为您匹配出项目为<span></span>个项目。</p></div>
                                        <!--<p class="nopro">抱歉！没有找到相匹配的项目。</p>-->
                                    </div>
                                    <div class="btn">
                                        <button id="pre">上一项</button><button id="skip">下一项</button><span id="last">
                                    </div>
                                    <div class="clearGuide">
                                        <a title="清空答题记录" href="#" id="qk">清空答题记录</a>
                                    </div>
                                    </div>
                                    <div class="ask_b">
                                        <p>抱歉！您还没有回答任何问题，我们无法为您匹配项目</p>
                                        <!--隐藏精准匹配<p><a title="" href="<?php echo urlbuilder::zhuntouzi();?>" id="re_ask"></a></p>-->
                                    </div>
                                </div>
                                <!--进度条开始-->
                                <div class="progress">
                                    <ul>
                                        <li id="one"></li>
                                        <!-- 临时方案 -->
                                        <li <?php if(isset($qconfig[2])){?>class="all"<?php }?>><span></span></li>
                                        <!-- 临时方案 -->
                                        <li></li>
                                        <li <?php if(isset($qconfig[6])){?>class="all"<?php }?>></li>
                                        <li></li>
                                        <li id="two"></li>
                                    </ul>
                                </div>
                                <!--进度条结束-->
                            </div>
                        </div>
                        <div class="right">
                            <!--flash开始-->
                            <div class="flash">
                                <div>
                                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
                            codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" id="fileId" width="400" height="460">
                                        <param name="movie" value="<?php echo URL::webstatic("flash/logowall.swf");?>" />
                                        <param name="quality" value="high" />
                                        <param name="wmode" value="transparent" />
                                        <param name="wmode" value="opaque">
                                        <param name="allowScriptAccess" value="always" />
                                        <embed src="<?php echo URL::webstatic("flash/logowall.swf");?>" allowScriptAccess="always"  name="fileId1" width="400" height="460" quality="high" pluginspage=http://www.macromedia.com/go/getflashplayer type="application/x-shockwave-flash" wmode="opaque"></embed>
                                     </object>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="opacity"></div>
<div class="wait_for_filter">
	<p><img alt="正在为您匹配项目，请稍后..." src="<?php echo URL::webstatic("images/platform/filter/wait_icon.gif");?>" /><span>正在为您匹配项目，请稍后...</span></p>
    <div class="clear"></div>
</div>
<script>
var img = $(".subject ul");
$(".progress ul li").removeClass();
$(".answer ul li span").html("");
$(".subject ul li input").attr("checked",false);
$("#one_sentence").html("我想做项目");

$(".nopro").hide();
$("#last2").hide();
$("#last4").hide();
$(".li_last").hide();

$("#skip").hide();
$(".li_last1").show();
$("#proj_dl").html("");
//跳回第一题
var subject_li = $(".subject ul li");
if(img.find("li").eq(0).attr("class")=="li_2"){
    for(var i=0;i<4;i++){
        img.find("li").eq(0).appendTo(img);
    }
    img.animate({'margin-left':'-460px'},function(){
        img.css({'margin-left':0});
    })
    img.find("li").eq(0).appendTo(img);
}else if(img.find("li").eq(0).attr("class")=="li_3"){
    for(var i=0;i<3;i++){
        img.find("li").eq(0).appendTo(img);
    }
    img.animate({'margin-left':'-460px'},function(){
        img.css({'margin-left':0});
    })
    img.find("li").eq(0).appendTo(img);
}else if(img.find("li").eq(0).attr("class")=="li_4"){
    for(var i=0;i<2;i++){
        img.find("li").eq(0).appendTo(img);
    }
    img.animate({'margin-left':'-460px'},function(){
        img.css({'margin-left':0});
    })
    img.find("li").eq(0).appendTo(img);
}else if(img.find("li").eq(0).attr("class")=="li_5"){
    for(var i=0;i<1;i++){
        img.find("li").eq(0).appendTo(img);
    }
    img.animate({'margin-left':'-460px'},function(){
        img.css({'margin-left':0});
    })
    img.find("li").eq(0).appendTo(img);
}else if(img.find("li").eq(0).attr("class")=="li_6"){
    img.animate({'margin-left':'-460px'},function(){
        img.css({'margin-left':0});
    })
    img.find("li").eq(0).appendTo(img);
}
$.ajax({
    type: "post",
    dataType: "json",
    url: "/platform/Ajaxcheck/clearguide",
    data: "",
    complete :function(){
    },
    success: function(msg){
    }
});
$("#pre").hide();
$("#city").html("");
$("#sheng span a").removeClass("addRedBg");
$(".hangy2").html("");
</script>