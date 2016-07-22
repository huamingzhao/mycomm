<?php echo URL::webcss("common.css")?>
<?php echo URL::webcss("per_infor_0502.css")?>
<?php echo URL::webjs("header.js")?>
<?php echo URL::webjs("invitepro_zg.js")?>
<?php echo URL::webjs("person_new.js")?>
<?php echo URL::webjs("personinfo_vali.js")?>
<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>
                <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>基本信息管理</span><div class="clear"></div></h2>
                       <div class="per_infor_0502">

                      <ul class="per_infor_title">
                      <li><a href="<?php echo URL::website("/person/member/basic/person")?>" class="current"><img src="<?php echo URL::webstatic("images/per_infor/icon01.jpg")?>" style="padding:21px 10px 0 62px;">我的基本信息</a></li>
                      <li><a href="<?php echo URL::website("/person/member/basic/personInvestShow")?>">意向投资信息</a></li>
                      <li class="last"><a href="<?php echo URL::website("/person/member/basic/experience")?>">从业经验</a></li>
                      </ul>


                      <form id="form_id" action="<?php echo URL::website('/person/member/basic/personupdate').'?type='.$type?>" method="POST">
                      <div class="per_infor_content">
                         <h4><b>我的基本信息</b>（<em>*</em>为必填项）</h4>

                         <p>
                         <span class="per_infor_left"><em>*</em> 姓名：</span><span class="per_infor_right"><input id="per_realname" class="per_infor_long_text" name="per_realname" type="text" value="<?php echo isset($personinfo->per_realname) ? $personinfo->per_realname : "请输入您的真实姓名";?>">
                         <span id="etxt1" style="color:red; display:none;">* 请输入您的真实姓名</span>
                         </span>
                         <div class="clear"></div>
                         </p>

                         <input type="hidden" name="per_id"  value="<?php echo isset($personinfo->per_id) ? $personinfo->per_id : '';?>"  />


                         <p><span class="per_infor_left"><em>*</em> 性别：</span><span class="per_infor_right"><input class="per_infor_radio" name="per_gender" type="radio" value="1" checked="ckecked" <?php if(isset($personinfo->per_gender) && ($personinfo->per_gender == 1)){ ?>checked<?php } ?> ><span>男</span><input class="per_infor_radio" name="per_gender" type="radio" value="2" <?php if(isset($personinfo->per_gender) && ($personinfo->per_gender == 2)){ ?>checked<?php } ?>><span>女</span></span>
                         <div class="clear"></div>
                         </p>


                         <p>
                         <span class="per_infor_left"><em>*</em> 出生日期：</span><span class="per_infor_right"><input id="per_birthday" class="per_infor_long_text" name="per_birthday" type="text" value="<?php if( isset( $personinfo->per_birthday ) && $personinfo->per_birthday!=0) { echo date("Y-m-d",$personinfo->per_birthday); }?>" onclick="WdatePicker()" readonly="readonly">
                         <span id="etxt2" style="color:red; display:none;">* 请输入您的出生日期</span>
                         </span>
                         <div class="clear"></div>
                         </p>

                         <?php
                         //手机号码未填写

                         if( $mobile=='' ){
                         ?>
                         <p>
                           <span class="per_infor_left"><em>*</em> 手机号码：</span>
                           <span class="per_infor_right">
                              <input id="per_phone" class="per_infor_long_text" name="per_phone" type="text" value="请输入您的11位手机号码">
                              <span id="etxt3" style="color:red; display:none;">* 请输入正确的手机号码</span>
                              <!--<span class="per_infor_message">手机号码格式错误，请重新输入...</span>-->
                              </span>
                              <div class="clear"></div>
                         </p>
                         <?php
                         }else{
                             //手机号码已填写,未验证
                             if( !$user->valid_mobile) {
                         ?>
                            <p>
                            <span class="per_infor_left"><em>*</em> 手机号码：</span>
                            <span class="per_infor_right">
                            <span class="per_infor_mail"><?php echo $mobile?><input id="per_phone" type="hidden" name="per_phone" value="<?php echo $mobile?>"></span>
                            <a href="/person/member/valid/mobile?to=change" class="per_infor_check_img">
                            <img src="<?php echo URL::webstatic("images/per_infor/no_check.jpg")?>">
                            </a>
                            <a class="per_infor_check_text" href="/person/member/valid/mobile?to=change">去验证</a>
                            </span>
                            <div class="clear"></div>
                            </p>

                          <?php
                             }else{
                                //手机号码已填写，已验证
                          ?>
                          <p>
                            <span class="per_infor_left"><em>*</em> 手机号码：</span>
                            <span class="per_infor_right">
                            <span class="per_infor_mail"><?php echo $mobile?><input id="per_phone" type="hidden" name="per_phone" value="<?php echo $mobile?>"></span>
                            <a href="/person/member/valid/mobile?to=change" class="per_infor_check_img">
                            <!--<img src="<?php echo URL::webstatic("images/per_infor/no_check.jpg")?>">-->
                            <img src="<?php echo URL::webstatic("images/infor1/tel_check.jpg")?>">
                            </a>
                            <a class="per_infor_check_text" href="/person/member/valid/mobile?to=change">修改手机号码</a>
                            </span>
                            <div class="clear"></div>
                            </p>
                          <?php
                             }
                         }
                         ?>

                         <p><span class="per_infor_left"><em></em> 邮箱：</span><span class="per_infor_right per_infor_mail"><?php echo isset($email) ? $email : '';?></span><div class="clear"></div></p>


                         <p>
                            <span class="per_infor_left"><em>*</em> 目前所在地：</span>
                            <span class="per_infor_right">
                            <select name="per_area" id="address">
                                <option value="">请选择</option>
                            <?php
                            if( !empty( $area ) ){
                                foreach ( $area as $v ){
                                ?>
                                    <option value="<?php echo $v['cit_id']?>" <?php if($v['cit_id']==$pro_id ){echo "selected='selected'";}?>><?php echo $v['cit_name']?></option>
                                <?php
                                }
                            }
                            ?>
                            </select>
                            <select name="area_id" id="address1">
                                <option value="" >不选</option>
                                <?php
                                if( $areaIds!=0 && !empty($cityarea) ){
                                    foreach ($cityarea as $v){
                                ?>
                                    <option value="<?=$v->cit_id ;?>" <?php if($v->cit_id == $areaIds) echo "selected='selected'"; ?> ><?=$v->cit_name;?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                            <span id="etxt7" style="color:red; display:none;">* 请选择目前所在地</span>
                            </span>
                         <div class="clear"></div></p>
                         <p class="per_infor_more"><a href="javascript:void(0)" onclick="clickInfoDisplay('show_id')">收起更多信息</a></p>
                         <div id="show_id" style="display:block;">

                             <p>
                             <span class="per_infor_left"><em></em> QQ：</span><span class="per_infor_right"><input id="per_qq" class="per_infor_long_text" name="per_qq" type="text" value="<?php echo isset( $personinfo->per_qq ) ? $personinfo->per_qq : ''?>">
                             <span id="etxt4" style="color:red; display:none;">* 请输入正确的QQ</span>
                             </span>
                             <div class="clear"></div>
                             </p>

                             <p>
                             <span class="per_infor_left"><em></em> 身份证号：</span><span class="per_infor_right"><input id="per_card_id" class="per_infor_long_text" name="per_card_id" type="text" value="<?php echo isset( $personinfo->per_card_id ) ? $personinfo->per_card_id : ''?>">
                             <span id="etxt5" style="color:red; display:none;">* 请输入正确的身份证号</span>
                             </span>
                             <div class="clear"></div>
                             </p>


                             <p><span class="per_infor_left"><em></em> 我的学历：</span><span class="per_infor_right">
                                <select name="per_education" onchange="school_input_show(this.value)">
                                    <option value="">请选择</option>
                                    <?php
                                    if( !empty( $edu_arr ) ){
                                        foreach ( $edu_arr as $k=>$value ){
                                    ?>
                                        <option value="<?php echo $k?>" <?php if( $personinfo->per_education==$k ){?> selected<?php } ?>><?php echo $value?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select></span><div class="clear"></div></p>


                             <p id="per_school_id" <?php if( $personinfo->per_education!='' ){?>style="display:block;"<?php }else { ?>style="display:none;"<?php } ?>><span class="per_infor_left"><em></em> 毕业学校：</span>
                             <span class="per_infor_right"><input id="per_school" class="per_infor_long_text" name="per_school" type="text" value="<?php echo isset( $personinfo->per_school ) ? $personinfo->per_school : ''?>">
                             <span id="etxt6" style="color:red; display:none;">* 请输入正确的毕业学校</span>
                             </span>
                             <div class="clear"></div>
                             </p>

                         </div>


                         <p class="per_infor_btn"><a id="bc" href="javascript:void(0)" class="ryl_btn01">保存</a></p>


                         <div class="clear"></div>
                      </div>
                      </form>

                      <div class="clear"></div>
                    </div>
                </div>
                <!--主体部分结束-->
                <div class="clear"></div>

