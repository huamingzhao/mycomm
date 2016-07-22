<!--中间部分-->
		<div class="project_home_content">
			<div class="project_home_content_poster" style="text-align:left;color:#000;">
			<?php if($ispage==false){?>
			 <?php
                 $i=0;
                 if(!empty($poster['b_imgs'])){
                foreach ($poster['b_imgs'] as $v){
                $i++;
                ?>
                    <img src="<?=$v?>" />
                   <?php }}?>
                   <?php for($i;$i<4;$i++){?>
                   <img src="<?php echo URL::webstatic("images/platform/yellow/temp-img.jpg");?>" />
                   <?php }?>
			 <?php }else{?>
                        <?=$outPoster?>
            <?php }?>
			</div>
		</div>
    <script type="text/javascript">
    	var project_home_center_fixed = true;
    	$(document).ready(function(){
    	    //意向浏览统计
    	    var projectid = <?php echo $projectinfo->project_id;?>;
    	    var url = "/platform/ajaxcheck/TongJiProjectPv";
    	    $.post(url,{"project_id":projectid,"type":2},function(data){
        	},"json");

            $("#ai").hide();
            $("#bi").hide();
    	});
    </script>
		<!--中间部分 END-->