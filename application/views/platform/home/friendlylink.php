<?php echo URL::webcss("platform/web_map.css")?>
<div class="main" style="height:auto; background-color:#e3e3e3; padding:15px 0;">
   <div class="map_bg">
       <div class="map_bg01"></div>
       <div class="fridens_link">
          <h3>友情链接<span>LINKS</span></h3>
          <div class="clear"></div>
          <ul>
          <!--  
          <li><a href="http://www.tonglukuaijian.com" target="_blank" title="通路快建官网">通路快建官网</a></li>
          <li><a href="http://www.875.cn" target="_blank" title="875招商平台">875招商平台</a></li>
          <li><a href="http://www.shengyijie.net" target="_blank" title="生意街招商平台">生意街招商平台</a></li>
          <li><a href="http://www.zhaoshangyi.com" target="_blank" title="招商易平台">招商易平台</a></li>
          -->
          <!-- 合作媒体开始 -->
            <?php if(isset($friend_link) && !empty($friend_link)){?>            
            <?php foreach($friend_link as $v){?>
            	<?php if(isset($v->name) && isset($v->domain)){?>
            		<li><a href="http://<?php echo str_replace("http://", "", $v->domain);?>" target="_blank" title="<?php echo $v->name;?>" ><?php echo $v->name;?></a></li>
            	<?php }?>
             <?php }?>            
            <?php }?>
            <!-- 合作媒体结束 -->
          <div class="clear"></div>          
          </ul>
          <div class="clear"></div>
       </div>
       <div class="map_bg03"></div>
    <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>