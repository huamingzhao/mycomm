<?php echo URL::webcss("platform/index.css")?>
<!--公共背景框-->
  <div class="main" style="height:auto;">
    <!--浏览记录-->  
    <div class="browse_record">
      <div class="browse_record_nav">
        <a href="<?php echo URL::website("");?>">首页</a>
        &gt;
        <span>我的浏览记录</span>
      </div>
      <!-- 你最近浏览记录 -->
      <div class="browse_record_content">
        <div class="browse_record_title">
          <h2>您最近浏览过的项目</h2>
          <a id="delete_all" href="javascript:void(0)">删除所有项目</a>
        </div>
        <ul class="browse_record_cont browse_record_cont_left">
        <?php if(isset($arr_data[0])) { foreach ($arr_data[0] as $key1=>$val1){ if(isset($val1['brose_id'])){?>
        <li>
            <label>
              <a href="<?php echo urlbuilder::project($val1['project_id']);?>" target="_blank" title="<?php echo $val1['project_brand_name']?>">
                <img alt="<?php echo $val1['project_brand_name']?>" width="150" height="120" src="<?if($val1['project_source'] != 1) {$img =  project::conversionProjectImg($val1['project_source'], 'logo', $val1);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val1['project_logo']);}?>"></a>
              <span>
               <a href="<?php echo URL::website("platform/index/deleteHavaBrose?id=".arr::get($val1,'brose_id'));?>">删除此项</a>
              </span>
            </label>
            <div class="browse_record_infor">
              <p>
                <span>品牌名称：</span> <b><?php echo $val1['project_brand_name']?></b>
              </p>
              <p>
                <span>投资金额：</span> <em><?php $monarr= common::moneyArr(); echo arr::get($val1, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val1, 'project_amount_type')];?></em>
              </p>
              <p>
                <span>行　　业：</span> <b><?php  echo $val1['industry_name']?></b>
              </p>
              <p>
                <span>招商地区：</span>
                <b><?php  echo mb_substr($val1['area_name'],0,13,'UTF-8')."";?></b>
              </p>
            </div>
            <div class="clear"></div>
          </li>
        <?php }}}?>
        <?php if(isset($arr_data[2])) { foreach ($arr_data[2] as $k=>$v){ if(isset($v['brose_id'])){?>
        <li>
            <label>
              <a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank" title="<?php echo $v['project_brand_name']?>">
                <img alt="<?php echo $v['project_brand_name']?>" width="150" height="120" src="<?if($v['project_source'] != 1) {$img =  project::conversionProjectImg($v['project_source'], 'logo', $v);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($v['project_logo']);}?>"></a>
              <span>
               <a href="<?php echo URL::website("platform/index/deleteHavaBrose?id=".$v['brose_id']);?>">删除此项</a>
              </span>
            </label>
            <div class="browse_record_infor">
              <p>
                <span>品牌名称：</span> <b><?php echo $v['project_brand_name']?></b>
              </p>
              <p>
                <span>投资金额：</span> <em><?php $monarr= common::moneyArr(); echo arr::get($v, 'project_amount_type') == 0 ? '无': $monarr[arr::get($v, 'project_amount_type')];?></em>
              </p>
              <p>
                <span>行　　业：</span> <b><?php  echo $v['industry_name']?></b>
              </p>
              <p>
                <span>招商地区：</span>
                <b><?php  echo mb_substr($v['area_name'],0,13,'UTF-8')."";?></b>
              </p>
            </div>
            <div class="clear"></div>
          </li>
        <?php }}}?>
        </ul>

        <ul class="browse_record_cont browse_record_cont_right">
            <?php if(isset($arr_data[1])) { foreach ($arr_data[1] as $key1=>$val1){ if(isset($val1['brose_id'])){?>
        <li>
            <label>
              <a href="<?php echo urlbuilder::project($val1['project_id']);?>" target="_blank" title="<?php echo $val1['project_brand_name']?>">
                <img alt="<?php echo $val1['project_brand_name']?>" width="150" height="120" src="<?if($val1['project_source'] != 1) {$img =  project::conversionProjectImg($val1['project_source'], 'logo', $val1);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val1['project_logo']);}?>"></a>
              <span>
               <a href="<?php echo URL::website("platform/index/deleteHavaBrose?id=".$val1['brose_id']);?>">删除此项</a>
              </span>
            </label>
            <div class="browse_record_infor">
              <p>
                <span>品牌名称：</span> <b><?php echo $val1['project_brand_name']?></b>
              </p>
              <p>
                <span>投资金额：</span> <em><?php $monarr= common::moneyArr(); echo arr::get($val1, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val1, 'project_amount_type')];?></em>
              </p>
              <p>
                <span>行　　业：</span> <b><?php  echo $val1['industry_name']?></b>
              </p>
              <p>
                <span>招商地区：</span>
                <b><?php  echo mb_substr($val1['area_name'],0,13,'UTF-8')."";?></b>
              </p>
            </div>
            <div class="clear"></div>
          </li>
        <?php }}}?>
        </ul>
        <!-- 你最近浏览记录 END-->
        <!-- 根据您最近浏览的项目为您推荐 -->
        <div class="browse_record_title">
          <h2>根据您最近浏览的项目为您推荐</h2>
        </div>
       <ul class="browse_list">
        <?php if($arr_recommended_project_data){ $i= 0 ;foreach ($arr_recommended_project_data as $key=>$val){ $i++;?>
        	<li <?php if($i == 5){echo "class='last'";}?>>
            <label>
              <a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name']?>">
                <img alt="<?php echo $val['project_brand_name']?>" width="120" height="95" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>"></a>
            </label>
            <span>
              <a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name']?>"><?php echo $val['project_brand_name']?></a>
            </span>
            <span class="browse_pro_money">
              ￥ <em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em>
            </span>
            <div>
              <p class="p_01">
                <a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>">
                  <?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?> <em class="browsed_fc01">品牌发源地</em>
                </a>
              </p>
              <p class="p_02">
                <a href="<?php echo urlbuilder::project($val['project_id']);?>">
                  <?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?>
                  <em class="browsed_fc02">项目人气</em>
                </a>
              </p>
            </div>
          </li>
        <?php }}?>
          <div class="clear"></div>
        </ul>
        <!-- 根据您最近浏览的项目为您推荐 END-->
        <div class="clear"></div>
      </div>
      <!-- 您可能也喜欢 -->
      <div class="browse_record_recommend">
        <div class="browse_record_title">
          <h2>您可能喜欢的新项目</h2>
        </div>
        <ul class="browse_list">
        <?php if(isset($arr_people_like_data)){$i = 0; foreach ($arr_people_like_data as $key=>$val){$i++?>
        	
        	<li <?php if($i == 5){echo "class='last'";}?>>
            <label>
              <a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name']?>">
               <img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>"></a>
            </label>
            <span>
              <a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php echo $val['project_brand_name']?>"><?php echo $val['project_brand_name']?></a>
            </span>
            <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em>
            </span>
            <div>
              <p class="p_01">
                <a title="" href="<?php echo urlbuilder::project($val['project_id']);?>">
               <?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?> 
                  <em class="browsed_fc01">品牌发源地</em>
                </a>
              </p>
              <p class="p_02">
                <a href="<?php echo urlbuilder::project($val['project_id']);?>">
                  <?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?>
                  <em class="browsed_fc02">项目人气</em>
                </a>
              </p>
            </div>
          </li>
        	
        <?php }}?>
          <li class="more"><a  target="_blank" href="<?php echo urlbuilder:: guideShowRanking ("4");?>">查看更多>></a></li>
          <div class="clear"></div>
        </ul>
        <div class="clear"></div>
      </div>
      <!-- 您可能也喜欢 END-->
      <div class="clear"></div>
    </div>
    <!--浏览记录 END-->  
    <div class="clear"></div>
  </div>
  <div style="display:none;"></div>
</div>
<!-- 弹出框背景 -->
<div id="opacity" style="z-index: 999;"></div>
<!-- 弹出框背景 END -->
<!-- 删除确认弹出框 -->
<div class="browse_record_delete_dialog">
  <div class="close"></div>
  <p class="msg">您确定要删除您浏览过的所有项目吗？</p>
  <p class="btn">
    <a href="<?php echo URL::website("platform/index/deleteHavaBrose?id=all");?>" class="ok">确  定</a>
    <a href="#" class="cancel">取  消</a>
  </p>
</div>
<!-- 删除确认弹出框 END -->
<?php echo URL::webjs("platform/browse_record.js");?>
