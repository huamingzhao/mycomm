<div class="project_home_content project_home_proucts_div">
	<div class="project_home_content_products">
      <!-- <input type="hidden" name="hid" value="<?php echo isset($projectinfo->project_id)?$projectinfo->project_id :'';?>" id="hid"> -->
		<div class="ph_products_img_main">
			<div class="ph_products_img_content">
				<div class="ph_products_img_div">
					<div class="ph_products_img_container">
						<?php if($imgresult){foreach($imgresult as $k=>$v){ ?>
							<div class="ph_products_img">
								<img src="<?php echo $v['b_image'];?>" alt="<?php echo $projectinfo->project_brand_name.'产品图片';?>" onload="javascript:DrawImage(this,674,539)"/>
								<h3><?php echo $v['content'];?></h3>
							</div>
						<?php }}?>
						<div class="clear"></div>
					</div>
					<div class="ph_products_img_left" title="查看上一张" style="cursor:url(<?php echo URL::webstatic('images/platform/project_home/ph_products_mouse_ico_2.cur')?>),auto;"></div>
					<div class="ph_products_img_right" title="查看下一张" style="cursor:url(<?php echo URL::webstatic('images/platform/project_home/ph_products_mouse_ico.cur')?>),auto;">
						<div class="ph_products_img_play_area"></div>
					</div>
					<a href="javascript:void(0)" title="暂停/继续播放" class="ph_products_img_icon pause"></a>
				</div>
			</div>
			<div class="ph_products_img_tool">
				<div class="ph_products_img_tool_top"><a href="javascript:void(0)"><img src="<?php echo URL::webstatic('images/platform/project_home/project_home_images_06.png')?>" alt="向上翻动"/></a></div>
				<div class="ph_products_img_tool_center">
					<ul>
						<?php if($imgresult){$ii=1;foreach($imgresult as $v){?>
						<li <?php if($ii==1){echo 'class="fc"';}?> ><img src="<?php echo $v['s_image'];?>" alt="<?php echo $projectinfo->project_brand_name.'产品小图'.$ii;?>" title="<?php echo $projectinfo->project_brand_name.'产品小图'.$ii;?>" onload="javascript:DrawImage(this,127,101)"/></li>
						<?php $ii++;}}?>
					</ul>
				</div>
				<div class="ph_products_img_tool_bottom"><a href="javascript:void(0)"><img src="<?php echo URL::webstatic('images/platform/project_home/project_home_images_09.png')?>" alt="向下翻动"/></a></div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php echo URL::webjs("platform/template/gallery.js");?>
<script>
$(document).ready(function(){
    //意向浏览统计
    var projectid = <?php echo $projectinfo->project_id;?>;
    var url = "/platform/ajaxcheck/TongJiProjectPv";
    $.post(url,{"project_id":projectid,"type":4},function(data){
    },"json");
});
</script>
