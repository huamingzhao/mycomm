<div class="searchlistbox clearfix">
	<div class="serchbox">
		<div class="titleserch mt30">
			<h3>搜索 - <i>全部</i></h3>
			<span>
				<a href="javascript:;" class="on">全部</a><a href="javascript:;">文章</a><a href="javascript:;">问题</a><a href="javascript:;">话题</a><a href="javascript:;">用户</a>
			</span>
		</div>
		<div class="serchcontent">
			<div class="serchulbox" style="display:block">
				<ul class="clearfix">
                                    
                                        <?
                                    if($searchUser) {
                                    foreach($searchUser as $val) {?>
					<li class="clearfix searchfirst">
                                            <a href="<?=$val['href']?>"  class="imgbox fl ml10"><img src="<?=$val['img']?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')"></a>
						<div class="fl ml10 wt850">
							<a href="<?=$val['href']?>" class="serchlink"><?=$val['username']?></a>
							<p class="mt5"><?=$val['signature']?></p>
							<p class="mt15 replyinfo iconhua"><a  href="<?=$val['href']?>" target="_self" ><var><?=$val['count']?> </var> 个回答</a></p>
						</div>
					</li>
                                    <?
                                    break;
                                    }
                                    
                                    }
                                    ?>
                                        <?if($searchZixun) {
                                        foreach($searchZixun as $val) {
                                        ?>
					<li class="clearfix searchfirst">
						<a href="<?=$val['href']?>" target="_self" class="imgbox fl ml10"><img src="<?=$val['articleImg']?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')" ></a>
						<div class="fl ml10 wt850">
							<a href="<?=$val['href']?>" target="_self" class="serchlink"><?=$val['articleName']?></a>
							<p class="bcsm mt5"><?=$val['articleContent']?></p>
							</div>
					</li>
                                    <?break;}
                                    }?>
                                        
                                        <?if($searchTag) {?>
                                        <?foreach($searchTag as $val) {?>
					<li class="clearfix searchfirst">
						<a href="<?=$val['href']?>" class="imgbox fl ml10"><img src="<?=$val['img']?>"></a>
						<div class="fl ml10 wt850">
                                                    <a href="<?=$val['href']?>"  class="serchlink"><?=$val['name']?></a>
							<p class="mt15 replyinfo"><span class="wentispan">话题</span><var><?=$val['num']?></var>个问题</p>
						</div>
					</li>
                                        <?break;}}?>
					
					<?if($searchWen) {?>
                                    <?foreach($searchWen as $val) {?>
					<li class="clearfix">
						<a href="<?=$val['userHref']?>"  class="imgbox fl ml10"><img src="<?=$val['userPhoto']?>"  onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')" ></a>
						<div class="fl ml10 wt850">
                                                    <a href="<?=$val['href']?>"  class="serchlink"><?=$val['title']?></a>
                                                        <p class="mt15 replyinfo"><?if($val['tagName']) {?><a href="<?=$val['tagHref']?>" target="_self"  class="replyp"><?=$val['tagName']?><i></i></a><?}?><?if($val['userName']) { ?><a href="<?=$val['userLastHref']?>" target="_self"  class="ml10 mr10 syusername"><?=$val['userName']?></a>回复了问题<?}?><var><?=$val['num']?></var>个回复<span class="ml10"><var><?=$val['view_count']?></var>次浏览</span></p>
						</div>
                                                </li>
                                    <?}?>
                            <?}?>
				</ul>
                <input class="pagevalue" type="hidden" value="1">
                                <?if($searchWen) {?><p class="more mt15">更多</p><?}?>
                                <?if(!$searchUser && !$searchZixun && !$searchTag && !$searchWen) {?>
                                    <div class="serchcontent mt10">
                                        <div class="noserch clearfix">
                                     <img class="fl" src="<?=URL::webstatic('/images/syb/toubg.png')?>">
                                     <div class="fl" style="margin-left: 60px;">
                                       <p class="fz16">抱歉，未找到“<?=$word?>”相关结果</p>
                                       <p class="mt30 fz14" style="font-weight: bold">建议：</p>
                                       <p class="fz14">您可以尝试更换关键词，再次搜索</p>
                                     </div>
                                  </div>
                                        </div>
                                <?}?>
			</div>
			<div class="serchulbox" >
                            <?if($searchZixun) {?>
				<ul class="clearfix">
                                    <?if($searchZixun) {
                                        foreach($searchZixun as $val) {
                                        ?>
					<li class="clearfix">
						<a href="<?=$val['href']?>" target="_self" class="imgbox fl ml10"><img src="<?=$val['articleImg']?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')" ></a>
						<div class="fl ml10 wt850">
							<a href="<?=$val['href']?>" target="_self" class="serchlink"><?=$val['articleName']?></a>
							<p class="bcsm mt5"><?=$val['articleContent']?></p>
							</div>
					</li>
                                    <?}
                                    }?>
				</ul>
                <input class="pagevalue" type="hidden" value="1">
				<p class="more mt15">更多</p>
                            <?}else{?>
                                <div class="serchcontent mt10">
                                    <div class="noserch clearfix">
                                 <img class="fl" src="<?=URL::webstatic('/images/syb/toubg.png')?>">
                                 <div class="fl" style="margin-left: 60px;">
                                   <p class="fz16" style="margin-top:-10px">抱歉，未找到“<?=$word?>”相关结果</p>
                                   <p class="mt30 fz14" style="font-weight: bold">建议：</p>
                                   <p class="fz14">您可以尝试更换关键词，再次搜索</p>
                                 </div>
                              </div>
                                    </div>
                            <?}?>
			</div>
                    
			<div class="serchulbox">
                            <?if($searchWen) {?>
				<ul class="clearfix">
                                    <?foreach($searchWen as $val) {?>
					<li class="clearfix">
						<a href="<?=$val['userHref']?>"   class="imgbox fl ml10"><img src="<?=$val['userPhoto']?>"  onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')" ></a>
						<div class="fl ml10 wt850">
                                                    <a href="<?=$val['href']?>"  class="serchlink"><?=$val['title']?></a>
                                                        <p class="mt15 replyinfo"><?if($val['tagName']) {?><a href="<?=$val['tagHref']?>" target="_self"  class="replyp"><?=$val['tagName']?><i></i></a><?}?><?if($val['userName']) {?><a href="<?=$val['userLastHref']?>" target="_self"  class="ml10 mr10 syusername"><?=$val['userName']?></a>回复了问题<?}?><var><?=$val['num']?></var>个回复<span class="ml10"><var><?=$val['view_count']?></var>次浏览</span></p>
						</div>
					</li>
                                    <?}?>
				</ul>
                <input class="pagevalue" type="hidden" value="1">
				<p class="more mt15">更多</p>
                            <?}else{?>
                                <div class="serchcontent mt10">
                                    <div class="noserch clearfix">
                                 <img class="fl" src="<?=URL::webstatic('/images/syb/toubg.png')?>">
                                 <div class="fl" style="margin-left: 60px;">
                                   <p class="fz16" style="margin-top:-10px">抱歉，未找到“<?=$word?>”相关结果</p>
                                   <p class="mt30 fz14" style="font-weight: bold">建议：</p>
                                   <p class="fz14">您可以尝试更换关键词，再次搜索</p>
                                 </div>
                              </div>
                                    </div>
                            <?}?>
			</div>
			<div class="serchulbox" >
                            <?if($searchTag) {?>
				<ul class="clearfix">
                                    <?foreach($searchTag as $val) {?>
					<li class="clearfix">
						<a href="<?=$val['href']?>"  class="imgbox fl ml10"><img src="<?=$val['img']?>"></a>
						<div class="fl ml10 wt850">
                                                    <a href="<?=$val['href']?>" class="serchlink"><?=$val['name']?></a>
							<p class="mt15 replyinfo"><span class="wentispan">话题</span><var><?=$val['num']?></var>个问题</p>
						</div>
					</li>
                                    <?}?>
				</ul>
                            <input class="pagevalue" type="hidden" value="1">
				<!--<p class="more mt15">更多</p>-->
                            <?}else{?>
                                <div class="serchcontent mt10">
                                    <div class="noserch clearfix">
                                 <img class="fl" src="<?=URL::webstatic('/images/syb/toubg.png')?>">
                                 <div class="fl" style="margin-left: 60px;">
                                   <p class="fz16" style="margin-top:-10px">抱歉，未找到“<?=$word?>”相关结果</p>
                                   <p class="mt30 fz14" style="font-weight: bold">建议：</p>
                                   <p class="fz14">您可以尝试更换关键词，再次搜索</p>
                                 </div>
                              </div>
                                    </div>
                            <?}?>
			</div>
			<div class="serchulbox">
                            <?
                                    if($searchUser) {?>
				<ul class="clearfix">
                                    <?
                                    if($searchUser) {
                                    foreach($searchUser as $val) {?>
					<li class="clearfix">
                                            <a href="<?=$val['href']?>"  class="imgbox fl ml10"><img src="<?=$val['img']?>" onerror="$(this).attr('src', '<?=URL::webstatic('/images/quickrelease/company_default.png')?>')"></a>
						<div class="fl ml10 wt850">
							<a href="<?=$val['href']?>" class="serchlink"><?=$val['username']?></a>
							<p class="mt5"><?=$val['signature']?></p>
							<p class="mt15 replyinfo iconhua"><a  href="<?=$val['href']?>" target="_self" ><var><?=$val['count']?> </var> 个回答</a></p>
						</div>
					</li>
                                    <?}
                                    
                                    }
                                    ?>
				</ul>
                <input class="pagevalue" type="hidden" value="1">
				<p class="more mt15">更多</p>
                                   <?}else{?>
                                <div class="serchcontent mt10">
                                    <div class="noserch clearfix">
                                 <img class="fl" src="<?=URL::webstatic('/images/syb/toubg.png')?>">
                                 <div class="fl" style="margin-left: 60px;">
                                   <p class="fz16" style="margin-top:-10px">抱歉，未找到“<?=$word?>”相关结果</p>
                                   <p class="mt30 fz14" style="font-weight: bold">建议：</p>
                                   <p class="fz14">您可以尝试更换关键词，再次搜索</p>
                                 </div>
                              </div>
                            <?}?>
			</div>
		</div>
	</div>
    <input type="hidden" name="wordHidden" id="wordHidden" value="<?=$word?>" />
</div>
  <script type="text/javascript">
    
    $(".titleserch span a").click(function(){
    	var $index=$(this).index();
    	$(".serchulbox").eq($index).show().siblings().hide();
    	$(this).addClass('on').siblings().removeClass('on');
    	$(".titleserch h3 i").text($(this).text())
    })
	
	$(".more").click(function(){
		$(this).text("加载中...")
		var $index=$(this).parents(".serchulbox").index();
		var url="business/ajaxcheck/getSearchAjax";
		var page=$(this).prev().val();
		page++;
		$(this).prev().val(page);
		var appendhtml="";
		var msg=$.ajaxsubmit(window.$config.wenurl+url,{"word":$("#wordHidden").val(), "type":$index, "page":page})
		if(msg.data.length == 0)
		{
			$(this).hide()
		}
		for(var i=0;i<msg.data.length;i++){
			
			if($index == 0)
			{
				
				appendhtml+='<li class="clearfix"><a class="imgbox fl ml10" target="_self" href="'+msg.data[i].userHref+'"</a><img src='+msg.data[i].userPhoto+'></a><div class="fl ml10 wt850"><a class="serchlink" target="_self" href="'+msg.data[i].href+'">'+msg.data[i].title+'</a><p class="mt15 replyinfo"><a class="replyp" target="_self" href="'+msg.data[i].tagHref+'">'+msg.data[i].tagName+'<i></i></a><a class="ml10 mr10 syusername" target="_self" href="'+msg.data[i].userLastHref+'">'+msg.data[i].userName+'</a>回复了问题<var>'+msg.data[i].num+'</var>个回复<span class="ml10"><var>'+msg.data[i].view_count+'</var>次浏览</span></p></div></li>'
			}else if($index == 1)
			{
				imgurl=msg.data[i].articleImg?window.$config.picurl+msg.data[i].articleImg:window.$config.staticurl+'images/quickrelease/company_default.png';
				
                                if(msg.data[i].userName) {
                                    appendhtml+='<li class="clearfix"><a class="imgbox fl ml10" target="_self" href="'+msg.data[i].userHref+'"</a><img src='+msg.data[i].userPhoto+'></a><div class="fl ml10 wt850"><a class="serchlink" target="_self" href="'+msg.data[i].href+'">'+msg.data[i].title+'</a><p class="mt15 replyinfo"><a class="replyp" target="_self" href="'+msg.data[i].tagHref+'">'+msg.data[i].tagName+'<i></i></a><a class="ml10 mr10 syusername" target="_self" href="'+msg.data[i].userLastHref+'">'+msg.data[i].userName+'</a>回复了问题<var>'+msg.data[i].num+'</var>个回复<span class="ml10"><var>'+msg.data[i].view_count+'</var>次浏览</span></p></div></li>';
                                }else{
                                    appendhtml+='<li class="clearfix"><a class="imgbox fl ml10" target="_self" href="'+msg.data[i].userHref+'"</a><img src='+msg.data[i].userPhoto+'></a><div class="fl ml10 wt850"><a class="serchlink" target="_self" href="'+msg.data[i].href+'">'+msg.data[i].title+'</a><p class="mt15 replyinfo"><a class="replyp" target="_self" href="'+msg.data[i].tagHref+'">'+msg.data[i].tagName+'<i></i></a><var>'+msg.data[i].num+'</var>个回复<span class="ml10"><var>'+msg.data[i].view_count+'</var>次浏览</span></p></div></li>';
                                } 
                                
                            }else if($index == 2)
			{
				imgurl=msg.data[i].userPhoto?window.$config.picurl+msg.data[i].userPhoto:window.$config.staticurl+'images/quickrelease/company_default.png';
				if(msg.data[i].userName) {
                                    appendhtml+='<li class="clearfix"><a class="imgbox fl ml10" target="_self" href="'+msg.data[i].userHref+'"</a><img src='+msg.data[i].userPhoto+'></a><div class="fl ml10 wt850"><a class="serchlink" target="_self" href="'+msg.data[i].href+'">'+msg.data[i].title+'</a><p class="mt15 replyinfo"><a class="replyp" target="_self" href="'+msg.data[i].tagHref+'">'+msg.data[i].tagName+'<i></i></a><a class="ml10 mr10 syusername" target="_self" href="'+msg.data[i].userLastHref+'">'+msg.data[i].userName+'</a>回复了问题<var>'+msg.data[i].num+'</var>个回复<span class="ml10"><var>'+msg.data[i].view_count+'</var>次浏览</span></p></div></li>';
                                }else{
                                    appendhtml+='<li class="clearfix"><a class="imgbox fl ml10" target="_self" href="'+msg.data[i].userHref+'"</a><img src='+msg.data[i].userPhoto+'></a><div class="fl ml10 wt850"><a class="serchlink" target="_self" href="'+msg.data[i].href+'">'+msg.data[i].title+'</a><p class="mt15 replyinfo"><a class="replyp" target="_self" href="'+msg.data[i].tagHref+'">'+msg.data[i].tagName+'<i></i></a><var>'+msg.data[i].num+'</var>个回复<span class="ml10"><var>'+msg.data[i].view_count+'</var>次浏览</span></p></div></li>';
                                } 
                        }else if($index == 3)
			{
				
				
			}else if($index == 4)
			{
				imgurl=msg.data[i].userPhoto?window.$config.picurl+msg.data[i].userPhoto:window.$config.staticurl+'images/quickrelease/company_default.png';
				appendhtml+='<li class="clearfix"><a class="imgbox fl ml10" target="_self" href="'+msg.data[i].userHref+'"><img src='+imgurl+'></a><div class="fl ml10 wt850"><a class="serchlink" target="_self" href="'+msg.data[i].userHref+'">'+msg.data[i].title+'</a><p class="mt5"></p><p class="mt15 replyinfo iconhua"><a href="javascript:;"><var>'+msg.data[i].num+' </var>个回答</p></div></li>'
				
			}
			
		}
		
		$(this).prev().prev().append(appendhtml);
		$(this).text("更多")
	})
  </script>
