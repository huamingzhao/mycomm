<?php echo URL::webjs("getcards_quick.js")?>
<?php echo URL::webjs("invitepro_zg.js")?>


    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>我收到的留言</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="getcards">
                <div class="list">
                </div> 
                <div class="ryl_comp_invest_bg">
                    <span class="span1">意向投资者 </span>
                    <span class="span2"><!--  递出留言状态 -->&nbsp;&nbsp;</span>
                    <span class="span3">操作</span>
                </div>
                <?php foreach( $list as $v ){
                	?>
                <div class="list">
                    <div class="list_checkbox">
                        <input type="checkbox" value="<?php echo  $v['card_id'];?>" name="exchangecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>"/>
                    </div>
                    <div class="left">
                        <img src="<?php  if(!empty($v['per_photo'])) { echo URL::imgurl($v['per_photo']);} else{ echo URL::webstatic("/images/getcards/photo.png") ;}?>" />
                        <a href="javascript:void(0)" class="viewcard" id="getonecard_<?php echo  $v['from_user_id'].'_'.$v['card_id'];?>">查看详情</a>
                    </div>
                    <div class="right" style="width:255px; padding-left:0;padding-bottom:0;">
                         <p style="font-weight:bold;"><?php echo  $v['per_realname'] ; if($v['per_gender']==2){ echo '  女士';}else{echo '  先生';}?></p>
                         <p>投资金额：<?php $monarr= common::moneyArr(); echo $v["per_amount"]== 0 ? '无': $monarr[$v["per_amount"]];?></p>
                         <p>投资行业：<?php echo $v['this_per_industry']; ?></p>
                         <p><b>收到留言时间：<?php echo date("Y-m-j H:i",$v['send_time'] ) ?></b>
                         <a id="exchangecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>_projectid" style="display:none;"><?php echo $v['to_project_id']?$v['to_project_id']:0;?></a>
                        </p>
                    </div>

                    <div class="ryl_comp_change01" id="exchangecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>_span">
                    &nbsp;
                    </div>
                    <a id="exchangecard_<?php echo  $v['card_id'];?>_<?php echo  $v['from_user_id'];?>_name" style="display:none"><?php echo $v['per_realname'];?></a>
                    <div class="ryl_comp_change02">
                         
                            <div style="height:20px; display:block; padding-left:3px; clear:both">
                            <img src="<?php echo URL::webstatic("/images/getcards/delete.png") ?>" style="padding-top:2px"/>
                            <a href="javascript:void(0)" class="delete" id="deletecard_<?php echo  $v['card_id'].'_'.$v['from_user_id'];?>">删除留言</a>
                            </div>
                            <?php if(isset($v['isHasLetter']) && $v['isHasLetter']){?>
	                          <div>
		                          <a class="view_message" href="javascript:void(0)" id="viewmessage_<?php echo $v['from_user_id']?>">
		                          <!-- <img width="82" height="24" src="<?php //echo URL::webstatic("/images/getcards/receivecard_lookbtn_03.jpg") ?>" alt="查看留言" /> -->
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
                        <!-- <div class="three favoriteall"><a href="#">批量收藏留言</a></div> -->
                        <div class="four"><a href="#">批量删除</a></div>
                        <div class="clear"></div>
                    </div>
                  </div>
                  <?php } ?>
            </div>
          <?php echo $page;?>
            <?php if(count($list)==0) { ?>
            <br><br><br><br><br>
            <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;没有找到您想要的留言，建议您放宽搜索条件
            <em><a href="<?php echo URL::website('/company/quick/card/receivecard')?>">展示全部留言</a></em>
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

<!--查看留言开始-->
<div id="getcards_view" class="getcards_view2">
    <div id="getcards_view_repeat">
        <a href="#" class="close">关闭</a>
        <div id="getcards_view_content">

      </div>
    </div>
</div>
<!--查看留言结束-->

<!--交换留言开始-->
<div id="getcards_change">
    <a href="#" class="close">关闭</a>
    <div class="getcards_change_suc">
    </div>
</div>
<!--交换留言结束-->

<!--删除留言开始-->
<div id="getcards_delete">
    <a href="#" class="close">关闭</a>
    <div class="btn">
        <div id="deletecard_content">
        </div>
    </div>
</div>
<!--删除留言结束-->
<!--批量交换留言开始-->
<div id="getcards_changebox">
    <a href="#" class="close">关闭</a>
    <div class="text">
        <p></p>
    </div>
</div>
<!--批量交换留言结束-->
<!--批量收藏留言开始-->
<div id="getcards_savebox">
    <a href="#" class="close">关闭</a>
    <div class="text">
        <p></p>
    </div>
</div>
<!--批量收藏留言结束-->
<!--批量删除留言开始-->
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
<!--批量删除留言结束-->
<!--mycardtype记录当前页面为我收到的留言-->
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