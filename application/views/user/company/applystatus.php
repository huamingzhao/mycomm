<?php echo URL::webcss("my_bussines.css")?>
 <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>申请招商外包服务</span><div class="clear"></div></h2>
                    <div class="ryl_applicat_cont">
                        <div class="ryl_applicat_card">
                           <div class="ryl_applicat_card01"></div>
                           <div class="ryl_applicat_card02">
                                <p>企业名称：<?=$companyinfo->com_name?></p>
                                <p>联系人：<?=$applyinfo->contact_name?></p>
                                <p>联系电话：<?=$phone?></p>
                                <p>联系邮箱：<?=$user->email?></p>
                                <p>联系地址：<?=$applyinfo->business_address?></p>
                                <div class="clear"></div>
                           </div>
                           <div class="ryl_applicat_card03"></div>
                        <div class="clear"></div>
                        </div>
                        <p class="ryl_applicat_ok">恭喜您！<?=$companyinfo->com_name?></p>
                        <span class="ryl_applicat_oktext01">您申请招商外包服务的信息已成功提交，我们会在<b>3</b>个工作日由招商外包服务专家联系您。谢谢！</span>
                        <div class="clear"></div>
                        <span class="ryl_applicat_oktext02"><em>招商外包服务热线：</em><b><?php $arrCustomerPhone = common::getCustomerPhone();echo $arrCustomerPhone[1]?></b></span>
                        <div class="clear"></div>
                    </div>

              </div>
 <!--主体部分结束-->