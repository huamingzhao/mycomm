<?php echo URL::webcss("account_zg.css")?>
<?php echo URL::webjs("account_zg.js");?>
<style>
.account_zg .accountList dt span,.account_zg .accountList dd span{ width:110px;}
.account_zg .accountList dd span{ font-family:"微软雅黑";}
.accountList p{ height:44px; line-height:44px; padding-left:8px;}
.accountList p a{ color:#0035fe;}
#dlList .span_0,#dlList .span_1{ text-align:left;}
#dlList .span_0{ width:112px;}
#dlList .span_none{ width:10px;}
.account_zg .balance dd span { float: left;padding:0 8px;}
.account_zg .balance dd {height: 32px;line-height: 32px;}
</style>
    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span style="width: 112px; height: 17px;">我的帐户</span><div class="clear"></div></div>
        <div id="right_con">
            <!--我的账户-->
            <div class="account_zg" style="padding-top:30px;">
               <?php if(isset($isplatformfree) && $isplatformfree){?>
                <div class="account_user_icon user_zsthy">
                    <i></i>招商通会员
                </div>
                <?php }else{?>
                <div class="account_user_icon user_normal">
                    <i></i>普通会员
                </div>
                <?php }?>
                <div class="balance" style="padding:0 0 14px 10px; position:relative;">
                    <dl style="width:100%; float:left;">
                       <?php if(isset($isplatformfree) && $isplatformfree==0){?>
                        <dt>申请招商通会员，请先支付平台服务费</dt>
                        <?php }?>
                        <dd class="blance_input"><label for="account" style="float:left">账户总余额：</label><em class="sum"><?php
                        if (strrpos($account, "."))
                            $total_fee = $account!=""?number_format($account,2,".",""):'0.00';
                        else
                            $total_fee = $account!=""?$account.".00":'0.00';
                        echo $total_fee;
                        ?></em>元
                        </dd>
                        <dd class="ddFirst" style="margin-bottom:25px;">
                            <label for="recharge">我要充值：</label>
                            <input type="text" placeholder="" class="recharge" id="recharge"/><span>元</span>
                            <span class="helpWord"><a href="<?php echo URL::website('company/member/account/platformAccountAbout');?>">查看充值及服务</a></span>
                            <em id="errorCharge" class="errorCharge">必须输入数字</em>
                            <em id="errorCharge2" class="errorCharge">最多输入7位数</em>
                            <em id="errorCharge3" class="errorCharge" style=" padding-left:90px;">首次充值不小于6500元，其中5000元为账户充值金额，1500元为平台服务费</em>
                            <em id="errorCharge4" class="errorCharge">每次充值不小于5000元</em>
                            <input type="hidden" id="isplatform" value="<?php echo $isplatformfree?>"></input>
                            <div class="clear"></div>
                        </dd>
                        <?php if(isset($isplatformfree) && $isplatformfree==0){?>
	                        <dd class="service_treaty" style="margin-bottom: 25px;">
	                            <input type="checkbox" checked="checked" id="service_treaty"/>
	                            <label for="service_treaty">我同意<a href="<?php echo URL::website('company/member/account/platformAgreement');?>">《一句话平台协议》</a>，并成为招商通会员</label>
	                            <em id="errorCharge5" class="errorCharge">你还没有同意一句话平台协议</em>
	                        </dd>
                        <?php }?>
                        <dd style="margin-bottom: 25px;"><label>&nbsp;</label><a href="javascript:void(0);" id="actionrecharge"><img src="<?php echo URL::webstatic('/images/account/moeny01.jpg');?>" width="102" height="32"/></a>网上支付充值金额可以即时到账</dd>
                        <dd style="margin-bottom:25px;"><label>&nbsp;</label><a class="bank_pay_1" href="<?php echo URL::website('/company/member/account/outline');?>">银行汇款</a>，如果您选择银行汇款，我们会在您的款项到账后为您充值 <a class="bank_pay_2" href="<?php echo URL::website('/company/member/account/outline');?>">关于银行汇款</a></dd>
                    </dl>
                    <p class="view_detail"><a href="<?php echo URL::website('/company/member/account/accountlist');?>"></a></p>
                    <div class="clear"></div>
                </div>

                <?php if($list!=""){?>
                <div class="accountList">
                    <h4 style="font:bold 14px/30px '宋体'; margin-left: 10px;">账户信息：</h4>
                    <dl id="dlList">
                        <dt style="background:none;line-height: 32px;">
                            <span class="span_none">&nbsp;</span>
                            <span class="span_0">充值/支付时间</span>
                            <span class="span_1">内容明细</span>
                            <span class="span_2">充值金额</span>
                            <span class="span_3">支付金额</span>
                            <span class="span_4">账户余额 </span>
                            <span class="span_4">赠送金额</span>
                        </dt>
                        <?php foreach ($list as $k=>$v){if ($k<5){
                        	//获取赠送金额
                    		$costfreee=ORM::factory('Accountlog')->where('account_comments_type', '=', 14)->where('account_type_id', '=', $v->account_type_id)->where('account_user_id', '=', $v->account_user_id)->find()->account_change_amount;?>
                        <dd>
                            <span class="span_none">&nbsp;</span>
                            <span class="span_0"><?php echo date('Y-m-d H:i',$v->account_log_time);?></span>
                            <span class="span_1"><?php echo mb_substr($v->account_note,0,12,'UTF-8');?></span>
                            <span class="span_2"><?php if($v->account_class==1){echo "+".$v->account_change_amount;}?>&nbsp;</span>
                            <span class="span_3"><?php if($v->account_class==2){echo "-".$v->account_change_amount;}?>&nbsp;</span>
                            <span class="span_4"><?php if($costfreee){echo number_format($v->account_amount+$costfreee, 2, '.', '');}else{echo  $v->account_amount;}?></span>
                            <span class="span_4"><?php if($costfreee){echo number_format($costfreee, 2, '.', '');}else{echo '--';}?></span>
                        </dd>
                        <?php }}?>
                        <div class="clear"></div>
                    </dl>
                    <div class="clear"></div>
                    <p><a href="<?php echo URL::website('/company/member/account/accountlist');?>">更多交易明细>></a></p>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
    <!--右侧结束-->
    <div class="clear"></div>
</div>
<div id="getcards_opacity"></div>
<div id="getcards_savebox" style="display: none;">
    <a class="close" href="#">关闭</a>
    <div class="text">
        <p id="errcontent"></p>
    </div>
</div>