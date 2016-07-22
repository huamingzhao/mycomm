<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title!="" ? $title : "一句话|投资赚钱好项目，一句话的事。";?></title>
<?php echo URL::webcss("common_header.css")?>
<?php echo URL::webcss("platform/index0514.css")?>
<?php echo URL::webcss("browse_record.css");?>
<meta property="qc:admins" content="2445577256611250516654" />
<meta property="wb:webmaster" content="9fd271dcc23ff676" />
<meta name="description" content="<?php echo $description!="" ? $description : "一句话平台专业、快速、精准匹配帮助加盟商与投资者达成真实、诚信的招商投资互动。投资好项目赚钱一句话的事。";?>">
<meta name="Keywords" content="<?php echo $keywords!="" ? $keywords : "投资，赚钱，好项目，一句话，投资赚钱";?>">
<?php echo URL::webjs("jquery-1.4.2.min.js")?>
<?php echo URL::webjs("jquery.cookies.2.2.0.js")?>
</head>
<script type="text/javascript">
 $(document).ready(function() {
     var obj = $(".login");
     //头部加载登录注册
     var url = "/ajaxcheck/isLogn";
     var content ="";
     $.post(url,function(data){
        var bool = data.status;
        var user_type = "";
        var user_type_url = "";
        if(data.user_type == true){
            user_type = "去企业中心";
        }else if(data.user_type == false){
            user_type ="去个人中心";
        }
        if(data.user_type_url !=null){
            user_type_url = data.user_type_url;
        }
        if(bool == true){
            content = "<a id='Basic_index' href='"+data.url+"' >"+user_type+"</a>";
            content += "<a id='Msg_index'";
            if( Number(data.msg_total_count)>0 ){
                content+= " class='menu_link_msg' href='"+user_type_url+"'>消息<span id='msg_top_tips'>"+data.msg_total_count+"</span></a>";
            }else{
                content+= " class='menu_link_msg_no' href='"+user_type_url+"'>消息<span id='msg_top_tips'></span></a>";
            } 
            content += "<a href='<?php echo URL::website('userlogin/logout')?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>'>退出</a>";
            obj.html(content);
        }else{
            content = "<a  href='<?php echo urlbuilder::geren('denglu');?><?php if($to_url){ echo '?to_url='.$to_url; ?><?php }?>' rel='nofollow'>登录</a>";
            content += " <a  href='<?php echo urlbuilder::register('geren');?><?php if($to_url){ echo '?to_url='.$to_url; ?><?php }?>' rel='nofollow'>注册</a>";
            obj.html(content);
            }
    },'json');
     var method=$("#actionmethod").html();
     var method2=$("#controllermethod").html();
     var linkstyle=$("#header .center .left ul li .ct a");
     linkstyle.removeClass();
     $("#Basic_index").removeClass();
     $("#Msg_index").removeClass();
     if(method=='Basic_index' || method=="Msg_index"){
         $("#"+method).attr("style","color:#FFFFFF;");
     }else if(method=="SearchInvestor_search"){
         $("#SearchInvestor_index").addClass("on2");
     }else if(method=="Index_comCenter"){
         $("#Index_comCenter").css("color",'#FFFFFF');
     }
     else if(method2=="Index_News"){
         $("#Zixun_index").addClass("on2");
     }
     else{
         $("#"+method).addClass("on2");
     }
    $("#selDown").hover(function(){
         $("#Browsing_showbrowsing").addClass("on");
         $("#sel_icon").attr("src",$config.staticurl+"images/platform/home0514/icon06.png")
         $(".head_find_item_cont").show();
     },function(){
         $("#sel_icon").attr("src",$config.staticurl+"images/platform/home0514/icon05.png")
         $(".head_find_item_cont").hide();
     });
});
</script>
<body style="width:auto;background:none;">
<!--头部开始-->
  <div id="header">
    <div class="center">
        <div class="left">
        <ul>
            <?php if(isset($controllermethod) && $controllermethod=='ProjectGuide_Platform'){//项目向导?>
            <li class="first"><a title="一句话" href="<?php echo URL::website('')?>" class="logo_icon" style="margin-right:8px;text-indent:-9999px;">一句话</a></li>
            <li class="first"><a title="项目向导" href="<?php echo urlbuilder:: projectGuide ("fenlei");?>" class="logo_icon_xd" >项目向导</a></li>
            <?php }elseif(isset($controllermethod) && stristr($controllermethod, '_News')!== FALSE){//资讯?>
            <li class="first"><a title="一句话" href="<?php echo URL::website('')?>" class="logo_icon" style="margin-right:8px;text-indent:-9999px;">一句话</a></li>
            <li class="first"><a title="学做生意" href="<?php echo URL::website('/zixun/')?>" class="logo_icon_news"  style="text-indent:-9999px;">学做生意</a></li>
            <?php }else{?>
            <li class="first"><a title="学做生意" href="<?php echo URL::website('')?>"  class="logo_icon" style="text-indent:-9999px;">学做生意</a></li>
            <?php }?>
            <li class="ct"><a id="Index_index" href="<?php echo URL::website('')?>" >首页</a></li>
            <li class="ct">
                <a id="Index_search" class="head_find_item"  href="<?php echo urlbuilder::rootDir('xiangdao');?>">找项目</a>
            </li>
            <?php if( isset( $xiangdaoshow ) && ($xiangdaoshow=='1' || $xiangdaoshow=='2') ){?>
            <li class="ct">
                <a id="Xiangdao_search" class="head_find_item <?php if(isset($xiangdaoshow) && $xiangdaoshow == '2'){?>head_find_item on2<?php }?>"  href="<?php echo urlbuilder:: projectGuide ("fenlei");?>">项目向导</a>
            </li>
            <?php }?>            
            <li class="ct"><a  id="Zixun_index" href="<?php echo urlbuilder::rootDir("zixun");?>">学做生意</a></li>
            <li class="ct choujiang"><a  id="Zixun_choujiang" href="<?php echo URL::website('/zt/zhuce.shtml')?>" target="_blank">抽奖<img class="lottery_menu_icon" src="<?php echo URL::webstatic('images/event/lottery/lottery_menu_icon_03.png')?>"></a></li>
        </ul>
        <a id="actionmethod" style="display:none"><?php echo $actionmethod;?></a>
        <a id="controllermethod" style="display:none"><?php if(isset($controllermethod) && stristr($controllermethod, '_News')!== FALSE){echo 'Index_News';}else{echo '';}?></a>
        </div>
        <div class="right" >
            <div class="login">
            <?php /*?>
            <?php if(!$islogin) { ?>
            <a  href="<?php echo urlbuilder::geren("denglu");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>" rel="nofollow">登录</a>
            <a  href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>" rel="nofollow">注册</a>
            <?php }else{?>
            <a id="Basic_index" href="<?=$url?>" ><?php echo $username?></a>
            <a  id="Msg_index" href="<?php if($user_type==1) {echo URL::website('/company/member/msg/index');}else{echo URL::website('person/member/msg');}?>">消息<span id="msg_top_tips"><?=$msg_total_count?></span></a>
            <a  href="<?php echo URL::website('userlogin/logout')?>">退出</a>
            <?php }?>
            <?php */?>
             <a  href="<?php echo urlbuilder::geren("denglu");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>" rel="nofollow">登录</a>
            <a  href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>" rel="nofollow">注册</a>
            </div>
             <div style="float:left; margin-left:5px;">
             <a  id="Index_comCenter" href="<?php echo urlbuilder::qiye("denglu");?>">企业服务</a>
             </div>
        </div>
    </div>
</div>
<!--头部结束-->
<?php echo $content;?>
<!--尾部开始-->
  <div class="ryl_index_foot">
    <div class="ryl_index_foot_cont">
       <div class="ryl_index_foot_left">
         <p class="ryl_index_botlogo"><a href="http://www.yjh.com" ><img src="<?php echo URL::webstatic("images/platform/home0514/logo_bot02.png")?>" alt="一句话，投资好项目，一句话的事"/></a></p>

    <div class="ryl_index_botlogo_r">
         <p class="ryl_index_contact">
            <span class="ryl_index_contact01"><?php $mobiles=common::getCustomerPhone();echo $mobiles['1']?></span>
            <a  href="http://wpa.qq.com/msgrd?v=1&uin=2885778591&site=qq&menu=yes" class="ryl_index_contact02" rel="nofollow">2885778591</a>
            <a  href="mailto:kefu@yjh.com" class="ryl_index_contact03" rel="nofollow">kefu@yjh.com</a>
         </p>

         <p class="ryl_index_attention">
           <span><a href="http://weibo.com/u/3303765214" target="_Blank" class="weibo"  rel="nofollow"><img src="<?php echo URL::webstatic("images/platform/home0514/icon_sina.png")?>" alt="一句话项目图片"/><em>关注微博</em></a></span>
           <span><a href="<?php echo urlbuilder::root('weixin');?>" class="weixin"  rel="nofollow"><img src="<?php echo URL::webstatic("images/platform/home0514/icon_weixin.png")?>" alt="一句话项目图片"/><em>进入微信</em></a></span>
         </p>
         <div class="clear"></div>
         </div>
         <div class="clear"></div>
         <p class="ryl_index_text">
            <span>上海通路快建 版权所有  沪ICP备09003231号-25 Copyright 2009 - 2013 www.yjh.com All Rights Reserved</span>
            <!-- 合作媒体开始 -->
            <?php if(isset($friend_link) && !empty($friend_link)){?>
            <span>合作媒体：
            <?php foreach($friend_link as $v){?>
                <?php if(isset($v->name) && isset($v->domain)){?>
                    <a href="http://<?php echo str_replace("http://", "", $v->domain);?>" target="_blank" ><?php echo $v->name;?></a>
                <?php }?>
             <?php }?>
            </span>
            <?php }?>
            <!-- 合作媒体结束 -->
         </p>
       </div>
       <div class="ryl_index_foot_right">
           <div class="ryl_index_foot_r01">
           <h3>关于一句话</h3>
           <ul>
           <li><a  href="<?php echo urlbuilder::help("grfw")?>" target="_blank">个人服务</a></li>
           <li><a  href="<?php echo urlbuilder::help("qyfw")?>" target="_blank">企业服务</a></li>
           <li><a  href="<?php echo urlbuilder::help('aboutus')?>" target="_blank">关于我们</a></li>
           <li><a  href="<?php echo urlbuilder::help('lianxi')?>" target="_blank">联系我们</a></li>
           <li><a  href="<?php echo urlbuilder::root('sitemap')?>" target="_blank">网站地图</a></li>
           </ul>
           </div>

           <div class="ryl_index_foot_r02">
           <h3>帮助中心</h3>
           <ul>
           <li><a  href="<?php echo urlbuilder::help('mianze')?>" target="_blank">免责声明 </a></li>
           <li><a  href="<?php echo urlbuilder::help('xuzhi')?>" target="_blank">客户须知 </a></li>
           <li><a  href="<?php echo urlbuilder::help('chengxin')?>" target="_blank">诚信与安全</a></li>
           <li><a  href="<?php echo urlbuilder::help('yinsi')?>" target="_blank">隐私声明 </a></li>
           <li><a  href="<?php echo urlbuilder::root("touzibao");?>" target="_blank">投资保障</a></li>
           </ul>
           </div>

           <div class="ryl_index_foot_r03">
           <h3>其他</h3>
           <ul>
           <li><a  href="<?php echo urlbuilder::root('link')?>" target="_blank">友情链接</a></li>
           <li class="ct"><a  id="Help_fankui" href="<?php echo urlbuilder::help('fankui');?>">意见反馈</a></li>
           </ul>
           </div>
       </div>
    </div>
</div>
<!--尾部结束-->
<div style="display:none;">
<script>
<?php if($_SERVER['HTTP_HOST']=="www.yjh.com"){?>

var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fb9e95605f53e148b15df399ec8d8b0a3' type='text/javascript'%3E%3C/script%3E"));


  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41009671-1', 'yjh.com');
  ga('send', 'pageview');
<?php }?>
$(function(){
    //头部搜索框事件
    $("#quickso_submit").click(function(){
        if( $("#quickso_M").val()=="搜索"){
            alert("请输入关键字");
            return false;
        }else{
            window.location.href=$config.siteurl+'platform/index/search?w='+encodeURI($("#quickso_M").val());
        }
    });
    $("body").focus();
})

</script>
</div>
<?php /*消息定时获取*/if( $islogin===true && $user_type=='1' ){ echo URL::webjs('getmessage_company.js'); }elseif( $islogin===true && $user_type=='2' ){ echo URL::webjs('getmessage_person.js'); }?>
<?php $tongji_name = parse_url(URL::website(""));  echo URL::webjs("stat/{$tongji_name['host']}.tongji.js")?>

</body>
</html>
