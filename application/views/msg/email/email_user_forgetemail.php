<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>找回密码确认</title>
</head>

<body>
<table cellspacing="0" cellpadding="0" style=" border: 1px solid #f0f0f0; margin:0 auto; width: 700px; color: #333333; font-family:'微软雅黑';">
    <tr>
        <td><img src="<?php echo URL::webstatic('images/edm/headerForgetPassword.png')?>" width="700" height="184" alt="生意街"></td>
    </tr>
    <tr>
        <td>
            <div style="width: 622px; margin: 0 auto;">
            <h3 style="color: #de1817; font-size: 16px;">您好：</h3>

            <p style="font-size: 12px; line-height: 24px">您在生意街点击了“忘记密码”，系统自动为您发送了这封邮件。您可以点击以下链接修改密码：</br>
            <a style=" display: inline-block; max-width: 622px; color:#0b73bb; word-break:break-all;" href="<?php echo $url?>"><?php echo $url?></a></br>
            </br>
            </br>此链接有效期为两个小时，请在两小时内点击链接进行修改。如果您不需要修改密码，或者您从未点击过“忘记密码”按钮，请忽略本邮件。
            </br>
            </br>
            </br>
            如有任何疑问，请联系客服，客服热线：<span style=" color: #c80000; font-size: 14px;">400-1015-908</span></p>
            </div>
        </td>
    </tr>
</table>
</body>
</html>
