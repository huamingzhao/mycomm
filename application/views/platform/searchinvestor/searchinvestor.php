<?php echo URL::webcss("platform/search_tzz.css")?>
<?php echo URL::webjs("platform/home/login_fu.js")?>
<div class="searchIndex_banner_bg">
    <div class="top_bg">
        <div class="search_box">
        <form action="<?php echo urlbuilder::qiye("touzizhe");?>" method="get" enctype="application/x-www-form-urlencoded">
            <div class="search_select">
                <div class="select_flo">
                           <select id="address" name="per_area"  >
                                 <option value="">选择所在地</option>
                                  <?php foreach ($area as $v){?>
                                  <option value="<?=$v['cit_id']?>" <?php if($v['cit_id']==arr::get($postlist, 'per_area')){echo "selected='selected'";}?>><?=$v['cit_name']?></option>
                                 <?php }?>
                            </select>

                                <select name="per_amount" >
                                    <option  value="" >选择意向投资金额</option>
                                    <?php $moneylist = common::moneyArr(); foreach ($moneylist as $k=>$v): ?>
                                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'per_amount')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                                    <?php endforeach; ?>
                                </select>
                </div>
                <div class="select_flo">
                            <select name="parent_id" >
                            <option  value="" >选择意向投资行业</option>
                            <?php $primarylist = common::primaryIndustry(0); foreach ($primarylist as $v):?>
                            <option value="<?=$v->industry_id;?>" <?php if(arr::get($postlist,'parent_id')==$v): echo 'selected="selected"';endif;?> ><?=$v->industry_name;?></option>
                            <?php endforeach; ?>
                            </select>
                </div>
                <div class="button_flo"><button></button></div>
                <div class="clear"></div>
            </div>
             </form>
        </div>
    </div>
</div>
<div class="searchIndex_content">
    <div class="step"><img src="<?php echo URL::webstatic("images/platform/search_index/step.jpg");?>" alt="根据需求，搜索投资者 查看名片，获得潜在投资者信息，线下沟通，达成加盟意向"/></div>
    <div class="case">
        <div class="case_tp"><img src="<?php echo URL::webstatic("images/platform/search_index/case_icon.png");?>"  alt="投资成功案例"/></div>
        <div class="case_bg">
            <ul>
                <li>
                    <div class="case_in">
                        <p><img src="<?php echo URL::webstatic("images/platform/search_index/case11.jpg");?>" alt="投资商 肖荣旭"/></p>
                        <p class="text">“一句话”带来革命性OT模式和全国销售网络，阔展蓝海市场，比肩世界品牌。</p><br>
                        <p class="name">龙润集团副总裁  肖荣绪</p>
                    </div>
                </li>
                <li>
                    <div class="case_in">
                        <p><img src="<?php echo URL::webstatic("images/platform/search_index/case12.jpg");?>" alt="投资商 王德坤"/></p>
                        <p class="text">“一句话”成功树立鸿兴源品牌形象，与加盟商成功签约，开店400家以上。</p><br>
                        <p class="name">山东鸿兴源  王德坤董事长</p>
                    </div>
                </li>
                <li>
                    <div class="case_in">
                        <p><img src="<?php echo URL::webstatic("images/platform/search_index/case13.jpg");?>" alt="投资商 程晨"/></p>
                        <p class="text">“一句话”企业服务能力强，短时间内可扩展项目渠道，吸引投资者签约加盟。 </p><br>
                        <p class="name">巨人集团副总裁  程晨 </p>
                    </div>
                </li>
                <li>
                    <div class="case_in">
                        <p><img src="<?php echo URL::webstatic("images/platform/search_index/case14.jpg");?>" alt="投资商 张蔷"/></p>
                        <p class="text">“一句话”强大的企业招商宣传力，让每次招商会的签约率都达到了70%以上。</p><br>
                        <p class="name">上海生态家董事长  张蔷</p>
                    </div>
                </li>
            </ul>
        </div>
        <div class="clear"></div>
    </div>
    <?php if(!$islogoin){?>
    <div class="case_btn" ><a title="立即登录体验" id="search_login" href="javascript:void(0)">立即登录体验</a></div>
    <?php }?>
<!--透明背景开始-->
<div id="opacity"></div>
<!--透明背景结束-->

</div>
