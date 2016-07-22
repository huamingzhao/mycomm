<?php echo URL::webcss("platform/project_home_no_ad.css");?>
<!--中间部分-->
		<div class="project_home_content">
			<!--左侧部分-->
			<div class="project_home_content_left">
				<!-- 招商会_公共页 -->
				<div class="project_home_content_noinfo_div">
					<p class="project_home_content_noinfo">
						<span>您好，该项目目前没举办投资考察会！</span>
						<font>敬请期待下场投资考察会……</font>
					</p>
                    <div class="project_home_content_noinfo_btn_div">
					<p class="project_home_content_noinfo_btn">
						<span>您可联系企方报名下场投资考察会……<a id="mbx_<?php if (isset($companyinfo->com_user_id))echo $projectinfo->project_id.'_'.$companyinfo->com_user_id.'_1';else echo $projectinfo->project_id.'_0_1';?>" href="javascript:void(0)" class="consult">马上联系企方</a></span>
						<span>您也可搜索相关条件的投资考察会……<a href="<?php echo urlbuilder::rootDir ("touzikaocha");?>" target="_blank">搜索投资考察会</a></span>
					</p>
                    </div>
				</div>
				<!-- 招商会_公共页 END-->
			</div>
			<!--左侧部分 END-->
			<!--右侧部分-->
			<div class="project_home_content_right">
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
				
				
				<?php if (isset($historyInvest)&&$historyInvest!=array()){?>
				<ul class="project_home_right_investment">
					<li class="project_home_right_title"><h2><a href="#" title="会议地点">历史投资考察会</a></h2></li>
					<?php foreach ($historyInvest as $k=>$v){?>
					<li>
						<span class="img_info">
							<a href="<?php echo urlbuilder::projectInvest($v['investment_id']);?>" target="_blank"><img width="122" height="96" src="<?php echo URL::imgurl($v['investment_logo']);?>"></a>
							<?php if(arr::get($v,'investment_apply')!=""){?><span class="img_info_text">报名人数<br/><em><?php if(arr::get($v,'investment_apply')<=0){ echo rand(50, 150);}else echo arr::get($v,'investment_apply')?></em>人</span><?php }?>
						</span>
						<font><a href="<?php echo urlbuilder::projectInvest($v['investment_id']);?>" target="_blank"><? echo mb_strlen($v['investment_name'])>16?Text::limit_chars($v['investment_name'],16):$v['investment_name'];?></a></font>
						<span>
							<i>时间：</i><?php echo date('Y/m/d',$v['investment_start'])?><br />
							<i>地址：</i><?php echo $v['investment_address']?>
						</span>
					</li>
					<?php }?>
				</ul>
				<?php }?>
			</div>
			<div class=" clear"></div>
			<!--右侧部分 END-->
		</div>
		<!--中间部分 END-->
<?php if(isset($user_type)&&$user_type==2){?>

    <?php }elseif(empty($user)){?>
    <?php echo URL::webjs("platform/login/plat_login.js")?>
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
<script type="text/javascript">
$(function(){
	var opacity = $("#opacity");
    var box = $("#send_box");
	$('#project_home_letter_open_company').click(function(){
		opacity.show();
		$('#msgcontent').html('<p class="errorbox">很抱歉，只能个人用户才能报名参会哦！</p>');
        box.slideDown(500);
	});
});
</script>
<!--参会邀请函弹出框 END-->

		