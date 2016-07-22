
 <script type="text/javascript">
  //广告大图
  function viewImage2(_str){
	    $("#imghead1").css('display', 'block');
	      $("#imghead1").attr('src', _str);
	      $("#pro_logo1").attr('value',_str);
	      $("#uploadImg1").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='100' height='26' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
	  }
	//广告小图
  function viewImage3(_str){
	    $("#imghead2").css('display', 'block');
	      $("#imghead2").attr('src', _str);
	      $("#pro_logo2").attr('value',_str);
	      $("#uploadImg2").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='100' height='26' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
	  }
$(function(){
	$("#tijiao").click(function(){
    var dt=$.ajaxsubmit("/company/member/ajaxcheck/check_project_advert",{project_advert:$.trim($("#project_advert").val())})
		if(dt.status==2){
        $("#project_advert").parent().find(".info2").text("当前项目推广语重复，请重新输入").show();
        $("#project_advert").focus();
        return false;
    }
    else{
        $("#project_advert").parent().find(".info2").hide();
     }
    if(editor.isEmpty()){
            $("#tishi").css('display','block')
            return false;
          }else{
              $("#tipjiej").hide();
          }
			
	})
})
</script>
 
 <div class="right">
                        <h2 class="user_right_title">
                            <span>我的项目</span>
                            <div class="clear"></div>
                        </h2>
                        <div class="my_business_new">
                            <div class="project_detial project_release">
                            <form id="publishForm" method="post" action="<?php echo URL::website('/company/member/project/doUpdateProjectSpread')?>" enctype="multipart/form-data">
                                <ul class="info">
                                    <li class="title"><b>项目推广信息</b></li>
                                    <li class="label">项目推广广告语：</li>
                                    <li class="content">
                                        <input type="hidden" value="<?=$project_id;?>" name="project_id">
                                        <input type="hidden" value="<?=$type;?>" name="type">
                                        <input type="text" maxlength="15" value="<?=arr::get($result, 'project_advert', '')?>" id="project_advert" name="project_advert"/>
                                        <span class="info info2">请填写品牌名称</span>
                                        <font>项目宣传语，最多15个汉字字符。示例：送长辈 黄金酒</font>
                                    </li>
                                    <li class="label">项目广告大图：</li>
                                    <li class="content two_lines">
                                       <span id="imgd1" class="upload_btn" style="width:120px; float:none;">
			                             <input type='hidden' name='project_xuanchuan_da_logo' value='<?php echo $str_image_big;?>' id='pro_logo1'>
                            			 <img  style="border: 1px solid #CBCBCB;height: 110px;padding:1px;margin-bottom:10px;width: 120px;<?php if (empty($str_image_big)){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($str_image_big) ? URL::imgurl($str_image_big) : '';?>" id="imghead1" />
                            		</span>
                            		<span class="infor1_modify upload_btn" style="width:112px;" >
		                                <span class="uploadImg upload_btn" id="uploadImg1" style=" width:112px;">
		                                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
		                                <?php if(isset($str_image_big) && !empty($str_image_big)){?>
		                                	 <param name="movie" value="<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>" />
		                                <?php }else{ ?>
		                                 <param name="movie" value="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>" />
		                               <?php }?>
		                               
		                                <param name="quality" value="high" />
		                                <param name="wmode" value="transparent" />
		                                <param name="allowScriptAccess" value="always" />
		                               	<?php if(isset($str_image_big) && !empty($str_image_big)){?>
                                	<embed src="<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                               			 <?php }else{?>	
                              		<embed src="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                               			<?php  }?>
		                                </object>
		                                </span>
                            		</span>
                                        <font>用于项目官网宣传展示产品特点，2M以内，尺寸705px*142px，了解项目广告大图用途，请查阅<a class="zhaos_big_img" href="<?php echo URL::webstatic('/images/my_business/replease_project_eg_1.png') ?>" target="blank">示例</a></font>
                                        
                                    </li>
                                    <li class="label">项目广告小图：</li>
                                    <li class="content two_lines">
                                      <span id="imgd_xiao" class="upload_btn" style="width:120px; float:none;">
			                             <input type='hidden' name='project_xuanchuan_xiao_logo' value='<?php echo $str_image_small;?>' id='pro_logo2'>
			                             <img  style="border: 1px solid #CBCBCB;height: 110px;padding:1px;margin-bottom:10px;width: 120px;<?php if (empty($str_image_small)){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($str_image_small) ? URL::imgurl($str_image_small) : '';?>" id="imghead2" />
                            			</span>
                            			<span class="infor1_modify upload_btn" style="width:112px;" >
			                                <span class="uploadImg upload_btn" id="uploadImg2" style=" width:112px;">
			                                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
			                                <?php if(isset($str_image_small) && !empty($str_image_small)){?>
			                                	<param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>" />
			                                <?php }else{?>
			                                	<param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_project.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>" />
			                                <?php }?>
			                                
			                                <param name="quality" value="high" />
			                                <param name="wmode" value="transparent" />
			                                <param name="allowScriptAccess" value="always" />
			                               <?php if(isset($str_image_small) && !empty($str_image_small)){ ?>
                                					<embed src="<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                               			 <?php }else{ ?>	
                              						<embed src="<?php echo URL::webstatic('/flash/uploadhead_project.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                               			<?php  }?>
			                                </object>
			                                </span>
                            		 </span>
                                        <font>吸引投资者去查看你所发布的项目，1M以内，尺寸120px*110px，了解项目广告小图用途，请查阅<a class="zhaos_big_img" href="<?php echo URL::webstatic('/images/my_business/replease_project_eg_3.png') ?>" target="blank">示例</a></font>
                                    </li>
                                    <li class="label"><font>*</font>项目详情介绍：</li>
                                    <li class="content height_auto">
                                     <?php
                            				echo  Editor::factory(isset($result['project_summary']) ? $result['project_summary'] : '',"nobar",array("field_name"=> 'project_summary',"width"=>"580","height"=>"200"));
                           				?>
                                        <font>介绍此项目的起源、特点、发展历程、优势、能给投资者带来怎样的收益等</font>
                                        <span id="tishi"  style="display:none;color:red;">请填写项目详情介绍</span>
                                    </li>
                                    <li class="label">项目标签：</li>
                                    <li class="content">
                                        <input type="text" id="project_release_tag" name="project_tag" value="<?=$project_tag?>" class="text2"/>
                                    </li>
                                    <li class="label">常用标签：</li>
                                    <li class="content project_release_tag_normal">
                                       <?php  foreach ($ProjectTag as $key=>$val){?>
                                        	<a href="javascript:void(0)"><?php echo $val['tag_name']?></a>
                                       <?php  }?>
                                    </li>
                                    <li class="clear"></li>
                                </ul>
                                <ul class="info">
                                  
                                    <li class="content"><input class="red sent" id="tijiao" type="submit" value="提交"/></li>
                                    <li class="clear"></li>
                                </ul>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--透明背景开始-->
                    <div id="getcards_opacity" ></div>
                    <!--透明背景结束-->
                    <?php echo URL::webjs("zhaos_box.js")?>
                    <script type="text/javascript">init_big_img();initFormCheck();</script>
