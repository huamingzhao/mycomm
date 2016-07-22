<?php echo URL::webjs("platform/template/yellow.js");?>
<?php echo URL::webjs("platform/login/plat_login.js")?>
<?php echo URL::webcss("platform/login_new.css")?>
<script type="text/javascript">
var tj_type_id = 1;
var tj_pn_id = <?php echo $projectinfo->project_id;?>;
$(document).ready(function(){
    //用户访问项目的记录
     var projectid = <?php echo $projectinfo->project_id;?>;
     $.ajax({
         type: "post",
         dataType: "json",
         url: "/platform/ajaxcheck/addPersonAboutPro",
         data: "projectid="+projectid,
         complete :function(){
         },
         success: function(msg){
         }
    });
    $('#changeCodeImg').click(function() {
            var url = '/captcha';
                url = url+'?'+RndNum(8);
                $("#vfCodeImg1").attr('src',url);
        });

});

function setVisit(){
    var projectid = <?php echo $projectinfo->project_id;?>;
    $.ajax({
        type: "post",
        dataType: "json",
        url: "/platform/ajaxcheck/setVisit",
        data: "projectid="+projectid,
        complete :function(){
        },
        success: function(msg){
        }
   });
}
</script>
<style>
#yellow #center .contant .ct h3 a {
    color: #B4B4B4;
    font-size: 12px;
    font-weight: normal;
    padding-top: 5px;
    position: absolute;
    right: 0;
}
</style>
<!--中部开始-->
<div id="yellow">
    <div id="center"  style="height:auto">
        <div id="title">
            <div class="logo_bg"><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>"><img src="<?php
            if($projectinfo->project_source != 1) {
                $tpurl=project::conversionProjectImg($projectinfo->project_source, 'logo', $projectinfo->as_array());if(project::checkProLogo($tpurl)){echo $tpurl;} else{echo URL::webstatic('images/common/company_default.jpg');}}
            else {
                $tpurl=URL::imgurl($projectinfo->project_logo);
                 if(project::checkProLogo($tpurl)){echo $tpurl;} else{echo URL::webstatic('images/common/company_default.jpg');}
            } ?>" alt="<?php echo mb_substr($projectinfo->project_brand_name,0,12,'UTF-8');?>" /></a></div>
            <div class="name">
                <div class="a" alt="<?php echo $projectinfo->project_brand_name; ?>">
                <h1 style="font-weight:normal; font-size:26px; float:left;"><?php echo mb_substr($projectinfo->project_brand_name,0,10,'UTF-8');?></h1>
                <?php if(isset($isshowrenling) && $isshowrenling){?>
                    <a href="javascript:void(0)" style="margin-left:10px;" id="renling">
                    <img src="<?php echo URL::webstatic('images/platform/yellow/renling.png');?>" />
                    </a>
                    <a id="renling_projectid" style="display:none"><?php echo $projectinfo->project_id;?></a>
                    <a id="renling_usertype" style="display:none"><?php echo $usertype;?></a>
                <?php }?>
                </div>
                <p class="b"><?php
                if(mb_strlen($companyinfo->com_name)>26){
                    echo mb_substr($companyinfo->com_name,0,26,'UTF-8').'...';
                }
                elseif($companyinfo->com_name){
                     echo $companyinfo->com_name;
                }else{
                    echo "暂无相关信息";
                }
                if($projectinfo->project_source ==1 || $isrenglingok){?><a href="<?php echo urlbuilder::help("qzhishuji")."#level";?>" target="_blank"><img src="<?php echo URL::webstatic("images/integrity/{$ity_level['level']}.png")?>" />&nbsp;</a><?php }?></p>
                 <div id="cardandproject">
                 <?php if($card){?>
                   <button>已递送</button>
                 <?php } else{?>
                 <div id="a1" style="position:relative;display:inline-block;">
                     <button id="button_a" class="sendcard_<?php echo $userid."_".$com_user_id?>">发送名片</button>
                     <div id="a2">了解更多项目详情，想让企业主动联系您请发送名片</div>
                 </div>
                 <?php } ?>
                  <?php if($wathcproject){?>
                   <button id="watchproject_<?php echo $userid."_".$projectinfo->project_id?>" class="button_c">已收藏</button>
                  <?php } else{?>
                  <button id="watchproject_<?php echo $userid."_".$projectinfo->project_id?>" class="button_b"><span>+</span>收藏</button>
                 <?php } ?>
                </div>
                <div class="clear"></div>
            </div>
             <div class="linkWebsite"><!-- onclick="setVisit()" -->
            <?php if($companyinfo->com_site && stristr($companyinfo->com_site, 'http') !== FALSE){ ?>
                <p class="aa">了解更多项目详情</p>
                <p class="bb"><a rel="nofollow" id="gotourl" class="gotourl_<?php echo $projectinfo->project_id;?>" target="_Blank" href="<?php echo $companyinfo->com_site;?>" onclick="setVisit()" ><img alt="去企业官网" src="<?php echo URL::webstatic("images/platform/yellow/a3.png")?>" /></a></p>
            <?php }?>
            </div>

            <div class="project">
                <h3><a  target="_Blank"  style="display:block;height:27px; outline:none;">投资保障</a></h3>
                <p>
                <span class="<?php if( !isset($server_status_all) || $server_status_all['base']!="1"){echo 'xq2_2';}else{echo 'xq2';}?>">基础保障</span>
                <span class="<?php if( !isset($server_status_all) || $server_status_all['quality']!="1"){echo 'xq3_2';}else{echo 'xq3';}?>">品质保障</span>
                </p>

                <p>
                <span class="<?php if( !isset($server_status_all) || $server_status_all['safe']!="1"){echo 'xq4_2';}else{echo 'xq4';}?>">安全保障</span>
                <span class="<?php if( !isset($server_status_all) || $server_status_all['server']!="1"){echo 'xq5_2';}else{echo 'xq5';}?>">服务保障</span>
                </p>
            </div>

        </div>
        <div class="contant">
            <div class="lt"  style="height:auto">
                <div class="a">
                    <p><span class="span1">品牌名称</span><span class="span2"><?php
                     if(mb_strlen($projectinfo->project_brand_name)>16){
                        echo mb_substr($projectinfo->project_brand_name,0,16,'UTF-8').'...';
                      }
                      else{
                          echo $projectinfo->project_brand_name;
                      }
                    ?></span></p>
                    <p><span class="span1">投资金额</span><span class="span2"><?php $monarr= common::moneyArr(); echo $projectinfo->project_amount_type== 0 ? '无': $monarr[$projectinfo->project_amount_type];?></span></p>
                    <p><span class="span1">行　　业</span><span class="span2">
                    <?php
                      $arr_pro=explode(",",$pro_industry);
                      if(isset($arr_pro[0]) && $arr_pro[0]!=''){
                        echo '<a target="_Blank" href="/platform/index/search?w='.urlencode($arr_pro[0]).'">'.$arr_pro[0].'</a>';
                      }
                      if(isset($arr_pro[1]) && $arr_pro[1]!=''){
                        echo '、<a target="_Blank" href="/platform/index/search?w='.urlencode($arr_pro[1]).'">'.$arr_pro[1].'</a>';
                      }
                    ?></span></p>
                    <p><span class="span1">适合人群</span><span class="span2"><?php
                        if(mb_strlen($group_text)>14){
                            echo mb_substr($group_text,0,14,'UTF-8').'...';
                        }
                        else{
                            echo $group_text;
                        }
                       ?></span></p>
                    <p><span class="span1">招商地区</span><span class="span2"><?php
                           if(count($pro_area)&& is_array($pro_area)){
                            $area='';
                            foreach ($pro_area as $v){
                                $area=$area.$v.',';
                            }
                            $area= substr($area,0,-1);
                            if(mb_strlen($area)>16){
                                echo mb_substr($area,0,16,'UTF-8').'...';
                            }
                            else{
                                echo $area;
                            }
                          }else{
                              echo $pro_area;
                          }
                   ?></span></p>
                    <p><span class="span1">招商形式</span><span class="span2"><?php $lst = common::businessForm();
                    $pro_count=count($projectcomodel);
                    if($pro_count){
                        $comodel_text=0;
                        foreach ($projectcomodel as $v){
                            $comodel_text++;
                            echo '<a target="_Blank" href="/platform/index/search?w='.urlencode($lst[$v]).'">'.$lst[$v].'</a>';
                            if($comodel_text < $pro_count){
                                echo '、';
                            }
                        }
                    } ?></span></p>
                    <div class="clear"></div>
                </div>
                <div class="b">
                    <h2>标签</h2>
                    <?php $arr = explode(",",$projectinfo->project_tags);
                        foreach ($arr as $ke=>$ve){
                            if($ke <10){
                                if(mb_strlen($ve)>10){ $ve=mb_substr($ve,0,10,'UTF-8'); }
                                echo '<a target="_Blank" href="/platform/index/search?w='.urlencode($ve).'">'.$ve.'</a>';
                       }}?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="ct">
                <div class="a">
                    <h2>项目介绍 <?php
                        $sumary_text=htmlspecialchars_decode($projectinfo->project_summary);
                        $project_summary_delete = str_replace(' ','',$sumary_text); if(mb_strlen($project_summary_delete)>260){ ?>
                        <a href="#" class="more_js">更多</a>
                        <?php } ?>
                    </h2>
                     <p style="word-break:break-all;"><?php echo mb_substr(strip_tags($sumary_text),0,270,'UTF-8').'...';?>
                     </p>
                </div>
                <div class="b">
                    <h2>产品特点
                    <?php  if(mb_strlen($projectinfo->product_features)>160){ ?>
                        <a class="more_td" href="#">更多</a>
                        <?php } ?></h2>
                    <p style="word-break:break-all;"><?php echo mb_substr($projectinfo->product_features,0,180,'UTF-8');?></p>
                </div>
            </div>
            <div class="rt">
                <h2>加盟详情</h2>
                <div style="line-height: 24px;">
                <span style="width:190px; height:336px; overflow:hidden; display:block;word-break:break-all;">
                <?php $conditions_text=htmlspecialchars_decode($projectinfo->project_join_conditions); echo mb_substr($conditions_text,0,200,'UTF-8');?>
                <div class="clear"></div>
                </span>
                <span>
                  <?php $conditions_text_delete = str_replace(' ','',$conditions_text);if(mb_strlen($conditions_text_delete)>200){ ?>
                    <a href="#" class="more_td_jmxq" style="float:right;color:#B4B4B4;">更多</a>
                    <?php } ?>
                 </span>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div id="right_nav">
             <ul>
                <!-- <li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">封面</a></li>-->
                <li><a href="#" class="current">项目</a></li>
                <?php if(isset($ispage) && $ispage){?><li><a href="<?php echo urlbuilder::projectPoster($projectinfo->project_id);?>">海报</a></li><?php }?>
                <?php if(isset($is_has_image) && $is_has_image){?><li><a href="<?php echo urlbuilder::projectImages($projectinfo->project_id);?>">产品</a></li><?php }?>
                <?php if(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){?><li><a href="<?php echo urlbuilder::projectCompany($projectinfo->project_id);?>">公司</a></li><?php }?>
                <?php if($isCerts){?><li><a href="<?php echo urlbuilder::projectCerts($projectinfo->project_id);?>">资质</a></li><?php }?>
                <?php if($isinvest){?><li><a href="<?php if($isinvest>0){echo urlbuilder::projectInvest($projectinfo->project_id).'?investid='.$isinvest;}else{echo urlbuilder::projectInvest($projectinfo->project_id);}?>" class="three">招商会</a></li><?php }?>
                <!-- <li><a href="<?php echo urlbuilder::projectEnd($projectinfo->project_id);?>">封底</a></li>-->
            </ul>
        </div>
        <div class="clear"></div>
    </div>

    <div class="clear"></div>
    <div class="ryl_project_bg">
    <p class="ryl_project_page">
      <!-- 下一页 -->
         <?php if(isset($ispage) && $ispage){
                        echo '<a href="'.urlbuilder::projectPoster($projectinfo->project_id).'" class="ryl_next_page"></a>';
                    }elseif(isset($is_has_image) && $is_has_image){
                        echo '<a href="'.urlbuilder::projectImages($projectinfo->project_id).'" class="ryl_next_page"></a>';
                    }elseif(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){
                        echo '<a href="'.urlbuilder::projectCompany($projectinfo->project_id).'" class="ryl_next_page"></a>';
                    }elseif(isset($isCerts) && $isCerts){
                        echo '<a href="'.urlbuilder::projectCerts($projectinfo->project_id).'" class="ryl_next_page"></a>';
                    }elseif(isset($isinvest) && $isinvest) {
                        if($isinvest>0){
                            echo '<a href="'.urlbuilder::projectInvest($projectinfo->project_id).'?investid='.$isinvest.'" class="ryl_next_page"></a>';
                        }else{
                            echo '<a href="'.urlbuilder::projectInvest($projectinfo->project_id).'" class="ryl_next_page"></a>';
                        }
                    }else{
                        echo '';
                    }
            ?>
    </p>
    </div>
</div>
<!--中部结束-->
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
<div id="yellow_xq_box" class="aa clo" style="height:500px;margin-top: -250px;">
    <a href="#" class="close"></a>
    <div style="width:690px;height:500px; overflow:auto;">
        <h2>产品特点</h2>
        <div class="p"><?php echo $projectinfo->product_features;?></div>
    </div>
</div>
<div id="yellow_xq_box" class="bb clo" style="height:500px;margin-top: -250px;">
    <a href="#" class="close"></a>
    <div style="width:690px;height:500px; overflow:auto;">
        <h2>项目介绍</h2>
        <div class="p"><?php echo htmlspecialchars_decode($projectinfo->project_summary);?></div>
    </div>
</div>
<div id="yellow_xq_box" class="cc clo" style="height:500px;margin-top: -250px;">
    <a href="#" class="close"></a>
    <div style="width:690px;height:500px; overflow:auto;">
        <h2>加盟详情</h2>
        <div class="p"><?php echo htmlspecialchars_decode($projectinfo->project_join_conditions);?></div>
    </div>
</div>
<!--更多文字结束-->
    <!--登陆-弹出框开始-->


    <!--登陆-弹出框结束-->
