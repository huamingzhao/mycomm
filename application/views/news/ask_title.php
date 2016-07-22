<?php echo URL::webcss("ask.css")?>
<?php echo URL::webjs('ask.js')?>
<?php echo URL::webjs("js/cms/show.js");?>
<div class="asknavwrap">
  <div class="asknav">
  	<span>当前位置：</span><a href="<?php echo urlbuilder::rootDir('');?>">一句话网</a><span class="wt15"> > </span><a href="<?php echo url::webwen('');?>">创业问答</a><span class="wt15"> > </span>
  	<a href="<?php 
  	echo isset($get->ask_id) && $get->ask_id ? url::webwen('ask/ask/updateMyAsk?title_id='.$get->ask_id) :url::webwen('ask/ask/addMyAsk');?>">
  	<?php echo isset($get->ask_id) && $get->ask_id ?'修改提问':'我要提问'; ?>
  	</a>
  </div>
</div>
<div class="goask">
	<form action="/ask/ask/addMyask" method="post" id="add_title_form">
		<div class="question">
	    	<div  class="title"><span>问题分类：</span></div>
	        <select name="ask_category_first" class="marl1" id="ask_category_first">
	        	<?php 
	        		if(isset($first_industry) && count($first_industry)){			
						foreach($first_industry as $key=>$value){
							if(isset($get->ask_category_first) && $get->ask_category_first == $value->ask_industry_id){
								echo '<option selected="selected" value="'.$value->ask_industry_id.'" >'.$value->ask_industry_name.'</option>';
							}else{
								echo '<option value="'.$value->ask_industry_id.'" >'.$value->ask_industry_name.'</option>';
							}
						}
	        	}?>
	        </select>
	        <select name="ask_category_second" class="marl1" id="ask_category_second">
	        	<option value="-1" <?php if(isset($get->ask_category_second) && $get->ask_category_second <=0){echo 'selected="selected"';}?>>不选</option>
				<?php
				if(isset($first_industry) && count($first_industry)){
					foreach($second_industry as $key=>$value){
						if(isset($get->ask_category_second) && $get->ask_category_second == $key){
							echo '<option selected="selected" value="'.$key.'" >'.$value.'</option>';
						}else{
							echo '<option value="'.$key.'" >'.$value.'</option>';
						}
					}
				}?>
	        </select>
	    </div>
		<div class="question">
	    	<div  class="title"><span>问题标题：</span></div>
	        <input class="marl1 inputt" onkeyup="this.value = this.value.slice(0,30)" id="ask_title" name="ask_title" type="text" value="<?php echo  isset($get->ask_name)?$get->ask_name:'';?>" />
	        <p style="margin-left:160px;margin-top:5px;">（最多支持30个汉字字符长度）</p>	    	
	    </div>
		<div class="question">
	    	<div  class="title"><span>问题详述：</span></div>
	        <textarea id="ask_description" onkeyup="this.value = this.value.slice(0,300)" name="ask_description" class="ques-text marl1" rows="3" name=""><?php echo isset($get->ask_describe)?strip_tags($get->ask_describe):'';?></textarea>
	    	<p style="margin-left:160px;margin-top:5px;">（最多支持300个汉字字符长度）</p>
	    </div>
		<div class="question" style="width:550px;">
	        <label class="" >匿名</label >
	        <?php
	    	if(isset($anonymous) && $anonymous){
	    		echo '<input type="checkbox"  value="1" class="checkb"  checked="checked" name="anonymous"> ';
	    	}else{
				echo '<input type="checkbox"  value="1"  class="checkb"  name="anonymous"> ';
	    	}
	    	?>
	        <div style=" clear:both"></div>
	    </div>
	    <div class="question">
	        <a href="javascript:void(0)" id="add_title_submit" class="gosub"></a>
	    </div>
	    <input type="hidden" value="<?php echo  isset($get->ask_id)?$get->ask_id:0;?>" name="id">
	    <input type="hidden" value="submit" name="submit">
    </form>
</div>
<script type="text/javascript">
//根据一级行业 寻找二级行业
$("#ask_category_first").change(function(){ 
	var ask_category_first = $(this).val();
	$.ajax({
		type : "post",
		dataType : "json",
		url : "/ajaxcheck/getSecIndByFirInd",
		data : "ask_category_first="+ask_category_first,
		//jsonp: 'callback',
		async: false,		
		success: function(msg){
		   var flag = false; 
		   $("#ask_category_second option").remove(); 
		   $("#ask_category_second").append("<option value='-1'>不选</option>");
           for(var key in msg){			    
			    $("#ask_category_second").append("<option value='"+key+"'>"+msg[key]+"</option>"); 				    
				flag = true;				
		   }
		   if(flag == false){
			   $("#ask_category_second option").remove();
			   $("#ask_category_second").append("<option selected='selected' value='-1'>不选</option>");
		   }
		}					
	});	
});
$("#add_title_submit").click(function(){ 
	$.ajax({
		type : "post",
		dataType : "json",
		url : "/ajaxcheck/addMyAsk/",
		data:$('#add_title_form').serialize(),
		async: false,
		success: function(msg){
		   var data = {'-2':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">操作失败</p>','-1':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">未登录</p>','1':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 0;">恭喜你，获得 10 创业币积分。</p><p style=" font-size:12px; line-height:18px; color:#ff0000; width:445px; padding-left:70px; font-family:"微软雅黑"">友情提示:</p><p style=" font-size:12px; line-height:18px; width:445px; padding-left:70px; padding-bottom:15px; font-family:"微软雅黑";">提问中如果含有违反国家法律的信息，直接从你的创业币总积分中扣除20创业币，管理员将删除此提问。</p>','2':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">操作成功</p>','3':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 70px;">修改成功</p>','4':'<p style="font-size:18px; text-align:center; padding:30px 70px 20px 0;">恭喜你提问成功</p><p style=" font-size:12px; line-height:18px; color:#ff0000; width:445px; padding-left:70px; font-family:"微软雅黑"">友情提示:</p><p style=" font-size:12px; line-height:18px; width:445px; padding-left:70px; padding-bottom:15px; font-family:"微软雅黑";">今日你已累计获得提问奖励100创业币，提问将不再增加创业币奖励</p>'};		  
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
						window.location.href='<?php echo url::webwen('');?>question/'+msg.msg+'.shtml';
					}
				},
				target:"new"
		   });			  	
		}					
	});	
}); 
</script>
</html>
