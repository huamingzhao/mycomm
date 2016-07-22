<?php echo URL::webjs("search_tz.js")?>
<?php echo URL::webjs("invitepro_zg.js")?>
<?php echo URL::webjs("getcards.js")?>
<?php echo URL::webcss("search_touzi.css")?>
<style>
.ryl_search_list tr td p {line-height: 25px; width: 100%;}
.ryl_view_jingyan01,.ryl_view_jingyan02{padding-bottom:0px;}
</style>
              <!--主体部分开始-->
                <div id="right">
                  <div id="right_top"><span>搜索投资者</span><div class="clear"></div></div>
                    <div id="right_con">
                      <div class="ryl_search_touzi" style="padding-bottom:15px;">
                        <p style="padding-top:10px;"><a href="<?php echo URL::website('/company/member/investor/searchConditionsList')?>" class="search_lishi_list">查看我的历史搜索记录</a><b>搜索投资者</b></p>
                         <form action="" method="get" enctype="application/x-www-form-urlencoded" target="_self">
                         <div class="ryl_gj_search" >
                         <p>
                           <label>投资行业：</label>
                            <select name="parent_id" style="width:110px;" id="hye">
                            <option  value="" >不限</option>
                            <?php $primarylist = common::primaryIndustry(0); foreach ($primarylist as $v):?>
                            <option value="<?=$v->industry_id;?>" <?php if(arr::get($postlist,'parent_id')==$v): echo 'selected="selected"';endif;?> ><?=$v->industry_name;?></option>
                            <?php endforeach; ?>
                            </select>
                            <select name="industry_id" id="hye1" style="width:110px;">
                                 <option value="" >不限</option>
                                   <?php if(arr::get($postlist,'parent_id')!='' && count($list_industry2)){ foreach ($list_industry2 as $k=>$v): ?>
                                   <option value="<?=$v['industry_id'];?>" <?php if($v['industry_id'] == arr::get($postlist,'industry_id')) echo "selected='selected'"; ?> ><?=$v['industry_name'];?></option>
                                   <?php endforeach; } ?>
                           </select>
                          </p>
                           <p>
                               <label>投资金额：</label>
                                <select name="per_amount" style="width:225px;">
                                    <option  value="" >不限</option>
                                    <?php $moneylist = common::moneyArr(); foreach ($moneylist as $k=>$v): ?>
                                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'per_amount')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                                    <?php endforeach; ?>
                                </select>
                           </p>
                           <p>
                               <label>投资者身份：</label>
                             <select name="per_identity" style="width:225px;">
                                <option  value="" >不限</option>
                                <?php $perIdentity= common::perIdentityArr(); foreach ($perIdentity as $k=>$v): ?>
                                <option  value="<?=$k;?>" <?php if(arr::get($postlist,'per_identity')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                                <?php endforeach; ?>
                             </select>
                           </p>
                           <p>
                           <label>投资地区：</label>
                           <select id="address" name="pro_id"  style="width:110px;">
                                 <option value="">不限</option>
                                  <?php foreach ($area as $v){?>
                                  <option value="<?=$v['cit_id']?>" <?php if($v['cit_id']==arr::get($postlist, 'pro_id')){echo "selected='selected'";}?>><?=$v['cit_name']?></option>
                                 <?php }?>
                            </select>
                            <select id="address1" name="area_id" style="width:110px;">
                                  <option value="" >不限</option>
                                  <?php if(arr::get($postlist,'pro_id')!='' && count($cityarea)){ foreach ($cityarea as $v): ?>
                                  <option value="<?=$v->cit_id ;?>" <?php if($v->cit_id == arr::get($postlist,'area_id')) echo "selected='selected'"; ?> ><?=$v->cit_name;?></option>
                                  <?php endforeach; } ?>
                            </select>
                          </p>
                          <div id="search_hide" class="ryl_gj_search_cont" <?php if(arr::get($postlist,'per_join_project')=='' && arr::get($postlist,'per_connections')=='' && arr::get($postlist,'per_investment_style')=='' && arr::get($postlist,'hiddenvalue','0')==0) { echo 'style="display:none"';}?>>
                          <p>
                               <label>投资者加盟项目方式：</label>
                                <select name="per_join_project" style="width:225px;">
                                    <option  value="" >不限</option>
                                    <?php $joinProject= common::joinProjectArr(); foreach ($joinProject as $k=>$v): ?>
                                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'per_join_project')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                                    <?php endforeach; ?>
                                </select>　
                           </p>
                           <p>
                               <label>人脉关系：</label>
                            <select name="per_connections" style="width:225px;">
                                <option  value="" >不限</option>
                                <?php $connections= common::connectionsArr(); foreach ($connections as $k=>$v): ?>
                                <option  value="<?=$k;?>" <?php if(arr::get($postlist,'per_connections')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                                <?php endforeach; ?>
                            </select>　
                           </p>
                           <p>
                               <label>个人投资风格：</label>
                               <select name="per_investment_style" style="width:225px;">
                                    <option  value="" >不限</option>
                                    <?php $investment= common::investmentStyle(); foreach ($investment as $k=>$v): ?>
                                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'per_investment_style')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                                    <?php endforeach; ?>
                               </select>　
                           </p>
                           </div>
                           <div class="clear"></div>
                           <div class="ryl_gj_search_btn">
                            <input type="image" src="<?php echo URL::webstatic("images/search_btn/search_btn.png") ?>" />
                            <?php if(arr::get($postlist,'per_join_project')=='' && arr::get($postlist,'per_connections')=='' && arr::get($postlist,'per_investment_style')=='' && arr::get($postlist,'hiddenvalue','0')==0) { ?>
                            <a href="#" class="zk_gaoji" style="display: none;">收起高级搜索></a>
                            <a href="#" class="gaoji">高级搜索</a>
                            <?php } else{?>
                            <a href="#" class="gaoji" style="display: none;">高级搜索</a>
                            <a href="#" class="zk_gaoji">收起高级搜索></a>
                            <?php }?>
                           </div>
                         </div>
                         </form>
                         <div class="clear"></div>
                         <p class="ryl_search_ptitle">为您搜索到<span class="number"><b><?php echo $totalcount;?></b></span>位投资者 <a style="color:#0036ff; font-size:12px; font-weight:normal;" href="<?php echo URL::website('/company/member/investor/searchSubscription');?>">订阅搜索结果</a></p>
                         <div class="ryl_comp_invest_bg">
                    <span class="span1">意向投资者 </span>
                    <span class="span2">递出名片状态</span>
                    <span class="span3">操作</span>
                </div>
                         <table cellpadding="0" cellspacing="0" class="ryl_search_list" style="border-collapse:collapse;" id="getcards">
                          <!--<tr style="width:100%;display:block;background:url(images/search_btn/th_bg.jpg) no-repeat 0 0;">
                            <th>意向投资者</th>
                            <th>&nbsp;</th>
                            <th>递出名片状态</th>
                            <th>操作</th>
                         </tr>-->
                         <?php foreach( $list as $v){ ?>
                          <tr class="list">
                            <td style="width:120px;"><div class="ryl_search_photo">
                            <img src="<?php  if(!empty($v['per_photo'])) { echo URL::imgurl($v['per_photo']);} else{ echo URL::webstatic("/images/getcards/photo.png") ;}?>" />
                            <a href="javascript:void(0)" class="viewcard" id="getonecard_<?php echo  $v['per_user_id'].'_0';?>">查看名片</a>
                            </div>
                            </td>
                            <td  style="width:400px;">
                            <div class="ryl_search_text">
                            <p><b><?php echo $v['per_realname']; if($v['per_gender']==2){ echo '  女士';}else{echo '  先生';}?></b></p>
                            <p>投资金额：<?php $monarr= common::moneyArr(); echo $v["per_amount"]== 0 ? '无': $monarr[$v["per_amount"]];?></p>
                            <p>投资行业：<? echo $v['per_industry_string']; ?></p>
                            </div>
                            </td>

                             <td style="width:106px;">

                            <?php if ($v['exchangecardcount']){ ?><!--名片已交换-->
                               <p><span >已交换</span></p>
                            <?php } elseif($v['outcardcount']){?><!--名片已递出-->
                                 <span id="resendcard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['to_user_id'];?>_cishu">
                                 <p>
                                 <span>
                                 <em style="color:#000;font-weight:normal">
                                 <?php if($v['cardinfo']['send_count']>1) {echo  '已重复递出 <b style="color:red">'.$v['cardinfo']['send_count'].'</b> 次';}else{echo "已递出";}?>
                                 <a id="resendcard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['to_user_id'];?>_cishu_name" style="display:none"><?php echo $v['cardinfo']['send_count'];?></a>
                                 </em>
                                 </span></p>
                                </span>

                                 <span id="resendcard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['to_user_id'];?>_span">
                                 <?php if($v['cardinfo']['exchange_status']==0 && time()-(604800+$v['cardinfo']['send_time'])>0){ ?>
                                  <a href="#" class="re_send" id="resendcard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['to_user_id'];?>">
                                  <img src="<?php echo URL::webstatic("images/getcards/btn5.gif") ?>" />
                                  </a>
                                  <a id="resendcard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['to_user_id'];?>_name" style="display:none"><?php echo mb_substr($v['per_realname'],0,4,'utf-8') ?></a>
                                 <?php }?>
                                 </span>

                        <?php } elseif($v['receivedcardcount']){?><!--名片已收到-->
                                 <span id="exchangecard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['from_user_id'];?>_span">
                                  <?php if($v['cardinfo']['send_count']>1){ ?>
                                  <span>已重复收到 <em style="color:red;font-weight: bold;font-style: normal;"><?php echo  $v['cardinfo']['send_count'];?></em> 次</em></span>
                                  <?php } else{ ?>
                                  <span>已收到</span>
                                  <?php }?>


                                 <a href="javascript:void(0)" id="exchangecard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['from_user_id'];?>" class="changecard">
                                 <img src="<?php echo URL::webstatic("/images/getcards/change1.png") ?>"/>
                                  </a>
                                  <a id="exchangecard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['from_user_id'];?>_name" style="display:none"><?php echo mb_substr($v['per_realname'],0,4,'utf-8') ?></a>
                                </span>
                            <?php } else {?>
                                    <span id="exchangecard_<?php echo  $v['per_user_id'];?>_remove">
                                    <a href="javascript:void(0)" id="exchangecard_<?php echo  $v['per_user_id'];?>" class="outcard"><img src="<?php echo URL::webstatic("/images/search_tz/search_btn1.png") ?>" class="img2"/></a></span>
                                    <a id="exchangecard_<?php echo  $v['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($v['per_realname'],0,4,'utf-8') ?></a>
                            <?php }?>
                           </td>

                            <td>
                            <p>
                            <?php if ($v['per_experience_stutas']==0){ ?>
                            <a href="javascript:void(0)" class="ryl_view_jingyan02" >暂无从业经验</a>
                            <?php } else{?>
                            <a href="javascript:void(0)" class="view ryl_view_jingyan01" id="getexperience_<?php echo  $v['per_user_id'].'_0';?>">查看从业经验</a>
                            <?php }?>
                            </p>
                             <p style="margin-left:15px;">
                             <?php if($v['favorite_status']){ ?>
                                <img src="<?php echo URL::webstatic("/images/getcards/shouc_icon1.png") ?>" class="ysc" id="favoritecard_0_<?php echo  $v['per_user_id'];?>" />
                                <a style="color:#9d9d9d" href="javascript:void(0)" id="favoritecard_0_<?php echo  $v['per_user_id'];?>_span">已收藏</a>
                              <?php }else{?>
                               <img src="<?php echo URL::webstatic("/images/getcards/shouc_icon.png") ?>" class="ysc" id="favoritecard_0_<?php echo  $v['per_user_id'];?>" />
                               <b style="font-weight: normal;" id="favoritecard_0_<?php echo  $v['per_user_id'];?>_span" >
                               <a style="color:#0036FF" href="javascript:void(0)" id="shoucang_0_<?php echo  $v['per_user_id'];?>" class="sc">收藏名片</a>
                               </b>
                              <?php }?>
                            </p>
                            </td>
                          </tr>
                         <?php }?>
                           </table>
                           <?php echo $page;?>
                      <div class="clear"></div>
                      </div>
                  </div>
                </div>
  <!--主体部分结束-->

<div class="clear"></div>
<!--透明背景开始-->
<div id="getcards_opacity"></div>
<div id="getcards_opacity2"></div>
<!--透明背景结束-->

<!--从业经验弹出框开始-->
<div class="my_cyjy_view_box" style="z-index:99999">
    <a href="#" class="close">关闭</a>
    <h3>从业经验</h3>
    <div id="cyjy_content">
    </div>
</div>
<!--从业经验弹出框结束-->

<!--查看名片开始-->
<div id="getcards_view" class="getcards_view2">
    <div id="getcards_view_repeat">
        <a href="#" class="close">关闭</a>
        <div id="getcards_view_content">

      </div>
    </div>
</div>
<!--查看名片结束-->
<!--批量收藏名片开始-->
<div id="getcards_savebox">
    <a href="#" class="close">关闭</a>
    <div class="text">
        <p></p>
    </div>
</div>
<!--批量收藏名片结束-->
<!--交换名片开始-->
<div id="getcards_change">
    <a href="#" class="close">关闭</a>
    <div class="getcards_change_suc">
    </div>
</div>
<!--交换名片结束-->
<a id="mycardtype" style="display:none">4</a>
<!--名片服务提醒框开始-->
<div id="getcards_deletebox">
    <a href="#" class="close">关闭</a>
    <div class="text" style="background:none;padding-left:0px;text-align: left;color: #000;">
        <p></p>
        <p id="this_content2"  style="width:270px; margin:0 auto;" ><a href="#" class="ensure"><img src="<?php echo URL::webstatic("/images/getcards/ensure1.jpg") ?>" /></a>
           <a href="#" class="cancel"><img src="<?php echo URL::webstatic("/images/getcards/cancel1.jpg") ?>" /></a>
        </p>
        <input id="getcards_deletebox_hid" type="hidden" value="0"></input>
    </div>
</div>
<!--名片服务提醒框结束-->
