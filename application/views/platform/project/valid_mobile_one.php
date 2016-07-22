<?php echo URL::webcss("companyTel_zg.css")?>
<?php echo URL::webjs("comtel_zg1.js") ?>
<?php echo URL::webcss("platform/renling.css")?>
<style>
.telContent { padding:0;}
.telYz dd input {font-weight: normal;}

</style>
    
<!--公共背景框-->
<div class="main" style="background-color:#e3e3e3; height:auto;">
   <div class="ryl_main_bg">
       <div class="ryl_main_bg01"></div>
       <div class="ryl_main_bg02">
          <!--认领-->
          <div class="renling_main">
             <h3>企业认领项目</h3>
             <!--验证手机号码-->
             <div class="renling_title"><img src="<?php echo URL::webstatic('images/platform/renling/renling_title01.jpg');?>" /></div>
             <div class="renling_tel_succ">
                 <form id="renling_mobile_form" method="post" enctype="application/x-www-form-urlencoded" action="javascript:;">
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
                                    <input type="text" id="tel" name="receiver" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" maxlength="11" value="<?=$mobile?>" style="font-size:16px; font-family:Arial;" />
                                    <?php //}?>
                                    <ins id="insWord"></ins>
                                    <div class="clear"></div>
                                    <div class="timeDiv" id="timeDiv"><em>您如果没有收到验证码</em>
                                    <span class="spanYz" id="timeControl" style="font-size:12px; margin-bottom:5px;">获取验证码</span></div>
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
                                    <a id="project_name" style="display:none"><?php echo $project_id;?></a>
                                </dd>
                            </dl>
                        </div>
                    </div>
                 </form>
             </div>
          </div>
          
          <div class="clear"></div>
       </div>
       <div class="ryl_main_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>
<div id="opacity_box"></div>
<!--认领提示框-->
<div style="z-index: 999; display: none;" id="send_box" style="width: 470px;height: 190px;">
    <a class="close" href="#">关闭</a>
    <div class="btn" id="msgcontent"><p style="font: 14px/42px '微软雅黑';padding:75px 0 0 110px;"><img style=" float: left;
    padding-right: 25px;" src="<?php echo URL::webstatic("images/platform/renling/reload.gif");?>" />您的手机号码验证成功，正待您进入下一步...</p></div>
</div>