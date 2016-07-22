<?php echo URL::webcss("platform/renling.css")?>
<!--公共背景框-->
<div class="main" style="background-color:#e3e3e3; height:auto;">
   <div class="ryl_main_bg">
       <div class="ryl_main_bg01"></div>
       <div class="ryl_main_bg02">
          <!--认领-->
          <div class="renling_main">
             <h3>企业认领项目</h3>
             <!--验证手机号码-->
             <div class="renling_title"><img src="<?php echo URL::webstatic('images/platform/renling/renling_title01.jpg');?>" /></div>
             <div class="renling_tel_succ">
                 <img src="<?php echo URL::webstatic('images/platform/renling/renling_04.jpg');?>" /><span>您的手机号码已经验证成功：<?=$mobile?></span><a href="<?php echo URL::website("platform/project/claimPhone?to=change").'&project_id='.$project_id;?>">修改</a>
                 <div class="clear"></div>
                 <a href="<?php echo URL::website("platform/project/comCertification").'?project_id='.$project_id;?>" class="renling_tel_next"><img src="<?php echo URL::webstatic('images/platform/renling/next.jpg');?>" /></a>
             </div>
          </div>
          
          <div class="clear"></div>
       </div>
       <div class="ryl_main_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>