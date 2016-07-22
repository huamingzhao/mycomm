<input type="hidden" id="question_id" value="<?php echo $question_id;?>" />
<input type="hidden" id="talk_id" datatalk_id="<?php echo $talk_id;?>" value="<?php echo $talk_id;?>" />
<input type="hidden" value="1" class="pagevalue1">
<input type="hidden" value="1" class="pagevalue2">
<div class="sybinfo clearfix">
<?php  //print_r($question_info);
if($question_info){?>
  <div class="askbox wt680 fl">
    <div class="share fr mt25">
     <?php $question_href=urlbuilder::business_detail($question_id);
	   $share_content='这问题挺不错：{'.$question_info['title'].'}-{'.$question_info['talk_name'].'}（分享自 @1句话网）';
    ?>
	<a href="#" class="weibo" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo $question_href;?>&amp;title=<?php echo $share_content;?>');return false;"></a>
	<a href="#" class="qq ml5" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo $question_href;?>&amp;title=<?php echo $share_content;?>');return false;"></a>
	<a href="#" class="kongjian ml5" onclick="{ var _t = '<?php echo $share_content;?>';  var _url = '<?php echo $question_href;?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&amp;url='+_url+'&amp;appkey='+_appkey+'&amp;site='+_site; window.open( _u,'转播到腾讯微博');  };"></a>
	<a href="#" class="renren ml5"  onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo $question_href;?>&amp;description=<?php echo $share_content;?>');return false;"></a>
	</div>
    <div class="crumble mt25"><a href="<?php echo URL::webwen('');?>">疑惑</a> &gt; <a href="<?php echo urlbuilder::business_index($question_info['talk_id']);?>"><?php echo $question_info['talk_name'];?></a></div>
    <h1 class="mt15"><?php echo $question_info['title'];?></h1>
    <p class="mt15 askcon"><?php echo $question_info['content'];?></p>
    <div class="updatetimer fr mt30"><?php echo $question_info['pub_time']; ?><a target="_blank" href="http://www.yjh.com/help/fankui.html" class="jubao">举报</a></div>
    <div class="zanp mt30">
      <a href="javascript:;" class="zanpa1<?php if($question_info['is_nice']==1) echo ' zanpa1on';?> fl ">
         <span class="num mr5"><?php echo $question_info['nice_count'];?></span><span>同感</span>
      </a>
      <a href="javascript:;" class="fl<?php if($question_info['is_against']==1) echo ' zanpa2on';?> zanpa2">&nbsp;</a>
    </div>
  <?php }?>  
  <?php $answer_count=isset($count_answer_list['answer_count'])?$count_answer_list['answer_count']:0; ?>
    <div class="titleserch mt30">
      <h3><?php echo $answer_count;?>个解答</h3>
      <span>排序：<a class="on" href="javascript:;">票数</a><a href="javascript:;" class="sorttime">时间</a>
      </span>
    </div>
    <div class="mt20 answerlistbox">
    <ul class="answerlist">
    <?php if($count_answer_list['count']>0) {?>
    <?php $data_info=$count_answer_list['data']; //d($count_answer_list);
		  foreach($data_info as $key=>$value)
		  {
				$question_id=$value['question_id'];
				$detail_href=urlbuilder::business_detail($question_id);
				$answer_user_id=$value['answer_user_id'];
				$answer_user_href=urlbuilder::business_userinfo($value['answer_user_id']);
			?>
      <li class="clearfix mt20">
        <div class="fl zancaibox" answer_id="<?php echo $value['answer_id'];?>">
          <a href="javascript:;" class="zan<?php if($value['is_nice']==1) echo ' zanon';?>"><?php echo $value['nice_count'];?><var>赞同回复<i></i></var></a>
          <a href="javascript:;" class="cai<?php if($value['is_against']==1) echo ' caion';?> mt5">&nbsp;<var>对回复持反对意见<i></i></var></a>
        </div>
        <div class="fl ml10 answerbox">
          <h3><a href="<?php echo $answer_user_href;?>"><?php echo $value['answer_user_name'];?></a></h3>
          <?php $nice_user_info=$value['nice_user_info'];
                $nice_user_count=count($nice_user_info);
                $three_nice_user=$all_nice_user='';
                $sum_nice_user_count=0;
                if($nice_user_count>0)
                {
	                for($item=0;$item<$nice_user_count;$item++)
	                {
	                	$nice_user_href=urlbuilder::business_userinfo($nice_user_info[$item]['user_id']);
	                	$str='<a href="'.$nice_user_href.'">'.$nice_user_info[$item]['user_name'].'</a>';
	                	if($item<3)
	                	{
	                		$three_nice_user.=$str;
	                	}
	                	$all_nice_user.=$str;
                    $sum_nice_user_count++;
	                }
                }
             ?>
          <p class="zanrenabox mt10">
          <?php if(!empty($three_nice_user)) {?>
          <?php echo $three_nice_user;?>
          <?php if($sum_nice_user_count>3) { ?>
          <a href="javascript:;" class="zantongbtn">等人赞同</a>
          <?php } else { echo '赞同';}?>	
          <?php }?>
           </p>
          <p class="zanrenabox mt10" style="display:none;"><?php if(!empty($all_nice_user)) {?><?php echo $all_nice_user;?><?php }?></p>
          <p class="mt10 wt640"><?php echo htmlspecialchars_decode($value['content']);?></p>
          <p class="mt20 c999"><?php echo $value['pub_time'];?></p>
        </div>
        <a href="<?php echo $answer_user_href;?>" class="userphotoa">
          <img width="37" height="30" src="<?php echo URL::imgurl($value['answer_user_photo']);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')" />
          </a>
      </li>
      <?php }?>
       <?php }?>
    </ul>
    <ul class="answerlist" style="display:none;">	
    <?php if($new_answer_list['count']>0) {?>
    <?php $data_info=$new_answer_list['data']; //d($data_info);
      foreach($data_info as $key=>$value)
      {
        $question_id=$value['question_id'];
        $detail_href=urlbuilder::business_detail($question_id);
        $answer_user_id=$value['answer_user_id'];
        $answer_user_href=urlbuilder::business_userinfo($value['answer_user_id']);
      ?>
      <li class="clearfix mt20">
        <div class="fl zancaibox" answer_id="<?php echo $value['answer_id'];?>">
          <a href="javascript:;" class="zan<?php if($value['is_nice']==1) echo ' zanon';?>"><?php echo $value['nice_count'];?></a>
          <a href="javascript:;" class="cai<?php if($value['is_against']==1) echo ' caion';?> mt5">&nbsp;</a>
        </div>
        <div class="fl ml10 answerbox">
          <h3><a href="<?php echo $answer_user_href;?>"><?php echo $value['answer_user_name'];?></a></h3>
          <?php $nice_user_info=$value['nice_user_info'];
                $nice_user_count=count($nice_user_info);
                $three_nice_user=$all_nice_user='';
                $sum_nice_user_count=0;
                if($nice_user_count>0)
                {
                  for($item=0;$item<$nice_user_count;$item++)
                  {
                    $nice_user_href=urlbuilder::business_userinfo($nice_user_info[$item]['user_id']);
                    $str='<a href="'.$nice_user_href.'">'.$nice_user_info[$item]['user_name'].'</a>';
                    if($item<3)
                    {
                      $three_nice_user.=$str;
                    }
                    $all_nice_user.=$str;
                    $sum_nice_user_count++;
                  }
                }
             ?>
          <p class="zanrenabox mt10">
          <?php if(!empty($three_nice_user)) {?>
          	<?php echo $three_nice_user;?>
          	<?php if($sum_nice_user_count>3) { ?>
          		<a href="javascript:;" class="zantongbtn">等人赞同</a>
          	<?php } else { echo '赞同';}?>	
          <?php }?>
          </p>
          <p class="zanrenabox mt10" style="display:none;"><?php if(!empty($all_nice_user)) {?><?php echo $all_nice_user;?><?php }?></p>
          <p class="mt10 wt640"><?php echo $value['content'];?></p>
          <p class="mt20 c999"><?php echo $value['pub_time'];?></p>
        </div>
        <a href="<?php echo $answer_user_href;?>" class="userphotoa">
          <img width="37" height="30" src="<?php echo URL::imgurl($value['answer_user_photo']);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')" />
          </a>
      </li>
      <?php }?>
       <?php }?>
    </ul>
   <?php if(!empty($count_answer_list['has_next_page'])) {?>
      <p class="more mt15">更多</p>
    <?php }?>
    <?php if(!empty($new_answer_list['has_next_page'])) {?>
      <p class="more mt15" style="display:none;">更多</p>
    <?php }?>
    </div>
    <?php $login_user_href='';
         if(!empty($login_user_id) && !empty($login_user_name))
    	 {
    	 	$login_user_href=urlbuilder::business_userinfo($login_user_id);
    	} ?>
    <div class="mt35 tiwenbox">
      <p style="color: #222; height: 30px; line-height: 30px;margin-bottom: 10px;"><?php if(!empty($login_user_href) && !empty($login_user_name)) {?>
        <a href="<?php echo $login_user_href;?>" class="username"><?php echo $login_user_name;?></a> <?php if(!empty($login_user_sign)) echo ','.$login_user_sign;?>
        <?php }?></p>
        <p class="tiwentishi">已超出<var>465</var>字</p>
        <?php echo  Editor::factory("","business",array("field_name"=>'message',"width"=>"580","height"=>"200"));?>
      <!--<textarea placeholder="写下你的解答意见" style="color:#999;" value="" name="message" class="answertext mt10"></textarea>-->
      <p class="detailtishi mt5">还可以输入<var>5</var>字</p>
      <div class="clearfix mt10">
        <p class="fr releasebox">
          <a class="ml30" href="javascript:;">发布解答</a>
        </p>
        <!-- <input type="checkbox" id="checkboxni"><label for="checkboxni" class="ml5">匿名</label> -->
      </div>
       <?php if(!empty($login_user_href) && !empty($login_user_name)) {?>
      <a href="<?php echo $login_user_href;?>" class="userphotoa"><img width="37" height="30" src="<?php echo URL::imgurl($login_user_photo);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')"></a>
      <?php }?>
      </div>
  </div>
  <div class="fl mt80 sybinforight wt235 ml20">
   <?php if($question_user_info){?>
    <h3 class="fz14">发起人</h3>
    <a href="<?php echo urlbuilder::business_userinfo($question_user_info['user_id']); ?>">
    <img class="mt20" src="<?php echo URL::imgurl($question_user_info['user_photo']);?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')" width="100" height="80"/>
    </a>
    <p class="fz16 mt15"><?php echo $question_user_info['user_name'];?></p>
    <?php if(isset($question_user_info['user_sign'])) {?><p class="c666 mt10"><?php echo $question_user_info['user_sign'];?></p><?php }?>
     <?php }?>
    <?php if($the_same_question['count']>0) {?>
    <div class="relatedissues">
      <h3 class='fz14'>相关问题</h3>
      <ul class="mt15 relatelist">
      <?php $data_info=$the_same_question['data'];
	       foreach($data_info as $key=>$value)
			{
				$question_id=$value['id'];
         		$detail_href=urlbuilder::business_detail($question_id);
         	?>
       			 <li><a href="<?php echo $detail_href;?>"><?php echo $value['title']?></a></li>
        <?php }?>
      </ul>
    </div>
    <?php }?>
    <?php if($question_info){?>
    <div class="problemstate">
      <h3 class="fz14">问题状态</h3>
      <p>最新活跃 :<var><?php echo  $question_info['last_update_time'];?></var></p>
      <p>浏览 :<var><?php echo $question_info['view_count'];?></var></p>
    </div>
    <?php }?>
  </div>
</div>
<div class="answerssuccess">解答成功</div>
<?php echo URL::webjs("detail.js")?>
