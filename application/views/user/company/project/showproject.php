<?php echo URL::webjs("del_shenhe.js")?>
<?php echo URL::webcss("my_bussines.css")?>
<script type="text/javascript">
$(document).ready(function() {
    var is_renling=$("#renling").html();
    if(is_renling=='renling'){
        var opacity = $("#getcards_opacity");
        var deleteBox = $("#getcards_delete");
        opacity.show();
        deleteBox.slideDown(500);
        return false;
    }
});
</script>
<style>
#getcards_delete .btn,#getcards_delete11 .btn{padding-top:50px;}
#getcards_delete .btn p,#getcards_delete11 .btn p{ line-height:40px;}
</style>
<div class="right">
    <h2 class="user_right_title"><span>项目管理</span><div class="clear"></div></h2>
    <div class="my_business">
      <div class="ryl_mybusiness_title"><b>我的项目：</b><a href="/company/member/project/addproject">+添加新项目</a></div>
        <div class="clear"></div>
      <ul class="ryl_myproject_list my_project_add_op">
        <?php foreach ($list as $value):?>
          <?if($value['project_status'] != 0) {?>
          <li>
            <p class="ryl_myproject_logo"><a href="<?php echo urlbuilder::project($value['project_id']); ?>" target="_blank"><img src="<?if($value['project_source'] != 1) {$img =  project::conversionProjectImg($value['project_source'], 'logo', $value);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($value['project_logo']);}?>" /></a></p>
            <div class="ryl_myproject_intro">
              <div class="ryl_myproject_intro_tit">
                 <b><a style="padding-left:0;color:#000;" href="<?php echo urlbuilder::project($value['project_id'])?>" target="_blank" ><?=$value['project_brand_name'];?></a></b>
                 <div  class="shenhe_kuang">

                 <?php if($value['isrenling_project']==0){?>
                   <?php if($value['project_status']==1){?>
                     <a href="#" class="icon_shenhe_now" onmouseout="$(this).next('.ryl_shenhebg').hide();return false;" onmouseover="$(this).next('.ryl_shenhebg').show();return false;">项目正在审核中...</a>
                        <!--未通过审核 开始-->
                        <div class="ryl_shenhebg" id="ryl_shenhebg_now">
                           <div class="ryl_shenhebg_top"></div>
                           <div class="ryl_shenhebg_center">您的项目还在审核中，审核通过后投资者可以查看您的项目官网。
                           <div class="clear"></div>
                           </div>
                           <div class="ryl_shenhebg_bot"></div>
                        </div>
                        <!--未通过审核 结束-->

                    <?php }elseif($value['project_status']==2){?>
                        <a href="#" class="icon_shenhe_has">项目已通过审核</a>

                    <?php } elseif($value['project_status']==0){?>
                     <a href="#" class="icon_publish_fail">项目发布失败</a>
                     <a href="/company/member/project/updateproject?project_id=<?=$value['project_id']?>" class="icon_go_perfect">继续完善我的项目</a>
                     <a href="/company/member/project/submitProject?project_id=<?=$value['project_id']?>" class="icon_publish">发布我的项目</a>

                     <?php }elseif($value['project_status']==3){?>
                        <a href="#" class="icon_shenhe_no" onmouseout="$(this).next('.ryl_shenhebg').hide();return false;" onmouseover="$(this).next('.ryl_shenhebg').show();return false;">项目未通过审核</a>
                        <!--未通过审核 开始-->
                        <div class="ryl_shenhebg" id="ryl_shenhebg_no">
                           <p class="ryl_shenhebg_top"></p>
                           <p class="ryl_shenhebg_center">您的项目未通过审核，审核通过后投资者才可以查看您的项目官网。
                           <div class="clear"></div>
                           </p>
                           <p class="ryl_shenhebg_bot"></p>
                        </div>
                        <!--未通过审核 结束-->
                       <?}else{	}?>
                       <!--认领项目开始-->
                      <?}else{
                        if($value['isrenling_project_status']==0){//审核中
                            echo '<a href="javascript:void(0)" class="icon_shenhe_now">认领项目正在审核中...</a>';
                        }elseif($value['isrenling_project_status']==1){//审核通过
                            echo '<a href="javascript:void(0)" class="icon_shenhe_has">认领项目已通过审核</a>';
                        }elseif($value['isrenling_project_status']==2){//审核未通过
                            echo '<a href="javascript:void(0)" class="icon_shenhe_no">认领项目未通过审核</a>';
                        }else{	}

                      }?>
                 </div>
              </div>

              <span>
                所属行业：<?=$value['project_industry_id'];?><br/>
                招商电话：<?php echo $value['project_phone'];?><br/>
                项目简介：<?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars($value['project_summary'], 0)));echo mb_strimwidth(strip_tags($string), 0, 125, "......");?><br/>
                <?php if( $value['project_status']==2 || $value['isrenling_project_status']==1 ){?>
                    <?php $project_pv_rs= common::getProjectStatPvNum($value['project_id'], date("Y-m-d",time()-86400*30), date("Y-m-d"))?>
                    30天内项目官网总访问量<font class="other_info"><?php echo $project_pv_rs['pro_pv']?></font><br/>
                    30天内导入企业官网总访问量<font class="other_info"><?php echo $project_pv_rs['pro_web_pv']?></font><a class="other_info_link" href="/company/member/project/showProjectPv?project_id=<?php echo $value['project_id']?>">查看详情</a>
                <?php }else{?>
                    30天内项目官网总访问量<font class="other_info">0</font><br/>
                    30天内导入企业官网总访问量<font class="other_info">0</font><a class="other_info_link" href="/company/member/project/showProjectPv?project_id=<?php echo $value['project_id']?>">查看详情</a>
                <?php
                }?>

              </span>
             </div>
            <p class="ryl_myproject_check">
                <a href="<?php echo urlbuilder::project($value['project_id']);?>" target="_blank" class="icon_yulan">预览项目官网</a>
                <?php if($value['isrenling_project']==0 || ($value['isrenling_project']==1 && $value['isrenling_project_status']==1)){?>
                <a href="/company/member/project/projectinfo?project_id=<?=$value['project_id'];?>" class="icon_edit">编辑项目基本信息</a>
                <a href="/company/member/project/addproimg?project_id=<?=$value['project_id'];?>" class="icon_edit">编辑项目图片</a>
                <a href="/company/member/project/addprocertsimg?project_id=<?=$value['project_id'];?>" class="icon_edit">编辑项目资质图片</a>
                <a href="/company/member/project/addposter?project_id=<?=$value['project_id'];?>" class="icon_edit">编辑项目海报</a>
                <a href="/company/member/project/addproinvestment?project_id=<?=$value['project_id'];?>" class="icon_edit">编辑投资考察会</a>
                <?php }else{?>
                 <a href="/platform/project/renlingProjectInfo?project_id=<?=$value['project_id'];?>" target="_blank"  class="icon_yulan">查看提交的认领信息</a>
                <?php }?>
            </p>

        </li>

          <?}?>
        <?php endforeach;?>
        <div class="clear"></div>
        <a id="renling" style="display:none"><?php if(isset($isrenling)){echo $isrenling;}else{echo '';}?></a>
      </ul>
    </div>

<?=$page;?>
</div>
<!--透明背景开始-->
<div id="getcards_opacity" <?php if($addproject =="ok"){?> style="display:block" <?}?>></div>
<!--透明背景结束-->

<!--删除项目开始-->
<div id="getcards_delete">
    <a href="#" class="close">关闭</a>
    <div class="btn">
        <p>您的认领信息已经提交审核，我们的工作人员将会</p>
        <p>在两个工作日内联系您，请耐心等待！</p>
    </div>
</div>
<!--删除项目结束-->

<!--审核弹出层开始-->
<?php if($addproject =="ok"):?>

<div id="getcards_delete11">
    <a href="#" class="close">关闭</a>
    <div class="btn">
        <p>您的项目信息已经提交审核，两个工作日内会告知您审核结果，请耐心等待。</p>
        <p>审核通过后，投资者可以查看您的项目官网。</p>
    </div>
</div>
<?php endif;?>
<!--审核弹出层结束-->
