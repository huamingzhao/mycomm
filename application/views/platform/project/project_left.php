<?php echo URL::webcss("platform/project_home_common.css");?>
<!--项目官网公共部分开始-->
<div class="project_home_page_main">
    <div class="project_home_center">
    <!--页面上方阴影-->
    <div class="project_home_top_bg">
        <ul class="position clearfix">
            <li>您的位置：</li>
            <li>&nbsp;<a href="<?php echo URL::website('');?>">一句话</a>&nbsp;></li>
            <?php if(arr::get($industryid,'id')){?>
            <li>&nbsp;<a title="<?php echo $industryid['name'];?>" href="<?php echo URL::website('xiangdao/fenlei/zhy'.$industryid['id'].'.html')?>"><?php echo $industryid['name'];?>行业加盟</a>&nbsp;></li>
            <?php }?>
            <?php if(arr::get($industryid,'twoid')){?>
            <li>&nbsp;<a title="<?php echo $industryid['twoname'];?>" href="<?php echo URL::website('xiangdao/fenlei/zhy'.$industryid['twoid'].'.html')?>"><?php echo $industryid['twoname'];?>加盟</a>&nbsp;></li>
            <?php }?>
            <li>&nbsp;<h1><?php echo $projectinfo->project_brand_name;?><?php if($proactionmethod=='Project_images'){echo '产品';}elseif($proactionmethod=='Project_projectposter'){echo '海报';}elseif($proactionmethod=='Project_projectCerts'){echo '资质';}elseif($proactionmethod=='Project_projectcompany'){echo '公司';}elseif($proactionmethod=='Project_projectinvest'){echo '投资考察';}?>加盟详情</h1></li>
        </ul>
    </div>
    <!--页面上方阴影 END-->
        <!--顶部标题按钮等-->
        <div class="project_home_title project_home_title_top">
            <?php if(isset($companyinfo->com_user_id)){$comuserid=$companyinfo->com_user_id;}else{$comuserid=0;}?>
            <div class="project_home_title_left">
                <h2>
	                <?php echo mb_substr($projectname,0,25,'UTF-8');?>              
	                <!-- 显示标示项目图标开始 -->
	                <?php 
	                	// 添加了自动结束标志
	                    $spe_flag = 0;  // 这个用在下面我要咨询的那里显示
	                	if($projectinfo->project_id && time()>=$setting['start_time'] && time()<=$setting['end_time'] && isset($setting['project_ids']) && in_array($projectinfo->project_id, $setting['project_ids'])){
	                		// 显示10标志的代码
	                		echo '<var></var>';
							// 用在咨询点击按钮那里 标志变量
	                		if($userid && $reg_time >= $setting['start_time'] &&  $reg_time <= $setting['end_time']){
								$spe_flag = 1;
							}
	                	}
	                ?>
                <!-- 显示标示项目图标结束 -->
                </h2>
                <span id="cardandproject" class="clearfix">
                	<?php if($project_advert) { if($projectinfo->project_vip_set==2){ echo '<font   title="可使用创业币抵扣投资款项目">'.$project_advert.'</font>';}else{echo '<font >'.$project_advert.'</font>';}}//产品广告语?>
                    
                    <?php if(!$project_advert ) {//没有广告语的时候下面收藏显示?>
                    	<?php if($projectinfo->project_vip_set==2){echo '<font   title="可使用创业币抵扣投资款项目">&nbsp;</font>';}?>
	                    <?php $isshowsc='2';if($wathcproject){//显示收藏图标?>
	                        <a  rel="nofollow" class="project_home_title_btn" href="javascript:void(0)" title="已收藏"><img src="<?php echo URL::webstatic('images/platform/project_home/project_home_1_10.png')?>" width="65" height="25" alt="已收藏"/></a>
	                    <?php } else{?>
	                        <a  id="watchproject_<?php echo $userid."_".$projectinfo->project_id?>" rel="nofollow" class="project_home_title_btn project_home_favorites" href="javascript:void(0)" title="收藏"><img src="<?php echo URL::webstatic('images/platform/project_home/project_home_1_09.jpg')?>" width="65" height="25" alt="收藏"/></a>
	                    <?php } ?>
	                <?php } else{$isshowsc='1';}?>
                </span>
                <?php if(false){//显示认领图标isset($isshowrenling) && $isshowrenling?>
                    <a id="renling_projectid" style="display:none"><?php echo $projectinfo->project_id;?></a>
                    <a id="renling_usertype" style="display:none"><?php echo $usertype;?></a>
                <?php }?>

                <font>
                <?php if($is_has_company){?>
                    <a href="<?php echo urlbuilder::projectCompany($projectinfo->project_id);?>" title="<?php echo $comname;?>"><?php echo mb_substr($comname,0,20,'UTF-8');?>
                    </a>
                <?php }else{echo mb_substr($comname,0,25,'UTF-8');
                }?>
                    <?php if($comname){//显示等级?>
                        <a  href="<?php echo urlbuilder::help("qzhishuji")."#level";?>" target="_blank" title="等级">
                        <img src="<?php echo URL::webstatic("images/integrity/{$ity_level['level']}.png")?>"  width="22" height="9" alt="等级"/>
                        </a>
                    <?php }?>
                </font>
                
                <?php if($isshowsc=='1') {//有广告语的时候下面收藏显示?>
	                <span id="cardandproject">
	                    <?php if($wathcproject){//显示收藏图标?>
	                        <a  rel="nofollow" class="project_home_title_btn" href="javascript:void(0)" title="已收藏"><img src="<?php echo URL::webstatic('images/platform/project_home/project_home_1_10.png')?>" width="65" height="25" alt="已收藏"/></a>
	                    <?php } else{?>
	                        <a  id="watchproject_<?php echo $userid."_".$projectinfo->project_id?>" rel="nofollow" class="project_home_title_btn project_home_favorites" href="javascript:void(0)" title="收藏"><img src="<?php echo URL::webstatic('images/platform/project_home/project_home_1_09.jpg')?>" width="65" height="25" alt="收藏"/></a>
	                    <?php } ?>
	                </span>
	           <?php }?>
            </div>
                <div class="project_home_title_right">
                    <a date-type="<?php echo $spe_flag;?>" rel="nofollow" class="consult" id="zx_<?php echo $projectinfo->project_id.'_'.$comuserid.'_1'?>" href="javascript:void(0)" title="我要留言"><img src="<?php echo URL::webstatic('images/platform/project_home/project_home_1_12.png')?>" width="109" height="42" alt="我要留言" /></a>
                    <a date-type="<?php echo $spe_flag;?>"  rel="nofollow" class="consult" id="fsyx_<?php echo $projectinfo->project_id.'_'.$comuserid.'_3'?>" href="javascript:void(0)" title="发送名片"><img src="<?php echo URL::webstatic('images/platform/project_home/project_home_1_16.png')?>" width="109" height="42" alt="发送名片"/></a>
                </div>

            <div class="clear"></div>
            <input type="hidden" id="hidproselect" value="<?php echo $proactionmethod;?>" ></input>
            <input type="hidden" id="hidseclecttype" value="1" ></input>
            <div class="project_home_top_btn_bg"></div>
        </div>
        <!--顶部标题按钮等 END-->

        <!--中间部分-->
        <?php echo $content; ?>
        <!--中间部分 END-->

        <!--左侧悬浮菜单-->
        <div class="project_home_tool_div">
        <div id="project_home_tool" class="project_home_tool">
            <ul>
                <li id="Project_index" ><a href="<?php echo urlbuilder::project($projectinfo->project_id,1);?>"><i class="xm"></i><font>项目</font></a></li>
                <?php if(isset($ispage) && $ispage){?><li id="Project_projectposter"><a href="<?php echo urlbuilder::projectPoster($projectinfo->project_id);?>"><i class="hb"></i><font>海报</font></a></li><?php }?>
                <?php if(isset($is_has_image) && $is_has_image){?><li id="Project_images"><a href="<?php echo urlbuilder::projectImages($projectinfo->project_id);?>"><i class="cp"></i><font>产品</font></a></li><?php }?>
                <?php if($isCerts){?><li id="Project_projectCerts"><a href="<?php echo urlbuilder::projectCerts($projectinfo->project_id);?>"><i class="zztp"></i><font class="zztp_font">资质图片</font></a></li><?php }?>
                <?php if ($isProjectInvest==1){?><li id="Project_projectinvest"><a href="<?php echo urlbuilder::projectInvest($invest_id);?>"><i class="tz"></i><font class="tz_font">投资考察</font></a></li><?php }?>
                <?php if($is_has_company){?><li id="Project_projectcompany"><a href="<?php echo urlbuilder::projectCompany($projectinfo->project_id);?>"><i class="gs"></i><font>公司</font></a></li><?php }?>
            </ul>
        </div>
        </div>
        <!--左侧悬浮菜单 END-->
        <!--底部按钮-->
        <div class="project_home_title project_home_bottom_btn">
            <div class="project_home_title_left">
                <a target="_blank" href="<?php if(arr::get($industryid,'twoid')){$fanhuiid=$industryid['twoid'];}else{$fanhuiid=$industryid['id'];} echo URL::website('xiangdao/fenlei/zhy'.$fanhuiid.'.html')?>" title="<?php echo $industryid['name'];?>加盟"><img src="<?php echo URL::webstatic('images/platform/project_home/project_home_1_80.jpg')?>" width="94" height="42" alt="<?php echo $industryid['name'];?>加盟"></a>
            </div>
            <div class="project_home_title_right">
                <a rel="nofollow" class="consult" id="zx2_<?php echo $projectinfo->project_id.'_'.$comuserid.'_1'?>" href="javascript:void(0)" title="我要咨询"><img src="<?php echo URL::webstatic('images/platform/project_home/project_home_1_12.jpg')?>" width="109" height="42" alt="我要咨询"/></a>
                <a rel="nofollow" class="consult" id='fsyx2_<?php echo $projectinfo->project_id.'_'.$comuserid.'_3'?>' href="javascript:void(0)" title="发送意向"><img src="<?php echo URL::webstatic('images/platform/project_home/project_home_1_16.jpg')?>" width="109" height="42" alt="发送意向"/></a>
            </div>
            <div class="clear"></div>
        </div>
        <!--底部按钮 END-->
    </div>
    <!--页面下方阴影-->
    <div class="project_home_bottom_bg"></div>
    <!--页面下方阴影 END-->
    <div class="clear"></div>
</div>

<!--透明背景开始-->
<div id="opacity_box" style="z-index:999"></div>
<!--透明背景结束-->
<!--举报弹出框-->
<div id="jubao" class="project_home_jubao" style="z-index:1000;height:auto"></div>
<script type="text/javascript">
	
    $(document).ready(function() {
		
        var method=$("#hidproselect").val();
        var linkstyle=$("#project_home_tool li");
        linkstyle.removeClass();
        $("#"+method).addClass("project_home_fc");

        if(method == "Project_projectzixunlist" || method == "Project_zixuninfo" || method == "Project_industryzixunlist"){
            $("#Project_index").addClass("project_home_fc");
        }
		//initGetCards()
		
		//initJubao()
		//initQuickRegister()
    });
	
	
</script>
<input type="hidden" id="platform_num" value="<?=$platform_num?>" /><!--个项目-->
<input type="hidden" id="user_num" value="<?=$user_num?>" /><!--个用户加入一句话-->
<input type="hidden" id="projectname" value="<?php ?><?php echo $projectinfo->project_brand_name;?><?php ?>" /><!--项目名称-->
<input type="hidden" id="projectid" value="<?php ?><?php echo $projectinfo->project_id."_".$projectinfo->project_brand_name;?><?php ?>" /><!--项目id-->

<select id="address_hide" name="per_area" style="display:none">
<option value="" >不选</option>
        <?php
        if( !empty( $area ) ){
            foreach ( $area as $v ){
           		
              echo   '<option value="'.$v['cit_id'].'">'.$v['cit_name'].'</option>';
           
            }
        }
        ?>
</select>
<?php echo URL::webjs("platform/project_home/project_home_index.js");?>
<?php echo URL::webjs("platform/project_home/project_industry_news.js");?>
<!--[if lt IE 9]>
    <?php echo URL::webjs("platform/project_home/project_home_judge_width.js");?>
<![endif]-->