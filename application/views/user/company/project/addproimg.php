<?php echo URL::webcss("zhaoshang_zg.css")?>

<?php echo URL::webjs("invitepro.js")?>
    
<style>
.upLoad a {
    color: #0134FF;
    float: left;
    font-size: 14px;
    padding-left: 13px;
    padding-right: 27px;
}
</style>
    <div class="flashPopup" id="flashPopup">
       <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" width="750" height="500" id="1234556">
            <param name="movie" value="<?php echo URL::webstatic("flash/uploadProjectImg.swf")?>" />
            <param name="allowScriptAccess" value="always" />
            <param name="quality" value="high" />
            <param name="wmode" value="transparent" />
            <embed src="<?php echo URL::webstatic("flash/uploadProjectImg.swf");?>" quality="high" allowScriptAccess="always" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="750" height="500"></embed>
      </object>
    </div>
    <div class="popupDelete" id="popupDelete">
        <div class="closeImg" id="closeImg"><img src="<?php echo URL::webstatic("/images/infor2/closeimg.gif") ?>" width="20" height="20"/></div>
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
                                    <li class="title"><b>产品图片</b><font>用于展示项目产品特点，吸引投资者查看此项目。建议上传图片尺寸：674px*539px</font></li>
<!--                                     <li class="img content"> -->
<!--                                         <button>上  传</button> -->
<!--                                     </li> -->
                                    <?php if(count($list) < 1){?>
                                       <div class="upLoad">
                        					<div class="flashBtn" id="flashBtn" style="margin-bottom:30px;"></div>
                   						</div>
                   						 <?php }else{?>
                   						 <div class="upLoadImgBtn" id="upLoadImgBtn" style="padding-right:15px;">
                   						 <li style="margin-left: 45px"><img src="<?php echo URL::webstatic("/images/infor2/uploadbtn.gif") ?>" width="117" height="32" id="flashBtn1" style="cursor:pointer"/></li>
                   						 <li class="img">
                   						
                   						 <?php foreach ($list as $key=>$value){
                   						 	if($value['redis_type'] == intval(1)){
                   						 		$url = "/company/member/ajaxcheck/deleteprojectimg?project_id=".arr::get($forms, 'project_id')."&project_type=1&redis_type=".$key;
                   						 	}else{
                   						 		$url = "/company/member/ajaxcheck/deleteprojectimg?project_id=".arr::get($forms, 'project_id')."&id=".$value['project_certs_id']."&project_type=1";
                   						 	}
                                			
                              			 ?>
                                        	<span>
                                            <i><img width="183" height="145" src="<?php echo URL::imgurl($value['project_img']);?>"/></i>
                                            <input type="hidden" value="<?=str_replace('/s_','/b_',$value['project_img']);?>">
                                            <a class="project_detial_product_img zhaos_big_img" href="<?=URL::imgurl(str_replace('/s_','/b_',$value['project_img']));?>" target="blank">查看大图</a>
                                            <a class="project_detial_product_img del delete" href="<?php echo URL::website($url) ?>">删除</a>
                                        	</span>
                                        	<?php }?>
                                        <p class="clear"></p>
                                        
                                    	</li>
                   						  <li class="img content"><button class="red upload" id="when_finish">完  成</button></li>
                   						 </div>
                   						 <?php }?>
                                </ul>
                                 <?=$page;?>
                            </div>
                        </div>
                    </div>
                    <!--透明背景开始-->
                    <div id="getcards_opacity" ></div>
                    <!--透明背景结束-->
                    <!--主体部分结束-->
                    <div class="clear"></div>
<?php echo URL::webjs("zhaos_box.js")?>

<!--右侧开始-->

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
