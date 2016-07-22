<!--用户中心修改邮箱-->
<div class="right">
    <h2 class="user_right_title">
        <span>修改邮箱</span>
        <div class="clear"></div>
    </h2>
    <div class="user_change_mail">
        <ul class="change_step">
            <li class="step_first">1、帐号登录密码</li>
            <li class="step_second_fc">2、新邮箱验证</li>
            <li class="step_third">3、修改邮箱成功</li>
        </ul>
        <div class="change_content">
            <p class="mial_send">
                <span>已发送验证邮件至：<?php echo $email?></span>
                <span>请您登录您的邮箱并点击验证链接完成验证，邮箱验证不通过则修改邮箱失</span>
                <a class="button_1" target="_blank"  href="<?php echo $toemailurl?>">去查看邮件</a>
            </p>
        </div>
        <div class="change_content">
            <p class="msg">
                <b>没有收到验证邮件？</b>
                <span>
                    请检查验证邮件是否被您提供的邮箱系统自动拦截，或被误认为垃圾邮件放到垃圾箱了。
如果您仍然没有收到验证邮件，您可以<a href="javascript:void(0)" class="span_1 span_2" id="timeControl">重新发送邮件</a>
                </span>
                <div style="display:none;">邮件已发送，请注意查收！</div>
            </p>
        </div>
    </div>
</div>
<script>
function showTishiEmail(time){
    var i = time;
    setInterval(function(){
        if(i == 1) {
            $("#timeControl").attr('class','').addClass("span_1 span_2").html("重新发送邮件");
            i=-1;
        }
        else if(i>1){
            $("#timeControl").attr('class','').addClass("span_1").html(i--+"秒后重新发送");
        }
    },1000);
}
$(function(){
    $("#timeControl").bind("click",function() {
        if($(this).attr('class')=='span_1 span_2'){
            $("#showimg").html('<img src="'+$config.staticurl+'images/email_zg/email_1.gif" width="127" height="101" />');
            $("#showmessage").html('已经向您的邮箱发送验证邮件，请尽快点击邮件链接完成验证，只有完成验证才可以进行其他操作哦！');
            $(".emailSucceed").css("display","block");
            //ajax邮件发送验证
            $.ajax({
                type: "post",
                dataType: "json",
                url: "/ajaxcheck/checksendvemail",
                data: "",
                complete :function(){
                },
                success: function(msg){
                    if(msg['error']){
                        //发送失败的后续处理
                        showTishiEmail(60);
                    }
                    else{
                        //发送成功的后续显示
                        showTishiEmail(60);
                    }
                }
            });
        }
    });
});

</script>
