<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("invitepro.js")?>
<style>
.ryl_zizhi_ceng{height: 25px; display: block; line-height: 25px; opacity: 0.5; position: absolute; top: 104px; background-color:#000; width: 160px; left: 3px;}
.ryl_zizhi_ceng em{float:left; padding-left:7px; font-style:normal; color:#fff;}
.ryl_zizhi_ceng img{float:left; width:10px;padding-left:85px; padding-top:8px; cursor:pointer;}
</style>
 	
    <!--右侧开始-->
   
    <style>
.upLoad a {
    color: #0134FF;
    float: left;
    font-size: 14px;
    padding-left: 13px;
    padding-right: 27px;
}
</style>
    <div class="imgPopup" id="imgPopup">
        <div class="imgPopupTop"><a href="#" class="close" style="float:right;">关闭</a></div>
        <div class="imgZoom"><img id="imgZoom"/></div>
        <div class="imgShow"></div>
    </div>
    <div class="flashPopup flashPopup2" id="flashPopup">
        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" width="750" height="620" id="1234551236">
            <param name="movie" value="<?php echo URL::webstatic("flash/uploadProjectImg2.swf")?>" />
            <param name="allowScriptAccess" value="always" />
            <param name="quality" value="high" />
            <param name="wmode" value="transparent" />
            <embed src="<?php echo URL::webstatic("flash/uploadProjectImg2.swf");?>" quality="high" allowScriptAccess="always" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="750" height="620"></embed>
        </object>
    </div>
    <div class="popupDelete" id="popupDelete">
        <div class="closeImg" id="closeImg"><img src="<?php echo URL::webstatic("images/infor2/closeimg.gif") ?>" width="20" height="20"/></div>
        <div class="clear"></div>
        <p>确认要删除图片吗？</p>
        <div class="confirm"><a href="#" id="sure"><img src="<?php echo URL::webstatic("/images/infor2/true.gif") ?>" width="112" height="32" /></a><a href="#" id="cancel"><img src="<?php echo URL::webstatic("/images/infor2/btn9.gif") ?>" width="112" height="32"></a></div>
    </div>
    <div class="opacityBg" id="opacityBg"></div>
     <div class="right">
                        <h2 class="user_right_title">
                            <span>我的项目</span>
                            <div class="clear"></div>
                        </h2>
                        <div class="my_business_new">
                            <div class="project_detial upload_img">
                                <ul class="info">
                                    <li class="title"><b>项目资质图片</b><font>用于展示项目诚信度，更能吸引投资者查看此项目。</font></li>
                                   <?php if(count($list) < 1){?>
                                    <li class="content img" style="padding-left:0px;">
                                        <font style="float:none;">上传项目荣获奖项、项目所获专利证书等资质图片，建议上传图片尺寸：674px*539px</font>
                                    </li>
                                     
	                                     <div class="upLoad">
	                                      <div class="flashBtn" id="flashBtn" style="margin-bottom:30px;"></div>
	                                     </div>
                                     <?php }else{?>
                                     <div class="upLoadImgBtn" id="upLoadImgBtn" style="padding-right:15px;">
                                     <li style="margin-left: 0px;margin-top:-20px" class="content img"><img style="border:none;vertical-align:middle" src="<?php echo URL::webstatic("/images/infor2/uploadbtn.gif") ?>" width="117" height="32" id="flashBtn1" style="cursor:pointer"/><font>上传项目荣获奖项、项目所获专利证书等资质图片，建议上传图片尺寸：674px*539px</font></li>
                                     </div>
                                    <li class="img">
                                    <?php foreach ($list as $key=>$value){
                                    	if($value['redis_type'] == 1){
                                    		$url = "/company/member/ajaxcheck/deleteprojectimg?project_id=".arr::get($forms, 'project_id')."&project_type=2&redis_type=".$key."&redis_type_delete=1";
                                    	}else{
                                    		$url = "/company/member/ajaxcheck/deleteprojectimg?project_id=".arr::get($forms, 'project_id')."&id=".$value['project_certs_id']."&project_type=2";
                                    	}
                                		
                                	?>
                                        <span>
                                            <h4><?php echo mb_substr($value['project_imgname'],0,13,'UTF-8')."";  ?></h4>
                                            <i><img width="183" height="145" src="<?php echo URL::imgurl($value['project_img']);?>"/></i>
                                            <input type="hidden" value="<?=str_replace('/s_','/b_',$value['project_img']);?>">
                                            <a class="project_detial_product_img zhaos_big_img" href="<?=URL::imgurl(str_replace('/s_','/b_',$value['project_img']));?>" target="blank">查看大图</a>
                                            <a class="project_detial_product_img del delete" href="<?php echo URL::website($url) ?>">删除</a>
                                        </span>
                                        <?php }?>
                                        <p class="clear"></p>
                                    </li>
                                    <li class="img content"><button style="padding-top:0px;" class="red upload" id="when_finish">完  成</button></li>
                                    <?php }?>
                                </ul>
                                <?//=$page;?>
                            </div>
                        </div>
                    </div>
    <div class="clear"></div>
<?php echo URL::webjs("zhaos_box.js")?>

 <script type="text/javascript">
 	init_big_img();
    $(document).ready(function(){
        $("#when_finish").click(function(){
        	window.location.href="/company/member/project/showproject";
        });
     });
    // 关闭flash 浮层
    function closeBox(){
        $("#flashPopup").slideUp("500",function(){
            $("#opacityBg").hide();
        });
    }
    // 刷新页面
    function reloadWin(){
        window.location.reload();
    }
   
    </script>