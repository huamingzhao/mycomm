<?php echo URL::webcss("platform/index0514.css")?>
<?php echo URL::webcss("find_invester.css")?>
<?php echo URL::webcss("common.css")?>
<?php echo URL::webjs("platform/comsearchinvestor.js");?>
<?php echo URL::webjs("getcards.js")?>
<?php echo URL::webjs("platform/searchhome.js")?>

<!--公共背景框-->
<div style="height:auto; background-color:#fff; padding:10px 0 5px 0;" class="main">
   <div class="map_bg">
       <div class="map_bg01"></div>
       <div class="map_bg02">
          <!--投资者 详情-->
          <div class="invester_information">
             <div class="title">投资者基本信息</div>
             <div class="invester_information_cont">
               <div class="invester_information_unit">
                  <div class="invester_inform_unit invester_inform_unit_first">
                     <!--左侧-->
                     <div class="invester_infor_left invester_last_view">
                      <p><!--<a href="#" class="invester_man"></a>-->
                      <?php if($result['per_photo'] && project::checkProLogo(URL::imgurl($result['per_photo']))){ 
                      	  $per_photostatus = 1;?>
                          <a href="javascript:void(0)"><img src='<?php echo URL::imgurl($result['per_photo']);?>'/></a>
                      <?php }elseif($result['per_gender']==1){  
                      	   $per_photostatus = 2; ?>
                       	  <a href="javascript:void(0)" class="invester_man"></a>
                      <?php }else{ 
                      	   $per_photostatus = 3;?>
                      	   <a href="javascript:void(0)" class="invester_woman"></a>
                      <?php }?>
                      <!--<a href="#" class="invester_woman"><img src="images/find_invester/photo_pic.jpg" alt="投资者" /></a>--><em><?php echo $result['last_logintime']; ?>登录</em></p>
                      <div class="clear"></div>
                      <?php if($result['recentlyviewproject_id']){?>
                      <span>最近看过<a href="<?php echo urlbuilder::project($result['recentlyviewproject_id']);?>" target="_blank" title="<?php echo $result['recentlyviewproject'];?>"><?php echo $result['recentlyviewproject'];?></a>项目</span>
                      <?php }?>
                      <div class="clear"></div>
                     </div>
                     <!--右侧-->
                     <div class="invester_infor_right">
                        <p class="name"><span><?php echo $result['per_realname'] ;?></span><em>
                        	<?php if($result['per_gender']==1){
								echo '男';
							}elseif($result['per_gender']==2){
								echo '女';
							}else{
								echo '';
							} ?></em></p>
                        <p class="active_num"><?php echo $result['this_huoyuedu'];?></p>
                        <ul class="invester_infor_cont">
                        <?php if($result['per_birthday']){?>
						   <li><label>出生日期：</label><p><span><?php echo date('Y-m-d',$result['per_birthday']);?></span></p></li>
						<?php }?>
                            <li>
                                <div class="invester_infor_tebie01">
                                <?php if($result['mobile']){?>
                                    <label>手机号码：</label>
                                    <span class="red" id="mobile_profile"><?php echo $result['mobile']; ?></span>
                                    <?php if($result['valid_mobile']){?>
										<a href="javascript:void(0)" class="invester_infor_check"><img src="<?php echo URL::webstatic("images/find_invester/icon05.jpg"); ?>" /></a>
									<?php }else{?>
										<a href="javascript:void(0)" class="invester_infor_check"><img src="<?php echo URL::webstatic("images/find_invester/icon06.jpg"); ?>" /></a>
									<?php }?>
								<?php }?>
                                    <div class="clear"></div>
                                 <?php if($result['email']){?>
                                    <label>邮箱：</label>
                                    <span class="red" id="email_profile"><?php echo $result['email']; ?></span>                 
                                 	<?php if($result['valid_email']){?>
										<a href="javascript:void(0)"  class="invester_infor_check"><img src="<?php echo URL::webstatic("images/find_invester/icon05.jpg"); ?>" /></a>
									<?php }else{?>
										<a href="javascript:void(0)"  class="invester_infor_check"><img src="<?php echo URL::webstatic("images/find_invester/icon06.jpg"); ?>" /></a>
									<?php }?>
                                 <?php }?>
                                </div>
                                <!-- 马上联系no.1开始  -->
                                <div class="invester_infor_tebie02" >
                                <input type="hidden" id="hidden_opcity_id"/>
                                <a href="javascript:void(0)" id="getonecard_<?php echo $result['per_user_id'].'_0';?>" class="viewcard orange02">马上联系</a>
                                </div>
                                <!-- 马上联系no.1结束  -->
                                <div class="clear"></div>
                            </li>
							<?php if($result['this_area']){?>
                            <li><label>目前所在地：</label><p><span><?php echo $result['this_area'];?></span></p></li>
                            <?php }?>
                            <?php if($result['per_card_id']){?>
                            <li><label>身份证：</label><p><span>***************</span>
                            	<?php if($result['per_auth_status'] == 2){?><a href="javascript:void(0)"  class="invester_infor_check"><img src="<?php echo URL::webstatic("images/find_invester/icon05.jpg"); ?>" /></a>
                            	<?php }else{?><a href="javascript:void(0)" class="invester_infor_check"><img src="<?php echo URL::webstatic("images/find_invester/icon06.jpg"); ?>" /></a>
                            	<?php }?>
                            </p></li>
                            <?php }?>
                            <?php if($result['per_education']){?>
                            <li><label>学历：</label><p><span> <?php echo $result['per_education'] ;?></span></p></li>
                            <?php }?>
                            <?php if($result['per_remark']){?>
                            <li><label>个人简介：</label><p><span><?php echo $result['per_remark'] ;?></span></p></li>
                            <?php }?>
                        </ul>
                        <div class="clear"></div>
                     </div>
                     <div class="clear"></div>
                  </div>
               </div>
               <!--意向投资信息-->
               <div class="invester_information_unit">
                  <div class="invester_inform_unit">
                    <h3>意向投资信息</h3>
                    <ul>
                    <?php if($result['per_amount']){?>
                    <li><label>意向投资金额：</label><span class="red"><?php echo $result['per_amount'];?></span></li>
                    <?php }?>
                    <?php if($result['industry']){?>
                    <li><label>意向投资行业：</label><span><?php echo $result['industry']; ?></span></li>
                    <?php }?>
                    <?php if($result['area']){?>
                    <li><label>意向投资地区：</label><span><?php echo $result['area']; ?></span></li>
                    <?php }?>
                    <?php if($result['tag_name']){?>
                    <li><label>投资者标签：</label><span><?php echo $result['tag_name'] ;?></span></li>
                    <?php }?>
                    <?php if($result['per_connections']){?>
                    <li><label>投资者人脉关系：</label><span><?php echo $result['per_connections']; ?></span></li>
                    <?php }?>
                    <?php if($result['per_investment_style']){?>
                    <li><label>投资者的投资风格：</label><span><?php echo $result['per_investment_style'] ;?></span></li>
                    <?php }?>
                    <li><label>店铺：</label><span><?php if($result['user_person']->per_shop_status==1 && $result['user_person']->per_shop_area){echo $result['user_person']->per_shop_area.'平方米';}else{echo '无';}?></span></li>
                    </ul>
                    <div class="clear"></div>
                    <p class="invester_inform_unit_btn"> 
                    	<!-- 发信判断开始 -->
                    	<?php if(isset($result['card_type']) && $result['card_type']==3){//已交换 ?>
				         <span><a href="javascript:void(0)" class="gray">已发信</a></span>
				
				         <?php }elseif (isset($result['card_type']) && $result['card_type']==2){//递出的名片?>
				         <?php if(time()-(604800+$result['sendtime'])>0){ //7天后又可以重复递出?>
				         <a href="javascript:void(0)"  class="orange send_letter" id="letter_<?php echo $result['per_user_id'];?>_1_<?php echo $result['card_id'];?>_1" perinfo="<?php echo URL::imgurl($result['per_photo']);?>|<?php echo mb_substr($result['per_realname'],0,3,'UTF-8');?>|<?php echo $result['this_area'] ;?>|<?php echo $result['industry'];?>|<?php echo $result['per_amount'];?>|<?php echo $result['per_gender'];?>|<?php echo $per_photostatus;?>|<?php echo $result['this_huoyuedu'];?>" >发信</a>
				         <a id="resendcard_<?php echo $result['card_id'];?>_<?php echo $result['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($result['per_realname'],0,4,'utf-8') ?></a>
				          <?php }else{?>
				         <span><a href="javascript:void(0)" class="gray">已发信</a></span>
				         <?php }?>
				
				         <?php }elseif (isset($result['card_type']) && $result['card_type']==1){//收到的名片?>
				         <a href="javascript:void(0)" class="orange send_letter" id="letter_<?php echo $result['per_user_id'];?>_2_<?php echo $result['card_id'];?>_1" perinfo="<?php echo URL::imgurl($result['per_photo']);?>|<?php echo mb_substr($result['per_realname'],0,3,'UTF-8');?>|<?php echo $result['this_area'] ;?>|<?php echo $result['industry'];?>|<?php echo $result['per_amount'];?>|<?php echo $result['per_gender'];?>|<?php echo $per_photostatus;?>|<?php echo $result['this_huoyuedu'];?>">发信</a>
				         <a id="exchangecard_<?php echo $result['card_id'];?>_<?php echo $result['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($result['per_realname'],0,4,'utf-8') ?></a>
				         <?php }else{?>
				         <a href="javascript:void(0)" class="orange send_letter" id="letter_<?php echo $result['per_user_id'];?>_3_0_1" perinfo="<?php echo URL::imgurl($result['per_photo']);?>|<?php echo mb_substr($result['per_realname'],0,3,'UTF-8');?>|<?php echo $result['this_area'] ;?>|<?php echo $result['industry'];?>|<?php echo $result['per_amount'];?>|<?php echo $result['per_gender'];?>|<?php echo $per_photostatus;?>|<?php echo $result['this_huoyuedu'];?>" >发信</a>
				         <a id="exchangecard_<?php echo  $result['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($result['per_realname'],0,4,'utf-8') ?></a>
				         <?php }?>
				
				        <!-- <a href="javascript:void(0)"  class="viewcard" id="getonecard_<?php echo  $result['per_user_id'].'_0';?>">查看名片</a>  -->    	
                    	<!-- 发信判断结束 -->
                    	<!-- 马上联系no.2开始  -->
                    	 <span id="invester_infor_tebie03">
                         <a href="javascript:void(0)" id="getonecard_<?php echo $result['per_user_id'].'_0';?>" class="viewcard orange">马上联系</a>
                         </span>
                        <!-- 马上联系no.2结束  -->
                    </p>
                    <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
               </div>
               <!--从业经验-->
               <?php if( !empty($experiences)){?>
               <div class="invester_information_unit">
                  <div class="invester_inform_unit">
                    <h3>从业经验</h3>                  
                    <?php foreach($experiences as $key=>$experience){ ?>
                    <ul <?php if($key % 2 == 0){echo 'class="ryl_cy_perience_gray"';}?>>
                    <li><label>工作时间：</label><span><?php echo substr($experience['exp_starttime'],0,4)."年".substr($experience['exp_starttime'],4)."月"?>到<?php if( $experience['exp_endtime']=='0' ){ echo '今天'; }else{echo substr($experience['exp_endtime'],0,4)."年".substr($experience['exp_endtime'],4)."月";}?></span></li>
                    <li><label>工作地点：</label><span><?php echo $experience['pro_name'].$experience['area_name']?></span></li>
                    <li><label>企业名称：</label><span><?php echo $experience['exp_company_name'];?></span></li>
                    <li><label>企业性质：</label><span><?php foreach ( common::comnature_new() as $k=>$vs ){ if( $k==$experience['exp_nature'] ){ echo $vs; } }?></span></li>
                    <?php if( $experience['exp_scale']!='0' && $experience['exp_scale']!='' ){?>
                    <li><label>企业规模：</label><span><?php foreach( common::comscale() as $k=>$vs ){ if( $k==$experience['exp_scale'] ){ echo $vs; } }?></span></li>
                    <?php }?>
                    <li><label>行业类别：</label><span><?php echo $experience['exp_industry_sort_name'];?></span></li>
                    <?php if( $experience['exp_department']!='' ){?>
                    <li><label>所在部门：</label><span><?php echo $experience['exp_department'];?></span></li>
                    <?php }?>
                    <li><label>职位类别：</label><span><?php echo $experience['pos_name'];?></span></li>
                    <li><label>职位名称：</label><span><?php echo $experience['occ_name'];?></span></li>
                    <li><label>工作描述：</label><span><?php echo $experience['exp_description'];?><div class="clear"></div></span></li>
                    </ul>
                   	<?php }?>
                    <div class="clear"></div>
                    <p class="invester_inform_unit_btn">
                    	<!-- 发信判断开始 -->
                    	<?php if(isset($result['card_type']) && $result['card_type']==3){//已交换 ?>
				         <span><a href="javascript:void(0)" class="gray">已发信</a></span>
				
				         <?php }elseif (isset($result['card_type']) && $result['card_type']==2){//递出的名片?>
				         <?php if(time()-(604800+$result['sendtime'])>0){ //7天后又可以重复递出?>
				         <a href="javascript:void(0)"  class="orange send_letter" id="letter_<?php echo $result['per_user_id'];?>_1_<?php echo $result['card_id'];?>_2" perinfo="<?php echo URL::imgurl($result['per_photo']);?>|<?php echo mb_substr($result['per_realname'],0,3,'utf-8');?>|<?php echo $result['this_area'] ;?>|<?php echo $result['industry'];?>|<?php echo $result['per_amount'];?>|<?php echo $result['per_gender'];?>|<?php echo $per_photostatus;?>|<?php echo $result['this_huoyuedu'];?>" >发信</a>
				         <a id="resendcard_<?php echo $result['card_id'];?>_<?php echo $result['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($result['per_realname'],0,4,'utf-8') ?></a>
				          <?php }else{?>
				         <span><a href="javascript:void(0)" class="gray">已发信</a></span>
				         <?php }?>
				
				         <?php }elseif (isset($result['card_type']) && $result['card_type']==1){//收到的名片?>
				         <a href="javascript:void(0)" class="orange send_letter" id="letter_<?php echo $result['per_user_id'];?>_2_<?php echo $result['card_id'];?>_2" perinfo="<?php echo URL::imgurl($result['per_photo']);?>|<?php echo mb_substr($result['per_realname'],0,3,'utf-8');?>|<?php echo $result['this_area'] ;?>|<?php echo $result['industry'];?>|<?php echo $result['per_amount'];?>|<?php echo $result['per_gender'];?>|<?php echo $per_photostatus;?>|<?php echo $result['this_huoyuedu'];?>">发信</a>
				         <a id="exchangecard_<?php echo $result['card_id'];?>_<?php echo $result['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($result['per_realname'],0,4,'utf-8') ?></a>
				         <?php }else{?>
				         <a href="javascript:void(0)" class="orange send_letter" id="letter_<?php echo $result['per_user_id'];?>_3_0_2" perinfo="<?php echo URL::imgurl($result['per_photo']);?>|<?php echo mb_substr($result['per_realname'],0,3,'utf-8');?>|<?php echo $result['this_area'] ;?>|<?php echo $result['industry'];?>|<?php echo $result['per_amount'];?>|<?php echo $result['per_gender'];?>|<?php echo $per_photostatus;?>|<?php echo $result['this_huoyuedu'];?>" >发信</a>
				         <a id="exchangecard_<?php echo  $result['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($result['per_realname'],0,4,'utf-8') ?></a>
				         <?php }?>
                  		<!-- 发信判断结束 -->
                  		<!-- 马上联系no.3开始  -->
                  		<span id="invester_infor_tebie04">
                         <a href="javascript:void(0)" id="getonecard_<?php echo $result['per_user_id'].'_0';?>" class="viewcard orange">马上联系</a>
                        </span>
                        <!-- 马上联系no.3结束  -->
                    </p>
					
                    <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php }?>
               <div class="clear"></div>
             </div>
             <div class="clear"></div>
          </div>
          <div class="clear"></div>
       </div>
       <div class="map_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>

<!--透明背景开始-->
<div id="getcards_opacity"></div>
<div id="getcards_opacity2"></div>
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
        <p id="this_content2" style="width:270px; margin:0 auto;"><a href="#" class="ensure"><img src="<?php echo URL::webstatic("/images/getcards/ensure1.jpg") ?>" /></a>
           <a href="#" class="cancel"><img src="<?php echo URL::webstatic("/images/getcards/cancel1.jpg") ?>" /></a>
        </p>
        <input id="getcards_deletebox_hid" type="hidden" value="0"></input>
    </div>
</div>
<!--名片服务提醒框结束-->
<!--企业发信时没有审核通过的项目弹出层开始-->
<div id="error_box_renling" class="tan_floor" style="position:fixed;top:50%;left:50%;margin:-144px 0 0 -327px;display:none;z-index:9999">
    <input type="hidden" id="get_type"  qiye_value="<?php echo $ishasproject;?>" />
    <a href="#" class="tan_floor_close"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_close.jpg')?>" /></a>
    <div class="clear"></div>
    <span id="error_box_renling_content" style="display:block;padding-top:90px;">只有企业用户才能查看详情，赶紧注册企业用户吧！</span>
    <p></p>
</div>
<!--企业发信时没有审核通过的项目弹出层结束-->
<!-- 发信弹出层开始 -->
<div class="message_box" id="letter_box" style=" width:640px; margin-left:-321px; margin-top:-309px;">
  <dl>
    <dt>
      <input type="hidden"  id="text_output_judge" value="2"/>
      <font>向“<em id="letter_name" style="font-style:normal;">张先生</em>”递送名片并留言吧</font>
      <a class="close" href="#" title="关闭"></a>
    </dt>
    <dd>
      <div class="invester_deliver_shadow"></div>
      <div class="invester_deliver_cont">
   
      <!-- 个人资料显示区 -->
      <div class="invester_deliver_cont_l" id="per_info_letter"></div>
      <form>
      <div class="invester_deliver_cont_r">
          <div class="invester_fc_r_title" >自定义信件内容</div>
          <!-- 模板信件内容 -->
          <div id="modify_box">
            <div class="invester_fc_letter01">
              <input name="template" type="radio" value="0" checked="checked"/>
              <p>
                <span id="tem_content"></span>
                <a href="javascript:void(0)" id="tem_modify">修改</a>
              </p>
              <div class="clear"></div>
            </div>
          </div>
          <!-- 精选信件内容 -->
          <div class="invester_fc_r_title">精选信件内容</div>
          <ul>
            <li><input name="template" type="radio" value="1" /><p><span>想你的钱生钱吗？让我们满足您的愿望吧！</span></p></li>
            <li><input name="template" type="radio" value="2" /><p><span>刚毕业找不到满意的工作，投资做生意吧！买车买房，并不是那不遥远！</span></p></li>
            <li><input name="template" type="radio" value="3" /><p><span>有权有钱是人们一生的追求，您还在等什么呢？</span></p></li>
            <li><input name="template" type="radio" value="4" /><p><span>有钱有时间的您，想坐等收钱环游世界吗？</span></p></li>
            <li><input name="template" type="radio" value="5" /><p><span>想让家人过的更舒适吗？让小投资大回报帮您实现愿望吧！</span></p></li>
          </ul>
          <p class="invester_fc_change"><a href="#"><!-- 换一批 --></a></p>
          <p class="btn" id="submit_box">
            <a href="javascript:void(0)" class="ok">确定</a>
            <a href="javascript:void(0)" class="btn_cancle cancel">取消</a>
          </p>
          <div class="clear"></div>
      </div>
      </form>
      <div class="clear"></div>
    </div>
    </dd>
  </dl>
</div>
<!-- 发信弹出层结束 -->