<?php echo URL::webcss('personcenter.css')?>
<!--主体部分开始-->    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>帐号绑定</span><div class="clear"></div></div>
        <div class="wsinfobox">
            <h3>请完善您的信息生成新用户并完成绑定</h3>
            <form id="userform" action="/person/member/basic/userBindComplete" method="post">
                <ul class="oullist">
                <li class="clearfix">
                    <span class="flospan"><em>*</em>姓名：</span>
                    <input type="text" class="wt160 username" placeholder="请输入您的真实姓名" name="realname">
                    <span style="display: none; color: red;">请输入正确的姓名</span>
                </li>
                <li class="clearfix">
                    <span class="flospan"><em>*</em>手机号：</span>
                    <input type="text" class="mobile" placeholder="请输入您常用的手机号" name="mobile">
                    <span style="display: none; color: red;">请输入正确的手机号码</span>
                </li>
                <li class="clearfix">
                    <span class="flospan"><em>*</em>短信验证码：</span>
                    <input type="text" class="wt160 mobilecheck" style="width:140px;" placeholder="请输入您获取的手机验证码" name="mobile_code">
                    <a href="javascript:;" class="getcaptcha" canGet>获取验证码</a>
                    <i>如果没有收到验证码，请重新获取</i>
                    <p style="display: none; color: red; margin-left: 110px;">验证码不能为空</p>
                </li>
                <li class="clearfix">
                    <span class="flospan">邮箱：</span>
                    <input type="text" placeholder="请输入您常用的邮箱" name="email" class="email">
                    <span style="display: none; color: red;">邮箱格式错误，请重新输入</span>
                </li>
                <li class="clearfix">
                    <span class="flospan">目前所在地：</span>
                    <select class="cascadingDropList" name="per_area">
                      <option>请选择</option>
                    </select>
                    <select name="area_id">
                      <option>请选择</option>
                    </select>
                </li>
                <li class="clearfix">
                    <span class="flospan"> 意向投资金额：</span>
                    <select name="per_amount">
                        <option>请选择</option>
                        <?php
                        $tz_arr= common::moneyArr();
                        if( !empty( $tz_arr ) ){
                            foreach ( $tz_arr as $key=>$tz_vss ){
                        ?>
                        <OPTION value="<?php echo $key?>"><?php echo $tz_vss?></OPTION>
                        <?php }}?>
                    </select>
                </li>
                <li class="clearfix">
                    <span class="flospan"> 意向投资行业：</span>
                    <div style="position:relative;">
                      <a href="#" class="select_area_toggle" data-url="/ajaxcheck/primaryIndustry" first-result=".per_area_id" second-result=".per_area_id" box-title="行业" select-all="clear">请选择行业</a>
                      <input type="hidden" value="" class="per_area_id" name="per_industry">
                    </div>
                </li>
                <li class="clearfix">
                    <span class="flospan"><em>*</em>绑定密码：</span>
                    <input type="password" placeholder="请设置您登录平台的密码" class="pwd" name="password">
                    <span style="display: none; color: red;">密码不能为空</span>
                </li>
                <li class="clearfix">
                    <span class="flospan"><em>*</em>确认绑定密码：</span>
                    <input type="password" placeholder="请确认您登录平台的密码" class="checkpwd" name="confirm">
                    <span style="display: none; color: red;">确认密码不能为空</span>

                </li>
                <li class="clearfix">
                    <span class="flospan">&nbsp;</span>
                    <INPUT class="bindsubmit" type="submit" value="完成，并绑定">
                    <a href="/person/member/basic/oauthUserBinding" class="ml15">已有账号，直接绑定</a>

                </li>
            </ul>
            </form>
        </div>
        <script type="text/javascript">
            var username=$(".username");
            var isPhone = /(^(13|14|15|18)\d{9}$)/;
            var mobile=$(".mobile");
            var checkpwd=$(".checkpwd");
            var pwd=$(".pwd");
            var usernamecheck=/^([\u4e00-\u9fa5a-zA-Z]{1,20})$/;
            var isEmail = /^([a-zA-Z0-9]+[_|\-|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
            $(".bindsubmit").click(function(){
                if(usernamecheck.test(username.val())){
                    username.next().hide();
                }
                else{
                    username.next().show();
                    return false;
                }
                if(isPhone.test(mobile.val())){
                    var msg=$.ajaxsubmit("/ajaxcheck/checkMobile",{"mobile":mobile.val()});
                    if(msg.code == 500){
                        mobile.next().show().text("手机号已被绑定");
                        $(".getcaptcha").attr("canGet", "false");
                        return false;
                    }else{
                        mobile.next().hide();
                        $(".getcaptcha").attr("canGet", "true");
                    }
                }
                else{
                    mobile.next().show();
                }
                if($(".mobilecheck").val()==""){
                    $(".mobilecheck").nextAll("p").show().text("验证码不能为空");
                    return false;
                }
                else{
                    var dt=$.ajaxsubmit("/ajaxcheck/getMobileCodeEof",{"mobile":$(".mobile").val(),"code":$(".mobilecheck").val()});
                    if(dt.code==200){
                        $(".mobilecheck").nextAll("p").hide();
                        
                        
                    }
                    else{
                        $(".mobilecheck").nextAll("p").show().text("短信验证码错误");
                        return false;
                    }
                }
                if($(".email").val()!="" && $(".email").val()!=$(".email").attr("placeholder")){
                    if(isEmail.test($(".email").val())){
                        var msg=$.ajaxsubmit("/ajaxcheck/checkemail",{"email":$(".email").val()});
                        if(msg == 1){
                           $(".email").next().hide();
                        }
                       if(msg == 0){
                            $(".email").next().show().text("邮箱已注册");
                       }
                    }else{ 
                       $(".email").next().show();
                        return false;
                    }
                }
                else{
                    $(".email").next().hide();
                }

                if(pwd.val()==""){
                    pwd.next().show().text("密码不能为空");
                    return false;
                }
                else{
                    pwd.next().hide();
                }
                if(checkpwd.val()==""){
                    checkpwd.next().show().text("密码不能为空");
                    return false;
                }
                else{
                    checkpwd.next().hide();
                }
                if(checkpwd.val()!==pwd.val()){
                   
                   checkpwd.nextAll("span").text("两次密码不一致").show();
                    checkpwd.show();
                    pwd.show()
                    return false;
                }
                else{
                    alert("提交")
                   // $("#userform").submit()
                }
            })
            mobile.blur(function(){
                if(isPhone.test(mobile.val())){
                    var msg=$.ajaxsubmit("/ajaxcheck/checkMobile",{"mobile":mobile.val()});
                    if(msg.code == 500){
                        mobile.next().show().text("手机号已被绑定");
                        $(".getcaptcha").attr("canGet", "false");
                        return false;
                    }else{
                        mobile.next().hide();
                        $(".getcaptcha").attr("canGet", "true");
                    }
                }
                else{
                    mobile.next().show();
                }
            })
            //点击获取验证码
            $(".getcaptcha").click(function(){
                if($(".getcaptcha").attr("canGet")!="true"){
                    return false;
                }
                var this_ = this;
                $(this).html("正在获取...");
                $(this_).attr("canGet", "false");
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: "/ajaxcheck/sendMobileCode",
                    data: "mobile=" + mobile.val(),
                    complete :function(){
                    },
                    success: function(msg){
                        if( msg['error'] == true){
                        }else{
                        }
                        if(msg['code']=="200"){
                            //发送成功
                            $(this_).next().text("验证码已发手机；如果没收到，请重新获取");
                            getPhoneCodeWait($(this_), 60);
                        }
                        else{
                            //发送失败
                            $(this_).next().text(msg["msg"]);
                            $(this_).attr("canGet", "");
                            $(this_).text("获取验证码");
                        }
                        return true;
                    }
                });
            });
            function getPhoneCodeWait(obj, count){
                obj.text(count+"秒后重新获取");
                if(count!=0){
                    setTimeout(function(){getPhoneCodeWait(obj, --count);}, 1000);
                }else{
                    obj.text("重新获取");
                    obj.attr("canGet", "true");
                }
            }
            
        </script>
    </div>
<!--主体部分结束-->