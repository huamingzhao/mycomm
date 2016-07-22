<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>邮件邀请</title>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
		<table id="__01" width="700" height="980" border="0" cellpadding="0" cellspacing="0" style=" margin: 0 auto; font-family:'微软雅黑';">
			<tr>
				<td style="background: #950b0b;">
				<a href="<?php echo URL::website('')?>" target="_blank"><img src="<?php echo URL::webstatic("images/event/lottery4/lottery4Email_01.jpg")?>" width="700" height="387" style="border:none;" alt="马上有奖第四期重磅来袭100%中奖"></a>
				</td>
			</tr>
			<tr>
				<td style="background: #950b0b; height: 250px; color:#ffffff;" valign="middle">
					<h2 style="padding: 0px 35px; font-size: 20px; margin:0;">尊敬的先生/女士：</h2>
					<p style="padding: 0px 35px; margin:25px 0px 0px; line-height:25px; font-size:14px; text-indent: 28px;">您的好友<font style="color:#f9df03; margin:0px 5px;"><?php echo $str;?></font>邀请您加入<?php echo $send_content;?><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/zt4/zhuce.shtml?inviter_user_id=<?php echo $user_id?>&inviter_type=1" style="color:#0b73bb; text-decoration: none;">http://<?php echo $_SERVER['HTTP_HOST'];?>/zt4/jiangpin.shtml?inviter_user_id=<?php echo $user_id;?>&inviter_type=1</a></p>
				</td>
			</tr>
			<tr>
				<td style="background: #950b0b;">
					<img src="<?php echo URL::webstatic('images/event/lottery4/lottery4Email_04.jpg')?>" width="700" height="343" style="border:none;" alt="网站介绍">
				</td>
			</tr>
		</table>
</body>
</html>