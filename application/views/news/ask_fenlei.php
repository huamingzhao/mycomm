<!--中部开始-->
<?php echo URL::webcss("ask.css")?>
<?php echo URL::webjs('ask.js')?>

<div class="asknavwrap">
  <div class="asknav"><span>当前位置：</span>
  <a href="<?php echo urlbuilder::rootDir('');?>">一句话网</a>
  <span> > </span>
  <a href="<?php echo url::webwen('');?>" >创业问答</a>
  <span> > </span>
  <a <?php if($choseid == arr::get($askfenleilist,'one_id','1')){echo 'class="lastalink"';}?> href="<?php echo url::webwen(arr::get(Service_News_Ask::getAskIndustryOneArrById(),arr::get($askfenleilist,'one_id','1')).'/');?>" ><?php echo arr::get($askfenleilist,'one_name','');?></a>
  
  <?php if($choseid != arr::get($askfenleilist,'one_id','1')){?>
   <span> > </span>
  <a href="<?php echo url::webwen(arr::get(Service_News_Ask::getAskIndustryOneArrById(),arr::get($askfenleilist,'one_id','1')).'/'.$choseid.'/');?>" class="lastalink"><?php echo $choseidname;?></a>
  <?php }?>
  </div>
</div>

<div class="contenttop clearfix">
  <div class="floleft ">
    <div class="einteilungtitle clearfix">
    <?php if(arr::get($askfenleilist,'one_id')==$choseid){//有h1标签?>
       <h1>
        <a class="floleft h1" href="<?php echo URL::webwen(arr::get(Service_News_Ask::getAskIndustryOneArrById(),arr::get($askfenleilist,'one_id','1')).'/');?>"><?php echo arr::get($askfenleilist,'one_name','');?></a>
       </h1>
    <?php }else{?>
     	<a class="floleft h1" href="<?php echo URL::webwen(arr::get(Service_News_Ask::getAskIndustryOneArrById(),arr::get($askfenleilist,'one_id','1')).'/');?>"><?php echo arr::get($askfenleilist,'one_name','');?></a>
    <?php }?>
      
      <div class="floleft einteilungabox">
      <?php if(isset($askfenleilist['two']) && count($askfenleilist['two'])){?>
      		<?php foreach($askfenleilist['two'] as $v){?>
      	   <?php if($choseid==arr::get($v,'two_id')){?>
          <h1 class="h1a">
          	<a  href="<?php echo URL::webwen(arr::get(Service_News_Ask::getAskIndustryOneArrById(),arr::get($askfenleilist,'one_id','1')).'/'.arr::get($v,'two_id',101).'/') ;?>"  class="on"><?php echo arr::get($v,'two_name');?></a>
		  </h1>
		  <?php }else{?>
          	<a  href="<?php echo URL::webwen(arr::get(Service_News_Ask::getAskIndustryOneArrById(),arr::get($askfenleilist,'one_id','1')).'/'.arr::get($v,'two_id',101).'/') ;?>" ><?php echo arr::get($v,'two_name');?></a>
      <?php }}}?>
      </div>
    </div>
    <div class="ganggaobg">
    <?php echo $seoContent;?>
    </div>
    <div class="mt10">
      <div class="asktab">
        <a href="javascript:;" <?php if($pagetype==1){echo 'class="on"';}?>>全部</a>
        <a href="javascript:;"  <?php if($pagetype==2){echo 'class="on"';}?>>已解决问题</a>
        <a href="javascript:;"  <?php if($pagetype==3){echo 'class="on"';}?>>待解决问题</a>
      </div>
      <div class="asktabbox wt700">
        <div class="askcon solveul" <?php if($pagetype==1){echo 'style="display:block;"';}?>>
          <table cellspacing="0" cellpadding="0">
            <tr>
              <th><p class="wt520">标题</p></th>
              <th><p class="wt50">回答数</p></th>
              <th><p class="wt110">提问时间</p></th>
            </tr>
            
            <?php foreach($allasklist as $v1){?>
            <tr>
              <td>
                <div class="wt520 solveul-p <?php if( $v1->ask_adopt_type==0){echo 'solveul-p1';}?>">
                <a title="<?php echo $v1->ask_name?>" href="<?php echo URL::webwen('question/'.$v1->ask_id.'.shtml');?>"><?php echo $v1->ask_name;?></a> 
              </div>
              </td>
              <td><p class="wt50 c999"><?php echo $v1->ask_answer_count?></p></td>
              <td><p class="wt110 c999"><?php echo date("Y-m-j",$v1->add_time);?></p></td>
              </tr>
              <?php }?>
          </table>
          <div class="ryl_search_result_page" style="margin-bottom: 20px">
				<?php echo $allasklist_page;?>
          </div>
			
        </div>
        <div class="askcon solveul" <?php if($pagetype==2){echo 'style="display:block;"';}?>>
          <table cellspacing="0" cellpadding="0" >
            <tr>
              <th><p class="wt520">标题</p></th>
              <th><p class="wt50">回答数</p></th>
              <th><p class="wt110">提问时间</p></th>
            </tr>
            <?php foreach($isadoptlist as $v2){?>
            <tr>
              <td>
                <div class="wt520 solveul-p">
                <a title="<?php echo $v2->ask_name?>" href="<?php echo URL::webwen('question/'.$v2->ask_id.'.shtml');?>"><?php echo $v2->ask_name;?></a> 
              </div>
              </td>
              <td><p class="wt50 c999"><?php echo $v2->ask_answer_count?></p></td>
              <td><p class="wt110 c999"><?php echo date("Y-m-j",$v2->add_time);?></p></td>
              </tr>
              <?php }?>
          </table>
          <div class="ryl_search_result_page">
				<?php echo $isadoptlist_page;?>
          </div>
        </div>
        <div class="askcon solveul" <?php if($pagetype==3){echo 'style="display:block;"';}?>>
          <table cellspacing="0" cellpadding="0" >
            <tr>
              <th><p class="wt520">标题</p></th>
               <th><p class="wt50">回答数</p></th>
              <th><p class="wt110">提问时间</p></th>
            </tr>
            <?php foreach($notadoptlist as $v3){?>
            <tr>
              <td>
                <div class="wt520 solveul-p  solveul-p1">
                <a title="<?php echo $v3->ask_name?>" href="<?php echo URL::webwen('question/'.$v3->ask_id.'.shtml');?>"><?php echo $v3->ask_name;?></a> 
              </div>
              </td>
              <td><p class="wt50 c999"><?php echo $v3->ask_answer_count?></p></td>
              <td><p class="wt110 c999"><?php echo date("Y-m-j",$v3->add_time);?></p></td>
              </tr>
              <?php }?>
          </table>
          <div class="ryl_search_result_page">
				<?php echo $notadoptlist_page;?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- 右上角项目推荐注释掉开始 -->
  <?php /*
  <div class="floright askannouncement width248">
    <h2>最好的<?php echo arr::get($askfenleilist,'one_name','赚钱');?>项目</h2>
    <div class="askimgbox">
     <p class="img">
      <label>
      <a target="_blank" href="<?php echo urlbuilder::project(arr::get($projectinfo,'0'));?>" title="<?php echo arr::get($projectinfo,'1');?>"><img src="<?php echo arr::get($projectinfo,'2');?>" alt="<?php echo arr::get($projectinfo,'1');?>"></a>
      </label>
      </p>
      <a target="_blank" href="<?php echo urlbuilder::project(arr::get($projectinfo,'0'));?>" class="atitle text" title="<?php echo arr::get($projectinfo,'1');?>"><?php echo arr::get($projectinfo,'1');?></a>
    </div>   
  </div>
 */?>
 <!-- 右上角项目推荐注释掉结束 -->
  <div class="askbottomright floright">
      <div class="askannouncement">
        <h2>问答频道公告</h2>
        <ul class="announcementlist">
          <li><span>1</span><a target="_blank" href="<?php echo url::webwen('gonggao/102.shtml');?>">企业如何加盟一句话创业网？</a></li>
          <li><span>2</span><a target="_blank" href="<?php echo url::webwen('gonggao/101.shtml');?>">在问答频道发创业问题的好处有哪些？</a></li>
        </ul>
      </div>
      <div class="entrepreneurship">
        <h2><?php echo $push_ask_list['industry_name'];?>问题推荐</h2>
        <ul class="entrlist">
          <?php 
          	if(!empty($push_ask_list['list'])){
				foreach($push_ask_list['list'] as $value){
          ?>
          <li><a title="<?php echo arr::get($value,'1','');?>" href="<?php echo url::webwen('question/'.arr::get($value,'0','').'.shtml')?>"><?php echo arr::get($value,'1','');?></a></li>
          <?php }}?>
        </ul>
      </div>
      <!-- 知识推荐注释开始 -->
      <?php /*
      <div class="entrepreneurship">
        <h2><?php echo $choseidname;?>知识</h2>
        <ul class="entrlist">
        <?php if(count($push_zhishi_list)){?>
            <?php foreach($push_zhishi_list as $v_1){?>
          	<li><a href="<?php echo zxurlbuilder::zixuninfo(arr::get($v_1,'id',''),date("Ym",arr::get($v_1,'article_addtime',null)));?>" title="<?php echo arr::get($v_1,'name','')?>"><?php echo arr::get($v_1,'name','')?></a></li>
		<?php }}?>
          
        </ul>
      </div>
      */ ?>
      <!-- 知识推荐注释结束 -->
  </div>
</div>

<!--中部结束-->