<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>邮件邀请</title>
</head>

<body>
    <table cellspacing="0" cellpadding="0" style="color: #333; font-family:'微软雅黑'; width: 700px; margin:0 auto;">
        <tr>
            <td><img src="<?php echo URL::webstatic("images/edm/friendmail/header.png")?>"></td>
        </tr>
        <tr>
            <td style="background: url(<?php echo URL::webstatic("images/edm/friendmail/content.png")?>) no-repeat; width:700px; height:275px; margin:0" valign="top">
                <p style="width:600px; margin:0 auto;line-height:28px;" ><span style="font-size: 18px;">尊敬的先生/女士：</span><br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您的好友 <font style=" color:#ff015b; font-weight: bold;"><?php echo $str;?></font>邀请您加入<?php echo $send_content;?><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>/zt3/zhuce.shtml?inviter_user_id=<?php echo $user_id?>&inviter_type=1" style="color:#0b73bb; text-decoration: none;">http://<?php echo $_SERVER['HTTP_HOST'];?>/zt3/zhuce.shtml?inviter_user_id=<?php echo $user_id;?>&inviter_type=1</a></p>
            </td>
        </tr>
        <tr>
            <td style="background:url(<?php echo URL::webstatic("images/edm/friendmail/footer.png")?>) no-repeat; width:700px; height:325px;" valign="top">
                    <p style="line-height:20px;text-align: center; color: #9c2501; font-size: 24px;">网站介绍</p>
                    <p style="width:600px; margin:0 auto; line-height:25px; font-size: 14px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;一句话网站是通路快建旗下领先的招商投资平台。网站致力于为投资者和企业建立精确高效的意向合作关系，以丰富的企业和投资者数据信息为基础，独家推出“精准匹配”功能，使招商创业真正成为一句话的事。<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;已有数千家企业入驻一句话。欢迎您入住使用一句话，享受免费服务，获得实实在在的流量收益！<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;将达百万创业者注册一句话。真实的全国各地投资者信息等待项目精准匹配，就在一句话！</p>
            </td>
        </tr>
    </table>
</body>
</html>