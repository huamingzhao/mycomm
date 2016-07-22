<?php echo URL::webcss("platform/index.css")?>
<?php echo URL::webjs("platform/home/home.js");?>
  <!--公共背景框-->
  <div class="main" style="height:auto;">
    <!--浏览记录-->  
    <div class="browse_record">
      <div class="browse_record_nav">
        <a href="<?php echo URL::website("");?>">首页</a>
        &gt;
        <span>我的浏览记录</span>
      </div>
      <div class="browse_record_delete_all">
        <p class="content">您最近没有浏览过项目</p>
        <div class="browse_search">
            <p class="message">您可以输入条件直接搜索项目</p>
            <div class="browse_search_cont">
            <form action="/platform/index/search" method="get" class="formStyle">
            <input type="hidden"  id="hiddenvalue"  value="1"  />
                <p>
                 <input type="text" placeholder="请输入您要搜索的条件。如： 餐饮 10万 上海" id="word" name="w" class="ryl_index_searchtext" autocomplete="off" maxlength="38" style="color: rgb(188, 188, 188);">               
                </p>
                <input type="button" class="ryl_index_searchbtn" id="inputSubmit">
             </form>
              <ul style="display:none;left:0px; top:37px; width:492px;" class="auto_list"></ul>
            </div>            
            <h1 class="browse_search_text">用一句话描述您的需求，我们将为您推荐最适合的好项目。</h1>
        </div>
        <p class="link">推荐您访问：<a href="<?php echo urlbuilder::rootDir('xiangdao');?>">找项目</a><a href="<?php echo urlbuilder:: projectGuide ("fenlei");?>">项目向导</a><a href="<?php echo urlbuilder::rootDir("zixun");?>">学做生意</a></p>
      <div class="browse_record_title">
        <h2>一句话人气项目</h2>
      </div>
      <ul class="browse_list">
      <?php if($project_list_sevent_day){$i = 0;foreach ($project_list_sevent_day as $key=>$val){$i++;?>
      	<li <?php if($i== 6){echo "class='last'";}?>>
          <label>
            <a href="<?php echo urlbuilder::project($val['project_id']);?>"  title="<?php echo $val['project_brand_name'];?>">
              <img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php echo $val['project_brand_name'];?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>"></a>
          </label>
          <span>
            <a href="<?php echo urlbuilder::project($val['project_id']);?>"  title="<?php echo $val['project_brand_name'];?>"><?php echo $val['project_brand_name'] ? mb_substr($val['project_brand_name'], 0,8,'UTF-8')."":"未知";?></a>
          </span>
          <span class="browse_pro_money">
            ￥ <em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em>
          </span>
          <div>
            <p class="p_01">
              <a title="<?php echo $val['project_brand_name'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>">
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
        <div class="clear"></div>
      </div>
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
    <a href="#" class="ok">确  定</a>
    <a href="#" class="cancel">取  消</a>
  </p>
</div>
<!-- 删除确认弹出框 END -->
<script type="text/javascript" src="http://static.myczzs.com/js/platform/browse_record.js"></script>
</body>
</html>