<?php echo url::webcss("touzi_security.css");?>
<?php echo url::webjs("header.js");?>
<?php echo url::webjs("del_shenhe.js")?>
<?php echo URL::webjs("touzi.js")?>
<!--中部开始-->


                <!--主体部分开始-->
                <form id="form_id" action="/company/member/guard/server">
                    <div class="right">
                        <h2 class="user_right_title"><span><a href="/company/member/guard/" class="touzi_security_title">投资保障</a>> 服务保障</span><div class="clear"></div></h2>
                        <!--投资保障-->
                        <div class="touzi_security_main">

                            <div class="touzi_security_main_fw">
                                <div class="touzi_security_fw_title">
                                    <b>请填写以下申请信息</b>
                                    <em>
                                        （
                                        <strong>*</strong>
                                        为必填项）
                                    </em>
                                </div>

                         <?php
                                                                                                    // 如果手机号已经验证了,就不用显示了
                                                                                                    if ($mobile == "0") {
                                                                                                       //  if ($mobile == "1") {
                                                                                                        ?>
                         <div
                                    class="touzi_security_fw_cont touzi_security_fw_cont_first">
                                    <div class="touzi_security_fw_list">
                                        <label>
                                            <em>*</em>
                                            手机号码：
                                        </label>
                                        <p>
                                            <input name="receiver" type="text"
                                                class="touzi_security_fw_text01" id="phone_yz_id" />
                                            <span class="touzi_security_yz_tel" id="phone_error">
                                                <a href="javascript:void(0)" onclick="sendCode()">获取验证码</a>
                                            </span>
                                        </p>
                                        <div class="clear"></div>
                                    </div>

                                    <!--<div class="touzi_security_fw_list" id="tel_code_id" style="display: none;">
                               <label></label>
                               <p style="border:1px solid #dbdbdb; width:280px;padding-left: 10px;"><em style="display:block; font:normal 12px/40px '微软雅黑'; float: left;padding-right: 4px;">您如果没有收到验证码</em><span class="touzi_security_yz_tel" style="margin-top:6px;"><a href="#">获取验证码</a></span></p>
                               <div class="clear"></div>
                             </div>  -->


                                    <div class="touzi_security_fw_list">
                                        <label>输入验证码：</label>
                                        <p>
                                            <input name="check_code" type="text"
                                                class="touzi_security_fw_text01" />
                                        </p>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                        <?php }?>

                         <div class="touzi_security_fw_cont">

                                    <div class="touzi_security_fw_list">
                                        <label>
                                            <em>*</em>
                                            您有加盟店：
                                        </label>
                                        <p>
                                            <input name="store_num" type="text" class="touzi_security_fw_text02" value="<?php if( ceil($store_num)!=0 ){echo $store_num;}?>" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9]/gi,'')" id="touzi_security_fw_text02"/>
                                            <span class="touzi_security_yz_text">家</span>
                                            <span class="my_bussines_bobao_sm">
                                                如没有
                                                <small>3家</small>
                                                或以上加盟店，将不会为您通过服务保障审核。
                                            </span>
                                            <span style="color:#f00;" id="touzi_error1"></span>
                                        </p>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="touzi_security_fw_list">

                                        <label>
                                            <em>*</em>
                                            加盟店地址：
                                        </label>
                                        <p>
                                        <div id="add_html_id">
                               <?php
                                                                                                                            if (empty ( $address_arr )) {
                                                                                                                                for($i = 1; $i <= 3; $i ++) {
                                                                                                                                    ?>
                                       <div class="touzi_security_fw_jionadd" id="add_html_id_<?php echo $i?>">
                                            <select name="area[]" id="address_<?php echo $i?>"
                                                onchange="set_area( '<?php echo $i?>' )">
                                                <option value="">请选择省</option>
                                   <?php
                                                                                                                                    if (! empty ( $area )) {
                                                                                                                                        foreach ( $area as $v ) {
                                                                                                                                            ?>
                                        <OPTION
                                                    value="<?php echo $v['cit_id']?>"><?php echo $v['cit_name']?></OPTION>
                                   <?php
                                                                                                                                        }
                                                                                                                                    }

                                                                                                                                    ?>
                                   </select>

                                            <select name="area_id[]" id="city_<?php echo $i?>">
                                                <option value="">请选择市</option>
                                            </select>

                                            <input name="address[]" type="text" id="input_address_<?php echo $i?>"
                                                class="touzi_security_fw_text03" />
                                            <span >
                                                <a style="display: none" class="del_span_class" href="javascript:void(0)" onclick="delHtml('<?php echo $i?>')">删除</a>

                                            </span>
                                            <ins id="error_input_address_<?php echo $i?>" style="color: #FF0000"></ins>
                                            <div class="clear"></div>
                                        </div>
                                <?php
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                foreach ( $address_arr as $i => $address_vss ) {
                                                                                                                                    ?>
                                    <div
                                            class="touzi_security_fw_jionadd" id="add_html_id_<?php echo $i?>">
                                            <select name="area[]" id="address_<?php echo $i?>"
                                                onchange="set_area( '<?php echo $i?>' )">
                                                <option value="">请选择省</option>
                                   <?php
                                                                                                                                    if (! empty ( $area )) {
                                                                                                                                        foreach ( $area as $v ) {
                                                                                                                                            ?>
                                        <OPTION
                                                    value="<?php echo $v['cit_id']?>"
                                                    <?php if( $address_vss['area_id']==$v['cit_id'] ){?>
                                                    selected <?php }?>><?php echo $v['cit_name']?></OPTION>
                                   <?php
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                    ?>
                                   </select>

                                            <select name="area_id[]" id="city_<?php echo $i?>">
                                                <option value="">请选择市</option>
                                                <?php
                                                                                                                                    foreach ( $address_vss ["cityarea"] as $vs ) {
                                                                                                                                        ?>
                                                <OPTION
                                                    value="<?php echo $vs["id"]?>"
                                                    <?php if( $address_vss['city_id']==$vs['id'] ){?> selected
                                                    <?php }?>><?php echo $vs['name']?></OPTION>
                                                <?php }?>
                                            </select>

                                            <input name="address[]" type="text" id="input_address_<?php echo $i?>"
                                                value="<?php echo $address_vss["address"]?>"
                                                class="touzi_security_fw_text03" />

                                            <span>
                                            <?php
                                            //if( count( $address_arr )>3 ){
                                            ?>
                                                <a <?php if( count( $address_arr )<=3 ){?>style="display: none"<?php }?> class="del_span_class" href="javascript:void(0)" onclick="delHtml('<?php echo $i?>')">删除</a>
                                            <?php //}?>
                                            </span>
                                            <ins id="error_input_address_<?php echo $i?>" style="color: #FF0000" ></ins>
                                            <div class="clear"></div>
                                        </div>


                                    <?php
                                                                                                                                }
                                                                                                                            }

                                                                                                                            ?>
</div>
<INPUT type="hidden" id="count_area_id" value="<?php echo $i?>" >

                               <div class="touzi_security_fw_jionadd">
                                            <a href="javascript:void(0)" onclick="add_store_html()">添加加盟地地址</a>
                                        </div>
                                        </p>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="touzi_security_fw_list">
                                        <label>
                                            <em>*</em>
                                            加盟店图片：
                                        </label>
                                        <p>

                                            <span>
                                                <object width="112" height="32" id="flashrek2"
                                                    name="fileId4"
                                                    codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0"
                                                    classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
                                                    <param
                                                        value="<?php echo URL::webstatic('/flash/uploadimage_company.swf?fun=previewImage')?>"
                                                        name="movie">
                                                    <param value="high" name="quality">
                                                    <param value="transparent" name="wmode">
                                                    <param value="always" name="allowScriptAccess">
                                                    <embed width="112" height="32"
                                                        type="application/x-shockwave-flash"
                                                        pluginspage="http://www.macromedia.com/go/getflashplayer"
                                                        quality="high" wmode="transparent" id="fileId4"
                                                        allowscriptaccess="always"
                                                        src="<?php echo URL::webstatic('/flash/uploadimage_company.swf?fun=previewImage')?>">

                                                </object>
                                            </span>

                                            <span class="my_bussines_bobao_sm">
                                                请上传至少
                                                <small>3张</small>
                                                不同加盟店的图片
                                            </span>
                                            <span id="touzi_error2" style="color:#f00;padding-left:10px;"></span>

                                        <div class="clear"></div>
                                        <ul id="show_img_id">
                                        <?php
                                        if( !empty( $img ) ){
                                            foreach ( $img as $ni=>$vi ){
                                        ?>
                                            <li id="li_id_<?php echo $ni?>"><input type='hidden' name='img_src[]' value="<?php echo $vi["url"]?>"><img src="<?php echo url::imgurl( $vi['url'] )?>"><em onclick="removeImage('<?php echo $ni?>')">删除图片</em></li>

                                        <?php
                                            }
                                        }
                                        ?>

                                        </ul>

                                        <div class="clear"></div>
                                        </p>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>

                                </div>

                            </div>
                            <div class="touzi_security_fw_list">
                                <label></label>
                                <p>
                                    <a href="javascript:void(0)" onclick=""
                                        class="touzi_security_fw_btn" id="touzi_security_fw_btn"></a>
                                </p>
                                <div class="clear"></div>
                            </div>
                        </div>

                    </div>
                    <!--主体部分结束-->
                </form>
                <div class="clear"></div>



