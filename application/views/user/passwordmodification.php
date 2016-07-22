<?php echo URL::webcss("platform/common.css")?>
<?php echo URL::webjs("platform/login/login.js")?>
<?php echo URL::webcss("common.css")?>
<?php echo URL::webjs("jquery.validate.js")?>
<?php echo URL::webjs("validate_expand.js")?>
<?php echo URL::webjs("validate.js")?>
<?php echo URL::webjs("common.js")?>
<?php echo URL::webcss("875/forgetPassword.css")?>
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
.map_bg02{ padding-left:20px; padding-right:20px; width:940px;}
.mail_error {padding: 70px 0 250px;}
#password_rt {margin: 0 auto;padding-top: 70px;width: 880px;}
</style>
<div class="main" style="height:auto; padding:15px 0;">
   <div class="map_bg">
       <div class="map_bg02">
        <div class="ryl_find_password">找回密码</div>
          <div class="mail_error mainContent">
                    <div id="password_rt">
                      <ul class="title clearfix">
                        <li class="first">1 输入帐号</li>
                        <li>2 身份验证</li>
                        <li class="fc ">3 重置密码</li>
                        <li class=" last">4 完成</li>
                      </ul>
                        <div class="password_rt_con">
                        <form id="resetform" method="post" action="<?php echo URL::website('/member/passwordsuccess')?>">
                        <input type="hidden" name="email" id="email" value="<?=$emails;?>"/><input type="hidden" name="suii" value="<?php echo $uid?>"/>
                            <p><label>请输入新密码：</label><input type="password" name="newpassword" id="newpassword" value="" class="long"/><span class="tipinfo1"></span></p>
                            <p><label>确认新密码：</label><input type="password" value="" name="confirm" id="confirm" class="long"/><span class="tipinfo1"></span></p>
                            <p class="btn">
                              <label>&nbsp;</label><input type="submit" class="ensure" value="确定"/>
                              <a href="<?php echo urlbuilder::geren("denglu")?>" style="margin-left:10px;" class="btnCancel">取消</a>
                            </p>
                        </form>
                        </div>
                    </div>
            <div class="clear"></div>
          </div>
          <div class="clear"></div>
       </div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>


