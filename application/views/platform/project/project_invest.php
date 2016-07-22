<?php echo URL::webcss("platform/project_home_no_ad.css");?>
<!--中间部分-->
		<div class="project_home_content">
			<!--左侧部分-->
			<div class="project_home_content_left">
			<div class="project_home_content_left_title">
            <h1><?=$invest['investment_name']?></h1>
             <span class="project_home_content_left_count">意向人数： <font><? echo $investment_bi;?></font> 人</span>
            <div class="clear"></div>
            </div>
				<!--左侧部分头部-->
				<div class="project_home_content_info_div">
					<div class="project_home_content_info project_home_content_info_investment">
						<div class="project_home_info_top">
							<div class="project_home_info_investment_left" style="padding-top:8px;">
							<p style="padding-top:17px;">
								<img src="<?php echo  URL::webstatic('/images/platform/project_home/project_home_investment_06.jpg');?>"/><i>报名倒计时：</i><font><?=$invest['spantime']?></font><i>天</i>
								<a rel="nofollow" <?php if(isset($user_type)&&$user_type==1){echo 'data-content="<p class=\'errorbox\'>很抱歉，只能个人用户才能报名参会哦！</p>"  data-btn="ok"';}else{?>data-tag='#project_home_letter_div'<?php }?> class="<?php if(!isset($user_type)||$user_type==""){echo " gologinbtn";}else{echo "messageBox";}?>" href="javascript:void(0)" data-type="click" data-title="参会邀请函" title="报名参会"><img style="padding-top:22px;" src="<?php echo  URL::webstatic('/images/platform/project_home/project_home_invest_join_03.jpg')?>"/></a>
							</p>
							</div>
						</div>
						<div class="project_home_info_center">
							<div class="project_home_info_investment_center" style="padding-top:5px;">
								<ul>
									<li><i>时间：<?php if($start==$end){?><?=date('Y.m.d',$start)?><?php }else{?><?=date('Y.m.d',$start)?>-<?=date('Y.m.d',$end)?><?php }?> </i></li>
									<li>地点：<?=$invest['investment_address']?></li>
									<li>招商经理：<?=$invest['com_name']?></li>
									<li>联系电话： <?=$invest['com_phone']?></li>
								</ul>
							</div>
						</div>
						<div class="project_home_info_bottom" style="padding-top:6px;">
							<div class="project_home_info_investment_right">
							<a href="javascript:void(0)" title="广告"><img width="160" height="120" src="<?php echo URL::imgurl($invest['investment_logo']);?>" alt="<?=$invest['investment_name']?>"/></a>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div class="project_home_info_img project_home_investment_img">
						<img src="<?php echo  URL::webstatic('/images/platform/project_home/project_home_1_36.jpg')?>"  alt="企业宣传图片"/>
					</div>
					<div class="clear"></div>
				</div>
				<!--左侧部分头部 END-->
				<!--左侧部分正文-->
				<div class="project_home_content_main">
					<ul class="menu">
						<li class=" project_home_fc"><h2><a href="#" title="详情">详情</a></h2></li>
						<li><h2><a href="#" title="参会流程">参会流程</a></h2></li>
						<li class="project_home_last"><h2><a href="#" title="会议地点">会议地点</a></h2></li>
						<div class="clear"></div>
					</ul>
					<div id="content_text1" class="project_home_content_text">
						<div>
						<?php echo '<strong>'.$projectinfo->project_brand_name.'参会详情</strong></br>';?>
						<?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_details'), 0))); echo $string;?>
						</div>
						<div id="project_home_content_text_other" style="display:block;">
						<p class="title">参会流程</p>
						<?$agenda = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_agenda'), 0))); echo $agenda;?>
						<?php if (arr::get($invest, 'investment_preferential')!=""){?><p class="title">优惠政策</p><?php }?>
						<?$investment_preferential = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_preferential'), 0)));echo $investment_preferential;?>
						<p class="title">会议地点</p>
						<div class="map_main" id="map_canvass" style="width:100%; height:350px;  margin-top:10px;"></div>
						</div>
					</div>
					<div class="clear"></div>
					<div id="content_text2" class="project_home_content_text" style="display:none;">
                    <?$agenda = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($invest, 'investment_agenda'), 0)));echo $agenda;?>
						
					</div>
					<div id="content_text3" class="project_home_content_text" style="display:none;">
                    	<div class="map_main" id="map_canvas" style="width:100%; height:350px;  margin-top:10px;"></div>
					</div>
					<div class="clear"></div>
				</div>
				<!--左侧部分正文 END-->
			</div>
			<!--左侧部分 END-->
			<!--右侧部分-->
			<div class="project_home_content_right">
            <?php if($willInvest_num == 1){?>
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
<?php if(isset($user_type)&&$user_type==2){?>
<!--参会邀请函弹出框-->
<div id="project_home_letter_div" class="project_home_letter_div">
	<div class="project_home_letter">
		<h1>参会邀请函</h1>
		<p class="msg">如果您对此项目有兴趣，请花上1分钟的时间填写邀请函</p>
		<div class="project_home_letter_main">
			<form action="<?php echo URL::site('/platform/project/applyInvest');?>" method="post" id='applyinvest'>
				<span class="title">	
					<label>  姓    名 ：</label>
					<label>  性    别 ：</label>
					<label>  手    机 ：</label>
				</span>
				<span class="content">	
					<span>
					<input type="text" name="apply_name" value="<?=$user->per_realname?>" id="apply_realname"/><span class="project_home_chyq_msg" style="color: red;padding-left:15px;visibility:hidden;" id="tishi7">姓名不能为空</span>
					</span>
					<span class="sex">
					<input id="project_home_letter_man" name="apply_sex" type="radio" value="1" <?php if($user->per_gender==1||$user->per_gender==0){echo "checked";}?>/>
					<label for="project_home_letter_man">男</label>
					<input id="project_home_letter_woman" name="apply_sex" type="radio" value="2" <?php if($user->per_gender==2){echo "checked";}?>/>
					<label for="project_home_letter_woman">女</label>
					</span>
					<span>
					<input type="text" name="apply_mobile" value="<?=$mobile?>" id="apply_moblie"/><span class="project_home_chyq_msg" style="color: red;padding-left:15px;visibility:hidden;" id="tishi8">手机不能为空</span>
					</span>
                    <span>
                    <input type="radio" name="invest_id" <?php if($invest_array['num']==0) echo 'checked';?> class="radio add_radio" value="<?=$invest_array['investment_id']?>" <?php if($invest_array['num']>0){?>disabled<?php }?> style="display: none"/>
                    </span>
					<span class="project_home_letter_msg"></span>
				</span>
				<span class="hotel">
						<label>是否要入住公司统一安排的酒店：</label>
						<input type="radio" id="project_home_letter_hotel_yes"  name="is_hotel" value="1" checked='checked' />
						<label for="project_home_letter_hotel_yes">是</label>
						<input type="radio" id="project_home_letter_hotel_no" name="is_hotel" value="2"/>
						<label for="project_home_letter_hotel_no">否</label>
				</span>
				<span class="submit">
					<input type="hidden" name="projectid" value="<?=$project_id?>">
	                <input type="hidden" name="user_id" value="<?=$user_id?>">
					<?php if(!$invest_array['num']){?><input type="submit" value="确认信息，提交"/><?php }?>
				</span>
				</form>
				<div class="clear"></div>
		</div>
	</div>
</div>
<?php }?>
<?php if(isset($user_type)&&$user_type==2){?>

    <?php }elseif(empty($user)){?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#changeCodeImg').click(function() {
                        var url = '/captcha';
                            url = url+'?'+RndNum(8);
                            $("#vfCodeImg1").attr('src',url);
                    });
            });
         </script>
    <?php }else{?>
    <!--提示-弹出框开始-->
    <div id="yellow-c-box">
        <a title="close" href="#" class="close">close</a>
        <span>很报歉，只能个人用户才能报名参会哦！</span>
        <p><input name="" type="button" class="btn_close"></p>
    </div>
    <!--提示-弹出框结束-->
    <?php }?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.5&ak=C9a685ebeddee255888caceda5eebe25"></script>
<script type="text/javascript">
var tj_type_id = 2;
var tj_pn_id = <?=$invest['investment_id']?>;
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
		
	var opacity = $("#opacity");
    var box = $("#send_box");
	$('#project_home_letter_open_company').click(function(){
		opacity.show();
		$('#msgcontent').html('<p class="errorbox">很抱歉，只能个人用户才能报名参会哦！</p>');
        box.slideDown(500);
	});
	//用户访问招商会的记录
	var investmentid = "<?php echo $investmentsid;?>";
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
<!--参会邀请函弹出框 END-->

		