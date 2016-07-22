
<script>
var tj_type_id= 3;
var tj_pn_id= <?php echo $data->article_id?>;


</script>
<!-- 中间 -->
         <div class="infor_center ad-pt">
         <?php if ($columnlist!=""){//存在子类的?>
         <div class="ad_caifu_tp">
                <ul>
                <?php foreach ($columnlist as $k=>$v){?>
                    <?php if ($v->column_id==$currentcolumnid){?>
                    <li class="current"><a href="<?php echo zxurlbuilder::xiangmucolumn($v->column_name);?>"><?php echo $v->column_name?></a></li>
                    <?php }else{?>
                    <li><a href="<?php echo zxurlbuilder::xiangmucolumn($v->column_name);?>"><?php echo $v->column_name?></a></li>
                    <?php }?>
                <?php }?>
                </ul>
                <div class="clear"></div>
            </div>
         <?php }?>
         <?php if($data!=""){?>
             <h1 class="ad_list_title"><?php echo $data->article_name?></h1>
            <div class="ad_list_time"><?php echo date('Y年n月d日 H:i',$data->article_addtime)?>更新	　围观: <?php echo $data->article_ding?> 次</div>
            <div class="ad_list_tags">标签：<?php if ($data->article_tag!=""){
                      $data->article_tag = str_replace("，", ',', $data->article_tag);
                      if (strrpos($data->article_tag,',')){
                          $tags = explode(',', $data->article_tag);
                          foreach ($tags as $k=>$tag){
                              if( $k+1==count($tags) ){
                                  $t= '';
                              }else{
                                  $t= ';';
                              }
                              echo '<a href="'.zxurlbuilder::tag($tag).'" title="bfghng">'.$tag.'</a><b>'.$t.'</b>';
                           }
                      }
                      else
                          echo '<a href="'.zxurlbuilder::tag($data->article_tag).'" title="bfghng">'.$data->article_tag.'</a>';
                  }?></div>
            <div class="ad_list_text">
                <?php
                if( $data->article_img!='' ){
                    $img_arr= getimagesize( URL::imgurl( str_replace( "s_","b_",$data->article_img ) ) );
                    if( $img_arr[0]>500 ){
                        $width= '500';
                    }else{
                        $width= '';
                    }
                ?>
                <p class="ad_list_text_img"><img src="<?php echo URL::imgurl( str_replace("s_","b_",$data->article_img) )?>" alt="<?php echo $data->article_name?>" width="<?php echo $width?>" /></p>
                <?php }?>
                <?php echo htmlspecialchars_decode($data->article_content)?>
            </div>
            <div class="ad_list_share">
                <span class="ad_span1" id="ding_id_<?php echo $data->article_id?>" onclick="act_dingcai( <?php echo $data->article_id?>,'up' )"><?php echo $data->article_ding?></span>
                <span class="ad_span2" id="cai_id_<?php echo $data->article_id?>" onclick="act_dingcai( <?php echo $data->article_id?>,'down' )"><?php echo $data->article_cai?></span>
                <span class="ad_span3 person_search_login" onclick="addfavorite('<?php echo $data->article_id?>')">收藏<b class="ad_span3_collect_fc" style="display:none" id="favorite_<?php echo $data->article_id?>"><font>收藏成功<!--去收藏--></font></b></span>
                <span class="ad_list_share_all">

                    <em>
                    <?php if($data->article_img!=""){?>
                         <a href="#" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo zxurlbuilder::zixuninfo($data->article_id,date("Ym",$data->article_intime))?>&pic=<?php echo URL::imgurl( str_replace("s_","b_",$data->article_img))?>&appkey=1343713053&title=<?php echo "【".$data->article_name."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($data->article_content), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($data->article_content) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon08.jpg')?>" /></a>
                         <a href="#" onclick="{ var _t = '<?php echo $data->article_name?>';  var _url = '<?php echo zxurlbuilder::zixuninfo($data->article_id,date("Ym",$data->article_intime))?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = '<?php echo URL::imgurl( str_replace("s_","b_",$data->article_img))?>'; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');  };" ><img src="<?php echo URL::webstatic('images/platform/information/icon09.jpg')?>" /></a>
                         <a href="#" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo zxurlbuilder::zixuninfo($data->article_id,date("Ym",$data->article_intime))?>&pics=<?php echo URL::imgurl( str_replace("s_","b_",$data->article_img))?>&title=<?php echo "【".$data->article_name."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($data->article_content), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($data->article_content) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon10.jpg')?>" /></a>
                         <a href="#" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo zxurlbuilder::zixuninfo($data->article_id,date("Ym",$data->article_intime))?>&pic=<?php echo URL::imgurl( str_replace("s_","b_",$data->article_img))?>&description=<?php echo "【".$data->article_name."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($data->article_content), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($data->article_content) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon11.jpg')?>" /></a>
                    <?php }else{?>
                         <a href="#" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo zxurlbuilder::zixuninfo($data->article_id,date("Ym",$data->article_intime))?>&appkey=1343713053&title=<?php echo "【".$data->article_name."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($data->article_content), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($data->article_content) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon08.jpg')?>" /></a>
                         <a href="#" onclick="{ var _t = '<?php echo $data->article_name?>';  var _url = '<?php echo zxurlbuilder::zixuninfo($data->article_id,date("Ym",$data->article_intime))?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');  };" ><img src="<?php echo URL::webstatic('images/platform/information/icon09.jpg')?>" /></a>
                         <a href="#" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo zxurlbuilder::zixuninfo($data->article_id,date("Ym",$data->article_intime))?>&title=<?php echo "【".$data->article_name."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($data->article_content), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($data->article_content) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon10.jpg')?>" /></a>
                         <a href="#" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo zxurlbuilder::zixuninfo($data->article_id,date("Ym",$data->article_intime))?>&description=<?php echo "【".$data->article_name."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($data->article_content), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($data->article_content) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon11.jpg')?>" /></a>

                    <?php }?>
                      </em>
                      <span>分享到</span>
                </span>
            </div>
            <div class="ad_about_tuijian">
                <div class="ad_tp">相关项目推荐</div>
                <ul>
                <?php
                    if ($project!=""){
                        foreach ($project as $k=>$v){
                            $project_brand_name = UTF8::substr(zixun::setContentReplace($v['project_brand_name']), 0,10);
                            if( UTF8::strlen( zixun::setContentReplace($v['project_brand_name']) )>10 ){
                                $project_brand_name.="...";
                            }
                            if ($k<3){
                                if($v['project_source'] != 1)
                                    echo '<li><a href="'.urlbuilder::project($v['project_id']).'"><img src="'.project::conversionProjectImg($v['project_source'], 'logo', $v).'"/><span>'.$project_brand_name.'</span></a></li>';
                                else
                                    echo '<li><a href="'.urlbuilder::project($v['project_id']).'"><img src="'.URL::imgurl($v['project_logo']).'"/><span>'.$project_brand_name.'</span></a></li>';
                            }
                            if ($k==3){
                                if($v['project_source'] != 1)
                                    echo '<li style="margin-right:0;"><a href="'.urlbuilder::project($v['project_id']).'"><img src="'.project::conversionProjectImg($v['project_source'], 'logo', $v).'"/><span>'.$project_brand_name.'</span></a></li>';
                                else
                                    echo '<li style="margin-right:0;"><a href="'.urlbuilder::project($v['project_id']).'"><img src="'.URL::imgurl($v['project_logo']).'"/><span>'.$project_brand_name.'</span></a></li>';
                            }
                        }
                    }
                ?>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="ad_otherlist">
                <ul>
                  <?php
                  if ($list!=""){
                      foreach ($list as $k=>$v){
                          if ($k%2)
                              echo '<li class="width1"><a href="'.zxurlbuilder::xiangmucolumnxmid(common::getcolumnname($v->column_id),$v->article_id).'">'.$v->article_name.'</a></li>';
                          else
                              echo '<li class="width2"><a href="'.zxurlbuilder::xiangmucolumnxmid(common::getcolumnname($v->column_id),$v->article_id).'">'.$v->article_name.'</a></li>';
                      }
                  }
                ?>
                </ul>
            </div>
            <?php }?>
         </div>
<script>
    $(function(){
        var current_parent = <?=$currentparentid?>;
        if(current_parent>0){
            $("#infor_menu ul li").eq(current_parent).addClass("cur");
        }

    })
</script>