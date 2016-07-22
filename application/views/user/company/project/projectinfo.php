<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>
<?php echo URL::webjs("zhaos.js")?>
<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("my_business_infor.js")?>
<?php echo URL::webcss("renzheng.css"); ?>
 <script type="text/javascript">
   function viewImage1(_str){
      $("#imghead").attr('src', _str);
      $("#pro_log").attr("value",_str);
  }
  </script>
    <!--右侧开始-->
<style>
.ryl_mybussine_mess li b{ text-align:left; width:auto; padding-left:30px;}
</style>
    <div class="popupDelete" id="popupDelete">
        <div class="closeImg" id="closeImg"><img src="<?php echo URL::webstatic("images/infor2/closeimg.gif") ?>" width="20" height="20"/></div>
        <div class="clear"></div>
        <p class="errorPara">抱歉，您的项目基本信息还没有填写完整，一旦离开此页面将不会为您保存信息。确认离开吗？</p>
        <div class="confirm"><img src="<?php echo URL::webstatic("images/infor2/true.gif") ?>" width="112" height="32" id="sure"/><img src="<?php echo URL::webstatic("images/infor2/btn9.gif") ?>" width="112" height="32" id="cancel"></div>
    </div>
    <div class="opacityBg" id="opacityBg"></div>
    <div id="right">
        <div id="right_top"><span><a href="/company/member/project/showproject">项目管理</a></span><div class="clear"></div></div>
        <div class="ryl_add_project" style="padding-left:0px; width:745px;"><b><?=arr::get($forms, 'project_brand_name')?></b><a href="<?php echo URL::website("/company/member/project/addproject")?>">添加新项目</a></div>
        <div id="right_con">
            <div id="zhaos" class="my_business_post0513" style="padding:10px 0 0 0px;">
                <div class="nav">
                    <ul>
                        <li class="liCur"><span><a href="/company/member/project/projectinfo?project_id=<?=arr::get($forms, 'project_id')?>" >项目基本信息</a></span></li>
                        <li><span><a href="/company/member/project/addproimg?project_id=<?=arr::get($forms, 'project_id')?>">项目图片</a></span></li>
                        <li><span><a href="/company/member/project/addprocertsimg?project_id=<?=arr::get($forms, 'project_id')?>">项目资质图片</a></span></li>
                        <li><span><a href="/company/member/project/addposter?project_id=<?=arr::get($forms, 'project_id')?>">项目海报</a></span></li>
                        <li class="liLast"><span><a href="/company/member/project/viewProInvestment?project_id=<?=arr::get($forms, 'project_id')?>">我的投资考察会</a></span></li>
                    </ul>
                    <div class="clear"></div>
                </div>
                 <p class="ryl_bussin_proname" style="background:none; border-bottom:none; padding-right:15px;">
                   <span><?=arr::get($forms, 'investment_name')?></span>
                   <a href="<?php echo urlbuilder::project(arr::get($forms, 'project_id'));?>" class="preview_officialweb" style="padding-left:0;" target="_blank">预览项目官网</a>
                   <a href="/company/member/project/updateproject?project_id=<?=arr::get($forms, 'project_id')?>" class="modify_infor">修改信息</a>
                </p>
                   <ul class="ryl_mybussine_mess" style="padding-top:0;">
                    <li><b>品牌名称：</b><span><?=arr::get($forms, 'project_brand_name')?></span><div class="clear"></div></li>
                    <li><b>品牌logo：</b><span><img src="<?if($forms['project_source'] != 1) {$img =  project::conversionProjectImg($forms['project_source'], 'logo', $forms);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($forms['project_logo']);}?>" /></span><div class="clear"></div></li>
                    <li><b>项目广告图：</b><span><?php if(!empty($xuanchuan)){echo "<img height='120px'width='150px' src='".$xuanchuan."'";}else{echo "赞未上传项目广告图";}?></span></li>
                    <? if(arr::get($forms, 'project_brand_birthplace', 0)) {?><li><b>品牌发源地：</b><span><?=arr::get($forms, 'project_brand_birthplace')?></span><div class="clear"></div></li><?}?>
                    <li><b>所属行业：</b><span><?=$pro_industry?></span><div class="clear"></div></li>
                    <li><b>主营产品：</b><span><?=arr::get($forms, 'project_principal_products')?></span><div class="clear"></div></li>
                    <? if(arr::get($forms, 'projcet_founding_time', 0)) {?><li><b>成立时间：</b><span><?=arr::get($forms, 'projcet_founding_time')?></span><div class="clear"></div></li><?}?>
                    <li><b>投资金额：</b><span><?php $monarr= common::moneyArr(); echo arr::get($forms, 'project_amount_type') == 0 ? '无': $monarr[arr::get($forms, 'project_amount_type')];?></span><div class="clear"></div></li>
                    <li><b>加盟费用：</b><span><?=arr::get($forms, 'project_joining_fee')?>万</span><div class="clear"></div></li>
                    <? if(arr::get($forms, 'project_security_deposit', 0)) {?><li><b>保证金：</b><span><?=arr::get($forms, 'project_security_deposit', 0)?>万</span><div class="clear"></div></li><?}?>
                    <li><b>投资风险：</b><span><?php $list = guide::attr10(); foreach ($list as $k=>$v){ ?><?php if(arr::get($forms, 'risk') == $k) echo $v; ?><?}?></span><div class="clear"></div></li>
                    <li><b>投资回报率：</b><span><?php $list = guide::attr8(); foreach ($list as $k=>$v){ ?><?php if(arr::get($forms, 'rate_return') == $k) echo $v; ?><?}?></span><div class="clear"></div></li>
                    <li><b>适合人脉关系：</b><span><?$list = guide::attr5();foreach ($list as $k=>$v){ ?><?php if(isset($get_onnection[$k])){echo $v.'&nbsp;';}?><?}?></span><div class="clear"></div></li>
                    <li><b>项目标签：</b><span><?=arr::get($forms, 'project_tag')?></span><div class="clear"></div></li>
                    <li><b>投资人群：</b><span><?php
                    echo $group_text;

                ?></span><div class="clear"></div></li>
                    <li><b>招商地区：</b><span>
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

                   }else{echo $pro_area;}  ?></span><div class="clear"></div></li>
                    <li><b>招商形式：</b><span><?php $lst = common::businessForm();
                if(count($projectcomodel)){
                    $comodel_text='';
                    foreach ($projectcomodel as $v){
                        $comodel_text=$comodel_text.$lst[$v].',';
                    }
                    $xingshi= substr($comodel_text,0,-1);

                        echo $xingshi;

                } ?></span><div class="clear"></div></li>
                    <li><b>加盟时限：</b><span><?php $lst = guide::attr9();foreach ($lst as $k=>$v){?><?php if(arr::get($forms, 'time_limit') == $k) echo $v; ?><?}?></span><div class="clear"></div></li>
                    <li><b>座机号码：</b><span><?=arr::get($call, 0)?><?if(arr::get($call, 1, 0)) { echo "-".arr::get($call, 1, 0);}?></span><div class="clear"></div></li>
                   <?if(arr::get($forms, 'project_handset')){?> <li><b>手机号码：</b><span><?=arr::get($forms, 'project_handset')?></span><div class="clear"></div></li><?}?>
                   <li><b>联系人：</b><span><?=arr::get($forms, 'project_contact_people')?></span><div class="clear"></div></li>
                   <li><b>项目特点：</b><span><?=mb_strimwidth(arr::get($forms, 'product_features'), 0, 125, "......");?></span><div class="clear"></div></li>
                    <li><b>加盟条件：</b><span><?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($forms, 'project_join_conditions'), 0)));echo mb_strimwidth(strip_tags($string), 0, 125, "......");?></span><div class="clear"></div></li>
                    <li><b>项目简介：</b><span><?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($forms, 'project_summary'), 0)));echo mb_strimwidth(strip_tags($string), 0, 125, "......");?></span><div class="clear"></div></li>
                    </ul>

        </div>
    </div>
    <!--右侧结束-->
    <div class="clear"></div>
</div>
<!--透明背景开始-->
<div id="zhaos_opacity"></div>
<!--透明背景结束-->
<!--投资人群开始-->
<div id="zhaos_alert1">
    <h3>投资人群</h3>
    <a href="#" class="ensure"><img src="<?php echo URL::webstatic("images/zhaos/add_btn1_bg.png") ?>" /></a>
    <a href="#" class="cancel"><img src="<?php echo URL::webstatic("images/zhaos/add_btn2_bg.png") ?>" /></a>
    <div class="add_list">
        <p>您还没有添加投资人群标签哦！请从下方选择标签，或者自由填写哦！</p>
        <ul>

        </ul>
    </div>
    <div class="clear"></div>
    <div class="scroll">
        <span class="prev">prev</span>
        <span class="next">next</span>
        <div class="scroll_list">
            <div>
                 <?php foreach ($tag as $v): ?>
                <ul><?php foreach ($v as $val): ?><li  id="<?=$val['tag_id'];?>"><a href="#"><?=$val['tag_name'];?></a></li><?php endforeach; ?></ul>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="scroll_add">
        <span>自由标签输入框：</span><input type="button" placeholder="添加" class="add_btn" /><input type="text" placeholder="不可超过5个字" class="add_text" onFocus="scrollFocus(this)" onBlur="scrollBlur(this)"/>
       </div>
    <div class="clear"></div>
    <p class="tipadd"></p>
</div>
<!--投资人群结束-->
<!--招商地区开始-->
<div id="zhaos_alert2">
    <div id="zhaosArea_top">
    <a href="#" class="close">关闭</a>
        <div id="zhaosArea_center">
            <div id="province">
                <ul id="quanguo">
                    <li><span><b>全国</b></span></li>
                </ul>
                <ul id="liProvince">
                <?php if(count($forms['area']) > 0):?>
                <?php foreach ($areas as $k=>$v):?>
                <?php  if(isset($forms['area'][$v->cit_id]) && ($v->cit_id ==$forms['area'][$v->cit_id]['pro_id'])){?>
                  <li><span><?php echo $v->cit_name;?></span><input type="hidden" name="false" class="<?php echo $v->cit_id;?>" /></li>
                <?php  }else{?>
                <li><span><?php echo $v->cit_name;?></span><input type="hidden" name="true" class="<?php echo $v->cit_id;?>" /></li>
                <?php  }?>
                <?php endforeach;?>
                <?php  endif; ?>
                </ul>
                <div class="clear"></div>
            </div>
            <h3>请选择市：</h3>
            <div id="city" class="city1" >
                <ul></ul>
                <div class="clear"></div>
            </div>
            <h3>已选择地区：</h3>
            <div id="selectTown">
            <?php if(count($forms['area']) > 0): foreach ($forms['area'] as $k=>$v):?>
            <div class="list">
             <dl style="padding-bottom:7px;">
             <dt id="city_<?php echo $v['pro_id']?>"><?php echo $v['pro_name']?><img src="<?php echo URL::webstatic("images/zhaoshangAddress/closeBg.gif") ?>"/><input type="hidden" value="<?php echo $v['pro_id']?>" name="project_city[]"></dt>
             <dd>
              <?php if(isset($v['data'])): foreach ($v['data'] as $key=>$value): ?>
              <span id="city_<?php echo $value['area_id']?>"><?php echo $value['area_name']?><img src="<?php echo URL::webstatic("images/zhaoshangAddress/closeBg.gif") ?>"/><input type="hidden" value="<?php echo $value['area_id']?>" name="project_city[]"></span>
              <?php endforeach; endif; ?>
              </dd>
              </dl>
              </div>
              <?php endforeach; endif;?>
            </div>
            <div class="addressBtn">
                <a href="#" class="addressSave" id="addressSave"></a>
                <a href="#" class="addressCancel" id="addressCancel"></a>
            </div>
        </div>
    </div>
</div>
<div style="display:none" class="hiddenDiv"></div>
<div id="getcards_delete" style="display:none;">
    <a href="#" class="close">关闭</a>
    <div class="btn">
        <p>您还没有查看此项目，一旦删除，将无法取回。确定要删除此项目吗？</p>
        <p><a href="#" class="ensure" id="deleteProject"><img src="<?php echo URL::webstatic("images/getcards/ensure.png") ?>"/></a><a href="#" class="cancel"><img src="<?php echo URL::webstatic("images/getcards/cancel.png") ?>" /></a></p>
    </div>
</div>
<div id="getcards_opacity"></div>
<!--招商地区结束-->
<?php echo URL::webjs("zhaos_scroll.js")?>
<?php echo URL::webjs("zhaosArea.js")?>
<?php echo URL::webjs("common.source.js")?>
<?php echo URL::webjs("province.source.js")?>
<?php echo URL::webjs("deleteImg.js")?>
</script>








