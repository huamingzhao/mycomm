<?php echo URL::webjs("search_history.js")?>
<!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>搜索投资者历史记录</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="search_history">
                <div class="title">您有<span><?php echo $totalcount;?></span>条历史搜索记录：<a style=" color:#0036ff; font-size:12px; font-weight:normal;" href="<?php echo URL::website('/company/member/investor/searchSubscription');?>">订阅搜索结果</a>
                    <?php if ($totalcount) {?>
                        <a href="#" class="delete">删除此页面所有搜索记录</a>
                    <?php }?>
                </div>
                <?php foreach( $list as $v ){?>
                    <div class="list">
                        <p><?php echo date("Y-m-j H:i",$v['update_time'] );?>为您搜索到<span><?php echo $v['total_count'];?></span>位：</p>
                        <p><em><?php  echo  $v['group']?></em></p>
                        <p><b>目前已新增<span><?php echo $v['nowtotalcount'];?>位</span>投资者</b><a href="<?php echo  $v['url'];?>">查看最新结果</a></p>
                        <p id="deletedata_<?php echo  $v['id']?>" class="delete_one">
                        <a href="<?php echo URL::website('/company/member/investor/deleteConditionsByArr').'?idarr='.$v['id'];?>">删除</a></p>
                    </div>
                <?php }?>
             <?php echo $page;?>
            </div>
        </div>
    </div>
<!--右侧结束-->
    <div class="clear"></div>
</div>
<div id="opacity"></div>
<div id="getcards_delete">
    <a href="#" class="close">关闭</a>
    <div class="btn">
        <p>您确定删除此页面所有搜索记录吗？</p>
        <p><a href="#" class="ensure"><img src="<?php echo URL::webstatic("images/getcards/ensure1.jpg") ?>" /></a>　<a href="#" class="cancel"><img src="<?php echo URL::webstatic("images/getcards/cancel1.jpg") ?>" /></a></p>
    </div>
</div>
