<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("invitepro.js")?>

    <!--右侧开始-->
    <div class="opacityBg" id="opacityBg"></div>
    <div id="right">
        <div id="right_top"><span>招商项目管理</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="zhaos"  style="padding:10px 0 0 0px;">
                <div class="tips tipsSmall"></div>
                <div class="nav">
                    <ul>
                        <li><span><a href="/company/member/project/updateproject?project_id=<?=arr::get($forms, 'project_id')?>" >项目基本信息</a></span></li>
                        <li><span><a href="/company/member/project/addproimg?project_id=<?=arr::get($forms, 'project_id')?>">项目图片</a></span></li>
                        <li><span><a href="/company/member/project/addprocertsimg?project_id=<?=arr::get($forms, 'project_id')?>">项目资质图片</a></span></li>
                        <li><span><a href="/company/member/project/addproplaybill?project_id=<?=arr::get($forms, 'project_id')?>">项目海报</a></span></li>
                        <li><span><a href="/company/member/project/viewProInvestment?project_id=<?=arr::get($forms, 'project_id')?>">我的投资考察会</a></span></li>
                        <li  class="liLast liCur" ><span><a href="/company/member/project/addpropublish?project_id=<?=arr::get($forms, 'project_id')?>">发布我的项目</a></span></li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div class="zhaos_center">
                    <form action="/company/member/project/addpropublish?project_id=<?=arr::get($forms, 'project_id')?>" method="post">
                    <div class="publish">
                        <div class="publishTop">
                            <div class="word"  style="width:400px;">
                                <p>恭喜您！您的项目即将发布成功，但先要选择下方的项目官网皮肤哦！</p>
                                <em>请注意：项目皮肤和您之前选择的海报模板是相对应的。</em>
                            </div>
                            <input type="submit" class="publishSubmit" value=""/>
                        </div>
                        <div class="moban">
                        <input type="hidden" name="project_id" value="<?=arr::get($forms, 'project_id')?>" />
                            <ul>
                                <li>
                                    <label for="skin1"><img src="<?php echo URL::webstatic("images/infor2/img_0.jpg") ?>"></label>
                                    <p><input type="radio" name="project_template" value="1" id="skin1" <?php if($forms['project_template'] == 1 || $forms['project_template'] == ""){?> checked = "checked" <?php }?> /><label for="skin1">皮肤1</label></p>
                                </li>
                                <li>
                                    <label for="skin2"><img src="<?php echo URL::webstatic("images/infor2/img_1.jpg") ?>"></label>
                                    <p><input type="radio" name="project_template" value="2" <?php if($forms['project_template'] == 2){?> checked = "checked" <?php }?>  id="skin2" /><label for="skin2">皮肤2</label></p>
                                </li>
                                <li class="noMargin">
                                    <label for="skin3"><img src="<?php echo URL::webstatic("images/infor2/img_2.jpg") ?>"></label>
                                    <p><input type="radio" name="project_template" value="3" <?php if($forms['project_template'] == 3){?> checked = "checked" <?php }?>  id="skin3" /><label for="skin3">皮肤3</label></p>
                                </li>

                            </ul>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>