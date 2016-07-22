<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>
<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("zhaosArea.js")?>
<?php echo URL::webjs("common.source.js")?>
<?php echo URL::webjs("province.source.js")?>
<?php echo URL::webjs("my_business_infor.js")?>
<?php echo URL::webcss("renzheng.css"); ?>
<?php echo URL::webcss("select_area.css")?>
<?php echo URL::webjs("zhaos_box_zhanhui.js")?>
<?php echo URL::webcss("cz.css")?>
<?php echo URL::webjs("vali.js")?>
<!--右侧开始-->
<div class="popupDelete" id="popupDelete">
	<div class="closeImg" id="closeImg">
        <img src="<?php echo URL::webstatic('images/infor2/closeimg.gif');?>" width="20" height="20"/>
    </div>
    <div class="clear"></div>
    <p class="errorPara">抱歉，您的项目基本信息还没有填写完整，您还不能离开此页面！</p>
    <div class="confirm">
    	<img src="<?php echo URL::webstatic('images/infor2/true.gif');?>" width="112" height="32" id="sure"/>
    </div>    
</div>
<div class="opacityBg" id="opacityBg"></div>
<div class="right">
	<form id="forminfo" action="<?php echo URL::website('/company/member/exhb/addproject');?>" enctype="multipart/form-data" method="post">		
		<input type="hidden" name="com_id" value="<?php echo $com_id;?>">
		<input type="hidden" id="exhb_id" name="exhb_id" value="<?php echo $exhb_id;?>">
		<input type="hidden" id="type" name="type" value="<?php echo $type;?>" />
		<input type="hidden" id="without" name="without" value="1" />
		
		<h2 class="user_right_title">
	        <span>参展项目管理</span>
	        <div class="clear"></div>
    	</h2>
    	<div class="my_business_new">
    		<div class="project_detial project_release">
    			<ul class="info">
    				<li class="title"> <b>项目基本信息</b></li>
    				
    				<li class="label"> <font>*</font>选择参展项目：</li>
    				<li class="content">
    					<?php if($historyPro){?>
    					<p class="inputbox"><input type="radio" name="pro_list" <?php if(isset($type) && $type == 1){?>checked="true"<?php }?> class="require validate"><label>一句话平台参展项目</label><input type="radio" name="pro_list" <?php if(isset($type) && $type == 2){?>checked="true"<?php }?>><label>历史展会参展项目</label></p>
    					<?php }?>    		
    					<input id="pro_type" type="hidden" name="pro_type" value="1" />	 
    					<?php if($yijuhuaPro){?>   							
    					<select class="mt15 validate selval" name="project_id">
    						<option value="" selected="selected">请选择项目</option>
    						<?php if($yijuhuaPro){foreach($yijuhuaPro as $v){?>
    						<option value="<?php echo $v['project_id']?>" <?php if($v['project_id'] == $project_id){?>selected="selected"<?php }?>><?php echo $v['project_brand_name']?></option>
    						<?php }}?>	   						
    					</select>
    					<?php }?>
    					<?php if($historyPro){?>
    					<select class="mt15 validate selval" style="display:none;">
    						<option value="" selected="selected">请选择项目</option>
    						<?php if($historyPro){foreach($historyPro as $v){?>
    						<option value="<?php echo $v['project_id']?>" <?php if($v['project_id'] == $project_id){?>selected="selected"<?php }?>><?php echo $v['project_brand_name']?></option>
    						<?php }}?>	   						
    					</select>
    					<?php }?>
    					<font>选择你要参展的项目</font>
    				</li>
                    <li class="label"> <font>*</font>项目类别：</li>
                    <li class="content"> 
                        <select name="catalog_id" class="require validate">
                            <option value="" selected="selected">请选择项目类别</option>
                            <?php if($catalog_info){foreach($catalog_info as $v){?>
                            <option value="<?php echo $v['catalog_id']?>"><?php echo $v['catalog_name']?></option>
                            <?php }}?>                          
                        </select>                       
                        <font>选择你要参展的项目类别</font>
                    </li>
    				<li class="label"> <font>*</font>参展项目名称：</li>
    				<li class="content">
                        <input type="text" name="project_name" id="user" class="text long require validate" maxlength="15" placeholder="最多支持15个汉字字符长度" value="<?php echo isset($forms['project_name']) ? $forms['project_name'] : ""?>"/>
                    </li>
                    <li class="label"><font>*</font>项目宣传图：</li>
                    <li class="content">
                    	<span id="imgd1" style="width:120px;" class="validate require2">
                            <input type='hidden' name='project_logo' value='<?=isset($forms['project_logo']) ? URL::imgurl($forms['project_logo']) : '';?>' id='project_logo'>
                      		<img  style="border: 1px solid #CBCBCB;float: left;height: 110px;padding:1px;width: 120px;<?php if (empty($forms['project_logo'])){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($forms['project_logo']) ? URL::imgurl($forms['project_logo']) : '';?>" id="imghead1" />
                  		</span>
                  		<span class="infor1_modify floleft" style="width:112px;" >
                   			<span class="uploadImg" id="uploadImg1" style="width:112px; height:32px; display:inline;">
	                		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
	                      	<param name="movie" value="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" />
	                      	<param name="quality" value="high" />
	               			<param name="wmode" value="transparent" />
	                      	<param name="allowScriptAccess" value="always" />
	                    	<embed src="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
	                     	</object>
	                     	</span>
	                 	</span>
	                 	<font class="floleft" style="line-height:35px;">吸引用户来关注你的参展项目，建议尺寸<a class="zhaos_big_img" href="<?php echo URL::webstatic('images/zhanhui/shili1.png');?>" target="blank">示例</a></font> 
                    	<span class="info">请上传项目宣传图</span>
                    </li>
                    <li class="label"><font>*</font>所属行业：</li>
                    <li class="content" style="z-index:9;">
                    	<div class="ryl_search_label_right upload_btn" style=" display:inline-block; height:25px;">
                        <div class="ryl_search_result_jia_cont" style="z-index:9999;"></div>
                        <a id="choice_industry" href="#" class="select_area_toggle" data-url="/ajaxcheck/primaryIndustry" first-result=".industry_id1" second-result=".industry_id2" box-title="一级行业">
                            	<?php if(isset($forms['pro_industry']['industry_name'])){echo $forms['pro_industry']['industry_name'];}else{echo "请选择";}?>
                        </a>
                        
                        <!--意向投资地区浮层-->
	                    </div>
	                    <input type="hidden" name="industry_id1" class="industry_id1 validate require" value="<?php echo isset($forms['pro_industry']['industry_id1']) ? $forms['pro_industry']['industry_id1'] : '';?>"/>
	                        <input type="hidden" name="industry_id2" class="industry_id2" value="<?php echo isset($forms['pro_industry']['industry_id2']) ? $forms['pro_industry']['industry_id2'] : '';?>"/>
	                    <font style=" ">项目分类，投资者可以按照行业搜索到此项目</font>
	                    <span class="info">请选择所属行业</span>
	                </li>  
	                <li class="label"><font>*</font>所需投资金额：</li>
	                <li class="content" id="need_money">
	                	<p>
	                    <input type="radio" class="validate require" name="project_amount_type" id="need_money1" value="1" <?php if(isset($forms['project_amount_type']) && $forms['project_amount_type'] == 1){?>checked="checked"<?php }?>/>
	                    <label for="need_money1">5万以下</label>
	                    <input type="radio" name="project_amount_type" id="need_money2" value="2" <?php if(isset($forms['project_amount_type']) && $forms['project_amount_type'] == 2){?>checked="checked"<?php }?>/>
	                    <label for="need_money2">5-10万</label>
	                    <input type="radio" name="project_amount_type" id="need_money3" value="3" <?php if(isset($forms['project_amount_type']) && $forms['project_amount_type'] == 3){?>checked="checked"<?php }?>/>
	                    <label for="need_money3">10-20万</label>
	                    <input type="radio" name="project_amount_type" id="need_money4" value="4" <?php if(isset($forms['project_amount_type']) && $forms['project_amount_type'] == 4){?>checked="checked"<?php }?>/>
	                    <label for="need_money4">20-50万</label>
	                    <input type="radio" name="project_amount_type" id="need_money5" value="5" <?php if(isset($forms['project_amount_type']) && $forms['project_amount_type'] == 5){?>checked="checked"<?php }?>/>
	                    <label for="need_money5">50万以上</label>
	                    <font>投资此项目所需的费用</font></p>
	                    <span class="info">请选择所需投资金额</span>
	                </li>  
	                <li class="label"><font>*</font>招商形式：</li>
	                <li class="content" id="service_type">
	                	<p>
	                    <input class="validate require" type="checkbox" name="project_co_model[]" id="canvass_type1" value="1" <?php if(isset($forms['project_model']) && in_array(1, $forms['project_model'])){?>checked="checked"<?php }?>/>
	                    <label for="canvass_type1">开店加盟</label>
	                    <input type="checkbox" name="project_co_model[]" id="canvass_type2" value="2" <?php if(isset($forms['project_model']) && in_array(2, $forms['project_model'])){?>checked="checked"<?php }?>/>
	                    <label for="canvass_type2">批发代理</label>
	                    <input type="checkbox" name="project_co_model[]" id="canvass_type3" value="3" <?php if(isset($forms['project_model']) && in_array(3, $forms['project_model'])){?>checked="checked"<?php }?>/>
	                    <label for="canvass_type3">网上销售</label>
	                    <font>可以通过哪些方式投资此项目</font>
	                    </p>
	                    <span class="info">请选择招商形式</span>
	                </li> 
	                <li class="label"><font>*</font>招商地区：</li>
	                <li class="content">
	                	<div class="list list_area">
                        <div class="list_li" id="diqu">
                        	<?php
                       			if(isset($forms['area']) && count($forms['area']) > 0){
                        		foreach ($forms['area'] as $k=>$v){
                          	?>
                     		<div class="list">
                         		<dl>
                          		<dt id="webcity_<?php echo $v['pro_id']?>"><?php echo $v['pro_name']?><img src="<?php echo URL::webstatic("images/zhaos/diqu_close.png") ?>"><input type="hidden" value="<?php echo $v['pro_id']?>" name="project_city[]"></dt>
                             	<dd>
                            	<?php
                              		if(isset($v['data'])){
                             		foreach ($v['data'] as $key=>$value){
                              	?>
                              	<span id="webcity_<?php echo $value['area_id']?>"><?php echo $value['area_name']?><img src="<?php echo URL::webstatic("images/zhaoshangAddress/closeBg.gif") ?>"/>
                            	<input type="hidden" value="<?php echo $value['area_id']?>" name="project_city[]"></span>
                              	<?php
                              		}
                               		}
                              	?>
                              	</dd>

                            	</dl>
                       		</div>
                        	<?php
                          		}
                       			}
                         	?>
                        </div>
                        <div class="clear"></div>
                        <p class="aa" style="width:101px;">
                        </p>
                        <span class="tipinfo" id="tipdiqu"></span>
	                    </div>
	                            <a href="#" class="add2_img_btn messageBox require1 validate" data-msg-require1='此处为必填项' data-tag="#select_area_box" data-title="选择招商地区" data-width="748" id="addressClickEffect" style="float:left; text-decoration:none;">
	                        <button id="addImg">请选择</button>
	                        <button id="addImg2" style="disply:none;">重新添加</button>
	                            </a>
	                    <font>该项目本场展会将要在那些地方进行招商</font>
	                </li>  	                
	                <script type="text/javascript">
    					var select_area_list = '<?php foreach ($areas as $k=> $v):?><li><span><?php echo $v->cit_name;?></span><input type="hidden" name="true" class="<?php echo $v->cit_id;?>" /></li><?php endforeach;?>';
					</script>
	                
	                <li class="clear"></li>          
    			</ul>
    			<ul class="info">
    				<li class="title"> <b>项目推广信息</b></li>
    				<li class="label">优惠宣传语：</li>
    				<li class="content">
	                    <input type="text" name="advertisement" maxlength="15" style="width:450px;" id="advertisement" value="<?php echo isset($forms['advertisement']) ? $forms['advertisement'] : '';?>"/>
	                    <p><font class="floleft">设置该项目优惠措施宣传语，吸引用户去关注该展会项目
                            <a class="zhaos_big_img" href="<?php echo URL::webstatic('images/zhanhui/shili3.png');?>" target="blank">示例</a></font></p>
	                    <span class="info info2">请填写优惠宣传语</span>
	                </li>
	                <li class="label"><font>*</font>优惠金额券：</li>
	                <li class="content">
	                    <p><input type="text" name="project_coupon" maxlength="15" id="project_coupon" class="validate require number" style="width:150px;" value="<?php echo isset($forms['project_coupon']) ? $forms['project_coupon'] : '';?>"/>
                        <font>通过当前展会加盟该项目，投资者所能享受的优惠<a class="zhaos_big_img" href="<?php echo URL::webstatic('images/zhanhui/shili2.png');?>" target="blank">示例</a></font>
	                    </p>
	                </li>
	                <li class="label"><font>*</font>优惠券数量：</li>
	                <li class="content">
	                    <input type="text" name="coupon_num" maxlength="15" id="coupon_num" class="validate require number" style="width:150px;" value="<?php echo isset($forms['coupon_num']) ? $forms['coupon_num'] : '';?>"/>
	                    <font>本场展会项目优惠券设置数量，开展中还可随时增加</font>
	                    <span class="info info2">请填写优惠券数量</span>
	                </li>

	                <li class="label">有效期：</li>

	                <li class="content">
	                    <input type="text" value="<?php echo (isset($forms['coupon_deadline']) && $forms['coupon_deadline']) ? date('Y-m-d',$forms['coupon_deadline']) : '';?>" style="width:150px;" onfocus="WdatePicker({minDate:'%y-%M-%d', maxDate:'#{%y+1}-%M-%d'})" name="coupon_deadline" maxlength="15" id="coupon_deadline"/><font>投资者领取项目优惠券后，企方为该投资者保留意向加盟/代理项目最后时间</font>
	                    <span class="info info2">请填写有效时间</span>
	                </li>
	                <li class="label">一句话项目介绍：</li>
	                <li class="content">
	                    <input placeholder="最多支持50个汉字字符长度" type="text" style="width:450px;" name="project_introduction" maxlength="50" id="project_introduction" value="<?php echo isset($forms['project_introduction']) ? $forms['project_introduction'] : '';?>"/>
	                    <span class="info info2">请填写一句话项目介绍</span>
	                </li>
	                <li class="clear"></li>
    			</ul>
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
                            <input type='hidden' name='project_img_url' class="validate require" value='<?php echo $str1;?>' id='project_img_url'>
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
			        	var numb=a.concat(arr).length;
			        	if(numb>4){
			        		$("#beforep").show();
			        	}
			        	else{
			        		$("#beforep").hide();
			        	}

			        	var html=""
			        	for(var i=0;i<arr.length;i++){
			        		html+='<span class="imgboxlist clearfix"><i><img width="120" height="95" src="'+arr[i]+'"></i><a class="project_detial_product_img zhaos_big_img" href="javascript:;" target="blank" data-img="'+arr[i]+'">查看大图</a><a class="project_detial_product_img del delete" href="javascript:;"></a></span>'
			        	}
			        	$(".appendspan").append(html);
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
					        $(".flashPopup").slideDown(function(){ 
					    })
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
			        	c.splice($index,1);			        	
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
                            <input type='hidden' name='project_temp_video' value='' id='project_temp_video'>                            
                        </span>
                        <span class="infor1_modify floleft" style="width:110px;" >
                            <span class="uploadImg" id="uploadImg7" style="width:100px; height:26px; display:inline;">
                            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
                            <param name="movie" value="<?php echo URL::webstatic('/flash/uploadvideo.swf?url='.URL::website('/Upload/Upload/uploadVideo').'&fun=viewImage7&fun2=startUpload')?>" />
                            <param name="quality" value="high" />
                            <param name="wmode" value="transparent" />
                            <param name="allowScriptAccess" value="always" />
                            <embed src="<?php echo URL::webstatic('/flash/uploadvideo.swf?url='.URL::website('/upload/uploadVideo').'&fun=viewImage7&fun2=startUpload')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                            </object>
                            </span>
                        </span>
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
			        	<span id="imgd3" style="width:120px;">
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
    			<ul class="info">
    				<li class="title"> <b>项目其他信息</b></li>
    				<li class="label">公司展示图：</li>
    				<li class="content">
    					<span id="imgd4" style="width:120px;">
			               <input type='hidden' name='company_strength_img' value='<?=isset($forms['company_strength_img']) ? URL::imgurl($forms['company_strength_img']) : '';?>' id='company_strength_img'>
			               <img  style="border: 1px solid #CBCBCB;float: left;height: 110px;padding:1px;width: 120px;<?php if (empty($forms['company_strength_img'])){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($forms['company_strength_img']) ? URL::imgurl($forms['company_strength_img']) : '';?>" id="imghead4" />
			      		</span>
			      		<span class="infor1_modify floleft" style="width:112px;" >
                   			<span class="uploadImg" id="uploadImg4" style="width:112px; height:32px; display:inline;">
	                		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
	                      	<param name="movie" value="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage4')?>" />
	                      	<param name="quality" value="high" />
	               			<param name="wmode" value="transparent" />
	                      	<param name="allowScriptAccess" value="always" />
	                    	<embed src="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage4')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
	                     	</object>
	                     	</span>
	                 	</span>
                        <font>更加直观展示你的参展项目信息，吸引用户来关注</font>
    				</li>
    				<li class="label"><font>*</font>公司实力</li>
                    <li class="content height_auto" id="project_detial">
			            <?php echo  Editor::factory(isset($forms['company_strength']) ? $forms['company_strength'] : '',"exhb_nobar3",array("field_name"=>'company_strength',"width"=>"580","height"=>"200"));?>
			            <font>介绍公司发展历程，目前取得的荣誉、市场收益等</font>
			            <span class="info">请填写公司实力</span>
			        </li>
			        <li class="label">预期收益图：</li>
			        <li class="content">
    					<span id="imgd5" style="width:120px;">
			               <input type='hidden' name='expected_return_img' value='<?=isset($forms['expected_return_img']) ? URL::imgurl($forms['expected_return_img']) : '';?>' id='expected_return_img'>
			               <img  style="border: 1px solid #CBCBCB;float: left;height: 110px;padding:1px;width: 120px;<?php if (empty($forms['expected_return_img'])){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($forms['expected_return_img']) ? URL::imgurl($forms['expected_return_img']) : '';?>" id="imghead5" />
			      		</span>
			      		<span class="infor1_modify floleft" style="width:112px;" >
                   			<span class="uploadImg" id="uploadImg5" style="width:112px; height:32px; display:inline;">
	                		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
	                      	<param name="movie" value="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage5')?>" />
	                      	<param name="quality" value="high" />
	               			<param name="wmode" value="transparent" />
	                      	<param name="allowScriptAccess" value="always" />
	                    	<embed src="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage5')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
	                     	</object>
	                     	</span>
	                 	</span>
                        <font>更加直观展示你的参展项目信息，吸引用户来关注</font>
    				</li>
    				<li class="label">预期收益</li>
    				<li class="content height_auto" id="project_detial">
			            <?php echo  Editor::factory(isset($forms['expected_return']) ? $forms['expected_return'] : '',"exhb_nobar4",array("field_name"=>'expected_return',"width"=>"580","height"=>"200"));?>
			            <font>根据往期加盟商数据反馈，加盟此项目能给投资者带来怎样的收益</font>
			            <span class="info">请填写预期收益</span>
			        </li>
			        <li class="label">优惠政策图：</li>
			        <li class="content">
    					<span id="imgd6" style="width:120px;">
			               <input type='hidden' name='preferential_policy_img' value='<?=isset($forms['preferential_policy_img']) ? URL::imgurl($forms['preferential_policy_img']) : '';?>' id='preferential_policy_img'>
			               <img  style="border: 1px solid #CBCBCB;float: left;height: 110px;padding:1px;width: 120px;<?php if (empty($forms['preferential_policy_img'])){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($forms['preferential_policy_img']) ? URL::imgurl($forms['preferential_policy_img']) : '';?>" id="imghead6" />
			      		</span>
			      		<span class="infor1_modify floleft" style="width:112px;" >
                   			<span class="uploadImg" id="uploadImg6" style="width:112px; height:32px; display:inline;">
	                		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
	                      	<param name="movie" value="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage6')?>" />
	                      	<param name="quality" value="high" />
	               			<param name="wmode" value="transparent" />
	                      	<param name="allowScriptAccess" value="always" />
	                    	<embed src="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage6')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
	                     	</object>
	                     	</span>
	                 	</span>
                        <font>更加直观展示你的参展项目信息，吸引用户来关注</font>
    				</li>
    				<li class="label">优惠政策</li>
    				<li class="content height_auto" id="project_detial">
			            <?php echo  Editor::factory(isset($forms['preferential_policy']) ? $forms['preferential_policy'] : '',"exhb_nobar5",array("field_name"=>'preferential_policy',"width"=>"580","height"=>"200"));?>
			            <font>加盟本项目将会给投资者带来哪些优惠政策，包括签约优惠和后期运营支持等</font>
			            <span class="info">请填写预期收益</span>
			        </li>
			        <li class="clear"></li>
    			</ul>
    			<ul class="info">
			        <li class="content">			        		       
                        <input style="margin-left:303px; display:block;" type="submit" class="red sent" id="tijiao" value="发布项目" data-go="true"/>
			        </li>
			        <li class="clear"></li>
			    </ul>
    		</div>
    	</div>
	</form>
</div>

<script type="text/javascript">
$(".selval").change(function(){
	if($(this).val()==0){return false;}
	window.location.href=window.$config.siteurl+'company/member/exhb/addproject?exhibition_id='+$("#exhb_id").val()+'&project_id='+$(this).val()+'&type='+$("#pro_type").val()+'&without=1';
});

init_big_img();

function closeBox(){
    $(".flashPopup").hide();
}

$(".inputbox input[type='radio']").click(function(e){
	var $index=$(this).index();
	if($index==0){
   		$index=1;
   		$(".selval").eq(0).show().attr("name","project_id").addClass("require");
   		$(".selval").eq(1).hide().attr("name","").removeClass("require");
  	}else{
   		$index=2;
   		$(".selval").eq(1).show().attr("name","project_id").addClass("require");
   		$(".selval").eq(0).hide().attr("name","").removeClass("require");
   	}
  	$("#pro_type").val($index);
})
var typenum=$("#type").val()-1;
$(".inputbox input[type='radio']").eq(typenum).click();
$.vali({	
	"formid":"#forminfo",
	"submitid":"#tijiao",
	rule:{
		"require1":function(){
			if($(this).find("#addImg2").css("display")!="block"){
				return false;
			}
			return true;
		},
        "require2":function(){
            if($(this).find("#imghead1").css("display")!="block"){
                return false;
            }
            return true;
        }
	},
    callback:function(){
        var flag;
        if(editor_1.text().length<=0){
            $("textarea[name='project_advantage']").nextAll(".info").show();
            $('html, body').animate({"scrollTop":$("textarea[name='project_advantage']").offset().top-$(window).height()}, 1000);
            flag=false;
        }
        else if(editor_1.text().length>=200){
            $("textarea[name='project_advantage']").nextAll(".info").show().text("字数大于200");
            $('html, body').animate({"scrollTop":$("textarea[name='project_advantage']").offset().top-$(window).height()}, 1000);
            flag=false;
        }
        else{
             $("textarea[name='project_advantage']").nextAll(".info").hide();
        }
        if(editor_2.text().length<=0){
            $("textarea[name='project_running']").nextAll(".info").show();
            $('html, body').animate({"scrollTop":$("textarea[name='project_running']").offset().top-$(window).height()}, 1000);
            flag=false;
        }
        else if(editor_2.text().length>=200){
            $("textarea[name='project_running']").nextAll(".info").show().text("字数大于200");
            $('html, body').animate({"scrollTop":$("textarea[name='project_running']").offset().top-$(window).height()}, 1000);
            flag=false;
        }
        else{
             $("textarea[name='project_running']").nextAll(".info").hide();
        }
        if(editor_3.text().length<=0){
            $("textarea[name='company_strength']").nextAll(".info").show();
            $('html, body').animate({"scrollTop":$("textarea[name='company_strength']").offset().top-$(window).height()}, 1000);
            flag=false;
        }
        else if(editor_3.text().length>=200){
            $("textarea[name='company_strength']").nextAll(".info").show().text("字数大于200");
            $('html, body').animate({"scrollTop":$("textarea[name='company_strength']").offset().top-$(window).height()}, 1000);
            flag=false;
        }
        else{
             $("textarea[name='company_strength']").nextAll(".info").hide();
             flag=true;
        }
        if($("#tijiao").attr("data-go")){
       	 	flag=true;
          	$("#tijiao").attr("data-go","false");
        }else{
        	flag=false;
        }
        return flag;
    }
});
    

//项目宣传图
function viewImage1(_str){
	$("#imghead1").css('display', 'block');
    $("#imghead1").attr('src', _str);
    $("#project_logo").attr('value',_str);
    $("#uploadImg1").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='112' height='32' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
}

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

//公司展示图
function viewImage4(_str){
	$("#imghead4").css('display', 'block');
    $("#imghead4").attr('src', _str);
    $("#company_strength_img").attr('value',_str);
    $("#uploadImg4").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage4')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage4')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='112' height='32' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
}

//预期收益图
function viewImage5(_str){
	$("#imghead5").css('display', 'block');
    $("#imghead5").attr('src', _str);
    $("#expected_return_img").attr('value',_str);
    $("#uploadImg5").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage5')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage5')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='112' height='32' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
}

//优惠政策图
function viewImage6(_str){
	$("#imghead6").css('display', 'block');
    $("#imghead6").attr('src', _str);
    $("#preferential_policy_img").attr('value',_str);
    $("#uploadImg6").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage6')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage6')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='112' height='32' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
}

//视频
function viewImage7(_str){
    $("#project_temp_video").attr('value',_str);    
    $("#uploadImg7").html("<span class='uploadspan' style='display:block'>上传成功</span><object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/uploadvideo2.swf?url='.URL::website('/Upload/Upload/uploadVideo').'&fun=viewImage7')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/uploadvideo2.swf?url='.URL::website('/upload/uploadVideo').'&fun=viewImage7&fun2=startUpload')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='100' height='26' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
    
}
function startUpload()
{
   
    
}
</script>
<?php if(empty($yijuhuaPro) && empty($historyPro)){?>
<script type="text/javascript">
    $(function(){       
        window.MessageBox({
	    title:"标题",
	    content:"<p>您还没有发布属于您的一句话平台项目，去<a href='<?php echo URL::website('/company/member/project/addproject');?>'>发布项目</a></p>",
	    btn:"ok",
	    width:600,
	    target:"new"
	  	});
    })
 </script>
 <?php }?>  
 <script type="text/javascript"> 
    var popur = window.MessageBox({
        title:"生意街提示您",
        content:"<p> 1、当前选择项目信息<span style='color:red;'>审核未通过</span>，需要修改重新提交后，才能发布展会项目，去<a href='javascript:;'>修改></a></p><p style='padding:0px 70px 30px; font-size:14px;'>2、或者重新选择其他你所发布的项目，去<a href='javascript:;' class='closepopur'>重新选择></a></p>",
        width:600,        
        target:"new"
    });
    $(".closepopur").live("click",function(){
        popur.hide();
    })

 </script>   
