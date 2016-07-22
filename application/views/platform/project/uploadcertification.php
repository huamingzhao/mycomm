<?php echo URL::webjs("renzheng.js");?>
<?php echo URL::webcss("renzheng.css"); ?>
<?php echo URL::webcss("platform/renling.css")?>
<style>
.renzhengBtn input {height: 32px; width: 136px;}
.renzhengContent h2 span {color: #7C7C7C;}
.renzhengContent span.grayColor{width:107px; text-align:right; color:#000;}
.renzhengContent span.grayColor em{color:#f00; font-style:normal;}
.renzhengContent h2 {padding:0;}
.renzhengContent h2 em {color:#f00;}
.scListContent h4{ padding: 40px 0 40px 115px; font:bold 15px/20px "微软雅黑"; height:20px;}
.scListContent h4 span{font:12px/20px "微软雅黑";}
.scListContent h4 span em{ font-style:normal; color:#f00;}
.renzhengContent {padding: 10px 0 0 105px;}
.renling_tel_succ{ padding-left:70px;}
.renling_tel_succ img{ width:102px; padding-right:10px;}
</style>
<!--右侧开始-->
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
<div class="rzPopup" id="rzPopup1">
    <dl>
        <dt><img src="<?php echo URL::webstatic("images/getcards/close.jpg"); ?>" id="close1"/></dt>
        <dd class="first">
            <p id="text">您还没有上传企业的<em id="em1">"企业营业执照"</em><em id="em3">"组织机构代码证"</em></p>
            <p>请上传完毕再认证</p>
        </dd>
        <dd class="second"></dd>
    </dl>
</div>

<!--公共背景框-->
<div class="main" style="background-color:#e3e3e3; height:auto;">
   <div class="ryl_main_bg">
       <div class="ryl_main_bg01"></div>
       <div class="ryl_main_bg02">
          <!--认领-->
          <div class="renling_main">
             <h3>企业认领项目</h3>
             <!--验证手机号码-->
             <div class="renling_title"><img src="<?php echo URL::webstatic('images/platform/renling/renling_title02.jpg');?>" /></div>
               <?php if($is_has_img || $is_has_img_no){?>
                            <form enctype="multipart/form-data" method="post" action="<?php echo URL::website('company/member/basic/uploadcertification?type=2&project_id='.$project_id);?>">
			             <div class="comrzContent" style="padding:11px 20px 0 20px;">
			                <div class="scListContent">
			                    <?php if(!$is_has_img_no){?>		
			                    <h4>请上传您的企业资质图片<span>（<em>*</em> 为必传项）</span></h4>
			                    <?php }?>
			                    <div class="renling_no_upload">
			                          <?php if($is_has_img_no){?>
			                          <img src="<?php echo URL::webstatic('images/platform/renling/renling_03.jpg');?>" />
			                           <p>						     
									      <label>您的企业资质图片已经上传，状态为：<strong>未通过审核</strong>。</label>
			                              <span>原因是：<?php echo $com_auth_unpass_reason;?>。（<em>*</em> 为必传项）</span>
									     </p>
									   <?php }?>
			                        
			                       <div class="clear"></div>
			                    </div>
			                    <div class="renzhengContent">
			                        <h2>
			                            <span class="grayColor"><em>*</em> 企业营业执照：</span>
			                            <span class="uploadImg" id="uploadImg">
			                             <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="112" height="32" id="flashrek2">
			                            <param name="movie" value="<?php echo URL::webstatic('/flash/uploadimage_company.swf?url='.URL::website('/company/member/ajaxcheck/uploadComCerts').'&swfid=1&curimg='.$com_business_licence)?>" />
			                            <param name="quality" value="high" />
			                            <param name="wmode" value="transparent" />
			                            <param name="allowScriptAccess" value="always" />
			                            <embed src="<?php echo URL::webstatic('/flash/uploadimage_company.swf?url='.URL::website('/company/member/ajaxcheck/uploadComCerts').'&swfid=1&curimg='.$com_business_licence)?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
			                            </object>
			                            </span>
			                            <span class="spanDeco">已上传<em id="num1"><?=$com_business_licence?></em>张，还可上传<em id="imgNum1"><?php echo 2-$com_business_licence;?>张</em>，支持JPG、GIF、PNG等,大小不超过2M</span>
			                            <div class="clear"></div>
			                        </h2>
			                    </div>
			                    <div class="imgList imgListSpe" id="list1" style="padding-left:213px;">
			                        <ul>
			                            <?php
			                            if(isset($commonimg_list['com_business_licence'])):
			                            foreach ($commonimg_list['com_business_licence'] as $k=>$v):
			                            ?>
			                                <li>
			                                    <span  class="<?=$v['common_img_id'];?>">删除图片</span>
			                                   <?php if(!empty($v['url'])){ echo HTML::image(URL::imgurl($v['url']));}?>
			                                </li>
			                            <?php
			                            endforeach;
			                            endif;
			                            ?>
			                        </ul>
			                    </div>
			                    <div class="clear"></div>
			                    <div class="renzhengContent">
			                            <h2>
			                            <span class="grayColor"><em>*</em> 组织机构代码证：</span>
			                            <span class="uploadImg" id="uploadImg">
			                              <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="112" height="32" id="flashrek2">
			                            <param name="movie" value="<?php echo URL::webstatic('/flash/uploadimage_company.swf?url='.URL::website('/company/member/ajaxcheck/uploadComCerts').'&swfid=2&curimg='.$organization_credit)?>" />
			                            <param name="quality" value="high" />
			                            <param name="wmode" value="transparent" />
			                            <param name="allowScriptAccess" value="always" />
			                            <embed src="<?php echo URL::webstatic('/flash/uploadimage_company.swf?url='.URL::website('/company/member/ajaxcheck/uploadComCerts').'&swfid=2&curimg='.$organization_credit)?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
			                            </object>
			                            </span>
			                            <span class="spanDeco">已上传<em id="num2"><?=$organization_credit;?></em>张，还可上传<em id="imgNum2"><?php echo 2-$organization_credit;?>张</em>，支持JPG、GIF、PNG等,大小不超过2M</span>
			                            <div class="clear"></div>
			                            </h2>
			                    </div>
			                    <div class="imgList imgListSpe" id="list2"  style="padding-left:213px;">
			                        <ul>
			                            <?php
			                            if(isset($commonimg_list['organization_credit'])):
			                            foreach ($commonimg_list['organization_credit'] as $k=>$v):
			                            ?>
			                                <li>
			                                    <span class="<?=$v['common_img_id'];?>">删除图片</span>
			                                   <?php if(!empty($v['url'])){ echo HTML::image(URL::imgurl($v['url']));}?>
			                                </li>
			                            <?php
			                            endforeach;
			                            endif;
			                            ?>
			                        </ul>
			
			                    </div>
			                    <div class="clear"></div>
			                    <div class="renzhengContent">
			                            <h2>
			                            <span class="grayColor">税务登记证：</span>
			                            <span class="uploadImg" id="uploadImg">
			                            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="112" height="32" id="flashrek2">
			                            <param name="movie" value="<?php echo URL::webstatic('/flash/uploadimage_company.swf?url='.URL::website('/company/member/ajaxcheck/uploadComCerts').'&swfid=3&curimg='.$tax_certificate)?>" />
			                            <param name="quality" value="high" />
			                            <param name="wmode" value="transparent" />
			                            <param name="allowScriptAccess" value="always" />
			                            <embed src="<?php echo URL::webstatic('/flash/uploadimage_company.swf?url='.URL::website('/company/member/ajaxcheck/uploadComCerts').'&swfid=3&curimg='.$tax_certificate)?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
			                            </object>
			                            </span>
			                            <span class="spanDeco">已上传<em id="num3"><?=$tax_certificate;?></em>张，还可上传<em id="imgNum3"><?php echo 2-$tax_certificate;?>张</em>，支持JPG、GIF、PNG等,大小不超过2M</span>
			                            <div class="clear"></div>
			                            </h2>
			                    </div>
			                    <div class="imgList imgListSpe" id="list3"  style="padding-left:213px;">
			                        <ul>
			                            <?php
			                            if(isset($commonimg_list['tax_certificate'])):
			                            foreach ($commonimg_list['tax_certificate'] as $k=>$v):
			                            ?>
			                                <li>
			                                    <span  class="<?=$v['common_img_id'];?>">删除图片</span>
			                                   <?php if(!empty($v['url'])){ echo HTML::image(URL::imgurl($v['url']));}?>
			                                </li>
			                            <?php
			                            endforeach;
			                            endif;
			                            ?>
			                        </ul>
			                    </div>
			                </div>
			                <div class="renzhengBtn" style="height:36px; padding:20px 0 20px 213px;"><input type="image" src="<?php echo URL::webstatic("/images/platform/renling/zhizhi_btn.jpg"); ?>" id="renzhengBtn"/></div><?php ?>
			           </div>
			           </form>
               <?php }elseif($tax_certificate_status==1&&$com_business_licence_status==1&&$organization_credit_status==1){?>
	                <div class="renling_tel_succ">
	                 <img src="<?php echo URL::webstatic('images/platform/renling/renling_02.jpg');?>"  style="height:50px;padding-top:9px;"/><span>您的企业资质图片已经上传，状态为：<b>审核通过</b></span><a target="_blank" href="<?php echo URL::website('company/member/basic/comCertification');?>">查看上传的企业资质图片</a>
	                 <div class="clear"></div>
	                 <a href="<?php echo URL::website('platform/project/writeProjectInfo').'?project_id='.$project_id;?>" class="renling_tel_next" style="padding-left:340px;"><img src="<?php echo URL::webstatic('images/platform/renling/next.jpg');?>" /></a>
	                 <div class="clear"></div>
	                </div>
               <?php }else{?>
               	      <div class="renling_tel_succ">
	                 <img src="<?php echo URL::webstatic('images/platform/renling/renling_01.jpg');?>"  style="height:56px;padding-top:9px;"/><span>您的企业资质图片已经上传，状态为：<b>正在审核中</b></span><a target="_blank" href="<?php echo URL::website('company/member/basic/comCertification');?>">查看上传的企业资质图片</a>
	                 <div class="clear"></div>
	                 <a href="<?php echo URL::website('platform/project/writeProjectInfo').'?project_id='.$project_id;?>" class="renling_tel_next" style="padding-left:340px;"><img src="<?php echo URL::webstatic('images/platform/renling/next.jpg');?>" /></a>
	                 <div class="clear"></div>
	                </div>
                <?php }?>

          </div>
          <div class="clear"></div>
       </div>
       <div class="ryl_main_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>