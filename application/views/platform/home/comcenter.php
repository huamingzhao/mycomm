<?php echo URL::webjs("platform/login/plat_login.js")?>
<?php echo URL::webcss("platform/login_new.css")?>
<?php echo URL::webjs("platform/login/com_login_new.js")?>
<script type="text/javascript">
$(document).ready(function(){
    $(".login_lk_login_btn").hover(function(){
        $(this).attr("src",$config.staticurl + "images/platform/login_new/btn_login_hover.jpg");
    },function(){
        $(this).attr("src",$config.staticurl + "images/platform/login_new/btn_login.jpg");
    })
    $('#changeCodeImg').click(function() {
            var url = '/captcha';
                url = url+'?'+RndNum(8);
                $("#vfCodeImg1").attr('src',url);
                $("#vfCodeImg2").attr('src',url);
        });
    $('#changeCodeImg2').click(function() {
        var url = '/captcha';
            url = url+'?'+RndNum(8);
            $("#vfCodeImg2").attr('src',url);
            $("#vfCodeImg1").attr('src',url);
    });
    var button_a = $("#button_a_xm");
    var button_b = $("#button_b_zsh");
    var loginbox = $("#yellow-b-box");
    var errorbox = $("#error_box_renling");
    var opacity = $("#opacity");
    var closes = $("#yellow-b-box .close");
    var closes2=$(".tan_floor_close");
    var cacelcom=$("#cancel_com");
    //发布项目
    button_a.click(function(){
        $.ajax({
            type: "post",
            dataType: "json",
            url: $config.siteurl+"platform/ajaxcheck/comCenter/",
            data: '',
            complete :function(){
            },
            success: function(msg){
                if(msg['error']=='notlogin'){
                    $("#loginHidden").val('1');//只能企业登录
                    //隐藏第三方登陆框
                    $("#login_lk_friendlink_span").hide();
                    //验证码显示
                    setCaptcha();
                    loginbox.slideDown(500);
                }else if(msg['error']!=''){
                  window.MessageBox({
                    title:"生意街网站提醒您",
                    content:["<p>只有企业用户才能发布项目，赶紧注册为企业用户吧！</p>",
                             "<p class='btn'><a class='ok' href='<?php echo URL::website('/qiye/zhuce.html?loginout=true')?>'>马上注册</a>",
                             "<a class='cancel' href='#'>取消</a></p>"].join(""),
                  });
                }else{//调转发布项目
                    window.location=$config.siteurl+"company/member/project/addproject";
                }
            }
        });
    });
    //发布招商会
    button_b.click(function(){
        $.ajax({
            type: "post",
            dataType: "json",
            url: $config.siteurl+"platform/ajaxcheck/comCenter/",
            data: '',
            complete :function(){
            },
            success: function(msg){
                if(msg['error']=='notlogin'){
                    $("#loginHidden").val('1');//只能企业登录
                    //隐藏第三方登陆框
                    $("#login_lk_friendlink_span").hide();
                    setCaptcha();
                    loginbox.slideDown(500);
                }else if(msg['error']!=''){
                  window.MessageBox({
                    title:"生意街网站提醒您",
                    content:["<p>只有企业用户才能发布项目，赶紧注册为企业用户吧！</p>",
                             "<p class='btn'><a class='ok' href='<?php echo URL::website('/qiye/zhuce.html?loginout=true')?>'>马上注册</a>",
                             "<a class='cancel' href='#'>取消</a></p>"].join(""),
                  });
                }else{//调转发布招商会
                    window.location=$config.siteurl+"company/member/project/addinvest";
                }
            }
        });
    });
    closes.click(function(){
        loginbox.slideUp(500,function(){
            opacity.hide();
        })
        return false;
    });
    closes2.click(function(){
        errorbox.slideUp(500,function(){
            opacity.hide();
        })
        return false;
    });
    cacelcom.click(function(){
        errorbox.slideUp(500,function(){
            opacity.hide();
        })
        return false;
    });
});
</script>
<style>
p.login_new_check{padding-bottom:5px;}
</style>
<div class="main" style="height:auto">
   <!--企业登录-->
   <div class="ryl_qy_login_contain" >
         <!--top-->
         <div class="ryl_qy_login_top">
            <div class="ryl_qy_login_top_center">
                <div class="ryl_qy_login_top_l">
                    <a title="发布项目" id="button_a_xm" href="javascript:void(0)">发布项目</a>
                    <a title="发布招商会" id="button_b_zsh" href="javascript:void(0)">发布招商会</a>
                </div>
                <?php if(isset($is_logoin) && $is_logoin){?>
                <!--欢迎界面-->
                <div class="ryl_qy_login_top_r02">
                   <div class="ryl_qy_login_form">
                       <p class="ryl_qy_login_welcome" style="word-break:break-all;"><span>欢迎您：<br/>亲爱的<em><?php if(isset($com_name)){echo $com_name;}?></em>！</span></p>
                       <p class="ryl_qy_login_welcome_go"><a title="一句话项目图片" href="<?php echo URL::website('company/member');?>"></a></p>
                   </div>
                   <div class="clear"></div>
                </div>
                <?php }else{?>
                   <div class="ryl_qy_login_top_r">
                   <div class="ryl_qy_login_form">
                       <h1>企业登录</h1>
                       <form id="loginForm2" method="post" action="<?php echo URL::website('platform/index/comCenter')?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>">
                       <div class="login_lk_insert qy_login_form_insert" >
                           <p style="padding-bottom:5px;"><input name="email" type="text" value="<?php echo isset($emails) ? $emails : '';?>" placeholder="邮箱" class="login_lk_text03" id="login_email_id_com"/></p>
                           <p class="login_new_check" id="tishi1_com"><?php echo isset($error['email']) ?$error['email'] :"";?></p>

                           <p style="padding-bottom:5px;"><input type="password" placeholder="密码" class="login_lk_text03" name="password" id="loginPassword_com"/></p>
                           <p class="login_new_check" id="tishi2_com"><?php echo isset($error['password']) ? $error['password'] : "";?></p>

                           <p style="padding-bottom:5px;"><input name="valid_code" id="loginVfCode_com" type="text" placeholder="验证码" class="login_lk_text04" /><img src="<?php echo $verification_code;?>" width="95" height="37" id="vfCodeImg1" class="login_lk_yzm"  alt="验证码"/><a href="javascript:void(0)" id="changeCodeImg" class="login_lk_yzmtext">看不清楚？换一张</a></p>
                           <p class="login_new_check" id="tishi3_com"><?php echo isset($error['captcha']) ? $error['captcha'] : "";?></p>
                           <div class="clear"></div>
                           <p class="login_lk_shadow" style="margin-left:0"></p>
                           <div class="clear"></div>
                           <p class="login_lk_wenzi"><input type="checkbox" name="remember" id="cookietime" tabindex="1" value="1" /><span>一周内自动登录</span><a title="忘记密码？" href="<?php echo URL::website('/member/forgetpassword')?>">忘记密码？</a></p>
                           <div class="clear"></div>
                           <p class="login_lk_login"><input type="image" src="<?php echo URL::webstatic('images/platform/login_new/btn_login.jpg')?>" class="login_lk_login_btn"><span>还不是一句话用户？</span><a href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url.'&type=com'; }else{echo '?type=com';}?>">免费注册</a></p>
                           <div class="clear"></div>
                        </div>
                        </form>
                   </div>
                   <div class="clear"></div>
                </div>
                <?php }?>
            </div>
         </div>
         <!--content-->
         <div class="ryl_qy_login_content">
            <!--标题-->
            <div class="ryl_qy_login_content_title">
               <h2>一句话为您提供</h2>
               <div class="ryl_qy_login_content_title_r">
                 <!--<span></span>-->
                 <span class="ryl_qy_login_welcome_shadow"></span>
                 <img src="<?php echo URL::webstatic('images/platform/login_new/hr_pic.jpg')?>" alt="一句话项目图片"/>
               </div>
            </div>
            <!--文字-->
            <div class="ryl_qy_login_content_center">
               <ul>
               <li class="ryl_qy_login_li_left">
                  <p><img src="<?php echo URL::webstatic('images/platform/login_new/qy_icon01.jpg')?>"  alt="一句话项目图片"/><b>搜索投资者精准服务</b></p>
                  <span>“一句话”平台打造专属您企业的服务中心，只需轻击鼠标，筛选想要搜索的投资者条件，在数以万计的个人用户中，快速找到匹配企业项目的个人投资者，企业可通过递送名片，达成友好关系，建立与投资者互动沟通的桥梁，第一时间赢取商机。</span>
               </li>
               <li class="ryl_qy_login_li_right">
                  <p><img src="<?php echo URL::webstatic('images/platform/login_new/qy_icon02.jpg')?>" alt="名片定制人性化服务" /><b>名片定制人性化服务</b></p>
                  <span>小小名片承载无限信息。“一句话”网站为企业定制名片服务，可查看投资者个人信息，进行即时通讯；可浏览投资者从业经验，分析投资潜能；可与投资者交换名片，建立友好互动关系，构建商机平台。</span>
               </li>
               <li class="ryl_qy_login_li_left">
                  <p><img src="<?php echo URL::webstatic('images/platform/login_new/qy_icon03.jpg')?>" alt="信息发布通路服务" /><b>信息发布通路服务</b></p>
                  <span>“一句话”网站打造企业航母级讯息发布通道，为企业提供开放展示平台。能宣传公司形象，发布项目详情；能制定项目标签，传扬产品特点，能倡导加盟条件，告知政策支持；强势推出精美项目图片，真实资质认证和全能海报，横扫千军，席卷投资者眼球。</span>
               </li>
               <li class="ryl_qy_login_li_right">
                  <p><img src="<?php echo URL::webstatic('images/platform/login_new/qy_icon04.jpg')?>" alt="企业自主招商会服务" /><b>企业自主招商会服务</b></p>
                  <span>“一句话”网站为希望拓展项目通路的企业提供自主招商会发布服务，通过网站发布招商会，吸引更多投资者关注，提升公司形象，提高公司知名度，开阔行业影响力带来潜在客户，能加大区域开发和扩展区域加盟代理商，为企业带来直观收益。</span>
               </li>
               <div class="clear"></div>
               </ul>
               <div class="clear"></div>
            </div>
            <!--电话部分-->
            <div class="clear"></div>
         </div>

     <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>
<div class="clear"></div>
<!--透明背景开始-->
<div id="opacity"></div>
<!--透明背景结束-->
    <!--登陆-弹出框开始-->

    <!--登陆-弹出框结束-->

   <!--弹出层-->
   <div id="error_box_renling" class="tan_floor" style="position:fixed;top:50%;left:50%;margin:-144px 0 0 -327px;display:none;z-index:9999">
     <a href="#" class="tan_floor_close"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_close.jpg')?>" alt="一句话项目图片" /></a>
     <div class="clear"></div>
     <span id="errortext"></span>
     <!--<span>只有企业用户才能发布招商会，赶紧注册为企业用户吧！</span>-->
     <p><a href="<?php echo urlbuilder::register("qiye").'?loginout=true'?>"><img src="<?php echo URL::webstatic('images/platform/login_new/qy_btn03.jpg')?>" alt="一句话项目图片" /></a><a id ="cancel_com" href="javascript:void(0)"><img src="<?php echo URL::webstatic('images/platform/login_new/qy_btn04.jpg')?>" alt="一句话项目图片" /></a></p>
   </div>
