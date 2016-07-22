<?php echo URL::webcss("platform/broadcast.css")?>
<div class="main" style="height:auto; background-color:#eaeaea;">

   <!--头部-->
   <div class="broadcast_head">
     <div class="broadcast_head_cont">
         <div class="broadcast_head_bg01"></div>
         <div class="broadcast_head_bg02"></div>
         <div class="broadcast_head_bg03"><span><a href="<?php echo urlbuilder::help("qyfw")?>" target="_blank">了解企业服务</a></span></div>
     </div>
   </div>
   <!--内容-->
   <div class="broadcast_cont">

       <div class="broadcast_unit broadcast_cont02">
           <div class="broadcast_unit_intro">
              <p>快速搜索项目</p>
              <span>输入关键字或者一句话，我们全力搜索您需要的项目</span>
              <a href="/" target="_blank">去快速搜索项目</a>
           </div>
           <img alt="快速搜索项目" src="<?php echo URL::webstatic("images/platform/broadcast/pic02.jpg")?>" />
           <div class="clear"></div>
       </div>
       <div class="broadcast_unit broadcast_cont03">
           <div class="shadow"><img  src="<?php echo URL::webstatic("images/platform/broadcast/shadow_blue.jpg")?>" /></div>
           <img  alt="项目向导" src="<?php echo URL::webstatic("images/platform/broadcast/pic03.jpg")?>" />
           <div class="broadcast_unit_intro">
              <p>项目向导</p>
              <span>最佳口碑项目、最受关注项目、女性创业首选项目……您可以在此浏览众多优质项目，从投资行业到投资风格，总会有适合您的选择！</span>
              <a href="<?php echo urlbuilder::fenleiCond();?>" target="_blank">去项目向导</a>
           </div>
           <div class="clear"></div>
       </div>
       <div class="broadcast_unit broadcast_cont04">
           <div class="shadow"><img src="<?php echo URL::webstatic("images/platform/broadcast/shadow_white.jpg")?>" /></div>
           <div class="broadcast_unit_intro">
              <p>名片服务</p>
              <span>与企业交换自己的名片，建立友好互动关系，增加投资合作机会</span>
              <a href="<?php echo URL::website("person/member/card/mycard");?>" target="_blank">去生成我的名片</a>
           </div>
           <img alt="名片服务" src="<?php echo URL::webstatic("images/platform/broadcast/pic04.jpg")?>" />
           <div class="clear"></div>
       </div>
       <div class="clear"></div>
   </div>
   <div class="broadcast_cont_bot"><img src="<?php echo URL::webstatic("images/platform/broadcast/cont_bg_bot02.jpg")?>" /></div>


   <div class="clear"></div>
</div>