<?php echo URL::webcss("card_zg.css")?>
<?php echo URL::webjs("personcard.js")?>
<?php echo URL::webjs("my_jobs.js")?>
<?php echo URL::webcss("per_infor_0502.css")?>
    <!--右侧开始-->
    <div id="right">
           <a id="cardstyle_<?php echo $cardstyleid;?>" style="display:none"></a>
        <div id="right_top"><span>我的名片</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="person_card">
                <div class="show_publicity">
                    <span>您的名片公开度为</span>
                    <?php if($personinfo->per_open_stutas == 2):?>
                                        <span class="msg"><em><?php echo $industry; ?>行业查看</em></span>
                    <?php elseif($personinfo->per_open_stutas == 3):?>
                                        <span class="msg"><em>不允许任何企业查看</em></span>
                    <?php elseif($personinfo->per_open_stutas == 4):?>
                                        <span class="msg"><em>允许VIP企业查看</em></span>
                    <?php else:?>
                                        <span class="msg"><em>可以被所有企业查看</em></span>
                    <?php endif;?>
                    <a id="setbtn" class="set_btn" href="javascript:void(0)">设置名片公开度</a>
                    <div class="clear"></div>
                </div>
                <div id="cardBg" class="cardTop_zg">
                    <div class="card1">
                        <div class="card1_pt"><img src="<?php if($personinfo->per_photo){echo URL::imgurl($personinfo->per_photo);}else{ echo URL::webstatic('images/getcards/photo.png');}?>" /></div>
                        <div class="card1_nm card1_show">
                            <p class="name"><span><?php echo mb_substr($personinfo->per_realname,0,5,'UTF-8');?></span><?php  if($personinfo->per_gender == 1){echo '先生';}else{echo '女士';}?></p>
                            <p class="tel"><?php echo $per_phone;?></p>
                            <p class="email"><?php echo $email;?></p>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="card2">
                        <p><span class="shux">目前所在地</span><span class="zhi"><span class="zhi2"><?php if($this_area){echo $this_area;}else{echo '暂无信息';}?></span></span></p>
                        <p><span class="shux">年龄</span><span class="zhi"><span class="zhi2"><?php if($per_age){ echo $per_age;}else{echo '暂无信息';}?></span></span></p>
                          <p>
                          <?php if($per_education){?>
                        <span class="shux">学历</span><span class="zhi"><span class="zhi2"><?php echo $per_education;?></span></span>
                        <?php }?>
                        </p>
                        <p style="padding-top:12px;"><span class="shux">意向投资金额</span><span class="zhi"><span class="zhi2"><?php echo $per_amount;?></span></span></p>
                        <p><span class="shux">意向投资行业</span><span class="zhi"><span class="zhi2"><?php if($industry){echo $industry;}else{echo '暂无';}?></span></span></p>
                        <p><span class="shux">意向投资地区</span><span class="zhi"><span class="zhi2"><?php if($area){echo $area;}else{echo '暂无';}?></span></span></p>
                        <p><span class="shux">我的人脉关系</span><span class="zhi"><span class="zhi2"><?php if($per_connections){echo $per_connections;}else{echo '暂无';}?></span></span></p>
                        <p><span class="shux">我的投资风格</span><span class="zhi"><span class="zhi2"><?php if($per_investment_style){echo $per_investment_style;}else{echo '暂无';}?></span></span></p>
                        <p><span class="shux">我的标签</span><span class="zhi"><span class="zhi2"><?php if($personinfo->per_per_label){echo $personinfo->per_per_label;}else{echo '暂无';}?></span></span></p>
                        <p><span class="shux">店铺信息</span><span class="zhi"><span class="zhi2"><?php echo (isset($personinfo->per_shop_status) && $personinfo->per_shop_status)?($personinfo->per_shop_area? $personinfo->per_shop_area.'平方米':'0平方米'):'无';?></span></span></p>		
                    </div>
                    <?php if($ishasexperience){?>
                    <div class="card3"><span class="my_cyjy"><a id="getexperience_<?php echo $userid;?>" class="messageBox" data-tag="#myExperience" data-title="我的从业经验" data-type="click" href="javascript:void(0)">查看从业经验 &gt;</a></span></div>
                    <?php }?>
                </div>
                <div class="modify_btnlist">
                <!--<a href="<?php echo URL::website('/person/member/card/cardStyle')?>" class="btnlist_choose_card"></a>-->
                <a href="<?php echo URL::site('/person/member/basic/personupdate') ?>?type=2" class="btnlist_modify_card"></a></div>

                <div class="radio">
                  <form id="personForm" method="post" action="<?php echo URL::website('/person/member/card/cardopendegree')?>" enctype="multipart/form-data">
                    <input type="hidden" name="per_id" value="<?php echo $personinfo->per_id;?>" />
                    <p><input type="radio" name="cardtype" value="1"  <?php if($personinfo->per_open_stutas == 1){ ?>checked <?php }?>/> 允许所有的企业搜索到我的名片</p>
                    <p><input type="radio" name="cardtype"  value="2" <?php if($personinfo->per_open_stutas == 2){ ?>checked <?php }?> /> 只允许您的意向投资行业：“<?php echo $industry;?>”类企业搜索到我的名片</p>
                    <p><input type="radio" name="cardtype"  value="3" <?php if($personinfo->per_open_stutas == 3){ ?>checked <?php }?> /> 不允许任何企业搜索到我的名片</p>
                    <p class="btn"><input type="submit" value="确认" />　<input type="button" value="取消"  class="cancel"/></p>
                  </form>
                </div>
                <!-- <img id="card_image_url" src="<?php echo URL::imgurl($personinfo->per_card_image);?>" style="display:none" /> -->
            </div>
        </div>
    </div>

<!-- 各种弹出层 -->
<!-- 背景弹出层开始 -->
<div id="opacity_box"></div>
<!-- 背景弹出层结束 -->

<!-- 修改公开度弹框 -->
<div id="setpublicitybox" class="set_publicity_box" style="display:none;">
     <form id="personForm" method="post" action="<?php echo URL::website('/person/member/card/cardopendegree')?>" enctype="multipart/form-data">
    <dl>
        <dt>公开形式：</dt>
        <dd><input id="radioid1"  value="1" type="radio" <?php if($personinfo->per_open_stutas == 1){echo 'checked="checked"';}?> name="publicity" id="all" /><label for="radioid1">允许所有企业查看</label></dd>
        <dd><input id="radioid4" value="4" type="radio" <?php if($personinfo->per_open_stutas == 4){echo 'checked="checked"';}?> name="publicity" id="vip" /><label for="radioid4">只允许VIP企业查看</label></dd>
        <dd><input id="radioid2" value="2" type="radio" <?php if($personinfo->per_open_stutas == 2){echo 'checked="checked"';}?> name="publicity" id="suitable" /><label for="radioid2">只允许意向投资行业的企业查看</label></dd>
        <dd><input id="radioid3" value="3" type="radio" <?php if($personinfo->per_open_stutas == 3){echo 'checked="checked"';}?> name="publicity" id="no" /><label for="radioid3">不允许任何企业查看</label></dd>
    </dl>
     <input id="hidradiovalue" value="<?php echo $personinfo->per_open_stutas;?>" type="hidden" ></input>
    <div class="btn">
        <input type="submit" value="确定" />
        <input id="closebox" class='cancel' type="button" value="取消" />
    </div>
    </form>
</div>
<!-- 修改公开度弹框 END -->

<!-- 从业经验弹出框开始 -->
<div id="myExperience">
<div class="my_cyjy_view_box">
      <div id="cyjy_content">
            <?php if($experiences){?>
                <?php foreach($experiences as $experience){?>
                <div>
                    <p>工作时间：<?php echo substr($experience['exp_starttime'],0,4)."年".substr($experience['exp_starttime'],4)."月"?>到<?php if( $experience['exp_endtime']=='0' ){ echo '今天'; }else{echo substr($experience['exp_endtime'],0,4)."年".substr($experience['exp_endtime'],4)."月";}?></p>
                    <p>工作地点：<?php echo $experience['pro_name'].$experience['area_name']?></p>
                    
                    <?php if( $experience['exp_company_name']!='' ){?>
                    <p>企业名称：<?php echo $experience['exp_company_name']?></p>
                    <?php }?>                   
                    <p>企业性质：<?php foreach ( common::comnature_new() as $k=>$vs ){ if( $k==$experience['exp_nature'] ){ echo $vs; } }?></p>
                    
                    <?php if( $experience['exp_scale']!='0' && $experience['exp_scale']!='' ){?>
                    <p>企业规模：<?php foreach( common::comscale() as $k=>$vs ){ if( $k==$experience['exp_scale'] ){ echo $vs; } }?></p>
                    <?php }?>
                    
                    <?php if( $experience['exp_industry_sort_name'] ){?>
                    <p>行业类别：<?php echo $experience['exp_industry_sort_name']?></p>
                    <?php }?>
                    
                    <?php if( $experience['exp_department']!='' ){?>
                    <p>所在部门：<?php echo $experience['exp_department']?></p>
                    <?php }?>
                    
                    <?php if( $experience['pos_name']!='' ){?>
                    <p>职位类别：<?php echo $experience['pos_name']?></p>
                    <?php }?>
                    
                    <?php if( $experience['occ_name']!='' && $experience['occ_name']!='若无适合选项请在此填写'){?>
                    <p>职位名称：<?php echo $experience['occ_name']?></p>
                    <?php }?>
                    
                    <?php if( $experience['exp_description']!='' && $experience['exp_description']!='请详细描述您的职责范围、工作任务以及取得的成绩等。'){?>
                    <p>工作描述：<?php echo $experience['exp_description']?></p>
                    <?php }?>
                </div>
                <?php }?>
            <?php }?>
            </div>
</div>
</div>
<!--从业经验弹出框结束-->
<!--透明背景开始-->
<div id="getcards_opacity"></div>