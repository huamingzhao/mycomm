<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webcss("platform/renling.css")?>
<?php echo URL::webjs("invitepro.js")?>

    <!--右侧开始-->
    <script type="text/javascript">
    // 关闭flash 浮层
    function closeBox(){
        $("#flashPopup").slideUp("500",function(){
            $("#opacityBg").hide();
        });
    }
    // 刷新页面
    function reloadWin(){
        window.location.reload();
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
<div class="main" style="background-color:#e3e3e3; height:auto;">
   <div class="ryl_main_bg">
       <div class="ryl_main_bg01"></div>
       <div class="ryl_main_bg02">
          <!--认领-->
          <div class="renling_main">
             <h3><span><a href="<?php echo URL::website('company/member/project/showproject')?>">我的项目</a> > 修改提交的认领信息</span></h3>
             <!--验证手机号码-->
             <div id="cg-basic-info">
    <?php echo Form::open(URL::website('platform/project/addRenlingProjectInfo').'?project_id='.$project_id, array('method' => 'post','id'=>'projectedit','enctype'=>"multipart/form-data"))?>
    	
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
            <div class="infoRight"><input type="text"  class="text text1" id="tel" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" maxlength="20" value="<?php echo $renlinglist['project_phone'];?>" name="com_phone"/>
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
                echo  Editor::factory(isset($renlinglist['project_summary']) ? htmlspecialchars_decode($renlinglist['project_summary']):'',"nobar",array("field_name"=> 'com_desc',"width"=>"475","height"=>"200"));
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
                           <span style="color:red;height:30px; width:430px; display:block; overflow:hidden;line-height:30px; float:left; " id="iamge_renling" class="tipin"></span>
                        </dt>
                       <p style="color: #7C7C7C; line-height:24px;">建议上传<em style="color:red;font-style:normal; ">2张</em>或以上可以证明此项目属于贵企业的资料图片，如项目资质图片、产品相关照片、门店经营情况等，但不得直接从网站复制。</p>
                       
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
          
          <div class="clear"></div>
       </div>
       <div class="ryl_main_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>

<script type="text/javascript">
$(function(){
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
	$("#tel").focus(function(){
		$("#tel").siblings(".tipin").text("");
	});
	$("#tel").blur(function(){
		if($("#tel").val().length == 0){
			$("#tel").siblings(".tipin").text("请输入您常用的手机号码或座机号码");
			$("#connection").hide();
		}else if($("#tel").val().length < 5){
			$("#tel").siblings(".tipin").text("联系方式需大于5个字符");
			$("#connection").hide();
		}else{
			$("#tel").siblings(".tipin").text("");
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
	$("#projectedit").submit(function(){
		//项目名称
		if($("#projectname").val().length == 0){
			$("#projectname").siblings(".tipin").text("请输入项目名称");
			a=0;
		}else{
			$("#projectname").siblings(".tipin").text("");
			a=1;
		}

		//座机号码
		if($("#tel").val().length == 0){
			$("#tel").siblings(".tipin").text("请输入您常用的手机号码或座机号码");
			$("#connection").hide();
			b=0;
		}else{
			$("#tel").siblings(".tipin").text("");
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
// 			$("#iamge_renling").text("请至少上传两张项目图片");
// 			g=0;
// 		}else{
// 			$("#iamge_renling").text(" ");
// 			g=1;
// 		}
		
		//数据不符阻止提交表单
		var total = a+b+c+d+e;
		if(total != 5){
			return false;
		}
	});
	
})

</script>