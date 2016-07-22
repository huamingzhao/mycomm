<?php echo url::webcss("/platform/common.css")?>
<?php echo url::webcss("/platform/base_infor.css")?>
<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>
<?php echo URL::webjs("invitepro_zg.js")?>
<?php echo URL::webjs("personinfo_vali.js")?>
<?php echo URL::webjs("userlead.js")?>
<?php echo URL::webjs("province.source.js")?>
<?php echo URL::webjs("zhaosArea.js")?>
<?php echo URL::webcss("select_area.css")?>
<?php echo URL::webjs("zhaos_box.js")?>
<!-- 报错提示class base_infor_tx_current 放在 base_infor_detail_r 这个后面-->
<div id="opacity"></div>
<div class="base_infor" style="height:auto;">
   <div class="base_infor_contain">
     <div class="base_infor_left">
       <!--<div class="base_infor_left01"></div>-->
       <div class="base_infor_left02">
          <p class="base_infor_title"><span class="base_infor_title_span">基本信息，只需10秒，轻松搞定</span></p>
          <ul>
        <form id="form_id" method="post" action="/member/userlead">
          <li>
             <label><font class="require1">*</font>头像：</label>
             <div class="base_infor_detail_r" id="base_infor_detail_r">
                <!-- <input name="imgfile" class="input_fille" type="file" id="imgfile" size="40" onchange="viewmypic(showimg,this.form.imgfile);" />-->

                 <div class="base_infor_tx">
                               <span class="uploadImg" id="uploadImg" style="float:left; height:32px;">
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="85" height="32" id="flashrek2">
                     <param name="movie" value="<?php echo URL::webstatic('/flash/upload_syj.swf?fun=viewImage1&type=edit')?>" />
                     <param name="quality" value="high" />
                     <param name="wmode" value="transparent" />
                     <param name="allowScriptAccess" value="always" />
                     <embed src="<?php echo URL::webstatic('/flash/upload_syj.swf?fun=viewImage1&type=edit')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="85" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                     </object>
                    </span>
                    
                 <!--<input class="base_infor_detail_r_bot" name="" type="button" />-->
                   <p class="base_infor_txa">建议上传头像图片尺寸大小为150px*120px</p>
                 </div>

                 <div class="base_infor_txa_img" >
                 
                  <INPUT type="hidden" id="log_id" name="per_photo" value="" >
              <p class="base_infor_photo">
                 <span class="base_infor_photo_big"><em><img id="imghead" src="<?php echo url::webstatic('images/platform/base_infor_yd/pic_big.jpg')?>" /></em><!--<b>尺寸头像，120X110像素</b>--></span>
                 <!--<span class="base_infor_photo_small"><b>仅支持jpg,gif,png图片文件</b></span>-->

                 <div class="clear"></div>
              </p>
                 
                 </div>
             </div>
             <div class="clear"></div>
          </li>
           <li>
             <label><font class="require1">*</font>姓名：</label>
             <div class="base_infor_detail_r" id="base_infor_detail_r">
                 <input name="per_realname"  type="text" class="base_infor_text01" id="per_realname_text"/>
                 <!--<div class="base_infor_tx">
                   <div class="base_infor_tx_l"></div>
                   <div class="base_infor_tx_z" id="name_error">请输入您的真实姓名</div>
                   <div class="base_infor_tx_r"></div>
                 </div>-->
             </div>
             <div class="clear"></div>
          </li>
           <li>
             <label>性别：</label>
             <div class="base_infor_detail_r">
                 <span><input name="per_gender" type="radio" value="1" checked /> 男</span>
                 <span><input name="per_gender" type="radio" value="2" /> 女</span>
             </div>
             <div class="clear"></div>
          </li>

          
          
          <li>
             <label><font class="require1">*</font>手机号码：</label>
             <div class="base_infor_detail_r" id="phone_error_div_id">
             <?php if( $mobile=='' || $user->valid_mobile=='0' ){?>
                 <input name="per_phone" type="text" class="base_infor_text01" id="phone_yz_id" />
                 <div class="base_infor_tx">
                   <div class="base_infor_tx_l"></div>
                   <div class="base_infor_tx_z" id="phone_error">验证手机号，表明您对投资是认真的</div>
                   <div class="base_infor_tx_r"></div>
                 </div>

                 <a href="javascript:void(0)" class="base_infor_yzm" style="display: none;">发送验证码</a>
            <?php }else{?>
                 <!--手机已验证-->
                 <span style="padding-right:5px"><?php echo $mobile?></span>
                 <?php if( $user->valid_mobile=='0' ){?>
                    <img src="<?php echo URL::webstatic('/images/infor1/tel_nocheck.jpg')?>" style="margin-top: 5px;">
                 <?php }else{//已验证?>
                     <img src="<?php echo URL::webstatic('/images/infor1/tel_check.jpg')?>" style="margin-top: 5px;">
                 <?php }?>
            <?php }?>
              </div>
             <div class="clear"></div>
          </li>

          <li class="clearfix">
             <label><font class="require1">*</font>邮箱：</label>
             <div class="base_infor_detail_r" id="yzemail">
             <?php if( $email=='' ){?>
                  <input name="per_mail" type="text" class="base_infor_text01" id="per_mail" />
                 <div class="base_infor_tx" style="display:none">
                   <div class="base_infor_tx_l"></div>
                   <div class="base_infor_tx_z" id="mail_error">邮箱格式不正确</div>
                   <div class="base_infor_tx_r"></div>

                 </div>
             <?php }else{?>
                 <!--邮箱已验证-->
                 <span style="padding-right:5px"><?php echo $email?></span>
                 <?php if( $user->valid_email=='0' ){?>
                    <img src="<?php echo URL::webstatic('/images/infor1/tel_nocheck.jpg')?>" style="margin-top: 5px; float:left">
                 <?php }else{?>
                     <img src="<?php echo URL::webstatic('/images/infor1/tel_check.jpg')?>" style="margin-top: 5px;float:left">
                 <?php }?>
             <?php }?>
             </div>
          </li>



           <li>
             <label><font class="require1">*</font>我的标签：</label>
             <div class="base_infor_detail_r" id="base_infor_detail_r">
                 <div class="base_infor_detail_r_diva"><p class="base_infor_detail_r_diva_pa"><input id="cashbagSaveYN" name="cashbagSaveYN" type="checkbox" value="" /></p><p class="base_infor_detail_r_diva_pb">代理商</p></div>
                 <div class="base_infor_detail_r_diva"><p class="base_infor_detail_r_diva_pa"><input id="cashbagSaveYN" name="cashbagSaveYN" type="checkbox" value="" /></p><p class="base_infor_detail_r_diva_pb">加盟商</p></div>
                 <div class="base_infor_detail_r_diva"><p class="base_infor_detail_r_diva_pa"><input id="cashbagSaveYN" name="cashbagSaveYN" type="checkbox" value="" /></p><p class="base_infor_detail_r_diva_pb">批发商</p></div>
                 <div class="base_infor_detail_r_diva"><p class="base_infor_detail_r_diva_pa"><input id="cashbagSaveYN" name="cashbagSaveYN" type="checkbox" value="" /></p><p class="base_infor_detail_r_diva_pb">其他</p></div>
                 <input name="" type="text" class="base_infor_text05"  />
                 <!--<div class="base_infor_tx">
                   <div class="base_infor_tx_l"></div>
                   <div class="base_infor_tx_z" id="name_error">请输入您的真实姓名</div>
                   <div class="base_infor_tx_r"></div>
                 </div>-->
             </div>
             <div class="clear"></div>
          </li>

          <li>
             <label></label>
             <div class="base_infor_detail_ra">
                <input type="submit" class="base_infor_next" style="cursor: pointer;"  onClick="ifChecked()" value="提交"/>
             </div>
             <div class="clear"></div>
          </li>
        </form>
         </ul>
          <div class="clear"></div>
       </div>
       <!--<div class="base_infor_left03"></div>-->
       <div class="clear"></div>
     </div>
     <div class="base_infor_right"><!--<span class="base_infor_right_per"><img src="<?php /** echo url::webstatic('images/platform/base_infor_yd/per_text.png')**/?>" /></span>-->
     <div class="login_new_attention_num">
            <p class="login_new_attention_num_p"><span>目前已有</span><b><?php if( isset($reg_fu_platform_num) ){echo $reg_fu_platform_num;}else{echo 0;}?></b><span>个项目，</span></p>
            <p class="login_new_attention_num_p"><b><?php if( isset($reg_fu_user_num) ){echo $reg_fu_user_num;}else{echo 0;}?></b><span>个用户加入一句话</span></p>
            </div>
      <div class="base_infor_right_div">

       <p class="base_infor_right_div_pa">一句话生意网文化：</p>
       <p class="base_infor_right_div_pb"><span>使命：</span>让找生意更高效，让做生意更简单。</p>
       <p class="base_infor_right_div_pb"><span>愿景：</span>人人信赖的生意信息服务平台。</p>
       <p class="base_infor_right_div_pb"><span>品牌定位：</span>一句话生意网，让生意更简单，更高效</p>

      </div>




     </div>
     <div class="clear"></div>
   </div>

</div>




<div style="display:none" class="hiddenDiv"></div>
<div id="zhaos_opacity"></div>
<div class="comPopup" id="comPopup">
<dl>
<script>
//上传图片
function viewImage1(_str){
    $("#imghead").attr('src', _str);
   $("#log_id").attr('value',_str);

}
</script>
<!--招商地区结束-->
<?php echo $script?>