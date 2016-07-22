<?php echo URL::webcss("platform/common.css")?>
<?php echo URL::webjs("platform/login/login.js")?>
<?php echo URL::webcss("common.css")?>

<style>
.map_bg02{ padding-left:20px; padding-right:20px; width:940px;}
.mail_error {padding: 70px 0 250px;}
#password_rt {margin: 0 auto;padding-top: 70px;width: 880px;}
</style>
<div class="main" style="height:auto; background-color:#e3e3e3; padding:15px 0;">
   <div class="map_bg">
       <div class="map_bg01"></div>
       <div class="map_bg02">
        <div class="ryl_find_password">找回密码</div>
          <div class="mail_error">
            <form id="forgetpassword" method="post" action="<?php echo URL::website('/member/forgetpassword')?>">
                <div id="password_rt">
                    <p class="find_password_bg process01"></p>
                    <div class="password_rt_con">
                        <p><label>您的邮箱：</label><input type="text" name="email" class="long"/><img src="<?php echo URL::webstatic('images/password_rt/tishi.png')?>" class="floleft" /><span class="tipinfo1"><?php echo isset($error['email']) ?$error['email'] :"";?></span></p>
                        <p><label>验 证 码：</label><input type="text" name="valid_code" class="short" /><img src="<?=common::verification_code();?>"  id="vfCodeImg1" class="floleft img_yz"/><span class="see_again vfCodeCh"><a href="#" style="position:relative;">看不清？换一张</a></span><span class="tipinfo1"><?php echo isset($error['captcha']) ?$error['captcha'] :"";?></span></p>
                        <p class="btn"><label>&nbsp;</label><input  class="ensure" type="image" src="<?php echo URL::webstatic("images/getcards/ensure.jpg") ?>" /><input  class="cancle" type="image" src="<?php echo URL::webstatic("images/getcards/cancel.jpg") ?>" /></p>
                    </div>
                </div>
                </form>
            <div class="clear"></div>
          </div>
          <div class="clear"></div>
       </div>
       <div class="map_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#changeCodeImg').click(function() {
            var url = '/captcha';
                url = url+'?'+RndNum(8);
                $("#vfCodeImg1").attr('src',url);
         });
});
</script>
