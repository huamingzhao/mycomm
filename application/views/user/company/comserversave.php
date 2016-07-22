<?php echo url::webcss("touzi_security.css");?>
<?php echo url::webjs("header.js");?>
<?php echo url::webjs("del_shenhe.js")?>
<?php echo URL::webjs("touzi.js")?>


                <!--主体部分开始-->
                <div class="right">
    <h2 class="user_right_title"><span><a href="/company/member/guard/">投资保障</a> > 审核信息</span><div class="clear"></div></h2>

                    <!--投资考察会成果播报-->
                    <div class="my_bussines_bobao">


                       <div class="my_bussines_bobao_cont my_bussines_bobao_cont_save">

                         <p class="touzi_bz_know">
                         <?php
                         if( $server_status!="1" ){
                         ?>
                         <a href="/company/member/guard/server" class="touzi_bz_modify_infor">修改信息</a>
                         <?php }?>
                         </p>

                         <div class="touzi_security_fw_list">
                           <label>有加盟店：</label>
                           <p><?php echo $store_num?>家</p>
                           <div class="clear"></div>
                         </div>
                         <div class="touzi_security_fw_list">
                           <label>加盟店地址：</label>
                           <p>
                           <?php
                           if( !empty( $address ) ){
                                foreach ( $address as $vs ){
                           ?>
                             <span class="touzi_security_fw_jionaddress"><?php echo $vs['area_name'].$vs['city_name'].$vs["address"]?></span>
                            <?php }}?>



                             <div class="clear"></div>
                           </p>
                         </div>
                         <div class="touzi_security_fw_list">
                           <label><em>*</em> 加盟店图片：</label>
                           <p>
                            <ul class="touzi_security_fw_jion_pic">
                            <?php
                            if( !empty( $img ) ){
                                foreach ( $img as $iv ){
                            ?>
                            <li><a href="<?php echo url::imgurl( $iv['b_image'] )?>" target="blank"><img src="<?php echo url::imgurl( $iv['url'] )?>"><em>放大图片</em></a></li>
                            <?php }}?>


                            </ul></p>
                           <div class="clear"></div>
                         </div>
                         <div class="clear"></div>
                       </div>
                       <div class="clear"></div>
                    </div>

              </div>
                <!--主体部分结束-->
                <div class="clear"></div>



                <!--删除弹出框 开始-->
              <div id="getcards_opacity"></div>
              <div id="getcards_delete">
                    <a class="close" href="#">关闭</a>
                <div class="btn">
                  <p>您还没有查看此项目，一旦删除，将无法取回。确定要删除此项目吗？</p>
                        <!--<p>一旦删除，将无法取回。您确定要删除此名片吗？</p>-->
                        <!--<p>您还没选择需要删除的名片，请先选择后操作。</p>-->
                  <p><a id="deleteProject" class="ensure" href="/company/member/project/deleteproject?id=2183"><img src="http://static.myczzs.com/images/getcards/ensure.png"></a>&#12288;<a class="cancel" href="#"><img src="http://static.myczzs.com/images/getcards/cancel.png"></a></p>
                    </div>
                </div>
                <div class="clear"></div>
                <!--删除弹出框 结束-->


