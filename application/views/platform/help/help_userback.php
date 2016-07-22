<?php echo URL::webjs("platform/feedback.js")?>
<script type="text/javascript">
            $(document).ready(function(){
                $('#changeCodeImg').click(function() {
                        var url = '/captcha';
                            url = url+'?'+RndNum(8);
                            $("#vfCodeImg1").attr('src',url);
                    });
            });
</script>

  <div class="help_cont">
              <p class="help_title">用户反馈<em>USER FEEDBACK</em></p>
              <div class="help_center_right">
              <form id="userbackForm" method="post">
                  <table border="0" cellpadding="0" cellspacing="0" style="margin-left:30px;">
                      <tr>
                        <td height="154" align="right" valign="top"><span style="color:red">意见：</span></td>
                        <td valign="top"><textarea name="textcontent" id="textcontent" cols="" rows="" class="help_text03" ></textarea></td>
                    </tr>
                    <tr>
                        <td width="86" height="40" align="right">姓名：</td>
                    <td width="573"><input type="text" name="textname" id="textname" class="help_text01" /></td>
                    </tr>
                      <tr>
                        <td align="right" height="40">邮箱：</td>
                        <td><input type="text" name="textemail" id="textemail" class="help_text01" /></td>
                      </tr>
                       <tr <?php if($isshowyanzhengma){echo 'style="display:black"';}else{echo 'style="display:none"';}?>>
                        <td align="right" height="40">验 证 码：</td>
                        <td>
                          <input type="text"  class="help_text01" name="valid_code" id="loginVfCode" style="width:83px; float:left;margin-right:10px;" />
                          <img src="<?=common::verification_code();?>" id="vfCodeImg1" style="float:left;"/>
                          <a href="javascript:void(0)"  class="change vfCodeCh" id="changeCodeImg" style="padding:6px 0 0 6px;">看不清？换一张</a>
                        </td>
                       </tr>
                      <tr>
                        <td height="80" align="right">&nbsp;</td>
                        <td>
                        <a href="#" class="help_submit"></a>
                        <a href="#" class="help_cancel"></a></td>
                      </tr>
                  </table>
            </form>
             </div>
           </div>