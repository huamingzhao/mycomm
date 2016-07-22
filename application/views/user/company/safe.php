<?php echo URL::webcss("my_bussines.css")?>
<?php echo URL::webcss("account_zg.css")?>
<?php echo URL::webjs("header.js")?>
<?php echo URL::webjs("del_shenhe.js")?>

            <div id="right" class="right">
            <div class="user_right_title">
                <span>安全中心</span>
                <div class="clear"></div>
            </div>
                    <div style="padding-left:19px; padding-top:10px;" id="right_con">
                       <div class="ryl_safe_center">
                        <ul>
                        <li>
                            <span class="safe_password"></span>
                            <p>
                            <b>登录密码</b>
                            <em>互联网账号存在被盗风险，建议您定期更改密码以保护账户安全。</em>
                            </p>
                            <a href="<?php echo URL::website('company/member/basic/modifypassword')?>" class="safe_link_01">修改</a>
                            <div class="clear"></div>
                        </li>
                        <li class="safe_shadow"></li>
                        <li>
                            <span class="safe_mail"></span>
                            <p>
                            <b>邮箱验证</b>
                            <?php if( isset( $usermail ) ){?>
                                <?php if( $valid_email=='1' ){?>
                                    <em>您验证的邮箱：<strong><?php echo $usermail?></strong> 已验证</em>
                                    <em style="position:relative; top: -10px;">邮箱最多只能修改2次！如有疑问请拨打客服电话400 1015 908</em>
                                <?php }else{?>
                                    <em>您验证的邮箱：<strong><?php echo $usermail?></strong> 未验证</em>
                                <?php }?>
                            <?php }else{?>
                                <em>验证邮箱有利于你找回密码，增加信誉</em>
                            <?php }?>
                            </p>
                            <?php if( $show_edit_tishi===true ){?>
                                <a href="javascript:void(0)" class="safe_link_02">已验证</a>
                            <?php }else{?>
                                <?php if( $valid_email=='1' ){?>
                                    <a href="/company/member/basic/editMail" class="safe_link_01">修改</a>
                                <?php }else{?>
                                    <a href="/company/member/basic/setEmail" class="safe_link_01">去验证</a>
                                <?php }?>
                            <?php }?>
                            <div class="clear"></div>
                        </li>
                        <li class="safe_shadow"></li>
                        <li>
                            <span class="safe_tel"></span>
                            <p>
                            <b>手机验证</b>
                            <?php
                            //未填写基本信息并且未验证过手机号
                            if( $is_complete_basic===false && !$valid_mobile ){
                            ?>
                                <em>通过验证可增强企业诚信度，吸引更多投资者</em>
                                 </p>
                                <a href="<?php echo URL::website('company/member/valid/mobile')?>" class="safe_link_03">去验证</a>
                            <?php
                            //已经填写了基本信息但是未验证，显示转换后的手机号
                            }elseif ( $is_complete_basic===true && !$valid_mobile ) {
                            ?>
                                <em>您的手机号码：<strong><?php echo $usermobile?></strong></em>
                                </p>
                                <a href="<?php echo URL::website('company/member/valid/mobile')?>" class="safe_link_03">去验证</a>
                            <?php
                            //已经验证过手机号
                            }else{
                            ?>
                                <em>您验证的手机号码：<strong><?php echo $usermobile?></strong></em>
                                </p>
                                <a href="<?php echo URL::website('company/member/valid/mobile?to=change')?>" class="safe_link_03">修改</a>
                            <?php
                            }
                            ?>
                            <div class="clear"></div>
                        </li>
                        <li class="safe_shadow"></li>
                        <div class="clear"></div>
                        </ul>
                        <p class="safe_detail_text">
                          <b>安全服务：</b>
                          <span>1、确认您登录的是一句话网（http://www.yjh.com），注意防范进入钓鱼网站，不要轻信各种即时通讯工具发送的商品或支付链接，谨防网购诈骗。</span><span>2、建议您安装杀毒软件，并定期更新操作系统等软件补丁，确保账户及交易安全。</span>
                        </p>
                       <div class="clear"></div>
                       </div>
                    </div>
                </div>