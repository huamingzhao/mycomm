<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>
<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("zhaosArea.js")?>
<?php echo URL::webjs("common.source.js")?>
<?php echo URL::webjs("province.source.js")?>
<?php echo URL::webjs("my_business_infor.js")?>
<?php echo URL::webcss("renzheng.css"); ?>
<?php echo URL::webcss("select_area.css")?>
<?php echo URL::webjs("zhaos_box.js")?>
<?php echo URL::webcss("cz.css")?>
<?php echo URL::webjs("vali.js")?>
<!--右侧开始-->
<div class="opacityBg" id="opacityBg"></div>
<div class="right">
	<form id="forminfo" action="<?php echo URL::website('/company/member/exhb/editExhbMore');?>" enctype="multipart/form-data" method="post">
		<input type="hidden" name="project_id" value="<?php echo $project_id;?>" />
    	<input type="hidden" name="exhb_id" value="<?php echo $exhb_id;?>" />
    	<input type="hidden" name="status" value="<?php echo $status;?>" />
		<h2 class="user_right_title">
	        <span>参展项目管理</span>
	        <div class="clear"></div>
    	</h2>
    	<div class="my_business_new">
    		<div class="project_detial project_release">
    			<ul class="info">
    				<li class="title"> <b>项目详情信息</b></li>
    				<li class="label"><font>*</font>产品展示：</li>
    				<li class="content cpzs">
    					<span id="imgPro" style="width:120px;">
    						<?php 
    							$str1 = "";
    							$str2 = "";
    							$str3 = "";
    							if(isset($forms['project_zhanshi']) && $forms['project_zhanshi']){
    								foreach($forms['project_zhanshi'] as $v){
    									$str1.=$v['project_zhanshi_pic'];
    									$str1 = $str1.',';
    									$str2.=$v['project_zhanshi_pic_name'];
    									$str2 = $str2.',';
    									$str3.=$v['project_zhanshi_shuoming'];
    									$str3 = $str3.',';
    								}
    							}
    							$str1 = substr($str1,0, strlen($str1)-1);
    							$str2 = substr($str2,0, strlen($str2)-1);
    							$str3 = substr($str3,0, strlen($str3)-1);
    						?>
                            <input type='hidden' name='project_img_url' value='<?php echo $str1;?>' id='project_img_url' class="validate require">
                            <input type='hidden' name='project_imgname' value='<?php echo $str2;?>' id='project_imgname'>
                            <input type='hidden' name='project_describe' value='<?php echo $str3;?>' id='project_describe'>                      		
                  		</span>
    					<div class="flashPopup" id="flashPopup" style="z-index: 1000">
							<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" width="637" height="500" id="1234556">
							<param name="movie" value="<?php echo URL::webstatic('/flash/uploadImg_wlzh.swf?fun=viewPros');?>">
							<param name="allowScriptAccess" value="always">
						 	<param name="quality" value="high">
						  	<param name="wmode" value="transparent">
						   	<embed src="<?php echo URL::webstatic('/flash/uploadImg_wlzh.swf?fun=viewPros');?>" quality="high" allowscriptaccess="always" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="637" height="500">
						   	</object>
						</div>
			      		<button id="flash">请选择</button>
			      		<font class="floleft" style="line-height:35px;">用于展示项目产品特点。请上传多张，建议尺寸：</font> 
			        	<span class="info">请上传产品展示图片</span>
			        	<div class="clear"></div>
			        	
			        	<div class="appendspan">
			        		<?php if(isset($forms['project_zhanshi']) && $forms['project_zhanshi']){?>
			        		<?php foreach($forms['project_zhanshi'] as $v){?>		        	
			        		<span class="imgboxlist clearfix"><i><img width="120" height="95" src="<?=(isset($v['project_zhanshi_pic']) && $v['project_zhanshi_pic']) ? URL::imgurl($v['project_zhanshi_pic']) : '';?>"></i><a target="blank" href="<?=URL::imgurl(str_replace('/s_','/b_',$v['project_zhanshi_pic']));?>" class="project_detial_product_img zhaos_big_img">查看大图</a><a href="javascript:;" class="project_detial_product_img del delete"></a></span>
			        		<?php }?>
			        		<?php }?>
			        	</div>
                        <p id="beforep" style="clear:both;margin-top:10px; <?php if(!(isset($forms['project_zhanshi']) && count($forms['project_zhanshi']) > 4)){?>display:none;<?php }?>"><a href="javascript:;">查看更多产品展示图</a></p>
                    </li>
			        
			        <script type="text/javascript"> 
				      //产品展示图
			        function viewPros(imgurl,txt1,txt2){
			        	var arr=imgurl.split("||");
			        	var arr2=txt1.split("||");
			        	var arr3=txt2.split("||");

			        	var a = $("#project_img_url").val();
			        	a = a===""?[]:a.split(",");
			        	
			        	var b = $("#project_imgname").val();
			        	b = b===""?[]:b.split(",");
			        	var c = $("#project_describe").val();
			        	c = c===""?[]:c.split(",");
			        	$("#project_img_url").val(a.concat(arr));
			        	$("#project_imgname").val(b.concat(arr2));
			        	$("#project_describe").val(c.concat(arr3));
			        	var numb=a.concat(arr).length
			        	if(numb>4){
			        		$("#beforep").show();
			        	}
			        	else{
			        		$("#beforep").hide();
			        	}

			        	var html=""
			        	for(var i=0;i<arr.length;i++){
			        		html+='<span class="imgboxlist clearfix"><i><img width="120" height="95" src="'+arr[i]+'"></i><a class="project_detial_product_img zhaos_big_img" href="'+arr[i]+'" target="blank" data-img="">查看大图</a><a class="project_detial_product_img del delete" href="javascript:;"></a></span>'
			        	}
			        	$(".appendspan").append(html);
			        	init_big_img();
			        	$("#flashPopup").hide();
			        	$("#flash").text("重新上传");
			        	
			        }
			        $("#beforep").toggle(function() {
                        $(".appendspan").css("overflow","visible");
                        $(this).find("a").text("收回");
                    }, function() {
                        $(".appendspan").css("overflow","hidden");
                        $(this).find("a").text("查看更多产品展示图");
                    });
			        var len;
			        
					$("#flash").click(function(){
						len=$(".appendspan .imgboxlist").length;
					    $(".flashPopup").slideDown(function(){})
					    return false;
					})
					function getLeftImgNum(){
			        	return len;//剩下的数字
					}
			        $(".delete").live("click",function(){
			        	var _this=$(this);
						window.MessageBox({
					    title:"标题",
					    content:"<p>你确定要删除嘛</p>",
					    btn:"ok cancel",
					    width:400,
					    callback:function(){
					    var $index=_this.parents(".imgboxlist").index();
					    
					    var a=$("#project_img_url").val().split(",");
					    a.splice($index,1);
			        	var b=$("#project_imgname").val().split(",");
			        	b.splice($index,1);
			        	var c=$("#project_describe").val().split(",");
			
			        	$("#project_img_url").val(a);
			        	$("#project_imgname").val(b);
			        	$("#project_describe").val(c);
							_this.parent().remove();
							if(_this.index()==1){
			        		$("#flash").text("请上传");
			        		}
			        		if(_this.index()<=4){
                            $("#beforep").show();
                            }
                            else{
                                $("#beforep").hide();
                            }
					    },
					    target:"new"
					  	});
			        	
			        })
			        </script>
			        
			        <div id="opacityBg"></div>
			        <li class="label">项目视频：</li>
			        <li class="content">
                        <span id="imgd7" style="width:120px;">
                            <input type='hidden' name='project_temp_video' value='<?=isset($forms['project_temp_video']) ? $forms['project_temp_video'] : '';?>' id='project_temp_video'>                            
                        </span>
                        <?php if(isset($forms['project_temp_video']) && $forms['project_temp_video']){?>                        
                        <span class="infor1_modify floleft" style="width:170px;" >
                            <span class="uploadImg" id="uploadImg7" style="width:170px; height:75px; display:inline;">
                            <span class='uploadspan' style='display:block'>已上传</span>
                            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
                            <param name="movie" value="<?php echo URL::webstatic('/flash/uploadvideo2.swf?url='.URL::website('/Upload/Upload/uploadVideo').'&fun=viewImage7')?>" />
                            <param name="quality" value="high" />
                            <param name="wmode" value="transparent" />
                            <param name="allowScriptAccess" value="always" />
                            <embed src="<?php echo URL::webstatic('/flash/uploadvideo2.swf?url='.URL::website('/upload/uploadVideo').'&fun=viewImage7')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                            </object>
                            </span>
                        </span>
                        <?php }else{?>
                        <span class="infor1_modify floleft" style="width:170px;" >
                            <span class="uploadImg" id="uploadImg7" style="width:170px; height:75px; display:inline;">
                            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
                            <param name="movie" value="<?php echo URL::webstatic('/flash/uploadvideo.swf?url='.URL::website('/Upload/Upload/uploadVideo').'&fun=viewImage7')?>" />
                            <param name="quality" value="high" />
                            <param name="wmode" value="transparent" />
                            <param name="allowScriptAccess" value="always" />
                            <embed src="<?php echo URL::webstatic('/flash/uploadvideo.swf?url='.URL::website('/upload/uploadVideo').'&fun=viewImage7')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                            </object>
                            </span>
                        </span>
                        <?php }?>
                        <font class="floleft" style="line-height:20px;">从项目优势、产品特点、预期收益、优惠政策、公司实力等角度进行解说，建议上传
mp4等格式（其他格式我们会帮您人工处理），视频容量最大支持50M。</font> 
                        <span class="info">请上传项目视频</span>
                    </li>			        
			        <li class="label">项目优势图：</li>
			        <li class="content">
                    	<span id="imgd2" style="width:120px;">
                            <input type='hidden' name='project_advantage_img' value='<?=isset($forms['project_advantage_img']) ? URL::imgurl($forms['project_advantage_img']) : '';?>' id='project_advantage_img'>
                      		<img  style="border: 1px solid #CBCBCB;float: left;height: 110px;padding:1px;width: 120px;<?php if (empty($forms['project_advantage_img'])){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($forms['project_advantage_img']) ? URL::imgurl($forms['project_advantage_img']) : '';?>" id="imghead2"/>
                  		</span>
                  		<span class="infor1_modify floleft" style="width:112px;" >
                   			<span class="uploadImg" id="uploadImg2" style="width:112px; height:32px; display:inline;">
	                		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
	                      	<param name="movie" value="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>" />
	                      	<param name="quality" value="high" />
	               			<param name="wmode" value="transparent" />
	                      	<param name="allowScriptAccess" value="always" />
	                    	<embed src="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
	                     	</object>
	                     	</span>
	                 	</span>
                                                <font class="floleft" style="line-height:35px;">更加直观展示你的参展项目信息，吸引用户来关注</font> 
                    	<span class="info">请上传项目优势图</span>
                    </li>
                    <li class="label"><font>*</font>项目优势</li>
                    <li class="content height_auto" id="project_detial">
			            <?php echo  Editor::factory(isset($forms['project_advantage']) ? $forms['project_advantage'] : '',"exhb_nobar1",array("field_name"=>'project_advantage',"width"=>"580","height"=>"200"));?>

			            <font>介绍此项目的起源、特点、发展历程、优势等</font>
			            <span class="info">请填写项目优势</span>
			        </li>
			        <li class="label">运营操作图：</li>
			        <li class="content">
			        	<span id="imgd2" style="width:120px;">
			               <input type='hidden' name='project_running_img' value='<?=isset($forms['project_running_img']) ? URL::imgurl($forms['project_running_img']) : '';?>' id='project_running_img'>
			               <img  style="border: 1px solid #CBCBCB;float: left;height: 110px;padding:1px;width: 120px;<?php if (empty($forms['project_running_img'])){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($forms['project_running_img']) ? URL::imgurl($forms['project_running_img']) : '';?>" id="imghead3" />
			      		</span>
			           <span class="infor1_modify floleft" style="width:112px;" >
                   			<span class="uploadImg" id="uploadImg3" style="width:112px; height:32px; display:inline;">
	                		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
	                      	<param name="movie" value="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>" />
	                      	<param name="quality" value="high" />
	               			<param name="wmode" value="transparent" />
	                      	<param name="allowScriptAccess" value="always" />
	                    	<embed src="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
	                     	</object>
	                     	</span>
	                 	</span>
	                 	<font class="floleft" style="line-height:35px;">吸引用户来关注你的参展项目，建议尺寸</font> 
				        <span class="info">请上传运营操作图片</span>
			        </li>
			        <li class="label"><font>*</font>运营操作</li>
                    <li class="content height_auto" id="project_detial">
			            <?php echo  Editor::factory(isset($forms['project_running']) ? $forms['project_running'] : '',"exhb_nobar2",array("field_name"=>'project_running',"width"=>"580","height"=>"200"));?>
			            <font>加盟本项目的流程说明，以及加盟后运营操作流程介绍</font>
			            <span class="info">请填写运营操作</span>
			        </li>
			        <li class="clear"></li>
    			</ul>
    			<input type="hidden" name="project_status" value="<?php echo isset($status) ? $status : 0?>" id="project_status"/>
    			<input type="submit"class="yellow zhanhuisave" value="保存">
    		</div>
    	</div>    	
	</form>
</div>    		
<script type="text/javascript">
init_big_img();
$(".zhanhuisave").click(function(){
	if($("#project_status").val() == 8){
		$("#project_status").val(10);
	}
})


function closeBox(){
    $(".flashPopup").hide();
}

$.vali({	
	"formid":"#forminfo",
	"submitid":".zhanhuisave",	
    callback:function(){
        if(editor_1.text().length<=0){
            $("textarea[name='project_advantage']").nextAll(".info").show();
            return false;
        }
        else if(editor_1.text().length>=200){
            $("textarea[name='project_advantage']").nextAll(".info").show().text("字数大于200");
            return false;
        }
        else{
             $("textarea[name='project_advantage']").nextAll(".info").hide();
        }
        if(editor_2.text().length<=0){
            $("textarea[name='project_running']").nextAll(".info").show();
            return false;
        }
        else if(editor_2.text().length>=200){
            $("textarea[name='project_running']").nextAll(".info").show().text("字数大于200");
            return false;
        }
        else{
             $("textarea[name='project_running']").nextAll(".info").hide();
        }
    }
});

//项目优势图
function viewImage2(_str){
	$("#imghead2").css('display', 'block');
    $("#imghead2").attr('src', _str);
    $("#project_advantage_img").attr('value',_str);
    $("#uploadImg2").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='112' height='32' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
}

//运营操作图
function viewImage3(_str){
	$("#imghead3").css('display', 'block');
    $("#imghead3").attr('src', _str);
    $("#project_running_img").attr('value',_str);
    $("#uploadImg3").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='112' height='32' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
}

//视频
function viewImage7(_str){
    $("#project_temp_video").attr('value',_str); 
    var project_status = $("#project_status").val();
    if(project_status == 1){
    	$("#project_status").val(8);
    }   
    $("#uploadImg7").html("<span>上传成功</span><object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/uploadvideo2.swf?url='.URL::website('/Upload/Upload/uploadVideo').'&fun=viewImage7')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/uploadvideo2.swf?url='.URL::website('/upload/uploadVideo').'&fun=viewImage7')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='100' height='26' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
    
}
</script>