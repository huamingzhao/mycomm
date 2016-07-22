<?php echo URL::webcss("common.css")?>
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("quickrelease.css")?>
<?php echo URL::webjs("vali_tuiguang.js")?>
<?php echo URL::webcss("select_area.css")?>
<div class="quickrelease-content">
	<div class="releaseform">
		<div class="basicinformation">
			<h3>推广信息<i></i></h3>
			<form id="forminfo" method="post" action="<?php echo URL::website('/quick/project/editQuickTuiGuang');?>" enctype="multipart/form-data">
				<input id="pid" type="hidden" name="project_id" value="<?php echo $forms['project_id'];?>" />
				<ul>
					<li class="content11">
						<span class="spantitle" ><var>*</var>标题：</span>
						<input type="text" maxlength="25" id="spantitle2" class="pingpaitext" name="project_title" value="<?php echo $forms['project_title'];?>">
						<font>你所发布的招商加盟信息名称，最多支持25个汉字字符长度</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content12">
						<span class="spantitle" >一句话介绍：</span>
						<input type="text" id="spantitle3" maxlength="35"  class="pingpaitext" name="project_introduction" value="<?php echo $forms['project_introduction'];?>">
						<font>招商加盟信息宣传语，最多35个汉字字符。示例：送长辈 黄金酒</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content13 clearfix">
						<span class="spantitle fl"><var>*</var>信息详情：</span>
						<div class="fl" style="width:580px;">
							<?php echo  Editor::factory($forms['project_summary'],"quick_editor",array("field_name"=>'project_summary',"width"=>"580","height"=>"200"));?>
						</div>
						<div class="clear"></div>
						<p style="padding-left: 128px;"><font style="margin-left:0">介绍此招商加盟信息的特点、发展历程、优势、加盟流程、能给投资者带来怎样的收益等</font></p>
                        <input type="hidden" id="spantitle4">
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content14">
						<span class="spantitle">展示图：</span>
                        <span id="imgPro" style="width:120px;">                        	
							<input id="project_img_url" class="validate require" type="hidden" value="<?php 
								$project_img_url = '';
								if(isset($forms['project_zhanshi'][0]['project_zhanshi_pic']) && $forms['project_zhanshi'][0]['project_zhanshi_pic']){									
									foreach($forms['project_zhanshi'] as $val){
										$project_img_url .= $val['project_zhanshi_pic'].',';
									}
									$project_img_url = substr($project_img_url, 0,strlen($project_img_url)-1);
								}
								echo $project_img_url;
							?>" name="project_img_url">
							<input id="project_logo" type="hidden" value="<?php echo $forms['project_logo'];?>" name="project_imgname">

						</span>
						<a href="javascript:;" class="yellow quickupload">请上传</a>
						<font>招商加盟信息图片、产品图片、店铺图片等，最多支持8张图片，建议每张图片建议尺寸：</font>
						<div class="imgbox clearfix">
							<?php if(isset($forms['project_zhanshi'][0]['project_zhanshi_pic']) && $forms['project_zhanshi'][0]['project_zhanshi_pic']){?>
    						<?php foreach($forms['project_zhanshi'] as $val){?>	
							<span class="clearfix <?php if(!(isset($val['project_zhanshi_pic']) && $forms['project_logo'] == $val['project_zhanshi_pic'])){?>on<?php }?>">
								<img src="<?php echo URL::webstatic('/images/quickrelease/shelogo.png');?>" class="xuanzeimg">
								<img src="<?php echo URL::webstatic('/images/quickrelease/logoimg.png');?>" class="logoicon">
								<i class="picbox">
									<img src="<?=(isset($val['project_zhanshi_pic']) && $val['project_zhanshi_pic']) ? URL::imgurl($val['project_zhanshi_pic']) : '';?>">
								</i>								
								<a class="bigimg zhaos_big_img" href="<?=URL::imgurl(str_replace('/s_','/b_',$val['project_zhanshi_pic']));?>" target="blank" data-img="">查看大图</a>								
								<a class="imgdelete" href="javascript:;"></a>
							</span>
							<?php }?>
			        		<?php }?>			        		
						</div>
						<div class="clearfix"></div>
						<p class="clickp">展开更多图片>></p>
                        <input type="hidden"  id="logoimg"  value="">
					</li>
				</ul>				
				<input type="submit" class="yellow quickrelsave" value="保存">
			</form>
		</div>
	</div>
</div>
<div id="flashPopup">
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="741" height="504" id="flashrek2">
		<!--  <param name="movie" value="http://static.yijuhua-alpha.net/flash/uploadImg_qsfb.swf?url=http://www.yijuhua-alpha.net/company/member/ajaxcheck/uploadComLogo&amp;fun=viewImage1">-->
		<param name="movie" value="<?php echo URL::webstatic('flash/uploadImg_qsfb.swf?url='.URL::website('company/member/ajaxcheck/uploadComLogo&amp;fun=viewImage1').'');?>">
		<param name="quality" value="high"><param name="wmode" value="transparent">
		<param name="allowScriptAccess" value="always">
		<!--  
		<embed src="http://static.yijuhua-alpha.net/flash/uploadImg_qsfb.swf?url=http://www.yijuhua-alpha.net/company/member/ajaxcheck/uploadComLogo&amp;fun=viewImage1" allowscriptaccess="always" id="fileId4" wmode="transparent" width="741" height="504" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash">
		-->
		<embed src="<?php echo url::webstatic('flash/uploadImg_qsfb.swf?url='.URL::website('company/member/ajaxcheck/uploadComLogo&amp;fun=viewImage1').'');?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="741" height="504" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash">
		</object>
	</div>
	<div class="flashPopupwrap" style="z-index: 1005;"></div>
