<?php echo URL::webcss("/platform/information.css")?>
<?php echo URL::webjs('news/news.js')?>
<?php
if( $reg_fu_is_login===false ){
?>
<?php echo URL::webjs("platform/home/login_fu.js");?>
<?php echo URL::webjs("jquery.validate.js");?>
<?php }?>
 <!--主体部分-->

<div class="main" id="main" style="background-color:#e3e3e3; height:auto;">

  <div class="infor_main"  >


    <!--菜单-->
    <div class="infor_menu">
    <div id="infor_menu">

        <ul>

            <?php if (isset($currentcolumnid)&&$currentcolumnid=="index"){?>
            <li class="cur"><h1><a class="infor_menu_icon01" href="<?php echo zxurlbuilder::zixun()?>">资讯首页</a></h1></li>
            <?php }else{?>
            <li><a class="infor_menu_icon01" href="<?php echo zxurlbuilder::zixun()?>">资讯首页</a></li>
            <?php }?>

            <?php
            foreach ($column as $k=>$v){
                if (isset($currentcolumnid)&&$currentcolumnid==$v->column_id&&$v->column_id!=2&&$v->column_id!=28 )
                    echo '<li class="cur"><h1>';
                elseif (isset($currentcolumnid)&&$currentcolumnid==$v->column_id&&($v->column_id==2||$v->column_id==28)){
                    echo '<li class="cur">';
                }
                else{
                    if( $v->column_id==29 && $count_pzn==0 ){

                    }else{
                        echo '<li>';
                    }
                }

                if ($v->column_id==2){
                    echo '<a class="infor_menu_icon0'.($v->column_id+1).'" href="'.zxurlbuilder::column($v->column_name).'">'.$v->column_name.'</a>';
                }elseif($v->column_id==28){
                    echo '<a class="infor_menu_icon09" href="'.zxurlbuilder::column($v->column_name).'">'.$v->column_name.'</a>';
                }else{
                    if( $v->column_id==29 && $count_pzn==0 ){

                    }else{
                        echo '<a class="infor_menu_icon0'.($v->column_id+1).'" href="'.zxurlbuilder::column($v->column_name).'">'.$v->column_name.'</a>';
                    }
                }


                if (isset($currentcolumnid)&&$currentcolumnid==$v->column_id&&$v->column_id!=2&&$v->column_id!=28)
                    echo '</h1></li>';
                else
                    echo '</li>';
            }
            ?>
        </ul>

    </div>
    </div>
    <!--内容-->
    <div class="yjh_item_list_backtop" id="goTopBtn">
      <img src="<?php echo URL::webstatic("/images/platform/item_list/back_top.png")?>" />
    </div>
    <div class="infor_content">


      <div class="infor_bg01"></div>
      <div class="infor_bg02 infor_bg_zl">
         <!-- 中间 -->

         <?=$rightcontent?>

         <!-- 右侧 -->
         <div class="infor_right">

            <?php if(!isset($action) || $action != 'zhuanlan'){?>
            <div class="infor_right_top">
              <div class="infor_right_search"><form id="search" action="/zixun/search/key"><input id="zixunso_M" name="a" type="text" placeholder="<?php echo $keywords?$keywords:"搜资讯"?>" /><a id="searchzixun" href="#"></a></form></div>
              <div  class="infor_right_weixin">
              <span><a href="<?php echo urlbuilder::root('weixin');?>" title="微信"><img src="<?php echo URL::webstatic("/images/platform/information/weixin.jpg")?>" alt="微信" /></a></span>
              <p>
              <b>微信扫一下</b>
              <em>微信号：</em>
              <em>yijuhuawang</em>
              <em>投资资讯天天推荐</em>              </p>
              </div>
              <div class="infor_right_btn"><a href="<?php echo zxurlbuilder::zixunTougao()?>" target="_blank"><img src="<?php echo URL::webstatic("images/platform/information/icon13.png")?>" /><b>投稿点这里</b></a></div>
              <div class="infor_right_btn1">
                <a href="<?php echo URL::webwen('');?>">创业问题我知道</a>
              </div> 
              <div class="clear"></div>
            </div>
			
			<?php if($tuijiantag != ""){?>
            <div class="infor_right_hot">
              <div class="infor_right_tit"><h2>热门标签</h2></div>
              <ul class="infor_hot_label">
              <?php
              if ($tuijiantag!=""){
                      foreach ($tuijiantag as $k=>$v){
                           echo '<li><a href="'.zxurlbuilder::tag($v->tag_name).'" target="_self" title="'.$v->tag_name.'">'.$v->tag_name.'</a></li>';
                      }
              }
              ?>
              </ul>
              <div class="clear"></div>
            </div>
            <?php }?>
            
            <?php }?>
            <?php

            if (isset($hotrecommend)&&$hotrecommend!="" && $currentcolumnid!=29 ){
            ?>
            <div class="infor_right_hot">
              <div class="infor_right_tit"><h2><?php echo arr::get($hotrecommend,'title');?>特别推荐</h2></div>
              <div class="infor_right_hotcont">
                  <ul class="infor_right_hottext">
                  <?php
                  if (isset($hotrecommend)&&$hotrecommend!=""){
                          foreach ($hotrecommend['list'] as $k=>$v){
                                  echo '<li><a href="'.zxurlbuilder::zixuninfo($v->article_id,date("Ym",$v->article_intime)).'" target="_blank" title="'.$v->article_name.'">'.$v->article_name.'</a></li>';
                          }
                  }
                  ?>
                  </ul>
                  <div class="clear"></div>
              </div>
              <div class="clear"></div>
            </div>
            <?php }?>
            <div class="infor_right">
            <?php
            if(isset($tui_list)&&$tui_list!=''){
            ?>
           <!-- 右侧 -->

            <div class="infor_right_hot infor_right_hot_first">
              <div class="infor_right_tit">
                <h2>专栏特别推荐</h2>
              </div>
              <div class="infor_right_hotcont">
                <ul class="infor_right_zltuijian">
                 <?php foreach($tui_list as $tui_v){?>
                  <li>
                    <a href="<?php echo zxurlbuilder::zhuanlaninfo($tui_v->zl_id);?>" title="<?php echo zixun::setContentReplace($tui_v->zl_title);?>" ><img width="50" height="40" src="<?php if($tui_v->zl_pic){ echo URL::imgurl($tui_v->zl_pic);}else{echo URL::webstatic("images/platform/information/zl_default_tp.png");}?>" alt="<?php echo zixun::setContentReplace($tui_v->zl_title);?>"/></a>
                    <a href="<?php echo zxurlbuilder::zhuanlaninfo($tui_v->zl_id);?>" title="<?php echo zixun::setContentReplace($tui_v->zl_title);?>" class="zltuijian_font"><?php echo mb_substr($tui_v->zl_title,0,20,'UTF-8');?></a>
                  </li>
                 <?php }?>
                </ul>
                <div class="clear"></div>
              </div>
              <div class="clear"></div>
            </div>

          <!-- 右侧 END -->
            <?php }?>
            <div class="infor_right_hot">
              <div class="infor_right_tit"><h2>热文点击排行</h2></div>
              <?php if(isset($ishangyenews) && $ishangyenews == 1){?>
              <div class="infor_right_hotcont">
                  <div class="infor_right_tab">
                      <a href="javascript:void(0)" class="cur" onmouseover="nTabs('show_1')" id="show_1">最近7天</a><a href="javascript:void(0)" class="last" onmouseover="nTabs('show_30')" id="show_30">30天</a>
                  </div>
                  <div class="clear"></div>
                  <ul class="infor_right_hottext" id="show_1_ul">
                     <?php
                     if($hangyePv_list_1!=""){
                         foreach ($hangyePv_list_1 as $k=>$v){

                            echo '<li><a href="'.zxurlbuilder::industryzixuninfo($v->article_id,date("Ym",$v->article_intime)).'" target="_blank" title="'.$v->article_name.'">'.$v->article_name.'</a></li>';
                            }
                     }
                     ?>
                  </ul>                  
                  <ul class="infor_right_hottext" id="show_30_ul" style="display:none">
                  <?php
                     if($hangyePv_list_2!=""){
                         foreach ($hangyePv_list_2 as $k=>$v){
                             
                             echo '<li><a href="'.zxurlbuilder::industryzixuninfo($v->article_id,date("Ym",$v->article_intime)).'" target="_blank" title="'.$v->article_name.'">'.$v->article_name.'</a></li>';
                         }
                     }
                     ?>
                  </ul>
                  <div class="clear"></div>
              </div>
              <?php }else{?>
              <div class="infor_right_hotcont">
                  <div class="infor_right_tab">
                      <a href="javascript:void(0)" class="cur" onmouseover="nTabs('show_1')" id="show_1">24小时</a><a href="javascript:void(0)"  onmouseover="nTabs('show_7')" id="show_7">7天</a><a href="javascript:void(0)" class="last" onmouseover="nTabs('show_30')" id="show_30">30天</a>
                  </div>
                  <div class="clear"></div>
                  <ul class="infor_right_hottext" id="show_1_ul">
                     <?php
                     if($paihang_today!=""){
                         foreach ($paihang_today as $k=>$v){

                            if(!isset($loop_count) || !$loop_count){
                                if($k>9){
                                    break;
                                }
                            }

                            echo '<li><a href="'.zxurlbuilder::zixuninfo($v->article_id,date("Ym",$v->article_intime)).'" target="_blank" title="'.$v->article_name.'">'.$v->article_name.'</a></li>';
                            }
                     }
                     ?>
                  </ul>
                  <ul class="infor_right_hottext" id="show_7_ul" style="display:none">
                    <?php
                     if($paihang_week!=""){
                         foreach ($paihang_week as $k=>$v){
                            if(!isset($loop_count) || !$loop_count){
                                if($k>9){
                                    break;
                                }
                            }
                          echo '<li><a href="'.zxurlbuilder::zixuninfo($v->article_id,date("Ym",$v->article_intime)).'" target="_blank" title="'.$v->article_name.'">'.$v->article_name.'</a></li>';
                         }
                     }
                     ?>

                  </ul>
                  <ul class="infor_right_hottext" id="show_30_ul" style="display:none">
                  <?php
                     if($paihang_month!=""){
                         foreach ($paihang_month as $k=>$v){
                             if(!isset($loop_count) || !$loop_count){
                                 if($k>9){
                                     break;
                                 }
                             }
                             echo '<li><a href="'.zxurlbuilder::zixuninfo($v->article_id,date("Ym",$v->article_intime)).'" target="_blank" title="'.$v->article_name.'">'.$v->article_name.'</a></li>';
                         }
                     }
                     ?>
                  </ul>
                  <div class="clear"></div>
              </div>
              <?php }?>
              <div class="clear"></div>
            </div>
           </div>
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

<script>
nambo_float(document.getElementById("infor_menu"), 233, true);
</script>

<?php if( $reg_fu_is_login===false ){?>
  <input type="hidden" value="<?php echo $to_url?>" id="to_url_id">
    <input type="hidden" value="<?php echo $reg_fu_platform_num?>" id="reg_fu_platform_num_id">
    <input type="hidden" value="<?php echo $reg_fu_user_num?>" id="reg_fu_user_num_id">
      <input type="hidden" id="loginHidden" value="0" />
<?php }?>

<script>
$(function(){
    inputWord("搜资讯",$("#zixunso_M"),"text");
    //资讯搜索
    $("#searchzixun").click(function(){
        if( $("#zixunso_M").val()=="搜资讯"){
            alert("请输入关键字");
            return false;
        }
        else{
            $('#search').submit();
        }
    });
    $("body").focus();

})
</script>