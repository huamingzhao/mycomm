<div class="right">
	<div id="right_top">
		<span>创业币统计详情</span>
		<div class="clear"></div>
	</div>
	<div id="right_con">
		<div id="my_points" class="exchange_sb">
			<p>
				<span>
					截至目前你所获取累计的创业币总数： <i><?php echo $count;?></i>
				</span>
			</p>
			<form action="<?php echo URL::site("/person/member/huodong/getChuangYeBiList")?>" method="get">
				<p>
					请选择月份：
					<select name="date">
						<?php if($date_option){ ?>
						<?php foreach($date_option as $k => $v){?>
						<option value="<?php echo $k;?>" <?php if($date_sel == $k){?>selected="selected"<?php }?>><?php echo $v;?></option>	
						<?php }?>
						<?php }?>					
					</select>
					<input type="submit" class="query_exchange" value="查询" title="查询"/>
				</p>
			</form>
			<table class="person_itylist_data">
				<tbody>
					<tr>
						<th>时间</th>
						<th>获取类型来源</th>
						<th>获取的创业币数</th>
					</tr>
					<?php if($list){?>
					<?php foreach($list as $v){?>
					<tr>
						<td><?php echo $v['date'];?></td>
						<td> 
							<b><?php echo $v['source'];?></b>
						</td>
						<td> 
							<font <?php if($v['fuhao'] == '-'){?>class="reduce"<?php }?>><?php echo $v['fuhao'];?><?php echo $v['score'];?></font>
						</td>
					</tr>
					<?php }?>	
					<?php }?>					
				</tbody>
			</table>
			<div class="page-effect">
                <?php echo $page; ?>
            </div>
		</div>
	</div>
</div>