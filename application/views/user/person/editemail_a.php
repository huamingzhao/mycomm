<!--用户中心修改邮箱-->
<div class="right">
    <h2 class="user_right_title">
        <span>修改邮箱</span>
        <div class="clear"></div>
    </h2>

    <div class="user_change_mail">
            <ul class="change_step">
                <li class="step_first_fc">1、帐号登录密码</li>
                <li class="step_second">2、新邮箱验证</li>
                <li class="step_third">3、修改邮箱成功</li>
            </ul>
            <FORM action='<?php echo URL::website('/person/member/basic/editMailB')?>' method="post" id="fo1">
            <div class="change_content">
                <font>您的账号登录密码：</font><input type="password" id="login_psd_id" value="" name='password' />
                <span id="tishi_psd" style="color: red; display: none;"></span>
                <div class="clear"></div>
            </div>
            <div class="change_content change_code">
                <font>验证码：</font><input type="text" value="" id="code_id" />
                <img width="87" height="25" src="<?=common::verification_code();?>" id="vfCodeImg1"/><a href="javascript:void(0)" id="changeCodeImg" >看不清换一张</a>
                &nbsp;<span id="tishi_code" style="color: red; display: none;"></span>
                <div class="clear"></div>
            </div>
            <div class="change_content">
                <a href="javascript:void(0)" class="button_1" onclick="goto_submit()">下一步</a>
            </div>
            </FORM>
    </div>

</div>

<!--用户中心修改邮箱 END-->
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

//submit
function goto_submit(){
    var rt= 0;
    if( $("#login_psd_id").val()=='' || $("#login_psd_id").val()==null ){
        rt= 1;
        //请输入密码
        $("#tishi_psd").show();
        $("#tishi_psd").html('请输入密码');
        return false;
    }else{
        $("#tishi_psd").hide();
        $("#tishi_psd").html('');
    }

    if( $("#code_id").val()=='' || $("#code_id").val()==null ){
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
        url:$config.siteurl + "ajaxcheck/getUserTypeByName",
        data: "login_psd="+$("#login_psd_id").val()+"&valid_code="+$("#code_id").val()+"&login_name=<?php echo $username?>",
        complete :function(){
        },
        success: function(data){
            var psd_error= data['psd_error'];
            if( psd_error=='1' ){
                rt= 1;
                $("#tishi_psd").show();
                $("#tishi_psd").html('密码错误');
                var url = '/captcha';
                url = url+'?'+RndNum(8);
                $("#vfCodeImg1").attr('src',url);

                return false;
            }else{
                $("#tishi_psd").hide();
                $("#tishi_psd").html('');
            }

            var code_error= data['code_error'];
            if( code_error=='2' ){
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
        $("#fo1").action= '/person/member/basic/editMailB';
        $("#fo1").method= "post";
        $("#fo1").submit();
    }
}
</script>
