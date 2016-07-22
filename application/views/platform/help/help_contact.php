<script>
var online= new Array(); 
</script> 
<script src="http://webpresence.qq.com/getonline?Type=1&2885778591:"></script> 

           <div class="help_cont">
              <p class="help_title">联系方式<em>CONTACT US</em></p>
              <div class="help_center_right">
                 <div class="help_contact">
                  <h2>上海通路快建网络服务外包有限公司</h2>
                    <p>客服电话：<?php $arrCustomerPhone = common::getCustomerPhone();echo $arrCustomerPhone[1]?></p>
                    <p class="qqkefu">客服QQ ：2885778591　  </p>
                    <p>客服工作时间：工作日9:00~17:30（节假日除外）</p>
                    <p class="mt60">E-mail： kefu@yjh.com</p>
                    <p>网　址： http://www.yjh.com/</p>
                    <p>地　址：上海市闵行区浦江镇联航路1188号9幢3楼</p>
                    <p>邮　编：201112</p>
                 </div>
              </div>
           </div>
<script> 
var qq='<?php echo URL::webstatic('/images/common/qq.png')?>';
var qqon='<?php echo URL::webstatic('/images/common/qqon.png')?>'
if (online[0]==0) {
    $(".qqkefu").append("<a class='qqa' target=blank href='http://wpa.qq.com/msgrd?V=1&Uin=2885778591&Site=在线咨询&Menu=no' title='在线即时交谈'><img src='"+qqon+"' border=0 align=center style='margin-bottom:10px' /></a>"); 
}
else {
  $(".qqkefu").append("<a class='qqb' target=blank  href='http://wpa.qq.com/msgrd?V=1&Uin=2885778591&Site=在线咨询&Menu=no' title='QQ不在线，请留言'><img src='"+qq+"' border=0 align=center  style='margin-bottom:10px' /></a>"); 
}
</script>