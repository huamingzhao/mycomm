<?php echo URL::webjs("my_business.js")?>
 <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span><a href="<?php echo url::site('/company/member/project/myinvestment');?>">我的投资考察会</a> &gt; <?=$invest_model->investment_name?></span><div class="clear"></div></h2>
                    <div class="my_application">
                        <div class="excel"><?php if($bobao->bobao_status==2){?>在线报名<span><?=$applyNum?></span>人，播报人数为<span><?=$bobao->bobao_num?></span>人<?php }else{?>已有<span><?=$applyNum?></span>人报名：<?php }?></div>
                        <table>
                            <tr class="th">
                                <th>姓名</th>
                                <th>性别</th>
                                <th>报名时间</th>
                                <th>联系方式</th>
                                <th>是否需要公司统一安排住宿</th>
                            </tr>
                        <?php foreach ($invest as $v){?>
                            <tr>
                                <td><?=$v->apply_name?></td>
                                <td><?php if($v->apply_sex==1){echo '男';}else{echo '女';}?></td>
                                <td><?php echo date('Y/m/d',$v->apply_addtime);?></td>
                                <td><span><?php echo substr($v->apply_mobile,0,4)."****".substr($v->apply_mobile,-3);?></span><input type="image" src="<?php echo URL::webstatic('images/my_application/view_btn.png')?>" id="<?=$v->apply_id?>" status = "<?=$v->apply_status?>"
                                                                                                                                     forbid= "<?=$account_test['is_forbid']?>"/></td>
                                <td><?php if($v->is_hotel==1){echo '是';}else{echo '否';}?></td>
                            </tr>
                        <?php }?>
                        </table>
                    </div>
                   <?=$page?>
                </div>
                <!--主体部分结束-->
                <!--透明层开始-->
<div id="opacity_box"></div>
<!--透明层结束-->
<!--递出名片层开始-->
<div id="send_box">
    <a href="#" class="close">关闭</a>
    <p class="view_sp"></p>
</div>
<!--递出名片层结束-->