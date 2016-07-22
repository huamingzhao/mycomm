<?php echo URL::webcss("platform/common.css")?>
<?php echo URL::webcss("platform/search_result.css")?>
<?php echo URL::webjs("platform/home/home.js")?>
<?php echo URL::webjs("platform/search_result.js")?>
<?php echo URL::webjs("platform/home/yellow.js");?>
<?php echo URL::webjs("platform/login/plat_login.js")?>
<?php echo URL::webcss("platform/login_new.css")?>
<?php echo URL::webcss("platform/index0514.css")?>
<?php echo URL::webcss("browse_record.css")?>
<?php echo URL::webcss("find_item.css")?>
<?php echo URL::webjs("platform/home/find_item.js")?>
<style>
.ryl_search_item_good:hover{ text-decoration:none;}
</style>
<?php /*?>
<script type="text/javascript">
function areaUrl(area_id) {
           var url = "/platform/index/search<?=  common::SearchUrl('', '', $allTag);?>&areaid="+area_id;
           window.location.href = url;
        }
$(document).ready(function(){
    $('#changeCodeImg').click(function() {
            var url = '/captcha';
                url = url+'?'+RndNum(8);
                $("#vfCodeImg1").attr('src',url);
        });
        $("#investmentStatus").click(function() {
            if($(this).attr("checked")) {
                var url = "/platform/index/search<?=  common::SearchUrl('istatus', 1, $allTag);?>";
                window.location.href = url;
            }else {
                var url = "/platform/index/search<?=  common::SearchUrl('istatus', 0, $allTag);?>";
                window.location.href = url;
            }
        })
});
<?php */?>


</script>
<!--公共背景框-->
<div class="main" style="height:auto;">
   <!--找项目-->
   <div class="find_item_main">

         <!--搜索框-->
         <div class="find_item_title">
            <!--此处播报有滚动效果-->
            <div class="find_item_bobao" style="float: right;">
                <div id="bb_list">
                     <div><span>已有<em><?php echo $projectAllNum?></em>个项目入驻一句话平台</span></div>
                    <!-- 项目丝芙兰化妆品刚刚赢得了第1312位投资者 -->
                     <?php if(!empty($project_Card_Num) && isset($project_Card_Num['roject_brand_name']) !=""  && isset($project_Card_Num['project_nums']) !=""){?>
                    	<div><span>项目<a title="<?php echo $project_Card_Num['roject_brand_name']; ?>" target="_blank" href="<?php echo urlbuilder::project($project_Card_Num['project_id']);?>"><em><?php echo $project_Card_Num['roject_brand_name'];?></em></a>刚刚赢得了第 <em><?php echo $project_Card_Num['project_nums'];?></em>位投资者</span></div>
                    <?php }?>
                   <!-- 张三刚刚查看了丝芙兰化妆品项目 -->
                    <div><span><?php if($ProjectAndPersonInfo['user_type'] ==1){echo "企业用户";}else{echo "投资者";}?><em><?php if(isset($ProjectAndPersonInfo['user_name'])){ echo  mb_substr($ProjectAndPersonInfo['user_name'],0,4,"UTF-8")."";}else{ echo "游客";} ?></em>查看了<a  title="<?php echo $ProjectAndPersonInfo['project_brand_name']?>" target="_blank" href="<?php echo urlbuilder::project($ProjectAndPersonInfo['project_id']);?>"><em><?php if(isset($ProjectAndPersonInfo['project_brand_name'])){echo mb_substr($ProjectAndPersonInfo['project_brand_name'], 0,18,"UTF-8")."...";}else{"火锅";}?></em></a>项目</span></div>
                </div>
              </div>
            <label><a href="<?php echo URL::website("")?>"><img src="<?php echo URL::webstatic("images/find_item/logo.jpg")?>" alt="一句话" /></a></label>
            <div class="find_item_search">
                <div class="item_search_cont">
                    <p><input type="text" maxlength="38" autocomplete="off" name="w" id="word" placeholder="请输入您要搜索的条件。如： 餐饮 10万 上海"></p>
                    <input type="button" class="ryl_index_searchbtn" id="inputSubmit">
                </div>
                <div class="item_search_text" style="max-width:220px;">
                    <span class="orange">热门：</span>
                   <?php if(isset($Tags)){ array_pop($Tags);foreach ($Tags AS $val){?>
                         <a href="<?php echo urlbuilder::rootDir('search').'?w='.$val?>"><?php echo $val;?></a>
                   <?php }}?>

                        
                </div>
                <div class="item_search_text" style="width:214px">
                   <?php  if(isset($history_search) && !empty($history_search)){?>
                    <?php if( count($history_search) > 0){?><span class="red">历史搜索：</span><?php }?>
                        <?php
                        	$history_search = array_slice($history_search, 0,3);
                             foreach($history_search as $v){?>
                             
                        <a href="<?php echo urlbuilder::rootDir('search').'?w='.$v?>"><?php echo $v;?></a>
                <?php }   }   ?>
                </div>
            </div>
            <div class="clear"></div>
         </div>
         <!--内容-->
         <div class="find_item_cont">
          <div class="find_item_right find_item_right01">
                <div class="find_item_r_unit">
                   <div class="find_item_r_unit_title"><h2>最近入驻好项目</h2></div>
                   <ul>
                   <?php if(isset($newProject)){$i=0;foreach ($newProject as $key=>$val){$i++?>
                       <li <?php if($i == 18){echo "class='last'";}?>>
                       <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'];?></a></span>
                       <p><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" height="80px" width="100px" alt="<?php echo $val['project_brand_name'];?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></p>
                       </li>
                   <?php }}?>
                   </ul>
                  <div class="clear"></div>
                </div>
                <div class="find_item_r_unit">
                   <div class="find_item_r_unit_title"><h2>最火赚钱好项目</h2></div>
                   <ul>
                   <?php if(isset($statisticsAll)){$i++;foreach ($statisticsAll as $key=>$val){?>
                        <li <?php if($i == 18){echo "class='last'";}?>>
                       <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'];?></a></span>
                       <p><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" height="80px" width="100px" alt="<?php echo $val['project_brand_name'];?>赚钱项目" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></p>
                       </li>
                   <?php }}?>
                   </ul>
                  <div class="clear"></div>
                </div>
                <div class="clear"></div>
             </div>
             <div class="find_item_left">
             
             <?php if(isset($arr_cookie_data['first_item'])){?>
                 <div class="browse_record_title"><span><h2>根据浏览记录为您推荐</h2></span></div>
                 <div class="browsed_record_list"><span class="cur_01 cur">您浏览过的项目</span><span class="cur_02">浏览此项目的用户也浏览了以下项目</span></div>
                 <ul class="browse_list">
                 <?php array_pop($arr_cookie_data['first_item']); $i=0;foreach ($arr_cookie_data['first_item'] as $key=>$val){$i++?>
                     <li <?php if($i== 5){ echo "class='last'";}?>>
                    <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
                     <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'];?></a></span>
                     <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                     <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8').'':"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php  echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{ echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
                     </li>
              <?php }?>
                 <div class="clear"></div>
                 </ul>
                 <div class="clear"></div>
               <?php }?>
                 <?php if(isset($arr_cookie_data['second_item']) && !empty($arr_cookie_data['second_item'])){?>
                 <ul class="browse_list">
                 <?php array_pop($arr_cookie_data['second_item']); $i=0;foreach ($arr_cookie_data['second_item'] as $key=>$val){$i++?>
                     <li <?php if($i== 5){ echo "class='last'";}?>>
                    <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
                     <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'];?></a></span>
                     <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                     <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8').'':"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php  echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{ echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
                     </li>
               <?php }?>
                 <div class="clear"></div>
                 </ul>
                 <div class="clear"></div>
               <?php }?>
               <?php if(isset($arr_cookie_data['third_item']) && !empty($arr_cookie_data['third_item'])){?>
                 <ul class="browse_list">
                 <?php array_pop($arr_cookie_data['third_item']); $i=0;foreach ($arr_cookie_data['third_item'] as $key=>$val){$i++?>
                     <li <?php if($i== 5){ echo "class='last'";}?>>
                    <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
                     <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'];?></a></span>
                     <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                     <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8').'':"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php  echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{ echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
                     </li>
               <?php }?>
                 <div class="clear"></div>
                 </ul>
                 <div class="clear"></div>
               <?php }?>
               <div class="browse_view_more"><a href="<?php echo urlbuilder::root("del");?>" target="_blank" title="查看或编辑您最近浏览过的项目">&gt;&nbsp;查看您更多的浏览记录</a></div>
                <div class="clear"></div>
                  <!-- 白领创业 -->
                 <div class="browse_record_title"><span><h2>更多供你参考的好项目</h2></span></div>
                 <div class="browsed_record_list"><a target="_blank" href="<?php echo urlbuilder:: guideCorwd ("4");?>">更多&gt;&gt;</a><span class="cur_03"><a href="<?php  echo urlbuilder:: guideCorwd ("4");?>">白领创业好项目</a></span></div>
                 <?php //echo "<pre>"; print_r($arr_peopleList);exit;?>
                 <ul class="browse_list">
                 <?php if(isset($arr_peopleList['WhiteWork'])){$i=0;foreach ($arr_peopleList['WhiteWork']['list'] as $key=>$val){$i++?>
                 <li <?php if($i == 5){echo "class='last'";}?>>
                     <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
                     <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'];?></a></span>
                     <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                     <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
                 </li>
                 <?php }} ?>
             <div class="clear"></div>
             </ul>
                 <div class="clear"></div>
                 <div class="kong_02"></div>
                 <!-- 大学生创业 -->
                 <div class="browsed_record_list"><a target="_blank" href="<?php echo urlbuilder:: guideCorwd ("2");?>">更多&gt;&gt;</a><span class="cur_03"><a href="<?php echo urlbuilder:: guideCorwd ("2");?>">大学生创业好项目</a></span></div>
                 <ul class="browse_list">
                 <?php if(isset($arr_peopleList['Collegestudents'])){$i=0;foreach ($arr_peopleList['Collegestudents']['list'] as $key=>$val){$i++?>
                 <li <?php if($i == 5){echo "class='last'";}?>>
                     <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
                     <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'];?></a></span>
                     <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                     <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
                 </li>
                 <?php }}?>
             <div class="clear"></div>
             </ul>
                 <div class="clear"></div>
                 <div class="kong_02"></div>
                 <!-- 农民工创业 -->
                 <div class="browsed_record_list"><a target="_blank" href="<?php echo urlbuilder:: guideCorwd ("3");?>">更多&gt;&gt;</a><span class="cur_03"><a href="<?php echo urlbuilder:: guideCorwd ("3");?>">农民创业好项目</a></span></div>
                 <ul class="browse_list">
                  <?php if(isset($arr_peopleList['farmers'])){$i=0;foreach ($arr_peopleList['farmers']['list'] as $key=>$val){$i++?>
                 <li <?php if($i == 5){echo "class='last'";}?>>
                     <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img  onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
                     <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'];?></a></span>
                     <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                     <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
                 </li>
                 <?php }}?>
             <div class="clear"></div>
             </ul>
                 <div class="clear"></div>
                 <div class="kong_02"></div>
                <!-- 女性创业好项目 -->
                 <div class="browsed_record_list"><a target="_blank"  href="<?php echo urlbuilder:: guideCorwd ("1");?>">更多&gt;&gt;</a><span class="cur_03"><a href="<?php echo urlbuilder:: guideCorwd ("1");?>">女性创业好项目</a></span></div>
                 <ul class="browse_list">
                <?php if(isset($arr_peopleList['women'])){$i=0;foreach ($arr_peopleList['women']['list'] as $key=>$val){$i++?>
                 <li <?php if($i == 5){echo "class='last'";}?>>
                     <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>加盟" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
                     <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'];?></a></span>
                     <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                     <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
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
<style type="text/css">
.ryl_search_jg_roll_cont div#content {
    position:relative;
    width:706px;
    height:133px;
    display:inline-block;
    overflow:hidden;
    float:left;
}
.ryl_search_jg_roll_cont div#content_list {
    position:absolute;
    width:4000px;
}
</style>
<script type="text/javascript">
$(function(){
    var page = 1;
    var i = 5; //每版放1个图片
    //向后 按钮
    $("a.next").click(function(){    //绑定click事件
         var content = $("div#content");
         var content_list = $("div#content_list");
         var v_width = content.width();
         var len = content.find("li").length;
         var page_count = Math.ceil(len / i) ;
         if( !content_list.is(":animated") ){    //判断“内容展示区域”是否正在处于动画
              if( page == page_count ){  //已经到最后一个版面了,如果再向后，必须跳转到第一个版面。
                content_list.animate({ left : '0px'}, "slow"); //通过改变left值，跳转到第一个版面
                page = 1;
              }else{
                content_list.animate({ left : '-='+(v_width-7) }, "slow");  //通过改变left值，达到每次换一个版面
                page++;
             }
         }
         return false;
   });
    //往前 按钮
    $("a.prev").click(function(){
         var content = $("div#content");
         var content_list = $("div#content_list");
         var v_width = content.width();
         var len = content.find("li").length;
         var page_count = Math.ceil(len / i) ;
         if(!content_list.is(":animated") ){    //判断“内容展示区域”是否正在处于动画
             if(page == 1 ){  //已经到第一个版面了,如果再向前，必须跳转到最后一个版面。
                content_list.animate({ left : '-='+(v_width*(page_count-1)-13) }, "slow");
                page = page_count;
            }else{
                content_list.animate({ left : '+='+(v_width-7) }, "slow");
                page--;
            }
        }
        return false;
    });
});
</script>
