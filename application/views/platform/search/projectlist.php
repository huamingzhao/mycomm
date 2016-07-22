<?php echo URL::webcss("platform/match.css")?>
<?php echo URL::webcss("common.css")?>
<?php echo URL::webjs("jquery.cookies.2.2.0.js");?>
<?php echo URL::webjs("platform/match/hovereffect.js");?>
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
<style>
.section .proList dd a {
    cursor: pointer;
    display: block;
    float: left;
    height: 33px;
    margin-right: 8px;
    position: relative;
    width: 32px;
}
</style>
<!--主体-->
<div class="wrap">
    <!--搜索-->

    <!--热点词-->
     <?if(isset($project_list['inaword']) && $project_list['inaword']) {?>
    <div class="keyWords" id="keyWords">

        <div class="otherWord">“我</div>
    <?php if(isset($project_list['inaword'][3])):?>
    <div class="otherWord">是</div>
    <div class="keywordDiv"><span><?php echo $project_list['inaword'][3]['name'];?></span><input type="hidden" name="str3" id="str3" value="3"/>
        <div class="subNav">
            <ul></ul>
            <div class="clear"></div>
            <div class="minNav"></div>
        </div>
        <div class="closeBtn"></div>
    </div>

    <div class="otherWord">，</div>
    <?php endif;?>
    <?php if(isset($project_list['inaword'][5])):?>
        <div class="otherWord">有</div>
   <div class="keywordDiv"><span><?php echo $project_list['inaword'][5]['name'];?></span><input type="hidden" name="str5" id="str5" value="5"/>
           <div class="subNav" id="subNavMargin1">
            <ul></ul>
            <div class="clear"></div>
            <div class="minNav"></div>
        </div>
        <div class="closeBtn"></div>
    </div>

    <?php endif;?>
    <?php if(isset($project_list['inaword'][7])):?>
    <div class="otherWord">有</div>
   <div class="keywordDiv"><span><?php echo $project_list['inaword'][7]['name'];?></span><input type="hidden" name="str7" id="str7" value="7"/>
           <div class="subNav">
            <ul></ul>
            <div class="clear"></div>
            <div class="minNav"></div>
        </div>
        <div class="closeBtn"></div>
    </div>

    <?php endif;?>
    <?php if(isset($project_list['inaword'][4])):?>
    <div class="otherWord">想</div>
   <div class="keywordDiv"><span><?php echo $project_list['inaword'][4]['name'];?></span><input type="hidden" name="str4" id="str4" value="4"/>
           <div class="subNav">
            <ul></ul>
            <div class="clear"></div>
            <div class="minNav"></div>
        </div>
        <div class="closeBtn"></div>
    </div>

    <?php endif;?>
    <?php if(isset($project_list['inaword'][9])):?>
   <div class="keywordDiv"><span><?php echo $project_list['inaword'][9]['name'];?></span><input type="hidden" name="str9" id="str9" value="9"/>
           <div class="subNav">
            <ul></ul>
            <div class="clear"></div>
            <div class="minNav"></div>
        </div>
        <div class="closeBtn"></div>
    </div>

    <?php endif;?>
    <?php if(isset($project_list['inaword'][2])):?>
    <div class="otherWord">在</div>
    <div class="keywordDiv" id="wordProvinc"><span class="provinceBtn"><?php echo $project_list['inaword'][2]['name'];?></span><input type="hidden" name="str2" id="str2" value="2"/>
        <div class="tabSelect tabSelect2">
            <div class="tabTop" id="tabTop">
                <div class="moveBg" id="moveBg"></div>
                <ul>

                    <li class="addBorder">省份</li>
                    <li class="borderLast">城市</li>
                </ul>
            </div>
            <div class="tabContent">
                <div class="tabContent1" id="tabContent1"></div>
                <div class="tabContent1 tabContent2" id="tabContent2">
                    <dl>
                        <dt>A-G</dt>
                        <dd>
                            <a href="#">安徽<input type="hidden" name="" id="13"/></a>
                            <a href="#">北京<input type="hidden" name="" id="02"/></a>
                            <a href="#">重庆<input type="hidden" name="" id="22"/></a>
                            <a href="#">福建<input type="hidden" name="" id="14"/></a>
                            <a href="#">甘肃<input type="hidden" name="" id="28"/></a>
                            <a href="#">广东<input type="hidden" name="" id="01"/></a>
                            <a href="#">广西<input type="hidden" name="" id="20"/></a>
                            <a href="#">贵州<input type="hidden" name="" id="24"/></a>
                        </dd>
                        <div class="clear"></div>
                    </dl>
                    <dl>
                        <dt>H-K</dt>
                        <dd>
                            <a href="#">海南<input type="hidden" name="" id="21"/></a>
                            <a href="#">河北<input type="hidden" name="" id="04"/></a>
                            <a href="#">河南<input type="hidden" name="" id="17"/></a>
                            <a href="#">黑龙江<input type="hidden" name="" id="09"/></a>
                            <a href="#">湖北<input type="hidden" name="" id="18"/></a>
                            <a href="#">湖南<input type="hidden" name="" id="19"/></a>
                            <a href="#">吉林<input type="hidden" name="" id="08"/></a>
                            <a href="#">江苏<input type="hidden" name="" id="11"/></a>
                            <a href="#">江西<input type="hidden" name="" id="15"/></a>

                        </dd>
                        <div class="clear"></div>
                    </dl>
                    <dl>
                        <dt>L-S</dt>
                        <dd>
                            <a href="#">辽宁<input type="hidden" name="" id="07"/></a>
                            <a href="#">内蒙古<input type="hidden" name="" id="06"/></a>
                            <a href="#">宁夏<input type="hidden" name="" id="30"/></a>
                            <a href="#">青海<input type="hidden" name="" id="29"/></a>
                            <a href="#">山东<input type="hidden" name="" id="16"/></a>
                            <a href="#">山西<input type="hidden" name="" id="05"/></a>
                            <a href="#">上海<input type="hidden" name="" id="10"/></a>
                            <a href="#">四川<input type="hidden" name="" id="23"/></a>
                            <a href="#">陕西<input type="hidden" name="" id="27"/></a>

                        </dd>
                        <div class="clear"></div>
                    </dl>
                    <dl>
                        <dt>T-Z</dt>
                        <dd>
                            <a href="#">天津<input type="hidden" name="" id="03"/></a>
                            <a href="#">西藏<input type="hidden" name="" id="26"/></a>
                            <a href="#">新疆<input type="hidden" name="" id="31"/></a>
                            <a href="#">云南<input type="hidden" name="" id="25"/></a>
                            <a href="#">浙江<input type="hidden" name="" id="12"/></a>
                        </dd>
                        <div class="clear"></div>
                    </dl>
                    <div class="clear"></div>
                </div>
                <div class="tabContent1" id="tabContent3"></div>
                <div class="clear"></div>
            </div>
        </div>
        <div class="closeBtn"></div>
    </div>

    <?php endif;?>
    <?php if(isset($project_list['inaword'][10])):?>
    <div class="otherWord">做</div>
   <div class="keywordDiv"><span><?php echo $project_list['inaword'][10]['name'];?></span><input type="hidden" name="str10" id="str10" value="10"/>
           <div class="subNav">
            <ul></ul>
            <div class="clear"></div>
            <div class="minNav"></div>
            <div class="clear"></div>
        </div>
        <div class="closeBtn"></div>
    </div>

    <?php endif;?>
    <?php if(isset($project_list['inaword'][8])):?>
    <div class="otherWord">回报率为</div>
   <div class="keywordDiv"><span><?php echo $project_list['inaword'][8]['name'];?></span><input type="hidden" name="str8" id="str8" value="8"/>
           <div class="subNav">
            <ul></ul>
            <div class="clear"></div>
            <div class="minNav"></div>
        </div>
        <div class="closeBtn"></div>
    </div>

    <div class="otherWord">的</div>
    <?php endif;?>
    <?php if(isset($project_list['inaword'][6])):?>
   <div class="keywordDiv"><span><?php echo $project_list['inaword'][6]['name'];?></span><input type="hidden" name="str6" id="str6" value="6"/>
        <div class="subNav" id="subNav">
            <ul></ul>
            <div class="clear"></div>
            <div class="minNav"></div>
        </div>
        <div class="closeBtn"></div>
    </div>

    <div class="otherWord">类</div>
    <?php endif;?>
    <?php if(isset($project_list['inaword'][1])):?>
    <div class="otherWord">做</div>
   <div class="keywordDiv"><span><?php echo $project_list['inaword'][1]['name'];?></span><input type="hidden" name="str1" id="str1" value="1"/>
           <div class="subNav">
            <ul></ul>
            <div class="clear"></div>
            <div class="minNav"></div>
        </div>
        <div class="closeBtn"></div>
    </div>

    <?php endif;?>
    <div class="otherWord">项目” </div>

    </div>
     <?}?>
    <!--项目属性-->
    <div class="projectList" id="projectList">
        <dl>
        <!--
            <dt>很抱歉，没有为您匹配到七星项目，您可以继续选择以下项目属性来获取精准结果</dt>
            -->
            <?php if(!isset($project_list['inaword'][1])):?>
            <dd><em>生意类型：</em>
                <?php $str1=guide::attr1(); foreach ($str1 as $k=>$v){?>
                <span><input type="hidden" name="str1" id="str1" value="<?php echo $k;?>"><?php echo $v;?></span>
                <?php } ?>
            </dd>
            <?php endif;?>
            <?php if(!isset($project_list['inaword'][2])):?>
            <dd><em>地区：</em>
                <span><input type="hidden" name="str2" id="str2" value="10" class="10">上海</span>
                <span><input type="hidden" name="str2" id="str2" value="1" class="01">广东</span>
                <span><input type="hidden" name="str2" id="str2" value="2" class="02">北京</span>
                <span><input type="hidden" name="str2" id="str2" value="3" class="03">天津</span>
                <span><input type="hidden" name="str2" id="str2" value="4" class="04">河北</span>
                <span><input type="hidden" name="str2" id="str2" value="5" class="05">山西</span>
                <div class="moreChoice" id="moreChoice"><a href="#" class="amoreClick" id="amoreClick">查看更多</a>
                    <div class="tabSelect">
                        <div class="tabTop" id="tabTop">
                            <div class="moveBg" id="moveBg"></div>
                            <ul>
                                <li class="addBorder">省份</li>
                                <li class="borderLast">城市</li>
                            </ul>
                        </div>
                        <div class="tabContent">
                            <div class="tabContent1" id="tabContent1"></div>
                            <div class="tabContent1 tabContent2" id="tabContent2">
                                <dl>
                                    <dt>A-G</dt>
                                    <dd>
                                        <a href="#">安徽<input type="hidden" name="" id="13"/></a>
                                        <a href="#">北京<input type="hidden" name="" id="02"/></a>
                                        <a href="#">重庆<input type="hidden" name="" id="22"/></a>
                                        <a href="#">福建<input type="hidden" name="" id="14"/></a>
                                        <a href="#">甘肃<input type="hidden" name="" id="28"/></a>
                                        <a href="#">广东<input type="hidden" name="" id="01"/></a>
                                        <a href="#">广西<input type="hidden" name="" id="20"/></a>
                                        <a href="#">贵州<input type="hidden" name="" id="24"/></a>
                                    </dd>
                                    <div class="clear"></div>
                                </dl>
                                <dl>
                                    <dt>H-K</dt>
                                    <dd>
                                        <a href="#">海南<input type="hidden" name="" id="21"/></a>
                                        <a href="#">河北<input type="hidden" name="" id="04"/></a>
                                        <a href="#">河南<input type="hidden" name="" id="17"/></a>
                                        <a href="#">黑龙江<input type="hidden" name="" id="09"/></a>
                                        <a href="#">湖北<input type="hidden" name="" id="18"/></a>
                                        <a href="#">湖南<input type="hidden" name="" id="19"/></a>
                                        <a href="#">吉林<input type="hidden" name="" id="08"/></a>
                                        <a href="#">江苏<input type="hidden" name="" id="11"/></a>
                                        <a href="#">江西<input type="hidden" name="" id="15"/></a>
                                    </dd>
                                    <div class="clear"></div>
                                </dl>
                                <dl>
                                    <dt>L-S</dt>
                                    <dd>
                                        <a href="#">辽宁<input type="hidden" name="" id="07"/></a>
                                        <a href="#">内蒙古<input type="hidden" name="" id="06"/></a>
                                        <a href="#">宁夏<input type="hidden" name="" id="30"/></a>
                                        <a href="#">青海<input type="hidden" name="" id="29"/></a>
                                        <a href="#">山东<input type="hidden" name="" id="16"/></a>
                                        <a href="#">山西<input type="hidden" name="" id="05"/></a>
                                        <a href="#">上海<input type="hidden" name="" id="10"/></a>
                                        <a href="#">四川<input type="hidden" name="" id="23"/></a>
                                        <a href="#">陕西<input type="hidden" name="" id="27"/></a>

                                    </dd>
                                    <div class="clear"></div>
                                </dl>
                                <dl>
                                    <dt>T-Z</dt>
                                    <dd>
                                        <a href="#">天津<input type="hidden" name="" id="03"/></a>
                                        <a href="#">西藏<input type="hidden" name="" id="26"/></a>
                                        <a href="#">新疆<input type="hidden" name="" id="31"/></a>
                                        <a href="#">云南<input type="hidden" name="" id="25"/></a>
                                        <a href="#">浙江<input type="hidden" name="" id="12"/></a>
                                    </dd>
                                    <div class="clear"></div>
                                </dl>
                                <div class="clear"></div>
                            </div>
                            <div class="tabContent1" id="tabContent3"></div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </dd>
            <?php endif;?>
            <?php if(!isset($project_list['inaword'][5])):?>
            <dd><em>人脉关系：</em>
                <?php $str5=guide::attr5(); foreach ($str5 as $k=>$v){?>
                <span><input type="hidden" name="str5" id="str5" value="<?php echo $k;?>"><?php echo $v;?></span>
                <?php } ?>
            </dd>
            <?php endif;?>
            <?php if(!isset($project_list['inaword'][6])):?>
            <dd><em>行业产品：</em>
                <?php $str6=guide::attr6(); foreach ($str6 as $k=>$v){?>
                <span><input type="hidden" name="str6" id="str6" value="<?php echo $k;?>"><?php echo $v;?></span>
                <?php } ?>
            </dd>
            <?php endif;?>
            <?php if(!isset($project_list['inaword'][7])):?>
            <dd><em>金额：</em>
                <?php $str7=guide::attr7(); foreach ($str7 as $k=>$v){?>
                <span><input type="hidden" name="str7" id="str7" value="<?php echo $k;?>"><?php echo $v;?></span>
                <?php } ?>
            </dd>
            <?php endif;?>
            <?php if(!isset($project_list['inaword'][8])):?>
            <dd><em>投资回报率：</em>
                <?php $str8=guide::attr8(); foreach ($str8 as $k=>$v){?>
                <span><input type="hidden" name="str8" id="str8" value="<?php echo $k;?>"><?php echo $v;?></span>
                <?php } ?>
            </dd>
            <?php endif;?>
            <?php if(!isset($project_list['inaword'][10])):?>
             <dd><em>投资风格：</em>
                <?php $str10=guide::attr10(); foreach ($str10 as $k=>$v){?>
                <span><input type="hidden" name="str10" id="str10" value="<?php echo $k;?>"><?php echo $v;?></span>
                <?php } ?>
             </dd>
            <?php endif;?>
        </dl>
    </div>
    <!---阴影条 -->
    <div class="shadowDiv_3"></div>

    <!---具体项目-->
    <div class="section">
        <?if(isset($project_list['inaword']) && $project_list['inaword']) {?>
        <h1>
            <?php if(isset($project_list['star_level']) && $project_list['star_level'] ==1){?>
            <em>一星匹配项目</em><span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <?php }elseif(isset($project_list['star_level']) && $project_list['star_level'] ==2){?>
            <em>二星匹配项目</em><span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <?php }elseif(isset($project_list['star_level']) && $project_list['star_level'] ==3){  ?>
            <em>三星匹配项目</em><span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <?php }elseif(isset($project_list['star_level']) && $project_list['star_level'] ==4){?>
            <em>四星匹配项目</em><span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <?php }elseif(isset($project_list['star_level']) && $project_list['star_level'] ==5){?>
            <em>五星匹配项目</em><span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <?php }elseif(isset($project_list['star_level']) && $project_list['star_level'] ==6){?>
            <em>六星匹配项目</em><span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <?php }elseif(isset($project_list['star_level']) && $project_list['star_level'] ==7){?>
            <em>七星匹配项目</em><span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <span class="star"><img src="<?php echo URL::webstatic("/images/platform/match/star.gif")?>" width="16" height="15"/></span>
            <?php }else{?>

            <?}?>
        </h1>
     <?}?>
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
    if(isset($v['project_id'])) {
?>
        <div class="proList">
            <dl>
                <dt><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank"><img src="<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>" width="150" height="120"/></a><input type="hidden" id="to_user_id"  value="<?=$v['com_user_id'];?>"><?php if($v['card'] !="ok"){?><div class="card2">已递出</div><div class="card">递出名片</div><?php }else{?><div class="card2">已递出</div><?php }?></dt>
                <dd>
                    <h2>
                        <em><?=$v['project_brand_name'];?></em>
                        <a href="<?php echo urlbuilder::projectEnd($v['project_id'])?>" target="_blank" class="aLink_0">
                            <span>名片</span>
                        </a>
                        <a href="<?php echo urlbuilder::project($v['project_id'])?>" target="_blank" class="aLink_1">
                            <span>详情</span>
                        </a>
                        <?php if($v['iconStatus']['ispage']) {?>
                        <a href="<?php echo urlbuilder::projectPoster($v['project_id']);?>" target="_blank" class="aLink_2">
                            <span>海报</span>
                        </a>
                        <?}?>
                       <?if($v['iconStatus']['proImgStatus']) {?>
                        <a href="<?php echo urlbuilder::projectImages($v['project_id'])?>" target="_blank" class="aLink_3">
                            <span>图片</span>
                        </a>
                        <?}?>
                        <?if($v['iconStatus']['proInvestStatus']) {?>
                        <a href="<?php echo urlbuilder::projectInvest($v['project_id'])?>" target="_blank" class="aLink_4">
                            <span>招商会</span>
                        </a>
                        <?}?>
                        <?if($v['iconStatus']['projectcertsStatus']) {?>
                        <a href="<?php echo urlbuilder::projectCerts($v['project_id'])?>" target="_blank" class="aLink_6">
                            <span>资质</span>
                        </a>
                        <?}?>
                    </h2>
                    <p><?php $summary= htmlspecialchars_decode($v['project_summary']);echo mb_strimwidth(strip_tags($summary),0,250,"......");?></p>
                </dd>
                <div class="clear"></div>
            </dl>
            <div class="<?if($v['project_source'] == 3) {echo "promise";}else{echo "promise_noimg";}?>"></div>
        </div>
        <div class="shadowDiv_1"></div>
<?php
    }
}
}else{
?>
<p style="color:#333333;line-height:35px;text-align:center;font-size:14px;">没有为你匹配到你想要找到的项目！</p>
<?php
}
?>
    </div>
<?php    if(count($project_list['list_make_up']) > 0){?>
    <div class="section">
        <h1 class="diffBg">
            <em>查看更多推荐项目</em>
        </h1>
<?php

foreach ($project_list['list_make_up'] as $k => $v){
?>
        <div class="proList">
            <dl>
                <dt><a href="<?php echo urlbuilder::project($v['project_id']);?>"><img src="<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>" width="150" height="120" target="_blank"/></a><input type="hidden" id="to_user_id"  value="<?=$v['com_user_id'];?>"><?php if($v['card'] !="ok"){?><div class="card2">已递出</div><div class="card">递出名片</div><?php }else{?><div class="card2">已递出</div><?php }?></dt>
                <dd>
                    <h2>
                        <em><?=$v['project_brand_name'];?></em>
                        <a href="<?php echo urlbuilder::projectEnd($v['project_id'])?>" class="aLink_0" target="_blank">
                            <span>名片</span>
                        </a>
                        <a href="<?php echo urlbuilder::project($v['project_id']);?>" class="aLink_1" target="_blank">
                            <span>详情</span>
                        </a>
                        <?php if($v['iconStatus']['ispage']) {?>
                        <a href="<?php echo urlbuilder::projectPoster($v['project_id'])?>" class="aLink_2" target="_blank">
                            <span>海报</span>
                        </a>
                        <?}?>
                        <?if($v['iconStatus']['proImgStatus']) {?>
                        <a href="<?php echo urlbuilder::projectImages($v['project_id'])?>" class="aLink_3" target="_blank">
                            <span>图片</span>
                        </a>
                        <?}?>
                        <?if($v['iconStatus']['proInvestStatus']) {?>
                        <a href="<?php echo urlbuilder::projectInvest($v['project_id'])?>" class="aLink_4" target="_blank">
                            <span>招商会</span>
                        </a>
                        <?}?>
                        <?if($v['iconStatus']['projectcertsStatus']) {?>
                        <a href="<?php echo urlbuilder::projectCerts($v['project_id'])?>" class="aLink_6" target="_blank">
                            <span>资质</span>
                        </a>
                        <?}?>
                    </h2>
                    <p><?php $summary= htmlspecialchars_decode($v['project_summary']);echo mb_strimwidth(strip_tags($summary),0,250,"......");?></p>
                </dd>
                <div class="clear"></div>
            </dl>
            <div class="<?if($v['project_source'] == 3) {echo "promise";}else{echo "promise_noimg";}?>"></div>
        </div>
        <div class="shadowDiv_1"></div>
<?php }}?>
<?=$project_list['page'];?>
    </div>

    <div class="word">
        <p>这些都不是您想要的？</p>
        <p class="para_1"><span>您可以</span><span><a href="#top" target="_self"><img src="<?php echo URL::webstatic("/images/platform/match/btn1.gif")?>" width="117" height="31"/></a></span><span>或</span><span><a id="qk" style="cursor:pointer;"><img src="<?php echo URL::webstatic("/images/platform/match/btn2.gif")?>" width="117" height="31"/></a></span></p>
    </div>
</div>
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


