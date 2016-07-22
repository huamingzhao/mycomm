<div class="quickrelease-content">
		<div class="releaseform">
			<div class="basicinformation">
				<h3>我收到的留言<i></i></h3>
				<?php if(count(arr::get($arr_data,"list")) > 0){?>
					<table width="100%" class="mymessageList" cellspacing="0" cellpadding="0">
					<tbody>
					<tr>
							<th width="358" height="34" class="first" align="left">意向投资者</th>
							<th width="342">留言内容</th>
							<th width="197" class="last">留言时间</th>
						</tr>
					<?php foreach ($arr_data['list'] as $key=>$val){?>
						<tr>
							<td align="left">
								<b><?=arr::get($val, "name",arr::get($val, "new_user_name"));?></b>
								<span>联系方式：<font><?=mb_substr(arr::get($val, "mobile"),0,3,'UTF-8').'****'.mb_substr(arr::get($val,"mobile"),7,11,'UTF-8'); ?></font><a class="seemobile" href="javascript:void(0)" mobile="<?=arr::get($val, "mobile")?>">查看</a></span>
								<span>邮箱：<?=arr::get($val, "email") ? arr::get($val, "email") : "暂无"?></span>
							</td>
							<td align="left">
								<span class="msgContent"><?=arr::get($val, "content")?></span>
							</td>
							<td align="center">
								<em><?=arr::get($val, "showtime");?></em>
							</td>
						</tr>
					<?php }?>
					</tbody>
				</table>
				<?php }else{?>
					<div class="noshenyi">目前您还没有收到生意留言信息，您可以刷新您的生意信息，将会优先被用户查看到！</div>
					<a href="<?php echo urlbuilder::qucikProManage();?>" class="yellow noshenyia">去刷新生意</a>
				<?php }?>
				<div class="ryl_search_result_page"><?=arr::get($arr_data, "page")?></div>
			</div>
		</div>
	</div>
	<script>
		$(".seemobile").click(function(){
			$(this).prev().html($(this).attr("mobile"))
			$(this).hide();
			})
	</script>