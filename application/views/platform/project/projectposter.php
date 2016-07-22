<?php echo URL::webjs("platform/template/yellow.js");?>
<style>
#yellow #center-b .poster ul{list-style-type:0;}
#yellow #center-b .poster img{height:auto;overflow:hidden;display:block;}
</style>
<!--中部开始-->
<div id="yellow">
    <div id="center-b">

        <div id="title">
            <h1><?php echo $project_brand_name;?>海报</h1>
            <img src="<?php echo URL::webstatic("images/platform/yellow/poster.png");?>" class="sign"/>
        </div>
        <div class="poster" style="height:auto;">
           <?php if($ispage==false){?>
            <div class="temp1">
                <div class="logo_on"></div>
                <div class="logo"><img src="<?php if($projectinfo->project_source != 1) {echo project::conversionProjectImg($projectinfo->project_source, 'logo', $projectinfo->as_array());}else{ echo URL::imgurl($poster['project_logo']);}?>"/></div>
                <div class="name">
                    <h1><?=$poster['project_name']?></h1>
                    <p><?php $summary= htmlspecialchars_decode($poster['project_summary']);strip_tags($summary);?></p>
                </div>
            </div>
            <div class="temp2">
                <ul>
                <?php
                 $i=0;
                 if(!empty($poster['b_imgs'])){
                foreach ($poster['b_imgs'] as $v){
                $i++;
                ?>
                    <li><img src="<?=$v?>" /></li>
                   <?php }}?>
                   <?php for($i;$i<4;$i++){?>
                   <li><img src="<?php echo URL::webstatic("images/platform/yellow/temp-img.jpg");?>" /></li>
                   <?php }?>
                </ul>
            </div>
            <?php }else{?>
                        <?=$outPoster?>
            <?php }?>

        </div>
        <div id="right_nav">
            <ul>
                <!-- <li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">封面</a></li>-->
                <li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">项目</a></li>
                <li><a href="#" class="current">海报</a></li>
                <?php if(isset($is_has_image) && $is_has_image){?><li><a href="<?php echo urlbuilder::projectImages($projectinfo->project_id);?>">产品</a></li><?php }?>
                <?php if(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){?><li><a href="<?php echo urlbuilder::projectCompany($projectinfo->project_id);?>">公司</a></li><?php }?>
                <?php if($isCerts){?><li><a href="<?php echo urlbuilder::projectCerts($projectinfo->project_id);?>">资质</a></li><?php }?>
                <?php if($isinvest){?><li><a href="<?php if($isinvest>0){echo urlbuilder::projectInvest($projectinfo->project_id).'?investid='.$isinvest;}else{echo urlbuilder::projectInvest($projectinfo->project_id);}?>" class="three">招商会</a></li><?php }?>
                 <!-- <li><a href="<?php echo urlbuilder::projectEnd($projectinfo->project_id);?>">封底</a></li>-->
            </ul>
        </div>

        <div class="clear"></div>

    </div>
    
    <div class="ryl_project_bg">
	<p class="ryl_project_page">
            <!-- 上一页 -->
            <a href="<?php echo urlbuilder::project($projectinfo->project_id);?>" class="ryl_prev_page"></a>
            <!-- 下一页 -->
            <?php if(isset($is_has_image) && $is_has_image){
                                echo '<a href="'.urlbuilder::projectImages($projectinfo->project_id).'" class="ryl_next_page"></a>';
                            }elseif(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){
                                echo '<a href="'.urlbuilder::projectCompany($projectinfo->project_id).'" class="ryl_next_page"></a>';
                            }elseif(isset($isCerts) && $isCerts){
                               echo '<a href="'.urlbuilder::projectCerts($projectinfo->project_id).'" class="ryl_next_page"></a>';
                            }elseif(isset($isinvest) && $isinvest){
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