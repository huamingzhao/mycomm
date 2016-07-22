<?php echo URL::webjs("per_history.js")?>
                <!--主体部分开始-->
                <div class="right"  style="height:auto !important;height:620px;min-height:620px;">
                    <h2 class="user_right_title"><span>我的历史搜索记录</span><div class="clear"></div></h2>
                       <div class="per_history">
                       <?php foreach( $list as $v ):?>
                        <div class="list">
                            <p><?php echo date("Y-m-j H:i",$v['update_time'] ) ;?>为您搜索到<span><?php echo $v['total_count']; ?></span>个：</p>
                            <p class="two"><?php echo $v['content']; ?>的项目</p>
                            <!-- <p><a target="_blank" href="<?php echo URL::website('platform/guide/projectlist?type=1&id=').$v['id']?>" class="view">查看最新结果</a></p> -->
                            <a class="delete" id="delete_<?php echo $v['id'];?>" href="javascipt:void(0)">删除</a></p>
                        </div>
                        <?php endforeach;?>
                    </div>
                </div>
                <!--主体部分结束-->

<!--透明层开始-->
<div id="opacity_box"></div>
<!--透明层结束-->
<!--删除搜索历史记录开始-->
<div id="cancel_box">
    <a href="#" class="close">关闭</a>
    <p>您确定要删除此搜索记录吗？</p>
    <a href="#" id="suredeletedata"><img src="<?php echo URL::webstatic("images/box/ensure.png") ?>" /></a>
    <a href="#" class="cancel"><img src="<?php echo URL::webstatic("images/box/cancel.png") ?>" /></a>
</div>
<!--删除搜索历史记录结束-->