<?php echo URL::webcss("platform/common.css")?>
<?php echo URL::webcss("platform/login_new.css")?>
<script type="text/javascript">
<!--
$(document).ready(function(){
    $("#name_p").hide();
    $("#password_p").hide();
    $("#creat").hide();
    $("#bind").show();
	//绑定账号
    $('#bind').click(function() {		
        var email = $('#email').val();
        var password = $('#password').val();
        var user_name = $('#user_name').val();
		if($("#email").val()==""){
			$("#email").siblings(".error").text("邮箱不能为空");
			return false;
		}else{
			var isEmail = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
			var returnBack=false;
			if(!isEmail.test($("#email").val())){
				$("#email").siblings(".error").text("邮箱格式错误，请重新输入");
				return false;
			}else{
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
			}
		}
		
        return false;
    });
	$('#creat').click(function() {		
        var email = $('#email').val();
        var password = $('#password').val();
        var user_name = $('#user_name').val();
		if($("#email").val()==""){
			$("#email").siblings(".error").text("邮箱不能为空");
			return false;
		}else{
			var isEmail = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
			var returnBack=false;
			if(!isEmail.test($("#email").val())){
				$("#email").siblings(".error").text("邮箱格式错误，请重新输入");
				return false;
			}else{
				$("#email").siblings(".error").text("");
			}
		}
		/*if($("#user_name").val()==""){
			$("#user_name").siblings(".error").text("昵称不能为空");
			return false;
		}else{
			if($("#user_name").val().length>30){
				$("#user_name").siblings(".error").text("请输入30个字以内");
				return false;
			}
		}*/
		if($("#password").val().length>20 || $("#password").val().length<6){
			$("#password").siblings(".error").text("请输入6-20位密码");
			return false;
		}
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
    });
    $('#email').blur(function() {
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/ajaxcheck/checkoauthbinduser/",
            data: 'email='+$('#email').val(),
            complete :function(){
            },
            success: function(msg){
                if(msg['isError']){
                    $("#name_p").show();
                    $("#password_p").show();
                    $("#bind").hide();
                    $("#creat").show();
                }
                else{
                    $("#name_p").hide();
                    $("#password_p").hide();
                    $("#creat").hide();
                    $("#bind").show();
                }
            }
        });
        return false;
    });
});
//-->
</script>
<div class="main" style="height:auto; background-color:#e3e3e3; padding:15px 0;">
   <div class="ryl_login_bg">
       <div class="ryl_login_bg01"></div>
       <div class="ryl_login_bg02">
           <div class="ryl_login_account_bind">
               <h4><img src="images/platform/login_new/icon01.jpg" /><span>绑定账号</span><div class="clear"></div></h4>
               <div class="ryl_login_bind_cont">
               <p id="email_p"><input name="email" id="email" type="text" placeholder="" class="login_lk_text01" /><span>邮箱 请输入您常用的邮箱</span><span class="error"></span></p>
              <!-- <p id="name_p"><input name="user_name" id="user_name" type="text" value="" class="login_lk_text01" /><span>昵称 中文或英文 最多为30个字符</span><span class="error"></span></p>-->
               <p id="password_p"><input name="password" id="password" type="password" placeholder="" class="login_lk_text01" /><span>密码 6-20位字母、数字或符号</span><span class="error"></span></p>
               <div class="clear"></div>
               <p class="ryl_login_bind_btn"><a href="#" id="creat" class="creat_bind">生成账号</a><a class="creat_bind" href="#" id="bind">绑定账号</a></p>
               </div>
               <div class="clear"></div>
           </div>

          <div class="clear"></div>
       </div>
       <div class="ryl_login_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>