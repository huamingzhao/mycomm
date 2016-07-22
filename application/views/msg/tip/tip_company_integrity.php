<?php //我的诚信?>

<?php if( $type=="tel" ){?>
您已经验证手机号码，增加<?php echo $code?>点诚信指数。
<?php }elseif ( $type=="email" ){?>
您已经验证邮箱，增加<?php echo $code?>点诚信指数。
<?php }elseif ( $type=="account" ) {
?>
您成功充值<?php echo $account?>元，增加<?php echo $code?>点诚信指数。
<?php
}else{

}?>

