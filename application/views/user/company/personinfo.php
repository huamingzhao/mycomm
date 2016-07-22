<?php echo URL::webjs("getcards.js")?>
<?php echo URL::webjs("invitepro_zg.js")?>


    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>我收到的名片</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="getcards">
                <form id="serchform" method="get" action="<?php echo URL::website('/company/member/card/receivecard')?>">
                <div class="filter">
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
                    <select name="per_amount" style="width:190px;">
                    <option  value="" >不限</option>
                    <?php $moneylist = common::moneyArr(); foreach ($moneylist as $k=>$v): ?>
                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'per_amount')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                    <?php endforeach; ?>
                    </select>
                     </p>
                     <p>
                     <label>收到名片时间：</label>
                    <select name="send_time" class="select1" style="width:224px;">
                    <option  value="" >不限</option>
                    <?php $timearr = common::receivetime(); foreach ($timearr as $k=>$v): ?>
                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'send_time')==$k): echo 'selected="selected"';endif;?> ><?=$v;?></option>
                    <?php endforeach; ?>
                     </select>
                     </p>
                     <p><label>收到名片次数：</label>
                    <select name="send_count" class="select1"  style="width:190px;">
                            <option  value="" >不限</option>
                            <?php $cardlist = common::card_number_of_times(); foreach ($cardlist as $k=>$v): ?>
                            <option  value="<?=$k;?>" <?php if(arr::get($postlist,'send_count')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                            <?php endforeach; ?>
                           </select></p>
                    <p class="radio">
                    <label>名片状态：</label>
                        <input type="radio" name="exchange_status" value=""  <?php if(arr::get($postlist,'exchange_status','')==''): echo 'checked="checked"';endif;?> > 不限
                        <input type="radio" name="exchange_status" value="1" <?php if(arr::get($postlist,'exchange_status')=='1'): echo 'checked="checked"';endif;?> > 已交换
                        <input type="radio" name="exchange_status" value="0" <?php if(arr::get($postlist,'exchange_status')=='0'): echo 'checked="checked"';endif;?> > 未交换
                    </p>
                    <p>
                    <label>名片显示：</label>
                        <input type="radio" name="to_read_status" value=""  <?php if(arr::get($postlist,'to_read_status','')==''): echo 'checked="checked"';endif;?> > 不限
                        <input type="radio" name="to_read_status" value="1" <?php if(arr::get($postlist,'to_read_status')=='1'): echo 'checked="checked"';endif;?> > 已查看
                        <input type="radio" name="to_read_status" value="0" <?php if(arr::get($postlist,'to_read_status')=='0'): echo 'checked="checked"';endif;?> > 未查看
                    </p>
                    <div class="clear"></div>
                    <div class="searchBtn" style=" height:32px; width:121px; margin:0 auto; display:block"><input type="image" src="<?php echo URL::webstatic("/images/getcards/getcards_filter_btn.jpg");?>" />
                    </div>
                  </div>
                </form>
                <div class="list">
                    <div class="allbtn">
                        <div class="one"><input type="checkbox" />全选</div>
                        <!--<div class="two"><a href="#">批量交换名片</a></div>-->
                        <div class="three favoriteall"><a href="#">批量收藏名片</a></div>
                        <div class="four"><a href="#">批量删除</a></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="ryl_comp_invest_bg">
                    <span class="span1">意向投资者 </span>
                    <span class="span2">递出名片状态</span>
                    <span class="span3">操作</span>
                </div>
                <?php foreach( $list as $v ){?>
                <div class="list">
                    <div class="list_checkbox">
                        <input type="checkbox" value="<?php echo  $v['card_id'];?>" name="exchangecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>"/>
                    </div>
                    <div class="left">
                        <img src="<?php  if(!empty($v['per_photo'])) { echo URL::imgurl($v['per_photo']);} else{ echo URL::webstatic("/images/getcards/photo.png") ;}?>" />
                        <a href="javascript:void(0)" class="viewcard" id="getonecard_<?php echo  $v['from_user_id'].'_'.$v['card_id'];?>">查看名片</a>
                    </div>
                    <div class="right" style="width:255px; padding-left:0;padding-bottom:0;">
                         <p style="font-weight:bold;"><?php echo  $v['per_realname'] ; if($v['per_gender']==2){ echo '  女士';}else{echo '  先生';}?></p>
                         <p>投资金额：<?php $monarr= common::moneyArr(); echo $v["per_amount"]== 0 ? '无': $monarr[$v["per_amount"]];?></p>
                         <p>投资行业：<?php echo $v['this_per_industry']; ?></p>
                         <p><b>收到名片时间：<?php echo date("Y-m-j H:i",$v['send_time'] ) ?></b>
                         <a id="exchangecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>_projectid" style="display:none;"><?php echo $v['to_project_id']?$v['to_project_id']:0;?></a>
                        <span id="isornot_readcard_<?php echo  $v['from_user_id'];?>">
                            <?php if($v['to_read_status']==0){ ?>
                            <img src="<?php echo URL::webstatic("/images/getcards/change_btn1.png") ?>" class="img4"/>
                            <?php }else{?>
                            <img src="<?php echo URL::webstatic("/images/getcards/change_btn2.png") ?>" class="img4"/>
                            <?php }?>
                        </span>
                        </p>
                    </div>

                    <div class="ryl_comp_change01" id="exchangecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>_span">
                    <?php if($v['exchange_status']==0){ ?>
                    	<?php if($v['send_count']>1){ ?><em style="font-style:normal;">&nbsp;&nbsp;已重复收到<b><?php echo  $v['send_count'];?></b>次</em><?php }?>
                        		<a href="javascript:void(0)" id-backup="exchangecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>" id="exchangecard_<?php echo $v['per_user_id'];?>_2_<?php echo $v['card_id'];?>_1" perinfo="<?php echo URL::imgurl($v['per_photo']);?>|<?php echo mb_substr($v['per_realname'],0,25,'UTF-8');?>|<?php echo $v['locate_per_area'];?>|<?php echo $v['this_per_industry'];?>|<?php echo $v["per_amount"]== 0 ? '无': $monarr[$v["per_amount"]];?>|<?php echo $v['per_gender'];?>|<?php echo $v['per_photo']?1:0;?>|<?php echo $v['huoyuedu']?>|<?php echo $v['to_project_id'];?>"  class="send_letter">
                            		<img src="<?php echo URL::webstatic("/images/getcards/change1.png") ?>" class="img2"/>
                            	</a>                    	
                   		<?php } else{?>
                            <b style="color:#000; font-weight:normal;" class="color">　　&nbsp;已交换</b>
                    <?php }?>
                    </div>
                    <a id="exchangecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>_name" style="display:none"><?php echo $v['per_realname'];?></a>
                    <div class="ryl_comp_change02">
                         <?php if ($v['per_experience_stutas']==0){ ?>
                         <p class="btn">
                         <img src="<?php echo URL::webstatic("/images/getcards/view2.png") ?>" class="img1"/>
                         <span>暂无从业经验</span>
                        <?php } else{?>
                         <p class="btn"><img src="<?php echo URL::webstatic("/images/getcards/view1.png") ?>"class="img1"/>
                         <a href="javascript:void(0)" class="view" id="getexperience_<?php echo  $v['from_user_id'].'_'.$v['card_id'];?>">查看从业经验</a>
                        <?php }?>
                          <?php if($v['favorite_status']){ ?>　
                              <img src="<?php echo URL::webstatic("/images/getcards/shouc_icon1.png") ?>" class="ysc" id="favoritecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>" /> <span id="favoritecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>_span">已收藏</span>　　
                              <?php }else{?>　
                              <img src="<?php echo URL::webstatic("/images/getcards/shouc_icon.png") ?>" id="favoritecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>" class="ysc" />  <span id="favoritecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>_span" ><a href="#" id="shoucang_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>" class="sc"  style="padding-bottom:0;">收藏名片</a></span>　
                              <?php }?>
                            <div style="height:20px; display:block; padding-left:3px; clear:both">
                           <!-- <a id="exchangecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>_name" style="display:none"><?php echo $v['per_realname'];?></a>-->
                            <img src="<?php echo URL::webstatic("/images/getcards/delete.png") ?>" style="padding-top:2px"/>
                            <a href="javascript:void(0)" class="delete" id="deletecard_<?php echo  $v['card_id'].'_'.$v['from_user_id'];?>">删除名片</a>
                            </div>
                            <?php if(isset($v['isHasLetter']) && $v['isHasLetter']){?>
	                          <div>
		                          <a class="view_message" href="javascript:void(0)" id="viewmessage_<?php echo $v['from_user_id']?>">
		                          <img width="82" height="24" src="<?php echo URL::webstatic("/images/getcards/receivecard_lookbtn_03.jpg") ?>" alt="查看留言" />
		                          </a>
	                          </div>
	                        <?php }?>

                    </div>
                    <div class="clear"></div>
                </div>

            <?php }?>
                 <?php if(count($list)>0) { ?>
                  <div class="list">
                    <div class="allbtn">
                        <div class="one"><input type="checkbox" />全选</div>
                        <!--<div class="two"><a href="#">批量交换名片</a></div>-->
                        <div class="three favoriteall"><a href="#">批量收藏名片</a></div>
                        <div class="four"><a href="#">批量删除</a></div>
                        <div class="clear"></div>
                    </div>
                  </div>
                  <?php } ?>
            </div>
          <?php echo $page;?>
            <?php if(count($list)==0) { ?>
            <br><br><br><br><br>
            <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;没有找到您想要的名片，建议您放宽搜索条件
            <em><a href="<?php echo URL::website('/company/member/card/receivecard')?>">展示全部名片</a></em>
            </div>
           <?php } ?>
        </div>
    </div>
    <!--右侧结束-->
  <div class="clear"></div>
</div>
<!--弹出框开始-->
<div id="getcard_opacity"></div>
    <div id="getcard_alert_bg"><a href="#" class="close" style="height:20px;display:block;width:20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
    <div class="context">
    <div id='getcard_opacity_content'>
    </div>
    </div>
</div>
<!--弹出框结束-->

<!--弹出框开始-->
<div id="getonecard_opacity">
</div>
<!--弹出框结束-->

<!--透明背景开始-->
<div id="getcards_opacity"></div>
<div id="getcards_opacity2"></div>
<!--透明背景结束-->

<!--从业经验弹出框开始-->
<div class="my_cyjy_view_box" style="z-index: 999999">
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

<!--交换名片开始-->
<div id="getcards_change">
    <a href="#" class="close">关闭</a>
    <div class="getcards_change_suc">
    </div>
</div>
<!--交换名片结束-->

<!--删除名片开始-->
<div id="getcards_delete">
    <a href="#" class="close">关闭</a>
    <div class="btn">
        <div id="deletecard_content">
        </div>
    </div>
</div>
<!--删除名片结束-->
<!--批量交换名片开始-->
<div id="getcards_changebox">
    <a href="#" class="close">关闭</a>
    <div class="text">
        <p></p>
    </div>
</div>
<!--批量交换名片结束-->
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
        <p id="this_content2" style="width:270px; margin:0 auto;" ><a href="#" class="ensure"><img src="<?php echo URL::webstatic("/images/getcards/ensure1.jpg") ?>" /></a>
           <a href="#" class="cancel"><img src="<?php echo URL::webstatic("/images/getcards/cancel1.jpg") ?>" /></a>
        </p>
        <input id="getcards_deletebox_hid" type="hidden" value="0"></input>
    </div>
</div>
<!--批量删除名片结束-->
<!--mycardtype记录当前页面为我收到的名片-->
<a id="mycardtype" style="display:none">1</a>

<!--查看留言开始-->
<div id="view_message_box">
    <a href="javascript:void(0)" class="close">关闭</a>
    <div class="text">
        <p>
            <ul>
            </ul>
        </p>
    </div>
</div>
<!--查看留言结束-->