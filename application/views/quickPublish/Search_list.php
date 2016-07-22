<?php echo URL::webcss("common_global.css")?>
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("quickrelease.css")?>
<div class="guidecontent">
<!-- 
	<div class="guidecontent">
	 <div class="guide clearfix">
		<div class="header_logo"><a href="<?=url::website('');?>">一句话商机速配平台</a></div>
		<h1 class="fl">找生意</h1>
		<div class="fl serchbox"><form action="<?=urlbuilder::quickSearchCond();?>" method="get"><input type="text" name="w" value="<?=$word?>" class="serchtext"><input type="submit" class="submitbtn" value="搜索" /></form></div>
                <div class="fr releasebtn"><a href="<?=  urlbuilder::qucikAddPro();?>" class="btn red"><i class="icons icon-edit"></i>免费发布生意</a><p class="alterinfo"><?if($isLogin == true) {?><a href="<?=URL::website("quick/project/showProject")?>" >管理我的生意信息</a><?}else{?>管理我的生意信息<?}?></p></div>
	</div>-->
	<div class="guideserchbox">
                <?if($search_count) {?><div class="jieguo">一句话为您找到符合 <h1><?if($is_search) {?><?=$word?><?}else{?><?=$condWord?><?}?></h1> 条件的有 <var><?=$search_count?></var> 个项目</div><?}else{?>
                <?if($word) {?><div class="jieguo">抱歉！没有找到 <h1> <?=$word?> </h1> 相关的生意信息，看看您可能喜欢的最新生意信息吧</div><?}?>
                <?if(!empty($condWord)) {?><div class="jieguo">抱歉！没有找到 <h1> <?=$condWord?> </h1> 相关的生意信息，看看您可能喜欢的最新生意信息吧</div><?}}?>
		<div class="hyserchbox">
			<div class="clearfix hyserch">
				<span class="fl hyfirst" style="padding-top:5px;">行业分类：</span>
				<div class="hyabox fl hyfirst">
                                    <a href="<?=urlbuilder::quickSearchCond(array('industry_id' => 0), $cond);?>" <?if(arr::get($cond, 'industry_id', 0) == 0) {?> class="on" <?}?> style="margin-left:0;">不限</a>
                                        <?if($industry) {
                                            foreach($industry as $key => $val) {
                                                ?>
                                            <a href="<?=urlbuilder::quickSearchCond(array('industry_id' => $key), $cond);?>" <?if($key == arr::get($cond, 'industry_id', 0) || $key == arr::get($cond, 'pid', 0)) {?> class="on" <?}?> style="margin-left:0;"><?=arr::get($val, 'first_name')?></a>
                                            <?}
                                        }?>
                                </div>
                                <?if(arr::get($cond, 'industry_id', 0)) {?>
				<div class="clearfix erhybox">
					<span class="fl">二级分类：</span>
					<div class="hyabox erhy fl">
                                            <a href="<?=urlbuilder::quickSearchCond(array('industry_id' => arr::get($cond, 'pid', 0)), $cond);?>"style="margin-left:0;" <?if(arr::get($cond, 'pid', 0) == arr::get($cond, 'industry_id', 0)) {?> class="on" <?}?>>不限</a>
                                                <?if(isset($industry[arr::get($cond, 'pid', 0)])) {
                                                        foreach($industry[arr::get($cond, 'pid', 0)]['secord'] as $key => $val) {
                                                            ?>
                                                        <a href="<?=urlbuilder::quickSearchCond(array('industry_id' => $key), $cond);?>" style="margin-left:0;" <?if($key == arr::get($cond, 'industry_id', 0)) {?> class="on" <?}?> ><?=$val?></a>
                                                        <?}
                                                    }?>
                                                </div>
				</div>
                                <?}?>
			</div>
			<div class="clearfix hyserch">
				<span class="fl">投资金额：</span>
				<div class="hyabox fl">
					<a href="<?=urlbuilder::quickSearchCond(array('atype' => 0), $cond);?>" <?if(0 == arr::get($cond, 'atype', 0)) {?> class="on" <?}?> style="margin-left:0;">不限</a>
                                        <a href="<?=urlbuilder::quickSearchCond(array('atype' => 1), $cond);?>" <?if(1 == arr::get($cond, 'atype', 0)) {?> class="on" <?}?> >1万以下</a>
                                        <a href="<?=urlbuilder::quickSearchCond(array('atype' => 2), $cond);?>" <?if(2 == arr::get($cond, 'atype', 0)) {?> class="on" <?}?> >1-2万</a>
                                        <a href="<?=urlbuilder::quickSearchCond(array('atype' => 3), $cond);?>" <?if(3 == arr::get($cond, 'atype', 0)) {?> class="on" <?}?> >2-5万</a>
                                        <a href="<?=urlbuilder::quickSearchCond(array('atype' => 4), $cond);?>" <?if(4 == arr::get($cond, 'atype', 0)) {?> class="on" <?}?> >5-10万</a>
                                        <a href="<?=urlbuilder::quickSearchCond(array('atype' => 5), $cond);?>" <?if(5 == arr::get($cond, 'atype', 0)) {?> class="on" <?}?> >10万以上</a>
				</div>
			</div>
			<div class="clearfix hyserch">
				<span class="fl">招商形式：</span>
				<div class="hyabox fl">
					<a href="<?=urlbuilder::quickSearchCond(array('pmodel' => 0), $cond);?>" <?if(0 == arr::get($cond, 'pmodel', 0)) {?> class="on" <?}?>  style="margin-left:0;">不限</a>
                                        <?if($pModel) {
                                            foreach($pModel as $key => $val) {?>
                                        <a href="<?=urlbuilder::quickSearchCond(array('pmodel' => $key), $cond);?>" <?if($key == arr::get($cond, 'pmodel', 0)) {?> class="on" <?}?> ><?=$val?></a>
                                        <?}}?>
                                </div>
			</div>
			<div class="clearfix">
				<span class="fl">所属地区：</span>
				<div class="hyabox fl">						
					<a <?if(0 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 0), $cond);?>" style="margin-left:0;" >不限</a>
					<?php if(isset($local_arr) && $local_arr){?>
					<a <?if($local_arr['cit_id'] == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => $local_arr['cit_id']), $cond);?>"><?php echo $local_arr['cit_name'];?></a>
					<?php }?>
					<a <?if(10 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 10), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 10){?>style="display:none;"<?php }?>>上海</a>
					<a  <?if(2 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 2), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 2){?>style="display:none;"<?php }?>>北京</a>
					<a <?if(1 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 1), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 1){?>style="display:none;"<?php }?>>广东</a>
					<a <?if(14 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 14), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 14){?>style="display:none;"<?php }?>>福建</a>
					<a <?if(12 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 12), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 12){?>style="display:none;"<?php }?>>浙江</a>
					<a <?if(11 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 11), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 11){?>style="display:none;"<?php }?>>江苏</a>
					<a <?if(16 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 16), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 16){?>style="display:none;"<?php }?>>山东</a>
					<a  <?if(3 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 3), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 3){?>style="display:none;"<?php }?>>天津</a>
					<a <?if(9 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 9), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 9){?>style="display:none;"<?php }?>>黑龙江</a>
					<a <?if(8 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 8), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 8){?>style="display:none;"<?php }?>>吉林</a>
					<a <?if(7 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 7), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 7){?>style="display:none;"<?php }?>>辽宁</a>
					<a  <?if(6 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 6), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 6){?>style="display:none;"<?php }?>>内蒙古</a>
					<a  <?if(4 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 4), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 4){?>style="display:none;"<?php }?>>河北</a>
					<a <?if(17 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 17), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 17){?>style="display:none;"<?php }?>>河南</a>
					<a <?if(19 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 19), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 19){?>style="display:none;"<?php }?>>湖南</a>
					<a <?if(15 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 15), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 15){?>style="display:none;"<?php }?>>江西</a>
					<a <?if(13 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 13), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 13){?>style="display:none;"<?php }?>>安徽</a>
					<a <?if(18 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 18), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 18){?>style="display:none;"<?php }?>>湖北</a>
					<a <?if(22 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 22), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 22){?>style="display:none;"<?php }?>>重庆</a>
					<a <?if(23 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 23), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 23){?>style="display:none;"<?php }?>>四川</a>
					<a  <?if(5 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 5), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 5){?>style="display:none;"<?php }?>>山西</a>
					<a <?if(27 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 27), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 27){?>style="display:none;"<?php }?>>陕西</a>
					<a <?if(25 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 25), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 25){?>style="display:none;"<?php }?>>云南</a>
					<a <?if(20 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 20), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 20){?>style="display:none;"<?php }?>>广西</a>
					<a <?if(21 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?>  href="<?=urlbuilder::quickSearchCond(array('area_id' => 21), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 21){?>style="display:none;"<?php }?>>海南</a>
					<a <?if(24 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 24), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 24){?>style="display:none;"<?php }?>>贵州</a>
					<a <?if(30 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 30), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 30){?>style="display:none;"<?php }?>>宁夏</a>
					<a <?if(28 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 28), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 28){?>style="display:none;"<?php }?>>甘肃</a>
					<a <?if(29 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 29), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 29){?>style="display:none;"<?php }?>>青海</a>
					<a <?if(31 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 31), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 31){?>style="display:none;"<?php }?>>新疆</a>
					<a <?if(26 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 26), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 26){?>style="display:none;"<?php }?>>西藏</a>
					<a <?if(32 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 32), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 32){?>style="display:none;"<?php }?>>台湾</a>
					<a <?if(33 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 33), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 33){?>style="display:none;"<?php }?>>香港</a>
					<a <?if(34 == arr::get($cond, 'area_id', 0)) {?> class="on" <?}?> href="<?=urlbuilder::quickSearchCond(array('area_id' => 34), $cond);?>" <?php if(isset($local_arr['cit_id']) && $local_arr['cit_id'] == 34){?>style="display:none;"<?php }?>>澳门</a>
					
				</div>
			</div>
		</div>
		
	</div>
	<div class="clearfix projectbox">
		<div class="fl serchprojectlist">
				<ul>
                                    <?if($list) {
                                        
                                        foreach($list as $v) {
                                            $projectName = arr::get(arr::get($v['match'], 'val', array()), 'projecttitle', '');
                                            $projectName = $projectName ? $projectName : $v['project_title'];
                                            ?>
					<li>
						<div class="fl">
							<a target="_blank" href="<?php echo urlbuilder::qucikProHome($v['project_id']);?>" class="serchprojectlistbox">
							<img src="<?=URL::imgurl($v['project_logo'])?>" alt="<?php echo $v['project_brand_name'];?>" title="<?php echo $v['project_brand_name'];?>"  onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')" >
						</a>
						</div>
						<div class="fl serlistbox">
                            <h3><a href="<?php echo urlbuilder::qucikProHome($v['project_id']);?>" target="_blank" ><?=$projectName?></a> <?if(arr::get($v, 'imgStatus')) {?><img src="<?=url::webstatic('/images/quickrelease/icon1.png');?>"><?}?> <?if($v['userStatsus']) { if($v['userStatsus'] == 1){ ?><img style="margin-left:40px;" src="<?=url::webstatic("images/quickrelease/icon7.png")?>" >企业已认证<?}else{?><img style="margin-left:40px;" src="<?=url::webstatic("images/quickrelease/icon2.png")?>" >个人已认证<?}}?></h3>
                            <p>
                            <?$title = arr::get($v, 'com_name', '') ? arr::get($v, 'com_name', '') : $v['project_introduction']; ?>
                            <?php if($title){?>
                            <a class="fontyellow" href="<?php echo urlbuilder::qucikProHome($v['project_id']);?>" target="_blank" >
                            <?php echo $title;?>
                            </a>
                            <?php }else{?>
                            <a class="fonthuise" href="<?php echo urlbuilder::qucikProHome($v['project_id']);?>" target="_blank" >
                            <?php echo mb_substr((str_replace('\r','',str_replace("\n",'',zixun::setContentReplace($v['project_summary']) ))),0,35,'UTF-8').'...';?>
                            </a>
                            <?php }?>
                            </p>                           
							<p>
								<b><var class="tishivar">所属行业<em></em></var><img src="<?=url::webstatic('/images/quickrelease/icon3.png');?>"><?php echo $v['industry_name'];?></b>
								<b><var class="tishivar">投资金额<em></em></var><img src="<?=url::webstatic('/images/quickrelease/icon4.png');?>"><span style="color:red;"><?php $monarr= common::moneyArr(); echo arr::get($v, 'project_amount_type') == 0 ? '无': $monarr[arr::get($v, 'project_amount_type')];?></span></b>
								<b><var class="tishivar">加盟地区<em></em></var><img src="<?=url::webstatic('/images/quickrelease/icon5.png');?>"><?php if(isset($v['area']) && $v['area']){                   			
                            $area='';
                            foreach ($v['area'] as $val){
                                $area .= $val['pro_name'];
                                $area = $area.',';
                            }
                            $area = substr($area, 0,strlen($area)-1);
                            echo $area;                           
                   		}
                        ?></b>
							</p>
						</div>
						<span class="serchtime"><?if($v['upTime']) {?><?=$v['upTime']?><?}else{?>暂无<?}?></span>
					</li>
                                    <?}?>
                                    <?}?>
				</ul>
				<div class="ryl_search_result_page" style="margin-bottom:20px"><?=$page?></div>
                                
		</div>
            <?if($advertList) {?>
    <div class="project-right" style="margin-top: 0">
			<dl class="project-list" style="margin-top: 0">
				<dt>招商加盟推荐</dt>
                                <?foreach($advertList as $val) {?>
				<dd>
                                    <a href="<?=urlbuilder::quickAdvert($val['id'])?>" target="_blank" class="img"><img src="<?=URL::imgurl($val['img'])?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')" ></a>
					<a href="<?=urlbuilder::quickAdvert($val['id'])?>" target="_blank" class="title"><?=$val['content']?></a>
				</dd>
                                <?}?>
				
			</dl>
            
		</div>
    <?}?>
	</div>

</div>

<?if(!$is_search && $seoList) {?>
<div class="tag-listbox clearfix">
	<div class="tag-list">
			<dl class="clearfix">
				<dt>热门城市</dt>
				<dd>
					<ul class="clearfix">
                                            <?if($seoList) {
                                                foreach($seoList as $key => $val) {?>
						<li><a href="<?=urlbuilder::quickSearchCond(array('area_id' => arr::get($val, 'area_id', 0), 'atype' => arr::get($val, 'atype', 0), 'pmodel' => arr::get($val, 'pmodel', 0), 'industry_id' => arr::get($val, 'industry_id', 0)))?>"><?=$val['name']?></a></li>
                                                <?}?>
                                            <?}?>
						
					</ul>
				</dd>
			</dl>
			
			
	</div>
    
</div>
<?}?>
    <script type="text/javascript">
    $(function(){
		$("#locationMore").click(function(){
			$(this).parent().animate({height:"84px"}, 200, function(){
				$("#locationMore").hide();
			});
			return false;
		});
    });
	
	nambo_float($(".project-right"), 150, true);
	
	function nambo_float(obj_, bottom_, left_flag_){//设置浮动
		  var obj = obj_;
		  var bottom = bottom_;
		  var left_flag = left_flag_;
		  var float_top = obj.offsetTop;
		  var title_height = $(".quicknavwrap").height()
		  var guide_height = $(".guide").height()
		  var searchbox_height = $(".guideserchbox").height()
		  var footer_height =  100+ 30//$(".footer").height()
		  
		  
	 // var float_left = obj.parentNode.offsetLeft?obj.parentNode.offsetLeft:0;
	 //365
	  window.onscroll = function(e){
		  if($(".serchprojectlist").height() > obj.height())
		  {
				e = e?e:event;
				var scrollHeight = document.documentElement.scrollHeight||document.body.scrollHeight;
				var winHeight = document.documentElement.scrollTop || document.body.scrollTop;
				
			   if(obj.height() > $(window).height())
				{
					var margintop = title_height + guide_height + 10 + searchbox_height + 10 + obj.height()- $(window).height()
					var midtop = $(window).height()-obj.height() //- 30
					var t = scrollHeight - footer_height  - $(window).height() - $(".tag-listbox").height()-10
					var newtop = t - winHeight + $(window).height()-obj.height()
				}else{
					
					var margintop = title_height + guide_height +searchbox_height + 10 
					var midtop = -10
					var t = scrollHeight - footer_height  - obj.height() - $(".tag-listbox").height() -10
					var newtop = scrollHeight - footer_height   - $(".tag-listbox").height() +16 - winHeight -obj.height() 
				}
			  // $("#txt").text(scrollHeight+"||"+winHeight+"|"+margintop+"|"+footer_height)
			  
			  if($(".serchprojectlist").height() > obj.height())
			  {
				   if(winHeight < margintop)
				   {
					   obj.css("position", "static")
					   obj.css("margin-left", "10px")
				   }
				   else if(winHeight >= margintop && winHeight<t)
				   {
					   obj.css("position", "fixed")
					   obj.css("top", midtop)
					   obj.css("margin-left", "790px")
				   }else {
					  
					  obj.css("position", "fixed")
					   obj.css("top", newtop)
					   obj.css("margin-left", "790px")
					  // obj.css("right", "0")
				   }
				   
				  }
			  }else{
				  
				  
			  }
		  }
	}
    </script>

