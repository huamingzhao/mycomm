         <!--右侧开始-->
            <div id="right">
        <div id="right_top"><span>认证信息管理</span><div class="clear"></div></div>
                    <div id="right_con">
                        <div class="infor4">
                              <?php if($emailstatus) {?>
                              <div class="information3_top"><img src="<?php echo URL::webstatic("/images/user_center/renz_email_suc.png") ?>" alt="" /><span class="ok">ok，邮箱已验证！</span></div>
                              <?}else{?>
                              <div class="information3_bot"><img src="<?php echo URL::webstatic("/images/user_center/renz_email_error.png") ?>" alt="" /> <a  href="<?php echo URL::website('company/member/basic/vemail')?>"> <span class="error">邮箱还没验证，马上进行验证?</span></a> </div>
                              <?}?>

                              <?php if($com_authstaus) {?>
                              <div class="information3_top"><img src="<?php echo URL::webstatic("/images/user_center/renz_company_suc.png") ?>" alt="" /><span class="ok">企业资质验证已成功</span></div>
                              <?}else{?>
                              <div class="information3_bot"><?php echo HTML::image(URL::webstatic("/images/user_center/renz_company_error.png")) ?><a href="<?php echo URL::webstatic("/company/member/basic/uploadcertification"); ?>"><span class="error">验证企业资质？</span></a></div>
                              <?}?>
                        </div>
                </div>
           </div>
       <!--右侧结束-->
