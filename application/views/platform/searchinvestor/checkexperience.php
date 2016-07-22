<?php echo URL::webjs("header.js")?>
<?php echo URL::webjs("my_jobs.js")?>
<?php echo URL::webcss("common.css")?>
<?php echo URL::webcss("per_infor_0502.css")?>
<?php echo URL::webcss("platform/common.css")?>
<!--从业经验弹出框开始-->

<div class="main" style="height:auto; background-color:#e3e3e3; padding:15px 0;">
   <div class="map_bg">
       <div class="map_bg01"></div>
       <div class="map_bg02">
        <div class="ryl_cy_perience_title"><?php echo $per_username; ?>的从业经验</div>
          <div class="ryl_cy_perience">
            <ul>
            <?php if( !empty($experiences)){ ?>
                <?php foreach($experiences as $key=>$experience){ ?>
            <li <?php if($key % 2 == 0){echo 'class="ryl_cy_perience_gray"';}?>>

                <span>工作时间：<?php echo substr($experience['exp_starttime'],0,4)."年".substr($experience['exp_starttime'],4)."月"?>到<?php if( $experience['exp_endtime']=='0' ){ echo '今天'; }else{echo substr($experience['exp_endtime'],0,4)."年".substr($experience['exp_endtime'],4)."月";}?></span>
                <SPAN>工作地点：<?php echo $experience['pro_name'].$experience['area_name']?></SPAN>
                <SPAN>企业名称：<?php echo $experience['exp_company_name']?></SPAN>
                <SPAN>企业性质：<?php foreach ( common::comnature_new() as $k=>$vs ){ if( $k==$experience['exp_nature'] ){ echo $vs; } }?></SPAN>
                
                <?php if( $experience['exp_scale']!='0' && $experience['exp_scale']!='' ){?>
                <SPAN>企业规模：<?php foreach( common::comscale() as $k=>$vs ){ if( $k==$experience['exp_scale'] ){ echo $vs; } }?></SPAN>
                <?php }?>
                
                <?php if($experience['exp_industry_sort_name']){?>
                <SPAN>行业类别：<?php echo $experience['exp_industry_sort_name']?></SPAN>
                <?php }?>
               
                <?php if( $experience['exp_department']!='' ){?>
                <SPAN>所在部门：<?php echo $experience['exp_department']?></SPAN>
                <?php }?>
                
                <?php if( $experience['pos_name']!='' ){?>
                <SPAN>职位类别：<?php echo $experience['pos_name']?></SPAN>
                <?php }?>
                
                <?php if( $experience['occ_name']!='' && $experience['occ_name']!='若无适合选项请在此填写'){?>
                <SPAN>职位名称：<?php echo $experience['occ_name']?></SPAN>
                <?php }?>
                
                <?php if( $experience['exp_description']!='' && $experience['exp_description']!='请详细描述您的职责范围、工作任务以及取得的成绩等。'){?>
                <SPAN>工作描述：<?php echo $experience['exp_description']?></SPAN>
				<?php }?>
            </li>
               <?php }?>
            <?php }?>
            </ul>
            <div class="clear"></div>
          </div>
          <div class="clear"></div>
       </div>
       <div class="map_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>
<!--从业经验弹出框结束-->