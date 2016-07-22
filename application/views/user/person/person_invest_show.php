<?php echo URL::webcss("common.css")?>
<?php echo URL::webcss("per_infor_0502.css")?>
<?php echo URL::webjs("header.js")?>
<?php echo URL::webjs("person_new.js")?>

                <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>基本信息管理</span><div class="clear"></div></h2>
                       <div class="per_infor_0502">

                       <ul class="per_infor_title">
                      <li><a href="<?php echo URL::website("/person/member/basic/person")?>">我的基本信息</a></li>
                      <li><a href="<?php echo URL::website("/person/member/basic/personInvestShow")?>" class="current"><img src="<?php echo URL::webstatic("images/per_infor/icon01.jpg")?>" style="padding:21px 10px 0 62px;">意向投资信息</a></li>
                      <li class="last"><a href="<?php echo URL::website("/person/member/basic/experience")?>">从业经验</a></li>
                      </ul>


                      <div class="per_infor_content">
                         <h4><b>我的意向投资信息</b></h4>

                         <p><b>意向投资金额：</b><span class="per_infor_right02"><?php $monarr= common::moneyArr(); echo $personinfo['person']->per_amount== 0 ? '无': $monarr[$personinfo['person']->per_amount];?></span><div class="clear"></div></p>

                         <p><b>意向投资行业：</b><span class="per_infor_right02"><?=$personalIndustryString?></span><div class="clear"></div></p>

                         <p><b>意向投资地区：</b><span class="per_infor_right02"><?=$personIndArea;?></span>
                         <div class="clear"></div>
                         </p>

                         <p><b>我的标签：</b><span class="per_infor_right02"><?=isset($personinfo['person']->per_per_label) ? $personinfo['person']->per_per_label : '';?></span>
                         <div class="clear"></div>
                         </p>
                        <p><b>我的人脉关系：</b><span class="per_infor_right02"><?php echo $connection?></span>
                         <div class="clear"></div>
                         </p>
                        <p><b>我的投资风格：</b><span class="per_infor_right02"><?php echo $investment_style?></span>
                         <div class="clear"></div>
                         </p>

                        <p><b>有无店铺：</b><span class="per_infor_right02"><?php if( $personinfo['person']->per_shop_status=='1' ){ echo '有'; }else{ echo '无'; }?></span>
                         <div class="clear"></div>
                         </p>
                        <?php if( $personinfo['person']->per_shop_status=='1' ){?>
                        <p><b>店铺面积：</b><span class="per_infor_right02"><?php echo $personinfo['person']->per_shop_area?></span>
                         <div class="clear"></div>
                         </p>
                        <?php }?>
                        <p class="per_infor_btn per_infor_btn02">
                         <a href="<?php echo URL::website('/person/member/basic/personInvest')?>" class="ryl_btn01">修改</a>
                         <?php
                         if( $personinfo['person']->per_realname=='' ){
                         ?>
                         <a href="<?php echo URL::website('/person/member/basic/personupdate')?>" class="text">去完善您的个人基本信息，生成个人名片吧，有利于您与招商者的沟通</a>
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


                      <div class="clear"></div>
                    </div>
                </div>
                <!--主体部分结束-->
                <div class="clear"></div>

<!--中部结束-->
