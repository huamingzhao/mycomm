
<script>

var tj_type_id= 5;
var tj_pn_id= <?php echo $info['article_id']?>;


</script>

         <!-- 中间 -->
         <div class="infor_center ad-pt">
            <ul class="position">
                <li><font>您的位置：</font></li>
                <li><a href="/zixun/">资讯首页</a>></li>
                <li><a href="<?php echo zxurlbuilder::column($parent_col_name)?>"><?php echo $parent_col_name;?></a>></li>
                <?php if($parent_id){?>
                    <li><a href="<?php echo zxurlbuilder::industry_news_menu($parent_id)?>"><?php echo $parent_name;?></a>></li>
                <?php }?>
                <?php if($industry_id){?>
                    <li><a href="<?php echo zxurlbuilder::industry_news_menu($industry_id)?>"><?php echo $industry_name;?></a>></li>
                <?php }?>


                <li>正文</li>
            </ul>
             <h1 class="ad_list_title"><?php echo $info['article_name']?></h1>
            <div class="ad_list_time"><?php echo date("Y年m月d日 H:i",$info['article_checktime'])?>更新  　<?php if( $info['user_type']=='1' ){?>小编编辑<?php }else{ echo "投稿人:".$author; }?>　围观: <?php echo $pv?> 次</div>
            <div class="ad_list_tags">标签：<?php if( !empty( $tags_arr ) ){foreach ( $tags_arr as $k=>$tgs ){?><span><a href="<?php echo zxurlbuilder::hyxwtag($tgs)?>"><?php echo $tgs?></a></span> <?php if( $k+1==count($tags_arr) ){ echo '';}else{ echo ';'; } ?> <?php }}?></div>
            <div class="ad_list_text">
                <?php
                if( $info['article_img']!='' ){
                    $img_arr= URL::imgurl( str_replace( "s_","b_",$info['article_img'] ) );
                    if(project::checkProLogo($img_arr)){//如果图片是真实存在的
                        $img_arr= getimagesize( $img_arr );
                    }
                    if( $img_arr[0]>500 ){
                        $width= '500';
                    }else{
                        $width= '';
                    }
                    ?>
                    <p class="ad_list_text_img"><img <?php echo $width=='500'?("width='500'"):''; ?> src="<?php echo URL::imgurl( str_replace( "s_","b_",$info['article_img'] ) )?>" alt="<?php echo $info['article_name']?>" /></p>
                <?php }?>

                <?php echo $show_content;?><?php if( $show_act=="yes" ){?><span><a href="javascript:void(0)" class="ad_look_more person_search_login">查看更多详情内容，敬请登录</a></span><?php }?>

            </div>


            <?php if( $show_act=="yes" ){?>
            <div class="ad_login">
                <a href="javascript:void(0)" class="ad_login_btn person_search_login"></a>
                <span>还不是一句话用户？<a href="<?php echo urlbuilder::register("geren");?><?php if($to_url){ echo "?to_url=".$to_url; ?><?php }?>">免费注册</a></span>
            </div>
            <?php }?>
            <div class="ad_list_text01"></div>
            <div class="ad_list_share">
                <span class="ad_span1 class_ding_industry" id="industry_ding_id_<?php echo $info['article_id']?>" onclick="act_dingcai_industry( <?php echo $info['article_id']?>,'up' )"></span>
                <span class="ad_span2 class_cai_industry" id="industry_cai_id_<?php echo $info['article_id']?>" onclick="act_dingcai_industry( <?php echo $info['article_id']?>,'down' )"></span>
                <span class="ad_span3 person_search_login" onmouseover="getfavoriteofindustry('<?php echo $info['article_id']?>')" onclick="addfavorite_industry('<?php echo $info['article_id']?>')">收藏<b class="ad_span3_collect_fc" style="display:none" id="favorite_<?php echo $info['article_id']?>"><font id="favorite_eof_<?php echo $info['article_id']?>">收藏成功<!--去收藏--></font></b></span>

                <span class="ad_list_share_all">

                    <em>
                        <?php if ($info['article_img']!=""){?>
                            <a href="#" class="sina" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo zxurlbuilder::zixuninfo($info['article_id'],date("Ym",$info['article_intime']))?>&pic=<?php echo URL::imgurl($info['article_img'])?>&appkey=1343713053&title=<?php echo "【".$info['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($show_content), 0,50);?>...');return false;"></a>

                            <a href="#" class="renren" onclick="{ var _t = '<?php echo "【".$info['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($show_content), 0,50);?>...';  var _url = '<?php echo zxurlbuilder::zixuninfo($info['article_id'],date("Ym",$info['article_intime']))?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = '<?php echo URL::imgurl($info['article_img'])?>'; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');  };"></a>

                            <a href="#" class="weibo" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo zxurlbuilder::zixuninfo($info['article_id'],date("Ym",$info['article_intime']))?>&pic=<?php echo URL::imgurl($info['article_img'])?>&description=<?php echo "【".$info['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($show_content), 0,50)?>...');return false;"></a>

                            <a href="#" class="kongjian" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo zxurlbuilder::zixuninfo($info['article_id'],date("Ym",$info['article_intime']))?>&pics=<?php echo URL::imgurl($info['article_img'])?>&title=<?php echo "【".$info['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($show_content), 0,50)?>...');return false;"></a>
                        <?php }else{?>
                            <a href="#" class="sina" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo zxurlbuilder::zixuninfo($info['article_id'],date("Ym",$info['article_intime']))?>&appkey=1343713053&title=<?php echo "【".$info['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($show_content), 0,50);?>...');return false;"></a>

                            <a href="#" class="renren" onclick="{ var _t = '<?php echo "【".$info['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($show_content), 0,50);?>...';  var _url = '<?php echo zxurlbuilder::zixuninfo($info['article_id'],date("Ym",$info['article_intime']))?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+''; window.open( _u,'转播到腾讯微博');  };"></a>

                            <a href="#" class="weibo" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo zxurlbuilder::zixuninfo($info['article_id'],date("Ym",$info['article_intime']))?>&description=<?php echo "【".$info['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($show_content), 0,50)?>...');return false;"></a>

                            <a href="#" class="kongjian" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo zxurlbuilder::zixuninfo($info['article_id'],date("Ym",$info['article_intime']))?>&title=<?php echo "【".$info['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($show_content), 0,50)?>...');return false;"></a>

                        <?php }?>
                    </em>
                <span>分享到</span>
                </span>
            </div>
            <?php if( !empty( $like_art_id_arr ) ){?>
            <div class="ad_otherlist">
                <span class="you_like_it">您可能也喜欢</span>
                <ul>
                    <?php
                    foreach( $like_art_id_arr as $k=>$like_art_id_vss ){
                        if( $k%2==0 ){
                            $class= "width1";
                        }else{
                            $class= "width2";
                        }
                    ?>
                    <li class="<?php echo $class?>"><a title="<?php echo strip_tags($like_art_name_arr[$k]);?>" href="<?php echo zxurlbuilder::zixuninfo($like_art_id_vss,date("Ym",$like_art_time_arr[$k]))?>"><?php echo UTF8::substr(strip_tags($like_art_name_arr[$k]), 0,15);if( UTF8::strlen( strip_tags($like_art_name_arr[$k]) )>15 ){ echo "..."; }?></a></li>
                <?php }?>

                </ul>
            </div>
            <?php }?>
            <?php if( $page_show=='yes' ){?>
            <div class="ad_otherpage">
                <span class="span1">上一篇：<a href="<?php echo zxurlbuilder::zixuninfo($up_art['id'],date("Ym",$up_art['article_intime']))?>" title="<?php echo $up_art['name']?>"><?php echo UTF8::substr($up_art['name'], 0,15)?></a></span>
                <span class="span2">下一篇：<a href="<?php echo zxurlbuilder::zixuninfo($down_art['id'],date("Ym",$down_art['article_intime']))?>" title="<?php echo $down_art['name']?>"><?php echo UTF8::substr($down_art['name'], 0,10)?></a></span>
            </div>
            <?php }?>
         <div class="clear"></div>
        <div class="askrukou">如果您有任何创业疑难杂症,快去找<a href="<?php echo URL::webwen('');?>">创业问题我知道</a>为您答疑解惑吧！</div>
      </div>