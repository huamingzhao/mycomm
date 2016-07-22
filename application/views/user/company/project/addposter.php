<?php echo URL::webcss("zhaoshang_zg.css")?>
 
<style>
.my_business_post0513 .nav li{ width:147px;padding-top:0px;}
.ryl_upload_view a.preview_officialweb {
    background-position: 0 9px;
    color: #0134FF;
    display: inline-block;
    float: right;
    font-weight: normal;
    padding-left: 20px;
    padding-right: 15px;
    text-align: right;
    width: 73px;
}

</style>
                <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>项目管理</span><div class="clear"></div></h2>
                	 <div class="ryl_add_project" style="width:745px;"><b><?=arr::get($project_info, 'project_brand_name')?></b>
                	 <?php if($project_info['project_status'] !=0){?>
                	 <a href="<?php echo URL::website("/company/member/project/addproject")?>">添加新项目</a><?php }?></div>
                	<div class="my_business_infor my_business_post0513">

                        <div class="nav">
                            <ul>
                                <li><span><a href="/company/member/project/projectinfo?project_id=<?=arr::get($forms, 'project_id')?>">项目基本信息</a></span></li>
                                <li><span><a href="/company/member/project/addproimg?project_id=<?=arr::get($forms, 'project_id')?>">项目图片</a></span></li>
                                <li><span><a href="/company/member/project/addprocertsimg?project_id=<?=arr::get($forms, 'project_id')?>">项目资质图片</a></span></li>
                                <li class="liCur"><a href="#">项目海报</a></li>
                                <li class="liLast"><span><a href="/company/member/project/viewProInvestment?project_id=<?=arr::get($forms, 'project_id')?>">我的投资考察会</a></span></li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                        <div class="ryl_haibao_check0513">
                         <?php if($status>0){?>
                         <p class="ryl_upload_view">
                         <a class="preview_officialweb" href="<?php echo urlbuilder::project(arr::get($forms, 'project_id'));?>" target="_blank">预览项目官网</a>
                         </p>
                            <div class="clear"></div>
                            <?php }?>
                             <?php if(isset($poster)&&$poster->poster_status==1){?>
                             <p class="ryl_upload_post ryl_upload_post_shehe">
                              <label>您的海报已经上传，正在审核中</label>
                              <span>两个工作日内告知您审核结果，请耐心等待。</span>
                            <div class="clear"></div>
                            </p>
                             <div class="ryl_upload_link_new ryl_upload_audit_fali">
                              <?php if(strtolower(substr($posterimg,strpos($posterimg,'.')+1)) != "psd"){?> <a href="/company/member/ajaxcheck/getPosterImg?project_id=<?=arr::get($forms, 'project_id')?>" target="_blank"></a><?php }?>
                              <span>
                                  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="223" height="52" id="flashrek2">
                                    <param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_hb2.swf?url='.URL::website('/company/member/ImageAjaxcheck/uploadPoster')."&fun=viewPoster&projectid=".arr::get($forms, 'project_id'))?>" />
                                    <param name="quality" value="high" />
                                    <param name="wmode" value="transparent" />
                                    <param name="allowScriptAccess" value="always" />
                                    <embed src="<?php echo URL::webstatic('/flash/uploadhead_hb2.swf?url='.URL::website('/company/member/ImageAjaxcheck/uploadPoster')."&fun=viewPoster&projectid=".arr::get($forms, 'project_id'))?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="223" height="52" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                                  </object>
                                 <div class="clear"></div>
                                 <em>一旦您重新上传海报，将不会为您保存您之前上传的海报图片</em>
                                 <div class="clear"></div>
                              </span>
                              <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                            <?php }elseif(isset($poster)&&$poster->poster_status==2){?>
                            <div class="ryl_upload_link_new">
                              <?php if(!isset($posterimg)){?><a href="<?php echo URL::imgurl("poster/html/ps_".$project_info['outside_id']."/poster.html");?>" target="_blank"></a><?php }elseif(strtolower(substr($posterimg,strpos($posterimg,'.')+1)) != "psd"){?> <a href="/company/member/ajaxcheck/getPosterImg?project_id=<?=arr::get($forms, 'project_id')?>" target="_blank"></a><?php }?>
                              <span>
                                  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="223" height="52" id="flashrek2">
                                    <param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_hb2.swf?url='.URL::website('/company/member/ImageAjaxcheck/uploadPoster')."&fun=viewPoster&projectid=".arr::get($forms, 'project_id'))?>" />
                                    <param name="quality" value="high" />
                                    <param name="wmode" value="transparent" />
                                    <param name="allowScriptAccess" value="always" />
                                    <embed src="<?php echo URL::webstatic('/flash/uploadhead_hb2.swf?url='.URL::website('/company/member/ImageAjaxcheck/uploadPoster')."&fun=viewPoster&projectid=".arr::get($forms, 'project_id'))?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="223" height="52" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                                  </object>
                                 <div class="clear"></div>
                                 <em>一旦您重新上传海报，将不会为您保存您之前上传的海报图片</em>
                                 <div class="clear"></div>
                              </span>
                            </div>
                            <?php }elseif(isset($poster)&&$poster->poster_status==3){?>
                            <p class="ryl_upload_post">
                              <label>您的海报没有通过审核</label>
                              <span>原因是：<?=$poster->poster_unpass_reason?></span>
                            <div class="clear"></div>
                            </p>
                             <div class="ryl_upload_link_new ryl_upload_audit_fali">
                              <?php if(strtolower(substr($posterimg,strpos($posterimg,'.')+1)) != "psd"){?> <a href="/company/member/ajaxcheck/getPosterImg?project_id=<?=arr::get($forms, 'project_id')?>" target="_blank"></a><?php }?>
                              <span>
                                   <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="223" height="52" id="flashrek2">
                                    <param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_hb2.swf?url='.URL::website('/company/member/ImageAjaxcheck/uploadPoster')."&fun=viewPoster&projectid=".arr::get($forms, 'project_id'))?>" />
                                    <param name="quality" value="high" />
                                    <param name="wmode" value="transparent" />
                                    <param name="allowScriptAccess" value="always" />
                                    <embed src="<?php echo URL::webstatic('/flash/uploadhead_hb2.swf?url='.URL::website('/company/member/ImageAjaxcheck/uploadPoster')."&fun=viewPoster&projectid=".arr::get($forms, 'project_id'))?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="223" height="52" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                                  </object>
                                 <div class="clear"></div>
                                 <em>一旦您重新上传海报，将不会为您保存您之前上传的海报图片</em>
                                 <div class="clear"></div>
                              </span>
                              <div class="clear"></div>
                            </div>
                            <?php }?>
                            <?php if(!isset($poster)){?>
                            <p class="ryl_upload_post_zj">
                              <object  style="float:left;padding-right:10px" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="268" height="52" id="flashrek2">
                              <param name="movie" value="<?php echo URL::webstatic('/flash/uploadhead_hb.swf?url='.URL::website('/company/member/ImageAjaxcheck/uploadPoster')."&fun=viewPoster&projectid=".arr::get($forms, 'project_id'))?>" />
                              <param name="quality" value="high" />
                              <param name="wmode" value="transparent" />
                              <param name="allowScriptAccess" value="always" />
                              <embed src="<?php echo URL::webstatic('/flash/uploadhead_hb.swf?url='.URL::website('/company/member/ImageAjaxcheck/uploadPoster')."&fun=viewPoster&projectid=".arr::get($forms, 'project_id'))?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="268" height="52" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
                             </object>
                              <span>请上传一张项目海报，高度不低于500像素，宽度不低于900像素，否则将不会通过审核</span>
                            </p>
                            <?php }?>
                            <?php if(!isset($poster)&&$status==0){?>
                            <div class="ryl_upload_post_zj_link">
                              <a href="/company/member/project/addProInvestment?project_id=<?=arr::get($forms, 'project_id')?>">跳过此步骤>></a>
                              <a href="/company/member/project/submitProject?project_id=<?=arr::get($forms, 'project_id')?>">发布项目>></a>
                            </div>
                            <?php }elseif(isset($poster)&&$status==0){?>
                            <div class="nextStep2">
                        		<span>&nbsp;</span><span class="nextStep" style="float:left;">您的项目还不完整哦，去继续完善项目信息吧</span>&nbsp;

                           	<a href="/company/member/project/addProInvestment?project_id=<?=arr::get($forms, 'project_id')?>" style="height: 32px; width: 102px; display: inline-block; float: left; padding:0 5px;"><img src="<?php echo URL::webstatic("images/infor2/nexstep.gif") ?>" width="102" height="32"/></a>

                           <input style="float:left;margin-left:10px;" type="submit" value="" class="zs_publish_btn" onclick="window.location.href='/company/member/project/submitProject?project_id=<?=arr::get($forms, 'project_id')?>'"/>
                        </div>
                            <?php }?>
                          <div class="clear"></div>
                        </div>

                    </div>
                </div>
                <!--主体部分结束-->
<script type="text/javascript">
   function viewPoster(_str){
    if(_str){
		location.href="/company/member/project/addposter?project_id=<?=arr::get($forms, 'project_id')?>";
     }
  }
  </script>