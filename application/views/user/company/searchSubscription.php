<?php echo URL::webjs("email_search.js")?>
<?if(!$subscriptionStatus && !$list) {?>  
<div id="right">
        <div id="right_top"><span>订阅投资者</span><div class="clear"></div></div>
        <div id="right_con">
        	<div id="dingy_free">
            	<div class="btn">
                	<p>当有新的投资者符合您的搜索条件时，我们会通过邮件的方式告知您。</p>
                    <input type="image" src="<?= URL::webstatic("images/email_back/btn2.png") ?>" />
                </div>
            </div>
        </div>
    </div>
<?}elseif($list && $subscriptionStatus){?>
<div id="right">
    	<div id="right_top"><span>订阅投资者</span></div>
        <div id="right_con">
        	<div id="dingy_free">
            	<div class="btn">
                	<p>您已经成功订阅符合您搜索条件的投资者，当有新的投资者符合您的搜索条件时，我们会以邮件的方式通知您。</p>
                </div>
                <p class="cantact">您的接收邮箱为您的注册邮箱：<span><?=$email?></span><input type="image" class="btnB" src="<?= URL::webstatic("images/email_back/btn3.png") ?>" /></p>
            </div>
        </div>
    </div>
<?}else{?>
<div id="right">
    	<div id="right_top"><span>订阅投资者</span></div>
        <div id="right_con">
        	<div id="dingy_free">
            	<div class="btn">
                	<p>您已经成功取消订阅符合您搜索条件的投资者，当有新的投资者符合您的搜索条件时，我们不会通知您。</p>
                </div>
                <p class="cantact">您的接收邮箱为您的注册邮箱：<span><?=$email?></span><input type="image" class="btnA" src="<?= URL::webstatic("images/email_back/btn2.png") ?>" /></p>
            </div>
        </div>
    </div>
<? } ?>
