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
	<form id="forminfo" action="<?php echo URL::website('/company/member/exhb/editExhbOther');?>" enctype="multipart/form-data" method="post">
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
    					<span id="imgd4" style="width:120px;">
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
    					<span id="imgd4" style="width:120px;">
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
    			<input type="submit"class="yellow zhanhuisave" value="保存">
    		</div>
    	</div>
	</form>
</div>   
<script type="text/javascript">
init_big_img();

function closeBox(){
    $(".flashPopup").hide();
}

$.vali({	
	"formid":"#forminfo",
	"submitid":".zhanhuisave",	
    callback:function(){        
        if(editor_3.text().length<=0){
            $("textarea[name='company_strength']").nextAll(".info").show();
            return false;
        }
        else if(editor_3.text().length>=200){
            $("textarea[name='company_strength']").nextAll(".info").show().text("字数大于200");
            return false;
        }
        else{
             $("textarea[name='company_strength']").nextAll(".info").hide();
             return true
        }

    }
});

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
</script> 		