<div class="right">
                        <h2 class="user_right_title">
                            <span>我的项目</span>
                            <a href="/company/member/project/addproject" style="float:right;margin-right: 80px;font-weight: normal; text-decoration: none;font-size: 12px; color: #d20000;margin-top: 3px;">+添加新项目</a>
                            <div class="clear"></div>
                        </h2>
                        <? if($list) {foreach ($list as $key => $value) { ?>
                        <div class="my_business_new">
                            <div class="my_business_new_msg" style="display:<?if($showProId && $key == 0){echo 'block';}else{echo 'none';}?>">
                                <p>
                                    <?= $showProMsg;?>
                                </p>
                            </div>
                            <p class="my_business_new_project_state"><span><b><?=$value['project_brand_name']?></b>
                            	【状态：<font <?php if(arr::get($value, "project_status") == 1){
                            					echo 'style="color:#ff6000;"';
                            				}elseif (arr::get($value,"str_project_status_des") == 3 || arr::get($value, "project_poster_status") == 3 || arr::get($value['project_post'],"poster_status") == 3){
                            					echo 'style="color:#D20000;"';
                            				}elseif(arr::get($value, "project_status") == 3 || arr::get($value['project_post'],"poster_status") == 3){
                            					echo 'style="color:#D20000;"';
                            				}elseif ((arr::get($value,"project_status") == 2 && arr::get($value, "project_pass_status") == 2) || (arr::get($value, "project_post_status") == 2 && arr::get($value['project_post'], "poster_status") == 2)) {
                            					echo 'style="color:#74b428;"';
                            				}elseif((((arr::get($value,"project_status") == 8 || arr::get($value['project_post'], "poster_temp_status") == 1 ||  arr::get($value['project_post'], "poster_temp_status") == 6 || arr::get($value, "project_poster_status") == 1) && arr::get($value,"str_project_status_des") != 3) || (arr::get($value,"project_status") == 8) && arr::get($value, "project_pass_status") != 2)){
                            					echo 'style="color:#ff6000;"';
                            				}elseif (arr::get($value, "project_status") == 2 && arr::get($value, 'project_pass_status') !=2){
                            					echo 'style="color:#74b428;"';
                            				}?>>
                            			<?php
                            				 $res_des = "";
                            				 if(arr::get($value, "project_reason")){
                            				 	if(arr::get($value,"project_status") == 3){
                            				 		if(arr::get($value['project_post'],"poster_unpass_reason")){
                            				 			$res_des = "你所发布的项目未通过原因 :<font>".arr::get($value, "project_reason")."</font></br>"."抱歉，您上传的项目宣传海报未能通过审核。失败原因： <font><a href=\"".URL::website("company/member/project/addPoster?project_id=".$value['project_id']."&type=2")."\">".arr::get($value['project_post'],"poster_unpass_reason")."</a></font>";
                            				 		}else{
                            				 			$res_des = "你所发布的项目未通过原因 :<font>".arr::get($value, "project_reason")."</font>";
                            				 		}
                            				 	}else{
                            				 		if(arr::get($value['project_post'],"poster_unpass_reason")){
                            				 			$res_des = arr::get($value, "project_reason")."</br>"."抱歉，您上传的项目宣传海报未能通过审核。失败原因： <font><a href=\"".URL::website("company/member/project/addPoster?project_id=".$value['project_id']."&type=2")."\">".arr::get($value['project_post'],"poster_unpass_reason")."</a></font>";
                            				 		}else{
                            				 			if(arr::get($value, "project_reason")){
                            				 				$res_des = arr::get($value, "project_reason");
                            				 			}else{
                            				 				$res_des = "你所发布的项目未通过原因 :<font>".arr::get($value, "project_reason")."</font>";
                            				 			}
                            				 		}
                            				 	}
                            				 }else{
                            				 	$res_des = "抱歉，您上传的项目宣传海报未能通过审核。失败原因： <font><a href=\"".URL::website("company/member/project/addPoster?project_id=".$value['project_id']."&type=2")."\">".arr::get($value['project_post'],"poster_unpass_reason")."</a></font>";
                            				 }
                            				#显示审核提示信息
											if(arr::get($value, "project_status") == 1){
                            					echo "审核中";
                            				}elseif (arr::get($value,"str_project_status_des") == 3 || arr::get($value, "project_poster_status") == 3 || arr::get($value['project_post'],"poster_status") == 3){
                            					echo "修改信息审核不通过  <a href='javascript:void(0)' class='show_fail_result' data-msg='".$res_des."'>查看原因</a>";
                            				}elseif(arr::get($value, "project_status") == 3 || arr::get($value['project_post'],"poster_status") == 3){
                            					echo "审核不通过  <a href='javascript:void(0)' class='show_fail_result' data-msg='".$res_des."'>查看原因</a>";
                            				}elseif ((arr::get($value,"project_status") == 2 && arr::get($value, "project_pass_status") == 2) || (arr::get($value, "project_post_status") == 2 && arr::get($value['project_post'], "poster_status") == 2)) {
                            					echo "修改信息审核通过";
                            				}elseif((((arr::get($value,"project_status") == 8 || arr::get($value['project_post'], "poster_temp_status") == 1 ||  arr::get($value['project_post'], "poster_temp_status") == 6 || arr::get($value, "project_poster_status") == 1) && arr::get($value,"str_project_status_des") != 3) || (arr::get($value,"project_status") == 8) && arr::get($value, "project_pass_status") != 2)){
                            					echo "修改信息审核中";
                            				}elseif (arr::get($value, "project_status") == 2 && arr::get($value, 'project_pass_status') !=2){
                            					echo "审核通过";
                            				}
                            			?>
                            	</font>】</span>
                            	
                            	<?php if(arr::get($value,"panduan_status") == 2 && arr::get($value,"project_upgrade_status") !=2){?>
                            	<span style="float:right" id="changeHtml">
                            	<?php if(arr::get($value,"project_upgrade_status") == 1){?>
                            		<a href="javascript:void(0)" class="GetComInfo" id="<?=arr::get($value,"project_id")?>">招商外包项目升级中</a>
                            	<?php }else{?>
                            		<a href="javascript:void(0)" class="GetComInfo" id="<?=arr::get($value,"project_id")?>">升级成为招商外包项目</a>
                            	<?php }?>
                            		
                            	</span>
                            	<?php }?>
                            </p>
                            <dl class="my_business_new_content">
                                <dt><h3><?=$value['project_advert']?></h3></dt>
                                <dd class="my_business_new_left">
                                    <span><img width="120" height="95" src="<?if($value['project_source'] != 1) {$img =  project::conversionProjectImg($value['project_source'], 'logo', $value);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($value['project_logo']);}?>" alt="项目图片" /></span>
                                    <a href="<?php echo urlbuilder::project($value['project_id']); ?>" title="<?if($value['project_status'] == 2){echo '查看项目官网';}else{echo '预览项目官网';}?>" target="_blank" ><?if($value['project_status'] == 2){echo '查看项目官网';}else{echo '预览项目官网';}?></a>
                                    <a href="<?php echo URL::website('/company/member/project/showProjectDetail?project_id='.$value['project_id']); ?>"   title="查看项目详情">查看项目详情</a>
                                </dd>
                                <dd class="my_business_new_right">
                                    <p class="info"><font class="first">行业：</font><?=arr::get($value,'project_industry_id', '不限');?><font>投资金额：</font><?php if(arr::get($value, 'project_amount_type', 0)) {$monarr= common::moneyArr(); echo  arr::get($value, 'project_amount_type') == 0 ? '不限': $monarr[arr::get($value, 'project_amount_type')];?></a><?}else{echo "不限";}?><font>招商地区：</font><?= mb_substr($value['project_merchants_region'],0,15);?></p>
                                    <p class="plan">
                                        <span class="text">项目完整度：<font><?=arr::get($value['infoComplete'], 'percentage', 0)?></font><font style="font-family: '微软雅黑'; font-size:12px;">%</font></span>
                                        <span class="bar"><font class="<?if(arr::get($value['infoComplete'], 'percentage', 0) <= 33) {
                                            echo 'first';
                                        }elseif (arr::get($value['infoComplete'], 'percentage', 0) > 33 && arr::get($value['infoComplete'], 'percentage', 0) <= 57) {
                                              echo 'second';  }elseif(arr::get($value['infoComplete'], 'percentage', 0) > 57 && arr::get($value['infoComplete'], 'percentage', 0) <= 86) {
                                                echo 'third';  
                                              }else{
                                                   echo 'fourth'; 
                                              }?>"style="width:<?=arr::get($value['infoComplete'], 'percentage', 0)?>%;"></font></span>
                                        <font class="msg">温馨提示：项目信息完整度越高，越会获得投资者亲睐</font>
                                    </p>
                                    <ul class="operation">
                                        <li class="<?= (arr::get($value['infoComplete'], 'basicStatus', 0)) ? 'ok' : 'no';?>"><?if(arr::get($value['infoComplete'], 'basicStatus', 0)) {?>项目基本信息已完善，去<a href="<?php echo URL::website('/company/member/project/editBasicInfo?project_id='.$value['project_id']);?>">修改</a><?}else{?>项目基本信息不完善，去<a href="<?php echo URL::website('/company/member/project/editBasicInfo?project_id='.$value['project_id']);?>">完善</a><?}?></li>
                                        <?if(!arr::get($value,'project_advert') || !arr::get($value,'project_advert_big') || !arr::get($value,'project_advert_small')) {?>
                                        <li class="<?= (arr::get($value,'project_advert') ? 'ok' : 'no')?>"><?if(!arr::get($value,'project_advert')){?>缺少项目推广广告语，去<a href="/company/member/project/updateprojectspread?project_id=<?=$value['project_id']?>&type=1">添加</a><?}else{?>项目推广广告语已完善，去<a href="/company/member/project/updateprojectspread?project_id=<?=$value['project_id']?>&type=2">修改</a><?}?></li>
                                        <li class="<?= (arr::get($value,'project_advert_big') ? 'ok' : 'no')?>"><?if(!arr::get($value,'project_advert_big')) {?>缺少项目推广大图，去<a href="/company/member/project/updateprojectspread?project_id=<?=$value['project_id']?>&type=1">添加</a><?}else{?>项目推广大图已完善，去<a href="/company/member/project/updateprojectspread?project_id=<?=$value['project_id']?>&type=2">修改</a><?}?></li>
                                        <li class="<?= (arr::get($value,'project_advert_small') ? 'ok' : 'no')?>"><?if(!arr::get($value,'project_advert_small')) {?>缺少项目推广小图，去<a href="/company/member/project/updateprojectspread?project_id=<?=$value['project_id']?>&type=1">添加</a><?}else{?>项目推广小图已完善，去<a href="/company/member/project/updateprojectspread?project_id=<?=$value['project_id']?>&type=2">修改</a><?}?></li>
                                        <?}else{?>
                                        <li class="<?= (arr::get($value,'project_advert') ? 'ok' : 'no')?>"><?if(!arr::get($value,'project_advert')){?>缺少项目推广广告语，去<a href="/company/member/project/updateprojectspread?project_id=<?=$value['project_id']?>&type=1">添加</a><?}else{?>项目推广信息已完善，去<a href="/company/member/project/updateprojectspread?project_id=<?=$value['project_id']?>&type=2">修改</a><?}?></li>
                                        <li class="<?= (arr::get($value['infoComplete'], 'contactStatus', 0)) ? 'ok' : 'no';?>"><?if(arr::get($value['infoComplete'], 'contactStatus', 0)) {?>项目联系人信息已完善，去<a href="/company/member/project/updateProjectContact?project_id=<?=$value['project_id']?>&type=2">修改</a><?}else{?>项目联系人信息不完善，去<a href="/company/member/project/updateProjectContact?project_id=<?=$value['project_id']?>&type=1">完善</a><?}?></li>
                                        <li class="<?= (arr::get($value['infoComplete'], 'moreStatus', 0)) ? 'ok' : 'no';?>"><?if (!arr::get($value['infoComplete'], 'moreStatus', 0)){?><?= (!arr::get($value['infoComplete'], 'moreAllStatus', 0)) ? '缺少更多项目详情信息' : '更多项目详情信息不完善';?>，去<a href="<?php echo URL::website('/company/member/project/editMoreInfo?project_id='.$value['project_id']);?>&type=<?= (!arr::get($value['infoComplete'], 'moreAllStatus', 0)) ? 1 : 2;?>"><?= (!arr::get($value['infoComplete'], 'moreAllStatus', 0)) ? '添加' : '完善';?></a><?}else{?>更多项目详情信息已完善，去<a href="<?php echo URL::website('/company/member/project/editMoreInfo?project_id='.$value['project_id']);?>&type=3">修改</a><?}?></li>
                                        <?}?>
                                    </ul>
                                    <ul class="operation last">
                                        <?if(!arr::get($value,'project_advert') || !arr::get($value,'project_advert_big') || !arr::get($value,'project_advert_small')) {?>
                                        <li class="<?= (arr::get($value['infoComplete'], 'contactStatus', 0)) ? 'ok' : 'no';?>"><?if(arr::get($value['infoComplete'], 'contactStatus', 0)) {?>项目联系人信息已完善，去<a href="/company/member/project/updateProjectContact?project_id=<?=$value['project_id']?>&type=2">修改</a><?}else{?>项目联系人信息不完善，去<a href="/company/member/project/updateProjectContact?project_id=<?=$value['project_id']?>&type=1">完善</a><?}?></li>
                                        <li class="<?= (arr::get($value['infoComplete'], 'moreStatus', 0)) ? 'ok' : 'no';?>"><?if (!arr::get($value['infoComplete'], 'moreStatus', 0)){?><?= (!arr::get($value['infoComplete'], 'moreAllStatus', 0)) ? '缺少更多项目详情信息' : '更多项目详情信息不完善';?>，去<a href="<?php echo URL::website('/company/member/project/editMoreInfo?project_id='.$value['project_id']);?>&type=<?= (!arr::get($value['infoComplete'], 'moreAllStatus', 0)) ? 1 : 2;?>"><?= (!arr::get($value['infoComplete'], 'moreAllStatus', 0)) ? '添加' : '完善';?></a><?}else{?>更多项目详情信息已完善，去<a href="<?php echo URL::website('/company/member/project/editMoreInfo?project_id='.$value['project_id']);?>&type=3">修改</a><?}?></li>
                                        <?}?>
                                        <li class="<?= ((count(arr::get($value,'project_img', array())) >= 3) ? 'ok' : 'no')?>"><?if(count(arr::get($value,'project_img', array())) < 1 || !arr::get($value,'project_img', array())) {?>缺少产品图片，去<a href="/company/member/project/addproimg?project_id=<?=$value['project_id']?>&type=1">上传产品图</a><?}elseif(count(arr::get($value,'project_img', array())) >= 1 && count(arr::get($value,'project_img', array())) < 3) {?>添加更多产品图片，去<a href="/company/member/project/addproimg?project_id=<?=$value['project_id']?>&type=1">添加</a><?}else{?>产品图片信息已完善，去<a href="/company/member/project/addproimg?project_id=<?=$value['project_id']?>&type=2">管理</a><?}?></li>
                                        <li class="<?= ((count(arr::get($value,'project_auth', array())) >= 3) ? 'ok' : 'no')?>"><?if(count(arr::get($value,'project_auth', array())) < 1 || !arr::get($value,'project_auth', array())) {?>缺少项目资质图片，去<a href="/company/member/project/addprocertsimg?project_id=<?=$value['project_id']?>&type=1">上传项目资质</a><?}elseif(count(arr::get($value,'project_auth', array())) < 3 && count(arr::get($value,'project_auth', array())) >= 1) {?>添加更多项目资质图片，去<a href="/company/member/project/addprocertsimg?project_id=<?=$value['project_id']?>&type=1">添加</a><?}else{?>项目资质信息已完善，去<a href="/company/member/project/addprocertsimg?project_id=<?=$value['project_id']?>&type=2">管理</a><?}?></li>
                                        <li class="<?= (arr::get(arr::get($value,'project_post'), 'project_id', 0) ? 'ok' : 'no')?>"><?if(!arr::get(arr::get($value,'project_post'), 'project_id', 0)) { ?>缺少项目海报，去<a href="<?php echo URL::website('/company/member/project/addPoster?project_id='.$value['project_id']);?>&type=1">上传项目宣传海报</a><?}else{?>项目宣传海报信息已完善，去<a href="<?php echo URL::website('/company/member/project/addPoster?project_id='.$value['project_id']);?>&type=2">管理</a><?}?></li>
                                    </ul>
                                </dd>
                                <div class="clear"></div>
                            </dl>
                            <ul class="message">
                                <li>昨日项目官网访问量为<font><?=arr::get($value,'project_pv_yesterday', 0)?></font>；30天内总访问量为<font><?=arr::get($value,'project_pv_month', 0)?></font>,去 <a href="/company/member/project/showProjectPv?project_id=<?=$value['project_id']?>">查看详情</a></li>
                                <li>昨日收到名片数为<font><?=arr::get($value,'project_card_yesterday', 0)?></font>，今日为<font><?=arr::get($value,'project_card_today', 0)?></font>；30天内收到总名片数为<font><?=arr::get($value,'project_card_month', 0)?></font>,去 <a href="/company/member/project/getprojectcard?project_id=<?php echo $value['project_id'];?>&start_time=<?php echo date('Y-m-d',time()-30*24*60*60);?>&end_time=<?php echo date('Y-m-d',time());?>" target="_blank">查看详情</a></li>
                                <li><?if(!arr::get($value, 'project_invesrment', 0)){?>你还没有发布投资考察会，现在就去 <a href="/company/member/project/addinvest?project_id=<?=$value['project_id']?>">发布投资考察会</a><?}else{?>你已发布项目投资考察会<font><?=arr::get($value, 'project_invesrment', 0);?></font>场，去<a href="/company/member/project/myinvestment?project_id=<?=$value['project_id']?>">查看详情</a><?}?></li>
                                <?php if($value['project_status'] == 2){?>
                                	<?php if($value['haspnews'] > 0){?>
                                		<li>你已经发布了<?php echo $value['haspnews'];?>篇项目推广文章， <a href="/company/member/article/projecttougaolist?project_id=<?=$value['project_id']?>">去看看吧</a></li>
                                	<?php }else{?>
                                		<li>你还没有发布项目推广文章，现在就去 <a href="/company/member/article/projecttougao?project_id=<?=$value['project_id']?>">发项目新闻</a></li>
                                	<?php }?>
                                <?php }?>
                            </ul>
                        </div>
                 
                        <?}}?>
       </div>
                    <!--透明背景开始-->
                    <div id="getcards_opacity" ></div>
                    <!--透明背景结束-->

                    <!--删除项目开始-->
                    <div id="getcards_delete">
                        <a href="#" class="close">关闭</a>
                        <div class="btn">
                            <p>您的认领信息已经提交审核，我们的工作人员将会</p>
                            <p>在两个工作日内联系您，请耐心等待！</p>
                        </div>
                    </div>
                    <!--删除项目结束-->

                    <!--审核弹出层开始-->
                    <!--审核弹出层结束-->
                    <!--主体部分结束-->
                    <div class="clear"></div>

<!-- 申请招商通弹框 -->
<div id="user_apply_zst" class="user_apply_zst">
    <a href="#" class="close">关闭</a>
    <p class="msg">普通企业用户可免费发布<i>1</i>个项目，如您需要发更多项目，请申请招商通服务（详情请查阅<a href="#" target="blank">资费说明</a>，或咨询<font>400 1015 908</font>） 。</p>
    <div class="btn">
      <a href="#" class="button_red width2">申请招商通服务</a>
      <a href="#" class="button_yellow cancel">以后再说</a>
      <div class="clear"></div>
    </div>
</div>
<!-- 申请招商通弹框 END -->
<!-- 继续发布弹框 -->
<div id="user_release_continue" class="user_apply_zst">
    <a href="#" class="close">关闭</a>
    <p class="msg">对不起，截至目前，您已发布<i>8</i>个项目，免费发布项目个数以达到限制，如您需要发更多项目，请查阅<a href="#" target="blank">资费说明</a>，或咨询<br/>
<font>400 1015 908</font>。</p>
    <div class="btn">
      <a href="#" class="button_red">继续发布</a>
      <a href="#" class="button_yellow">去续费</a>
      <div class="clear"></div>
    </div>
</div>
<!-- 继续发布弹框 END -->
<!-- 温馨提示 -->
<div id="user_release_continue" class="user_apply_zst">
    <a href="#" class="close">关闭</a>
    <h4>温馨提示</h4>
    <p class="msg">免费发布项目个数超限，继续发布项目，将会从你的账户里直接扣除<font>500</font>元，是否确认此操作？</p>
    <div class="btn">
      <a href="#" class="button_red">确定</a>
      <a href="#" class="button_yellow cancel">取消</a>
      <div class="clear"></div>
    </div>
</div>
<!-- 温馨提示 END -->
<div class="show_reason_box">
    <a href="javascript:void(0)" class="close"></a>
    <p class="msg">你所发布的项目<font>含有敏感词汇</font>，故未通过审核，请修改信息后重新提交。</p>
    <button>确定</button>
</div>

<!-- 申请招商外包项目   -->
 <div id="updateProInfo">
  <ul class="messageBoxForm">
  <input type="hidden" value="" id="shenqing_id" />
  <input type="hidden" value="" id="project_new_id" />
    <li>
      <label><i></i>企业名称：</label><span id="com_name"></span>
    </li>
    <li>
      <label><i>*</i>联系人：</label><input type="text" id="contact_name" value=""/><SPAN id="isShowContactName" style="color: red;display:none">*请填写联系人</SPAN>
    </li>
    <li>
      <label><i>*</i>手机号码：</label><input type="text" value="" id="mobile" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")'/><SPAN id="isShowmobile" style="color: red;display:none">*输入的手机号码有误</SPAN>
    </li>
    <li>
      <label>联系邮箱：</label><input type="text" value="" id="email"/><SPAN id="isShowemail" style="color: red;display:none">*输入的邮箱有误</SPAN>
    </li>
    <li>
      <label>联系地址：</label><input type="text" value="" id="address"/>
    </li>
    <li class="btn">
      <button class="ok">确定</button><a target="_blank" href="<?=URL::website("/company/member/project/showGaoshi");?>">查看升级招商外包项目服务</a>
    </li>
  </ul>
  </div>

<!-- 申请招商外包项目  END -->
<script type="text/javascript">
$("#contact_name").live("focus", function(){
	$("#isShowContactName").css("display","none");
})
$("#mobile").live("focus", function(){
	$("#isShowmobile").css("display","none");
})
//隐藏联系人

$(document).ready(function(){
	 var box = {};
	    new messageBox(box, {
	      title : "请填写您升级成招商外包项目的信息",
	      contentTag : "#updateProInfo",
	      onok : updateProInfoFc
	    }), nowId=null;
	$(".GetComInfo").click(function(){
		var shenqing_id = $("#shenqing_id");
		var com_name = $("#com_name");
		var contact_name = $("#contact_name");
		var mobile = $("#mobile");
		var email = $("#email");
		var address = $("#address");
		var project_id = $(this).attr("id");
		var project_new_id = $("#project_new_id");
		var url = "/platform/ajaxcheck/GetProjectUpgradeInfo";
		$.post(url,{"project_id":project_id},function(data){
			project_new_id.val(project_id)
			shenqing_id.val(data.id);
			com_name.html(data.com_name);
			contact_name.val(data.contact_name);
			mobile.val(data.mobile);
			email.val(data.email);
			address.val(data.address);
			box.show();
			nowId = project_id;
		},"json")
	})
function updateProInfoFc(){
	var mobile = /^1[3-8]\d{9}$/g,
        email = /^([a-zA-Z0-9]+[_|-|.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|-|.]?)*[a-zA-Z0-9]+.[a-zA-Z]{2,3}$/gi,
        checkFlag = true;
	if($.trim($("#contact_name").val()) ==  ""){
		$("#isShowContactName").css("display","inline-block");
		checkFlag = false;
	}
	if(mobile.test($("#mobile").val()) == false || $("#mobile").val() == ""){
		$("#isShowmobile").css("display","inline-block");
        checkFlag = false;
	}
	if(email.test($.trim($("#email").val())) == false && $("#email").val() != ""){
		$("#isShowemail").css("display","inline-block");
        checkFlag = false;
	}
    if(checkFlag == false){
        return false;
    }

	var url = "/platform/ajaxcheck/DoProjectUpgradeInfo";
	$.post(url,{"project_new_id":$("#project_new_id").val(),"shenqing_id":$("#shenqing_id").val(),"contact_name":$.trim($("#contact_name").val()),"mobile":$("#mobile").val(),"address":$.trim($("#address").val()),"email":$.trim($("#email").val())},function(data){
		if(data.status > 0 ){

			box.hide(function(){
				window.MessageBox({
					title:"生意街提示您",
					content:['<div class="updateProOk">',
					         '<p class="msg"><img src="<?=URL::webstatic("/images/common/updateProInfo_03.png");?>"/>恭喜您</p>',
					         '<p>您申请招商外包服务的信息已成功提交，我们会在3个工作日由招商外包服务专家联系您。谢谢！</p>',
					         '<p  class="contact"><b>招商外包服务热线：</b><font>400 1015 908</font></p>',
					       '</div>'].join(""),
					callback:function(){},
                    target:"new"
				});
			});
            if(nowId != null){
                $("#"+nowId).html("招商外包项目升级中");
                nowId = null;
            }
		}
	},"json");
}
$(".tiaozhuan").live('click',function(){
	var project_id = $(this).attr("id");
	window.location.href = "/company/member/project/addPoster?project_id="+project_id+"&type=2";
})
})
$(".user_apply_zst a.close").click(function(event) {
    /* Act on the event */
    $(this).parent().slideUp(500, function(){
        $("#getcards_opacity").hide();
    });
});
$(".user_apply_zst .btn a.cancel").click(function(event) {
    /* Act on the event */
    $(this).parent().parent().slideUp(500, function(){
        $("#getcards_opacity").hide();
    });
});

// 审核未通过的原因JS
$(".show_fail_result").click(function(){
    // $(".show_reason_box p.msg").html($(this).attr("data-msg"));
    $("body")[0].show({
        title:"查看项目未通过原因",
        content:"<p>"+$(this).attr("data-msg")+"</p>",
        btn:"ok"
    });
    // $("#getcards_opacity").show();
    // $(".show_reason_box").slideDown(500, function(){});
});
$(".show_reason_box .close").click(function(){
    $(".show_reason_box").slideUp(500, function(){
        $("#getcards_opacity").hide();
    });
});
$(".show_reason_box button").click(function(){
    $(".show_reason_box").slideUp(500, function(){
        $("#getcards_opacity").hide();
    });
});

// 审核未通过的原因JS
</script>
        