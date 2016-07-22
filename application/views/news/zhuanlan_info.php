 <!-- 中间 -->
          <div class="infor_center ad-pt">
            <div class="ad_caifu_tp">
              <ul></ul>
              <div class="clear"></div>
            </div>
            <div class="infor_newlist infor_new_content" style="">
              <ul class="infor_new_content_main" style="border:none;">
                  <!-- 文章中有图片 -->
                  <?php if($article->zl_pic){?>
                  <li>
                  <div class="infor_newlist_right">
                    <a href="<?php echo zxurlbuilder::zhuanlaninfo($article->zl_id);?>" title="<?php echo $article->zl_title;?>">
                      <img width="150" height="120" src="<?php echo URL::imgurl($article->zl_pic);?>" alt="<?php echo $article->zl_title;?>" />
                    </a>
                  </div>
                  <div class="infor_newlist_left ">
                    <h1>
                      <a target="_blank" href="<?php echo zxurlbuilder::zhuanlaninfo($article->zl_id);?>" title="<?php echo $article->zl_title;?>"><?php echo mb_substr($article->zl_title,0,20,'UTF-8');?></a>
                    </h1>
                    <label> <b><?php echo date('Y年n月d日 H:i',$article->zl_shtime)?>更新</b> <em>标签：</em>
                      <?php if( $article->zl_key!='' ){$tags_arr=  explode(',', $article->zl_key);foreach ( $tags_arr as $k=>$tags_vs ){ if($k>6){break;};if( $k+1==count($tags_arr) ){ $t=''; }else{ $t=';'; } echo "<a href='".zxurlbuilder::ptag($tags_vs)."'>".$tags_vs.$t."</a>"; } }?>
                    </label>
                    <span class="infor_new_text">
                      <?php echo zixun::setContentReplace($article->zl_introduce);?>
                    </span>
                    <p>
                      <span class="infor_new_share">
                        <a href="#" class="infor_new_icon04">分享</a>
                        <em class="share" style="display: none;">
                         <?php if ($article->zl_pic!=""){?>
                                        <a href="#" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo url::website("/zixun/ZhuanlanInfo?id=".$article->zl_id."");?>&pic=<?php echo URL::imgurl($article->zl_pic)?>&appkey=1343713053&title=<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon08.jpg')?>" /></a>


                                        <a href="#" onclick="{ var _t = '<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>';  var _url = '<?php echo url::website("/zixun/ZhuanlanInfo?id=".$article->zl_id."");?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = '<?php echo URL::imgurl($article->zl_pic)?>'; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');  };" ><img src="<?php echo URL::webstatic('images/platform/information/icon09.jpg')?>" /></a>


                                        <a href="#" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo url::website("/zixun/ZhuanlanInfo?id=".$article->zl_id."");?>&pics=<?php echo URL::imgurl($article->zl_pic)?>&title=<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon10.jpg')?>" /></a>


                                        <a href="#" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo url::website("/zixun/ZhuanlanInfo?id=".$article->zl_id."");?>&pic=<?php echo URL::imgurl($article->zl_pic)?>&description=<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon11.jpg')?>" /></a>
                    <?php }else{?>
                                        <a href="#" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo url::website("/zixun/ZhuanlanInfo?id=".$article->zl_id."");?>&appkey=1343713053&title=<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon08.jpg')?>" /></a>


                                        <a href="#" onclick="{ var _t = '<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>';  var _url = '<?php echo url::website("/zixun/ZhuanlanInfo?id=".$article->zl_id."");?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');  };" ><img src="<?php echo URL::webstatic('images/platform/information/icon09.jpg')?>" /></a>


                                        <a href="#" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo url::website("/zixun/ZhuanlanInfo?id=".$article->zl_id."");?>&title=<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon10.jpg')?>" /></a>


                                        <a href="#" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo url::website("/zixun/ZhuanlanInfo?id=".$article->zl_id."");?>&description=<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon11.jpg')?>" /></a>

                    <?php }?>
                        </em>
                      </span>
                    </p>
                    <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                </li>
                <?php }else{?>
                  <!-- 文章中没有图片 -->
                <li class="no_img">
                  <div class="infor_newlist_left ">
                    <h1>
                      <a target="_blank" href="<?php echo zxurlbuilder::zhuanlaninfo($article->zl_id);?>" title="<?php echo zixun::setContentReplace($article->zl_title);?>"><?php echo mb_substr(zixun::setContentReplace($article->zl_title),0,20,'UTF-8');?></a>
                    </h1>
                    <label> <b><?php echo date('Y年n月d日 H:i',$article->zl_shtime)?>更新</b> <em>标签：</em>
                      <?php if( $article->zl_key!='' ){$tags_arr=  explode(',', $article->zl_key);foreach ( $tags_arr as $k=>$tags_vs ){ if( $k+1==count($tags_arr) ){ $t=''; }else{ $t=';'; } echo "<a href='".zxurlbuilder::ptag($tags_vs)."'>".$tags_vs.$t."</a>"; } }?>
                    </label>
                    <span class="infor_new_text">
                      <?php echo zixun::setContentReplace($article->zl_introduce);?>
                    </span>
                    <p>
                      <span class="infor_new_share">
                        <a href="#" class="infor_new_icon04">分享</a>
                        <em class="share" style="display: none;">
                        <?php if ($article->zl_pic!=""){?>
                                        <a href="#" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo zxurlbuilder::zhuanlaninfo($article->zl_id);?>&pic=<?php echo URL::imgurl($article->zl_pic)?>&appkey=1343713053&title=<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon08.jpg')?>" /></a>


                                        <a href="#" onclick="{ var _t = '<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>';  var _url = '<?php echo url::website("/zixun/ZhuanlanInfo?id=".$article->zl_id."");?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = '<?php echo URL::imgurl($article->zl_pic)?>'; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');  };" ><img src="<?php echo URL::webstatic('images/platform/information/icon09.jpg')?>" /></a>


                                        <a href="#" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo zxurlbuilder::zhuanlaninfo($article->zl_id);?>&pics=<?php echo URL::imgurl($article->zl_pic)?>&title=<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon10.jpg')?>" /></a>


                                        <a href="#" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo zxurlbuilder::zhuanlaninfo($article->zl_id);?>&pic=<?php echo URL::imgurl($article->zl_pic)?>&description=<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon11.jpg')?>" /></a>
                    <?php }else{?>
                                        <a href="#" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo zxurlbuilder::zhuanlaninfo($article->zl_id);?>&appkey=1343713053&title=<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon08.jpg')?>" /></a>


                                        <a href="#" onclick="{ var _t = '<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>';  var _url = '<?php echo zxurlbuilder::zhuanlaninfo($article->zl_id);?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site; window.open( _u,'转播到腾讯微博');  };" ><img src="<?php echo URL::webstatic('images/platform/information/icon09.jpg')?>" /></a>


                                        <a href="#" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo zxurlbuilder::zhuanlaninfo($article->zl_id);?>&title=<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon10.jpg')?>" /></a>


                                        <a href="#" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo url::website("/zixun/ZhuanlanInfo?id=".$article->zl_id."");?>&description=<?php echo "【".$article->zl_title."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($article->zl_introduce), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($article->zl_introduce) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon11.jpg')?>" /></a>

                    <?php }?>
                        </em>
                      </span>
                    </p>
                    <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                </li>
               <?php }?>
              </ul>
              <!-- 深度解剖开始 -->
              <?php if(zixun::setContentReplace($article->zl_txt)){?>
              <p class="infor_new_content_list_title">深度剖析</p>
              <div class="infor_new_content_sdpx"><?php echo zixun::setContentReplaceByAddKey($article->zl_txt,zixun::setContentApply(true));?></div>
              <?php }?>
              <!-- 深度解剖结束 -->
              <a name="content_list"></a>
              <p class="infor_new_content_list_title">相关文章</p>
              <ul class="infor_new_content_list">
                <!-- 相关文章循环开始 -->
                <?php
                if(empty($rel_list)){
                    $rel_list = array();
                }
                 foreach($rel_list as $k=>$v){
					if(($page->current_page==3) && $k>9){
						break;
					};
                ?>
                <?php
                    if($v->article_img){
                ?>
                  <!-- 相关文章有图开始 -->
                <li class="infor_newlist_zl2">
                  <h3>
                    <font>【<a href="<?php if($v->parent_id == 2){echo zxurlbuilder::xiangmucolumn("$v->column_name");}else{echo zxurlbuilder::column($v->column_name);}?>" title="<?php echo $v->column_name; ?>"><?php echo $v->column_name; ?></a>】</font><a href="<?php echo zxurlbuilder::zixuninfo($v->article_id,date("Ym",$v->article_intime));?>" title="<?php echo zixun::setContentReplace($v->article_name);?>"><?php echo mb_substr(zixun::setContentReplace($v->article_name),0,20,'UTF-8');?></a>
                  </h3>
                  <label> <b><?php echo date('Y年n月d日 H:i',$v->article_addtime);?></b>
                  </label>
                  <div class="infor_newlist_left ">
                    <span class="infor_new_text">
                        <?php echo UTF8::substr(zixun::setContentReplace($v->article_content), 0,100)?><?php if( UTF8::strlen( zixun::setContentReplace($v->article_content) )>100 ){?>...<?php }?>
                    </span>
                    <div class="clear"></div>
                  </div>
                  <div class="infor_newlist_right">
                    <a href="<?php echo zxurlbuilder::zixuninfo($v->article_id,date("Ym",$v->article_intime));?>" title="<?php echo zixun::setContentReplace($v->article_name);?>">
                      <img width="80" height="65" src="<?php echo URL::imgurl($v->article_img);?>" alt="<?php echo zixun::setContentReplace($v->article_name);?>">
                    </a>
                  </div>
                  <div class="clear"></div>
                </li>
                <?php }else{?>
                <!-- 相关文章有图结束 -->
                <!-- 相关文章无图开始 -->
                <li class="infor_newlist_zl2 no_img">
                  <h3>
                    <font>【<a href="<?php if($v->parent_id == 2){echo zxurlbuilder::xiangmucolumn("$v->column_name");}else{echo zxurlbuilder::column($v->column_name);}?>" title="<?php echo $v->column_name; ?>"><?php echo $v->column_name; ?></a>】</font><a href="<?php echo zxurlbuilder::zixuninfo($v->article_id,date("Ym",$v->article_intime));?>" title="<?php echo zixun::setContentReplace($v->article_name);?>"><?php echo mb_substr(zixun::setContentReplace($v->article_name),0,20,'UTF-8');?></a>
                  </h3>
                  <label> <b><?php echo date('Y年n月d日 H:i',$v->article_addtime);?>更新</b>
                  </label>
                  <div class="infor_newlist_left ">
                    <span class="infor_new_text">
                         <?php echo UTF8::substr(zixun::setContentReplace($v->article_content), 0,100)?><?php if( UTF8::strlen( zixun::setContentReplace($v->article_content) )>100 ){?>...<?php }?>
                    </span>
                    <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                </li>
                <!-- 相关文章无图结束 -->
                <?php }}?>
                <!-- 相关文章循环结束 -->
              </ul>
              <p class="infor_new_content_list_more" style="display:none;"><a href="javascript:void(0)" title="加载更多">加载更多...</a></p>
            </div>
            <div class="ryl_search_result_page">
                <?php echo $page;?>
            </div>
            <div class="clear"></div>
            <div class="clear"></div>
          </div>
          <!-- 中间 END -->