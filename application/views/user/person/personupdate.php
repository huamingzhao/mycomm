<?php echo URL::webcss("personinfor.css")?>
<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>
<?php echo URL::webjs("zhaos.js")?>
<?php echo URL::webjs("search_pro.js")?>
<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("invitepro_zg.js")?>
<style>
li.infor1_size12 strong{font-size: 12px; color:#9f9f9f; font-family:Arial,"宋体"; font-weight:normal;}
li.infor1_size12 b{  font-weight:normal; padding-top:2px; padding-right:10px;}
li.infor1_size12 a{  color:#0036ff; font-size:12px;}
</style>
<!--右侧开始-->
    <div id="right">
        <div id="right_top"><span><?php if(isset($type) && $type==2){echo '你好，欢迎进入我的名片修改！';} else{ echo '基本信息管理';} ?></span><div class="clear"></div></div>
        <div id="right_con">
            <div class="personInfor">
                <h1>我的基本信息<span>（<em>*</em>为必填项）</span></h1>
                <?php  echo Form::open(URL::website('/person/member/basic/personupdate').'?type='.$type, array('method' => 'post','enctype'=>"multipart/form-data"))?>
                <input type="hidden" name="per_id"  value="<?=isset($personinfo->per_id) ? $personinfo->per_id : '';?>"  />
                
                <ul>
                    <li>
                        <label for="name"><em>*</em>姓名：</label>
                        <input style="color:#9f9f9f;" type="text" name="per_realname" id="name" placeholder="<?=isset($personinfo->per_realname) ? $personinfo->per_realname : '请输入您的真实姓名';?>"/>
                        <ins class="errorWord">* 请输入您的真实姓名</ins>
                    </li>
                    
                    <li class="liSpecial">
                        <label><em>*</em>性别：</label>
                        <input type="radio" name="per_gender" value="1"  checked="ckecked" <?php if(isset($personinfo->per_gender) && ($personinfo->per_gender == 1)){ ?>checked<?php } ?>><span>男</span><input type="radio" name="per_gender" value="2" <?php if(isset($personinfo->per_gender) && ($personinfo->per_gender == 2)){ ?>checked<?php } ?>><span>女</span>
                    </li>
                    
                    
                    <?if(!$user->valid_mobile) {?>
                    <li>
                        <label for="tel"><em>*</em>手机号码：</label>
                        <input style="color:#9f9f9f;"  type="type" name="per_phone" id="tel" value="<?=isset($mobile) ? $mobile : '请输入您的11位手机号码';?>" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")' /> <?if($mobile){?><b><img src="<?php echo URL::webstatic('images/infor1/tel_nocheck.jpg')?>" style="float:left;" /></b><a href="/person/member/valid/mobile?to=change">去验证</a><?}?>
                         <?php if(!empty($error['mobile_error'])){ ?><ins>* <?php echo $error['mobile_error'];?></ins><?php }?>
                        <ins class="errorWord">* <?php if(!empty($error['mobile_error'])){echo $error['mobile_error'];}else{?>手机号码格式错误，请重新输入...<?php }?></ins>
                    </li>
                    
                   <?}else{?>
                   <li class='infor1_size12'>
                        <label for="tel"><em>*</em>手机号码：</label><span style="color:#9f9f9f; font-family:Arial;"><?=isset($mobile) ? $mobile : '';?></span>
                        <input style="color:#9f9f9f;display:none"  type="type" name="per_phone" id="tel" value="<?=isset($mobile) ? $mobile : '请输入您的11位手机号码';?>" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")' /> <b><img src="<?php echo URL::webstatic('images/infor1/tel_check.jpg')?>" /></b><a href="/person/member/valid/mobile?to=change">修改手机号码</a>
                         <?php if(!empty($error['mobile_error'])){ ?><ins>* <?php echo $error['mobile_error'];?></ins><?php }?>
                        <ins class="errorWord">* <?php if(!empty($error['mobile_error'])){echo $error['mobile_error'];}else{?>手机号码格式错误，请重新输入...<?php }?></ins>
                    </li>
                    
                    <?}?>
                    <li>
                        <label>邮箱：</label>
                        <ins style="color:#9f9f9f; font-family:Arial;"><?=isset($email) ? $email : '';?></ins>
                    </li>
                    
                    <li class="clear">
                        <label><em>*</em>个人所在地：</label>
                                   <select id="address" class="short" name="per_area">
                                        <option value="">请选择</option>
                                        <?php foreach ($area as $v){?>
                                        <option value="<?=$v['cit_id']?>" <?php if($v['cit_id']==arr::get($areaIds, 0)){echo "selected='selected'";}?>><?=$v['cit_name']?></option>
                                        <?php }?>
                                    </select>
                                    <select id="address1" class="short" name="area_id" >
                                          <option value="" >不选</option>
                                            <?php if(arr::get($areaIds, 0)!='' && count($cityarea)){ foreach ($cityarea as $v): ?>
                                             <option value="<?=$v->cit_id ;?>" <?php if($v->cit_id == arr::get($areaIds, 1)) echo "selected='selected'"; ?> ><?=$v->cit_name;?></option>
                                             <?php endforeach; } ?>
                                     </select>
                        <!--
                                     <select id="address1" class="short" name="area_id" >
                                          <option value="" >不选</option>
                                          <option value="" >不选</option>
                                     </select>
                              -->
                        <ins class="errorWord">* 请选择个人所在地</ins>
                    </li>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    <li>
                        <label for="money"><em>*</em>意向投资金额：</label>
                        <select  name="per_amount" id="money">
                        <option value="请选择">请选择</option>
                        <?php $list = common::moneyArr(); foreach ($list as $k=>$v): ?>
                        <option  value="<?=$k;?>" <?php if(isset($personinfo->per_amount) && ($personinfo->per_amount == $k)) echo "selected='selected'"; ?> ><?=$v;?></option>
                        <?php endforeach; ?>
                        </select>
                        <ins class="errorWord">* 请选择意向投资金额</ins>
                    </li>
                    
                    
                    
                    <li>
                        <label for="area"><em>*</em>意向投资行业：</label>
                        <select  name="per_industry[]"  id="hye" >
                        <option value="">请选择</option>
                        <?php $list = common::primaryIndustry(0); foreach ($list as  $k=>$value): ?>
                        <option value="<?=$value->industry_id;?>" <?php if(isset($personalIndustry['parent_id']) && ($personalIndustry['parent_id'] == $k+1)) echo "selected='selected'"; ?>><?=$value->industry_name;?></option>
                        <?php endforeach; ?>
                        </select>
                        <select  name="per_industry_child[]" id="hye1">
                            <?if(isset($personalIndustry['parent_id']) && $personalIndustry['parent_id'] != 0) {?>
                                <?  if($personalIndustry['industry_arr']) {foreach ($personalIndustry['industry_arr'] as  $k=>$value): ?>
                                <option value="<?=$value['industry_id'];?>" <?php if(isset($personalIndustry['industry_id']) && ($personalIndustry['industry_id'] == $k)) echo "selected='selected'"; ?>><?=$value['industry_name'];?></option>
                                <? endforeach; }?>
                            <?}else{?>
                                <option value="">请选择</option>
                            <? } ?>
>
                         </select>
                        <ins class="errorWord">* 请选择意向投资行业</ins>
                    </li>
                    
                    
                    
                    
                    <li style="line-height:normal;padding-bottom:18px; height:auto;">
                    <label for="city_area" style="line-height:44px;"><em>*</em>意向投资地区：</label>
                        
                        <div class="add2" style="height:auto;float:left;width:560px">
                        <div class="list">
                                <div class="list_li" id="diqu">
                                   <?php
                                        if(count($readArea) > 0){
                                        foreach ($readArea as $k=>$v){
                                    ?>
                                    <div class="list">
                                        <dl >
                                            <dt id="webcity_<?php echo $v['pro_id']?>"><?php echo $v['cit_name']?><img src="<?php echo URL::webstatic("images/zhaos/diqu_close.png") ?>"><input type="hidden" value="<?php echo $v['pro_id']?>" name="project_city[]"></dt>
                                            <dd>
                                           <?php
                                           if(isset($v['data'])){
                                           foreach ($v['data'] as $key=>$value){
                                           ?>
                                                <span id="webcity_<?php echo $value['area_id']?>"><?php echo $value['cit_name']?><img src="<?php echo URL::webstatic("images/zhaoshangAddress/closeBg.gif") ?>"/>
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
                                   
                                    
   
                             </div></div>
                            <div class="clear"></div>
                          <a id="addressClickEffect" class="add2_img_btn" style="position:relative;display: block;padding-right: 10px;width: 100px;margin-top: 5px;" href="#">
<img id="addImg" src="<?php echo URL::webstatic('images/infor2/add.gif')?>" style="width: auto; display: none;">
<img id="addImg2" style="display: block; width: 101px; height:30px; position: static;" src="<?php echo URL::webstatic('images/infor2/re_add.gif');?>">
</a>
                        </div>
                        <ins class="errorWord" style="display:none; position: absolute; left: 208px; top: 11px;">* 请选择我的意向投资地区</ins>
                        <div class="clear"></div>
                        </li>
                    <li class="heightAuto">
                        <label><em>*</em>我的标签：</label>
                        <div class="divFloatLeft" id="divFloatLeft">
                        <ul class="ulFloat" id="ulFloat">
                                    <?php
                                        if(count($personinfo->per_label) > 0):
                                        foreach ($personinfo->per_label as $val):
                                    ?>
                                    <li id="li_<?=$val['tag_id'];?>" title="<?=$val['tag_name'];?>">
                                    <?=$val['tag_name'];?>
                                    <input type="hidden" value="<?=$val['tag_id'];?>" name="Investment_groups[]">
                                    <span class="testspan">关闭</span>
                                    </li>
                                    <?php
                                        endforeach;
                                        endif;
                                    ?>
                                    <?php
                                        if(count($personinfo->per_per_label) > 0):
                                        foreach ($personinfo->per_per_label as $k => $val):
                                    ?>
                                    <li id="li_<?=$k+100;?>" title="<?=$val;?>">
                                    <?=$val;?>
                                    <input type="hidden" value="<?=$val;?>" name="per_per_labels[]">
                                    <span class="testspan">关闭</span>
                                    </li>
                                    <?php
                                        endforeach;
                                        endif;
                                    ?>
                        </ul>
                            <button class="add1_img_btn" id="btnText"><?php if(count($personinfo->per_label) > 0){?>修改标签<?php }else{?>添加标签<?php }?></button>
                            <div class="clear"></div>
                            <ins class="errorWord">请选择我的标签</ins>
                        </div>

                        <div class="clear"></div>
                    </li>
                    <li class="liHeight">
                        <label><em>*</em>个性投资说明：</label>
                        <textarea name="per_remark"  id="textarea"><?=isset($personinfo->per_remark) ? $personinfo->per_remark : '';?></textarea>
                        <ins class="errorWord1" style="display:block;height:14px;color:#f00;"></ins>
                    </li>
                    <li>
                        <label><em></em></label>
                        <input type="submit" class="saveBtn" id="saveBtn" value="">
                    </li>
                </ul>
                </form>
            </div>
        </div>
    </div>
<!--右侧结束-->


<div class="clear"></div>
</div>
<div id="opacity"></div>
<div id="zhaos_alert1">
    <h3>我的标签</h3>
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
                 <?php foreach ($tag as $v): ?><ul><?php foreach ($v as $val): ?><li id="<?=$val['tag_id'];?>"><a href="#"><?=$val['tag_name'];?></a></li><?php endforeach; ?></ul><?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="scroll_add">
        <span>自由标签输入框：</span><input type="button" value="添加" class="add_btn" /><input type="text" value="不可超过5个字" class="add_text" onFocus="scrollFocus(this)" onBlur="scrollBlur(this)"/>
    </div>
    <div class="clear"></div>
    <p class="tipadd"></p>
</div>


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
                
                <?php foreach ($areas as $k=>$v):?>
                <?php  if(isset($personalArea[$v->cit_id]) && ($v->cit_id ==$personalArea[$v->cit_id]['pro_id'])){?>
                  <li><span><?php echo $v->cit_name;?></span><input type="hidden" name="false" class="<?php echo $v->cit_id;?>" /></li>
                <?php  }else{?>
                <li><span><?php echo $v->cit_name;?></span><input type="hidden" name="true" class="<?php echo $v->cit_id;?>" /></li>
                <?php  }?>
                <?php endforeach;?>
                </ul>
                <div class="clear"></div>
            </div>
            <h3>请选择市：</h3>
            <div id="city" class="city1" >
                <ul></ul>
                <div class="clear"></div>
            </div>
            <h3>已选择地区：</h3>
            <div id="selectTown" class = "town">
            <?php if(count($readArea) > 0): foreach ($readArea as $k=>$v):?>
            <div class="list">
             <dl>
             <dt id="city_<?php echo $v['pro_id']?>"><?php echo $v['cit_name']?><img src="<?php echo URL::webstatic("images/zhaoshangAddress/closeBg.gif") ?>"/><input type="hidden" value="<?php echo $v['pro_id']?>" name="project_city[]"></dt>
             <dd>
              <?php if(isset($v['data'])): foreach ($v['data'] as $key=>$value): ?>
              <span id="city_<?php echo $value['area_id']?>"><?php echo $value['cit_name']?><img src="<?php echo URL::webstatic("images/zhaoshangAddress/closeBg.gif") ?>"/><input type="hidden" value="<?php echo $value['area_id']?>" name="project_city[]"></span>
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
<?php echo URL::webjs("person_zg.js")?>
<?php echo URL::webjs("zhaosArea.js")?>
<?php echo URL::webjs("common.source.js")?>
<?php echo URL::webjs("province.source.js")?>
<?php echo URL::webjs("deleteImg.js")?>
