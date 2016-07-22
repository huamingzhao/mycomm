<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>退订成功</title>
<style type="text/css"/>
*{margin:0;padding:0;}
.wrap{width:700px;margin:0 auto;font-size:14px;font-family:"宋体";}
.title{background:url(<?=URL::webstatic('/images/email_back/email_back.jpg')?>) no-repeat;height:55px;line-height:55px;font-size:36px;font-family:"微软雅黑";color:#24b231;padding-left:60px;margin:200px 0 0 200px;}
.wrap p{padding:15px 0 0 200px;}
.wrap p span{color:#a4a4a4;}
.wrap p a{color:#003cff;font-weight:bold;}
</style>
</head>
<body>
<div class="wrap">
	<div class="title">退订成功！</div>
    <p><span>明天开始，您将不会收到此类邮件。</span></p>
    <p>如想再次订阅，请至一句话<a href="<?=URL::website('/company/member/investor/searchSubscription')?>">“订阅中心”</a>进行订阅。</p>
</div>
</body>
</html>
