<!--主体部分开始-->    <!--右侧开始-->
    <div id="right">
        <?php echo URL::webcss("personcenter.css")?>
        <div id="right_top"><span>解除绑定</span><div class="clear"></div></div>
        <div class="jcbind">
            <div class="clearfix hybox">
                <img class="floleft" src="<?php echo URL::webstatic('images/login_optimization/2.png')?>">
                <div class="floleft hyinfobox">
                    <p>欢迎您!<?php echo $user_name?>，您的账号已与第三方账号进行绑定！</p>
                    <p>您可直接用第三方账号登录一句话商机速配平台了哟！</p>
                </div>
            </div>
            <table class="mt15 bindtablebox" cellspacing="0" cellpadding="0" width="100%">
            <?php
            if( !empty( $result ) ){
                foreach ( $result as $vs ){
            ?>
                <tr>
                    <td width="145" style="padding-left:40px;"><?php if( $vs['type']=='1' ){ echo 'QQ';}elseif( $vs['type']=='2' ){ echo '新浪微博'; }else{ echo '支付宝'; }?></td>
                    <td width="165"><span class="clo003"><?php echo $vs['name']?></span></td>

                    <td width="300">绑定时间：<?php echo $vs['time']?></td><input type="hidden" id="url_id" value="/person/member/basic/oauthjc?action=jc&id=<?php echo $vs['id']?>&uid=<?php echo $vs['uid']?>&oid=<?php echo $vs['oid']?>"  />
                    <td width="85"><a href="javascript:;" class="clo003">解除绑定</a></td>
                </tr>
            <?php }}?>

            </table>
        </div>
        <script type="text/javascript">
            $(".bindtablebox a").click(function(){
                var info=$(this).parent().parent().find("td").eq(0).html();
                var _this=$(this)
                window.MessageBox({
                    title:"生意街提示您",
                    content:"<p>确定要与"+info+"解除绑定吗？</p>",
                    btn:"ok cancel",
                    callback:function(){
                        window.location.href= $("#url_id").val();
                        _this.parent().append("<span>已解除</span>");
                        _this.remove();
                    }
                });
            });
        </script>
    </div>
<!--主体部分结束-->