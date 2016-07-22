<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title!="" ? $title : "一句话|投资赚钱好项目，一句话的事。";?></title>
<meta name="Keywords" content="<?php echo $keywords!="" ? $keywords : "投资，赚钱，好项目，一句话，投资赚钱";?>" />
<meta name="description" content="<?php echo $description!="" ? $description : "一句话平台专业、快速、精准匹配帮助加盟商与投资者达成真实、诚信的招商投资互动。投资好项目赚钱一句话的事。";?>" />
<meta property="qc:admins" content="2445577256611250516654" />
<meta property="wb:webmaster" content="9fd271dcc23ff676" />
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("common_global.css")?>
<?php echo URL::webcss("common_plugIn.css")?>
<?php echo URL::webcss("quickrelease.css")?>
<?php echo URL::webjs("jquery-1.4.2.min.js")?>
<?php echo URL::webjs("quick_login.js")?>
<?php echo URL::webjs("commom_global.js")?>

</head>
<?php
	$login = cookie::get ( 'authautologin' );
	$cook_username= cookie::get('user_name');
	$pathinfo=@explode('/',$_SERVER['PATH_INFO']);
	//echo "<pre>"; print_r($actionmethod);exit;
?>
<body>
<!-- 快速发布头部开始 -->
<?php 
	$arr_data = array("FastReleaseProject_ShowAddFastReleaseProject",
						"Project_showProject",
						"Project_showProjectDetail",
						"Project_showQuickBasic",
						'Project_showQuickJiaMeng',
						'Project_showQuickTuiGuang',
						"Project_showQuickLianXiRen",
						"User_UserUpgrade",
						"User_UserMessage"
	);
$bool = false;
//echo $actionmethod;exit;
if(in_array($actionmethod,$arr_data)){
	$bool = true;
}
if(isset($pathinfo[1]) && $pathinfo[1] == 'quick' &&  $bool == true){?>
	<div class="quicknavwrap">
	<div class="quicknav clearfix">
		<p class="fl"> 您好，欢迎来到一句话生意网！</p>
		<div class="logreg fr">
			<div class="myyjh fr"><span>我的一句话</span>
			<ul class="myyjhul">
				<?php if( $login){?>
					<li><a href="<?= urlbuilder::qucikProManage();?>">修改/删除</a></li>
					<li><a href="<?= urlbuilder::qucikProManage();?>">我的发布</a></li>
				<?php }else{?>
					<li><a href="javascript:void(0)" class="alterinfo">修改/删除</a></li>
					<li><a href="javascript:void(0)" class="alterinfo">我的发布</a></li>
				<?php }?>
				<li><a href="<?= urlbuilder::qucikAddPro('1')?>">发布生意</a></li>
			</ul>
		</div>
		<?php if($login){?>
			 <span id="header_login" class="loginInfo"> 您好，<?php echo $cook_username;?>
                    <?php if( $user_type==1 ){?>
                        <a href="/company/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }else{?>
                        <a href="/person/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }?>
                    <a href="/userlogin/logout" class="loginout">[退出]</a>
             </span>
			 <?php }else{ ?>
				<span style=" margin-right: 35px;" id="header_login"><a href="javascript:;" class="loginclick">登录</a>
				<i style="margin:0 10px; color:#ddd; font-style: none;">|</i>
				<a href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>">注册</a></span>
			<?php }?>
		
	</div>
	</div>
</div>
<div class="quickrelease-header-wrap">
	<div class="quickrelease-header">
		<a href="<?=URL::website('');?>" class="alogolink fl"><img src="<?php echo URL::webstatic("images/header/logo.png")?>"></a>
		<div class="fl mnav">您的位置： <a href="<?=URL::website('');?>" target="_blank">一句话</a> <?if(isset($whereAreYou)){foreach($whereAreYou as $key => $val) { ?> > <?if($val) {?><a href="<?=$val?>"><?=$key?></a><?}else{?><?=$key?><?}?><?}}?></div>
	</div>
</div>
<!-- 快速发布头部结束 -->

<!--项目官网部 -->
<?php }elseif(isset($actionmethod) && $actionmethod == "Project_showTuiGuangGuide"){?>
	<div class="quicknavwrap">
	<div class="quicknav clearfix">
		<p class="fl quicknavbox">
			<a href="<?=URL::website("");?>" rel="nofollow">首页</a>
			<a href="<?=urlbuilder::quickSearchIndex();?>" rel="nofollow">找生意</a>
			<a href="<?=zxurlbuilder::zixun();?>" rel="nofollow">学做生意</a>
			<a href="<?=urlbuilder::qucikTuiGuangGuide();?>" rel="nofollow">推广指南</a>
			<!-- <a href="javascript:;" style="border-right:0 none;">用户认证</a>-->
		</p>
		<div class="logreg fr">
		<input type="hidden"  id="ss_hiddenvalue"  value="1"/>
		<div class="myyjh fr">
			<span>我的一句话</span>
			<ul class="myyjhul">
				<?php if( $login){?>
					<li><a href="<?= urlbuilder::qucikProManage();?>">修改/删除</a></li>
					<li><a href="<?= urlbuilder::qucikProManage();?>">我的发布</a></li>
				<?php }else{?>
					<li><a href="javascript:void(0)" class="alterinfo">修改/删除</a></li>
					<li><a href="javascript:void(0)" class="alterinfo">我的发布</a></li>
				<?php }?>
				<li><a href="<?= urlbuilder::qucikAddPro('1')?>">发布生意</a></li>
			</ul>
		</div>
		<?php if($login){?>
			 <span id="header_login" class="loginInfo"> 您好，<?php echo $cook_username;?>
                    <?php if( $user_type==1 ){?>
                        <a href="/company/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }else{?>
                        <a href="/person/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }?>
                    <a href="/userlogin/logout" class="loginout">[退出]</a>
             </span>
			 <?php }else{ ?>
       <span style="margin-right: 30px;" id="header_login">
				<a href="javascript:;" class="loginclick">登录</a>
				 <i style="margin:0 10px; color:#ddd;font-style: none;">|</i>
				<a  href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>">注册</a>
        </span>
			<?php }?>
		
	</div>
	</div>
</div>
	<div class="guide clearfix">
	<div class="header_logo"><a href="<?=URL::website('');?>">一句话商机速配平台</a></div>
	<h1 class="fl" style="margin-left: 10px;">> 找生意</h1>
	<p class="fl serchbox" style="margin-left: 30px;">
		<input type="text" placeholder="" id="word" name="w" value="<?=isset($word) ? $word : ''?>" class="serchtext ryl_index_searchtext" autocomplete="off" maxlength="38"/>
		<input type="submit" value="搜索" id="searchSubmit" class="submitbtn"/>
	</p>
	 <!--  <ul style="display:none;left:99px; top:37px; width:462px;" class="auto_list"></ul>-->
	<div class="fr releasebtn">
	<a href="<?php echo urlbuilder::qucikAddPro('1');?>" class="btn red">
		<i class="icons icon-edit">
		</i>免费发布生意</a>
		<?php if($login){?>
			<p><a href="<?= urlbuilder::qucikProManage();?>">管理我的生意信息</a></p>
		<?php }else{ ?>
			<p class="alterinfo">管理我的生意信息</p>
		<?php }?>
		
	</div>
</div>
<?php }elseif(isset($pathinfo[1]) && $pathinfo[1] == 'quick' && $actionmethod== "Project_projectinfo"){?>
	<div class="quicknavwrap">
	<div class="quicknav clearfix">
		<p class="fl"> 您好，欢迎来到一句话生意网！</p>
		<div class="logreg fr">
			<div class="myyjh fr"><span>我的一句话</span>
			<ul class="myyjhul">
				<?php if( $login){?>
					<li><a href="<?= urlbuilder::qucikProManage();?>">修改/删除</a></li>
					<li><a href="<?= urlbuilder::qucikProManage();?>">我的发布</a></li>
				<?php }else{?>
					<li><a href="javascript:void(0)" class="alterinfo">修改/删除</a></li>
					<li><a href="javascript:void(0)" class="alterinfo">我的发布</a></li>
				<?php }?>
				<li><a href="<?= urlbuilder::qucikAddPro('1')?>">发布生意</a></li>
			</ul>
		</div>
		<?php if($login){?>
			 <span id="header_login" class="loginInfo"> 您好，<?php echo $cook_username;?>
                    <?php if( $user_type==1 ){?>
                        <a href="/company/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }else{?>
                        <a href="/person/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }?>
                    <a href="/userlogin/logout" class="loginout">[退出]</a>
             </span>
			 <?php }else{ ?>
				<span style=" margin-right: 35px;" id="header_login"><a href="javascript:;" class="loginclick">登录</a>
				<i style="margin:0 10px; color:#ddd">|</i>
				<a href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>">注册</a></span>
			<?php }?>
		
	</div>
	</div>
</div>
<div class="quickrelease-header-wrap">
	<div class="quickrelease-header">
		<a href="<?=URL::website('');?>" class="alogolink fl"><img src="<?php echo URL::webstatic("images/header/logo.png")?>"></a>
		<div class="fl mnav">您的位置：
		 <a target="_blank" href="<?=URL::website('');?>" target="_blank">一句话</a> &gt; 
		 <a target="_blank" href="<?=urlbuilder::quickSearchIndex();?>">找生意</a> &gt;
		 <?php if(isset($arr_qucik_data['area_list'])){ $name = "";?>
		 	<?php if(arr::get($arr_qucik_data['area_list'], "area_name")){$name = arr::get($arr_qucik_data['area_list'], "area_name");?>
		 		 <a target="_blank" href="<?=urlbuilder::quickSearchCond(array('area_id'=>arr::get($arr_qucik_data['area_list'],"area_id")))?>"><?=arr::get($arr_qucik_data['area_list'], "area_name");?>招商加盟</a> &gt;
		 	<?php }?>
		 	<?php if(isset($arr_qucik_data['industry_list'])){?>
			 	<?php if(arr::get($arr_qucik_data['industry_list'], "industry_name")){ $name.=arr::get($arr_qucik_data['industry_list'], "industry_name");?>
			 		 <a  target="_blank" href="<?=urlbuilder::quickSearchCond(array("industry_id"=>arr::get($arr_qucik_data['industry_list'], "industry_id")));?>"><?=arr::get($arr_qucik_data['industry_list'], "industry_name");?>加盟</a> &gt;
			 	<?php }?>
		 	<?php }?>
		 		
		 <?php }?>
		 		<?php if($name){?> [<?=$name;?>]<?php }?><?=arr::get($arr_qucik_data,"project_brand_name");?>
		 </div>
		<a class="btn btn-yellow fr" style="margin-top:27px; width: 143px; height: 36px;" href="<?php echo URL::website('quick/FastReleaseProject/ShowAddFastReleaseProject');?>"/> <i class="icons icon-edit"></i>免费发布生意</a>
	</div>
</div>
<?php }elseif (isset($pathinfo[1]) && $pathinfo[1] == 'quick' && $actionmethod== "FastReleaseProject_ruleDescription"){?>
	<div class="quicknavwrap">
	<div class="quicknav clearfix">
		<p class="fl"> 您好，欢迎来到一句话商机网！</p>
		<div class="logreg fr">
		<?php if($login){?>
			 <span id="header_login" class="loginInfo"> 您好，<?php echo $username;?>
                    <?php if( $user_type==1 ){?>
                        <a href="/company/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }else{?>
                        <a href="/person/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }?>
                    <a href="/userlogin/logout" class="loginout">[退出]</a>
             </span>
			 <?php }else{ ?>
			 <span style="margin-right: 30px;">
				<a href="javascript:;" class="loginclick">登录</a>
				 <i style="margin:0 10px; color:#ddd">|</i>
				<a  href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>">注册</a>
			 </span>
			 <?php }?>
       			<input type="hidden" value="1" id="ss_hiddenvalue">
			<div class="myyjh fr"><span>我的一句话</span>
				<ul class="myyjhul">
				 <?php if( $login){?>
		          <li><a href="<?= urlbuilder::qucikProManage();?>">修改/删除</a></li>
		          <li><a href="<?= urlbuilder::qucikProManage();?>">我的发布</a></li>
		        <?php }else{?>
		          <li><a href="javascript:void(0)" class="alterinfo">修改/删除</a></li>
		          <li><a href="javascript:void(0)" class="alterinfo">我的发布</a></li>
		        <?php }?>
				<li><a href="<?=urlbuilder::qucikAddPro('1')?>">发布生意</a></li>
			</ul>
			</div>
		</div>
	</div>
</div>
<div class="guide clearfix">
	<div class="header_logo"><a href="<?=URL::website("");?>">一句话商机速配平台</a></div>
	<h1 style="margin-left: 10px;" class="fl">&gt; 帮助中心</h1>
	<div class="fr releasebtn">
	<a class="btn red" href="<?=urlbuilder::qucikAddPro('1')?>">
		<i class="icons icon-edit">
		</i>免费发布生意</a>
		<?php if($login){?>
			<p><a href="<?= urlbuilder::qucikProManage();?>">管理我的生意信息</a></p>
		<?php }else{ ?>
			<p class="alterinfo">管理我的生意信息</p>
		<?php }?>	
	</div>
</div>
<?php }else{?>
	<div class="quicknavwrap">
	<div class="quicknav clearfix">
		<p class="fl"> 您好，欢迎来到一句话生意网！</p>
		<div class="logreg fr">
			<div class="myyjh fr"><span>我的一句话</span>
				<ul class="myyjhul">
					
				<?php if( $login){?>
					<li><a href="<?= urlbuilder::qucikProManage();?>">修改/删除</a></li>
					<li><a href="<?= urlbuilder::qucikProManage();?>">我的发布</a></li>
				<?php }else{?>
					<li><a href="javascript:void(0)" class="alterinfo">修改/删除</a></li>
					<li><a href="javascript:void(0)" class="alterinfo">我的发布</a></li>
				<?php }?>
				<li><a href="<?= urlbuilder::qucikAddPro('1')?>">发布生意</a></li>
				</ul>
			</div>
			 <?php if($login){?>
			 <span id="header_login" class="loginInfo"> 您好，<?php echo $cook_username;?>
                    <?php if( $user_type==1 ){?>
                        <a href="/company/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }else{?>
                        <a href="/person/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }?>
                    <a href="/userlogin/logout" class="loginout">[退出]</a>
             </span>
			 <?php }else{ ?>
			 <span style=" margin-right: 35px;" id="header_login"><a href="javascript:;" class="loginclick">登录</a>
			 <i style="margin:0 10px; color:#ddd; font-style: none;">|</i>
				<a href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>">注册</a></span>
			 <?php }?>
			<input type="hidden"  id="ss_hiddenvalue"  value="1"/>
			
		</div>
	</div>
</div>
<div class="guide clearfix">
	<div class="header_logo"><a href="<?php echo URL::website("quick");?>">一句话商机速配平台</a></div>
	<p class="fl serchbox">
	 	<input type="text" value="<?=isset($word) ? $word : ''?>"  id="word" name="w" class="serchtext ryl_index_searchtext" autocomplete="off" maxlength="38"/>	
		<input type="submit" class="submitbtn" value="搜索" id="searchSubmit"/>
		 <!-- <ul style="display:none;left:99px; top:37px; width:462px;" class="auto_list"></ul>-->
	</p>
	<div class="fr releasebtn">
		<a href="<?= urlbuilder::qucikAddPro('1')?>" class="btn red">
			<i class="icons icon-edit"></i>免费发布生意
		</a>
		<?php if($login){?>
			<p><a href="<?=urlbuilder::qucikProManage();?>">管理我的生意信息</a></p>
		<?php }else{?>
			<p class="alterinfo">管理我的生意信息</p>
		<?php }?>
	</div>
</div> 
<?php }?>


<!--头部结束-->
<?php echo $content;?>
<!--尾部开始-->
<div class="footer">
 <div class="clear"></div>
<div class="quickfooter">
	<div class="clearfix about">
		<strong class="fl" style="height: 30px; line-height: 30px;">
			<a style="margin-left:0;" href="<?php echo urlbuilder::help('aboutus')?>" target="_blank">关于我们</a>
		</strong>
		<div class="fl" style="margin-left:-8px;height: 30px; line-height: 30px;">
			<a href="<?php echo URL::website('/help/aboutus.html')?>" target="_blank">关于一句话</a><a href="<?php echo URL::website('/help/mianze.html')?>" target="_blank">免责声明</a>  
			<?php /*?>
			<a href="<?php echo urlbuilder::help("grfw")?>" target="_blank">个人服务</a>  
			<a href="<?php echo urlbuilder::help("qyfw")?>" target="_blank">企业服务</a>
			<?php */?>
			<a href="<?php echo urlbuilder::root('sitemap')?>" target="_blank">网站地图</a>
			<a href="<?php echo urlbuilder::help('lianxi')?>" target="_blank">联系我们</a>
			<!--  
			<a href="http://weibo.com/u/3303765214" target="_blank" title="新浪微博">
			<img src="<?=URL::webstatic("images/platform/login_new/icon_sina.jpg");?>">
			</a>
			<a target="_blank" href="<?php echo URL::website('')."weixin.html";?>" style="margin-left:10px;" title="微信">
			<img src="<?=URL::webstatic("images/quickrelease/weixing.png");?>">
		   </a>
		   -->
		</div>
	</div>
	<?php if(isset($friend_link) && !empty($friend_link)){?>
	<div class="clearfix links" >
	
		<strong class="fl">
		<a style="margin-left:0;" href="javascript:;">友情链接</a></strong>
		<div class="fl" style="margin-left:-8px;width: 880px;">
			<!-- 合作媒体开始 -->
	          <?php foreach($friend_link as $v){?>
	            <?php if(isset($v->name) && isset($v->domain)){?>
	          		<a href="http://<?php echo str_replace("http://", "", $v->domain);?>" target="_blank" title="<?php echo $v->name;?>" >
	            <?php echo $v->name;?></a>
	            <?php }?>
	          <?php }?>
	       
          <!-- 合作媒体结束 -->
         </div>
          
          <div class="filing" style="padding-top:17px;border-top:1px solid #f1f1f1;margin-top:10px">
		<p> <a href="http://www.tonglukuaijian.com/" style="color:#666;">上海通路快建网络服务外包有限公司</a></p>
		<p>沪ICP备09003231号-25 Copyright 2010 - 2014 www.yjh.com 一句话生意网</p>
	</div>
	</div>
	<?php }else{?>
		 <div class="filing">
		<p> <a href="http://www.tonglukuaijian.com/" style="color:#666;">上海通路快建网络服务外包有限公司</a></p>
		<p>沪ICP备09003231号-25 Copyright 2010 - 2014 www.yjh.com 一句话生意网</p>
	</div>
	<?php }?>
</div>
<?php /*?>
  <div class="footer_bottom">
    <div class="footer_bottom_div clearfix">
      <table style="width:680px; height:60px; float:left;">
        <tbody>
          <tr>
            <td valign="center">
      <ul class="consociation clearfix">
        <!-- 合作媒体开始 -->
        <?php if(isset($friend_link) && !empty($friend_link)){?>
          <li style="height:auto; line-height:17px;">合作媒体：</li>
          <?php foreach($friend_link as $v){?>
            <?php if(isset($v->name) && isset($v->domain)){?>
            <li style="height:auto; line-height:17px;"><a href="http://<?php echo str_replace("http://", "", $v->domain);?>" target="_blank" title="<?php echo $v->name;?>" >
            <?php echo $v->name;?></a></li>
            <?php }?>
          <?php }?>
        <?php }?>
          <!-- 合作媒体结束 -->
      </ul>
            </td>
          </tr>
        </tbody>
      </table>
      <ul class="footer_contact clearfix">
        <li><a rel="nofollow" href="http://weibo.com/u/3303765214" title="关注微博" target="_blank"><img src="<?php echo URL::webstatic("images/platform/footer/footer_14.png")?>" alt="微博"></a></li>
        <li><a rel="nofollow" href="<?php echo urlbuilder::root('weixin');?>" title="进入微信" target="_blank"><img src="<?php echo URL::webstatic("images/platform/footer/footer_16.png")?>" alt="微信"></a></li>
      </ul>
    </div>
  </div>
  <?php */?>
</div>
<!--尾部结束-->
<div style="display:none">
<script>
<?php if(strpos($_SERVER['HTTP_HOST'], ".yjh.com")!==false){?>

//baidu sem
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write("<div style='display:none'>"+unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F80d2a4b8905e3c05839c4ca30516d9ca' type='text/javascript'%3E%3C/script%3E")+"</div>");
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
<script type="text/javascript">
	$(".myyjh").hover(function(){$(this).addClass('on');$(this).find("ul").show()},function(){$(this).removeClass('on');$(this).find("ul").hide()})
</script>
</body>
</html>
