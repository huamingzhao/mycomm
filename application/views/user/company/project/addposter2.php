<?php echo URL::webcss("zhaoshang_zg.css")?>

<div class="right">
	<h2 class="user_right_title">
  		<span>我的项目</span>
  		<div class="clear"></div>
	</h2>
	<div class="my_business_new">
		<div class="project_detial upload_img">
			<ul class="info">
				<li class="title"><b>项目海报</b><font>用于项目宣传的大幅高清图片。</font></li>
				<li class="content img">
					<?php if($poster['poster_status'] == ""){
                                $type = 1;
                            }
                            else{
                                $type = 2;
                            }
                    ?>
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="170" height="75" id="flashrek2">
                        <param name="movie" value="<?php echo URL::webstatic('/flash/upload_hb_dd.swf?type='.$type.'&upload_url='.URL::website('/upload/uploadBigImg').'&url='.URL::website('/company/member/ImageAjaxcheck/uploadPoster')."&fun=viewPoster&projectid=".arr::get($forms, 'project_id'))?>" />
                        <param name="quality" value="high" />
                        <param name="wmode" value="transparent" />
                        <param name="allowScriptAccess" value="always" />
                        <embed src="<?php echo URL::webstatic('/flash/upload_hb_dd.swf?type='.$type.'&upload_url='.URL::website('/upload/uploadBigImg').'&url='.URL::website('/company/member/ImageAjaxcheck/uploadPoster')."&fun=viewPoster&projectid=".arr::get($forms, 'project_id'))?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="170" height="75" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                    </object>
					<span class="uploadimg_info">
            <font style=" line-height: 15px;">为了更好的展示您的项目宣传海报，建议上传psd的格式图片</font>
            同时支持JPEG、JPG、GIF、PNG、PSD等格式，重新上传将覆盖原有的。
          </span>
          <p class="uploadimg_hint">
            <font>友情提示：</font><br/>
            <strong>上传的项目宣传海报出现以下问题我们将不予通过审核：</strong><br/>
1.尺寸小于900px（宽）*600px（高）：<br/>
2.正常状态下文字或者图片模糊：<br/>
3.含有违反国家法律，色情暴力的画面或者文字；<br/>
4.文字或者图片出现公司网址、联系电话、qq、邮箱等联系
          </p>
				</li>
				<?php if($poster['poster_status'] == ""){?>
	 			<li class="poster poster_no2">
                	<p>
                     	<font>如果你还没有属于自己的项目海报，我们可以帮你制作，</font>
                     	联系电话：<em>400 1015 908</em>
                   	</p>
                </li>
                <?php }elseif(isset($poster['poster_status']) && ($poster['poster_status'] == 1 || arr::get($poster, "poster_status") == 6 || arr::get($poster, "poster_temp_status") == 6)){?>
                <li class="poster poster_sh">
                	<p>
                    	<font>您的项目宣传海报已经上传，正在审核中</font>
                        	我们将在3个工作日内完成审核，审核通过后，投资者就可在您的项目官网查看到。
                    </p>                    
                </li>
                <?php }elseif(isset($poster['poster_status']) && ($poster['poster_status'] == 2 && arr::get($poster, "poster_temp_status") == 2)){?>
                <li class="poster poster_ok">
                	<p>
                    	<font>恭喜您，您上传的项目宣传海报审核通过。</font>
                    </p>                    
                </li>
                <?php }elseif(isset($poster['poster_status']) && ($poster['poster_status'] == 3 || arr::get($poster, "poster_temp_status") == 3)){?>
                <li class="poster poster_fail">
                	<p>
                    	<font>抱歉，您上传的项目宣传海报未能通过审核。</font>
                                            失败原因：<?php if(isset($poster['poster_unpass_reason'])){ echo $poster['poster_unpass_reason'];}?>
                    </p>                    
                </li>
                <?php }?>
                <?php if(isset($show) && $show == 1){?>
              	<li class="img content"><a class="button button_red" href="<?php echo URL::website("/company/member/project/updatePoster?project_id=".arr::get($forms, 'project_id'))."&type=".$type;?>">完  成</a></li>
              	<?php }?>
			</ul>
		</div>
	</div>
</div>
<script type="text/javascript">
   function viewPoster(_str){
    if(_str){
		location.href="/company/member/project/addposter?project_id=<?=arr::get($forms, 'project_id')."&type=".$type."&show=1"?>";
     }
  }
</script>