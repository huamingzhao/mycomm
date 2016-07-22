<?php echo URL::webcss("email_zg.css")?>
<script type="text/javascript">
//JavaScript Document
//倒数N秒展示公用方法
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
    $(".mail_tishi").hide();
    $(".span_2").live("click",function(){
        $(".mail_active a").show();
        $(".mail_tishi").show();
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/ajaxcheck/checkfailsendvemail",
            data: "email=<?php echo Arr::get($data, 'email');?>&user_id=<?php echo Arr::get($data, 'id');?>",
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
    });
});
</script>
<style type="text/css">
.reload{margin-left:0;}
.mail_active a{display:none;}
</style>
<div class="main" style="height:auto; background-color:#e3e3e3; padding:15px 0;">
   <div class="map_bg">
       <div class="map_bg01"></div>
       <div class="map_bg02">
          <div class="mail_error">
            <img src="<?php echo URL::webstatic('images/platform/mail/mail_error.jpg');?>" class="mail_error_pic" />
            <div class="mail_error_right">
               <span>您的认证邮件已过期，点击“重新发送邮件”重新进行邮箱认证！<br/>验证邮箱后，您可以方便找回自己的密码，接受招商投资、加盟等信息的推送。</span>
               <p class="mail_active"><span>接收邮箱：<em><?php echo Arr::get($data, 'email');?></em></span><a href="<?php
               $toemailurl = explode('@', Arr::get($data, 'email'));
               echo "http://mail.".Arr::get($toemailurl, '1');
        ?>"><img src="<?php echo URL::webstatic('images/platform/mail/mail_active.jpg');?>" /></a></p>
               <p class="mail_tishi">邮件已发送，请注意查收！</p>
               <div class="reload">
                    <span class="span_0">您如果没有收到邮件</span>
                    <span class="span_1 span_2" id="timeControl">重新发送邮件</span>
                </div>
            </div>
            <div class="clear"></div>
          </div>
          <div class="clear"></div>
       </div>
       <div class="map_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>