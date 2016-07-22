<?php echo URL::webcss("renzheng.css"); ?>
<!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>实名认证</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="person_id">
                <h3>通过实名认证的用户，更容易获得企业关注，成功进行项目合作！</h3>
                <div id="contant">
                    <p>姓名：<b><?php echo $personinfo['user_person']->per_realname;?></b></p>
                    <p>身份证：
                    <?php if(($personinfo['user_person']->per_identification_photo==""||$personinfo['user_person']->per_identification=="") and $personinfo['user_person']->per_auth_status == 0){ ?>
                    <?php echo Form::open(URL::website('person/member/basic/identificationcard'),array('method'=>'post','enctype'=>'multipart/form-data'))?>
                    <p><?php echo Form::file('per_identification_photo');
                             echo Form::image(NULL, NULL,array('src'=>URL::webstatic('images/infor3/upload.gif'),'class'=>'upload','style'=>'margin-bottom: -10px;margin-left:10px;'));
                             echo Form::close();
                             if (isset($error)) echo "<span style='color:#F00'>".$error."</span>";
                             ?></p>
                    <p class="tishi">身份证正面复印件必须上传，最多可上传两张（身份证正反复印件），支持JPG、GIF、PNG等，大小不超过2M</p>
                    <?php }?>
                    </div>
                <div class="photo">
                    <ul>
                        <li>
                            <?php
                            if($personinfo['user_person']->per_identification_photo!=""&&$personinfo['user_person']->per_auth_status==0){?>
                            <a href="<?php echo URL::website('person/member/basic/identificationcard/?img=photo')?>">删除图片</a>
                            <?php }if($personinfo['user_person']->per_identification_photo!=""){?>
                            <?php echo HTML::image($personinfo['user_person']->per_identification_photo)?>
                            <?php }?>
                        </li>
                        <li>
                            <?php
                            if($personinfo['user_person']->per_identification!=""&&$personinfo['user_person']->per_auth_status==0){?>
                            <a href="<?php echo URL::website('person/member/basic/identificationcard/?img=identification')?>">删除图片</a>
                            <?php }if($personinfo['user_person']->per_identification!=""){?>
                            <?php echo HTML::image($personinfo['user_person']->per_identification)?>
                            <?php }?>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <?php if(($personinfo['user_person']->per_identification_photo!=""||$personinfo['user_person']->per_identification!="") and $personinfo['user_person']->per_auth_status == 0){ ?>
                <div class="btn" style="height:42px; width:132px;"><a href="<?php echo URL::website('person/member/basic/identificationcard/?status=1')?>"><img src="<?php echo URL::webstatic("/images/person_id/id_btn.gif");?>" /></a></div>
                <?php }if($personinfo['user_person']->per_auth_status == 1){?>
                <div class="renzheng3">
                    <dl>
                        <dt><img src="<?php echo URL::webstatic("images/renzheng/renzheng3.gif"); ?>" width="60" height="88"/></dt>
                        <dd>
                            <em>您的身份证正在认证中，请耐心等待......</em>
                            <p><strong>如有任何疑问，欢迎联系我们：</strong></p>
                            <div>
                                <span class="span_0">电话：<b><?php $arrCustomerPhone = common::getCustomerPhone();echo $arrCustomerPhone[1]?></b></span>
                                <span class="span_1">邮箱：<a href="mailto:kefu@yjh.com">kefu@yjh.com</a></span>
                            </div>
                        </dd>
                    </dl>
                </div>
                <?php }if($personinfo['user_person']->per_auth_status == 2){?>
                <div class="renzheng2">
                    <dl>
                        <dt><img src="<?php echo URL::webstatic("images/renzheng/renzheng2.gif"); ?>" width="82" height="88"/></dt>
                        <dd><img src="<?php echo URL::webstatic("images/renzheng/succeed.gif"); ?>" width="143" height="53"/>
                            <p>恭喜您，您已经成功通过身份证认证！</p>
                        </dd>
                    </dl>
                </div>
                <?php }if($personinfo['user_person']->per_auth_status == 3){?>
                <div class="renzheng1">
                    <dl>
                        <dt><img src="<?php echo URL::webstatic("images/renzheng/renzheng1.gif") ?>"/></dt>
                        <dd>
                            <p class="para_0"><span>您的身份证认证没有通过，原因是：<?php echo $personinfo['user_person']->per_auth_unpass_reason?></span><a href="<?php echo URL::website('person/member/basic/identificationcard/?status=-1')?>"><img src="<?php echo URL::webstatic("images/renzheng/btn4.gif") ?>"/></a></p>
                            <p class="para_1"><strong>如有任何疑问，欢迎联系我们：</strong></p>
                            <p class="para_2"><span class="span">电话:<i><?php $arrCustomerPhone = common::getCustomerPhone();echo $arrCustomerPhone[1]?></i></span><span>邮箱：kefu@yjh.com</span></p>
                        </dd>
                    </dl>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
    <!--右侧结束-->