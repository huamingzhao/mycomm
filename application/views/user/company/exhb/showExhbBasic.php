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
	<h2 class="user_right_title">
        <span>参展项目管理</span>
        <div class="clear"></div>
    </h2>
    <div class="my_business_new">
    	<div class="project_detial project_release">
    		<form id="forminfo" method="post" action="<?php echo URL::website('/company/member/exhb/editExhbBasic');?>" enctype="multipart/form-data">
    			<input type="hidden" name="project_id" value="<?php echo $project_id;?>" />
    			<input type="hidden" name="exhb_id" value="<?php echo $exhb_id;?>" />
    			<input type="hidden" name="status" value="<?php echo $status;?>" />
    			<ul class="info">
    				<li class="title"> <b>项目基本信息</b></li>  
                    <li class="label"> <font>*</font>项目类别：</li>
                    <li class="content"> 
                        <select name="catalog_id" class="require validate">
                            <option value="" selected="selected">请选择项目类别</option>
                            <?php if($catalog_info){foreach($catalog_info as $v){?>
                            <option value="<?php echo $v['catalog_id']?>" <?php if(isset($catalog_id) && $v['catalog_id'] == $catalog_id){?>selected="selected"<?php }?>><?php echo $v['catalog_name']?></option>
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
          		<input type="submit"class="yellow zhanhuisave" value="保存">
    		</form>
    	</div>      
    </div>
</div>
<script type="text/javascript">
init_big_img();

function closeBox(){
    $(".flashPopup").hide();
}

$.vali({  
    "formid":"#forminfo",
    "submitid":".zhanhuisave",
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
    }
});

//项目宣传图
function viewImage1(_str){
	$("#imghead1").css('display', 'block');
    $("#imghead1").attr('src', _str);
    $("#project_logo").attr('value',_str);
    $("#uploadImg1").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='112' height='32' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
}
</script>

