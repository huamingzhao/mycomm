
	<div class="pageMain">		
		<div class="mainDiv">
			<h3>找回密码</h3>
			<div class="mainContent">
				<ul class="title clearfix">
					<li class="first">1 输入帐号</li>
					<li>2 身份验证</li>
					<li>3 重置密码</li>
					<li class="fc last">4 完成</li>
				</ul>
				<div class="content">
					<form>
						<p class="submit forgetPasswordOk">
							<span>您已成功修改了密码，请重新登录</span><br/>
							本页面将在<i id="tt_id">4</i>秒后自动跳转自登录页面，如果没有跳转请点击<a href="<?php echo $ref; ?>">登录</a>
						</p>
					</form>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
    Load('<?php echo $ref; ?>', 4);
function Load(url, time){
  if(time<=0){
    window.location.href = url;
    return false;
  }
  document.getElementById("tt_id").innerHTML = time--;
    setTimeout(function(){
    Load(url, time)
  }, 1000);
}
</script>
	