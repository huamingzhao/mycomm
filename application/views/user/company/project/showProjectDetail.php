<div class="right">
	<h2 class="user_right_title">
		<span>我的项目</span>
		<div class="clear"></div>
	 </h2>
	 <div class="my_business_new">
	 	<div class="project_detial">
	 		<p class="state"><?=arr::get($result, 'project_brand_name')?>【<font>状态：</font><?php if(isset($result['project_status_temp']) && $result['project_status_temp'] == 3){echo "<i style='color:#d20000;'>项目修改   审核未通过</i>";}else{ if(isset($result['project_status']) && $result['project_status'] == 2){?><i style="color:#74b428;">审核通过</i><?php }elseif(isset($result['project_status']) && $result['project_status'] == 3){?><i style="color:#d20000;">审核未通过</i><?php }else{?><i style="color:#ff6000;">审核中</i><?php }}?>】</p>
	 		<ul class="info">
	 			<li class="title"><b>项目基本信息</b><a href="<?php echo URL::website('/company/member/project/editBasicInfo?project_id='.$result['project_id']);?>">修改</a></li>
	 			<li class="label"><font>*</font>品牌名称：</li><li class="content"><?=arr::get($result, 'project_brand_name')?></li>
	 			<li class="label"><font>*</font>品牌logo图：</li><li class="content"><img width="120" height="95" src="<?if($result['project_source'] != 1) {$img =  project::conversionProjectImg($result['project_source'], 'logo', $result);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($result['project_logo']);}?>" alt="logo" ></li>
	 			<li class="label"><font>*</font>品牌发源地：</li><li class="content"><?=arr::get($result, 'project_brand_birthplace' ,'暂无')?></li>
	 			<li class="label">品牌成立年份：</li><li class="content"><?php if(arr::get($result, 'projcet_founding_time') != 0 && arr::get($result, 'projcet_founding_time') != ''){ ?><?=arr::get($result, 'projcet_founding_time')?><?php }else{echo '暂无';}?></li>
	 			<li class="label"><font>*</font>所属行业：</li><li class="content"><font><?=$pro_industry?></font></li>
	 			<li class="label"><font>*</font>投资金额：</li><li class="content"><?php $monarr= common::moneyArr(); echo arr::get($result, 'project_amount_type') == 0 ? '无': $monarr[arr::get($result, 'project_amount_type')];?></li>
	 			<li class="label"><font>*</font>招商地区：</li><li class="content">
	 				<?php if(count($pro_area) && is_array($pro_area)){
                            $area='';
                            foreach ($pro_area as $v){
                                $area = $area.$v.'<br/>';
                            }
                           $area= substr($area,0,-5);
                            if(mb_strlen($area)>10){
                                echo $area;
                            }
                            else{
                                echo $area;
                            }

                   }else{echo $pro_area;}  ?>
	 			</li>
	 			<li class="label"><font>*</font>招商形式：</li><li class="content"><font>
	 			<?php $lst = common::businessForm();
                if(count($projectcomodel)){
                    $comodel_text='';
                    foreach ($projectcomodel as $v){
                        $comodel_text=$comodel_text.$lst[$v].',';
                    }
                    $xingshi= substr($comodel_text,0,-1);

                        echo $xingshi;

                }else{echo '暂无';}?>
	 			</font></li>
	 			<li class="label"><font>*</font>适合人群：</li><li class="content"><font><?php if($group_text != ""){echo $group_text;}else{echo '暂无';}?></font></li>
	 			<li class="clear"></li>
	 		</ul>
	 		<ul class="info">
	 			<li class="title"><b>项目推广信息</b><a href="<?php echo URL::website('/company/member/project/updateprojectspread?project_id='.$result['project_id']).'&type=2';?>">修改</a></li>
	 			<li class="label">项目推广广告语：</li><li class="content"><?php if(isset($result['project_advert']) && $result['project_advert'] == ''){echo '暂无';}else{echo arr::get($result, 'project_advert','暂无');}?></li>
	 			
	 			<li class="label">项目广告大图：</li><li class="content"><?php if(isset($bigImg) && $bigImg != ""){?><a class="project_detial_product_img zhaos_big_img button" href="<?=URL::imgurl(str_replace('/s_','/b_',$bigImg));?>" target="blank">预  览</a><?php }else{echo '暂无';}?></li>
	 			<li class="label">项目广告小图：</li><li class="content"><?php if(isset($smallImg) && $smallImg != ""){?><img width="120" height="95" src="<?php echo $smallImg;?>" ><?php }else{echo '暂无';}?></li>
	 			<li class="label"><font>*</font>项目详情介绍：</li><li class="content"><?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($result, 'project_summary'), 0)));echo mb_strimwidth(strip_tags($string), 0, 125, "......");?><a href="<?php echo urlbuilder::project($result['project_id']);?>" target="_blank" class="more" >  查看更多请去项目官网</a></li>
	 			<li class="label">项目标签：</li><li class="content"><font><?php if(!empty($tag)){echo implode(' ', $tag);}else{echo '暂无';}?></font></li>
	 			<li class="clear"></li>
	 		</ul>
	 		<ul class="info">
	 			<li class="title"><b>联系人信息</b><a href="<?php echo URL::website('/company/member/project/updateProjectContact?project_id='.$result['project_id']).'type=1';?>">修改</a></li>
	 			<li class="label"><font>*</font>姓名：</li><li class="content"><?=arr::get($result, 'project_contact_people','暂无')?></li>
	 			<li class="label">职位：</li><li class="content"><?php if($result['project_position'] != ""){?><?=arr::get($result, 'project_position','暂无')?><?php }else{echo '暂无';}?></li>
	 			<li class="label"><font>*</font>手机号码：</li><li class="content"><?=arr::get($result, 'project_handset' ,'暂无')?></li>
	 			<li class="label">公司座机号码：</li><li class="content"><?php if(isset($call[0]) && $call[0] != ""){?><?=arr::get($call, 0)?><?if(arr::get($call, 1, 0)) { echo "-".arr::get($call, 1, 0);}?><?php }else{echo '暂无';}?></li>
	 			<li class="clear"></li>
	 		</ul>
	 		<ul class="info">
	 			<li class="title"><b>更多项目详情信息</b><?php if(($result['project_principal_products'] != '') || ($result['project_joining_fee'] != 0) || ($result['project_security_deposit'] != 0) || ($result['rate_return'] != 0) || !empty($get_onnection) || ($result['product_features'] != '') || ($result['project_join_conditions'] != '')){?><a href="<?php echo URL::website('/company/member/project/editMoreInfo?project_id='.$result['project_id']);?>">修改</a><?php }?></li>
	 			<?php if(($result['project_principal_products'] != '') || ($result['project_joining_fee'] != 0) || ($result['project_security_deposit'] != 0) || ($result['rate_return'] != 0) || !empty($get_onnection) || ($result['product_features'] != '') || ($result['project_join_conditions'] != '')){?>
	 			<li class="label">主营产品：</li><li class="content"><?php if($result['project_principal_products'] != ""){?><?=arr::get($result, 'project_principal_products')?><?php }else{echo '暂无';}?></li>
	 			<li class="label">加盟费：</li><li class="content"><?php if(isset($result['project_joining_fee']) && $result['project_joining_fee'] != 0){?><?=arr::get($result, 'project_joining_fee')?>万元<?php }else{echo '暂无';}?></li>
	 			<li class="label">保证金：</li><li class="content"><?php if(isset($result['project_security_deposit']) && $result['project_security_deposit'] != 0){?><?=arr::get($result, 'project_security_deposit')?>万<?php }else{echo '暂无';}?></li>
	 			<li class="label">年投资回报率：</li><li class="content"><?php if($result['rate_return'] != 0){?><?php $list = guide::attr8(); foreach ($list as $k=>$v){ ?><?php if(arr::get($result, 'rate_return') == $k){ echo $v;}?><?php }?><?php }else{echo '暂无';}?></li>
	 			<li class="label">需要的人脉关系：</li><li class="content"><font><?php if(empty($get_onnection)){echo '暂无';}else{ ?><?$list = guide::attr5();foreach ($list as $k=>$v){ ?><?php if(isset($get_onnection[$k])){echo $v.'&nbsp;';}?><?}?><?php }?></font></li>
	 			<li class="label">产品特点：</li><li class="content"><?php if($result['product_features'] != ''){?><?=mb_strimwidth(arr::get($result, 'product_features'), 0, 125, "......");?><a href="<?php echo urlbuilder::project($result['project_id']);?>" target="_blank" class="more" >  查看更多请去项目官网</a><?php }else{echo '暂无';}?></li>
	 			<li class="label">加盟详情：</li><li class="content"><?php if($result['project_join_conditions'] != ''){?><?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($result, 'project_join_conditions'), 0)));echo mb_strimwidth(strip_tags($string), 0, 125, "......");?><a href="<?php echo urlbuilder::project($result['project_id']);?>" target="_blank" class="more" >  查看更多请去项目官网</a><?php }else{echo '暂无';}?></li>
	 			<li class="clear"></li>
	 			<?php }else{?>
	 			<li class="poster poster_no">
                	<p>
                    	<font>缺少项目详情信息。</font>
                	</p>
                    <a class="button" href="<?php echo URL::website('/company/member/project/editMoreInfo?project_id='.$result['project_id']);?>">添加</a>
                </li>                
	 			<?php }?>
	 			<div class="clear"></div>
	 		</ul>
	 		<ul class="info">
	 			<li class="title"><b>产品图片（<?php if(isset($productImgCount)){ echo $productImgCount;}else{echo 0;}?>张）</b><?php if(isset($productImgCount) && $productImgCount > 0){?><a href="<?php echo URL::website('/company/member/project/addproimg?project_id='.$result['project_id']).'type=1';?>">管理</a><?php }?></li>
	 			<li class="img">
	 				<?php 
	 				if(isset($productImg) && !empty($productImg)){
						//$int_productImg_num = count($productImg);
	 					$productImg = array_slice($productImg, 0,3);	 					
	 					foreach($productImg as $val){
	 				?>
	 				<span>
	 					<i><img width="183" height="145" src="<?php echo URL::imgurl($val['project_img']);?>"/></i>
	 					<a class="project_detial_product_img zhaos_big_img" href="<?=URL::imgurl(str_replace('/s_','/b_',$val['project_img']));?>" target="blank">查看大图</a>
	 				</span>
	 				<?php }  }else{?>
	 				<li class="poster poster_no">
	                	<p>
	                    	<font>缺少产品图片。</font>
	                	</p>
                    	<a class="button" href="<?php echo URL::website('/company/member/project/addproimg?project_id='.$result['project_id']).'type=1';?>">添加</a>
                	</li>                	
	 				<?php }?>
	 			</li>
	 			<?php if(isset($productImg) && !empty($productImg)){?>
		 			<?php if($productImgCount > intval(3)){?>
		 				<li><span style="margin-left:48px;">查看更多请去  <a target="_blank" style="color: blue;" href="<?php echo urlbuilder::projectImages($result['project_id']);?>">项目官网></a></span></li>
		 			<?php } ?>
				<?php } ?>
				<div class="clear"></div>	 		
		</ul>
	 		<ul class="info">
	 			<li class="title"><b>项目资质图片（<?php if(isset($zizhiImgCount)){echo $zizhiImgCount;}else{echo 0;}?>张）</b><?php if(isset($zizhiImgCount) && $zizhiImgCount > 0){?><a href="<?php echo URL::website('/company/member/project/addprocertsimg?project_id='.$result['project_id']).'type=1';?>">管理</a><?php }?></li>
	 			<li class="img">
	 				<?php 
	 				if(isset($zizhiImg) && !empty($zizhiImg)){
						//$int_zizhiImg_num = count($zizhiImg);
	 					$zizhiImg = array_slice($zizhiImg, 0,3);	 					
	 					foreach($zizhiImg as $val){
	 				?>
	 				<span>
	 					<h4><?=$val['project_imgname']?></h4>
	 					<i><img width="183" height="145" src="<?php echo URL::imgurl($val['project_img']);?>"/></i>
	 					<a class="project_detial_product_img zhaos_big_img" href="<?=URL::imgurl(str_replace('/s_','/b_',$val['project_img']));?>" target="blank">查看大图</a>
	 				</span>
	 				<?php }}else{?>
	 				<li class="poster poster_no">
	                	<p>
	                    	<font>缺少项目资质图片。</font>
	                	</p>
                    	<a class="button" href="<?php echo URL::website('/company/member/project/addprocertsimg?project_id='.$result['project_id']).'&type=1';?>">添加</a>
                	</li>                	
	 				<?php }?>
	 			</li>
	 			<?php if(isset($zizhiImg) && !empty($zizhiImg)){?>
		 			<?php if($zizhiImgCount > intval(3)){?>
		 				<li><span style="margin-left:48px;">查看更多请去  <a target="_blank" style="color: blue;" href="<?php echo urlbuilder::projectCerts($result['project_id']);?>">项目官网></a></span></li>
		 			<?php } ?>
				<?php } ?>
				<div class="clear"></div>	
	 		</ul>
	 		<ul class="info">
	 			<li class="title"><b>项目宣传海报</b><?php if($poster != ""){?><a href="<?php echo URL::website('/company/member/project/addPoster?project_id='.$result['project_id']);?>">管理</a><?php }?></li>
	 			<?php if($poster['project_id'] == ""){?>
	 			<li class="poster poster_no">
                	<p>
                    	<font>缺少项目宣传海报。</font>
                	</p>
                    <a class="button" href="<?php echo URL::website('/company/member/project/addPoster?project_id='.$result['project_id']);?>">上传海报</a>
                </li>
                <?php }elseif(isset($poster['poster_status']) && $poster['poster_status'] == 1){?>
                <li class="poster poster_sh">
                	<p>
                    	<font>您的项目宣传海报已经上传，正在审核中</font>
                        	我们将在3个工作日内完成审核，审核通过后，投资者就可在您的项目官网查看到。
                    </p>
                    <a class="project_detial_product_img zhaos_big_img button" href="<?=URL::imgurl(str_replace('/s_','/b_',$posterImg));?>" target="blank">预  览</a>
                </li>
                <?php }elseif(isset($poster['poster_status']) && $poster['poster_status'] == 2){?>
                <li class="poster poster_ok">
                	<p>
                    	<font>恭喜您，您上传的项目宣传海报审核通过。</font>
                    </p>
                    <a class="project_detial_product_img zhaos_big_img button" href="<?=URL::imgurl(str_replace('/s_','/b_',$posterImg));?>" target="blank">预  览</a>
                </li>
                <?php }elseif(isset($poster['poster_status']) && $poster['poster_status'] == 3){?>
                <li class="poster poster_fail">
                	<p>
                    	<font>抱歉，您上传的项目宣传海报未能通过审核。</font>
 							失败原因：<?php if(isset($poster['poster_status'])){ echo $poster['poster_status'];}?>
                    </p>
                    <a class="button" href="<?php echo URL::website('/company/member/project/addPoster?project_id='.$result['project_id']);?>">重新上传</a>
                </li>
                <?php }?>
	 		</ul>
	 	</div>
	 </div>
</div>
<!--透明背景开始-->
<div id="getcards_opacity" ></div>
<!--透明背景结束-->
<?php echo URL::webjs("zhaos_box.js")?>
<script type="text/javascript">
	init_big_img();
</script>