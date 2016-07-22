<?php echo URL::webcss("platform/index.css")?>
<!-- 采用IE7模式解析：解决IE8 image max-length bug END-->
<?php header('X-UA-Compatible: IE=7'); ?>
<!-- 采用IE7模式解析：解决IE8 image max-length bug END-->
<!--公共背景框-->
<div class="main" style="height:auto;">
   <!--找项目-->
   <div class="find_item_main">
         <div class="find_item_cont">
              <!--右侧-->
             <div class="find_item_right">
                <div class="find_item_r_unit">
                   <div class="find_item_r_unit_title"><h2>最近入驻好项目</h2></div>
                   <ul>
                   <?php if(isset($newProject)){$i=0;foreach ($newProject as $key=>$val){$i++;if($i>18){break;}?>
                       <li <?php if($i == 18){echo "class='last'";}?>>
                       <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php  echo $val['project_brand_name'] ;?></a></span>
                       <p class="img"><span><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>" src="<?php echo URL::imgurl($val['project_logo']);?>" /></a></label></span></p>
                       </li>
                   <?php }}?>
                   </ul>
                  <div class="clear"></div>
                </div>
                <div class="find_item_r_unit">
                   <div class="find_item_r_unit_title"><h2>最火赚钱好项目</h2></div>
                   <ul>
                   <?php if(isset($statisticsAll)){$i=0;foreach ($statisticsAll as $key=>$val){$i++;if($i>18){break;}?>
                        <li <?php if($i == 18){echo "class='last'";}?>>
                       <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'];?></a></span>
                       <p class="img"><span><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>赚钱项目" src="<?php echo URL::imgurl($val['project_logo']);?>" /></a></label></span></p>
                       </li>
                   <?php }}?>
                   </ul>
                  <div class="clear"></div>
                </div>
                <div class="clear"></div>
             </div>
             <div class="find_item_left">
             <!-- 变动开始-->
             <div id="changeList">
                 <div class="browsed_record_list"><a href="<?php echo urlbuilder:: projectGuide ("fenlei");?>" target="_blank">查看更多赚钱好项目&nbsp;&gt;&gt;</a><span class="cur_03"><h2>您可能会喜欢的项目</h2></span></div>
                 <ul class="browse_list">
                  <?php  $i=0; if(isset($arr_cookie_data['YouMayLikeProject'])){foreach ($arr_cookie_data['YouMayLikeProject'] as $key=>$val){$i++;if($i>5){break;}?>
                 <li <?php if($i==5){echo "class='last'";}?>>
                 <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>加盟"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?php echo URL::imgurl($val['project_logo']);?>" /></a></label></p>
                 <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,16,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,16,'UTF-8')."";};?></a></span>
                 <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                 <div><p class="p_01"><a href="<?php echo urlbuilder::project($val['project_id']);?>" title="<?php echo $val['project_brand_birthplace'] ? $val['project_brand_birthplace'] :"未知";?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,5,'UTF-8')."":"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
                 </li>
                 <?php }}?>
             <div class="clear"></div>
             </ul>
                 <div class="clear"></div>
                 <div class="kong_02"></div>
                 <div class="browsed_record_list"><a href="<?php echo urlbuilder:: projectGuide ("fenlei");?>" target="_blank">查看更多赚钱好项目&nbsp;&gt;&gt;</a><span class="cur_03"><h2>大家都喜欢的项目</h2></span></div>
                 <ul class="browse_list">
                 <?php $i=0; if(isset($arr_cookie_data['EveryOneMayLikeProject'])){foreach ($arr_cookie_data['EveryOneMayLikeProject'] as $key=>$val){$i++;if($i>5){break;}?>
                    <li <?php if($i == 5){echo "class='last'";}?>>
                     <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>加盟"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?php echo URL::imgurl($val['project_logo']);?>" /></a></label></p>
                     <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,16,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,16,'UTF-8')."";};?></a></span>
                     <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                     <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,5,'UTF-8')."" :"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
                     </li>
                 <?php }}?>
             <div class="clear"></div>
             </ul>
                 <div class="clear"></div>
                 <div class="kong_02"></div>
            </div>
            <!-- 变动结束 -->
                 <!-- 白领创业 -->
                 <div class="browse_record_title"><span><h2>更多供你参考的好项目</h2></span></div>
                 <div class="browsed_record_list"><a target="_blank" href="<?php echo urlbuilder:: guideCorwd ("4");?>">更多&gt;&gt;</a><span class="cur_03"><a href="<?php echo urlbuilder:: guideCorwd ("4");?>">白领创业好项目</a></span></div>
                 <?php //echo "<pre>"; print_r($arr_peopleList);exit;?>
                 <ul class="browse_list">
                 <?php if(isset($arr_peopleList['WhiteWork'])){$i=0;foreach ($arr_peopleList['WhiteWork'] as $key=>$val){$i++;if($i>5){break;}?>
                 <li <?php if($i == 5){echo "class='last'";}?>>
                     <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>加盟"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?php echo URL::imgurl($val['project_logo']);?>" /></a></label></p>
                     <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,8,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,8,'UTF-8')."";};?></a></span>
                     <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                     <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,5,'UTF-8')."" :"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
                 </li>
                 <?php }} ?>
             <div class="clear"></div>
             </ul>
                 <div class="clear"></div>
                 <div class="kong_02"></div>
                 <!-- 大学生创业 -->
                 <div class="browsed_record_list"><a target="_blank" href="<?php echo urlbuilder:: guideCorwd ("2");?>">更多&gt;&gt;</a><span class="cur_03"><a href="<?php echo urlbuilder:: guideCorwd ("2");?>">大学生创业好项目</a></span></div>
                 <ul class="browse_list">
                 <?php if(isset($arr_peopleList['Collegestudents'])){$i=0;foreach ($arr_peopleList['Collegestudents'] as $key=>$val){$i++;if($i>5){break;}?>
                 <li <?php if($i == 5){echo "class='last'";}?>>
                     <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>加盟"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?php echo URL::imgurl($val['project_logo']);?>" /></a></label></p>
                     <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,8,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,8,'UTF-8')."";};?></a></span>
                     <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                     <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,5,'UTF-8')."" :"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
                 </li>
                 <?php }}?>
             <div class="clear"></div>
             </ul>
                 <div class="clear"></div>
                 <div class="kong_02"></div>
                 <!-- 农民工创业 -->
                 <div class="browsed_record_list"><a target="_blank" href="<?php echo urlbuilder:: guideCorwd ("3");?>">更多&gt;&gt;</a><span class="cur_03"><a href="<?php echo urlbuilder:: guideCorwd ("3");?>">农民创业好项目</a></span></div>
                 <ul class="browse_list">
                  <?php if(isset($arr_peopleList['farmers'])){$i=0;foreach ($arr_peopleList['farmers'] as $key=>$val){$i++;if($i>5){break;}?>
                 <li <?php if($i == 5){echo "class='last'";}?>>
                     <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>加盟"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?php echo URL::imgurl($val['project_logo']);?>" /></a></label></p>
                     <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,8,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,8,'UTF-8')."";};?></a></span>
                     <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                     <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,5,'UTF-8')."" :"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
                 </li>
                 <?php }}?>
             <div class="clear"></div>
             </ul>
                 <div class="clear"></div>
                 <div class="kong_02"></div>
                <!-- 女性创业好项目 -->
                 <div class="browsed_record_list"><a target="_blank"  href="<?php echo urlbuilder:: guideCorwd ("1");?>">更多&gt;&gt;</a><span class="cur_03"><a href="<?php echo urlbuilder:: guideCorwd ("1");?>">女性创业好项目</a></span></div>
                 <ul class="browse_list">
                <?php if(isset($arr_peopleList['women'])){$i=0;foreach ($arr_peopleList['women'] as $key=>$val){$i++;if($i>5){break;}?>
                 <li <?php if($i == 5){echo "class='last'";}?>>
                     <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>加盟"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?php echo URL::imgurl($val['project_logo']);?>" /></a></label></p>
                     <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,8,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,8,'UTF-8')."";};?></a></span>
                     <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                     <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,5,'UTF-8')."" :"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
                 </li>
                 <?php }}?>
             <div class="clear"></div>
             </ul>
                 <div class="clear"></div>
                 <div class="clear"></div>
             </div>
             <div class="clear"></div>
         </div>
         <div class="clear"></div>
   </div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>
<?php echo URL::webjs("jquery.cookies.2.2.0.js")?>
<?php echo URL::webjs("platform/search_result.js")?>
<script type="text/javascript">
getScanRecommend();//获取根据浏览记录推荐项目
$(document).ready(function(){
     $('.change_other').live('click', function() {
        var url = "/platform/ajaxcheck/getTags";
        $.post(url,function(data){
            var content  ="<span class='orange'>热门：</span>";
            for(var i=0;i<5;i++){
                content += "<a href='<?php echo urlbuilder::rootDir('search');?>"+"?w="+data[i]+"'>"+data[i]+"</a>";
            }
            /*content += "<a href='javascript:void(0)' class='change_other'>换一组</a>";*///删除多余的换一组
            $(".item_search_text").html(content);
        },'json')
    })
})
</script>