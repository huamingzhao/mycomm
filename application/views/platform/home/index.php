<?php echo URL::webcss("platform/index.css")?>
<div class="main" style="height:auto;">
   <!--浏览记录-->
   <div class="browse_record">
         <!--首页 来过-->

         <!-- 首页行业分类导航 -->
         <div class="industry_nav">
           <div class="industry_nav_list clearfix">
             <dl class="clearfix first firstLine">
               <dt>
                 <a target="_blank"   href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>1,'pid'=>0));?>">餐饮</a>
               </dt>
               <dd>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>8,'pid'=>0));?>">火锅</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>9,'pid'=>0));?>" class="red">饮品</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>10,'pid'=>0));?>">拉面</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>11,'pid'=>0));?>">快餐</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>18,'pid'=>0));?>" class="blue">餐具</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>12,'pid'=>0));?>">小吃</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>13,'pid'=>0));?>">西餐</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>16,'pid'=>0));?>">烧烤</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>17,'pid'=>0));?>">熟食</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>15,'pid'=>0));?>">茶</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>14,'pid'=>0));?>">精品餐饮</a>
                </dd>
             </dl>
             <dl class="clearfix firstLine">

               <dt>
                 <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>80,'pid'=>0));?>">美容</a>
               </dt>
               <dd>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>82,'pid'=>0));?>">保养滋生 </a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>83,'pid'=>0));?>" class="red">美发美甲</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>84,'pid'=>0));?>" class="blue">美体瘦身</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>85,'pid'=>0));?>">足疗蒸汗</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>81,'pid'=>0));?>">化妆品</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>86,'pid'=>0));?>">其他</a>
                </dd>
             </dl>
             <dl class="clearfix last firstLine">
               <dt>
                 <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>119,'pid'=>0));?>">珠宝饰品</a>
               </dt>
               <dd>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>120,'pid'=>0));?>" class="red">钻石</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>121,'pid'=>0));?>" class="blue">黄金</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>122,'pid'=>0));?>">银饰</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>123,'pid'=>0));?>">彩宝</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>124,'pid'=>0));?>">水晶琥珀</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>125,'pid'=>0));?>">玉器翡翠</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>127,'pid'=>0));?>">钟表</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>126,'pid'=>0));?>">工艺礼品</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>128,'pid'=>0));?>">其他</a>
                </dd>
             </dl>
             <dl class="clearfix first">
               <dt>
                 <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>103,'pid'=>0));?>">母婴</a>
               </dt>
               <dd>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>104,'pid'=>0));?>">儿童玩具</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>107,'pid'=>0));?>" class="red">儿童摄影</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>106,'pid'=>0));?>" class="blue">母婴及护理</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>105,'pid'=>0));?>">早教</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>108,'pid'=>0));?>">其他</a>
                </dd>
             </dl>
             <dl class="clearfix">
               <dt>
                 <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>95,'pid'=>0));?>">家纺</a>
               </dt>
               <dd>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>97,'pid'=>0));?>">布艺</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>98,'pid'=>0));?>">地毯</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>100,'pid'=>0));?>" class="red">毛巾</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>101,'pid'=>0));?>">丝绸</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>96,'pid'=>0));?>" class="blue">床上用品</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>99,'pid'=>0));?>">竹纤维</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>102,'pid'=>0));?>">其他</a>
                </dd>
             </dl>
             <dl class="clearfix last">
               <dt>
                 <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>6,'pid'=>0));?>">酒水饮料</a>
               </dt>
               <dd>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>63,'pid'=>0));?>" class="red">白酒</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>64,'pid'=>0));?>">红酒</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>65,'pid'=>0));?>" class="blue">啤酒</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>66,'pid'=>0));?>">其他</a>
                </dd>
             </dl>
             <dl class="clearfix first lastLine">
               <dt>
                 <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>2,'pid'=>0));?>">连锁零售</a>
               </dt>
               <dd>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>22,'pid'=>0));?>" class="red">连锁加盟</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>23,'pid'=>0));?>">商业零售</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>24,'pid'=>0));?>">专项零售</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>88,'pid'=>0));?>">个人护理店</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>87,'pid'=>0));?>" class="blue">文具店</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>25,'pid'=>0));?>">便利店</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>26,'pid'=>0));?>">其他</a>
                </dd>
             </dl>
             <dl class="clearfix lastLine">
               <dt>
                 <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>3,'pid'=>0));?>">服饰箱包</a>
               </dt>
               <dd>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>27,'pid'=>0));?>">男装</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>28,'pid'=>0));?>">女装</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>29,'pid'=>0));?>" class="red">童装</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>30,'pid'=>0));?>">休闲</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>31,'pid'=>0));?>">运动</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>38,'pid'=>0));?>">配饰</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>33,'pid'=>0));?>">户外</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>35,'pid'=>0));?>">内衣</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>36,'pid'=>0));?>">皮具</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>37,'pid'=>0));?>" class="blue">箱包</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>34,'pid'=>0));?>">孕妇装</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>32,'pid'=>0));?>">鞋</a>
                </dd>
             </dl>
             <dl class="clearfix last lastLine">
               <dt>
                 <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>5,'pid'=>0));?>">教育培训</a>
               </dt>
               <dd>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>54,'pid'=>0));?>">英语</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>55,'pid'=>0));?>">作文</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>56,'pid'=>0));?>" class="red">潜能</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>61,'pid'=>0));?>">留学</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>58,'pid'=>0));?>">一对一辅导</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>60,'pid'=>0));?>" class="blue">教具教材</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>59,'pid'=>0));?>">公务员认证</a>
                <a target="_blank"  href="<?= urlbuilder:: fenleiCond (array('inid'=>0,'pid'=>0),array('inid'=>62,'pid'=>0));?>">其他</a>
                </dd>
             </dl>
           </div>
         </div>
      <div>

    <div id="change_projet_list">
    </div>

      <div class="browse_record_title"><a href="<?php echo urlbuilder::rootDir('search');?>" target="_blank">查看更多创业投资项目></a><h2>您可能喜欢的创业项目</h2></div>
         <ul class="browse_list browse_list1">
         <?php $i=0; if(isset($arr_data['YouMayLikeProject'])){foreach ($arr_data['YouMayLikeProject'] as $key=>$val){$i++; if($i > 6){break;}?>
             <li <?php if($i == 6){echo "class='last'";}?>>

             <p class="img">
             <label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="创业项目<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="创业项目<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label>
           </p>
             <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,30,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,30,'UTF-8')."";};?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,30,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,30,'UTF-8')."";};?></a></span>
             <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
             <div><p class="p_01"><a href="<?php echo urlbuilder::project($val['project_id']);?>" title="<?php echo $val['project_brand_birthplace'] ? $val['project_brand_birthplace'] :"未知";?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'], 0,4,'UTF-8')."":"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
             </li>
             <?php }}?>
         </ul>
         <div class="clear"></div>
         <div class="kong_01"></div>

        <div class="browse_record_title"><h2>大家都喜欢的创业项目</h2></div>
         <ul class="browse_list browse_list2">
          <?php  $i=0; if(isset($arr_data['EveryOneMayLikeProject'])){foreach ($arr_data['EveryOneMayLikeProject'] as $key=>$val){$i++; if($i> 6){break;}?>
            <li <?php if($i == 6){echo "class='last'";}?>>
             <p class="img">
              <label><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="创业项目<?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,8,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,8,'UTF-8')."";};?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="创业项目<?php if($val['project_advert']){echo $val['project_advert'];}else{ echo $val['project_brand_name'];};?>" src="<?if($val['project_source'] != 1) {$img =  project::conversionProjectImg($val['project_source'], 'logo', $val);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($val['project_logo']);}?>" /></a></label>
            </p>
             <span><a href="<?php echo urlbuilder::project($val['project_id']);?>" target="_blank" title="<?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,30,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,30,'UTF-8')."";};?>"><?php if($val['project_advert']){echo mb_substr($val['project_advert'], 0,30,'UTF-8')."";}else{ echo mb_substr($val['project_brand_name'], 0,30,'UTF-8')."";};?></a></span>
             <span class="browse_pro_money">￥<em><?php $monarr= common::moneyArr(); echo arr::get($val, 'project_amount_type') == 0 ? '无': $monarr[arr::get($val, 'project_amount_type')];?></em></span>
             <div><p class="p_01"><a title="<?php echo $val['project_brand_birthplace'];?>" href="<?php echo urlbuilder::project($val['project_id']);?>"><?php echo $val['project_brand_birthplace'] ? mb_substr($val['project_brand_birthplace'],0,4,'UTF-8')."" :"未知";?><em class="browsed_fc01">品牌发源地</em></a></p><p class="p_02"><a href="<?php echo urlbuilder::project($val['project_id']);?>"><?php if($val['project_pv_count'] == 0){echo "1";}else{echo $val['project_pv_count'];}?><em class="browsed_fc02">项目人气</em></a></p></div>
             </li>
             <?php }}?>
         <div class="clear"></div>
         </ul>
         <div class="clear"></div>
         <div class="kong_01"></div>
   </div>
        <div class="browse_record_title browse_record_title_news"><h2><a href="<?php echo urlbuilder::rootDir("zixun");?>">创业开店</a>学知识</h2><a href="<?php echo urlbuilder::rootDir("zixun");?>" title="一句话">更多 》</a></div>
        <div class=" browse_record_news">
          <dl>
            <dt><h3><a target="_blank" href="<?php echo zxurlbuilder::column('投资前沿');?>">创业投资前沿趋势</a></h3></dt>
            <?php $i=0; foreach ($touziqianyan as $key=>$val){ $i++;?>
                <?php if($i==1){?>
                    <dd class="first">
                      <a  target="_blank" href="<?php echo zxurlbuilder::zixuninfo($val['article_id'],date("Ym",$val['article_intime']));?>" title="<?php echo $val['article_name']?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" width="75" height="60" src="<?php echo URL::imgurl($val['article_img'])?>" alt="<?php echo $val['article_name']?>"></a>
                      <h4 class="jchar"><a  target="_blank" href="<?php echo zxurlbuilder::zixuninfo($val['article_id'],date("Ym",$val['article_intime']));?>" title="<?php echo $val['article_name']?>"><?php echo $val['article_name'];?></a></h4>
                      <span><?php echo UTF8::substr(zixun::setContentReplace($val['article_content']), 0,18)?><?php if( UTF8::strlen( zixun::setContentReplace($val['article_content']) )>18 ){?>...<?php }?></span>
                      <div class="clear"></div>
                    </dd>
                <?php }else{?>
                     <dd class="jchar"><a class="link" target="_blank" href="<?php echo zxurlbuilder::zixuninfo($val['article_id'],date("Ym",$val['article_intime']));?>" title="<?php echo $val['article_name'];?>"><?php echo $val['article_name']?></a></dd>
                <?php }?>
            <?php }?>
          </dl>
          <dl>
            <dt><h3><a target="_blank" href="<?php echo zxurlbuilder::column('行业透视');?>">行业加盟深度透视</a></h3></dt>
           <?php $i=0; foreach ($hangyetoushi as $key=>$val){ $i++;?>
                <?php if($i==1){?>
                    <dd class="first">
                      <a  target="_blank" href="<?php echo zxurlbuilder::zixuninfo($val['article_id'],date("Ym",$val['article_intime']));?>" title="<?php echo $val['article_name']?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" width="75" height="60" src="<?php echo URL::imgurl($val['article_img'])?>" alt="<?php echo $val['article_name']?>"></a>
                      <h4 class="jchar"><a  target="_blank" href="<?php echo zxurlbuilder::zixuninfo($val['article_id'],date("Ym",$val['article_intime']));?>" title="<?php echo $val['article_name']?>"><?php echo $val['article_name']?></a></h4>
                      <span><?php echo UTF8::substr(zixun::setContentReplace($val['article_content']), 0,18)?><?php if( UTF8::strlen( zixun::setContentReplace($val['article_content']) )>18 ){?>...<?php }?></span>
                      <div class="clear"></div>
                    </dd>
                <?php }else{?>
                     <dd class="jchar"><a class="link" target="_blank" href="<?php echo zxurlbuilder::zixuninfo($val['article_id'],date("Ym",$val['article_intime']));?>" title="<?php echo $val['article_name'];?>"><?php echo $val['article_name']?></a></dd>
                <?php }?>
            <?php }?>
            </dl>
          <dl>
            <dt><h3><a target="_blank" href="<?php echo zxurlbuilder::column('开店指导');?>">创业开店专业指导</a></h3></dt>
            <?php $i=0; foreach ($chuangye as $key=>$val){ $i++;?>
                <?php if($i==1){?>
                    <dd class="first">
                      <a  target="_blank" href="<?php echo zxurlbuilder::zixuninfo($val['article_id'],date("Ym",$val['article_intime']));?>" title="<?php echo $val['article_name']?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" width="75" height="60" src="<?php echo URL::imgurl($val['article_img'])?>" alt="<?php echo $val['article_name']?>"></a>
                      <h4 class="jchar"><a  target="_blank" href="<?php echo zxurlbuilder::zixuninfo($val['article_id'],date("Ym",$val['article_intime']));?>" title="<?php echo $val['article_name']?>"><?php echo $val['article_name'];?></a></h4>
                      <span><?php echo UTF8::substr(zixun::setContentReplace($val['article_content']), 0,18)?><?php if( UTF8::strlen( zixun::setContentReplace($val['article_content']) )>18 ){?>...<?php }?></span>
                      <div class="clear"></div>
                    </dd>
                <?php }else{?>
                     <dd class="jchar"><a class="link" target="_blank" href="<?php echo zxurlbuilder::zixuninfo($val['article_id'],date("Ym",$val['article_intime']));?>" title="<?php echo $val['article_name'];?>"><?php echo $val['article_name'];?></a></dd>
                <?php }?>
            <?php }?>
          </dl>
          <dl class="last">
            <dt><h3><a target="_blank" href="<?php echo URL::website('')."zixun/shop/jiqiao.html";?>">开店经营管理技巧</a></h3></dt>
            <?php $i=0; foreach ($kaidian as $key=>$val){ $i++;?>
                <?php if($i==1){?>
                    <dd class="first">
                      <a  target="_blank" href="<?php echo zxurlbuilder::zixuninfo($val['article_id'],date("Ym",$val['article_intime']));?>" title="<?php echo $val['article_name']?>"><img onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" width="75" height="60" src="<?php echo URL::imgurl($val['article_img'])?>" alt="<?php echo $val['article_name']?>"></a>
                      <h4 class="jchar"><a  target="_blank" href="<?php echo zxurlbuilder::zixuninfo($val['article_id'],date("Ym",$val['article_intime']));?>" title="<?php echo $val['article_name']?>"><?php echo $val['article_name'];?></a></h4>
                      <span><?php echo UTF8::substr(zixun::setContentReplace($val['article_content']), 0,18)?><?php if( UTF8::strlen( zixun::setContentReplace($val['article_content']) )>18 ){?>...<?php }?></span>
                      <div class="clear"></div>
                    </dd>
                <?php }else{?>
                     <dd class="jchar"><a class="link" target="_blank" href="<?php echo zxurlbuilder::zixuninfo($val['article_id'],date("Ym",$val['article_intime']));?>" title="<?php echo $val['article_name'];?>"><?php echo $val['article_name'];?></a></dd>
                <?php }?>
            <?php }?>
          </dl>
          <div class="clear"></div>
        </div>
         <div class="clear"></div>
         <div class="kong_01"></div>
      <div class="clear"></div>

   <div class="clear"></div>
</div>
<?php echo URL::webjs("jquery.cookies.2.2.0.js")?>
<?php echo URL::webjs("platform/home/home.js");?>
<script>
$(function(){
    //alert(22);
  //获取cookies值
  //var history_project = $.cookies.get('history_project');
  //var user_id = $.cookies.get('user_id');
  var change_projet_list = $("#change_projet_list");
  var browse_list1 = $(".browse_list1");
 // var obj_browse1 = $(".browse_list1");
 // var obj_browse2 = $(".browse_list2");
 // var browse1_content = "";
//  if(history_project !=null && history_project.length !=parseInt(47) || user_id !=""){
      var url = "/platform/ajaxcheck/indexList";
      $.post(url,{'type':parseInt(2)},function(data){
          if(data.project_list != ""){
              change_projet_list.html(data.project_list);
           }
          if(data.YouMayLikeProject !=""){
              browse_list1.html(data.YouMayLikeProject);
           }
      },'json');
  //  }


});

</script>
