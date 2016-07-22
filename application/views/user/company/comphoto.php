<?php echo url::webcss("/platform/common.css")?>
<?php echo url::webcss("/platform/base_infor.css")?>
<?php echo URL::webjs("invitepro_zg.js")?>
<?php echo URL::webjs("personinfo_vali.js")?>
<?php echo URL::webjs("comlead.js")?>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>


<div class="base_infor" style="height:auto;">
<form action="/member/comphoto" id="form_id" method="post">
   <div class="base_infor_contain">
     <div class="base_infor_left">
       <div class="base_infor_left01"></div>
       <div class="base_infor_left02">
          <p class="base_infor_title"><img src="<?php echo url::webstatic('images/platform/base_infor_yd/step03_logo.png')?>" /></p>
          <div class="base_infor_step03">
              <p class="base_infor_upload">
               <span class="uploadImg" id="uploadImg" style="float:left; height:32px;">
                     <script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0','name','fileId4','width','146','height','39','id','flashrek2','src','<?php echo URL::webstatic('/flash/upload_comp?fun=viewImage1')?>','allowscriptaccess','always','wmode','transparent','quality','high','pluginspage','http://www.macromedia.com/go/getflashplayer','movie','<?php echo URL::webstatic('/flash/upload_comp?fun=viewImage1')?>' ); //end AC code
</script>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="146" height="39" id="flashrek2">
                     <param name="movie" value="<?php echo URL::webstatic('/flash/upload_comp.swf?fun=viewImage1')?>" />
                     <param name="quality" value="high" />
                     <param name="wmode" value="transparent" />
                     <param name="allowScriptAccess" value="always" />
                     <embed src="<?php echo URL::webstatic('/flash/upload_comp.swf?fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="146" height="39" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                     </object>
                    </span>
              <span>上传个人头像能得到更多的关注，提高找项目的效率！</span><div class="clear"></div></p>
              <INPUT type="hidden" id="log_id" name="com_logo" value="" >
              <p class="base_infor_photo">
                 <span class="base_infor_photo_big"><em><img id="imghead" src="<?php echo url::webstatic('images/platform/base_infor_yd/pic_big_logo.jpg')?>" /></em><b>尺寸头像，120X110像素</b></span>
                 <span class="base_infor_photo_small"><b>仅支持jpg,gif,png图片文件</b></span>
                 <div class="clear"></div>
              </p>
              <p class="base_infor_message">
                 <span>公司简介：</span>
                 <textarea name="com_desc" cols="" rows="" id="com_desc_id">介绍一下您的投资风格和意向项目类型吧，让您找项目更容易！</textarea>
              </p>
              <p class="base_infor_btn_big"><a href="javascript:void(0)">完成，去发布项目吧</a></p>
          </div>
          <div class="clear"></div>
       </div>
       <div class="base_infor_left03"></div>
       <div class="clear"></div>
     </div>
     <div class="base_infor_right"><span class="base_infor_right_per"><img src="<?php echo url::webstatic('images/platform/base_infor_yd/comp_text02.png')?>" /></span></div>
     <div class="clear"></div>
   </div>
   <div class="clear"></div>
</form>
</div>
<script>
//上传图片
function viewImage1(_str){
    $("#imghead").attr('src', _str);
    $("#log_id").attr('value',_str);

}
</script>
