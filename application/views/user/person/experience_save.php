<?php echo URL::webjs("header.js")?>
<?php echo URL::webjs("my_jobs.js")?>
<?php echo URL::webcss("per_infor_0502.css")?>
<style>
.my_cyjy .add .line input.text01{ margin-top:3px;}
.my_cyjy .add .add_rt {width: 560px;}
.my_cyjy .add .rq .one span,.my_cyjy .add .rq .one input,.my_cyjy .add .rq .one select,.my_cyjy .add .rq .one a{ float:left; line-height:24px; height:24px; padding:0 3px;}
.my_cyjy .add .rq .one a{ padding-top:3px;}
</style>
<!--主体部分开始-->
<div class="right" style="height:auto !important;height:620px;min-height:620px;">
                    <h2 class="user_right_title"><span>我的从业经验</span><div class="clear"></div></h2>
<div class="per_infor_0502">
<ul class="per_infor_title">
  <li><a href="<?php echo URL::website("/person/member/basic/person")?>">我的基本信息</a></li>
  <li><a href="<?php echo URL::website("/person/member/basic/personInvestShow")?>">意向投资信息</a></li>
  <li class="last"><a href="<?php echo URL::website("/person/member/basic/experience")?>" class="current"><img src="<?php echo URL::webstatic("images/per_infor/icon01.jpg")?>" style="padding:21px 10px 0 75px;">从业经验</a></li>
  </ul>
 </div>
<div class="my_cyjy">
<?php
foreach( $experiences as $k=>$v ){
    if( $k%2==0 ){
        $color= "";
    }else{
        $color= "background-color:#f3f3f3;";
    }
?>
  <div class="my_cyjy_save" style="<?php echo $color?>">
    <ul>
    <li><label>工作时间：</label><p><span><?php echo substr($v['exp_starttime'],0,4).".".substr($v['exp_starttime'],4)?>-<?php if( $v['exp_endtime']=='0' ){ echo '今天'; }else{ echo substr($v['exp_endtime'],0,4).".".substr($v['exp_endtime'],4);}?></span><a href="/person/member/basic/editexperience?exp_id=<?php echo $v['exp_id']?>" class="modify"><img src="<?php echo URL::webstatic("/images/my_cyjy/modify.jpg") ?>"  width="45" height="17" /></a> <?php if( $k==0 ){?><a href="/person/member/basic/experience" class="add_jy"><img src="<?php echo URL::webstatic("/images/my_cyjy/go_add_jy.jpg") ?>" width="144" height="26" /></a><?php }?> </p><div class="clear"></div></li>

    <li><label>工作地点：</label><p><span><?php echo $v['pro_name'].$v['area_name']?>  </span></p><div class="clear"></div></li>
    <li><label>企业名称：</label><p><span><?php echo $v['exp_company_name']?></span></p><div class="clear"></div></li>
    <li><label>企业性质：</label><p><span><?php foreach ( common::comnature_new() as $k=>$vs ){ if( $k==$v['exp_nature'] ){ echo $vs; } }?></span></p><div class="clear"></div></li>
    <?php if( $v['exp_scale']!='0' && $v['exp_scale']!='' ){?>
    <li><label>企业规模：</label><p><span><?php foreach( common::comscale() as $k=>$vs ){ if( $k==$v['exp_scale'] ){ echo $vs; } }?></span></p><div class="clear"></div></li>
    <?php }?>
    <?php if( $v['exp_industry_sort_name']!='' ){?>
    <li><label>行业类别：</label><p><span><?php echo $v['exp_industry_sort_name']?></span></p><div class="clear"></div></li>
    <?php }?>
    <?php if( $v['exp_department']!='' ){?>
    <li><label>所在部门：</label><p><span><?php echo $v['exp_department']?></span></p><div class="clear"></div></li>
    <?php }?>
    <?php if( $v['pos_name']!='' ){?>
    <li><label>职位类别：</label><p><span><?php echo $v['pos_name']?></span></p><div class="clear"></div></li>
    <?php }?>
    <?php if( $v['occ_name']!='若无适合选项请在此填写' ){?>
    <li><label>职位名称：</label><p><span><?php echo $v['occ_name']?></span></p><div class="clear"></div></li>
    <?php }?>
    <?php if( $v['exp_description']!='' && $v['exp_description']!='请详细描述您的职责范围、工作任务以及取得的成绩等。' ){?>
    <li><label>工作描述：</label><p><span><?php echo $v['exp_description']?></span></p><div class="clear"></div></li>
    <?php }?>
    </ul>
  </div>
<?php }?>


</div>
</div>
<!--主体部分结束-->

