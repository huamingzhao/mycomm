<?php echo URL::webcss("platform/common.css")?>
<?php echo URL::webcss("platform/search_result.css")?>
<?php echo URL::webjs("platform/home/home.js")?>
<?php echo URL::webcss("platform/item_list.css")?>

<!--主体部分-->
<div class="main" style="background-color:#e3e3e3;height:auto">

  <div class="yjh_item_list" id="yjh_item_list_top">
     <!--left-->
     <div class="yjh_item_list_left">
         <ul>

            <li><a href="<?php echo urlbuilder:: rootDir("xiangdao");?>" class="item_list_left_current">首页</a></li>
            <li><a href="<?php echo urlbuilder:: projectGuide ("fenlei");?>">分类导航</a></li>
            <li><a href="<?php echo urlbuilder:: projectGuide ("top");?>">排行榜</a></li>
            <li><a href="<?php echo urlbuilder:: projectGuide ("people");?>">按人群找</a></li>
            <li><a href="<?php echo urlbuilder:: projectGuide ("diqu");?>">按地区找</a></li>
         </ul>
     </div>
     <!--right-->
     <div class="yjh_item_list_right">
         <div class="yjh_item_list_r_bgtop"></div>
         <div class="yjh_item_list_r_bgcenter" style="padding-top:0;">

            <!--项目大全-->
            <div class="yjh_home_top">
              <div class="yjh_home_top_unit">
                 <h2><span>最佳口碑项目</span><b>30天排行</b></h2>
                 <div class="yjh_home_unit_cont">
                   <ul>
                       <?if($appRanking) {foreach($appRanking as $key => $val) {?>
                      <li>
                          <label><?=$key+1;?>、</label><span><a href="<?=$url = urlbuilder::project($val['project_id']);?>" target="_blank" ><?=$val['project_brand_name']?></a></span><b><img src="<?php echo URL::webstatic('images/platform/item_list/icon10.jpg');?>" /> <?=$val['amount']?></b>
                          <div class="clear"></div>
                          <div class="yjh_home_unit_licont"><a href="<?=$url = urlbuilder::project($val['project_id']);?>" target="_blank"  ><img src="<?=URL::imgurl($val['project_logo'])?>" alt="<?=$val['project_brand_name']?>" /></a><span><?=mb_substr($val['product_features'],0,20,'UTF-8').'...';?></span></div>
                      </li>
                       <?}}?>

                   </ul>
                   <p><a target="_blank" href="<?php echo urlbuilder:: guideShowRanking ("1");?>">查看完整榜单 》</a></p>
                   <div class="clear"></div>
                 </div>
              </div>
              <div class="yjh_home_top_unit">
                 <h2><span class="yjh_home_unit_shadow01">最受关注项目</span><b>30天排行</b></h2>
                 <div class="yjh_home_unit_cont yjh_home_unit_shadow02">
                   <ul>
                       <?if($watchRanking) {foreach($watchRanking as $key => $val) {?>
                      <li>
                          <label><?=$key+1;?>、</label><span><a href="<?=$url = urlbuilder::project($val['project_id']);?>" target="_blank"><?=$val['project_brand_name']?></a></span><b><em>关注</em><?=$val['amount']?></b>
                          <div class="clear"></div>
                          <div class="yjh_home_unit_licont"><a href="<?=$url = urlbuilder::project($val['project_id']);?>" target="_blank"><img src="<?=URL::imgurl($val['project_logo'])?>" alt="<?=$val['project_brand_name']?>" /></a><span><?=mb_substr($val['product_features'],0,20,'UTF-8').'...';?></span></div>
                      </li>
                       <?}}?>
                   </ul>
                   <p><a target="_blank" href="<?php echo urlbuilder:: guideShowRanking ("2");?>">查看完整榜单 》</a></p>
                   <div class="clear"></div>
                 </div>
              </div>
              <div class="yjh_home_top_unit">
                 <h2><span class="yjh_home_unit_shadow01">行业分类导航</span></h2>
                 <div class="yjh_home_unit_cont yjh_home_unit_shadow02 yjh_home_unit_cont_r">
                   <ul>
                       <?if($industry) {foreach($industry as $key => $val) {?>
                                <li>
                               <p><a href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('inid'=>$key));?>"><?=$val['first_name']?></a></p>
                               <span>
                                   <?if($val['secord']) {$countSecord = 0;$showCount = ($key == 2) ? 2 : 4;foreach($val['secord'] as $keyT => $valT) {if($countSecord<=$showCount) {$countSecord++;?>
                                   <a href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('inid'=>$keyT));?>"><?=$valT?></a>
                                   <?}else{?>
                                     <a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('inid'=>$key));?>">更多</a>
                                   <?break;}?>
                                   <?}}?>
                               </span>
                               <div class="clear"></div>
                               </li>
                       <?}}?>

                   </ul>
                   <div class="clear"></div>
                 </div>
              </div>
              <div class="clear"></div>
            </div>
            <div class="clear"></div>

            <div class="yjh_item_sx_list">
               <div><label class="sx_bg01"><h2><b>女性</b>创业首选项目</h2></label><span><a target="_blank" href="<?php echo urlbuilder:: guideCorwd ("1");?>">更多</a></span></div>
               <ul>
                   <?if($woman) {foreach($woman as $key => $val) {?>
               <li>
                   <a href="<?=$url = urlbuilder::project($val['project_id']);?>" target="_blank">
                   <span>
                      <strong><img src="<?=URL::imgurl($val['project_logo'])?>" alt="<?=$val['project_brand_name']?>" /></strong>
                      <em><?=$val['project_brand_name']?></em>
                      <b><?=mb_substr($val['product_features'],0,20,'UTF-8');?></b>
                   </span>
                   </a>
               </li>
                   <?}}?>

               </ul>
               <div class="clear"></div>
            </div>
            <div class="yjh_item_sx_list">
               <div><label class="sx_bg02"><h2><b>大学生</b>创业首选项目</h2></label><span><a target="_blank" href="<?php echo urlbuilder:: guideCorwd ("2");?>">更多</a></span></div>
               <ul>
               <?if($student) {foreach($student as $key => $val) {?>
               <li>
                   <a href="<?=$url = urlbuilder::project($val['project_id']);?>" target="_blank">
                   <span>
                      <strong><img src="<?=URL::imgurl($val['project_logo'])?>" alt="<?=$val['project_brand_name']?>" /></strong>
                      <em><?=$val['project_brand_name']?></em>
                      <b><?=mb_substr($val['product_features'],0,20,'UTF-8');?></b>
                   </span>
                   </a>
               </li>
                   <?}}?>

               </ul>
               <div class="clear"></div>
            </div>
            <div class="yjh_item_sx_list">
               <div><label class="sx_bg04"><h2><b>农民</b>创业首选项目</h2></label><span><a target="_blank" href="<?php echo urlbuilder:: guideCorwd ("3");?>">更多</a></span></div>
               <ul>
               <?if($farmer) {foreach($farmer as $key => $val) {?>
               <li>
                   <a href="<?=$url = urlbuilder::project($val['project_id']);?>" target="_blank">
                   <span>
                      <strong><img src="<?=URL::imgurl($val['project_logo'])?>" alt="<?=$val['project_brand_name']?>" /></strong>
                      <em><?=$val['project_brand_name']?></em>
                      <b><?=mb_substr($val['product_features'],0,20,'UTF-8');?></b>
                   </span>
                   </a>
               </li>
                   <?}}?>

               </ul>
               <div class="clear"></div>
            </div>
            <div class="yjh_item_sx_list">
               <div><label class="sx_bg03"><h2><b>白领</b>创业首选项目</h2></label><span><a target="_blank" href="<?php echo urlbuilder:: guideCorwd ("4");?>">更多</a></span></div>
               <ul>
               <?if($white) {foreach($white as $key => $val) {?>
               <li>
                   <a href="<?=$url = urlbuilder::project($val['project_id']);?>" target="_blank">
                   <span>
                      <strong><img src="<?=URL::imgurl($val['project_logo'])?>" alt="<?=$val['project_brand_name']?>" /></strong>
                      <em><?=$val['project_brand_name']?></em>
                      <b><?=mb_substr($val['product_features'],0,20,'UTF-8');?></b>
                   </span>
                   </a>
               </li>
                   <?}}?>
               </ul>
               <div class="clear"></div>
            </div>
            <div class="clear"></div>
            </div>
         <div class="yjh_item_list_r_bgbot"></div>
         <div class="clear"></div>
     </div>
     <div class="yjh_item_list_backtop"><a href="#header"><img src="<?php echo URL::webstatic('images/platform/item_list/back_top.png');?>" /></a></div>
  <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>
  <div class="clear"></div>
<script>
$(function(){
    $(".yjh_home_unit_cont").each(function(){
        $(this).find(".yjh_home_unit_licont").eq(0).show();
        $(this).find("li").hover(function(){
            $(this).children(".yjh_home_unit_licont").show();
            $(this).siblings("li").children(".yjh_home_unit_licont").hide();
        });
    });
})
</script>