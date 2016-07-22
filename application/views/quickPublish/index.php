<?php echo URL::webcss("common_global.css")?>
<?php echo URL::webcss("platform/index.css")?>
<?php echo URL::webjs("quick_main.js")?>
<?php echo URL::webjs("indexloading.js")?>
	<div class="pageMain newHome">
		<div class="navigation">
			<ul class="menu clearfix">
				<li class="fc">
					<a href="<?=URL::website('');?>" target="_blank">首页</a>
				</li>
				<li>
					<a href="<?=urlbuilder::quickSearchIndex();?>" target="_blank">找生意</a>
				</li>
				<li>
					<a href="<?=zxurlbuilder::zixun();?>" target="_blank">学做生意</a>
				</li>
				<li style="float:right;">
					<a href="<?=urlbuilder::qucikTuiGuangGuide();?>" target="_blank">推广指南</a>
				</li>
				<!-- 
				<li>
					<a href="">用户认证</a>
				</li>
				 -->
			</ul>
		</div>
		<div class="industry clearfix">
			<h4>行业分类<a target="_blank" href="<?=urlbuilder::quickSearchIndex();?>" class="more">查看更多行业>></a></h4>
			<dl class="cyjm ">
				<dt><a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>1));?>">餐饮加盟</a></dt>
				<dd class="clearfix">
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>8));?>">火锅</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>9));?>">饮品</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>10));?>">拉面</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>11));?>">快餐</a>
					<a class="yellow" target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>18));?>">餐具</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>12));?>">小吃</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>13));?>">西餐</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>16));?>">烧烤</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>17));?>">熟食</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>15));?>">茶</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>14));?>">精品餐饮</a>
				</dd>
			</dl>
			<dl class="fsxb">
				<dt><a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>3));?>">服饰箱包</a></dt>
				<dd>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>27));?>">男装</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>28));?>">女装</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>29));?>">童装</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>30));?>">休闲</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>31));?>">运动</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>38));?>">配饰</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>33));?>">户外</a>
					<a class="yellow" target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>35));?>">内衣</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>36));?>">皮具</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>37));?>">箱包</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>34));?>">孕妇装</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>32));?>">鞋</a>
				</dd>
			</dl>
			<dl class="lsls">
				<dt><a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>2));?>">连锁零售</a></dt>
				<dd>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>22));?>">连锁加盟</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>23));?>">商业零售</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>24));?>">专项零售</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>88));?>">个人护理店</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>87));?>">文具店</a>
					<a target="_blank" class="yellow" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>25));?>">便利店</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>26));?>">其他</a>
				</dd>
			</dl>
			<dl class="jjjc">
				<dt><a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>4));?>">家居建材</a></dt>
				<dd>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>40));?>">吊顶</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>43));?>">油漆</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>41));?>">装饰</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>44));?>">壁纸</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>42));?>">门窗</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>45));?>">地板</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>46));?>">楼梯</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>47));?>">厨电</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>50));?>">家具</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>48));?>">卫浴</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>51));?>">瓷砖</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>52));?>">采暖</a>
				</dd>
			</dl>
			<dl class="shfw">
				<dt><a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>109));?>">生活服务</a></dt>
				<dd>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>110));?>">干洗</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>113));?>">通讯</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>114));?>">家政</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>112));?>">酒店</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>115));?>">鲜花婚庆</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>117));?>">体检保健</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>111));?>">鞋美容</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>116));?>">影像</a>
				</dd>
			</dl>
			<dl class="jypx">
				<dt><a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>5));?>">教育培训</a></dt>
				<dd>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>54));?>">英语</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>55));?>">作文</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>56));?>">潜能</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>61));?>">留学</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>58));?>">一对一辅导</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>60));?>">教具教材</a>
					<a target="_blank" href="<?=urlbuilder::quickSearchCond(array('industry_id'=>59));?>">公务员认证</a>
				</dd>
			</dl>
		</div>
		<div class="businessNews">
			<h4>生意播报</h4>
			<ul class="info clearfix">
				<li>共<font id="projectCount"><?=arr::get($arr_data,"QuickProjectCount",0);?></font>条好生意</li>
				<li>共<font id="projectPvCount"><?=arr::get($arr_data, "QuickProjectPvCount",0)?></font>人关注了生意</li>
				<li class="last">共<font id="UserCount"><?=arr::get($arr_data, "UserCount",0)?></font>人成为注册会员</li>
			</ul>
			<div class="newsContent clearfix">
            <div class="newsContentDiv clearfix">
				<ul class="newsList newsListProject" id="newsListProject">
				<?php if(arr::get($arr_data, "QuickProjectList")){
					foreach ($arr_data['QuickProjectList'] as $key=>$val){?>
						<li class="clearfix">
							<div class="fl"><a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="img"><img alt="<?=arr::get($val, "project_brand_name");?>" src="<?=URL::imgurl(arr::get($val,"project_logo"));?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')"></a></div>
							<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="title"><?=mb_substr(arr::get($val,"project_title") ? arr::get($val,"project_title") : arr::get($val,"project_brand_name"), 0,15,'UTF-8')."";?></a>
							<span>
								<em class="tag_money"><i class="icon_money"><var class="tishivar">投资金额<b></b></var></i>
								<?php foreach (common::moneyArr() as $k=>$v){
									if($k == arr::get($val,"project_amount_type")){
										echo $v;
									}
								}?>
								</em>
								<em class="tag_view"><i class="icon_view"><var class="tishivar">浏览量<b></b></var></i><?=arr::get($val,"project_pv_count",0)?></em>
								<em class="tag_time"><i class="icon_time"><var class="tishivar">发布时间<b></b></var></i><?=date('m-d',arr::get($val,"project_passtime",time()))?></em>
							</span>
						</li>
					<?php }}?>
				</ul>
				<ul class="newsList newsListUserInfo" id="projectPVList">
				<?php if(arr::get($arr_data, "QuickProjectPvList")){ $num=0;
					foreach ($arr_data['QuickProjectPvList'] as $key=>$val){ $num++;if($num > 5){break;}?>
					<li class="clearfix">
						<div class="fl"><a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="img"><img alt="<?=arr::get($val, "project_brand_name");?>" src="<?=URL::imgurl(arr::get($val, "project_logo"))?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')"></a></div>
						<?php if(arr::get($val,"user_name")){?>
							<span>
							<font><?=mb_substr(arr::get($val, "user_name"),0,11,"UTF8");?></font>
							<?php if(arr::get($val,"insert_time")){?>
							<em>
							<?=arr::get($val,"insert_time");?>分钟前
							</em>关注了
							<?php }else{?>
								刚刚关注了
							<?php }?>
							</span>
							<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="title"><?=mb_substr(arr::get($val,"project_title") ? arr::get($val,"project_title") : arr::get($val,"project_brand_name"), 0,15,'UTF-8')."";?></a>
						<?php }else{?>
							<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="title"><?=mb_substr(arr::get($val,"project_title") ? arr::get($val,"project_title") : arr::get($val,"project_brand_name"), 0,15,'UTF-8')."";?></a>
								<span>
							<?php if(arr::get($val,"insert_time")){?>
							<em style="padding-left:0;">
							<?=arr::get($val,"insert_time");?>分钟前
							</em>被关注了
							<?php }else{?>
								刚刚被关注了
							<?php }?>
							</span>
						<?php }?>
						
					</li>
					<?php }}?>
				</ul>
				<ul class="newsList newsListNewUser" id="newsListNewUser">
					<?php if(arr::get($arr_data, "NewUserList")){
						foreach ($arr_data['NewUserList'] as $key=>$val){if($key >4){break;}?>
					<li class="clearfix">
						<div class="fl"><a class="img">
							<img alt="<?=arr::get($val,"user_name")?>" src="<?=URL::imgurl(arr::get($val, "user_portrait"))?>" <?php if(arr::get($val,"user_gender") == 0){?>onerror="$(this).attr('src', '<?= URL::webstatic("/images/find_invester/photo_man.jpg");?>')"<?php }else{?>onerror="$(this).attr('src', '<?= URL::webstatic("/images/find_invester/photo_woman.jpg");?>')"<?php } ?>>
						</a></div>
						<span><font><?=arr::get($val, "newmobile")?></font>
						<?php if(arr::get($val,"zhuceshijian")){?>
							<em>
							<?=arr::get($val,"zhuceshijian");?>
							</em>
						<?php }else{?>
							<em></em>
						<?php }?>
						</span>
						<span>加入一句话生意网</span>
					</li>
					<?php }}?>
				</ul>
                </div>
			</div>
		</div>
		<div class="hotProject">
			<h4>一周热门生意推荐</h4>
			<div class="projectNav">
				<ul class="menu clearfix">
					<li class="fc"><a href="javascript:void(0);">1万以下<i class="icon_arrow"></i></a></li>
					<li><a href="javascript:void(0);">1-2万<i class="icon_arrow"></i></a></li>
					<li><a href="javascript:void(0);">2-5万<i class="icon_arrow"></i></a></li>
					<li><a href="javascript:void(0);">5-10万<i class="icon_arrow"></i></a></li>
					<li><a href="javascript:void(0);">10万以上<i class="icon_arrow"></i></a></li>
				</ul>
				<a href="<?=urlbuilder::quickSearchCond();?>" class="more" target="_blank">查看更多好生意>></a>
			</div>
				<?php if(arr::get($arr_data,"HotList5")){?>
				<ul class="projectList clearfix" style="display: block;" id="HostList1">
					<?php foreach ($arr_data['HotList5'] as $key=>$val){ $key++;if($key >20){break;}?>	
					<li <?php if($key%4 == 0){echo 'class="last"';}?>>
						<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="title"><?=mb_substr(arr::get($val,"project_title") ? arr::get($val,"project_title") : arr::get($val,"project_brand_name"), 0,15,'UTF-8')."";?></a>
						<span>
								<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="tag_view"><i class="icon_view"><var class="tishivar">浏览量<b></b></var></i><?=arr::get($val,"project_pv_count");?></a>
								<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="tag_time"><i class="icon_time"><var class="tishivar">更新时间<b></b></var></i><?=date("m-d",arr::get($val, "project_passtime",time()))?></a>
						</span>
					</li>
					<?php }?>
				</ul>
			<?php }?>
			<?php if(arr::get($arr_data,"HotList5To10")){?>
				<ul class="projectList clearfix" style="display: none;" id="HostList1To2">
					<?php foreach ($arr_data['HotList5To10'] as $key=>$val){$key++;if($key >20){break;}?>	
					<li <?php if($key%4 == 0){echo 'class="last"';}?>>
						<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="title"><?=mb_substr(arr::get($val,"project_title") ? arr::get($val,"project_title") : arr::get($val,"project_brand_name"), 0,15,'UTF-8')."";?></a>
						<span>
								<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="tag_view"><i class="icon_view"><var class="tishivar">浏览量<b></b></var></i><?=arr::get($val,"project_pv_count");?></a>
								<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="tag_time"><i class="icon_time"><var class="tishivar">发布时间<b></b></var></i><?=date("m-d",arr::get($val, "project_passtime",time()))?></a>
						</span>
					</li>
					<?php }?>
				</ul>
			<?php }?>
			<?php if(arr::get($arr_data,"HotList10To20")){?>
				<ul class="projectList clearfix" style="display: none;" id="HostList2To5">
					<?php foreach ($arr_data['HotList10To20'] as $key=>$val){$key++;if($key >20){break;}?>	
					<li <?php if($key%4 == 0){echo 'class="last"';}?>>
						<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="title"><?=mb_substr(arr::get($val,"project_title") ? arr::get($val,"project_title") : arr::get($val,"project_brand_name"), 0,15,'UTF-8')."";?></a>
						<span>
								<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="tag_view"><i class="icon_view"><var class="tishivar">浏览量<b></b></var></i><?=arr::get($val,"project_pv_count");?></a>
								<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="tag_time"><i class="icon_time"><var class="tishivar">发布时间<b></b></var></i><?=date("m-d",arr::get($val, "project_passtime",time()))?></a>
						</span>
					</li>
					<?php }?>
				</ul>
			<?php }?>
			<?php if(arr::get($arr_data,"HotList20To50")){?>
				<ul class="projectList clearfix" style="display: none;" id="HostList5To10">
					<?php foreach ($arr_data['HotList20To50'] as $key=>$val){if($key >19){break;}?>	
					<li <?php if($key%4 == 0){echo 'class="last"';}?>>
						<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="title"><?=mb_substr(arr::get($val,"project_title") ? arr::get($val,"project_title") : arr::get($val,"project_brand_name"), 0,15,'UTF-8')."";?></a>
						<span>
								<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="tag_view"><i class="icon_view"><var class="tishivar">浏览量<b></b></var></i><?=arr::get($val,"project_pv_count");?></a>
								<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="tag_time"><i class="icon_time"><var class="tishivar">发布时间<b></b></var></i><?=date("m-d",arr::get($val, "project_passtime",time()))?></a>
						</span>
					</li>
					<?php }?>
				</ul>
			<?php }?>
			<?php if(arr::get($arr_data,"HotList50")){?>
				<ul class="projectList clearfix" style="display: none;" id="HostList10">
					<?php foreach ($arr_data['HotList50'] as $key=>$val){$key++;if($key > 20){break;}?>	
					<li <?php if($key%4 == 0){echo 'class="last"';}?>>
						<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="title"><?=mb_substr(arr::get($val,"project_title") ? arr::get($val,"project_title") : arr::get($val,"project_brand_name"), 0,15,'UTF-8')."";?></a>
						<span>
								<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="tag_view"><i class="icon_view"><var class="tishivar">浏览量<b></b></var></i><?=arr::get($val,"project_pv_count");?></a>
								<a target="_blank" href="<?=urlbuilder::qucikProHome(arr::get($val, "project_id"))?>" class="tag_time"><i class="icon_time"><var class="tishivar">发布时间<b></b></var></i><?=date("m-d",arr::get($val, "project_passtime",time()))?></a>
						</span>
					</li>
					<?php }?>
				</ul>
			<?php }?>
		</div>
	</div>
<script type="text/javascript">
	$(".projectList").click(function(){
	//	window.open($(this).find(".title").attr("href"));
		
	})
</script>
