    <?php echo URL::webjs("renzheng.js")?>
    <?php echo URL::webcss("renzheng.css")?>
   <!--右侧开始-->
   <div id="popupBg" class="popupBg"></div>
<div class="rzPopup" id="rzPopup">
    <dl>
        <dt><img src="<?php echo URL::webstatic("images/renzheng/close.gif"); ?>" id="close"/></dt>
        <dd class="first">
            <p>确认要删除此图片吗？</p>
            <span>
                <input type="button" value="" class="btn1" id="btn3"/>
                <input type="button" value="" class="btn2" id="btn2"/>
            </span>
        </dd>
        <dd class="second"></dd>
    </dl>
</div>
    <div id="right">
        <div id="right_top"><span>项目资质认证</span><div class="clear"></div></div>
        <div id="right_con">
           <div class="renzhengContent">
                <h1>您可以上传您的项目资质相关照片，如：产品许可证，企业荣誉证书等，上传的照片可以添加到您的招商项目中哟！</h1>
                 <form action="<?php echo URL::website('company/member/basic/upProjectCert');?>" method="post" enctype="multipart/form-data" id="upcertification">
                 <h2><span>项目资质照片：</span><span class="uploadImg" id="uploadImg"><input type="file" name="project_certification[]" class="fileBtn" id="fileBtn" onchange="previewImage(this)"/>
                <input type="image" src="<?php echo URL::webstatic("images/renzheng/file.gif"); ?>" class="btnImages" id="btnImages"/></span>
                  已上传<em id="zhangNum"><?=$count?></em>张，还可上传<em id="zhangNum1"><?=$num?></em>张，支持JPG、GIF、PNG等,大小不超过2M</h2>
                  <div class="clear"></div>
                <div class="imgList" id="imgList">
                    <ul>
                      <?php
                    if(!empty($project_cert)){
                        foreach($project_cert as $cert){
                    ?>
                        <li>
                        <span class="<?=$cert['file_id']?>">删除图片</span>
                        <img src="<?=$cert['spic']?>" />
                        <input type='hidden' name='certs_id[]' value='<?=$cert['file_id']?>'>
                        <input type="text" value="<?=$cert['file_name']?>" name="certs_name[]" class="projectCerts" id="<?=$cert['file_id']?>"/>
                        </li>
                    <?php
                    }
                    }
                    ?>
                    </ul>
                </div>

                <div class="imgPara">
                    <div class='inputSubmit'><input type='submit' value='' id='renzhengSubmit'/><ins>请输入证件照片名称...</ins></div>
                    <?php if($count!=0){?> <p class='proMsg'><?php if($count<12){?>您已经上传<span><?=$count?></span>张项目资质照片，还可以继续上传哟！<?php }else{?>您已经上传<span id="zhangNum"><?=$count?></span>张项目资质照片，如果继续上传，请删除一些项目资质照片<?php }?>上传后的照片可以显示在您的招商项目中。</p><?php }?>
                </div>
                </form>
           </div>
        </div>
    </div>
    <script type="text/javascript">
// fileBtn   上传图片id
// imgBtn    覆盖上传按钮上的图片id
// imgList   插入图片的div
// zhangNum  已经插入的张数的id
var upload = new Upload("fileBtn","btnImages","imgList","zhangNum");
</script>
    <!--右侧结束-->
