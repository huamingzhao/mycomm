<!DOCTYPE html>
<html>
<head>
<?php 
$to_url= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//echo $to_url;
/*echo $now_request_url;
$re_uri=$_SERVER['REQUEST_URI'];
$host_url=$_SERVER['HTTP_HOST'];
if(@stristr($host_url,'wen') ===FALSE){
	$to_url = URL::website($re_uri);
}else{
	$to_url =URL::webwen($re_uri);
}*/
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title!="" ? $title : "";?></title>
<meta name="Keywords" content="<?php echo $keywords!="" ? $keywords : "";?>" />
<meta name="description" content="<?php echo $description!="" ? $description : "";?>" />
<link rel="canonical" href="<?php echo $to_url;?>" />	
</head>
<?php echo URL::webcss("global.css")?>
<?php echo URL::webcss("syb.css")?>
<?php echo URL::webjs("jquery-1.4.2.min.js")?>
<?php echo URL::webjs("jquery.validate.js")?>
<?php echo URL::webjs("global.js")?>
<body>
  <!-- 头部 -->
  <?php $is_null_photo=URL::webstatic('/images/quickrelease/company_default.png');?>
  <input type="hidden" id="platform_num" value="<?php echo $reg_fu_platform_num;?>" />
  <input type="hidden" id="user_num" value="<?php echo $reg_fu_user_num;?>" />
  <input type="hidden" id="is_login" value="<?php echo $isLogin?1:0;?>" />
   <input type="hidden" id="login_user_id" value="<?php echo $login_user_id;?>" />
  <div class="headerAskDiv">
    <div class="headerAsk clearfix">
      <a href="<?php echo URL::website('');?>" class="logo">一句话</a>
      <a href="<?php echo URL::webwen('');?>" class="logo logo2">生意帮</a>
      <div id="questionSearchForm" class="searchForm">
        <span id="searchSelect" class="select">
          <a href="#" class="fc" data-val="生意帮">生意帮</a>
          <a href="#" data-val="找生意">找生意</a>
          <i class="icon_arrow"></i>
          <input type="hidden">
        </span>
        <input type="text" <?php  if(!empty($search_word)) { echo 'value="'.$search_word.'"';} else { echo  'placeholder="搜问题、话题或人"' ;}?> id="searchKey"/>
        <input type="submit" class="subBtn" id="btn_search" >
        <a href="#" onclick="window.QuestionBox()" class="askBtn">提问</a>
      </div>
      <ul class="menu clearfix">
        <li class="fc"><a href="<?php echo URL::webwen('');?>">疑惑</a></li>
       <!--  <li><a href="#">疑惑</a></li>--> 
      </ul>
       <?php if($isLogin) {
       	    $user_center='/person/member'; 
       	    if($login_user_type==1)  
       	    	$user_center='/company/member/';
       	    $user_center_href=URL::website("{$user_center}");
        ?>
      <div class="userLink">
	        <a   href="<?php echo urlbuilder::business_userinfo($login_user_id);?>" class="userInfo" title="<?php echo $login_user_name;?>">
	        <img src="<?php echo empty($login_user_photo)? $is_null_photo : URL::imgurl($login_user_photo);?>" onerror="$(this).attr('src', '<?=$is_null_photo;?>')"  width="37px" height="30px" /><?php echo validations::truncateStr($login_user_name,5,'');?></a>
        <ul class="userMenu">
          <li><a  href="<?php echo urlbuilder::business_userinfo($login_user_id);?>"><i class="icon_user"></i>我的问答</a></li>
          <li><a  target="_blank"  href="<?php echo $user_center_href;?>"><i class="icon_set"></i>资料设置</a></li>
          <li><a  href="/userlogin/logout?to_url=<?php echo $to_url;?>"   class="last"><i class="icon_out"></i>退出</a></li>
        </ul>
      </div>
        <?php } else {?>
      <div class="userLink loginLink">
                <span id="header_login">
                 
                <a href="javascript:;" class="userInfo loginclick"><img src="<?php echo $is_null_photo;?>" width="37" height="30">登录</a>
				</span>
      </div>
        <?php }?>
    </div>
  </div>
  <!-- 头部 END -->
<?php echo $content;?>
<!-- 底部 -->
<div class="askFooterDiv mt15">
<div class="askFooter">
  © 2014&emsp;<a href="http://www.yjh.com/" target="_blank">一句话生意网</a>&emsp;&emsp;&emsp;沪ICP备 09003231号-25
  <span>
    <a href="<?php echo urlbuilder::help('aboutus')?>" target="_blank">关于我们</a>
    <a href="<?php echo urlbuilder::root('sitemap')?>" target="_blank">网站地图</a>
    <a href="<?php echo urlbuilder::help('lianxi')?>" target="_blank">联系我们</a>
  </span>
</div>
</div>
<!-- 底部 END -->
<div style="display:none">
<script>
<?php if(strpos($_SERVER['HTTP_HOST'], ".yjh.com")!==false){?>
//baidu sem
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write("<div style='display:none'>"+unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fa51a2570c71f83c116916394fecddad5' type='text/javascript'%3E%3C/script%3E")+"</div>");
//yjh.com统计

/*
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F80d2a4b8905e3c05839c4ca30516d9ca' type='text/javascript'%3E%3C/script%3E"));
*/
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41009671-1', 'yjh.com');
  ga('send', 'pageview');

  // <!-- ZZCode -->
var _zzsiteid="0t7Ykp0uF29P";
var _zzid = "0t7Ykp0uF29O";
(function() {
  var zz = document.createElement('script');
  zz.type = 'text/javascript';
  zz.async = true;
  zz.src = ('https:' == document.location.protocol ? 'https://ssl.trace.zhiziyun.com' : 'http://static.zhiziyun.com/trace') + '/api/trace.js';
  var s = document.getElementsByTagName('script')[0];
  s.parentNode.insertBefore(zz, s);
})();
// <!-- end ZZCode -->
<?php }?>

</script>
</div>
<?php $tongji_name = parse_url(URL::website(""));  echo URL::webjs("stat/{$tongji_name['host']}.tongji.js")?>
</body>
</html>
