<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<?php echo URL::webcss("common.css")?>
<title>退订邮件</title>
</head>
<body>
<div id="dingy_email">
	<div class="title">退订邮件</div>
    <div class="context">
        <form action="/member/cancellationSubscription/" method='post'>
    	<p><b>您可以告诉我们您取消订阅的原因，我们会及时改进：</b></p>
        <p><input type="checkbox" name="reasone" value="没有我想要的信息"/>没有我想要的信息</p>
        <p><input type="checkbox" name="reasone" value="发送太频繁了"/>发送太频繁了</p>
        <input type="hidden" value="<?=$key?>" name='key'><input type="hidden" value="<?=$user_id?>" name='user_id'>
        <p>其他：<br/><textarea name="text_reasone"></textarea><br/><input type="image" src="<?=URL::webstatic('/images/dingyue_email/submit.jpg')?>" onclick="submit()" /></p>
    </div>
</div>
</body>
</html>
