<?php echo URL::webcss("ask.css")?>
<?php echo URL::webjs('ask.js')?>
<script type="text/javascript">
$(function() {

        $(".fdiv:gt(0)").hide();
           var contab = $("#.conleft .asktit a")  
           contab.hover(function(){  
           $(this).addClass("on").siblings().removeClass("on");	
        });  
        contab.click(function(){  
           $(this).addClass("on");   
           var congcong_index = $("#.conleft .asktit a").index(this);  
           $(".questcon").eq(congcong_index).show().siblings().hide();			 
        })   
});
</script>
<div class="asknavwrap">
  <div class="asknav">
  	<span>当前位置：</span><a href="<?php echo urlbuilder::rootDir('');?>">一句话网</a><span class="wt15"> > </span><a href="<?php echo url::webwen('');?>">创业问答</a><span class="wt15"> > </span><a href="<?php echo url::webwen('ask/ask/getMyAsk?type=1');?>">我的问答</a>
  </div>
</div>
<div class="myaskcon">
	<div class="conleft">
    	<div class="asktit">
        	<a <?php if(isset($type) && $type==1){echo 'class="on" ';}?> href="javascript:;">我的提问（<?php echo (isset($login_status) && $login_status)? $title_total : 0;?>）</a>
            <a <?php if(isset($type) && $type==2){echo 'class="on" ';}?> href="javascript:;">我的回答（<?php echo (isset($login_status) && $login_status)? $answer_total : 0;?>）</a>
        </div>
        <div class="questbox">
       
        <div style="<?php if(isset($type) && $type==2){echo 'display: none;';}?>" class="questcon">
          <ul>
          	<?php if(isset($list_title) && count($list_title)){
				foreach($list_title as $k=>$v){
			?>
	            <li>
	              	<p class="questl"><a href="<?php echo url::webwen('/question/'.$v->ask_id.'.shtml')?>" target="_blank"><?php echo $v->ask_name;?></a></p>
	                <p class="questb">
	                	<span class="p1"><?php echo isset($title_answer_count) && array_key_exists($v->ask_id, $title_answer_count) ?$title_answer_count[$v->ask_id]:0;?>个回答</span><span class="p2">浏览次数：<?php echo $v->ask_pv_count;?></span><span class="p3"><?php echo date('Y-m-d H:i:s',$v->add_time);?></span>
	                </p>
	                <?php if($v->ask_adopt_type ==1){?>
	                	<a class="action2" href="<?php echo url::webwen('/question/'.$v->ask_id.'.shtml')?>" target="_blank">已采纳</a>
	                <?php }else{?>
	                	<a class="action1" href="<?php echo url::webwen('/question/'.$v->ask_id.'.shtml')?>" target="_blank">去处理</a>
	                <?php }?>
	            </li>
	        <?php }}else{?>
	        	<li><p style="height:30px; line-height: 30px; font-size: 16px; margin-top: 25px;">您还没有提问问题，快去提问吧。去<a href="<?php echo url::webwen('ask/ask/addMyAsk');?>" target="_self">提问</a></p></li>
	        <?php }?>
          </ul>
          <!-- 分页开始 -->
          <div style="margin-bottom: 20px" class="ryl_search_result_page">
			<?php if(isset($page_title)){ echo $page_title;} ?>
          </div>
          <!-- 分页结束 -->
        </div>
        <div style="<?php if(isset($type) && $type==1){echo 'display: none;';} ?>" class="questcon">
          <ul>
          	<?php if(isset($list_answer) && count($list_answer)){
          		foreach($list_answer as $akey=>$avalue){
          	?>
	            <li>
	              	<p class="questl"><a href="<?php echo url::webwen('/question/'.$list_answer[$akey]['ask_id'].'.shtml')?>" target="_blank"><?php echo $list_answer[$akey]['title'];?></a></p>
	                <p class="questb">
	                	<span class="p1"><?php echo $list_answer[$akey]['answer_answer_count'];?>个回答</span><span class="p2">浏览次数：<?php echo $list_answer[$akey]['pv_count'];?></span><span class="p3"><?php echo date('Y-m-d H:i:s',$list_answer[$akey]['time']);?></span>
	                </p>
	                <?php if($list_answer[$akey]['adopt_type']){?>
	                	<a class="action2" href="<?php echo url::webwen('/question/'.$list_answer[$akey]['ask_id'].'.shtml')?>" target="_blank">已被采纳</a>
	                <?php }else{?>
	                	<a class="action1" href="<?php echo url::webwen('/question/'.$list_answer[$akey]['ask_id'].'.shtml')?>" target="_blank">去处理</a>
	                <?php }?>
	            </li>
	        <?php }}?>
          </ul>
          <!-- 分页开始 -->
          <div style="margin-bottom: 20px" class="ryl_search_result_page">
         		<?php if(isset($page_answer)){ echo $page_answer;} ?>	
          </div>
          <!-- 分页结束 -->
        </div>
        </div>
    </div>
	<div style="float:right;">
        <div class="askrightimg pb">
            <div class="ngask">
            	<p>共解决<?php $askcount_length=strlen($askcount);
      					 for( $i=0;$i<$askcount_length;$i++ ){ echo '<span>'.mb_substr($askcount,$i,1).'</span>'?>
     			 <?php }?>个问题
      			</p>
      		</div>
        <!--add go ask-->
            <div class="myquestion">
                <a href="<?php echo url::webwen('ask/ask/getMyAsk?type=1');?>" class="myask">我的提问<span>(<?php echo (isset($login_status) && $login_status)? $title_total : 0;?>)</span></a>
                <a href="<?php echo url::webwen('ask/ask/getMyAsk?type=2');?>" class="myask">我的回答<span>(<?php echo (isset($login_status) && $login_status)? $answer_total : 0;?>)</span></a>
                <a href="<?php echo url::webwen('ask/ask/addMyAsk');?>" class="igoask"></a>
            </div>
        </div>    
        <div class="askbottomright floright">
        
            <div class="askannouncement">
                <h2>问答频道公告</h2>
                <ul class="announcementlist">
                     <li><span>1</span><a target="_blank" href="<?php echo url::webwen('gonggao/102.shtml');?>">企业如何加盟一句话创业网？</a></li>
          			<li><span>2</span><a target="_blank" href="<?php echo url::webwen('gonggao/101.shtml');?>">在问答频道发创业问题的好处有哪些？</a></li>
                </ul>
            </div>
            <div class="entrepreneurship">
                <h2>创业者常问问题</h2>
                <ul class="entrlist">
                    <?php foreach($askusedlist as $v){?>
          				<li><a href="<?php echo URL::webwen('question/'.arr::get($v,'id','').'.shtml');?>"><?php echo arr::get($v,'ask_name','')?></a></li>
					<?php }?>
                </ul>
            </div>
            <div class="entrepreneurship">
                <h2>创业学知识推荐</h2>
                <ul class="entrlist">
                    <?php foreach($askZixunList as $v1){?>
          				<li><a href="<?php echo zxurlbuilder::zixuninfo($v1['id'],date("Ym",$v1['article_addtime']));?>" title="<?php echo arr::get($v1,'article_name');?>"><?php echo arr::get($v1,'article_name');?></a></li>
         		 	<?php }?>
                </ul>
            </div>
		</div>
    </div>    
</div>
<div style="clear:both; height:10px;"></div>