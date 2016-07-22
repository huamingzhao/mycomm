<?php echo URL::webcss("userzhanhui.css")?>
<div class="right">
	 <h2 class="fz14 mt30 ml10">1、 下载安装客服端</h2>
    <div class="mt20  ml40">
        <p><b>客服端下载地址：</b><a target="_blank" href="http://download.webim-aone.tonglukuaijian.com/">http://download.webim-aone.tonglukuaijian.com/</a></p>
        <p>支持pc端、ios客户端、android客户端</p>
    </div>
     <h2 class="fz14 mt30 ml10">2、提交在线沟通所需信息</h2>
     <div class="mt20 ml40">
        <p>最多可以申请<b style="color:red;"> 5 </b>个客服同时在线沟通，帮助投资者答疑解惑；</p>
        <p>审核通过后，一个月只有一次修改机会，如有紧急问题，请咨询在线沟通服务热线: <b style="color:red;">400 1015 908</b></p>
    </div>
    <FORM action="/company/member/exhb/DoUpdateCommunication" method="post" id="formid">
    <div class="kefubox ml40 mt50">
    <input type="hidden" name="com_id" value="<?=$com_id;?>"/>
     <input type="hidden" name="customer_id" value="<?=arr::get($arr_data, "customer_id");?>"/>
    
    <input type="hidden" name="customer_info_id" value="<?=arr::get($arr_data, "customer_info_id");?>"/>
         <div class="kefutitlebox"><span>客服<i><?=isset($kefu) ? $kefu : 1;?></i>：</span></div>
        <div class="formbox">
            <p><span><var>*</var>客服帐号：</span><input type="text" class="kefu" value="<?=arr::get($arr_data, "customer_account")?>" onkeyup="this.value=this.value.replace(/[^0-9a-z]+/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9a-z]+/g,'')" name="customer_info_account" maxlength="10"><span class="c999">登录账号/工号，小写字母或者数字，最多支持10个字符长度</span></p>
            <p><span class="wt70"></span><span class="error"></span></p>
            <p><span><var>*</var>客服密码：</span><input type="text" value="<?=arr::get($arr_data, "customer_password"); ?>" onkeyup="this.value=this.value.replace(/[^0-9a-z_]+/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9a-z_]+/g,'')"name="customer_info_password" maxlength="10"><span class="c999">登录密码，小写字母、下划线加数字，最多支持10个字符长度</span></p>
            <p><span class="wt70"></span><span class="error"></span></p>
            <p><span><var>*</var>客服姓名：</span><input type="text" value="<?=arr::get($arr_data, "customer_name")?>" onkeyup="this.value=this.value.replace(/[^a-z\u4e00-\u9fa5]+/g,'')" onafterpaste="this.value=this.value.replace(/[^a-z\u4e00-\u9fa5]+/g,'')" name="customer_info_name" maxlength="6"><span class="c999">客户端显示，小写字母或者汉字，最多支持6个字符长度</span></p>
            <p><span class="wt70"></span><span class="error"></span></p>
            <p><span><var>*</var>客服昵称：</span><input type="text" value="<?=arr::get($arr_data, "customer_nickname")?>" onkeyup="this.value=this.value.replace(/[^a-z\u4e00-\u9fa5]+/g,'')" onafterpaste="this.value=this.value.replace(/[^a-z\u4e00-\u9fa5]+/g,'')"name="customer_info_nickname" maxlength="6"><span class="c999">pc端显示，小写字母或者汉字，最多支持6个字符长度</span></p>
            <p><span class="wt70"></span><span class="error"></span></p>
            <p><span><var>*</var>联系电话：</span><input type="text" value="<?=arr::get($arr_data, "customer_tel")?>" name="customer_info_tel" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")'></p>
            <p><span class="wt70"></span><span class="error"></span></p>
        </div>
    </div>
    <input type="hidden" id="oldkefu" value="<?=arr::get($arr_data, "customer_account")?>">
    <input type="submit" class="yellow submitbtn" value="提交">
    </FORM>
</div>

<script type="text/javascript">
var mobile = /^(((13[0-9]{1})|(15[0-9]{1}))+\d{8})$/;   
var tel = /^\d{3,4}-?\d{7,9}$/;

    $(".submitbtn").click(function(){
    var len=$(".kefubox").length;
    var flag=false;
    var arr=[];

    $(".kefubox input").each(function(e){
        if($(this).val()==""){
            flag=false;
            $(this).parent().next().find(".error").text("不能为空")
        }
        else{
            flag=true;
            $(this).parent().next().find(".error").text("")
        }
        if($(this).hasClass('tel')){
            if(tel.test($(".tel").val())||mobile.test($(".tel").val())){
            $(this).parent().next().find(".error").text("");
            flag=true;
            }
        else{
            flag=false;
            $(this).parent().next().find(".error").text("请输入正确的联系号码")
            return false;
        }
        }
        if($(this).hasClass('kefu')){
            arr.push($(this).val())
            if($("#oldkefu").val()==$(this).val()){
                flag=true;
                return false;
            }
            var dt=$.ajaxsubmit("/platform/ajaxcheck/CheckKefu",{"kefuName":$(this).val()})
            if(dt==1){
                $(this).parent().next().find(".error").text("");
                flag=true;
            }
            else{
            flag=false;
            $(this).parent().next().find(".error").text("客户账号不能重复")
            return false;
            }

        
        }
    })
    $(".kefu").each(function(index, val){

        for(var i=0;i<arr.length;i++){
            if(arr[i]==$(this).val()&&index!=i){
                flag=false;
                $(this).parent().next().find(".error").text("客户账号不能重复")
                return false;
            }
        }

    });
    if(flag){
        $("#formid").submit();
    }
    else{
        return false;
    }
})
</script>