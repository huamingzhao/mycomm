
<?php echo URL::webcss("cz.css")?>
<div class="right">
    <form id="forminfo">
    <h2 class="user_right_title">
        <span>参展项目管理</span>
        <a class="fr applycommun" href="<?php echo URL::website('/company/member/exhb/ApplyForCommunication');?>">免费申请在线沟通服务</a>
        <div class="clear"></div>
    </h2>
    <div class="my_business_new">
        <div class="project_detial project_release">
            <div class="fabulayer" style="display:<?= $showMsg ? 'block' : 'none'?>"  >
                <div class="layercontent">
                    <h3>恭喜您，您的展会项目发布成功！</h3>
                    <p>恭喜您，你的参展项目发布成功，我们将尽快进行审核，审核通过展会开展后，你的参展项目信息将会被投资者查阅到。</p>
                </div>
            </div>
            <div class="layerbg"  style="display:<?= $showMsg ? 'block' : 'none'?>" ></div>
            <ul class="ulprojectlist">
                <?if(arr::get($exhbProList, 'list', array())){
                    foreach($exhbProList['list'] as $val) {
                    ?>
                    <li class="clearfix">
                    <div class="listtitle">
                        <?=$val['project_brand_name']?>
                        <?if($val['project_status'] == 0) {?>
                        	【状态：<var style="color: #ff6000;">审核中</var>】
                        <?}elseif(arr::get($val,"project_status") == 1 && arr::get($val,"project_temp_status") == 1){?>
                        	【状态：<var style="color:#ff6000;">修改待审核</var>】
                        <?}elseif(arr::get($val,"project_status") == 1 && arr::get($val,"project_temp_status") == 2){?>
                        	【状态：<var style=" color:#0b73bc;">审核通过</var>】
                        <?}elseif(arr::get($val,"project_status") == 2 && arr::get($val,"project_temp_status") != 3){?>
                        	【状态：<var style=" color:#e10602;">审核未通过</var><a href="javascript:void(0)" class='show_fail_result' data-msg='<?=arr::get($val, "project_reason")?>'>原因</a>】
                        <?php }elseif(arr::get($val,"project_status") == 1 && arr::get($val,"project_temp_status") == 3){?>
                        	【状态：<var style=" color:#e10602;">修改审核未通过</var><a href="javascript:void(0)" class='show_fail_result' data-msg='<?=arr::get($val, "project_reason")?>'>原因</a>】
                        <?php }?>
                        <a href="<?php echo URL::website('/company/member/exhb/showExhbProDetail?exhibition_id='.$val['exhibition_id'].'&project_id='.$val['project_id']);?>" class="editinfo floright">信息修改</a>
                    </div>
                    <div class="listcontent clearfix">
                        <div class="floleft imgbox">
                            <p><img width="150" height="138" src="<?=URL::imgurl($val['project_logo'])?>" alt="项目图片"></a></p>
                            <a class="abtn"  target="_blank" href="<?php echo URL::website('/company/member/exhb/showExhbProDetail?exhibition_id='.$val['exhibition_id'].'&project_id='.$val['project_id']);?>">参展项目详情</a>
                            <a class="abtn" target="_blank" href="<?php echo urlbuilder::exhbProject($val['project_id']);?>">参展项目官网</a>
                        </div>
                        <div class="floleft rightinfo">
                            <div class="righttitle">
                                <h3>参展展会：<?=$val['exhbInfo']['exhibition_name']?></h3>
                                <p><span>开展时间：</span><?=date('Y/m/d', $val['exhbInfo']['exhibition_start'])?>-<?=date('Y/m/d', $val['exhbInfo']['exhibition_end'])?></p>
                                <p><span>招商地区：</span><?php if(isset($val['area']) && $val['area']){                   			
                            $area='';
                            foreach ($val['area'] as $v){
                                $area .= $v['pro_name'];
                                $area = $area.',';
                            }
                            echo $area;                           
                   		}
                        ?></p>

                                <p><span>项目优惠券：共计<i><?=$val['coupon_num']?></i>个,还剩 <i><?=$val['hongbao']?></i>个, 去<span><a href="javascript:;" class="adjustbtn" data-projectid="<?php echo $val['project_id']; ?>">调整 ></a></p>

                            </div>
                            <div class="rightcontent">
                                <p class="text">项目完整度：<font><?=$val['infoComplete']?></font><font style="font-family: '微软雅黑'; font-size:12px;">%</font></p>
                                <span class="bar"><font class="fourth" style="width:<?=$val['infoComplete']?>%;"></font></span>
                                <font class="msg">友情提示：项目完整度越高，越能获得投资者去关注</font>
                                <?if($val['project_status'] == 1) {?>
                                <p class="pnum" style="margin-top:10px;">截至目前访问量为<i><?=arr::get($val, 'liulan', 0)?></i>，去   <a href="<?php echo URL::website('/company/member/exhb/showProjectPv');?>?project_id=<?=$val['project_id']?>">查看详情>> </a></p>
                                <p class="pnum">截至目前意向加盟签约<i><?=$val['mpian']?></i>人，去   <a href="<?php echo URL::website('/company/member/exhb/showYiXiangList?type=2&name=&status=0');?>?project_id=<?=$val['project_id']?>">查看详情>></a></p>
                                <?}?>
                                <ul class="operation clearfix">
                                    <?if(!$val['expected_return']) {?><li class="<?=$val['expected_return'] ? "ok" : "no" ?>">缺少项预期收益信息，去<a href="<?php echo URL::website('/company/member/exhb/showExhbOther?exhibition_id='.$val['exhibition_id'].'&project_id='.$val['project_id'])?>"><?=$val['expected_return'] ? "修改" : "添加" ?></a></li><?}?>
                                    <?if(!$val['preferential_policy']) {?><li class="<?=$val['preferential_policy'] ? "ok" : "no" ?>">缺少项目优惠政策信息，去<a href="<?php echo URL::website('/company/member/exhb/showExhbTuiGuang?exhibition_id='.$val['exhibition_id'].'&project_id='.$val['project_id'])?>"><?=$val['preferential_policy'] ? "修改" : "添加" ?></a></li><?}?>
                                    <?if(!$val['project_advantage_img']) {?><li class="<?=$val['project_advantage_img'] ? "ok" : "no" ?>">缺少项目优势图，去<a href="<?php echo URL::website('/company/member/exhb/showExhbMore?exhibition_id='.$val['exhibition_id'].'&project_id='.$val['project_id'])?>"><?=$val['project_advantage_img'] ? "修改" : "添加" ?></a></li><?}?>
                                    <?if(!$val['project_running_img']) {?><li class="<?=$val['project_running_img'] ? "ok" : "no" ?>">缺少项目运营操作图，去<a href="<?php echo URL::website('/company/member/exhb/showExhbMore?exhibition_id='.$val['exhibition_id'].'&project_id='.$val['project_id'])?>"><?=$val['project_running_img'] ? "修改" : "添加" ?></a></li><?}?>
                                    <?if(!$val['preferential_policy_img']) {?><li class="<?=$val['preferential_policy_img'] ? "ok" : "no" ?>">缺少项目优惠政策图，去<a href="<?php echo URL::website('/company/member/exhb/showExhbOther?exhibition_id='.$val['exhibition_id'].'&project_id='.$val['project_id'])?>"><?=$val['preferential_policy_img'] ? "修改" : "添加" ?></a></li><?}?>
                                    <?if(!$val['expected_return_img']) {?><li class="<?=$val['expected_return_img'] ? "ok" : "no" ?>">缺少项目预期收益图，去<a href="<?php echo URL::website('/company/member/exhb/showExhbOther?exhibition_id='.$val['exhibition_id'].'&project_id='.$val['project_id'])?>"><?=$val['expected_return_img'] ? "修改" : "添加" ?></a></li><?}?>
                                    <?if(!$val['company_strength_img']) {?><li class="<?=$val['company_strength_img'] ? "ok" : "no" ?>">缺少公司实力展示图，去<a href="<?php echo URL::website('/company/member/exhb/showExhbOther?exhibition_id='.$val['exhibition_id'].'&project_id='.$val['project_id'])?>"><?=$val['company_strength_img'] ? "修改" : "添加" ?></a></li><?}?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <?}}?>
                
            </ul>
        </div>
</div>
        <?=arr::get($exhbProList, 'page', '');?>
        </div>
        <!--透明背景开始-->
<div id="zhaos_opacity"></div>
<script type="text/javascript">

//审核未通过的原因JS
$(".show_fail_result").click(function(){
    // $(".show_reason_box p.msg").html($(this).attr("data-msg"));
    $("body")[0].show({
        title:"查看项目未通过原因",
        content:"<p>"+$(this).attr("data-msg")+"</p>",
        btn:"ok"
    });
    // $("#getcards_opacity").show();
    // $(".show_reason_box").slideDown(500, function(){});
});
    setTimeout(function(){
        $(".fabulayer").slideUp();
        $(".layerbg").hide();
    },3000)
    $(".adjustbtn").click(function(){
        var project_id=$(this).attr("data-projectid")
        var total=$(this).parent().parent().find("i").eq(0);
        var remainnum=$(this).parent().parent().find("i").eq(1);
        window.MessageBox({
        title:"一句话网站提醒您",
        content:"<p style='padding-bottom:0'>项目优惠券总数：<span style='color:#e10602'>"+total.text()+"</span>个</p><p style='font-size:14px; padding-left:70px; margin-top:10px;'>项目优惠券剩余数：<span style='color:#e10602'>"+remainnum.text()+"</span>个</p><p style='font-size:14px; padding-left:70px; margin-top:10px;'>增加项目优惠券：<input type='text' class='addnum' value='' onkeyup=\"this.value=this.value.replace(/\\D/g,'')\" onafterpaste=\"this.value=this.value.replace(/\\D/g,'')\"></p><p class='error' style='margin-bottom:30px; padding-left:70px; color:red;'></p>",
        btn:"ok",
        width:490,
        callback:function(){
            var addnumvalue=$(".addnum").val();
            var addnum=parseInt(addnumvalue);
            if(addnumvalue==""){
                $(".addnum").parent().next().text("优惠券数量不能为空");
            }
            else{
                var dt=$.ajaxsubmit("/platform/ajaxcheck/updateCouponNum",{"project_id":project_id,"num":addnum});
                if(dt){
                    total.text(parseInt(total.text())+addnum);
                    remainnum.text(parseInt(remainnum.text())+addnum);
                    window.MessageBox({
                        title:"标题",
                        content:"<p>优惠券调整成功</p>",
                        btn:"ok",
                        width:490,
                        target:"new"
                        });
                    }
                }
                $(".addnum").parent().next().text("");
            }
            
        })  
    })
</script>
