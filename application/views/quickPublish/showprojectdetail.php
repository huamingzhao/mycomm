<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("common.css")?>
<?php echo URL::webcss("quickrelease.css")?>

	
	<div class="quickrelease-content">
		<div class="releaseform">
			<div class="basicinformation">
				<h3>基本信息<i></i><a class="afterabtn" href="<?php echo URL::website('/quick/project/showQuickBasic?project_id='.$forms['project_id']);?>">修改</a></h3>
				<ul>
					<li>
						<span class="spantitle"><var>*</var> 品牌名称：</span>
						<?php echo $forms['project_brand_name'];?>
					</li>
					<li>
						<span class="spantitle">品牌发源地：</span>
						<?php echo $forms['project_brand_birthplace_name'] ? $forms['project_brand_birthplace_name'] : '暂无';?>
					</li>
					<li>
						<span class="spantitle">品牌历史：</span>
						<?php $monarr= common::projectHistory(); echo arr::get($forms, 'project_history') == 0 ? '暂无': $monarr[arr::get($forms, 'project_history')];?>
					</li>
					<li>
						<span class="spantitle"><var>*</var> 所在区域：</span>
						<?php echo $forms['merchants_area_name'] ? $forms['merchants_area_name'] : '暂无';?>
					</li>
					<li>
						<span class="spantitle"><var>*</var> 行业分类：</span>
						<?php echo $forms['industry_name'] ? $forms['industry_name'] : '暂无';?>
					</li>
				</ul>
				<h3>招商信息<i></i><a class="afterabtn" href="<?php echo URL::website('/quick/project/showQuickJiaMeng?project_id='.$forms['project_id']);?>">修改</a></h3>
				<ul>
					<li>
						<span class="spantitle"><var>*</var> 投资金额：</span>
						<?php $monarr= common::moneyArr(); echo arr::get($forms, 'project_amount_type') == 0 ? '暂无': $monarr[arr::get($forms, 'project_amount_type')];?>
					</li>
					<li>
						<span class="spantitle">加盟费：</span>
						<?php echo $forms['project_joining_fee'] ? $forms['project_joining_fee'].'&nbsp;&nbsp;&nbsp;元' : '暂无';?>
					</li>
					<li>
						<span class="spantitle">保证金：</span>
						<?php echo $forms['project_security_deposit'] ? $forms['project_security_deposit'].'&nbsp;&nbsp;&nbsp;元' : '暂无';?>
					</li>
					<li>
						<span class="spantitle"><var>*</var> 支持加盟城市：</span>
						<?php if(isset($forms['area']) && $forms['area']){                   			
                            $area='';
                            foreach ($forms['area'] as $v){
                                $area .= $v['pro_name'];
                                $area = $area.',';
                            }
                            $area = substr($area, 0,strlen($area)-1);
                            echo $area;                           
                   		}
                        ?>
					</li>
					<li>
						<span class="spantitle"><var>*</var>招商形式：</span>
						<?php if(isset($forms['project_model']) && $forms['project_model']){
                   		$lst = common::puickProjectModel();
		                if(count($forms['project_model'])){
		                    $comodel_text='';
		                    foreach ($forms['project_model'] as $v){
		                        $comodel_text=$comodel_text.$lst[$v].',';
		                    }
		                    $xingshi= substr($comodel_text,0,-1);
		
		                        echo $xingshi;
		
		                }else{echo '暂无';}}else{echo '暂无';}?>
					</li>
				</ul>
				<h3>推广信息<i></i><a class="afterabtn" href="<?php echo URL::website('/quick/project/showQuickTuiGuang?project_id='.$forms['project_id']);?>">修改</a></h3>
				<ul>
					<li>
						<span class="spantitle"><var>*</var>标题：</span>
						<?php echo $forms['project_title'] ? $forms['project_title'] : '暂无';?>						
					</li>
					<li>
						<span class="spantitle">一句话介绍：</span>
						<?php echo $forms['project_introduction'] ? $forms['project_introduction'] : '暂无';?>
					</li>
					<li class="clearfix">
						<span class="spantitle fl"><var>*</var>信息详情：</span>
						<span class="fl" style="width:748px;padding-top: 8px;">
						<?php echo (isset($forms['project_summary']) && $forms['project_summary']) ? zixun::setContentReplace($forms['project_summary']) : '暂无';?>
						</span>
					</li>
					<li class="clearfix">
						<span class="spantitle fl">展示图：</span>
						<?php if(isset($forms['project_zhanshi'][0]['project_zhanshi_pic']) && $forms['project_zhanshi'][0]['project_zhanshi_pic']){?>
						<div class="imgbox clearfix fl" >							
    						<?php foreach($forms['project_zhanshi'] as $val){?>	
							<span class="clearfix <?php if(!(isset($val['project_zhanshi_pic']) && $forms['project_logo'] == $val['project_zhanshi_pic'])){?>on<?php }?>">
								<img src="<?php echo URL::webstatic('/images/quickrelease/logoimg.png');?>" class="logoicon">
								<i class="picbox">
									<img src="<?=(isset($val['project_zhanshi_pic']) && $val['project_zhanshi_pic']) ? URL::imgurl($val['project_zhanshi_pic']) : '';?>">
								</i>								
								<a class="bigimg zhaos_big_img" href="<?=URL::imgurl(str_replace('/s_','/b_',$val['project_zhanshi_pic']));?>" target="blank" data-img="">查看大图</a>								
							</span>
							<?php }?>			        		
						</div>
						<?php }else{?>
						<span style="float:left; height:30px; line-height:30px;">暂无</span>
						<?php }?>
						<div class="clear"></div>
						<?php if(isset($forms['project_zhanshi']) && count($forms['project_zhanshi']) > 8){?>
						<p class="clickp">展开更多图片&gt;&gt;</p>
						<?php }?>
						<script type="text/javascript">
							$(".clickp").toggle(function() {
								$(".imgbox")[0].style.maxHeight="none"
								$(this).html("收回");
								}, function() {
								$(".imgbox")[0].style.maxHeight="346px"
								$(this).html("查看更多图片>>");
								}); 
						</script>
					</li>
				</ul>
				<h3>联系人信息<i></i><a class="afterabtn" href="<?php echo URL::website('/quick/project/showQuickLianXiRen?project_id='.$forms['project_id']);?>">修改</a></h3>
				<ul>
					<li>
						<span class="spantitle"><var>*</var> 联系人：</span>
						<?php echo $forms['project_contact_people'] ? $forms['project_contact_people'] : '暂无';?>
					</li>
					<li>
						<span class="spantitle"><var>*</var> 手机号码：</span>
						<?php echo $forms['mobile'] ? $forms['mobile'] : '暂无';?>
					</li>
					<li>
						<span class="spantitle">座机号码：</span>
						<?php echo $forms['project_phone'] ? $forms['project_phone'] : '暂无';?>
					</li>					
				</ul>
			</div>

			
		</div>
	</div>

