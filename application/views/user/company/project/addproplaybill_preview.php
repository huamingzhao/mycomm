<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>
<?php echo URL::webcss("renzheng.css")?>

<!--右侧开始-->
<div class="opacityBg" id="opacityBg"></div>
<div id="right">
        <div id="right_top"><span>招商项目管理</span><div class="clear"></div></div>
    <div class="ryl_add_project" style="padding-left:0px; width:745px;"><b>黄金酒项目</b><a href="#">添加新项目</a></div>
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
                    <!--<div class="ryl_upmodel_title"><a href="#" class="ryl_upmodel_btn"></a><span>只能上传一张，且高度不低于500像素，否则将不会通过审核</span><a href="#" class="preview_officialweb" target="_blank">预览项目官网</a></div>-->
                    <!--<h4 style="margin-right:15px;">我们根据您填写的项目信息和上传的项目图片为您生成了海报，您可以在以下一款模板中进行选择：<a href="#"  target="_blank" class="preview_officialweb">预览项目官网</a></h4>-->
                    <div class="ryl_upmodel_title" style="border-bottom: 1px solid #E3E3E3;"><span class="ryl_choosemodel_color01">您也可以</span><a href="#" class="ryl_choosemodel_btn"></a><span class="ryl_choosemodel_color01">我们为您准备的海报</span><a href="#" target="_blank" class="preview_officialweb">预览项目官网</a></div>
                    <p class="ryl_choosemodel_del"><a href="#">删除</a></p>
                    <div class="ryl_model_preview01"><img src="<?php echo URL::webstatic("images/infor2/imgmoban1.jpg") ?>" /></div>
                    <div class="clear"></div>
                </div>

            </form>
            <!-- 模板选择 -->

        </div>
    </div>
</div>

