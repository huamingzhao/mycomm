
<div class="infor_center ad-pt">
        <div class="clearfix industrynews">
          <h2 class="floleft">行业分类：</h2>
          <div class="floleft">
            <div class="newsabox">
                <?php if($industry) {foreach($industry as $key => $val) {?>
                    <a href="<?php echo zxurlbuilder::industry_news_menu($key);?>" class=<?php if($key==$industry_id || $key==$parent_id) echo "red"; ?>><?=$val['first_name']?></a>
                <?}}?>
            </div>
              <?php if($industry_child) {?>
            <div class="newerbox">
                <?php foreach($industry_child as $k => $v) {
                    if($v != "其他"){
                ?>
                    <a href="<?php echo zxurlbuilder::industry_news_menu($k);?>" class=<?php if($k==$industry_id) echo "red"; ?>><?=$v?></a>
                <?  }
                }?>
            </div>
              <?}?>
          </div>
        </div>

            <div class="infor_newlist" style="padding:0;">
              <ul style="border:none;">
              <?php if ($list!=""){?>
              <?php foreach ($list as $k=>$v){?>
              <li>
                <h3><a target="_blank" href="<?php echo zxurlbuilder::industryzixuninfo($v['article_id'],date("Ym",$v['article_intime']));?>" title="<?php echo $v['article_name']?>">
                <?php
                if (strlen(html_entity_decode(htmlspecialchars_decode($v['article_name'])))>80)
                    echo mb_substr(html_entity_decode(htmlspecialchars_decode($v['article_name'])),0,20,'UTF-8').'...';
                else
                    echo html_entity_decode(htmlspecialchars_decode($v['article_name']));
                ?></a>
                </h3>
                <div class="infor_newlist_left" <?php if( $v['article_img']=='' ){?>infor_newlist_left_noimg<?php }?>" <?php if($v['article_img']=='' ){echo "style='margin-left:0px;width:100%;'";}?>>
                    <label><b><?php echo date('Y年m月d日 H:i',$v['article_checktime'])?>更新</b>
                        <em>标签：</em>
                        <?php if ($v['article_tag']!=""){
                            $v['article_tag'] = str_replace("，", ',', $v['article_tag']);
                            if (strrpos($v['article_tag'],',')){
                                $tags = explode(',', $v['article_tag']);
                                foreach ($tags as $k=>$tag){
                                    if( $k+1==count($tags) ){
                                        $t= '';
                                    }else{
                                        $t= ';';
                                    }

                                    $show_tag = zxurlbuilder::hyxwtag($tag);

                                    echo '<a href='.$show_tag.' title="'.$tag.'">'.$tag.'</a><b>'.$t.'</b>';
                                }
                            }
                            else{
                                    $show_tag = zxurlbuilder::hyxwtag($v['article_tag']);
                                    echo '<a href='.$show_tag.' title="'.$v['article_tag'].'">'.$v['article_tag'].'</a>';
                            }
                        }?>
                    </label>
                  <span class="infor_new_text">
                  <?php echo UTF8::substr(zixun::setContentReplace($v['article_content']), 0,100)?><?php if( UTF8::strlen( zixun::setContentReplace($v['article_content']) )>100 ){?>...<?php }?>
                  </span>
                  <p>
                      <span class="infor_new_iconlist infor_new_iconlist01">
                        <a href="javascript:void(0)" class="infor_new_icon01" onclick="act_dingcai_industry( <?php echo $v['article_id']?>,'up' )"><span id="industry_ding_id_<?php echo $v['article_id']?>"><?php echo $v['article_ding']?></span></a>
                        <a href="javascript:void(0)" class="infor_new_icon02" onclick="act_dingcai_industry( <?php echo $v['article_id']?>,'down' )"><span id="industry_cai_id_<?php echo $v['article_id']?>"><?php echo $v['article_cai']?></span></a>
                      </span>
                      <span class="infor_new_iconlist infor_new_iconlist02">
                      <a href="javascript:void(0)" onmouseover="getfavoriteofindustry('<?php echo $v['article_id']?>')" onclick="addfavorite_industry('<?php echo $v['article_id']?>')" class="infor_new_icon03 person_search_login">收藏</a>
                      <b class="collect_fc" style="display:none" id="favorite_<?php echo $v['article_id']?>"><font id="favorite_eof_<?php echo $v['article_id']?>">收藏成功</font></b>
                      </span>
                      <span class="infor_new_share">
                      <a href="javascript:void(0)" class="infor_new_icon04">分享</a>
                      <em>
                      <?php if ($v['article_img']!=""){?>
                          <a href="#" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo zxurlbuilder::industryzixuninfo($v['article_id'],date("Ym",$v['article_intime']))?>&pic=<?php echo URL::imgurl( str_replace("s_","b_",$v['article_img']))?>&appkey=1343713053&title=<?php echo "【".$v['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($v['article_content']), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($v['article_content']) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon08.jpg')?>" /></a>
                          <a href="#" onclick="{ var _t = '<?php echo $v['article_name']?>';  var _url = '<?php echo zxurlbuilder::industryzixuninfo($v['article_id'],date("Ym",$v['article_intime']))?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = '<?php echo URL::imgurl( str_replace("s_","b_",$v['article_img']))?>'; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');  };" ><img src="<?php echo URL::webstatic('images/platform/information/icon09.jpg')?>" /></a>
                          <a href="#" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo zxurlbuilder::industryzixuninfo($v['article_id'],date("Ym",$v['article_intime']))?>&pics=<?php echo URL::imgurl( str_replace("s_","b_",$v['article_img']))?>&title=<?php echo "【".$v['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($v['article_content']), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($v['article_content']) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon10.jpg')?>" /></a>
                          <a href="#" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo zxurlbuilder::industryzixuninfo($v['article_id'],date("Ym",$v['article_intime']))?>&pic=<?php echo URL::imgurl( str_replace("s_","b_",$v['article_img']))?>&description=<?php echo "【".$v['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($v['article_content']), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($v['article_content']) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon11.jpg')?>" /></a>
                      <?php }else{?>
                         <a href="#" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo zxurlbuilder::industryzixuninfo($v['article_id'],date("Ym",$v['article_intime']))?>&appkey=1343713053&title=<?php echo "【".$v['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($v['article_content']), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($v['article_content']) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon08.jpg')?>" /></a>
                         <a href="#" onclick="{ var _t = '<?php echo $v['article_name']?>';  var _url = '<?php echo zxurlbuilder::industryzixuninfo($v['article_id'],date("Ym",$v['article_intime']))?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+''; window.open( _u,'转播到腾讯微博');  };" ><img src="<?php echo URL::webstatic('images/platform/information/icon09.jpg')?>" /></a>
                         <a href="#" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo zxurlbuilder::industryzixuninfo($v['article_id'],date("Ym",$v['article_intime']))?>&title=<?php echo "【".$v['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($v['article_content']), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($v['article_content']) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon10.jpg')?>" /></a>
                         <a href="#" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo zxurlbuilder::industryzixuninfo($v['article_id'],date("Ym",$v['article_intime']))?>&description=<?php echo "【".$v['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($v['article_content']), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($v['article_content']) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon11.jpg')?>" /></a>
                      <?php }?>
                      </em>
                      </span>
                  </p>
                  <div class="clear"></div>
                </div>
                <?php if ($v['article_img']!=""){?>
                    <?php echo '<div class="infor_newlist_right"><a href="'.zxurlbuilder::zixuninfo($v['article_id'],date("Ym",$v['article_intime'])).'" title="'.$v['article_name'].'"><img src="'.URL::imgurl(str_replace( "s_","b_",$v['article_img'])).'" alt="'.$v['article_name'].'" /></a></div>'?>
                <?php }?>
                <div class="clear"></div>
              </li>
              <?php }?>
              <?php }?>

              </ul>
           </div>
           <div class="ryl_search_result_page">
           <?php echo $page;?>
           </div>
           <div class="clear"></div>
         </div>

<script>
    $(function(){
        var current_parent = <?=$currentparentid?>;
        if(current_parent>0){
            $("#infor_menu ul li").eq(current_parent).addClass("cur");
        }

    })
</script>