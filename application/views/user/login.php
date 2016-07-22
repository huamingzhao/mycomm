<?php echo URL::webcss("platform/login_new.css")?>
<?php echo URL::webjs("platform/login/login_new.js")?>

<div class="main" style="height:auto; background-color:#e3e3e3; padding:15px 0;">
   <div class="ryl_login_bg">
       <!--<div class="ryl_login_bg01"></div>-->
       <div class="ryl_login_bg02">
          <!--左侧-->
          <div class="login_lk_left">
            <h4>投资赚钱好项目，<br/>一句话的事！</h4>
            <div class="login_new_attention_num">
            <p class="login_new_attention_num_p"><span>目前已有</span><b><?php if( isset($reg_fu_platform_num) ){echo $reg_fu_platform_num;}else{echo 0;}?></b><span>个项目，</span></p>
            <p class="login_new_attention_num_p"><b><?php if( isset($reg_fu_user_num) ){echo $reg_fu_user_num;}else{echo 0;}?></b><span>个用户加入一句话</span></p>
            </div>
             <p class="login_new_btn" style="padding-left: 125px"><a href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>">免费注册</a></p>
            <p class="login_new_tel"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_tel.jpg')?>" /><span>客服电话：</span><b><?php $arrCustomerPhone = common::getCustomerPhone();echo $arrCustomerPhone[1]?></b></p>

          </div>
          <!--右侧-->
          <div class="login_lk_right">
            <div class="login_lh_right_h4"><p class="login_lk_right_h4_spana">马上登录</p><a class="login_lk_right_h4_spanb" href="/geren/zhuce.html">免费注册</a></div>
            <div class="login_lk_insert">
            <form id="loginForm" method="post" action="<?php echo urlbuilder::geren("denglu");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>">

               <p><input name="email" autocomplete="off" type="text" value="<?php echo isset($emails) ? $emails : '';?>" placeholder="注册邮箱/已验证的手机号码" class="login_lk_text01" id="login_email_id" /></p>

               <p class="login_new_check" id="tishi1"><?php echo isset($error['email']) ?$error['email'] :"";?></p>

               <p><input type="password" placeholder="密码" class="login_lk_text01" name="password" id="loginPassword" /></p>

               <p class="login_new_check" id="tishi2"><?php echo isset($error['password']) ? $error['password'] : "";?></p>

               <p><input type="text" placeholder="验证码" class="login_lk_text02" name="valid_code" id="loginVfCode"/><img src="<?=common::verification_code();?>" width="95" height="37" id="vfCodeImg1" class="login_lk_yzm" /><a href="javascript:void(0)" class="login_lk_yzmtext" id="changeCodeImg">看不清楚？换一张</a></p>

               <p class="login_new_check" id="tishi3"><?php echo isset($error['captcha']) ? $error['captcha'] : "";?></p>


               <div class="clear"></div>
               <!--<p class="login_lk_shadow"></p>-->
               <div class="clear"></div>
               <p class="login_lk_wenzi"><input type="checkbox" name="remember" id="cookietime" tabindex="1" value="1" /><span>一周内自动登录</span><a href="<?php echo URL::website('/member/forgetpassword')?>">忘记密码？</a></p>
               <div class="clear"></div>
               <p class="login_lk_login"><input  type="image" src="<?php echo URL::webstatic('images/platform/login_new/btn_logina.jpg')?>" class="login_lk_login_btn"/><!--<span>还不是一句话用户？</span><a href="<?php /** echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }**/?>">免费注册</a>--></p>

               </form>
               <div class="clear"></div>
               <?php $oauth=Kohana::$config->load('oauth');if($oauth['app']['status']==1){?>
               <p class="login_lk_friendlink">
               <span>使用合作网站账号登录：</span>
               <?php if ($oauth['sina']['status']==1){?><a href="javascript:oauthlogin('2');" title="新浪微博"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_sina.jpg')?>" /></a><?php }?>
               <?php /*?><?php if ($oauth['qq']['status']==1){?><a href="javascript:oauthlogin('1');" title="腾讯QQ"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_qq.jpg')?>" /></a><?php }?><?php */?>
               <?php /*if ($oauth['alipay']['status']==1){?><a href="javascript:oauthlogin('3');" title="支付宝" class="zfb"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_zfb.jpg')?>" /></a><?php }*/?>
               </p>
               <?php }?>
            </div>
          </div>
          <div class="clear"></div>
       </div>
       <!--<div class="ryl_login_bg03"></div>-->
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#changeCodeImg').click(function() {
            var url = window.$config.siteurl+'/captcha';
                url = url+'?'+RndNum(8);
                $("#vfCodeImg1").attr('src',url);
     });
     $(".login_lk_login_btn").hover(function(){
        $(this).attr("src",$config.staticurl + "images/platform/login_new/btn_logina_hover.jpg");
    },function(){
        $(this).attr("src",$config.staticurl + "images/platform/login_new/btn_logina.jpg");
    })
});
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
</script>

<?php if( Session::instance ()->get( 'authautologin' )!='' ){?>
<script language="javascript">
$(".login_lk_login_btn").val("正在登录中...");
$("body").click(function(){
    return false;
})


var type='<?php if( isset( $type ) ){echo $type;}else{ echo Kohana::$config->load("site.website"); }?>';
$(document).ready(function(e) {
    var url='<?php echo Kohana::$config->load("site.web875")?>ajaxcheck/setlogintoken?token=<?php echo  Session::instance ()->get( 'authautologin' )?>&remember=<?php if( isset( $remember ) ){echo $remember;}else{ echo ''; }?>';

    var sjy_url= 'http://www.shengyijie.net/ajaxdo/user?is_login=1&user_id=<?php echo Session::instance ()->get('user_id');?>&login_name=<?php echo Session::instance ()->get('username')?>&user_type=<?php echo Session::instance ()->get('user_type')?>';
    SYJ_loadIframe(sjy_url);
    loadIframe(url,test);

});

function SYJ_loadIframe(src){
    var iframe = document.createElement("iframe");
    iframe.src = src;
    iframe.style.display='none'
    document.body.appendChild(iframe);
}
function loadIframe(src, callback){
    var iframe = document.createElement("iframe");
    iframe.src = src;
    iframe.style.display='none'

    if(iframe.attachEvent){ // IE
        iframe.attachEvent('onload', callback);
    }else{ // nonIE
        iframe.onload = callback;
    }
    document.body.appendChild(iframe);
}
function test(){

    if(type==1){
        window.location='http://<?php echo URL::website('member/userlead')?>';
    }else{
        window.location= type;
    }
}
</script>
<?php }?>