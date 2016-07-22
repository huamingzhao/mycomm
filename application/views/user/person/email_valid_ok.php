<!--主体部分开始-->
<?php echo URL::webcss("email_zg.css")?>
<?php echo URL::webjs('email.js')?>

    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>验证邮箱</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="index">
                <div class="emailContent">
                    <dl class="goActivateMain">
                        <dt id="showimg"><img src="<?php echo URL::webstatic('/images/email_zg/email.png')?>" width="91" height="79" /></dt>
                        <dd>
                            <p id="showmessage">
                                接收邮箱：<?php echo $email?><a href="<?php echo $toemailurl?>" target="_blank" class="goActivate">去邮箱激活</a><br/>
                                验证码已发送，请注意查收<br/>
                                您如果没有收到邮件<a href="javascript:void(0)" class="span_1 span_2 resendMail" id="timeControl">重新发送</a>
                            </p>
                        </dd>
                    </dl>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <!--右侧结束-->
<!--主体部分结束-->
