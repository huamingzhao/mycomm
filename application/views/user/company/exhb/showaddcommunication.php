<?php echo URL::webcss("userzhanhui.css")?>
<div class="right">
	 <h2 class="fz14 mt30 ml10">1、 下载安装客服端</h2>
    <div class="mt20  ml40">
        <p><b>客服端下载地址：</b><a href="http://download.webim-aone.tonglukuaijian.com/" target="_blank">http://download.webim-aone.tonglukuaijian.com/</a></p>
        <p>支持pc端、ios客户端、android客户端</p>
    </div>
     <h2 class="fz14 mt30 ml10">2、提交在线沟通所需信息</h2>
     <div class="mt20 ml40 bbox">
        <p>最多可以申请<b> 5 </b>个客服同时在线沟通，帮助投资者答疑解惑；</p>
        <p>审核通过后，一个客服信息一个月内仅限修改两次，如有紧急问题，请咨询在线沟通服务热线: <b>400 1015 908</b></p>
    </div>
    <FORM action="/company/member/exhb/AddCommunication" method="post" id="formid">
    <div class="kefubox ml40 mt50">
    <input type="hidden" name="com_id" value="<?=$com_id;?>"/>
         <div class="kefutitlebox"><span>客服<i>1</i>：</span><a href="javascript:;" class="delbtn">删除</a></div>
        <div class="formbox">
            <p><span><var>*</var>客服帐号：</span><input type="text" class="kefu" name="customer1[exhb_customer_account]" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9a-z]+/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9a-z]+/g,'')"><span class="c999">登录账号/工号，小写字母或者数字，最多支持10个字符长度</span></p>
            <p><span class="wt70"></span><span class="error"></span></p>
            <p><span><var>*</var>客服密码：</span><input type="text" name="customer1[exhb_customer_password]" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9a-z_]+/g,'')" onafterpaste="this.value=this.value.replace(/[^0-9a-z_]+/g,'')"><span class="c999">登录密码，小写字母、下划线加数字，最多支持10个字符长度</span></p>
            <p><span class="wt70"></span><span class="error"></span></p>
            <p><span><var>*</var>客服姓名：</span><input type="text" name="customer1[exhb_customer_name]" onkeyup="this.value=this.value.replace(/[^a-z\u4e00-\u9fa5]+/g,'')" onafterpaste="this.value=this.value.replace(/[^a-z\u4e00-\u9fa5]+/g,'')" maxlength="6"><span class="c999">客户端显示，小写字母或者汉字，最多支持6个字符长度</span></p>
            <p><span class="wt70"></span><span class="error"></span></p>
            <p><span><var>*</var>客服昵称：</span><input type="text" name="customer1[exhb_customer_nickname]" onkeyup="this.value=this.value.replace(/[^a-z\u4e00-\u9fa5]+/g,'')" onafterpaste="this.value=this.value.replace(/[^a-z\u4e00-\u9fa5]+/g,'')" maxlength="6"><span class="c999">pc端显示，小写字母或者汉字，最多支持6个字符长度</span></p>
            <p><span class="wt70"></span><span class="error"></span></p>
            <p><span><var>*</var>联系电话：</span><input type="text" name="customer1[exhb_customer_tel]"></p>
            <p><span class="wt70"></span><span class="error"></span></p>
        </div>
    </div>
    <p class="ml40 mt20 jsaddkefu"><a href="javascript:;">+ 添加更多客服</a></p>
    <input type="submit" class="yellow submitbtn" value="提交">
    </FORM>
</div>

<script type="text/javascript">
$(".delbtn").live("click",function(){
    var _this=$(this)
    window.MessageBox({
              title:"生意街网站提示您",
              content:"<p>您是否确认此客服信息删除操作？</p>",
              btn:"ok cancel",
              width:500,
              callback:function(){
                $(".jsaddkefu").show();
                    _this.parents(".kefubox").remove();
                    $(".kefunun").each(function(index){
                    _this.text(index+1)
                })
              }
            });
    

})
    $(".jsaddkefu").live("click",function(){
    	var i=$(".kefubox").length+1;
        var html='<div class="kefubox ml40 mt50"><div class="kefutitlebox"><span>客服<i class="kefunun">'+i+'</i>：</span><a href="javascript:;" class="delbtn">删除</a></div><div class="formbox"><p><span class="wt70"><var>*</var>客服帐号：</span><input name="customer'+i+'[exhb_customer_account]" onkeyup="this.value=this.value.replace(/[^0-9a-z]+/g,\'\')" onafterpaste="this.value=this.value.replace(/[^0-9a-z]+/g,\'\') " type="text" class="kefu" maxlength="10" ><span class="c999">登录账号/工号，小写字母或者数字，最多支持10个字符长度</span></p><p><span class="wt70"></span><span class="error"></span></p><p><span class="wt70"><var>*</var>客服密码：</span><input maxlength="10" name="customer'+i+'[exhb_customer_password]" onkeyup="this.value=this.value.replace(/[^0-9a-z_]+/g,\'\')" onafterpaste="this.value=this.value.replace(/[^0-9a-z_]+/g,\'\') " type="text"><span class="c999">登录密码，小写字母、下划线加数字，最多支持10个字符长度</span></p><p><span class="wt70"></span><span class="error"></span></p><p><span class="wt70"><var>*</var>客服姓名：</span><input maxlength="6" onkeyup="this.value=this.value.replace(/[^a-z\u4e00-\u9fa5]+/g,\'\')" onafterpaste="this.value=this.value.replace(/[^a-z\\u4e00-\\u9fa5]+/g,\'\')" type="text" name="customer'+i+'[exhb_customer_name]"><span class="c999">客户端显示，小写字母或者汉字，最多支持6个字符长度</span></p><p><span class="wt70"></span><span class="error"></span></p><p><span class="wt70"><var>*</var>客服昵称：</span><input name="customer'+i+'[exhb_customer_nickname]" type="text" maxlength="6" onkeyup="this.value=this.value.replace(/[^a-z\u4e00-\u9fa5]+/g,\'\')" onafterpaste="this.value=this.value.replace(/[^a-z\\u4e00-\\u9fa5]+/g,\'\')"><span class="c999">pc端显示，小写字母或者汉字，最多支持6个汉字字符长度</span></p><p><span class="wt70"></span><span class="error"></span></p><p><span class="wt70"><var>*</var>联系电话：</span><input type="text" class="tel" name="customer'+i+'[exhb_customer_tel]"></p><p><span class="wt70"></span><span class="error"></span></p></div></div>';
        $(this).before(html);
        if(i >=5){$(this).hide();return false;}
    })
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
            return false;
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