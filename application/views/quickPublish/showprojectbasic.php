<?php echo URL::webcss("common.css")?>
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("quickrelease.css")?>
<?php echo URL::webjs("vali_basic.js")?>
<?php echo URL::webcss("select_area.css")?>
<div class="quickrelease-content">
	<div class="releaseform">
		<div class="basicinformation">
			<h3>基本信息<i></i></h3>
			<form id="forminfo" method="post" action="<?php echo URL::website('/quick/project/editQuickBasic');?>" enctype="multipart/form-data">
				<input type="hidden" name="project_id" value="<?php echo $forms['project_id'];?>" />
				<ul >
					<li class="content1">
						<span class="spantitle" ><var>*</var> 品牌名称：</span>
						<input type="text"  id="spantitle1"  class="pingpaitext" maxlength="10" name="project_brand_name" value="<?php echo $forms['project_brand_name'];?>">
						<font>最多支持10个汉字字符</font>
			        	<span class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content2">
						<span class="spantitle">品牌发源地：</span>
						<span style="position: relative; display: inline-block;">
									<a callback="$(this).html($('.province_id').attr('data-name')+'&nbsp;-&nbsp;'+$('.per_area_id').attr('data-name'))" select-all="true" box-title="省级" second-result=".per_area_id" first-result=".province_id" data-url="/ajaxcheck/getArea" class="select_area_toggle select_area_toggle_0" href="javascript:;"><?php echo $forms['project_brand_birthplace_name'] ? $forms['project_brand_birthplace_name'] : '请选择';?></a>
									<input type="hidden" name="birthplace_area_id" class="province_id" value="<?php echo isset($forms['project_brand_birthplace']['area_id']) && $forms['project_brand_birthplace']['area_id'] ? $forms['project_brand_birthplace']['area_id'] : 0;?>">
			                        <input type="hidden" name="birthplace_pro_id" class="per_area_id" value="<?php echo isset($forms['project_brand_birthplace']['area_id']) && $forms['project_brand_birthplace']['pro_id'] ? $forms['project_brand_birthplace']['pro_id'] : 0;?>">
									</span>
									<font>品牌诞生并发展壮大的地区</font>
			                        
								</li>
								<li class="content3">								
									<span class="spantitle">品牌历史：</span>
									<span style="position: relative; display: inline-block">
							<a href="javascript:;" class="qingxuanze project_history"><?php $arr = common::projectHistory();echo isset($arr[$forms['project_history']]) ? $arr[$forms['project_history']] : '请选择';?></a>
						<ul class="project_historyul">
							<?php foreach ($arr_project_history as $key=>$val){?>
								<li date-type="<?=$key;?>"><?=$val;?></li>
							<?php }?>
						</ul></span>
						<input type="hidden" id="inputproject_history" name="project_history" value="<?php echo $forms['project_history'];?>">									
									
						<font>品牌诞生时间</font>
					</li>
					<li class="content4">
						<span class="spantitle"><var>*</var> 所在区域：</span>
						<span style="position: relative; display: inline-block;" id="location">
										<a callback="$(this).html($('.a').attr('data-name')+'&nbsp;-&nbsp;'+$('.b').attr('data-name'))" select-all="true" box-title="省级" second-result=".b" first-result=".a" data-url="/ajaxcheck/getArea" class="select_area_toggle select_area_toggle_1" href="#"><?php echo $forms['merchants_area_name'];?></a>
										<input type="hidden" name="merchants_area_first_id" class="a" value="<?php echo isset($forms['merchants_area_ids']['merchants_area_first_id']) && $forms['merchants_area_ids']['merchants_area_first_id'] ? $forms['merchants_area_ids']['merchants_area_first_id'] : 0;?>">
			                        	<input type="hidden" name="merchants_area_second_id" class="b" value="<?php echo isset($forms['merchants_area_ids']['merchants_area_second_id']) && $forms['merchants_area_ids']['merchants_area_second_id'] ? $forms['merchants_area_ids']['merchants_area_second_id'] : 0;?>">
									</span>
									<font>商家所在地或者项目所在地区，方便用户按照地域查找您的招商加盟信息</font>
			                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
					<li class="content5">
						<span class="spantitle"><var>*</var> 行业分类：</span>
						<span style="position: relative; display: inline-block;" id="industry">
										<a callback="$(this).html($('.c').attr('data-name')+'&nbsp;-&nbsp;'+$('.d').attr('data-name'))" select-all="true" box-title="一级行业" second-result=".d" first-result=".c" data-url="/ajaxcheck/primaryIndustry" class="select_area_toggle select_area_toggle_2" href="#"><?php echo $forms['industry_name'];?></a>
										<input type="hidden" name="first_industry_id" class="c" value="<?php echo isset($forms['industry_id']['first_industry_id']) && $forms['industry_id']['first_industry_id'] ? $forms['industry_id']['first_industry_id'] : 0;?>">
			                        	<input type="hidden" name="second_industry_id" class="d" value="<?php echo isset($forms['industry_id']['second_industry_id']) && $forms['industry_id']['second_industry_id'] ? $forms['industry_id']['second_industry_id'] : 0;?>">
									</span>
									<font>招商加盟信息分类，投资者可以按照行业搜索到</font>
			                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
				</ul>
				<input type="submit"class="yellow quickrelsave" value="保存">
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
$(".project_history").toggle(function(){
	$(".project_historyul").show();
},function(){
	$(".project_historyul").hide();
})
$(".project_historyul li").click(function(){
	$(".project_history").text($(this).text())
	$("#inputproject_history").val($(this).attr("date-type"));
	$(this).parent().hide();
	return false;
})
</script>