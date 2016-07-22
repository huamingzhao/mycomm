<?php echo URL::webcss("common_global.css")?>
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("quickrelease.css")?>
<div class="guidecontent">
<!-- 
	<div class="guide clearfix">
		 <div class="header_logo"><a href="<?=url::website('');?>">一句话商机速配平台</a></div>
		<h1 class="fl">找生意</h1>
		<div class="fl serchbox"><form action="<?=urlbuilder::quickSearchCond();?>" method="get"><input type="text" name="w" value="" class="serchtext"><input type="submit" class="submitbtn" value="搜索" /></form></div>
        <div class="fr releasebtn"><a href="<?=  urlbuilder::qucikAddPro();?>" class="btn red"><i class="icons icon-edit"></i>免费发布生意</a><p><?if($isLogin == true) {?><a href="<?=URL::website("quick/project/showProject")?>" >管理我的生意信息</a><?}else{?><p  class="alterinfo">管理我的生意信息<?}?></p></div>
		</div>
	-->
	<div class="guidebox">
		<div class="guidenav">
			<ul class="clearfix">
				<li>招商加盟</li>
                                <?if($industry) {
                                    foreach($industry as $key => $val) {
                                        if($key > 80) break;?>
                                <li><a class="white" href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=arr::get($val, 'first_name')?></a>
					<div class="liguideabox clearfix">
                                            <?if(arr::get($val, 'secord', array())) {
                                                foreach(arr::get($val, 'secord', array()) as $keyT => $valT) {?>
                                            <a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $keyT))?>"><?=$valT?></a>
                                            <?}}?>
					</div>
				</li>
                                <?}}?>
				
			</ul>
		</div>
		<div class="guidecon clearfix">
			<div class="guideconlist fl">
				<h3 class="canyin"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 1))?>">餐饮加盟</a></h3>
				<div class="guideabox clearfix">
                                    <?if(isset($industry[1])) {
                                        foreach($industry[1]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
					
				</div>
				<h3 class="shenhuo"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 109))?>">生活服务加盟</a></h3>
				<div class="guideabox clearfix">
                                     <?if(isset($industry[109])) {
                                        foreach($industry[109]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
				</div>
				<h3 class="liansuo"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 2))?>">连锁零售加盟</a></h3>
				<div class="guideabox clearfix">
                                    <?if(isset($industry[2])) {
                                        foreach($industry[2]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
				</div>
				<h3 class="wujin"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 89))?>">五金加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[89])) {
                                        foreach($industry[89]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
				</div>
				<h3 class="qiche"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 129))?>">汽车加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[129])) {
                                        foreach($industry[129]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
				</div>
			</div>
			<div class="guideconlist fl">
				<h3 class="jiaju"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 4))?>">家居建材加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[4])) {
                                        foreach($industry[4]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
				</div>
				<h3 class="zhubao"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 119))?>">珠宝饰品加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[119])) {
                                        foreach($industry[119]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
				</div>
				<h3 class="yule"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 72))?>">娱乐休闲加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[72])) {
                                        foreach($industry[72]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
				</div>
				<h3 class="meirong"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 80))?>">美容养生加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[80])) {
                                        foreach($industry[80]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
				</div>
				<h3 class="jiahuang"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 4))?>">家纺加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[4])) {
                                        foreach($industry[4]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
				</div>
			</div>
			<div class="guideconlist fl" style="border-right:none;">
				<h3 class="xiangbao"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 3))?>">服饰箱包加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[3])) {
                                        foreach($industry[3]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
				</div>
				<h3 class="jiaoyu"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 5))?>">教育培训加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[5])) {
                                        foreach($industry[5]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
				</div>
				<h3 class="muyin"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 103))?>">母婴加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[103])) {
                                        foreach($industry[103]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}}?>
				</div>
				<h3 class="jiushui"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 6))?>">酒水饮料加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[6])) {
                                        foreach($industry[6]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                    echo $qita;
                                        }?>
				</div>
				<h3 class="huanbao"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 137))?>">环保加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[137])) {
                                        foreach($industry[137]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}else{?>
                                        <?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?>
                                        <?}?>
                                        
                                    <?}
                                    echo $qita;
                                        }?>
					
				</div>
				<h3 class="xinqi"><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => 7))?>">新奇特加盟</a></h3>
				<div class="guideabox clearfix">
					 <?if(isset($industry[7])) {
                                        foreach($industry[7]['secord'] as $key => $val) {?>
                                        <?if($val != '其他') {?><a href="<?=  urlbuilder::quickSearchCond(array('industry_id' => $key))?>" ><?=$val?></a><?}?>
                                        <?if($val == '其他'){?><?$qita = "<a href='".urlbuilder::quickSearchCond(array('industry_id' => $key))."' >其他</a>"?><?}?>
                                    <?}
                                        }?>
				</div>
			</div>
		</div>
	</div>
    
	<div class="zstuijian" style="display:none">
		<div class="zstuijianboxlist">
                    <h1>招商加盟推荐</h1>
			<ul class="clearfix">
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
				<li>
					<a href="javascript:;" class="projectlistimgbox pd0"><img src="http://pic.4j4j.cn/upload/pic/20130801/023d1fb693.jpg"></a>
					<p><a href="javascript:;">首款以中华命名的精品白酒</a></p>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="tag-listbox clearfix">
	<div class="tag-list">
			<dl class="clearfix">
				<dt>热门城市</dt>
				<dd>
					<ul class="clearfix">
                                            <?if($seoList) {
                                                foreach($seoList as $key => $val) {?>
						<li><a href="<?=urlbuilder::quickSearchCond(array('area_id' => $val['area_id']))?>"><?=$val['name']?></a></li>
                                                <?}?>
                                            <?}?>
						
					</ul>
				</dd>
			</dl>
			
	</div>
</div>

