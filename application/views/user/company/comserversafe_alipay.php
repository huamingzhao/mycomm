<?php echo url::webcss("touzi_security.css");?>
<?php echo url::webjs("header.js");?>
<?php echo url::webjs("touzi.js");?>

<!--中部开始-->

                <!--主体部分开始-->
                <div class="right">
    <h2 class="user_right_title"><span><a href="/company/member/guard/">投资保障</a> > 安全保障</span><div class="clear"></div></h2>
                    <!--投资保障-->
                    <div class="touzi_security_main">
                       <div class="touzi_bz_aq_step01">
                          <p class="touzi_bz_know"><a href="/company/member/guard/safeone">安全保障协议</a></p>
                          <div class="touzi_bz_aq_sever">
                          <FORM action="/company/member/guard/safealipay" id="form_id">
                              <p class="touzi_bz_aq_text">您需交纳5万元保证金，您交纳的保证金将会被冻结在您的账户中，我们不会动用您的保证金作为他用。</p>
                              
                              <?php if($is_forbid===false){//没有被禁用?>
                              <div class="touzi_bz_aq_bzjcont">
                                <span><em>您的账户余额为：</em><b><?php echo $account?></b><em>元</em></span>
                                <span><em>需要交纳保证金：</em><b>50000.00</b><em>元</em></span>
                              </div>
                              <?php }else{ if(!$is_forbid){$is_forbid=='出现异常';}//已经被禁用?>
                               <div class="touzi_bz_aq_bzjcont">
                                 <span><em>您的账户因：<?php echo $is_forbid.'，已被禁用；';?></em></span>
                                  <span><em>如有疑问，请联系客服：</em><b>400 1015 908</b></span>
                                   </div>
                              <?php }?>
                              
                              
                              <?php if($is_forbid===false){//没有被禁用?>
								<?php
	                              if( $status=="no" ){
	                              ?>
	                              <p class="touzi_bz_sever_nomoney"><img src="<?php echo url::webstatic('images/touzi_security/icon03.jpg')?>"><span>您的余额不足</span><a href="/company/member/account/accountindex" target="blank">去充值</a></p>
	                              <p class="touzi_bz_sever_casemoney"><a href="javascript:void(0)" style="cursor: default"></a></p>
	                              <?php }else{?>
	                              <p class="touzi_bz_sever_next"><a href="javascript:void(0)" onclick="goto_sumbit()">缴纳保证金</a></p>
	                              <?php }?>
                              <?php }?>
                              <div class="clear"></div>
                              </FORM>
                          </div>
                       </div>
                       <div class="clear"></div>
                    </div>


              </div>
                <!--主体部分结束-->
                <div class="clear"></div>

