<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<SCRIPT type=text/javascript>
function oauthlogin(oauth_id){
    $.getJSON('/ajaxcheck/oauthLogin',{id:oauth_id,callback:""},
            function(content){
                if(content.isError == false)
                    window.location.href = content.url;
                else
                    alert(content.message);
            }
    );
}
</SCRIPT>
</head>
<body>


<a href="javascript:oauthlogin('1');" title="腾讯QQ"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_qq.jpg')?>" /></a>

<a href="javascript:oauthlogin('2');"  title="新浪微博"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_sina.jpg')?>" /></a>
<a href="javascript:oauthlogin('1');"  title="支付宝" class="zfb"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_zfb.jpg')?>" /></a>

</body>
</html>