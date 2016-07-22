<?php echo URL::webjs("favorite_company.js")?>
<?php echo URL::webjs("getcards.js")?>
<?php echo URL::webjs("invitepro_zg.js")?>
 <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>我收藏的名片</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="getcards">
                 <form action="" method="get" name="first" id="first" enctype="application/x-www-form-urlencoded" target="_self">
                  <div class="filter">
                    <p>
                    <label>投资行业：</label>
                    <select name="parent_id" style="width:110px;" id="hye">
                         <option  value="" >不限</option>
                         <?php  foreach ($list_industry as $v):?>
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
                    <select name="per_amount" style="width:190px;">
                    <option  value="" >不限</option>
                    <?php $moneylist = common::moneyArr(); foreach ($moneylist as $k=>$v): ?>
                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'per_amount')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                    <?php endforeach; ?>
                    </select>
                     </p>
                     <p>
                     <label>收藏名片时间：</label>
                    <select name="send_time" class="select1" style="width:224px;">
                    <option  value="" >不限</option>
                    <?php $timearr = common::receivetime(); foreach ($timearr as $k=>$v): ?>
                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'send_time')==$k): echo 'selected="selected"';endif;?> ><?=$v;?></option>
                    <?php endforeach; ?>
                     </select>
                     </p>
                     <p>
                     <label></label>

                     </p>
                    <p class="radio">
                    <label>名片状态：</label>
                        <?php $sta=FALSE; if(arr::get($postlist,'exchange_status')<1) $sta=TRUE; echo Form::radio('exchange_status','0',$sta);?> 不限　
                        <?php $sta=FALSE; if(arr::get($postlist,'exchange_status')==1) $sta=TRUE; echo Form::radio('exchange_status','1',$sta);?>已交换　
                        <?php $sta=FALSE; if(arr::get($postlist,'exchange_status')==2) $sta=TRUE; echo Form::radio('exchange_status','2',$sta);?> 已收到 　
                        <?php $sta=FALSE; if(arr::get($postlist,'exchange_status')==3) $sta=TRUE; echo Form::radio('exchange_status','3',$sta);?> 已递出　　
                    </p>
                    <p>
                    <label>名片显示：</label>
                         <?php  $sta=FALSE; if(arr::get($postlist,'from_read_status')=='-1') $sta=TRUE; echo Form::radio('from_read_status','-1',$sta);?> 不限　　
                        <?php  $sta=FALSE; if(arr::get($postlist,'from_read_status')=='1') $sta=TRUE; echo Form::radio('from_read_status','1',$sta);?> 已查看名片　　
                        <?php  $sta=FALSE; if(arr::get($postlist,'from_read_status')=='0') $sta=TRUE; echo Form::radio('from_read_status','0',$sta);?> 未查看名片
                    </p>
                    <div class="clear"></div>
                    <div class="searchBtn" style=" height:32px; width:121px; margin:0 auto; display:block"><input type="image" src="<?php echo URL::webstatic("/images/getcards/getcards_filter_btn.jpg");?>" />
                    </div>
                  </div>
                </form>
                <div class="list">
                    <div class="allbtn">
                        <div class="one"><input type="checkbox" />全选</div>
                        <div class="three favoritecancelall"><a href="#">批量取消收藏</a></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="ryl_comp_invest_bg">
                    <span class="span1">意向投资者 </span>
                    <span class="span2">递出名片状态</span>
                    <span class="span3">操作</span>
                </div>
                <?php foreach( $list as $v){ ?>
                <div class="list" id="list_<?php echo  $v['favorite_id'];?>">
                    <div class="list_checkbox">
                        <input type="checkbox" value="<?php echo  $v['favorite_id'];?>" name="exchangecard_<?php echo  $v['favorite_id'];?>"/>
                    </div>
                    <div class="left">
                        <img src="<?php  if(!empty($v['per_photo'])) { echo URL::imgurl($v['per_photo']);} else{ echo URL::webstatic('/images/getcards/photo.png') ;}?>" />
                        <a href="javascript:void(0)" class="viewcard" id="getonecard_<?php echo  $v['favorite_user_id'].'_';?><?php if (isset($v['cardinfo']['card_id']) && $v['cardinfo']['card_id']!=''){ echo $v['cardinfo']['card_id'];}?>_<?php if (isset($v['cardinfo']['card_type'])&&$v['cardinfo']['card_type']!='') echo $v['cardinfo']['card_type'];?>">查看名片</a>
                    </div>
                    <div class="right" style="width:255px;padding-left:0;padding-bottom:0;">
                        <p style="font-weight:bold;"><?php echo $v['per_realname']; if($v['per_gender']==2){ echo '  女士';}else{echo '  先生';} ?></p>
                        <p>投资金额：<?php $monarr= common::moneyArr(); echo $v["per_amount"]== 0 ? '无': $monarr[$v["per_amount"]];?></p>
                        <p>投资行业：<?php echo $v['this_per_industry']; ?></p>
 <p><b>收藏名片时间：<?php echo date("Y-m-j H:i",$v['favorite_time'] ) ?></b>
<?php if (isset($v['exchangecardcount'])&&$v['exchangecardcount']==1){//交换的名片?>
                         <sup id="isornot_readcard_<?php echo  $v['per_user_id'];?>">
                        <?php if(($v['cardinfo']['from_read_status']==0&&$v['cardinfo']['card_type']==2)){ ?>
                        <img src="<?php echo URL::webstatic("/images/getcards/change_btn1.png");?>" class="img4"/>
                        <?php }else{?><img src="<?php echo URL::webstatic("/images/getcards/change_btn2.png");?>" class="img4"/><?php }?>
                        </sup></p>
                    </div>
<?php }elseif (isset($v['receivedcardcount'])&&$v['receivedcardcount']==1){//收到的名片?>
                     <sup id="isornot_readcard_<?php echo  $v['per_user_id'];?>">
                    <?php if($v['cardinfo']['to_read_status']==0){ ?>
                            <img src="<?php echo URL::webstatic("/images/getcards/change_btn1.png");?>" class="img4"/>
                    <?php }else{?><img src="<?php echo URL::webstatic("/images/getcards/change_btn2.png");?>" class="img4"/><?php }?>
                    </sup></p>
                    </div>
<?php }elseif (isset($v['outcardcount'])&&$v['outcardcount']==1){//递出的名片?>
                        <sup id="isornot_readcard_<?php echo  $v['per_user_id'];?>">
                        <?php if($v['cardinfo']['exchange_status']==1 && $v['cardinfo']['from_read_status']==0){ ?>
                            <img src="<?php echo URL::webstatic("/images/getcards/change_btn1.png");?>" />
                        <?php }else{?><img src="<?php echo URL::webstatic("/images/getcards/change_btn2.png");?>" /><?php }?>
                        </sup></p>
                    </div>
<?php }else{//默认情况下?>
                        <img src="<?php echo URL::webstatic("/images/getcards/change_btn2.png");?>" class="img4"/>
                        </p>
                    </div>
<?php }?>

                    <div class="ryl_comp_change01">
                        <?php if (isset($v['exchangecardcount'])&&$v['exchangecardcount']==1){//交换的名片?>
                                <span><b class="color" style="color:#000; font-weight:normal;">　　&nbsp;已交换</b></span>

                        <?php }elseif (isset($v['receivedcardcount'])&&$v['receivedcardcount']==1){//收到的名片?>
                                 <span id="exchangecard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['from_user_id'];?>_span">
                                 <?php if($v['cardinfo']['send_count']>1) {echo '<em>&nbsp;已重复收到<b>'.$v['cardinfo']['send_count'].'</b>次</em>';}?>
                                 <a href="javascript:void(0)" id="exchangecard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['from_user_id'];?>" class="changecard">
                                 <img src="<?php echo URL::webstatic("/images/getcards/change1.png");?>" class="img2"/></a>
                                <a id="exchangecard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['per_user_id'];?>_name" style="display:none"><?php echo $v['per_realname'];?></a>
                                </span>

                        <?php }elseif (isset($v['outcardcount'])&&$v['outcardcount']==1){//递出的名片?>
                                <span id="resendcard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['to_user_id'];?>_span">
                                 <?php if($v['cardinfo']['send_count']>1) {echo '<em>&nbsp;已重复递出<b>'.$v['cardinfo']['send_count'].'</b>次</em>';}else{echo "<em>　　　已递出</em>";}?>

                                  <?php if($v['cardinfo']['exchange_status']==0 && time()-(604800+$v['cardinfo']['send_time'])>0){ ?>
                                      <a href="#" class="re_send" id="resendcard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['to_user_id'];?>">
                                      <img src="<?php echo URL::webstatic("/images/sendcards/re_send.png")?>" class="img2"/></a>
                                      <a id="resendcard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['to_user_id'];?>_name" style="display:none"><?php echo $v['per_realname'];?></a>
                                      <a id="resendcard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['to_user_id'];?>_cishu_name" style="display:none"><?php echo $v['cardinfo']['send_count'];?></a>
                                 <?php }?>
                                 </span>

                        <?php }else{//默认情况下?>
                                    <span id="exchangecard_<?php echo  $v['per_user_id'];?>_remove">
                                    <a href="javascript:void(0)" id="exchangecard_<?php echo  $v['per_user_id']?$v['per_user_id']:$v['favorite_user_id'];?>" class="outcard">
                                    <img src="<?php echo URL::webstatic("/images/search_tz/search_btn1.png");?>" class="img2"/></a></span>
                                    <a href="javascript:void(0)" id="exchangecard_<?php echo  $v['per_user_id']?$v['per_user_id']:$v['favorite_user_id'];?>_name" style="display:none"><?php echo $v['per_realname'];?></a>
                        <?php }?>
                    </div>
                    <div class="ryl_comp_change02">
                         <?php if ($v['per_experience_stutas']==0){ ?>
                         <p class="btn"><img src="<?php echo URL::webstatic("/images/getcards/view2.png");?>" class="img1"/>
                         <span>暂无从业经验</span>
                        <?php } ?>
                        <?php if (isset($v['exchangecardcount'])&&$v['exchangecardcount']==1){//交换的名片?>
                             <?php if ($v['per_experience_stutas']!=0){?>
                             <p class="btn"><img src="<?php echo URL::webstatic("/images/getcards/view1.png")?>" class="img1"/>
                             <a href="javascript:void(0)" class="view" id="getexperience_<?php echo  $v['favorite_user_id'];?>_<?php echo $v['cardinfo']['card_id']?>_2">查看从业经验</a>
                             </p>
                             <?php }?>
                        <?php }elseif (isset($v['receivedcardcount'])&&$v['receivedcardcount']==1){//收到的名片?>
                             <?php if ($v['per_experience_stutas']!=0){?>
                             <p class="btn"><img src="<?php echo URL::webstatic("/images/getcards/view1.png");?>" class="img1"/>
                             <a href="javascript:void(0)" class="view" id="getexperience_<?php echo  $v['favorite_user_id']."_".$v['cardinfo']['card_id']."_1";?>">查看从业经验</a>
                             </p>
                             <?php }?>
                        <?php }elseif (isset($v['outcardcount'])&&$v['outcardcount']==1){//递出的名片?>
                             <?php if ($v['per_experience_stutas']!=0){?>
                             <p class="btn"><img src="<?php echo URL::webstatic("/images/getcards/view1.png");?>" class="img1"/>
                             <a href="javascript:void(0)" class="view" id="getexperience_<?php echo  $v['favorite_user_id'];?>__">查看从业经验</a>
                             </p>
                             <?php }?>
                        <?php }else{//默认情况下?>
                             <?php if ($v['per_experience_stutas']!=0){?>
                             <p class="btn"><img src="<?php echo URL::webstatic("/images/getcards/view1.png");?>" class="img1"/>
                             <a href="javascript:void(0)" class="view" id="getexperience_<?php echo  $v['favorite_user_id'];?>__">查看从业经验</a>
                             </p>
                             <?php }?>
                        <?php }?>
                          <p><img src="<?php echo URL::webstatic("/images/getcards/shouc_icon.png");?>" class="ysc" />
                          <a href="javascript:void(0)" id="<?php echo $v['favorite_id'];?>" class="qux">取消收藏</a>
                          </p>
                          <?php if(isset($v['isHasLetter']) && $v['isHasLetter']){?>
                          <p><a class="view_message" href="javascript:void(0)" id="viewmessage_<?php echo $v['favorite_user_id']?>"><img width="82" height="24" src="<?php echo URL::webstatic("/images/getcards/receivecard_lookbtn_03.jpg") ?>" alt="查看留言" /></a>
                          </p>
                          <?php }?>
                    </div>
                <div class="clear"></div>
             </div>
<?php }?>
                <?php if(count($list)!=0) { ?>
                <div class="list">
                    <div class="allbtn">
                        <div class="one"><input type="checkbox" />全选</div>
                        <div class="three favoritecancelall"><a href="#">批量取消收藏</a></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <?php }?>
            </div>
           <?=$page;?>
            <?php if(count($list)==0) { ?>
            <br><br><br><br><br>
            <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;没有找到您想要的名片，建议您放宽搜索条件
            <em><a href="<?php echo URL::website('/company/member/card/favorite')?>">展示全部名片</a></em>
            </div>
           <?php } ?>
        </div>
    </div>
    <!--右侧结束-->
    <div class="clear"></div>
</div>
<!--透明背景开始-->
<div id="getcards_opacity"></div>
<div id="getcards_opacity2"></div>
<!--透明背景结束-->
<!--查看从业经验开始-->
<div id="getcards_alert1">
    <div id="getcards_alert1_top">
        <a href="#" class="close">关闭</a>
        <div id="getcards_alert1_center">
            <div id="overflow-y"></div>
       </div>
    </div>
</div>
<!--查看从业经验结束-->
<!--从业经验弹出框开始-->
<div class="my_cyjy_view_box" style="z-index:99999">
    <a href="#" class="close">关闭</a>
    <h3>从业经验</h3>
    <div id="cyjy_content">
    </div>
</div>
<!--从业经验弹出框结束--
<!--查看名片开始-->
<div id="getcards_view" class="getcards_view2">
    <a href="#" class="close">关闭</a>
    <div id="getcards_view_content">
    </div>
</div>
<!--查看名片结束-->

<!--删除名片开始-->
<div id="getcards_delete">
    <a href="#" class="close">关闭</a>
    <div class="btn">
        <div id="deletecard_content">
        </div>
    </div>
</div>
<!--删除名片结束-->
<!--批量收藏名片开始-->
<div id="getcards_savebox">
    <a href="#" class="close">关闭</a>
    <div class="text">
        <p></p>
    </div>
</div>
<!--批量收藏名片结束-->
<!--批量删除名片开始-->
<div id="getcards_deletebox">
    <a href="#" class="close">关闭</a>
        <div class="text" style="background:none;padding-left:0px;text-align: left;color: #000;">
        <p></p>
        <p id="this_content2" style="width:270px; margin:0 auto;"><a href="#" class="ensure"><img src="<?php echo URL::webstatic("/images/getcards/ensure1.jpg");?>" /></a>
           <a href="#" class="cancel"><img src="<?php echo URL::webstatic("/images/getcards/cancel1.jpg");?>" /></a>
        </p>
        <input id="getcards_deletebox_hid" type="hidden" value="0"></input>
    </div>
</div>
<!--批量删除名片结束-->
<!--取消收藏名片开始-->
<div id="getcards_deleteboxs">
    <a href="#" class="close">关闭</a>
    <div class="text">
        <p>您确定要取消收藏此名片吗？</p>
        <p><a href="javascript:void(0)" id="del_favorite" class="ensure"><img src="<?php echo URL::webstatic("/images/getcards/ensure1.jpg");?>" /></a><a href="#" class="cancel"><img src="<?php echo URL::webstatic("/images/getcards/cancel1.jpg");?>" /></a></p>
    </div>
</div>
<!--取消收藏名片结束-->
<!--交换名片开始-->
<div id="getcards_change">
    <a href="#" class="close">关闭</a>
    <div class="getcards_change_suc">
    </div>
</div>
<!--mycardtype记录当前页面为我收藏的名片-->
<a id="mycardtype" style="display:none">3</a>

<!--查看留言开始-->
<div id="view_message_box">
    <a href="javascript:void(0)" class="close">关闭</a>
    <div class="text">
        <p>
            <ul>
                <li>
                    <font>2013-06-23 15:49</font>
                    <span>留言如下：<br/>您的这个项目很好，请尽快联系我，谢谢！</span>
                </li>
                <li>
                    <font>2013-06-23 15:49</font>
                    <span>留言如下：<br/>您的这个项目很好，请尽快联系我，谢谢！</span>
                </li>
                <li>
                    <font>2013-06-23 15:49</font>
                    <span>留言如下：<br/>您的这个项目很好，请尽快联系我，谢谢！</span>
                </li>
                <li class="last">
                    <font>2013-06-23 15:49</font>
                    <span>留言如下：<br/>您的这个项目很好，请尽快联系我，谢谢！</span>
                </li>
            </ul>
        </p>
    </div>
</div>
<!--查看留言结束-->