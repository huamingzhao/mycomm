<?php echo URL::webcss("mingpian_zg.css")?>
<?php echo URL::webjs("favorite_person.js")?>
<?php echo URL::webjs("person_cernter_global.js")?>
 <!--右侧开始-->
<style>
#wrap_repeat .right {margin-left:0;}
#getcards .list .right p a.ryl_send_again{display:block;width:106px;height:22px;background:url(<?php echo URL::webstatic("images/sendcards/re_send.png")?>) no-repeat;text-indent:-9999px;margin:0 auto;}
</style>
    <div id="right">
        <div id="right_top"><span>我收藏的名片</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="getcards">
                 <form action="" method="get" name="first" id='first' enctype="application/x-www-form-urlencoded" target="_self">
                 <div class="filter">
                    <p>
                    <label>招商行业：</label>
                                 <select id="hye" name="parent_id" style="width:120px;">
                                <option value="" >请选择</option>
                                <?php foreach ($list_industry as $value): ?>
                                <option value="<?=$value->industry_id;?>" <?php if(arr::get($postlist,'parent_id')==$value->industry_id): echo 'selected="selected"';endif;?> ><?=$value->industry_name;?></option>
                                <?php endforeach; ?>
                            </select>
                          <select name="project_industry_id" id="hye1" style="width:120px;">
                                 <option value="" >请选择</option>
                                <?php if(arr::get($postlist,'parent_id')!='' && count($list_industry2)){ foreach ($list_industry2 as $k=>$v): ?>
                                 <option value="<?=$v['industry_id'];?>" <?php if($v['industry_id'] == arr::get($postlist,'project_industry_id')) echo "selected='selected'"; ?> ><?=$v['industry_name'];?></option>
                                 <?php endforeach; } ?>
                        </select>
                    </p>
                    <p>
                    <label>投资金额：</label>
                    <select name="project_amount_type" style="width:190px;">
                    <option  value="" >不限</option>
                    <?php $moneylist = common::moneyArr(); foreach ($moneylist as $k=>$v): ?>
                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'project_amount_type')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                    <?php endforeach; ?>
                    </select>
                     </p>
                     <p>
                     <label>收藏名片时间：</label>
                    <select name="favorite_time" class="select1" style="width:220px;">
                    <option  value="" >不限</option>
                    <?php $timearr = common::receivetime(); foreach ($timearr as $k=>$v): ?>
                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'favorite_time')==$k): echo 'selected="selected"';endif;?> ><?=$v;?></option>
                    <?php endforeach; ?>
                     </select>
                     </p>
                     <p>
                     <label></label>
                     </p>
                    <p class="radio">
                    <label>名片状态：</label>
                        <?php $sta=FALSE; if(arr::get($postlist,'exchange_status')<1) $sta=TRUE; echo Form::radio('exchange_status','0',$sta);?>不限
                        <?php $sta=FALSE; if(arr::get($postlist,'exchange_status')==1) $sta=TRUE; echo Form::radio('exchange_status','1',$sta);?>已交换
                        <?php $sta=FALSE; if(arr::get($postlist,'exchange_status')==2) $sta=TRUE; echo Form::radio('exchange_status','2',$sta);?>已收到
                        <?php $sta=FALSE; if(arr::get($postlist,'exchange_status')==3) $sta=TRUE; echo Form::radio('exchange_status','3',$sta);?>已递出
                    </p>
                    <p>
                    <label>名片显示：</label>
                        <?php  $sta=FALSE; if(arr::get($postlist,'from_read_status')=='-1') $sta=TRUE; echo Form::radio('from_read_status','-1',$sta);?>不限
                        <?php  $sta=FALSE; if(arr::get($postlist,'from_read_status')=='1') $sta=TRUE; echo Form::radio('from_read_status','1',$sta);?>已查看名片
                        <?php  $sta=FALSE; if(arr::get($postlist,'from_read_status')=='0') $sta=TRUE; echo Form::radio('from_read_status','0',$sta);?>未查看名片
                    </p>
                    <div class="clear"></div>
                    <div class="searchBtn" style=" height:32px; width:121px; margin:0 auto; display:block"><input type="image" src="<?php echo URL::webstatic("/images/getcards/getcards_filter_btn.jpg");?>" />
                    </div>
                  </div>
                </form>
                <div class="list">
                    <div class="allbtn">
                        <div class="one"><input type="checkbox">全选</div>
                        <div class="three"><a href="javascript:void(0)">批量取消收藏</a></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="paraTitle01">
                    <span class="span1" style=" padding-left:40px; width:420px;">意向招商者 </span>
                    <span class="span2">递出名片状态</span>
                    <span class="span3">操作</span>
                </div>
                <?php foreach( $list as $v){?>
                <div class="list" id="list_<?php echo  $v['favorite_id'];?>" style="border-bottom:1px solid #e3e3e3;padding: 15px 0 15px 13px;">
                    <div class="list_checkbox">
                        <input type="checkbox" value="<?php echo  $v['favorite_id'];?>" name="exchangecard_<?php echo  $v['favorite_id'];?>"/>
                    </div>
                    <div class="left">
                        <img src="<?php  if(!empty($v['com_logo'])) { echo URL::imgurl($v['com_logo']);} else{ echo URL::webstatic('/images/getcards/photo.png') ;}?>" />
                        <a href="javascript:void(0)" class="viewcard_person" id="getonecard_<?php echo  $v['favorite_user_id'].'_';?><?php if (isset($v['cardinfo']['card_id'])) echo $v['cardinfo']['card_id']?>_<?php if (isset($v['cardinfo']['card_type'])) echo $v['cardinfo']['card_type'];?>">查看名片</a>
                    </div>

                    <div class="right" style="width:300px;padding-bottom:0;">
                        <p class="name"><b style="color:#000; font-weight:bold;"><?php echo $v['com_name'];?></b></p>
                        <?php if ($v['project_brand_name']!=""){ ?>
                         <p>项目名称：<?php echo $v['project_brand_name'];?></p>
                         <p>招商金额：<?php echo $v['project_amount_type'];?></p>
                        <?php } else{?>
                         <p>项目名称：暂无相关信息</p>
                         <p>招商金额：暂无相关信息</p>
                        <?php }?>
                        <p><b style="float:left;padding-right:5px;">收藏名片时间：<?php echo date("Y-m-j H:i",$v['favorite_time'] ) ?></b>
<?php if (isset($v['exchangecardcount'])&&$v['exchangecardcount']==1){//交换的名片?>
                    <sup id="isornot_readcard_<?php echo  $v['com_user_id'];?>" style="float:left;">
                    <?php if(($v['cardinfo']['from_read_status']==0 && $v['cardinfo']['card_type']==2)){ ?>
                    <img style="padding-top:3px;" src="<?php echo URL::webstatic("/images/getcards/change_btn1.png");?>" class="img4"/>
                    <?php }else{?><img style="padding-top:3px;" src="<?php echo URL::webstatic("/images/getcards/change_btn2.png");?>" class="img4"/><?php }?>
                    </sup>
                    </p>
                    </div>
                    <div class="right right_0" style="padding-left:0;margin-left:0;"><b style="color:#000; font-weight:normal;">已交换</b></div>
<?php }elseif (isset($v['receivedcardcount'])&&$v['receivedcardcount']==1){//收到的名片?>
                        <sup  style="float:left;" id="isornot_readcard_<?php echo  $v['com_user_id'];?>">
                        <?php if($v['cardinfo']['to_read_status']==1){ ?>
                            <img style="padding-top:3px;"  src="<?php echo URL::webstatic("/images/getcards/change_btn2.png");?>" />
                        <?php }else{?>
                         <img style="padding-top:3px;" src="<?php echo URL::webstatic("/images/getcards/change_btn1.png");?>" class="img4"/>
                         <?php }?>
                         </sup>
                    </div>
                    <div class="right right_0" style="margin-left:0; padding-left:0;">
                        <?php if($v['cardinfo']['send_count']>1){echo  '<em style="font-style:normal;color:#000;">已重复收到 <b style="color: #FF0000">'.$v['cardinfo']['send_count'].'</b>次</em>';}?>
                        <p class="change_card" id="exchangecard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['from_user_id'];?>_span"><a href="javascript:void(0)" id="exchangecard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['cardinfo']['from_user_id'];?>"><img src="<?php echo URL::webstatic("/images/getcards/change1.png");?>" class="img2"/></a>
                        <a id="exchangecard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['com_user_id'];?>_name" style="display:none"><?php echo $v['com_name'];?></a>
                        </p>
                    </div>
<?php }elseif (isset($v['outcardcount'])&&$v['outcardcount']==1){//递出的名片?>
				    <sup style="float:left;" id="isornot_readcard_<?php echo  $v['com_user_id'];?>">
                        <?php if($v['cardinfo']['exchange_status']==1 && $v['cardinfo']['from_read_status']==0){ ?>
                            <img style="padding-top:3px;" src="<?php echo URL::webstatic("/images/getcards/change_btn1.png");?>" />
                        <?php }else{?><img style="padding-top:3px;"  src="<?php echo URL::webstatic("/images/getcards/change_btn2.png");?>" /><?php }?>
                    </sup></div>
                    <div class="right right_0" style="margin-left:0; padding-left:0;">
                    <span id="resendcard_<?php echo $v['cardinfo']['card_id'].'_'.$v['com_user_id'];?>_span">
	                        <?php if($v['cardinfo']['send_count']>1){echo  '<em style="font-style:normal;color:#000;">已重复递出 <b style="color: #FF0000">'.$v['cardinfo']['send_count'].'</b>次</em>';}else{echo '<p>已递出</p>';}?>
	                        <a id="resendcard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['com_user_id'];?>_cishu_name" style="display:none"><?php echo $v['cardinfo']['send_count'];?></a>
	                       <?php if(time()-(604800+$v['cardinfo']['send_time'])>0){ ?>
		                       <p class="change_card6" >
		                            <a href="#" class="ryl_send_again" id="resendcard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['com_user_id'];?>" > 重复递出名片</a>
		                            <a id="resendcard_<?php echo  $v['cardinfo']['card_id'];?>_<?php echo  $v['com_user_id'];?>_name" style="display:none"><?php echo $v['com_name'];?></a>
		                       </p>
	                       <?php }?>
	               </span> 
	            </div>
     			                     
<?php }else{//默认情况下?>
							 <sup id="isornot_readcard_678" style="float:left;">
								<img src="<?php echo URL::webstatic("/images/getcards/change_btn2.png");?>" style="padding-top:3px;">
								</sup> </div>
          
<?php }?>
                <div class="right right_1" style="padding-right:15px;"><img src="<?php echo URL::webstatic("/images/getcards/shouc_icon.png");?>" class="ysc" /> <a href="javascript:void(0)" id="<?php echo $v['favorite_id'];?>" class="sc">取消收藏</a>
				 
				
				<?php if(isset($v['liuyan']) && $v['liuyan']){?>
				 <a class="view_message" href="javascript:void(0)" id="viewmessage_<?php echo $v['com_user_id']?>"><img width="82" height="24" src="<?php echo URL::webstatic("/images/getcards/receivecard_lookbtn_03.jpg") ?>" alt="查看留言" /></a>
                 <?php }?>
				</div>
                <div class="clear"></div>
             </div>
<?php }?>
<?php if(count($list)!=0) { ?>
                <div class="list">
                    <div class="allbtn">
                        <div class="one"><input type="checkbox" />全选</div>
                        <div class="three"><a href="javascript:void(0)">批量取消收藏</a></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <?php }?>
            </div>
           <?=$page;?>
            <?php if(count($list)==0) { ?>
            <br><br><br><br><br>
            <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;没有找到您想要的名片，建议您放宽搜索条件
            <em><a href="<?php echo URL::website('/person/member/card/favorite')?>">展示全部名片</a></em>
            </div>
           <?php } ?>
        </div>
    </div>
    <!--右侧结束-->
    <div class="clear"></div>
</div>
<!--透明背景开始-->
<div id="getcards_opacity"></div>
<!--透明背景结束-->
<!--查看名片开始-->
<!--查看名片结束-->

<!--交换名片开始-->
<div id="getcards_change">
    <a href="#" class="close">关闭</a>
    <div class="getcards_change_suc">
    </div>
</div>
<!--交换名片结束-->
<!--批量交换名片开始-->
<div id="getcards_changebox">
    <a href="#" class="close">关闭</a>
    <div class="text">
        <p></p>
    </div>
</div>
<!--批量交换名片结束-->
<!--删除名片开始-->
<div id="getcards_delete">
    <a href="#" class="close">关闭</a>
    <div class="btn" id="deletecard_content">
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
<!--取消收藏名片开始-->
<div id="getcards_deleteboxs" class="message_box">
    <dl>
        <dt><font>取消收藏</font><a class="close" href="#" title="关闭"></a></dt>
        <dd class="text">
                <p>您确定要取消收藏此名片吗？</p>
                <p class="btn"><a href="javascript:void(0)" id="del_favorite" class="ok">确定</a><a href="#" class="cancel">取消</a></p>
           
        </dd>
    </dl>
</div>
<!--取消收藏名片结束-->
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
