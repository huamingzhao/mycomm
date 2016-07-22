<?php echo URL::webcss("email_zg.css")?>
<?php echo URL::webjs("email.js")?>
<?php if($showtime>0){?>
<script>
showTishiEmail(60);
</script>
    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>验证邮箱</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="index">
                <div class="emailContent">
                    <dl>
                        <dt id="showimg"><img src="<?php echo URL::webstatic("/images/email_zg/email_0.gif");?>" width="134" height="110" /></dt>
                        <dd>
                            <p id="showmessage">如没有收到向您发送的验证邮件,请点击下方发送按钮重新发送。</p>
                            <span class="yz">验证邮箱后，您可以方便找回自己的密码，接受招商投资、加盟等信息的推送。</span>
                            <a href="<?php echo $toemailurl?>" target="_blank" class="btn2"></a>
                            <div class="reload">
                                <span class="span_0">您如果没有收到邮件</span>
                                <span class="span_1 span2" id="timeControl">60秒钟后重新发送</span>
                            </div>
                            <div class="emailSucceed">邮件已发送，请注意查收！</div>
                        </dd>
                    </dl>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <!--右侧结束-->
<?php }else{?>
<!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>验证邮箱</span></div>
        <div id="right_con">
            <div id="index">
                <div class="emailContent">
                    <dl>
                        <dt><img src="<?php echo URL::webstatic("/images/email_zg/email_0.gif");?>" width="134" height="110" /></dt>
                        <dd>
                            <p>之前向您邮箱发送的验证邮件已经超时，请点击下方按钮再次发送验证邮箱，并尽快点击邮件链接完成验证。只有完成验证才可以进行其他操作哦！</p>
                            <span class="yz">验证邮箱后，您可以方便找回自己的密码，接受招商投资、加盟等信息的推送。</span>
                            <a href="<?php echo URL::website('/company/member/basic/vemail/?email=send')?>"></a>
                        </dd>
                    </dl>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <!--右侧结束-->
    <?php }?>
