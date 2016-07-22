<?php echo URL::webcss("cz.css")?>
<div class="right">
<h2 class="user_right_title">
        <span>参展项目管理</span>
        <div class="clear"></div>
    </h2>
    <div class="my_business_new">
    <div class="project_detial project_release">
        <div class="yidaowrap">
            <p class="norelease"><?=$errMsg?></p>
            <p class="titlefuzou">步骤</p>
            <ul class="yslist clearfix">
                <li>选择招商项目</li>
                <li>网络展会即将开展</li>
                <li>我要参展</li>
                <li style="background: none;">发布展会项目</li>
            </ul>
            <p class="linkp">1、如果你还没有还没有发布一句话平台招商项目，则需要先去<a href="<?php echo URL::website('/company/member/project/addProject');?>">发布项目>></a></p>
            <p class="linkp">2、如果你的项目还没有加入展会，需要选择一场（即将开展的）展会加入，去<a href="<?php echo URL::website('/platform/exhibition/index');?>">参加展会>></a></p>
        </div>
    </div>
</div>
</div>
<!--透明背景开始-->
<div id="zhaos_opacity"></div>
<!--透明背景结束-->
