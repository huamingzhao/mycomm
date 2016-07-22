<?php echo url::webcss("/platform/common.css")?>
<?php echo url::webcss("/platform/base_infor.css")?>
<?php echo URL::webjs("invitepro_zg.js")?>
<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>

<div class="base_infor" style="height:auto;">
   <div class="base_infor_contain">
     <div class="base_infor_left">
       <div class="base_infor_left01"></div>
       <div class="base_infor_left02">
          <p class="base_infor_title"><img src="<?php echo url::webstatic('images/platform/base_infor_yd/step02_logo.png')?>" /></p>

          <form action="/member/comlead" id="form_id" method="post">
          <ul>
          <li>
             <label>公司名称：</label>
             <div class="base_infor_detail_r">
                 <strong><?php echo $user_name?></strong>
             </div>
             <div class="clear"></div>
          </li>
          <li>
         <label><font class="require1">*</font>联系人：</label>
         <div class="base_infor_detail_r" id="base_infor_detail_r2">
             <input name="com_contact" type="text" class="base_infor_text01" id="com_contact_text" />
             <div class="base_infor_tx">
               <div class="base_infor_tx_l"></div>
               <div class="base_infor_tx_z" id="name_error2">请输入您的真实姓名</div>
               <div class="base_infor_tx_r"></div>
             </div>
         </div>
         <div class="clear"></div>
        </li>
          <li>
             <label><font class="require1">*</font>手机号码：</label>

             <div class="base_infor_detail_r" id="phone_error_div_id">
             <?php if( $user->mobile=='' ){?>
                 <input name="mobile" type="text" class="base_infor_text01" id="phone_yz_id"  />
                 <div class="base_infor_tx">
                   <div class="base_infor_tx_l"></div>
                   <div class="base_infor_tx_z" id="phone_error">验证手机号，表明您对招商是认真的</div>
                   <div class="base_infor_tx_r"></div>
                 </div>

             </div>
             <?php }else{?>
             <!--手机已验证-->
                 <span style="padding-right:5px"><?php echo $user->mobile;?></span>
                    <?php if( $user->valid_mobile=='0' ){?>
                        <img src="<?php echo URL::webstatic('/images/infor1/tel_nocheck.jpg')?>" style="margin-top: 5px;">
                    <?php }else{?>
                         <img src="<?php echo URL::webstatic('/images/infor1/tel_check.jpg')?>" style="margin-top: 5px;">
                    <?php }?>
            <?php }?>

             <div class="clear"></div>
          </li>
          <li class="clearfix">
             <label><font class="require1">*</font>邮箱：</label>

             <div class="base_infor_detail_r" id="yzemail">
             <?php if( $user->email=='' ){?>
                 <input name="per_mail" type="text" class="base_infor_text01" id="per_mail" />
                 <p class="tishiinfo" style="color:red; line-height: 30px; height: 30px; float: left; width: 150px; margin-left: 10px;">邮箱不能为空</p>
            <?php }else{?>
                 <!--邮箱已验证-->
                 <span style="padding-right:5px"><?php echo $user->email?></span>
                 <?php if( $user->valid_email=='0' ){?>
                    <img src="<?php echo URL::webstatic('/images/infor1/tel_nocheck.jpg')?>" style="margin-top: 5px;">
                 <?php }else{?>
                     <img src="<?php echo URL::webstatic('/images/infor1/tel_check.jpg')?>" style="margin-top: 5px;">
                 <?php }?>

           <?php }?>
             </div>

          </li>
          <li>
             <label><font class="require1">*</font>座机号码：</label>
             <div class="base_infor_detail_r">
                 <input name="com_phone" type="text" class="base_infor_text04 zuojitext" maxlength="20" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9-]+/,'');}).call(this)"/><input name="branch_phone" type="text" class="base_infor_text01" value="分机号" id="branch_phone_text" maxlength="6" maxlength="5" onkeyup="this.value=this.value.replace(/[^0-9]/gi,'')" />
                 <p class="tishiinfo" style="color:red; line-height: 30px; height: 30px; float: left; width: 110px; margin-left: 10px;">座机号码不能为空</p>
             </div>
             <div class="clear"></div>
          </li>
          <li>
             <label><font class="require1">*</font>公司成立时间：</label>
             <div class="base_infor_detail_r">
                 <input name="com_founding_time" type="text" class="base_infor_text04 timetishi" value=""onClick="WdatePicker( {dateFmt:'yyyy-MM',realDateFmt:'yyyy-MM'} )" readonly />
                 <p class="tishiinfo" style="color:red; line-height: 30px; height: 30px; float: left; width: 110px; margin-left: 10px;">公司成立时间</p>
            </div>
             <div class="clear"></div>
          </li>
          <li>
             <label>公司性质：</label>
             <div class="base_infor_detail_r">
            <?php
            $soure = common::comnature();
            foreach ($soure as $k=>$v){

            ?>
            <span><input name="com_nature" type="radio" value="<?php echo $k?>" <?php if( $k==0 ){?>checked<?php }?> /> <?php echo $v?></span>
            <?php }?>
             </div>
             <div class="clear"></div>
          </li>
          <li>
             <label><font class="require1">*</font>公司注册资本：</label>
             <div class="base_infor_detail_r">
                <span><input name="com_registered_capital" type="text" class="base_infor_text01 zhucetishi" onkeyup="this.value=this.value.replace(/[^0-9]/gi,'')" maxlength="10"/> 万</span>
                <p class="tishiinfo" style="color:red; line-height: 30px; height: 30px; float: left; width: 110px; margin-left: 10px;">注册资本不能为空</p>
            </div>
             <div class="clear"></div>
          </li>
          <li>
             <label><font class="require1">*</font>公司地址：</label>
             <div class="base_infor_detail_r">
                <select class="cascadingDropList" name="com_area" id="address">
                    <option>请选择地区</option>
                    <?php
                            if( !empty( $area ) ){
                                foreach ( $area as $v ){
                                ?>
                                    <option value="<?php echo $v['cit_id']?>"><?php echo $v['cit_name']?></option>
                                <?php
                                }
                            }
                            ?>
                </select>
                <select name="com_city" id="address1">
                    <option>不选</option>

                </select>
                 <input name="com_adress" type="text" class="base_infor_text02" maxlength="30" placeholder="县级以下详细地址"/>
                 <p class="tishiinfo" style="color:red; line-height: 30px; height: 30px; float: left; width: 110px; margin-left: 10px;">公司地址不能为空</p>
              </div>
             <div class="clear"></div>
          </li>
          <li>
             <label></label>
             <div class="base_infor_detail_r">
                 <a href="javascript:void(0)" class="base_infor_next floleft">下一步</a>


                 <?php
                if( $fromly==1 ){
                ?>
                       <a href="<?php echo Kohana::$config->load('site')->get('875domain')?>" class="returnnext">跳过</a>
                <?php }else{?>
                    <a href="<?php echo URL::website('')?>" class="returnnext">跳过</a>
                <?php }?>

             </div>
             <div class="clear"></div>
          </li>
          </ul>
          </form>

          <div class="clear"></div>
       </div>
       <div class="base_infor_left03"></div>
       <div class="clear"></div>
     </div>
     <div class="base_infor_right">
       <span class="base_infor_right_per"><img src="<?php echo url::webstatic('images/platform/base_infor_yd/comp_text01.png')?>" /></span>
     </div>
     <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>
<?php echo $script?>
<?php echo URL::webjs("comlead.js")?>
