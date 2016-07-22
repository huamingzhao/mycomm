<?php echo URL::webcss("platform/common.css")?>
<?php echo URL::webjs("platform/login/login.js")?>
<?php echo URL::webcss("common.css");?>
<?php
$url = "http://".$_SERVER['HTTP_HOST']."/member/forgetpassword";
if(!isset($_SERVER['HTTP_REFERER']) || ($url != $_SERVER['HTTP_REFERER'])){
    //Header("Location:/member/forgetpassword");exit;
}
?>
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
          <div class="mail_error" style="padding: 70px 0 250px;">

                <form id="forgetpassword" method="post" action="<?php echo URL::website('/member/forgetpassword')?>">

                    <div id="password_rt">
                        <p class="find_password_bg process02"></p>
                        <div class="password_rt_con1">
                            <p class="mail"><b style="float:left;">已向</b><span><?=$emails;?></span><b>发送邮件，请于两</b><b>个小时之内点击邮件中的链接完成密码的重置。</b></p>
                            <p class="open"><a href="<?=$toemailurl;?>"><img src="<?php echo URL::webstatic("images/password_rt/openmail.jpg") ?>" /></a><a href="<?php echo URL::website('/member/forgetpassword')?>"><img src="<?php echo URL::webstatic("images/password_rt/send_mail.jpg") ?>" /></a></p>
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
