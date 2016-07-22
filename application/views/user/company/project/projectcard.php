<!--主体部分开始-->
<?php echo URL::webcss("contact_list.css")?>
<?php echo URL::webcss("find_invester.css")?>
<?php echo URL::webjs("getcards.js")?>
<?php echo URL::webjs("invitepro_zg.js")?>
<?php echo URL::webjs("platform/searchhome.js")?>
<!--右侧开始-->

<div id="right">
	<div id="right_top">
		<span>名片统计</span>
		<div class="clear"></div>
	</div>
	
	<div id="right_con">
	
		<!-- 搜索开始 -->
		<div class="c_search_card">
			<form method='get' action='/company/member/project/getprojectcard'>
				<input type="hidden" name="project_id" value="<?php if(isset($get['project_id'])){echo $get['project_id'];}?>"/>
				<label for="">收到名片时间：</label>  
				<input id="startDate" name="start_time" value="<?php if(isset($get['start_time'])){ echo $get['start_time'];}?>" type="text" readonly="readonly" onclick="WdatePicker({minDate:'1990-01-01',maxDate:'#F{$dp.$D(\'stopDate\')||\'%y-%M-%d\'}'})"/>
				<i>至</i>  
				<input id="stopDate" name="end_time" value="<?php if(isset($get['end_time'])){ echo $get['end_time'];}?>" type="text" readonly="readonly" onclick="WdatePicker({minDate:'#F{$dp.$D(\'startDate\')||\'1990-01-01\'}',maxDate:'%y-%M-%d'})"/>
				<label for="" class="invester_name">投资者姓名：</label> 
				<input type="text" name="invester_name"  value="<?php if(isset($get['invester_name'])){ echo $get['invester_name'];}?>"/> <input type="submit" value="搜索" />
		    </form>
		</div>
		<!-- 搜索结束 -->
		
		<!-- 循环开始 -->
		<div class="invester_cont_list">
			<ul>
				<?php if(isset($notice) && $notice){echo '<li><dt>'.$notice.'</dt></li>';}?>
				<?php foreach($per_info as $k=>$v){?>
				<!-- 循环列表开始 -->				
				<li "<?php if($k%2==0){echo 'class="list_right"';}?>">
					<dl>
						<!-- 头像处理开始 -->
						<?php 
							$perphoto1 = URL::imgurl($v['per_photo']);
							if(project::checkProLogo($perphoto1)){//有存在的图片
								$per_photostatus = 1; //获取头像状态，用在发信弹出层
								echo '<dt class="invester_man"><a title="'.$v['per_realname'].'" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$v['per_user_id'].'"><img alt="投资者'.$v['per_realname'].'" src="'.$perphoto1.'"></a></dt>';
							}else{//没有头像
								if($v['per_gender']==1){//男
									$per_photostatus = 2;
									echo '<dt class="invester_man"><a title="'.$v['per_realname'].'" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$v['per_user_id'].'"></a></dt>';
								}else{
									$per_photostatus = 3;
									echo '<dt class="invester_woman"><a title="'.$v['per_realname'].'" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$v['per_user_id'].'"></a></dt>';
								}
							}
							//头像处理结束
						?>
						<!-- 头像处理结束 -->
						<dd><?php echo $v['last_logintime'];?></dd>
					</dl>
					<div class="invester_infor">
						<p class="invester_infor_name">
							<!-- 姓名处理开始 -->
							<a target='_blank' href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['per_user_id'];?>"><?php echo mb_substr($v['per_realname'],0,6,'UTF-8');?></a>
							<!-- 姓名处理结束 -->
							<!-- 手机验证处理开始 -->
							<?php if($v['isMobile']){?>
	                            <span class="checked">已验证</span>
	                            <?php }else{?>
								<span>未验证</span>
	                        <?php }?>
							<!-- 手机验证处理结束 -->
							<!-- 金jinspan  银yinspan 铜tongspan -->
                            <i class="jypspan tongspan"></i>
						</p>
						<p class="invester_infor_active"><?php echo $v['this_huoyuedu'];?></p>
						<!-- 行业 金额 地区 开始-->
						<p class="invester_infor_choose"><?if($v['this_per_industry']){foreach($v['this_per_industry'] as $keyIarr => $valIarr){?><a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('inid'=>$keyIarr));?>"><?=$valIarr?>加盟</a>&nbsp;<?}}else{echo "无";}?>，<?if(arr::get($v, 'per_amount', 0) != 0) {?><a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('atype'=>arr::get($v, 'per_amount', 0)));?>"><?php $monarr= common::moneyArr(); echo  arr::get($v, 'per_amount') == 0 ? '无': $monarr[arr::get($v, 'per_amount')];?></a><?}else{echo "无";}?><?php if($v['this_per_area']){echo '，';}?><?php 
                            if(mb_strlen($v['this_per_area'])>4){
                            	echo mb_substr($v['this_per_area'],0,3,'UTF-8').'...';
                            }
                            else{
                            	echo $v['this_per_area'];
                            }              
                            ?>
                        </p>
                        <!-- 行业 金额 地区 结束-->
 
						<p class="invester_infor_btn">
							<!-- 发信判断开始 -->
							<?php if($loginStatus=1){?>
								<?php if(isset($v['card_type']) && $v['card_type']==3){//已交换 ?>
								<span><a href="javascript:void(0)" class="gray">已发信</a></span>
								
								<?php }elseif (isset($v['card_type']) && $v['card_type']==2){//递出的名片?>
								<?php if(time()-(604800+$v['sendtime'])>0){ //7天后又可以重复递出?>
								<a href="javascript:void(0)"  class="orange send_letter" id="letter_<?php echo $v['per_user_id'];?>_1_<?php echo $v['card_id'];?>_1" perinfo="<?php echo URL::imgurl($v['per_photo']);?>|<?php echo mb_substr($v['per_realname'],0,3,'UTF-8');?>|<?php echo $v['this_per_area'] ;?>|<?if($v['this_per_industry']){foreach($v['this_per_industry'] as $keyIarr => $valIarr){echo $valIarr;}}else{echo "";}?>|<?php echo $v["per_amount"]== 0 ? '无': $monarr[$v["per_amount"]];?>|<?php echo $v['per_gender'];?>|<?php echo $per_photostatus;?>|<?php echo $v['this_huoyuedu'];?>" rel="nofollow" >发信</a>
								<a id="resendcard_<?php echo $v['card_id'];?>_<?php echo $v['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($v['per_realname'],0,4,'utf-8') ?></a>
								<?php }else{?>
								<span><a href="javascript:void(0)" class="gray">已发信</a></span>
								<?php }?>
								
								<?php }elseif (isset($v['card_type']) && $v['card_type']==1){//收到的名片?>
								<a href="javascript:void(0)" class="orange send_letter" id="letter_<?php echo $v['per_user_id'];?>_2_<?php echo $v['card_id'];?>_1" perinfo="<?php echo URL::imgurl($v['per_photo']);?>|<?php echo mb_substr($v['per_realname'],0,3,'UTF-8');?>|<?php echo $v['this_per_area'] ;?>|<?if($v['this_per_industry']){foreach($v['this_per_industry'] as $keyIarr => $valIarr){echo $valIarr;}}else{echo "";}?>|<?php echo $v["per_amount"]== 0 ? '无': $monarr[$v["per_amount"]];?>|<?php echo $v['per_gender'];?>|<?php echo $per_photostatus;?>|<?php echo $v['this_huoyuedu'];?>" rel="nofollow" >发信</a>
								<a id="exchangecard_<?php echo $v['card_id'];?>_<?php echo $v['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($v['per_realname'],0,4,'utf-8') ?></a>
								<?php }else{?>
								<a href="javascript:void(0)" class="orange send_letter" id="letter_<?php echo $v['per_user_id'];?>_3_0_1" perinfo="<?php echo URL::imgurl($v['per_photo']);?>|<?php echo mb_substr($v['per_realname'],0,3,'UTF-8');?>|<?php echo $v['this_per_area'] ;?>|<?if($v['this_per_industry']){foreach($v['this_per_industry'] as $keyIarr => $valIarr){echo $valIarr;}}else{echo "";}?>|<?php echo $v["per_amount"]== 0 ? '无': $monarr[$v["per_amount"]];?>|<?php echo $v['per_gender'];?>|<?php echo $per_photostatus;?>|<?php echo $v['this_huoyuedu'];?>" rel="nofollow" >发信</a>
								<a id="exchangecard_<?php echo  $v['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($v['per_realname'],0,4,'utf-8') ?></a>
					            <?php }?>		
							<?php }?>
							<!-- 发信判断结束 -->
							<!-- 马上联系开始 -->
							<?php if($loginStatus =1){//登录过
                            	if(isset($v['is_pay']) && !$v['is_pay']){?>
                            		<a href="javascript:void(0)"   id="getonecard_<?php echo $v['per_user_id'].'_0';?>" class="viewcard orange" rel="nofollow">马上联系</a>
                            	<?php }else{?>
                            		<a href="javascript:void(0)"  id="getonecard_<?php echo $v['per_user_id'].'_0';?>" class="viewcard orange" rel="nofollow">马上联系</a>
                            <?php }}?>
							<!-- 马上联系结束 -->
							<a target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['per_user_id']?>" class="gray" rel="nofollow">查看详情</a>

						</p>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</li>
				<!-- 循环列表结束 -->
				<?php }?>
			</ul>
			<div class="clear"></div>
			<!--翻页开始-->
			<div class="ryl_search_result_page">
				<?=$page;?>
			</div>
			<div class="clear"></div>
			<!--翻页结束-->
		</div>
		<!-- 循环结束 -->
	</div>
</div>
<div class="clear"></div>
</div>
</div>
<!--右侧结束-->
<div class="clear"></div>
</div>
<!-- 各种弹出层开始 -->
<!--透明背景开始-->
<div id="getcards_opacity"></div>
<div id="getcards_opacity2"></div>
<div id="opacity"></div>
<!--透明背景结束-->
<!--查看名片开始-->
<div id="getcards_view" class="getcards_view2">
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
    <input type="hidden" id="get_type"  qiye_value="<?php //echo $ishasproject;?>" />
    <a href="#" class="tan_floor_close"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_close.jpg')?>" /></a>
    <div class="clear"></div>
    <span id="error_box_renling_content" style="display:block;padding-top:90px;">只有企业用户才能查看详情，赶紧注册企业用户吧！</span>
    <p></p>
</div>
<!--企业发信时没有审核通过的项目弹出层结束-->
<!-- 发信弹出层开始 -->
<div class="invester_deliver_message" id="letter_box" style="display: none;">
   <input type="hidden"  id="text_output_judge" value="2"/>
   <p class="title"><span>向<em id="letter_name"></em>递送名片并留言吧</span><a href="javascript:void(0)" class="close"></a></p>
   <div class="invester_deliver_shadow"></div>
   <div class="invester_deliver_cont">
   
   	  <!-- 个人资料显示区 -->
      <div class="invester_deliver_cont_l" id="per_info_letter">
      </div>
      <form>
      <div class="invester_deliver_cont_r">
          <div class="invester_fc_r_title" >自定义信件内容</div>
			<div id="modify_box">
				<div class="invester_fc_letter01">
	            	<input name="template" type="radio" value="0" checked="checked"/>
	            	<p><span id="tem_content"></span><a href="javascript:void(0)" id="tem_modify">修改</a></p>
	            	<div class="clear"></div>
	          	</div>
            </div>
          <!-- 模板信件内容 -->
         <!-- <div class="invester_fc_letter02">
            <textarea id='modify_text' name="" cols="" rows="">您好呀！我们是五粮液集团，主要做黄金酒项目。我觉得挺适合您的，小投资，大回报。希望我们能有一个合作的机会。 </textarea>
            <p><a href="#" class="gray">取消</a><a href="#" class="orange02">存为模板</a><span>（仅限150字)</span></p>
            <div class="clear"></div>
          </div>-->
          
          <!-- 精选信件内容 -->
          <div class="invester_fc_r_title" id="invester_fc_r_title_box">精选信件内容</div>
          <ul>
            <li><input name="template" type="radio" value="1" /><p><span>想你的钱生钱吗？让我们满足您的愿望吧！</span></p></li>
            <li><input name="template" type="radio" value="2" /><p><span>刚毕业找不到满意的工作，投资做生意吧！买车买房，并不是那不遥远！</span></p></li>
            <li><input name="template" type="radio" value="3" /><p><span>有权有钱是人们一生的追求，您还在等什么呢？</span></p></li>
            <li><input name="template" type="radio" value="4" /><p><span>有钱有时间的您，想坐等收钱环游世界吗？</span></p></li>
            <li><input name="template" type="radio" value="5" /><p><span>想让家人过的更舒适吗？让小投资大回报帮您实现愿望吧！</span></p></li>
          </ul>
          <p class="invester_fc_change"><a href="#"><!-- 换一批 --></a></p>
          <p class="invester_fc_btn02" id="submit_box"><a href="javascript:void(0)" class="btn_ensure"></a><a href="javascript:void(0)" class="btn_cancle"></a></p>
          <div class="clear"></div>
      </div>
      </form>
      <div class="clear"></div>
   </div>
</div>
<!-- 发信弹出层结束 -->
<!-- 各种弹出层结束 -->
<!--主体部分结束-->
<?php echo URL::webjs("My97DatePicker1/WdatePicker.js")?>