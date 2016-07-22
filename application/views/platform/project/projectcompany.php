<?php echo URL::webcss("platform/yellow_company.css")?>
<?php echo URL::webjs("platform/filter/swfobject.js");?>
<?php echo URL::webjs("platform/template/yellow.js");?>
<?php echo URL::webjs("platform/template/yellow_company_flash.js");?>
<!--中部开始-->
<div id="yellow">
    <div id="center_company">
        <div class="yellow_company_top">
          <div class="yellow_company_logo"><img src="<?php if(isset($comlogo) && $comlogo==''){echo URL::webstatic("images/common/company_default.jpg");}else{echo $comlogo;}?>"></div>
          <div class="yellow_company_infor">
              <div class="yellow_company_name"><h1 style="font-weight:normal; font-size:26px; float:left;"><?php if($companyinfo->com_name){echo $companyinfo->com_name;}?></h1><em><?php if($projectinfo->project_source ==1){?><a href="<?php echo urlbuilder::help("qzhishuji")."#level";?>" target="_blank"><img src="<?php echo URL::webstatic("images/integrity/{$ity_level['level']}.png")?>" />&nbsp;</a><?php }?></em>
              </div>
              <p class="yellow_company_text">
                 <span class="yellow_text01">公司性质 : <?php
                    $soure = common::comnature();
                    foreach ($soure as $k=>$v){
                        if ($k==$companyinfo->com_nature)
                            echo $v;
                    }
                    ?></span><span class="yellow_text02">联系人：<?php if($companyinfo->com_contact){echo $companyinfo->com_contact;}else{echo "暂无相关资料";}?></span>
                 <span class="yellow_text01">注册资本：<?php if($companyinfo->com_registered_capital){echo $companyinfo->com_registered_capital."万";}else{echo "暂无相关资料";}?></span><span class="yellow_text02">公司地址：<?php if($companyinfo->com_adress){echo $companyinfo->com_adress;}else{echo "暂无相关资料";}?></span>
                 <span class="yellow_text01">成立时间：<?php if($companyinfo->com_founding_time){echo substr($companyinfo->com_founding_time,0,4)."年";}else{echo "暂无相关资料";}?></span>
              </p>
          </div>
          <div class="yellow_company_pic"><img src="<?php echo url::webstatic("images/platform/yellow/company_pic.jpg")?>"></div>
          <div class="clear"></div>
        </div>
        <div class="yellow_line"></div>
        <div class="yellow_company_bot">
           <div class="yellow_company_bot_l">

                    <h3><span>公司简介</span>
                <?php  if($companyinfo->com_desc){ if(mb_strlen($companyinfo->com_desc)>500){?>
                <a href="#" class="more_js">更多</a>
                <?php } }else{
                    if(mb_strlen($projectinfo->project_summary)>500){
                        echo '<a href="#" class="more_js">更多</a>';
                    }
                }?>
                </h3>
                <div>
                    <?php if($companyinfo->com_desc){
                            $desc_texts=htmlspecialchars_decode($companyinfo->com_desc);
                            echo mb_substr(strip_tags($desc_texts),0,500,'UTF-8');
                        }else{
                            $desc_texts=htmlspecialchars_decode($projectinfo->project_summary);
                            echo mb_substr(strip_tags($desc_texts),0,500,'UTF-8').'...';
                        }
                    ?>
                </div>
           </div>
           <div class="yellow_company_bot_r">
              <h3><?php if(isset($is_has_zizhiimage) && $is_has_zizhiimage){echo '企业资质图片';}?></h3>
              <div class="yellow_company_roll">
              <div id="imagewall"></div>
              <input type="hidden" name="hid" value="<?php echo isset($projectinfo->project_id)?$projectinfo->project_id :'';?>" id="hid">
              </div>
           </div>
        <div class="clear"></div>
        </div>
        <div id="right_nav">
            <ul>
                <!--<li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">封面</a></li>-->
                <li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">项目</a></li>
                <?php if(isset($ispage) && $ispage){?><li><a href="<?php echo urlbuilder::projectPoster($projectinfo->project_id);?>">海报</a></li><?php }?>
                <?php if(isset($is_has_image) && $is_has_image){?><li><a href="<?php echo urlbuilder::projectImages($projectinfo->project_id);?>">产品</a></li><?php }?>
                <?php if(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){?><li><a href="#" class="current">公司</a></li><?php }?>
                <?php if($isCerts){?><li><a href="<?php echo urlbuilder::projectCerts($projectinfo->project_id);?>">资质</a></li><?php }?>
                <?php if($isinvest){?><li><a href="<?php if($isinvest>0){echo urlbuilder::projectInvest($projectinfo->project_id).'?investid='.$isinvest;}else{echo urlbuilder::projectInvest($projectinfo->project_id);}?>" class="three">招商会</a></li><?php }?>
                <!--<li><a href="<?php echo urlbuilder::projectEnd($projectinfo->project_id);?>">封底</a></li> -->
            </ul>
        </div>
        <div class="clear"></div>
    </div>

    
	<div class="ryl_project_bg">
    <p class="ryl_project_page">
      <!-- 上一页 -->
      <a href="<?php if(isset($is_has_image) && $is_has_image){
                        echo urlbuilder::projectImages($projectinfo->project_id);
                    }elseif(isset($ispage) && $ispage){
                        echo urlbuilder::projectPoster($projectinfo->project_id);
                    }else{
                        echo urlbuilder::project($projectinfo->project_id);
                    }
                ?>" class="ryl_prev_page"></a>
    <!-- 下一页 -->
      <?php if(isset($isCerts) && $isCerts){
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
    <div class="clear"></div>
</div>
<!--中部结束-->
<!--更多文字开始-->
<div id="opacity_box"></div>
<div id="yellow_xq_box" class="bb clo">
    <a href="#" class="close"></a>
    <div style="width:690px;height:610px; overflow:auto;">
        <h2>公司简介</h2>
        <?php if($companyinfo->com_desc){?>
            <div class="p"><?php echo htmlspecialchars_decode($companyinfo->com_desc);?></div>
        <?php }else{?>
            <div class="p"><?php echo htmlspecialchars_decode($projectinfo->project_summary);?></div>
        <?php }?>
    </div>
</div>