<?php echo URL::webcss("common.css")?>
<?php echo URL::webjs("jquery.validate.js")?>
<?php echo URL::webjs("validate_expand.js")?>
<?php echo URL::webjs("validate.js")?>
<?php echo URL::webjs("common.js")?>
<div id="contain">
    <div id="register">
        <div id="register_title">快速注册</div>
        <!--左侧导航开始-->
        <div id="register_left">
            <ul>
                <li class="register_01"><a href="<?php echo URL::website('member/register')?>?type=per">个人注册</a></li>
                <li class="register_02"><a href="<?php echo URL::website('member/register')?>?type=com">企业注册</a></li>
                <li class="register_033"><a href="<?php echo URL::website('member/register')?>?type=gov">政府机构注册</a></li>
            </ul>
        </div>
        <!--左侧导航结束-->
        <!--右侧内容开始-->
        <div id="register_right">
            <!--政府注册开始-->
            <div class="register_right_03 change">
                <form id="thisForm2" method="post" action="">
                    <p><label for="email2"><span>*</span>登录邮箱：</label><input type='text' name="email" id="email2" class="text"/><span class="tipinfo"></span><b>请输入您的常用邮箱</b></p>
                    <p><label for="userName2"><span>*</span>政府机构名称：</label><input type="text" name="user_name" id="userName2" class="text" /><span class="tipinfo"></span><b>中文或英文 最多为30个字符</b></p>
                    <p><label for="passWord2"><span>*</span>登录密码：</label><input type="password" name="password" id="passWord2" class="text"/><span class="tipinfo"></span><b>请输入6-20位字母、数字或符号</b></p>
                    <p><label for="passWordAgain2"><span>*</span>确认密码：</label><input type='password' name="confirm" id="passWordAgain2" class="text"/><span class="tipinfo"></span><b>请再次输入密码</b></p>
                    <p><label for="verificationCode2"><span>*</span>验 证 码：</label><input type='text' name="verificationCode" id="verificationCode2" class="text"/><img src="<?php echo URL::webstatic("images/register/vf_code.png") ?>" alt="" id="vfCodeImg2"/><span class="vfCodeCh">看不清？换一张</span></p>
                    <p class="chk_p"><input type="checkbox" checked /><span>我已经阅读<a href="#" target="_blank">注册及服务条款</a>，并愿意遵守相关条款规定。</span></p>
                    <p class="btm"><input type="image" src="<?php echo URL::webstatic("images/register/bottom.png") ?>"/><span>我已经有账号了，我要<a href="#">登录</a></span></p>
                </form>
            </div>
            <!--政府注册结束-->
        </div>
        <!--右侧内容结束-->
        <div class="clear"></div>
    </div>
</div>