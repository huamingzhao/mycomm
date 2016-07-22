<?php echo URL::webjs("platform/filter/swfobject.js");?>
<?php echo URL::webjs("platform/template/yellow.js");?>
<?php echo URL::webjs("platform/template/yellow_flash.js");?>
<!--中部开始-->
<div id="yellow">
    <div id="center-f">

        <div id="title">
            <h1><?php echo $projectinfo->project_brand_name;?>产品展示</h1>
            <img src="<?php echo URL::webstatic("images/platform/yellow/products.jpg") ?>" class="sign"/>
        </div>
        <div class="contant">
            <div id="imagewall"></div>
            <input type="hidden" name="hid" value="<?php echo isset($projectinfo->project_id)?$projectinfo->project_id :'';?>" id="hid">
        </div>
        <div id="right_nav">
             <ul>
                 <!-- <li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">封面</a></li>-->
                <li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">项目</a></li>
                <?php if(isset($ispage) && $ispage){?><li><a href="<?php echo urlbuilder::projectPoster($projectinfo->project_id);?>">海报</a></li><?php }?>
                <li><a href="#" class="current">产品</a></li>
                <?php if(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){?><li><a href="<?php echo urlbuilder::projectCompany($projectinfo->project_id);?>">公司</a></li><?php }?>
                <?php if($isCerts){?><li><a href="<?php echo urlbuilder::projectCerts($projectinfo->project_id);?>">资质</a></li><?php }?>
                <?php if($isinvest){?> <li><a href="<?php if($isinvest>0){echo urlbuilder::projectInvest($projectinfo->project_id).'?investid='.$isinvest;}else{echo urlbuilder::projectInvest($projectinfo->project_id);}?>" class="three">招商会</a></li><?php }?>
                <!-- <li><a href="<?php echo urlbuilder::projectEnd($projectinfo->project_id);?>">封底</a></li>-->
            </ul>
        </div>
        <div class="corner"></div>
    </div>
    
    <div class="clear"></div>
    <div class="ryl_project_bg">
    <p class="ryl_project_page">
    <!-- 上一页 -->
      <a href="<?php if(isset($ispage) && $ispage){
                        echo urlbuilder::projectPoster($projectinfo->project_id);
                    }else{
                        echo urlbuilder::project($projectinfo->project_id);
                    }
            ?>" class="ryl_prev_page"></a>
      <!-- 下一页 -->
      <?php if(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){
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
       }?>
    </p>
    </div>
</div>
<!--中部结束-->