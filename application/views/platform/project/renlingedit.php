<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webcss("platform/renling.css")?>
<?php echo URL::webcss("platform/renling_ht.css")?>
<?php echo URL::webjs("invitepro.js")?>
<?php echo URL::webcss("companyTel_zg.css")?>
<?php echo URL::webjs("comtel_zg1.js") ?>
<?php echo URL::webjs("renzheng.js");?>
<?php echo URL::webcss("renzheng.css"); ?>
    <!--右侧开始-->
    <script type="text/javascript">
    // 关闭flash 浮层
    function closeBox(){
        $("#flashPopup").slideUp("500",function(){
            $("#opacityBg").hide();
        });
    }
    // 刷新页面
    function reloadWin(urldata){
         //window.location.reload();
    	 closeBox();
    	 if (urldata.indexOf('<html>')<0){
    		 ch = new Array;
             ch = urldata.split(',');
        	 if(ch.length>0){
	        	 for(i=0;i<ch.length;i++){
	            	 ch2 = new Array;
	        	     ch2 = ch[i].split('||');
	        	     if(ch2[0] && ch2[1] &&ch2[2]){
	        	     	$("#image_dl_renling").append('<dd><img width="158" height="124" class="imgStyle" src="'+ch2[2]+'"><input type="hidden" value="'+ch2[2]+'"><p><a class="delete deleterengling" href="javascript:void(0)" id="'+ch2[0]+'_'+ch2[1]+'_deltete">删除</a></p></dd>');
	        	     }
	             }
         	 }
          }
    }
    </script>
<style>
#cg-basic-info{padding:30px 0 30px 110px;}
#cg-basic-info strong{color:#f00;font-weight:normal;padding-right:5px;}
#cg-basic-info .info{padding-top:20px;}
#cg-basic-info .infoLeft{float:left;width:95px;text-align:right;}
#cg-basic-info .infoLeft.pt{padding-top:4px;}
#cg-basic-info .infoRight{float:left;width:590px;}
#cg-basic-info .infoRight .addImage{position:relative;top:0;}
#cg-basic-info .infoRight #imgd{}
#cg-basic-info .infoRight #imgd img{width:120px;height:110px;padding:1px;border:1px solid #cbcbcb;float:left;margin-right:10px;}
#cg-basic-info .infoRight .spanTip{color:#9f9f9f;width:200px;display:inline-block;padding-left:10px;}
#cg-basic-info .infoRight .spanTip2{color:#9f9f9f;width:auto;display:inline-block;padding-left:10px;}
#cg-basic-info .infoRight .text{height:22px;line-height:22px;border:1px solid #dbdbdb;text-indent:3px;}
#cg-basic-info .infoRight .text1{width:195px;}
#cg-basic-info .infoRight .text2{width:80px;}
#cg-basic-info .infoRight .text3{width:340px;}
#cg-basic-info .infoRight .mail{color:#9f9f9f;font-family:Arial;}
#cg-basic-info .infoRight .tel{color:#9f9f9f;font-family:Arial;padding-top:5px;display:inline-block;}
#cg-basic-info .infoRight .modify{color:#0036ff;margin-left:10px;}
#cg-basic-info .infoRight .gsxz{float:left;}
#cg-basic-info .infoRight .gsbq{float:left;padding:0 15px 0 3px;}
#cg-basic-info .infoRight .tipin{color:#eb0000;padding-left:10px;}
.upLoad a {
    color: #0134FF;
    float: left;
    font-size: 14px;
    padding-left: 13px;
    padding-right: 27px;
}
</style>
<div id="popupBg" class="popupBg" style="opacity:0.7;filter:alpha(opacity=70);"></div>
<div class="rzPopup" id="rzPopup">
    <dl>
        <dt><img src="<?php echo URL::webstatic("images/getcards/close.jpg"); ?>" id="close"/></dt>
        <dd class="first">
            <p style="text-align:center;">确认要删除此图片吗？</p>
            <span>
                <input type="button" value="" class="btn1" id="btn4"/>
                <input type="button" value="" class="btn2" id="btn2"/>
            </span>
        </dd>
        <dd class="second"></dd>
    </dl>
</div>
    <div class="imgPopup" id="imgPopup">
        <div class="imgPopupTop"><a href="#" class="close" style="float:right;">关闭</a></div>
        <div class="imgZoom"><img id="imgZoom"/></div>
        <div class="imgShow"></div>
    </div>
    <div class="flashPopup flashPopup2" id="flashPopup">
        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" width="750" height="620" id="1234551236">
            <param name="movie" value="<?php echo URL::webstatic("flash/uploadProjectImg3.swf")?>" />
            <param name="allowScriptAccess" value="always" />
            <param name="quality" value="high" />
            <param name="wmode" value="transparent" />
            <embed src="<?php echo URL::webstatic("flash/uploadProjectImg3.swf");?>" quality="high" allowScriptAccess="always" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="750" height="620"></embed>
        </object>
    </div>
    <div class="popupDelete" id="popupDelete">
        <div class="closeImg" id="closeImg"><img src="<?php echo URL::webstatic("images/infor2/closeimg.gif") ?>" width="20" height="20"/></div>
        <div class="clear"></div>
        <p>确认要删除图片吗？</p>
        <div id="confirm_delete" class="confirm"><a class="comfirm_class" href="javascript:void(0)" id="sure"><img src="<?php echo URL::webstatic("/images/infor2/true.gif") ?>" width="112" height="32" /></a>
        <a href="javascript:void(0)" id="cancel"><img src="<?php echo URL::webstatic("/images/infor2/btn9.gif") ?>" width="112" height="32"></a></div>
    </div>
    <div class="opacityBg" id="opacityBg"></div>

<!--公共背景框-->
    <style>
.timeDiv .spanYz {
    color: #707070;
    cursor: pointer;
    display: inline-block;
    height: 25px;
    line-height: 25px;
    margin-top: 0px;
    text-align: center;
    width: 120px;
	padding-top: 4px;
	background-position: left 4px;
}
.imgListSpe ul{padding:0 15px; height:auto!important; height:0; min-height:0;}
.renzhengContent span {
    color: #7C7C7C;
    float: left;
    line-height: 30px;
	padding-right:10px;
}
.renzhengContent span em {color: #E10602;display: inline-block;font-style: normal;}

.renling_item_cont_in li p span ins{color: #E10602;float: left;line-height: 30px;padding-right:10px;text-decoration: none;}
.addtimeDiv {border:none;float: left;margin-top: 0px;padding-left: 12px;}
</style>
<div class="main" style="background-color:#e3e3e3; height:auto;">
   <div class="ryl_main_bg">
       <div class="ryl_main_bg01"></div>
       <div class="ryl_main_bg02">
          <!--认领-->
          <div class="renling_main">
             <h3><span class="renling_main_title">认领项目信息</span></h3>
             
 				<!--第一块-->			
 				 <a id="renling_first_div" style="display:none"><?php echo $is_mobile;?></a>
 				 <?php if(!$is_mobile){?>			
 					 <div class="renling_item_cont" id="first_block">
                   <div class="renling_item_cont_in">
                      <h4><span>请验证您的手机号码</span></h4>
                      <ul>
                      <li>
                          <label><em>* </em>手机号码：</label>
                          <p>
                          <input type="text" class="renling_item_cont_text" id="tel" name="receiver" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" maxlength="11" value="<?=$mobile?>" style="font-size:16px; font-family:Arial;" />
                          <span><ins id="insWord"></ins></span>
                          <span class="timeDiv" id="timeDiv" style="clear:none;">
                             <em style="color:#7C7C7C; display:none;" >您如果没有收到验证码</em>
                             <span class="spanYz" id="timeControl" style="font-size:12px; margin-bottom:5px;">获取验证码</span>
                          </span>
                          <div class="clear"></div>
                          </p>
                          <div class="clear"></div>
                      </li>
                      <li>
                           <label for="telyzm">输入验证码：</label>
                          <p>
                          <input type="text" name="check_code" id="telyzm" class="renling_item_cont_text" />
                          <span>
                          <img id="yanzhengmaeror_img" style="display: none" src="<?php echo URL::webstatic('images/platform/renling/icon02.jpg');?>" /><em id="yanzhengmaeror"></em>
                          </span>
                          <div class="clear"></div>
                          </p>
                          <div class="clear"></div>
                      </li>
                      </ul>
                      <div class="clear"></div>
                   </div>
                  <div class="clear"></div>
                </div>
 				<?php }?>
 				
              <?php echo Form::open(URL::website('platform/project/addRenlingProjectInfo').'?project_id='.$project_id, array('method' => 'post','id'=>'projectedit','enctype'=>"multipart/form-data"))?>


	             <!-- 显示提交认领信息开始 -->
	        <div class="renling_item_cont">
            <div class="renling_item_cont_in">
             <h4><span>请填写您的认领信息<b>（<em>*</em> 为必填项）</b></span></h4>
          
	      <div id="cg-basic-info">
	    	<div class="info">
	        	<div class="infoLeft pt"><strong>*</strong>项目名称：</div>
	            <div class="infoRight"><input type="text"  class="text text1" id="projectname" maxlength="30"  value="<?php echo $renlinglist['project_brand_name'];?>" name="projectname"/><span class="tipin"></span></div>
	            <div class="clear"></div>
	        </div>
	        
	       <div class="info">
	        	<div class="infoLeft pt"><strong>*</strong>公司名称：</div>
	            <div class="infoRight"><input type="text" class="text text1" maxlength="30" id="companyename" name="companyename" value="<?php echo $renlinglist['company_name'];?>"/><span class="tipin"></span></div>
	            <div class="clear"></div>
	        </div>
	        
	        <div class="info">
	        	<div class="infoLeft pt"><strong>*</strong>主营产品：</div>
	            <div class="infoRight"><input type="text" class="text text1" maxlength="30" id="mainproject" name="mainproject" value="<?php echo $renlinglist['project_principal_products'];?>"/><span class="tipin"></span></div>
	            <div class="clear"></div>
	        </div>
	        
	       <div class="info">
	        	<div class="infoLeft pt"><strong>*</strong>联系方式：</div>
	            <div class="infoRight"><input type="text"  class="text text1" id="telphone" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" maxlength="20" value="<?php echo $renlinglist['project_phone'];?>" name="com_phone"/>
	            <span id="connection" style="display:inline-block; margin:0 3px;color: #7C7C7C;">请输入您常用的手机号码或座机号码</span><span class="tipin"></span></div>
	            <div class="clear"></div>
	        </div>
	        
	        <div class="info">
	        	<div class="infoLeft pt"><strong>*</strong>联&nbsp;系&nbsp;人：</div>
	            <div class="infoRight"><input type="text"  class="text text1" id="name" maxlength="10" onkeyup="value=value.replace(/[^\a-\z\A-\Z\u4E00-\u9FA5]/g,'')" value="<?php echo $renlinglist['project_contact_people'];?>" name="com_contact"/><span class="tipin"></span></div>
	            <div class="clear"></div>
	        </div>
	        
	        <div class="info">
	        	<div class="infoLeft pt">项目简介：</div>
	            <div class="infoRight">
	            <?php
	                echo  Editor::factory(isset($renlinglist['project_summary']) ? $renlinglist['project_summary'] :'',"nobar",array("field_name"=> 'com_desc',"width"=>"475","height"=>"200"));
	            ?>
	            <span class="tipin" id="editor" style="padding-left:0;height:30px;line-height:30px;display:block;">&nbsp;</span>
				</div>
	            <div class="clear"></div>
	        </div>
	        
	       <div class="info" style="padding-top:0;">
	        	<div class="infoLeft pt">项目图片：</div>
	            <div class="infoRight">
	               <div class="upLoadImgBtn" id="upLoadImgBtn" style="padding-right:15px; padding-top:0;">
	                        <dl id="image_dl_renling">
	                        <dt>
	                           <img src="<?php echo URL::webstatic("/images/infor2/uploadbtn.gif") ?>" width="117" height="32" id="flashBtn1" style="cursor:pointer"/>
	                           <span style="color:red;height:30px; width:430px; display:block; overflow:hidden;line-height:30px; float:left; " id="iamge_renling_span" class="tipin"></span>
	                        </dt>
	                       <p style="color: #7C7C7C; line-height:24px;">建议上传<em style="color: red;">2张</em>或以上可以证明此项目属于贵企业的资料图片，如项目资质图片、产品相关照片、门店经营情况等，但不得直接从网站复制。</p>
	                       
	                       <span class="tipin" id="iamge_renling" style="color:red;padding-left:0;height:30px;line-height:30px;display:block;">&nbsp;</span>
	                            <?php foreach ($list as $value){
	                                $url = "/platform/ajaxcheck/deleteRenlingProjectImg?project_id=".$project_id."&id=".$value->id;
	                                ?>
	                            <dd><img src="<?php echo URL::imgurl($value->project_img);?>" width="158" height="124" class="imgStyle"/><input type="hidden" value="<?=str_replace('/s_','/b_',$value->project_img);?>">
	                                <p><a id="<?php echo $project_id.'_'.$value->id.'_deltete'?>" href="javascript:void(0)" class="delete deleterengling">删除</a></p>
	                            </dd>
	                            <?php }?>
	                        </dl>
	                        <?=$page;?>
	                    </div>
	            </div>
	            <div class="clear"></div>
	        </div>
	
	          <div class="info">
	        	<div class="infoLeft">&nbsp;</div>
	            <div class="infoRight"><input type="image" src="<?php echo URL::webstatic('images/infor1/infor1_save.jpg')?>"/></div>
	            <div class="clear"></div>
	        </div>
	    <?php echo Form::close();?>
	            </div>
	       </div>
	  </div>
    <!-- 显示认领信息结束 -->
          </div>
          
          <div class="clear"></div>
       </div>
       <div class="ryl_main_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>

<script type="text/javascript">
$(function(){
	//输入验证码框 input框失去焦点 触发事件
	$("#telyzm").blur(function(){
		if($("#telyzm").val().length == 0){			
			$("#yanzhengmaeror").text("请输入验证码");
			$("#yanzhengmaeror_img").hide();
			e=0;
			mobile_validation(e);
		}else{
			$("#yanzhengmaeror_img").hide();
			$("#yanzhengmaeror").text("");
			e=1;
			mobile_validation(e);
		}
		});
	
	//项目名称
	$("#projectname").focus(function(){
		$("#projectname").siblings(".tipin").text("");
	});
	$("#projectname").blur(function(){
		if($("#projectname").val().length == 0){
			$("#projectname").siblings(".tipin").text("请输入项目名称");
			e=0;
		}else{
			$("#projectname").siblings(".tipin").text("");
			e=1;
		}
	});

	//公司名称
	$("#companyename").focus(function(){
		$("#companyename").siblings(".tipin").text("");
	});
	$("#companyename").blur(function(){
		if($("#companyename").val().length == 0){
			$("#companyename").siblings(".tipin").text("请输入公司名称");
			e=0;
		}else{
			$("#companyename").siblings(".tipin").text("");
			e=1;
		}
	});

	//主营产品
	$("#mainproject").focus(function(){
		$("#mainproject").siblings(".tipin").text("");
	});
	$("#mainproject").blur(function(){
		if($("#mainproject").val().length == 0){
			$("#mainproject").siblings(".tipin").text("请输入主营产品");
			e=0;
		}else{
			$("#mainproject").siblings(".tipin").text("");
			e=1;
		}
	});
	
	//联系方式
	$("#telphone").focus(function(){
		$("#telphone").siblings(".tipin").text("");
	});
	$("#telphone").blur(function(){
		if($("#telphone").val().length == 0){
			$("#telphone").siblings(".tipin").text("请输入您常用的手机号码或座机号码");
			$("#connection").hide();
		}else if($("#telphone").val().length < 5){
			$("#telphone").siblings(".tipin").text("联系方式需大于5个字符");
			$("#connection").hide();
		}else{
			$("#telphone").siblings(".tipin").text("");
		}
	});

	//联系人
	$("#name").focus(function(){
		$("#name").siblings(".tipin").text("");
	});
	$("#name").blur(function(){
		if($("#name").val().length == 0){
			$("#name").siblings(".tipin").text("请输入联系人");
		}else if($("#name").val().length < 2){
			$("#name").siblings(".tipin").text("输入的联系人需大于2个字符");
		}else{
			$("#name").siblings(".tipin").text("");
		}
	});

	//提交表单验证
	var a,b,c,d,e,f,g,h,i;
	
	$("#projectedit").submit(function(e){
		 mobile_validation(e);
		
		//项目名称
		if($("#projectname").val().length == 0){
			$("#projectname").siblings(".tipin").text("请输入项目名称");
			a=0;
		}else{
			$("#projectname").siblings(".tipin").text("");
			a=1;
		}

		//联系方式
		if($("#telphone").val().length == 0){
			$("#telphone").siblings(".tipin").text("请输入您常用的手机号码或座机号码");
			$("#connection").hide();
			b=0;
		}else{
			$("#telphone").siblings(".tipin").text("");
			b=1;
		}
		
		//公司名称
		if($("#companyename").val().length == 0){
			$("#companyename").siblings(".tipin").text("请输入公司名称");
			c=0;
		}else{
			$("#companyename").siblings(".tipin").text("");
			c=1;
		}

		//联系人
		if($("#name").val().length == 0){
			$("#name").siblings(".tipin").text("请输入联系人");
			d=0;
		}else if($("#name").val().length < 2){
			$("#name").siblings(".tipin").text("输入的联系人需大于2个字符");
			d=0;
		}else{
			$("#name").siblings(".tipin").text("");
			d=1;
		}

		//主营产品
		if($("#mainproject").val().length == 0){
			$("#mainproject").siblings(".tipin").text("请输入主营产品");
			e=0;
		}else{
			$("#mainproject").siblings(".tipin").text("");
			e=1;
		}

// 		if(editor.isEmpty()){
// 			$("#editor").text("请输入项目简介");
// 			f=0;
// 		}else{
// 			$("#editor").text(" ");
// 			f=1;
// 		}

// 		var count_image=$("#upLoadImgBtn dl dd").length;
// 		if(count_image<2){
// 			$("#iamge_renling_span").text("请至少上传两张项目图片");
// 			g=0;
// 		}else{
// 			$("#iamge_renling_span").text(" ");
// 			g=1;
// 		}

	
		//数据不符阻止提交表单
		var total = a+b+c+d+e;
		if(total != 5){
			return false;
		}
	});

	function mobile_validation(e){
		var firstdiv=$("#renling_first_div").html();
		var firsterror=false;
		if(firstdiv==0){//如果手机号码没有验证
				//判断验证码是否输入正确
				if(/(^(13|14|15|18)\d{9}$)/.test($("#tel").val())){
		            $("#insWord").css({"display":"block"});
		            if($("#telyzm").val() == ""){
					    e.preventDefault();
		                alert("验证码不能为空");
		                return false;
		            }
		        }else{
		            $("#insWord").css({"display":"block"});
		            $("#insWord").text("请输入正确的手机号码");
		            return false;
		        }
		        $.ajax({
		            type: "post",
		            dataType: "json",
		            url: "/ajaxcheck/sendMessageByRenling",
		            async:false,
		            data: "receiver=" + $("#tel").val()+"&check_code="+$("#telyzm").val(),
		            complete :function(){
		            },
		            success: function(msg){
		                if( msg['error'] != ''){
		                	$("#yanzhengmaeror_img").show();
		                	$("#yanzhengmaeror").html("您的验证码输入错误");
		                	alert(msg['error']);
		                	firsterror=true;
		                	return false;
		                }else{
		                	$("#yanzhengmaeror_img").hide();
		                }
		            }
		         });
		     }
		 if(firsterror==true){
			 return false;
	     }
	}	
})

</script>