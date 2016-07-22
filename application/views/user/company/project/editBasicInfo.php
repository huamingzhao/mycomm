<?php echo URL::webjs("My97DatePicker/testWdatePicker.js")?>
<?php //echo URL::webjs("zhaos.js")?>
<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php //echo URL::webjs("zhaos_scroll.js")?>
<?php echo URL::webjs("zhaosArea.js")?>
<?php echo URL::webjs("common.source.js")?>
<?php echo URL::webjs("province.source.js")?>
<?php echo URL::webjs("my_business_infor.js")?>
<?php echo URL::webcss("renzheng.css"); ?>
<?php echo URL::webcss("select_area.css")?>
<?php echo URL::webjs("zhaos_box.js")?>
<script type="text/javascript">
function viewImage1(_str){
    $("#imghead").css('display', 'block');
      $("#imghead").attr('src', _str);
      $("#pro_logo").attr('value',_str);
      $("#pro_logo").parent().parent().find("span.info").css("display", "none");
      $("#uploadImg").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='112' height='32' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/uploadhead_person.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/uploadhead_person.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='112' height='32' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
  }
</script>
<div class="right">
	<h2 class="user_right_title">
    	<span>我的项目</span>
    	<div class="clear"></div>
    </h2>
    <div class="my_business_new">
    <form id="publishForm" action="<?php echo URL::website('/company/member/project/updateBasicInfo'); ?>" method="post">
    	<input type="hidden" name="project_id" value="<?php echo arr::get($forms, 'project_id')?>" />
    	<div class="project_detial project_release">
    		<ul class="info">
                    <li class="title"> <b>项目基本信息</b>
                    </li>
                    <li class="label"> <font>*</font>品牌名称：</li>
                    <li class="content">
                        <input type="text" name = "project_brand_name" id="user" class="text long" maxlength="12" value="<?php echo arr::get($forms, 'project_brand_name');?>"/> <font>你要招商项目的品牌名称</font>
                        <input id="old_name" type="hidden" value="<?php echo arr::get($forms, 'project_brand_name')?>" />
                        <span class="info tishi_info">请填写产品名称</span>
                    </li>
                    <li class="label"><font>*</font>品牌logo：</li>
                    <li class="content">
                        <span id="imgd" class="upload_btn" style="width:120px;">
                            <input type='hidden' name='project_logo' value='<?php echo arr::get($forms, 'project_logo')?>' id='pro_logo'>
                            <img  style="border: 1px solid #CBCBCB;height: 110px;padding:1px; margin-bottom:10px; width: 120px;<?php if (empty($forms['project_logo'])){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($forms['project_logo']) ? URL::imgurl($forms['project_logo']) : '';?>" id="imghead" /></span>
                        <span class="infor1_modify upload_btn" style="width:112px;" >
                            <span class="uploadImg upload_btn"  id="uploadImg" style=" float:left; height:30px; padding-top:5px;">
                                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
                                    <param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_person.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" />
                                    <param name="quality" value="high" />
                                    <param name="wmode" value="transparent" />
                                    <param name="allowScriptAccess" value="always" />
                                    <embed src="<?php echo URL::webstatic('/flash/uploadhead_person.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash">
                                </embed>
                            </object>
                        </span>
                    </span>
                    <font style="line-height: 30px;">1M以内，尺寸150px*120px，了解品牌logo图用途，请查阅
                        <a class="zhaos_big_img" href="<?php echo URL::webstatic("images/my_business/replease_project_eg_2.png")?>" target="blank">示例</a></font> 
                    <span class="info">请上传LOGO图片</span>
                </li>
                <li class="label">
                    <font>*</font>品牌发源地：</li>
                <li class="content" style="z-index:10;">
                    <div class="ryl_search_label_right upload_btn" style=" display:inline-block; height:25px;">
                        <div class="ryl_search_result_jia_cont" style="z-index:9999;"></div>
                        <a id="choice_area" href="#" class="select_area_toggle" data-url="/ajaxcheck/getArea" first-result=".province_id" second-result=".per_area_id" box-title="省级" select-all="foreign">
                            	<?php  if(($place ? $place : $place2) != ""){echo $place ? $place : $place2;}else{ echo "请选择";}?>
                        </a>
                        <input type="hidden" name="province_id" class="province_id" value="<?php echo $province_id;?>"/>
                        <input type="hidden" name="per_area_id" class="per_area_id" value="<?php echo $per_area_id;?>"/>
                        <script>
                        </script>
                        <!--意向投资地区浮层-->
                    </div>
                    <font style=" ">项目品牌的诞生发展壮大的地区</font>
                    <span class="info">请选择品牌发源地</span>
                </li>
                <li class="label">品牌成立年份：</li>
                <li class="content">
                    <input type="text" class="text"  name="projcet_founding_time" onClick="WdatePicker({minDate:'1800-01-01'})" value="<?php if(isset($forms['projcet_founding_time']) && $forms['projcet_founding_time'] == 0){echo '';}else{ echo arr::get($forms, 'projcet_founding_time','');}?>"/>
                    <font>你所发布的项目品牌在哪一年诞生</font>
                </li>
                <li class="label"><font>*</font>所属行业：</li>
                <li class="content" style="z-index:9;">
                    <div class="ryl_search_label_right upload_btn" style=" display:inline-block; height:25px;">
                        <div class="ryl_search_result_jia_cont" style="z-index:9999;"></div>
                        <a id="choice_industry" href="#" class="select_area_toggle" data-url="/ajaxcheck/primaryIndustry" first-result=".industry_id1" second-result=".industry_id2" box-title="一级行业">
                           	 <?= $industry_name2 ? $industry_name2 : $industry_name1;?>
                        </a>
                        <input type="hidden" name="industry_id1" class="industry_id1" value="<?php echo $industry_id1;?>"/>
                        <input type="hidden" name="industry_id2" class="industry_id2" value="<?php echo $industry_id2;?>"/>
                        <!--意向投资地区浮层-->
                    </div>
                    <font style="">项目分类，投资者可以按照行业搜索到此项目</font>
                    <span class="info">请选择所属行业</span>
                </li>
                <li class="label"><font>*</font>所需投资金额：</li>
                <li class="content" id="need_money">
                    <input type="radio" name="need_money" class="need_money" id="need_money1" value="1" <?php if(arr::get($forms, 'project_amount_type') == 1){?>checked="checked""<?php }?>/>
                    <label for="need_money1" class="need_money">5万以下</label>
                    <input type="radio" name="need_money" class="need_money" id="need_money2" value="2" <?php if(arr::get($forms, 'project_amount_type') == 2){?>checked="checked""<?php }?>/>
                    <label for="need_money2" class="need_money">5-10万</label>
                    <input type="radio" name="need_money" class="need_money" id="need_money3" value="3" <?php if(arr::get($forms, 'project_amount_type') == 3){?>checked="checked""<?php }?>/>
                    <label for="need_money3" class="need_money">10-20万</label>
                    <input type="radio" name="need_money" class="need_money" id="need_money4" value="4" <?php if(arr::get($forms, 'project_amount_type') == 4){?>checked="checked""<?php }?>/>
                    <label for="need_money4" class="need_money">20-50万</label>
                    <input type="radio" name="need_money" class="need_money" id="need_money5" value="5" <?php if(arr::get($forms, 'project_amount_type') == 5){?>checked="checked""<?php }?>/>
                    <label for="need_money5" class="need_money">50万以上</label>
                    <input type="hidden" id="project_amount_type" name="project_amount_type" value="<?php echo arr::get($forms, 'project_amount_type');?>" />
                    <font>投资此项目所需的费用</font>
                    <span class="info">请选择所需投资金额</span>
                </li>
                <li class="label"><font>*</font>招商地区：</li>
                <li class="content">
                    <div class="list list_area">
                        <div class="list_li" id="diqu">
                        	<?php
                       			if(count($forms['area']) > 0){
                        		foreach ($forms['area'] as $k=>$v){
                          	?>
                     		<div class="list">
                         		<dl>
                          		<dt id="webcity_<?php echo $v['pro_id']?>"><?php echo $v['pro_name']?><img src="<?php echo URL::webstatic("images/zhaos/diqu_close.png") ?>"><input type="hidden" value="<?php echo $v['pro_id']?>" name="project_city[]"></dt>
                             	<dd>
                            	<?php
                              		if(isset($v['data'])){
                             		foreach ($v['data'] as $key=>$value){
                              	?>
                              	<span id="webcity_<?php echo $value['area_id']?>"><?php echo $value['area_name']?><img src="<?php echo URL::webstatic("images/zhaoshangAddress/closeBg.gif") ?>"/>
                            	<input type="hidden" value="<?php echo $value['area_id']?>" name="project_city[]"></span>
                              	<?php
                              		}
                               		}
                              	?>
                              	</dd>

                            	</dl>
                       		</div>
                        	<?php
                          		}
                       			}
                         	?>
                        </div>
                        <div class="clear"></div>
                        <p class="aa" style="width:101px;">
                        </p>
                        <span class="tipinfo" id="tipdiqu"></span>
                    </div>
                            <a href="#" class="add2_img_btn"  id="addressClickEffect" style="float:left; text-decoration:none;">
                        <button id="addImg" style="disply:none;">请选择</button>
                        <button id="addImg2">重新添加</button>
                            </a>
                    <font>项目在哪些地区举办招商活动</font>
                    <span class="info">请选择招商地区</span>
                </li>
                <li class="label"><font>*</font>招商形式：</li>
                <li class="content" id="service_type">
                    <input type="checkbox" name="project_co_model[]" id="canvass_type1" value="1" <?php if(in_array(1, $projectcomodel)){?>checked="checked"<?php }?>/>
                    <label for="canvass_type1">开店加盟</label>
                    <input type="checkbox" name="project_co_model[]" id="canvass_type2" value="2" <?php if(in_array(2, $projectcomodel)){?>checked="checked"<?php }?>/>
                    <label for="canvass_type2">批发代理</label>
                    <input type="checkbox" name="project_co_model[]" id="canvass_type3" value="3" <?php if(in_array(3, $projectcomodel)){?>checked="checked"<?php }?>/>
                    <label for="canvass_type3">网上销售</label>
                    <font>可以通过哪些方式投资此项目</font>
                    <span class="info">请选择招商形式</span>
                </li>
                <li class="label"><font>*</font>适合人群：</li>
                <li class="content" id="suit_crowd">
                    <div class="list_li zhaos_result">
                        <ul>
                        	<?php
                           		if(count($forms['project_investment_groups']) > 0):
                          		foreach ($forms['project_investment_groups'] as $val):
                           	?>
                          	<li id="<?=$val['tag_id'];?>" title="<?=$val['tag_name'];?>">
                          	<?=$val['tag_name'];?>
                       			<input type="hidden" value="<?=$val['tag_id'];?>" name="Investment_groups[]">
                               	<span class="testspan">关闭</span>
                           	</li>
                          	<?php
                          		endforeach;
                          		endif;
                          	?>
                           	<?php
                          		if(count($forms['project_groups_label']) > 0 && $forms['project_groups_label']):
                          		foreach ($forms['project_groups_label'] as $k => $val):
                         	?>
                         	<li id="<?=$k+100;?>" title="<?=$val;?>">
                        		<?=$val;?>
                          		<input type="hidden" value="<?=$val;?>" name="label[]">
                         		<span class="testspan">关闭</span>
                       		</li>
                         	<?php
                        		endforeach;
                           		endif;
                          	?>
                            <div class="clear"></div>
                        </ul>
                    </div>
                    <button id="crowd_btn">请选择</button>
                    <button id="crowd_btn2" style="disply:none;">重新添加</button>
                    <font>哪些类型的人群比较适合投资此项目</font>
                    <span class="info">请选择适合人群</span>
                </li>
                <li class="clear"></li>
            </ul>
            <ul class="info">
            	<li class="content">
                <a href="javascript:;" class="red sent1" id="tijiao" onclick='document.cookie = "user_click_kouqian = 0";'/>提交</a>
              </li>
            	<li class="clear"></li>
            </ul>
    	</div>
    </form>
    </div>    
</div>
<!--透明背景开始-->
<div id="zhaos_opacity"></div>
<!--透明背景结束-->
<!--投资人群开始-->
<?php echo URL::webjs("zhaos_box.js")?>
<script type="text/javascript">
$(document).ready(function(){
<?php $conent =""; foreach ($tag as $val){
    foreach ($val as $v){
        $conent .='"'.$v['tag_id'].'"'.':'.'"'.$v['tag_name'].'",';
    }
    
    } $content = substr($conent,0,-1);?>
  var  str_data ={<?php echo $content;?>};
new zhaos_box("#crowd_btn", str_data);
init_big_img();
});
</script>
<!--投资人群结束-->
<!--招商地区开始-->
<div id="zhaos_alert2" class="zhaos_area">
<div id="zhaosArea_top">
    <h3>投资人群</h3>
<a href="#" class="close">关闭</a>
<div id="zhaosArea_center">
<div id="province">
<ul id="quanguo">
    <li>
        <span>
            <b>全国</b>
        </span>
    </li>
</ul>
<ul id="liProvince">
    <?php foreach ($areas as $k=>$v):?>
    <li>
        <span>
            <?php echo $v->cit_name;?></span>
        <input type="hidden" name="true" class="<?php echo $v->cit_id;?>" /></li>
    <?php endforeach;?></ul>
<div class="clear"></div>
</div>
<h3>请选择市：</h3>
<div id="city" class="city1" style="display:block">
<ul></ul>
<div class="clear"></div>
</div>
<h3>已选择地区：</h3>
<div id="selectTown"></div>
<div class="addressBtn">
<a href="javascript:void(0)" class="addressSave" id="addressSave">确定</a>
<a href="javascript:void(0)" class="addressCancel" id="addressCancel">取消</a>
</div>
</div>
</div>
</div>
<div style="display:none" class="hiddenDiv"></div>
<div class="comPopup" id="comPopup">
<dl>
<dt></dt>
<dd class="first">
<p>您还没有填写企业基本信息，填写之后您的项目才可以成功发布哟！</p>
<p>
5秒之后自动跳转至
<a href="<?php echo URL::website('/company/member/basic/company')?>">“填写企业基本信息”</a>
</p>
</dd>
<dd class="second"></dd>
</dl>
</div>
<div id="refresh"></div>
<!--招商地区结束-->
<script>


$(document).ready(function(){
	$(".need_money").click(function(){
		$("#project_amount_type").val($(".need_money:checked").val());
	});
});
</script>
