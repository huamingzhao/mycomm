<?php echo URL::webcss("common.css")?>
<?php echo URL::webcss("per_infor_0502.css")?>
<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("header.js")?>
<?php echo URL::webjs("person_new.js")?>


<?php echo URL::webjs("zhaos.js")?>
<?php echo URL::webjs("search_pro.js")?>
<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("invitepro_zg.js")?>
<?php echo URL::webjs("personinfo_vali.js")?>
<?php echo URL::webjs("invest_new.js")?>
<style>
#zhaosArea_top .close {border:none;}
#zhaosArea_center #province ul li {padding: 0 0 10px 30px;}
#zhaosArea_center #city ul li { padding: 0 0 10px 25px;}
</style>

                <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>基本信息管理</span><div class="clear"></div></h2>
                       <div class="per_infor_0502">

                      <ul class="per_infor_title">
                      <li><a href="<?php echo URL::website("/person/member/basic/person")?>">我的基本信息</a></li>
                      <li><a href="<?php echo URL::website("/person/member/basic/personInvestShow")?>" class="current"><img src="<?php echo URL::webstatic("images/per_infor/icon01.jpg")?>" style="padding:21px 10px 0 62px;">意向投资信息</a></li>
                      <li class="last"><a href="<?php echo URL::website("/person/member/basic/experience")?>">从业经验</a></li>
                      </ul>



                      <form id="form_id" action="<?php echo URL::website('/person/member/basic/personInvest')?>" method="post">
                      <div class="per_infor_content">
                         <h4><b>我的基本信息</b>（<em>*</em>为必填项）</h4>

                         <p><span class="per_infor_left"><em>*</em> 意向投资金额：</span>
                            <span class="per_infor_right">
                            <select name="per_amount" id="money">
                            <option value="请选择">请选择</option>
                            <?php $list = common::moneyArr(); foreach ($list as $k=>$v): ?>
                                <option  value="<?=$k;?>" <?php if(isset($personinfo->per_amount) && ($personinfo->per_amount == $k)) echo "selected='selected'"; ?> ><?=$v;?></option>
                            <?php endforeach; ?>
                            </select>

                            </select>
                            <span class="tipinfo_new">请选择意向投资金额</span>
                            </span>
                            <div class="clear"></div>
                         </p>



                         <p><span class="per_infor_left"><em>*</em> 意向投资行业：</span>
                            <span class="per_infor_right">
                            <select  name="per_industry[]"  id="hye" >
                            <option value="">请选择</option>
                            <?php $list = common::primaryIndustry(0); foreach ($list as  $k=>$value): ?>
                                <option value="<?=$value->industry_id;?>" <?php if(isset($personalIndustry['parent_id']) && ($personalIndustry['parent_id'] == $k+1)) echo "selected='selected'"; ?>><?=$value->industry_name;?></option>
                            <?php endforeach; ?>
                            </select>
                            <select  name="per_industry_child[]" id="hye1">
                            <?php if( $personalIndustry['industry_id']==0 ){?>
                                <OPTION value='0'>不限</OPTION>
                            <?php }?>
                            <?if(isset($personalIndustry['parent_id']) && $personalIndustry['parent_id'] != 0) {?>
                                <?  if($personalIndustry['industry_arr']) {foreach ($personalIndustry['industry_arr'] as  $k=>$value): ?>
                                <option value="<?=$value['industry_id'];?>" <?php if(isset($personalIndustry['industry_id']) && ($personalIndustry['industry_id'] == $k)) echo "selected='selected'"; ?>><?=$value['industry_name'];?></option>
                                <? endforeach; }?>
                            <?}else{?>
                                <option value="">请选择</option>
                            <? } ?>



                            </select>
                            <span class="tipinfo_new">请选择意向投资行业</span>
                            </span>
                            <div class="clear"></div>
                         </p>



                        <p><span class="per_infor_left"><em>*</em> 意向投资地区：</span>
                            <span class="per_infor_right">

                                   <div style="height:auto;float:left; width:595px" class="add2">
                                          <div class="list">
                                              <div id="diqu" class="list_li">
                                            <?php
                                            if(count($readArea) > 0){
                                                foreach ($readArea as $k=>$v){
                                                ?>
                                                    <div class="list">
                                                        <dl >
                                                        <dt id="webcity_<?php echo $v['pro_id']?>"><?php echo $v['cit_name']?><img src="<?php echo URL::webstatic("images/per_infor/icon02.jpg") ?>"><input type="hidden" value="<?php echo $v['pro_id']?>" name="project_city[]"></dt>
                                                        <dd>
                                                        <?php
                                                        if(isset($v['data'])){
                                                        foreach ($v['data'] as $key=>$value){
                                                        ?>
                                                        <span id="webcity_<?php echo $value['area_id']?>"><?php echo $value['cit_name']?><img src="<?php echo URL::webstatic("images/per_infor/icon02.jpg") ?>"/>
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
                                           </div>

                                          <div class="clear"></div>

                                          <a href="#" style="position:relative;display:inline-block;padding-right: 10px;width: 100px;margin-top: 5px;" class="add2_img_btn" id="addressClickEffect">

                                            <img style="width: auto; display: none;" src="<?php echo url::webstatic('images/infor2/add.gif')?>" id="addImg">

                                            <img src="<?php echo url::webstatic('images/infor2/re_add.gif')?>" style="display: block; width: 101px; height: 30px; position: static;" id="addImg2">

                                          </a>
                                          <span class="tipinfo_new" id="diqu_tip" style="position:relative;top:-10px;">请选择意向投资地区</span>
                                    </div>

                            </span>
                            <div class="clear"></div>
                         </p>


                         <p><span class="per_infor_left"><em>*</em> 我的标签：</span>
                            <span class="per_infor_right">

                            <input class="per_infor_long_text02" name="" type="text" value="" id="tags_text">
                            <input class="per_infor_attach" name="" type="button" value="" id="tags_btn">
                            <span id="addTags" style="padding:0;width:380px;">
                            <?php
                            if(count($personinfo->per_label) > 0):
                                foreach ($personinfo->per_label as $val):
                            ?>
                            <a href="javascript:void(0)" title="<?=$val['tag_name'];?>" id="li_<?=$val['tag_id'];?>" class="per_infor_label"><label><?=$val['tag_name'];?></label><em></em><input type="hidden" value="<?=$val['tag_id'];?>" name="Investment_groups[]"></a>

                            <?php
                                endforeach;
                            endif;
                            ?>
                            <?php
                            if(count($personinfo->per_per_label) > 0):
                                foreach ($personinfo->per_per_label as $k => $val):
                            ?>
                                <a href="javascript:void(0)" title="<?=$val;?>" id="li_<?=$k+100;?>" class="per_infor_label"><label><?=$val;?></label><em></em><input type="hidden" value="<?=$val;?>" name="per_per_labels[]"></a>
                            <?php
                                 endforeach;
                             endif;
                             ?>


                            </span>
                            <span class="tipinfo_new" id="tags_tip">请选择意我的标签</span>
                            </span>
                            <div class="clear"></div>
                         </p>



                         <p>
                         <span class="per_infor_left"></span>
                            <span class="per_infor_right">
                               <b>推荐标签：</b>
                               <label class="per_infor_tj_label">
                               <?php
                               if( !empty( $tag ) ){
                                   foreach ($tag as $v){
                                       foreach ( $v as $val ){
                                   ?>
                                   <a href="javascript:void(0)" id="<?=$val['tag_id'];?>"><?php echo $val['tag_name']?></a>
                                   <?php
                                       }
                                   }
                               }
                               ?></label>
                            </span>
                            <div class="clear"></div>
                         </p>



                         <p><span class="per_infor_left"><em>*</em> 我的人脉关系：</span>
                            <span class="per_infor_right">
                            <?php
                            $attr5  = guide::attr5();
                            foreach ( $attr5 as $k=>$vss ){
                            ?>
                                <input class="radio_a per_infor_radio"  name="per_connections" type="radio" value="<?php echo $k?>" <?php if( $personinfo->per_connections==$k  ){?>checked<?php } ?>><span><?php echo $vss?></span>
                            <?php
                            }
                            ?>

                            <span class="tipinfo_new">请选择意我的人脉关系</span>
                            </span>
                            <div class="clear"></div>
                         </p>




                         <p><span class="per_infor_left"><em>*</em> 我的投资风格：</span>
                            <span class="per_infor_right">

                             <input class="radio_b per_infor_radio"  name="per_investment_style" type="radio" value="0" <?php if( $personinfo->per_investment_style=='0' ){?>checked<?php }?>><span>不限</span>
                             <?php
                             $attr10        = guide::attr10();
                             foreach ( $attr10 as $k=>$vss ){
                             ?>
                             <input class="per_infor_radio radio_b"  name="per_investment_style" type="radio" value="<?php echo $k?>" <?php if( $personinfo->per_investment_style==$k ){?>checked<?php } ?>><span><?php echo $vss?></span>
                             <?php
                             }
                             ?>

                            <span class="tipinfo_new">请选择意我的投资风险</span>
                            </span>
                            <div class="clear"></div>
                            <input type="hidden" name="per_id"  value="<?=isset($personinfo->per_id) ? $personinfo->per_id : '';?>"  />
                         </p>
                         <p class="clearfix"><span class="per_infor_left"><em>*</em> 有无店铺：</span>
                            <span class="per_infor_right per_hasShop">

                             <input type="radio" name="per_shop_status" value="1" checked="checked" id="per_hasShop" class="per_infor_radio "/><label for="per_hasShop">有</label>
                             <input type="radio" name="per_shop_status" value="0" id="per_noShop" class="per_infor_radio" <?php if( $personinfo->per_shop_status=='0' ){?>checked="checked"<?php }?>/><label for="per_noShop">无</label>

                            <span class="tipinfo_new">请选择意有无店铺</span>
                            </span>
                         </p>
                         <p class="clearfix" <?php if( $personinfo->per_shop_status=='0' ){?>style="display: none;" <?php }?>>

                            <span class="per_infor_left"><em>*</em> 店铺面积：</span>
                            <span class="per_infor_right">
                                <input class="per_infor_long_text02 per_shop_area" type="text" name="per_shop_area" onkeyup="this.value=this.value.replace(/[^0-9]/gi, '')" value="<?php echo $personinfo->per_shop_area?>" /><label>平方米</label>
                                <span class="tipinfo_new">请输入店铺面积</span>
                            </span>

                          </p>

                         <p class="per_infor_btn"><a href="#" class="ryl_btn01">保存</a></p>

                         <div class="clear"></div>
                      </div>
                      </form>
                      <div class="clear"></div>
                    </div>
                </div>
                <!--主体部分结束-->
                <div class="clear"></div>



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
<div id="zhaos_opacity"></div>
<!--招商地区结束-->
<?php echo URL::webjs("person_zg.js")?>
<?php echo URL::webjs("zhaosArea.js")?>
<?php echo URL::webjs("common.source.js")?>
<?php echo URL::webjs("province.source.js")?>
<?php echo URL::webjs("deleteImg.js")?>