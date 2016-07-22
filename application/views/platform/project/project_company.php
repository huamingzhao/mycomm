<!--中部开始-->
<div class="project_home_content">
	<!--左侧部分-->
	<div class="project_home_content_left">
		<!--左侧部分正文-->
		<div class="project_home_content_main">
			<ul class="menu">
				<li class=" project_home_fc"><h2><a href="#" title="公司简介">公司简介</a></h2></li>
				<?php if(isset($is_has_zizhiimage) && $is_has_zizhiimage){?>
					<li class="project_home_last"><h2><a href="javascript:void(0)" title="资质图片">资质图片</a></h2></li>
				<?php }?>
				<div class="clear"></div>
			</ul>
			<div>
				<div id="content_text1" class="project_home_content_text">
				   <?php if(isset($companyinfo->com_desc) && $companyinfo->com_desc){?>
			            <div class="p"><?php echo htmlspecialchars_decode($companyinfo->com_desc);?></div>
			        <?php }elseif(isset($projectinfo->outside_com_introduce) && $projectinfo->outside_com_introduce){?>
			            <div class="p"><?php echo htmlspecialchars_decode($projectinfo->outside_com_introduce);?></div>
			        <?php }elseif(isset($projectinfo->project_summary) && $projectinfo->project_summary){?>
			            <div class="p"><?php echo htmlspecialchars_decode($projectinfo->project_summary);?></div>
			        <?php }else{	}?>
				<?php if(isset($is_has_zizhiimage) && $is_has_zizhiimage){?>
					<div id="project_home_content_text_other" style="display:block;">
						<p class="title">资质图片</p>
						<div class="project_home_company_qualifications">
						  <div id="imagewall"></div>
						</div>
					</div>
				<?php }?>
				</div>
				<?php if(isset($is_has_zizhiimage) && $is_has_zizhiimage){?>
					<div id="content_text2" style="display: none; text-align:center;" class="project_home_content_text">
						<div id="imagewall2"></div>
					</div>
				<?php }?>
			</div>
			<div class="clear"></div>
			 <input type="hidden" name="hid" value="<?php echo isset($projectinfo->project_id)?$projectinfo->project_id :'';?>" id="hid">
		</div>
		<!--左侧部分正文 END-->
	</div>
	<!--左侧部分 END-->
	<!--右侧部分-->
	<div class="project_home_content_right">
		<ul class="project_home_right_detial">
			<li class="project_home_right_title"><h2>基本信息</h2></li>
			<li class="project_home_right_img_no_ad project_home_right_img_company">
				<p class="img"><span><label>
				<?php if(!$companyinfo->com_id && !$outside_user_company && !$outside_test_company){?>
					<img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?php echo $projectlogonew;?>" width="120" height="95" alt="<?php echo $projectinfo->project_brand_name;?>Logo图像"/>
				<?php }else{?>
					<img width="120" height="95" src="<?php if(isset($companyinfo->com_logo) && $companyinfo->com_logo==''){echo URL::webstatic("images/common/company_default.jpg");}else{echo URL::imgurl($companyinfo->com_logo);}?>" alt="公司图片"/>
				<?php }?>
				</label></span></p>
			</li>
			<!-- 没公司信息的显示项目信息开始 -->	
			<?php if(!$companyinfo->com_id && !$outside_user_company && !$outside_test_company){?>
			 <li class="yixiangrighta">
	            <span>意向加盟者<em><?php echo $pro_industry_count;?></em>人</span>
	        </li>
	        <li>
	            <font>品牌名称</font>
	                    <span><a target="_blank" href="<?php echo urlbuilder::project($projectinfo->project_id);?>" title="<?php echo $projectinfo->project_brand_name;?>"><?php
	                            if(mb_strlen($projectinfo->project_brand_name)>16){
	                                echo mb_substr($projectinfo->project_brand_name,0,16,'UTF-8').'...';
	                            }
	                            else{
	                                echo $projectinfo->project_brand_name;
	                            }
	                            ?></a></span>
	        </li>
	        <li>
	            <font>投资金额</font>
	            <span class="project_home_blue"><a target="_black" href="<?php echo URL::website('xiangdao/fenlei/m'.$projectinfo->project_amount_type.'.html');?>" title="<?php echo arr::get($monarr,$projectinfo->project_amount_type,'无');?>"><?php echo arr::get($monarr,$projectinfo->project_amount_type,'无');?></a></span>
	        </li>
	        <li>
	            <font>行    业</font>
	                    <span class="project_home_blue"><a href="" title="">
	                            <?php
	                            if(arr::get($pro_industry,'one_id','')){
	                                echo '<a style="color: #0E71B4;" target="_Blank" href="/xiangdao/fenlei/zhy'.arr::get($pro_industry,'one_id','1').'.html">'.arr::get($pro_industry,'one_name','').'</a>';
	                            }
	                            if(arr::get($pro_industry,'two_id','')){
	                                echo '<a style="color: #0E71B4;" target="_Blank" href="/xiangdao/fenlei/zhy'.arr::get($pro_industry,'two_id','1').'.html">'.arr::get($pro_industry,'two_name','').'</a>';
	                            }
	                            ?></a></span>
	        </li>
	        <li>
	            <font>适合人群 </font>
	                    <span><?php
	                        if(count($group_text)>0 && $group_text!='不限'){
	                            $t=1;
	                            foreach($group_text as $gro){
	                                $t++;
	                                echo '<a target="_black" href="/search/?w='.urlencode($gro).'" title="'.$gro.'">'.$gro.'</a>';
	                                if(count($group_text)==2){
	                                    if($t>1 && $t<=2){
	                                        echo '、';
	                                    }
	                                }
	                                if(count($group_text)>=3){
	                                    if($t>1 && $t<=3){
	                                        echo '、';
	                                    }
	                                }
	                                if($t>3){
	                                    break;
	                                }
	                            }
	                        }
	                        else{
	                            echo $group_text;
	                        }
	                        ?></span>
	        </li>
	        <li>
	            <font>招商地区 </font>
	            <span class="project_home_blue"><?php echo $area_zhong;?></span>
	        </li>
	        <?php if(isset( $companyinfo->com_name) && $companyinfo->com_name){ ?>
	            <li>
	                <font>公司名称 </font>
	                    <span>
	                    <?php if($is_has_company){?>
	                        <a target="_blank" href="<?php echo urlbuilder::projectCompany($projectinfo->project_id);?>" title="<?php echo $companyinfo->com_name;?>">
	                            <?php echo $companyinfo->com_name;?></a>
	                    <?php }else{echo $companyinfo->com_name;}?>
	                    </span>
	            </li>
	        <?php }?>
	        <li>
	            <font>招商形式 </font>
	                    <span ><a href="" title=""><?php $lst = common::businessForm();
	                            $pro_count=count($projectcomodel);
	                            if($pro_count){
	                                $comodel_text=0;
	                                foreach ($projectcomodel as $v){
	                                    $comodel_text++;
	                                    echo '<a target="_Blank" href="/xiangdao/fenlei/xs'.$v.'.html">'.$lst[$v].'</a>';
	                                    if($comodel_text < $pro_count){
	                                        echo '、';
	                                    }
	                                }
	                            }else{
	                                echo '不限';
	                            } ?></a></span>
	        </li>
	        <li>
	            <font>品牌发源地 </font>
	            <span><?php if($projectinfo->project_brand_birthplace){ echo $projectinfo->project_brand_birthplace;}else{echo '暂无信息';}?></span>
	        </li>
	        <!-- 没公司信息的显示项目信息结束 -->	
	        <!-- 有公司信息的显示公司信息开始 -->
			<?php }else{?>
			<li>
				<font>公司性质：</font>
				<span><?php
                    $soure = common::comnature();
                    if(isset($companyinfo->com_nature)){
                    	echo arr::get($soure,$companyinfo->com_nature,'暂无相关资料');
                    }else{
                    	echo '暂无相关资料';
                    }
                    ?></span>
			</li>
			<li>
				<font>注册资金：</font>
				<span><?php if(isset($companyinfo->com_registered_capital) && $companyinfo->com_registered_capital){echo $companyinfo->com_registered_capital."万";}else{echo "暂无相关资料";}?></span>
			</li>
			<li>
				<font>成立时间：</font>
				<span><?php if(isset($companyinfo->com_founding_time) && $companyinfo->com_founding_time){echo substr($companyinfo->com_founding_time,0,4)."年";}else{echo "暂无相关资料";}?></span>
			</li>
			<li>
				<font>联  系 人：</font>
				<span><?php if(isset($companyinfo->com_contact) && $companyinfo->com_contact){echo $companyinfo->com_contact;}else{echo "暂无相关资料";}?></span>
			</li>
			<li>
				<font>公司地址： </font>
				<span><?php if(isset($companyinfo->com_adress) && $companyinfo->com_adress){echo $companyinfo->com_adress;}else{echo "暂无相关资料";}?></span>
			</li>
			<?php }?>
		    <!-- 有公司信息的显示公司信息结束 -->
		</ul>
	</div>
	<div class=" clear"></div>
	<!--右侧部分 END-->
</div>
<!--中部结束-->
<?php echo URL::webjs("platform/template/yellow_company_flash.js");?>
<script>
$(document).ready(function(){
    //意向浏览统计
    var projectid = <?php echo $projectinfo->project_id;?>;
    var url = "/platform/ajaxcheck/TongJiProjectPv";
    $.post(url,{"project_id":projectid,"type":5},function(data){
    },"json");
});
</script>