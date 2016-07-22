<?php echo URL::webjs("my_messages.js")?>
<style>
#my_messages .mess_nav ul li.current a span {color: #FF0000;}
#my_messages .mess_nav ul li a span {color: #000;}
</style>
                <!--主体部分开始-->
                <div class="right" style="height:auto !important;height:620px;min-height:620px;">
                    <h2 class="user_right_title"><span>我的消息</span><div class="clear"></div></h2>
                    <div class="my_messages" id="my_messages">
                        <div class="mess_nav">
                            <ul>
                                <li class="current" style="width:364px;"><a style="height:50px; display:block" href="<?=URL::website("person/member/msg")?>">名片消息<span id="cards_top_tips_count"></span></a></li>
                                <li style="width:364px;"><a style="height:50px; display:block" href="<?=URL::website("person/member/msg/auditstatus")?>">审核消息<span id="audit_top_tips_count"></span></a></li>
                            </ul>
                        </div>

                        <?php if(!$msg_list->count()){?>
                        <div class="no_mess">
                            <p>您目前还没有消息！</p>
                        </div>
                        <?php }else{?>
                        <div class="delete_all"><a href="<?php echo URL::website("person/member/msg/delall?group=cards")?>">清空消息</a></div>
                        <div class="mess_num">
                            <?php
                            foreach($msg_list as $msg){
                            ?>
                            <p>
                            <?php if($msg->message_state !=2){?><b><?php }?>
                                <?=$msg->message_content?>
                                <a href="<?php echo URL::website("person/member/msg/read?msg_id=".$msg->id)?>">去看看>></a>
                            <?php if($msg->message_state !=2){?></b><?php }?>
                                <a class="delete_one" href="<?php echo URL::website("person/member/msg/del?msg_id=".$msg->id)?>"></a>
                            </p>
                            <?php
                                }
                            ?>
                        </div>
                       <?php }?>
                    </div>
                </div>
                <!--主体部分结束-->
                <div class="clear"></div>

<!--弹出框开始-->
<div id="opacity_box"></div>
<div id="mess_delete_box">
    <a href="#" class="close">关闭</a>
    <p>您确定清空消息吗？</p>
    <p><a href="#" class="ensure"><img src="<?php echo URL::webstatic("images/my_messages/ensure.png")?>" /></a><a href="#" class="cancel"><img src="<?php echo URL::webstatic("images/my_messages/cancel.png")?>" /></a></p>
</div>
<!--弹出框结束-->