<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>
<?php echo URL::webcss("renzheng.css")?>

<!--右侧开始-->
<div class="opacityBg" id="opacityBg"></div>
<div id="right">
        <div id="right_top"><span>招商项目管理</span><div class="clear"></div></div>
    <div class="ryl_add_project" style="padding-left:0px; width:745px;"><b><?=arr::get($project_info, 'project_brand_name')?></b></div>
    <div id="right_con">
        <div id="zhaos"   style="padding:10px 0 0 0px;">
            <!--<div class="tips tipsSmall"></div>-->
            <div class="nav" style="padding-top:0;">
                <ul>
                    <li><span><a
                            href="/company/member/project/updateproject?project_id=<?=arr::get($forms, 'project_id')?>">项目基本信息</a></span></li>
                    <li><span><a
                            href="/company/member/project/addproimg?project_id=<?=arr::get($forms, 'project_id')?>">项目图片</a></span></li>
                    <li><span><a
                            href="/company/member/project/addprocertsimg?project_id=<?=arr::get($forms, 'project_id')?>">项目资质图片</a></span></li>
                    <li class="liCur"><span><a
                            href="/company/member/project/addproplaybill?project_id=<?=arr::get($forms, 'project_id')?>">项目海报</a></span></li>
                    <li><span><a
                            href="/company/member/project/viewProInvestment?project_id=<?=arr::get($forms, 'project_id')?>">我的投资考察会</a></span></li>
                    <li class="liLast"><span><a
                            href="/company/member/project/addpropublish?project_id=<?=arr::get($forms, 'project_id')?>">发布我的项目</a></span></li>
                </ul>
                <div class="clear"></div>
            </div>

            <!-- 项目信息不完善 -->
                <?php if(!$is_project){?>
                    <div class="haibaoDiv">抱歉，您需要先填写您的项目基本信息才能发布其他内容<a href="#"><img
                    src="<?php echo URL::webstatic("images/infor2/btn5.gif") ?>"
                    width="162" height="33" /></a>
            </div>
                <?php }?>
                <!-- 项目信息不完善 -->

            <!-- 模板选择 -->
            <form action="" method="post">
                <div class="haibao2">
                    <!--<div class="ryl_upmodel_title"><a href="#" class="ryl_upmodel_btn"></a><span>只能上传一张，且高度不低于500像素，否则将不会通过审核</span><a href="#" target="_blank" class="preview_officialweb">预览项目官网</a></div>-->
                    <h4 style="margin-right:15px;">我们根据您填写的项目信息和上传的项目图片为您生成了海报，您可以在以下一款模板中进行选择：<a href="#" class="preview_officialweb" target="_blank">预览项目官网</a></h4>
                    <ul>
                        <li><label for="moban1"><img
                                src="<?php echo URL::webstatic("images/infor2/moban1.jpg") ?>"
                                width="332" height="203" /></label>
                            <p>
                                <input type="radio" value="1" name="template" id="moban1"
                                    checked="checked" /><label for="moban1">海报模板1</label>
                            </p></li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <p class="submit">
                    <span>&nbsp;</span><span class="nextStep">您的项目还不完整哦，去<input
                        type="submit" class="zhaoshangPublish" value="">继续完善项目信息吧
                    </span>
                </p>
            </form>
            <!-- 模板选择 -->

        </div>
    </div>
</div>

