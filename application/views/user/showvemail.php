<?php echo URL::webcss("common.css")?>
<?php echo URL::webjs("jquery.validate.js")?>
<?php echo URL::webjs("email.js")?>
<div id="contain">
    <div id="email_yz">
        <div id="email_yz_top">
            <p><span>注册邮箱：<?php echo $email?></span><?php echo HTML::image("/images/email_yz/email_yz_finish.png")?></p>
        </div>
        <div id="email_yz_con">
            <div><b>亲爱的用户您好：</b></div>
            <p>一句话欢迎您使用邮箱验证功能，当您点击”验证邮箱“按钮时，我们将向您的邮箱发送一封验证邮件，您可以通过点击其中的验证链接，完成您的邮箱验证。</p>
            <p>通过邮箱验证将为您的账号建立完全的保护，您可以通过邮箱取回密码，您可以使用更多更丰富的网站功能和业务。</p>
        </div>
    </div>
</div>