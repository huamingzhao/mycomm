<html>
<head>
<title>邮件消息模板</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="">
<table id="__01" width="700" height="588" border="0" cellpadding="0" cellspacing="0" style="margin: 0px auto; font-family:'微软雅黑';">
    <tr>
        <td width="350" height="107" background="<?php echo URL::webstatic("images/mail_template/mailTemplate_01.png")?>">
            <a href="<?php echo URL::website('')?>"><img style=" margin-left: 34px; border: none;" width="276" height="61" src="<?php echo URL::webstatic('images/mail_template/mailTemplateLogo_03.png')?>" alt="一句话"></a>
        </td>
        <td width="350" height="107" background="<?php echo URL::webstatic('images/mail_template/mailTemplate_02.png')?>">
            <ul style=" margin-right: 35px;">
                <li style="float: left; list-style: none; padding: 0px 12px; border-right: 1px solid #d0d7d9;"><a href="<?php echo URL::website('/xiangdao/')?>" style="font-size: 12px; color: #0b73bb; text-decoration: none;">找项目</a></li>                
                <li style="float: left; list-style: none; padding: 0px 12px;"><a href="<?php echo URL::website('/zixun/')?>" style="font-size: 12px; color: #0b73bb; text-decoration: none;">学做生意</a></li>
            </ul>
        </td>
    </tr>
    <tr>
        <td colspan="2" width="700" height="230" background="<?php echo URL::webstatic('images/mail_template/mailTemplate_03.png')?>" valign="top">
            <h3 style=" padding: 3px 39px 0px 39px; margin: 0; line-height: 42px; font-size: 16px; font-weight: normal; color: #333333;"><font style="color: #de1817; font-size: 16px;"><?php echo $name;?></font>，企业，您好:</h3>
            <p style=" padding: 0px 110px 0px 72px; margin: 0; line-height: 42px; font-size: 16px; color: #333333;">

           您在一句话网站平台收到<?php echo $count?>张投资者名片，他们会是你合适的投资者吗？机会就在眼前。立即<a href="<?php echo URL::website('/company/member/card/receivecard')?>" style="color: #0b73bb; text-decoration: underline;">查看我收到的名片</a>

</p>
        </td>
    </tr>
    <tr>
        <td colspan="2" width="700" height="251" background="<?php echo URL::webstatic('images/mail_template/mailTemplate_04.png')?>" valign="top">
            <p style=" padding: 177px 0px 0px 184px; font-size: 12px; color: #333333;">一句话网站客服电话<img style=" margin-left: 8px;  vertical-align: -2px;" src="<?php echo URL::webstatic('images/mail_template/mailTemplateTel_03.png')?>" alt="400 1015 908"></p>
        </td>
    </tr>
</table>
</body>
</html>