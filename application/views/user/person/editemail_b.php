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
            <FORM action="<?php echo URL::website('/person/member/basic/editMailBOk')?>" method="post" id='f2'>
            <div class="change_content">
                <font>您的新邮箱：</font><input name="new_email" id="email_id" type="text" /><INPUT type="hidden" name="password" value="<?php echo $password?>" >
                <span id="tishi_email" style="color: red; display: none;"></span>
                <div class="clear"></div>
            </div>
            <div class="change_content change_code">
                <font>验证码：</font><input type="text" name="code" id="code_id" />
                <img width="87" height="25" src="<?=common::verification_code();?>" id="vfCodeImg1"/><a href="javascript:void(0)" id="changeCodeImg">看不清换一张</a>
                &nbsp;<span id="tishi_code" style="color: red; display: none;"></span>
                <div class="clear"></div>
            </div>
            <div class="change_content">
                <a href="javascript:void(0)" class="button_2" onclick="goto_submit()">完成</a>
            </div>
            </FORM>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#changeCodeImg').click(function() {
            var url = '/captcha';
                url = url+'?'+RndNum(8);
                $("#vfCodeImg1").attr('src',url);
         });

         $(".ryl_new_regist_btn01").hover(function(){
        $(this).attr("src",$config.staticurl + "images/platform/login_new/btn_regist_hover.jpg");
    },function(){
        $(this).attr("src",$config.staticurl + "images/platform/login_new/btn_regist.jpg");
    })

});

function goto_submit(){
    var rt= 0;

    //验证email格式等数据，好吧，实际上就是和注册一样了
    //注册邮箱验证
    var email= $("#email_id").val();
    if( email=='' || email==null ){
        rt= 1;
        $("#tishi_email").show();
        $("#tishi_email").html('请输入邮箱');
        return false;
    }else{
        $("#tishi_email").hide();
        $("#tishi_email").html('');
    }

    var isEmail= /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    //邮箱格式
    if(!isEmail.test( email )){
        rt= 1;
        $("#tishi_email").show();
        $("#tishi_email").html('邮箱格式不正确');
        return false;
    }else{
        $("#tishi_email").hide();
        $("#tishi_email").html('');
    }
    //邮箱是否已经注册
    $.ajax({
        async:false,
        type: "post",
        dataType: "json",
        url:$config.siteurl + "/ajaxcheck/checkemail",
        data: "email="+email,
        complete :function(){
        },
        success: function(msg){
                if(msg == 0){
                    rt= 1;
                    $("#tishi_email").show();
                    $("#tishi_email").html('邮箱已被注册');
                    return false;
                }else{
                    $("#tishi_email").hide();
                    $("#tishi_email").html('');
                }
           }
    });

    //判断验证码
    var code= $("#code_id").val();
    if( code=='' || code==null ){
        rt= 1;
        $("#tishi_code").show();
        $("#tishi_code").html('请输入验证码');
        return false;
    }else{
        $("#tishi_code").hide();
        $("#tishi_code").html('');
    }
    $.ajax({
        async:false,
        type: "post",
        dataType: "json",
        url:$config.siteurl + "/ajaxcheck/checkvalidcode",
        data: "valid_code="+code,
        complete :function(){
        },
        success: function(msg){
                if(msg != 1){
                    rt= 1;
                    $("#tishi_code").show();
                    $("#tishi_code").html('验证码错误');
                    var url = '/captcha';
                    url = url+'?'+RndNum(8);
                    $("#vfCodeImg1").attr('src',url);
                    return false;
                }else{
                    $("#tishi_code").hide();
                    $("#tishi_code").html('');
                }
           }
    });

    if( rt==0 ){
        //post
        $("#f2").action= '/person/member/basic/editMailBOk';
        $("#f2").method= 'post';
        $("#f2").submit();
    }else{ return false; }


}
</script>