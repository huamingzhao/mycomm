<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("huodong.css")?>
<?php echo URL::webjs("commom_global.js")?>
<body>
<div class="huodongnav">
	<div class="search_news">
      <ul id="searchNews">
      	<?php if($bobaolist){?>
      	<?php foreach($bobaolist as $v){?>      	
      	<li><?php echo $v;?></li>      	
      	<?php }?>
      	<?php }?>
      </ul>
    </div>
    <a href="<?php echo URL::website('/zt5/index.shtml');?>#huodongcon" class="poabtn"></a>
</div>
<div class="huodongnavbox">
	<ul>
		<li>最佳创业</li>
		<li>创业首选</li>
		<li>最新商机</li>
		<li>精品推荐</li>
		<li>知名品牌</li>
		<li>商家推荐</li>
	</ul>
</div>
<div class="huodong">
	<?php if($list1){?>
	<div class="shangji zuijia">
		<div class="aimgbox clearfix">	
		<?php $arr = array_slice($list1, 0,14);
			if($arr){
				foreach($arr as $v){
		?>						
			<a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank"  class="pic_box"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?if($v['project_source'] != 1) {$img =  project::conversionProjectImg($v['project_source'], 'logo', $v);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($v['project_logo']);}?>" /></a>
		<?php 
				}
			}
		?>
		</div>
		<ul class="zuijiaul clearfix">
			<li>
			<?php $arr = array_slice($list1, 14);
				if($arr){
					foreach($arr as $k => $v){
			?>		
				<?php if($k % 5 == 0 && $k != 0){?>
				</li>
				<li>
				<?php }?>
				<p><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank" ><?php echo $v['project_advert'] != '' ? $v['project_advert'] : $v['project_brand_name'];?> </a></p>
			<?php 
					}
				}
			?>
			</li>			
		</ul>
	</div>
	<?php }?>
	<div class="shangji shouxuan">
		<div class="title">创业首选</div>
		<?php if($list2){?>
		<div class="cycontent">			
			<ul class="cyulbox clearfix">
			<?php $arr = array_slice($list2, 0,6);
				if($arr){
					foreach($arr as $v){
			?>		
				<li class="clearfix">
					<a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank"  class="fl pic_box pic_box1"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?if($v['project_source'] != 1) {$img =  project::conversionProjectImg($v['project_source'], 'logo', $v);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($v['project_logo']);}?>" /></a>
					<div class="fl flconbox">
						<p class="contitle"><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank" ><?php echo $v['project_advert'] != '' ? $v['project_advert'] : $v['project_brand_name'];?></a></p>
						<p class="psite clearfix"><span class="floleft"><var>¥ </var><i><?php $monarr= common::moneyArr(); echo arr::get($v, 'project_amount_type') == 0 ? '无': $monarr[arr::get($v, 'project_amount_type')];?></i></span><span class="sitespan ml10 floright" style="margin-top:3px;"><em class="browsed_fc01">品牌发源地</em><?php echo $v['project_brand_birthplace'] ? mb_substr($v['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?></span></p>
						<p><a target="_blank" href="<?php echo urlbuilder::project($v['project_id']);?>" class="abtn">投递名片</a></p>
					</div>
				</li>	
			<?php 
					}
				}
			?>			
			</ul>
			<ul class="zuijiaul clearfix">
			<li>
			<?php $arr = array_slice($list2, 6);
				if($arr){
					foreach($arr as $k => $v){
			?>		
				<?php if($k % 5 == 0 && $k != 0){?>
				</li>
				<li>
				<?php }?>
				<p><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank" ><?php echo $v['project_advert'] != '' ? $v['project_advert'] : $v['project_brand_name'];?> </a></p>
			<?php 
					}
				}
			?>
			</li>			
		</ul>
		</div>
		<?php }?>
	</div>
	<div class="shangji">
		<div class="title">最新商机</div>
		<?php if($list3){?>
		<div class="shangjicon clearfix">
			<div class="fl sjproducts">
				<h2>最佳咨询排行</h2>
				<ul>
					<?php $arr = array_slice($list3, 0,10);
						if($arr){
							foreach($arr as $k=>$v){
					?>
					<li><span><?php echo $k+1;?></span><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank" ><?php echo $v['project_advert'] != '' ? $v['project_advert'] : $v['project_brand_name'];?></a></li>
					<?php 
							}
						}
					?>
				</ul>
			</div>
			<ul class="fl zuixinsj">
				<?php $arr = array_slice($list3, 10);
					if($arr){
						foreach($arr as $k => $v){
				?>
				<li><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank"  class="pic_box pic_box1"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?if($v['project_source'] != 1) {$img =  project::conversionProjectImg($v['project_source'], 'logo', $v);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($v['project_logo']);}?>" /></a><p><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank" ><?php echo $v['project_advert'] != '' ? $v['project_advert'] : $v['project_brand_name'];?></a></p></li>				
				<?php 
						}
					}
				?>
			</ul>
		</div>
		<?php }?>
	</div>
	<div class="shangji">
		<div class="title">精品推荐</div>
		<?php if($list4){?>
		<div class="shangjicon clearfix">
			<div class="fl">				
				<ul class="jpul clearfix">
					<?php $arr = array_slice($list4, 0,5);
						if($arr){
							foreach($arr as $k => $v){
					?>		
					<li>
						<a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank"  class="pic_box">
							<img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?if($v['project_source'] != 1) {$img =  project::conversionProjectImg($v['project_source'], 'logo', $v);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($v['project_logo']);}?>" />
						</a>
						<p class="pfont"><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank"><?php echo $v['project_advert'] != '' ? $v['project_advert'] : $v['project_brand_name'];?></a></p>
						<p class="psite bl ml5"><span><var>¥ </var><i><?php $monarr= common::moneyArr(); echo arr::get($v, 'project_amount_type') == 0 ? '无': $monarr[arr::get($v, 'project_amount_type')];?></i></span></p>
						<p class="mt10 ml5"><span class="sitespan"><em class="browsed_fc01">品牌发源地</em><?php echo $v['project_brand_birthplace'] ? mb_substr($v['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?></span><span class="spanhot ml10"><em class="browsed_fc02">项目人气</em><?php if($v['project_pv_count'] == 0){echo "1";}else{echo $v['project_pv_count'];}?></span></p>
						<p class="wt70auto"><a target="_blank" href="<?php echo urlbuilder::project($v['project_id']);?>" class="abtn">投递名片</a></p>
					</li>					
					<?php 
							}
						}
					?>
				</ul>				
				<ul class="imgulbox clearfix">
					<?php $arr = array_slice($list4, 5,5);
						if($arr){
							foreach($arr as $k => $v){
					?>	
					<li>
						<a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank"  class="pic_box"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?if($v['project_source'] != 1) {$img =  project::conversionProjectImg($v['project_source'], 'logo', $v);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($v['project_logo']);}?>" /></a>
					</li>
					<?php 
							}
						}
					?>
				</ul>
			</div>
			<div class="fl sjproducts" style="margin-left:30px;">
				<h2>最佳咨询排行</h2>
				<ul>
					<?php $arr = array_slice($list4, 10);
						if($arr){
							foreach($arr as $k => $v){
					?>	
					<li><span><?php echo $k+1;?></span><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank" ><?php echo $v['project_advert'] != '' ? $v['project_advert'] : $v['project_brand_name'];?></a></li>					
					<?php 
							}
						}
					?>
				</ul>
			</div>
		</div>
		<?php }?>
	</div>
	<div class="shangji">
		<div class="title">知名名牌</div>
		<?php if($list5){?>
		<div class="shangjicon clearfix">
			<ul class="zmulbox mt5 fl">
				<?php $arr = array_slice($list5, 0,2);
					if($arr){
						foreach($arr as $k => $v){
				?>
				<li class="clearfix">
					<a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank" class="fl pic_box pic_box1"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?if($v['project_source'] != 1) {$img =  project::conversionProjectImg($v['project_source'], 'logo', $v);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($v['project_logo']);}?>" /></a>
					<div class="fl" style="width:80px;">
						<p class="psite ml10" style="margin-top: 0"><span><var>¥ </var><i><?php $monarr= common::moneyArr(); echo arr::get($v, 'project_amount_type') == 0 ? '无': $monarr[arr::get($v, 'project_amount_type')];?></i></span></p>
						<p class="mt10 ml10"><span class="sitespan"><em class="browsed_fc01">品牌发源地</em><?php echo $v['project_brand_birthplace'] ? mb_substr($v['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?></span></p>
						<p class="mt10 ml10"><span class="spanhot" style="float: none;"><em class="browsed_fc02">项目人气</em><?php if($v['project_pv_count'] == 0){echo "1";}else{echo $v['project_pv_count'];}?></span></p>
						<p class="ml10"><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank" class="abtn">投递名片</a></p>
					</div>
					<p class="contitle hl35" style="clear:both;"><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank"><?php echo $v['project_advert'] != '' ? $v['project_advert'] : $v['project_brand_name'];?></a></p>
				</li>
				<?php 
						}
					}
				?>
			</ul>
			<ul class="imgulbox fl clearfix" style="width:690px;">
				<?php $arr = array_slice($list5, 2);
					if($arr){
						foreach($arr as $k => $v){
				?>	
				<li>
					<a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank" class="pic_box"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?if($v['project_source'] != 1) {$img =  project::conversionProjectImg($v['project_source'], 'logo', $v);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($v['project_logo']);}?>" /></a>
				</li>
				<?php 
						}
					}
				?>					
			</ul>
		</div>
		<?php }?>
	</div>
	<div class="shangji">
		<div class="title">商家推荐</div>
		<?php if($list6){?>
		<div class="shangjicon clearfix">
			<ul class="jpul clearfix">
				<?php $arr = array_slice($list6, 0,7);
					if($arr){
						foreach($arr as $k => $v){
				?>	
				<li>
					<a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank" class="pic_box">
						<img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?if($v['project_source'] != 1) {$img =  project::conversionProjectImg($v['project_source'], 'logo', $v);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($v['project_logo']);}?>" />
					</a>
					<p class="pfont"><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank"><?php echo $v['project_advert'] != '' ? $v['project_advert'] : $v['project_brand_name'];?></a></p>
					<p class="psite bl ml5"><span><var>¥ </var><i><?php $monarr= common::moneyArr(); echo arr::get($v, 'project_amount_type') == 0 ? '无': $monarr[arr::get($v, 'project_amount_type')];?></i></span></p>
					<p class="mt10 ml5"><span class="sitespan"><em class="browsed_fc01">品牌发源地</em><?php echo $v['project_brand_birthplace'] ? mb_substr($v['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?></span><span class="spanhot ml10"><em class="browsed_fc02">项目人气</em><?php if($v['project_pv_count'] == 0){echo "1";}else{echo $v['project_pv_count'];}?></span></p>
					<p class="wt70auto"><a target="_blank" href="<?php echo urlbuilder::project($v['project_id']);?>" class="abtn">投递名片</a></p>
				</li>
				<?php 
						}
					}
				?>	
			</ul>
			<ul class="zmulbox sjzmulbox mt5 fl">
				<?php $arr = array_slice($list6, 7);
					if($arr){
						foreach($arr as $k => $v){
				?>
				<li class="clearfix">
					<a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank" class="fl pic_box"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?if($v['project_source'] != 1) {$img =  project::conversionProjectImg($v['project_source'], 'logo', $v);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($v['project_logo']);}?>" /></a>
					<div class="fl">
						<p class="psite ml10 bl" style="margin-top: 0"><span><var>¥ </var><i><?php $monarr= common::moneyArr(); echo arr::get($v, 'project_amount_type') == 0 ? '无': $monarr[arr::get($v, 'project_amount_type')];?></i></span></p>
						<p class="mt10 ml10"><span class="sitespan"><em class="browsed_fc01">品牌发源地</em><?php echo $v['project_brand_birthplace'] ? mb_substr($v['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?></span></p>
						<p class="ml10 mt5"><a target="_blank" href="<?php echo urlbuilder::project($v['project_id']);?>" class="abtn">投递名片</a></p>
					</div>
					<p class="contitle hl35" style="clear:both;"><a href="<?php echo urlbuilder::project($v['project_id']);?>" target="_blank"><?php echo $v['project_advert'] != '' ? $v['project_advert'] : $v['project_brand_name'];?></a></p>
				</li>
				<?php 
						}
					}
				?>	
			</ul>
		</div>
		<?php }?>
	</div>
</div>

</body>
<script type="text/javascript">
$(window).scroll(function(){
sroll();
});
$(window).resize(function(event) {
	sroll();
});
var num=381;
function sroll(){
	
    if($(window).scrollTop()>num){
        $(".huodongnavbox").css("top",0);
    }
    else{
       $(".huodongnavbox").css("top",num-$(window).scrollTop())
    }
}
$(".huodongnavbox ul li").click(function(){
	var $index=$(this).index();
	$(window).scrollTop($(".huodong").find('.shangji').eq($index).offset().top-59)
	
})
</script>
</html>
