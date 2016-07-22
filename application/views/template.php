<?
  //当前选中栏目在导航条高亮
  $nav_active = array("", "", "", "", "", "", "", "");
  	
  if(isset($controllermethod) && stristr($controllermethod, '_News')!== FALSE){
    //学做生意
    $nav_active[5]="active";
  }
  else if(strpos($actionmethod, "Index_index") === 0){
    //首页
    $nav_active[0]="active";
  }else if(strpos($actionmethod, "Index_search") === 0){
    //找项目
    $nav_active[1]="active";
  }else if(strpos($actionmethod, "ProjectGuide") === 0){
    //项目向导
    $nav_active[2]="active";
  }else if(strpos($actionmethod, "SearchInvestor") === 0){
    //找投资者
    $nav_active[3]="active";
  }else if(strpos($actionmethod, "Investment") === 0){
    //投资考察
    $nav_active[4]="active";
  }else if(strpos($actionmethod, "Exhibition") === 0){
    //网络展会
    $nav_active[6]="active";
  }else if(strpos($actionmethod, "ExhbProject") === 0){
    //网络展会项目官网
    $nav_active[7]="active";
  }
?>
<?php
	$login = cookie::get ( 'authautologin' );
	$cook_username= cookie::get('user_name');
?>
<? if($nav_active[5]=="active" ||  ($nav_active[0] == "" && $nav_active[1] == "" && $nav_active[2] == "" && $nav_active[3] == "" && $nav_active[4] == ""&& $nav_active[6] == ""&& $nav_active[7] == "") || $nav_active[2]=="active"){//显示学做生意头部 ?>
<?php
$pathinfo=explode('/',$_SERVER['PATH_INFO']);
//var_dump($pathinfo);
//print_r($actionmethod);exit;
?>
<?php if($pathinfo[1] == 'quick' && $actionmethod == 'Search_search' || $actionmethod == 'Search_index'){?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title!="" ? $title : "一句话|投资赚钱好项目，一句话的事。";?></title>
<meta name="Keywords" content="<?php echo $keywords!="" ? $keywords : "投资，赚钱，好项目，一句话，投资赚钱";?>" />
<meta name="description" content="<?php echo $description!="" ? $description : "一句话平台专业、快速、精准匹配帮助加盟商与投资者达成真实、诚信的招商投资互动。投资好项目赚钱一句话的事。";?>" />
<meta property="qc:admins" content="2445577256611250516654" />
<meta property="wb:webmaster" content="9fd271dcc23ff676" />
<!--[if IE 8]>
<meta http-equiv="X-UA-Compatible" content="IE=8"> 
<![endif]-->
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("common_global.css")?>
<?php echo URL::webcss("common_plugIn.css")?>
<?php echo URL::webcss("quickrelease.css")?>
<?php echo URL::webjs("jquery-1.4.2.min.js")?>
<?php echo URL::webjs("quick_login.js")?>
</head>
<body>
	<!-- //找找生意首页 and  结果页  -->
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
				 <i style="margin:0 10px; color:#ddd; font-style: none;">|</i>
				<a  href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>">注册</a>
        </span>
			<?php }?>
		
	</div>
	</div>
</div>
	<div class="guide clearfix">
	<div class="header_logo"><a href="<?=URL::website('');?>">一句话生意网</a></div>
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
<?php }elseif (($pathinfo[1]=='member' && $pathinfo[2]!='login' && $pathinfo[2]!='register' && $pathinfo[2]!='comlead' && $pathinfo[2]!='comphoto')){?>
<!-- //快速发布头部结束 -->
<?php 
//会员功能找回密码等头部 @yamasa
//if(($pathinfo[1]=='member' && $pathinfo[2]!='login' && $pathinfo[2]!='register' && $pathinfo[2]!='comlead' && $pathinfo[2]!='comphoto')){
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title!="" ? $title : "一句话|投资赚钱好项目，一句话的事。";?></title>
    <meta http-equiv="Content-Type" content="textml; charset=utf-8">
    <?php echo URL::webjs("jquery-1.4.2.min.js")?>
    <?php echo URL::webcss("common_global.css")?>
    <?php echo URL::webcss("common_plugIn.css")?>
    <?php echo URL::webcss("875/forgetPassword.css")?>
</head>
<body>
    <div class="header">
        <div class="headerTop">
            <div class="headerContent clearfix">
                <ul class="shortcutNav">
                    <li class="linkOther"><a href="<?php echo URL::website('');?>">一句话首页</a>|</li>
                    <li class="linkOther"><a href="http://www.875.cn">生意街首页</a>|</li>
                    <?php /*?><li><a href="<?php echo URL::website('/zt5/index.shtml');?>">精品商机</a>|</li><?php */?>
                    <li><a href="http://front.875.cn/app/index.html?aid=0&sid=10000">APP客户端下载</a>|</li>
                    <li><a href="http://jt.875.cn/edm/weixin/index.html?aid=0&sid=10000">官方微信</a></li>
                </ul>
                <?php if($login){?>
                <span id="header_login" class="loginInfo"> 您好，<?php echo $username;?>
                    <?php if( $user_type==1 ){?>
                        <a href="/company/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }else{?>
                        <a href="/person/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }?>
                    <a href="/userlogin/logout" class="loginout">[退出]</a>
                </span>
                <?php }else{?>
                 <span id="header_login" class="login_info">  您好，欢迎来到生意街！  <a href="/geren/denglu.html" rel="nofollow" style="color:#f20000;">[请登录]</a>  <a href="/geren/zhuce.html" rel="nofollow">[免费注册]</a>
                 </span>
                <?php }?>
            </div>
        </div>
        <div class="logo"><img src="<?php echo URL::webstatic('/images/875/forgetPassword_18.png');?>">
        </div>
    </div>

    <?php echo URL::webcss("875/forgetPassword.css")?>


<?php
//会员中心头部 @yamasa
}else if( $pathinfo[1]=='person' || $pathinfo[1]=='company'){?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title!="" ? $title : "一句话|投资赚钱好项目，一句话的事。";?></title>
    <meta http-equiv="Content-Type" content="textml; charset=utf-8">
    <?php echo URL::webjs("jquery-1.4.2.min.js")?>
    <?php echo URL::webcss("common_global.css")?>
    <?php echo url::webcss("/platform/common.css")?>
    <?php echo URL::webcss("875/forgetPassword.css")?>
</head>
<body>
    <div class="header" style="background:#fff;">
        <div class="headerTop" style="background:#f7f7f7;">
            <div class="headerContent clearfix" style="width:980px;">
                <ul class="shortcutNav">
                    <li class="linkOther"><a href="<?php echo URL::website('');?>">一句话首页</a>|</li>
                    <li class="linkOther"><a href="http://www.875.cn">生意街首页</a>|</li>
                    <?php if($pathinfo[1]=='company'){?>
                         <!-- <li><a href="<?php echo URL::website('qiye/denglu.html');?>">企业服务</a>|</li> -->
                         <li><a href="http://www.tonglukuaijian.com/check/quick.html?questionType=1&aid=0&sid=10000">360度招商体验</a></li>
                    <?php }else{?>
                         <?php /*?><li><a href="<?php echo URL::website('/zt5/index.shtml');?>">精品商机</a>|</li><?php */?>
                         <li class="downloadAPP"><a href="http://front.875.cn/app/index.html?aid=0&sid=10000">APP客户端下载</a>|</li>
                         <li class="weixin"><a href="http://jt.875.cn/edm/weixin/index.html?aid=0&sid=10000">官方微信</a></li>
                    <?php }?>
                </ul>
                <?php if($login){?>
                <span id="header_login" class="loginInfo"> 您好，<?php echo $cook_username;?>
                    <?php if( $user_type==1 ){?>
                        <a href="/company/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }else{?>
                        <a href="/person/member/msg/msglist" class="msg">消息<i id="messageNum">0</i></a>
                    <?php }?>
                    <a href="/userlogin/logout" class="loginout">[退出]</a>
                </span>
                <?php }else{?>
                 <span id="header_login" class="login_info">  您好，欢迎来到生意街！  <a href="/geren/denglu.html" rel="nofollow" style="color:#f20000;">[请登录]</a>  <a href="/geren/zhuce.html" rel="nofollow">[免费注册]</a>
                 </span>
                <?php }?>
            </div>
        </div>
        <div class="clearfix logobox">
    <a class="floleft usercenterlogo" href="<?php echo URL::website('/member/upgradeNotice')?>"><img src="<?php echo URL::webstatic('/images/875/logo_new.png');?>"></a>
    <div class="floleft navusercenter">

        <div class="user_serchbox">
            <form id="user_serch" action="<?php if($pathinfo[1]=='company'){echo URL::website('/zs/');}elseif($pathinfo[1]=='person'){echo URL::website('/search/');} ?>" method="get">
                <input type="text" class="user_serch" name="w" placeholder="<?php if($pathinfo[1]=='company'){echo '输入您要搜索生意的条件';}elseif($pathinfo[1]=='person'){echo '输入您要搜索项目的条件';} ?>">
                <a href="javascript:;" class="serchbtn"></a>
            </form>
        </div>

        <ul>
            <?php if($pathinfo[1]=='company'){?>
                <li><a href="<?php echo URL::website('');?>">找生意</a></li>
            <?php }else{?>
                <li><a href="http://front.875.cn/index01.html?aid=0&sid=10000">精选商机</a></li>
                <li><a href="<?php echo urlbuilder::quickSearchIndex();?>">找生意</a></li>
                <li><a href="<?php echo URL::website('/zixun/')?>">学做生意</a></li>
            <?php }?>
        </ul>

    </div>
</div>
    </div>
    <?php echo URL::webcss("common_plugIn.css")?>
    <?php echo URL::webcss("875/forgetPassword.css")?>
    <script type="text/javascript">
      $.ajax({
        url : $config.siteurl+"ajaxcheck/updatePersonMsg",
        type : "post",
        dataType : "json",
        success : function(data){
          var obj = $("#messageNum");
          if(obj.length > 0){
            data['msg_total_count']>0 ? obj.html(data['msg_total_count']) : obj.remove();
          }
        }
      });
      // 个人中心 企业中心 头部 搜索项目
      $(".serchbtn").click(function(){
          $("#user_serch").submit();
      });
    </script>
<?php }else{?>
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
</head>

<body style="width:auto;background:none;">
<!--头部开始-->
<div id="header">
    <div class="center">
        <div class="left">
        <ul>
            <?php if(isset($controllermethod) && $controllermethod=='ProjectGuide_Platform'){//项目向导?>
            <li class="first"><a title="一句话" href="<?php echo URL::website('')?>" class="logo_icon logo_icon1">一句话</a></li>
            <li class="first"><a title="项目向导" href="<?php echo urlbuilder:: projectGuide ("fenlei");?>" class="logo_icon_xd" >项目向导</a></li>
            <?php }elseif(isset($controllermethod) && stristr($controllermethod, '_News')!== FALSE){//资讯?>
            <li class="first"><a title="一句话" href="<?php echo URL::website('')?>" class="logo_icon logo_icon1">一句话</a></li>
            <li class="first"><a title="学做生意" href="<?php echo URL::website('/zixun/')?>" class="logo_icon_news">学做生意</a></li>
            <?php }else{?>
            <li class="first"><a title="一句话" href="<?php echo URL::website('')?>"  class="logo_icon">一句话</a></li>
            <?php }?>
            <li class="ct"><a id="Index_index" href="<?php echo URL::website('')?>" rel="nofollow">首页</a></li>
            <li class="ct">
            <!--  
                <a id="Index_search" class="head_find_item"  href="<?php echo urlbuilder::rootDir('xiangdao');?>">找项目</a>
           	-->
           	 <a id="Index_search" class="head_find_item"  href="<?=urlbuilder::quickSearchIndex();?>" rel="nofollow">找生意</a>
            </li>
            <?php //if( isset( $xiangdaoshow ) && ($xiangdaoshow=='1' || $xiangdaoshow=='2') ){?>
           
           <!--  <li class="ct">
                <a id="Xiangdao_search" class="head_find_item <?php if(isset($xiangdaoshow) && $xiangdaoshow == '2'){?>head_find_item on2<?php }?>"  href="<?php echo urlbuilder:: projectGuide ("fenlei");?>">项目向导</a>
            </li>
            --> 
            <?php //}?>
            <!--  <li class="ct"><a  id="SearchInvestor_index" href="<?php echo urlbuilder::zhaotouzi("zhaotouzi");?>">找投资者</a></li> -->
            <!--  <li class="ct"><a  id="Investment_index" href="<?php echo urlbuilder::rootDir ("touzikaocha");?>">投资考察</a></li> -->
            <li class="ct"><a class="<? if($nav_active[5]=="active")echo 'on2'; ?>"  id="Zixun_index" href="<?php echo urlbuilder::rootDir("zixun");?>" rel="nofollow">学做生意</a></li>
            <?php /*?>
            <li class="ct"><a class="<? if($nav_active[7]=="active")echo 'on2'; ?>"  id="ExhbProject" href="<?php echo urlbuilder::rootDir("zhanhui");?>">网络展会</a></li>
            <?php */?>
            <?php /*?><li class="ct choujiang"><a  id="Zixun_choujiang" href="<?php echo URL::website('/zt5/index.shtml');?>" target="_blank">精品商机<img class="lottery_menu_icon" src="<?php echo URL::webstatic('images/event/lottery/lottery_menu_icon_03.png')?>"></a></li><?php */?>
        </ul>
        <a id="actionmethod" style="display:none"><?php echo $actionmethod;?></a>
        <a id="controllermethod" style="display:none"><?php if(isset($controllermethod) && stristr($controllermethod, '_News')!== FALSE){echo 'Index_News';}else{echo '';}?></a>
        </div>
        <div class="right" >
            <div id="loginInfo" class="login">
            <a href="javascript:;" class="loginclick">登录</a>
            <a  href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>" rel="nofollow">注册</a>
            </div>
             <div style="float:left; margin-left:5px;">
             <!-- <a  id="Index_comCenter" href="<?php echo urlbuilder::qiye("denglu");?>">企业服务</a> -->
             </div>
        </div>
    </div>
</div>
<?php }?>
<? }elseif($nav_active[6]=="active"){//网络展会暂时不做头部 @花文刚 ?>

<? }elseif($nav_active[7]=="active"){//网络展会项目官网暂时不做头部 @郁政?>

<? }else{//显示新版头部 ?>
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
<?php echo URL::webjs("commom_global.js")?>
<?php echo URL::webjs("quick_login.js")?>
<?php echo URL::webjs("vali_fastrelease.js")?>
</head>
<body style="width:auto;background:none;">
<!--头部开始-->
<div class="quicknavwrap">
	<div class="quicknav clearfix">
		<p class="fl"> 您好，欢迎来到一句话生意网！</p>
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
			   <span style="margin-right: 30px;" id="header_login">
			 <a href="javascript:;" class="loginclick">登录</a>
			 <i style="margin:0 10px; color:#ddd; font-style: none;">|</i>
			 <a href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>">注册</a>
			 </span>
			  <?php }?>
		       <div class="myyjh fr"><span>我的一句话</span>
		        <ul class="myyjhul" id="myyjhul">
          
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
     
      
			<input type="hidden"  id="ss_hiddenvalue"  value="1"/>
			
		</div>
	</div>
</div>
<div class="guide clearfix">
	<div class="header_logo"><a href="<?php echo URL::website("");?>">一句话生意网</a></div>
	<p class="fl serchbox">
	 	<input type="text" placeholder="" id="word" name="w" class="serchtext ryl_index_searchtext" autocomplete="off" maxlength="38"/>	
		<input type="submit" class="submitbtn" value="搜索" id="searchSubmit"/>
		 <!--  <ul style="display:none;left:99px; top:37px; width:462px;" class="auto_list"></ul>-->
	</p>
	<div class="fr releasebtn" id="myupdateordel">
		<a href="<?= urlbuilder::qucikAddPro('1')?>" class="btn red">
			<i class="icons icon-edit"></i>免费发布生意
		</a>
		<?php if($login){?>
			<p ><a href="<?=urlbuilder::qucikProManage();?>">管理我的生意信息</a></p>
		<?php }else{?>
			<p class="alterinfo">管理我的生意信息</p>
		<?php }?>
	</div>
</div> 
<? } ?>
<!-- 
	<!DOCTYPE html>
	<html>
	<head>
 -->
<?php echo URL::webjs("jquery-1.4.2.min.js")?>
<?php echo URL::webjs("jquery.validate.js")?>
<?php echo URL::webjs("commom_global.js")?>
<?php
$tem_aid = cookie::get ( 'cpa_aid', '0' );
//如果登录了，并且cpa存在 处理cpa吧
if( $islogin===true && $tem_aid!='0' ){

        $aid= $tem_aid;
        $mid = 'yjhcpa';
        $o_cd = Cookie::get('user_id');
        $p_cd = '1';
        $mbr_id = Cookie::get('user_id');
        $sign = md5 ( $aid . '^' . $mid . '^' . $o_cd . '^' . $p_cd );
        //echo "http://service.linktech.cn/purchase_cpa.php?a_id=' . $aid . '&m_id=' . $mid . '&o_cd=' . $o_cd . '&mbr_id=' . $mbr_id . '&sign=' . $sign . '&p_cd=' . $p_cd . '";exit;
        $script = '<script src="http://service.linktech.cn/purchase_cpa.php?a_id=' . $aid . '&m_id=' . $mid . '&o_cd=' . $o_cd . '&mbr_id=' . $mbr_id . '&sign=' . $sign . '&p_cd=' . $p_cd . '"> </script>';
            Cookie::delete('cpa_aid');


}else{
    $script= '';
}

echo $script;
?>

<?php echo $content;?>

<?php
if(!isset($_SERVER['PATH_INFO']))$_SERVER['PATH_INFO']='/123/123/';
$pathinfo=explode('/',$_SERVER['PATH_INFO']);
//会员功能忘记密码等尾部 @yamasa
if($pathinfo[1]=='member' && $pathinfo[2]!='login' && $pathinfo[2]!='register' && $pathinfo[2]!='comlead' && $pathinfo[2]!='comphoto'){
?>
<div class="footer clearfix">
        <ul class="info">
            <li class="telephone">400 1015 908</li>
            <li class="serviceNum">
                <span>客服热线: 021-33233823  400-0606-875</span>
                <span>招商热线：400-0587-988</span>
                <span>招商广告投放热线：400-1006-269</span>
            </li>
            <li> <a href="http://www.tonglukuaijian.com/" style="color:#666;">上海通路快建网络服务外包有限公司</a> 版权所有</li>
            <li>沪ICP备09003231号-38 Copyright 2009 - 2013 www.yjh.com All Rights Reserved</li>
        </ul>
        <ul class="link">
            <li><a href="http://front.875.cn/help/about_syj.html?aid=0&sid=10000">关于生意街</a></li>
            <li><a href="<?php echo URL::website('/help/aboutus.html')?>">关于一句话</a></li>
            <li><a href="<?php echo URL::website('/touzibao.html')?>">投资保障</a></li>
        </ul>
    </div>

     <?php echo URL::webjs("875/jquery.validate.js")?>
      <?php echo URL::webjs("875/forgetPassword.js")?>

 </body>
</html>
<?php
//会员中心尾部 @yamasa
}else if( $pathinfo[1]=='person' || $pathinfo[1]=='company' ){?>
<div class="footer clearfix" style="width:970px;">
        <ul class="info">
            <li class="telephone">400 1015 908</li>
            <li class="serviceNum">
                <span>客服热线: 021-33233823  400-0606-875</span>
                <span>招商热线：400-0587-988</span>
                <span>招商广告投放热线：400-1006-269</span>
            </li>
            <li> <a href="http://www.tonglukuaijian.com/" style="color:#666;">上海通路快建网络服务外包有限公司</a> 版权所有</li>
            <li>沪ICP备09003231号-38 Copyright 2009 - 2013 www.yjh.com All Rights Reserved</li>
        </ul>
        <ul class="link usercenterLinkNew">
            <li><a href="<?php echo URL::website('/member/upgradeNotice')?>">关于生意街会员</a></li>
            <li><a href="http://front.875.cn/help/about_syj.html?aid=0&sid=10000">关于生意街</a></li>
            <li><a href="<?php echo URL::website('/help/aboutus.html')?>">关于一句话</a></li>
            <li><a href="<?php echo URL::website('/touzibao.html')?>">投资保障</a></li>
        </ul>
    </div>

     <?php echo URL::webjs("875/jquery.validate.js")?>
      <?php echo URL::webjs("875/forgetPassword.js")?>

<?php }else{?>
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
	</div>
	<div class="filing" style="padding-top:17px;border-top:1px solid #f1f1f1;margin-top:10px">
		<p>   <a href="http://www.tonglukuaijian.com/" style="color:#666;">上海通路快建网络服务外包有限公司</a> - 如您的电话被冒用，<a href="javascript:;" class="setsaorao" <?php if($login){?>datalogin="1"<?php }else{?>datalogin="0"<?php }?>>去设置防骚扰模式</a><a href="javascript:;" class="cancelsaorao" <?php if($login){?>datalogin="1"<?php }else{?>datalogin="0"<?php }?>>去取消防骚扰模式</a></p>
		<p>沪ICP备09003231号-25 Copyright 2010 - 2014 www.yjh.com 一句话生意网</p>
	</div>
<?php }else{?>
	<div class="filing">
		<p>   <a href="http://www.tonglukuaijian.com/" style="color:#666;">上海通路快建网络服务外包有限公司</a> - 如您的电话被冒用，<a href="javascript:;" class="setsaorao" <?php if($login){?>datalogin="1"<?php }else{?>datalogin="0"<?php }?>>去设置防骚扰模式</a><a href="javascript:;" class="cancelsaorao" <?php if($login){?>datalogin="1"<?php }else{?>datalogin="0"<?php }?>>去取消防骚扰模式</a></p>
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
 <?php */?>
    </div>
<!--尾部结束-->
<div style="display:none;">
<?php if(strpos($_SERVER['HTTP_HOST'], ".yjh.com")!==false){?>
<script>


//baidu sem
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F80d2a4b8905e3c05839c4ca30516d9ca' type='text/javascript'%3E%3C/script%3E"));

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
</script>
<?php }?>
</div>
<?php /*消息定时获取*/if( $islogin===true && $user_type=='1' ){ echo URL::webjs('getmessage_company.js'); }elseif( $islogin===true && $user_type=='2' ){ echo URL::webjs('getmessage_person.js'); }?>
<?php $tongji_name = parse_url(URL::website(""));  echo URL::webjs("stat/{$tongji_name['host']}.tongji.js")?>

<script type="text/javascript">
	$(".myyjh").hover(function(){$(this).addClass('on');$(this).find("ul").show()},function(){$(this).removeClass('on');$(this).find("ul").hide()})
</script>
<?php }?>
</body>
</html>

