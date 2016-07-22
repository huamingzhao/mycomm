<?php echo URL::webjs("email.js")?>
<?php echo URL::webcss("email_zg.css")?>
<div id="right">
        <div id="right_top"><span>验证邮箱</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="index">
                <div class="emailContent">
                    <dl class="goActivateMain">
                        <dt id="showimg"><img src="<?php echo URL::webstatic('/images/email_zg/email.png')?>" width="91" height="79" /></dt>
                        <dd>
                            <form id="form_id" action="/company/member/basic/sendEmail" method="POST">
                            <p id="showmessage" style=" margin-top: 15px;">
                                <span style="font-size: 14px;">通过邮箱验证可以增强您的信誉，也方便您找回忘记的密码！</span></br>
                                <span id="setMailInputInfo" style=" display:none; color:#EB0000; font-size: 14px;">请输入正确的邮箱！</br></span>
                                您的邮箱：
                                <input id="setMailInput" value="<?php echo $email?>" name="inp_email" type="text" placeholder="请输入您常用的邮箱" style="width:185px; height: 30px; line-height: 30px; border: 1px #ddd solid; padding: 0 5px;"><INPUT type="hidden" id="hidden_email" value="<?php echo $email?>">
                                <a href="javascript:void(0)" id="setMailSend" class="span_1 span_2 resendMail" id="timeControl" style="height: 30px; line-height: 30px;">发送邮箱验证码</a>

                            </p>
                            </form>
                        </dd>
                    </dl>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <!--右侧结束-->