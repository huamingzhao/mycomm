<?php echo URL::webcss("account_zg.css")?>

    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>帐户明细查询</span><div class="clear"></div></div>
        <div id="right_con">
            <div class="accountDetail accountDetail_new">
            	<!-- 查看明细添加开始 -->
       		    <form method="get" id="accountDetail_submit" action="<?php echo URL::site('/company/member/account/accountlist')?>">
                <div class="detailTop">
                    <label>日期：</label>
                    <input id="startDate" name="start" value="<?=isset($start) ? $start: ''?>" type="text" readonly="readonly" onclick="WdatePicker({minDate:'1990-01-01',maxDate:'#F{$dp.$D(\'stopDate\')||\'%y-%M-%d\'}'})"/>
                    <label class="date_text">至</label>  
                    <input id="stopDate" name="end" value="<?=isset($end) ? $end : ''?>" type="text" readonly="readonly" onclick="WdatePicker({minDate:'#F{$dp.$D(\'startDate\')||\'1990-01-01\'}',maxDate:'%y-%M-%d'})"/>
                    <label class="select_text" >消费方式：</label>
                    <select name="account_comments_type">
                        <option value="0" <? if(isset($account_comments_type)&& $account_comments_type==0){echo 'selected';}?>>全部方式</option>
                        <option value="6" <? if(isset($account_comments_type)&& $account_comments_type==6){echo 'selected';}?>>查看名片</option>
                        <option value="7" <? if(isset($account_comments_type)&& $account_comments_type==7){echo 'selected';}?>>递出名片</option>
                        <option value="9" <? if(isset($account_comments_type)&& $account_comments_type==9){echo 'selected';}?>>查看报名投资考察会</option>
                    </select>
                   <input type="image" class="submit" width="67" height="26" src="<?php echo URL::webstatic('/images/account/account_cz_03.png');?>">
                	
                </div>
                </form>
                <div style="display:block">
                <div class="detailTop">
                    您的账户余额 <font class="num"><?php echo $account_amount!=""?$account_amount:'0';?></font> 元，总支出 <font class="num"><?php echo $reduceaccount!=""?$reduceaccount:'0';?></font> 元<?php 
                    if($start && !$end){
                    	echo '，'.$start_date.'至今'.$account_comments_type_arr[$account_comments_type].'支付 <font class="num">'.$sub_reduceaccount.'</font> 元';
                    }
                    if(!$start && $end){
                    	echo '，截止'.$end_date.$account_comments_type_arr[$account_comments_type].'支付 <font class="num">'.$sub_reduceaccount.'</font> 元';
                    }
                    if($start && $end ){ 	
						echo '，'.$start_date.'至'.$end_date.$account_comments_type_arr[$account_comments_type].'支付 <font class="num">'.$sub_reduceaccount.'</font> 元'; 						
					}?>
					<?php if(!$start && !$end && $account_comments_type){
							echo '，其中'.$account_comments_type_arr[$account_comments_type].'支付 <font class="num">'.$sub_reduceaccount.'</font> 元';
					}?> 
                    <!-- <div class="income">收入：<span><?php echo $addaccount!=""?$addaccount:"0";?></span>元</div>
                    <div class="export">支出：<span><?php echo $reduceaccount!=""?$reduceaccount:'0';?></span>元</div> -->
                   <div class="daochu"><a style="font-weight:bold;" href="<?php echo URL::website('/company/member/account/getAccountExcel?start='.$start_date.'&end='.$end_date.'&account_comments_type='.$account_comments_type.'')?>" target="_blank">导出</a></div>
                   </div>
                  <div class="clear"></div>
                </div>
                <dl class="accountdlList">
                    <dt>
                        <span class="span_0">充值/支付时间</span>
                        <span class="span_1">内容明细</span>
                        <span class="span_2">充值金额</span>
                        <span class="span_3">支付金额</span>
                        <span class="span_4">账户余额 </span>
                        <span class="span_5">赠送金额</span>
                    </dt>
                    <?php foreach ($list as $k=>$v){ 
                    	//获取赠送金额
                    	$costfreee=ORM::factory('Accountlog')->where('account_comments_type', '=', 14)->where('account_type_id', '=', $v->account_type_id)->where('account_user_id', '=', $v->account_user_id)->find()->account_change_amount;?>
                    <dd>
                        <span class="span_0"><?php echo date('Y-m-d H:i',$v->account_log_time);?></span>
                        <span class="span_1"><?php echo mb_substr($v->account_note,0,12,'UTF-8');?></span>
                        <span class="span_2"><?php if($v->account_class==1){echo "+".$v->account_change_amount;}?>&nbsp;</span>
                        <span class="span_3"><?php if($v->account_class==2){echo "-".$v->account_change_amount;}?>&nbsp;</span>
                        <span class="span_4"><?php if($costfreee){echo number_format($v->account_amount+$costfreee, 2, '.', '');}else{echo  $v->account_amount;}?></span>
                        <span class="span_5"><?php if($costfreee){echo number_format($costfreee, 2, '.', '');}else{echo '--';}?></span>
                    </dd>
                    <?php }?>
                </dl>
                <div class="ryl_search_result_page">
                <?=$page;?>
                </div>
            </div>
        </div>
    </div>
    <!--右侧结束-->
<div class="clear"></div>
</div>
<?php echo URL::webjs("My97DatePicker1/WdatePicker.js")?>
<script language="javascript">
$(document).ready(function() {
    $('#time').change(function(){
        var time = $("#time option:selected").val();
        window.location.href = "/company/member/account/accountlist?time="+time;
    });
})
</script>