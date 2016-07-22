<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<title></title>
<link rel="stylesheet" type="text/css" href="http://static.myczzs.com/css/reset.css">
<link type="text/css" href="http://static.myczzs.com/css/common.css" rel="stylesheet" />
<link type="text/css" href="http://static.myczzs.com/css/quickrelease.css" rel="stylesheet" />
<script type="text/javascript" src="http://static.myczzs.com/js/My97DatePicker1/WdatePicker.js"></script>
<script type="text/javascript" src="http://static.myczzs.com/js/vali_fastrelease.js"></script>

<body>
	<div class="quickrelease-header-wrap">
		<div class="quickrelease-header">
			<a href="javascript:;" class="alogolink fl"><img src="http://static.myczzs.com/images/header/logo.png"></a>
			<div class="quicknav fl">您的位置： <a href="javascript:;">一句话</a> > <a href="javascript:;">免费发布项目</a></div>
			<span class="logreg fr"><a href="javascript:;">登陆</a><a href="javascript:;">注册</a></span>
		</div>
	</div>
	<div class="quickrelease-content">
		<div class="quickhint">
			<h3>友情提示：</h3>
			<p>1、请不要使用带有"一句话网站平台"等字样的图片，信息标题及内容中请不要使用"一句话网站平台"等词语。</p>
			<p>2、以上情况一经发现，将视为虚假宣传并予以删除。</p>
			<p>3、发布重复信息或灌水信息，信息将会被删除，严重者将锁定帐户。</p>
			<p>4、请勿发布涉及民族宗教信仰违反国家法律法规的信息，一旦发现，将删除信息、锁定账户，严重者将提交司法机关进行法律制裁。</p>
		</div>
		<div class="releaseform">
			<div class="basicinformation">
				<h3>基本信息<i></i></h3>
				<ul >
					<li class="content1">
						<span class="spantitle" ><var>*</var> 品牌名称：</span>
						<input type="text"  id="spantitle1"  class="pingpaitext" maxlength="10">
						<font>最多支持10个汉字字符</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content2">
						<span class="spantitle">品牌发源地：</span>
						<span style="position: relative; display: inline-block;">
							<a href="#" class="select_area_toggle" data-url="/ajaxcheck/getArea" first-result=".province_id" second-result=".per_area_id" box-title="省级" select-all="true">请选择</a>
						<input type="hidden" name="province_id" class="province_id">
                        <input type="hidden" name="per_area_id" class="per_area_id">
						</span>
						<font>品牌诞生并发展壮大的地区</font>
                        
					</li>
					<li class="content3">
						<span class="spantitle">品牌历史：</span>
						<input type="text" class="pingpaitext" name="projcet_founding_time" onclick="WdatePicker({minDate:'1800-01-01'})">
						<font>品牌诞生时间</font>
					</li>
					<li class="content4">
						<span class="spantitle"><var>*</var> 所在区域：</span>
						<span style="position: relative; display: inline-block;" id="location">
							<a href="#" class="select_area_toggle" data-url="/ajaxcheck/getArea" first-result=".a" second-result=".b" box-title="省级" select-all="true">请选择</a>
							<input type="hidden" name="province_id" class="a">
                        	<input type="hidden" name="per_area_id" class="b">
						</span>
						<font>商家所在地或者项目所在地区，方便用户按照地域查找您的招商加盟信息</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
					<li class="content5">
						<span class="spantitle"><var>*</var> 行业分类：</span>
						<span style="position: relative; display: inline-block;" id="industry">
							<a href="#" class="select_area_toggle" data-url="/ajaxcheck/primaryIndustry" first-result=".a" second-result=".a" box-title="一级行业" select-all="true">请选择</a>
							<input type="hidden" name="province_id" class="a">
                        	<input type="hidden" name="per_area_id" class="a">
						</span>
						<font>招商加盟信息分类，投资者可以按照行业搜索到</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
				</ul>
				<h3>加盟信息<i></i></h3>
				<ul>
					<li class="content6">
						<span class="spantitle"><var>*</var> 投资金额：</span>
						<input type="radio" name="project_amount_type" id="need_money1" value="1">
                    	<label for="need_money1">5万以下</label>
                    	<input type="radio" name="project_amount_type" id="need_money2" value="2">
                    	<label for="need_money2">5-10万</label>
                    	<input type="radio" name="project_amount_type" id="need_money3" value="3">
                    	<label for="need_money3">10-20万</label>
                    	<input type="radio" name="project_amount_type" id="need_money4" value="4">
                    	<label for="need_money4">20-50万</label>
                    	<input type="radio" name="project_amount_type" id="need_money5" value="5">
                   		<label for="need_money5">50万以上</label>
						<font>投资此招商加盟信息所需的费用</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
					<li class="content7">
						<span class="spantitle">加盟费：</span>
						<input type="text" onkeyup="this.value=this.value.replace(/\D/g,'')"  class="pingpaitext">
						<font>投资者加盟此招商信息所需的费用</font>
					</li>
					<li class="content8">
						<span class="spantitle">保证金：</span>
						<input type="text"  onkeyup="this.value=this.value.replace(/\D/g,'')" class="pingpaitext">
						<font>投资者加盟此招商信息所需缴纳的诚信保证金，后期需退换</font>
					</li>
					<li class="content9">
						<span class="spantitle"><var>*</var> 支持加盟城市：</span>
						<span style="position: relative; display: inline-block;" id="pingpaitext9">
							<a href="#" class="select_area_toggle" data-url="/ajaxcheck/getArea" first-result=".a" second-result=".a" box-title="省级" select-all="true">请选择</a>
							<input type="hidden" name="province_id" class="a">
                        	<input type="hidden" name="per_area_id" class="a">
						</span>
						<font>支持项目在哪些地区可以招商加盟</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必选项</span>
					</li>
					<li class="content10">
						<span class="spantitle">经营模式：</span>
						<input type="checkbox" name="project_amount_type" id="checkbox1" value="1">
                    	<label for="checkbox1">经销</label>
                    	<input type="checkbox" name="project_amount_type" id="checkbox2" value="2">
                    	<label for="checkbox2">批发</label>
                    	<input type="checkbox" name="project_amount_type" id="checkbox3" value="3">
                    	<label for="checkbox3">网售</label>
                    	<input type="checkbox" name="project_amount_type" id="checkbox4" value="4">
                    	<label for="checkbox4">代理</label>
                    	<input type="checkbox" name="project_amount_type" id="checkbox5" value="5">
                   		<label for="checkbox5">加盟</label>
                   		<input type="checkbox" name="project_amount_type" id="checkbox6" value="6">
                    	<label for="checkbox6">直营</label>
                    	<input type="checkbox" name="project_amount_type" id="checkbox7" value="7">
                   		<label for="checkbox7">其他</label>
						<font>可以通过哪些方式投资此招商加盟此项目</font>
					</li>
				</ul>
				<h3>推广信息<i></i></h3>
				<ul>
					<li class="content11">
						<span class="spantitle" ><var>*</var>标题：</span>
						<input type="text"    id="spantitle2" class="pingpaitext">
						<font>你所发布的招商加盟信息名称</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content12">
						<span class="spantitle" ><var>*</var> 一句话介绍：</span>
						<input type="text" id="spantitle3"  class="pingpaitext">
						<font>招商加盟信息宣传语，最多15个汉字字符。示例：送长辈 黄金酒</font>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content13">
						<span class="spantitle"><var>*</var>信息详情：</span>
						<textarea id="spantitle4"></textarea>
						<p style="padding-left: 128px;"><font>介绍此招商加盟信息的特点、发展历程、优势、加盟流程、能给投资者带来怎样的收益等</font></p>
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content14">
						<span class="spantitle">展示图：</span>
                        <span id="imgPro" style="width:120px;">
<input id="project_img_url" class="validate require" type="hidden" value="" name="project_img_url">
<input id="project_logo" type="hidden" value="" name="project_imgname">

</span>
						<a href="javascript:;" class="yellow quickupload">请上传</a>
						<font>招商加盟信息图片、产品图片、店铺图片等，最多支持30张图片，建议每张图片建议尺寸：</font>
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
						<input type="text"  id="spantitle5"  class="pingpaitext">
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content16">
						<span class="spantitle"><var>*</var> 手机号码：</span>
						<input type="text" onkeyup="checkmobile(this)"  id="spantitle6" class="pingpaitext">
                        <span class="msg" style="color:red; margin-left: 10px; display:none">此处为必填项</span>
					</li>
					<li class="content17">
						<span class="spantitle">验证码：</span>
						<input type="text" name="mobile" style="width:112px;" class="pingpaitext" id="valid_code">
						<input href="javascript:;" id="send_code" type="button" value="获取验证码" class="yellow getcheck" name="send_code"></input>              
						<font>验证通过则享有所发项目免审核权利</font>
						<span class="msg" style="color: red; margin-left: 10px; display: none;">请输入正确的手机号</span>
					</li>
					<li class="content18">
						<span class="spantitle">座机号码：</span>
						<input type="text" onkeyup="this.value=this.value.replace(/\D/g,'')" placeholder="区号" class="pingpaitext" style="width:108px; padding-left: 10px;"><input style=" padding-left: 10px;width:205px;margin-left: 15px;" onkeyup="this.value=this.value.replace(/\D/g,'')" type="text" placeholder="号码" class="pingpaitext"><input style=" padding-left: 10px;width:105px;margin-left: 15px;" onkeyup="this.value=this.value.replace(/\D/g,'')" class="pingpaitext" type="text"placeholder="分机号">
					</li>
					<li class="content19">
						<span class="spantitle"></span>
						<a id="tijiao" href="javascript:;" class="red submitbtn" >马上发布</a>
					</li>
				</ul>
			</div>

			
		</div>
	</div>
	<div id="flashPopup"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="701" height="504" id="flashrek2"><param name="movie" value="http://static.yijuhua-alpha.net/flash/uploadImg_qsfb.swf?url=http://www.yijuhua-alpha.net/company/member/ajaxcheck/uploadComLogo&amp;fun=viewImage1"><param name="quality" value="high"><param name="wmode" value="transparent"><param name="allowScriptAccess" value="always"><embed src="http://static.yijuhua-alpha.net/flash/uploadImg_qsfb.swf?url=http://www.yijuhua-alpha.net/company/member/ajaxcheck/uploadComLogo&amp;fun=viewImage1" allowscriptaccess="always" id="fileId4" wmode="transparent" width="701" height="504" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></object></div>
	<div class="flashPopupwrap" style="z-index: 1005;"></div>
</body>
<script type="text/javascript">
init_big_img()
	
</script>
</html>