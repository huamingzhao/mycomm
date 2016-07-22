<?php echo URL::webcss("platform/project_home_no_ad.css");?>
<?php echo URL::webjs("platform/filter/swfobject.js");?>
<?php if($bobao['bobao_status']==2){echo URL::webjs("platform/template/bobao_flash.js");}?>
<!--中间部分-->
		<div class="project_home_content">
			<!--左侧部分-->
			<div class="project_home_content_left">
            <div class="project_home_content_left_title">
            <h1><?=$invest['investment_name']?></h1>
            <span class="project_home_content_left_count">意向人数： <font><?php  echo $investment_bi;?></font> 人</span>
            <?php if($bobao['bobao_status']==2){?>
            <span class="project_home_content_left_info">
            <font><?php echo arr::get($bobao,'bobao_sign');?></font>人签约，签约率<font><?php echo floor(arr::get($bobao,'bobao_sign')/arr::get($bobao,'bobao_num')*100);?></font>%
            </span>
            <?php }?>
            <div class="clear"></div>
            </div>
				<!--左侧部分头部-->
				<div class="project_home_content_info_div">
					<div class="project_home_content_info project_home_content_info_investment project_home_content_info_investment_history">
						<div class="project_home_info_top">
							<div class="project_home_info_investment_left" style="padding-top:8px;">
							<p style="margin-top:0px;">
								<img width="20" height="19" src="<?php echo URL::webstatic("images/platform/project_home/project_home_investment_06.jpg")?>" alt="报名结束图片" /><i>报名已结束</i>
							</p>
							<p style="margin-top:0px; line-height:0px;">
								<img width="20" height="19" style=" vertical-align:-4px;" src="<?php echo URL::webstatic("images/platform/project_home/project_home_investment_09.jpg")?>" alt="报名人数图片"/><i style="font-size:1.4em;">报名人数：</i><font><? echo arr::get($invest,'in_num');?></font>人
							</p>
							<p>
								<i style="font-size:1.1em;">我想报名下场会议</i><br/>
								<a id="mb_<?php echo $project_id.'_'.$com_user_id.'_1'?>" href="javascript:void(0)" class="consult" title="马上联系企方"><img src="<?php echo URL::webstatic("images/platform/project_home/project_home_investment_12.jpg")?>" width="133" height="31" alt="马上联系企方"/></a>
							</p>
							</div>
						</div>
						<div class="project_home_info_center" style="padding-top:5px;">
							<div class="project_home_info_investment_center">
								<ul>
									<li>时间：<?php if($start==$end){?><?=date('Y.m.d',$start)?><?php }else{?><?=date('Y.m.d',$start)?>-<?=date('Y.m.d',$end)?><?php }?><font>（已结束）</font></li>
									<li>地点：<?php if($invest['spantime']<0){?><?=$invest['investment_address']?><?php }else{?><?=$city?><?php }?></li>
									<li>招商经理：<?=$invest['com_name']?></li>
									<li>联系电话： <?=$invest['com_phone']?></li>
								</ul>
							</div>
						</div>
						<div class="project_home_info_bottom" style="padding-top:6px;">
							<div class="project_home_info_investment_right">
								<a href="javascript:void(0)" title="广告图片"><img width="160" height="120" alt="<?=$invest['investment_name']?>" src="<?php echo URL::imgurl($invest['investment_logo']);?>"/></a>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
				</div>
				<!--左侧部分头部 END-->
				<!--左侧部分正文-->
				<div class="project_home_content_main">
					<ul class="menu">
						<li class=" project_home_fc"><h2><a href="#" title="详情">详情</a></h2></li>
						<li><h2><a href="#" title="参会流程">参会流程</a></h2></li>
						<?php if($bobao['bobao_status']==2){?><li><h2><a href="#" title="现场影集">现场影集</a></h2></li><?php }?>
						<li class="project_home_last"><h2><a href="#" title="会议地点">会议地点</a></h2></li>
						<div class="clear"></div>
					</ul>
					<div  id="content_text1" class="project_home_content_text">
						<div>
						<?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_details'), 0)));echo $string;?>
	                	</div>
					<div id="project_home_content_text_other" style="display:block;">
						<p class="title">参会流程</p>
						<?$agenda = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_agenda'), 0)));echo $agenda;?>
                    <?php if (arr::get($invest, 'investment_preferential')!=""){?><p class="title">优惠政策</p><?php }?>
                    <?$investment_preferential = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_preferential'), 0)));echo $investment_preferential;?>
					<?php if($bobao['bobao_status']==2){?>
					<div class="project_home_img_center">
						<p class="title">现场影集</p>
		                      <div id="imagewallss"></div>
					</div>
					<?php }?>
					<p class="title">会议地点</p>
						<div class="map_main" id="map_canvass" style="width:100%; height:350px;  margin-top:10px;"></div>
					
						</div>
					</div>
					<div class="clear"></div>
					<div  id="content_text2" class="project_home_content_text" style="display:none;">
 					<?$agenda = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_agenda'), 0)));echo $agenda;?>	
					</div>
					<?php if($bobao['bobao_status']==2){?>
					<div  id="content_text3" class="project_home_content_text_other" style="display:none;">
            	<?php if($bobao['bobao_status']==2){?>
					<div class="project_home_img_center">
						<div class="zsh_hist_jj">
		                      <div id="imagewalls"></div>
		            		  <input type="hidden" name="hid" value="<?php echo arr::get($invest, 'investment_id');?>" id="hids">
		                   </div>
		                   <div class="clear"></div>
					</div>
					<?php }?>
					</div>
					<div id="content_text4" class="project_home_content_text" style="display:none;">
 						<div class="map_main" id="map_canvas" style="width:100%; height:350px;  margin-top:10px;"></div>
					</div>
					<?php }else{?>
					<div id="content_text3" class="project_home_content_text" style="display:none;">
 						<div class="map_main" id="map_canvas" style="width:100%; height:350px;  margin-top:10px;"></div>
					</div>
					<?php }?>
				</div>
				<!--左侧部分正文 END-->
			</div>
			<!--左侧部分 END-->
			         <!--右侧部分-->
            <div class="project_home_content_right">
            <?php if($willInvest_num == 0){?>
            <ul class="project_home_right_detial">
                <li class="project_home_right_title"><h2>项目详情</h2></li>
                <li class="project_home_right_img_no_ad">
                <p class="img"><span><label>
                    <a href="#" title="广告图片"><img src="<?php
                if($projectinfo->project_source != 1) {//项目logo图片
                    $tpurl=project::conversionProjectImg($projectinfo->project_source, 'logo', $projectinfo->as_array());if(project::checkProLogo($tpurl)){echo $tpurl;} else{echo URL::webstatic('images/common/company_default.jpg');}}
                else {
                    $tpurl=URL::imgurl($projectinfo->project_logo);
                     if(project::checkProLogo($tpurl)){echo $tpurl;} else{echo URL::webstatic('images/common/company_default.jpg');}
                } ?>" width="120" height="95" alt="广告图片"/></a>
                </label></span></p>
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
                        echo '、<a style="color: #0E71B4;" target="_Blank" href="/xiangdao/fenlei/zhy'.arr::get($pro_industry,'two_id','1').'.html">'.arr::get($pro_industry,'two_name','').'</a>';
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
                <li>
                    <font>公司名称 </font>
                    <span>
                    <?php if($is_has_company){?>
                        <a target="_blank" href="<?php echo urlbuilder::projectCompany($projectinfo->project_id);?>" title="<?php if(isset( $companyinfo->com_name)){echo $companyinfo->com_name;}?>">
                        <?php if(isset( $companyinfo->com_name)){echo $companyinfo->com_name;}?></a>
                    <?php }else{if(isset( $companyinfo->com_name)){echo $companyinfo->com_name;}}?>
                    </span>
                </li>
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
            </ul>
            <?php }else{?>
            <div class="otherinvestigate">
                <h3>即将召开的投资考察会</h3>
                <?php if ($willInvest){?>
                <ul>
                    <?php foreach ($willInvest as $k=>$v){?>
                    <li>
                        <a class="mtauto" href="<?php echo urlbuilder::projectInvest($v['investment_id']);?>"><img width="122" height="96" src="<?php echo URL::imgurl($v['investment_logo']);?>"></a>
                        <p><a href="<?php echo urlbuilder::projectInvest($v['investment_id']);?>"><? echo mb_strlen($v['investment_name'])>16?Text::limit_chars($v['investment_name'],16):$v['investment_name'];?></a></p>
                        <p class="fz14">报名倒计时：<strong><?php echo $v['spantime']?></strong>天</p>
                        <p class="clearfix"><span class="floleft col80">时间：</span><span class="floleft"><?php echo date('Y.m.d',$v['investment_start'])?>-<?php echo date('Y.m.d',$v['investment_end'])?></span></p>
                        <p class="clearfix"><span class="floleft col80">地点：</span><span class="floleft jchar wt144"><?php echo $v['investment_address']?></span></p>
                    </li>
                    <?php }?>
                </ul>
                <?php }?>
            </div>
            <?php }?>
            <?php if ($historyInvest){?>
            <div  class="otherinvestigate">
                <h3>历史投资考察会</h3>
            <ul class="liangul">
                <?php foreach ($historyInvest as $k=>$v){?>
                <li>
                    <a class="mtauto" href="<?php echo urlbuilder::projectInvest($v['investment_id']);?>"><img width="122" height="96" src="<?php echo URL::imgurl($v['investment_logo']);?>"></a>
                    <p><a href="<?php echo urlbuilder::projectInvest($v['investment_id']);?>"><? echo mb_strlen($v['investment_name'])>16?Text::limit_chars($v['investment_name'],16):$v['investment_name'];?></a></p>
                    <p class="clearfix fz14">报名人数：<strong><?php echo $v['investment_apply']?></strong>人</p>
                    <p class="clearfix"><span class="floleft col80">时间：</span><span class="floleft"><?php echo date('Y.m.d',$v['investment_start'])?>-<?php echo date('Y.m.d',$v['investment_end'])?></span></p>
                    <p class="clearfix"><span class="floleft col80">地点：</span><span class="floleft jchar wt144"><?php echo $v['investment_address']?></span></p>
                </li>
                <?php }?>
            </ul>
            </div>
                <?php }?>

            </div>
            <div class=" clear"></div>
            <!--右侧部分 END-->
		</div>
		<!--中间部分 END-->
<script type="text/javascript">
    var tj_type_id = 2;
    var tj_pn_id = <?=$invest['investment_id']?>;
</script>
<!--更多文字结束-->
<!--中部结束-->
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.5&ak=C9a685ebeddee255888caceda5eebe25"></script>
<script type="text/javascript">
$(function(){
	var map = new BMap.Map("map_canvas");
	var maps = new BMap.Map("map_canvass");	
	var myGeo = new BMap.Geocoder();
	map.centerAndZoom(new BMap.Point(121.482804, 31.231188), 11);
	maps.centerAndZoom(new BMap.Point(121.482804, 31.231188), 11);
	myGeo.getPoint("<?=$invest['investment_address']?>", function(point){
	  if (point) {
		<?php if(strlen($invest['investment_address'])<=16||($invest['outside_investment_id']>0&&$invest['investment_type']==2)){?>
		map.centerAndZoom(point, 11);
		maps.centerAndZoom(point, 11);
		<?php }else{?>
	    map.centerAndZoom(point, 16);
	    maps.centerAndZoom(point, 16);
	    <?php }?>
	    map.addControl(new BMap.OverviewMapControl());
	    map.enableScrollWheelZoom();
	    map.addOverlay(new BMap.Marker(point));
	    map.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
	    map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
	    map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
	    map.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮
	    var opts = {
	    		  width : 200,     // 信息窗口宽度
	    		  height: 60,     // 信息窗口高度
	    		  title : "<?=$invest['investment_name']?>" , // 信息窗口标题
	    		  enableMessage:false,//设置允许信息窗发送短息
	    		  message:"加入该场投资考察会？"
	    		}
	    var infoWindow = new BMap.InfoWindow("地址：<?=$invest['investment_address']?>", opts);  // 创建信息窗口对象
	    map.openInfoWindow(infoWindow,point); //开启信息窗口
	    maps.openInfoWindow(infoWindow,point); //开启信息窗口
	    maps.addControl(new BMap.OverviewMapControl());
	    maps.enableScrollWheelZoom();
	    maps.addOverlay(new BMap.Marker(point));
	    maps.addControl(new BMap.NavigationControl());  //添加默认缩放平移控件
	    maps.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL}));  //右上角，仅包含平移和缩放按钮
	    maps.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT, type: BMAP_NAVIGATION_CONTROL_PAN}));  //左下角，仅包含平移按钮
	    maps.addControl(new BMap.NavigationControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, type: BMAP_NAVIGATION_CONTROL_ZOOM}));  //右下角，仅包含缩放按钮
	  }
	}, "全国");
	
	//用户访问招商会的记录
	 var investmentid = <?php echo $invest['investment_id'];?>;
	 $.ajax({
	     type: "post",
	     dataType: "json",
	     url: "/platform/ajaxcheck/addPersonAboutInv",
	     data: "investmentid="+investmentid,
	     complete :function(){
	     },
	     success: function(msg){
	     }
	});
});
</script>