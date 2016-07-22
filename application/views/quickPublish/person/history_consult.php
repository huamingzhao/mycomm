<!--主体部分开始-->
<?php echo URL::webjs("my_business.js")?>
<?php echo URL::webcss("my_bussines.css")?>
<!--主体部分开始-->
<div class="right">
	<h2 class="user_right_title">
		<span>我发出的留言</span>
		<div class="clear"></div>
	</h2>
	<?php if(isset($consult_list) && !empty($consult_list)){?>
		<div class="my_business my_history_query">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tbody>
					<tr>
						<th width="380" align="left">留言项目</th>
						<th width="200" align="left">留言时间</th>
						<th width="150" align="left">留言内容</th>
					</tr>
	
					<?php 
						if(isset($consult_list) && !empty($consult_list)){
							foreach($consult_list as $key=>$list){
					?>
					<tr <?php if($key == count($consult_list)-1){ echo 'class="last"';} ?> >
						<td width="380" <?php if($list[7]!=2){echo 'class="no_link"';}?>>
							<?php 
								if($list[7]!=2){
							?>
								<img src="<?php echo url::imgurl($list[2]);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')"  width="122" height="96" alt="<?php echo $list[3]?>"/>
								<h4><?php echo $list[3]?></h4>
								<font>该项目已下架，无法预览</font>
							<?php }else{
								$project_href=urlbuilder::qucikProHome($list[0]);
								?>
								<a href="<?php echo $project_href;?>" target="_blank" title="<?php echo $list[3]?>"><img src="<?php echo url::imgurl($list[2]);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')"  width="122" height="96" alt="<?php echo $list[3]?>"/></a>
								<h4><a href="<?php echo $project_href;?>" target="_blank" title="<?php echo $list[3]?>"><?php echo $list[3]?></a></h4>
							<?php }?>						
							<p>
								招商金额：<?php echo $list[4]?><br/>
								招商地区：<span title="<?php echo $list[1]?>"><?php echo $list[1]?></span>
							</p>
						</td>
						<td width="200"><?php echo $list[6]?></td>
						<td width="140"><?php echo $list[5]?></td>
					</tr>
					<?php }}?> 
				</tbody>
			</table>
		</div>
		<?php  echo $page;?>
	<?php }else{?>
    <div class="noserchresult">
    	<div class="notishibox">
            <p class="noserchfz18">您的项目目前还没有收到留言信息。</p>
            <p class="noserchfz14 mt10">您可以刷新您的生意信息，将会优先展示，去 <a href="<?=urlbuilder::qucikProManage();?>">刷新我的生意>></a></p> 
        </div>
    </div>
	<?php }?>
</div>
<!--主体部分结束-->
<!--主体部分结束-->