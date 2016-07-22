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
   function viewImage2(_str){
	    $("#imghead1").css('display', 'block');
	      $("#imghead1").attr('src', _str);
	      $("#pro_logo1").attr('value',_str);
	      $("#uploadImg1").html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0' name='fileId4' width='112' height='32' id='flashrek2'><param name='movie' value='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><param name='allowScriptAccess' value='always' /><embed src='<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>' allowscriptaccess='always' id='fileId4' wmode='transparent' width='112' height='32' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'> </embed></object>");
	  }
  </script>
    <!--右侧开始-->
    <div class="popupDelete" id="popupDelete">
        <div class="closeImg" id="closeImg"><img src="<?php echo URL::webstatic("images/infor2/closeimg.gif") ?>" width="20" height="20"/></div>
        <div class="clear"></div>
        <p class="errorPara">抱歉，您的项目基本信息还没有填写完整，一旦离开此页面将不会为您保存信息。确认离开吗？</p>
        <div class="confirm"><img src="<?php echo URL::webstatic("images/infor2/true.gif") ?>" width="112" height="32" id="sure"/><img src="<?php echo URL::webstatic("images/infor2/btn9.gif") ?>" width="112" height="32" id="cancel"></div>
    </div>
    <div class="opacityBg" id="opacityBg"></div>
    <div id="right">
        <div id="right_top"><span><a href="/company/member/project/showproject">项目管理</a></span><div class="clear"></div></div>
        <div class="ryl_add_project" style="padding-left:0px; width:745px;"><b><?=arr::get($forms, 'project_brand_name')?></b></div>
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

                <div id="zhaos_center">
                    <form id="publishForm" class="publishForm" method="post" action="<?php echo URL::website("/company/member/project/updateproject")?>" enctype="multipart/form-data">
                    <input type="hidden" name="project_id" id="project_id" value="<?=arr::get($forms, 'project_id')?>" />
                    <input type="hidden"  id="type" value="<?=$type?>" />
                        <p>
                            <span><em>*</em>品牌名称：</span><input type="text" name="project_brand_name" class="text"  id="user" value="<?php if($type == 1){ echo arr::get($forms, 'project_brand_name');}else{echo $project_name;}?>" maxlength="15"/>
                            <span class="tipinfo" id="tishiuser">
                            <?=isset($error['project_brand_name']) ? $error['project_brand_name'] : '';?>
                            </span>
                            <input type="hidden"  id="old_name" name="old_name" value="<?=arr::get($forms, 'project_brand_name')?>" />
                        </p>
                        <p class="tishi" style="_height:125px;*height:125px;">
                             <span><em>*</em>品牌logo：</span>
                             <span id="imgd" style="width:150px;height:120px;border: 1px solid #CBCBCB;margin: 0 auto;display:block; " ><input type='hidden' name='project_logo' value='<?php echo URL::imgurl($forms['project_logo']);?>' id='pro_log'><img  style="width:150px;height:120px;margin: 0 auto;display:block;padding: 1px;<?php if (!isset($forms['project_logo'])){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($forms['project_logo']) ? ($forms['project_source'] != 1 ? project::conversionProjectImg($forms['project_source'], 'logo', $forms) : URL::imgurl($forms['project_logo'])) : '';?>" id="imghead" /></span>
                            <span class="infor1_modify" style="padding-left:19px;width:112px;">
                            <span class="uploadImg" id="uploadImg" style="width:112px; height:32px;">
                            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="112" height="32" id="flashrek2">
                            <param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_person.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" />
                            <param name="quality" value="high" />
                            <param name="wmode" value="transparent" />
                            <param name="allowScriptAccess" value="always" />
                            <embed src="<?php echo URL::webstatic('/flash/uploadhead_person.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage1')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                            </object>
                            </span>
                            <span class="tipinfo" id="tipfile">
                            <?=isset($error['project_logo']) ? $error['project_logo'] : '';?>
                            </span>
                            </span>
                            <input type="hidden" name="project_logo_old" id="project_logo_old" value="<?php echo URL::imgurl(arr::get($forms, 'project_logo'));?>" />
                        </p>
 							<p class="tishi" style="padding-bottom:5px; height:114px;">
                            <span>项目广告图：</span>
                            <span id="imgd1" style=" width: 150px">
                             <input type='hidden' name='project_xuanchuan_logo' value='' id='pro_logo1'>
                             <img  style="border: 1px solid #CBCBCB;float: left;height: 110px;padding:1px;width: 148px;<?php if (empty($forms['project_xuanchuan_logo'])){?>display:none<?php }else{?>display:block;<?php }?>" src="<?=isset($forms['project_xuanchuan_logo']) ? URL::imgurl($forms['project_xuanchuan_logo']) : '';?>" id="imghead1" />
                            </span>
                            <span class="infor1_modify" style="padding:5px 10px 0 10px;width:112px;" >
                                <span class="uploadImg" id="uploadImg1" style=" width:112px; height:32px;">
                                <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="112" height="32" id="flashrek2">
                                <param name="movie" value="<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>" />
                                <param name="quality" value="high" />
                                <param name="wmode" value="transparent" />
                                <param name="allowScriptAccess" value="always" />
                                <?php if(!empty($forms['project_xuanchuan_logo'])){?>
                                	<embed src="<?php echo URL::webstatic('/flash/upload_xcp_edit.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                                <?php }else{?>	
                              		<embed src="<?php echo URL::webstatic('/flash/upload_xcp.swf?url='.URL::website('/company/member/ajaxcheck/uploadComLogo').'&fun=viewImage2')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="100" height="26" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                               <?php  }?>
                                </object>
                                </span>
                            </span>
                             <?php if(empty($forms['project_xuanchuan_logo'])){?>
                            <span style=" width:300px; text-align:left; color:#8b8a8a; line-height:18px;">支持JPEG、GIF、PNG、JPG等格式,<br/>大小不超过4M，长480px,宽270px为佳</span>
                            <?php }?>
                            <span class="tipinfo" id="tipfile">
                            </span>
                        </p>
                        <p class="set_select_width1">
                            <span><em>*</em>品牌发源地：</span>
                           <select id="area_id">
                            <option value="0">请选择</option>
                            <?php foreach ($areas as $key=>$value){;?>
                                 <option value="<?php echo $value->cit_id;?>" <?php if(isset($forms['project_brand_birthplace'][0])){if($value->cit_name == $forms['project_brand_birthplace'][0] )echo "selected='selected'";}?>><?=$value->cit_name;?></option>
                            <?php }?>
                            </select>
                            <select id="area_id1">
                            <?php if(!empty($areas2) && isset($forms['project_brand_birthplace'][1])){
                                    foreach ($areas2 as $key=>$value){?>
                                        <option value="<?php echo $value['cit_id'];?>" <?php if(isset($forms['project_brand_birthplace'][1])){if($value['cit_name'] == $forms['project_brand_birthplace'][1] )echo "selected='selected'";}?>><?=$value['cit_name'];?></option>
                                    <?php }?>
                            <?php }elseif(!empty($areas2)){?>
                                       <?php foreach ($areas2 as $key=>$value){;?>
                                             <option value="<?php echo $value['cit_id'];?>"><?=$value['cit_name'];?></option>
                                      <?php }?>
                            <?php }else{?>
                                       <option value="0">请选择</option>
                            <?php }?>
                            </select>
                            <input type="hidden" id="hidden_area_name" name ="project_brand_birthplace" value="<?php echo isset($project_brand_birthplace)?$project_brand_birthplace:"";?>"/>
                            <span class="tipinfo" id="tiphye_project_brand_birthplace"><?=isset($error['project_brand_birthplace'])?$error['project_brand_birthplace']:'';?></span>
                            <?php /*?>
                            <input type="text" class="text" name="project_brand_birthplace" value="<?=arr::get($forms, 'project_brand_birthplace')?>" id="source"/>
                            <span class="tipinfo" id="tipsource">
                            <?=isset($error['project_brand_birthplace']) ? $error['project_brand_birthplace'] : '';?>
                            </span>
                            <?php */?>
                        </p>
                        <p class="set_select_width1">
                            <span><em>*</em>所属行业：</span>
                            <select  name="project_industry_id[]" id="hye">
                                <? if(arr::get($list_industry, 0,array())) {?>
                            <?php foreach ($list_industry[0] as $k=>$v): ?>
                            <option value="<?=$v['industry_id'];?>" <?php if(isset($v['status']) && $v['status'] == 1) echo "selected='selected'"; ?> ><?=$v['industry_name'];?></option>
                            <?php endforeach; ?>
                                <?}else{?>
                             <option value="">请选择</option>
                                <?php foreach ($indList as $value): ?>
                                <option value="<?=$value->industry_id;?>"><?=$value->industry_name;?></option>
                                <?php endforeach; ?>
                                    <?}?>
                            </select>
                            <select  name="project_industry_id[]" id="hye1">
                                <? if(arr::get($list_industry, 1,array())) {?>
                            <?php foreach ($list_industry[1] as $k=>$v): ?>
                            <option value="<?=$v['industry_id'];?>" <?php if(isset($v['status']) && $v['status'] == 1) echo "selected='selected'"; ?> ><?=$v['industry_name'];?></option>
                                <?php endforeach; ?><?}else{?>
                            <option value="">请选择</option>
                                <?}?>
                            </select>
                            <span class="tipinfo" id="tiphye"><?=isset($error['project_industry_id'])?$error['project_industry_id']:'';?></span>
                        </p>
                        <p>
                            <span><em>*</em>主营产品：</span>
                            <input type="text" name="project_principal_products" value="<?=arr::get($forms, 'project_principal_products')?>" class="text long" id="pro"/>
                            <span class="tipinfo" id="tipro"><?=isset($error['project_principal_products']) ? $error['project_principal_products'] : '';?></span>
                        </p>
                        <p>
                            <span>成立时间：</span>
                            <input type="text" class="text long"  name="projcet_founding_time" value="<?=arr::get($forms, 'projcet_founding_time')?>" onClick="WdatePicker()"/>
                        </p>
                        <p>
                            <span><em>*</em>投资金额：</span>
                            <select  name="project_amount_type" id="tzhi">
                             <?php $list = common::moneyArr(); foreach ($list as $k=>$v): ?>
                            <option  value="<?=$k;?>" <?php if(arr::get($forms, 'project_amount_type') == $k) echo "selected='selected'"; ?>><?=$v;?></option>
                            <?php endforeach; ?>
                            </select>
                            <span class="tipinfo" id="tiptzhi">
                            <?=isset($error['project_amount_type'])?$error['project_amount_type']:'';?>
                            </span>
                        </p>
                        <p>
                            <span><em>*</em>加盟费用：</span>
                            <input maxlength="6" type="text" name="project_joining_fee"  value="<?=arr::get($forms, 'project_joining_fee')?>"  class="text short" id="jiam" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")' /> 万元
                            <span class="tipinfo" id="tipjiam">
                            <?=isset($error['project_joining_fee'])?$error['project_joining_fee']:'';?>
                            </span>
                        </p>
                        <p>
                        <span>保证金：</span>
                        <input maxlength="6" type="text" name="project_security_deposit"  value="<?=arr::get($forms, 'project_security_deposit')?>"  class="text short" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")' /> 万元
                        </p>
                         <p>
                            <span><em>*</em>投资风险：</span>
                            <select  name="risk" id="fengx">
                             <?php $list = guide::attr10(); foreach ($list as $k=>$v): ?>
                            <option  value="<?=$k;?>" <?php if(arr::get($forms, 'risk') == $k) echo "selected='selected'"; ?>><?=$v;?></option>
                            <?php endforeach; ?>
                            </select>
                            <span class="tipinfo" id="tipfengx"></span>
                        </p>
                        <p>
                            <span><em>*</em>投资回报率：</span>
                            <select name="rate_return" id="huibl">
                             <?php $list = guide::attr8(); foreach ($list as $k=>$v): ?>
                            <option  value="<?=$k;?>" <?php if(arr::get($forms, 'rate_return') == $k) echo "selected='selected'"; ?>><?=$v;?></option>
                            <?php endforeach; ?>
                            </select>
                            <span class="tipinfo" id="tiphuibl"></span>
                        </p>
                        <p id="checkbox3">
                            <span><em>*</em>适合人脉关系：</span>
                            <?php $lst = guide::attr5(); foreach ($lst as $k=>$v): ?>
                            <label><input type="checkbox" name="connection[]" value="<?=$k;?>" <?php if(isset($get_onnection[$k])){echo "checked";}?>/><?=$v;?></label>
                            <?php endforeach; ?>
                            <span class="tipinfo" id="tipstyle3"></span>
                        </p>

                        <p >
                            <span class="em"><em>*</em>项目标签：</span>
                            <input type="text" name="project_tag"  value="<?=isset($forms['project_tag']) ? arr::get($forms, 'project_tag') : ''?>" class="text long" id="project_tag"/>
                            <span class="tipinfo" id="project_tagtel">
                            <?=isset($error['project_tag'])?$error['project_tag']:'请用空格隔开';?>
                            </span>
                        </p>

                        <div class="add1">
                            <span class="em"><em>*</em>投资人群：</span>
                            <div class="list">
                                <p class="aa"><a href="#" class="add1_img_btn"><img src="<?php echo URL::webstatic("images/infor2/add.png") ?>" /></a></p>
                                <div class="list_li" id="renq">
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
                                        if(count($forms['project_groups_label']) > 0):
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
                                    </ul>
                                </div>
                                <span class="tipinfo" id="tiprenq"></span>
                                <div class="clear"></div>
                                <p class="bb"><a href="#" class="re_add1_img_btn"><img src="<?php echo URL::webstatic("images/infor2/re_add.png") ?>" class="re_add1" /></a></p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="add2" style="height:auto;">

                            <span class="em"><em>*</em>招商地区：</span>

                            <div class="list">
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
                            <p class="aa" style="width:101px;"><a href="#" class="add2_img_btn"  id="addressClickEffect"><img src="<?php echo URL::webstatic("images/infor2/add.png") ?>" id="addImg" /><img src="<?php echo URL::webstatic("images/infor2/re_add.png") ?>" style="display:none;" id="addImg2"/></a></p>
                        </div>

                            <div class="clear"></div>
                        </div>
                        <p id="checkbox">
                            <span><em>*</em>招商形式：</span>
                            <?php $lst = common::businessForm();foreach ($lst as $k=>$v):?>
                            <input type="checkbox" name="project_co_model[]" value="<?=$k;?>" <?php if(isset($forms['project_co_model'][$k])){echo "checked";} ?> /><?=$v;?>　　
                            <?php endforeach; ?>
                            <span class="tipinfo" id="tipstyle">
                            <?=isset($error['project_co_model'])?$error['project_co_model']:'';?>
                            </span>
                        </p>
                        <p>
                            <span><em></em>加盟时限：</span>
                            <select  name="time_limit">
                             <?php $list = guide::attr9(); foreach ($list as $k=>$v): ?>
                            <option  value="<?=$k;?>" <?php if(arr::get($forms, 'time_limit') == $k) echo "selected='selected'"; ?>><?=$v;?></option>
                            <?php endforeach; ?>
                            </select>
                        </p>
                        <p>
                        <span><em>*</em>座机号码：</span>
                        <input type="text" name="project_phone"  value="<?=arr::get(arr::get($forms, 'project_phone'),0)?>" class="text long" id="tel"  maxlength="15" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)" onblur="this.v();"/> 分机号 <input type="text" name="phone_fj" value="<?=arr::get(arr::get($forms, 'project_phone'),1, '')?>" class="text short" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")' maxlength="5" />
                        <span class="tipinfo" id="tiptel">
                        <?=isset($error['project_phone'])?$error['project_phone']:'';?>
                        </span>
                        </p>

                        <p>
                        <span>手机号码：</span>
                        <input type="text" name="project_handset"  value="<?=arr::get($forms, 'project_handset')?>" class="text long" id="tel1" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")' maxlength="11"/>
                        <span class="tipinfo" id="tiptel">
                        <?=isset($error['project_handset'])?$error['project_handset']:'';?>
                        </span>
                        </p>

                        <p>
                        <span><em>*</em>联系人：</span>
                        <input type="text" name="project_contact_people"  value="<?=arr::get($forms, 'project_contact_people')?>" class="text long" id="contactPeople"/>
                        <span class="tipinfo" id="tipcontactPeople">
                        <?=isset($error['project_contact_people'])?$error['project_contact_people']:'';?>
                        </span>
                        </p>

                        <p class="textarea">
                            <span><em>*</em>项目特点： </span>
                            <textarea  name="product_features" class="tedian" id="tedian"><?=isset($forms['product_features']) ? $forms['product_features'] : '';?></textarea>
                        </p>
                        <p class="pp">
                            <span>&nbsp;</span>
                            <span class="tipinfo tipinfo1" id="tiptjian1">请填写项目特点，10-200个字符之间</span>
                        </p>
                        <p class="textarea">
                            <span><em>*</em>加盟条件： </span>
                            <?php
                            echo  Editor::factory(isset($forms['project_join_conditions']) ? $forms['project_join_conditions'] : '',"nobars",array("field_name"=> 'project_join_conditions',"width"=>"580","height"=>"200"));
                            ?>

                        <!-- <textarea  name="project_join_conditions"  id="tjian"><?//=arr::get($forms, 'project_join_conditions')?></textarea> -->
                        </p>
                        <p class="pp">
                            <span>&nbsp;</span>
                            <span class="tipinfo tipinfo1" id="tiptj">请填写加盟条件，10-150个字符之间</span>
                        </p>
                        <p class="pp">
                            <span>&nbsp;</span>
                            <span class="tipinfo" id="tiptjian"><?=isset($error['project_join_conditions'])?$error['project_join_conditions']:'';?></span>
                        </p>
                        <p class="textarea">
                            <span><em>*</em>项目简介： </span>
                           <?php
                            echo  Editor::factory(isset($forms['project_summary']) ? $forms['project_summary'] : '',"nobar",array("field_name"=> 'project_summary',"width"=>"580","height"=>"200"));
                            ?>
                            <!-- <textarea name="project_summary" id="jjie"><?=arr::get($forms, 'project_summary')?></textarea> -->
                        </p>
                         <p class="pp">
                            <span>&nbsp;</span>
                            <span class="tipinfo tipinfo1" id="tipjiej">请填写项目简介</span>
                        </p>
                        <p class="pp">
                            <span class="tipinfo" id="tipjjie">
                            <?=isset($error['project_summary'])?$error['project_summary']:'';?>
                            </span>
                        </p>
                        <span class='<?=arr::get($forms, 'project_display')?>' id="spanPopup"></span>
                        <input type="hidden" value="1" id="submitType" name="submitType"/>
                        <p style="margin-bottom:15px;">
                        <input type="submit" value="" class="zhaoshangPublish ryl_save_infor01" onclick="$('#submitType').attr('value', 1);"/>
                        </p>
                        <!--<p class="submit"><span>&nbsp;</span><span class="nextStep">您的项目还不完整哦，去<input type="submit" value="" class="zhaoshangPublish"/>继续完善项目信息吧</p>-->
                    </form>
                </div>
            </div>
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
        <span>自由标签输入框：</span><input type="button" value="添加" class="add_btn" /><input type="text" placeholder="不可超过5个字" class="add_text" onFocus="scrollFocus(this)" onBlur="scrollBlur(this)"/>
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
                <?php if(count($forms['area']) >= 0):?>
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
<script>
/*
$(document).ready(function(){
    //显示信息
    var type = $("#type").val();
    if(type == 2){
         $("#tishiuser").text("您已经发布过此项目，请重新输入项目名称");
         $("#user").focus();
     }
 var old_project_name = $.trim($("#old_name").val());
 $("#user").blur(function(){
       var project_name = $.trim($("#user").val());
       if(project_name == ""){
           $("#tishiuser").text("请填写您要招商的品牌名称");
           $("#user").focus();
           return false;
       }
       //当有对项目名称做修改时
       if(project_name != old_project_name){
           changeName(project_name);
       }
    })
});
//验证项目名称同一个用户是不是一样

function changeName(project_name){
    var url = "/company/member/ajaxcheck/changeName";
    $.post(url,{'project_name':project_name},function(data){
        // 1表示一发布过
       if(data == 1){
           $("#tishiuser").text("您已经发布过此项目，请重新输入项目名称");
           $("#user").focus();
           return false;
       }
    },'json');
}*/
</script>








