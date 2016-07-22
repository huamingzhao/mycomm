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
//     $("#title").hover(function(){
//         $("#renling").stop(true,true).fadeIn(500);
//     },function(){
//         $("#renling").fadeOut(500);
//     });
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
#yellow #center .contant .lt {width: 242px;}
#yellow #center .contant .lt .a,#yellow #center .contant .lt .b{ margin-right:2px;}
</style>
<!--中部开始-->123
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
                <div class="a" alt="<?php echo $projectinfo->project_brand_name; ?>"><h1 style="font-weight:normal; font-size:26px; float:left;"><?php echo mb_substr($projectinfo->project_brand_name,0,10,'UTF-8');?></h1>
                <?php if(isset($isshowrenling) && $isshowrenling){?>
                    <a href="javascript:void(0)" style="margin-left:10px;" id="renling">
                    <img src="<?php echo URL::webstatic('images/platform/yellow/renling.png');?>" alt="认领"/>
                    </a>
                    <a id="renling_projectid" style="display:none"><?php echo $projectinfo->project_id;?></a>
                    <a id="renling_usertype" style="display:none"><?php echo $usertype;?></a>
                <?php }?>
                </div>
                <p class="b"><?php
                if(isset($companyinfo->com_name) && mb_strlen($companyinfo->com_name)>26){
                    echo mb_substr($companyinfo->com_name,0,26,'UTF-8').'...';
                }
                elseif(isset($companyinfo->com_name) && $companyinfo->com_name){
                     echo $companyinfo->com_name;
                }else{
                    echo "暂无相关信息";
                }
                if($projectinfo->project_source ==1 || $isrenglingok){?><a href="<?php echo urlbuilder::help("qzhishuji")."#level";?>" target="_blank"><img src="<?php echo URL::webstatic("images/integrity/{$ity_level['level']}.png")?>" />&nbsp;</a><?php }?></p>
                 <div id="cardandproject">
                 <?php if($card && $projectinfo->com_id != 0){?>
                   <button>已递送</button>
                 <?php } elseif(isset($isShowSendCard) && $isShowSendCard && $projectinfo->com_id != 0){?>
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
            </div>
             <div class="linkWebsite">
            <?php if(isset($companyinfo->com_site) && $companyinfo->com_site && stristr($companyinfo->com_site, 'http') !== FALSE){ ?>
                <p class="aa">了解更多项目详情</p>
                <p class="bb"><a rel="nofollow" id="gotourl" class="gotourl_<?php echo $projectinfo->project_id;?>" target="_Blank" href="<?php echo $companyinfo->com_site;?>" onclick="setVisit()"><img alt="去企业官网" src="<?php echo URL::webstatic("images/platform/yellow/a3.png")?>" /></a></p>
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
                        elseif($group_text){
                            echo $group_text;
                        }else{
                            echo "不限";
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
                    <p><span class="span1">公司名称</span><span class="span2"><?php
                     if(isset($companyinfo->com_name) && mb_strlen($companyinfo->com_name)>16){
                        echo mb_substr($companyinfo->com_name,0,16,'UTF-8').'...';
                      }
                      elseif(isset($companyinfo->com_name) && $companyinfo->com_name){
                          echo $companyinfo->com_name;
                      }else{
                          echo "暂无相关信息";
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
                    }else{echo "不限";} ?></span></p>
                    <div class="clear"></div>
                </div>
                <div class="b">
                    <h2>标签</h2>
                    <?php if(isset($projectinfo->project_tags) && $projectinfo->project_tags != ""){?>
                    <?php $arr = explode(",",$projectinfo->project_tags);
                        foreach ($arr as $ke=>$ve){
                            if($ke <10){
                                if(mb_strlen($ve)>10){ $ve=mb_substr($ve,0,10,'UTF-8'); }
                                echo '<a target="_Blank" href="/platform/index/search?w='.urlencode($ve).'">'.$ve.'</a>';
                       }}?>
                     <?php }?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="xq_cont_ryl">
                <div class="xq_cont_ryl_dl">
                <?php if(isset($companyinfo->com_name) && isset($arr_pro[0]) && $arr_pro[0]!='' && $projectinfo->project_amount_type!= 0 && count($pro_area) && is_array($pro_area)){?>
                <span><strong><?php echo $projectinfo->project_brand_name; ?>加盟优势</strong></span><span><?php echo $projectinfo->project_brand_name; ?>是<?php echo $companyinfo->com_name;?>的最成功的的产品之一。<?php echo $companyinfo->com_name;?>拥有一支专业化的团队，以卓越的服务品质和超越的经营策略打造极具市场竞争力的强势品牌。在<?php echo $arr_pro[0];?>领域里，<?php echo $projectinfo->project_brand_name; ?>拥有很大的市场份额，<?php echo $monarr[$projectinfo->project_amount_type];?>就可以加盟、开店、创业，经常在<?php echo $area;?>举办招商会、投资考察会。加盟<?php echo $projectinfo->project_brand_name; ?>的最好办法就是通过一句话招商平台，每月都有大量的<?php echo $arr_pro[0];?>投资考察会。<?php echo $projectinfo->project_brand_name; ?>加盟会是您创业项目的最好选择！</span>
                <?php }else{?>
                <span><strong><?php echo $projectinfo->project_brand_name; ?>开店加盟优势</strong></span><span>一句话加盟网提供<?php echo $projectinfo->project_brand_name; ?>开店加盟信息，<?php echo $projectinfo->project_brand_name; ?>在全国各地都有招商加盟会，<?php echo $projectinfo->project_brand_name; ?>投资考察以最好的服务、最全的产品线、优秀的品质及拥有同行业下最大化的市场份额，让<?php echo $projectinfo->project_brand_name; ?>开店加盟、投资、赚钱更容易。加盟<?php echo $projectinfo->project_brand_name; ?>的形式有：<?php $lst = common::businessForm();
                    $pro_count=count($projectcomodel);
                    if($pro_count){
                        $comodel_text=0;
                        foreach ($projectcomodel as $v){
                            $comodel_text++;
                            echo $lst[$v];
                            if($comodel_text < $pro_count){
                                echo '、';
                            }
                        }
                    }else{echo "全国";} ?>，低门槛、高收益。<?php echo $projectinfo->project_brand_name; ?>项目拥有完善的管理机制，开店、加盟、投资、创业的朋友们，选择<?php echo $projectinfo->project_brand_name; ?>是正确的选择，我们推荐的项目<?php echo $projectinfo->project_brand_name; ?>适合各种创业群体。</span>
                <?php }?>
                <span><?php echo $projectinfo->project_summary;?></span>
                </div>

                <div class="xq_cont_ryl_link">
                       <span style="display:none;" id="shouqi"><a href="#" class="xq_cont_ryl_link_close">收起</a><img src="<?php echo URL::webstatic("images/platform/yellow/xq_icon01.jpg")?>" /></span>
                      <span id="chakanquanbu" style="display:none;"><a href="#" class="xq_cont_ryl_link_view">查看全部</a><img src="<?php echo URL::webstatic("images/platform/yellow/xq_icon02.jpg")?>" /></span>
                 </div>
                <div class="clear"></div>
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
    <?php
    $i = 0;
    if(isset($ispage) && $ispage){
        $i++;
    }elseif(isset($is_has_image) && $is_has_image){
        $i++;
    }elseif(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){
        $i++;
    }elseif(isset($isCerts) && $isCerts){
        $i++;
    }elseif(isset($isinvest) && $isinvest){
        $i++;
    }
    if($i > 0){
    ?>
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
    <?php }?>
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
<div id="yellow_xq_box" class="aa clo">
    <a href="#" class="close"></a>
    <div style="width:690px;height:610px; overflow:auto;">
        <h2>产品特点</h2>
        <div class="p"><?php echo $projectinfo->product_features;?></div>
    </div>
</div>
<div id="yellow_xq_box" class="bb clo">
    <a href="#" class="close"></a>
    <div style="width:690px;height:610px; overflow:auto;">
        <h2>项目介绍</h2>
        <div class="p"><?php echo htmlspecialchars_decode($projectinfo->project_summary);?></div>
    </div>
</div>
<div id="yellow_xq_box" class="cc clo">
    <a href="#" class="close"></a>
    <div style="width:690px;height:610px; overflow:auto;">
        <h2>加盟详情</h2>
        <div class="p"><?php echo htmlspecialchars_decode($projectinfo->project_join_conditions);?></div>
    </div>
</div>
<!--更多文字结束-->
    <!--登陆-弹出框开始-->


    <!--登陆-弹出框结束-->
<script type="text/javascript" >
$(function(){
    //展开
    $("#chakanquanbu a").click(function(){
        $(".xq_cont_ryl_dl").css("height","auto");
        $(this).parent("span").siblings("span").show();
        $(this).parent("span").hide();
        return false;
    });
    //收起
    $("#shouqi a").click(function(){
        $(".xq_cont_ryl_dl").css("height","375px");
        $(this).parent("span").siblings("span").show();
        $(this).parent("span").hide();
        return false;
    });
    //alert($(".xq_cont_ryl_dl span").height())
    var xq_cont_ryl_dl_height = 0;
    $(".xq_cont_ryl_dl span").each(function(index, val){
      xq_cont_ryl_dl_height += $(val).height();
    });
    if(xq_cont_ryl_dl_height > 396){
      $("#chakanquanbu").show();
    }
});
</script>
