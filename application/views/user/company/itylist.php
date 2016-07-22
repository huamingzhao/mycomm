    <?php echo URL::webjs("my_points.js")?>
    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>我的诚信</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="my_points">
                <div class="title"><span>会员诚信指数：<b><?=$total_itys?>点</b></span>
                    <span class="dengj">会员等级：
                    <em>
                    <a href="<?php echo urlbuilder::help("qzhishuji")."#level";?>" target="_blank"><img src="<?php echo URL::webstatic("images/integrity/{$ity_level['level']}.png")?>"></a>
                    </em>
                    <div class="hover">
                        <p>等级：<?=$ity_level['level']?>级</p>
                        <?php if($ity_level['next_short']){?>
                        <p>还需要<?=$ity_level['next_short']?>个点达到<?=$ity_level['level']+1?>级</p>
                        <?php }else{?>
                        <p>您已经是最最高等级了</p>
                        <?php }?>
                    </div>
                    </span>
                    <span><a href="<?php echo urlbuilder::help("qzhishuji");?>" target="_blank">查看诚信指数规则>></a></span>
                </div>
                <form action="" method="get">
                <div class="select" id="select_all">时间：
                    <?=Form::select("from_year",$year_list,Arr::get($search_status,'from_year'))?>
                    <?=Form::select("from_month",$month_list,Arr::get($search_status,'from_month'))?> 至
                    <?=Form::select("to_year",$year_list,Arr::get($search_status,'to_year'))?>
                    <?=Form::select("to_month",$month_list,Arr::get($search_status,'to_month'))?>
                    <button id="cx_button">查询</button>
                    <span class="tipinfor_tim" style="color:#EB0000;padding-left:10px;"></span>
                </div>
                </form>
                <table>
                    <tr>
                        <th>时间</th>
                        <th>诚信指数类型</th>
                        <th>获取诚信指数</th>
                        <th>总诚信指数</th>
                    </tr>
                    <?php if($result['total_count']){
                        foreach($result['list'] as $l){
                    ?>
                    <tr>
                        <td><span><?=date("Y-m-d H:i",$l['add_time'])?></span></td>
                        <td><?=$l['desc']?></td>
                        <td>
                            <?php if($l['is_plus']){?>
                            <em class="em2">+<?=$l['value']?></em>
                            <?php }else{?>
                            <em class="em1">-<?=$l['value']?></em>
                            <?php }?>
                        </td>
                        <td><?=$l['total_count']?></td>
                    </tr>
                    <?php }
                    }
                    ?>
                </table>
                <?=$result['page']?>
            </div>
        </div>
    </div>
    <!--右侧结束-->