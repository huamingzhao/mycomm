	<div class="pageMain">
		<div class="mainDiv">
			<h3>找回密码</h3>
			<div class="mainContent">
				<ul class="title clearfix">
					<li class="fc first">1 输入帐号</li>
					<li>2 身份验证</li>
					<li>3 重置密码</li>
					<li class=" last">4 完成</li>
				</ul>
				<div class="content">
					<form id="forgetPassword1" method="post">
						<p class="username">
							<label>账户名：</label>
							<input id="login_name" type="text" name="login_name" placeholder="邮箱或手机号" />
						</p>
						<p class="code">
							<label>验证码：</label>
							<input id="loginVfCode" type="text" name="valid_code" placeholder="验证码" />
							<img id="identifyingCode" src="<?=common::verification_code();?>" width="95" height="37">
							看不清楚？<a href="javascriot:void(0)" id="refreshCode">换一张</a>
						</p>
						<p class="submit">
							<input type="submit" class="btnOk" value="确定" />
						</p>
					</form>
				</div>
			</div>
		</div>
	</div>
	