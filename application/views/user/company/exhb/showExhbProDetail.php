<?php echo URL::webjs("zhaos_box.js")?>
<?php echo URL::webcss("cz.css")?>
<!--右侧开始-->
<div class="opacityBg" id="opacityBg"></div>
<div class="right">
	<h2 class="user_right_title">
        <span>参展项目管理</span>
        <div class="clear"></div>
    </h2>
    <div class="my_business_new">
    	<div class="project_detial project_release">
    		<form id="publishForm" method="post" action="<?php echo URL::website('/company/member/exhb/editProBasic');?>" enctype="multipart/form-data">
    			<ul class="info">
    				<li class="title"> <b>项目基本信息</b><a href="<?php echo URL::website('/company/member/exhb/showExhbBasic?exhibition_id='.$exhb_id.'&project_id='.$forms['project_id'])?>">修改</a>
    				</li>
    				<li class="label"> <font>*</font>选择参展项目：</li>
    				<li class="content">
                        <?php echo isset($forms['project_name']) ? $forms['project_name'] : '';?>
                    </li>
                    <li class="label"> <font>*</font>参展项目名称：</li>
                    <li class="content">
                    	<?php echo isset($forms['exhibition_name']) ? $forms['exhibition_name'] : '';?>
                    </li>
                    <li class="label"><font>*</font>项目宣传图：</li>
                    <li class="content">
                        <div class="imgwrap">
                            <p><img src="<?=isset($forms['project_logo']) ? URL::imgurl($forms['project_logo']) : '';?>"></p>
                            <p>
                            <input type="hidden" value="<?=isset($forms['project_logo']) ? URL::imgurl($forms['project_logo']) : '';?>">
                            <a class="project_detial_product_img zhaos_big_img" href="<?=isset($forms['project_logo']) ? URL::imgurl(str_replace('/s_','/b_',$forms['project_logo'])) : '';?>" target="blank" data-img="javascript:;">查看大图</a></p>
                        </div>
                    </li>
                    <li class="label"><font>*</font>所属行业：</li>
                    <li class="content" style="z-index:9;">
						<?php if(isset($forms['pro_industry']['industry_name'])){echo $forms['pro_industry']['industry_name'];}else{echo "暂无";}?>
                	</li>
                	<li class="label"><font>*</font>所需投资金额：</li>
                	<li class="content" id="need_money">
                    	<?php $monarr= common::moneyArr(); echo arr::get($forms, 'project_amount_type') == 0 ? '无': $monarr[arr::get($forms, 'project_amount_type')];?>
                	</li>
                	<li class="label"><font>*</font>招商形式：</li>
                	<li class="content" id="service_type">
                   		<?php if(isset($forms['project_model']) && $forms['project_model']){
                   		$lst = common::businessForm();
		                if(count($forms['project_model'])){
		                    $comodel_text='';
		                    foreach ($forms['project_model'] as $v){
		                        $comodel_text=$comodel_text.$lst[$v].',';
		                    }
		                    $xingshi= substr($comodel_text,0,-1);
		
		                        echo $xingshi;
		
		                }else{echo '暂无';}}?>
               		</li>
               		<li class="label"><font>*</font>招商地区：</li>
               		<li class="content">
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
                	<li class="clear"></li>
    			</ul>
    			<ul class="info">
    				<li class="title"> <b>项目推广信息</b><a href="<?php echo URL::website('/company/member/exhb/showExhbTuiGuang?exhibition_id='.$exhb_id.'&project_id='.$forms['project_id'])?>">修改</a>
    				</li>
    				<li class="label">优惠宣传语：</li>
    				<li class="content">
                    	<?php echo (isset($forms['advertisement']) && $forms['advertisement']) ? $forms['advertisement'] : '暂无';?>
                	</li>
                	<li class="label"><font>*</font>优惠金额券：</li>
                	<li class="content">
	                    <?php echo isset($forms['project_coupon']) ? $forms['project_coupon'].'元' : '暂无';?>
	                </li>
	                <li class="label"><font>*</font>优惠券数量：</li>
	                <li class="content">
	                    <?php echo isset($forms['coupon_num']) ? $forms['coupon_num'].'张' : '暂无';?>
	                </li>
	                <li class="label">有效时间：</li>
	                <li class="content">
	                    <?php echo (isset($forms['coupon_deadline']) && $forms['coupon_deadline']) ? date('Y年m月d日',$forms['coupon_deadline']) : '暂无';?>
	                </li>
	                <li class="label">一句话项目介绍：</li>
	                <li class="content">
	                    <?php echo (isset($forms['project_introduction']) && $forms['project_introduction']) ? $forms['project_introduction'] : '暂无';?>
	                </li>
	                <li class="clear"></li>
    			</ul>
    			<ul class="info">
    				<li class="title"> <b>项目详情信息</b><a href="<?php echo URL::website('/company/member/exhb/showExhbMore?exhibition_id='.$exhb_id.'&project_id='.$forms['project_id'])?>">修改</a>
    				</li>
    				<li class="label"><font>*</font>产品展示：</li>
    				<li class="content cpzs">
    					
    					<div class="appendspan">	
    						<?php if(isset($forms['project_zhanshi']) && $forms['project_zhanshi']){?>
    						<?php foreach($forms['project_zhanshi'] as $v){?>			        			        	
			        		<span class="imgboxlist clearfix"><i><img width="120" height="95" src="<?=(isset($v['project_zhanshi_pic']) && $v['project_zhanshi_pic']) ? URL::imgurl($v['project_zhanshi_pic']) : '';?>"></i><a target="blank" href="<?=URL::imgurl(str_replace('/s_','/b_',$v['project_zhanshi_pic']));?>" class="project_detial_product_img zhaos_big_img">查看大图</a><a href="javascript:;" class="project_detial_product_img del delete"></a></span>			        		
			        		<?php }?>
			        		<?php }?>
			        	</div>                        
    					<p id="beforep" style="clear:both;margin-top:10px; <?php if(!(isset($forms['project_zhanshi']) && count($forms['project_zhanshi']) > 4)){?>display:none;<?php }?>"><a href="javascript:;">查看更多产品展示图</a></p>
    				</li>
    				
    				<li class="label">项目视频：</li>
    				<li class="content">
    					<?php if(!(isset($forms['project_temp_video']) && $forms['project_temp_video'])){?>
    					暂无
    					<?php }elseif(isset($forms['project_temp_video']) && $forms['project_temp_video'] && isset($forms['project_video']) && $forms['project_video']){?>
    					详情请查阅参展<a href="<?=urlbuilder::exhbProject($forms['project_id']);?>">项目官网</a>
    					<?php }else{?>
    					审核通过后，即可正常播放
    					<?php }?>
    				</li>
    				<li class="label">项目优势图：</li>
    				<li class="content">   
    					<?php if(isset($forms['project_advantage_img']) && $forms['project_advantage_img'] != ''){?> 					
                        <div class="imgwrap">                        	
                            <p><img src="<?=isset($forms['project_advantage_img']) ? URL::imgurl($forms['project_advantage_img']) : '';?>"></p>
                            <p>
                            <input type="hidden" value="<?=isset($forms['project_advantage_img']) ? URL::imgurl($forms['project_advantage_img']) : '';?>">
                            <a class="project_detial_product_img zhaos_big_img" href="<?=isset($forms['project_advantage_img']) ? URL::imgurl(str_replace('/s_','/b_',$forms['project_advantage_img'])) : '';?>" target="blank" data-img="javascript:;">查看大图</a></p>                      		
                        </div>
                        <?php }else{?>
                      	暂无
                      	<?php }?> 
                    </li>
    				<li class="label"><font>*</font>项目优势：</li>
    				<li class="content">
    					<?php echo (isset($forms['project_advantage']) && $forms['project_advantage']) ? zixun::setContentReplace($forms['project_advantage']) : '暂无';?>
    				</li>
    				<li class="label">运营操作图：</li>
    				<li class="content">
    					<?php if(isset($forms['project_running_img']) && $forms['project_running_img'] != ''){?>
                        <div class="imgwrap">
                            <p><img src="<?=isset($forms['project_running_img']) ? URL::imgurl($forms['project_running_img']) : '';?>"></p>
                            <p>
                            <input type="hidden" value="<?=isset($forms['project_running_img']) ? URL::imgurl($forms['project_running_img']) : '';?>">
                            <a class="project_detial_product_img zhaos_big_img" href="<?=isset($forms['project_running_img']) ? URL::imgurl(str_replace('/s_','/b_',$forms['project_running_img'])) : '';?>" target="blank" data-img="javascript:;">查看大图</a></p>
                        </div>
                        <?php }else{?>
                      	暂无
                      	<?php }?> 
                    </li>
                    <li class="label"><font>*</font>运营操作：</li>
                    <li class="content">
    					<?php echo (isset($forms['project_running']) && $forms['project_running']) ? zixun::setContentReplace($forms['project_running']) : '暂无';?>
    				</li>
    				<li class="clear"></li>
    			</ul>
    			<ul class="info">
    				<li class="title"> <b>项目其他信息</b><a href="<?php echo URL::website('/company/member/exhb/showExhbOther?exhibition_id='.$exhb_id.'&project_id='.$forms['project_id'])?>">修改</a>
    				</li>
    				<li class="label">公司展示图：</li>
    				<li class="content">
    					<?php if(isset($forms['company_strength_img']) && $forms['company_strength_img'] != ''){?>
    					<div class="imgwrap">
                            <p><img src="<?=isset($forms['company_strength_img']) ? URL::imgurl($forms['company_strength_img']) : '';?>"></p>
                            <p>
                            <input type="hidden" value="<?=isset($forms['company_strength_img']) ? URL::imgurl($forms['company_strength_img']) : '';?>">
                            <a class="project_detial_product_img zhaos_big_img" href="<?=isset($forms['company_strength_img']) ? URL::imgurl(str_replace('/s_','/b_',$forms['company_strength_img'])) : '';?>" target="blank" data-img="javascript:;">查看大图</a></p>
                        </div>
                        <?php }else{?>
                      	暂无
                      	<?php }?> 
    				</li>
    				<li class="label"><font>*</font>公司实力：</li>
    				<li class="content">
    					<?php echo (isset($forms['company_strength']) && $forms['company_strength']) ? zixun::setContentReplace($forms['company_strength']) : '暂无';?>
    				</li>
    				<li class="label">预期收益图:</li>
    				<li class="content">
    					<?php if(isset($forms['expected_return_img']) && $forms['expected_return_img'] != ''){?>
    					<div class="imgwrap">
                            <p><img src="<?=isset($forms['expected_return_img']) ? URL::imgurl($forms['expected_return_img']) : '';?>"></p>
                            <p>
                            <input type="hidden" value="<?=isset($forms['expected_return_img']) ? URL::imgurl($forms['expected_return_img']) : '';?>">
                            <a class="project_detial_product_img zhaos_big_img" href="<?=isset($forms['expected_return_img']) ? URL::imgurl(str_replace('/s_','/b_',$forms['expected_return_img'])) : '';?>" target="blank" data-img="javascript:;">查看大图</a></p>
                        </div>
                        <?php }else{?>
                      	暂无
                      	<?php }?> 
    				</li>
    				<li class="label">预期收益：</li>
    				<li class="content">
    					<?php echo (isset($forms['expected_return']) && $forms['expected_return']) ? zixun::setContentReplace($forms['expected_return']) : '暂无';?>
    				</li>
    				<li class="label">优惠政策图:</li>
    				<li class="content">
    					<?php if(isset($forms['preferential_policy_img']) && $forms['preferential_policy_img'] != ''){?>
    					<div class="imgwrap">
                            <p><img src="<?=isset($forms['preferential_policy_img']) ? URL::imgurl($forms['preferential_policy_img']) : '';?>"></p>
                            <p>
                            <input type="hidden" value="<?=isset($forms['preferential_policy_img']) ? URL::imgurl($forms['preferential_policy_img']) : '';?>">
                            <a class="project_detial_product_img zhaos_big_img" href="<?=isset($forms['preferential_policy_img']) ? URL::imgurl(str_replace('/s_','/b_',$forms['preferential_policy_img'])) : '';?>" target="blank" data-img="javascript:;">查看大图</a></p>
                        </div>
                        <?php }else{?>
                      	暂无
                      	<?php }?> 
    				</li>
    				<li class="label">优惠政策：</li>
    				<li class="content">
    					<?php echo (isset($forms['preferential_policy']) && $forms['preferential_policy']) ? zixun::setContentReplace($forms['preferential_policy']) : '暂无';?>
    				</li>
    				<li class="clear"></li>
    			</ul>
    		</form>
    	</div>
    </div>
</div>
<script type="text/javascript">
init_big_img()
$("#beforep").toggle(function() {
                        $(".appendspan").css("overflow","visible");
                        $(this).find("a").text("收回");
                    }, function() {
                        $(".appendspan").css("overflow","hidden");
                        $(this).find("a").text("查看更多产品展示图");
                    });

</script>