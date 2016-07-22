<?php echo URL::webjs("platform/filter/swfobject.js");?>
<?php echo URL::webjs("platform/template/yellow.js");?>
<?php echo URL::webjs("platform/template/slides.min.jquery.js");?>
<?php echo URL::webjs("platform/template/certs_flash.js");?>
<script>
$(function(){
    $('.honor #slides').slides({
        preload: false,
        preloadImage: 'images/platform/yellow/loading.gif',
        play: 0,
        pause: 2500,
        hoverPause: false,
        animationStart: function(){
            $('.caption').animate({
                bottom:-35
            },100);
        },
        animationComplete: function(current){
            $('.caption').animate({
                bottom:0
            },200);
            if (window.console && console.log) {
                // example return of current slide number
                console.log(current);
            };
        }
    });
    $('.license #slides').slides({
        preload: true,
        preloadImage: 'images/platform/yellow/loading.gif',
        play: 0,
        pause: 2500,
        hoverPause: true,
        animationStart: function(){
            $('.caption').animate({
                bottom:-35
            },100);
        },
        animationComplete: function(current){
            $('.caption').animate({
                bottom:0
            },200);
            if (window.console && console.log) {
                // example return of current slide number
                console.log(current);
            };
        }
    });
    /*$(".quolif img").each(function(){
        if($(this).width()>((435/461)*$(this).height())){

        }else{
        }
    })*/
    jQuery(window).load(function () {
            jQuery("div.quolif img").each(function () {
                DrawImage(this, 435, 461);
            });
        });
    function DrawImage(ImgD, FitWidth, FitHeight) {
            var image = new Image();
            image.src = ImgD.src;
            if (image.width > 0 && image.height > 0) {

                if (image.width / image.height >= FitWidth / FitHeight) {
                    if (image.width > FitWidth) {
                        ImgD.width = FitWidth;
                        ImgD.height = (image.height * FitWidth) / image.width;
                        ImgD.style.paddingTop = (FitHeight-(image.height * FitHeight) / image.width)/2 + "px";
                    } else {
                        ImgD.width = image.width;
                        ImgD.height = image.height;
                        ImgD.style.paddingTop = (FitHeight-image.height)/2 + "px";
                        ImgD.style.paddingLeft = (FitWidth-image.width)/2 + "px";
                    }
                } else {
                    if (image.height > FitHeight) {
                        ImgD.height = FitHeight;
                        ImgD.width = (image.width * FitHeight) / image.height;
                        ImgD.style.paddingLeft = (FitWidth-(image.width * FitHeight) / image.height)/2 + "px";

                    } else {
                        ImgD.width = image.width;
                        ImgD.height = image.height;
                        ImgD.style.paddingTop = (FitHeight-image.height)/2 + "px";
                        ImgD.style.paddingLeft = (FitWidth-image.width)/2 + "px";
                    }
                }
            }
     }

});
</script>
<!--中部开始-->
<div id="yellow">
    <div id="center-d">

        <div id="title">
            <h1><?php echo $com_name;?>资质</h1>
            <img src="<?php echo URL::webstatic("images/platform/yellow/qualification.png");?>" class="sign"/>
        </div>
        <div class="contant" style="padding-left: 25px;padding-top: 20px;">
            <div id="imagewall"></div>
            <input type="hidden" name="hid" value="<?php echo isset($project_id)?$project_id :'';?>" id="hid">
        </div>
        <div class="corner"></div>
        <div id="right_nav">
              <ul>
                <!-- <li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">封面</a></li>-->
                <li><a href="<?php echo urlbuilder::project($projectinfo->project_id);?>">项目</a></li>
                <?php if(isset($ispage) && $ispage){?><li><a href="<?php echo urlbuilder::projectPoster($projectinfo->project_id);?>">海报</a></li><?php }?>
                <?php if(isset($is_has_image) && $is_has_image){?><li><a href="<?php echo urlbuilder::projectImages($projectinfo->project_id);?>">产品</a></li><?php }?>
                <?php if(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){?><li><a href="<?php echo urlbuilder::projectCompany($projectinfo->project_id);?>">公司</a></li><?php }?>
                <li><a href="#" class="current">资质</a></li>
                <?php if($isinvest){?><li><a href="<?php if($isinvest>0){echo urlbuilder::projectInvest($project_id).'?investid='.$isinvest;}else{echo urlbuilder::projectInvest($project_id);} ?>" class="three">招商会</a></li><?php }?>
                <!-- <li><a href="<?php echo urlbuilder::projectEnd($project_id);?>">封底</a></li>-->
            </ul>
        </div>
    </div>
    
    <div class="clear"></div>
	<div class="ryl_project_bg">
    <p class="ryl_project_page">
      <!-- 上一页 -->
      <a href="<?php  if(($projectinfo->project_source ==1 && $projectinfo->com_id!=0) || $isrenglingok){
                           echo urlbuilder::projectCompany($projectinfo->project_id);
                        }elseif(isset($is_has_image) && $is_has_image){
                           echo urlbuilder::projectImages($projectinfo->project_id);
                    }elseif(isset($ispage) && $ispage){
                           echo urlbuilder::projectPoster($projectinfo->project_id);
                    }else{
                           echo urlbuilder::project($projectinfo->project_id);
                    }
    ?>" class="ryl_prev_page"></a>
    <!-- 下一页 -->
       <?php if(isset($isinvest) && $isinvest) {
                        if($isinvest>0){
                            echo '<a href="'.urlbuilder::projectInvest($projectinfo->project_id).'?investid='.$isinvest.'" class="ryl_next_page"></a>';
                        }else{
                            echo '<a href="'.urlbuilder::projectInvest($projectinfo->project_id).'" class="ryl_next_page"></a>';
                        }
                    }else{
                       echo '';
                    }
         ?>
    </p>
	</div>
</div>
<!--中部结束-->