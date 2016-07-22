<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>
<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("zhaosArea.js")?>
<?php echo URL::webjs("common.source.js")?>
<?php echo URL::webjs("province.source.js")?>
<?php echo URL::webjs("my_business_infor.js")?>
<?php echo URL::webcss("renzheng.css"); ?>
<?php echo URL::webcss("select_area.css")?>
<?php echo URL::webjs("zhaos_box.js")?>
<?php echo URL::webcss("cz.css")?>
<?php echo URL::webjs("vali.js")?>
<!--右侧开始-->
<div class="opacityBg" id="opacityBg"></div>
<div class="right">	
		<h2 class="user_right_title">
	        <span>参展项目管理</span>
	        <div class="clear"></div>
    	</h2>
    	<div class="my_business_new">
    		<div class="project_detial project_release">
    			<form id="forminfo" action="<?php echo URL::website('/company/member/exhb/editExhbTuiGuang');?>" enctype="multipart/form-data" method="post">
    			<input type="hidden" name="project_id" value="<?php echo $project_id;?>" />
    			<input type="hidden" name="exhb_id" value="<?php echo $exhb_id;?>" />
    			<input type="hidden" name="status" value="<?php echo $status;?>" />
    			<ul class="info">
    				<li class="title"> <b>项目推广信息</b></li>
    				<li class="label">优惠宣传语：</li>
    				<li class="content">
	                    <input type="text" name="advertisement" maxlength="15" style="width:450px;" id="advertisement" value="<?php echo isset($forms['advertisement']) ? $forms['advertisement'] : '';?>"/>
	                    <p><font class="floleft">设置该项目优惠措施宣传语，吸引用户去关注该展会项目
                            <a class="zhaos_big_img" href="<?php echo URL::webstatic('images/zhanhui/shili3.png');?>" target="blank">示例</a></font></p>
                    <span class="info info2">请填写优惠宣传语</span>
                </li>
                <li class="label"><font>*</font>优惠金额券：</li>
                <li class="content">
                    <p><input type="text" name="project_coupon" maxlength="15" id="project_coupon" class="validate require number" style="width:150px;" value="<?php echo isset($forms['project_coupon']) ? $forms['project_coupon'] : '';?>"/>
                        <font>通过当前展会加盟该项目，投资者所能享受的优惠<a class="zhaos_big_img" href="<?php echo URL::webstatic('images/zhanhui/shili2.png');?>" target="blank">示例</a></font>
                    </p>
                </li>
                <li class="label"><font>*</font>优惠券数量：</li>
                <li class="content">
                    <input type="text" name="coupon_num" maxlength="15" id="coupon_num" class="validate require number" style="width:150px;" value="<?php echo isset($forms['coupon_num']) ? $forms['coupon_num'] : '';?>"/>
                    <font>本场展会项目优惠券设置数量，开展中还可随时增加</font>
                    <span class="info info2">请填写优惠券数量</span>
                </li>

                <li class="label">有效期：</li>

                <li class="content">
                    <input type="text" value="<?php echo (isset($forms['coupon_deadline']) && $forms['coupon_deadline']) ? date('Y-m-d',$forms['coupon_deadline']) : '';?>" style="width:150px;" onfocus="WdatePicker({minDate:'%y-%M-%d', maxDate:'#{%y+1}-%M-%d'})" name="coupon_deadline" maxlength="15" id="coupon_deadline"/><font>投资者领取项目优惠券后，企方为该投资者保留意向加盟/代理项目最后时间</font>
                    <span class="info info2">请填写有效时间</span>
                </li>
                <li class="label">一句话项目介绍：</li>
                <li class="content">
                    <input placeholder="最多支持50个汉字字符长度" type="text" style="width:450px;" name="project_introduction" maxlength="50" id="project_introduction" value="<?php echo isset($forms['project_introduction']) ? $forms['project_introduction'] : '';?>"/>
	                    <span class="info info2">请填写一句话项目介绍</span>
	                </li>
	                <li class="clear"></li>
    			</ul>
    			<input type="submit"class="yellow zhanhuisave" value="保存">
    			</form>
    		</div>
    	</div>    	
</div>
<script type="text/javascript">
$.vali({	
	"formid":"#forminfo",
	"submitid":".zhanhuisave"
});
</script>
