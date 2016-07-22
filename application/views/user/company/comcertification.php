<?php echo URL::webjs("renzheng.js");?>
<?php echo URL::webcss("renzheng.css"); ?>
    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>企业资质认证</span><div class="clear"></div></div>
        <div id="right_con">
           <div class="comrzContent" style=" padding:11px 20px 0 20px;">
                <h3 style="background:none; background: url(<?php echo URL::webstatic("images/renzheng/icon01.gif") ?>) no-repeat 12px 18px;">通过诚信认证的企业更能获得投资者的青睐</h3>
                <div class="scListContent">

                   <div class="renzheng_consult">
                      <span>企业营业执照：</span>
                      <p>
                          <?php
                            if(isset($commonimg_list['com_business_licence'])):
                            foreach ($commonimg_list['com_business_licence'] as $k=>$v):
                            ?>

                                   <?php if(!empty($v['url'])){ echo "<span>".HTML::image(URL::imgurl($v['url']))."</span>";}?>

                            <?php
                            endforeach;
                            endif;
                            ?>
                      </p>
                      <div class="clear"></div>
                   </div>

                   <div class="renzheng_consult" style="padding-bottom:10px;">
                      <span>组织机构代码证：</span>
                      <p>
                       <?php
                            if(isset($commonimg_list['organization_credit'])):
                            foreach ($commonimg_list['organization_credit'] as $k=>$v):
                            ?>

                                   <?php if(!empty($v['url'])){ echo "<span>".HTML::image(URL::imgurl($v['url']))."</span>";}?>

                            <?php
                            endforeach;
                            endif;
                            ?>
                      </p>
                      <div class="clear"></div>
                   </div>

                     <?php if(isset($commonimg_list['tax_certificate'])){ ?>
                    <div class="renzheng_consult">
                      <span>税务登记证：</span>
                      <p>
                      <?php
                            foreach ($commonimg_list['tax_certificate'] as $k=>$v){
                            ?>

                                   <?php if(!empty($v['url'])){ echo "<span>".HTML::image(URL::imgurl($v['url']))."</span>";}?>

                            <?php } ?>
                      </p>
                      <div class="clear"></div>
                   </div>
                   <?php }?>


                </div>
<?php if($tax_certificate_status==2||$com_business_licence_status==2||$organization_credit_status==2){?>
                <div class="renzheng1">
                    <dl>
                        <dt><img src="<?php echo URL::webstatic("images/renzheng/renzheng1.gif") ?>"/></dt>
                        <dd>
                            <p class="para_0"><span>您的企业诚信认证没有通过，原因是：<?php if(!empty($com_auth_unpass_reason)){echo $com_auth_unpass_reason;}else{?>资质图片不合要求<?php }?></span><a href="<?php echo URL::website('/company/member/basic/uploadCertification/'); ?>"><img src="<?php echo URL::webstatic("images/renzheng/btn4.gif") ?>"/></a></p>
                            <p class="para_1"><strong>如有任何疑问，欢迎联系我们：</strong></p>
                            <p class="para_2"><span class="span">电话:<i><?php $arrCustomerPhone = common::getCustomerPhone();echo $arrCustomerPhone[1]?></i></span><span>邮箱：kefu@yjh.com</span></p>
                        </dd>
                    </dl>
                </div>
<?php }elseif($tax_certificate_status==1&&$com_business_licence_status==1&&$organization_credit_status==1){?>
                 <div class="renzheng2" style="padding-bottom:20px;">
                    <dl>
                        <dt><img src="<?php echo URL::webstatic("images/renzheng/renzheng2.gif"); ?>" width="82" height="88"/></dt>
                        <dd><img src="<?php echo URL::webstatic("images/renzheng/succeed.gif"); ?>" width="143" height="53"/>
                            <p>恭喜您，您已经成功通过企业诚信认证！</p>
                        </dd>
                    </dl>
                    <div class="clear"></div>
                </div>
<?php }else{?>
                <div class="renzheng3">
                    <dl>
                        <dt><img src="<?php echo URL::webstatic("images/renzheng/renzheng3.gif"); ?>" width="60" height="88"/></dt>
                        <dd>
                            <em>您的企业诚信正在认证中，请耐心等待......</em>
                            <p><strong>如有任何疑问，欢迎联系我们：</strong></p>
                            <div>
                                <span class="span_0">电话：<b><?php $arrCustomerPhone = common::getCustomerPhone();echo $arrCustomerPhone[1]?></b></span>
                                <span class="span_1">邮箱：<a href="mailto:kefu@yjh.com">kefu@yjh.com</a></span>
                            </div>
                        </dd>
                    </dl>
                </div>
<?php }?>
           </div>
        </div>
    </div>
    <!--右侧结束-->
