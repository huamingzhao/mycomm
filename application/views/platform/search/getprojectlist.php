<?php echo URL::webjs("platform/filter/swfobject.js");?>
<?php echo URL::webjs("platform/filter/json2.js");?>
<?php echo URL::webjs("platform/filter/filter.js");?>
<div id="filter">
    <div id="filter_bt">
        <div id="filter_tp">
            <div id="filter_rp">
				<h1>独家项目匹配系统</h1>
					<div id="getprojectlist">
                    	<div class="getWrap">
                        	<div class="getLeft">
                            	<div class="getWord">您搜索的一句话为：</div>
                                <div class="getWord_bg">
                                        <div class="getWord_bg1">"<?if($oneWord) {
                                            foreach($oneWord as $val) {
                                                echo $val;
                                            }
                                            
                                        }?>"</div>
                                </div>
                                <div class="getWord_tags">
                                	<ul>
                                            <?if($allCond) {
                                            foreach($allCond as $key => $val) {?>
                                            <li class="<?=arr::get($allCondCss, $key, 'a')?>"><?=$val;?></li>
                                           <? }
                                            
                                        }?>
                                    </ul>
                                    <div class="arrow"></div>
                                </div>
                                <div class="reMatch"><a title="重新匹配" href="/platform/guide/">重新匹配</a></div>
                            </div>
                            <div class="getRight">
                            	<div class="getTip">
                                	<span>根据您的回答，为您匹配如下项目:</span>
                                    <div class="page1"><span><em><?=$page;?></em>/4</span><?if($page != 1){?><a href="/platform/guide/getprojectlist?page=<?=$page-1;?>">&lt;</a><?}if($page != 4){?><a href="/platform/guide/getprojectlist?page=<?=$page+1;?>">&gt;</a><?}?></div>
                                </div>
                                <div class="getProject">
                                    <?if($projectList) {?>
                                	<ul>
                                            <?foreach($projectList as $val) {?>
                                    	<li>
                                        	<div class="getHover">
                                            	<a href="<?=$val['link']?>"  target="_blank">
                                            	<div><img src="<?=$val['project_logo']?>" /></div>
                                                <div class="getName" title="<?=$val['project_brand_name']?>"><?=mb_substr($val['project_brand_name'],0,6,'UTF-8');?></div>
                                                <p>匹配度：<span><?=$val['perce']?>%</span></p>
                                                <p>已被查看 <span><?=$val['project_pv_count']?></span> 次</p>
                                                </a>
                                            </div>
                                        </li>
                                            <?}?>
                                        
                                    </ul>
                                            <?}?>
                                </div>
                                <div class="clear"></div>
                                <div class="getTip">
                                     <div class="page1"><span><em><?=$page;?></em>/4</span><?if($page != 1){?><a href="/platform/guide/getprojectlist?page=<?=$page-1;?>">&lt;</a><?}if($page != 4){?><a href="/platform/guide/getprojectlist?page=<?=$page+1;?>">&gt;</a><?}?></div>
                               
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>