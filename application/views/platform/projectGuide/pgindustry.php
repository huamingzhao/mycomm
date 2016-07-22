
<!-- 采用IE7模式解析：解决IE8 image max-length bug END-->
<?php header('X-UA-Compatible: IE=7'); ?>
<!-- 采用IE7模式解析：解决IE8 image max-length bug END-->
<?php echo URL::webcss("platform/project_guide.css")?>
<!--主体部分-->
<div class="main" style="background-color:#e3e3e3;height:auto">

  <div class="yjh_item_list" id="yjh_item_list_top">
     <!--right-->
     <div class="yjh_item_list_right">
         <div class="yjh_item_list_r_bgtop"></div>
         <div class="yjh_item_list_r_bgcenter <?php if(!$isDefault){?>yjh_item_list_r_bgcenter_1<?php }?>">

            <!--项目大全-->            
            <div class="yjh_itemlist_all_title">
               <div class="yjh_itemlist_all_title_div" style="border-bottom:none">
               <?php //var_dump($cond)?>
                   <em>行业分类</em>
                   <span id="first_item">
                   <?php //echo common::SearchUrl('inid', '0', $cond);exit;?>
                       <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('inid'=>0,'pid'=>0));?>" <?if($cond['inid'] == 0) {?>class="itemlist_all_current" <?}?>>不限</a>
                       <?$nowKey = 0;if($industry) {foreach($industry as $key => $val) {?>
                        <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('inid'=>$key,'pid'=>0));?>" <?if($cond['inid'] == $key || $cond['pid'] == $key) {$nowKey = $key;?>class="itemlist_all_current" <?}?>><?=$val['first_name']?><i>(<?=arr::get($guideNumb['noIn'],$val['first_name'], '0')?>)</i></a>
                       <?}}?>
                       <div class="clear"></div>
                   </span>
                   <div class="clear"></div>
               </div>

               <div class="itemlist_all_hot itemlist_all_erji yjh_itemlist_all_title_div" id="first_list">
                       <?if($industry) {
                           
                           foreach($industry as $key => $val) { 
                           $num = arr::get($guideNumb['noIn'],$val['first_name'], '0'); 
                           if($val['secord']) {if($nowKey == $key){
                              echo "<font style=\"display:inline\">二级分类</font>";
                               echo "<span style=\"display:inline\">";
                               
                           }else{
                              echo "<font>二级分类</font>";
                               echo "<span>";
                               
                           }
                           
                           foreach($val['secord'] as $keyT => $valT) {?>
                     <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('inid'=>$keyT,'pid'=>0));?>"
                         <?if($cond['inid'] == $keyT) {?>class="itemlist_all_current" <?}?>><?=$valT?><i>(<?if($valT != '其他123') { ?><?=arr::get($guideNumb['inPid'],strtolower($valT), '0')?><?}else{echo ($num >= 0) ? $num : 0;}?>)</i></a>
                       <?}
                       echo "</span>";}
                        }}?>
<div class="clear"></div>
                   </span>

               </div>
               <div class="yjh_itemlist_all_title_div" style="border-top:1px solid #F3F3F3;">
                   <em>投资金额</em>
                   <span>
                   <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('atype'=>0));?>" <?if($cond['atype'] == 0) {?>class="itemlist_all_current" <?}?> >不限<i></i></a>
                   <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('atype'=>1));?>"<?if($cond['atype'] == 1) {?>class="itemlist_all_current" <?}?>>5万以下<i>(<?=arr::get($guideNumb['noAt'],'5万以下', '0')?>)</i></a>
                   <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('atype'=>2));?>" <?if($cond['atype'] == 2) {?>class="itemlist_all_current" <?}?>>5-10万<i>(<?=arr::get($guideNumb['noAt'],'5-10万', '0')?>)</i></a>
                   <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('atype'=>3));?>" <?if($cond['atype'] == 3) {?>class="itemlist_all_current" <?}?>>10-20万<i>(<?=arr::get($guideNumb['noAt'],'10-20万', '0')?>)</i></a>
                   <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('atype'=>4));?>" <?if($cond['atype'] == 4) {?>class="itemlist_all_current" <?}?>>20-50万<i>(<?=arr::get($guideNumb['noAt'],'20-50万', '0')?>)</i></a>
                   <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('atype'=>5));?>" <?if($cond['atype'] == 5) {?>class="itemlist_all_current" <?}?>>50万以上<i>(<?=arr::get($guideNumb['noAt'],'50万以上', '0')?>)</i></a>
                   </span>
                  <div class="clear"></div>
               </div>
               <div class="yjh_itemlist_all_title_div">
                   <em>招商形式</em>
                   <span>
                   <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('pmodel'=>0));?>" <?if($cond['pmodel'] == 0) {?>class="itemlist_all_current" <?}?> >不限<i></i></a>
                   <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('pmodel'=>2));?>" <?if($cond['pmodel'] == 2) {?>class="itemlist_all_current" <?}?> >批发代理<i>(<?=arr::get($guideNumb['noPm'],'批发代理', '0')?>)</i></a>
                   <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('pmodel'=>1));?>" <?if($cond['pmodel'] == 1) {?>class="itemlist_all_current" <?}?> >开店加盟<i>(<?=arr::get($guideNumb['noPm'],'开店加盟', '0')?>)</i></a>
                   <a href="<?php echo urlbuilder:: fenleiCond ($cond,array('pmodel'=>3));?>" <?if($cond['pmodel'] == 3) {?>class="itemlist_all_current" <?}?> >网上销售<i>(<?=arr::get($guideNumb['noPm'],'网上销售', '0')?>)</i></a>
                   </span>
               <div class="clear"></div>
               </div>              
               <div class="clear"></div>
               <div class="yjh_itemlist_area_title03">
               <?php if($isDefault){?>
                   <span>一句话为您推荐</span><b><?=arr::get($guideNumb, 'count', 0);?></b><span>个项目</span>
               <?}elseif(!$project_list['list'] || $hasRes == 1) {?>
                   <span>抱歉！没有找到</span>
                   <?if($condName ) {foreach($condName as $val) {
                       echo "<b>{$val}</b>";
                   }
                   }?>
                   <span>的项目。您可以看看其他的项目。</span>
                   <?}else{?>
                    <span>一句话为您找到符合</span>
                    <?if($condName ) {foreach($condName as $val) {
                        echo "<b> {$val} </b>";
                    }
                    }?>
                    <span>条件的有</span><b><?=arr::get($guideNumb, 'count', 0);?></b><span>个项目</span>
                   <?}?>
                                     
                  <div class="yjh_itemlist_area_cont_order">
                  <?php if(isset($type) && isset($order) && $type == 1 && $order == 1){?>
                      <a class="oreder_option order_asc <?php if(isset($type) && isset($order) && $type == 1 && $order == 1){?>oreder_fc<?php }?>" href="<?php if(urlbuilder::fenleiCond($cond,array()) != URL::website('xiangdao/fenlei/')){echo urlbuilder::fenleiCond($cond,array())."?type=1&order=2";}else{echo urlbuilder::fenleiOrder(2);}?>">按时间<i></i></a>
                  <?php }else{?>  
                    <a class="oreder_option order_desc <?php if((isset($type) && isset($order) && $type == 1 && $order == 2) || ($type == 0 && $order == 0)){?>oreder_fc<?php }?>" href="<?php if(urlbuilder::fenleiCond($cond,array()) != URL::website('xiangdao/fenlei/')){echo urlbuilder::fenleiCond($cond,array())."?type=1&order=1";}else{echo urlbuilder::fenleiOrder(1);}?>">按时间<i></i></a>                                   
                  <?php }?> 
                  <?php if(isset($type) && isset($order) && $type == 2 && $order == 2){?>               
                      <a class="oreder_option order_desc <?php if(isset($type) && isset($order) && $type == 2 && $order == 2){?>oreder_fc<?php }?>" href="<?php if(urlbuilder::fenleiCond($cond,array()) != URL::website('xiangdao/fenlei/')){echo urlbuilder::fenleiCond($cond,array())."?type=2&order=1";}else{echo urlbuilder::fenleiOrder(3);}?>">按意向数<i></i></a>
                  <?php }else{?>
                    <a class="oreder_option order_asc <?php if(isset($type) && isset($order) && $type == 2 && $order == 1){?>oreder_fc<?php }?>" href="<?php if(urlbuilder::fenleiCond($cond,array()) != URL::website('xiangdao/fenlei/')){echo urlbuilder::fenleiCond($cond,array())."?type=2&order=2";}else{echo urlbuilder::fenleiOrder(4);}?>">按意向数<i></i></a>
                  <?php }?>
                  <div class="clear"></div>
                  </div>
                </div>
               
               <div class="clear"></div>
            </div>
            <div class="ryl_search_result_message">
              <?php if($condName){
              			$str = '';
              			foreach($condName as $v){
              				$str = $str.$v; 
              } ?>
              <h2><?php echo $str;?>加盟投资友情提示</h2>
              <p><?php echo $str;?>加盟项目是一句话商机速配网为用户精心打造、推送的<?php echo $str;?>加盟信息，大量的<?php echo $str;?>加盟项目均为正规企业上传，选择<?php echo $str;?>加盟、投资、开店就上一句话商机网。我们为您的<?php echo $str;?>加盟投资提供全面的投资保障，让您的创业投资更安全、更可靠、更容易成功！同时欢迎广大企业上传<?php echo $str;?>加盟项目，我们将以热诚的态度为您提供优质服务，第一时间审核通过大家上传的<?php echo $str;?>加盟项目。</p>
              <?php  }?>
            </div>
            <div class="yjh_itemlist_area_cont">

                <!--搜索结果列表-->
                <div class="ryl_search_result_contain">
                       <ul class="project_list1">
                        <?php if(count($project_list['list']) > 0){?>
                        <?php foreach ($project_list['list'] as $k => $v){?>
                          <li>
                            <dl>
                              <dt>
                                <p>
                                  <span>
                                    <a target="_blank" href="<?$url = urlbuilder::project($v['project_id']);$projectName = $v['product_features'];?><?= urlbuilder::project($v['project_id'])?>">
                                      <img alt="<?=UTF8::substr($v['project_brand_name'],0, 20);?>" src="<?= URL::imgurl($v['project_logo']);?>" onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')">
                                    </a>
                                  </span>
                                </p>
                                
                                <?if(arr::get($v, 'project_real_order') != 4 && arr::get($v, 'project_real_order') != 6){?>
                          <i class="project_list_baozhang"></i>
                        <?php }?>
                              </dt>
                              <?if(false){?>
                              	<dd id="searchsendcard_<?php echo $user_id."_".$v['project_id'].'_dd';?>">
                              		<a href="<?= urlbuilder::project($v['project_id'])?>" target="_blank">查看详情</a>
                              		<?if($v['viewProject']){?>
                              			<span>已收藏</span>
                              		<?}else{?>
                              			<a href="javascript:void(0)" id="watchproject_<?php echo $user_id."_".$v['project_id'];?>"  class="button_bb2" com_user_id="<?php echo $v['project_id']; ?>">收藏项目</a>
                              		<?}?>
                              	</dd>
                              <?php }else{?>
                              <dd id="searchsendcard_<?php echo $user_id."_".$v['com_user_id'].'_dd';?>">
                              		<?if($v['postCart']){?>
                              			<span>已咨询</span>
						  			<?}else{?>
						  				<a href="javascript:void(0)" id="searchsendcard_<?php echo $v['project_id']."_".$v['com_user_id']?>_1" class="searchsendcard">我要咨询</a>
						  			<?}?>
						  			<?if($v['viewProject']){?>
						  				<span>已收藏</span>
						  			<?}else{?>
						  				<a href="javascript:void(0)" id="watchproject_<?php echo $user_id."_".$v['project_id'];?>" class="button_bb2" com_user_id="<?php echo $v['com_user_id']; ?>">收藏项目</a>
						  			<?}?>
						  	</dd>
                              <?php }?>
                            </dl>
                            <div class="ryl_search_item_right content">
                              <h4 class="clearfix">
                              	<a href="<?= urlbuilder::project($v['project_id'])?>" onclick="saveSearchClick(<?=$v['project_id'];?>, 1, 1)" target="_blank" class="ryl_search_itemname" title="<?=UTF8::substr($v['project_brand_name'],0, 20);?>">
				              		<?php echo $v['project_brand_name'];?>				              	
				              	</a>
				              	<?$project_advert = $v['project_advert'];?><span><?php if(isset($project_advert) && $project_advert != ""){ ?><var>:</var><?php }?><?=$project_advert?></span>
				              	
				              </h4>
				              
                      		  
                              <span class="wantInvest">意向加盟<font><?=arr::get($v,"project_pv_count",0)?></font>人</span>
                              <span class="ryl_search_item_intro <?php if(arr::get($v, "projectImg","") == "" || count(arr::get($v, "projectImg")) == 0){echo 'ryl_search_item_no_img';}?>"> 
                              <?php if(arr::get($v, "projectImg","") !="" && count(arr::get($v, "projectImg")) > 0 ){ foreach ($v["projectImg"] as $key=>$val){?>
                              	 <a href="<?= urlbuilder::projectImages($v['project_id'])?>" target="_blank"><img alt="<?=UTF8::substr($v['project_brand_name'],0, 20);?>" src="<?= URL::imgurl($val);?>" onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')"></a>
                             	<?php }?>
                              	<b>
                              		<?php $sumary_text=$v['product_features'] ? htmlspecialchars_decode($v['product_features']) : htmlspecialchars_decode($v['project_summary']);  
                              			if($sumary_text){ echo mb_substr(strip_tags(str_replace(' ','',$sumary_text)),0,60,'UTF-8').'...';
                              		}?>
                              		
                                </b> 
                                 <?php }else{?>
                                 		<b>
                              		<?php $sumary_text=$v['product_features'] ? htmlspecialchars_decode($v['product_features']) : htmlspecialchars_decode($v['project_summary']);  
                              			if($sumary_text){ echo mb_substr(strip_tags(str_replace(' ','',$sumary_text)),0,130,'UTF-8').'...';
                              		}?>
                              		
                                </b>
                                 <?php } ?>
                              </span>
                              <span class="ryl_search_item_icon">
                              	<b class="ryl_search_item_icon01"><img src="<?php echo URL::webstatic('images/platform/item_list/icon03.jpg')?>">                          
                                  <?if($v['industryArr']){
                                  		foreach($v['industryArr'] as $keyIarr => $valIarr){?>
                                  			<a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('inid'=>$keyIarr));?>">
                                  				<?=$valIarr?>
                                  			</a>&nbsp;
                                  	<?}}else{echo "不限";}?> 
                                 <em style="display: none;">行业</em></b> 
                                <b class="ryl_search_item_icon02">
                                  <img src="<?php echo URL::webstatic('images/platform/item_list/icon04.jpg')?>">                          
                                  <?if(arr::get($v, 'project_amount_type', 0) != 0) {?>
                                  	<a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('atype'=>arr::get($v, 'project_amount_type', 0)));?>">
                                  		<i><?php $monarr= common::moneyArr(); echo  arr::get($v, 'project_amount_type') == 0 ? '不限': $monarr[arr::get($v, 'project_amount_type')];?></i>
                                  	</a>
                                  <?}else{echo "<i>不限</i>";}?>
                                  <em style="display: none;">投资金额</em>
                                </b>
                                <b class="ryl_search_item_icon03">
                                  <img src="<?php echo URL::webstatic("images/platform/item_list/icon05.jpg")?>">
                                  <?php $lst = common::businessForm();
               					  	if(count($v['projectcomodel'])){
                    					$comodel_text='';
                    					foreach ($v['projectcomodel'] as $keyT => $vT){
                        					$comodel_text[$keyT]=$lst[$vT];
                    					}
										if($comodel_text) {
											foreach($comodel_text as $keyCt => $valCt) {
									?>
										<a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('pmodel'=>$keyCt));?>"><?=$valCt?></a>&nbsp;
									<?}}else{ echo "不限";}}else{echo "不限";} ?>
                                  <em style="display: none;">招商形式</em>
                                </b>
                              </span>
                              <span class="ryl_search_item_bot">
                              <?php if(arr::get($v['issetInvestment'], "investment_id","0") > 0){?>
                              	<span class="ryl_search_item_bobao">
                                <img src="<?php echo URL::webstatic('/images/platform/search_result/icon25.jpg')?>">
                                <a href="<?php echo urlbuilder::rootDir ("touzikaocha").arr::get($v['issetInvestment'], "investment_id").".html";?>"><?= arr::get($v['issetInvestment'], "investment_start")?>&nbsp;&nbsp;<?=arr::get($v['issetInvestment'], "investment_address")?>&nbsp;&nbsp;招商会报名中</a>
                                </span>
                              <?php }?>
                                <span class="ryl_search_item_bot_right">
                                  
                                  <a href="#" class="ryl_search_item_share"></a>
                                  <a href="javascript:void(0)" proid ="<?=$v['project_id'];?>"  class="zan <?if(!$v['isApproving']){?>searchItemGood<?}?>" title="<?if($v['isApproving']){?>已赞<?}else{?>赞<?php  }?>"><?=$v['project_approving_count']?></a>
                                </span>
                              </span>
                            </div>
                            <div class="ryl_search_item_share_list" style="margin-left:310px;">
	                            <a href="#" class="ryl_search_share_sina" onclick="window.open('http://service.weibo.com/share/share.php?url=<?=$url?>&title=我在一句话发现了一个叫<?=zixun::setContentReplace($projectName);?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&appkey=1343713053&pic=<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>');return false;" >新浪微博</a>
	                            <a href="#" class="ryl_search_share_zone" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?=$url?>&title=我在一句话发现了一个叫<?=zixun::setContentReplace($projectName);?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&pics=<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>');return false;">QQ空间</a>
	                            <a href="#" onclick="{ var _t = '我在一句话发现了一个叫<?=zixun::setContentReplace($projectName);?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！';  var _url = '<?=$url?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = '<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>'; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');  };" class="ryl_search_share_qqwei">腾讯微博</a>
	                            <a href="#" class="ryl_search_share_ren" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?=$url?>&description=我在一句话发现了一个叫<?=zixun::setContentReplace($projectName);?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&appkey=1343713053&pic=<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>');return false;" >人人网</a>
	                            <div class="clear"></div>
                          </div>
                          <div class="clear"></div>
                          </li>
                          <?php }}?>
                        </ul>
                        <div class="ryl_search_result_page"><?=$project_list['page']?$project_list['page'] : $pidlist['page'];?>
                     </div>
            </div>

            <div class="clear"></div>
            </div>
         <div class="clear"></div>
     </div>
     <div class="yjh_item_list_backtop"><a href="#header"><img src="<?php echo URL::webstatic('images/platform/item_list/back_top.png');?>" /></a></div>
  <div class="clear"></div>
  </div>
     <!--left-->
     <div class="yjh_item_list_left">
         <ul>            
            <li><a href="<?php echo urlbuilder:: projectGuide ("fenlei");?>" class="item_list_left_current">分类导航</a></li>
             <li><a href="<?php echo urlbuilder:: projectGuide ("top");?>">排行榜</a></li>
            <li><a href="<?php echo urlbuilder:: projectGuide ("people");?>">按人群找</a></li>
            <li><a href="<?php echo urlbuilder:: projectGuide ("diqu");?>">按地区找</a></li>
         </ul>
          <!-- 热门项目列表 -->
         <?php if(isset($hotprojectlist) && !empty($hotprojectlist)){?>
         <dl>
           <dt><h2>热门项目</h2></dt>
           <?php foreach($hotprojectlist as $val){?>
           <dd>
             <p><span>
              <a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>" src="<?=URL::imgurl($val['project_logo']);?>" /></a>
              </span></p>
             <h4><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,8,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,8,'UTF-8')."";};?>"><?php echo mb_substr($val['project_brand_name'], 0,8,'UTF-8')."";?></a></h4>
           </dd>
           <?php }?>
         </dl> 
         <?php }?>
          <!-- 热门项目列表 END -->
     </div>
  <div class="clear"></div>
</div>
<!--递出名片层开始-->
<div id="send_box" style="z-index:999">
    <a href="#" class="close">关闭</a>
    <div id="msgcontent" class="btn">
    </div>
</div>
<!--递出名片层结束-->
<div id="opacity" style="z-index:998;"></div>
<!--索要资料弹出框-->
<div id="getcards_view" style="display: none;z-index:9999">
    <div id="getcards_view_repeat">
        <a href="#" id="getcards_view_close" class="close" title="关闭">关闭</a>
        <div id="getcards_view_content">
        	<div class="card1">
        		<div class="card1_pt">
        			<img id="tboximgae" width="110" height="100" title="头像">
        		</div>
        		<div class="card1_nm card1_show">
        			<p class="name" id="tboxrelname_span">
        			</p>
        			<p class="tel" id="tboxtel"></p>
        			<p class="email" id="tboxemail"></p>
        		</div>
        		<div class="card1_btn">
        			<a target="_blank" href="/person/member/basic/personupdate?type=2" id="checkPersonContact_644" class="view_cantant" title="修改"></a>
        		</div>
        		<div class="clear"></div>
        	</div>
        	<div class="card2">
        		<p>
        			<span class="shux">目前所在地</span>
        			<span class="zhi"><span class="zhi2" id="tboxarea"></span>
        		</p>
        		<p>
        			<span class="shux">意向投资金额</span>
        			<span class="zhi"><span class="zhi2" id="tboxmoney"></span></span>
        		</p>
        		<p>
        			<span class="shux">意向投资行业</span>
        			<span class="zhi"><span class="zhi2" id="tboxindustry"></span></span>
        		</p>
        		<p>
        			<span class="shux">意向投资地区</span>
        			<span class="zhi"><span class="zhi2" id="tboxyixiangarea"></span></span>
        		</p>
        		<p class="ask_for_content" style="padding-top:32px;">
        			<span class="shux">咨询内容</span>
        			<span class="ask_for_text">
        				<textarea id="textareacontent" maxlength="150">你们的项目很好，请速速联系我详谈</textarea><font>仅限150字</font>
        			</span>
        		</p>
        		<div class="ask_for_em">
        			<span class="shux">精选咨询内容</span>
        			<span class="ask_for_text_em">
        				<input id="ask_for_text_em1" checked="checked" name="ask_for_text_em" type="radio"/>
        				<label for="ask_for_text_em1">你们的项目很好，请速速联系我详谈</label><br/>
        				<input id="ask_for_text_em2" name="ask_for_text_em" type="radio"/>
        				<label for="ask_for_text_em2">我想了解更多你们的项目，请给我相关的加盟资料</label><br/>
        				<input id="ask_for_text_em3" name="ask_for_text_em" type="radio"/>
        				<label for="ask_for_text_em3">加盟你们的项目需要多少费用？</label><br/>
        				<input id="ask_for_text_em4" name="ask_for_text_em" type="radio"/>
        				<label for="ask_for_text_em4">请问我所在的地区有加盟商了吗？</label>
        			</span>
        			<div class="clear"></div>
        		</div>
        		<p class="ask_for_btn">
                    <input id="ask_for_send" class="" type="submit" value="发送"/>
                    <input type="hidden" value="1" id="hidtype"></input>
        		</p>
        	</div>
        </div>
    </div>
</div>
<!--索要资料弹出框 END-->

<!--个人快速注册,右边目前已有platform_num个项目，user_num个用户加入一句话注册-->

<input type="hidden" id="platform_num" value="<?=$platform_num?>" /><!--个项目-->
<input type="hidden" id="user_num" value="<?=$user_num?>" /><!--个用户加入一句话-->
<select id="address_hide" name="per_area" style="display:none">
<option value="" >不选</option>
        <?php
        if( !empty( $area ) ){
            foreach ( $area as $v ){
              
              echo   '<option value="'.$v['cit_id'].'">'.$v['cit_name'].'</option>';
           
            }
        }
        ?>
</select>
<div class="project_home_quick_register" id="quick_register" style="display:none;"></div>

<!--个人快速注册END弹出框-->

<!--文字弹出框-->
<div id="project_home_msg_box" style="z-index:9999">
    <a href="javascript:void(0)" class="nambo_box_close"></a>
	<div id="msg_content" class="msg"></div>
	<a id='project_home_msg_box_ok' class="project_home_btn1" href="javascript:void(0)" title="确定">确定</a>
</div>
<!--文字弹出框 END-->
<!--文字弹出框-->
<div id="project_home_msg_box2" style="z-index:9999">
    <a href="javascript:void(0)" class="nambo_box_close"></a>
    <div id="msg_content2" class="msg"></div>
    <a id='project_home_msg_box_ok2' class="project_home_btn1" href="javascript:void(0)" title="确定">确定</a>
</div>
<!--文字弹出框 END-->

<?php echo URL::webjs("platform/search_result.js")?>