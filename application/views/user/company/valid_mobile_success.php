<?php echo URL::webcss("tel_zg.css")?>
<style>
.telSucceed p{line-height:24px;}
.telSucceed p span,.telSucceed p em,.telSucceed p a{ float:left;}
</style>
    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>手机验证</span><div class="clear"></div></div>
        <div id="right_con">
            <div class="telContent">
                <div class="telYz">
                     <div class="telSucceed">
                        <h4>手机验证成功！</h4>
                        <p><span>已绑定手机：</span><em style="font-style:normal;"><?=$mobile?></em><a style="padding-left:10px; color:#f00; width:70px;" href="<?php echo URL::website("company/member/valid/mobile?to=change")?>">修改手机号</a></p>

                     </div>
                </div>
            </div>
        </div>
    </div>
    <!--右侧结束-->