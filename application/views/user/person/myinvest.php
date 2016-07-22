<?php echo URL::webcss("zhaoshang.css"); ?>
<?php echo URL::webjs("invitepro_zg.js"); ?>
<style>
*{ font-family:"宋体";}
.renqiList ul li{ height:auto; margin-bottom:0;}
.renqiList p.number{ padding-top:5px;}
</style>
 <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>我报名的投资考察会</span><div class="clear"></div></div>
        <div id="right_con" style="padding-top:20px;">
            <div class="invest" style=" width:730px;">
                <?php foreach ($list as $value){?>
                <div class="renqiList">
                    <ul>
                        <li><a href="<?php echo urlbuilder::projectInvest($value['investment_id']);?>"><img src="<?php echo URL::imgurl($value['investment_logo']);?>" width="120" height="96"/></a></li>
                        <li class="liSecond">
                            <h4><?=$value['investment_name']?></h4>
                            <p class="ryl_zs">投资考察会详情：<?=$value['investment_details']?></p>
                            <p class="ryl_zs">投资考察会时间：<?=$value['investment_start']?> - <?=$value['investment_end']?></p>
                            <p>投资考察会地址：<?=$value['investment_address']?></p>
                        </li>
                        <li class="liThird">
                            <a href="<?php echo urlbuilder::projectInvest($value['investment_id']);?>" class="aLink_0">查看详情</a>
                            <?php if($value['spantime']==-1){?><a onclick="$('body')[0].show({title:'删除投资者考察会',content:$(this).attr('data-content')});" data-content="<p>一旦删除，将无法取回。确定要删除此投资者考察会吗？</p><p class='btn'><a href='<?php echo URL::site('/person/member/invest/deleteInvest'.'?apply_id='.$value['apply_id']);?>' class='ok'>确定</a><a href='#' class='cancel'>取消</a></p>" href="javascript:void(0)" class="aLink_2">删除投资考察会</a><?php }?>
                        </li>
                        <div class="clear"></div>
                    </ul>
                    <div class="clear"></div>
                    <p class="number"><?php if($value['spantime']==-1){?><span style="color:#cfcfcf">
                        倒计时：已结束</span><?php }else{?><span>倒计时：还有<ins> <?=$value['spantime']?> </ins>天</span><?php }?></p>
                </div>
                <?php }?>
                <?=$page?>

            </div>
        </div>
    </div>
    <!--右侧结束-->
    <div class="opacityDiv" id="opacityDiv"></div>
<div class="deletePopup" id="deletePopup">
    <div class="top" id="deleteTop"><img src="<?php echo URL::webstatic("images/zhaoshang/close.gif");?>" width="20" height="20" /></div>
    <div class="clear"></div>
    <p class="para_0">您确定要取消此报名吗？</p>
    <div class="popupBtn">
        <a href="#" class="sure" id="deleteSure"></a>
        <a href="#" class="cancel" id="deleteCancel"></a>
    </div>
</div>