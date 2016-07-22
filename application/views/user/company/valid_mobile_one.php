<?php echo URL::webcss("companyTel_zg.css")?>
<?php echo URL::webjs("comtel_zg1.js") ?>


    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>手机号码验证</span><div class="clear"></div></div>
        <div id="right_con">
            <form id="mobile_form" method="post" enctype="application/x-www-form-urlencoded" target="_self">
            <div class="telContent">
                <div class="telYz">
                    <h1>通过验证可增强企业诚信度，吸引更多投资者</h1>
                    <dl>
                        <dt><label for="tel">您的手机号码：</lable></dt>
                        <dd>
                            <?php //if($mobile){?>
                            <!-- 逻辑修改
                            <input type="text" id="tel" class="speTxt" name="receiver" value="<?=$mobile?>"  readOnly = "readOnly" /><ins class="xiugai" id="xiugai">修改我的手机号码</ins>
                             -->
                            <?php //}else{?>
                            <input type="text" id="tel" name="receiver" value="<?=$mobile?>" style="font-size:16px; font-family:Arial; color:#f00;" />
                            <?php //}?>
                            <ins id="insWord"></ins>
                            <div class="clear"></div>
                            <div class="timeDiv" id="timeDiv"><em>您如果没有收到验证码</em><span class="spanYz" id="timeControl">获取验证码</span></div>
                            <div class="clear"></div>
                        </dd>
                    </dl>
                    <div class="clear"></div>
                    <dl>
                        <dt><label for="telyzm">输入验证码：</lable></dt>
                        <dd>
                            <input type="text" name="check_code" id="telyzm"/><ins id="yzmWord"><?php if(isset($error)){echo $error;} ?></ins>
                            <div class="clear"></div>
                            <input type="submit" class="telSubmit" value="" id="submitBtn"/>
                        </dd>
                    </dl>
                </div>
            </div>
            </form>
        </div>
    </div>
    <!--右侧结束-->