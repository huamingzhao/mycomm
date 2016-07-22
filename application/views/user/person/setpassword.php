<!--主体部分开始-->    <!--右侧开始-->
<?php echo URL::webcss("personcenter.css")?>
    <div id="right">
        <div id="right_top"><span>手机验证</span><div class="clear"></div></div>
        <div class="phonecheck">
            <p><?php echo $showc?></p>
            <form id="phonecheck" action="/person/member/basic/setpassword" method="post">
            <dl class="phonedl clearfix">
                <dt><?php echo $showt?>：</dt>
                <dd><?php echo $show?><a href="<?php echo $url?>" class="ml30">修改</a></dd>
                <dt>绑定密码：</dt>
                <dd><input name='passwd' type="password" class="pw1" placeholder="请设置您登录平台的密码"><span class="pwtishi1">密码不能为空</span></dd>
                <dt>确认绑定密码：</dt>
                <dd><input type="password" class="pw2" placeholder="请确认您登录平台的密码"><span class="pwtishi2">密码不能为空</span></dd>
                <dt>&nbsp;</dt>
                <dd><a href="javascript:;" class="login_btna ptb7 js_loginpwd">确&nbsp;&nbsp;定</a></dd>
            </dl>
            </form>
            <script type="text/javascript">
                $(".js_loginpwd").click(function(){
                    if($(".pw1").val()==""){
                        $(".pwtishi1").show().text("密码不能为空");
                        return false;
                    }
                    if($(".pw2").val()==""){
                        $(".pwtishi2").show().text("密码不能为空");
                        return false;
                    }
                    if($(".pw1").val()!=$(".pw2").val()){
                        $(".pwtishi2").show().text("两次密码不一样");
                        return false;
                    }
                    $("#phonecheck").submit();
                })
                $(".pw1").blur(function(){
                    if($(this).val()!=""){
                        $(this).next().hide();
                    }
                })
                $(".pw2").blur(function(){
                    if($(this).val()!=""){
                        $(this).next().hide();
                    }
                    if($(".pw1").val()!=$(".pw2").val()){
                        $(".pwtishi2").show().text("两次密码不一样");
                        return false;
                    }
                })
            </script>
        </div>
    </div>