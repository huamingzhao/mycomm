<!--中部开始-->
<?php echo URL::webcss('ask.css');?>
<?php echo URL::webjs('ask.js');?>
<div class="asknavwrap">
  <div class="asknav">
  	<span>当前位置：</span><a href="<?php echo urlbuilder::rootDir('');?>">一句话网</a><span class="wt15"> > </span><a href="<?php echo url::webwen('');?>">创业问答</a><span class="wt15"> > </span><a href="<?php echo url::webwen($nav_ask_category_first);?>/"><?php echo $ask_category_first;?></a><span class="wt15"> > </span>
  	<?php if($ask_category_second){?>
  	<a href="<?php echo url::webwen(arr::get(Service_News_Ask::getAskIndustryOneArrById(),$ask_title->ask_category_first).'/'.$ask_title->ask_category_second.'/');?>"><?php echo $ask_category_second;?></a><span class="wt15"> > </span>
  	<?php }?>
  	<span class="lastalink"><?php echo $ask_title->ask_name;?></span>
  </div>
</div>

<div class="contenttop clearfix">
  <div class="floleft ">
    <div class="questionbox">
      <div class="blline">
        <h1><?php echo $ask_title->ask_name;?></h1>
        <p class="pl40 c999">提问者：<span class="mr25 c333"><?php if($ask_title->ask_user_name){echo $ask_title->ask_user_name;}else{echo '匿名';}?></span>浏览次数：<?php echo $ask_title->ask_pv_count;?></p>
      </div>
      <p class="pl40 c666 fz14"><?php echo $ask_title->ask_describe;?></p>
          
      <?php if(isset($login_id) && $login_id && $login_id == $ask_title->ask_user_id && $ask_title->ask_adopt_type == 0 ){?>
       <!--修改问题开始-->
      <p class="pl40 imodask fz14 c333">
      	<span class="floleft">您还可以：</span>
        <a class="modask floleft modwenti" target="_self" href="<?php echo url::webwen('ask/ask/updateMyask?title_id='.$ask_title->ask_id);?>"></a>
      </p>      
      <!-- 修改问题结束 -->
      <?php }?>
      <?php if(isset($login_id) && $login_id && $login_id != $ask_title->ask_user_id && $ask_title->ask_adopt_type == 0 && !$user_title_r || !isset($login_status)){?>
      <!-- 回答问题开始 -->
      <form id="add_answer">
	      <input type="hidden" value="<?php echo $ask_title->ask_id; ?>" name="title_id">
	      <input type="hidden" value="submit" name="submit"/>
	      <p class="pl40 imodask fz14 c333"><span class="helpask fz14">我来帮他 / 她回答</span></p>
	      <textarea class="helpask-text"onkeyup="this.value = this.value.slice(0,300)" rows="3" name="answer_description"></textarea>
		  <p style="height:30px; line-height: 30px; margin-left:40px;">最多支持300个汉字字符长度。</p>
	      <p class="pl40 imodask fz14 c333">
	      	<a class="ask_submit floright" id="add_answer_submit" href="javascript:void(0)" name="submit"></a>
	        <label class="floright ask_lab" >匿名</label ><input class="floright ask_niming" type="checkbox" name="anonymous" value="1"/>
	      </p>   
      </form>
      <!-- 回答问题结束 -->
      <?php }?>
    </div>
    
    <?php if($ask_adopt_answer && $ask_adopt_answer->ask_answer_status == 1){?>
	    <div class="answerbox mt25" >
	      <p class="huita"><?php echo $ask_title->ask_title_adopt_people || $ask_title->ask_user_type == 0 && $ask_title->ask_title_adopt_people == 0 ? '提问者采纳回答' : '管理员采纳回答';?></p>
	      <p class="pl40 mt10"><?php echo $ask_adopt_answer->ask_answer_describe;?></p>
	      <p class="pl40 c999 mt10"><span class="mr15 c333"><?php if($ask_adopt_answer->ask_answer_user_name){echo $ask_adopt_answer->ask_answer_user_name;}else{echo '匿名';}?></span><?php echo date('Y-m-d H:i:s',$ask_adopt_answer->add_answer_time);?></p>
	     </div>
	     <?php if($ask_title->ask_title_adopt_comment){?>
	      	<p style="font-size:14px;margin:15px 0 0 45px;;"><span style="color:#f78e1b; font-weight:bold;">提问者感言：</span><?php echo $ask_title->ask_title_adopt_comment;?></p>
	      <?php }?>
    <?php }elseif($ask_adopt_answer && $ask_adopt_answer->ask_answer_status == 0){?>
		 <div class="answerbox mt25" >
	      <p class="huita">问题已被采纳，但采纳答案被管理员禁用</p>
	     </div>
    <?php }?>
    <div class="answernum mt10">
      <p class="numht"><?php echo $ask_answer_amount;?>条回答</p>
      <ul>
      <?php if($ask_answer_amount){
      		foreach ($ask_answer as $value){
      ?>
	 		<li class="clearfix">
	          <span class="floleft wt45"><img src="<?php echo URL::webstatic('images/ask/icon.png');?>"></span>
	          <div class="floleft wt655">
	            <p class="c999 mt5"><span class="mr15 c333"><?php if($value->ask_answer_user_name){echo $value->ask_answer_user_name;}else{echo '匿名';}?></span><?php echo date('Y-m-d H:i:s',$value->add_answer_time);?></p>
	            <p class="c666 answerinfo fz14 mt10" style="word-break: break-all;"><?php echo $value->ask_answer_describe;?></p>
                <!--add botton-->
                <?php if(isset($login_id) && $login_id && $login_id == $value->ask_answer_user_id && $ask_title->ask_adopt_type == 0 ){?>
                <!-- 修改答案开始 -->
                <p style="" class="listab"><a class="modask modaskclick" href="javascript:void(0);" ></a></p>
                <?php }?>
                <!-- 采纳答案开始 -->
                <?php if(isset($login_id) && $login_id && $login_id == $ask_title->ask_user_id && $ask_title->ask_adopt_type == 0 && $value->ask_answer_adopt_type == 0 ){?>
                		<p class="listab"><a class="pleasask" href="javascript:void(0)" value="<?php echo $ask_title->ask_id?>_<?php echo $value->ask_answer_id;?>">采纳为满意答案</a></p>              			
                <?php  }?>
                <!-- 修改答案 采纳答案 文本框开始 -->
                <div class="btnblock" style="display:none;">
                	<?php if(isset($login_id) && $login_id && $login_id == $ask_title->ask_user_id && $ask_title->ask_adopt_type == 0 && $value->ask_answer_adopt_type == 0 ){?>
                		<p style="height:30px; line-height: 30px;">向帮助了你的他/她的说句感谢话吧!&nbsp;&nbsp;最多支持300个汉字字符长度。</p>              			
                	<?php  }?>
                	<textarea class="helpask-text fontcontent" onkeyup="this.value = this.value.slice(0,600)" style="margin-left:0"></textarea>
                	
                	<p class="pl40 imodask fz14 c333">
	                	<?php if(isset($login_id) && $login_id && $login_id == $value->ask_answer_user_id && $ask_title->ask_adopt_type == 0 ){?>
			      			<a class="ask_submit floright abtnsubmit" href="javascript:;" value="<?php echo $ask_title->ask_id?>_<?php echo $value->ask_answer_id;?>"></a>
				      		<label class="floright ask_lab" >匿名</label >
				      		<?php if($value->ask_answer_user_name =='匿名用户'){?>
				      		<input class="floright ask_niming" type="checkbox" name="anonymous" checked="checked" value="1"/>
			      			<?php }else{?>
			      			<input class="floright ask_niming" type="checkbox" name="anonymous"  value="1"/>
			      			<?php }?>
			      		<?php  }elseif(isset($login_id) && $login_id && $login_id == $ask_title->ask_user_id && $ask_title->ask_adopt_type == 0 && $value->ask_answer_adopt_type == 0){?>
			      			<a class="ask_submit floright abtnsubmit" href="javascript:;" value="<?php echo $ask_title->ask_id?>_<?php echo $value->ask_answer_id;?>"></a>
			      		<?php }?>
			      	</p>
                </div>
                <!-- 修改答案 采纳答案 文本框结束 -->
	          </div>
	        </li>
      <?php }}?>

      </ul>
    </div>
    <div class="keyanswer mt25">
      <h2>更多<?php if($ask_category_second){echo $ask_category_second;}else{echo $ask_category_first;}?>相关提问</h2>
      <table cellspacing="0" cellpadding="0" >
      	<?php 
      		if(!empty($relate_list)){
				foreach($relate_list as $value){
		?>
			        <tr>
			          <td><p class="wt570"><a class=" fz14 c666" href="<?php echo URL::webwen("question/".$value[0].".shtml")?>" title="<?php echo $value[1];?>"><?php echo $value[1];?></a></p></td>
			          <td><p class="wt50 c999"><?php echo $value[2];?>回复</p></td>
			          <td><p class="wt70 c999"><?php echo date('Y-m-d',$value[3]);?></p></td>
			        </tr>	
		<?php }}?>
      </table>
    </div>
  </div>
  <div class="floright">
	  <div class="askrightimg">
	      <div class="ngask"><p>共解决
       		<?php $askcount_length=strlen($askcount);
       			for( $i=0;$i<$askcount_length;$i++ ){ echo '<span>'.mb_substr($askcount,$i,1).'</span>'?>
      		<?php }?>个问题
		</p></div>
        <div class="myquestion">
            <a class="myask" href="<?php echo url::webwen('ask/ask/getMyAsk?type=1');?>">我的提问<span>(<?php echo (isset($login_status) && $login_status)? $title_total : 0;?>)</span></a>
       		<a class="myask" href="<?php echo url::webwen('ask/ask/getMyAsk?type=2');?>">我的回答<span>(<?php echo (isset($login_status) && $login_status)? $answer_total : 0;?>)</span></a>
        	<a class="igoask" href="<?php echo url::webwen('ask/ask/addMyAsk');?>"></a>
        </div>
            
	  </div>
	  <div class="askbottomright mt10">
	      <div class="entrepreneurship">
	        <h2>热门<?php if($ask_category_second){echo $ask_category_second;}else{echo $ask_category_first;}?>提问</h2>
	        <ul class="entrlist">
	        <?php 
	        	if(!empty($relate_top_list)){
					foreach($relate_top_list as $value){
	        ?>
	        		<li><a href="<?php echo URL::webwen("question/".$value[0].".shtml")?>" title="<?php echo $value[1];?>"><?php echo $value[1];?></a></li>
	        <?php }}?>
	        </ul>
	      </div>
	    </div>
	  <!-- 优质项目推荐注释开始 -->
	  <?php /*?>
	  <?php if(!empty($match_project_list)){?>
	  <div class="askannouncement width248 mt10">  
	    <h2>优质<?php if($ask_category_second){echo $ask_category_second;}else{echo $ask_category_first;}?>项目推荐</h2>
	    <div class="mtb15">
	     	<?php 
	     			foreach($match_project_list as $key=>$value){
	     	?>
     					<div class="askimgbox pbt0">
     						<p class="img">
					        <label>
							<a href="<?php echo urlbuilder::project($value[0]);?>" target="_blank" title="<?php echo $value[1];?>"><img src="<?php echo $value[2];?>" alt="<?php echo $value[1];?>"></a>
						    </label>
					      	</p>
						    <a href="<?php echo urlbuilder::project($value[0]);?>" target="_blank" class="atitle text" title="<?php echo $value[1];?>"><?php echo $value[1];?></a>
						</div> 					    	
	     	<?php }?>
	    </div>	    
	  </div>
	  <?php }?>
	  */?>
	  <!-- 优质项目推荐注释结束 -->
</div>
</div>
<script type="text/javascript">
//答案修改--提交
$(".ashsubmita1").live("click",function(){
	var data = $(this).attr("value");
	var data_array = data.split("_");
    var askid = data_array[0];
    var answerid = data_array[1];
	var comment = $(this).parent().parent().find("textarea").val();
	var anonymous = 0;
	if($(this).next().next().attr("checked") == 'checked'){
		anonymous = 1;
	}else{
		anonymous = 0;
	}
	$.ajax({
		type : "post",
		dataType : "json",
		url : "/ajaxcheck/addMyAnswer/",
		data: "title_id="+askid+"&answer_id="+answerid+"&answer_description="+comment+"&anonymous="+anonymous,
		async: false,
		success: function(msg){
		   var data = {'-2':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">操作失败</p>','-1':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">未登录</p>','3':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">修改成功</p>'};
		   if(msg.code == -1){
			   // 未登录跳转
			   window.location.href = '<?php echo url::website('/geren/denglu.html?to_url='.cookie::get("to_url"));?>';
			   return false;
		   }
		   window.MessageBox({
				title:"提示信息",
				content:data[msg.code],
				btn:"ok",
				width:570,
				callback:function(){
					if(msg.code == 3){
						window.location.reload();
					}
				},
				target:"new"
		   });			  	
		}					
	});	 					   
})
// 问题采纳--提交
$(".ashsubmita2").live("click",function(){
	var data = $(this).attr("value");
	var data_array = data.split("_");
    var askid = data_array[0];
    var answerid = data_array[1];
	var comment = $(this).parent().parent().find("textarea").val();
	$.ajax({
		type : "post",
		dataType : "json",
		url : "/ajaxcheck/addAdopt/",
		data: "title_id="+askid+"&answer_id="+answerid+"&comment="+comment,
		async: false,
		success: function(msg){
		   var data = {'-2':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">操作失败</p>','-1':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">未登录</p>','1':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">操作成功</p>'};
		   if(msg.code == -1){
			   // 未登录跳转
			   window.location.href = '<?php echo url::website('/geren/denglu.html?to_url='.cookie::get("to_url"));?>';
			   return false;
		   }
		   window.MessageBox({
				title:"提示信息",
				content:data[msg.code],
				btn:"ok",
				width:570,
				callback:function(){
					if(msg.code == 1){
					    window.location.reload();
					}	
				},
				target:"new"
		   });		  	
		}					
	});	 					   
})

// 回答提交
$("#add_answer_submit").click(function(){ 
		$.ajax({
			type : "post",
			dataType : "json",
			url : "/ajaxcheck/addMyAnswer/",
			data:$('#add_answer').serialize(),
			async: false,
			success: function(msg){
			   var data = {'-2':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">操作失败</p>','-1':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">未登录</p>','1':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 0;">恭喜你，获得 5 创业币积分。</p><p style=" font-size:12px; line-height:18px; color:#ff0000; width:445px; padding-left:70px; font-family:"微软雅黑"">友情提示:</p><p style=" font-size:12px; line-height:18px; width:445px; padding-left:70px; padding-bottom:15px; font-family:"微软雅黑";">提问中如果含有违反国家法律的信息，直接从你的创业币总积分中扣除10创业币，管理员将删除此提问。</p>','2':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">操作成功</p>','3':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">修改成功</p>','4':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 0;">恭喜您提问成功！</p><p style=" font-size:12px; line-height:18px; color:#ff0000; width:445px; padding-left:70px; font-family:"微软雅黑"">友情提示:</p><p style=" font-size:12px; line-height:18px; width:445px; padding-left:70px; padding-bottom:15px; font-family:"微软雅黑";">今日你已累计获得回答奖励100创业币，回答将不再增加创业币奖励</p>'};
			   if(msg.code == -1){
				   // 未登录跳转
				   window.location.href = '<?php echo url::website('/geren/denglu.html?to_url='.cookie::get("to_url"));?>';
				   return false;
			   }			   
			   window.MessageBox({
					title:"提示信息",
					content:data[msg.code],
					btn:"ok",
					width:570,
					callback:function(){
						if(msg.code > 0){
							window.location.reload();
						}
					},
					target:"new"
			   });		  	
			}					
		});	
	}); 
//采纳--输入框显示
$(".pleasask").click(function(){
	var $parent=$(this).parent();
	$parent.nextAll(".btnblock").is(":hidden")?$parent.nextAll(".btnblock").show():$parent.next().hide();
	$parent.nextAll(".btnblock").find(".ask_submit").attr("class","ask_submit floright ashsubmita2")

})
// 修改答案--输入框显示
$(".modaskclick").click(function(){
	var $parent=$(this).parent();
	var $text=$(this).parent().prev().html();
	var str = $text.replace(/\<br\>/gi,'\r');
	str = html_decode(str);
	$parent.nextAll(".btnblock").is(":hidden")?$parent.prev().hide().nextAll(".btnblock").show().find(".fontcontent").text(str):$parent.next().hide().prevAll(".answerinfo").show();
	$parent.nextAll(".btnblock").find(".ask_submit").attr("class","ask_submit floright ashsubmita1")
	
})
function html_decode(str)  
{  
  var s = "";
  if(str.length ==0) return "";
  s = str.replace(/&amp;/g, "&");
  s = s.replace(/&lt;/g, "<");
  s = s.replace(/&gt;/g, ">");
  s = s.replace(/&nbsp;/g, " ");
  s = s.replace(/&#39;/g, "\'");
  s = s.replace(/&quot;/g, "\""); 
  return s;  
}

</script>
<!--中部结束-->