<?php
if( $reg_fu_is_login===false ){
?>
<?php echo URL::webjs("platform/home/login_fu.js");?>
<?php echo URL::webjs("jquery.validate.js");?>
<?php }?>
<?php echo $maincontent;?>
<?php if( $reg_fu_is_login===false ){?>
 <input type="hidden" value="<?php echo $to_url?>" id="to_url_id">
    <input type="hidden" value="<?php echo $reg_fu_platform_num?>" id="reg_fu_platform_num_id">
    <input type="hidden" value="<?php echo $reg_fu_user_num?>" id="reg_fu_user_num_id">
      <input type="hidden" id="loginHidden" value="0" />
<?php }?>
