<div class="right">
	<h2 class="user_right_title">
		<span>我的项目</span>
    	<div class="clear"></div>
    </h2>
    <div class="my_business_new">
    <form action="<?php echo URL::website('/company/member/project/updateMoreInfo'); ?>" method="post">
    	<input type="hidden" name="project_id" value="<?php echo arr::get($forms, 'project_id')?>" />
    	<div class="project_detial project_release">
    		<ul class="info">
    			<li class="title"><b>更多项目详情信息</b></li>
    			<li class="label">主营产品：</li>
    			<li class="content">
                            <input type="hidden" value="<?=$type?>" name="type">
                	<input type="text" name="project_principal_products" value="<?php echo arr::get($forms, 'project_principal_products');?>" />
               		<font>贵公司所经营的主要产品（名称）</font>
         		</li>
       			<li class="label">加盟费：</li>
              	<li class="content">
             		<input class="text3" type="text" name="project_joining_fee" value="<?php if(isset($forms['project_joining_fee']) && $forms['project_joining_fee'] != 0){echo arr::get($forms, 'project_joining_fee');}else{echo '';}?>" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")' />万元
       				<font>投资者加盟此项目所需的费用</font>
          		</li>
       			<li class="label">保证金：</li>
        		<li class="content">
           			<input class="text3" type="text" name="project_security_deposit" value="<?php if(isset($forms['project_security_deposit']) && $forms['project_security_deposit'] != 0){echo arr::get($forms, 'project_security_deposit');}else{echo '';}?>" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")' />万元
             		<font>投资者加盟此项目所需缴纳的诚信保证金，后期退换</font>
             	</li>
            	<li class="label">年投资回报率：</li>
         		<li class="content">
          			<input type="radio" name="rate_return" class="need_money" id="need_money1" value="1" <?php if(arr::get($forms, 'rate_return',0) == 1){?>checked="checked"<?php }?>>
             		<label class="need_money" for="need_money1">10%以下</label>
             		<input type="radio" name="rate_return" class="need_money" id="need_money2" value="2" <?php if(arr::get($forms, 'rate_return',0) == 2){?>checked="checked"<?php }?>>
           			<label class="need_money" for="need_money2">10%-50%</label>
      				<input type="radio" name="rate_return" class="need_money" id="need_money3" value="3" <?php if(arr::get($forms, 'rate_return',0) == 3){?>checked="checked"<?php }?>>
                	<label class="need_money" for="need_money3">50%-100%</label>
                	<input type="radio" name="rate_return" class="need_money" id="need_money4" value="4" <?php if(arr::get($forms, 'rate_return',0) == 4){?>checked="checked"<?php }?>>
                	<label class="need_money" for="need_money4">100%以上</label>
                 	<font>投资与收获的比率，请如实填写</font>
              	</li>
               	<li class="label">需要的人脉关系：</li>
          		<li class="content">
              		<?php $lst = guide::attr5();foreach ($lst as $k=>$v){ ?>
                    	<label><input type="checkbox" name="connection[]" value="<?=$k;?>" <?php if(isset($get_onnection[$k-1])){echo "checked";}?>/><?=$v;?></label>
                    <?php } ?>
             	</li>
         		<li class="label">产品特点：</li>
             	<li class="content height_auto">
             		<textarea name="product_features"><?=isset($forms['product_features']) ? $forms['product_features'] : '';?></textarea>
            		<font>详细介绍此产品的起源、制作工艺、特点、功能等</font>
              	</li>
           		<li class="label">加盟详情：</li>
              	<li class="content height_auto">
             		 <?php
                     	echo  Editor::factory(isset($forms['project_join_conditions']) ? $forms['project_join_conditions'] : '',"nobars",array("field_name"=> 'project_join_conditions',"width"=>"580","height"=>"200"));
                     ?>
        			<font>说明加盟此项目所需要的条件，包括资金、地区、加盟方式等</font>
            	</li>
             	<li class="clear"></li>
    		</ul>
    		<ul class="info">
            	<li class="content"><button class="red sent">提交</button></li>
            	<li class="clear"></li>
            </ul>
    	</div>
    </form>
    </div>
</div>
<script>
$(document).ready(function(){
	$(".need_money").click(function(){
		$("#rate_return").val($(".need_money:checked").val());
	});
});
</script>