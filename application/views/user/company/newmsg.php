<!--主体部分开始-->
<?php echo URL::webjs('my_messages.js')?>
<style>
    #my_messages .mess_nav ul li.current a span {color: #FF0000;}
    #my_messages .mess_nav ul li a span {color: #000;}
</style>
<div class="right"  style="height:auto !important;height:620px;min-height:620px;">
    <h2 class="user_right_title">
        <span>我的消息</span>
        <div class="clear"></div>
    </h2>
    <div class="my_messages" id="my_messages">
        <ul class="my_messages_list">
            <li class="my_messages_nav">
                <select onchange="change_list( this.value,'<?php echo $ust?>' )">
                    <option value="all" <?php if( $stype=='all' ){?>selected="selected"<?php }?> >全部消息</option>
                    <?php if( ceil($cardsnum)>0 ){?>
                    <option value="cards" <?php if( $stype=='cards' ){?>selected="selected"<?php }?>>名片消息</option>
                    <?php }?>
                    <?php if( ceil($projectnum)>0 ){?>
                    <option value="project" <?php if( $stype=='project' ){?>selected="selected"<?php }?>>项目消息</option>
                    <?php }?>
                    <?php if( ceil($auditnum)>0 ){?>
                    <option value="audit" <?php if( $stype=='audit' ){?>selected="selected"<?php }?>>审核消息</option>
                    <?php }?>
                    <?php if( ceil($servernum)>0 ){?>
                    <option value="server" <?php if( $stype=='server' ){?>selected="selected"<?php }?>>服务消息</option>
                    <?php }?>
                    <?php if( ceil($accountnum)>0 ){?>
                    <option value="account" <?php if( $stype=='account' ){?>selected="selected"<?php }?>>账户消息</option>
                    <?php }?>
                </select>
                <a id="delete_all" href="javascript:void(0)">删除全部</a>
            </li>

            <?php
            if( !empty( $msg_list ) ){
                $type_arr= message_type::types();

                foreach ( $msg_list as $k=>$vs ){
                    $msg_val= json_decode($vs);
                    //获取消息类型
                    $msg_type= $msg_val->message_type;
                    //获取类型对应的消息组
                    $group_name= $msggroupdy_info[$msg_type];
                    //计算时间
                    $save_min= ceil( time()-$msg_val->save_time );
                    if( $save_min<3600 ){
                        //1小时内
                        $show_time= round( $save_min/60 )."分钟前";
                    }elseif( $save_min>=3600 && $save_min<86400 ){
                        //1天内
                        $show_time= round($save_min/3600)."小时前";
                    }elseif( $save_min>=86400 && $save_min<172800 ){
                        //大于1天
                        $show_time= round($save_min/86400)."天前";
                    }else{
                        $show_time= date("Y-m-d",$msg_val->save_time);
                    }
                    foreach( $type_arr as $xt=>$vs_t ){
                        if( $vs_t['id']==$msg_type ){
                            $href_name= $vs_t['href'];
                            if( strstr( $xt,'false' )===false ){
                            }else{
                                $group_name= 'false';
                            }
                        }
                    }
                    if( $show_time=='0分钟前' ){
                        $show_time= '1分钟前';
                    }
                    $redis_key= message_key::buildKey($msg_val->user_id, $group_name);


            ?>
            <li class="<?php echo $msggroupcalss_info[$group_name]?> <?php if( $msg_val->read_time>0 ){ echo "mark_read";}?> <?php if( count( $msg_list )==$k+1 ){ echo "last"; }?> ">
                <i class="icon "></i>
                <span>
                    <?php echo base64_decode($msg_val->message_content)?>。
                </span>
                <a href="<?php echo $msg_val->to_url?>" target="_blank" onclick="update_msg_status( '<?php echo $redis_key?>','<?php echo $msg_val->id?>' )"><?php echo $href_name?><?php if( $href_name!='' ){?>>><?php }?></a> <font><?php echo $show_time; ?></font>
                <a class="delete" title="删除该项" href="/<?php echo $ust?>/member/msg/msglist?id=<?php echo $msg_val->id?>&action=del&stype=<?php echo $stype?>&msg_type=<?php echo $group_name?>"></a>
            </li>
            <?php }}?>



        </ul>
    </div>
    <!-- 翻页 -->
    <?php echo $msg_page?>
    <!-- 翻页 END -->
</div>
<!--主体部分结束-->
<div class="clear"></div>

<!-- 删除所有确认对话框 -->
<div class="delete_all_confirm_box" style="z-index: 9999;">
    <a href="javascript:void(0)" class="close"></a>
    <div id="msg_content" class="msg">您确定要删除全部消息吗？</div>
    <a id="project_home_msg_box_ok" class="btn_ok" href="javascript:void(0)" title="确定" onclick="delall_msg( '<?php echo $stype?>','<?php echo $ust?>' )">确定</a>
    <a id="project_home_msg_box_cancel" class="btn_cancel" href="javascript:void(0)" title="取消">取消</a>
</div>
<!-- 删除所有确认对话框 -->

<!--弹出框开始-->
<div id="opacity_box"></div>
<div id="mess_delete_box">
    <a href="#" class="close">关闭</a>
    <p>您确定删除此页面所有消息吗？</p>
    <p>
        <a href="#" class="ensure">
            <img src="http://static.myczzs.com/images/my_messages/ensure.png" />
        </a>
        <a href="#" class="cancel">
            <img src="http://static.myczzs.com/images/my_messages/cancel.png" />
        </a>
    </p>
</div>
<!--弹出框结束-->
<!--主体部分结束-->
<div class="clear"></div>
