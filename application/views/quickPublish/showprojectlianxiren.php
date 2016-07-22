<?php echo URL::webcss("common.css")?>
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("quickrelease.css")?>
<div class="quickrelease-content">
	<div class="releaseform">
		<div class="basicinformation">
			<h3>联系人信息<i></i></h3>
			<form id="forminfo" method="post" action="<?php echo URL::website('/quick/project/editQuickLianXiRen');?>" enctype="multipart/form-data">
				<input type="hidden" name="project_id" value="<?php echo $forms['project_id'];?>" />
				<ul>
					<li>
						<span class="spantitle"><var>*</var> 联系人：</span>
						<?php echo $forms['project_contact_people'] ? $forms['project_contact_people'] : '暂无';?>
					</li>
					<li>
						<span class="spantitle"><var>*</var> 手机号码：</span>
						<?php echo $forms['mobile'] ? $forms['mobile'] : '暂无';?>
					</li>
					<li class="content18">
						<span class="spantitle">座机号码：</span>
						<input name="quhao" type="text" onkeyup="this.value=this.value.replace(/\D/g,'')" placeholder="区号" class="pingpaitext" style="width:108px; padding-left: 10px;" value="<?php echo isset($forms['quhao']) ? $forms['quhao'] : '';?>">
						<input name="haoma" style=" padding-left: 10px;width:205px;margin-left: 15px;" onkeyup="this.value=this.value.replace(/\D/g,'')" type="text" placeholder="号码" class="pingpaitext" value="<?php echo isset($forms['haoma']) ? $forms['haoma'] : '';?>">
						<input name="fenjihao" style=" padding-left: 10px;width:105px;margin-left: 15px;" onkeyup="this.value=this.value.replace(/\D/g,'')" class="pingpaitext" type="text"placeholder="分机号" value="<?php echo isset($forms['fenjihao']) ? $forms['fenjihao'] : '';?>">
					</li>
				</ul>
				<input type="submit"class="yellow quickrelsave" value="保存">
			</form>
		</div>
	</div>
</div>