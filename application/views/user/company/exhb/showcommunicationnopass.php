<?php echo URL::webcss("userzhanhui.css")?>
<div class="right">
	<h2 class="user_right_title">
		<span>免费申请在线沟通服务</span>
		<div class="clear"></div>
	</h2>
     <div class="downbox">
            <p><b>客服端下载地址：</b><a target="_blank" href="http://download.webim-aone.tonglukuaijian.com/">http://download.webim-aone.tonglukuaijian.com/</a></p>
            <p>支持pc端、ios客户端、android客户端</p>
        </div>
        <div class="rengonbox shengheshib">
            <h3>抱歉,在线沟通服务申请审核失败！</h3>
            
            <p>原因：<?=arr::get($arr_data_Customer,"customer_reason","信息填写有误,请修改后重新提交。")?><br>审核通过后，一个客服信息一个月内仅限修改两次，如有紧急问题，请咨询在线沟通服务热线：<b style="color: red">400 1015 908</b></p>
            <p><a href="<?=URL::website("/company/member/exhb/showCommunication?type=1&com_id=".$com_id);?>" class="achakan">查看申请信息></a></p>
    </div>
</div>