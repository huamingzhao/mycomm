<?php echo URL::webcss("common.css")?>
<?php echo URL::webcss("per_infor_0502.css")?>
<?php echo URL::webjs("header.js")?>
<?php echo URL::webjs("person_new.js")?>
                <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>基本信息管理</span><div class="clear"></div></h2>
                       <div class="per_infor_0502">

                      <ul class="per_infor_title">
                      <li><a href="<?php echo URL::website("/person/member/basic/person")?>" class="current"><img src="<?php echo URL::webstatic("images/per_infor/icon01.jpg")?>" style="padding:21px 10px 0 62px;">我的基本信息</a></li>
                      <li><a href="<?php echo URL::website("/person/member/basic/personInvestShow")?>">意向投资信息</a></li>
                      <li class="last"><a href="<?php echo URL::website("/person/member/basic/experience")?>">从业经验</a></li>
                      </ul>

                      <div class="per_infor_content">
                         <h4><b>我的基本信息</b></h4>

                         <p><b>姓名：</b><span class="per_infor_right02"><?php echo isset( $personinfo['person']->per_realname ) ? $personinfo['person']->per_realname : '';?></span><div class="clear"></div></p>

                         <p><b>性别：</b><span class="per_infor_right02"><?php if( isset( $personinfo['person']->per_gender ) && ( $personinfo['person']->per_gender == 1 ) ){ ?>男<?php }else{ ?>女<?php } ?></span><div class="clear"></div></p>

                        <?php if( $personinfo['person']->per_birthday!=0 && $personinfo['person']->per_birthday!='' ){?>
                         <p><b>出生日期：</b><span class="per_infor_right02"><?php echo date("Y-m-d",$personinfo['person']->per_birthday) ?></span>
                         <div class="clear"></div>
                         </p>
                        <?php }?>

                         <p><b>手机号码：</b><span class="per_infor_right02"><?php echo isset($personinfo['mobile']) ? $personinfo['mobile'] : '';?></span>
                         <?php
                         if( isset( $personinfo['mobile'] ) && $personinfo['mobile'] ){
                             //未验证
                             if( !$personinfo['valid_mobile'] ){
                         ?>
                             <a href="#" class="per_infor_check_img"><img src="<?php echo URL::webstatic("images/per_infor/no_check.jpg")?>"></a>
                            <a class="per_infor_check_text" href="<?php echo URL::website('/person/member/valid/mobile')?>">去验证</a>
                        <?php
                            }else{
                                //已经验证
                        ?>
                            <img src="<?php echo URL::webstatic("images/infor1/tel_check.jpg")?>">
                        <?php
                            }
                        }
                        ?>

                         <div class="clear"></div>

                         </p>

                         <p><b>邮箱：</b><span class="per_infor_right02"><?php echo isset($personinfo['email']) ? $personinfo['email'] : '';?></span><div class="clear"></div></p>
                         <?php

                         if( ceil( $pro_id )!=0 ){
                         ?>
                         <p><b>目前所在地：</b>

                         <span class="per_infor_right02">
                        <?php
                        foreach ($area as $v){
                            if( $v['cit_id']==$pro_id ){
                                echo $v['cit_name'].' ';
                            }
                        }
                        if($areaIds!='0' && !empty($cityarea)){
                            foreach ($cityarea as $v){
                                if($v->cit_id == $areaIds){
                                    echo $v->cit_name;
                                }
                            }
                        }
                        ?>

                         </span>
                         <div class="clear"></div>
                         </p>
                         <?php }?>

                         <div class="clear"></div>
                    </div>
                    <!--显示更多信息开始-->

                    <div class="per_infor_content">


                     <?php if( $personinfo['person']->per_qq!="" ){?>
                         <p><b>QQ：</b><span class="per_infor_right02"><?php echo isset( $personinfo['person']->per_qq ) ? $personinfo['person']->per_qq : '' ?></span><div class="clear"></div></p>
                     <?php } ?>

                     <?php if(  $personinfo['person']->per_card_id!="" ){?>
                         <p><b>身份证号：</b>
                         <?php
                         //判断身份证是否验证
                         if( $personinfo['person']->per_auth_status!='2' ){
                         ?>
                             <span class="per_infor_right02"><?php echo isset( $personinfo['person']->per_card_id ) ? $personinfo['person']->per_card_id : ''?></span>
                             <?php
                             //未填写身份证，则不显示
                             if( $personinfo['person']->per_card_id!='' ){
                             ?>
                             <a href="<?php echo URL::website('/person/member/basic/identificationcard')?>" class="per_infor_check_img"><img src="<?php echo URL::webstatic("images/per_infor/no_check.jpg")?>"></a>
                             <a class="per_infor_check_text" href="<?php echo URL::website('/person/member/basic/identificationcard')?>">去验证</a>
                             <?php }?>

                         <?php }else{?>
                            <span class="per_infor_right02"><?php echo $personinfo['person']->per_card_id?></span><img src="<?php echo URL::webstatic("images/infor1/tel_check.jpg")?>">
                         <?php } ?>
                         <div class="clear"></div>
                         </p>
                        <?php } ?>

                        <?php if(  $personinfo['person']->per_education!='' ){?>
                         <p><b>学历：</b><span class="per_infor_right02"><?php echo $edu?></span><div class="clear"></div></p>
                        <?php } ?>

                        <?php
                         if( $personinfo['person']->per_school!='' ){
                         ?>
                             <p><b>毕业学校：</b><span class="per_infor_right02"><?php echo  $personinfo['person']->per_school?></span><div class="clear"></div></p>
                         <?php } ?>

                         <p class="per_infor_btn per_infor_btn02">
                         <a href="<?php echo URL::website('/person/member/basic/personupdate')?>" class="ryl_btn01">修改</a>
                         <?php
                         if( $personinfo['person']->per_connections=='0' || $personinfo['person']->per_connections=='' ){
                         ?>
                         <a href="<?php echo URL::website('/person/member/basic/personInvest')?>" class="text">您的名片已生成,请继续完善意向投资信息,有利于您与招商者的沟通。</a>
                         <?php
                         }else{
                         ?>
                            <a href="<?php echo URL::website('/person/member/card/mycard')?>" class="text">您的个人名片已生成，现在去看看吧</a>
                         <?php
                         }
                         ?>
                         </p>

                         <div class="clear"></div>
                      </div>
                      <!--显示更多信息end-->

                      <div class="clear"></div>
                    </div>
                </div>
                <!--主体部分结束-->
                <div class="clear"></div>

<!--中部结束-->
