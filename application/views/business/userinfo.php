<input type="hidden" id="user_id" value="<?php echo $user_id;?>"/>
<?php if(!empty($user_info)){  //d($user_info); ?>
<div class="homepagewrap">
	<div class="homepageheader">
		<div class="fl userlogoimgbox mt25">
			<img src="<?php echo URL::imgurl($user_info['user_photo']);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')" width="150" height="120">
		</div>
		<div class="fl ml15 mt25 userinfo">
			<h1><?php echo $user_info['user_name'];?></h1>
			<div class="intrp mt5">
				<?php $user_sign='';
				if(isset($user_info['user_sign']))
				{	
						if(!empty($user_info['user_sign']))
								$user_sign=$user_info['user_sign'];
				}
				echo "<span class='intrspan'>{$user_sign}</span>";
				?>	

			<?php if($login_user_id>0 && $user_id>0 && $login_user_id==$user_id) {?>
			    <a href="javascript:;" class="<?php if(!empty($user_sign)) echo 'fillnone '; ?>fillinfo" style="display:<?php echo empty($user_sign) ? 'block;' : 'none'; ?>"><?php echo empty($user_sign) ? '填写个人简介' :'修改';?></a>
				<div class="introbox"><input type="text" class="introtext"><a href="javascript:;" class="intro">确定</a></div>
			<?php } ?>
			</div>
			<p class="mt5" style="color: #999999; height: 18px;">
			<?php if(!empty($last_update_time)) {?>
			最近活跃于：<?php echo $last_update_time;?>
		    <?php }?>
		    </p>
			<ul class="ultablist clearfix">
			<?php if(isset($question_info['question_count'])) {?>
				<li class="on">
					<p><span class="fz24"><?php echo $question_info['question_count'];?></span><span class="fz16">提问</span></p>
					<var></var>
				</li>
				<?php }?>
				<?php if(isset($answer_info['answer_count'])) {?>
				<li>
					<p><span class="fz24"><?php echo $answer_info['answer_count'];?></span><span class="fz16">解答</span></p>
					<var></var>
				</li>
				<?php }?>
			</ul>
		</div>
		
	</div>
</div>
<?php } ?>
<div class="homepagecontent serchcontent">
	<div class="serchulbox" style="display:block" >
<?php if($question_info['count']>0) {?>
				<ul class="clearfix">
				<?php $data_info=$question_info['data']; //d($data_info);
				      foreach($data_info as $key=>$value)
				      {
				      	$question_id=$value['question_id'];
				      	$talk_href=urlbuilder::business_index($value['talk_id']);
				      	$detail_href=urlbuilder::business_detail($question_id);
				      	$answer_user_id=$value['answer_user_id'];
				      	if(!empty($answer_user_id))
				      		$answer_user_href=urlbuilder::business_userinfo($value['answer_user_id']);
				    ?>
					<li class="clearfix">
						<div class="fl ml10 wt500">
							<a href="<?php echo $detail_href;?>" class="serchlink"><?php echo $value['title']?></a>
							<p class="mt15 replyinfo">
							   <a href="<?php echo $talk_href;?>" class="replyp"><?php echo $value['talk_name'];?><i></i></a>
							   <?php if(!empty($answer_user_id)) {?>
							   <a href="<?php echo $answer_user_href;?>" class="ml10 mr10 syusername"><?php echo $value['answer_user_name'];?></a>回复了问题
							   <?php }?>
							  <var><?php echo $value['answer_count'];?></var>个回复<span class="ml10"><var><?php echo $value['view_count'];?></var>次浏览</span></p>
						</div>
						<span class="timer"><?php echo $value['pub_time'];?></span>
					</li>
					<?php }?>
				</ul>
				<input type="hidden" value="1" class="pagevalue">
				<?php if(!empty($question_info['has_next_page'])) {?>
				<p class="more mt15">更多</p>
				<?php }?>	
		<?php }?>
		</div>
	<div class="serchulbox" >
	<?php if($answer_info['count']>0) {?>	
		<ul class="clearfix">
		<?php $data_info=$answer_info['data']; //d($data_info);
				      foreach($data_info as $key=>$value)
				      {
				      	$question_id=$value['question_id'];
				      	$detail_href=urlbuilder::business_detail($question_id);
				      	$split_content=$value['split_content'];
				    ?>
			<li class="clearfix">
				<p class="fl ml10 ztbg"><span><?php echo $value['nice_count'];?></span><span>赞同</span></p>
				<div class="fl ml10 wt700">
					<a href="<?php echo $detail_href;?>"  class="serchlink"><?php echo $value['title']?></a>
					<p class="mt5"><?php echo $split_content;?></p>
				</div>
				<span class="timer"><?php echo $value['pub_time'];?></span>
			</li>
			<?php }?>
		</ul>
		<input type="hidden" value="1" class="pagevalue">
		<?php if(!empty($answer_info['has_next_page'])) {?>
		<p class="more mt15">更多</p>
		<?php }?>
	<?php }?>
	</div>
</div>
  <script type="text/javascript">
  	$(".ultablist li").click(function(){
  		var $index=$(this).index();
  		$(this).addClass('on').siblings().removeClass('on');
  		$('.serchulbox').eq($index).show().siblings().hide();
  	})
	$(".intrspan").mouseover(function() {
		$(this).next().css("display","inline-block").text("修改");
	});
	$(".intrp").mouseleave(function() {
		$(this).find(".fillnone").hide();
	});
	$(".intro").click(function(){
		var dt=$.ajaxsubmit(window.$config.wenurl+"business/ajaxcheck/update_user_sign",{"sign":$(".introtext").val()})
		if(dt.code=='200'){
			if(!$(this).parent().prev().hasClass('fillnone')){$(this).parent().prev().addClass('fillnone')};
			
			if($(".introtext").val()==""){
				$(this).parent().prev().show().removeClass('fillnone').text("填写个人简介");
				$(this).parent().hide();
			}
			else{
				$(this).parent().prevAll(".intrspan").text($(".introtext").val())
			$(this).parent().hide().prevAll().show();
			}
			
		}
	})
	$(".fillinfo").live("click",function(){
		$(this).hide().prev().hide();
		$(this).next().show();
	})
	$(".more").click(function(){
		
		$(this).text("加载中...")
		var $index=$(this).parents(".serchulbox").index();
		var urlarr=["business/ajaxcheck/get_my_question_list","business/ajaxcheck/get_my_answer_list"];
		var page=$(this).prev().val();
		
		page++;
		$(this).prev().val(page);
		var appendhtml="";
		//var msg=$.ajaxsubmit("/business/ajaxcheck/get_my_question_list",{"user_id":0, "page":page})
		var msg=$.ajaxsubmit(window.$config.wenurl+urlarr[$index],{"user_id":$("#user_id").val(), "page":page})
		
		for(var i=0;i<msg.count;i++){
			if($index == 0)
			{
				
				answer_user_id=msg.data[i].answer_user_id?'<a href="'+window.$config.wenurl+'/business/business/userinfo?user_id='+msg.data[i].user_id+'" class="ml10 mr10 syusername">'+msg.data[i].user_name+'</a>回复了问题':""
				
				appendhtml+='<li class="clearfix"><div class="fl ml10 wt500"><a href="'+window.$config.wenurl+'/business/business/detail?question_id='+msg.data[i].question_id+'" class="serchlink">'+msg.data[i].title+'</a><p class="mt15 replyinfo"><a href="'+window.$config.wenurl+'/business/business/index?talk_id='+msg.data[i].talk_id+'" class="replyp">'+msg.data[i].talk_name+'<i></i></a>'+answer_user_id+'<var>'+msg.data[i].answer_count+'</var>个回复<span class="ml10"><var>'+msg.data[i].view_count+'</var>次浏览</span></p></div><span class="timer">'+msg.data[i].pub_time+'</span></li>';
			}else{
				appendhtml+='<li class="clearfix"><p class="fl ml10 ztbg"><span>'+msg.data[i].nice_count+'</span><span>赞同</span></p><div class="fl ml10 wt700"><a class="serchlink" href="'+window.$config.wenurl+'/business/business/detail?question_id='+msg.data[i].question_id+'">'+msg.data[i].title+'</a><p class="mt5">'+msg.data[i].split_content+'</p></div><span class="timer">'+msg.data[i].pub_time+'</span></li>'
				
			}
		}
		
		$(this).prev().prev().append(appendhtml);
		$(this).text("更多")
		if(msg.has_next_page==0){$(this).hide();return false;}
		
	})
  </script>