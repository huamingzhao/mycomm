<?php echo URL::webjs("platform/AC_RunActiveContent.js")?>
<?php echo URL::webjs("platform/home/home.js");?>
<!--搜索部分-->
<div class="ryl_index_search">
  <div class="ryl_index_searchleft" style="text-indent:-9999px">一句话，创业投资好项目，一句话的事</div>
  <div class="ryl_index_searchright">
      <form action="/platform/index/search" method="get" class="formStyle" id="formStyle">
    <div class="ryl_search_frame">
    <p>

<!--      <span><a href="#">找投资者</a>
      <em>
        <a href="#" target="_blank" class="cur" title="找项目">找项目</a>
        <a href="#" target="_blank" title="找投资者">找投资者</a>
      </em>
      </span>-->

      <input type="text" placeholder="我想在全国找餐饮娱乐项目" id="word" name="w" class="ryl_index_searchtext" autocomplete="off" maxlength="38" style="width:380px;"/>
    </p>
    <input type="button" id="inputSubmit" class="ryl_index_searchbtn" />
    </div>
      <ul style="display:none" class="auto_list"></ul>
     <div class="clear"></div>
     <h1 class="ryl_search_frame_text">用一句话描述您的需求，我们将为您推荐最适合的好项目。</h1>
    </form>
  </div>
</div>
<!--主体部分-->
<div class="ryl_index_main">
    <div class="ryl_index_cont">
      <div class="ryl_index_cont_left" ></div>
      <div class="ryl_index_cont_center">
      <ul>
       <li class="ryl_index_cont_ty_first"><a href="<?php echo urlbuilder::rootDir('search');?>" class="chart_icon"></a><a href="<?php echo urlbuilder::rootDir('search');?>"  class="chart_btn"></a></li>
       <li class="ryl_index_cont_ty_second"><a href="<?php echo urlbuilder::zhuntouzi();?>" class="chart_icon"></a><a href="<?php echo urlbuilder::zhuntouzi();?>"  class="chart_btn"></a></li>
       <li class="ryl_index_cont_ty_third"><a href="<?php echo urlbuilder:: projectGuide ("fenlei");?>" class="chart_icon"></a><a href="<?php echo urlbuilder:: projectGuide ("fenlei");?>"  class="chart_btn"></a></li>
       <li class="ryl_index_cont_ty_forth"><a href="<?php echo urlbuilder::guangtouzi();?>" class="chart_icon"></a><a href="<?php echo urlbuilder::guangtouzi();?>"  class="chart_btn"></a></li>
      </ul>
      </div>
      <div class="ryl_index_cont_right">
         <a href="<?php echo urlbuilder::qiye("denglu");?>" style="TEXT-DECORATION:none"><p>企业服务</p></a>
         <span>我们为您提供搜索投资者精准服务、名片定制人性化服务、信息发布通路服务、企业自主招商会服务</span>
      </div>
    </div>
</div>
