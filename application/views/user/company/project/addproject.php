<?php echo URL::webjs("My97DatePicker/testWdatePicker.js")?>
<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("zhaosArea.js")?>
<?php echo URL::webjs("common.source.js")?>
<?php echo URL::webjs("province.source.js")?>
<?php echo URL::webjs("my_business_infor.js")?>
<?php echo URL::webcss("renzheng.css"); ?>
<?php echo URL::webcss("select_area.css")?>
<?php echo URL::webjs("zhaos_box.js")?>
<script type="text/javascript">
//广告logo
function viewImage1(_str){
 $("#imghead").css('display', 'block');
   $("#imghead").attr('src', _str);
   $("#pro_logo").attr('value',_str);
   $("#uploadImg").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/com/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/com/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='112' height='32' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
}
//广告大图
function viewImage2(_str){
	    $("#imghead1").css('display', 'block');
	      $("#imghead1").attr('src', _str);
	      $("#pro_logo1").attr('value',_str);
	      $("#uploadImg1").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='112' height='32' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
	  }
//广告小图
function viewImage3(_str){
    $("#imghead2").css('display', 'block');
      $("#imghead2").attr('src', _str);
      $("#pro_logo2").attr('value',_str);
      $("#uploadImg2").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='100' height='26' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
  }
  </script>
<style>#publishForm select{ width:120px;}</style>
<!--右侧开始-->
<div class="popupDelete" id="popupDelete">
    <div class="closeImg" id="closeImg">
        <img src="<?php echo URL::webstatic("images/infor2/closeimg.gif") ?>" width="20" height="20"/></div>
    <div class="clear"></div>
    <p class="errorPara">抱歉，您的项目基本信息还没有填写完整，您还不能离开此页面！</p>
    <div class="confirm">
        <img src="<?php echo URL::webstatic("images/infor2/true.gif") ?>" width="112" height="32" id="sure"/></div>
</div>
<div class="opacityBg" id="opacityBg"></div>
<div class="right">
    <h2 class="user_right_title">
        <span>新建项目</span>
        <span class="title_other_link">发布项目详情请查阅<a href="/company/member/account/platformAccountAbout" target="_blank">资费说明</a></span>
        <div class="clear"></div>
    </h2>
    <div class="my_business_new">
        <div class="project_detial project_release">
            <form id="publishForm" method="post" action="<?php echo URL::website('/company/member/project/addproject')?>
                " enctype="multipart/form-data">
                <ul class="info">
                    <li class="title"> <b>项目基本信息</b>
                    </li>
                    <li class="label"> <font>*</font>品牌名称：</li>
                    <li class="content">
                        <input type="text" name = "project_brand_name" id="user" class="text long" maxlength="12"/> <font>你要招商项目的品牌名称</font>
                        <span class="info tishi_info">请填写品牌名称</span>
                    </li>
                    <li class="label"><font>*</font>品牌logo：</li>
                    <li class="content">
                      <span id="imgd floleft" style="width:120px;">
                           <input type='hidden' name='project_logo' value='' id='pro_logo'>
                           <img  style="border: 1px solid #CBCBCB;float: left;height: 110px;padding:1px;width: 120px;<?php if (empty($forms['project_logo'])){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($forms['project_logo']) ? URL::imgurl($forms['project_logo']) : '';?>" id="imghead" />
                       </span>
                       
                       <span class="infor1_modify floleft" style="padding:5px 10px 0 10px;width:112px;" >
                                <span class="uploadImg" id="uploadImg" style="width:112px; height:32px; display:inline;">
                                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
                                <param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_project.swf?url='.URL::website('/cms/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" />
                                <param name="quality" value="high" />
                                <param name="wmode" value="transparent" />
                                <param name="allowScriptAccess" value="always" />
                                <embed src="<?php echo URL::webstatic('/flash/uploadhead_project.swf?url='.URL::website('/cms/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                                </object>
                                </span>
                       </span>
                    <font class="floleft" style="line-height:35px;">1M以内，尺寸150px*120px，了解品牌logo图用途，请查阅
                        <a class="zhaos_big_img" href="<?php echo URL::webstatic("images/my_business/replease_project_eg_2.png")?>" target="blank">示例</a></font> 
                    <span class="info">请上传LOGO图片</span>
                </li>
                <li class="label">
                    <font>*</font>品牌发源地：</li>
                <li class="content" style="z-index:10;">
                    <div class="ryl_search_label_right upload_btn" style=" display:inline-block; height:25px;">
                        <div class="ryl_search_result_jia_cont" style="z-index:9999;"></div>
                        <a id="choice_area" href="#" class="select_area_toggle" data-url="/ajaxcheck/getArea" first-result=".province_id" second-result=".per_area_id" box-title="省级" select-all="foreign">
                            请选择
                        </a>
                        <input type="hidden" name="province_id" class="province_id"/>
                        <input type="hidden" name="per_area_id" class="per_area_id"/>
                        <!--意向投资地区浮层-->
                    </div>
                    <font style=" ">项目品牌的诞生发展壮大的地区</font>
                    <span class="info">请选择品牌发源地</span>
                </li>
                <li class="label">品牌成立年份：</li>
                <li class="content">
                    <input type="text" class="text"  name="projcet_founding_time" onClick="WdatePicker({minDate:'1800-01-01'})"/>
                    <font>你所发布的项目品牌在哪一年诞生</font>
                </li>
                <li class="label"><font>*</font>所属行业：</li>
                <li class="content" style="z-index:9;">
                    <div class="ryl_search_label_right upload_btn" style=" display:inline-block; height:25px;">
                        <div class="ryl_search_result_jia_cont" style="z-index:9999;"></div>
                        <a id="choice_industry" href="#" class="select_area_toggle" data-url="/ajaxcheck/primaryIndustry" first-result=".industry_id1" second-result=".industry_id2" box-title="一级行业">
                            请选择
                        </a>
                        <input type="hidden" name="industry_id1" class="industry_id1"/>
                        <input type="hidden" name="industry_id2" class="industry_id2"/>
                        <!--意向投资地区浮层-->
                    </div>
                    <font style=" ">项目分类，投资者可以按照行业搜索到此项目</font>
                    <span class="info">请选择所属行业</span>
                </li>
                <li class="label"><font>*</font>所需投资金额：</li>
                <li class="content" id="need_money">
                    <input type="radio" name="project_amount_type" id="need_money1" value="1"/>
                    <label for="need_money1">5万以下</label>
                    <input type="radio" name="project_amount_type" id="need_money2" value="2"/>
                    <label for="need_money2">5-10万</label>
                    <input type="radio" name="project_amount_type" id="need_money3" value="3"/>
                    <label for="need_money3">10-20万</label>
                    <input type="radio" name="project_amount_type" id="need_money4" value="4"/>
                    <label for="need_money4">20-50万</label>
                    <input type="radio" name="project_amount_type" id="need_money5" value="5"/>
                    <label for="need_money5">50万以上</label>
                    <font>投资此项目所需的费用</font>
                    <span class="info">请选择所需投资金额</span>
                </li>
                <li class="label"><font>*</font>招商地区：</li>
                <li class="content">
                    <div class="list list_area">
                        <div class="list_li" id="diqu"></div>
                        <div class="clear"></div>
                        <p class="aa" style="width:101px;">
                        </p>
                        <span class="tipinfo" id="tipdiqu"></span>
                    </div>
                            <a href="#" class="add2_img_btn" data-tag="#select_area_box" data-title="选择招商地区" data-width="748" id="addressClickEffect" style="float:left; text-decoration:none;">
                        <button id="addImg">请选择</button>
                        <button id="addImg2" style="disply:none;">重新添加</button>
                            </a>
                    <font>项目在哪些地区举办招商活动</font>
                    <span class="info">请选择招商地区</span>
                </li>
                <li class="label"><font>*</font>招商形式：</li>
                <li class="content" id="service_type">
                    <input type="checkbox" name="project_co_model[]" id="canvass_type1" value="1"/>
                    <label for="canvass_type1">开店加盟</label>
                    <input type="checkbox" name="project_co_model[]" id="canvass_type2" value="2"/>
                    <label for="canvass_type2">批发代理</label>
                    <input type="checkbox" name="project_co_model[]" id="canvass_type3" value="3"/>
                    <label for="canvass_type3">网上销售</label>
                    <font>可以通过哪些方式投资此项目</font>
                    <span class="info">请选择招商形式</span>
                </li>
                <li class="label"><font>*</font>适合人群：</li>
                <li class="content" id="suit_crowd">
                    <div class="list_li zhaos_result">
                        <ul></ul>
                    </div>
                    <button id="crowd_btn">请选择</button>
                    <button id="crowd_btn2" style="disply:none;">重新添加</button>
                    <font>哪些类型的人群比较适合投资此项目</font>
                    <span class="info">请选择适合人群</span>
                </li>
                <li class="clear"></li>
            </ul>
            <ul class="info">
                <li class="title"> <b>项目推广信息</b>
                </li>
                <li class="label">项目推广广告语：</li>
                <li class="content">
                    <input type="text" name="project_advert" maxlength="15" id="project_advert"/>
                    <font>项目宣传语，最多15个汉字字符。示例：送长辈 黄金酒</font>
                    <span class="info info2">请填写品牌名称</span>
                </li>
                <li class="label">项目广告大图：</li>
                <li class="content two_lines">
                     <span id="imgd1" style="width:120px;">
                             <input type='hidden' name='project_xuanchuan_da_logo' value='' id='pro_logo1'>
                             <img  style="border: 1px solid #CBCBCB;float: left;height: 110px;padding:1px;width: 120px;<?php if (empty($forms['project_logo'])){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($forms['project_logo']) ? URL::imgurl($forms['project_logo']) : '';?>" id="imghead1" />
                     </span>
                    <span class="infor1_modify" style="padding:5px 10px 0 10px;width:112px;float:left;" >
                                <span class="uploadImg" id="uploadImg1" style=" width:112px; height:32px; display:inline;">
                                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
                                <param name="movie" value="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>" />
                                <param name="quality" value="high" />
                                <param name="wmode" value="transparent" />
                                <param name="allowScriptAccess" value="always" />
                                <embed src="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                                </object>
                                </span>
                     </span>
                <font>用于项目官网宣传展示产品特点，2M以内，尺寸705px*142px，了解项目广告大图用途，请查阅
                    <a class="zhaos_big_img" href="<?php echo URL::webstatic('/images/my_business/replease_project_eg_1.png') ?>" target="blank">示例</a></font> 
            </li>
            <li class="label">项目广告小图：</li>
            <li class="content two_lines">
                <span id="imgd_xiao" style="width:120px;">
                   <input type='hidden' name='project_xuanchuan_xiao_logo' value='' id='pro_logo2'>
                   <img  style="border: 1px solid #CBCBCB;float: left;height: 110px;padding:1px;width: 120px;<?php if (empty($forms['project_logo'])){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($forms['project_logo']) ? URL::imgurl($forms['project_logo']) : '';?>" id="imghead2" />
                </span>
                <span class="infor1_modify" style="padding:5px 10px 0 10px;width:112px;float:left;" >
                                <span class="uploadImg" id="uploadImg2" style=" width:112px; height:32px; display:inline;">
                                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="100" height="26" id="flashrek2">
                                <param name="movie" value="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>" />
                                <param name="quality" value="high" />
                                <param name="wmode" value="transparent" />
                                <param name="allowScriptAccess" value="always" />
                                <embed src="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                                </object>
                                </span>
                </span>
            <font>吸引投资者去查看你所发布的项目，1M以内，尺寸120px*95px，了解项目广告小图用途，请查阅
                <a class="zhaos_big_img" href="<?php echo URL::webstatic('/images/my_business/replease_project_eg_3.png') ?>" target="blank">示例</a>
            </font>
        </li>
        <li class="label">
            <font>*</font>项目详情介绍：</li>
        <li class="content height_auto" id="project_detial">
            <?php echo  Editor::factory(isset($forms['project_summary']) ? $forms['project_summary'] : '',"nobar",array("field_name"=>'project_summary',"width"=>"580","height"=>"200"));?>
            <font>介绍此项目的起源、特点、发展历程、优势、能给投资者带来怎样的收益等</font>
            <span class="info">请填写项目详情介绍</span>
        </li>
        <li class="label">项目标签：</li>
        <li class="content">
            <input type="text" id="project_release_tag" class="text2"  name="project_tag"/>
        </li>
        <li class="label">常用标签：</li>
        <li class="content project_release_tag_normal">
            <?php foreach ($ProjectTag  as $key=>$val){?>
            <a href="javascript:void(0)"><?php echo $val['tag_name']?></a><?php }?></li>
        <li class="clear"></li>
    </ul>
    <ul class="info info_contant">
        <li class="title">
            <b>项目联系人信息</b>
        </li>
        <li class="label"><font>*</font> 姓名：</li>
        <li class="content">
            <input type="text" name="project_contact_people" id="contactPeople"/>
            <span class="info">请填写姓名</span>
        </li>
        <li class="label label2">职位：</li>
        <li class="content">
            <input type="text" name="project_position" id="project_position"/>
            <font>联系人在公司所担任的职务</font>
        </li>
        <li class="label"><font>*</font>手机号码：</li>
        <li class="content content2">
            <input type="text" name="project_handset" id="handSet" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")' maxlength="11"/>
            <font>方便投资者和我们随时与您取得联系</font>
            <span class="info">手机号码不能为空</span>
        </li>
        <li class="label">公司座机号码：</li>
        <li class="content">
            <input type="text" name="project_phone" id="tel" maxlength="15" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();"/>
        </li>
        <li class="label label2">转：</li>
        <li class="content">
            <input type="text"  name="phone_fj" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")' maxlength="5"/>
        </li>
        <li class="clear"></li>
    </ul>
    <div id="more_info_open"><a href="javascript:void(0)">添加更多项目详情</a></div>
    <ul class="info" id="info_more" style=" display:none;">
        <li class="title">
            <b>更多项目详情信息</b>
            <a href="#">收起</a>
        </li>
        <li class="label">主营产品：</li>
        <li class="content">
            <input type="text" name="project_principal_products" maxlength = "20" id="pro">
            <font>贵公司所经营的主要产品（名称）</font>
        </li>
        <li class="label">加盟费：</li>
        <li class="content">
            <input class="text3" type="text" name="project_joining_fee" id="jiam" maxlength="6" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")'>万元
            <font>投资者加盟此项目所需的费用</font>
        </li>
        <li class="label">保证金：</li>
        <li class="content">
            <input class="text3" type="text"  maxlength="6" name="project_security_deposit" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")'>万元
            <font>投资者加盟此项目所需缴纳的诚信保证金，后期退换</font>
        </li>
        <li class="label">年投资回报率：</li>
        <li class="content">
            <input type="radio" name="rate_return" id="need_money1" value="1" >
            <label for="need_money1">10%以下</label>
            <input type="radio" name="rate_return" id="need_money2" value="2">
            <label for="need_money2">10%-50%</label>
            <input type="radio" name="rate_return" id="need_money3" value="3">
            <label for="need_money3">50%-100%</label>
            <input type="radio" name="rate_return" id="need_money4" value="4">
            <label for="need_money4">100%以上</label>
            <font>投资与收获的比率，请如实填写</font>
        </li>
        <li class="label">需要的人脉关系：</li>
        <li class="content">
            <input type="checkbox" name="connection[]" id="canvass_type1" value="1">
            <label for="canvass_type1">有政府关系</label>
            <input type="checkbox" name="connection[]" id="canvass_type2" value="2">
            <label for="canvass_type2">有学校关系</label>
            <input type="checkbox" name="connection[]" id="canvass_type3" value="3">
            <label for="canvass_type3">有医疗关系</label>
            <input type="checkbox" name="connection[]" id="canvass_type3" value="4">
            <label for="canvass_type3">有团购关系</label>
            <input type="checkbox" name="connection[]" id="canvass_type3" value="5">
            <label for="canvass_type3">有企事业单位关系</label>
            <input type="checkbox" name="connection[]" id="canvass_type3" value="6">
            <label for="canvass_type3">无人脉关系</label>
        </li>
        <li class="label">产品特点：</li>
        <li class="content height_auto">
            <textarea name="product_features"></textarea>
            <font>详细介绍此产品的起源、制作工艺、特点、功能等</font>
        </li>
        <li class="label">加盟详情：</li>
        <li class="content height_auto">
            <?php echo  Editor::factory(isset($forms['project_join_conditions']) ? $forms['project_join_conditions'] : '',"nobars",array("field_name"=>'project_join_conditions',"width"=>"580","height"=>"200"));?>
            <font>说明加盟此项目所需要的条件，包括资金、地区、加盟方式等</font>
        </li>
        <li class="clear"></li>
    </ul>
    <ul class="info">
        <li class="content">
            <a href="javascript:;" class="red sent" id="tijiao" onclick='document.cookie = "user_click_kouqian = 0";'/>发布项目</a>
        </li>
        <li class="clear"></li>
    </ul>
</form>
</div>
</div>
</div>
<div class="clear"></div>
</div>
<!--透明背景开始-->
<div id="zhaos_opacity"></div>
<!--透明背景结束-->
<!--投资人群开始-->

<script type="text/javascript">
   function viewImage1(_str){
    $("#imghead").css('display', 'block');
      $("#imghead").attr('src', _str);
      $("#pro_logo").attr('value',_str);
      $("#pro_logo").parent().parent().find("span.info").css("display", "none");
      
      $("#uploadImg").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='100' height='26' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
  }
   //广告大图
   function viewImage2(_str){
        $("#imghead1").css('display', 'block');
          $("#imghead1").attr('src', _str);
          $("#pro_logo1").attr('value',_str);
          $("#uploadImg1").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='100' height='26' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
      }
    //广告小图
   function viewImage3(_str){
        $("#imghead2").css('display', 'block');
          $("#imghead2").attr('src', _str);
          $("#pro_logo2").attr('value',_str);
          $("#uploadImg2").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='100' height='26' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/uploadhead_project_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage3')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='100' height='26' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
      }
  </script>
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
<script type="text/javascript">
    var select_area_list = '<?php foreach ($areas as $k=> $v):?><li><span><?php echo $v->cit_name;?></span><input type="hidden" name="true" class="<?php echo $v->cit_id;?>" /></li><?php endforeach;?>';
</script>
<div id="select_area_box">
<div id="select_area_div" class="zhaos_area">
    <div id="zhaosArea_top">
        <div id="zhaosArea_center">
            <div id="province">
                <ul id="quanguo">
                    <li>
                        <span> <b>全国</b>
                        </span>
                    </li>
                </ul>
                <ul id="liProvince">
                    <?php foreach ($areas as $k=> $v):?>
                    <li><span><?php echo $v->cit_name;?></span><input type="hidden" name="true" class="<?php echo $v->cit_id;?>" /></li>
                    <?php endforeach;?>
                </ul>
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
<?php  $num = -1; if((arr::get($projectMoney, "count") >= 1) && (arr::get($projectMoney, "code") == 1)){
	$num = 1;
}elseif (arr::get($projectMoney, "code") == 2){
	$num = 2;
}?>
<?php if($user_account['is_forbid'] == true && ($num == 1 || $num == 2)){?>
	<div id="getcards_opacity" style="display:block;" ></div>
	<div id="project_err_2" class="message_box" style="display:block; margin-top:-115px; ">
    <dl>
        <dt>
            <font>生意街提示您</font>
            <a href="javascript:void(0)" title="关闭" onclick="$('#project_err_2').hide(500);$('#getcards_opacity').hide(500);" class="ok"></a>
        </dt>
        <dd>
            <p style="font-family:'微软雅黑'; font-size: 22px; color: #666;">您的账户因<?php echo arr::get($user_account, "forbid_content","出现异常");?>，已被禁用！</p>
            <p style="font-size: 14px;padding:0 70px 70px;">如用疑问，请联系客服：<span style="color:#ea0602;">400 1015 908</span></p>
        </dd>
    </dl>
</div>
	
<?php }else{ ?>
<div id="getcards_opacity" style="display:<?= ($projectMoney['code'] == 1 || ($projectMoney['code'] == 2 && !$user_click_kouqian)) ? 'block' : 'none';?>" >
<a href="/company/member/project/showproject" class="close">关闭</a></div>
<!-- 申请招商通弹框 -->

<div id="user_apply_zst" class="message_box" style="display:<?= ($projectMoney['code'] == 1 ) ? 'block' : 'none';?>; margin-top:-115px; ">
    <dl>
        <dt>
            <font>生意街提示您</font>
            <a class="close" href="/company/member/project/showproject" title="关闭"></a>
        </dt>
        <dd>
            <p>普通企业用户可免费发布<i class="red">1</i>个项目，如您需要发更多项目，请申请招商通服务（详情请查阅<a class="blue" href="/company/member/account/platformAccountAbout" target="blank">资费说明</a>，或咨询<font class="red">400 1015 908</font>） 。</p>
        </dd>
        <dd class="btn">
            <a href="/company/member/account/applyPlatformServiceFee" class="ok">申请招商通服务</a>
            <a href="/company/member/project/showproject" class="cancel">以后再说</a>
        </dd>
    </dl>
</div>
<!-- 申请招商通弹框 END -->
<!-- 继续发布弹框 -->
<div id="project_err_2" class="message_box" style="display:<?= ($projectMoney['code'] == 2 && !$user_click_kouqian) ? 'block' : 'none';?>; margin-top:-115px; ">
    <dl>
        <dt>
            <font>生意街提示您</font>
            <a class="close" href="/company/member/project/showproject" title="关闭"></a>
        </dt>
        <dd>
            <p>对不起，截至目前，您已发布<i class="red"><?=$projectMoney['count']?></i>个项目，免费发布项目个数已达到限值，如您需要发更多项目，请查阅<a class="blue" href="/company/member/account/platformAccountAbout" target="_blank">资费说明</a>，或咨询<br/>
<font class="red">400 1015 908</font>。</p>
        </dd>
        <dd class="btn">
            <a href="javascript:void(0)" onclick="$('#project_err_2').hide();$('#project_cz').show();" class="ok">继续发布</a>
            <a href="/company/member/account/accountindex" class="cancel">去续费</a>
        </dd>
    </dl>
</div>
<!-- 继续发布弹框 END -->
<!-- 温馨提示 -->
<div id="project_cz" class="message_box" style=" margin-top:-115px; ">
    <dl>
        <dt>
            <font>生意街提示您</font>
            <a class="close" href="/company/member/project/showproject" title="关闭"></a>
        </dt>
        <dd>
            <p>免费发布项目个数达到限值，项目发布后，将会从你的账户里直接扣除<font class="red">500</font>元，是否确认此操作？</p>
        </dd>
        <dd class="btn">
            <a href="javascript:void(0)" id="kouqian" tel="<?=$account;?>" class="ok">确定</a>
            <a href="/company/member/project/showproject" class="cancel">取消</a>
        </dd>
    </dl>
</div>

<div id="project_cz_err" class="message_box" style=" margin-top:-115px; ">
    <dl>
        <dt>
            <font>生意街提示您</font>
            <a class="close" href="/company/member/project/showproject" title="关闭"></a>
        </dt>
        <dd>
            <p>很抱歉，您的账户余额少于<font class="red">500</font>元。如您需要发更多项目，您可以去<a class="blue" href="/company/member/account/accountindex">充值</a>。</p>
        </dd>
        <dd class="btn">
            <a href="/company/member/account/accountindex" tel="<?=$account;?>" class="ok">去充值</a>
            <a href="/company/member/project/showproject" class="cancel">取消</a>
        </dd>
    </dl>
</div>
<?php }?>
<script type="text/javascript">
$(function(){
    <?php $comid = isset($com_id) ? $com_id : "";?>
    var com_id ="<?php echo $comid;?>";
    if(com_id){
       $("#zhaos_opacity").show();
        $("#comPopup").slideDown(500,function(){
            setTimeout(function(){
                window.location = "/company/member/basic/company";
            },5000)
        })
    }
})
$("#kouqian").click(function() {
    var money = $(this).attr('tel');
    if(money >= 500) {
        $('#project_cz').hide();
        $('#getcards_opacity').hide();
        document.cookie = "user_click_kouqian = 1";
    }else{
        $('#project_cz').hide();
        $('#project_cz_err').show();
    }
})
//标签添加
$("#project_release_tag").focus(function(){
		//获取名称
		var project_brand_name = $.trim($("#user").val());
		//招商形式
		 var str ="";
		   $("[name = project_co_model[]]:checkbox:checked").each(function(){
		          str += $(this).val()+"";
		     });
		   var strs = "";
		   for(var i=0;i<str.length;i++){
				if(str[i] == parseInt(1)){
				  strs = "开店加盟";
				}else if(str[i] == parseInt(2)){
					if(strs == ""){
						strs = "批发代理";
					}else{
						strs +=" "+"批发代理";
					}
				}else if(str[i] == parseInt(3)){
					if(strs == ""){
						strs = "网上销售";
					}else{
						strs +=" "+"网上销售";
					}
				}
			}
			var content = project_brand_name +" "+ strs;
            if($("#project_release_tag").val()==""){
                $("#project_release_tag").val($.trim(content));
            }else{
                return false;
            }
			
	});
</script>