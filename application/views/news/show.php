<?php echo URL::webcss("/platform/information.css")?>
<?php echo URL::webcss('/platform/common.css')?>
<!--主体部分-->
<div class="main" style="background-color:#e3e3e3; height:auto;">

  <div class="infor_main">
    <!--菜单-->
    <!--<div id="infor_menu">
        <ul>
            <li class="cur"><h1><a class="infor_menu_icon01" href="/">资讯首页</a></h1></li>
            <li><a class="infor_menu_icon02" href="#">投资前沿</a></li>
            <li><a class="infor_menu_icon03" href="#">项目向导</a></li>
            <li><a class="infor_menu_icon04" href="#">开店指导</a></li>
            <li><a class="infor_menu_icon05" href="#">开店管理</a></li>
            <li><a class="infor_menu_icon06" href="#">财富人物</a></li>
            <li><a class="infor_menu_icon07" href="#">行业透视</a></li>
            <li><a class="infor_menu_icon08" href="#">考察报告</a></li>
        </ul>
    </div>-->
    <!--内容-->
    <div class="infor_content">
      <div class="infor_bg01"></div>
      <div class="infor_bg02">
<!--投稿查看-->
       <div class="infor_center tg_contribut">
           <h1><?php echo $info['article_name']?></h1>
           <div class="ad_list_tags">标签：
           <?php if ($info['article_tag']!=""){
                      $info['article_tag'] = str_replace("，", ',', $info['article_tag']);
                      if (strrpos($info['article_tag'],',')){
                          $tags = explode(',', $info['article_tag']);
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
                          echo '<a href="'.zxurlbuilder::tag($info['article_tag']).'" title="'.$info['article_name'].'">'.$info['article_tag'].'</a>';
                  }?>
           </div>
           <div class="tg_contribut_cont">
           <?php if( $info['article_img']!='' ){?>
                <p class="tg_img"><img src="<?php echo URL::imgurl( str_replace( "s_","b_",$info['article_img'] ) )?>" alt="<?php echo $info['article_name']?>" /></p>
            <?php }?>
            <?php $allow=zixun::setContentApply()?>
            <?php echo zixun::setContentReplace($info['article_content'],$allow)?>
           <div class="clear"></div>
           </div>
          <div class="clear"></div>
       </div>



      <div class="clear"></div>
      </div>
      <div class="infor_bg03"></div>

     <div class="clear"></div>
    </div>

  <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>
