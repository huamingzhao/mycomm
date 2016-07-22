<?php echo URL::webjs("platform/AC_RunActiveContent.js")?>
<?php echo URL::webjs("platform/home/home.js");?>
<div class="main" style="height:auto;">
   <!--浏览记录-->
   <div class="browse_record">
         <!--首页 来过-->
         <div class="browse_logo"><a href="<?php echo URL::website('');?>" title="一句话"><img src="<?php echo URL::webstatic("images/platform/browse_record/logo.jpg");?>" alt="一句话" /></a><span><img alt = "投资好项目，只要一句话" src="<?php echo URL::webstatic("images/platform/browse_record/logo_text.jpg");?>" /></span></div>
         <div class="clear"></div>
         <div class="browse_search">
            <div class="browse_search_cont">
            <FORM action="/platform/index/search" method="get" class="formStyle">
                <p>
                  <span>
                    <a class="browse_search_cont_menu" value="" href="javascript:void(0)" >找项目</a>
                    <a class="browse_search_cont_menu" value="" href="javascript:void(0)">找投资者</a>
                    <input type="hidden"  id="hiddenvalue"  value="<?php echo $src_type;?>"  />
                  </span>
                 <input type="text" placeholder="请输入您要搜索的条件。如： 餐饮 10万 上海" id="word" name="w" class="ryl_index_searchtext" autocomplete="off" maxlength="38"/>
                </p>
                <input type="button" class="ryl_index_searchbtn" id="inputSubmit">
                </FORM>
                <ul style="display:none;left:0px; top:37px; width:492px;" class="auto_list"></ul>
            </div>
            <h1 class="browse_search_text">用一句话描述您的需求，我们将为您推荐最适合的好项目。</h1>
         </div>
         <div class="browse_branch">
           <ul>
           <li class="browse_branch01">
               <a href="<?php echo urlbuilder:: projectGuide ("fenlei");?>" target="_blank" title="项目向导"><img alt="项目向导" src="<?php echo URL::webstatic("images/platform/browse_record/icon04.jpg");?>" /></a>
               <img src="<?php echo URL::webstatic("images/platform/browse_record/icon03.jpg");?>" />
               <a href="<?php echo urlbuilder:: projectGuide ("fenlei");?>" target="_blank" title="项目向导"><em>项目向导</em></a>
           </li>
           <li class="browse_branch02">
               <a href="<?php echo urlbuilder::zhaotouzi("zhaotouzi");?>" target="_blank" title="找投资者"><img alt="找投资者" src="<?php echo URL::webstatic("images/platform/browse_record/icon05.jpg");?>" /></a>
               <img src="<?php  echo URL::webstatic("images/platform/browse_record/icon03.jpg");?>" />
               <a href="<?php echo urlbuilder::zhaotouzi("zhaotouzi");?>" target="_blank" title="找投资者"><em>找投资者</em></a>
           </li>
           <li class="browse_branch03">
               <a href="<?php echo urlbuilder::rootDir ("touzikaocha");?>" target="_blank" title="投资考察"><img alt="投资考察" src="<?php echo URL::webstatic("images/platform/browse_record/icon06.jpg");?>" /></a>
               <img src="<?php echo URL::webstatic("images/platform/browse_record/icon03.jpg");?>" />
               <a href="<?php echo urlbuilder::rootDir ("touzikaocha");?>" target="_blank" title="投资考察"><em>投资考察</em></a>
           </li>
           <li class="browse_branch04">
               <a href="<?php echo urlbuilder::rootDir("zixun");?>" target="_blank" title="学做生意"><img  alt="学做生意" src="<?php  echo URL::webstatic("images/platform/browse_record/icon07.jpg");?>" /></a>
               <img src="<?php echo URL::webstatic("images/platform/browse_record/icon03.jpg");?>" />
               <a href="<?php echo urlbuilder::rootDir("zixun");?>" target="_blank" title="学做生意"><em>学做生意</em></a>
           </li>
           <li class="browse_branch05">
               <a href="<?php echo urlbuilder::qiye("denglu");?>" target="_blank" title="企业服务"><img  alt="企业服务" src="<?php echo  URL::webstatic("images/platform/browse_record/icon08.jpg");?>" /></a>
               <img src="<?php echo URL::webstatic("images/platform/browse_record/icon03.jpg")?>" />
               <a href="<?php echo urlbuilder::qiye("denglu");?>" target="_blank" title="企业服务"><em>企业服务</em></a>
           </li>
           </ul>
         </div>
<?php if(count($arr_data) == 4){?>
      <?php if(isset($arr_data['first_item'])){?>
      <div class="browse_record_title"><h2>根据浏览记录为您推荐</h2></div>
         <div class="browsed_record_list"><span class="cur_01 cur">您浏览过</span><span class="cur_02">查看此项目的用户也查看了</span></div>
         <ul class="browse_list">
         <?php $i=0;foreach ($arr_data['first_item'] as $key=>$val){$i++?>
             <li <?php if($i== 6 ){ echo " class='last'";}?>>
             <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
             <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'] ? mb_substr($val['project_brand_name'], 0,8,'UTF-8')."":"未知";?></a></span>
             <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
             <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8').'':"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php  echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{ echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
             </li>
            <?php }?>
         <div class="clear"></div>
         </ul>
         <div class="clear"></div>
         <div class="browse_view_more"><a rel="nofollow" href="<?php echo urlbuilder::root('del');?>"  title="查看或编辑您最近浏览过的项目">&gt;&nbsp;查看或编辑您最近浏览过的项目</a></div>
         <?php }?>
         <!-- 您可能喜欢的新项目开始 -->
           <div class="browse_record_title"><a href="<?php echo urlbuilder::rootDir('search');?>" target="_blank">查看更多创业投资项目 ></a><h2>您可能喜欢的创业项目</h2></div>
           <ul class="browse_list">
           <?php  $i=0; if(isset($arr_data['newProject'])){foreach ($arr_data['newProject'] as $key=>$val){$i++; ?>
             <li <?php if($i == 6){echo " class='last'";}?>>
             <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
             <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'] ? mb_substr($val['project_brand_name'], 0,8,'UTF-8')."":"未知";?></a></span>
             <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
             <div><p class="p_01"><a href="<?php echo urlbuilder::project($val['project_id']);?>" title="<?php echo $val['project_brand_birthplace'] ? $val['project_brand_birthplace'] :"未知";?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'], 0,4,'UTF-8')."":"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
             </li>
             <?php }}?>
            <div class="clear"></div>
         </ul>
         <div class="clear"></div>
         <div class="kong_01"></div>
         <!-- 您可能喜欢的新项目结束 -->
         
         <!-- 大家都喜欢的好项目开始 -->
         <div class="browse_record_title"><h2>大家都喜欢的创业项目</h2></div>
         <ul class="browse_list">
          <?php $i=0; if(isset($arr_data['newWatchProject'])){foreach ($arr_data['newWatchProject'] as $key=>$val){$i++; ?>
            <li <?php if($i == 6){echo " class='last'";}?>>
             <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img   onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')"alt="<?php echo $val['project_brand_name'];?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
             <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'] ? mb_substr($val['project_brand_name'], 0,8,'UTF-8')."":"未知";?></a></span>
             <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
             <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
             </li>
             <?php }}?>
         <div class="clear"></div>
         </ul>
         <div class="clear"></div>
         <div class="kong_01"></div>
      <div class="clear"></div>
         
         <!-- 大家都喜欢的好项目结束-->
<?php }elseif(count($arr_data) == 5){?>
	 <?php if(isset($arr_data['first_item'])){?>
      <div class="browse_record_title"><h2>根据浏览记录为您推荐</h2></div>
         <div class="browsed_record_list"><span class="cur_01 cur">您浏览过</span><span class="cur_02">查看此项目的用户也查看了</span></div>
         <ul class="browse_list">
         <?php $i=0;foreach ($arr_data['first_item'] as $key=>$val){$i++?>
             <li <?php if($i== 6 ){ echo " class='last'";}?>>
             <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img   onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')"alt="<?php echo $val['project_brand_name'];?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
             <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'] ? mb_substr($val['project_brand_name'], 0,8,'UTF-8')."":"未知";?></a></span>
             <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
             <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8').'':"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php  echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{ echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
             </li>
            <?php }?>
         <div class="clear"></div>
         </ul>
         <div class="clear"></div>
         <div class="browse_view_more"><a rel="nofollow" href="<?php echo urlbuilder::root('del');?>"  title="查看或编辑您最近浏览过的项目">&gt;&nbsp;查看或编辑您最近浏览过的项目</a></div>
         <?php }?>
		<?php if(isset($arr_data['second_item']) && !empty($arr_data['second_item'])){?>
	         <div class="browsed_record_list"><span class="cur_01 cur">您浏览过</span><span class="cur_02">查看此项目的用户也查看了</span></div>
	         <ul class="browse_list">
	             <?php $i=0;foreach ($arr_data['second_item'] as $key=>$val){$i++?>
	             <li <?php if($i== 6 ){ echo " class='last'";}?>>
	             <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img   onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')"alt="<?php echo $val['project_brand_name'];?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
	             <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'] ? mb_substr($val['project_brand_name'], 0,8,'UTF-8')."":"未知";?></a></span>
	             <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
	             <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8').'':"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php  echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{ echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
	             </li>
	             <?php }?>
	         <div class="clear"></div>
	         </ul>
	         <div class="clear"></div>
	         <div class="browse_view_more"><a rel="nofollow" href="<?php echo urlbuilder::root('del');?>" title="查看或编辑您最近浏览过的项目">&gt;&nbsp;查看或编辑您最近浏览过的项目</a></div>
	        <?php }?>
			<!-- 大家都喜欢的好项目开始 -->
		         <div class="browse_record_title"><h2>大家都喜欢的创业项目</h2></div>
		         <ul class="browse_list">
		          <?php $i=0; if(isset($arr_data['newWatchProject'])){foreach ($arr_data['newWatchProject'] as $key=>$val){$i++; ?>
		            <li <?php if($i == 6){echo " class='last'";}?>>
		             <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img   onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')"alt="<?php echo $val['project_brand_name'];?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
		             <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'] ? mb_substr($val['project_brand_name'], 0,8,'UTF-8')."":"未知";?></a></span>
		             <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
		             <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
		             </li>
		             <?php }}?>
		         <div class="clear"></div>
		         </ul>
		         <div class="clear"></div>
		         <div class="kong_01"></div>
		      <div class="clear"></div>
		         
         <!-- 大家都喜欢的好项目结束-->
<?php }else{?>
		<?php if(isset($arr_data['first_item'])){?>
	      <div class="browse_record_title"><h2>根据浏览记录为您推荐</h2></div>
	         <div class="browsed_record_list"><span class="cur_01 cur">您浏览过</span><span class="cur_02">查看此项目的用户也查看了</span></div>
	         <ul class="browse_list">
	         <?php $i=0;foreach ($arr_data['first_item'] as $key=>$val){$i++?>
	             <li <?php if($i== 6 ){ echo " class='last'";}?>>
	             <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img   onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')"alt="<?php echo $val['project_brand_name'];?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
	             <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'] ? mb_substr($val['project_brand_name'], 0,8,'UTF-8')."":"未知";?></a></span>
	             <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
	             <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8').'':"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php  echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{ echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
	             </li>
	            <?php }?>
         <div class="clear"></div>
         </ul>
         <div class="clear"></div>
         <div class="browse_view_more"><a rel="nofollow" href="<?php echo urlbuilder::root('del');?>"  title="查看或编辑您最近浏览过的项目">&gt;&nbsp;查看或编辑您最近浏览过的项目</a></div>
         <?php }?>
		<?php if(isset($arr_data['second_item']) && !empty($arr_data['second_item'])){?>
	         <div class="browsed_record_list"><span class="cur_01 cur">您浏览过</span><span class="cur_02">查看此项目的用户也查看了</span></div>
	         <ul class="browse_list">
	             <?php $i=0;foreach ($arr_data['second_item'] as $key=>$val){$i++?>
	             <li <?php if($i== 6 ){ echo " class='last'";}?>>
	             <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img   onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')"alt="<?php echo $val['project_brand_name'];?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
	             <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'] ? mb_substr($val['project_brand_name'], 0,8,'UTF-8')."":"未知";?></a></span>
	             <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
	             <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8').'':"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php  echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{ echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
	             </li>
	             <?php }?>
	         <div class="clear"></div>
	         </ul>
	         <div class="clear"></div>
	         <div class="browse_view_more"><a rel="nofollow" href="<?php echo urlbuilder::root('del');?>" title="查看或编辑您最近浏览过的项目">&gt;&nbsp;查看或编辑您最近浏览过的项目</a></div>
	        <?php }?>
			<?php if(isset($arr_data['third_item']) && !empty($arr_data['third_item'])){?>
		         <div class="browse_record_title"><h2>更多供您选择的项目</h2></div>
		         <ul class="browse_list">
		              <?php $i=0;foreach ($arr_data['third_item'] as $key=>$val){$i++ ?>
		             <li <?php if($i== 6 ){ echo " class='last'";}?>>
		             <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><img   onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')"alt="<?php echo $val['project_brand_name'];?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></p>
		             <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'] ? mb_substr($val['project_brand_name'], 0,8,'UTF-8')."":"未知";?></a></span>
		             <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
		             <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8').'':"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php  echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{ echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
		             </li>
		             <?php }?>
		         <div class="clear"></div>
		         </ul>
		         <div class="kong_01"></div>
		         <?php }?>	
<?php }?>
   </div>
   <div class="clear"></div>
</div>
