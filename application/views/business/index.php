<input type="hidden" id="talk_id" value="<?php echo $talk_id;?>" />
<div class="searchlistbox clearfix">
	<div class="searchabox">
	<a href="<?php echo urlbuilder::business_index();?>" <?php if($now_talk_id==0) echo 'class="on"';?>>全部</a>
	<?php  foreach($talk_list as $key=>$value) {
	 	$talk_id=$value['id'];
	 	$href=urlbuilder::business_index($talk_id);	
	 ?>
		<a href="<?php echo $href;?>" <?php if($now_talk_id==$talk_id) echo 'class="on"';?>><?php echo $value['name'];?></a>
		<?php }?>
	</div>
	<div class="serchbox wt700 fl">
		<div class="titleserch mt30">
			<h3>疑惑</h3>
			<span>
				<a href="javascript:;" class="on">最新</a><a href="javascript:;">热门</a><a href="javascript:;">等待回复</a>
			</span>
		</div>
		<div class="serchcontent">
		<!-- 最新列表 Start-->	
		<?php if($new_question_info['count']>0) {?>
			<div class="serchulbox" style="display:block" >
				<ul class="clearfix">
				<?php $data_info=$new_question_info['data']; //d($data_info);
				      foreach($data_info as $key=>$value)
				      {
				      	$question_id=$value['question_id'];
				      	$talk_href=urlbuilder::business_index($value['talk_id']);
				      	$user_href=urlbuilder::business_userinfo($value['user_id']);
				      	$detail_href=urlbuilder::business_detail($question_id);
				      	$answer_user_id=$value['answer_user_id'];
				      	if(!empty($answer_user_id))
				      		$answer_user_href=urlbuilder::business_userinfo($value['answer_user_id']);
				    ?>
					<li class="clearfix">
						<a href="<?php echo $user_href;?>" class="imgbox fl ml10">
						<img src="<?php echo URL::imgurl($value['user_photo']);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')">
						</a>
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
				<?php if(!empty($new_question_info['has_next_page'])) {?>
				<p class="more mt15">更多</p>
				<?php }?>
			</div>
			<?php }?>
		<!-- 最新列表 End -->	
		<!-- 热门列表 Start -->		
			<?php if($hot_question_info['count']>0) {?>
			<div class="serchulbox">
				<ul class="clearfix">
				<?php $data_info=$hot_question_info['data']; //d($data_info);
				      foreach($data_info as $key=>$value)
				      {
				      	$question_id=$value['question_id'];
				      	$talk_href=urlbuilder::business_index($value['talk_id']);
				      	$user_href=urlbuilder::business_userinfo($value['user_id']);
				      	$detail_href=urlbuilder::business_detail($question_id);
				      	$answer_user_id=$value['answer_user_id'];
				      	if(!empty($answer_user_id))
				      		$answer_user_href=urlbuilder::business_userinfo($value['answer_user_id']);
				    ?>
					<li class="clearfix">
						<a href="<?php echo $user_href;?>" class="imgbox fl ml10">
						<img src="<?php echo URL::imgurl($value['user_photo']);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')">
						</a>
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
				<?php if(!empty($hot_question_info['has_next_page'])) {?>
				<p class="more mt15">更多</p>
				<?php }?>
			</div>
			<?php }?>
		<!-- 热门列表 End -->	
		<!-- 等待回复列表 Start -->		
			<?php if($wait_question_info['count']>0) {?>
			<div class="serchulbox">
				<ul class="clearfix">
				<?php $data_info=$wait_question_info['data']; //d($data_info);
				      foreach($data_info as $key=>$value)
				      {
				      	$question_id=$value['question_id'];
				      	$talk_href=urlbuilder::business_index($value['talk_id']);
				      	$user_href=urlbuilder::business_userinfo($value['user_id']);
				      	$detail_href=urlbuilder::business_detail($question_id);
				      	$answer_user_id=$value['answer_user_id'];
				      	if(!empty($answer_user_id))
				      		$answer_user_href=urlbuilder::business_userinfo($value['answer_user_id']);
				    ?>
					<li class="clearfix">
						<a href="<?php echo $user_href;?>" class="imgbox fl ml10">
						<img src="<?php echo URL::imgurl($value['user_photo']);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')">
						</a>
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
				<?php if(!empty($wait_question_info['has_next_page'])) {?>
				<p class="more mt15">更多</p>
				<?php }?>
			</div>
			<?php }?>
		<!-- 等待回复列表 End -->		
		</div>
	</div>
	<div class="serchright fl wt240 ml20 mt30">
	<?php if(count($hot_talk_question)>0){?>
		<div class="hotti hottiborder">
			<h3>热门话题</h3>
			<ul>
			  <?php foreach($hot_talk_question as $key=>$value){
			 	$href=urlbuilder::business_index($value['talk_id']);?>
				<li class="clearfix mt15">
					<a href="javascript:;" class="fl mt5"><img src="<?=URL::webstatic('/images/syb/huati.png')?>"></a>
					<div class="fl ml15">
						<p class="ml10"><a href="<?php echo $href;?>" class="replyp"><?php echo $value['talk_name'];?><i></i></a></p>
						<p class="mt10">该话题下有<var><?php echo $value['count'];?></var>个问题</p>
					</div>
				</li>
				<?php }?>
			</ul>
		</div>
		<?php }?>
		<?php if(count($hot_user)>0){ ?>
		<div class="hotti hotuser mt25">
			<h3>热门用户</h3>
			<ul>
			  <?php foreach($hot_user as $key=>$value){
			 	$href=urlbuilder::business_userinfo($value['user_id']);?>
				<li class="clearfix mt10" <?php if($key==0) { echo 'style="margin-top:0"';}?>>
					<a href="<?php echo $href;?>" class="fl mt10">
					<img src="<?php echo URL::imgurl($value['user_photo']);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')">
					</a>
					<div class="fl ml15">
						<p style="padding-top:7px;"><a href="<?php echo $href;?>" class="hotusera"><?php echo $value['user_name'];?></a></p>
						<p>回复了<var><?php echo $value['answer_count'];?></var>问题，提出了<var><?php echo $value['question_count'];?></var>问题</p>
					</div>
				</li>
				<?php }?>
			</ul>
		</div>
		<?php }?>
	</div>
</div>



<script type="text/javascript">

	$(function(){
		$(".pagevalue").val(1)
		$(".more").click(function(){
			$(this).text("加载中...")
			var $index=$(this).parents(".serchulbox").index();
			var arr=["new","hot","wait"];
			var page=$(this).prev().val();
			page++;
			$(this).prev().val(page);
			var appendhtml="";
			var msg=$.ajaxsubmit("/business/ajaxcheck/get_home_left_list",{"talk_id":$("#talk_id").val(),"type":arr[$index],"page":page})
			
			for(var i=0;i<msg.count;i++){
				imgurl=msg.data[i].user_photo?msg.data[i].user_photo:window.$config.staticurl+'images/quickrelease/company_default.png';
				answer_user_id=msg.data[i].answer_user_id?'<a href="'+window.$config.wenurl+'/business/business/userinfo?user_id='+msg.data[i].user_id+'" class="ml10 mr10 syusername">'+msg.data[i].user_name+'</a>回复了问题':""
				appendhtml+='<li class="clearfix"><a href="'+window.$config.wenurl+'/business/business/userinfo?user_id='+msg.data[i].user_id+'" class="imgbox fl ml10"><img src="'+imgurl+'"></a><div class="fl ml10 wt500"><a href="'+window.$config.wenurl+'/business/business/detail?question_id='+msg.data[i].question_id+'" class="serchlink">'+msg.data[i].title+'</a><p class="mt15 replyinfo"><a href="'+window.$config.wenurl+'/business/business/index?talk_id='+msg.data[i].talk_id+'" class="replyp">'+msg.data[i].talk_name+'<i></i></a>'+answer_user_id+'<var>'+msg.data[i].answer_count+'</var>个回复<span class="ml10"><var>'+msg.data[i].view_count+'</var>次浏览</span></p></div><span class="timer">'+msg.data[i].pub_time+'</span></li>';
			}
			$(this).prev().prev().append(appendhtml);
			$(this).text("更多");
			if(msg.has_next_page==0){$(this).hide();return false;}
			
		})
		$(".titleserch span a").click(function(){
			$(this).addClass('on').siblings().removeClass("on");
			var $index=$(this).index();
			$(".serchulbox").eq($index).show().siblings().hide();
		})
	});
</script>