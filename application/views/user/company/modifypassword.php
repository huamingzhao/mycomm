<?php echo URL::webjs("jquery.validate.js")?>
<?php echo URL::webjs("validate_expand.js")?>
<?php echo URL::webjs("validate.js")?>
<?php echo URL::webjs("common.js")?>
<?php echo URL::webjs("modify.js")?>

    <!--右侧开始-->
    <div id="right" class="right">
        <div class="user_right_title">
            <span>修改密码</span>
            <div class="clear"></div>
        </div>
        <div id="right_con" style="height:auto!important; height:300px; min-height:300px;">
            <div id="modify">
            <form id="modifypassword" method="post" action="">
                <p><label><span>*</span>原密码：</label><input type="password" name="password_xg"  value="" id="password_xg" class="password_xg" onkeyup='this.value=this.value.replace(/\s/g,"")'/><span class="tipinfo2" id="password_oldinfoid1"></span><!--<b>请输入原密码</b>--></p>
                <p><label><span>*</span>新密码：</label><input type='password' name="newpassword_xg" id="newpassword_xg" class="password_xg"  onkeyup='this.value=this.value.replace(/\s/g,"")'/><span class="tipinfo2"></span><!--<b>请输入6-20位字母或数字，区分大小写</b>--></p>
                <p><label><span>*</span>确认新密码：</label><input type='password' name="confirmpassword_xg" id="confirmpassword_xg" class="password_xg" onkeyup='this.value=this.value.replace(/\s/g,"")'/><span class="tipinfo2"></span><!--<b>请再次输入密码</b>--></p>
                <div id="modify_btn"><input type="image" src="<?php echo URL::webstatic("images/modify/modify_btn.png") ?>" /></div>
            </form>
            </div>
        </div>
    </div>

    <!--右侧结束-->
<!--弹出框开始-->
<div id="modify_opcity"></div>
<div id="modify_alert"><a href="<?php echo URL::website("/userlogin/logout/");?>" class="close">关闭</a>
    <p> <a href="<?php echo URL::website("/userlogin/logout/");?>" >确定</a></p>
</div>
<!--弹出框结束-->
<?php if(isset($password) && $password == "ok"):?>
<script>
//弹出框
jQuery(document).ready(function (){
    window.MessageBox({
		title:"修改密码",
		content:"<p>您的密码已经成功修改...</p>",
		btn:"ok",
		onclose:function(){
			window.location.href="<?php echo URL::website("/userlogin/logout/");?>";
		}
   	})
    return false;
});
</script>
<?php endif;?>
