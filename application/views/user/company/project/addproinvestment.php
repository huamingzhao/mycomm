<?php echo URL::webjs("My97DatePicker1/WdatePicker.js")?>
<?php echo URL::webjs("my_business_infor.js")?>
<?php echo URL::webcss("renzheng.css"); ?>
 <style>
 .my_business_infor .nav li{width:147px;}
 </style>

 <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span><a href="/company/member/project/showproject">项目管理</a></span><div class="clear"></div></h2>
                    <div class="ryl_add_project"><b><?=arr::get($project_info, 'project_brand_name')?></b>
                     <?php if($project_info['project_status'] !=0){?>
                    <a href="<?php echo url::site("/company/member/project/addproject");?>">添加新项目</a><?php }?></div>
                    <div class="my_business_infor">
                        
                        <div class="cantext">
                            <div class="tishi">（<em>*</em>为必填项）</div>
                              <form action="/company/member/project/addproinvestment"  method="post" enctype="multipart/form-data" id="formStyle">
                            <input type="hidden" name="project_id" id="project_id" value="<?=arr::get($forms, 'project_id')?>" />
                            <div class="info_zs">
                                <div class="a"><em>*</em>投资考察会标题：</div>
                                <div class="b"><input type="text" class="text1 zs_title" name="investment_name" value="<?=arr::get($forms, 'investment_name')?>" maxlength="20" id="invest_name"/><br><span class='tishi1' style='color:red;'></span></font></div>
                                <div class="clear"></div>
                            </div>
                            <div class="info_zs">
                                <div class="a"><em>*</em>投资考察会联系人：</div>
                                <div class="b"><input type="text" class="text1" maxlength="12" name="com_name"  value="<?=arr::get($forms, 'com_name')?>" id="com_name"/><br><span class='tishi2' style='color:red;'></span></div>
                                <div class="clear"></div>
                            </div>
                            <div class="info_zs">
                                <div class="a"><em>*</em>投资考察会电话：</div>
                                <div class="b"><input type="text" class="text1" name="com_phone" value="<?=arr::get($forms, 'com_phone')?>" id="com_phone"/><br><span class='tishi3' style='color:red;'></span></div>
                                <div class="clear"></div>
                            </div>
                            <div class="info_zs">
                                <div class="a"><em>*</em>投资考察会图片：</div>
                                <div class="b" id="invest_logo">
                                <?php if(!empty($forms['investment_logo'])){?>
                                <span class="uploadImg" id="uploadImg">
                                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="112" height="32" id="flashrek2">
                                <param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_zsh.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" />
                                <param name="quality" value="high" />
                                <param name="wmode" value="transparent" />
                                <param name="allowScriptAccess" value="always" />
                                <embed src="<?php echo URL::webstatic('/flash/uploadhead_zsh.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                                </object>
                                </span>
                                    <div class="imgs">
                                        <div id="imgd"><img src="<?php echo URL::imgurl(arr::get($forms, 'investment_logo'));?>" id="imghead"/><input type="hidden" name="investment_logo" id="investment_logo"></div>
                                        <a href="#">删除</a>
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
                                </span>
                                <div class="imgs" style="display:none;">
                                        <div id="imgd"><img src="<?php echo URL::imgurl(arr::get($forms, 'investment_logo'));?>" id="imghead"/><input type="hidden" name="investment_logo" id="investment_logo"></div>
                                        <a href="#">删除</a>
                                    </div>
                                <?php }?>
                                <span class='tishi4' style='color:red;'></span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <?php
                            if(!empty($invests)){
                            foreach ($invests as $v){
                            ?>
                            <div class="info_zs aa">
                                <div class="a"><em>*</em>投资考察会时间/地址：</div>
                                <div class="b"><p class="pb"><input type="text" class="text2" name="investment_start[]" value="<?php echo date('Y-m-d',$v->investment_start)?>" onclick="WdatePicker()"/>&nbsp;至&nbsp;<input type="text" class="text2" name="investment_end[]" value="<?php echo date('Y-m-d',$v->investment_end)?>" onclick="WdatePicker()"/><span>(如只召开一天，截止日期写当天即可，如2013/01/01-2013/01/01)</span></p>
                                <p><select class="province" name="investment_province[]"><option value="">请选择省份</option><?php foreach ($area as $a){?><option value='<?=$a['cit_id']?>' <?php if($a['cit_id']==$v->investment_province){echo "selected='selected'";}?>><?=$a['cit_name']?></option><?php }?></select>
                                <select class="city" name="investment_city[]"><option value="">请选择市区</option><?php foreach ($city[$v->investment_province] as $c){?><option value='<?=$c['cit_id']?>' <?php if($c['cit_id']==$v->investment_city){echo "selected='selected'";}?>><?=$c['cit_name']?></option><?php }?></select>
                                <input type="text" class="text1" name="investment_address[]" placeholder="<?=$v->investment_address?>"/>
                                <input type="hidden" name="investment_id[]" value="<?=$v->investment_id?>">
                                <a href="#" class="delete">删除此投资考察会场次</a></p></div>
                                <div class="clear"></div>
                            </div>
                            <?php }}else{?>
                             <div class="info_zs aa">
                                <div class="a"><em>*</em>投资考察会时间/地址：</div>
                                <div class="b"><p class="pb"><input type="text" class="text2" name="investment_start[]" value="" onclick="WdatePicker()"/>&nbsp;至&nbsp;<input type="text" class="text2" name="investment_end[]" value="" onclick="WdatePicker()"/><span>(如只召开一天，截止日期写当天即可，如2013/01/01-2013/01/01)</span></p>
                                <p><select class="province" name="investment_province[]"><option value="">请选择省份</option><?php foreach ($area as $a){?><option value='<?=$a['cit_id']?>' /><?=$a['cit_name']?></option><?php }?></select>
                                <select class="city" name="investment_city[]"><option value="">请选择市区</option></select>
                                <input type="text" class="text1 address" name="investment_address[]" value=""/>
                                <a href="#" class="delete">删除此投资考察会场次</a></p></div>
                                <div class="clear"></div>
                            </div>
                            <?php }?>
                            <div class="info_zs">
                                <div class="a"></div>
                                <div class="b"><a href="#" class="add">增加投资考察会场次</a></div>
                                <span class='tishi5' style='color:red;'></span>
                                <span class='tishi8' style='color:red;'></span>
                                <div class="clear"></div>
                            </div>
                            <div class="info_zs">
                                <div class="a"><em>*</em>投资考察会详情：</div>
                                <div class="b">
                                <?php echo  Editor::factory(htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($forms, 'investment_details'), 0))),"nobars",array("field_name"=> 'investment_details',"width"=>"580","height"=>"200","id"=>"detail"));?><br><span class='tishi6' style='color:red;'></span></div>
                                <div class="clear"></div>
                            </div>
                            <div class="info_zs">
                                <div class="a"><em>*</em>投资考察会流程说明：</div>
                                <div class="b"><?php echo  Editor::factory(htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($forms, 'investment_agenda'), 0))),"nobars_1",array("field_name"=> 'investment_agenda',"width"=>"580","height"=>"200","id"=>"agenda"));?><br><span class='tishi7' style='color:red;'></span></div>
                                <div class="clear"></div>
                            </div>
                            <div class="info_zs">
                                <div class="a">投资考察优惠政策：</div>
                                <div class="b"><?php echo  Editor::factory(htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($forms, 'investment_preferential'), 0))),"nobar",array("field_name"=> 'investment_preferential',"width"=>"580","height"=>"200","id"=>"preferential"));?></div>
                                <div class="clear"></div>
                            </div>
                            <div class="info_zs">
                                <div class="a"><em>*</em>公司统一安排住宿：</div>
                                <div class="b"><input type="radio" name='putup_type' class="as" value="1" <?php if(arr::get($forms, 'putup_type') !=2){?> checked ="checked"<?php }?> />&nbsp;是&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name='putup_type' class="as" value="2" <?php if(arr::get($forms, 'putup_type') ==2){?> checked ="checked"<?php }?>/>&nbsp;否</div>
                                <div class="clear"></div>
                            </div>
                        </div>
                        <div class="sq">
                        <?php if($project_status == 0){?>
                        <input type="image" src="<?php echo URL::webstatic('images/my_business_infor/btn_a.gif');?>" />
                         <a href="/company/member/project/submitProject?project_id=<?=arr::get($forms, 'project_id')?>"><img src="<?php echo URL::webstatic("/images/my_business_infor/btn_b.gif") ?>" width="156" height="32"  border="0"/></a>
                         <?php }else{?>                         
                          <input type="image" src="<?php echo URL::webstatic('images/infor2/btn3.gif');?>" />                          
                         <?php }?>
                        </div>
                        </form>
                    </div>
                </div>
                <!--主体部分结束-->
<script>
$(function(){
if($(".zs_title").val() == "" || $(".zs_title").val() == null || $(".zs_title").val() == "请填写投资考察会标题，20个字符以内"){
    $(".zs_title").val("请填写投资考察会标题，20个字符以内").addClass("col").css("color","#bcbcbc");
}else{
    $(".zs_title").removeClass("col").css("color","#000");
}
$(".zs_title").focus(function(){
    if($(".zs_title").val() == "请填写投资考察会标题，20个字符以内"){
        $(".zs_title").val("");
    }
    $(this).removeClass("col").css("color","#000");
});
$(".zs_title").blur(function(){
        if($(".zs_title").val() == ""){
            $(".zs_title").val("请填写投资考察会标题，20个字符以内");
            $(".zs_title").addClass("col").css("color","#bcbcbc");
        }else if($(".zs_title").val() == "请填写投资考察会标题，20个字符以内"){
            $(".zs_title").addClass("col").css("color","#bcbcbc");
        }else{
            $(".zs_title").removeClass("col").css("color","#000");
        }
    })
$(".my_business_infor .delete").eq(0).hide();
$(".my_business_infor .delete").live("click",function(){
    if($(".aa").length == 2){
        $(this).parent().parent().parent().remove();
        $(".my_business_infor .delete").hide();
    }else{
        $(this).parent().parent().parent().remove();
    }
    $(".my_business_infor .add").parent().parent().show();
    return false;
})
var add = '<div class="info_zs aa"><div class="a"><em>*</em>投资考察会时间/地址：</div><div class="b"><p class="pb"><input type="text" class="text2" name="investment_start[]" value="" onclick="WdatePicker()"/>&nbsp;至&nbsp;<input type="text" class="text2" name="investment_end[]" value="" onclick="WdatePicker()"/><span>(如只召开一天，截止日期写当天即可，如2013/01/01-2013/01/01)</span></p><p><select  class="province" name="investment_province[]"><option value="">请选择省份</option><?php foreach ($area as $a){?><option value="<?=$a['cit_id']?>"><?=$a['cit_name']?></option><?php }?></select><select  class="city" name="investment_city[]"><option value="">请选择市区</option></select><input type="text" class="text1 address" maxlength="30" name="investment_address[]"/><a href="#" class="delete">删除此投资考察会场次</a></p></div><div class="clear"></div></div>';
$(".my_business_infor .add").click(function(){
    if($(".aa").length < 6){
        $(this).parent().parent().before(add);
        if($(".aa").length == 5){
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
//表单验证
$("form").live("submit",function(){
    formInput();
    total = 0;
    for(var i =0;i<arr.length;i++){
        total = arr[i] * 1 + total;
    }
    if( total != 10){
        return false;
    }
});
var isPhone = /^\d{3,4}-?\d{3,11}-?\d{0,5}$/;
var re = new RegExp(isPhone);
function formInput(){
    if($("#invest_name").val().Trim()==""||$("#invest_name").val()=="请填写投资考察会标题，20个字符以内"){
         $(".tishi1").text("投资考察会标题不能为空");
         $("#invest_name").focus();
         arr[0] = 0;
         return false;
     }else{
         $(".tishi1").text("");
         arr[0] = 1;
     }
     if($("#com_name").val().Trim()==""){
         $(".tishi2").text("投资考察会联系人不能为空");
         $("#com_name").focus();
         arr[1] = 0;
         return false;
     }else{
         $(".tishi2").text("");
        arr[1] = 1;
     }
     if(re.test($("#com_phone").val())){
            $(".tishi3").text("");
            arr[2] = 1;
        }else{
            $(".tishi3").text("投资考察会联系电话格式不正确");
            $("#com_phone").focus();
            arr[2] = 0;
            return false;
    }
        var logo="<?=arr::get($forms, 'investment_logo')?>";

        var img_logo = document.getElementById('imgd');

        if($(".imgs").is(":visible") == false)
        {

            $(".tishi4").text("请选择您需要上传的投资考察会图片");
            $("#comBtn").focus();
            arr[3] = 0;
            return false;
        }else{

            $(".tishi4").text("");
            arr[3] = 1;
        }
        var aaa = 0
    $(".aa").each(function(){
        if($(this).find(".text2").eq(0).val()>$(this).find(".text2").eq(1).val()){
            $(".tishi5").text("投资考察会后面的时间不能小于前面的时间");
            $(this).focus();
            aaa = 8;
            arr[7] = 0;
            return false;
        }else{
            $(".tishi5").text("");
            arr[7] = 1;
        }
    });
        if(aaa == 8){
            return false;
        }
     $(".aa select").each(function(){
        if($(this).val() == ""){
            $(".tishi5").text("请选择投资考察会地址");
            $(this).focus();
            aaa = 1;
            arr[8] = 0;
            return false;
        }else{
            $(".tishi5").text("");
            arr[8] = 1;
        }
    })
    if(aaa == 1){
        return false;
    }
    $(".aa input").each(function(){
        if($(this).val().Trim() == ""){
            $(".tishi5").text("投资考察会详细地址和时间不能为空");
            aaa = 2;
            arr[6] = 0;
            return false;
        }else{
            $(".tishi5").text("");
            arr[6] = 1;
        }
    })
    if(aaa == 2){
        return false;
    }
    $(".aa input").each(function(){
        if($(this).val().length > 30){
            $(".tishi5").text("投资考察会详细地址不能超过30个字符");
            aaa = 3;
            arr[9] = 0;
            return false;
        }else{
            $(".tishi5").text("");
            arr[9] = 1;
        }
    })
    if(aaa == 3){
        return false;
    }

    if(editors.isEmpty()){
        $(".tishi6").text("投资考察会详情不能为空");
        arr[4] = 0;
        return false;
    }else{
        $(".tishi6").text("");
        arr[4] = 1;
    }
    if(editors_1.isEmpty()){
        $(".tishi7").text("投资考察会流程不能为空");
        arr[5] = 0;
        return false;
    }else{
        $(".tishi7").text("");
        arr[5] = 1;
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