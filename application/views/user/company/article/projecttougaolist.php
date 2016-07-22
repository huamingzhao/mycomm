
                    <!--右侧开始-->
                    <div id="right">
                        <div id="right_top">
                            <span>项目新闻</span>
                            <div class="clear"></div>
                        </div>
                        <div id="right_con">
                            <div class="project_news_tool">
                                <select onchange="change(this.value)">
                                <option value=''>全部项目新闻 </option>
                                <?php
                                if( !empty( $all_project ) ){
                                    foreach( $all_project as $vp ){
                                ?>
                                    <option value="<?php echo $vp->project_id?>" <?php if( $pid==$vp->project_id ){?>selected="selected"<?php }?>><?php echo $vp->project_brand_name?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                                <!-- <font>可免费发布30篇</font> -->
                                <a href="<?php echo URL::website('/company/member/article/projecttougao')?>">发布项目新闻</a>
                                <div class="clear"></div>
                            </div>
                            <ul class="project_news_form">
                            <?php
                            if( !empty( $list ) ){
                                foreach ( $list as $v ){
                                    switch ( $v['article_status'] ){
                                        //待审核
                                        case "1":
                                            $class= 'check_state';
                                            $status= "审核中";
                                        break;
                                        //审核通过
                                        case "2":
                                            $class= 'check_state_ok';
                                            $status= "通过审核";
                                        break;
                                        //审核没通过
                                        case "3":
                                            $class= 'check_state_no';
                                            $status= "未通过审核";
                                        break;
                                        default:
                                            $class= 'check_state';
                                            $status= "审核中";
                                        break;
                                    }
                            ?>
                                <li>
                                    <span class="article">
                                    <?php if( $v['article_status']=='2' ){?>
                                        <h4>
                                            <a href="<?php echo zxurlbuilder::porjectzixuninfo( $v['project_id'],$v['article_id'] )?>" target="_blank"><?php echo $v['article_name']?></a>
                                            <font class="<?php echo $class?>"><?php echo $status?></font>
                                        </h4>
                                    <?php }else{?>
                                        <h4>
                                            <a href="<?php echo URL::website('/member/showzixun?id='.$v['article_id'])?>" target="_blank"><?php echo $v['article_name']?></a>
                                            <font class="<?php echo $class?>"><?php echo $status?></font>
                                        </h4>
                                    <?php }?>
                                        <font><?php echo date("Y年m月d日 H:i",$v['article_intime'])?>发布</font>
                                    <?php if( $v['article_status']=='2' ){?>
                                        <font>浏览数:<?php echo $v['pv_count']?></font>
                                    <?php }?>
                                    </span>
                                    <i>关联项目：<?php echo $v['project_name']?> </i>
                                    <?php if( $v['article_status']=='1' ){?>
                                    <span class="operate" style="margin-top:13px;">
                                        <a class="edit" href="<?php echo URL::website('/company/member/article/projecttougao?id='.$v['article_id'])?>">编辑</a>
                                    </span>
                                    <?php }else if( $v['article_status']=='2' ){?>
                                        <span class="operate" style="margin-top:13px;">
                                            <a class="view_detial" href="<?php echo zxurlbuilder::porjectzixuninfo( $v['project_id'],$v['article_id'] )?>" target="_blank" data-reason="#">查看详情</a>
                                        </span>
                                    <?php }?>
                                    <?php if( $v['article_status']=='3' ){?>
                                        <span class="operate">
                                            <a class="view" href="javascript:void(0)" data-reason="<?php echo $v['article_comment']?>">查看原因</a>
                                            <a class="edit" href="<?php echo URL::website('/company/member/article/projecttougao?id='.$v['article_id'])?>">编辑</a>
                                        </span>
                                    <?php }?>
                                    <div class="clear"></div>
                                </li>
                        <?php }}?>

                            </ul>
                            <div class="page-effect">
                                <?php echo $page?>

                            </div>
                        </div>
                    </div>
                    <div id="getcards_opacity"></div>
                    <div id="getcards_deletebox">
                        <a href="#" class="close">关闭</a>
                        <div class="text" style="background:none;padding-left:0px;text-align: left;color: #000;">
                            <p>
                                <p id="reason_content" style="width:440px;padding-top:2px;">
                                    查看审核未通过原因
                                </p>
                            </p>
                            <p id="this_content2" style="width:270px; margin:0 auto;">
                                <a href="javascript:void(0)" class="ensure">
                                    <img src="<?php echo URL::webstatic('images/getcards/ensure1.jpg')?>"></a>
                            </p>
                            <input id="getcards_deletebox_hid" type="hidden" value="0"></div>
                    </div>
                    <!--右侧结束-->
<script>
function change( val ){
    window.location.href= "/company/member/article/projecttougaolist?project_id="+val;
}

//初始化查看原因弹框
$("#getcards_deletebox .close").click(function(event) {
    /* Act on the event */
    $(this).parent().slideUp(500, function(){
        $("#getcards_opacity").hide();
    });
});
$("#getcards_deletebox .ensure").click(function(event) {
    /* Act on the event */
    $("#getcards_deletebox").slideUp(500, function(){
        $("#getcards_opacity").hide();
    });
});
$(".operate a.view").click(function(event) {
    /* Act on the event */
    $("body")[0].show({
        title:"审核备注",
        content:"<p>"+$(this).attr("data-reason")+"</p>",
        btn:"ok"
    });
});
</script>
