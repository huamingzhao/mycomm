<?php echo URL::webcss("common.css")?>
<style>
#cg-basic-info{padding:50px 0 30px 60px;}
#cg-basic-info strong{color:#f00;font-weight:normal;padding-right:5px;}
#cg-basic-info .info{padding-top:20px;*padding-top:10px;}
#cg-basic-info .infoLeft{float:left;width:95px;text-align:right;}
#cg-basic-info .infoLeft.pt{padding-top:4px;}
#cg-basic-info .infoRight{float:left;width:590px;}
#cg-basic-info .infoRight .addImage{position:relative;top:0;}
#cg-basic-info .infoRight #imgd{}
#cg-basic-info .infoRight #imgd img{width:150px;height:120px;padding:1px;border:1px solid #cbcbcb;float:left;margin-right:10px;}
#cg-basic-info .infoRight .spanTip{color:#9f9f9f;display:inline-block;padding-left:10px;}
#cg-basic-info .infoRight .spanTip2{color:#9f9f9f;width:auto;display:inline-block;padding-left:10px;}
#cg-basic-info .infoRight .text{height:22px;line-height:22px;border:1px solid #dbdbdb;text-indent:3px;}
#cg-basic-info .infoRight .text1{width:190px;}
#cg-basic-info .infoRight .text2{width:80px;}
#cg-basic-info .infoRight .text3{width:340px;}
#cg-basic-info .infoRight .mail{color:#9f9f9f;font-family:Arial;}
#cg-basic-info .infoRight .tel{color:#9f9f9f;font-family:Arial;padding-top:5px;display:inline-block;}
#cg-basic-info .infoRight .modify{color:#0036ff;margin-left:10px;}
#cg-basic-info .infoRight .gsxz{float:left;}
#cg-basic-info .infoRight .gsbq{float:left;padding:0 15px 0 3px;}
#cg-basic-info .infoRight .tipin{color:#eb0000;padding-left:10px;}
#wrap_repeat {border:none;}
#address{border:1px solid #cbcbcb}
#address1{border:1px solid #cbcbcb}
</style>
<?php echo URL::webjs("invitepro_zg.js")?>
<div id="wrap">
    <div id="wrap_outer">
        <div id="wrap_inner" style="position: relative">
<div id="wrap_repeat" style="border:none;">
<div id="right" style="width:960px; border:none; padding-right: 0">
    <div id="right_top"><span><?php echo '你好，欢迎进入企业基本信息管理！'; ?></span><div class="clear"></div></div>
    <div id="cg-basic-info">
    <?php echo Form::open(URL::website('/quick/User/DoComInfo'), array('method' => 'post','id'=>'companyedit','enctype'=>"multipart/form-data"))?>
        <div><b>请填写基本信息</b>（<strong>*</strong>为必填项）</div>
       <input type="hidden" value="<?=arr::get($companyinfo,"com_id");?>" name="com_id"/>
        <div class="info">
            <div class="infoLeft"><strong>*</strong>公司名称：</div>
            <div class="infoRight"><input type="text" id="com_name" name="com_name" class="text text3"value="<?php echo arr::get($companyinfo, 'com_name')?>" maxlength="20"/><span class="tipin"></span></div>
            <div class="clear"></div>
        </div>
        <div class="info">
            <div class="infoLeft"><strong>*</strong>公司logo：</div>
            <div class="infoRight">
                <span id="imgd">
                    <img src="<?php echo URL::imgurl(arr::get($companyinfo, 'com_logo'));?>" id="imghead" <?php if (arr::get($companyinfo, 'com_logo') == ""){?>style="display:none"<?php }?>/>
                    <input type='hidden' name='com_logo' value='' id='company_log'>
                </span>
                 <?php if(arr::get($companyinfo, 'com_logo') == ""){?>
                    <span class="uploadImg" id="uploadImg" style="float:left; height:32px;">
                     <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
                     <param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_project.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" />
                     <param name="quality" value="high" />
                     <param name="wmode" value="transparent" />
                     <param name="allowScriptAccess" value="always" />
                     <embed src="<?php echo URL::webstatic('/flash/uploadhead_project.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                     </object>
                    </span>

                    <?php }else{?>
                    <span class="uploadImg" id="uploadImg"  style="float:left; padding-left:10px;">
                     <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="112" height="32" id="flashrek2">
                     <param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_person.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" />
                     <param name="quality" value="high" />
                     <param name="wmode" value="transparent" />
                     <param name="allowScriptAccess" value="always" />
                     <embed src="<?php echo URL::webstatic('/flash/uploadhead_person.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                     </object>
                    </span>
                 <?php }?>
                 <span class="spanTip">支持JPEG、GIF、PNG等格式,大小不超过1M，长120px,宽110px为佳</span>
                 <span class="tipin"></span>
             </div>
            <div class="clear"></div>
        </div>
        <div class="info">
            <div class="infoLeft pt"><strong>*</strong>座机电话：</div>
            <div class="infoRight"><input type="text"  class="text text1" id="tel" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" maxlength="20" value="<?php echo $com_phone?>" name="com_phone"/> <span>分机号</span> <input type="text" class="text text2" onkeyup="this.value=this.value.replace(/[^0-9]/gi,'')" maxlength="5" name="branch_phone" value="<?php echo $branch_phone?>"/><span class="spanTip2">如没有分机号可不填</span><span class="tipin"></span></div>
            <div class="clear"></div>
        </div>
        <div class="info">
            <div class="infoLeft pt"><strong>*</strong>手机号码：</div>
            <div class="infoRight">
            <?php if(!arr::get($user_info, "valid_mobile")) {?>
            <input type="text" class="text text1" id="mobile" onkeyup="this.value=this.value.replace(/[^0-9]/gi,'')" maxlength="11" value="<?php echo arr::get($user_info, "mobile");?>" name="mobile"/><span class="tipin"></span>
            <?php }else{?>
            <input type="hidden" name="mobile" id="mobile" value="<?php echo arr::get($user_info, "mobile");?>">
            <span class="tel"><?php echo arr::get($user_info, "mobile");?></span><span><img src="<?php echo URL::webstatic('images/infor1/tel_check.jpg')?>"></span><a href="/quick/User/UpdataMobile" class="modify">修改手机号码</a>
            <?}?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="info">
            <div class="infoLeft pt"><strong>*</strong>联&nbsp;系&nbsp;人：</div>
            <div class="infoRight"><input type="text"  class="text text1" id="name" maxlength="10" onkeyup="value=value.replace(/[^\a-\z\A-\Z\u4E00-\u9FA5]/g,'')" value="<?php echo arr::get($companyinfo, "com_contact")?>" name="com_contact"/><span class="tipin"></span></div>
            <div class="clear"></div>
        </div>
        <div class="info">
            <div class="infoLeft"><strong>*</strong>公司性质：</div>
            <div class="infoRight"><!--<input type="radio" name="gsxz" class="gsxz" checked="checked"/><span class="gsbq">外资企业</span><input type="radio" name="gsxz" class="gsxz"/><span class="gsbq">国有企业</span><input type="radio" name="gsxz" class="gsxz"/><span class="gsbq">私营企业</span><input type="radio" name="gsxz" class="gsxz"/><span class="gsbq">个体经营</span><input type="radio" name="gsxz" class="gsxz"/><span class="gsbq">事业单位</span>-->
            <?php
                $soure = common::comnature();
                foreach ($soure as $k=>$v){
                    if ($k==arr::get($companyinfo,"com_nature"))
                        echo Form::radio("com_nature",$k,TRUE,array('class'=>'gsxz')).'<span class="gsbq">'.$v.'</span>';
                    else
                        echo Form::radio("com_nature",$k,FALSE,array('class'=>'gsxz')).'<span class="gsbq">'.$v.'</span>';
                }
            ?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="info">
            <div class="infoLeft pt"><strong>*</strong>公司成立时间：</div>
            <div class="infoRight"><input type="text" name="com_founding_time_year" class="text text2" value="<?php echo $com_founding_time_year?>" maxlength="4" onkeyup="this.value=this.value.replace(/[^0-9]/gi,'')" id="clYear"/> <span>年</span> <input type="text" name="com_founding_time_month" class="text text2" value="<?php echo $com_founding_time_month?>" maxlength="2" onkeyup="this.value=this.value.replace(/[^0-9]/gi,'')" id="clMoonth"/> <span>月</span><span class="tipin"></span>
            </div>
            <div class="clear"></div>
        </div>
        <div class="info">
            <div class="infoLeft pt"><strong>*</strong>公司注册资本：</div>
            <div class="infoRight">
            <input type="text" name="com_registered_capital" class="text text1" style="width:170px;" value="<?php echo arr::get($companyinfo,"com_registered_capital")?>" onkeyup="this.value=this.value.replace(/[^0-9]/gi,'')" maxlength="10" id="zczb"/> <span>万</span><span class="tipin"></span>
            </div>
            <div class="clear"></div>
        </div>
        <div class="info">
            <div class="infoLeft pt" >公司网址：</div>
            <div class="infoRight"><input type="text" class="text text3" maxlength="50" name="com_site" value="<?php echo arr::get($companyinfo, "com_site");?>" id="website"/><span class="tipin"></span></div>
            <div class="clear"></div>
        </div>
        <div class="info">
            <div class="infoLeft pt"><strong>*</strong>公司地址：</div>
            <div class="infoRight">
                <select class="" name="com_area" id="address">
                    <option>请选择地区</option>
                            <?php
                            if( !empty( $area ) ){
                                foreach ( $area as $v ){
                                ?>
                                    <option value="<?php echo $v['cit_id']?>" <?php if($v['cit_id']==$pro_id ){echo "selected='selected'";}?> ><?php echo $v['cit_name']?></option>
                                <?php
                                }
                            }
                            ?>
                </select>
                <select name="com_city" id="address1">
                    <option value="0">不选</option>
<?php
                                if( $areaIds!=0 && !empty($cityarea) ){
                                    foreach ($cityarea as $v){
                                ?>
                                    <option value="<?=$v->cit_id ;?>" <?php if($v->cit_id == $areaIds) echo "selected='selected'"; ?> ><?=$v->cit_name;?></option>
                                <?php
                                    }
                                }
                                ?>
                </select>
                <input type="text" class="text text3" maxlength="30" id="address_p" style="width:142px;" name="com_adress" value="<?php echo arr::get($companyinfo, "com_adress");?>"  placeholder="县级以下详细地址"/>
                <span class="tipin"></span>
            </div>
            <div class="clear"></div>
        </div>
        <div class="info">
            <div class="infoLeft pt"><strong>*</strong>公司简介：</div>
            <div class="infoRight">
            <?php
                echo  Editor::factory(arr::get($companyinfo,"com_desc"),"nobar",array("field_name"=> 'com_desc',"width"=>"340","height"=>"100"));
            ?>
            <span class="tipin" id="editor" style="padding-left:0;height:30px;line-height:30px;display:block;">&nbsp;</span>
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
</div>
</div>
</div>
<div class="clear"></div>
<script type="text/javascript">
$(function(){
    //座机号码
    $("#tel").focus(function(){
        $("#tel").siblings(".tipin").text("");
    });
    $("#tel").blur(function(){
        if($("#tel").val().length == 0){
            $("#tel").siblings(".tipin").text("请输入座机号码");
        }else if($("#tel").val().length < 5){
            $("#tel").siblings(".tipin").text("座机号码需大于5个字符");
        }else{
            $("#tel").siblings(".tipin").text("");
        }
    });
    //手机号码
    $("#mobile").focus(function(){
        $("#mobile").siblings(".tipin").text("");
    });
    $("#mobile").blur(function(){
        var mobile = /(^(13|14|15|18)\d{9}$)/;
        if($("#mobile").val().length == 0){
            $("#mobile").siblings(".tipin").text("请输入手机号码");
        }else if(!mobile.test($("#mobile").val())){
            $("#mobile").siblings(".tipin").text("请输入正确的手机号码");
        }else{
            $.ajax({
                 type: "post",
                 dataType: "json",
                 url: "/ajaxcheck/checkMobile",
                 data: "mobile="+$("#mobile").val(),
                 complete :function(){
                 },
                 success: function(data){
                    if(data.code == 500){
                        $("#mobile").siblings(".tipin").text("手机号已被绑定");
                    }else{
                        $("#mobile").siblings(".tipin").text("");
                    }
                 }
             });
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
    //公司地址
    $("#address_p").focus(function(){
        $("#address_p").siblings(".tipin").text("");
    });
    $("#address_p").blur(function(){
        if($("#address_p").val().length == 0){
            $("#address_p").siblings(".tipin").text("请输入公司地址");
            e=0;
        }else{
            $("#address_p").siblings(".tipin").text("");
            e=1;
        }
    });
    //提交表单验证
    var a,b,c,d,e,f,g,h,i;
    $("#companyedit").submit(function(){
	//公司名称
   	 //联系人
        if($("#com_name").val().length == 0){
            $("#com_name").siblings(".tipin").text("请输入公司名称");
            d=0;
        }else if($("#com_name").val().length > 20){
        	$("#com_name").siblings(".tipin").text("已超出限制范围,请保持在20个字数以内");
            d=0;
         }else{
            $("#com_name").siblings(".tipin").text("");
            d=1;
        }
        //公司logo
        if($("#imghead").css("display") == "none"){
            $("#imgd").siblings(".tipin").text("请选择公司logo");
            a=0;
        }else{
            $("#imgd").siblings(".tipin").text("");
            a=1;
        }
        //座机号码
        if($("#tel").val().length == 0){
            $("#tel").siblings(".tipin").text("请输入座机号码");
            b=0;
        }else if($("#tel").val().length < 5){
            $("#tel").siblings(".tipin").text("座机号码需大于5个字符");
            b=0;
        }else{
            $("#tel").siblings(".tipin").text("");
            b=1;
        }
        //手机号码
        if($("#mobile").attr("type") == "hidden"){
            c=1;
        }else{
            var mobile = /(^(13|14|15|18)\d{9}$)/;
            if($("#mobile").val().length == 0){
                $("#mobile").siblings(".tipin").text("请输入手机号码");
                c=0;
            }else if(!mobile.test($("#mobile").val())){
                $("#mobile").siblings(".tipin").text("请输入正确的手机号码");
                c=0;
            }else{
                $.ajax({
                     type: "post",
                     dataType: "json",
                     async:false,
                     url: "/ajaxcheck/checkMobile",
                     data: "mobile="+$("#mobile").val(),
                     complete :function(){
                     },
                     success: function(data){
                        if(data.code == 500){
                            $("#mobile").siblings(".tipin").text("手机号已被绑定");
                            c=0;
                        }else{
                            $("#mobile").siblings(".tipin").text("");
                            c=1;
                        }
                     }
                 });
            }
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
        //公司地址
        if($("#address_p").val().length == 0){
            $("#address_p").siblings(".tipin").text("请输入公司地址");
            e=0;
        }else{
            $("#address_p").siblings(".tipin").text("");
            e=1;
        }
        //公司网址
        if($("#website").val().length != 0){
            str = $("#website").val().match(/http:\/\/.+/);
            if(str){
                $("#website").siblings(".tipin").text("");
                f=1;
            }else{
                $("#website").siblings(".tipin").text("请输入正确的公司网址");
                f=0;
            }
        }else{
            f=1;
        }
        //成立时间
        var today = new Date();
        var clYear = today.getFullYear();
        var clMonth = today.getMonth()+1;
        if($("#clYear").val().length == 0 || $("#clMoonth").val().length == 0){
            $("#clYear").siblings(".tipin").text("请输入公司成立的年份和月份");
            g=0;
        }else if($("#clYear").val().length != 4){
            $("#clYear").siblings(".tipin").text("请输入正确的公司成立时间");
            g=0;
        }else{
            if($("#clYear").val() > clYear){
                $("#clYear").siblings(".tipin").text("公司成立的年份时间不能大于当前的时间");
                g=0;
            }else if($("#clYear").val() == clYear && $("#clMoonth").val() > clMonth){
                $("#clYear").siblings(".tipin").text("公司成立的年份时间不能大于当前的时间");
                g=0;
            }else{
                $("#clYear").siblings(".tipin").text("");
                g=1;
            }
        }
        //注册资本
        if($("#zczb").val().length == 0){
            $("#zczb").siblings(".tipin").text("请输入公司注册资本");
            h=0;
        }else{
            h=1;
        }
        if(editor.isEmpty()){
            $("#editor").text("请输入公司简介");
            i=0;
        }else{
            $("#editor").text(" ");
            i=1;
        }
        //数据不符阻止提交表单
        var total = a+b+c+d+e+f+g+h+i;
        if(total != 9){
            return false;
        }
    });

var isshow = "<?=isset($showqiye) ? $showqiye:'bukeyi';?>";
	if(isshow == 'keyi'){
		window.MessageBox({
    		title:"一句话网站提示您",
    		content:"<p class='reasonMsg'>你所提交的公司认证进入人工审核阶段，预计<font style='color: red; font-size: 14px;'>1</font>个工作日内完成审核，如有疑问请来电咨询<font> 400 1015 908</font></p>",
    		btn:"ok",
    		callback:function(){
    			window.location.href = $config.siteurl+"/quick/project/showProject/";
    		}
    	});
		return false;
	}
    
})
//上传图片
function viewImage1(_str){
    $("#imgd").siblings(".tipin").text("");
    $("#imghead").css('display', 'block');
    $("#imghead").attr('src', _str);
    $("#company_log").attr('value',_str);
    $("#uploadImg").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='112' height='32' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/uploadhead_person.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/uploadhead_person.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='112' height='32' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
}
</script>