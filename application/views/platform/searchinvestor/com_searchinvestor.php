<?php echo URL::webjs("platform/comsearchinvestor.js");?>
企业用户<br>
              <!--主体部分开始-->
                <div id="right">
                    <div id="right_top"><span>搜索投资者</span></div>
                    <div id="right_con">
                      <div class="ryl_search_touzi" style="padding-bottom:15px;">
                        <p style="padding-top:10px;"><b>搜索投资者</b></p>
                         <form action="" method="get" enctype="application/x-www-form-urlencoded" target="_self">
                         <div class="ryl_gj_search" >
                        <p>
                           <label>个人所在地：</label>
                           <select id="address" name="per_area"  style="width:110px;">
                                 <option value="">不限</option>
                                  <?php foreach ($area as $v){?>
                                  <option value="<?=$v['cit_id']?>" <?php if($v['cit_id']==arr::get($postlist, 'per_area')){echo "selected='selected'";}?>><?=$v['cit_name']?></option>
                                 <?php }?>
                            </select>
                          </p>
                          
                           <p>
                           <label>选择学历：</label>
                            <select name="per_education" style="width:110px;" id="hye">
                            <option  value="" >不限</option>
                            <?php foreach ($edu_arr as  $k=>$v):?>
                            <option value="<?=$k;?>" <?php if(arr::get($postlist,'per_education')==$k): echo 'selected="selected"';endif;?> ><?=$v;?></option>
                            <?php endforeach; ?>
                            </select>
                          </p>
                          
                         <p>
                           <label>投资行业：</label>
                            <select name="parent_id" style="width:110px;" id="hye">
                            <option  value="" >不限</option>
                            <?php $primarylist = common::primaryIndustry(0); foreach ($primarylist as $v):?>
                            <option value="<?=$v->industry_id;?>" <?php if(arr::get($postlist,'parent_id')==$v): echo 'selected="selected"';endif;?> ><?=$v->industry_name;?></option>
                            <?php endforeach; ?>
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


                         </div>
                          <span><input name="valid_mobile" type="checkbox" value="1" <?php if(arr::get($postlist,'valid_mobile','')==1){ echo 'checked';}?> id="valid_mobile"/>是否手机验证</span>  
                            <input type="image" src="<?php echo URL::webstatic("images/search_btn/search_btn.png") ?>" />
                         </form>
                          <?php foreach ($list as $per_v){ ?>
                            <div class="test">
                            <a class="atest" style="display: none"><?php echo $per_v['per_user_id'];?></a>
                            </div>
                             <img alt="图片验证" src="<?php  if(!empty($per_v['per_photo'])) { echo URL::imgurl($per_v['per_photo']);} else{ echo URL::webstatic("images/getcards/photo.png") ;}?>" /><br>
                             <p style="font-weight:bold;"><?php echo  $per_v['per_realname'] ; if($per_v['per_gender']==2){ echo '  女士';}else{echo '  先生';}?></p>
                       
                           <p>投资金额：<?php $monarr= common::moneyArr(); echo $per_v['per_amount'] == 0 ? '无': $monarr[$per_v['per_amount']];?></p>
                           <p>投资行业：<?php echo $per_v['this_per_industry']; ?></p>
                           <p>个性说明：<?php echo $per_v['per_remark']; ?></p>
                            <p>学历：<?php if(isset($per_v['per_education']) && $per_v['per_education']){echo $edu_arr[$per_v['per_education']];}?></p>
                          <?php echo $per_v['is_pay']; ?>
                          
                          <?php if(isset($per_v['card_type']) && $per_v['card_type']==3){//已交换 ?>
                           <br>已经交换  <br>
                          <?php }elseif (isset($per_v['card_type']) && $per_v['card_type']==2){//递出的名片?>
                          <br>已经递出  <br>
                          	 <?php if(isset($per_v['send_time']) && time()-(604800+$per_v['send_time'])>0){ ?>
                          	  <br>又可以重复递出  <br>
                          	 <?php }?>
                          	 
                          	 <?php }elseif (isset($per_v['card_type']) && $per_v['card_type']==1){//收到的名片?>
                          	 <br>收到的名片  <br>
                          	 <?php }else{?>
                          	 <br>无记录  <br>
                          	 <?php }?>
                          
                          <?php }?>

                    <a href="javascript:void(0)" id="test">ddddd</a>

                                 
                     
                      <div class="clear"></div>
                      <?php echo $page; ?>
                      </div>
                  </div>
                </div>
  <!--主体部分结束-->

<div class="clear"></div>
<!--透明背景开始-->
<div id="getcards_opacity"></div>
<div id="getcards_opacity2"></div>
<!--透明背景结束-->

