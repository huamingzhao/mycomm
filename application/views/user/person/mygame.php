    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>抽奖记录</span><div class="clear"></div></div>
        <div id="right_con">
        	<?php if(count($temp_id) > 0){?>
        	<p class="pinfo">您本期（<strong>第四期：2月26日--4月25日</strong>）的抽奖活动编号为：<var class="cbh"><?php echo $temp_id['temp_id'];?></var>，该编号用于抽取 <var>mini iPad  大奖</var>使用</p>
            <?php }else{?>
            <p class="pinfo">您还没有本期（<strong>第四期：2月26日--4月25日</strong>）抽取 <var>mini iPad  大奖</var>的活动编号<a href="javascript:;" class="lqbtn">去领取</a></p>
            <?php }?>
            <div><span class="fz14">抽奖记录查询：</span>
            <form id="search_form" action="<?php echo URL::site('/person/member/huodong/mygame')?>" method="get">
            <select id="seljl" class="seljl" name="game_id">
            <?php 
            	$date = array();
            	$date = common::getGameName();
            	foreach($date as $k => $v){
            ?>
            <option value="<?php echo $k;?>" <?php if($game_id == $k){?>selected="selected"<?php }?>><?php echo $v;?></option>
            <?php }?>
            </select>
            </form>
            </div>
            <table class="lotterytable" cellspacing="0" cellpadding="0" >
                <tr>
                    <th>抽奖时间</th>
                    <th>所获奖项</th>
                </tr>
                <?php if($list){?>
                <?php foreach($list as $v){?>
                <tr>
                    <td><?php echo $v['lucky_time'];?></td>
                    <td <?php if(($game_id == 1 && $v['prize_id'] >= 1 && $v['prize_id'] <= 3) || ($game_id == 2 && $v['prize_id'] >= 1 && $v['prize_id'] <= 4) || ($game_id == 3 && $v['prize_id'] >= 1 && $v['prize_id'] <= 5)){?>class="red"<?php }?>><?php echo $v['prize'];?></td>
                </tr>
                <?php }?>
                <?php }?>
            </table>
            <div class="page-effect">
                <?php echo $page; ?>
            </div>
        </div>
    </div>
<script>
$(document).ready(function(){	
	$(".lqbtn").click(function(){
	    $.ajax({
	       url:window.$config.siteurl+"platform/HuoDong4/getTempIdForChouJiang",
	       type:"post",
	       dataType:"json",
	       success:function(msg){
	       if(msg["status"] == 0){
	       window.location.href = window.$config.siteurl+"person/member/valid/mobile";
	       }else{
	           $(".pinfo").html('您本期（<strong>第四期：2014年2月26日--4月25日</strong>）的抽奖活动编号为：<var class="cbh">'+msg["status"]+'</var>，该编号用于抽取 <var>mini iPad  大奖</var>使用');
	       }
	       },
	       error:function(){
	       callbackdata="对不起，服务器出现异常！";
	       }
	   })
	});

	$("#seljl").change(function(){
		var sel = $("#seljl option:selected").val();
		$(this).val(sel);
		$("#search_form").submit();
	});
});

</script>