<?php echo URL::webcss("common.css")?>
<?php echo URL::webjs("My97DatePicker1/WdatePicker.js")?>
<?php echo URL::webjs("my_business_infor.js")?>
<?php echo URL::webcss("renzheng.css"); ?>
<?php echo URL::webcss("postinvestment.css"); ?>
<?php echo URL::webjs("zhaos_box.js")?>

<script>
String.prototype.Trim = function(){return this.replace(/^\s+|\s+$/g,"");}
$(function(){
    init_big_img();
    $("#project_ids").change(function(){
        var project_id = $("#project_ids").attr("value");
        window.location.href="/company/member/project/addInvest?project_id="+project_id;
    })
    $(".my_business_infor .delete").eq(0).hide();
    $(".my_business_infor .delete").live("click",function(){
        var investBCount = 3;
        var investCount = 1;
        var length = investBCount;
        if(investCount >= 3) {
            length = investCount+1;
        }
        if(length >=5 ) {
            length = 5;
        }
        if($(".aa").length == 2){
            $(this).parent().parent().parent().remove();
            $(".my_business_infor .delete").hide();
        }else{
            $(this).parent().parent().parent().remove();
        }
        $(".my_business_infor .add").parent().parent().show();
        return false;
    })
    var add = '<div class="info_zs"><div class="a"><em>*</em>投资考察会时间：</div><div class="b"><p class="pb"><input type="text" class="text2" name="investment_start" value="" onclick="WdatePicker()"/>&nbsp;至&nbsp;<input type="text" class="text2" name="investment_end" value="" onclick="WdatePicker()"/></p></div><div class="clear"></div></div>';
    add += '<div class="info_zs"><div class="a"><em>*</em>投资考察会地址：</div><div class="b"><p><select  class="province" name="investment_province"><option value="">请选择省份</option><option value="1">广东</option><option value="2">北京</option><option value="3">天津</option><option value="4">河北</option><option value="5">山西</option><option value="6">内蒙古</option><option value="7">辽宁</option><option value="8">吉林</option><option value="9">黑龙江</option><option value="10">上海</option><option value="11">江苏</option><option value="12">浙江</option><option value="13">安徽</option><option value="14">福建</option><option value="15">江西</option><option value="16">山东</option><option value="17">河南</option><option value="18">湖北</option><option value="19">湖南</option><option value="20">广西</option><option value="21">海南</option><option value="22">重庆</option><option value="23">四川</option><option value="24">贵州</option><option value="25">云南</option><option value="26">西藏</option><option value="27">陕西</option><option value="28">甘肃</option><option value="29">青海</option><option value="30">宁夏</option><option value="31">新疆</option><option value="32">台湾</option><option value="33">香港</option><option value="34">澳门</option></select><select  class="city" name="investment_city"><option value="">请选择市区</option></select><input type="text" class="text1 address" maxlength="30" name="investment_address"/><a href="#" class="delete">删除此投资考察会场次</a></p></div><div class="clear"></div></div>';
    $(".my_business_infor .add").click(function(){
        var investBCount = 3;
        var investCount = 1;
        var length = investBCount;
        if(investCount >= 3) {
            length = investCount+1;
        }
        if($(".aa").length < length){
            len_str ++;
            $(this).parent().parent().before(add);
            if($(".aa").length == length){
                $(this).parent().parent().hide();
            }
            $(".my_business_infor .delete").show();
        }
        return false;
    });
    $(".imgs a").live("click",function(){
        $(".imgs").css("display","none");
        $(".uploadImg").show();
        return false;
    });
    $(".province").live("change",function(){
        var aa = $(this);
        var parent_id= $(this).attr("value");
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/ajaxcheck/getArea",
            data: "parent_id="+parent_id,
            complete :function(){
            },
            success: function(msg){
                var count = msg.length;
                if(count > 0){
                    var content ="<option value=''>请选择市区</option>";
                    for(i=0;i<count;i++){
                        content+='<option value="'+msg[i]['cit_id']+'">'+msg[i]['cit_name']+'</option>';
                    }
                    aa.siblings(".city").html(content);
                }
            }
        });
    })
    var arr = [];
    var jia = <?=($type == 2) ? 0 : 1?>;
    var len_str = <?=$investCount?>+jia;

//表单验证
    var release_project_money_flag = false;
    $("form").live("submit",function(){
        formInput();
        total = 0;
        for(var i =0;i<arr.length;i++){
            if(arr[i])total = arr[i] * 1 + total;
        }
        if( total != 11){
            return false;
        }else{
        }
        var invest_count = $("#invest_count").val();
        var invest_b_count = $("#invest_b_count").val();
        if(invest_count >= invest_b_count && jia == 1) {
            if(release_project_money_flag) {
                return true;
            }
            $("#getcards_opacity").show();
            $("#project_err_2").show();
            return false;
        }
    });
    $("#jx_submit").click(function(){
        release_project_money_flag = true;
        $("form")[0].submit();
    });
    var isPhone = /^1[3|4|5|8][0-9]\d{4,8}$/;
    var re = new RegExp(isPhone);
    function formInput(){


        $(".info_zs .b span.tishiMsg").html("");

        if($("#project_ids").val().Trim() == ""){
            $(".tishi21").text("请选择招商项目");
            $("#project_ids").focus();
            arr[0] = 0;
            return false;
        }else{
            $(".tishi21").text("");
            arr[0] = 1;
        }
        if($("#invest_name").val().Trim()==""){
            $(".tishi1").text("投资考察会标题不能为空");
            $("#invest_name").focus();
            arr[1] = 0;
            return false;
        }else{
            $(".tishi1").text("");
            arr[1] = 1;
        }

        var logo="";

        var img_logo = document.getElementById('imgd');

        if($(".imgs").is(":visible") == false)
        {

            $(".tishi4").text("请选择您需要上传的投资考察会图片");
            $("#comBtn").focus();
            arr[4] = 0;
            return false;
        }else{

            $(".tishi4").text("");
            arr[4] = 1;
        }
        var aaa = 0

        $(".info_zs p.pb").each(function(index, val){
            var firstDate = $(this).find("input[type='text']:first").val();
            var lastDate = $(this).find("input[type='text']:last").val();
            if(firstDate == ""){
                $(".tishi_time").text("投资考察会时间不能为空");
                $(this).find("input[type='text']:first").focus();
                arr[8] = 0;
                aaa = 8;
                return false;
            }
            if(lastDate == ""){
                $(".tishi_time").text("投资考察会时间不能为空");
                $(this).find("input[type='text']:last").focus();
                arr[8] = 0;
                aaa = 8;
                return false;
            }
            firstDate = firstDate.split("-");
            lastDate = lastDate.split("-");
            var startDate = new Date(firstDate[0], firstDate[1], firstDate[2]);
            var endDate = new Date(lastDate[0], lastDate[1], lastDate[2]);
            if(startDate > endDate){
                $(".tishi5").text("投资考察会后面的时间不能小于前面的时间");
                $(this).find("input[type='text']:first").focus();
                arr[8] = 0;
                aaa = 8;
                return false;
            }else{
                $(".tishi5").text("");
                arr[8] = 1;
            }
        });
        if(aaa == 8){
            return false;
        }
        $(".info_zs_address select").each(function(){
            if($(this).val() == ""){
                $(".tishi5").text("请选择投资考察会地址");
                $(this).focus();
                aaa = 1;
                arr[9] = 0;
                return false;
            }else{
                arr[9] = 1;
            }
        })
        if(aaa == 1){
            return false;
        }
        $(".info_zs_address input.address").each(function(){
            $(".tishi5").text("");
            arr[7] = 1;
        })
        if(aaa == 2){
            return false;
        }
        $(".info_zs_address input.address").each(function(){
            if($(this).val().length > 30){
                $(".tishi5").text("投资考察会详细地址不能超过30个字符");
                $(this).focus();
                aaa = 3;
                arr[10] = 0;
                return false;
            }else{
                $(".tishi5").text("");
                arr[10] = 1;
            }
        })
        if(aaa == 3){
            return false;
        }

        if(editors.isEmpty()){
            $(".tishi6").text("投资考察会详情不能为空");
            arr[5] = 0;
            return false;
        }else{
            $(".tishi6").text("");
            arr[5] = 1;
        }
        if(editors_1.isEmpty()){
            $(".tishi7").text("投资考察会流程不能为空");
            arr[6] = 0;
            return false;
        }else{
            $(".tishi7").text("");
            arr[6] = 1;
        }
        if($("#com_name").val().Trim()==""){
            $(".tishi2").text("投资考察会联系人不能为空");
            $("#com_name").focus();
            arr[2] = 0;
            return false;
        }else{
            $(".tishi2").text("");
            arr[2] = 1;
        }
        if($("#com_phone").val()==""){
            $(".tishi3").text("投资考察会联系电话不能为空");
            $("#com_phone").focus();
            arr[3] = 0;
            return false;
        }
        else if(re.test($("#com_phone").val())){
            $(".tishi3").text("");
            arr[3] = 1;
        }else{
            $(".tishi3").text("投资考察会联系电话格式不正确");
            $("#com_phone").focus();
            arr[3] = 0;
            return false;
        }


    }

})
function viewImage1(_str){
    $("#imghead").attr('src', _str);
    $("#investment_logo").attr('value',_str);
    $(".imgs").show();
    $(".uploadImg").css("display","none");
}
</script>

<div class="right">
<h2 class="user_right_title"><span><?=($type==2)? '编辑' : '发布';?>投资考察会</span><div class="clear"></div></h2>
<div class="my_business_infor">
<div class="cantext">
<div class="tishi huiyiinfo">会议基本信息</div>
<div class="my_business_infor">
<div class="cantext">
    <form action="/company/member/project/addproinvestment" method="post" enctype="multipart/form-data" id="formStyle">
        <input type="hidden" name="project_id" id="project_id" value="<?=arr::get($forms, 'project_id')?>">
        <input type="hidden" id="invest_count" value="<?=$investCount?>">
        <input type="hidden" id="invest_b_count" value="<?=$investBCount?>">
        <div class="info_zs">
            <div class="a"><em>*</em>选择项目：</div>
            <div class="b">
                <select name="project_id" id="project_ids" style="border: 1px solid #DBDBDB;height: 30px;line-height:30px;width: 258px;" <?php if($type==2) echo "disabled";?>>
                    <option value="">请选择</option>
                    <?php foreach($projects as $p){?>
                        <option value="<?=$p->project_id?>" <?php if(arr::get($forms, 'project_id')==$p->project_id) echo "selected='selected'";?>><?=$p->project_brand_name?></option>
                    <?php }?>
                </select>
                <span class="tishi21 c999">请选择您要召开投资考察会的项目</span><br><?=$msg?>
                <span class="tishi21 tishiMsg" style="color:red;"></span>
            </div>
            <div class="clear"></div>
        </div>
        <div class="info_zs">
            <div class="a"><em>*</em>会议标题：</div>
            <div class="b">
                <input type="text" class="text1 zs_title" name="investment_name" value="<?php if($type==2) echo arr::get($forms, 'investment_name')?>" value="" maxlength="20" style="width:255px;" id="invest_name">
                <span class="c999">请填写您要召开投资考察会的标题</span>
                <br><span class="tishi1 tishiMsg" style="color:red;"></span>
            </div>
            <div class="clear"></div>
        </div>
        <div class="info_zs">
            <div class="a"><em>*</em>宣传图片：</div>
            <div class="b" id="invest_logo">
                <?php if(!empty($forms['investment_logo']) && $type==2){?>
                    <span class="uploadImg" id="uploadImg">
                                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="112" height="32" id="flashrek2">
                                    <param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_zsh.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" />
                                    <param name="quality" value="high" />
                                    <param name="wmode" value="transparent" />
                                    <param name="allowScriptAccess" value="always" />
                                    <embed src="<?php echo URL::webstatic('/flash/uploadhead_zsh.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                                </object>
                                <span class="c999" style="float: right; margin-right: 150px; line-height: 30px; width: 302px;">1M以内，尺寸160px*120px，了解图用途，请查阅       <a class="zhaos_big_img" href="<?php echo URL::webstatic('images/touzi/shili.png')?>" target="blank" >示例</a></span>

                                </span>
                    
                    <div class="imgs" style="">
                        <div id="imgd"><img src="<?php echo URL::imgurl(arr::get($forms, 'investment_logo'));?>" id="imghead"/><input type="hidden" name="investment_logo" id="investment_logo" value="<?php echo $forms['investment_logo']; ?>"></div>
                        <embed src="<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash">
                        <span class="c999" style="float: right; margin-right: 150px; line-height: 30px; width: 302px;">1M以内，尺寸160px*120px，了解图用途，请查阅       <a class="zhaos_big_img" href="<?php echo URL::webstatic('images/touzi/shili.png')?>" target="blank" >示例</a></span><br>
                    </div>

                <?php }else{?>
                    <span class="uploadImg" id="uploadImg" style="display:block;">
                                 <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="112" height="32" id="flashrek2">
                                     <param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_zsh.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" />
                                     <param name="quality" value="high" />
                                     <param name="wmode" value="transparent" />
                                     <param name="allowScriptAccess" value="always" />
                                     <embed src="<?php echo URL::webstatic('/flash/uploadhead_zsh.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                                 </object>
                                 <span class="c999" style=" width: 302px;float: right; margin-right: 150px; line-height: 30px;">1M以内，尺寸160px*120px，了解图用途，请查阅       <a class="zhaos_big_img" href="<?php echo URL::webstatic('images/touzi/shili.png')?>" target="blank" >示例</a></span>

                                </span>
                    <div class="imgs" style="display:none;">
                        <div id="imgd"><img src="<?php echo URL::imgurl(arr::get($forms, 'investment_logo'));?>" id="imghead"/><input type="hidden" name="investment_logo" id="investment_logo"></div>
                        <embed src="<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash">
                            <span class="c999" style=" width: 302px;float: right; margin-right: 150px; line-height: 30px;">1M以内，尺寸160px*120px，了解图用途，请查阅       <a class="zhaos_big_img" href="<?php echo URL::webstatic('images/touzi/shili.png')?>" target="blank" >示例</a></span><br>
                    </div>
                    
                <?php }?>
                <span class='tishi4 tishiMsg' style='color:red;'></span>
            </div>
            <div class="clear"></div>
        </div>
        <?php if(!empty($invests)){ ?>
        <div class="info_zs">
            <div class="a"><em>*</em>会议日期：</div>
            <div class="b"><p class="pb">
                    <input type="text" class="text2" name="investment_start" value="<?php if($type==2) echo date('Y-m-d',$invests['investment_start'])?>" onclick="WdatePicker()" placeholder="开始时间">&nbsp;至&nbsp;
                    <input type="text" class="text2" name="investment_end" value="<?php if($type==2) echo date('Y-m-d',$invests['investment_end'])?>" placeholder="结束时间" onclick="WdatePicker()"></p>
                <p class="c999">请选择您要召开投资考察会的时间；如会议召开日期为一天，开始时间与结束时间请选择同一日期</p>
                
            </div>
            <div class="clear"></div>
        </div>
        <div class="info_zs info_zs_address">
            <div class="a"><em>*</em>会议地点：</div>
            <div class="b">
                <p><select class="province" name="investment_province"><option value="">请选择省份</option><?php foreach ($area as $a){?><option value='<?=$a['cit_id']?>' <?php if($a['cit_id']==$invests['investment_province'] && $type==2){echo "selected='selected'";}?>><?=$a['cit_name']?></option><?php }?></select>
                    <select class="city" name="investment_city"><option value="">请选择市区</option><?php foreach ($city[$invests['investment_province']] as $c){?><option value='<?=$c['cit_id']?>' <?php if($c['cit_id']==$invests['investment_city'] && $type==2){echo "selected='selected'";}?>><?=$c['cit_name']?></option><?php }?></select>
                    <input type="text" class="text1 address" name="investment_address" value="<?php if($type==2) echo $invests['investment_address']?>"/>
                    <?if($type == 2) {?>
                        <input type="hidden" name="investment_id" value="<?=$invests['investment_id']?>">
                    <? } ?>
                </p>
                <p class="c999">请选择或填写您要召开投资考察会的地址</p>
                <span class="tishi_time tishiMsg tishi5" style="color:red;"></span>
            </div>
            <div class="clear"></div>
        </div>
        <?}else{?>
            <div class="info_zs">
                <div class="a"><em>*</em>会议日期：</div>
                <div class="b"><p class="pb"><input type="text" class="text2" name="investment_start" value="" onclick="WdatePicker()" placeholder="开始时间"/>&nbsp;至&nbsp;<input type="text" class="text2" name="investment_end" value="" placeholder="结束时间" onclick="WdatePicker()"/></p>
                    <p class="c999">请选择您要召开投资考察会的时间；如会议召开日期为一天，开始时间与结束时间请选择同一日期</p>
                    <span class='tishi_time tishi5 tishiMsg' style='color:red;'></span>
                </div>
                <div class="clear"></div>
            </div>
            <div class="info_zs info_zs_address">
                <div class="a"><em>*</em>会议地点：</div>
                <div class="b">
                    <p><select class="province" name="investment_province"><option value="">请选择省份</option><?php foreach ($area as $a){?><option value='<?=$a['cit_id']?>' /><?=$a['cit_name']?></option><?php }?></select>
                        <select class="city" name="investment_city"><option value="">请选择市区</option></select>
                        <input type="text" class="text1 address" name="investment_address" value=""/>
                    </p>
                    <p class="c999">请选择或填写您要召开投资考察会的地址</p>
                </div>
                <div class="clear"></div>
            </div>
        <?php }?>
        <div class="info_zs">
            <div class="a"><em>*</em>公司统一安排住宿：</div>
            <div class="b"><input type="radio" name='putup_type' class="as" value="1" <?php if(arr::get($forms, 'putup_type') !=2){?> checked ="checked"<?php }?> />&nbsp;是&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name='putup_type' class="as" value="2" <?php if(arr::get($forms, 'putup_type') ==2){?> checked ="checked"<?php }?>/>&nbsp;否</div>
            <div class="clear"></div>
        </div>

        <div class="tishi huiyiinfo">会议更多信息</div>

        <div class="info_zs">
            <div class="a"><em>*</em>会议详情：</div>
            <div class="b">
                <?php echo  Editor::factory(htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($forms, 'investment_details'), 0))),"nobars",array("field_name"=> 'investment_details',"width"=>"580","height"=>"200","id"=>"detail"));?>
                <span class="c999">介绍此投资考察会的起源、特点、优势、能给投资者带来怎样的收益等</span><br>
                <span class='tishi6 tishiMsg' style='color:red;'></span></div>
            <div class="clear"></div>
        </div>
        <div class="info_zs">
            <div class="a"><em>*</em>会议流程：</div>
            <div class="b"><?php echo  Editor::factory(htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($forms, 'investment_agenda'), 0))),"nobars_1",array("field_name"=> 'investment_agenda',"width"=>"580","height"=>"200","id"=>"agenda"));?>
                <span class="c999">介绍此投资考察会的可以给予投资者的优惠事项</span><br>
                <span class='tishi7 tishiMsg' style='color:red;'></span></div>
            <div class="clear"></div>
        </div>

        <div class="info_zs">
            <div class="a">参会优惠政策：</div>
            <div class="b"><?php echo  Editor::factory(htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($forms, 'investment_preferential'), 0))),"nobar",array("field_name"=> 'investment_preferential',"width"=>"580","height"=>"200","id"=>"preferential"));?>
                <span class="c999">介绍此投资考察会的可以给予投资者的优惠事项</span>
            </div>
            <div class="clear"></div>
        </div>

    </div>
<div class="tishi huiyiinfo">会议联系人</div>
<div class="info_zs">
    <div class="a"><em>*</em>姓名：</div>
    <div class="b">
        <input type="text" style="width:154px" class="text1" maxlength="12" name="com_name"  value="<?=arr::get($forms, 'com_name')?>" id="com_name"/><br>
        <span class='tishi2 tishiMsg' style='color:red;'></span></div>
    <div class="clear"></div>
</div>
<div class="info_zs">
    <div class="a"><em>*</em>手机号码：</div>
    <div class="b">
        <input type="text" class="text1" name="com_phone" value="<?=arr::get($forms, 'com_phone')?>" id="com_phone"/><span class="c999">&nbsp;&nbsp;&nbsp;&nbsp;方便投资者和我们随时取得联系</span><br>
        <span class='tishi3 tishiMsg' style='color:red;'></span></div>
    <div class="clear"></div>
</div>
<div class="info_zs">
    <div class="a">公司座机号码：</div>
    <div class="b">
        <input type="text" class="text1" style="width:154px" name="telephone" value="<?=arr::get($forms, 'telephone')?>" >&nbsp;转：&nbsp;
        <input type="text" class="text1" style="width:154px" name="extension" value="<?=arr::get($forms, 'extension')?>">
        <br>
    </div>
    <div class="clear"></div>
</div>
<div class="sq">
    <input type="image" src="<?php echo URL::webstatic('images/touzi/subimg.png')?>"></div>
</div>
</form>
<!--主体部分结束-->
</div>
</div>
</div>

<div id="project_err_2" class="message_box" style="display:
<?php if($investCount>=$investBCount && arr::get($forms, 'project_id') && $investment_id==0 && ($account_test['is_forbid'] || $account_test['type'] == "2")){echo 'block';}else{echo 'none';} ?>;
    margin-top:-115px; ">
    <dl>
        <dt>
            <font>生意街提示您</font>
            <a class="close" href="/company/member/project/addinvest" title="关闭"></a>
        </dt>
        <dd>
            <p><?=$account_test['message']?></p>
        </dd>
        <dd class="btn">
            <?php if($account_test['is_forbid']){?>
            <a href="/company/member/project/addinvest" class="cancel">取消</a>
            <?php }elseif($account_test['type'] == "2"){?>
            <a href="/company/member/account/accountindex" class="cancel">去充值</a>
            <a href="/company/member/project/addinvest" class="cancel">取消</a>
            <?php }else{?>
            <a href="javascript:void(0)" id="jx_submit" tel="" class="ok">继续发布</a>
            <a href="/company/member/account/accountindex" class="cancel">去充值</a>
            <?php }?>
        </dd>
    </dl>
</div>

<div id="getcards_opacity" style="display:<?php if($investCount>=$investBCount && arr::get($forms, 'project_id') &&  $investment_id==0 && ($account_test['is_forbid'] || $account_test['type'] == "2")){echo 'block';}else{echo 'none';} ?>"></div>

