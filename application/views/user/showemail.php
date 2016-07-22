<?php echo URL::webcss("platform/common.css")?>
<?php echo URL::webjs("platform/login/login.js")?>

<?php echo URL::webcss("email_zg.css")?>
<?php echo URL::webjs("email.js")?>
<script type="text/javascript">
window.onload =function() {
    showTishiEmail(60);
};
$(".span_2").click(function(){

});
</script>

<div class="main" style="height:auto; background-color:#e3e3e3; padding:15px 0;">
   <div class="map_bg">
       <div class="map_bg01"></div>
       <div class="map_bg02">
             <div class="mail_check_top">
                  <span class="mail_succheck_icon"><img src="<?php echo URL::webstatic('images/platform/mail/mail_ok.jpg')?>" /></span>
                  <div class="mail_error_right">
                       <h1>恭喜您<?php if( isset( $acttxt ) ){ echo $acttxt; }else{ echo '注册'; }?>成功！</h1>
                       <b>通路快建已经向您的邮箱发送邮件，请立即查收！请您尽快完成邮箱验证激活操作，以便提升您的信誉度！</b>
                       <p class="mail_active"><span>接收邮箱：<em><?php echo $email?></em></span><a href="<?php echo $toemailurl?>"><img src="<?php echo URL::webstatic('images/platform/mail/mail_active.jpg')?>"></a></p>
                       <div class="reload mail_resend">
                            <span class="span_0">如果您没有收到邮件</span>
                            <span class="span_1 span_2" id="timeControl">重新发送邮件</span>
                       </div>
                  </div>
                  <FORM action="/member/showemail" method="post">
                  <div class="mail_verify_mobile">
                      <h4>您也可以通过手机验证来增加您的信誉，以便与招商者沟通，加速您找到心仪的项目</h4>
                      <p class="clearfix mobileNum"><input type="text" id="mail_phoneNum" name="mobile" placeholder="手机号" /><a href="#" id="getPhoneCode">获取手机验证码</a></p>
                      <p class="clearfix mobileCode"><input type="text" id="mail_phoneCode" name="usdf_code" placeholder="验证码" /><span class="error">验证码错误</span></p>
                      <p class="clearfix sumbitNum"><input id="mail_checkMobileBtn" type="image" src="<?php echo URL::webstatic('images/platform/mail/haveSend_11.png')?>"></p>
                  </div>
                  </FORM>
                <div class="clear"></div>
             </div>
             <div class="mail_check_text">
                <h3>验证遇到问题？</h3>
                <ul>
                <li>
                <b>1.为什么要进行邮箱验证？</b>
                <span>答：如您完成邮箱验证，今后在您密码遗忘时，可更方便的找回密码；网上登录更加便捷；同时以便提升您的信誉度。</span>
                </li>
                <li>
                <b>2.如果多次长时间没有收到注册开通账户的确认邮件怎么办？</b>
                <span>答：请您查看邮箱内的“垃圾邮件”中是否有相应的确认邮件，如果“垃圾邮件”中无相应的确认邮件，建议您可尝试重新发送。 </span>
                </li>
                <li>
                <b>3. 验证过的邮箱今后是否可以修改？</b>
                <span>答：会员可使用邮箱注册成为用户，用户名与企业名称绑定将不能进行修改。</span>
                </li>
                </ul>
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

window.onload =function() {
    showTishiEmail(60);
};
$(".span_2").click(function(){

});
</script>
<?php echo $script?>
<!--
<div id="contain">
    <div id="top"></div>
    <div class="emailWrap">
        <span><img src="<?php echo URL::webstatic('/images/email_zg/emailSucceed.jpg')?>"/></span>
        <div class="getEmail">
            <p><span>接收邮箱：<?php echo $email?></span><a href="<?php echo $toemailurl?>" target="_blank"></a></p>
            <div class="reload">
                <span class="span_0">您如果没有收到邮件</span>
                <span class="span_1" id="timeControl">60秒钟后重新发送</span>
            </div>
        </div>
        <div class="emailWord">
            <h1>验证遇到问题？</h1>
            <h2>1.为什么要进行邮箱验证？</h2>
            <p>答：如您完成邮箱验证，今后在您密码遗忘时，可更方便的找回密码；网上登录更加便捷；同时以便提升您的信誉度。</p>
            <h2>2.如果多次长时间没有收到注册开通账户的确认邮件怎么办？</h2>
            <p>答：请您查看邮箱内的“垃圾邮件”中是否有相应的确认邮件，如果“垃圾邮件”中无相应的确认邮件，建议您可尝试重新发送。 </p>
            <h2>3. 验证过的邮箱今后是否可以修改？</h2>
            <p>答：会员可使用邮箱注册成为用户，用户名与企业名称绑定将不能进行修改。</p>
        </div>
    </div>
    <div class="clear"></div>
</div>
 -->
