<?php echo url::webcss("/platform/common.css")?>
<?php echo url::webcss("/platform/base_infor.css")?>
<?php echo URL::webjs("invitepro_zg.js")?>
<?php echo URL::webjs("personinfo_vali.js")?>
<?php echo URL::webjs("userlead.js")?>


<div class="base_infor" style="height:auto;">
<FORM action="/member/userphoto" method="post" id="form_id">
   <div class="base_infor_contain">
     <div class="base_infor_left">
       <div class="base_infor_left01"></div>
       <div class="base_infor_left02">
          <p class="base_infor_title"><img src="<?php echo url::webstatic('images/platform/base_infor_yd/step03.png')?>" /></p>
          <div class="base_infor_step03">
              <p class="base_infor_upload">
              <span class="uploadImg" id="uploadImg" style="float:left; height:32px;">
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="146" height="39" id="flashrek2">
                     <param name="movie" value="<?php echo URL::webstatic('/flash/upload_per.swf?fun=viewImage1')?>" />
                     <param name="quality" value="high" />
                     <param name="wmode" value="transparent" />
                     <param name="allowScriptAccess" value="always" />
                     <embed src="<?php echo URL::webstatic('/flash/upload_per.swf?fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="146" height="39" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                     </object>
                    </span>

              <span>上传个人头像能得到更多的关注，提高找项目的效率！</span>

              <div class="clear"></div>

              </p>
            <INPUT type="hidden" id="log_id" name="per_photo" value="" >
              <p class="base_infor_photo">
                 <span class="base_infor_photo_big"><em><img id="imghead" src="<?php echo url::webstatic('images/platform/base_infor_yd/pic_big.jpg')?>" /></em><b>尺寸头像，120X110像素</b></span>
                 <span class="base_infor_photo_small"><b>仅支持jpg,gif,png图片文件</b></span>

                 <div class="clear"></div>
              </p>
              <div class="cardradio">
                <h4>您的名片已生成，请设置名片公开度：</h4>
                <ul>
                  <li>
                    <input type="radio" value="1" name="per_open_stutas" checked="true">允许所有企业查看
                  </li>
                  <li>
                    <input type="radio" value="4" name="per_open_stutas">只允许VIP企业查看
                  </li>
                  <li>
                    <input type="radio" value="2" name="per_open_stutas">只允许意向投资行业的企业查看
                  </li>
                  <li>
                    <input type="radio" value="3" name="per_open_stutas">不允许任何企业查看
                  </li>
                </ul>
              </div>
              <p class="base_infor_message">
                 <span>个人留言：</span>
                 <textarea name="per_remark" id="per_remark_id" cols="" rows="">介绍一下您的投资风格和意向项目类型吧，让您找项目更容易！</textarea>
              </p>
              <p class="base_infor_btn_big"><a href="javascript:void(0)" onclick="$('#form_id').submit();">完成，去找项目吧</a></p>
          </div>
          <div class="clear"></div>
       </div>
       <div class="base_infor_left03"></div>
       <div class="clear"></div>
     </div>
     <div class="base_infor_right"><span class="base_infor_right_per"><img src="<?php echo url::webstatic('images/platform/base_infor_yd/per_text.png')?>" /></span></div>
</div>
     <div class="clear"></div>
   </div>
   <div class="clear"></div>

</FORM>
</div>
<script>
//上传图片
function viewImage1(_str){
    $("#imghead").attr('src', _str);
    $("#log_id").attr('value',_str);

}
</script>
