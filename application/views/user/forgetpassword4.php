
	<div class="pageMain">
		<div class="mainDiv">
			<h3>找回密码</h3>
			<div class="mainContent">
				<ul class="title clearfix">
					<li class="first">1 输入帐号</li>
					<li class="fc ">2 身份验证</li>
					<li>3 重置密码</li>
					<li class=" last">4 完成</li>
				</ul>
				<div class="content">
					<form id="forgetPassword1" method="post">
						<p class="email verificationPhone">
							<label>您常用的手机号：</label>
							<span id="phoneNumOk" class="phoneNumOk"><?php echo $mobile;?></span>
							<a href="#" id="getCode" class="getCode" data-getFlag="true">获取验证码</a>
							<span class="info">验证码已发送至您的手机，如果您没有收到验证码，请重新获取</span>
						</p>
						<p class="email verificationPhone">
							<label>验证码：</label>
							<input id="phoneCode" type="text" name="phoneCode" />
						</p>
						<p class="submit sentMail verificationPhone">
							<input type="submit" class="btnOk" value="确定" />
						</p>
					</form>
				</div>
			</div>
		</div>
	</div>
	