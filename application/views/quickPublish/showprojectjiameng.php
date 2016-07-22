<?php echo URL::webcss("common.css")?>
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("quickrelease.css")?>
<?php echo URL::webjs("vali_jiameng.js")?>
<?php echo URL::webcss("select_area.css")?>
<div class="quickrelease-content">
	<div class="releaseform">
		<div class="basicinformation">
			<h3>招商信息<i></i></h3>
			<form id="forminfo" method="post" action="<?php echo URL::website('/quick/project/editQuickJiaMeng');?>" enctype="multipart/form-data">
				<input type="hidden" name="project_id" value="<?php echo $forms['project_id'];?>" />
				<ul>
					<li class="content6">
						<span class="spantitle"><var>*</var> 投资金额：</span>
						<input type="radio" name="project_amount_type" id="need_money1" value="1" <?php if($forms['project_amount_type'] == 1){?>checked="checked"<?php }?>>
                    	<label for="need_money1">1万以下</label>
                    	<input type="radio" name="project_amount_type" id="need_money2" value="2" <?php if($forms['project_amount_type'] == 2){?>checked="checked"<?php }?>>
                    	<label for="need_money2">1-2万</label>
                    	<input type="radio" name="project_amount_type" id="need_money3" value="3" <?php if($forms['project_amount_type'] == 3){?>checked="checked"<?php }?>>
                    	<label for="need_money3">2-5万</label>
                    	<input type="radio" name="project_amount_type" id="need_money4" value="4" <?php if($forms['project_amount_type'] == 4){?>checked="checked"<?php }?>>
                    	<label for="need_money4">5-10万</label>
                    	<input type="radio" name="project_amount_type" id="need_money5" value="5" <?php if($forms['project_amount_type'] == 5){?>checked="checked"<?php }?>>
                   		<label for="need_money5">10万以上</label>
						<font>投资此招商加盟信息所需的费用</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
	</li>
	<li class="content7">
		<span class="spantitle">加盟费：</span>
		<input type="text" onkeyup="this.value=this.value.replace(/\D/g,'')"  class="pingpaitext" name="project_joining_fee" value="<?php echo $forms['project_joining_fee'];?>">&nbsp;&nbsp;&nbsp;元
		<font>投资者加盟此招商信息所需的费用</font>
	</li>
	<li class="content8">
		<span class="spantitle">保证金：</span>
		<input type="text"  onkeyup="this.value=this.value.replace(/\D/g,'')" class="pingpaitext" name="project_security_deposit" value="<?php echo $forms['project_security_deposit'];?>">&nbsp;&nbsp;&nbsp;元
		<font>投资者加盟此招商信息所需缴纳的诚信保证金，后期需退换</font>
	</li>
	<li class="content9 clearfix">
		<span class="spantitle fl"><var>*</var> 支持加盟城市：</span>
		<span ckass="fl" style="position: relative;display: inline-block;" id="pingpaitext9">
			<div class="list_li" id="diqu">
                        	<?php
                       			if(isset($forms['area']) && count($forms['area']) > 0){
                        		foreach ($forms['area'] as $k=>$v){
                          	?>
                     		<div class="list">
                         		<dl>
                          		<dt id="webcity_<?php echo $v['pro_id']?>"><?php echo $v['pro_name']?><img src="<?php echo URL::webstatic("images/zhaos/diqu_close.png") ?>"><input type="hidden" value="<?php echo $v['pro_id']?>" name="project_city[]"></dt>
                             	<dd>
                            	<?php
                              		if(isset($v['data'])){
                             		foreach ($v['data'] as $key=>$value){
                              	?>
                              	<span id="webcity_<?php echo $value['area_id']?>"><?php echo $value['area_name']?><img src="<?php echo URL::webstatic("images/zhaoshangAddress/closeBg.gif") ?>"/>
                            	<input type="hidden" value="<?php echo $value['area_id']?>" name="project_city[]"></span>
                              	<?php
                              		}
                               		}
                              	?>
                              	</dd>

                            	</dl>
                       		</div>
                        	<?php
                          		}
                       			}
                         	?>
                        </div>
			<a href="#" class="add2_img_btn qingxuanze" data-tag="#select_area_box" data-title="选择招商地区" max-flag="true" data-width="748" id="addressClickEffect" style="float:left; text-decoration:none;">
                        		<span id="addImg"style="display: block;">请选择</span>
                        		<span id="addImg2" style="display: none;">重新添加</span>
                            </a>
	                            <script type="text/javascript">
				var select_area_list = '<?php foreach ($areas as $k=> $v):?><li><span><?php echo $v->cit_name;?></span><input type="hidden" name="true" class="<?php echo $v->cit_id;?>" /></li><?php endforeach;?>';
			</script>
		</span>
		<div class="clear"></div>
		<p style="margin-left:128px;"><font style="margin-left:0">支持项目在哪些地区可以招商加盟</font></p>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
					<li class="content10">
						<span class="spantitle"><var>*</var>招商形式：</span>						
                    	<input type="checkbox" name="project_model_type[]" id="checkbox2" value="2" <?php if(isset($forms['project_model']) && in_array(2, $forms['project_model'])){?>checked="checked"<?php }?>>
                    	<label for="checkbox2">批发</label>                    	
                    	<input type="checkbox" name="project_model_type[]" id="checkbox4" value="4" <?php if(isset($forms['project_model']) && in_array(4, $forms['project_model'])){?>checked="checked"<?php }?>>
                    	<label for="checkbox4">代理</label>
                    	<input type="checkbox" name="project_model_type[]" id="checkbox5" value="5" <?php if(isset($forms['project_model']) && in_array(5, $forms['project_model'])){?>checked="checked"<?php }?>>
                   		<label for="checkbox5">加盟</label>                   		
						<font>可以通过哪些方式加盟此生意</font>
						<span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
				</ul>
				<input type="submit"class="yellow quickrelsave" value="保存">
			</form>
		</div>
	</div>
</div>
