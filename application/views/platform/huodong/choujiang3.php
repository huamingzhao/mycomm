<?php echo URL::webcss("platform/lottery3.css?version=1")?>
<?php echo URL::webjs("jquery.cookies.2.2.0.js")?>
<?php echo URL::webjs("lottery.js")?>
<!-- 内容开始 -->
<div class="lottert3_bg">
	<div class="lottery3_main">
		<?php if(!$isLogin){?>
		<div class="lottery3_login_info">您还未登录，请先<a href="<?php echo urlbuilder::geren("denglu");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>" class="lottery3_btnred" title="登录">登录</a>如您还没有帐号，请先<a href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>" class="lottery3_btnyellow" title="注册">注册</a></div>
		<?php }elseif(count($temp_id) > 0){?>
		<div class="lottery3_login_info">您本次参加抽奖活动编号是<font class="num"><?php echo $temp_id['temp_id'];?></font>，该编号用于抽取<font>Mini iPad大奖</font>使用</div>
		<?php }elseif($user_type == 2){?>
		<div id="lottery_id_info" class="lottery3_login_info">请领取您抽<font>mini Ipad 大奖</font>的编号<a id="lottery_getId" href="javascript:void(0)" class="lottery3_btnred" title="我要领取">我要领取</a></div>
		<?php }else{?>
		<div class="lottery3_login_info">企业用户不参加本次抽奖</div>
		<?php }?>
		<div class="lottery3_join_info">
			<span>当前正在参加本次抽奖活动的人数为<font><?php echo $countPeople;?></font>人</span>
			<span>当前累计完成抽奖次数为<font><?php echo $countRoulette;?></font>次</span>
		</div>
		<div class="lottery3_flash">
		  <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="960" height="382" id="flashrek2">
	       <param name="movie" value="<?php echo URL::webstatic('flash/wheel_11th.swf')?>">        
	       <param name="quality" value="high">        
	       <param name="wmode" value="transparent">        
	       <param name="allowScriptAccess" value="always">        
	       <embed src="<?php echo URL::webstatic('flash/wheel_11th.swf')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="960" height="382" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash">
      </object>
		</div>
	<div class="lottery3_end">
      <h2>本次活动已结束</h2>
      <p>2014年1月22日上证指数收盘数为：<font>2051.75</font>，详情请查阅相关股票网站<br>
		当前正在参加本次抽奖活动的人数为：<font>1933</font>人，<br>
		通过抽奖规则计算出中<i>mini iPad</i>大奖 得主的编号是：<font>638</font>，<br>		
			恭喜手机号为<i>徐瑞龙(手机号码为158****6305)</i>喜获一句话网站抽奖活动第三期一等奖！
	  </p>
    </div>	
	<!--  
	<?php if($szlist){?>
    <div class="lottery3_end" style="display:none;">
      <h2>本次活动已结束</h2>
      <p>2014年1月22日上证指数收盘数为：<font><?php echo isset($szlist['sz']) ? $szlist['sz'] : '';?></font>，详情请查阅相关股票网站<br>
		当前正在参加本次抽奖活动的人数为：<font><?php echo isset($szlist['people']) ? $szlist['people'] : 0;?></font>人，<br>
		通过抽奖规则计算出中<i>mini iPad</i>大奖 得主的编号是：<font><?php echo isset($szlist['lucky_id']) ? $szlist['lucky_id'] : 0;?></font>，<br>
		<?php if(isset($szlist['mobile']) && $szlist['mobile'] != ''){ ?>
			<?php if(isset($szlist['name']) && $szlist['name'] != ''){?>
			恭喜手机号为<i><?php echo $szlist['name'];?>(手机号码为<?php echo $szlist['mobile'];?>)</i>喜获一句话网站抽奖活动第三期一等奖！
			<?php }else{?>
			恭喜手机号码为<i><?php echo $szlist['mobile'];?></i>用户喜获一句话网站抽奖活动第三期一等奖！
			<?php }?>
		<?php }?>
	  </p>
    </div>
    <?php }?>
    -->
		<div class="lottery3_section">
		<dl class="share_button">
    		<dt><font>+</font>分享到</dt>
            <dd><a href="#" title="QQ空间" onclick="javascript:share('qq_zone',0);"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_03.png');?>"></a></dd>
            <dd><a href="#" title="新浪微博" onclick="javascript:share('sina',0);"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_05.png');?>"></a></dd>
            <dd><a href="#" title="腾讯微博" onclick="javascript:share('qq',0);"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_07.png');?>"></a></dd>
            <dd><a href="#" title="人人网" onclick="javascript:share('renren',0);"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_09.png');?>"></a></dd>
            <dd><a href="#" title="豆瓣" onclick="javascript:share('douban',0)"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_11.png');?>"></a></dd>
        </dl>
        <h2 class="title">活动资格</h2>
        <div class="content">
          <span class="info">
           	 活动时间： <font>2013年11月22日 9 : 00 --- 2014年1月22日 16 : 00</font>
          </span>
          <p>
            1.注册成为一句话商机速配网会员的网友，请第一时间绑定并验证你的手机号，否则无法参与抽奖活动；
            <br/>
            2.老会员，请登录一句话商机速配网站，并在当前活动页面领取您本期抽 <font>mini iPad大奖</font>的编号；
          </p>
        </div>
      </div>
      <div class="lottery3_section">
        <h2 class="title">一等奖抽奖规则</h2>
        <div class="content">
          <p>
           	 为公平公正原则，一句话商机速配网会员抽奖活动用2014年1月22日
            <font>上证指数</font>
         	   为基数作为抽奖一个因子，去乘以
            <font>10000</font>
            	，再除以参加
            <font>本次抽奖活动的总人数</font>
            	，结果
            <font>取余数</font>
          	  即为本期一等奖中奖编号！
          </p>
          <p>
           	 比如：
            <br/>
         	   本期共有
            <font>3000个会员</font>
          	  参与抽奖，2014年1月22日 上证收盘指数为:
            <font>2233.41</font>
           	 ，按照上面的
            <font>mini iPad大奖</font>
          	  抽奖规则，计算出中奖编号为 :
            <font>10000*2233.41/3000 得到余数 2100</font>
          	  ，即中奖编号为：
            <font>2100的网友</font>
         	   获得本次抽奖活动
            <font>mini iPad大奖。</font>
          </p>
          <p>
           	 请每位会员牢记自己的
            <font>抽奖编号</font>
         	   ，2014年1月22日下午16:00时，每位会员都可以根据网站上的参加抽奖人员数量、2014年1月22日上证指数来计算自己是否中一等奖？
          </p>
        </div>
      </div>
      <div class="lottery3_section">
        <h2 class="title">活动规则</h2>
        <div class="content lottery3_rule">
          <ul>
            <li>抽中<font>“瑞士十字电脑背包”</font>的，系统发送短信通知您，你可以免费领取 瑞士十字电脑背包 一个。</li>
            <li>抽中<font>“2g Ipod”</font>的，系统发送短信通知您，你可以免费领取 2g Ipod 一个。</li>
            <li class="right">抽中<font>“80元创业币”</font>的，系统自动发 80创业币 至您的一句话账号。</li>
            <li>抽中<font>“8g U盘”</font>的，系统发送短信通知您，你可以免费领取 8g U盘 一个。</li>
            <li class="right">抽中<font>“50元创业币”</font>的，系统自动发 50创业币 至您的一句话账号。</li>
            <li>抽中<font>“20元话费”</font>的，系统发送短信通知您，我们将尽快为你手机充值 20元话费。</li>
            <li class="right">抽中<font>“20元创业币”</font>的，系统自动发 20创业币 至您的一句话账号。</li>
            <li>抽中<font>“10元话费”</font>的，系统发送短信通知您，我们将尽快为你手机充值 10元话费。</li>
            <li class="right">抽中<font>“10元创业币”</font>的，系统自动发 10创业币 至您的一句话账号。</li>
            <li>抽中<font>“100元创业币”</font>的，系统自动发 100创业币 至您的一句话账号。</li>
            <li class="last">* 如果连续10次无法电话联系到中奖者，则自动取消其获奖资格，奖品重新进入奖池供其他网友抽奖,最终解释权归一句话商机速配网所有。</li>
            <li style="font-size: 14px;">* 对于抽中手机话费充值的会员，系统将在活动结束时自动对中奖用户验证的手机进行充值，请勿在活动期间取消、更换验证手机号码，否则自动取消中奖机会！</li>
          </dl>
        </div>
      </div>
      <div class="lottery3_section">
        <h2 class="title">什么是创业币</h2>
        <div class="content">
          <div class="lottery3_whatsb">
            <p>
              <font class="lottery3_whatsb_sb">10创业币=</font>
              <font class="lottery3_whatsb_rmb">1人民币</font>
            </p>
            <p>
              1.创业币是一句话商机速配网站针对网站会员推出的一种虚拟货币；
              <br/>
              2.创业币主要通过参加一句话商机速配网站内的活动来获得；
              <br/>
              3.创业者在一句话商机速配网站平台进行项目投资时，可以使用所获得的创业币，直接抵扣带有
              <img width="42" height="20" src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_44.png')?>" title="vip"/>
             	 标志的项目的投资款；
              <br/>
              4.一句话商机速配网站力求通过创业币机制真正帮助到那些资金不足或没有资金的创业者；
              <br/>
              5.加盟一句话商机速配网站项目，每个创业者最多能兑现20万创业币创业。
            </p>
          </div>
        </div>
      </div>
      <div class="lottery3_section">
        <h2 class="title">奖品展示</h2>
        <div class=" lottery3_prize">
          <ul class="clearfix">
            <li>
              <img width="140" height="137" src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_49.png')?>" title="mini iPad" alt="mini iPad" />
              <span>mini iPad</span>
            </li>
            <li>
              <img width="140" height="137" src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_51.png')?>" title="iPod" alt="iPod"/>
              <span>iPod</span>
            </li>
            <li>
              <img width="140" height="137" src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_53.png')?>" title="瑞士十字电脑包" alt="瑞士十字电脑包" />
              <span>瑞士十字电脑包</span>
            </li>
            <li>
              <img width="140" height="137" src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_55.png')?>" title="U盘（8G）" alt="U盘（8G）" />
              <span>U盘（8G）</span>
            </li>
            <li>
              <img width="140" height="137" src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_57.png')?>" title="20元话费" alt="20元话费" />
              <span>20元话费</span>
            </li>
            <li>
              <img width="140" height="137" src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_59.png')?>" title="100创业币" alt="100创业币" />
              <span>100创业币</span>
            </li>
          </ul>
        </div>
      </div>
      <div class="lottery3_section">
        <h2 class="title">第二期中奖名单</h2>
        <div class="content lottery3_history_winners">
          <div class="lottery3_last_winner">恭喜来自山东烟台的<font>潘媛媛</font>(手机号码为139****6932 )<br>
			喜获一句话商机速配网站第二期抽奖活动<font>Iphone 5S</font>大奖！</div>
          <ul>
            <li class="lottery_winners_header">
              <ul class="clearfix">
                <li style="width: 120px;">姓名</li>
                <li style="width: 172px;">手机</li>
                <li style="width: 130px;">奖品</li>
                <li style="width: 120px; margin-left: 30px;">姓名</li>
                <li style="width: 172px;">手机</li>
                <li style="width: 130px;">奖品</li>
              </ul>
            </li>            
      		<li class="lottery_winners_list">
              <ul id="lottery_winners_data" class="clearfix" style="top: 0px;">
              	<?php if($big_prize_list){?>
	            <?php foreach($big_prize_list as $v){?>
                <li style="width: 120px;"><?php echo $v['name'] != '' ? $v['name'] : '佚名';?></li>
                <li style="width: 172px;"><?php echo $v['mobile'];?></li>
                <li style="width: 130px;">
                  <font><?php echo $v['prize'];?></font>
                </li>
                <?php }?>
              	<?php }?> 
              </ul>
            </li>
          </ul>
        </div>
      </div>
      <div class="lottery3_section lottery3_history clearfix">
        <h2 class="title">往期活动</h2>
        <div class="content first">
          <a href="<?php echo URL::website('/zt2/zhuce.shtml')?>" target="_blank" title="第二期抽奖页面"><img width="458" height="158" src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_72.png');?>" alt="第二期抽奖页面"></a>
        </div>
        <div class="content">
          <a href="<?php echo URL::website('/zt/zhuce.shtml')?>" target="_blank" title="第一期抽奖页面"><img width="458" height="158" src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_74.png');?>" alt="第一期抽奖页面"></a>
        </div>
      </div> 
      
      <div class="lottery3_section lottery3_history_photos">
        <h2 class="title">获奖感言</h2>
        <div class="content">
          <div class="lottery3_photos_list">
          <div class="lottery3_photos_list_div">
          <ul class="clearfix lottery_list_scroll" data-direction="horizontal">
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_77.png')?>" alt="潘媛媛" width="170" height="190">
              <span class="clearfix">
                <font class="name">潘媛媛</font>
                <i class="time">2013-11-13</i>
              </span>
              <span>很高兴可以中奖，运气不错！</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_81.png')?>" alt="郭占军" width="170" height="190">
              <span class="clearfix">
                <font class="name">郭占军</font>
                <i class="time">2013-11-03</i>
              </span>
              <span>人品爆发了，哈哈哈！</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_83.png')?>" alt="夏静" width="170" height="190">
              <span class="clearfix">
                <font class="name">夏静</font>
                <i class="time">2013-10-25</i>
              </span>
              <span>奖品很喜欢，赞一个！</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_85.png')?>" alt="邢静" width="170" height="190">
              <span class="clearfix">
                <font class="name">邢静</font>
                <i class="time">2013-11-08</i>
              </span>
              <span>东西很好，快递很快，谢谢！</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_87.png')?>" alt="张爱巧" width="170" height="190">
              <span class="clearfix">
                <font class="name">张爱巧</font>
                <i class="time">2013-10-13</i>
              </span>
              <span>随便抽抽居然也能中奖！</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery3/lottery3_79.png')?>" alt="王宁宁" width="170" height="190">
              <span class="clearfix">
                <font class="name">王宁宁</font>
                <i class="time">2013-10-20</i>
              </span>
              <span>感谢主办方，希望活动多多！</span>
            </li>
          </ul>
          </div>
          </div>
        </div>
      </div>     
	</div>
	
	<div id="lottery3_tollbar" class="lottery3_toolBar">
        <dl class="lottery3_moreChance">
          <dt>获取抽奖机会</dt>
          <dd class="invite">
            <p>1.每邀请好友成功注册<font>（指手机号码验证通过）</font>1人，您将获赠一次额外的抽奖机会。</p>
            <a href="<?php echo URL::website('/person/member/huodong/showInviteFriends') ?>" target="_blank" title="去邀请">去邀请</a>
          </dd>
          <dd class="find_project">
            <p>2.给意向投资项目每投递一张名片，您将获赠一次额外的抽奖机会。<font>（获赠抽奖机会每天仅限投递1张名片有效，重复投递无效）</font></p>
            <a href="<?php echo URL::website('')?>" target="_blank" title="找项目">找项目</a>
          </dd>
        </dl> 
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
</div>
<!-- 内容结束 -->
<script>

function share(str,id){
	var name = '';
	var image = '';
	if(id == 1){
		name = 'iPod';
		image = '<?= URL::webstatic("/images/cj3/prize/p1.jpg");?>';
	}else if(id == 2){
		name = '电脑双肩包';
		image = '<?= URL::webstatic("/images/cj3/prize/p2.jpg");?>';
	}else if(id == 3){
		name = 'U盘';
		image = '<?= URL::webstatic("/images/cj3/prize/p3.jpg");?>';
	}else if(id == 4){
		name = '20元话费';
		image = '<?= URL::webstatic("/images/cj3/prize/p4.jpg");?>';
	}else if(id == 5){
		name = '10元话费';
		image = '<?= URL::webstatic("/images/cj3/prize/p5.jpg");?>';
	}else if(id == 6){
		name = '100创业币';
		image = '<?= URL::webstatic("/images/cj3/prize/p6.jpg");?>';
	}else if(id == 7){
		name = '80创业币';
		image = '<?= URL::webstatic("/images/cj3/prize/p7.jpg");?>';
	}else if(id == 8){
		name = '50创业币';
		image = '<?= URL::webstatic("/images/cj3/prize/p8.jpg");?>';
	}else if(id == 9){
		name = '20创业币';
		image = '<?= URL::webstatic("/images/cj3/prize/p9.jpg");?>';
	}else if(id == 10){
		name = '10创业币';
		image = '<?= URL::webstatic("/images/cj3/prize/p10.jpg");?>';
	}
	if(str == 'qq_zone'){
		if(id != 0){
			window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=http://www.yjh.com/zt3/zhuce.shtml?sid%3D3fe2d905787f3cf7ca0a567d567a7c71_189&title=今天我参加一句话商机网举办【第三期】会员注册有奖活动，我抽中了'+name+'，很开心，晒晒我的幸福时刻！亲们，你们也快来参与吧！&pics='+image);return false;
		}else{
			window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=http://www.yjh.com/zt3/zhuce.shtml?sid%3D3fe2d905787f3cf7ca0a567d567a7c71_189&title=2013年11月22日—2014年1月22日一句话商机网举办【第三期】会员注册有奖活动，1000万创业币、2万话费、iPad、iPod、电脑双肩包等奖品等你拿！亲，赶紧行动吧~');return false;
		}		
	}
	if(str == 'sina'){
		if(id != 0){
			window.open('http://service.weibo.com/share/share.php?url=http://www.yjh.com/zt3/zhuce.shtml?sid%3Daf9bbd2356a5dfa90bb88d75331bdbe0_191&title=今天我参加一句话商机网举办【第三期】会员注册有奖活动，我抽中了'+name+'，很开心，晒晒我的幸福时刻！亲们，你们也快来参与吧！&pic='+image);return false;
		}else{
			window.open('http://service.weibo.com/share/share.php?url=http://www.yjh.com/zt3/zhuce.shtml?sid%3Daf9bbd2356a5dfa90bb88d75331bdbe0_191&title=2013年11月22日—2014年1月22日一句话商机网举办【第三期】会员注册有奖活动，1000万创业币、2万话费、iPad、iPod、电脑双肩包等奖品等你拿！亲，赶紧行动吧~&appkey=1343713053');return false;
		}		
	}	
	if(str == 'qq'){
		if(id != 0){
			var _t = '今天我参加一句话商机网举办【第三期】会员注册有奖活动，我抽中了'+name+'，很开心，晒晒我的幸福时刻！亲们，你们也快来参与吧！';  var _url = 'http://www.yjh.com/zt3/zhuce.shtml?sid%3Dca22f912bc45b3c33917344986226c62_193'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = image; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');return false;
		}else{
			var _t = '2013年11月22日—2014年1月22日一句话商机网举办【第三期】会员注册有奖活动，1000万创业币、2万话费、iPad、iPod、电脑双肩包等奖品等你拿！亲，赶紧行动吧~';  var _url = 'http://www.yjh.com/zt3/zhuce.shtml?sid%3Dca22f912bc45b3c33917344986226c62_193'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site; window.open( _u,'转播到腾讯微博');return false;
		}		
	}
	if(str == 'renren'){
		if(id != 0){
			window.open('http://widget.renren.com/dialog/share?resourceUrl=http://www.yjh.com/zt3/zhuce.shtml?sid%3Dbe2047bf903886ec2fb28a04ac0e8608_195&description=今天我参加一句话商机网举办【第三期】会员注册有奖活动，我抽中了'+name+'，很开心，晒晒我的幸福时刻！亲们，你们也快来参与吧！&appkey=1343713053&pic='+image);return false;
		}else{
			window.open('http://widget.renren.com/dialog/share?resourceUrl=http://www.yjh.com/zt3/zhuce.shtml?sid%3Dbe2047bf903886ec2fb28a04ac0e8608_195&description=2013年11月22日—2014年1月22日一句话商机网举办【第三期】会员注册有奖活动，1000万创业币、2万话费、iPad、iPod、电脑双肩包等奖品等你拿！亲，赶紧行动吧~&appkey=1343713053');return false;
		}		
	}
	if(str == 'douban'){
		if(id != 0){
			window.open('http://shuo.douban.com/!service/share?href=http://www.yjh.com/zt3/zhuce.shtml?sid%3D41a76b1e43447405f88c1c6c9e103a73_197&name=今天我参加一句话商机网举办【第三期】会员注册有奖活动，我抽中了'+name+'，很开心，晒晒我的幸福时刻！亲们，你们也快来参与吧！&pic='+image)
		}else{
			window.open('http://shuo.douban.com/!service/share?href=http://www.yjh.com/zt3/zhuce.shtml?sid%3D41a76b1e43447405f88c1c6c9e103a73_197&name=2013年11月22日—2014年1月22日一句话商机网举办【第三期】会员注册有奖活动，1000万创业币、2万话费、iPad、iPod、电脑双肩包等奖品等你拿！亲，赶紧行动吧~')
		}		
	}
}
</script>