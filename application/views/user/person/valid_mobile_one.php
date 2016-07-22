<?php echo URL::webcss("companyTel_zg.css")?>
<?php echo URL::webjs("comtel_zg1.js") ?>
<!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>手机验证</span><div class="clear"></div></div>
        <div id="right_con">
         <form method="post" id="mobile_form" enctype="application/x-www-form-urlencoded" target="_self">
            <div class="telContent">
                <div class="telYz">
                    <h1>通过验证可增强诚信度，方便与企业沟通<INPUT type="hidden" name="type" value="<?php echo $type?>" ></h1>
                    <dl>
                        <dt><label for="tel">您的手机号码：</lable></dt>
                        <dd>
                            <input type="text" id="tel"  value="<?=$mobile?>"  name="receiver" style="font-size:16px; font-family:Arial; color:#f00;" /><ins id="insWord"><?php if(isset($error)){echo $error;} ?></ins>
                             <div class="clear"></div>
                            <div class="timeDiv" id="timeDiv"><em>您如果没有收到验证码</em><span class="spanYz" id="timeControl">获取验证码</span></div>
                            <div class="clear"></div>
                        </dd>
                    </dl>
                    <div class="clear"></div>
                    <dl>
                        <dt><label for="telyzm">输入验证码：</lable></dt>
                        <dd>
                            <input type="text" name="check_code" id="telyzm"/><p class="telPError" style="padding-bottom:0;"><?php if(isset($error)){echo $error;} ?></p>
                            <div class="clear"></div>
                            <input type="submit" class="telSubmit" value="" id="submitBtn"/>
                        </dd>
                    </dl>
                </div>
            </div>
        </form>
        </div>
    </div>