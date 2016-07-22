<?php echo URL::webjs("my_message.js")?>
<!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>我的消息</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="my_message">
                <div id="message_nav">
                    <ul>
                        <li><a href="<?=URL::website("person/member/msg")?>">名片管理</a></li>
                        <li class="hover"><a href="<?=URL::website("person/member/msg/project")?>">项目信息</a><span><!-- 消息条数 --></span></li>
                        <li><a href="<?=URL::website("person/member/msg/auditstatus")?>">审核状态</a><span><!-- 消息条数 --></span></li>
                    </ul>
                </div>
                <div class="clear"></div>
                <div id="message_text">
                    <div class="one">
                        <?php if($msg_list->count()){?>
                        <p class="delete_all"><a href="<?php echo URL::website("person/member/msg/delall?group=prject")?>">删除此页面所有消息</a></p>
                        <?php }?>

                        <?php
                            if($msg_list->count()){
                            foreach($msg_list as $msg){
                        ?>
                        <p class="list">
                        <?php if($msg->message_state !=2){?><b><?php }?>
                            <?=$msg->message_content?>
                            <a href="<?php echo URL::website("person/member/msg/read?msg_id=".$msg->id)?>">去看看>></a>
                        <?php if($msg->message_state !=2){?></b><?php }?>
                        <span class="delete">
                              <a href="<?php echo URL::website("person/member/msg/del?msg_id=".$msg->id)?>">删除</a>
                        </span>
                        </p>
                        <?php }}
                            else{
                        ?>
                        <p class="no_message">您目前还没有消息！</p>
                        <?php }?>
                    </div>
                    <div class="two">
                    </div>

                    <div class="three">
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--右侧结束-->

    <!--批量删除名片开始-->
<div id="getcards_deletebox">
    <a href="#" class="close">关闭</a>
    <div class="text">
        <p>您确定删除此页面所有消息吗？</p>
        <p><a href="#" class="ensure"><img src="<?php echo URL::webstatic("images/getcards/ensure1.png")?>" /></a><a href="#" class="cancel"><img src="<?php echo URL::webstatic("images/getcards/cancel1.png")?>" /></a></p>
    </div>
</div>
<!--批量删除名片结束-->