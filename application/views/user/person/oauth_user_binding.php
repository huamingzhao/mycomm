<!--主体部分开始-->
    <div id="right"><?php echo URL::webcss("personcenter.css")?>
        <div id="right_top"><span>账号绑定</span><div class="clear"></div></div>
        <div class="accountbind">
            <h2>已有账号绑定</h2>
            <dl class="clearfix">
                <dt>登录账号：</dt>
                <dd><input type="text" class="usertext"><span class="tishispan1" style="display:none; color: red; margin-left:10px;">请输入正确的账号</span></dd>
                <dt>登录密码：</dt>
                <dd><input type="password" class="password"><span class="tishispan2" style="display:none; color: red; margin-left:10px;">用户名密码错误</span></dd>
                <dt>&nbsp;&nbsp;</dt>
                <dd><a href="javascript:;" class="login_btna ptb1">绑定</a><a href="/person/member/basic/userBindComplete" class="ml20">如果您没有账号，请完善信息生成新用户并完成绑定</a></dd>
            </dl>
        </div>
        <script type="text/javascript">
            var isEmail = /^([a-zA-Z0-9]+[_|\-|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\-|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
            var isPhone = /(^(13|14|15|18)\d{9}$)/;
            var flag;
            $(".usertext").blur(function(){
                if(isEmail.test($(this).val())){
                    $(".tishispan1").hide();
                    flag=true;
                }
                else if(isPhone.test($(this).val())){
                    $(".tishispan1").hide();
                    flag=false;
                }
                else{
                    $(".tishispan1").show();
                }

            })
            $(".login_btna").click(function(){
                if(flag===undefined){
                    return false;
                }
                var obj=flag?"email":"mobile";
                var dt=$.ajaxsubmit("/ajaxcheck/oauthBindUser",
                    flag?{email:$(".usertext").val(),"password":$(".password").val()}:{mobile:$(".usertext").val(),"password":$(".password").val()})
                //console.log(dt.message)
                if(dt.isError){
                    window.MessageBox(dt.message);
                }
                else{
                    window.MessageBox({
                        title:"标题",
                        content:"<p>绑定成功</p>",
                        btn:"ok",
                        width:400,
                        callback:function(){
                            window.location.href=window.$config.siteurl+"/person/member";
                        },
                         onclose:function(){
                            window.location.href=window.$config.siteurl+"/person/member";
                            return true;
                        },
                        target:"new"
                      });
                }
            })
        </script>
    </div>
<!--主体部分结束-->

