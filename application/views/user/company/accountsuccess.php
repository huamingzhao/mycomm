            <div class="right">
    <h2 class="user_right_title"><span>我的帐户</span><div class="clear"></div></h2>
                    <div class="acount-pay">
                        <div class="suc">
                            <p class="one">支付成功！</p>
                            <p class="two">您已经成功付款<span><?php echo isset($total_fee)?$total_fee:"0.00"?></span>元</p>
                            <p class="three">支付订单号：<span><?php echo isset($order_no)?$order_no:""?></span></p>
                        </div>
                        <div class="btn">
                            
                             <a href="<?php echo URL::site("/company/member/account/accountindex")?>"><img src="<?php echo URL::webstatic("/images/account/btn_b.png")?>" /></a>
                        </div>
                    </div>
                </div>
