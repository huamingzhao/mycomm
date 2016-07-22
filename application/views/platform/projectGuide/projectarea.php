<!-- 采用IE7模式解析：解决IE8 image max-length bug END-->
<?php header('X-UA-Compatible: IE=7'); ?>
<!-- 采用IE7模式解析：解决IE8 image max-length bug END-->
<?php echo URL::webcss("platform/project_guide.css")?>

<body>
<!--主体部分-->
<div class="main" style="background-color:#e3e3e3;height:auto">

  <div class="yjh_item_list" id="yjh_item_list_top">
     <!--left-->
     <div class="yjh_item_list_left">
         <ul>            
            <li><a href="<?php echo urlbuilder::projectGuide ("fenlei");?>">分类导航</a></li>
            <li><a href="<?php echo urlbuilder::projectGuide ("top");?>">排行榜</a></li>
            <li><a href="<?php echo urlbuilder::projectGuide ("people");?>">按人群找</a></li>
            <li><a href="<?php echo urlbuilder::projectGuide ("diqu");?>" class="item_list_left_current">按地区找</a></li>
         </ul>
         <!-- 热门项目列表 -->
         <?php if(isset($hotprojectlist) && !empty($hotprojectlist)){?>
         <dl>
           <dt>热门项目</dt>
           <?php foreach($hotprojectlist as $val){?>
           <dd>
             <p><span>
              <a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>" src="<?=URL::imgurl($val['project_logo']);?>" /></a>
              </span></p>
             <h4><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,8,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,8,'UTF-8')."";};?>"><?php  echo mb_substr($val['project_brand_name'], 0,8,'UTF-8')."";?></a></h4>
           </dd>
           <?php }?>
         </dl> 
         <?php }?>
          <!-- 热门项目列表 END -->
     </div>
     <!--right-->
     <div class="yjh_item_list_right">
         <div class="yjh_item_list_r_bgtop"></div>
         <div class="yjh_item_list_r_bgcenter">
            <!--按地区找-->
            <div class="yjh_itemlist_area_title">
               <h1 class="yjh_itemlist_area_title_l">
                   <span>我想在</span>
                   <a href="#" class="select_area_toggle select_area_toggle_0" data-url="/ajaxcheck/getArea" first-result=".per_area_id" second-result=".per_area_id" box-title="省级" pro-flag="1" callback="areaUrl(area_id)"><?php echo isset($cityName) ? $cityName : '全国'?></a>
                   <span>找热门项目</span>
                   <p id="quanguo" style="display:none">1</p>
               </h1>
               <p class="yjh_itemlist_area_title_r">
               
               
                 <!--   <a href="#" class="competit_entrance_area_choose"><?php //echo isset($cityName) ? $cityName : '全国'?></a>   -->            </p>
            </div>
            <?php if(isset($isshow) == 1){?>
            <div class="yjh_itemlist_area_title02">
                   <span>抱歉！没有找到</span>
                   <b><?php echo isset($cityName) ? $cityName : '全国'?></b>
                   <span>地区的热门项目。您可以看看其它地区热门项目。</span>
            </div>
                <?php }?>
            <div class="yjh_itemlist_area_cont">
              <!--搜索结果列表-->
                <div class="ryl_search_result_contain">
                        <ul class="project_list1">
                        <?php foreach ($projectList['list'] as $key=>$val){?>
                          <li>
                            <dl>
                              <dt>
                                <p>
                                  <span>
                                    <a target="_blank"  href="<? echo  $url = urlbuilder::project($val['project_id']);?>">
                                      <img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?if($val['project_source'] != 1) {echo project::conversionProjectImg($val['project_source'], 'logo', $val);} else {echo URL::imgurl($val['project_logo']);}?>" alt="<?= $val['project_brand_name']?>" />
                                    </a>
                                  </span>
                                </p>
                                
                                <?if(arr::get($val, 'project_real_order') != 4 && arr::get($val, 'project_real_order') != 6){?>
                          <i class="project_list_baozhang"></i>
                        <?php }?>
                              </dt>
                              <?if(false){?>
                              <dd id="searchsendcard_<?php echo $user_id."_".$val['project_id'].'_dd';?>">
                              	<a href="<?= urlbuilder::project($val['project_id'])?>" target="_blank">查看详情</a><?if($val['viewProject']){?><span>已收藏</span>
                              <?}else{?>
                              	<a href="javascript:void(0)" id="watchproject_<?php echo $user_id."_".$val['project_id'];?>" class="button_bb2" com_user_id="<?php echo $val['project_id']; ?>">收藏项目</a><?}?></dd>
                              <?php }else{?>
                              	<dd id="searchsendcard_<?php echo $user_id."_".$val['com_user_id'].'_dd';?>">
                              		<?if($val['postCart']){?>
                              			<span>已咨询</span>
                              		<?}else{?>
                              			<a href="javascript:void(0)" id="searchsendcard_<?php echo $val['project_id']."_".$val['com_user_id']?>_1" class="searchsendcard">我要咨询</a>
                              		<?}?>
                              		<?if($val['viewProject']){?>
                              			<span>已收藏</span>
                              		<?}else{?>
                              			<a href="javascript:void(0)" id="watchproject_<?php echo $user_id."_".$val['project_id'];?>" class="button_bb2" com_user_id="<?php echo $val['com_user_id']; ?>">收藏项目</a>
                              		<?}?>
                              	</dd>
                              <?php }?>
                            </dl>
                            <div class="ryl_search_item_right content">
                              <h4 class="clearfix">
	                              <a href="<?= urlbuilder::project($val['project_id'])?>" target="_blank" class="ryl_search_itemname" title="<?=UTF8::substr($val['project_brand_name'],0, 20);?>">
	                               	<?php echo $val['project_brand_name'];?>				              	
				              	</a>
				              	<?$project_advert = $val['project_advert'];?><span><?php if(isset($project_advert) && $project_advert != ""){ ?><var>:</var><?php }?><?=$project_advert?></span>
                                 
                              </h4>
                              <span class="wantInvest">意向加盟<font><?=arr::get($val,"project_pv_count",0)?></font>人</span>
                              <span class="ryl_search_item_intro <?php if(arr::get($val, "projectImg","") == "" || count(arr::get($val, "projectImg")) == 0){echo 'ryl_search_item_no_img';}?>"> 
                              	<?php if(arr::get($val, "projectImg","") !="" && count(arr::get($val, "projectImg")) > 0){ foreach ($val["projectImg"] as $k=>$v){?>
                              	 <a href="<?= urlbuilder::projectImages($val['project_id'])?>" target="_blank"><img alt="<?=UTF8::substr($val['project_brand_name'],0, 20);?>" src="<?= URL::imgurl($v);?>" onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')"></a>
                              <?php } ?>
                             
                              	<b>
                              		<?php $sumary_text=$val['product_features'] ? htmlspecialchars_decode($val['product_features']) : htmlspecialchars_decode($val['project_summary']);  if($sumary_text){echo mb_substr(strip_tags(str_replace(' ','',$sumary_text)),0,60,'UTF-8').'...';}?>
                              	</b> 
                              	<?php }else{?>
                              	<b>
                              		<?php $sumary_text=$val['product_features'] ? htmlspecialchars_decode($val['product_features']) : htmlspecialchars_decode($val['project_summary']);  if($sumary_text){echo mb_substr(strip_tags(str_replace(' ','',$sumary_text)),0,130,'UTF-8').'...';}?>
                              	</b> 
                              	<?php }?>
                              </span>
                              
                              <span class="ryl_search_item_icon"> 
                              <b class="ryl_search_item_icon01">
                             	<IMG src="<?php echo URL::webstatic('images/platform/item_list/icon03.jpg')?>"><?if($val['industryArr']){foreach($val['industryArr'] as $keyIarr => $valIarr){?><a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('inid'=>$keyIarr));?>"><?=$valIarr?></a>&nbsp;<?}}else{echo "不限";}?> <em style="display: none;">行业</em>
                              </b> 
                              <b class="ryl_search_item_icon02">
                                  <IMG src="<?php echo URL::webstatic('images/platform/item_list/icon04.jpg')?>"><?if(arr::get($val, 'project_amount_type', 0) != 0) {?><a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('atype'=>arr::get($val, 'project_amount_type', 0)));?>"><i><?php $monarr= common::moneyArr(); echo  arr::get($val, 'project_amount_type') == 0 ? '不限': $monarr[arr::get($val, 'project_amount_type')];?></i></a><?}else{echo "<i>不限</i>";}?> <em style="display: none;">投资金额</em>
                              </b>
                                <b class="ryl_search_item_icon03">
                                  <img src="<?php echo URL::webstatic("images/platform/item_list/icon05.jpg")?>" /><?php $lst = common::businessForm();
               						if(count($val['projectcomodel'])){
                    					$comodel_text='';
                    					foreach ($val['projectcomodel'] as $keyT => $vT){
                        					$comodel_text[$keyT]=$lst[$vT];
                    					}
									if($comodel_text) {
										foreach($comodel_text as $keyCt => $valCt) {?>
									<a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('pmodel'=>$keyCt));?>"><?=$valCt?></a>&nbsp;
									<?}}else{echo "不限";}}else{echo "不限";} ?>
                                <em>招商形式</em>
                               </b>
                              </span>
                              <span class="ryl_search_item_bot">
                               <?php if(arr::get($val['issetInvestment'], "investment_id","0") > 0){?>
                              	<span class="ryl_search_item_bobao">
                                <img src="<?php echo URL::webstatic('/images/platform/search_result/icon25.jpg')?>">
                                <a href="<?php echo urlbuilder::rootDir ("touzikaocha").arr::get($val['issetInvestment'], "investment_id").".html";?>"><?= arr::get($val['issetInvestment'], "investment_start")?>&nbsp;&nbsp;<?=arr::get($val['issetInvestment'], "investment_address")?>&nbsp;&nbsp;招商会报名中</a>
                                </span>
                               <?php }?>
                                <span class="ryl_search_item_bot_right">
                                  <a href="#" class="ryl_search_item_share"></a>
                                  <a href="javascript:void(0)" proid="<?=$val['project_id']; ?>" class="zan <?if(!$val['isApproving']){?>searchItemGood<?}?>" title="<?if($val['isApproving']){?>已赞<?}else{?>赞<?php }?>"><?=$val['project_approving_count']?></a>
                                  
                                  <?php /*?>
                                  <?if(!$val['isApproving']) {?>
                               		<a href="#" class="ryl_search_item_good" proid='<?=$val['project_id']?>'><img src="<?php echo URL::webstatic('images/platform/search_result/icon15.jpg');?>" /> <em><?=$val['project_approving_count']?></em></a>
                               	 <?}else{?>
                               		<label <?if($val['isApproving']){?>title="已赞"<?}?>><img src="<?php echo URL::webstatic('images/platform/search_result/icon14.jpg');?>" /> <em><?=$val['project_approving_count']?></em></label>
                                 <?}?>
                                 <?php */?>
                                </span>
                              </span>
                            </div>
                            <div class="ryl_search_item_share_list" style="margin-left:310px;">
                            <a href="javascript::void(0)" class="ryl_search_share_sina" onClick="window.open('http://service.weibo.com/share/share.php?url=<?=$url?>&title=我在一句话发现了一个叫<?=$val['project_brand_name'];?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&appkey=1343713053&pic=<?if($val['project_source'] != 1) {echo project::conversionProjectImg($val['project_source'], 'logo', $val);} else {echo URL::imgurl($val['project_logo']);}?>');return false;" >新浪微博</a>
                            <a href="javascript::void(0)" class="ryl_search_share_zone" onClick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?=$url?>&title=我在一句话发现了一个叫<?=$val['project_brand_name'];?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&pics=<?if($val['project_source'] != 1) {echo project::conversionProjectImg($val['project_source'], 'logo', $val);} else {echo URL::imgurl($val['project_logo']);}?>');return false;">QQ空间</a>
                            <!--<a href="#"   onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?=$url?>&title=我在一句话发现了一个叫<?=$val['project_brand_name'];?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&pics=<?if($val['project_source'] != 1) {echo project::conversionProjectImg($val['project_source'], 'logo', $val);} else {echo URL::imgurl($val['project_logo']);}?>');return false;" class="ryl_search_share_qq">QQ好友</a>-->
                            <a href="javascript::void(0)" onClick="{ var _t = '我在一句话发现了一个叫<?=$val['project_brand_name'];?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！';  var _url = '<?=$url?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = '<?if($val['project_source'] != 1) {echo project::conversionProjectImg($val['project_source'], 'logo', $val);} else {echo URL::imgurl($val['project_logo']);}?>'; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');  };" class="ryl_search_share_qqwei">腾讯微博</a>
                            <a href="javascript::void(0)" class="ryl_search_share_ren" onClick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?=$url?>&description=我在一句话发现了一个叫<?=$val['project_brand_name'];?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&appkey=1343713053&pic=<?if($val['project_source'] != 1) {echo project::conversionProjectImg($val['project_source'], 'logo', $val);} else {echo URL::imgurl($val['project_logo']);}?>');return false;" >人人网</a>
                            <div class="clear"></div>
                          </div>
                          <div class="clear"></div>
					<?php }?>
                          </li>
                        </ul>
                         <div class="ryl_search_result_page">
                          <?php echo isset($projectList['page'])?$projectList['page']:"";?>
                        </div>
                         <div class="clear"></div>


                     </div>
            </div>
            <div class="clear"></div>
            </div>
         <div class="yjh_item_list_r_bgbot"></div>
         <div class="clear"></div>
     </div>
     <div class="yjh_item_list_backtop"><a href="#header"><img src="<?php echo URL::webstatic('images/platform/item_list/back_top.png')?>" /></a></div>
  <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>
<div id="send_box" style="z-index:999">
    <a href="#" class="close">关闭</a>
    <div id="msgcontent" class="btn">
    </div>
</div>
<div id="opacity" style="z-index:998;"></div>
<!--索要资料弹出框-->
<div id="getcards_view" style="display: none;z-index:9999">
    <div id="getcards_view_repeat">
        <a href="#" id="getcards_view_close" class="close" title="关闭">关闭</a>
        <div id="getcards_view_content">
        	<div class="card1">
        		<div class="card1_pt">
        			<img id="tboximgae" width="110" height="100" title="头像"/>
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
        			<span class="zhi"><span class="zhi2" id="tboxarea"></span></span>
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
</body>
<?php echo URL::webjs("platform/search_result.js")?>