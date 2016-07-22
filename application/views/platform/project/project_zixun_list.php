<?php if(!$is_has_image){//无广告版 引入该CSS?>
    <?php echo URL::webcss("platform/project_home_no_ad.css")?>
<?php }?>
<div class="project_home_content">
<!--左侧部分-->
<div class="project_home_content_left">
<!--左侧部分头部-->
<!-- 无广告图展示开始 -->
<?php if(!$is_has_image){ ?>
<?php }else{?>
    <!-- 无广告图展示结束 -->
    <!-- 有广告图展示开始 -->
    <div class="project_home_content_info_div">
        <?php if($is_has_image){?>
            <div class="project_home_info_img project_home_info_img_auto">
                <img src="<?php echo $bigimage;?>" alt="<?php echo $projectinfo->project_brand_name;?>开店加盟"/>
            </div>
        <?php }?>
        <div class="clear"></div>
    </div>
<?php }?>
<!-- 有广告图展示结束 -->
<!--左侧部分头部 END-->
<!--左侧部分正文-->
<div class="project_home_content_main">
<ul class="menu">
    <li class=""><a href="javascript:void(0)" title="项目介绍">项目介绍</a></li>
    <?php if($projectinfo->product_features){//产品特点?>
        <li><a href="javascript:void(0)" title="产品特点">产品特点</a></li>
    <?php }?>

    <?php if($projectinfo->project_join_conditions){//加盟详情?>
        <li class="project_home_last"><a href="javascript:void(0)" title="加盟详情">加盟详情</a></li>
    <?php }?>
    <?php if(!empty($history_consult) && count($history_consult)>10){//历史咨询?>
        <li class="project_home_last" data-flag="false"><a href="javascript:void(0)" title="历史咨询">历史咨询</a></li>
    <?php }?>
    <?php if( !empty( $article_list ) ){?>
        <li class=" project_home_fc ">
            <a href="<?php echo URL::website('/info/'.$projectinfo->project_id.'.html')?>" title="项目新闻">项目新闻</a>
        </li>
    <?php }?>
    <?php if( !empty( $industry_article_list ) ){?>
        <li class="project_home_last">
            <a href="<?php echo URL::website('/industry/'.$projectinfo->project_id.'.html')?>" title="行业新闻">行业新闻</a>
        </li>
    <?php }?>
    <div class="clear"></div>
</ul>
<div class="project_home_info_intention"><!-- 意向加盟者<font><?php //echo $pro_industry_count;?></font>人 -->
    <!--<br/>申请加盟者<font><?php echo $jiaomeng_count;?></font>人-->
</div>
<div id="content_text1" class="project_home_content_text project_home_content_text1" style="display:none;">

    <div>
        <?php if(isset($companyinfo->com_name) && $industry_zhong != "" && $projectinfo->project_amount_type!= 0 && $area_zhong != ""){?>

            <div class="clearfix">
                <?php if($xuanchuan_project_logo){//存在项目推广小图?>
                    <p class="project_home_content_text_img_icon">
                            <span>
                                <label>
                                    <img alt="<?php echo $projectinfo->project_brand_name;?>加盟" width="150" height="120" src="<? echo $xuanchuan_project_logo;?>"/>
                                </label>
                            </span>
                    </p>
                <?php }?>
                <h2 class="superiority_title2"><?php echo $projectinfo->project_brand_name; ?>加盟优势</h2>
                <p><?php echo $projectinfo->project_brand_name; ?>是<?php echo $companyinfo->com_name;?>的最成功的的产品之一。<?php echo $companyinfo->com_name;?>拥有一支专业化的团队，以卓越的服务品质和超越的经营策略打造极具市场竞争力的强势品牌。在<?php echo $industry_zhong;?>领域里，<?php echo $projectinfo->project_brand_name; ?>拥有很大的市场份额，<?php echo $monarr[$projectinfo->project_amount_type];?>就可以加盟、开店、创业，经常在<?php echo $area_zhong;?>举办招商会、投资考察会。加盟<?php echo $projectinfo->project_brand_name; ?>的最好办法就是通过一句话招商平台，每月都有大量的<?php echo $industry_zhong;?>投资考察会。<?php echo $projectinfo->project_brand_name; ?>加盟会是您创业项目的最好选择！</p>
            </div>
        <?php }else{?>

            <div class="clearfix">
                <?php if($xuanchuan_project_logo){//存在项目推广小图?>
                    <p class="project_home_content_text_img_icon">
                    <span>
                                <label>
                                    <img alt="<?php echo $projectinfo->project_brand_name;?>加盟" width="150" height="120" src="<? echo $xuanchuan_project_logo;?>"/>
                                </label>
                            </span>
                    </p><?php }?>
                <h2 class="superiority_title"><?php echo $projectinfo->project_brand_name; ?>开店加盟优势</h2><br/>
                <p>一句话加盟网提供<?php echo $projectinfo->project_brand_name; ?>开店加盟信息，<?php echo $projectinfo->project_brand_name; ?>在全国各地都有招商加盟会，<?php echo $projectinfo->project_brand_name; ?>投资考察以最好的服务、最全的产品线、优秀的品质及拥有同行业下最大化的市场份额，让<?php echo $projectinfo->project_brand_name; ?>开店加盟、投资、赚钱更容易。加盟<?php echo $projectinfo->project_brand_name; ?>的形式有：<?php $lst = common::businessForm();
                    $pro_count=count($projectcomodel);
                    if($pro_count){
                        $comodel_text=0;
                        foreach ($projectcomodel as $v){
                            $comodel_text++;
                            echo $lst[$v];
                            if($comodel_text < $pro_count){
                                echo '、';
                            }
                        }
                    }else{echo "全国";} ?>，低门槛、高收益。<?php echo $projectinfo->project_brand_name; ?>项目拥有完善的管理机制，开店、加盟、投资、创业的朋友们，选择<?php echo $projectinfo->project_brand_name; ?>是正确的选择，我们推荐的项目<?php echo $projectinfo->project_brand_name; ?>适合各种创业群体。
                </p>
            </div>
        <?php }?>
        <p><br/>
            <?php echo htmlspecialchars_decode($projectinfo->project_summary);//项目介绍?>
        </p>
    </div>
    <div id="project_home_content_text_other" style="display:block;">
        <?php if( $projectinfo->product_features){?>
            <h2><p class="title"><?php echo $projectinfo->project_brand_name;?>产品特点</p></h2>
            <div class="project_info_other"><?php echo htmlspecialchars_decode($projectinfo->product_features);//产品特点?></div>
        <?php }?>

        <?php if( $projectinfo->project_join_conditions){?>
            <h2><p class="title"><?php echo $projectinfo->project_brand_name;?>加盟详情</p></h2>
            <div class="project_info_other">
                <?php echo htmlspecialchars_decode($projectinfo->project_join_conditions);//加盟详情?>
            </div>
        <?php }?>
        <p class="project_home_content_btn clearfix" id="jubao_span">
	                        <span class="project_home_other_btn">
	                        <?php if(isset($isshowrenling) && $isshowrenling){//显示认领图标 ?>
                                <a rel="nofollow" id="renling"  class="project_home_content_report1" href="javascript:void(0)" title="认领">认领</a>
                                <i class="project_home_other_btn_msg"><img alt="认领" src="<?php echo URL::webstatic("images/platform/project_home/project_home_renling_03.png")?>">这是您企业的项目吗？您需要先注册为该企业用户才能认领此项目</i>
                                <a id="renling_projectid" style="display:none"><?php echo $projectinfo->project_id;?></a>
                            <?php }?>
                                <a id="jubao_<?php echo $projectinfo->project_id;?>" class="project_home_content_report" href="javascript:void(0)" title="举报" rel="nofollow">举报</a>
	                        </span>
        </p>
        <div class="clear"></div>
        <!-- 历史咨询切换开始 -->
        <?php if(!empty($history_consult)){?>
            <h2><p class="title"><?php echo $projectinfo->project_brand_name;?>历史咨询</p></h2>
            <div class="project_info_other clearfix">
                <table cellspacing="0" cellpadding="0" class="hisconsulttab">
                    <?php
                    $history_consult2 = array();
                    $history_consult2 = $history_consult;
                    foreach(array_slice($history_consult2,0,10) as $k=>$v){?>
                        <?php if($v['reply_content'] && $v['reply_time'] && $v['reply_time']<=time()){?>
                            <tr>
                                <td class="wt90">
                                    <div class="imgwidth">
                                        <img onerror="$(this).attr('src', '<?= URL::webstatic("/images/company_center/icon_33.png");?>')" src="<?php echo $v['photo'];?>">
                                    </div>
                                </td>
                                <td class="wt125">
                                    <p><?php echo $v['user_name'];?></p>
                                    <p class="c999"><?php echo $v['last_login_ip'];?></p>
                                </td>
                                <td class="wt385"><p class="wt325"><?php echo $v['content'];?></p></td>
                                <td class="wt80 c999"><?php echo $v['time'];?></td>
                            </tr>
                            <tr class="trbor pb">
                                <td class="wt90">
                                </td>
                                <td class="wt125">
                                    <p class="qiyere">企业回复</p>
                                </td>
                                <td class="wt385"><p class="wt325 cf47a20"><?php echo $v['reply_content'];?></p></td>
                                <td class="wt80 c999"><?php echo date('Y-m-d',$v['reply_time']);?></td>
                            </tr>
                        <?php }else{?>
                            <tr class="trbor">
                                <td class="wt90">
                                    <div class="imgwidth">
                                        <img onerror="$(this).attr('src', '<?= URL::webstatic("/images/company_center/icon_33.png");?>')" src="<?php echo $v['photo'];?>">
                                    </div>
                                </td>
                                <td class="wt125">
                                    <p><?php echo $v['user_name'];?></p>
                                    <p class="c999"><?php echo $v['last_login_ip'];?></p>
                                </td>
                                <td class="wt385"><p class="wt325"><?php echo $v['content'];?></p></td>
                                <td class="wt80 c999"><?php echo $v['time'];?></td>
                            </tr>
                        <?php }}?>
                </table>
            </div>
            <?php if(!empty($history_consult) && count($history_consult)>10){?>
                <a href="javascript:;" class="morehistory">查看更多历史咨询>></a>
            <?php }?>
            <div class="clear"></div>
        <?php }?>
        <!-- 历史咨询切换结束-->

        <?php if($article_list_menu) {?>
            <h2><p class="title"><?php echo $projectinfo->project_brand_name;?>项目新闻</p></h2>
            <div class="project_info_other">
                <ul class="news_list" style="border:none;">
                    <?php foreach ( array_slice($article_list_menu,0,6) as $vs_article_list ){
                        ?>
                        <li class="<?php if( $vs_article_list['article_img']!='' ){ echo 'haveimg'; }else{echo 'no_img';}?>">
                            <h3>
                                <a href="<?php echo zxurlbuilder::porjectzixuninfo($projectinfo->project_id, $vs_article_list['article_id'])?>" title="<?php echo $vs_article_list['article_name']?>"><?php echo $vs_article_list['article_name']?></a>
                            </h3>
                            <div class="infor_newlist_left ">
                                <label> <b><?php if( ceil($vs_article_list['aritcle_modtime'])=='0' ){ echo date("Y年m月d日 H:i",$vs_article_list['article_intime']); }else{echo date("Y年m月d日 H:i",$vs_article_list['aritcle_modtime']);}?>更新</b> <em>标签：</em>
                                    <?php
                                    if( $vs_article_list['article_tag']!='' ){
                                        $tag_arr= explode( ',',$vs_article_list['article_tag'] );

                                        foreach ( $tag_arr as $tk=>$vs ){
                                            ?>
                                            <a href="<?php echo zxurlbuilder::ptag($vs)?>" title="<?php echo $vs?>"><?php echo $vs?></a> <b>;</b>

                                        <?php }}?>

                                </label>
                                      <span class="infor_new_text">
                                        <?php echo UTF8::substr( zixun::setContentReplace( ( $vs_article_list['article_content'] ) ),0,200 );if( UTF8::strlen( zixun::setContentReplace($vs_article_list['article_content']) )>200 ){?>...<?php }?>
                                      </span>
                                <div class="clear"></div>
                            </div>
                            <?php
                            //存在图片就加载下面的
                            if( $vs_article_list['article_img']!='' ){
                                ?>
                                <div class="infor_newlist_right">
                                    <a href="<?php echo zxurlbuilder::porjectzixuninfo($projectinfo->project_id, $vs_article_list['article_id'])?>" title="<?php echo $vs_article_list['article_name']?>"><img src="<?php echo URL::imgurl( $vs_article_list['article_img'] )?>"></a>
                                </div>
                            <?php }?>
                            <div class="clear"></div>
                        </li>
                    <?php }?>
                </ul>
                <?php if(!empty($article_list_menu) && count($article_list_menu)>6){?>
                    <a href="<?php echo URL::website('/info/'.$projectinfo->project_id.'.html')?>" class="morehistory">查看更多项目新闻>></a>
                <?php }?>
                <div class="clear"></div>

            </div>
        <?php }?>

        <?php if($industry_article_list_menu) {?>
            <h2><p class="title">行业新闻</p></h2>
            <div class="project_info_other">
                <ul class="news_list" style="border:none;">
                    <?php foreach ( array_slice($industry_article_list_menu,0,6) as $vs_article_list ){
                        ?>
                        <li class="<?php if( $vs_article_list['article_img']!='' ){ echo 'haveimg'; }else{echo 'no_img';}?>">
                            <h3>
                                <a href="<?php echo zxurlbuilder::industryzixuninfo($vs_article_list['article_id'],date("Ym",$vs_article_list['article_intime']));?>" title="<?php echo $vs_article_list['article_name']?>" TARGET="_blank"><?php echo $vs_article_list['article_name']?></a>
                            </h3>
                            <div class="infor_newlist_left ">
                                <label> <b><?php if( ceil($vs_article_list['aritcle_modtime'])=='0' ){ echo date("Y年m月d日 H:i",$vs_article_list['article_intime']); }else{echo date("Y年m月d日 H:i",$vs_article_list['aritcle_modtime']);}?>更新</b> <em>标签：</em>
                                    <?php
                                    if( $vs_article_list['article_tag']!='' ){
                                        $tag_arr= explode( ',',$vs_article_list['article_tag'] );

                                        foreach ( $tag_arr as $tk=>$vs ){
                                            ?>
                                            <a href="javascript:void(0)" title="<?php echo $vs?>"><?php echo $vs?></a> <b>;</b>

                                        <?php }}?>
                                </label>
                  <span class="infor_new_text">
                    <?php echo UTF8::substr( zixun::setContentReplace( ( $vs_article_list['article_content'] ) ),0,200 );if( UTF8::strlen( zixun::setContentReplace($vs_article_list['article_content']) )>200 ){?>...<?php }?>
                  </span>
                                <div class="clear"></div>
                            </div>
                            <?php
                            //存在图片就加载下面的
                            if( $vs_article_list['article_img']!='' ){
                                ?>
                                <div class="infor_newlist_right">
                                    <a href="<?php echo zxurlbuilder::industryzixuninfo($vs_article_list['article_id'],date("Ym",$vs_article_list['article_intime']));?>" title="<?php echo $vs_article_list['article_name']?>" target="_blank"><img src="<?php echo URL::imgurl( $vs_article_list['article_img'] )?>"></a>
                                </div>
                            <?php }?>
                            <p class="clear">
                      <span class="infor_new_iconlist infor_new_iconlist01">
                        <a href="javascript:void(0)" class="infor_new_icon01" onclick="act_dingcai_industry( <?php echo $vs_article_list['article_id']?>,'up' )"><span id="industry_ding_id_<?php echo $vs_article_list['article_id']?>"><?php echo $vs_article_list['article_ding']?></span></a>
                        <a href="javascript:void(0)" class="infor_new_icon02" onclick="act_dingcai_industry( <?php echo $vs_article_list['article_id']?>,'down' )"><span id="industry_cai_id_<?php echo $vs_article_list['article_id']?>"><?php echo $vs_article_list['article_cai']?></span></a>
                      </span>
                      <span class="infor_new_iconlist infor_new_iconlist02">
                      <a href="javascript:void(0)" onmouseover="getfavoriteofindustry('<?php echo $vs_article_list['article_id']?>')" onclick="addfavorite_industry('<?php echo $vs_article_list['article_id']?>')" class="infor_new_icon03 person_search_login">收藏</a>
                      <b class="collect_fc" style="display:none" id="favorite_<?php echo $vs_article_list['article_id']?>"><font id="favorite_eof_<?php echo $vs_article_list['article_id']?>">收藏成功</font></b>
                      </span>
                      <span class="infor_new_share">
                      <a href="javascript:void(0)" class="infor_new_icon04">分享</a>
                      <em>
                          <?php if ($vs_article_list['article_img']!=""){?>
                              <a href="#" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo zxurlbuilder::industryzixuninfo($vs_article_list['article_id'],date("Ym",$vs_article_list['article_intime']))?>&pic=<?php echo URL::imgurl( str_replace("s_","b_",$vs_article_list['article_img']))?>&appkey=1343713053&title=<?php echo "【".$vs_article_list['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($vs_article_list['article_content']), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($vs_article_list['article_content']) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon08.jpg')?>" /></a>
                              <a href="#" onclick="{ var _t = '<?php echo $vs_article_list['article_name']?>';  var _url = '<?php echo zxurlbuilder::industryzixuninfo($vs_article_list['article_id'],date("Ym",$vs_article_list['article_intime']))?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _pic = '<?php echo URL::imgurl( str_replace("s_","b_",$vs_article_list['article_img']))?>'; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+'&pic='+_pic; window.open( _u,'转播到腾讯微博');  };" ><img src="<?php echo URL::webstatic('images/platform/information/icon09.jpg')?>" /></a>
                              <a href="#" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo zxurlbuilder::industryzixuninfo($vs_article_list['article_id'],date("Ym",$vs_article_list['article_intime']))?>&pics=<?php echo URL::imgurl( str_replace("s_","b_",$vs_article_list['article_img']))?>&title=<?php echo "【".$vs_article_list['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($vs_article_list['article_content']), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($vs_article_list['article_content']) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon10.jpg')?>" /></a>
                              <a href="#" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo zxurlbuilder::industryzixuninfo($vs_article_list['article_id'],date("Ym",$vs_article_list['article_intime']))?>&pic=<?php echo URL::imgurl( str_replace("s_","b_",$vs_article_list['article_img']))?>&description=<?php echo "【".$vs_article_list['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($vs_article_list['article_content']), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($vs_article_list['article_content']) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon11.jpg')?>" /></a>
                          <?php }else{?>
                              <a href="#" onclick="window.open('http://service.weibo.com/share/share.php?url=<?php echo zxurlbuilder::industryzixuninfo($vs_article_list['article_id'],date("Ym",$vs_article_list['article_intime']))?>&appkey=1343713053&title=<?php echo "【".$vs_article_list['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($vs_article_list['article_content']), 0,50);?><?php if( UTF8::strlen( zixun::setContentReplace($vs_article_list['article_content']) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon08.jpg')?>" /></a>
                              <a href="#" onclick="{ var _t = '<?php echo $vs_article_list['article_name']?>';  var _url = '<?php echo zxurlbuilder::industryzixuninfo($vs_article_list['article_id'],date("Ym",$vs_article_list['article_intime']))?>'; var _appkey = '333cf198acc94876a684d043a6b48e14'; var _site = encodeURI; var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&url='+_url+'&appkey='+_appkey+'&site='+_site+''; window.open( _u,'转播到腾讯微博');  };" ><img src="<?php echo URL::webstatic('images/platform/information/icon09.jpg')?>" /></a>
                              <a href="#" onclick="window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo zxurlbuilder::industryzixuninfo($vs_article_list['article_id'],date("Ym",$vs_article_list['article_intime']))?>&title=<?php echo "【".$vs_article_list['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($vs_article_list['article_content']), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($vs_article_list['article_content']) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon10.jpg')?>" /></a>
                              <a href="#" onclick="window.open('http://widget.renren.com/dialog/share?resourceUrl=<?php echo zxurlbuilder::industryzixuninfo($vs_article_list['article_id'],date("Ym",$vs_article_list['article_intime']))?>&description=<?php echo "【".$vs_article_list['article_name']."】（分享来自@通路快建一句话）".UTF8::substr(zixun::setContentReplace($vs_article_list['article_content']), 0,50)?><?php if( UTF8::strlen( zixun::setContentReplace($vs_article_list['article_content']) )>50 ){?>...<?php }?>');return false;"><img src="<?php echo URL::webstatic('images/platform/information/icon11.jpg')?>" /></a>
                          <?php }?>
                      </em>
                      </span>
                            </p>
                            <div class="clear"></div>
                        </li>
                    <?php }?>
                </ul>
                <?php if(!empty($industry_article_list_menu) && count($industry_article_list_menu)>6){?>
                    <a href="<?php echo URL::website('/industry/'.$projectinfo->project_id.'.html')?>" class="morehistory">查看更多行业新闻>></a>
                <?php }?>
                <div class="clear"></div>
            </div>
        <?php }?>

    </div>
</div>
<div class="clear"></div>
<?php if($projectinfo->product_features){//产品特点?>
    <div id="content_text2" class="project_home_content_text" style="display:none">
        <?php //echo $projectinfo->product_features;?>
    </div>
<?php }?>

<?php if($projectinfo->project_join_conditions){//加盟详情?>
    <div id="content_text<?php if($projectinfo->product_features){echo 3;}else{echo 2;}?>" class="project_home_content_text" style="display:none">
        <?php //echo htmlspecialchars_decode($projectinfo->project_join_conditions);?>
    </div>
<?php }?>
<!-- 历史咨询项目详情页面开始 -->
<!-- 添加一个数量显示判断 -->
<?php if(!empty($history_consult) && count($history_consult)>10){?>
    <div class="project_home_content_text"style="display:none;">
        <table cellspacing="0" cellpadding="0" class="hisconsulttab">
            <?php foreach($history_consult as $k=>$v){?>
                <?php if($v['reply_content'] && $v['reply_time'] && $v['reply_time']<=time()){?>
                    <tr>
                        <td class="wt90">
                            <div class="imgwidth">
                                <img onerror="$(this).attr('src', '<?= URL::webstatic("/images/company_center/icon_33.png");?>')" src="<?php echo $v['photo'];?>">
                            </div>
                        </td>
                        <td class="wt125">
                            <p><?php echo $v['user_name'];?></p>
                            <p class="c999"><?php echo $v['last_login_ip'];?></p>
                        </td>
                        <td class="wt385"><p class="wt325"><?php echo $v['content'];?></p></td>
                        <td class="wt80 c999"><?php echo $v['time'];?></td>
                    </tr>
                    <tr class="trbor pb">
                        <td class="wt90">
                        </td>
                        <td class="wt125">
                            <p class="qiyere">企业回复</p>
                        </td>
                        <td class="wt385"><p class="wt325 cf47a20"><?php echo $v['reply_content'];?></p></td>
                        <td class="wt80 c999"><?php echo date('Y-m-d',$v['reply_time']);?></td>
                    </tr>
                <?php }else{?>
                    <tr class="trbor">
                        <td class="wt90">
                            <div class="imgwidth">
                                <img onerror="$(this).attr('src', '<?= URL::webstatic("/images/company_center/icon_33.png");?>')"  src="<?php echo $v['photo'];?>">
                            </div>
                        </td>
                        <td class="wt125">
                            <p><?php echo $v['user_name'];?></p>
                            <p class="c999"><?php echo $v['last_login_ip'];?></p>
                        </td>
                        <td class="wt385"><p class="wt325"><?php echo $v['content'];?></p></td>
                        <td class="wt80 c999"><?php echo $v['time'];?></td>
                    </tr>
                <?php }}?>
        </table>
    </div>
<?php }?>
<!-- 历史咨询项目详情页面结束 -->
<div id="content_text4" class="project_home_content_text" style="display:block">
    <ul class="news_list" style="border:none;">

        <?php
        if( !empty( $article_list ) ){
            foreach ( $article_list as $vs_article_list ){
                ?>
                <li class="<?php if( $vs_article_list['article_img']!='' ){ echo 'haveimg'; }else{echo 'no_img';}?>">
                    <h3>
                        <a href="<?php echo zxurlbuilder::porjectzixuninfo($projectinfo->project_id, $vs_article_list['article_id'])?>" title="<?php echo $vs_article_list['article_name']?>"><?php echo $vs_article_list['article_name']?></a>
                    </h3>
                    <div class="infor_newlist_left ">
                        <label> <b><?php if( ceil($vs_article_list['aritcle_modtime'])=='0' ){ echo date("Y年m月d日 H:i",$vs_article_list['article_intime']); }else{echo date("Y年m月d日 H:i",$vs_article_list['aritcle_modtime']);}?>更新</b> <em>标签：</em>
                            <?php
                            if( $vs_article_list['article_tag']!='' ){
                                $tag_arr= explode( ',',$vs_article_list['article_tag'] );

                                foreach ( $tag_arr as $tk=>$vs ){
                                    ?>
                                    <a href="<?php echo zxurlbuilder::ptag($vs)?>" title="<?php echo $vs?>"><?php echo $vs?></a> <b>;</b>

                                <?php }}?>

                        </label>
                  <span class="infor_new_text">
                    <?php echo UTF8::substr( zixun::setContentReplace( ( $vs_article_list['article_content'] ) ),0,100 );if( UTF8::strlen( zixun::setContentReplace($vs_article_list['article_content']) )>100 ){?>...<?php }?>
                  </span>
                        <div class="clear"></div>
                    </div>
                    <?php
                    //存在图片就加载下面的
                    if( $vs_article_list['article_img']!='' ){
                        ?>
                        <div class="infor_newlist_right">
                            <a href="<?php echo zxurlbuilder::porjectzixuninfo($projectinfo->project_id, $vs_article_list['article_id'])?>" title="<?php echo $vs_article_list['article_name']?>"><img src="<?php echo URL::imgurl( $vs_article_list['article_img'] )?>"></a>
                        </div>
                    <?php }?>
                    <div class="clear"></div>
                </li>
            <?php }}?>

    </ul>
    <div class="ryl_search_result_page">
        <?php echo $article_page?>
    </div>
</div>
</div>

<!--左侧部分正文 END-->
</div>
<!--左侧部分 END-->


<!--右侧部分-->
<div class="project_home_content_right">
    <ul class="project_home_right_detial">
        <li class="project_home_right_img_no_ad">
            <p class="img"><span><label>
                        <a href="<?php echo URL::website("project/{$projectinfo->project_id}.html")?>" ><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" src="<?php
                            echo $projectlogonew;
                            ?>" width="120" height="95" alt="<?php echo $projectinfo->project_brand_name;?>Logo图像"/></a>
                    </label></span></p>
        </li>
        <li class="yixiangrighta">
            <span>意向加盟者<em><?php echo $pro_industry_count;?></em>人</span>
        </li>
        <li>
            <font>品牌名称</font>
                    <span><a target="_blank" href="<?php echo urlbuilder::project($projectinfo->project_id);?>" title="<?php echo $projectinfo->project_brand_name;?>"><?php
                            if(mb_strlen($projectinfo->project_brand_name)>16){
                                echo mb_substr($projectinfo->project_brand_name,0,16,'UTF-8').'...';
                            }
                            else{
                                echo $projectinfo->project_brand_name;
                            }
                            ?></a></span>
        </li>
        <li>
            <font>投资金额</font>
            <span class="project_home_blue"><a target="_black" href="<?php echo URL::website('xiangdao/fenlei/m'.$projectinfo->project_amount_type.'.html');?>" title="<?php echo arr::get($monarr,$projectinfo->project_amount_type,'无');?>"><?php echo arr::get($monarr,$projectinfo->project_amount_type,'无');?></a></span>
        </li>
        <li>
            <font>行    业</font>
                    <span class="project_home_blue"><a href="" title="">
                            <?php
                            if(arr::get($pro_industry,'one_id','')){
                                echo '<a style="color: #0E71B4;" target="_Blank" href="/xiangdao/fenlei/zhy'.arr::get($pro_industry,'one_id','1').'.html">'.arr::get($pro_industry,'one_name','').'</a>';
                            }
                            if(arr::get($pro_industry,'two_id','')){
                                echo '<a style="color: #0E71B4;" target="_Blank" href="/xiangdao/fenlei/zhy'.arr::get($pro_industry,'two_id','1').'.html">'.arr::get($pro_industry,'two_name','').'</a>';
                            }
                            ?></a></span>
        </li>
        <li>
            <font>适合人群 </font>
                    <span><?php
                        if(count($group_text)>0 && $group_text!='不限'){
                            $t=1;
                            foreach($group_text as $gro){
                                $t++;
                                echo '<a target="_black" href="/search/?w='.urlencode($gro).'" title="'.$gro.'">'.$gro.'</a>';
                                if(count($group_text)==2){
                                    if($t>1 && $t<=2){
                                        echo '、';
                                    }
                                }
                                if(count($group_text)>=3){
                                    if($t>1 && $t<=3){
                                        echo '、';
                                    }
                                }
                                if($t>3){
                                    break;
                                }
                            }
                        }
                        else{
                            echo $group_text;
                        }
                        ?></span>
        </li>
        <li>
            <font>招商地区 </font>
            <span class="project_home_blue"><?php echo $area_zhong;?></span>
        </li>
        <?php if(isset( $companyinfo->com_name) && $companyinfo->com_name){ ?>
            <li>
                <font>公司名称 </font>
                    <span>
                    <?php if($is_has_company){?>
                        <a target="_blank" href="<?php echo urlbuilder::projectCompany($projectinfo->project_id);?>" title="<?php echo $companyinfo->com_name;?>">
                            <?php echo $companyinfo->com_name;?></a>
                    <?php }else{echo $companyinfo->com_name;}?>
                    </span>
            </li>
        <?php }?>
        <li>
            <font>招商形式 </font>
                    <span ><a href="" title=""><?php $lst = common::businessForm();
                            $pro_count=count($projectcomodel);
                            if($pro_count){
                                $comodel_text=0;
                                foreach ($projectcomodel as $v){
                                    $comodel_text++;
                                    echo '<a target="_Blank" href="/xiangdao/fenlei/xs'.$v.'.html">'.$lst[$v].'</a>';
                                    if($comodel_text < $pro_count){
                                        echo '、';
                                    }
                                }
                            }else{
                                echo '不限';
                            } ?></a></span>
        </li>
        <li>
            <font>品牌发源地 </font>
            <span><?php if($projectinfo->project_brand_birthplace){ echo $projectinfo->project_brand_birthplace;}else{echo '暂无信息';}?></span>
        </li>
    </ul>
    <ul class="project_home_right_tag">
        <li class="project_home_right_title"><h2>标签</h2></li>
        <?php if($projectinfo->project_tags){
            $arr = explode(",",$projectinfo->project_tags);
            foreach ($arr as $ke=>$ve){
                if($ke <10){
                    if(mb_strlen($ve)>10){ $ve=mb_substr($ve,0,10,'UTF-8'); }
                    echo '<li><a target="_Blank" href="/search/?w='.urlencode($ve).'">'.$ve.'</a></li>';
                }}
        }
        ?>
        <div class="clear"></div>
    </ul>
    <?php if(isset($top_projectct) && count($top_projectct)){ ?>
        <ul class="project_home_right_top10">
            <li class="project_home_right_title"><h2><a target="_blank" href="/xiangdao/top/" title="热门项目排行">热门项目排行</a></h2></li>
            <?php
            $ii=1;
            foreach ($top_projectct as $k=>$v){
                echo '<li class="top'.$ii.'"><a target="_blank" href="'.URL::website("project/{$k}.html").'" title="'.$v.'">'.mb_substr($v,0,13,'UTF-8').'</a></li>';
                $ii++;
            }
            ?>
            <div class="clear"></div>
        </ul>
    <?php }?>
    <?php if(isset($newtop_projectct) && count($newtop_projectct)){ ?>
        <ul class="project_home_right_top10">
            <li class="project_home_right_title"><h2><a target="_blank" href="/xiangdao/top/" title="最新项目推荐">最新项目推荐</a></h2></li>
            <?php
            $ii=1;
            foreach ($newtop_projectct as $k=>$v){
                echo '<li class="top'.$ii.'"><a target="_blank" href="'.URL::website("project/{$k}.html").'" title="'.$v.'">'.mb_substr($v,0,13,'UTF-8').'</a></li>';
                $ii++;
            }
            ?>
            <div class="clear"></div>
        </ul>
    <?php }?>
</div>
<div class=" clear"></div>
<!--右侧部分 END-->
</div>

<script type="text/javascript">
    var tj_type_id = 1;
    var tj_pn_id = <?php echo $projectinfo->project_id;?>;
    $(document).ready(function(){
        //用户访问项目的记录
        var projectid = <?php echo $projectinfo->project_id;?>;
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/platform/ajaxcheck/addPersonAboutPro",
            data: "projectid="+projectid,
            complete :function(){
            },
            success: function(msg){
            }
        });
        //意向浏览统计
        var url = "/platform/ajaxcheck/TongJiProjectPv";
        $.post(url,{"project_id":projectid,"type":1},function(data){
        },"json");
    });

    function setVisit(){
        var projectid = <?php echo $projectinfo->project_id;?>;
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/platform/ajaxcheck/setVisit",
            data: "projectid="+projectid,
            complete :function(){
            },
            success: function(msg){
            }
        });
    }
</script>