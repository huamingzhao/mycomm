<?php echo URL::webjs("platform/template/yellow.js");?>
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
.bar_code img{ padding-top:5px;}
#yellow #center-g .left .btn .a p .bottom_focus {color: #AF4800;}
</style>
<!--中部开始-->
<div id="yellow">
    <div id="center-g">
        <div class="left">
            <div class="btn">
                <div class="a">
                <p id="cardandproject">
                  <?php if($card){?>
                   <button class="bottom_send1">已 递 送</button>
                   <?php } else{?>
                   <button id="button_a" class="sendcard_<?php echo $userid."_".$com_user_id?>" >递 送 名 片</button>
                  <?php } ?>
                  </p>
                  <p id="endwatchproject_span">
                  <?php if($wathcproject){?>
                  <button id="endwatchproject_<?php echo $userid."_".$projectinfo->project_id?>" class="bottom_focus1 button_c">已 关 注</button>
                  <?php } else{?>
                  <button id="endwatchproject_<?php echo $userid."_".$projectinfo->project_id?>" class="bottom_focus button_b">加 关 注</button>
                  <?php } ?>
                  </p>
                  <p id="backcenter"><button class="bottom_review" onclick="javascript:window.location.href='<?php echo urlbuilder::project($projectinfo->project_id);?>';" >返 回</button></p>
                </div>
                <div class="b" style="background:none;">
                    <div class="bar_code"><?php echo $encodeimage;?></div>
                    <h2><?php echo $projectinfo->project_brand_name.'项目';?></h2>
                    <p>招商经理：<?php echo $companyinfo->com_contact;?></p>
                    <p>联系电话：<?php if($branch_phone!=''){echo $com_phone.'分机'.$branch_phone;}else{echo $com_phone;}?></p>
                </div>
            </div>
            <div class="name">
                <div class="pro"><?php echo $projectinfo->project_brand_name.'项目';?></div>
                <div class="oneword">一句话@通路快建</div>
            </div>
        </div>
        <div class="right">
            <div class="ryl_paper_bot">
               <span>如有任何疑问,<br/>欢迎拨打我们的免费热线电话</span>
               <p><?php $arrCustomerPhone = common::getCustomerPhone();echo $arrCustomerPhone[1]?></p>
            </div>

        </div>
    </div>

    <div class="clear"></div>
    <div class="ryl_project_bg">
    <p class="ryl_project_page">
      <a href="<?php if(isset($isinvest) && $isinvest){
                             if($isinvest>0){
                                   echo urlbuilder::projectInvest($projectinfo->project_id).'?investid='.$isinvest;
                           }else{
                               echo urlbuilder::projectInvest($projectinfo->project_id);
                           }
                     }elseif(isset($isCerts) && $isCerts){
                        echo urlbuilder::projectCerts($projectinfo->project_id);
                     }elseif($projectinfo->project_source ==1 || $isrenglingok){
                       echo urlbuilder::projectCompany($projectinfo->project_id);
                     }elseif(isset($is_has_image) && $is_has_image){
                        echo urlbuilder::projectImages($projectinfo->project_id);
                     }elseif(isset($ispage) && $ispage){
                        echo urlbuilder::projectPoster($projectinfo->project_id);
                     }else{echo urlbuilder::projectInfo($projectinfo->project_id);
                     }?>" class="ryl_prev_page" style="margin-left:40px"></a>
    </p>
    </div>
</div>
<!--中部结束-->
<!--透明背景开始-->
<div id="opacity"></div>
<!--透明背景结束-->

<!--递出名片层开始-->
<div id="send_box"  style="z-index:999">
    <a href="#" class="close">关闭</a>
    <div id="msgcontent" class="btn">
    </div>
</div>
<!--递出名片层结束-->
    <!--登陆-弹出框开始-->

    <!--登陆-弹出框结束-->