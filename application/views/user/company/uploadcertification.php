<?php echo URL::webjs("renzheng.js");?>
<?php echo URL::webcss("renzheng.css"); ?>
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
    <div id="right">
        <div id="right_top"><span>企业资质认证</span><div class="clear"></div></div>
        <div id="right_con">
        <form enctype="multipart/form-data" method="post" action="<?php echo URL::website('company/member/basic/uploadcertification');?>">
           <div class="comrzContent" style="padding:11px 20px 0 20px;">
                <h3 style="background:none;">通过诚信认证的企业更能获得投资者的青睐，请上传您的企业营业执照，税务登记证，和组织机构代码证进行企业资质的认证。<span style=" font:12px/26px '宋体';">（<em style="color:#f00; font-style:normal;">*</em>为必传项）</span></h3>
                <div class="scListContent">
                    <div class="renzhengContent">
                        <h2>
                            <span class="grayColor" style="width:105px; text-align:right;"><em style="color:#f00; font-style:normal;">*</em> 企业营业执照：</span>
                            <span class="uploadImg" id="uploadImg">
                             <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="112" height="32" id="flashrek2">
                            <param name="movie" value="<?php echo URL::webstatic('/flash/uploadimage_company.swf?url='.URL::website('/company/member/ajaxcheck/uploadComCerts').'&swfid=1&curimg='.$com_business_licence)?>" />
                            <param name="quality" value="high" />
                            <param name="wmode" value="transparent" />
                            <param name="allowScriptAccess" value="always" />
                            <embed src="<?php echo URL::webstatic('/flash/uploadimage_company.swf?url='.URL::website('/company/member/ajaxcheck/uploadComCerts').'&swfid=1&curimg='.$com_business_licence)?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                            </object>
                            </span>
                            <span class="spanDeco">已上传<em id="num1"><?=$com_business_licence?></em>张，还可上传<em id="imgNum1"><?php echo 2-$com_business_licence;?></em>张(正反面)，支持JPG、GIF、PNG等,大小不超过4M</span>
                            <div class="clear"></div>
                        </h2>
                    </div>
                    <div class="imgList imgListSpe" id="list1">
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
                            <span style="width:110px; text-align:left;" class="grayColor"><em style="color:#f00; font-style:normal;">*</em> 企业营业执照编号:</span>
                            <span><input type="text" name="com_business_licence_number" id="zzbhtext" style="width:254px; border: 1px solid #dbdbdb; height: 23px; line-height: 23px;" value="<?php if( $com_business_licence_number=='0' ){ echo ''; }else{ echo $com_business_licence_number;}?>"></span>
                        </h2>
                    </div>
                    <div class="renzhengContent">
                            <h2>
                            <span class="grayColor" style="width:105px; text-align:right;"><em style="color:#f00; font-style:normal;">*</em>组织机构代码证：</span>
                            <span class="uploadImg" id="uploadImg">
                              <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="112" height="32" id="flashrek2">
                            <param name="movie" value="<?php echo URL::webstatic('/flash/uploadimage_company.swf?url='.URL::website('/company/member/ajaxcheck/uploadComCerts').'&swfid=2&curimg='.$organization_credit)?>" />
                            <param name="quality" value="high" />
                            <param name="wmode" value="transparent" />
                            <param name="allowScriptAccess" value="always" />
                            <embed src="<?php echo URL::webstatic('/flash/uploadimage_company.swf?url='.URL::website('/company/member/ajaxcheck/uploadComCerts').'&swfid=2&curimg='.$organization_credit)?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                            </object>
                            </span>
                            <span class="spanDeco">已上传<em id="num2"><?=$organization_credit;?></em>张，还可上传<em id="imgNum2"><?php echo 2-$organization_credit;?></em>张(正反面)，支持JPG、GIF、PNG等,大小不超过4M</span>
                            <div class="clear"></div>
                            </h2>
                    </div>
                    <div class="imgList imgListSpe" id="list2">
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
                            <span class="grayColor" style="width:105px; text-align:right;">税务登记证：</span>
                            <span class="uploadImg" id="uploadImg">
                            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="112" height="32" id="flashrek2">
                            <param name="movie" value="<?php echo URL::webstatic('/flash/uploadimage_company.swf?url='.URL::website('/company/member/ajaxcheck/uploadComCerts').'&swfid=3&curimg='.$tax_certificate)?>" />
                            <param name="quality" value="high" />
                            <param name="wmode" value="transparent" />
                            <param name="allowScriptAccess" value="always" />
                            <embed src="<?php echo URL::webstatic('/flash/uploadimage_company.swf?url='.URL::website('/company/member/ajaxcheck/uploadComCerts').'&swfid=3&curimg='.$tax_certificate)?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="112" height="32" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                            </object>
                            </span>
                            <span class="spanDeco">已上传<em id="num3"><?=$tax_certificate;?></em>张，还可上传<em id="imgNum3"><?php echo 2-$tax_certificate;?></em>张(正反面)，支持JPG、GIF、PNG等,大小不超过4M</span>
                            <div class="clear"></div>
                            </h2>
                    </div>
                    <div class="imgList imgListSpe" id="list3">
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
                <div class="renzhengBtn" style="height:36px; padding:20px 0 20px 160px;"><input type="image" src="<?php echo URL::webstatic("/images/renzheng/btn3.gif"); ?>" id="renzhengBtn"/></div><?php ?>
           </div>
           </form>
        </div>
    </div>
    <!--右侧结束-->