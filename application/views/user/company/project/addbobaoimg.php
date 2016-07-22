<?php echo URL::webcss("my_bussines_bobao.css")?>
<?php echo URL::webcss("renzheng.css"); ?>
<?php echo URL::webcss("zhaoshang_zg.css"); ?>
<?php echo URL::webjs("bobao.js")?>
<?php echo URL::webjs("zhaos_box.js")?>
<!--主体部分开始-->
<div class="right">

    <!--投资考察会成果播报-->    
                            <h2 class="user_right_title">
                            <span>投资考察会成果播报</span>
                            <div class="clear"></div>
                        </h2>
                        <div class="my_business_new">
                            <form method="post" action="/company/member/project/addbobaoimg" id="bobao_img">
                            <div class="project_detial upload_img">
                                <ul class="info">
                                    <li class="title"><b>产品图片</b><font>用于展示项目产品特点，吸引投资者查看此项目。建议上传图片尺寸：674px*539px</font></li>
<!--                                     <li class="img content"> -->
<!--                                         <button>上  传</button> -->
<!--                                     </li> -->


                                         <?php if(count($bobao_img) > 0){?>
                                         <div class="upLoadImgBtn" id="upLoadImgBtn" style="padding-right:15px;">
                                         <li style="margin-left: 45px"><img src="<?php echo URL::webstatic("/images/infor2/uploadbtn.gif") ?>" width="117" height="32" id="flashBtn1" style="cursor:pointer"/></li>
                                         <li class="img">
                                        
                                         <?php foreach ($bobao_img as $v){ ?>
                                            <span>
                                            <i><img width="183" height="145" src="<?=$v?>"/></i>
                                            <input type="hidden" name="bobao_img[]" value="<?=$v?>">
                                            <a class="project_detial_product_img zhaos_big_img" href="<?=URL::imgurl(str_replace('/s_','/b_',$v));?>" target="blank">查看大图</a>
                                            <a class="project_detial_product_img del delete" href="javascript:;">删除</a>
                                            </span>
                                            <?php }?>
                                        <p class="clear"></p>
                                        
                                        </li>
                                          <li class="img content"><button class="red upload" id="when_finish">完  成</button></li>
                                         </div>
                                         <?php }else{?>
                                         <div class="upLoadImgBtn" id="upLoadImgBtn" style="padding-right:15px;display: none" >
                                         <li style="margin-left: 45px"><img src="<?php echo URL::webstatic("/images/infor2/uploadbtn.gif") ?>" width="117" height="32" id="flashBtn1" style="cursor:pointer"/></li>
                                         <li class="img">
                                        

                                        <p class="clear"></p>
                                        
                                        </li>
                                          <li class="img content"><button class="red upload" id="when_finish">完  成</button></li>
                                         </div>

                                         <?php }?>
                                </ul>
                                    <div class="upLoad noupload" <?php if(count($bobao_img) > 0){?> style="display:none" <?php }?>>
                                            <div class="flashBtn" id="flashBtn" style="margin-bottom:30px;"></div>
                                        </div>
                                <input type="hidden" id ="invest_id" name="invest_id" value="<?=$invest_id?>">
                            </div>
                                </form>
                        </div>

</div>
<!--主体部分结束-->


<!--删除弹出框 开始-->
<div id="popupBg" class="popupBg" style="opacity:0.7;filter:alpha(opacity=70);"></div>
<div class="rzPopup" id="rzPopup">
    <dl>
        <dt><img src="<?php echo URL::webstatic("images/getcards/close.jpg"); ?>" id="close"/></dt>
        <dd class="first">
            <p style="text-align:center;">确认要删除此图片吗？</p>
                            <span>
                                <input type="button" value="" class="btn1" id="btn4"/>
                                <input type="button" value="" class="btn2" id="btn2"/>
                            </span>
        </dd>
        <dd class="second"></dd>
    </dl>
</div>
<!--删除弹出框 结束-->
<!--上传图片弹出框-->
<div class="flashPopup" id="flashPopup">
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" width="750" height="500" id="1234551236">
        <param name="movie" value="<?php echo URL::webstatic("flash/uploadImg_zsh.swf?img_allow=".$img_allow."&img_left=".$img_left)?>" />
        <param name="allowScriptAccess" value="always" />
        <param name="quality" value="high" />
        <param name="wmode" value="transparent" />
        <embed src="<?php echo URL::webstatic("flash/uploadImg_zsh.swf?img_allow=".$img_allow."&img_left=".$img_left);?>" quality="high" allowScriptAccess="always" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="750" height="500"></embed>
    </object>
</div>
<div class="opacityBg" id="opacityBg"></div>
<script type="text/javascript">
    // 关闭flash 浮层
    function closeBox(){
        $("#flashPopup").slideUp("500",function(){
            $("#opacityBg").hide();
        });
    }
    // 刷新页面
    function reloadWin(){
        // window.location.reload();
    }
    //上传完成在页面上显示
    function getImageInfo(_imagesInfo){
        _imagesInfo = _imagesInfo.split("||");
        $(".upLoadImgBtn").show();
        for(var i=0; i<_imagesInfo.length; i++){
            $("#upLoadImgBtn li").eq(1).prepend('<span><i><img width="183" height="145" src='+_imagesInfo[i]+'></i><input type="hidden" name="bobao_img[]" value='+_imagesInfo[i]+'><a class="project_detial_product_img zhaos_big_img" href='+_imagesInfo[i]+' target="blank">查看大图</a><a class="project_detial_product_img del delete" href="javascript:;">删除</a></span></span>');
        }
        $(".noupload").hide();
        closeBox();
    }

    $(".delete").live("click",function(){
        var _this=$(this);
        var img_url = $(this).prevAll("input").val();
        var noupload=$(".noupload");
        var invest_id = $("#invest_id").val();
        $("body")[0].show({
            content:"<p>确定要删除图片吗？</p>",
            btn:"ok cancel",
            callback:function delli(){
                var dt=$.ajaxsubmit("/company/member/ajaxcheck/delBobaoImg",{invest_id:invest_id,img_url:img_url});
                if(_this.parent().parent().find("span").length==1){
                    _this.parents("#upLoadImgBtn").hide();
                    _this.parent().remove();
                    noupload.show();
                }
                else{
                   _this.parent().remove(); 
                }
                
            }
        });
        return false;
    })
    $("#when_finish").click(function(){
        $("#bobao_img").submit();
    })
    init_big_img();
</script>
<!--上传图片弹出框 END-->
