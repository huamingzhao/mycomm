<?php echo URL::webcss("platform/common.css")?>
<?php echo URL::webjs("platform/login/login.js")?>
<?php echo URL::webcss("common.css");?>

<style>
.map_bg02{ padding-left:20px; padding-right:20px; width:940px;}
.mail_error {padding: 70px 0 250px;}
#password_rt {margin: 0 auto;padding-top: 70px;width: 880px;}
</style>



  <div class="pageMain">
    <div class="mainDiv">
      <h3>找回密码</h3>
      <div class="mainContent">
        <ul class="title clearfix">
          <li class="first">1 输入帐号</li>
          <li class="fc ">2 身份验证</li>
          <li>3 重置密码</li>
          <li class=" last">4 完成</li>
        </ul>
        <div class="content">
          <form>
            <p class="submit sentFail">
              找回密码连接已失效！<br/>
              <a class="sentAgain" href="<?php echo URL::website('/member/forgetpassword')?>">重新找回邮件</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
