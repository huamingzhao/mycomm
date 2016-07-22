<?php echo URL::webcss("zhaoshang_zg.css")?>
<?php echo URL::webjs("invitepro.js")?>
    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>招商项目管理</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="zhaos"  style="padding:10px 0 0 0px;">
                <div class="tips tipsSmall"></div>
                <div class="nav">
                    <ul>
                        <li ><span><a href="/company/member/project/updateproject?project_id=<?=arr::get($forms, 'project_id')?>" >项目基本信息</a></span></li>
                        <li><span><a href="/company/member/project/addproimg?project_id=<?=arr::get($forms, 'project_id')?>">项目图片</a></span></li>
                        <li><span><a href="/company/member/project/addprocertsimg?project_id=<?=arr::get($forms, 'project_id')?>">项目资质图片</a></span></li>
                        <li class="liCur liLast"><span><a href="/company/member/project/viewProInvestment?project_id=<?=arr::get($forms, 'project_id')?>">我的投资考察会</a></span></li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div class="zhaos_center">
                    <div class="investment">
                        <div class="investapply" id="investapply">
                            <span>我想召开投资考察会，让更多的投资者了解我的项<a href="/company/member/project/addproinvestment?project_id=<?=arr::get($forms, 'project_id')?>"><img src="<?php echo URL::webstatic("images/infor2/btn4.gif") ?>" width="112" height="32"  border="0"/></a><a href="/company/member/project/addpropublish?project_id=<?=arr::get($forms, 'project_id')?>">跳过此步骤>></a></span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

