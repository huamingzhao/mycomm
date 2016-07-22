<?php echo URL::webcss("account_zg.css")?>
<?php echo URL::webjs("account_zg.js");?>
    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>我的帐户</span><div class="clear"></div></div>
        <div id="right_con" style="padding-left:19px; width:745px;">
            <div class="forbidden_account">
               <span class="forbidden_icon"></span>
               <span class="forbidden_text">
               		<?php 
               			if($forbid_result &&  $forbid_result['type'] =='refund'){
               				echo '<h4>您的账户因全额退款中，已被禁用！</h4>';
               			}else{
               				echo '<h4>您的账户因异常，已被禁用！</h4>';
               			}
               		?>
                   <span><em>如有疑问，请联系客服：</em><b><?php $arrphone=common::getCustomerPhone();echo $arrphone['1']; ?></b></span>      
               </span>
            </div>
            
        </div>
    </div>
    <!--右侧结束-->
    <div class="clear"></div>
</div>