<?php echo url::webcss("platform/common.css");?>
<?php echo url::webcss("touzi_security.css");?>
<?php echo url::webjs("header.js");?>
<?php echo url::webjs("touzi.js");?>

                <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>投资保障</span><div class="clear"></div></h2>
                    <INPUT type="hidden" id="safe_ok_id" value="<?php if( isset( $safe_ok ) ){ echo $safe_ok; }else{ echo "0"; } ?>" >
                   <INPUT type="hidden" id="server_ok_id" value="<?php if( isset( $server_ok ) ){ echo $server_ok; }else{ echo "0"; } ?>" >

                    <!--投资保障-->
                    <div class="touzi_security_main">
                       <p class="touzi_bz_know"></p>
                       <ul class="touzi_bz_home">

                       <li>
                       <div class="touzi_bz_unit">
                         <p class="touzi_bz_unit_left"><span>1</span></p>
                         <div class="touzi_bz_unit_center" id="2">
                            <p><em>基础</em>保障</p>
                            <span>
                              <b>如要点亮基础保障，需要上传您的企业证书且等待审核通过：公司营业执照、组织机构代码证。</b>
一句话网站对入驻本站的企业均进行严格审查，确保公司营业执照、组织机构代码证等资质证书齐全；如涉及特殊行业的品牌，则要求提供相关行业的有效资质证书，为您营造一个公平的招商环境。
                            </span>
                         </div>
                         <div class="touzi_bz_unit_right">
                         <?php
                        if( $complete=='1' ){
                         if( $server['base']=="1" ){
                         ?>
                           <img src="<?php echo URL::webstatic("images/touzi_security/bz_01_liang.jpg")?>"/>
                            <div class="clear"></div><b>已点亮基础保障</b>
                        <?php }else{?>
                            <img src="<?php echo URL::webstatic("images/touzi_security/bz_01.jpg")?>"/>
                            <?php
                            if( $server['base']=="0" ){
                            ?>
                                <div class="clear"></div><b>基础保障审核中</b>
                            <?php
                            }elseif ( $server['base']=="2" ){
                            ?>
                            <div class="clear"></div><b>基础保障审核未通过</b>
                            <?php }else{?>
                            <div class="clear"></div><a href="javascript:void(0)" onclick="showWindow('base')">我要点亮基础保障</a>
                            <?php }?>


                        <?php }}else{?>
                            <img src="<?php echo URL::webstatic("images/touzi_security/bz_01.jpg")?>"/>
                            <div class="clear"></div><a href="<?php echo URL::website('/company/member/basic/editcompany?type=1');?>">去完善基本信息</a>
                        <?php }?>

                         </div>
                         <div class="clear"></div>
                       </div>
                       </li>

                       <li>
                       <div class="touzi_bz_unit">
                         <p class="touzi_bz_unit_left"><span>2</span></p>
                         <div class="touzi_bz_unit_center" id="3">
                            <p><em>品质</em>保障</p>
                            <span>
                              <b>如要点亮品质保障，请发布您的项目且等待审核通过。</b>
一句话网站致力成为优质品牌汇聚的卓越招商平台，凡在本站注册且发布的项目，我们会对其进行层层严格审核，确保项目的真实性，杜绝虚假项目产生的同时也是最大程度的保障您的权益。
                            </span>
                         </div>
                         <div class="touzi_bz_unit_right">
                             <?php
                             if( $server['quality']=="1" ){
                             ?>
                                   <img src="<?php echo URL::webstatic("images/touzi_security/bz_02_liang.jpg")?>"/>
                                   <div class="clear"></div><b>已点亮品质保障</b>

                            <?php }else{?>
                                <img src="<?php echo URL::webstatic("images/touzi_security/bz_02.jpg")?>"/>
                                <?php
                                if( $server['quality']=="0" ){
                                ?>
                                <div class="clear"></div><b>品质保障审核中</b>
                                <?php
                                }elseif ( $server['quality']=="2" ){
                                ?>
                                <div class="clear"></div><b>品质保障审核未通过</b>
                                <?php }else{?>
                                <div class="clear"></div><a href="javascript:void(0)" onclick="showWindow('quality')">我要点亮品质保障</a>
                                <?php }?>

                            <?php }?>

                         </div>
                         <div class="clear"></div>
                       </div>
                       </li>


                       <li>
                       <div class="touzi_bz_unit">
                         <p class="touzi_bz_unit_left"><span>3</span></p>
                         <div class="touzi_bz_unit_center" id="4">
                            <p><em>安全</em>保障</p>
                            <span>
                              <b>经过调查，投资者对“安全保障”点亮标识的项目更加感兴趣，签约意向更为明显。在一句话网站签署《一句话网站投资保障服务协议-企方》且缴纳5万元保证金的的企业用户，我们会为您点亮安全保障。</b>
关于保证金：如企业对加盟者有任何欺骗行为，一句话网站都将代企业先行支付2-5万元人民币赔偿金，并协助对该企业/品牌进行追偿。如企业需要终止和一句话网站关于安全保障的协议且经审查企业并无欺骗投资者的行为，保障金将会如数退还给企业。<br/>
如有任何疑问，欢迎拨打我们的免费热线电话：<em>400-1015-908</em>
                            </span>
                         </div>
                         <div class="touzi_bz_unit_right">
                         <?php
                         if( $server['safe']=="1" ){
                         ?>
                           <img src="<?php echo URL::webstatic("images/touzi_security/bz_03_liang.jpg")?>"/>
                           <div class="clear"></div><b>已点亮安全保障</b>
                         <?php }else{?>

                           <img src="<?php echo URL::webstatic("images/touzi_security/bz_02.jpg")?>"/>
                                <?php
                                if( $server['safe']=="0" ){
                                ?>
                                <div class="clear"></div><b>安全保障审核中</b>
                                <?php
                                }elseif ( $server['safe']=="2" ){
                                ?>
                                <div class="clear"></div><b>安全保障审核未通过</b>
                                <?php }else{?>
                                <div class="clear"></div><a href="/company/member/guard/safeone">我要点亮安全保障</a>
                                <?php }?>

                         <?php }?>
                         </div>
                         <div class="clear"></div>
                       </div>
                       </li>

                       <li>
                       <div class="touzi_bz_unit">
                         <p class="touzi_bz_unit_left"><span>4</span></p>
                         <div class="touzi_bz_unit_center">
                            <p><em>服务</em>保障<?php if( $server['server']=="0" || $server['server']=="2" ){?><a href="/company/member/guard/serversave">查看提交的信息</a><?php }?></p>
                            <span>
                              针对有实体门店三家及以上的企业，我们会有资深招商经理和投资顾问对门店进行实体考察，包括对门店的真实性、经营状况及盈利情况等多方面进行核实，力求投资者加盟项目后的各项服务可以得到最有效的保障，增加投资者对企业的信任。
                            </span>
                         </div>
                         <div class="touzi_bz_unit_right">
                         <?php
                         if( $server['server']=="1" ){
                         ?>
                           <img src="<?php echo URL::webstatic('images/touzi_security/bz_04_liang.jpg')?>"/><b>已点亮服务保障</b>
                         <?php }else{?>
                             <img src="<?php echo URL::webstatic('images/touzi_security/bz_04.jpg')?>"/>
                                 <?php
                                if( $server['server']=="0" ){
                                ?>
                                <div class="clear"></div><b>服务保障审核中</b>
                                <?php
                                }elseif ( $server['server']=="2" ){
                                ?>
                                <div class="clear"></div><b>服务保障审核未通过</b>
                                <?php }else{?>
                                <a href="/company/member/guard/server">我要点亮服务保障</a>
                                <?php }?>

                         <?php }?>
                         </div>
                         <div class="clear"></div>
                       </div>
                       </li>

                       </ul>
                    </div>


              </div>
                <!--主体部分结束-->
                <div class="clear"></div>

                <!--删除弹出框 开始-->


              <div id="getcards_delete" class="fu_id_1">
                    <a class="close" href="javascript:void(0)" onclick="delWindow()">关闭</a>
                <div class="btn" id="show_content_href_div_id">
                  <p id="show_window_html_id"></p>

                  <p id="show_href_id"><a id="href_id" class="ensure"><img src="<?php echo url::webstatic( 'images/getcards/ensure1.jpg' )?>"></a>&#12288;<a id="cancel_href_id" class="cancel" href="javascript:void(0)" onclick="delWindow()"><img src="<?php echo url::webstatic( 'images/getcards/cancel1.jpg' )?>"></a></p>
                    </div>
                </div>

                <div class="clear"></div>
                <!--删除弹出框 结束-->

                <!--恭喜弹出框 开始-->


                <div id="getcards_delete" class="fu_id_2" >
                    <a class="close" href="javascript:void(0)" onclick="delWindow()">关闭</a>
                    <div class="touzi_fc">
                         <img src="<?php echo url::webstatic('images/touzi_security/icon_ok.jpg')?>"/>
                         <p>
                         <span>恭喜您！</span>
                         <b>已经成功点亮安全保障</b>
                         </p>
                    </div>
                </div>


                <!--恭喜弹出框 结束-->

<div id="opacity_box" ></div>
