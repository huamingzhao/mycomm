
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
					<form>
						<p class="sentMailOk">
							已向<i id="sentEmail"><?php echo $email?></i>发送邮件，请于两个小时之内点击邮件中的链接完成密码的重置。
						</p>
						<p class="submit sentOkBtn ">
							<a href="http://<?php echo $hosts?>" class="btnOk">打开邮箱</a>
							<a href="javascript:;" id="sentAgain" class="btnCancel" data-getFlag="true">重新发送链接</a>
						</p>
					</form>
				</div>
			</div>
		</div>
	</div>
