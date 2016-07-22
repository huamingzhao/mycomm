    <?php echo URL::webjs("my_points.js")?>
    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>我的积分</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="my_points">
                <div class="title"><span>会员积分：<b><?=$useable_points?>分</b></span>
                    <span class="dengj">会员等级：
                    <em>
                    <!-- 直接为皇冠 -->
                    <?php if($user_level['level'] == 17){?>
                    <img src="/images/integral_rules/5.png" />
                    <?php
                        }
                        elseif($user_level['rule']['name'] == 'heart'){
                           for($grade = 0;$grade<$user_level['rule']['grade'];$grade++){
                    ?>
                        <img src="/images/integral_rules/1.png" />
                    <?php }
                    }
                        elseif($user_level['rule']['name'] == 'star'){
                            for($grade = 0;$grade<$user_level['rule']['grade'];$grade++){
                    ?>
                        <img src="/images/integral_rules/2.png" />
                    <?php }
                    }
                        elseif($user_level['rule']['name'] == 'diamond'){
                            for($grade = 0;$grade<$user_level['rule']['grade'];$grade++){
                    ?>
                        <img src="/images/integral_rules/3.png" />
                    <?php }
                    }
                    elseif($user_level['rule']['name'] == 'hat'){
                        for($grade = 0;$grade<$user_level['rule']['grade'];$grade++){
                    ?>
                        <img src="/images/integral_rules/4.png" />
                    <?php }
                    }
                    ?>
                    </em>
                    <div class="hover">
                        <p>等级：<?=$user_level['level']?>级</p>
                        <?php if($user_level['next_level']){?>
                        <p>还需要<?=$user_level['next_level']['min']-$user_level['total_points']?>个积分达到<?=$user_level['level']+1?>级</p>
                        <?php }else{?>
                        <p>您已经是最最高等级了</p>
                        <?php }?>
                    </div>
                    </span>
                    <span><a href="<?php echo URL::website("company/member/points/rule")?>">查看积分规则>></a>
                    </span><span><a href="#">兑换礼品>></a></span>
                </div>
                <form action="" method="get">
                <div class="select">
                    <?=Form::select("point_type",$points_type_list,Arr::get($search_status,'point_type'),array("class"=>"type"))?>时间：
                    <?=Form::select("from_year",$year_list,Arr::get($search_status,'from_year'))?>
                    <?=Form::select("from_month",$month_list,Arr::get($search_status,'from_month'))?> 至
                    <?=Form::select("to_year",$year_list,Arr::get($search_status,'to_year'))?>
                    <?=Form::select("to_month",$month_list,Arr::get($search_status,'to_month'))?>
                    <button>查询</button>
                </div>
                </form>
                <table>
                    <tr>
                        <th>时间</th>
                        <th>积分类型</th>
                        <th>获取积分</th>
                        <th>总积分值</th>
                    </tr>
                    <?php if($result['total_count']){
                        foreach($result['list'] as $l){
                    ?>
                    <tr>
                        <td><span><?=date("Y-m-d H:i",$l['add_time'])?></span></td>
                        <td><a><?=$l['points_desc']?></a></td>
                        <td>
                            <?php if($l['is_plus']){?>
                            <em class="em2">+<?=$l['points']?></em>
                            <?php }else{?>
                            <em class="em1">-<?=$l['points']?></em>
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