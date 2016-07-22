<?php echo URL::webcss("platform/common.css")?>
<?php echo URL::webcss("common.css")?>
<?php echo URL::webcss("platform/match.css")?>
<?php echo URL::webjs("platform/home/home.js");?>
<?php echo URL::webjs("platform/login/plat_login.js")?>
<?php echo URL::webjs("platform/match/hovereffect.js");?>
<script type="text/javascript">
$(function(){
    //登录框验证码 更改
    $('#changeCodeImg').click(function() {
        var url = '/captcha';
            url = url+'?'+RndNum(8);
            $("#vfCodeImg1").attr('src',url);
    });
    $(".proList li").not(".tags").hover(function(){
        $(this).css("background","#ddd");
    },function(){
        $(this).not(".tags").css("background","#fff")
    });
});
</script>
<!--主体-->
<div class="wrap">
    <!--搜索-->
    <div class="search" style="width:981px;">

        <form action="/platform/index/search" method="get" class="formStyle" id="formStyle">
            <span><input type="text" placeholder="<?=$wordShow?>"  id="word" name="w" style="color: rgb(0, 0, 0);"/></span>
            <input type="button" value="搜索" class="inputSubmit" id="inputSubmit"/>
        </form>
    </div>
    <!--热点词-->
    <?php
if(count($project_list['list']) > 0){

?>
    <div class="key-tags">
    <ul>
    <?php
    $i=0;
    if(count($keywords) > 0){
        foreach ($keywords as $k => $v){
            if(mb_strlen($k,'UTF8')>1) {
            $i++;
                if($i<=10){
    ?>
        <li><a href="<?php echo URL::website("platform/index/search")?>?w=<?php  echo $k;?>"><?php  echo $k;?></a></li>

    <?php
                }
            }
        }
    }
    ?>
    </ul>
    </div>
<?}?>
<?php
if(count($project_list['list']) > 0){

?>

    <!---具体项目-->
    <div class="section">
        <h1>
            <em>一句话搜索结果</em>
        </h1>
<?php
foreach ($project_list['list'] as $k => $v){
?>
        <div class="proList">
            <dl>
                <dt><a href="<?php echo urlbuilder::project($v['project_id'])?>"><img src="<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>"  width="150" height="120"/></a><input type="hidden" id="to_user_id"  value="<?=$v['com_user_id'];?>"><?php if($v['card'] !="ok"){?><div class="card2">已递出</div><div class="card">递出名片</div><?php }else{?><div class="card2">已递出</div><?php }?></dt>
                <dd>
                    <h2>
                        <em><a href="<?php echo urlbuilder::project($v['project_id'])?>" style="width:auto; color:#000;" class="proList_title_ryl">
                        <?=UTF8::substr($v['project_brand_name'],0, 20);?>
                        </a></em>
                        <ul>

                            <?php
                        if($v['showTag']){
                        foreach ($v['showTag'] as $key => $value){
                            if($key < $project_tag_count_show) {
                        ?>
                        <li class="tags">
                        <a href="<?php echo URL::website("platform/index/search")?>?w=<?php  echo $value;?>">
                        <?=UTF8::substr($value,0, 8);?>
                        </a></li>
                        <?php }}?>
                        <?php }?>
                        </ul>
                    </h2>
                    <p style="word-break: break-all; width:748px;">项目简介：<?php $sumary_text=htmlspecialchars_decode($v['project_summary']);  echo mb_substr(strip_tags($sumary_text),0,200,'UTF-8').'...';?></p>
                </dd>
                <div class="clear"></div>
            </dl>
            <div class="<?if($v['project_source'] == 3) {echo "promise";}else{echo "promise_noimg";}?>"></div>
        </div>
        <div class="shadowDiv_1"></div>
<?php

}
?>
<?=$project_list['page'];?>

    </div>
</div>
<?}else{?>

    <!--搜索-->
    <!--热点词-->
    <div class="keyWords2" id="keyWords">
        <p>抱歉！没有找到与“
            <?php
    if(count($keywords) > 0){
        foreach ($keywords as $k => $v){
    ?>
            <span class="tags_aa"><?php  echo $k;?></span>
        <?php
        }
    }?>”相关的项目</p>
    </div>
    <!--项目属性-->
    <div class="projectList">
        <dl>
            <dd style="border-top:none;border-bottom:1px dashed #dcdcdc;margin-right:5px;"><b>试试大家正在搜的热门标签？</b></dd>
            <dd style="height:auto;border-top:none;">
                <a href="<?php echo URL::website("platform/index/search")?>?w=服装生意" class="tags_bb">服装生意</a>
                <a href="<?php echo URL::website("platform/index/search")?>?w=安徽" class="tags_bb">安徽</a>
            </dd>
        </dl>
    </div>
    <!---阴影条 -->
    <div class="shadowDiv_0"></div>
    <!---具体项目-->
    <div class="per_service" style=" margin-bottom:10px; height:323px;">
        <img src="<?php echo URL::webstatic('images/platform/home/zs_result.jpg');?>" style="border:1px solid #e0e0e0;"/>
    </div>

<?}?>
<!--透明背景开始-->
<div id="opacity_box"></div>
<!--透明背景结束-->
<!--递出名片层开始-->
<div id="send_box">
    <a href="#" class="close">关闭</a>
    <div id="msgcontent">
    </div>
</div>
<!--递出名片层结束-->
    <!--登陆-弹出框开始-->

    <!--登陆-弹出框结束-->
