<!--中部开始-->
<?php echo URL::webcss("ask.css")?>
<?php echo URL::webjs('ask.js')?>
<div class="asknavwrap">
    <div class="asknav"><span>当前位置：</span><a href="<?php echo urlbuilder::rootDir('');?>">一句话网</a> <span>></span> <h1><a href="<?php echo url::webwen('');?>">创业问答</a></h1></div>
 </div>
<div class="contenttopwrap">
  <div class="contenttop clearfix">
    <div class="floleft bannerleft">
      <?php if(count($ad_image)){?>
	      <a target="_blank" href="<?php echo $ad_image['link_url'];?>" title="<?php echo $ad_image['description'];?>">
	      <img alt="<?php echo $ad_image['description'];?>" src="<?php echo $ad_image['image_url'];?>" width="230" height="230">
	      </a>
	      <a target="_blank" title="<?php echo $ad_image['description'];?>" href="<?php echo $ad_image['link_url'];?>" class="imglink"><?php echo $ad_image['description'];?></a>
      <?php }?>
    </div>
    <div class="floleft askcontentz">
    <?php if(count($ad_head_info)){?>
	      <div class="clearfix">
	        <var class="floleft asktitle"></var>
	        <div class="floleft h3con">
	          <h3 class="cy2013"><a href="<?php echo URL::webwen('question/'.$ad_head_info['ask_id'].'.shtml');?>" class="c666"><?php echo $ad_head_info['ask_name'];?></a></h3>
	          <p class="c666"><?php echo $ad_head_info['ask_describe'];?></p>
	        </div>
	      </div>
	      <div class="clearfix da2">
	        <var class="floleft asktitle1"></var>
	        <div class="floleft h3con">
	          <p><a href="<?php echo URL::webwen('question/'.$ad_head_info['ask_id'].'.shtml');?>" class="c333"><?php echo $ad_head_info['answer_describe'];?></a></p>
	        </div>
	      </div>
      <?php }?>
      <ul class="askullist">
      	<?php foreach($pvcountlist as $vplist){?>
        	<li><a href="<?php echo URL::webwen('question/'.$vplist->ask_id.'.shtml');?>"><?php echo $vplist->ask_name;?></a></li>
        <?php }?>
      </ul>
    </div>  
    <div class="floleft askrightimg ml40">
      <div class="ngask"><p>共解决
       <?php $askcount_length=strlen($askcount);
       for( $i=0;$i<$askcount_length;$i++ ){ echo '<span>'.mb_substr($askcount,$i,1).'</span>'?>
      <?php }?>个问题</p></div>
      <!--add go ask-->
      <div class="myquestion">
        <a class="myask" href="<?php echo url::webwen('ask/ask/getMyAsk?type=1');?>">我的提问<span>(<?php echo (isset($login_status) && $login_status)? $title_total : 0;?>)</span></a>
        <a class="myask" href="<?php echo url::webwen('ask/ask/getMyAsk?type=2');?>">我的回答<span>(<?php echo (isset($login_status) && $login_status)? $answer_total : 0;?>)</span></a>
        <a class="igoask" href="<?php echo url::webwen('ask/ask/addMyAsk');?>"></a>
      </div>
      
    </div>
  </div>
</div>
<div class="contentbottom clearfix">
    <div class="askcbottomleft floleft">
      <div class="problemleft">
      <h2>创业问题分类</h2>
      <ul class="clearfix">
      	<?php foreach($askIndustryOneArr as $k=>$v){?>
        	<li><a href="<?php echo URL::webwen($k.'/') ;?>"><?php echo $v;?></a></li>
        <?php }?>
      </ul>
      </div>
      <!-- 创业最多的项目 注释掉开始 -->
      <?php /*
      <div class="upprojects">
        <h2>创业最多的项目</h2>
        <ul class="projectslist">
        <?php foreach($projectlist as $v){?>
          <li>
              <p class="img"><label><a href="<?php echo urlbuilder::project($v['projectid']);?>" target="_blank" title="<?php echo $v['projectname'];?>">
              <img width="120px" height="80px" src="<?php echo $v['projectimage'];?>" alt="<?php echo $v['projectname'];?>"></a>
               </label></p>
              <a href="<?php echo urlbuilder::project($v['projectid']);?>" target="_blank" class="mt5 text"><?php echo $v['projectname'];?></a>
          </li>
          <?php }?>
        </ul>
      </div>
 	 */?>
 	 <!-- 创业最多的项目 注释掉结束 -->
 	 <!-- 首页文案开始 -->
 	 <div class="upprojects">
 	 	<p>一句话创业问答网是中国最好的创业、投资、加盟问答平台！为投资创业人员提供投资创业中各种难题的解答，创业问答网包含代理、加盟、批发、开店、投资、商机、买卖、致富等14个大类海量的创业问题，各种创业问题及热心网友的解答帮助创业投资者走向创业致富之路！</p>
 	 	<p>所有创业问答内容均由创业者提问和热心网友回答，欢迎广大网友在一句话创业问答网的所有问答分类提问或发表建议，我们将以热诚的态度为您提供优质服务！</p>
 	 	<p>这里有一群创业精英，欢迎提问、欢迎解答他人的提问。</p>
 	 </div>
 	 <!-- 首页文案结束 -->
    </div>
    <div class="askbottomz floleft">
      <div class="asktab">
      	<a href="javascript:;" class="on">待解决问题</a>
        <a href="javascript:;">已解决问题</a>
        
      </div>
      <div class="asktabbox">
       
        <div class="askcon" style="display:block;">
          <ul class="solveul">
          <?php foreach($notanswerlist as $v){//待解决问题?>
            <li>
              <span class="floright"><?php echo arr::get($v,'time','');?></span>
              <div class="solveul-p solveul-p1">
                <a href="<?php echo URL::webwen('question/'.arr::get($v,'id','').'.shtml');?>" title="<?php echo arr::get($v,'ask_name','');?>"><?php echo arr::get($v,'ask_name','');?></a> 
                <p><?php echo arr::get($v,'content','');?></p>
              </div>
            </li>
			<?php }?>
          </ul>
        </div>
         <div class="askcon">
          <ul class="solveul">
          <?php foreach($isanswerlist as $v){//已经解决问题?>
            <li>
              <span class="floright"><?php echo arr::get($v,'time','');?></span>
              <div class="solveul-p">
                <a href="<?php echo URL::webwen('question/'.arr::get($v,'id','').'.shtml');?>" title="<?php echo arr::get($v,'ask_name','');?>"><?php echo arr::get($v,'ask_name','');?></a> 
                <p><?php echo arr::get($v,'content','');?></p>
              </div>
            </li>
            <?php }?>
          </ul>
        </div>
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
        <h2>热门创业问题</h2>
        <ul class="entrlist">
          <?php foreach($pvlatest as $v1){?>
          	<li><a href="<?php echo URL::webwen('question/'.$v1->ask_id.'.shtml');?>" title="<?php echo $v1->ask_name;?>" target="_blank"><?php echo $v1->ask_name;?></a></li>
          <?php }?>
        </ul>
      </div>
    </div>
</div>
<!--中部结束-->