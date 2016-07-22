<?php echo URL::webcss("platform/match.css")?>
<!--主体-->
<div class="wrap">
    <!--搜索-->
    <!--
    <div class="search">
        <span><input type="text" value=""/></span>
        <input type="submit" value="商机搜索" class="inputSubmit">
    </div>
    -->
    <!--热点词-->
    <!--
    <div class="keyWords" id="keyWords">
    <p>“作为<span>上班族</span>，您是不是<span>随时可以</span>
    <?php if(isset($project_list['inaword'][2]) && $project_list['inaword'][2] !=""):?>
        在<span><?php echo $project_list['inaword'][2];?></span>
    <?php endif;?>
    <?php if(isset($project_list['inaword'][7]) && $project_list['inaword'][7] !=""):?>
        投资<span><?php echo $project_list['inaword'][7];?></span>
    <?php endif;?>
    <?php if(isset($project_list['inaword'][6]) && $project_list['inaword'][6] !=""):?>
        的<span><?php echo $project_list['inaword'][6];?></span>
    <?php endif;?>
        项目？”</p>
    </div>
    -->
    <!--项目属性-->
    <!--
    <div class="projectList">
        <dl>
            <dt>很抱歉，没有为您匹配到七星项目，您可以继续选择以下项目属性来获取精准结果</dt>
            <dd><em>人脉关系：</em>有企事业单位关系   有政府关系   由学校关系  有医疗关系   有团购客户  其他</dd>
            <dd><em>投资风格：</em>低风险   高回报</dd>
            <dd><em>生意类型：</em>开店加盟  批发代理   网上销售</dd>
            <dd><em>投资回报率：</em>10%   10-50%   50%－100%   100%以上</dd>
        </dl>
    </div>
    -->
    <!---阴影条 -->
    <div class="shadowDiv_3"></div>
    <!---具体项目-->
    <div class="section">
        <h1>
            <?php if(isset($project_list['star_level']) && $project_list['star_level'] ==1){?>
            <em>一星匹配项目</em><span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <?php }elseif(isset($project_list['star_level']) && $project_list['star_level'] ==2){?>
            <em>二星匹配项目</em><span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <?php }elseif(isset($project_list['star_level']) && $project_list['star_level'] ==3){  ?>
            <em>三星匹配项目</em><span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <?php }elseif(isset($project_list['star_level']) && $project_list['star_level'] ==4){?>
            <em>四星匹配项目</em><span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <?php }elseif(isset($project_list['star_level']) && $project_list['star_level'] ==5){?>
            <em>五星匹配项目</em><span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <?php }elseif(isset($project_list['star_level']) && $project_list['star_level'] ==6){?>
            <em>六星匹配项目</em><span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <?php }else{?>
            <em>七星匹配项目</em><span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <span class="star"><img src="/images/platform/match/star.gif" width="16" height="15"/></span>
            <?php }?>
        </h1>
        <!--
        <div class="proList">
            <dl>
                <dt><a href="#"><img src="/images/platform/match/proImg.jpg" width="150" height="120"/></a>
                    <div class="card">递出名片</div>
                </dt>
                <dd>
                    <h2>
                        <em>童话故事童装</em>
                        <a href="#" class="aLink_0">
                            <span>名片</span>
                        </a>
                        <a href="#" class="aLink_1">
                            <span>名片</span>
                        </a>
                        <a href="#" class="aLink_2">
                            <span>名片</span>
                        </a>
                        <a href="#" class="aLink_3">
                            <span>名片</span>
                        </a>
                        <a href="#" class="aLink_4">
                            <span>名片</span>
                        </a>
                        <a href="#" class="aLink_5">
                            <span>名片</span>
                        </a>
                        <a href="#" class="aLink_6">
                            <span>名片</span>
                        </a>
                    </h2>
                    <p>项目简介：童话故事童装定位为年龄2-15岁，身高80-160厘米的儿童，面料以健康健康环保，舒适为色彩缤纷适合儿童活泼好动的天性；款式上简洁、时尚、经典，在设计时主要注重服装典，在设计时穿着时的安全性和舒适性。童话故事童装定位为年龄2-15岁。</p>
                </dd>
                <div class="clear"></div>
            </dl>
            <div class="promise"></div>
        </div>
        <div class="shadowDiv_1"></div>
 -->


<?php
if(count($project_list['list']) > 0){
foreach ($project_list['list'] as $k => $v){
?>
        <div class="proList">
            <dl>
                <dt><a href="<?php echo urlbuilder::project($v['project_id']);?>"><img src="<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>" width="150" height="120"/></a><input type="hidden" id="to_user_id"  value="<?=$v['com_id'];?>"><?php if($v['card'] !="ok"){?><div class="card">递出名片</div><?php }?></dt>
                <dd>
                    <h2>
                        <em><?=$v['project_brand_name'];?></em>
                        <a href="#" class="aLink_0">
                            <span>名片</span>
                        </a>
                        <a href="#" class="aLink_1">
                            <span>名片</span>
                        </a>
                        <a href="#" class="aLink_2">
                            <span>名片</span>
                        </a>
                        <a href="#" class="aLink_3">
                            <span>名片</span>
                        </a>
                        <a href="#" class="aLink_4">
                            <span>名片</span>
                        </a>
                        <a href="#" class="aLink_5">
                            <span>名片</span>
                        </a>
                        <a href="#" class="aLink_6">
                            <span>名片</span>
                        </a>
                    </h2>
                    <p style="word-break: break-all; width:748px;"><?php $summary= htmlspecialchars_decode($v['project_summary']);mb_substr(strip_tags($summary),0,140,'UTF-8').'...';?></p>
                </dd>
                <div class="clear"></div>
            </dl>
            <div class="promise"></div>
        </div>
        <div class="shadowDiv_1"></div>
<?php
}
}
?>
<?=$project_list['page'];?>
    </div>

    <div class="word">
        <p>这些都不是您想要的？</p>
        <p class="para_1"><span>您可以</span><span class="span_0"></span><span>或</span><span class="span_1"></span></p>
    </div>
</div>
<!--透明背景开始-->
<div id="opacity_box"></div>
<!--透明背景结束-->
<!--删除从业经验开始-->
<div id="box">
    <a href="#" class="close">关闭</a>
    <div class="btn">
        <p>确定要递出名片吗</p>
        <p><a href="#" class="ensure"><img src="/images/platform/my_exp/ensure.png" /></a>　<a href="#" class="cancel"><img src="/images/platform/my_exp/cancel.png" /></a></p>
    </div>
</div>
<!--删除从业经验结束-->
<?php echo URL::webjs("platform/match/hovereffect.js");?>
<?php echo URL::webjs("platform/slideBox.js");?>

