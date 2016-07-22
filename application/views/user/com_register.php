<?php echo URL::webcss("platform/login_new.css")?>
<?php echo URL::webjs("platform/register/register_com_new.js")?>
  
<!--中部开始-->
<!--许晟玮注释 日期2013年4月27日-->

<!--add by 许晟玮  date 2013年4月27日-->
<div class="main" style="height:auto; background-color:#e3e3e3; padding:15px 0;">
   <div class="ryl_login_bg">
       <div class="ryl_login_bg01"></div>
       <div class="ryl_login_bg02">

           <div class="ryl_new_regist">
<!--title标题-->
               <div class="regtitle clearfix">
                 <h1 class="floleft">新用户注册</h1>
                 <p class="floleft">马上注册并填写基本信息，生成名片，您会得到更多的关注哟！</p>
               </div>
<!--表单-->
               <div class="ryl_new_regist_cont01">
               <form id="registerForm" method="post" action="<?php echo urlbuilder::register("qiye");?>?type=com">
                   <p class="Identityinput"><em>*</em>您的身份：<input type="radio" name="per" value="0"><span>个人用户</span><input name="per" value="1" type="radio" checked="true" style="margin-left: 50px;"><span>企业用户</span></p>
                   <p id="email_id"><em>*</em><input type="text" class="login_lk_text01" name="email" id="email1" value="<?if(isset($form['email'])){echo $form['email'];}?>"  placeholder="邮箱或手机号"/><span class="ryl_new_regist_tx_text" id="tishi_mail">请输入您常用的邮箱/手机号</span></p>

                  <p id="input_phoneCode">
                    <em>*</em>
                    <input type="text" class="login_lk_text01" name="phoneCode" id="phoneCode" placeholder="短信验证码"  value=""/>
                    <a href="javascript:void(0)" id="getPhoneCode" class="getPhoneCode">获取手机验证码</a>
                    <span class="ryl_new_regist_tx_text" id="tishi_phoneCode">点击获取验证码
                    </span>
                  </p>
                   <p id="name_id"><em>*</em><input type="text" class="login_lk_text01" name="user_name" id="userName1" value="<?if(isset($form['user_name'])){echo $form['user_name'];}?>" placeholder="企业名称"/><span class="ryl_new_regist_tx_text" id="tishi_name">中文或英文 最多30个字符，注册后不能更改</span></p>

                   <p><em>*</em><input type="password" class="login_lk_text01" name="password" placeholder="密码" id="passWord1" /><span class="ryl_new_regist_tx_text" id="tishi_passwd">6-20位字母、数字或符号</span></p>

                   <p><em>*</em><input type="password" class="login_lk_text01" name="confirm" id="passWordAgain1"  placeholder="确认密码" /><span class="ryl_new_regist_tx_text" id="tishi_confirm">6-20位字母、数字或符号</span></p>

                   <p id="mail_code"><em>*</em><input type="text" class="login_lk_text02" name="valid_code" placeholder="验证码" value="<?if(isset($form['valid_code'])){echo $form['valid_code'];}else{?><?}?>" id="verificationCode1"/><img src="<?=common::verification_code();?>" width="95" height="37" class="login_lk_yzm" id="vfCodeImg1"/><a href="javascript:void(0)" id="changeCodeImg" class="login_lk_yzmtext">看不清楚？换一张</a><span class="tishi <?if(isset($error['valid_code'])) {echo ' error';}?>" id="tishi_code"><?if(isset($error['valid_code'])) {echo $error['valid_code'];}?></span></p>

                   <div class="clear"></div>
                   <p class="login_lk_wenzi regist_font12" id="agree_id"><input type="checkbox" checked/><span>我已经阅读</span><a href="#" style="width:85px;" class="ryl_new_regist_tx_blue messageBox" data-tag="#registerContent" data-title="会员服务条款" data-type="click" data-width="690">注册及服务条款</a><span>，并愿意遵守相关条款规定。</span><span id="tishi_check"></span><input type='hidden' name="user_type" value="1"/></p>

                   <div class="clear"></div>
                   <p class="ryl_new_regist_btn"><input  type="image"  src="<?php echo URL::webstatic('images/platform/login_new/btn_regist.jpg')?>" class="ryl_new_regist_btn01" />

                   <span>我已经有账号了，我要</span><a href="<?php echo urlbuilder::geren("denglu");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>">登录</a></p>
                   <div class="clear"></div>
                  </form>
                  <div class="clear"></div>
               </div>
<!--合作-->
               <!--
               <div class="regist_friendlink">
                   <span>使用合作网站账号登录：</span>
                   <a href="#" title="新浪微博"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_sina.jpg')?>" /></a>
                   <a href="#" title="腾讯QQ"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_qq.jpg')?>" /></a>
                   <a href="#" title="支付宝" class="zfb"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_zfb.jpg')?>" /></a>
               </div>
                   -->
          <div class="clear"></div>
          </div>

          <div class="clear"></div>
       </div>
       <div class="ryl_login_bg03"></div>
       <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>

<!--中部结束-->


<!--透明层开始-->
<div id="register_opacity"></div>
<div id="registerContent">
<div id="register_box">
    <div class="context">
        <p>欢迎阅读一句话服务条款协议（下称“本协议”）。本协议阐述之条款和条件适用于您使用一句话网站（所涉域名为：<a href="/" target="_blank">http://www.yjh.com</a>下同），所提供企业间进行招商和交流的各种工具和服务（下称“服务”）。</p>
        <h3>一、服务条款的确认和接纳</h3>
        <p>一句话的各项运作权、解释权和所有权归一句话。一旦用户使用了该服务，即表示用户已接受了以下所述的条款和条件。如果用户不同意接受全部的条款和条件，那么用户将无权使用该服务。当用户完成注册并点击本协议下方"同意"键时，即表示用户已同意受一句话网站服务协议约束，包括但不限于本协议、一句话会员服务协议和隐私权政策等。</p>
        <h3>二、注册要求如下：</h3>
        <ul>
            <li>（1）会员注册可以个人名义或单位名义注册。</li>
            <li>（2）会员注册需提供相应真实的个人资料或单位资料。</li>
            <li>（3）会员需不断更新注册资料，符合准确、详细和及时的要求。</li>
            <li>（4）如果会员提供的资料包含有虚假或者不正确的信息，一句话保留结束用户的会员资格的权利。</li>
        </ul>
        <h3>三、注册协议的修改和服务修订</h3>
        <p>一句话保留在任何时候自行决定对服务及其相关功能、应用程序变更、升级、修改、转移的权利。一句话进一步保留在服务中开发新的模块、功能和软件或其它语种服务的权利。上述所有新的模块、功能、软件服务的提供，除非一句话另有说明，否则仍适用本服务协议。 一句话会员在此同意一句话在任何情况下都无需向一句话会员或第三方在使用服务时对其在传输或联络中的迟延、不准确、错误或疏漏及因此而致使的损害负责。</p>
        <h3>四、会员的帐号，密码及其安全性</h3>
        <p>一句话根据会员号和其密码确认使用服务的会员的身份。会员应妥善保管会员号和密码，并对其使用，包括遗失承担责任。会员承诺，其密码或帐号遭到未获授权的 使用，或者发生其他任何安全问题时，将立即通知一句话。会员在此同意并确认，一句话对上述情形产生的遗失或损害不负责任。</p>
        <h3>五、通知</h3>
        <p>所有发给用户的通知都以电子邮件或常规的信件传送。一句话会通过邮件服务发报消息给用户，告诉他们服务条款的修改、服务变更、或其它重要事情。</p>
        <h3>六、隐私</h3>
        <p>尽管第五条规定的许可使用权，一句话将仅根据本公司的隐私声明使用“您的资料”。本公司隐私声明的全部条款属于本协议的一部分，因此，您必须仔细阅读。请注意，您一旦自愿地成为一句话的会员，披露“您的资料”，该等资料即可能被其他人士获取和使用。</p>
        <h3>七、您授予本公司的许可使用权</h3>
        <p>您授予本公司独家的、全球通用的、免费的、永久的使用权利（并有权在多个层面对该权利进行再授权），使本公司有权（全部或部分的）使用、修订、复制、发 布、改写、翻译、分发、执行和展示“您的资料”，和/或与现在已知或日后开发的任何形式、媒体或技术，将“您的项目”纳入其他作品内。</p>
        <h3>八、一句话拒绝提供担保</h3>
        <p>一句话网站提供空间，一句话会员可自行张贴其商业信息，包括其文字描述、图片或照片说明。一句话不对上述信息的真实性、准确性或及时性、完整性负责。用户同意自行承担由于使用一句话服务所获知信息而产生的全部商业风险，一句话及其组织机构不承担任何责任。</p>
        <h3>九、会员服务管理</h3>
        <p>一句话会员保证并同意提供真实，准确、及时、完整的信息，包括会员自身的相关资料，维护并及时更新上述资料，以保证其真实，准确，及时和完整性。会员须承 诺不传输、发布任何非法的、骚扰性的、中伤他人的、辱骂性的、恐吓性的、伤害性的、庸俗的、淫秽等信息资料。另外，一句话会员也不能传输、发布任何教唆他人构成犯罪行为的资料；不能传输、发布助长国内不利条件和涉及国家安全的资料；不能传输、发布任何不符合国际法律、国家法律和当地法规的资料。会员未经许可而非 法进入其它电脑系统是禁止的。若会员的行为不符合以上提到的服务条款，一句话有权中止或终止会员的会员资格并拒绝会员使用任何现有的服务及以后可能提供的 功能或服务。一句话不为任何会员发布的内容负责，包括但不限于任何内容中任何错误或遗漏而衍生之任何损失或损害。</p>
        <h3>十、结束服务</h3>
        <dl>
            <dt>(1) 一句话有权在下列情形下拒绝会员的订购或终止用户的会员资格，</dt>
            <dd>(a) 会员违反了本协议或会员服务协议的任一条款；</dd>
            <dd>(b) 一句话或其授权的第三方无法确认会员提供的注册登记资料或身份信息；</dd>
            <dd>(c) 根据本协议相关约定而终止服务；</dd>
        </dl>
        <dl>
            <dt>(2) 如协议一方，除为重组或兼并的目的外，通过决议或由法院判令解散，则接收方或指定的债权人代表有权经通知后终止本协议。</dt>
        </dl>
        <dl>
            <dt>(3) 协议期内，在会员被第三方多次投诉等合理情形下，为避免会员及一句话的损失，一句话有权以电子邮件或其他书面形式通知会员后解除本合同，本合同自通知到达会员处时终止。合同解除后，会员有权凭一句话就本合同开具的发票原件及退款申请要求通路快建公司退还未履行部分的合同价款至会员名下的指定帐号。</dt>
        </dl>
        <h3>十一、知识产权</h3>
        <ul>
            <li>（1）通路快建公司是服务所有权利的拥有者。服务包含商业秘密和全世界范围内的版权和其它知识产权。服务的全部权利，包括所有权和知识产权将由一句话公司保留。其它本协议中未经提及的权利亦由一句话保留。</li>
            <li>（2）一句话（<a href="/" target="_blank">www.yjh.com</a>），一句话及相关的标识和徵记是通路快建公司在中国和其它国家的商标并受到版权法，商标法和其它知识产权法的保护。未经授权地复制，模仿，使用或发布上述标识，均被禁止。</li>
        </ul>
        <div>补充协议</div>
        <div>（由于客户选择的服务或产品有差异，故根据不同产品和提供的服务另外签订具体服务内容和费用补充协议）</div>
        <h3>十二、法律精神</h3>
        <p>用户和一句话一致同意有关本协议以及使用一句话的服务产生的争议交由仲裁解决，但是一句话有权选择采取诉讼方式，并有权选择受理该诉讼的有管辖权的法院。若有任何服务条款与法律相抵触，那这些条款将按尽可能接近的方法重新解析，而其它条款则保持对用户产生法律效力和影响。</p>
        <div>附：</div>
        <div>中华人民共和国电信条例</div>
        <div>互联网信息服务管理办法</div>
        <div>互联网电子公告服务管理规定</div>
        <div>中华人民共和国计算机信息网络国际联网管理暂行规定</div>
        <div>中华人民共和国计算机信息网络国际联网管理暂行规定实施办法</div>
        <div>中华人民共和国计算机信息系统安全保护条例</div>
        <div>计算机信息网络国际联网安全保护管理办法 </div>
    </div>
</div>
</div>
<!--透明层结束-->


<script type="text/javascript">
$(document).ready(function(){
    $('#changeCodeImg').click(function() {
            var url = '/captcha';
                url = url+'?'+RndNum(8);
                $("#vfCodeImg1").attr('src',url);
         });
    $(".ryl_new_regist_btn01").hover(function(){
        $(this).attr("src",$config.staticurl + "images/platform/login_new/btn_regist_hover.jpg");
    },function(){
        $(this).attr("src",$config.staticurl + "images/platform/login_new/btn_regist.jpg");
    })
    $(".Identityinput input[name='per']").change(function(){
      if($(this).val()==0){
        window.location.href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>";
      }
      else{
        window.location.href="<?php echo urlbuilder::register("qiye"); ?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>";
      }
    });
});
</script>