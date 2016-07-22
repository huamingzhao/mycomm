<?php echo URL::webcss("platform/common.css")?>
<?php echo URL::webcss("platform/login_new.css")?>
<script type="text/javascript">
$(function(){
	
    //密码规则验证
    function password(){
        if(passWord.val().length > 20 || passWord.val().length < 6 || passWord.val()=='密码' ){
            passWord.siblings("#tishi_passswd").addClass("error").removeClass("valid").text("密码最少6个字符，最长不得超过20个字符");
        }else{
            passWord.siblings("#tishi_passswd").addClass("valid").removeClass("error").text("");
        }
    }
    //确认密码规则验证
    function confirm_psw(){
        if(conFirm.val().length > 20 || conFirm.val().length < 6 || conFirm.val()=='确认密码'){
            conFirm.siblings("#tishi_confirm").addClass("error").removeClass("valid").text("密码最少6个字符，最长不得超过20个字符");
        }else{
            conFirm.siblings("#tishi_confirm").addClass("valid").removeClass("error").text("");
        }
        if(conFirm.val() == passWord.val()){
            conFirm.siblings("#tishi_confirm").addClass("valid").removeClass("error").text("");
        }else{
            conFirm.siblings("#tishi_confirm").addClass("error").removeClass("valid").text("两次密码输入不一致");
        }
    }

    var inputEmail = $("#input_email_id input");
    //注册邮箱验证
    var isEmail = /^([a-zA-Z0-9]+[_|\-|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;

    function Email(){
        if(!isEmail.test(inputEmail.val())){
            inputEmail.siblings("#tishi_mail").addClass("error").removeClass("valid").text("邮箱格式错误，请重新输入");
        }else{
        	if(!isEmail.test(inputEmail.val())){
                inputEmail.siblings("#tishi_mail").addClass("error").removeClass("valid").text("邮箱格式错误，请重新输入");
            }else{
            	inputEmail.siblings("#tishi_mail").addClass("valid").removeClass("error").text("");
            }
        }
    }
    inputEmail.change(function(){
        Email();
    });
    
	//请输入您常用的邮箱提示语
	
	inputWord("邮箱",inputEmail,"text");

    passWord = $("#passWord");

    $("#passWord").blur(function(){
        pwd = $(this).val();
        if(pwd == ""){
            $(this).hide();
            $("#passWord2").val("密码");
            $("#passWord2").addClass("text");
            $("#passWord2").show();
        }else{
            return false;
        }
    });
    $("#passWord2").focus(function(){
        $(this).hide();
        $("#passWord").show();
        $("#passWord").focus();
        $("#passWord").val("");
    });


    passWord.change(function(){
        password();
    });
    conFirm = $("#passWordAgain");

    $("#passWordAgain").blur(function(){
        pwd = $(this).val();
        if(pwd == ""){
            $(this).hide();
            $("#passWordAgain2").val("确认密码");
            $("#passWordAgain2").addClass("text");
            $("#passWordAgain2").show();
        }else{
            return false;
        }
    });
    $("#passWordAgain2").focus(function(){
        $(this).hide();
        $("#passWordAgain").show();
        $("#passWordAgain").focus();
        $("#passWordAgain").val("");
    });

    conFirm.change(function(){
        confirm_psw();
    });
    
    //提交表单验证规则
    var btn = $("#creat");
    btn.click(function(){
        //邮箱
        if(inputEmail.val() == "" || inputEmail.val() == null || inputEmail.val() == "邮箱"){
            inputEmail.siblings("#tishi_mail").addClass("error").removeClass("valid").text("邮箱不能为空");
            return false;
        }else{
            var returnBack=false;
            if(!isEmail.test(inputEmail.val())){
                inputEmail.siblings("#tishi_mail").addClass("error").removeClass("valid").text("邮箱格式错误，请重新输入");
                return false;
            }else{
            	//密码
                if(passWord.val() == "" || passWord.val() == null){
                    passWord.siblings("#tishi_passswd").addClass("error").removeClass("valid").text("密码不能为空");
                    return false;
                }else{
                    if(passWord.val().length > 20 || passWord.val().length < 6 ){
                        passWord.siblings("#tishi_passswd").addClass("error").removeClass("valid").text("密码最少6个字符，最长不得超过20个字符");
                        return false;
                    }else{
                        passWord.siblings("#tishi_passswd").addClass("valid").removeClass("error").text("");
                    }
                }
                //确认密码
                if(conFirm.val() == "" || conFirm.val() == null){
                    conFirm.siblings("#tishi_confirm").addClass("error").removeClass("valid").text("确认密码不能为空");
                    return false;
                }else{
                    if(conFirm.val().length > 20 || conFirm.val().length < 6 ){
                        conFirm.siblings("#tishi_confirm").addClass("error").removeClass("valid").text("密码最少6个字符，最长不得超过20个字符");
                        return false;
                    }else{
                        if(conFirm.val() == passWord.val()){
                            conFirm.siblings("#tishi_confirm").addClass("valid").removeClass("error").text("");
                        }else{
                            conFirm.siblings("#tishi_confirm").addClass("error").removeClass("valid").text("两次密码输入不一致");
                            return false;
                        }
                    }
                }
                //
                var email = $('#email').val();
                var password = $('#passWord').val();
                var user_name = $('#user_name').val();
            	$.ajax({
        			type: "post",
        			dataType: "json",
        			url: "/ajaxcheck/oauthbinduser/",
        			data: 'email='+email+"&user_name="+user_name+"&password="+password,
        			complete :function(){
        			},
        			success: function(msg){
        				if(msg['isError']){
        					$("#email").siblings(".error").text(msg['message']);
        					returnBack=true;
        				}
        				else{
        					window.location.href = "/person/member/";
        				}
        			}
        		});
                return false;
            }
            if(returnBack){return false;}
        }

    });
})
</script>
<!--中部开始-->
<!--add by 许晟玮 2013年4月27日-->
<div class="main" style="height:auto; background-color:#e3e3e3; padding:15px 0;" id="register">
   <div class="ryl_login_bg">
       <div class="ryl_login_bg01"></div>
       <div class="ryl_login_bg02 ryl_login_bg02_3">
           <div class="ryl_new_regist">
              <span class="ryl_new_regist_info">为保证您能更好的享受网站服务，请完善基本信息</span>
              <!--表单-->
              <div class="ryl_new_regist_cont01 ryl_new_regist_cont01_2">
                <form id="registerForm" method="post">
                  <p id="input_email_id">
                    <em>*</em>
                    <input type="text" class="login_lk_text01" name="email" id="email" placeholder="邮箱"/>
                    <span class="ryl_new_regist_tx_text" id="tishi_mail">请输入您常用的邮箱</span>
                  </p>
                  <div class="clear"></div>
                  <p>
                    <em>*</em>
                    <input type="text" placeholder="密码" class="login_lk_text01" id="passWord2" />
                    <input type="password" placeholder="密码" class="login_lk_text01" name="password" id="passWord" style="display:none;" />
                    <span class="ryl_new_regist_tx_text" id="tishi_passswd">6-20位字母、数字或符号</span>
                  </p>
                  <div class="clear"></div>
                  <p>
                    <em>*</em>
                    <input type="text" placeholder="确认密码" class="login_lk_text01" id="passWordAgain2" />
                    <input type="password" class="login_lk_text01" name="confirm" id="passWordAgain" placeholder="" style="display:none;" />
                    <span class="ryl_new_regist_tx_text" id="tishi_confirm">6-20位字母、数字或符号</span>
                  </p>
                  <div class="clear"></div>
                  <p class="ryl_new_regist_btn">
                    <a class="login_btn" id="creat" href="javascript:void(0)">保  存</a>
                  </p>
                </form>
                <div class="clear"></div>
              </div>
              <!--表单 END-->
            <div class="clear"></div>
          </div>
          <div class="clear"></div>
       </div>
       <div class="ryl_login_bg03"></div>
       <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>

<!--中部结束-->