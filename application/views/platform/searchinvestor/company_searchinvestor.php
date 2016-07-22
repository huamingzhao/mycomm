<?php echo URL::webcss("platform/search_tzz.css")?>
<?php echo URL::webcss("common.css")?>
<?php echo URL::webjs("platform/comsearchinvestor.js");?>
<?php echo URL::webjs("getcards.js")?>
<form action="<?php echo urlbuilder::qiye("touzizhe");?>" method="get" enctype="application/x-www-form-urlencoded">
<div class="searchIndex_banner_bg">
    <div class="top_bg">
        <div class="search_box">
            <div class="search_select">
                <div class="select_flo">
                           <select id="address" name="per_area"  >
                                 <option value="">选择所在地</option>
                                  <?php foreach ($area as $v){?>
                                  <option value="<?=$v['cit_id']?>" <?php if($v['cit_id']==arr::get($postlist, 'per_area')){echo "selected='selected'";}?>><?=$v['cit_name']?></option>
                                 <?php }?>
                            </select>

                                <select name="per_amount" >
                                    <option  value="" >选择意向投资金额</option>
                                    <?php $moneylist = common::moneyArr(); foreach ($moneylist as $k=>$v): ?>
                                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'per_amount')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                                    <?php endforeach; ?>
                                </select>
                </div>
                <div class="select_flo">
                            <select name="parent_id" >
                            <option  value="" >选择意向投资行业</option>
                            <?php $primarylist = common::primaryIndustry(0); foreach ($primarylist as $v):?>
                            <option value="<?=$v->industry_id;?>" <?php if(arr::get($postlist,'parent_id')==$v): echo 'selected="selected"';endif;?> ><?=$v->industry_name;?></option>
                            <?php endforeach; ?>
                            </select>
                </div>
                <div class="button_flo"><button type="submit"></button></div>
                <div class="clear"></div>
            </div>

        </div>
    </div>
</div>
<div class="searchIndex_content">
    <div class="case">
        <div class="case_tp">
            <span class="case_geren_bg">为您找到符合的投资者<?php echo $totalcount;?>名</span>
            <span class="change">
            <input name="valid_mobile" class="chk" type="checkbox" value="1" <?php if(arr::get($postlist,'valid_mobile','')==1){ echo 'checked';}?> id="valid_mobile"/>
            <span>手机认证用户</span>
            <?php if($totalcount>0){?>
            <span class="fenye_num"><?php if($totalcount<=8){echo 1;} else{echo $page;}?>/<?php echo $total_pages;?></span>
            <?if($page != 1 && $totalcount>8){?><a href="<?php echo urlbuilder::qiye("touzizhe") ?><?php if($t_url=='') {echo '?page='.($page-1);}else{echo $t_url.'&page='.($page-1);}?>" class="fenye">&lt;
            </a>
            <?}if($page != $total_pages && $totalcount>8){?><a href="<?php echo urlbuilder::qiye("touzizhe") ?><?php if($t_url=='') {echo '?page='.($page+1);}else{echo $t_url.'&page='.($page+1);}?>" class="fenye">&gt;
            </a><? } }?>
            </span>
        </div>
</form>
        <div class="case_geren_bg">
            <ul>
            <?php $perk=1;foreach ($list as $per_v){?>
                <li>
                    <div class="case_geren_box" id="person_<?php echo $perk;?>_div">
                        <div class="geren_left" id="getonecard_<?php echo  $per_v['per_user_id'].'_0_div';?>"><img src="<?php  if(!empty($per_v['per_photo'])) { echo URL::imgurl($per_v['per_photo']);} else{ echo URL::webstatic("images/getcards/photo.png") ;}?>"/>
                        <?php if($per_v['is_pay']){echo '<span></span>';}?>
                        </div>
                        <a class="atestuserid" style="display: none"><?php echo $per_v['per_user_id'];?></a>
                        <div class="geren_right" style="width:190px;">
                            <p class="a">
                            <span class="name"><?php echo  mb_substr($per_v['per_realname'],0,3) ; if($per_v['per_gender']==2){ echo '  女士';}else{echo '  先生';}?></span>
                            <span><?php if($per_v['this_per_area']){echo $per_v['this_per_area'];} ?></span>
                            <span><?php if(isset($per_v['per_education']) && $per_v['per_education']){echo $edu_arr[$per_v['per_education']];}?></span>
                            </p>
                            <p class="b">
                            <span class="fs"><?php if($per_v['this_per_industry']){echo $per_v['this_per_industry'];}else{echo '暂无数据';} ?></span>
                            <span class="je"><?php $monarr= common::moneyArr(); echo $per_v['per_amount'] == 0 ? '无': $monarr[$per_v['per_amount']];?></span>
                            </p>
                            <p class="c"><?php echo mb_substr($per_v['per_remark'],0,30); ?></p>
                        </div>
                        <div class="qiye_right" id="<?php echo  $per_v['per_user_id'];?>_span">
                           <?php if(isset($per_v['card_type']) && $per_v['card_type']==3){//已交换 ?>
                           <span>已交换</span>

                           <?php }elseif (isset($per_v['card_type']) && $per_v['card_type']==2){//递出的名片?>
                                   <?php if(time()-(604800+$per_v['sendtime'])>0){ //7天后又可以重复递出?>
                                 <a href="javascript:void(0)" id="resendcard_<?php echo $per_v['card_id'];?>_<?php echo  $per_v['per_user_id'];?>" class="re_send">递出名片</a>
                                 <a id="resendcard_<?php echo $per_v['card_id'];?>_<?php echo $per_v['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($per_v['per_realname'],0,1,'utf-8') ?>先生/女士</a>
                                 <?php }else{?>
                                 <span>已递出</span>
                                 <?php }?>

                            <?php }elseif (isset($per_v['card_type']) && $per_v['card_type']==1){//收到的名片?>
                             <a href="javascript:void(0)" class="changecard" id="exchangecard_<?php echo $per_v['card_id'];?>_<?php echo $per_v['per_user_id'];?>">交换名片</a>
                             <a id="exchangecard_<?php echo $per_v['card_id'];?>_<?php echo $per_v['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($per_v['per_realname'],0,1,'utf-8') ?></a>
                            <?php }else{?>

                            <a href="javascript:void(0)" id="exchangecard_<?php echo  $per_v['per_user_id'];?>" class="outcard">递出名片</a>
                            <a id="exchangecard_<?php echo  $per_v['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($per_v['per_realname'],0,1,'utf-8') ?>先生/女士</a>
                            <?php }?>

                            <a href="javascript:void(0)"  class="viewcard" id="getonecard_<?php echo  $per_v['per_user_id'].'_0';?>">查看名片</a>

                            <?php if(isset($per_v['favorite_status']) && $per_v['favorite_status']==1){//已收藏?>
                             <span>已收藏</span>
                            <?php }else{?>
                            <div id="shoucang_0_<?php echo  $per_v['per_user_id'];?>_div">
                            <a href="javascript:void(0)" id="shoucang_0_<?php echo  $per_v['per_user_id'];?>" class="sc">收藏名片</a>
                            </div>
                            <?php }?>

                            <?php if($totalcount>8){//换一个投资者?>
                            <a href="javascript:void(0)" id="person_<?php echo $perk;?>" class="replaceone">不感兴趣</a>
                            <?php }?>
                        </div>
                    </div>
                </li>
                 <?php $perk++;}?>
            </ul>
        </div>
        <div class="clear"></div>
        <div class="case_tp" style="border-top:1px solid #e5e5e5;border-bottom:none;margin-top:10px;">
            <?php if($totalcount>0){?>
            <span class="change">
            <span class="fenye_num"><?php if($totalcount<=8){echo 1;} else{echo $page;}?>/<?php echo $total_pages;?></span>
            <?if($page != 1 && $totalcount>8){?><a href="<?php echo urlbuilder::qiye("touzizhe") ?><?php if($t_url=='') {echo '?page='.($page-1);}else{echo $t_url.'&page='.($page-1);}?>" class="fenye">&lt;
            </a>
            <?}if($page != $total_pages && $totalcount>8){?><a href="<?php echo urlbuilder::qiye("touzizhe") ?><?php if($t_url=='') {echo '?page='.($page+1);}else{echo $t_url.'&page='.($page+1);}?>" class="fenye">&gt;
            </a><?}?>
            </span>
            <?}?>
        </div>
    </div>
</div>
<script>
$(".case_geren_bg ul li").hover(function(){
    $(this).children(".case_geren_box").css("background-color","#fffdf8");
},function(){
    $(this).children(".case_geren_box").css("background-color","#fff");
});
</script>
<!--透明背景开始-->
<div id="getcards_opacity"></div>
<div id="getcards_opacity"></div>
<div id="opacity"></div>
<!--透明背景结束-->
<!--查看名片开始-->
<div id="getcards_view">
    <div id="getcards_view_repeat">
        <a href="#" class="close">关闭</a>
        <div id="getcards_view_content">

      </div>
    </div>
</div>
<!--查看名片结束-->
<!--从业经验弹出框开始-->
<div class="my_cyjy_view_box" style="z-index:99999">
    <a href="#" class="close">关闭</a>
    <h3>从业经验</h3>
    <div id="cyjy_content">
    </div>
</div>
<!--从业经验弹出框结束-->
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
<a id="mycardtype" style="display:none">3</a>
<!--名片服务提醒框开始-->
<div id="getcards_deletebox">
    <a href="#" class="close">关闭</a>
    <div class="text" style="background:none;padding-left:0px;text-align: left;color: #000;">
        <p></p>
        <p id="this_content2" style="padding-left:65px;"><a href="#" class="ensure"><img src="<?php echo URL::webstatic("/images/getcards/ensure1.jpg") ?>" /></a>
           <a href="#" class="cancel"><img src="<?php echo URL::webstatic("/images/getcards/cancel1.jpg") ?>" /></a>
        </p>
        <input id="getcards_deletebox_hid" type="hidden" value="0"></input>
    </div>
</div>
<!--名片服务提醒框结束-->