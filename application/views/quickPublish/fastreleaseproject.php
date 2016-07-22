<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("quickrelease.css")?>
<?php echo URL::webjs("vali_fastrelease.js")?>
<?php echo URL::webcss("select_area.css")?>
	<input type='hidden' class='hiddenDiv'/>
	<div class="quickrelease-content">
		<div class="quickhint">
			<h3>友情提示：</h3>
			<p>1、生意信息中请不要使用带有"一句话网站推荐"等字样的图片，信息标题及内容中请不要使用"一句话网站推荐"等词语。</p>
			<p>2、请勿发布与生意无关的信息，或者重复生意信息、灌水行为等信息。</p>
			<p>3、请勿发布涉及民族宗教信仰，违反国家法律法规的信息。</p>
			<p>4、以上情况一经发现，生意信息将予以删除，严重者将暂时冻结手机号码，甚至直接锁定帐户，违反国家法律法规的将提交司法机关进行处理。<a href="<?=urlbuilder::ruleDescription();?>"  target="_blank" style="color:#0b73bb;">详情请查看版规说明>> </a></p>
		</div>
		<div class="releaseform">
			<div class="basicinformation">
			  <form method="post" action="<?php echo URL::website('/quick/FastReleaseProject/DoAddFastReleaseProject')?> " enctype="multipart/form-data" id="formsubmit">
				<h3>基本信息<i></i></h3>
				<ul >
					<li class="content1">
						<span class="spantitle" ><var>*</var> 品牌名称：</span>
						<input type="text"  id="spantitle1"  class="pingpaitext" maxlength="10" name="project_brand_name">
						<font>最多支持10个汉字字符</font>
                        <span id="content1" class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content2">
						<span class="spantitle">品牌发源地：</span>
						<span style="position: relative; display: inline-block;">
							<a href="javascript:;" class="select_area_toggle" data-url="/ajaxcheck/getArea" first-result=".province_id" second-result=".per_area_id" box-title="省级" select-all="true" callback="$(this).html($('.province_id').attr('data-name')+'&nbsp;-&nbsp;'+$('.per_area_id').attr('data-name'))">请选择</a>
						<input type="hidden" name="birthplace_area_id" class="province_id">
                        <input type="hidden" name="birthplace_pro_id" class="per_area_id">
						</span>
						<font>品牌诞生并发展壮大的地区</font>
                        
					</li>
					<li class="content3">
						<span class="spantitle">品牌历史：</span>
						<span style="position: relative; display: inline-block">
							<a href="javascript:;" class="qingxuanze project_history">请选择</a>
						<ul class="project_historyul">
							<?php foreach ($arr_project_history as $key=>$val){?>
								<li date-type="<?=$key;?>"><?=$val;?></li>
							<?php }?>
						</ul></span>
						<input type="hidden" id="inputproject_history" name="project_history" value="">
						<font>品牌已存在时长</font>
					</li>
					<li class="content4">
						<span class="spantitle"><var>*</var> 所在区域：</span>
						<span style="position: relative; display: inline-block;" id="location">
							<a href="#" class="select_area_toggle" data-url="/ajaxcheck/getArea" first-result=".a" second-result=".b" box-title="省级" select-all="true" callback="$(this).html($('.a').attr('data-name')+'&nbsp;-&nbsp;'+$('.b').attr('data-name'))">请选择</a>
							<input type="hidden" name="merchants_area_first_id" class="a">
                        	<input type="hidden" name="merchants_area_second_id" class="b">
						</span>
						<font>商家所在地或者项目所在地区，方便用户按照地域查找您的招商加盟信息</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
					<li class="content5">
						<span class="spantitle"><var>*</var> 行业分类：</span>
						<span style="position: relative; display: inline-block;" id="industry">
							<a href="#" class="select_area_toggle" data-url="/ajaxcheck/primaryIndustry" first-result=".c" second-result=".d" box-title="一级行业" select-all="true" callback="$(this).html($('.c').attr('data-name')+'&nbsp;-&nbsp;'+$('.d').attr('data-name'))">请选择</a>
							<input type="hidden" name="first_industry_id" class="c">
                        	<input type="hidden" name="second_industry_id" class="d">
						</span>
						<font>招商加盟信息分类，投资者可以按照行业搜索到</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
				</ul>
				<h3>招商信息<i></i></h3>
				<ul>
					<li class="content6">
						<span class="spantitle"><var>*</var> 投资金额：</span>
						<?php $arr_moneyArr = common::moneyArr();
							foreach ($arr_moneyArr as $key=>$val){?>
								<input type="radio" name="project_amount_type" id="need_money<?=$key;?>" value="<?=$key;?>">
                    			<label for="need_money<?=$key;?>"><?=$val;?></label>
						<?php }?>
						<font>投资此招商加盟信息所需的费用</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
					<li class="content7">
						<span class="spantitle">加盟费：</span>
						<input type="text" onkeyup="this.value=this.value.replace(/\D/g,'')"  class="pingpaitext" name="project_joining_fee">&nbsp;&nbsp;&nbsp;元
						<font>投资者加盟此招商信息所需的费用</font>
					</li>
					<li class="content8">
						<span class="spantitle">保证金：</span>
						<input type="text"  onkeyup="this.value=this.value.replace(/\D/g,'')" class="pingpaitext" name="project_security_deposit">&nbsp;&nbsp;&nbsp;元
						<font>投资者加盟此招商信息所需缴纳的诚信保证金，后期需退换</font>
					</li>
					<li class="content9 clearfix">
						<span class="spantitle fl"><var>*</var> 支持加盟城市：</span>
						<span ckass="fl" style="position: relative;display: inline-block;" id="pingpaitext9">
							<div class="list_li" id="diqu"></div>
							<a href="#" class="add2_img_btn qingxuanze" data-tag="#select_area_box" data-title="选择招商地区" max-flag="true" data-width="748" id="addressClickEffect" style="text-decoration:none; margin-top: 2px; margin-left: 4px;">
                        		<span id="addImg"style="display: block;">请选择</span>
                        		<span id="addImg2" style="display: none;">重新添加</span>
                            </a><font>支持项目在哪些地区可以招商加盟</font>
                            <script type="text/javascript">
								var select_area_list = '<?php foreach ($areas as $k=> $v):?><li><span><?php echo $v->cit_name;?></span><input type="hidden" name="true" class="<?php echo $v->cit_id;?>" /></li><?php endforeach;?>';
							</script>
						</span>
						<span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
					<li class="content10">
						<span class="spantitle"><var>*</var>招商形式：</span>						
                    	<input type="checkbox" name="project_model_type[]" id="checkbox2" value="2" class="ml5">
                    	<label for="checkbox2">批发</label>                    	
                    	<input type="checkbox" name="project_model_type[]" id="checkbox4" value="4" class="ml5">
                    	<label for="checkbox4">代理</label>
                    	<input type="checkbox" name="project_model_type[]" id="checkbox5" value="5" class="ml5">
                   		<label for="checkbox5">加盟</label>                   		
						<font>可以通过哪些方式加盟此生意</font>
						<span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
					 
				</ul>
				<h3>推广信息<i></i></h3>
				<ul>
					<li class="content11">
						<span class="spantitle" ><var>*</var>标题：</span>
						<input type="text"    id="spantitle2" class="pingpaitext" name="project_title" maxlength="25">
						<font>你所发布的招商加盟信息名称，最多支持25个汉字字符长度</font>
                        <span id="content11" class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content12">
						<span class="spantitle" >一句话介绍：</span>
						<input type="text" id="spantitle3" maxlength="35"  class="pingpaitext" name="project_introduction">
						<font>招商加盟信息宣传语，最多35个汉字字符。示例：送长辈 黄金酒</font>
                        <span id="content12" class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content13 clearfix">
						<span class="spantitle fl"><var>*</var>信息详情：</span>
						<div id="spantitle4" class="fl" style="width:580px;">
							<?php echo  Editor::factory("","quick_editor",array("field_name"=>'project_summary',"width"=>"580","height"=>"200"));?>
						</div>
						<div class="clear"></div>
						<font style="margin-left:128px">介绍此招商加盟信息的特点、发展历程、优势、加盟流程、能给投资者带来怎样的收益等</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content14">
						<span class="spantitle">展示图：</span>
                        <span id="imgPro" style="width:120px;">
							<input id="project_img_url" class="validate require" type="hidden" value="" name="project_img_url">
							<input id="project_logo" type="hidden" value="" name="project_imgname">

						</span>
						<a href="javascript:;" class="yellow quickupload">请上传</a>
						<font>招商加盟信息图片、产品图片、店铺图片等，最多支持8张图片，建议每张图片建议尺寸：510px*404px</font>
						<div class="imgbox clearfix">
							
						</div>
						<div class="clearfix"></div>
						<p class="clickp">展开更多图片>></p>
                        <input type="hidden"  id="logoimg"  value="">
					</li>
				</ul>
				<h3>联系人信息<i></i></h3>
				<ul>
					<li class="content15">
						<span class="spantitle"><var>*</var> 联系人：</span>
						<input type="text"  id="spantitle5"  class="pingpaitext" name="project_contact_people" maxlength="6">
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content16">
						<span class="spantitle"><var>*</var> 手机号码：</span>
						<input type="text" maxlength="13" onkeyup="checkmobile(this)"  id="spantitle6" class="pingpaitext" name="mobile_num" value="<?=arr::get($userinfo,"mobile","")?>" <?php if(arr::get($userinfo,"mobile") !=""){echo 'readonly="true"';}?>>
                        <font>手机号码将作为后续生意信息管理的登陆账号，请妥善保存。</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">手机号码填写有误</span>
                        <input  type="hidden" value="1" name="project_status" id="project_status"/>
					</li>
					<li class="content17">
						<span class="spantitle">验证码：</span>
						<input type="text" name="mobile" style="width:92px;" class="pingpaitext" id="valid_code">
						<input id="send_code" type="button" value="获取验证码" class="yellow getcheck" name="send_code"></input>              
						<!--  <font>验证通过则享有所发项目免审核权利</font>-->
						<span class="error" style="color: red; margin-left: 10px; display: none;">请输入正确的手机号</span>
					</li>
					<li class="content18">
						<span class="spantitle">座机号码：</span>
						<input name="quhao" type="text" onkeyup="this.value=this.value.replace(/\D/g,'')" placeholder="区号" class="pingpaitext" style="width:108px; padding-left: 10px;">
						<input name="haoma" style=" padding-left: 10px;width:205px;margin-left: 15px;" onkeyup="this.value=this.value.replace(/\D/g,'')" type="text" placeholder="号码" class="pingpaitext">
						<input name="fenjihao" style=" padding-left: 10px;width:105px;margin-left: 15px;" onkeyup="this.value=this.value.replace(/\D/g,'')" class="pingpaitext" type="text"placeholder="分机号">
					</li>
					<li style="margin-top: 0">
						<span class="spantitle"> </span>
						<span style="font-size: 14px;">发布信息即代表您已阅读并同意一句话生意网<a href="<?=urlbuilder::ruleDescription();?>"  target="_blank" style="color: #0b73bb; text-decoration:underline;margin-left:5px;">版规说明</a></span>
					</li>
					<li class="content19">
						<span class="spantitle"></span>
						<input type="submit" value="马上发布" id="tijiao"  class="red submitbtn">
					</li>
				</ul>

				</form>
			</div>
			<?php if(isset($mobile_num) && $mobile_num > 0){?>
			<input type="hidden" id="project_mobile_num" value="<?=$mobile_num;?>"/>
		<?php }?>
		</div>
	</div>
	<div id="flashPopup">
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="741" height="504" id="flashrek2">
		<!--  <param name="movie" value="http://static.yijuhua-alpha.net/flash/uploadImg_qsfb.swf?url=http://www.yijuhua-alpha.net/company/member/ajaxcheck/uploadComLogo&amp;fun=viewImage1">-->
		<param name="movie" value="<?php echo URL::webstatic('flash/uploadImg_qsfb.swf?url='.URL::website('company/member/ajaxcheck/uploadComLogo&amp;fun=viewImage1').'');?>">
		<param name="quality" value="high"><param name="wmode" value="transparent">
		<param name="allowScriptAccess" value="always">
		<!--  
		<embed src="http://static.yijuhua-alpha.net/flash/uploadImg_qsfb.swf?url=http://www.yijuhua-alpha.net/company/member/ajaxcheck/uploadComLogo&amp;fun=viewImage1" allowscriptaccess="always" id="fileId4" wmode="transparent" width="741" height="504" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash">
		-->
		<embed src="<?php echo url::webstatic('flash/uploadImg_qsfb.swf?url='.URL::website('company/member/ajaxcheck/uploadComLogo&amp;fun=viewImage1').'');?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="741" height="504" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash">
		</object>
	</div>
	<div class="flashPopupwrap" style="z-index: 1005;"></div>
<script type="text/javascript">
//init_big_img();
var relpupor;
$(function(){
	
	var isfabuproject = <?php echo $isHaveFaBuProject ? $isHaveFaBuProject :0;?>;
	var type="<?php echo $type;?>";
	var user_id =<?php echo isset($user_id) ? $user_id : 0;?>;
    var user_status =<?php echo isset($user_status) ? $user_status : 0;?>;
    var resonContent = "<p class='reasonMsg'><font style='margin:0 5px;'>友情提示:</font>防止他人冒用你的账号发布违法违规信息，两条信息发布时间请间隔<font style='margin:0 5px;'>5</font>分钟以上。</p>";
   
	if(type == 2){
		if(user_id > 0 || user_status == 2){
			relpupor = window.MessageBox({
			    title:"一句话网站提示您",
			    content:'<p>招商加盟信息发布成功，进入人工审核阶段，最多1个工作日内完成审核，审核结果将以短信告知。</p><p class="btn"><a href="<?php echo urlbuilder::qucikProManage();?>" class="red closepupor">去管理已发布的生意</a><a href="<?=URL::website('').'zhaoshangjiameng.html';?>" class="red">去找生意</a></p>',
			    width:400,
			    onclose:function(){
					    if(isfabuproject == false || isfabuproject == 0){
							window.MessageBox({
								title:"一句话生意网提示您",
								content:resonContent,
								btn:"ok"
							});			        	
						}
				    return true;
				}
			  });
			  return false;
		}else{
			relpupor = window.MessageBox({
			    title:"一句话网站提示您",
			    content:'<p>恭喜，您的招商加盟信息发布成功，您就可以坐等投资者和您联系啦。</p><p class="btn"><a href="<?php echo urlbuilder::qucikProManage();?>" class="red alterinfo">去管理已发布的生意</a><a href="<?=URL::website('').'zhaoshangjiameng.html';?>" class="red">去找生意</a></p>',
			    width:400
			  });

			$(".alterinfo").click(function(){
				relpupor.hide();
				var html='<div class="clearfix loginpuper"><a class="fl yiaccount" href="'+window.$config.siteurl+'member/login"><span class="spantitle">已有帐号登录</span><span class="spaninfo">会员通过用户中心修改删除自己发布的信息</span>	</a><a class="fl mobileaccount" href="javascript:;"><span class="spantitle">手机号码登陆</span><span class="spaninfo">电话被冒用或忘记密码可以使用此功能</span></a></div>';
				loginpuper=window.MessageBox({
				    title:"请选择登录方式",
				    content:html,
				    width:790
				});
			})
			return false;
		}
	}
	if(isfabuproject == false || isfabuproject == 0){
		window.MessageBox({
			title:"一句话生意网提示您",
			content:resonContent,
			btn:"ok"
		});			        	
	}
	$(".closepupor").live("click",function(){
		relpupor.hide();
	})
})
$(".project_history").toggle(function(){
	$(".project_historyul").show();
},function(){
	$(".project_historyul").hide();
})
$(".project_historyul li").click(function(){
	$(".project_history").text($(this).text())
	$("#inputproject_history").val($(this).attr("date-type"));
	$(this).parent().hide();
	return false;
})
function checkmobile(obj)
{
obj.value=obj.value.replace(/[^0-9-]+/,'');
} 
</script>
