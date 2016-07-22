<?php echo URL::webjs("apply_zst.js");?>
<!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>申请招商外包服务</span><div class="clear"></div></h2>
                    <div class="apply_zst1">
                    <form method="post" action="" id="business">
                        <p class="tishi"><b>请填写申请信息</b>（<em>*</em>为必填项）</p>
                        <p><span><em>*</em>企业名称：</span><b><?=$companyinfo->com_name?></b></p>
                        <p><span><em>*</em>联系人：</span><input type="text" class="text1" name="contact_name" value="<?=$companyinfo->com_contact?>" id="link"/></p>
                        <p><span><em>*</em>联系电话：</span><input type="text" class="text1" name="business_phone" value="<?=$com_phone?>" id="phone"/> 分机号 <input type="text" class="text3" name="branch_phone" value="<?=$branch_phone?>" id="branch"/></p>
                        <p><span>手机号码：</span><input type="text" class="text1" value="<?=$user->mobile?>" name="business_mobile" id="mobile"/></p>
                        <p><span>联系邮箱：</span><strong><?=$user->email?></strong></p>
                        <p><span><em>*</em>联系地址：</span><input type="text" class="text2" value="<?=$companyinfo->com_adress?>" name="business_address" id="address"/></p>
                        <input type="hidden" name="user_id" value="<?=$user->id?>">
                        <p><span></span><a href="#" class="ensure"><img src="<?php echo URL::webstatic("images/apply_zst/ensure.png") ?>" /></a><em class="tishi" style="visibility: hidden;" id="tishi">请把信息填写完整...</em></p>
                        </form>
                    </div>
                </div>
                <!--主体部分结束-->
<!--弹出层开始-->
<div id="opacity_box"></div>
<div class="apply_zst1_box">
    <a href="#" class="close"></a>
    <h3>请确认您填写的申请信息：</h3>
    <p>企业名称：<?=$companyinfo->com_name?></p>
    <p>联系人：<span class="link"></span></p>
    <p>联系电话：<span class="phone"></span></p>
    <p>联系邮箱：<?=$user->email?></p>
    <p>联系地址：<span class="address"></span></p>
    <p class="last"><a href="#" class="submit"><img src="<?php echo URL::webstatic("images/apply_zst/ensure.png") ?>" /></a><a href="#" class="cancel"><img src="<?php echo URL::webstatic("images/apply_zst/cancel.png") ?>" /></a></p>
</div>
<!--弹出层结束-->