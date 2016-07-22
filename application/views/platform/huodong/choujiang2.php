<?php echo URL::webcss("platform/lottery.css?version=4")?>
<?php echo URL::webjs("jquery.cookies.2.2.0.js")?>
<?php echo URL::webjs("lottery.js")?>
<title>一句话网会员注册盛典_第二期疯狂会员月，大奖天天抽_100%中奖【iPhone5S、ipod、移动电源、U盘、T恤、500万创业币】</title>
<meta name="description" content="2013年9月1日——2013年9月30日是一句话网会员注册盛典活动，一句话网提供整月抽奖活动，百万抽奖，让利300万，更有发放300万创业币给有缘创业的网友。并且提供Ipad、Ipod、U盘、创业T恤、大量的创业币等抽奖活动，100%中奖！" />
<meta name="Keywords" content="会员注册,百万抽奖,会员注册活动,100%中奖,一句话" />
<!-- 内容开始 -->
	<?php $url=URL::website('zt2/zhuce.shtml');?>

    <div class="lottery_new_main1">
      <div class="lottery_main">
        <div class="lottery_main_id_info">
          <ul class="clearfix">
            <?php if(!$isLogin){?>
            <li class="lottery_info_content">您还未登录，请先<a href="<?php echo urlbuilder::geren("denglu");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>"><img src="<?php echo URL::webstatic('/images/event/lottery/lottery_btn_bg_03.png')?>"/></a>；如您还没有帐号，请先<a href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>"><img src="<?php echo URL::webstatic('/images/event/lottery/lottery_btn_bg_05.png')?>"/></a></li>
            <?php }elseif(count($temp_id) > 0){?>
            <li class="lottery_info_content">您本次参加抽奖活动编号是<font class="lottery_id"><?php echo $temp_id['temp_id'];?></font>，该编号用于抽取<font>iPhone5s大奖</font>使用</li>
            <?php }elseif($user_type == 2){?>
            <!--  
            <li class="lottery_info_content" id="lottery_id_info">请领取您抽<font>iPhone5s大奖</font>的编号<a id="lottery_getId" href="javascript:void(0)"><img src="<?php echo URL::webstatic('/images/event/lottery/lottery_btn_lq_03.png')?>"/></a></li>
            -->
            <?php }else{?>
            <li class="lottery_info_content" style="width:345px; text-algin:left;">企业用户不参加本次抽奖</li>
            <?php }?>
            <li class="lottery_detial clearfix">
              <p>当前正在参加本次抽奖活动的人数为<i><?php echo $countPeople;?></i>人</p>
              <p>当前累计完成抽奖次数为<i><?php echo $countRoulette;?></i>次</p>
            </li>

          </ul>
        </div>
      </div>
    </div>
    <div class="lottery_main_div2">
      <div class="lottery_main">
        <div class="lottery_flash_div2">
          <div class="lottery_flash2">
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="960" height="565" id="flashrek2">
              <param name="movie" value="<?php echo URL::webstatic('flash/wheel_10th.swf')?>">
              <param name="quality" value="high">
              <param name="wmode" value="transparent">
              <param name="allowScriptAccess" value="always">
              <embed src="<?php echo URL::webstatic('flash/wheel_10th.swf')?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="960" height="542" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> 
            </object>
          </div>
        </div>
        <div class="lottery_rule lottery_rule1">
          <dl class="share_button">
            <dt><font>+</font>分享到</dt>
            <dd><a href="#" title="QQ空间" onclick="javascript:share('qq_zone');"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_03.png');?>" /></a></dd>
            <dd><a href="#" title="新浪微博" onclick="javascript:share('sina');"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_05.png');?>" /></a></dd>
            <dd><a href="#" title="腾讯微博" onclick="javascript:share('qq');"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_07.png');?>" /></a></dd>
            <dd><a href="#" title="人人网" onclick="javascript:share('renren');"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_09.png');?>" /></a></dd>
            <dd><a href="#" title="豆瓣" onclick="javascript:share('douban')"><img width="18" height="17" src="<?php echo URL::webstatic('images/event/lottery/zt_lotery_images1_11.png');?>" /></a></dd>
          </dl>
          <p class="time"><font>活动时间：</font><strong>2013年10月11日9点---2013年11月11日16:00截止</strong></p>
          
          
          <div class="lottery2_result">
              <h3>本次活动已结束</h3>
              <p class="lowHeight">2013年11月11日上证指数收盘数为：<font>2109.47</font>，详情请查阅相关股票网站</p>              
              <p>已参加本次抽奖活动的人数为：<i>1855人</i>，</p>
              <p class="lowHeight">通过抽奖规则计算出中<i>iPhone5S</i>大奖 得主的编号是：<font>1495</font></p>              
              <p>恭喜<i>潘媛媛</i>(手机号码为<i>139****6932</i>)喜获一句话网站抽奖活动第二期一等奖！</p>         	  	
          </div>
          <?php /* if($szlist){?>
          <div class="lottery2_result" style="display:none;">
              <h3>本次活动已结束</h3>
              <p class="lowHeight">2013年11月11日上证指数收盘数为：<font><?php echo isset($szlist['sz']) ? $szlist['sz'] : '';?></font>，详情请查阅相关股票网站</p>              
              <p>已参加本次抽奖活动的人数为：<i><?php echo isset($szlist['people']) ? $szlist['people'] : 0;?>人</i>，</p>
              <p class="lowHeight">通过抽奖规则计算出中<i>iPhone5S</i>大奖 得主的编号是：<font><?php echo isset($szlist['lucky_id']) ? $szlist['lucky_id'] : 0;?></font></p>
              <?php if(isset($szlist['mobile']) && $szlist['mobile'] != ''){ ?>
              	<?php if(isset($szlist['name']) && $szlist['name'] != ''){?>
              	<p>恭喜<i><?php echo $szlist['name'];?></i>(手机号码为<i><?php echo $szlist['mobile'];?></i>)喜获一句话网站抽奖活动第二期一等奖！</p>          	  	
              	<?php }else{?>
              	<p>恭喜手机号码为<i><?php echo $szlist['mobile'];?></i>用户喜获一句话网站抽奖活动第二期一等奖！</p>	
              	<?php }?>
              <?php }?>
          </div>
          <?php } */?>
          <p class="title"><i>参与活动资格:</i></p>
          <div class="content">
            <div class="center_bg">
              <dl class="clearfix">
                <dd>
                  1、注册成为一句话网会员的网友，请第一时间绑定并验证你的手机号，否则无法参与抽奖活动；<br/>
                  2、老会员，请登录一句话网站，并在当前活动页面领取您本期抽<font>iPhone5s 大奖</font>的编号；
                </dd>
                <div class="clear"></div>
              </dl>
            </div>
          </div>
          <p class="title"><i>一等奖（iPhone5s）抽奖规则:</i></p>
          <div class="content content2">
            <div class="center_bg center_bg_new">
               <dl class="clearfix">
                <dd>为公平公正原则，一句话创业网网会员抽奖活动用2013年11月11日<font>上证指数</font>为基数作为抽奖一个因子，去乘以<font>10000</font>，再除以参加<font>本次抽奖活动的总人数</font>，结果<font>取余数</font>即为本期一等奖中奖编号！</dd>
                <dd>比如：<br/>
本期共有<font>3000个会员</font>参与抽奖，2013年11月11日上证收盘指数为:<font>2233.41</font>，按照上面的<font>iPhone5S大奖</font>抽奖规则，计算出中奖编号为:<font>10000*2233.41/3000 得到余数 2100</font>，即中奖编号为：<font>2100的网友</font>获得本次抽奖活动<font>iPhone5S大奖</font>。</dd>
                <dd>请每位会员牢记自己的<font>抽奖编号</font>，2013年11月11日下午16:00时，每位会员都可以根据网站上的参加抽奖人员数量、2013年11月11日上证指数来计算自己是否中一等奖？</dd>
                <div class="clear"></div>
              </dl>
              <i></i>
            </div>
          </div> 
          <p class="title"><i>活动规则:</i></p>
          <div class="content content3">
            <div class="center_bg">
              <dl class="clearfix">
                <dt>一句话用户绑定手机号注册的会员，每个账号每天有1次抽奖机会。</dt>
                <dd class="first">抽中<font>"iPod"</font>的，系统发送短信通知您，你可以免费领取 iPod 一个。</dd>
                <dd>抽中<font>"80创业币"</font>的，系统自动发 80创业币 至您的一句话账号。</dd>
                <dd class="first">抽中<font>"移动电源"</font>的，系统发送短信通知您，你可以免费领取 移动电源 一个。</dd>
                <dd>抽中<font>"50创业币"</font>的，系统自动发 50创业币 至您的一句话账号。</dd>
                <dd class="first">抽中<font>"（8G）U盘"</font>的，系统发送短信通知您，你可以免费领取 （8G）U盘 一个。</dd>
                <dd>抽中<font>"20创业币"</font>的，系统自动发 20创业币 至您的一句话账号。</dd>
                <dd class="first">抽中<font>"一句话T恤"</font>的，系统发送短信通知您，你可以免费领取 一句话T恤 一件。</dd>
                <dd>抽中<font>"10创业币"</font>的，系统自动发 10创业币 至您的一句话账号。</dd>
                <dd class="first">抽中<font>"100创业币"</font>的，系统自动发 100创业币 至您的一句话账号。</dd>
                <dd class="last">*如果连续10次无法电话联系到中奖者，则自动取消其获奖资格，奖品重新进入奖池供其他网友抽奖；最终解释权归一句话网所有。</dd>
                <div class="clear"></div>
              </dl>
            </div>
          </div>         
        </div>
        <div class="lottery_prize lottery_sm_info">
          <h2>什么是创业币?</h2>
          <ul class="clearfix">
            <li>
              <span>
                <font class="lottery_icon_sm">10创业币=</font>
                <font class="lottery_icon_yuan">1人民币</font>
              </span>
              <p>
                1.创业币是一句话网站针对网站会员推出的一种虚拟货币；<br/>
                2.创业币主要通过参加一句话网站内的活动来获得，如每天登陆、轮盘抽奖、发名片、收藏项目等；<br/>
                3.创业者在一句话网站平台进行项目投资时，可以使用所获得的创业币，直接抵扣带有<img src="<?php echo URL::webstatic('/images/event/lottery/lottery_sc_lv_03.png')?>">标志的项目的投资款；<br/>
                4.一句话网站力求通过创业币机制真正帮助到那些资金不足或没有资金的创业者；<br/>
                5.加盟一句话网站项目，每个创业者最多能兑现20万创业币创业。
              </p>
            </li>
          </ul>
        </div>
        <div class="lottery_prize">
          <h2>奖品展示</h2>
          <ul class="clearfix">
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery/lottery_prize_img_03.png')?>" alt="iPhones5s">
              <span>iPhones5s</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery/lottery_prize_img_05.png')?>" alt="iPod">
              <span>iPod</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery/lottery_prize_img_07.png')?>" alt="移动充电器">
              <span>移动充电器</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery/lottery_prize_img_09.png')?>" alt="U盘">
              <span>U盘</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery/lottery_prize_img_11.png')?>" alt="T恤">
              <span>T恤</span>
            </li>
            <li>
              <img src="<?php echo URL::webstatic('/images/event/lottery/lottery_prize_img_13.png')?>" alt="创业币">
              <span>创业币</span>
            </li>
          </ul>
        </div>
        <div class="lottery_prize lottery_winners_info lottery_winners_info1">
          <h2>第一期中奖名单</h2>
          <ul class="clearfix">
            <li>
              <table width="836">
                <tbody>
                  <tr>
                    <th width="120">姓名</th>
                    <th width="172">手机</th>
                    <th width="169">奖品</th>
                    <th width="120">姓名</th>
                    <th width="172">手机</th>
                    <th width="83">奖品</th>
                  </tr>
                </tbody>
              </table>
            </li>
            <li class="lottery_winners_list">
              <ul id="lottery_winners_data" class="clearfix" style="top:0px;">
	              	<li class="first" style="width: 120px;">刘栋</li>
	                <li style="width: 172px;">139****8526</li>
	                <li style="width: 120px;"><font>iPad</font></li>
                <?php if($big_prize_list){?>
	              	<?php foreach($big_prize_list as $v){?>
	                <li class="first" style="width: 120px;"><?php echo $v['name'] != '' ? $v['name'] : '佚名';?></li>
	                <li style="width: 172px;"><?php echo $v['mobile'];?></li>
	                <li style="width: 120px;"><font><?php echo $v['prize'];?></font></li>
	                <?php }?>
              	<?php }?>               
              </ul>
            </li>
          </ul>
        </div>
        <div class="lottery_winners_h">
          <a href="<?php echo URL::website('/zt/zhuce.shtml')?>" target="_blank" title="第一期更多中奖信息查看"><img src="<?php echo URL::webstatic('/images/event/lottery/lottery_new_winners_07.png')?>" alt="第一期更多中奖信息查看"></a>
        </div>
      </div>
    </div>
<!-- 内容结束 -->
<script>

function share(str){
	if(str == 'qq_zone'){
		window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=http://www.yjh.com/zt2/zhuce.shtml?sid%3D466a81abcae565f2d642a0fcc1fd590b_93&title=一句话网会员注册盛典_第二期疯狂会员月，大奖天天抽_100%中奖【iPhone5S、ipod、移动电源、U盘、T恤、500万创业币】创业选项目，就是一句话！精准招商平台，富有前景投资项目一站式打造，最火热赚钱项目助你开店创业，从此激活财富人生！');return false;;
	}
	if(str == 'sina'){
		window.open('http://service.weibo.com/share/share.php?url=http://www.yjh.com/zt2/zhuce.shtml?sid%3D7b6d72cb6f77720b7443cbd107380572_95&title=一句话网会员注册盛典_第二期疯狂会员月，大奖天天抽_100%中奖【iPhone5S、ipod、移动电源、U盘、T恤、500万创业币】创业选项目，就是一句话！精准招商平台，富有前景投资项目一站式打造，最火热赚钱项目助你开店创业，从此激活财富人生！&appkey=1343713053');return false;;
	}	
	if(str == 'qq'){
		var _t = '一句话网会员注册盛典_第二期疯狂会员月，大奖天天抽_100%中奖【iPhone5S、ipod、移动电源、U盘、T恤、500万创业币】创业选项目，就是一句话！精准招商平台，富有前景投资项目一站式打造，最火热赚钱项目助你开店创业，从此激活财富人生！';  var _url = 'http://www.yjh.com/zt2/zhuce.shtml?sid%3D45fc0288d1dcae025bfe71793a0d23fc_97'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site; window.open( _u,'转播到腾讯微博');return false;
	}
	if(str == 'renren'){
		window.open('http://widget.renren.com/dialog/share?resourceUrl=http://www.yjh.com/zt2/zhuce.shtml?sid%3Dbf7228967e12ed7708c9eccd342350a2_127&description=一句话网会员注册盛典_第二期疯狂会员月，大奖天天抽_100%中奖【iPhone5S、ipod、移动电源、U盘、T恤、500万创业币】创业选项目，就是一句话！精准招商平台，富有前景投资项目一站式打造，最火热赚钱项目助你开店创业，从此激活财富人生！&appkey=1343713053');return false;
	}
	if(str == 'douban'){
		window.open('http://shuo.douban.com/!service/share?href=http://www.yjh.com/zt2/zhuce.shtml?sid%3D3a2ff3a4d2adc500b902bb4a0eeb294c_129&name=一句话网会员注册盛典_第二期疯狂会员月，大奖天天抽_100%中奖【iPhone5S、ipod、移动电源、U盘、T恤、500万创业币】创业选项目，就是一句话！精准招商平台，富有前景投资项目一站式打造，最火热赚钱项目助你开店创业，从此激活财富人生！')
	}
}
</script>
