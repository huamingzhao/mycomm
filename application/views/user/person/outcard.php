<?php echo URL::webjs("person_getcard.js")?>
<?php echo URL::webjs("person_cernter_global.js") ?>
<!--右侧开始-->
<div id="right">
        <div id="right_top"><span>我递出的名片</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="person_getcard">
                <form id="serchform" method="get" action="<?php echo URL::website('/person/member/card/outcard')?>">
                <div id="getcard_filter">
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
                       <label>招商金额：</label>
                        <select name="project_amount_type" class="select1"  style="width:190px;">
                        <option  value="" >不限</option>
                        <?php $moneylist = common::moneyArr(); foreach ($moneylist as $k=>$v): ?>
                        <option  value="<?=$k;?>" <?php if(arr::get($postlist,'project_amount_type')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                        <?php endforeach; ?>
                        </select>
                   </p>
                    <p><label>递出名片时间：</label>
                    <select name="send_time" class="select1" style="width:220px;">
                            <option  value="" >不限</option>
                            <?php $timearr = common::receivetime(); foreach ($timearr as $k=>$v): ?>
                            <option  value="<?=$k;?>" <?php if(arr::get($postlist,'send_time')==$k): echo 'selected="selected"';endif;?> ><?=$v;?></option>
                            <?php endforeach; ?>
                     </select></p>
                    <p><label>递出名片次数：</label>
                    <select name="send_count" class="select1"  style="width:190px;">
                            <option  value="" >不限</option>
                            <?php $cardlist = common::card_number_of_times(); foreach ($cardlist as $k=>$v): ?>
                            <option  value="<?=$k;?>" <?php if(arr::get($postlist,'send_count')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                            <?php endforeach; ?>
                           </select></p>
                    <p><label>名片状态：</label><span>
                       <input type="radio" name="exchange_status" value=""  <?php if(arr::get($postlist,'exchange_status','')==''): echo 'checked="checked"';endif;?> > 不限
                       <input type="radio" name="exchange_status" value="1" <?php if(arr::get($postlist,'exchange_status')=='1'): echo 'checked="checked"';endif;?> > 已交换
                       <input type="radio" name="exchange_status" value="0" <?php if(arr::get($postlist,'exchange_status')=='0'): echo 'checked="checked"';endif;?> > 未交换
                    </span></p>
                    <p><label>名片显示：</label><span>
                        <input type="radio" name="from_read_status" value=""  <?php if(arr::get($postlist,'from_read_status','')==''): echo 'checked="checked"';endif;?> > 不限
                        <input type="radio" name="from_read_status" value="1" <?php if(arr::get($postlist,'from_read_status')=='1'): echo 'checked="checked"';endif;?> > 已查看名片
                        <input type="radio" name="from_read_status" value="0" <?php if(arr::get($postlist,'from_read_status')=='0'): echo 'checked="checked"';endif;?> > 未查看名片
                    </span></p>
                    <div class="clear"></div>
                    <div class="filter_btn">
                    <input type="image" src="<?php echo URL::webstatic("/images/getcards/getcards_filter_btn.jpg") ?>" /></div>
                </div>
                </form>
                <div class="all_list">
                    <ul>
                        <li class="one"><input type="checkbox" />全选</li>
                        <!-- <li class="three">批量收藏名片</li> -->
                        <li class="four">批量删除</li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <table>
                    <tr class="th">
                        <th width="25"></th>
                        <th width="100">意向招商者</th>
                        <th width="350"></th>
                        <th width="155">递出名片状态</th>
                        <th width="80">操作</th>
                    </tr>
                    <?php foreach( $list as $v ):?>
                    <tr class="td">
                        <td class="list_checkbox"><input type="checkbox" class="checkbox" value="<?php echo  $v['card_id'];?>" name="exchangecard_<?php echo  $v['card_id'];?>_<?php echo  $v['com_user_id'];?>"/></td>
                        <td>
                        
	                        <?php if($v['to_user_id']>0 && $v['com_contact'] && $v['com_phone']){//企业信息?>
	                        	<img class="ryl_card_logo" src="<?php  if(!empty($v['com_logo'])) { echo URL::imgurl($v['com_logo']);} else{ echo URL::webstatic("/images/getcards/photo.png") ;}?>" />
	                        	<a href="javascript:void(0)" class="viewcard_person" id="getonecard_<?php echo  $v['com_user_id'].'_'.$v['card_id'];?>">查看名片</a>
	                        <?php }else{//项目信息?>
	                        	<img class="ryl_card_logo" src="<?php echo $v['projectlogo'];?>" />
	                        	<a target="_blank" class="viewcard_project" href="<?php echo URL::website("project/{$v['to_project_id']}.html");?>">查看详情</a>
	                        <?php }?>
	                        
	                        
                        </td>
                        <td>
                            <?php if($v['to_user_id']>0 && $v['com_contact'] && $v['com_phone']){//企业信息?>
                                <p class="name"><b><?php echo $v['com_name'];?></b></p>
                            	<p>项目名称：<?php if($v['project_brand_name']!=''){echo $v['project_brand_name'];}else{ echo '暂无相关信息';}?></p>
                            	<p>招商金额：<?php if($v['project_brand_name']!=''){ echo $v['project_amount_type'];}else{ echo '暂无相关信息';}?></p>
                            <?php }else{//项目信息?>
                            	<p><?php if($v['projectname']){echo $v['projectname'];}else{ echo '暂无相关信息';}?></p>
                            	<p>招商金额：<?php echo $v['projectmonney'];?></p>
                            	<p>投资地区：<?php echo $v['projectarea'];?></p>
                            <?php }?>
                            
                            <p class="time">递出名片时间：<?php echo date("Y-m-j H:i",$v['send_time'] ) ?>
                             <sup id="isornot_readcard_<?php echo  $v['com_user_id'];?>">
                                <?php if($v['exchange_status']==1 && $v['from_read_status']==0){ ?>
                                 <img src="<?php echo URL::webstatic("/images/getcards/change_btn1.png") ?>" />
                                <?php }else{?>
                                 <img src="<?php echo URL::webstatic("/images/getcards/change_btn2.png") ?>" />
                                <?php }?>
                            </sup>
                            </p>
                        </td>
                        <td>
                            <?php if($v['exchange_status']==0){ ?>　
                                 <?php if($v['send_count']>1) {?>
                                  <p class="re_get" id="resendcard_<?php echo  $v['card_id'];?>_<?php echo  $v['com_user_id'];?>_cishu" style="font-style:normal;color:#000;">已重复递出<span  style="color: #FF0000"><?php echo $v['send_count'];?></span>次</p>
                                  <?php }else{?>
                                   <p class="change_card5" id="resendcard_<?php echo  $v['card_id'];?>_<?php echo  $v['com_user_id'];?>_cishu" style="font-style:normal;color:#000;">已递出</p>
                                  <?php }?>
                                 <a id="resendcard_<?php echo  $v['card_id'];?>_<?php echo  $v['com_user_id'];?>_cishu_name" style="display:none"><?php echo $v['send_count'];?></a>
                                  <?php if(time()-(604800+$v['send_time'])>0){ ?>
                                      <p class="change_card6" id="resendcard_<?php echo  $v['card_id'];?>_<?php echo  $v['com_user_id'];?>_span" style="font-style:normal;color:#000;">
                                         <a href="#"  id="resendcard_<?php echo  $v['card_id'];?>_<?php echo  $v['com_user_id'];?>" > 重复递出名片</a>
                                         <a id="resendcard_<?php echo  $v['card_id'];?>_<?php echo  $v['com_user_id'];?>_name" style="display:none"><?php echo $v['com_name'];?></a>
                                      </p>
                                  <?php }?>

                            <?php }else{?>　
                            <p class="change_card4">已交换</p>
                            <?php }?>
                        </td>
                        <td>
                        
                        <?php if($v['to_user_id']>0 && $v['com_contact'] && $v['com_phone']){//企业信息才能有收藏?>
                        	<?php if($v['favorite_status']){ ?>
                        	<p class="save2" >已收藏</p>
                        	<?php }else{?>
                        	<p class="save" id="favoritecard_<?php echo  $v['card_id'];?>_<?php echo  $v['com_user_id'];?>_span" ><a href="#" id="favoritecard_<?php echo  $v['card_id'];?>_<?php echo  $v['com_user_id'];?>" >收藏名片</a></p>
                        <?php }}else{echo '</br>';}?>
                        
                        <p class="delete"><a href="javascript:void(0)" id="deletecard_<?php echo  $v['card_id'].'_'.$v['com_user_id'];?>">删除名片</a></p>
                       <?php if(isset($v['liuyan']) && $v['liuyan']){?>
                           <a class="view_message" href="javascript:void(0)" id="viewmessage_<?php echo $v['com_user_id']?>"><img width="82" height="24" src="<?php echo URL::webstatic("/images/getcards/receivecard_lookbtn_03.jpg") ?>" alt="查看留言" /></a>
                        <?php }?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </table>
                 <?php if(count($list)>0) { ?>
                    <div class="all_list">
                        <ul>
                            <li class="one"><input type="checkbox" />全选</li>
                            <!-- <li class="three">批量收藏名片</li> -->
                            <li class="four">批量删除</li>
                        </ul>
                        <div class="clear"></div>
                    </div>
                     <?php } ?>
            </div>
           <?=$page;?>
            <?php if(count($list)==0) { ?>
            <br><br><br><br><br>
            <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;没有找到您想要的名片，建议您放宽搜索条件
            <em><a href="<?php echo URL::website('/person/member/card/outcard')?>">展示全部名片</a></em>
            </div>
           <?php } ?>
        </div>
    </div>
<!--右侧结束-->
<div class="clear"></div>

<!--透明背景开始-->
<div id="getcards_opacity"></div>
<!--透明背景结束-->
<!--查看名片开始-->
<div id="person_viewcard">
    <div id="person_layout">
        <a href="#" class="close">关闭</a>
        <div  id="layout_compan">
        </div>
    </div>

</div>
<!--查看名片结束-->
<!--删除名片开始-->
<div id="getcards_delete">
    <a href="#" class="close">关闭</a>
    <div class="btn" id="deletecard_content">
    </div>
</div>
<!--批量收藏名片开始-->
<div id="getcards_savebox">
    <a href="#" class="close">关闭</a>
    <div class="text">
        <p></p>
    </div>
</div>
<!--交换名片开始-->
<div id="getcards_change">
    <a href="#" class="close">关闭</a>
    <div class="getcards_change_suc">
    </div>
</div>
<!--mycardtype记录当前页面为我递出的名片-->
<a id="mycardtype" style="display:none">2</a>
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