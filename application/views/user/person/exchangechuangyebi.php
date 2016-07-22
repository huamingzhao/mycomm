<div class="right">
    <div id="right_top">
        <span>创业币兑换</span>
        <div class="clear"></div>
    </div>
    <div id="right_con">
        <div class="exchange_sb">
            <p>
                <span>截止目前你的活跃度指数为：<a href="<?php echo URL::website('person/member/basic/itylist');?>"><i><?php echo $pointCount;?></i></a>，累计已兑换：<i><?php echo $hasUsedCount;?></i></span>
                <span class="last">截止目前你的创业币为：<a href="<?php echo URL::website('person/member/huodong/getChuangYeBiList')?>" title="活跃度指数"><i><?php echo $chuangyebiCount;?></i></a></span>
            </p>
                <form id="exchange_form" action="<?echo URL::website('person/member/huodong/goExchange');?>" method="get">
            <p>
                输入你要兑换的活跃度指数：<input type="text" name="amount" onkeyup='this.value=this.value.replace(/[^0-9]/gi,"")' /><input type="submit" value="兑换"/>
              
            </p>  </form>
            <p>
                <font >10</font>活跃度 =<font>10</font>创业币 =<font>1</font>元人民币
            </p>
        </div>
        <div class="per_index">
        	<?php if($arr_tuijian){?>
            <h3>
                <span class="fc last">你可能会喜欢的创业项目</span>
                <div class="clear"></div>
            </h3>
            <ul class="browse_list sb_project_list clearfix">
            	<?php foreach($arr_tuijian as $k => $val){?>                
                <li <?php if($k == 4){?>class="last"<?php }?>>
                    <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>" src="<?=URL::imgurl($val['project_logo'])?>" /></a></label></p>
                    <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,16,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,16,'UTF-8')."";};?></a></span>
                    <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                    <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?></a></p></div>
                </li>
                <?php }?>
            </ul>
            <?php }?>
            <?php if($arr_xihuan){?>
            <h3>
                <span class="fc last">大家都喜欢的创业项目</span>
                <div class="clear"></div>
            </h3>
            <ul class="browse_list sb_project_list clearfix">
            	<?php foreach($arr_xihuan as $k => $val){?>                
                <li <?php if($k == 4){?>class="last"<?php }?>>
                    <p class="img"><label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>" src="<?=URL::imgurl($val['project_logo'])?>" /></a></label></p>
                    <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,16,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,16,'UTF-8')."";};?></a></span>
                    <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
                    <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?></a></p></div>
                </li>
                <?php }?>
            </ul>
            <?php }?>
        </div>
    </div>
</div>
<?php echo URL::webjs("person_cernter_global.js")?>