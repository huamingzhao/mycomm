<?php echo URL::webcss("userzhanhui.css")?>
<div class="right">
	<h2 class="user_right_title">
 		<span>修改红包</span>
     	<div class="clear"></div>
 	</h2>
 	<div class="hbtablebox">
 		<form action="<?php echo URL::site("/person/member/exhb/myHongBao")?>" method="get">
 		<div>
        	<select name="type">
           		<option value="1" <?php if($type == 1){?>selected="selected"<?php }?>>展会红包</option>
           		<option value="2" <?php if($type == 2){?>selected="selected"<?php }?>>项目优惠劵</option>
          	</select>
            <?php if(isset($type) && $type == 1){echo '展会名称';}else{echo '项目名称';}?>：<input type="text" name="name" value="<?php echo (isset($name) && $name != '') ? $name : '';?>">
                                状态：<select name="status">
            		<option selected="selected" value="0">不限</option>
            		<option value="1" <?php if($status == 1){?>selected="selected"<?php }?>>即将开展</option>
            		<option value="2" <?php if($status == 2){?>selected="selected"<?php }?>>开展中</option>
            		<option value="3" <?php if($status == 3){?>selected="selected"<?php }?>>已结束</option>
               	</select>
              	<input type="submit" value="查找">
     	</div>
     	</form>
     	<table width="100%" cellspacing="0" cellpadding="0" >
     		<tr>
            	<th width="100" style="text-align:left; text-indent: 1em;">金额</th>
           		<th width="105">类型</th>
              	<th width="210"><?php if(isset($type) && $type == 1){echo '展会名称';}else{echo '项目名称';}?></th>
              	<th width="90">状态</th>
            	<th width="140">展会开展时间</th>
               	<th width="85">有效期</th>
         	</tr>
         	<?php if($forms){?>
         	<?php foreach($forms as $v){?>
         	<tr>
         		<td style="text-align:left; text-indent: 1em;"><?php echo $v['jine'];?>元</td>
              	<td><?php echo $v['type'];?></td>
            	<td><?php echo $v['name'];?></td>
              	<td><?php echo $v['status'];?></td>
              	<td><?php echo $v['time'];?></td>
              	<td><?php echo $v['validity'];?></td>
        	</tr>
        	<?php }?>
        	<?php }?>
     	</table>
     	<div class="page-effect">
      		<?php echo $page; ?>
       	</div>
 	</div>
</div>