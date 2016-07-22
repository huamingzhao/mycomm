<!-- 采用IE7模式解析：解决IE8 image max-length bug END-->
<?php header('X-UA-Compatible: IE=7'); ?>
<!-- 采用IE7模式解析：解决IE8 image max-length bug END-->
<?php echo URL::webcss("platform/index.css");?>
<style>
.ryl_search_item_good:hover {
    text-decoration: none;
}
</style>
<!--公共背景框-->
<div class="main" style="height:auto;">
    <!--找项目-->
    <div class="find_item_main">
        <!--内容-->
        <div class="find_item_cont">
             <div class="find_item_left find_item_left01">
                  <div class="item_cont_title01">
                      <a class="conditions" href="#">条件筛选</a>
                      <label id="hide" style="display:block;"><img src="<?php echo URL::webstatic('images/find_invester/icon02.jpg')?>" /></label>
                    <label id="show"><img src="<?php echo URL::webstatic('images/find_invester/icon03.jpg')?>" /></label>
                    <em class="invester_icon_01"><img src="<?php echo URL::webstatic('images/find_invester/icon01.jpg')?>" /></em>
                  <?php if(!empty($correctWord) && $project_list['total_count'] < 20){?>
                    <span>您要找的是不是：<?php foreach($correctWord as $v){?><a style="float:none;" href="<?php echo urlbuilder::rootDir('search').'?w='.$v['cword'];?>"><em style="text-decoration:underline;"><?php echo $v['cword'];?></em></a><?php }?></span>
                   <?php }elseif(count($project_list['list']) > 0){?>
                    <span>一句话为您找到符合<em><?php
                        if(mb_strlen($selectvalue)>15){
                            echo mb_substr($selectvalue,0,15,'UTF-8').'...';
                      }
                      else{
                          echo $selectvalue;
                      }?></em>条件的有<em class="invester_num"><?=$project_list['total_count'];?></em>个项目</span>
                          <?php }else{?>
                                <?php if($selectvalue){?>
                                <span>抱歉！没有找到<em><?php
                                if(mb_strlen($selectvalue)>15){
                                    echo mb_substr($selectvalue,0,15,'UTF-8').'...';
                              }
                              else{
                                  echo $selectvalue;
                              }?></em>相关的项目，看看最受欢迎的热门项目吧。</span>
                              <?php }else{ echo '<span>根据您的条件搜索心仪的项目吧</span>';}?>
                        <?php }?>
                </div>
                <input type="hidden" id="words_id" value="<?php if($wordShow){echo $wordShow; }else{ echo '';}?>">
                <div class="item_cont_title02"  style="display:block;">
                    <form action="/search/" method="get" class="formStyle" id="formStyle2">
                    <ul>
                    <li class="item_cont_title02_szd" style="position: relative;"><em style="float: left;font-style: normal;line-height: 27px;">招商地区：</em>
                     <div class="ryl_search_label_right" style="">
                                    <div class="ryl_search_result_jia_cont" style="z-index:9999;">
                                    </div>
                                   <a href="javascript:;" style="width:100px;" class="select_area_toggle select_area_toggle_0" data-url="/ajaxcheck/getArea" first-result=".per_area_id" second-result=".per_area_id" box-title="省级"><?php if(isset($keyList['allow'][2])) {$areaName = array_slice($keyList['allow'][2], -1);$areaName_v= arr::get($areaName, 0, '');echo $areaName_v;}else{$areaName_v='';echo '不限';}?></a>
                                    <input type="hidden" value="" class="per_area_id" name="per_area_id">
                                </div>

                               <div class="clear">
                               </div>
                        </li>
                        <li>行业：<select id="parent_id" name="parent_id"><option value="">不限</option>
                          <?php if(isset($keyList['allow'][6])) { $testv=$keyList['allow'][6];}else{$testv=array();}
                          $primarylist = common::primaryIndustry(0); foreach ($primarylist as $k=>$v):?>
                                <option value="<?=$v->industry_id;?>" <?php if(arr::get($postlist,'parent_id')==$v || in_array($v->industry_name,$testv)): echo 'selected="selected"';endif;?> ><?=$v->industry_name;?></option>
                                <?php endforeach; ?>
                         </select></li>
                        <li>投资金额：<select id="per_amount" name="per_amount"><option value="">不限</option>
                        <?php $money = arr::get($allTag, 'atype', 0);$moneylist = common::moneyArr(); foreach ($moneylist as $k=>$v): ?>
                                        <option  value="<?=$k;?>" <?php if(arr::get($postlist,'per_amount')==$k || $money==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                        <?php endforeach; ?>
                         </select></li>
                    </ul>
                    <input id="cz" type="submit"  value="" class="select_btn" />
                    </form>
                </div>

                <!--搜索结果列表-->
                 <div class="ryl_search_result_contain">
                        <ul class="project_list1">                          
                     <?php
                        if(count($project_list['list']) > 0){
                            $getPage = arr::get($_GET, 'page', 1) ? arr::get($_GET, 'page', 1)-1 : 0;$countList = 0;
                        ?>
                        <?php
                        //echo "<pre>"; print_r($project_list['list']);exit;
                        $countList = 0;                        
                        foreach ($project_list['list'] as $k => $v){
                            $countList++;
                        ?>
                                <li>
                                <dl>
                                    <dt>
                                      <p>
                                        <span>
                                        <a target="_blank" onclick="saveSearchClick(<?=$v['project_id']?>, <?=$countList+$getPage*20;?>, 2);" 
                                            href="<?$url = urlbuilder::project($v['project_id']);?><?= urlbuilder::project($v['project_id'])?>"><img
                                            alt="<?$projectName = $v['project_brand_name'];?><?=UTF8::substr($v['project_brand_name'],0, 20);?>加盟"
                                            src="<?if($v['project_source'] != 1) {$img =  project::conversionProjectImg($v['project_source'], 'logo', $v);} echo URL::imgurl($v['project_logo']);?>"  onerror="$(this).attr('src', '<?= URL::webstatic("/images/quickrelease/company_default.png");?>')" />
                                        </a>
                                        </span>
                                      </p>
                                      	<?if(!$v['isApproving']) {?>
                                        
                                    	<?php }else{?>
                                    	<a href="javascript:void(0)" class="zan" title="已赞"><?=$v['project_approving_count']?></a>
                                    	<?php }?>
                                      <?if(arr::get($v, 'project_real_order') != 4 && arr::get($v, 'project_real_order') != 6){?>
                              <i class="project_list_baozhang"></i><?}?>
                                    </dt>
                        <?if(false){?><dd
                                        id="searchsendcard_<?php echo $user_id."_".$v['project_id'].'_dd';?>">
                                        <a href="<?= urlbuilder::project($v['project_id'])?>" 
                                            target="_blank" rel="nofollow">查看详情</a><?if($v['viewProject']){?><span>已收藏</span><?}else{?><a
                                            href="javascript:void(0)"
                                            id="watchproject_<?php echo $user_id."_".$v['project_id'];?>"
                                            class="button_bb2"
                                            com_user_id="<?php echo $v['project_id']; ?>" rel="nofollow">收藏项目</a><?}?></dd> <?}else{?><dd
                                        id="searchsendcard_<?php echo $user_id."_".$v['com_user_id'].'_dd';?>"><? if($v['postCart']){?><span>已咨询</span><?}else{?><a
                                            href="javascript:void(0)"
                                            id="searchsendcard_<?php echo $v['project_id']."_".$v['com_user_id'].'_1'?>"
                                            class="searchsendcard" rel="nofollow">我要咨询</a><?}?><?if($v['viewProject']){?><span>已收藏</span><?}else{?><a
                                            href="javascript:void(0)"
                                            id="watchproject_<?php echo $user_id."_".$v['project_id'];?>"
                                            class="button_bb2"
                                            com_user_id="<?php echo $v['com_user_id']; ?>" rel="nofollow">收藏项目</a><?}?></dd><?}?>
                      </dl>
                      	<div class="ryl_search_item_right content">
                      		<h4 class="clearfix">
								<a href="<?= urlbuilder::project($v['project_id'])?>" onclick="saveSearchClick(<?=$v['project_id']?>, <?=$countList+$getPage*20;?>, 1)" 
                                        target="_blank" class="ryl_search_itemname">
                                        <?$projectName = arr::get(arr::get(arr::get($match, $v['project_id'], array()),'val', array()), 'projectBrandName', '');if($projectName){echo $projectName;}else{?><?$projectName = $v['project_brand_name'];?><?=$projectName;?><?}?>
                            	</a>                            	
                            	<?$project_advert = ''; $project_advert = arr::get(arr::get(arr::get($match, $v['project_id'], array()),'val', array()), 'projectAdvert', '');$project_advert = $project_advert ? $project_advert : $v['project_advert'];?><span><?php if(isset($project_advert) && $project_advert != ""){ ?><var>:</var><?php }?><?=$project_advert?></span> 
                            	 
                      		</h4>
                          <span class="wantInvest">意向加盟<font><?php echo isset($v['project_pv_count']) ? $v['project_pv_count'] : 0;?></font>人</span>
                      		<span class="ryl_search_item_intro <?$summaryLath = 70;if(!$v['projectImg']){ $summaryLath = 90;?>ryl_search_item_no_img<?}?>">
                              <?if($v['projectImg']) { foreach($v['projectImg'] as $valP) {?><a
                                        href="<?php echo urlbuilder::projectImages($v['project_id']);?>"
                                        target="_blank"><img
                                            alt="<?$projectName = $v['project_brand_name'];?><?=UTF8::substr($v['project_brand_name'],0, 20);?>图片"
                                            src="<?=URL::imgurl($valP);?>" onerror="$(this).attr('src', '<?= URL::webstatic("/images/quickrelease/company_default.png");?>')" /></a><?}}?>
                            <b><?php $sumary_text = arr::get(arr::get(arr::get($match, $v['project_id'], array()),'val', array()), 'productFeatures', '') ? arr::get(arr::get(arr::get($match, $v['project_id'], array()),'val', array()), 'productFeatures', '') : arr::get(arr::get(arr::get($match, $v['project_id'], array()),'val', array()), 'projectSummary', '');if(!$sumary_text) {$sumary_text=$v['product_features'] ? htmlspecialchars_decode($v['product_features']) : htmlspecialchars_decode($v['project_summary']);  if($sumary_text){echo mb_substr(strip_tags(str_replace(' ','',$sumary_text)),0,$summaryLath,'UTF-8').'...';}}else{echo $sumary_text;}?></b>

                        	</span>
                        	<span class="ryl_search_item_icon"><b
                                        class="ryl_search_item_icon01"><img
                                            src="<?php echo URL::webstatic('images/platform/item_list/icon03.jpg')?>" /><?if($v['industryArr']){foreach($v['industryArr'] as $keyIarr => $valIarr){?><a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('inid'=>$keyIarr));?>"><?php if(isset($allTag['inid']) && $allTag['inid'] == $keyIarr){echo "<i>".$valIarr."</i>";}else{echo $valIarr;}?></a>&nbsp;<?}}else{echo "不限";}?><em
                                            style="display: none;">行业</em></b><b
                                        class="ryl_search_item_icon02"><img
                                            src="<?php echo URL::webstatic('images/platform/item_list/icon04.jpg')?>" /><?if(arr::get($v, 'project_amount_type', 0) != 0){?><a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('atype'=>arr::get($v, 'project_amount_type', 0)));?>"><i><?php $monarr= common::moneyArr(); if(arr::get($v, 'project_amount_type') != 0){if(isset($allTag['atype']) && $allTag['atype'] == arr::get($v, 'project_amount_type')){echo "<i>".$monarr[arr::get($v, 'project_amount_type')]."</i>";}else{echo $monarr[arr::get($v, 'project_amount_type')];}}?></i></a><?}else{echo "不限";}?><em
                                            style="display: none;">投资金额</em></b><b
                                        class="ryl_search_item_icon03"><img
                                            src="<?php echo URL::webstatic('images/platform/item_list/icon05.jpg')?>" /><?php $lst = common::businessForm();
               if(count($v['projectcomodel'])){
                    $comodel_text='';
                    foreach ($v['projectcomodel'] as $keyT => $vT){
                        $comodel_text[$keyT]=$lst[$vT];
                    }
                if($comodel_text) {
                    foreach($comodel_text as $keyCt => $valCt) {
                        ?>
                        <a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('pmodel'=>$keyCt));?>"><?php if(isset($allTag['pmodel']) && $allTag['pmodel'] == $keyCt){echo "<i>".$valCt."</i>";}else{echo $valCt;}?></a>&nbsp;
                        <?
                    }

                }else{
                    echo "不限";
                }
                }else{echo "不限";} ?><em style="display: none;">招商形式</em></b>
                			</span>
                			<span class="ryl_search_item_bot"> <span
                                        class="ryl_search_item_bobao"><?if($v['issetInvestment'] && isset($v['issetInvestment']['investment_id']) && $v['issetInvestment']['investment_id'] > 0) {?><img
                                            src="<?php echo URL::webstatic('images/platform/search_result/icon25.jpg');?>" /><a
                                            href="<?= urlbuilder::projectInvest($v['issetInvestment']['investment_id']) ;?>"><?echo date('n月j日',strtotime(str_replace('.', '-', $v['issetInvestment']['investment_start']))).'-'.date('n月j日',strtotime(str_replace('.', '-', $v['issetInvestment']['investment_end'])));?>&nbsp;&nbsp;<?=$v['issetInvestment']['investment_name']?></a><?}?></span>
                                        <span class="ryl_search_item_bot_right"> <a href="#"
                                            class="ryl_search_item_share"></a>
                                            <a href="javascript:void(0)" class="zan searchItemGood" title="赞" proid="<?=$v['project_id']?>"><?=$v['project_approving_count']?></a>                            
                             </span>
                             </span>
                      	</div>
                                
                                <div class="ryl_search_item_share_list" style="margin-left:175px;">

                                    <a href="#" class="ryl_search_share_sina"
                                        onclick="window.open('http://service.weibo.com/share/share.php?url=<?=$url?>&title=我在一句话发现了一个叫<?=$v['project_brand_name'];?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&appkey=1343713053&pic=<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>');return false;">新浪微博</a>
                                    <a href="#" class="ryl_search_share_zone"
                                        onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?=$url?>&title=我在一句话发现了一个叫<?=$v['project_brand_name'];?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&pics=<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>');return false;">QQ空间</a>
                                    <!--<a href="#"   onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?=$url?>&title=我在一句话发现了一个叫<?=$v['project_brand_name'];?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&pics=<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>');return false;" class="ryl_search_share_qq">QQ好友</a>-->
                                    <a href="#"
                                        onclick="{ var _t = '我在一句话发现了一个叫<?=$v['project_brand_name'];?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！';  var _url = '<?=$url?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = '<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>'; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');  };"
                                        class="ryl_search_share_qqwei">腾讯微博</a> <a href="#"
                                        class="ryl_search_share_ren"
                                        onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?=$url?>&description=我在一句话发现了一个叫<?=$v['project_brand_name'];?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&appkey=1343713053&pic=<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>');return false;">人人网</a>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>

                            </li>
                        <?}}else{?>
                        <?if(count($project_list['list_make_up']) > 0){ $getPage = arr::get($_GET, 'page', 1) ? arr::get($_GET, 'page', 1)-1 : 0;$countList = 0;?>
                     <?php
                        foreach ($project_list['list_make_up'] as $k => $v){
                            $countList ++;
                        ?>
                    <li>
                                <dl>
                                    <dt>
                                        <a  onclick="saveSearchClick(<?=$v['project_id']?>, <?=$countList+$getPage*20;?>, 2)" target="_blank"
                                            href="<?$url = urlbuilder::project($v['project_id']);?><?$projectName = $v['project_brand_name'];?><?= urlbuilder::project($v['project_id'])?>"><img
                                            alt="<?$projectName = $v['project_brand_name'];?><?=UTF8::substr($v['project_brand_name'],0, 20);?>加盟"
                                            src="<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>" />
                                        </a>
                                         
                                        <?if(arr::get($v, 'project_real_order') != 4 && arr::get($v, 'project_real_order') != 6){?>
                                  <i class="project_list_baozhang"></i><?}?> 
                                    </dt>
                                    <dd
                                        id="searchsendcard_<?php echo $user_id."_".$v['com_user_id'].'_dd';?>"><?if($v['postCart']){?><span>已咨询</span><?}else{?><a
                                            href="javascript:void(0)"
                                            id="searchsendcard_<?php echo $v['project_id']."_".$v['com_user_id'].'_1'?>"
                                            class="searchsendcard" rel="nofollow">我要咨询</a><?}?>
                                            <?if($v['viewProject']){?><span>已收藏</span><?}else{?><a
                                            href="javascript:void(0)"
                                            id="watchproject_<?php echo $user_id."_".$v['project_id'];?>"
                                            class="button_bb2"
                                            com_user_id="<?php echo $v['com_user_id']; ?>" rel="nofollow">收藏项目</a><?}?></dd>
                                </dl>
                                <div class="ryl_search_item_right content">
                                	<h4 class="clearfix">
                                		<a onclick="saveSearchClick(<?=$v['project_id']?>, <?=$countList+$getPage*20;?>, 1)" 
                                        href="<?= urlbuilder::project($v['project_id'])?>"
                                        target="_blank" class="ryl_search_itemname"><?$projectName = arr::get(arr::get(arr::get($match, $v['project_id'], array()),'val', array()), 'projectBrandName', '');if($projectName){echo $projectName;}else{?><?$projectName = $v['project_brand_name'];?><?=$projectName;?><?}?></a>
                                        
                            	<?$project_advert = ''; $project_advert = arr::get(arr::get(arr::get($match, $v['project_id'], array()),'val', array()), 'projectAdvert', '');$project_advert = $project_advert ? $project_advert : $v['project_advert'];?><span><?php if(isset($project_advert) && $project_advert != ""){ ?><var>:</var><?php }?><?=$project_advert?></span> 
                            	
                                	</h4>
                                  <span class="wantInvest">意向加盟<font><?php echo isset($v['project_pv_count']) ? $v['project_pv_count'] : 0;?></font>人</span>
                                	<span class="ryl_search_item_intro <?$summaryLath = 70;if(!$v['projectImg']){ $summaryLath = 90;?>ryl_search_item_no_img<?}?>">
                              <?if($v['projectImg']) { foreach($v['projectImg'] as $valP) {?><a
                                        href="<?php echo urlbuilder::projectImages($v['project_id']);?>"
                                        target="_blank"><img
                                            alt="<?$projectName = $v['project_brand_name'];?><?=UTF8::substr($v['project_brand_name'],0, 20);?>图片"
                                            src="<?=$valP?>" onerror="$(this).attr('src', '<?= URL::webstatic("images/quickrelease/company_default.png");?>')" /></a><?}}?>
                            <b><?php $sumary_text=trim(htmlspecialchars_decode($v['product_features']));  echo mb_substr(strip_tags(str_replace('\r','',str_replace("\n",'',$sumary_text))),0,$summaryLath,'UTF-8').'...';?></b>
                                    </span>
                                    <span class="ryl_search_item_icon"><b
                                        class="ryl_search_item_icon01"><img
                                            src="<?php echo URL::webstatic('images/platform/item_list/icon03.jpg')?>" /><?if($v['industryArr']){foreach($v['industryArr'] as $keyIarr => $valIarr){?><a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('inid'=>$keyIarr));?>"><?php if(isset($allTag['inid']) && $allTag['inid'] == $keyIarr){echo "<i>".$valIarr."</i>";}else{echo $valIarr;}?></a>&nbsp;<?}}else{echo "不限";}?><em
                                            style="display: none;">行业</em></b><b
                                        class="ryl_search_item_icon02"><img
                                            src="<?php echo URL::webstatic('images/platform/item_list/icon04.jpg')?>" /><?if(arr::get($v, 'project_amount_type', 0) != 0){?><a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('atype'=>arr::get($v, 'project_amount_type', 0)));?>"><i><?php $monarr= common::moneyArr(); if(arr::get($v, 'project_amount_type') != 0){if(isset($allTag['atype']) && $allTag['atype'] == arr::get($v, 'project_amount_type')){echo "<i>".$monarr[arr::get($v, 'project_amount_type')]."</i>";}else{echo $monarr[arr::get($v, 'project_amount_type')];}}?></i></a><?}else{echo "不限";}?><em
                                            style="display: none;">投资金额</em></b><b
                                        class="ryl_search_item_icon03"><img
                                            src="<?php echo URL::webstatic('images/platform/item_list/icon05.jpg')?>" /><?php $lst = common::businessForm();
               if(count($v['projectcomodel'])){
                    $comodel_text='';
                    foreach ($v['projectcomodel'] as $keyT => $vT){
                        $comodel_text[$keyT]=$lst[$vT];
                    }
                if($comodel_text) {
                    foreach($comodel_text as $keyCt => $valCt) {
                        ?>
                        <a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('pmodel'=>$keyCt));?>"><?php if(isset($allTag['pmodel']) && $allTag['pmodel'] == $keyCt){echo "<i>".$valCt."</i>";}else{echo $valCt;}?></a>&nbsp;
                        <?
                    }

                }else{
                    echo "不限";
                }
                }else{echo "不限";} ?><em style="display: none;">招商形式</em></b>
                					</span>
                					<span class="ryl_search_item_bot"> <span
                                        class="ryl_search_item_bobao"><?if($v['issetInvestment'] && isset($v['issetInvestment']['investment_id']) && $v['issetInvestment']['investment_id'] > 0) {?><img
                                            src="<?php echo URL::webstatic('images/platform/search_result/icon25.jpg');?>" /><a
                                            href="<?= urlbuilder::projectInvest($v['issetInvestment']['investment_id']) ;?>"><?echo date('n月j日',strtotime(str_replace('.', '-', $v['issetInvestment']['investment_start']))).'-'.date('n月j日',strtotime(str_replace('.', '-', $v['issetInvestment']['investment_end'])));?>&nbsp;&nbsp;<?=$v['issetInvestment']['investment_name']?></a><?}?></span>
                                        <span class="ryl_search_item_bot_right"> <a href="#"
                                            class="ryl_search_item_share"></a>
                                            <a href="javascript:void(0)" class="zan searchItemGood" title="赞" proid="<?=$v['project_id']?>"><?=$v['project_approving_count']?></a>
                        
                             </span>
                                    </span>
                                </div>
                                
                                <div class="ryl_search_item_share_list" style="margin-left:175px;">
                                    <a href="#" class="ryl_search_share_sina"
                                        onclick="window.open('http://service.weibo.com/share/share.php?url=<?=$url?>&title=我在一句话发现了一个叫<?=$projectName;?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&appkey=1343713053&pic=<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>');return false;">新浪微博</a>
                                    <a href="#" class="ryl_search_share_zone"
                                        onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?=$url?>&title=我在一句话发现了一个叫<?=$projectName;?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&pics=<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>');return false;">QQ空间</a>
                                    <!--<a href="#"   onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?=$url?>&title=我在一句话发现了一个叫<?=$projectName;?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&pics=<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>');return false;" class="ryl_search_share_qq">QQ好友</a>-->
                                    <a href="#"
                                        onclick="{ var _t = '我在一句话发现了一个叫<?=$projectName;?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！';  var _url = '<?=$url?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = '<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>'; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');  };"
                                        class="ryl_search_share_qqwei">腾讯微博</a> <a href="#"
                                        class="ryl_search_share_ren"
                                        onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?=$url?>&description=我在一句话发现了一个叫<?=$projectName;?>的好项目，很靠谱，你也来看看吧。创业投资不迷茫，海量项目任你挑，找项目就是一句话的事！&appkey=1343713053&pic=<?if($v['project_source'] != 1) {echo project::conversionProjectImg($v['project_source'], 'logo', $v);} else {echo URL::imgurl($v['project_logo']);}?>');return false;">人人网</a>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>

                            </li>
                        <?}}?>
                        <?}?>
                    </ul>

                        <div class="ryl_search_result_page">
                        <?=$project_list['page'];?>
                    </div>

                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <!--右侧-->
                <div class="find_item_right">
                    <div class="find_item_r_unit">
                        <div class="find_item_r_unit_title"><h2>最近入驻好项目</h2></div>
                        <ul>
                            <?php if(isset($newProject)){$newProject=array_slice($newProject,0,10);$i=0;foreach ($newProject as $key=>$val){$i++?>
                           <li <?php if($i == 18){echo "class='last'";}?>>
                           <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'];?></a></span>
                           <p class="img"><span><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>开店"><img alt="<?php echo $val['project_brand_name'];?>开店" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></span></p>
                           </li>
                               <?php }}?>
                        </ul>
                        <div class="clear"></div>
                    </div>
                    <div class="find_item_r_unit">
                        <div class="find_item_r_unit_title"><h2>最火赚钱好项目</h2></div>
                        <ul>
                            <?php if(isset($statisticsAll)){$statisticsAll=array_slice($statisticsAll,0,10);$i++;foreach ($statisticsAll as $key=>$val){?>
                                <li<?php if($i == 18){echo "class='last'";}?>>
                               <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'];?></a></span>
                               <p class="img"><span><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name'];?>开店"><img alt="<?php echo $val['project_brand_name'];?>开店" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label></span></p>
                               </li>
                           <?php }}?>
                        </ul>
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
    <div class="clear"></div>
</div>
<!--递出名片层开始-->
<div id="send_box" style="z-index: 999">
    <a href="#" class="close">关闭</a>
    <div id="msgcontent" class="btn"></div>
</div>
<!--递出名片层结束-->
<div id="opacity"></div>
<!--登陆-弹出框开始-->


<!--登陆-弹出框结束-->
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
<input id="hiddenvalue" type="hidden"  value="" />
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
<?php echo URL::webjs("platform/search_result.js")?>
<script type="text/javascript">
    function saveSearchClick(project_id, click_seat, click_hot_zone) {
        var word = $('#word').val();
        var arr = {'click_id' : project_id, 'click_seat' : click_seat,'click_hot_zone' : click_hot_zone, 'search_word' : word}
        $.ajax({
        type : "post",
        url : "/platform/ajaxcheck/saveSearchClick",
        data : arr,
        dataType : "",
        success : function(){return false;}
        }); 
    }
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
    });
      
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


     $(".ryl_search_item_icon").each(function(){
        $(this).find("img").hover(function(){
            $(this).siblings("em").show();
        },function(){
            $(this).siblings("em").hide();
        });
      $(".yjh_itemlist_rank_tab a").click(function(){
        var _index = $(".yjh_itemlist_rank_tab a").index(this);
        $(this).addClass("itemlist_rank_tab_current").siblings().removeClass("itemlist_rank_tab_current");
        $(".ryl_search_result_contain ul").eq(_index).show().siblings("ul").hide();
      });
    });

});
</script>