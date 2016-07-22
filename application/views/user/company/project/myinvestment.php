<?php echo URL::webjs("my_business.js")?>
<?php echo URL::webcss("my_bussines.css")?>
<?php echo URL::webcss("postinvestment.css")?>

                 <!--主体部分开始-->
<div class="right">
    <h2 class="user_right_title clearfix">
        <span>我的投资考察会</span>
        <a href="/company/member/project/addinvest" class="postinvestment">发布投资考察会</a>
    </h2>
    <div class="investmentulbox">
        <ul class="ryl_myproject_list ryl_myproject_zshlist qzhizshlist">

            <?php foreach ($invest as $v){?>
            <li class="mt25">
                <h3>会议标题：<?=$v['investment_name']?>
                    <span>
                    <?php if($v['investment_status']==0){?>
                        【<var class="pending">待审核</var>】
                    <?php }elseif($v['investment_status']==3){?>
                        【<var class="auditfailure">发布失败</var>】
                    <?php }elseif($v['investment_status']==1){?>
                        <?php if($v['investment_start']-$now>=0){
                            if($v['wait_edit_check']=='0'){?>
                                【<var class="modifypending">修改待审核</var>】
                            <?php }elseif($v['wait_edit_check']=='2'){?>
                                【<var class="noaudit">修改审核未通过</var><a href="javascript:void(0)" id="<?=$v['investment_id']?>" class="reason">查看原因</a>】
                            <?php }else{?>
                                【<var>正常</var>】
                            <?php }}else{?>
                            <?php if($v['bobao_status']==0){?>
                                【<var class="endbroadcast">已结束（未播报）</var>】
                            <?php }elseif($v['bobao_status']==1){?>
                                【<var class="pending"> 已结束（播报待审核）</var>】
                            <?php }elseif($v['bobao_status']==2){?>
                                【<var class="ybroadcast"> 已结束（已播报）</var>】
                            <?php }elseif($v['bobao_status']==3){?>
                                【<var class="auditfailure"> 已结束（播报审核失败） <a href="javascript:void(0)" id="<?=$v['investment_id']?>" class="reason">查看原因</a> </var>】
                            <?php }}} elseif($v['investment_status']==2){?>
                        【<var class="auditfailure">审核失败</var><a href="javascript:void(0)" id="<?=$v['investment_id']?>" class="reason">查看原因</a>】
                    <?php }?>
                    </span>
                    <?php if($v['investment_status']==1 && $v['investment_start']<=$now && $v['wait_edit_check']==''){ ?>
                    <a href="/company/member/project/addbobao?invest_id=<?=$v['investment_id']?>&page_num=<?=$page_num?>" class="floright modify release">
                        <?php if($v['bobao_status']==0){ ?>
                                发布成果播报
                        <?php   }elseif($v['bobao_status']==1 ||  $v['bobao_status']==2){ ?>
                                查看成果播报
                        <?php   }elseif($v['bobao_status']==3){ ?>
                                重新提交播报
                        <?php }?>
                    </a>
                    <?php }else{?>
                    <a href="<?php echo url::site('/company/member/project/addproinvestment?project_id='.$v['project_id'].'&investment_id='.$v['investment_id']);?>" class="floright modify">
                        <?php if($v['investment_status']==2 || $v['wait_edit_check']=='2'){?>
                            重新提交信息
                        <?php }else{?>
                            修改
                        <?php }?>
                    </a>
                    <?php }?>
                </h3>
                <div class="clearfix mt20">
                    <div class="floleft">
                        <p class="ryl_myproject_logo">
                        <a href="<?php echo urlbuilder::projectInvest($v['investment_id']); ?>" target="_blank"><img src="<?php echo URL::imgurl($v['investment_logo']);?>" /></a>
                        </p>
                        <div class="clear"></div>
                        <p>
                            <a href="<?php echo urlbuilder::projectInvest($v['investment_id']); ?>" class="modifybtn" target="_blank">查看投资考察会详情</a>
                        </p>
                    </div>
                    <div class="floleft ml25 modifybtninfo">
                        <p class="mt0">召开时间：<?php if($v['investment_start']==$v['investment_end']){echo date('Y/m/d',$v['investment_start']);}else{echo date('Y/m/d',$v['investment_start']).'—'.date('Y/m/d',$v['investment_end']);}?></p>
                        <p>召开地址：<?=$v['investment_address']?></p>
                        <p class="tozipline">所属项目：<?=$v['project_name']?></p>
                        <p class="mt15">总访问量：<a href="<?php echo URL::website('/company/member/project/myInvestDetails').'?invest_id='.$v['investment_id'];?>" class="totalnum"><?=$v['have_watch_investment_num']?></a> <a href="<?php echo URL::website('/company/member/project/myInvestDetails').'?invest_id='.$v['investment_id'];?>" class="ml25">谁看过会议详情</a></p>
                        <p>当前报名总人数：<a href="<?php echo URL::website('/company/member/project/myApplyInvest').'?invest_id='.$v['investment_id'];?>" class="totalnum"><?=$v['investment_apply']?></a> <a href="<?php echo URL::website('/company/member/project/myApplyInvest').'?invest_id='.$v['investment_id'];?>" class="ml25">查看详情</a></p>
                        <?php if($v['bobao_status']>0){?>
                        <p>播报参会人数：<var class="totalnum"><?=$v['bobao_num']?></var> ；播报签约人数：<var class="totalnum"><?=$v['bobao_sign']?></var> ；签约率：<var class="totalnum"><?php echo round(($v['bobao_sign']/$v['bobao_num'])*100)?> %</var><a href="<?php echo URL::website('/company/member/project/addbobao').'?invest_id='.$v['investment_id'];?>" class="ml25">查看详情</a></p>
                        <p>
                            现场图片：
                            <?php if($v['bobao_have_img']){?>
                            已上传<a href="<?php echo URL::website('/company/member/project/addBobaoImg').'?invest_id='.$v['investment_id'];?>" class="ml25">去看看</a>
                            <?php }else{?>
                            未上传<a href="<?php echo URL::website('/company/member/project/addBobaoImg').'?invest_id='.$v['investment_id'];?>" class="ml25">去上传</a>
                            <?php }?>
                        </p>
                        <?php }?>
                    </div>
                </div>
            </li>
            <?php }?>
        </ul>
    </div>
    <?=$page;?>
</div>
<script>


    $(".reason").click(function(){
        var invest_id= $(this).attr("id");
        $.ajax({
            type: "post",
            dataType: "text",
            url: "/company/member/ajaxcheck/getReason",
            data: "invest_id="+invest_id,
            complete :function(){
            },
            success: function(msg){
                if(msg){
                    $("body")[0].show({
                        title:"查看审核失败原因",
                        content:"<div class='mb_content'>"+msg+"</div>",
                        btn:"ok"
                    });
                }
            }
        });
        return false;
    });


</script>
                <!--主体部分结束-->