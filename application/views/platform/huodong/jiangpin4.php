<?php echo URL::webcss("platform/lottery4.css?version=1")?>
<?php echo URL::webjs("lottery4.js")?>
<div class="lottery4Banner clearfix">
	<div class="banner1"></div>
	<div class="banner2"></div>
	<div class="banner3">
	</div>
</div>
<div class="lottery4Main">
	<dl class="lottery4Prize clearfix">
        <dt><img width="124" height="50" src="<?php echo URL::webstatic('images/event/lottery4/lottery4_07.png')?>" alt="奖品预览"/></dt>
        <dd class="first">
          <img src="<?php echo URL::webstatic('images/event/lottery4/lottery4_11.png')?>" alt="mini iPad">
          <span>mini iPad</span>
        </dd>
        <dd>
          <img src="<?php echo URL::webstatic('images/event/lottery4/lottery4_13.png')?>" alt="小虫手机">
          <span>小虫手机</span>
        </dd>
        <dd>
          <img src="<?php echo URL::webstatic('images/event/lottery4/lottery4_15.png')?>" alt="瑞士十字电脑双肩包">
          <span>瑞士十字电脑双肩包</span>
        </dd>
        <dd>
          <img src="<?php echo URL::webstatic('images/event/lottery4/lottery4_17.png')?>" alt="话费">
          <span>话费</span>
        </dd>
        <dd>
          <img src="<?php echo URL::webstatic('images/event/lottery4/lottery4_19.png')?>" alt="创业币">
          <span>创业币</span>
        </dd>
      </dl>
      <div class="lottery4_info">
        <font style="font-size:30px;">奖品丰厚，让人垂涎欲滴，</font>
        <font style="font-size:20px;">但抽奖机会太少，怎么办？？？抽奖机会大赠送行动开始了，</font>
        <font style="font-size:24px;">从现在开始，你就可以狂赚抽奖机会，活动开始后，你可以一次抽个够！！！</font>
        <font style="font-size:36px;">马上行动，幸运由你掌控！</font>
      </div>
      <dl class="lottery4Wainner">
        <dt>第三期中奖信息</dt>
        <dd>
          <p>恭喜来自北京的<font>徐瑞龙</font>(手机号码为158****6305) <br>
喜获一句话网站第三期抽奖活动<font>mini iPad</font>大奖！</p>
          <a href="<?php echo URL::website('/zt3/zhuce.shtml')?>" target="_blank">第三期中奖名单详情</a>
        </dd>
      </dl>
      <dl class="lottery4Wainner">
        <dt>第二期中奖信息</dt>
        <dd>
          <p>恭喜来自山东烟台的<font>潘媛媛</font>(手机号码为139****6932 ) <br>
喜获一句话网站第二期抽奖活动<font>iPhone 5S</font>大奖！</p>
          <a href="<?php echo URL::website('/zt2/zhuce.shtml')?>" target="_blank">第二期中奖名单详情</a>
        </dd>
      </dl>
      <dl class="lottery4Wainner">
        <dt>第一期中奖信息</dt>
        <dd>
          <p>恭喜来自上海的<font>刘栋</font>(手机号码为139****8526 ) <br>
喜获一句话网站第一期抽奖活动<font>iPad</font>大奖！</p>
          <a href="<?php echo URL::website('/zt/zhuce.shtml')?>" target="_blank">第一期中奖名单详情</a>
        </dd>
      </dl> 
      <dl class="lottery4Project clearfix">
      	<dt class="clearfix">
            <img width="287" height="49" src="<?php echo URL::webstatic('images/event/lottery4/lottery4_34.png')?>" alt="2014年最值得创业好项目"/>
            <a href="<?php echo urlbuilder::rootDir('xiangdao');?>">更多>></a>
        </dt>
        <dd class="dataDiv">
        	<ul class="browse_list">
	        	<?php $i=0; if($guess_like_list){foreach ($guess_like_list as $key=>$val){$i++; ?>
	            <li <?php if($i == 6){echo "class='last'";}?>>
	            <label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="创业项目<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="创业项目<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label>
	            <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,30,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,30,'UTF-8')."";};?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,30,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,30,'UTF-8')."";};?></a></span>
	            <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
	            <div><p class="p_01"><a href="<?php echo urlbuilder::project($val['project_id']);?>" title="<?php echo $val['project_brand_birthplace'] ? $val['project_brand_birthplace'] :"未知";?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'], 0,4,'UTF-8')."":"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
	            </li>
	            <?php }}?>
	            <div class="clear"></div>
        	</ul>	
        </dd>
      </dl> 
      <dl class="lottery4Project clearfix">  
      	<dt class="clearfix">
          <img width="166" height="49" src="<?php echo URL::webstatic('images/event/lottery4/lottery4_37.png')?>" alt="创业文章必读"/>
          <a href="<?php echo urlbuilder::rootDir("zixun");?>">更多>></a>
        </dt>  
        <dd class="dataDiv">
          <div class=" browse_record_news">
            <dl>
              <dt>
                <h3>
                  <a href="<?= URL::website("/zixun/invest.html");?>">创业投资前沿趋势</a>
                </h3>
              </dt>
              <dd class="first">
                <a target="_blank" href="http://www.yjh.com/zixun/201309/4607.shtml" title="从蜜蜂传播看病毒式传播策略 ">
                  <img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" width="100" height="80" src="<?php echo URL::webstatic('images/event/lottery4/article.png')?>" alt="从蜜蜂传播看病毒式传播策略 ">
                </a>
                <h4 class="jchar">
                  <a target="_blank" href="http://www.yjh.com/zixun/201309/4607.shtml" title="从蜜蜂传播看病毒式传播策略">从蜜蜂传播看病毒式传播策略</a>
                </h4>
                <span>在营销界，蜜蜂常常被用来作为优秀传播者的代名词。但最贴切的比喻应该要用在</span>
                <div class="clear"></div>
              </dd>
              <dd class="jchar">
                <a class="link" target="_blank" href="http://www.yjh.com/zixun/201309/4686.shtml" title="APP营销将成服装行业营销新策略">APP营销将成服装行业营销新策略</a>
              </dd>
              <dd class="jchar">
                <a class="link" target="_blank" href="http://www.yjh.com/zixun/201309/5569.shtml" title="如何挖掘新兴城镇化建设的小本投资商机？">如何挖掘新兴城镇化建设的小本投资商机？</a>
              </dd>
              <dd class="jchar">
                <a class="link" target="_blank" href="http://www.yjh.com/zixun/201309/5441.shtml" title="新智能电视领域广阔的投资商机">新智能电视领域广阔的投资商机 </a>
              </dd>
              <dd class="jchar">
                <a class="link" target="_blank" href="http://www.yjh.com/zixun/201311/7947.shtml" title="小本创业者选择连锁加盟的几大优势">小本创业者选择连锁加盟的几大优势</a>
              </dd>
            </dl>
            <dl>
              <dt>
                <h3>
                  <a target="_blank" href="<?= URL::website("/zixun/guide.html");?>">创业开店专业指导</a>
                </h3>
              </dt>
              <dd class="first">
                <a target="_blank" href="http://www.yjh.com/zixun/201309/4673.shtml" title="市场新形势下LED照明的现状和竞争格局">
                  <img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" width="100" height="80" src="<?php echo URL::webstatic('images/event/lottery4/article2.png')?>" alt="市场新形势下LED照明的现状和竞争格局"></a>
                <h4 class="jchar">
                  <a target="_blank" href="http://www.yjh.com/zixun/201309/4673.shtml" title="市场新形势下LED照明的现状和竞争格局">市场新形势下LED照明的现状和竞争格局</a>
                </h4>
                <span>随着经济快速成长与节能政策的大力推动，中国已成为全球最具成长潜力的节能照</span>
                <div class="clear"></div>
              </dd>
              <dd class="jchar">
                <a class="link" target="_blank" href="http://www.yjh.com/zixun/201309/5493.shtml" title="家具行业发展高端化品牌趋势解析 ">家具行业发展高端化品牌趋势解析 </a>
              </dd>
              <dd class="jchar">
                <a class="link" target="_blank" href="http://www.yjh.com/zixun/201309/5243.shtml" title="早教行业投资创业的新契机">早教行业投资创业的新契机</a>
              </dd>
              <dd class="jchar">
                <a class="link" target="_blank" href="http://www.yjh.com/zixun/201309/5239.shtml" title="一线城市儿童早教行业的现状分析">一线城市儿童早教行业的现状分析</a>
              </dd>
              <dd class="jchar">
                <a class="link" target="_blank" href="http://www.yjh.com/zixun/201309/5149.shtml" title="未来五金刀具产业必经高端制造趋势">未来五金刀具产业必经高端制造趋势</a>
              </dd>
            </dl>
            <dl class="last">
              <dt>
                <h3>
                  <a target="_blank" href="<?= URL::website("/zixun/shop/jiqiao.html");?>">开店经营管理技巧</a>
                </h3>
              </dt>
              <dd class="first">
                <a target="_blank" href="http://www.yjh.com/zixun/201309/5481.shtml" title="西餐厅有着哪些装修格调？">
                  <img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" width="100" height="80" src="<?php echo URL::webstatic('images/event/lottery4/article3.png')?>" alt="西餐厅有着哪些装修格调？"></a>
                <h4 class="jchar">
                  <a target="_blank" href="http://www.yjh.com/zixun/201309/5481.shtml" title="西餐厅有着哪些装修格调？">西餐厅有着哪些装修格调？</a>
                </h4>
                <span>西餐厅在经受过市场调查、资金筹备和选址后，投资者所要面对的就是装修了。西</span>
                <div class="clear"></div>
              </dd>
              <dd class="jchar">
                <a class="link" target="_blank" href="http://www.yjh.com/zixun/201309/5523.shtml" title="店铺商品陈列如何玩转十一黄金周">店铺商品陈列如何玩转十一黄金周</a>
              </dd>
              <dd class="jchar">
                <a class="link" target="_blank" href="http://www.yjh.com/zixun/201309/5477.shtml" title="解析汽车配件加盟店的经营战略">解析汽车配件加盟店的经营战略</a>
              </dd>
              <dd class="jchar">
                <a class="link" target="_blank" href="http://www.yjh.com/zixun/201401/10207.shtml" title="开运动服加盟店前期的开店指南">开运动服加盟店前期的开店指南</a>
              </dd>
              <dd class="jchar">
                <a class="link" target="_blank" href="http://www.yjh.com/zixun/201309/5501.shtml" title="投资健身房的开店创业流程">投资健身房的开店创业流程</a>
              </dd>
            </dl>
            <div class="clear"></div>
          </div>
        </dd>
      </dl>
</div>
<div class="lottery4Float">
	<img src="<?php echo URL::webstatic('images/event/lottery4/lottery4_float_03.png')?>">
  	<div class="lottery4FloatMain">
 		<p>1.每邀请好友成功注册（指手机号码验证通过）1人，您将获赠1次额外的抽奖机会。</p>
		<a href="<?php echo URL::website('/person/member/huodong/showInviteFriends') ?>" target="_blank" title="去邀请">去邀请</a>
 		<p class="line"></p>
		<p>2.给意向投资项目每投递一张名片，您将获赠一次额外的抽奖机会。（获赠抽奖机会每天仅限投递1张名片有效，重复投递无效）</p>
		<a href="<?php echo URL::website('')?>" target="_blank" title="找项目">找项目</a>
	</div>
	<img src="<?php echo URL::webstatic('images/event/lottery4/lottery4_float_07.png')?>">
</div>
<script type="text/javascript">
	$(function(){
 		$(window).scroll(function(event) {
			var divheight=($(window).scrollTop()+$(".lottery4Float").height())-$(".footer").offset().top;
			var flag=($(window).scrollTop()+$(".lottery4Float").height())>$(".footer").offset().top;
	 		if($(window).scrollTop()>=682 && !flag){
	 			$(".lottery4Float").css({"position":"fixed","top":0});
			}
			else if(($(window).scrollTop()+$(".lottery4Float").height())>$(".footer").offset().top){
	 			$(".lottery4Float").css({"position":"fixed","top":-divheight});
	  		}
			else{
				$(".lottery4Float").css({"position":"absolute","top":682});
			}
		});
	})
</script>        