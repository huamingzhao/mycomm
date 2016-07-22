<?php echo URL::webcss("card_zg.css")?>
<?php echo URL::webjs("personcard.js")?>
    <!--右侧开始-->
    <div id="right">
          
        <div id="right_top"><span>手机验证</span><div class="clear"></div></div>
        
        <div id="right_con">
            <div class="ryl_person_tel_check">
                <span>您没填写基本信息哦，请填写您的基本信息再来验证手机号码吧！</span>
                <a href="<?=URL::website('/person/member/basic/personupdate')?>"></a>
            </div>
        </div>
    </div>
<!-- Baidu Button BEGIN -->

<!--右侧结束-->
<div class="clear"></div>
<script type="text/javascript" id="bdshare_js" data="type=tools" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
//分享图片
var shareimg=$("#top_right img").attr("src");
var bds_config = {'bdText':"我在一句话生成一张投资名片，分享给亲们！",'bdPic':shareimg};
document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
</script>
<!-- Baidu Button END -->