<?php echo URL::webcss("platform/lottery4.css?version=1")?>
<?php echo URL::webjs("jquery.cookies.2.2.0.js")?>
<!-- 内容开始 -->
<div class="lottery4Banner clearfix">
	<div class="banner1"></div>
	<div class="banner2"></div>
	<div class="banner3">
		<div class="loginabsolte">
			<?php if(!$isLogin){?>
			<p>您还未登录，请先<a href="<?php echo urlbuilder::geren("denglu");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>" class="lottery3_btnred" title="登录"><img src="<?php echo URL::webstatic('/images/event/lottery4/loginbtn.png');?>"></a>如您还没有帐号，请先<a href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>" class="lottery3_btnyellow" title="注册"><img src="<?php echo URL::webstatic('/images/event/lottery4/regbtn.png');?>"></a></p>
			<?php }elseif(count($temp_id) > 0){?>
			<p>您本次参加抽奖活动编号是&nbsp;<i><?php echo $temp_id['temp_id'];?></i>，该编号用于抽取<var>mini iPad&nbsp;大奖&nbsp;</var>使用</p>
			<?php }elseif($user_type == 2){?>
			<p id="lottery_id_info">请领取你抽&nbsp;<var>mini iPad&nbsp;大奖&nbsp;</var> 的编号<a href="javascript:;" id="lottery_getId"><img src="<?php echo URL::webstatic('/images/event/lottery4/ljlq.png');?>"></a></p>
			<?php }else{?>
			<p>企业用户不参加本次抽奖</p>
			<?php }?>
		</div>
		<div class="perc">当前正在参加本次抽奖活动的人数为<span><?php echo $countPeople;?></span>人 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 当前累计完成抽奖次数为<span><?php echo $countRoulette;?></span>次</div>
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="962" height="473" id="flashrek2">
         <param name="movie" value="<?php echo URL::webstatic('flash/wheel_1402th.swf')?>">        
         <param name="quality" value="high">        
         <param name="wmode" value="transparent">        
         <param name="allowScriptAccess" value="always">        
         <embed src="<?php echo URL::webstatic('flash/wheel_1402th.swf')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="962" height="473" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash">
      </object>
	</div>	
</div>
<div class="lottery4Main">
	<h3><img width="124" height="50" src="<?php echo URL::webstatic('/images/event/lottery4/lottery4_07.png');?>" alt="奖品预览"/></h3>
	<div class="lottery4wrap">
		<ul class="lottery4Prize clearfix"data-direction="horizontal">
		      <li>
            <img src="<?php echo URL::webstatic('/images/event/lottery4/j1.png');?>" alt="mini iPad">
            <span>mini iPad</span>
          </li>
          <li>
            <img src="<?php echo URL::webstatic('/images/event/lottery4/j8.png');?>" alt="创业币">
            <span>10创业币</span>
          </li>
          <li>
            <img src="<?php echo URL::webstatic('/images/event/lottery4/j7.png');?>" alt="小虫手机">
            <span>小虫手机</span>
          </li>
          <li>
            <img src="<?php echo URL::webstatic('/images/event/lottery4/j6.png');?>" alt="创业币">
            <span>20创业币</span>
          </li>
          <li>
            <img src="<?php echo URL::webstatic('/images/event/lottery4/j3.png');?>" alt="瑞士十字电脑双肩包">
            <span>瑞士十字电脑双肩包</span>
          </li>
          <li>
            <img src="<?php echo URL::webstatic('/images/event/lottery4/j10.png');?>" alt="创业币">
            <span>50创业币</span>
          </li>
          <li>
            <img src="<?php echo URL::webstatic('/images/event/lottery4/j4.png');?>" alt="话费">
            <span>20元话费</span>
          </li>
          <li>
            <img src="<?php echo URL::webstatic('/images/event/lottery4/j2.png');?>" alt="创业币">
            <span>80创业币</span>
          </li>
          <li>
            <img src="<?php echo URL::webstatic('/images/event/lottery4/j9.png');?>" alt="话费">
            <span>10元话费</span>
          </li>
          <li>
            <img src="<?php echo URL::webstatic('/images/event/lottery4/j5.png');?>" alt="创业币">
            <span>100创业币</span>
          </li>
          
          
          
          
		</ul>
	</div>
	<div class="lottery4_end">
    	<h3>活动已结束</h3>
    	<p style="margin-top: 35px;">2014年04月25日上证指数收盘数为：<var>2036.52</var>，详情请查阅相关股票网站</p>
    	<p>截止当前正在参加本次抽奖活动的人数为<var>5065</var>人，</p>
    	<p>通过抽奖规则计算出中 <var class="fz20">mini iPad</var> 大奖 得主的编号是：<var>3901</var>，</p>    	
  		<p>恭喜来自江苏地区手机号码为<var class="fz20">189****5054</var>用户喜获一句话网站抽奖活动第四期一等奖！</p>
    	
  	</div>
	<!--  
  	<?php if($szlist){?>
	<div class="lottery4_end" style="display:none;">
    	<h3>活动已结束</h3>
    	<p style="margin-top: 35px;">2014年04月25日上证指数收盘数为：<var><?php echo isset($szlist['sz']) ? $szlist['sz'] : '';?></var>，详情请查阅相关股票网站</p>
    	<p>截止当前正在参加本次抽奖活动的人数为<var><?php echo isset($szlist['people']) ? $szlist['people'] : 0;?></var>人，</p>
    	<p>通过抽奖规则计算出中 <var class="fz20">mini iPad</var> 大奖 得主的编号是：<var><?php echo isset($szlist['lucky_id']) ? $szlist['lucky_id'] : 0;?></var>，</p>
    	<?php if(isset($szlist['mobile']) && $szlist['mobile'] != ''){ ?>
    	<?php if(isset($szlist['name']) && $szlist['name'] != ''){?>
    	<p>恭喜<var class="fz20"><?php echo $szlist['name'];?></var>(手机号码为<var class="fz20"><?php echo $szlist['mobile'];?></var> ) 喜获一句话网站抽奖活动第四期一等奖！</p>
  		<?php }else{?>
  		<p>恭喜手机号码为<var class="fz20"><?php echo $szlist['mobile'];?></var>用户喜获一句话网站抽奖活动第四期一等奖！</p>
    	<?php }?>
  		<?php }?>
  	</div>
  	<?php }?>
  	-->
	<div class="lottery4_info">
        <div class="mtatuo">
        <font style="font-size:30px; font-weight: bold; text-align: center; padding:0;">活动资格</font>
        <font style="font-size:24px; font-weight: bold; margin-top: 30px;">活动时间：2014年2月26日——4月25日</font>
        <font style="font-size:14px; margin-top:12px;">1、注册成为一句话商机速配网会员的网友，请第一时间绑定并验证你的手机号，否则无法参与抽奖活动；</font>
        <font style="font-size:14px; margin-top:12px;">2、老会员，请登录一句话商机速配网站，并在当前活动页面领取您本期抽 mini iPad大奖的编号；</font>
        <dl class="share_button">
    		<dt>+分享到</dt>
            <dd><a href="#" title="QQ空间" onclick="javascript:share('qq_zone',0);"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_03.png');?>"></a></dd>
            <dd><a href="#" title="新浪微博" onclick="javascript:share('sina',0);"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_05.png');?>"></a></dd>
            <dd><a href="#" title="腾讯微博" onclick="javascript:share('qq',0);"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_07.png');?>"></a></dd>
            <dd><a href="#" title="人人网" onclick="javascript:share('renren',0);"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_09.png');?>"></a></dd>
            <dd><a href="#" title="豆瓣" onclick="javascript:share('douban',0)"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_11.png');?>"></a></dd>
        </dl>
        </div>
    </div>
    <dl class="lottery4Wainner">
        <dt>一等奖抽奖规则</dt>
        <dd>
          <p class="lh40">为公平公正原则，一句话创业网会员抽奖活动用2014年4月25日 <var>上证指数</var> 为基数作为抽奖一个因子，去乘以<var>10000</var> ，再除以参加 
<var>本次抽奖活动的总人数</var>，结果 <var>取余数+1</var> 即为本期一等奖中奖编号！</p>
          <p class="lh40">比如：
本期共有 <var>3000个会员</var> 参与抽奖，2014年4月25日上证收盘指数为 <var>2233.41</var>，按照上面的 <var>mini iPad大奖</var> 抽奖规则，计算出中奖编号为<var>:10000*2233.41/3000</var> 得到余数 2100，即中奖编号为：<var>2101的网友</var> 获得本次抽奖活动 <var>mini iPad大奖</var>
</p><p class="lh40">
请每位会员牢记自己的 <var>抽奖编号</var>，2014年4月25日下午16:00时，每位会员都可以根据网站上的参加抽奖人员数量、2014年4月25日上证指数来计算自己是否中一等奖？</p>
        </dd>
    </dl>
    <dl class="lottery4Wainner lottery4Wainnerbg">
        <dt>活动规则</dt>
        <dd>
          <div class="marginauto">
            <p class="clearfix">
              <span>抽中<var>“小虫手机”</var>的，一句话商机速配平台短信通知您领取 小虫手机 一个。</span>
            </p>
            <p>
              <span class="wt470">抽中<var>“瑞士十字电脑背包”</var>的，一句话商机速配平台短信通知您领取 电脑包 一个。</span>
              <span>抽中<var>“80元创业币”</var>的，平台自动发 80创业币 至您的一句话账号。</span>
            </p>
              <p class="clearfix">
                <span class="wt470">抽中<var>“20元话费”</var>的，一句话商机速配平台短信通知您,我们将尽快充值 20元话费。</span>
            <span>抽中<var>“50元创业币”</var>的，平台自动发 50创业币 至您的一句话账号。</span>
            </p>
            <p class="clearfix">
              <span class="wt470">抽中<var>“10元话费”</var>的，一句话商机速配平台短信通知您，我们将尽快充值 10元话费。</span>
              <span>抽中<var>“20元创业币”</var>的，平台自动发 20创业币 至您的一句话账号。</span>
            </p>
            <p class="clearfix">
              <span class="wt470">抽中<var>“100元创业币”</var>的，一句话商机速配平台自动发 100创业币 至您的一句话账号。</span><span>抽中<var>“10元创业币”</var>的，平台自动发 10创业币 至您的一句话账号。</span>
            </p>
            <p class="fz14" style="margin-top:28px;"> 1、如果连续10次无法电话联系到中奖者，则自动取消其获奖资格，奖品重新进入奖池供其他网友抽奖,最终解释权归一句话商机速配网所有。</p>
            <p class="fz14">
                2、 对于抽中手机话费充值的会员，系统将在活动结束时自动对中奖用户验证的手机进行充值，请勿在活动期间取消、更换验证手机号码，否则自动取消中奖机会！
            </p>
          </div>
        </dd>
    </dl>
    <dl class="lottery4Wainner lottery4bg3">
        <dt>什么是创业币</dt>
        <dd>
          <div class="cybwrap">
            <p style="font-size: 18px;"><img src="<?php echo URL::webstatic('/images/event/lottery4/1.png');?>">10创业币=<img src="<?php echo URL::webstatic('/images/event/lottery4/2.png');?>">1人民币</p>
            <p class="fz14 lh32" style="margin-top:20px;">1.创业币是一句话网站针对网站会员推出的一种虚拟货币；</p><p class="fz14 lh32">
2.创业币主要通过参加一句话网站内的活动来获得；</p><p class="fz14 lh32 lh32">
3.创业者在一句话网站平台进行项目投资时，可以使用所获得的创业币，直接抵扣带有<img src="<?php echo URL::webstatic('/images/event/lottery4/3.png');?>">标志的项目的投资款；</p><p class="fz14 lh32">
4.一句话网站力求通过创业币机制真正帮助到那些资金不足或没有资金的创业者；</p><p class="fz14 lh32">
5.加盟一句话网站项目，每个创业者最多能兑现20万创业币创业。 </p>
          </div>
        </dd>
    </dl>
    <dl class="lottery4Wainner lottery4bg4">
        <dt>第三期中奖名单</dt>
        <dd>
          <div style="width: 630px; margin:0 auto; font-size: 24px; text-align: center"><p>恭喜来自北京的<var>徐瑞龙</var> (手机号码为158****6305) </p><p>
喜获一句话网站第三期抽奖活动 <var>mini iPad</var> 大奖！</p></div>
          <ul style="width: 800px; margin:0 auto;text-align: center">
            <li class="lottery_winners_header">
              <ul class="clearfix">
                <li style="width: 120px;">姓名</li>
                <li style="width: 170px;">手机</li>
                <li style="width: 110px;">奖品</li>
                <li style="width: 120px;">姓名</li>
                <li style="width: 170px;">手机</li>
                <li style="width: 110px;">奖品</li>
              </ul>
            </li>            
            <li class="lottery_winners_list">
              <ul id="lottery_winners_data" class="clearfix" style="top: 0px;">
                <?php if($big_prize_list){?>
	            <?php foreach($big_prize_list as $v){?>
                <li style="width: 120px;"><?php echo $v['name'] != '' ? $v['name'] : '佚名';?></li>
                <li style="width: 170px;"><?php echo $v['mobile'];?></li>
                <li style="width: 110px;">
                  <?php echo $v['prize'];?>
                </li>
                <?php }?>
              	<?php }?> 
              </ul>
            </li>
          </ul>
        </dd>
    </dl>
    <h3 class="titleh3"><img src="<?php echo URL::webstatic('/images/event/lottery4/wqhd.png');?>"></h3>
    <ul class="ullist clearfix">
        <li style="margin:0 0 0 1px;"><a href="<?php echo URL::website('/zt3/zhuce.shtml')?>" target="_blank" title="第三期抽奖页面"><img src="<?php echo URL::webstatic('/images/event/lottery4/a1.png');?>"></a></li>
        <li><a href="<?php echo URL::website('/zt2/zhuce.shtml')?>" target="_blank" title="第二期抽奖页面"><img src="<?php echo URL::webstatic('/images/event/lottery4/a2.png');?>"></a></li>
        <li><a href="<?php echo URL::website('/zt/zhuce.shtml')?>" target="_blank" title="第一期抽奖页面"><img src="<?php echo URL::webstatic('/images/event/lottery4/a3.png');?>"></a></li>
    </ul>
    <h3 class="titleh3"><img src="<?php echo URL::webstatic('/images/event/lottery4/hjgy.png');?>"></h3>
    <div class="content lottery3_history_photos">
    	<div class="lottery3_photos_list">
        <div class="lottery3_photos_list_div">
        <ul class="clearfix lottery_list_scroll" data-direction="horizontal" id="lottery_list_scroll0">
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery4/r1.jpg')?>" alt="徐瑞龙" width="170" height="190">
              <span class="clearfix">
                <font class="name">徐瑞龙</font>
                <i class="time">2013-1-22</i>
              </span>
              <span>运气刚刚的，一不留神居然中大奖了（mini iPad）</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery4/r6.jpg')?>" alt="施磊" width="170" height="190">
              <span class="clearfix">
                <font class="name">施磊</font>
                <i class="time">2013-12-21</i>
              </span>
              <span>功夫不负有心人，终于抽中了梦想中的电脑双肩包</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery4/r2.jpg')?>" alt="陈雪" width="170" height="190">
              <span class="clearfix">
                <font class="name">陈雪</font>
                <i class="time">2013-12-25</i>
              </span>
              <span>正等着交话费呢，居然抽中了，不用交话费了，高兴！</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery4/r3.jpg')?>" alt="魏小燕" width="170" height="190">
              <span class="clearfix">
                <font class="name">魏小燕</font>
                <i class="time">2013-11-28</i>
              </span>
              <span>朋友介绍，半信半疑，第一次抽就中了ipod，嘻嘻！</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery4/r4.jpg')?>" alt="陈欣" width="170" height="190">
              <span class="clearfix">
                <font class="name">陈欣</font>
                <i class="time">2013-1-13</i>
              </span>
              <span>电脑双肩包，不错，旅游登山正好派上用场。</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery4/r5.jpg')?>" alt="廖建勇" width="170" height="190">
              <span class="clearfix">
                <font class="name">廖建勇</font>
                <i class="time">2013-12-20</i>
              </span>
              <span>ipod，欧耶，女友生日礼物有了，嘿嘿！</span>
            </li></ul>
        </div>        	
        </div>
    </div>
</div>
<div class="lottery4Float">
	<img class="imgblock" src="<?php echo URL::webstatic('/images/event/lottery4/lottery4_float_03.png')?>">
   	<div class="lottery4FloatMain">
   		<p>1.每邀请好友成功注册（指手机号码验证通过）1人，您将获赠1次额外的抽奖机会。</p>
  		<a href="<?php echo URL::website('/person/member/huodong/showInviteFriends') ?>" target="_blank" title="去邀请">去邀请</a>
 		<p class="line"></p>
     	<p>2.给意向投资项目每投递一张名片，您将获赠一次额外的抽奖机会。（获赠抽奖机会每天仅限投递1张名片有效，重复投递无效）</p>
     	<a href="<?php echo URL::website('')?>" target="_blank" title="找项目">找项目</a>
  	</div>
   	<img class="imgblock" src="<?php echo URL::webstatic('/images/event/lottery4/lottery4_float_07.png')?>">
	<?php if($guess_like_list){?>
   	<dl class="lottery3_projects">
  		<dt> 您可能会喜欢的项目</dt>
   		<?php foreach($guess_like_list as $val){?>
    	<dd>
       	<a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>">
	      	<img width="120" height="95" onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>" src="<?=URL::imgurl($val['project_logo']);?>" />
	      	<span><?php echo mb_substr($val['project_brand_name'], 0,8,'UTF-8')."";?></span>
       	</a>
       	</dd>   
       	<?php }?>       
   	</dl>
   	<?php }?>
</div>
<!-- 内容结束 -->
<script>
function share(str,id){
	var name = '';
	var image = '';
	if(id == 1){
		name = '小虫手机';
		image = '<?= URL::webstatic("/images/event/lottery4/j7.png");?>';
	}else if(id == 2){
		name = '电脑双肩包';
		image = '<?= URL::webstatic("/images/event/lottery4/j3.png");?>';
	}else if(id == 4){
		name = '20元话费';
		image = '<?= URL::webstatic("/images/event/lottery4/j4.png");?>';
	}else if(id == 5){
		name = '10元话费';
		image = '<?= URL::webstatic("/images/event/lottery4/j9.png");?>';
	}else if(id == 6){
		name = '100创业币';
		image = '<?= URL::webstatic("/images/event/lottery4/j5.png");?>';
	}else if(id == 7){
		name = '80创业币';
		image = '<?= URL::webstatic("/images/event/lottery4/j2.png");?>';
	}else if(id == 8){
		name = '50创业币';
		image = '<?= URL::webstatic("/images/event/lottery4/j10.png");?>';
	}else if(id == 9){
		name = '20创业币';
		image = '<?= URL::webstatic("/images/event/lottery4/j6.png");?>';
	}else if(id == 10){
		name = '10创业币';
		image = '<?= URL::webstatic("/images/event/lottery4/j8.png");?>';
	}
	if(str == 'qq_zone'){
		if(id != 0){
			window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=http://www.yjh.com/zt4/zhuce.shtml?sid%3Dd24f7cf394344ffa0d3995e941b485b3_273&title=今天我参加一句话商机网举办【第四期】会员注册有奖活动，我抽中了'+name+'，很开心，晒晒我的幸福时刻！亲们，你们也快来参与吧！&pics='+image);return false;
		}else{
			window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=http://www.yjh.com/zt4/zhuce.shtml?sid%3Dd24f7cf394344ffa0d3995e941b485b3_273&title=2014年2月26日—2014年4月25日一句话商机网举办【第四期】会员注册有奖活动，1000万创业币、2万话费、iPad、小虫手机、电脑双肩包等奖品等你拿！亲，赶紧行动吧~');return false;
		}		
	}
	if(str == 'sina'){
		if(id != 0){
			window.open('http://service.weibo.com/share/share.php?url=http://www.yjh.com/zt4/zhuce.shtml?sid%3Db0f4cb7a2fe116521672de000d674c26_275&title=今天我参加一句话商机网举办【第四期】会员注册有奖活动，我抽中了'+name+'，很开心，晒晒我的幸福时刻！亲们，你们也快来参与吧！&pic='+image);return false;
		}else{
			window.open('http://service.weibo.com/share/share.php?url=http://www.yjh.com/zt4/zhuce.shtml?sid%3Db0f4cb7a2fe116521672de000d674c26_275&title=2014年2月26日—2014年4月25日一句话商机网举办【第四期】会员注册有奖活动，1000万创业币、2万话费、iPad、小虫手机、电脑双肩包等奖品等你拿！亲，赶紧行动吧~&appkey=1343713053');return false;
		}		
	}	
	if(str == 'qq'){
		if(id != 0){
			var _t = '今天我参加一句话商机网举办【第四期】会员注册有奖活动，我抽中了'+name+'，很开心，晒晒我的幸福时刻！亲们，你们也快来参与吧！';  var _url = 'http://www.yjh.com/zt4/zhuce.shtml?sid%3D178b16a635078ab280268d2ce9b59ae7_277'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = image; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');return false;
		}else{
			var _t = '2014年2月26日—2014年4月25日一句话商机网举办【第四期】会员注册有奖活动，1000万创业币、2万话费、iPad、小虫手机、电脑双肩包等奖品等你拿！亲，赶紧行动吧~';  var _url = 'http://www.yjh.com/zt4/zhuce.shtml?sid%3D178b16a635078ab280268d2ce9b59ae7_277'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site; window.open( _u,'转播到腾讯微博');return false;
		}		
	}
	if(str == 'renren'){
		if(id != 0){
			window.open('http://widget.renren.com/dialog/share?resourceUrl=http://www.yjh.com/zt4/zhuce.shtml?sid%3Dd688ffb82b11dc1eea370ac03ff53f17_279&description=今天我参加一句话商机网举办【第四期】会员注册有奖活动，我抽中了'+name+'，很开心，晒晒我的幸福时刻！亲们，你们也快来参与吧！&appkey=1343713053&pic='+image);return false;
		}else{
			window.open('http://widget.renren.com/dialog/share?resourceUrl=http://www.yjh.com/zt4/zhuce.shtml?sid%3Dd688ffb82b11dc1eea370ac03ff53f17_279&description=2014年2月26日—2014年4月25日一句话商机网举办【第四期】会员注册有奖活动，1000万创业币、2万话费、iPad、小虫手机、电脑双肩包等奖品等你拿！亲，赶紧行动吧~&appkey=1343713053');return false;
		}		
	}
	if(str == 'douban'){
		if(id != 0){
			window.open('http://shuo.douban.com/!service/share?href=http://www.yjh.com/zt4/zhuce.shtml?sid%3Dfc7fce22b30cc3dd7cd94103402dc970_281&name=今天我参加一句话商机网举办【第四期】会员注册有奖活动，我抽中了'+name+'，很开心，晒晒我的幸福时刻！亲们，你们也快来参与吧！&pic='+image)
		}else{
			window.open('http://shuo.douban.com/!service/share?href=http://www.yjh.com/zt4/zhuce.shtml?sid%3Dfc7fce22b30cc3dd7cd94103402dc970_281&name=2014年2月26日—2014年4月25日一句话商机网举办【第四期】会员注册有奖活动，1000万创业币、2万话费、iPad、小虫手机、电脑双肩包等奖品等你拿！亲，赶紧行动吧~')
		}		
	}
}


$(document).ready(function() {
	$("#lottery_getId").click(function(){
		$.ajax({
			url:window.$config.siteurl+"platform/HuoDong4/getTempIdForChouJiang",
			type:"post",
			dataType:"json",
			success:function(msg){
				if(msg["status"] == 0){
					window.location.href = window.$config.siteurl+"person/member/valid/mobile";
				}else{
					$("#lottery_id_info").html('您本次参加抽奖活动编号是&nbsp;<i>'+msg["status"]+'</i>，该编号用于抽取<var>mini iPad&nbsp;大奖&nbsp;</var>使用');
				}
			},
			error:function(){
				callbackdata="对不起，服务器出现异常！";
			}					
		})		
	});
	if($("#lottery_winners_data").length > 0){
		new winners_list_rolling("#lottery_winners_data");
	}
	if($(".lottery_list_scroll").length > 0){
		$(".lottery_list_scroll").each(function(index, val){
			val.id = "lottery_list_scroll"+index;
			new winners_list_rolling("#"+val.id);
		})
	}
	if($(".lottery4Prize").length > 0){
		$(".lottery4Prize").each(function(index, val){
			val.id = "lottery4Prize"+index;
			new winners_list_rolling("#"+val.id);
		})
	}
});

(function(){
	winners_list_rolling = function (id){
		var this_ = this;
		this.id = id;
		this.rolling_flag = true;
		this.direction = $(id).attr("data-direction");
		this.length = 28;
		if(this.direction == "horizontal"){
			var obj = $(this.id + " li:first")[0];
			var margLeft = parseInt($(obj).css("marginRight"));
			this.length = obj.offsetWidth + margLeft;
			if(!this.length){
				this.length = 28;
			}
		}
		this.init = function(){
			if(this_.direction == "horizontal"){
				this_.rollingH(this_);
			}else{
				this_.rolling(this_);
			}
			$(id).hover(function(){
				this_.stop(this_);
			},function(){
				this_.goon(this_);
			});
		}
	
		this.init();
	}
	
	winners_list_rolling.prototype.rolling = function(this_){
		$(this_.id).append($(this_.id).html());
		var speed = 1;
		setInterval(function(){
			if(!this_.rolling_flag)return false;
			$(this_.id).css("top", -speed);
			speed += 1;
			if(speed >= this_.length){
				speed = 0;
				this_.moveLine(this_);
				this_.moveLine(this_);
				$(this_.id).css("top", 0);
			}
		}, 30);
	}
	
	winners_list_rolling.prototype.moveLine = function(this_){
		var first = $(this_.id+" li:first");
		first.remove();
		var second = $(this_.id+" li:first");
		second.remove();
		var third = $(this_.id+" li:first");
		third.remove();
		$(this_.id).append(first);
		$(this_.id).append(second);
		$(this_.id).append(third);
	}
	
	winners_list_rolling.prototype.rollingH = function(this_){
		$(this_.id).append($(this_.id).html());
		var speed = 1;
		setInterval(function(){
			if(!this_.rolling_flag)return false;
			$(this_.id).css("left", -speed);
			speed += 1;
			if(speed >= this_.length){
				speed = 0;
				this_.moveLineH(this_);
				$(this_.id).css("left", 0);
			}
		}, 30);
	}
	winners_list_rolling.prototype.moveLineH = function(this_){
		var first = $(this_.id+" li:first");
		first.remove();
		$(this_.id).append(first);
	}	
	
	winners_list_rolling.prototype.goon = function(this_){
		if(!this_.rolling_flag)this_.rolling_flag = true;
	}
	
	winners_list_rolling.prototype.stop = function(this_){
		if(this_.rolling_flag)this_.rolling_flag = false;
	}
}());



</script>
<script type="text/javascript">
          $(function(){
            $(window).scroll(function(event) {
             var divheight=($(window).scrollTop()+$(".lottery4Float").height())-$(".footer").offset().top;
             var flag=($(window).scrollTop()+$(".lottery4Float").height())>$(".footer").offset().top;
              if($(window).scrollTop()>=698 && !flag){
                $(".lottery4Float").css({"position":"fixed","top":-100});
              }
              else if(($(window).scrollTop()+$(".lottery4Float").height())>$(".footer").offset().top){
                  $(".lottery4Float").css({"position":"fixed","top":-divheight});
              }
              else{
                $(".lottery4Float").css({"position":"absolute","top":698});
              }
            });
          })
        </script>