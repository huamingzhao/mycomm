<?php echo URL::webjs("platform/home/home.js");?>
<!--公共背景框-->
<div class="main" style="height: auto">
   <div class="error_visit_clum">
       <p class="error_visit_img"><img src="<?php echo URL::webstatic("images/platform/404error/404_img.jpg")?>" /></p>
       <div class="error_visit_text">
            <span>1. 请检查您访问的网址是否正确。</span>
            <span>2. 如果您不能确认访问的网址，请浏览一句话 <a href="<?php echo urlbuilder::root('sitemap')?>">网站地图</a> 查看更多生意信息。</span>
            <span>3. 直接搜索您想要查找的生意：</span>
             <div class="ryl_index_searchright error_search_clum">
               <div class="ryl_search_frame">
                <p>
                  <!--<span><a href="#">找投资者</a>
                  <em style="display:none;">
                    <a title="找项目" class="cur" target="_blank" href="#">找项目</a>
                    <a title="找投资者" target="_blank" href="#">找投资者</a>
                  </em>
                  </span>-->

                  <input type="text" placeholder="" id="word" name="w" class="ryl_index_searchtext" autocomplete="off" maxlength="38" style="width:380px;"/>
                </p>
                <input type="button" class="ryl_index_searchbtn" id="inputSubmit">
             </div>
             </div>
             <div class="clear"></div>
            <span>4.如有任何意见或建议，请及时 <a href="<?php echo urlbuilder::help('lianxi')?>">联系我们</a></span>
            <div class="clear"></div>
       </div>
       <div class="error_tj_list">
              <a href="<?php echo urlbuilder::rootDir("/")?>">一句话首页<em></em></a>
              <a href="<?php echo urlbuilder::quickSearchIndex();?>">找生意</a>
              <a href="<?php echo urlbuilder::rootDir("zixun");?>">学做生意</a>
       </div>
       <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>