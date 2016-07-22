<?php echo URL::webcss("userzhanhui.css")?>
<?php echo URL::webjs("getcards.js")?>
<div class="right">
  <h2 class="user_right_title"> <span>意向加盟</span>
    <div class="clear"></div>
  </h2>
  <div class="hbtablebox">
  	<form action="<?php echo URL::site("/company/member/exhb/showYiXiangList")?>" method="get">
    <div>
	    <select name="type">
	   		<option value="1" <?php if($type == 1){?>selected="selected"<?php }?>>项目优惠劵</option>
	      	<option value="2" <?php if($type == 2){?>selected="selected"<?php }?>>名片咨询</option>
	   	</select>
     项目名称：
      <input type="text" name="name" value="<?php echo (isset($name) && $name != '') ? $name : '';?>">
      状态：
      <select name="status">
        <option value="0" selected="selected">不限</option>
        <option value="1" <?php if($status == 1){?>selected="selected"<?php }?>>正常</option>
        <option value="2" <?php if($status == 2){?>selected="selected"<?php }?>>已过期</option>
      </select>
      <input type="submit" value="查找">
    </div>
    </form>
    <table width="100%" cellspacing="0" cellpadding="0" >
      <tr>
        <th width="80" style="text-align:left; text-indent: 1em;">姓名</th>
        <th width="190">项目名称</th>
        <th width="90">状态</th>
        <th width="110">有效期</th>
        <th width="95">操作</th>
        <th width="170">联系方式</th>
      </tr>
      <?php if($forms){?>
      <?php foreach($forms as $v){?>
      <tr>
        <td style="text-align:left; text-indent: 1em;"><?php echo $v['per_realname'];?></td>
        <td><?php echo $v['project_brand_name'];?></td>
        <td><?php echo $v['status'];?></td>
        <td><?php echo $v['coupon'];?></td>
        <td><a href="javascript:void(0)" id="getonecard_<?php echo $v['user_id'];?>_0" class="viewcard orange" rel="nofollow" onshow="">查看名片</a></td>
        <td><span class="tel"><?php echo $v['mobile'] ? $v['mobile'] : "无";?></span><?php if($v['mobile']){?><a href="javascript:;" class="yellow ckbtn view_cantantbtn"id="getonecard_<?php echo $v['user_id'];?>_0">查看</a><?php }?></td>
      </tr>
      <?php }?>
      <?php }?>
    </table>
    <div class="page-effect">
   		<?php echo $page; ?>
  	</div>
  </div>
</div>
