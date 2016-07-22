<!-- 采用IE7模式解析：解决IE8 image max-length bug END-->
<?php header('X-UA-Compatible: IE=7'); ?>
<!-- 采用IE7模式解析：解决IE8 image max-length bug END-->
<?php echo URL::webcss("platform/project_guide.css")?>

<!--主体部分-->
<div class="main" style="background-color:#e3e3e3; height:auto;">

  <div class="yjh_item_list" id="yjh_item_list_top">
     <!--left-->
     <div class="yjh_item_list_left">
         <ul>            
            <li><a href="<?php echo urlbuilder:: projectGuide ("fenlei");?>">分类导航</a></li>
            <li><a href="<?php echo urlbuilder:: projectGuide ("top");?>" class="item_list_left_current">排行榜</a></li>
            <li><a href="<?php echo urlbuilder:: projectGuide ("people");?>">按人群找</a></li>
            <li><a href="<?php echo urlbuilder:: projectGuide ("diqu");?>">按地区找</a></li>
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
         <div class="yjh_item_list_r_bgcenter" style="padding-top:0;">

            <!--项目大全-->
            <div class="yjh_home_top">
              <div class="yjh_home_top_unit yjh_home_unit_cont_phb yjh_home_unit_p20">
                 <h2><span>最佳口碑项目排行</span></h2>
                 <div class="yjh_home_unit_cont">
                     <div class="yjh_home_unit_cont_in">
                       <div class="yjh_itemlist_rank_tab"><a title="30天排行榜" href="#" class="itemlist_rank_tab_current">30天排行榜</a><a title="7天排行榜" href="#">7天排行榜</a></div><div class="clear"></div>
                       <div class="yjh_itemlist_rank_tab_nr">
                           <!-- 30天最佳口碑 -->
                           <ul>
                              <?php if(count($approing30list)>0){?>
                                <?php 	foreach($approing30list as $k => $v){?>
                                            <li>
                                                <label <?php if($k<3) echo 'class="top3"'; ?>><?php echo $k+1; if($k>=3)echo "、"; ?></label><span><?php if(isset($v['project_brand_name'])){?><a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><?=$v['project_brand_name'] ?></a><?php }?></span><b><img src="<?php echo URL::webstatic("images/platform/item_list/icon10.jpg")?>" /><?php if(isset($v['amount'])){echo $v['amount'];}?></b>
                                                <?php if($k == 0){?>
                                                <div class="spread">
                                                    <div class="clear"></div>
                                                    <div class="yjh_home_unit_licont">
                                                      <?php if(isset($v['project_logo'])){?>
                                                      <span class="img"><label>
                                                      <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                      </label></span>
                                                      <?php }?><?php if(isset($v['product_features']) && $v['product_features'] != ""){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?>
                                                    </div>
                                                </div>
                                                <?php }else{?>
                                                <div class="spread">
                                                    <div class="clear"></div>
                                                    <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                      <span class="img"><label>
                                                      <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                      </label></span>
                                                      <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                                </div>
                                                <?php }?>
                                            </li>
                                    <?php }?>
                              <?php }?>
                           </ul>
                           <div class="clear"></div>
                           <p><a title="查看完整榜单 》" target="_blank" href="<?php echo urlbuilder:: guideShowRanking ("1","1");?>">查看完整榜单 》</a></p>
                           <div class="clear"></div>
                       </div>
                       <div class="yjh_itemlist_rank_tab_nr"  style="display:none;">
                           <!-- 7天最佳口碑 -->
                           <ul>
                              <?php if(count($approing7list)>0){?>
                                <?php 	foreach($approing7list as $k => $v){?>
                                            <li>
                                                <label <?php if($k<3) echo 'class="top3"'; ?>><?php echo $k+1; if($k>=3)echo "、"; ?></label><span><?php if(isset($v['project_brand_name'])){?><a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><?=$v['project_brand_name'] ?></a><?php }?></span><b><img src="<?php echo URL::webstatic("images/platform/item_list/icon10.jpg")?>" /><?php if(isset($v['amount'])){echo $v['amount'];}?></b>
                                                <?php if($k == 0){?>
                                                <div class="spread">
                                                    <div class="clear"></div>
                                                    <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                      <span class="img"><label>
                                                      <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                      </label></span>
                                                      <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                                </div>
                                                <?php }else{?>
                                                <div class="spread">
                                                    <div class="clear"></div>
                                                    <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                      <span class="img"><label>
                                                      <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                      </label></span>
                                                      <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                                </div>
                                                <?php }?>
                                            </li>
                                    <?php }?>
                              <?php }?>
                           </ul>
                           <div class="clear"></div>
                           <p><a title="查看完整榜单 》" target="_blank" href="<?php echo urlbuilder:: guideShowRanking ("1","2");?>">查看完整榜单 》</a></p>
                           <div class="clear"></div>
                       </div>
                       <div class="clear"></div>
                     </div>
                   <div class="clear"></div>
                 </div>
              </div>
              <div class="yjh_home_top_unit yjh_home_unit_cont_phb yjh_home_unit_p20">
                 <h2><span class="yjh_home_unit_shadow01">最受关注项目排行</span></h2>
                 <div class="yjh_home_unit_cont yjh_home_unit_shadow02">
                   <div class="yjh_home_unit_cont_in">
                       <div class="yjh_itemlist_rank_tab"><a title="30天排行榜" href="#" class="itemlist_rank_tab_current">30天排行榜</a><a title="7天排行榜" href="#">7天排行榜</a></div><div class="clear"></div>
                       <div class="yjh_itemlist_rank_tab_nr">
                           <!-- 30天最受关注 -->
                           <ul>
                              <?php if(count($watch30list)>0){?>
                                <?php 	foreach($watch30list as $k => $v){?>
                                            <li>
                                                <label <?php if($k<3) echo 'class="top3"'; ?>><?php echo $k+1; if($k>=3)echo "、"; ?></label><span><?php if(isset($v['project_brand_name'])){?><a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><?=$v['project_brand_name'] ?></a><?php }?></span><b>关注<?php if(isset($v['amount'])){echo $v['amount'];}?></b>
                                                <?php if($k == 0){?>
                                                <div class="spread">
                                                    <div class="clear"></div>
                                                    <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                      <span class="img"><label>
                                                      <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                      </label></span>
                                                      <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                                </div>
                                                <?php }else{?>
                                                <div class="spread">
                                                    <div class="clear"></div>
                                                    <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                      <span class="img"><label>
                                                      <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                      </label></span>
                                                      <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                                </div>
                                                <?php }?>
                                            </li>
                                    <?php }?>
                              <?php }?>
                           </ul>
                            <div class="clear"></div>
                            <p><a title="查看完整榜单 》" target="_blank" href="<?php echo urlbuilder:: guideShowRanking ("2","1");?>">查看完整榜单 》</a></p>
                           <div class="clear"></div>
                       </div>
                      <div class="yjh_itemlist_rank_tab_nr"  style="display:none;">
                           <!-- 7天最受关注 -->
                           <ul>
                              <?php if(count($watch7list)>0){?>
                                <?php 	foreach($watch7list as $k => $v){?>
                                            <li>
                                                <label <?php if($k<3) echo 'class="top3"'; ?>><?php echo $k+1; if($k>=3)echo "、"; ?></label><span><?php if(isset($v['project_brand_name'])){?><a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><?=$v['project_brand_name'] ?></a><?php }?></span><b>收藏<?php if(isset($v['amount'])){echo $v['amount'];}?></b>
                                                <?php if($k == 0){?>
                                                <div class="spread">
                                                    <div class="clear"></div>
                                                    <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                      <span class="img"><label>
                                                      <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                      </label></span>
                                                      <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                                </div>
                                                <?php }else{?>
                                                <div class="spread">
                                                    <div class="clear"></div>
                                                    <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                      <span class="img"><label>
                                                      <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                      </label></span>
                                                      <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                                </div>
                                                <?php }?>
                                            </li>
                                    <?php }?>
                              <?php }?>
                           </ul>
                           <div class="clear"></div>
                           <p><a title="查看完整榜单 》" target="_blank" href="<?php echo urlbuilder:: guideShowRanking ("2","2");?>">查看完整榜单 》</a></p>
                           <div class="clear"></div>
                      </div>
                       <div class="clear"></div>
                   </div>
                   <div class="clear"></div>
                 </div>
              </div>
              <div class="clear"></div>
              <div class="yjh_home_top_unit yjh_home_unit_cont_phb">
                 <h2><span>最热项目排行</span></h2>
                 <div class="yjh_home_unit_cont">
                   <div class="yjh_home_unit_cont_in">
                       <div class="yjh_itemlist_rank_tab"><a title="30天排行榜" href="#" class="itemlist_rank_tab_current">30天排行榜</a><a title="7天排行榜" href="#">7天排行榜</a></div><div class="clear"></div>
                       <div class="yjh_itemlist_rank_tab_nr">
                       <!-- 30天最热 -->
                       <ul>
                          <?php if(count($click30list)>0){?>
                              <?php 	foreach($click30list as $k => $v){?>
                                          <li>
                                              <label <?php if($k<3) echo 'class="top3"'; ?>><?php echo $k+1; if($k>=3)echo "、"; ?></label><span><?php if(isset($v['project_brand_name'])){?><a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><?=$v['project_brand_name'] ?></a><?php }?></span><b>围观<?php if(isset($v['amount'])){echo $v['amount'];}?></b>
                                              <?php if($k == 0){?>
                                              <div class="spread">
                                                  <div class="clear"></div>
                                                  <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                    <span class="img"><label>
                                                    <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                    </label></span>
                                                    <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                              </div>
                                              <?php }else{?>
                                              <div class="spread">
                                                  <div class="clear"></div>
                                                  <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                    <span class="img"><label>
                                                    <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                    </label></span>
                                                    <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                              </div>
                                              <?php }?>
                                          </li>
                                  <?php }?>
                          <?php }?>
                       </ul>
                       <div class="clear"></div>
                       <p><a title="查看完整榜单 》" target="_blank" href="<?php echo urlbuilder:: guideShowRanking ("3","1");?>">查看完整榜单 》</a></p>
                       <div class="clear"></div>
                       </div>

                       <div class="yjh_itemlist_rank_tab_nr" style="display:none;">
                       <!-- 7天最热 -->
                       <ul>
                          <?php if(count($click7list)>0){?>
                              <?php 	foreach($click7list as $k => $v){?>
                                          <li>
                                              <label <?php if($k<3) echo 'class="top3"'; ?>><?php echo $k+1; if($k>=3)echo "、"; ?></label><span><?php if(isset($v['project_brand_name'])){?><a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><?=$v['project_brand_name'] ?></a><?php }?></span><b>围观<?php if(isset($v['amount'])){echo $v['amount'];}?></b>
                                              <?php if($k == 0){?>
                                              <div class="spread">
                                                  <div class="clear"></div>
                                                  <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                    <span class="img"><label>
                                                    <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                    </label></span>
                                                    <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                              </div>
                                              <?php }else{?>
                                              <div class="spread">
                                                  <div class="clear"></div>
                                                  <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                    <span class="img"><label>
                                                    <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                    </label></span>
                                                    <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                              </div>
                                              <?php }?>
                                          </li>
                                  <?php }?>
                          <?php }?>
                       </ul>
                       <div class="clear"></div>
                       <p><a title="查看完整榜单 》" target="_blank" href="<?php echo urlbuilder:: guideShowRanking ("3","2");?>">查看完整榜单 》</a></p>
                       <div class="clear"></div>
                       </div>
                       <div class="clear"></div>
                   </div>
                   <div class="clear"></div>
                 </div>
              </div>
              <div class="yjh_home_top_unit yjh_home_unit_cont_phb">
                 <h2><span class="yjh_home_unit_shadow01">最新项目排行</span></h2>
                 <div class="yjh_home_unit_cont yjh_home_unit_shadow02">
                   <div class="yjh_home_unit_cont_in">
                       <div class="yjh_itemlist_rank_tab"><a title="全部排行榜" href="#" class="itemlist_rank_tab_current">全部排行榜</a></div><div class="clear"></div><div class="clear"></div>
                       <div class="yjh_itemlist_rank_tab_nr">
                           <!-- 最新项目 -->
                           <ul>
                              <?php if(count($newprojectlist)>0){?>
                                <?php 	foreach($newprojectlist as $k => $v){?>
                                            <li>
                                                <label <?php if($k<3) echo 'class="top3"'; ?>><?php echo $k+1; if($k>=3)echo "、"; ?></label><span><?php if(isset($v['project_brand_name'])){?><a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><?=$v['project_brand_name'] ?></a><?php }?></span><b></b>
                                                <?php if($k == 0){?>
                                                <div class="spread">
                                                    <div class="clear"></div>
                                                    <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                      <span class="img"><label>
                                                      <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                      </label></span>
                                                      <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                                </div>
                                                <?php }else{?>
                                                <div class="spread">
                                                    <div class="clear"></div>
                                                    <div class="yjh_home_unit_licont"><?php if(isset($v['project_logo'])){?>
                                                      <span class="img"><label>
                                                      <a target="_blank" href="<?$url =urlbuilder::project($v['project_id']); echo $url;?>"><img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?=$v['project_brand_name'] ?>" /></a>
                                                      </label></span>
                                                      <?php }?><?php if(isset($v['product_features'])){?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['product_features'])),0,45,'UTF-8').'...';?></span><?php }else{?><span><?=mb_substr(strip_tags(htmlspecialchars_decode($v['project_summary'])),0,45,'UTF-8').'...';?></span><?php }?></div>
                                                </div>
                                                <?php }?>
                                            </li>
                                    <?php }?>
                              <?php }?>
                           </ul>
                            <div class="clear"></div>
                            <p><a title="查看完整榜单 》" target="_blank" href="<?php echo urlbuilder:: guideShowRanking ("4");?>">查看完整榜单 》</a></p>
                            <div class="clear"></div>
                        </div>
                       <div class="clear"></div>
                   </div>
                   <div class="clear"></div>
                 </div>
              </div>


              <div class="clear"></div>
            </div>
            <div class="clear"></div>
            </div>



         <div class="yjh_item_list_r_bgbot"></div>
         <div class="clear"></div>
     </div>

     <div class="yjh_item_list_backtop"><a title="" href="#header"><img alt="" src="<?php echo URL::webstatic("images/platform/item_list/back_top.png");?>" /></a></div>
  <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>
<?php echo URL::webjs("platform/home/jquery.cookies.2.2.0.js");?>

<script>
$(function(){
    $(".yjh_home_top_unit").each(function(){
        var _this = $(this);
        $(this).find(".yjh_itemlist_rank_tab").children("a").click(function(){
            var _index =_this.find(".yjh_itemlist_rank_tab").children("a").index(this);
            $(this).addClass("itemlist_rank_tab_current").siblings().removeClass("itemlist_rank_tab_current");
            _this.find(".yjh_itemlist_rank_tab_nr").eq(_index).show().siblings(".yjh_itemlist_rank_tab_nr").hide();
            return false;
        });
        $(this).find("ul").each(function(){
            $(this).find(".yjh_home_unit_licont").eq(0).show();
            $(this).find("li").eq(0).addClass('active');
            $(this).find("li").hover(function(){
                $(this).addClass('active');
                $(this).find(".yjh_home_unit_licont").show();
                $(this).siblings("li").find(".yjh_home_unit_licont").hide();
                $(this).siblings("li").find(".yjh_home_unit_licont").parent().parent().removeClass('active');
            });
        });
    });
})
</script>
