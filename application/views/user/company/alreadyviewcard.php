<?php echo URL::webjs("getcards.js")?>
<div class="right">
	<h2 class="user_right_title">
		<span>我查看的名片</span>
		<div class="clear"></div>
	</h2>
	<div class="right_con cardHaveView">
		<ul class="cardHaveViewList clearfix">
		<?php foreach($list as $v){?>
			<li>
				<p class="cardHaveViewPhoto">
					<label>
						<a href="<?php echo URL::website('/platform/SearchInvestor/showInvestorProfile?userid='.$v['second_user_id']);?>" target="_blank">
							<img onerror="$(this).attr('src', '<?= URL::webstatic("/images/getcards/photo.png");?>')" src="<?php echo $v['per_photo'];?>" alt="<?php echo $v['per_realname']?>">
						</a>
					</label>
				</p>
				<span class="cardHaveViewType">
					<a href="javascript:void(0)"><?php if($v['this_per_industry']){ echo mb_substr($v['this_per_industry'],0,2,'UTF-8');}else{echo '无';}?><font><?php if($v['this_per_industry']) {echo $v['this_per_industry'];}else{echo '无';}?><i></i></font></a>，
					<a href="javascript:void(0)"><?php if($v['per_amount']) {echo $v['per_amount'];}else{echo '无';}?></a>，
					<a href="javascript:void(0)"><?php if($v['per_area']){ echo mb_substr($v['per_area'],0,2,'UTF-8');}else{echo '无';}?><font><?php if($v['per_area']) {echo $v['per_area'];}else{echo '无';}?><i></i></font></a>
				</span>
				<span class="cardHaveViewDate"><?php echo $v['add_time'].'查看';?></span>
				<a  id="viewcard_<?php echo  $v['second_user_id'].'_';?>" href="javascript:void(0)" class="viewcard cardHaveViewRe">再次查看</a>
			</li>
		<?php }?>
			<?php $yucount=count($list)%4;if($yucount){
				for($i=0;$i<=4-$yucount;$i++){echo '<li></li>';}
			}?>
		</ul>
		<?php echo $page ?>
	</div>
</div>
<script type="text/javascript">
	$(".cardHaveViewType font").each(function(){
		$(this).css("margin-left", "-"+$(this).width()/2+"px");
	});
</script>