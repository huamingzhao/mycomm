<?php echo URL::webcss("account_zg.css")?>
<?php echo URL::webjs("account_zg.js");?>
<!--弹框效果-->
<div class="popupBg" id="popupBg"></div>
<div class="popup1" id="popup1">
    <div class="top"><a href="#"></a></div>
    <div class="content">
        <span><img src="<?php echo URL::webstatic('/images/account/right.jpg')?>" width="81" heigh="60"/></span>
        <div class="divFirst">
            <p id="showmsg"></p>
        </div>
        <div class="clear"></div>
        <div class="imgBtn" id="imgBtn"><a href="<?php echo URL::website('/company/member/account/accountindex')?>"><img src="<?php echo URL::webstatic('/images/account/btn_6.gif')?>" width="122" height="37"/></a></div>
    </div>
    <div class="bottom"></div>
</div>
<div class="popup1" id="popup3">
    <div class="top"><a href="#"></a></div>
    <div class="content">
        <div class="divThird">
            <p>对不起！<span></span> 帐户余额不足</p>
            <p>前往帐户中心充值以享受服务？<p>
        </div>
        <div class="clear"></div>
        <div class="imgBtn" id="imgBtn1"><a href="<?php echo URL::website('/company/member/account/accountindex')?>"><img src="<?php echo URL::webstatic('/images/account/btn_6.gif')?>" width="122" height="37"/></a><a href="<?php echo URL::website('/company/member/account/cardservice')?>"><img src="<?php echo URL::webstatic('/images/account/btn_7.gif')?>" width="122" height="37"/></a></div>
    </div>
    <div class="bottom"></div>
</div>

    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>购买服务</span><div class="clear"></div></div>
        <div id="right_con">
            <div class="service_zg">
            <?php /*?>
                <dl>
                    <dt>
                        <span><img src="<?php echo URL::webstatic('/images/account/img_0.gif')?>" width="61" height="52"/></span>
                        <div>
                            <h3 style="padding-top:13px;">看名片包月</h3>
                        </div>
                    </dt>
                    <dd>
                        <form action="" method="post" id="form1">
                            <p><input type="radio" name="buy_type_1" id="radio_0" value="1_1_" checked/><label for="radio_0">12个月</label><span>¥1000</span><em>¥2.7/天</em></p>
                            <p><input type="radio" name="buy_type_1" id="radio_1" value="1_2_"/><label for="radio_1">6个月</label><span>¥700</span><em>¥3.8/天</em></p>
                            <p><input type="radio" name="buy_type_1" id="radio_2" value="1_3_"/><label for="radio_2">3个月</label><span>¥400</span><em>¥4.4/天</em></p>
                            <p><input type="radio" name="buy_type_1" id="radio_3" value="1_4_"/><label for="radio_3">1个月</label><span>¥200</span><em>¥6/天</em></p>
                            <div><a href="#"><input name="type_number" id="type_number1" value="1" type="hidden"/><img id="submit_1" class="imgsubmit" src="<?php echo URL::webstatic('/images/account/btn_4.jpg')?>"/></a></div>
                        </form>
                    </dd>
                </dl>
                <dl class="noMarginRight">
                    <dt>
                        <span><img src="<?php echo URL::webstatic('/images/account/img_1.gif')?>" width="61" height="52"/></span>
                        <div>
                            <h3>发名片包月</h3>
                            <p>每天免费发送名片<em>30张</em></p>
                        </div>
                    </dt>
                    <dd>
                    <form action="" method="post" id="form3">
                        <p><input type="radio" name="buy_type_3" id="radio_4" value="3_1_" checked/><label for="radio_4">12个月</label><span>¥1000</span><em>¥2.7/天</em></p>
                        <p><input type="radio" name="buy_type_3" id="radio_5" value="3_2_"/><label for="radio_5">6个月</label><span>¥700</span><em>¥3.8/天</em></p>
                        <p><input type="radio" name="buy_type_3" id="radio_6" value="3_3_"/><label for="radio_6">3个月</label><span>¥400</span><em>¥4.4/天</em></p>
                        <p><input type="radio" name="buy_type_3" id="radio_7" value="3_4_"/><label for="radio_7">1个月</label><span>¥200</span><em>¥6/天</em></p>
                        <div><a href="#"><input name="type_number" id="type_number3" value="3" type="hidden"/><img id="submit_3" class="imgsubmit" src="<?php echo URL::webstatic('/images/account/btn_4.jpg')?>"/></a></div>
                        </form>
                    </dd>
                </dl>
                <?php */?>
                <dl>
                    <dt>
                        <span><img src="<?php echo URL::webstatic('/images/account/img_2.gif')?>" width="61" height="52"/></span>
                        <div>
                            <h3>名片放大镜</h3>
                            <p>一个放大镜免费查阅<em>1张</em>名片</p>
                        </div>
                    </dt>
                    <dd>
                    <form action="" method="post" id="form2">
                        <p><input type="radio" name="buy_type_2" id="radio_8" value="2_1_10" checked/><label for="radio_8">10张</label><span class="red">¥980</span></p>
                        <p><input type="radio" name="buy_type_2" id="radio_9" value="2_2_20"/><label for="radio_9">20张</label><span class="red">¥1900</span></p>
                        <p><input type="radio" name="buy_type_2" id="radio_10" value="2_3_50"/><label for="radio_10">50张</label><span class="red">¥4500</span></p>
                        <p><input type="radio" name="buy_type_2" id="radio_11" value="2_4_100"/><label for="radio_11">100张</label><span class="red">¥8000</span></p>
                        <div><a href="#"><input name="type_number" id="type_number2" value="2" type="hidden"/><img id="submit_2" class="imgsubmit" src="<?php echo URL::webstatic('/images/account/btn_4.jpg')?>"/></a></div>
                        </form>
                    </dd>
                </dl>
                <dl class="noMarginRight">
                    <dt>
                        <span><img src="<?php echo URL::webstatic('/images/account/img_3.gif')?>" width="61" height="52"/></span>
                        <div>
                            <h3 class="noPaddingBottom">名片邮票</h3>
                            <p>一个邮票免费递送<em>1张</em>名片</p>
                        </div>
                    </dt>
                    <dd>
                    <form action="" method="post" id="form4">
                        <p><input type="radio" name="buy_type_4" id="radio_12" value="4_1_10" checked/><label for="radio_12">10张</label><span class="red">¥294</span></p>
                        <p><input type="radio" name="buy_type_4" id="radio_13" value="4_2_20"/><label for="radio_13">20张</label><span class="red">¥570</span></p>
                        <p><input type="radio" name="buy_type_4" id="radio_14" value="4_3_50"/><label for="radio_14">50张</label><span class="red">¥1350</span></p>
                        <p><input type="radio" name="buy_type_4" id="radio_15" value="4_4_100"/><label for="radio_15">100张</label><span class="red">¥2400</span></p>
                        <div><a href="#"><input name="type_number" id="type_number4" value="4" type="hidden"/><img id="submit_4" class="imgsubmit" src="<?php echo URL::webstatic('/images/account/btn_4.jpg')?>"/></a></div>
                        </form>
                    </dd>
                </dl>
                <?php /*?>
                 <dl>
                    <dt>
                        <span><img src="<?php echo URL::webstatic('/images/account/img_4.jpg')?>" width="61" height="52"/></span>
                        <div>
                            <h4>查看报名投资考察会的投资者</h4>
                        </div>
                    </dt>
                    <dd>
                    <form action="" method="post" id="form5">
                        <p><input type="radio" name="buy_type_5" id="radio_16" value="5_1_500" checked/><label for="radio_16">500人次</label><span class="red">¥42500</span></p>
                        <p><input type="radio" name="buy_type_5" id="radio_17" value="5_2_100"/><label for="radio_17">100人次</label><span class="red">¥8900</span></p>
                        <p><input type="radio" name="buy_type_5" id="radio_18" value="5_3_20"/><label for="radio_18">20人次</label><span class="red">¥1900</span></p>
                        <p><input type="radio" name="buy_type_5" id="radio_19" value="5_4_10"/><label for="radio_19">10人次</label><span class="red">¥990</span></p>
                        <div><a href="#"><input name="type_number" id="type_number5" value="5" type="hidden"/><img id="submit_5" class="imgsubmit" src="<?php echo URL::webstatic('/images/account/btn_4.jpg')?>"/></a></div>
                        </form>
                    </dd>
                </dl>
                <?php */?>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    <!--右侧结束-->
    <div class="clear"></div>
</div>
<div id="getcards_opacity"></div>
<div id="getcards_deletebox">
    <a class="close" href="#">关闭</a>
    <div class="text">
        <p class="showmsg" style="padding-left:35px;"></p>
        <p class="cz_suc"><a class="ensure" href="<?php echo URL::website('company/member/card/receivecard');?>"><img src="<?php echo URL::webstatic('/images/getcards/ensure1.jpg')?>"></a>
           <a class="cancel" href=""><img src="<?php echo URL::webstatic('/images/getcards/cancel1.jpg')?>"></a>
        </p>
        <input id="getcards_deletebox_hid" type="hidden" value="0"></input>
    </div>
</div>