<?php echo URL::webcss("personinfor.css")?>
<style>
#ryl_tel_check strong{font-family:Arial; font-weight:normal;}
#ryl_tel_check b{ float:left; font-weight:normal; padding-top:2px; padding-right:10px;}
#ryl_tel_check a{ float:left; color:#0036ff; font-size:12px;}
</style>
   <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>基本信息</span><div class="clear"></div></div>
        <div id="right_con">
            <div class="personInfor">
                <form action="" method="post">
                    <div class="inforDetail">
                        <p><em>姓名：</em><?=isset($personinfo['person']->per_realname) ? $personinfo['person']->per_realname : '';?></p>
                        <p><em>性别： </em><?php if(isset($personinfo['person']->per_gender) && ($personinfo['person']->per_gender == 1)){ ?>男<?php }else{ ?>女<?php } ?></p>
						
                        <p id='ryl_tel_check' ><em style='float:left'>手机号码：</em><strong style="float:left; padding-right:10px; font-weight:normal;"><?=isset($personinfo['mobile']) ? $personinfo['mobile'] : '';?></strong><?if(isset($personinfo['mobile']) && $personinfo['mobile']){;?><b ><?if(!$personinfo['valid_mobile']){?><img src="<?php echo URL::webstatic('images/infor1/tel_nocheck.jpg')?>" /></b><a href="/person/member/valid/mobile">去验证</a><?}else{?><img src="<?php echo URL::webstatic('images/infor1/tel_check.jpg');?>" /></b><a href="/person/member/valid/mobile?to=change">修改手机号码</a><?}}?></p>

                        <div class='clear'></div>
                        <p><em>邮箱：</em><?=isset($personinfo['email']) ? $personinfo['email'] : '';?></p>
                        <p><em>个人所在地：</em>
                        <?php foreach ($area as $v){?>
                        <?php if($v['cit_id']==arr::get($areaIds, 0)) { echo $v['cit_name']; }?>
                        <?php }?>
                        <?php if(arr::get($areaIds, 0)!='' && count($cityarea)){ foreach ($cityarea as $v): ?>
                        <?php if($v->cit_id == arr::get($areaIds, 1)) echo $v->cit_name;?>
                        <?php endforeach; } ?>
                        </p>



                        <p><em>意向投资金额：</em><?php $monarr= common::moneyArr(); echo $personinfo['person']->per_amount== 0 ? '无': $monarr[$personinfo['person']->per_amount];?></p>
                        <p><em>意向投资行业：</em><?=$personalIndustryString?></p>
                        <p><em>意向投资地区：</em><?=$personIndArea;?></p>
                        <p><em>我的标签：</em><?=isset($personinfo['person']->per_per_label) ? $personinfo['person']->per_per_label : '';?></p>
                        <p><em>个性投资说明：</em><?=isset($personinfo['person']->per_remark) ? $personinfo['person']->per_remark : '';?></p>
                        <a href="<?php echo URL::website('/person/member/basic/personupdate')?>"  class="aLinkBtn"></a><a href="<?php echo URL::website('/person/member/card/mycard')?>" id="aLinkStyle">您的个人名片已经生成，现在去看看吧</a>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--右侧结束-->
