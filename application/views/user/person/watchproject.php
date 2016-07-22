<?php echo URL::webcss("zhaoshang.css")?>
<?php echo URL::webjs("invitepro_zg.js")?>
<?php echo URL::webjs("my_focus.js")?>
<!--主体部分开始-->
                <div class="right" style="height:auto !important;height:620px;min-height:620px;">
                    <h2 class="user_right_title"><span>我收藏的项目</span><div class="clear"></div></h2>
                    <div class="my_focus">
                    <form method="get" action="<?php echo URL::site('/person/member/project/watchProject');?>">
                        <div class="filter">
                            <h3>筛选我收藏的项目：</h3>
                            <p class="one">
                                <span>项目行业：
                                    <select id="hye" name="parent_id" >
                                        <option value="" >不限</option>
                                        <?php foreach ($list_industry as $value): ?>
                                        <option value="<?=$value->industry_id;?>" <?php if(arr::get($search,'parent_id')==$value->industry_id): echo 'selected="selected"';endif;?> ><?=$value->industry_name;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select name="project_industry_id" id="hye1" >
                                        <option value="" >不限</option>
                                        <?php if(arr::get($search,'parent_id')!='' && count($list_industry2)){ foreach ($list_industry2 as $k=>$v): ?>
                                         <option value="<?=$v['industry_id'];?>" <?php if($v['industry_id'] == arr::get($search,'project_industry_id')) echo "selected='selected'"; ?> ><?=$v['industry_name'];?></option>
                                         <?php endforeach; } ?>
                                    </select>
                               </span>
                               <span>项目地区：
                                    <select id="address" name="pro_id">
                                        <option value="">不限</option>
                                        <?php foreach ($area as $v){?>
                                        <option value="<?=$v['cit_id']?>" <?php if($v['cit_id']==arr::get($search, 'pro_id')){echo "selected='selected'";}?>><?=$v['cit_name']?></option>
                                        <?php }?>
                                    </select>
                                    <select id="address1" name="area_id" >
                                          <option value="" >不限</option>
                                            <?php if(arr::get($search,'pro_id')!='' && count($cityarea)){ foreach ($cityarea as $v): ?>
                                             <option value="<?=$v->cit_id ;?>" <?php if($v->cit_id == arr::get($search,'area_id')) echo "selected='selected'"; ?> ><?=$v->cit_name;?></option>
                                             <?php endforeach; } ?>
                                     </select>
                               </span>
                            </p>
                            <p class="two">
                                <span>项目金额：
                                 <select id="money" class="moreWidth" name="project_amount_type">
                                    <option value="">不限</option>
                                    <?php foreach ($money as $k=>$v){?>
                                    <option value="<?=$k?>" <?php if($k==arr::get($search, 'project_amount_type')){echo "selected='selected'";}?>><?=$v?></option>
                                    <?php }?>
                                 </select>
                               </span>
                               <span>收藏时间：
                                <select name="watch_update_time" class="select1">
                                    <option  value="" >不限</option>
                                    <?php $timearr = common::receivetime(); foreach ($timearr as $k=>$v): ?>
                                    <option  value="<?=$k;?>" <?php if(arr::get($search,'watch_update_time')==$k): echo 'selected="selected"';endif;?> ><?=$v;?></option>
                                    <?php endforeach; ?>
                                  </select>
                               </span>
                            </p>
                            <button>开始筛选</button>
                        </div>
                  </form>

                        <div class="all">
                            <input type="checkbox" />全选
                            <span>批量取消收藏</span>
                        </div>
                        
                       <?php  foreach( $list as $v ){ ?>
                        <div class="list project_list1 clearfix">
                            <div class="one"><input type="checkbox" value="<?php echo $v['watch_project_id'];?>" ></div>
                            <div class="two">
                              <span>
                                <a href="<?php echo urlbuilder::project($v['watch_project_id'])?>" target="_blank">
                                  <img src="<?php 
		                            if($v["project_source"] != 1) {
										$tpurl=project::conversionProjectImg( $v["project_source"], 'logo', $v); if(project::checkProLogo($tpurl)){echo $tpurl;}else{  echo URL::webstatic('images/common/company_default.jpg'); }	
									}
		                             else {
												$tpurl=URL::imgurl($v['project_logo']);
												if(project::checkProLogo($tpurl)){echo $tpurl;}else{echo URL::webstatic('images/common/company_default.jpg'); }
									 } ?>">
                                </a>
                                <?php if($v['project_approving_count']){//赞?>
                                <a href="javascript:void(0)" class="zan"><?php echo $v['project_approving_count'] ?></a>
                              	<?php }?>
                              </span>
                              
                            </div>
                            <div class="three content">
                              <h4 class="clearfix"><a target="_blank" href="<?php echo urlbuilder::project($v['watch_project_id']);?>" style="font-size: 16px;"><?php echo $v['project_name'] ?></a>
                              <?php if($v['project_source']==2){//875项目?>
                              <i class="project_list_vip" title="可使用创业币抵扣投资款项目"></i><i class="project_list_baozhang"></i>
                              <?php }?>
                              </h4>
                              <p><?php $summary=htmlspecialchars_decode($v['project_summary']);echo mb_substr(strip_tags($summary),0,40,'UTF-8')?>...</p>
                              <span class="ryl_search_item_icon clearfix">
                                <b class="ryl_search_item_icon01"><img src="<?php echo URL::webstatic('images/platform/item_list/icon03.jpg')?>">                              
                                  <a  href="javascript:void(0)" > <?php echo $v['pro_industry'];?>
                                  </a>
                                  &nbsp; <em>行业</em></b>  <b class="ryl_search_item_icon02"><img src="<?php echo URL::webstatic('images/platform/item_list/icon04.jpg')?>">
                                  
                                  <a  href="javascript:void(0)"><i><?php $monarr= common::moneyArr(); echo $v['project_amount_type']== 0 ? '无': $monarr[$v['project_amount_type']];?></i></a> <em>投资金额</em></b> 
                                   &nbsp;
                                <b class="ryl_search_item_icon03">
                                  <img src="<?php echo URL::webstatic('images/platform/item_list/icon05.jpg')?>">
                                  <a href="javascript:void(0)"><?php $lst = common::businessForm();
				                    $pro_count=count($v['project_zhaoshang']);
				                    if($pro_count){
				                        $comodel_text=0;
				                        foreach ($v['project_zhaoshang'] as $v1){
				                            $comodel_text++;
				                            echo $lst[$v1];
				                            if($comodel_text < $pro_count){
				                                echo '、';
				                            }
				                        }
				                    }else{
				                        echo '不限';
				                    } ?></a>
                                  &nbsp;
                                  <em>招商形式</em>
                                </b>
                              </span>
                              <?php  if($v['invest_id']){//投资考察?>
                              	<span class="list_investment"><a target="_blank" href="<?php echo urlbuilder::projectInvest($v['invest_id']);?>"><img src="<? echo URL::webstatic('images/platform/search_result/icon25.gif'); ?>"><?php  echo $v['investment_start'];?>    招商会报名中 </a></span>
                              <?php }?>
                            </div>
                            <div class="four">
                              <p class="wantInvest">意向加盟<font><?php echo $v['project_pv_count'] ?></font>人</p>
                              <p class="cancel"><a href="javascript:void(0)" id="cancelwatch_<?php echo $v['watch_project_id'];?>">取消收藏</a></p>
                              <p class="viewDetialDiv"><a target="_blank" href="<?php echo urlbuilder::project($v['watch_project_id']);?>" class="viewDetial">查看详情</a></p>
                            </div>
                        </div>
                         <?php }?>
                        
                         
                      <?php if(count($list)>0) { ?>
                        <div class="all">
                            <input type="checkbox" />全选
                            <span>批量取消收藏</span>
                        </div>
                   <?php } else{?>
                   <br><br><br>
                   &nbsp;&nbsp;&nbsp;&nbsp;没有找到您想要的项目，建议您放宽搜索条件
                   <?php }?>
                    </div>
                <?=$page;?>
                </div>
                <!--主体部分结束-->
                <div class="clear"></div>


 <!--透明层开始-->
<div id="opacity_box"></div>
<!--透明层结束-->
<!--递出名片层开始-->
<div id="send_box">
    <a href="#" class="close">关闭</a>
    <p class="suc">您已向<span id="send_box_username"></span>递出您的个人名片，对方会在登录企业用户中心时看到您的名片。一句话预祝你们沟通顺畅，合作愉快！</p>
</div>
<!--递出名片层结束-->

<!--交换名片层开始-->
<div id="change_box">
    <a href="#" class="close">关闭</a>
    <p class="suc">您已向<span id="change_box_username"></span>交换了您的个人名片，对方会在登录企业用户中心时看到您的名片。一句话预祝你们沟通顺畅，合作愉快！</p>
</div>
<!--交换名片层结束-->
<!--批量取消收藏开始-->
<div id="cancels_box">
    <a href="#" class="close">关闭</a>
    <p>您确定要取消收藏吗？</p>
    <!--<p>您还没选择项目，请选择需要取消收藏的项目</p>-->
    <a href="#" id="cancel_id"><img src="<?php echo URL::webstatic("images/box/ensure.png") ?>" /></a>
    <a href="#" class="cancel"><img src="<?php echo URL::webstatic("images/box/cancel.png") ?>" /></a>
</div>
<!--批量取消收藏结束-->
<!--取消收藏开始-->
<div id="cancel_box">
    <a href="#" class="close">关闭</a>
    <p>您确定要取消收藏吗？</p>
    <a href="#" id="ensureupdate"><img src="<?php echo URL::webstatic("images/box/ensure.png") ?>" /></a>
    <a href="#" class="cancel"><img src="<?php echo URL::webstatic("images/box/cancel.png") ?>" /></a>
</div>
<!--取消收藏结束-->