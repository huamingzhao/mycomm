<style>
#infor1 #ryl_tel_check strong{font-family:Arial,"宋体"; font-weight:normal;}
#infor1 #ryl_tel_check b{ float:left; font-weight:normal; padding-top:2px; padding-right:10px;}
#infor1 #ryl_tel_check a{ float:left; color:#0036ff; font-size:12px;}
</style>
        <!--右侧开始-->
        <div class="right">
            <div class="user_right_title">
                <span>企业基本信息管理</span>
                <div class="clear"></div>
            </div>
            <div class="user_right_content">
                <div >
                    <p><label>公司名称：</label><b><?php echo $user_name?></b></p>
                    <p><label>公司logo：</label><?php if ($companyinfo->com_logo!=""){echo '<img style="max-width:110px;max-height:85px;" src="'.URL::imgurl($companyinfo->com_logo).'">';}?></p>
                    <p><label>座机电话：</label><em><b><?php echo $com_phone;?><?if($branch_phone) echo '-'.$branch_phone;?></b></em></p>
                    <?php if(!empty($mobile)){?><p id="ryl_tel_check"><label>手机号码：</label><strong style=" padding-right:10px; font-weight:normal;"><?=$mobile?></strong><b><?if($mobile) {if(!$user_info->valid_mobile){?><img src="<?php echo URL::webstatic("images/infor1/tel_nocheck.jpg")?>" /></b><a href="/company/member/valid/mobile">去验证</a><?}else{?><img src="<?php echo URL::webstatic("images/infor1/tel_check.jpg")?>" /></b><a href="/company/member/valid/mobile?to=change">修改手机号码</a><?}?></p><?php }}?>

                    <p><label>邮箱：</label><?=$email?></p>
                    <p><label>联 系 人：</label><?php echo $companyinfo->com_contact;?></p>
                    <p><label>公司性质：</label>
                    <?php
                    $soure = common::comnature();
                    foreach ($soure as $k=>$v){
                        if ($k==$companyinfo->com_nature)
                            echo $v;
                    }
                    ?></p>
                    <p><label>公司成立时间：</label>
                    <?php if( isset( $companyinfo->com_founding_time ) ){?>
                    <?php echo UTF8::substr( $companyinfo->com_founding_time,0,4 )?>-<?php echo UTF8::substr( $companyinfo->com_founding_time,4 )?>
                    <?php }?>
                    </p>
                    <p><label>公司注册资本：</label><?php echo $companyinfo->com_registered_capital?>万</p>
                    <?php if( $companyinfo->com_site!='' ){?>
                    <p><label>公司网址：</label><?php echo $companyinfo->com_site;?></p>
                    <?php }?>
                    <p><label>公司地址：</label><?php echo $area_name.$city_name.$companyinfo->com_adress;?></p>
                    <p style="height:auto;"><label>公司简介：</label>
                    <?php $string = (htmlspecialchars_decode(HTML::chars($companyinfo->com_desc, 0)));echo mb_strimwidth(strip_tags($string), 0, 125, "......");?>
                    </p>
                    <p class="infor1_btn"><a class="pAlinkStyle" href="<?php echo URL::website('/company/member/basic/editCompany/');?>?type=1"></a>   <?php if($visit_card==0){?><a href="<?php echo URL::website('/company/member/card/mycard');?>">您的企业名片已经生成，现在去看看吧!</a><?php }?></p>
                    <p>
                    <a class="icon02_modify project_home_btn1" href="<?php echo URL::website("company/member/basic/editCompany").'?type=1';?>">修改</a>
                    </p>
                <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
