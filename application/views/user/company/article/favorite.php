<?php echo URL::webcss("information_ht.css")?>
<!--主体部分开始-->
                <div class="right">
    <h2 class="user_right_title"><span>我收藏的文章</span><div class="clear"></div></h2>
                    <div class="my_business_infor">

                        <?php if($list){?>
                        <!--我收藏的文章-->
                         <div class="infor_newlist">
                          <ul>
            <?php if($list){?>
            <?php foreach ($list as $k=>$val){ $v=$val->article;?>
              <li>
                <h3><a target="_blank" href="<?php echo zxurlbuilder::zixuninfo($v->article_id,date("Ym",$v->article_intime));?>" title="">
                <?php
                if (strlen(html_entity_decode(htmlspecialchars_decode($v->article_name)))>80)
                    echo mb_substr(html_entity_decode(htmlspecialchars_decode($v->article_name)),0,20,'UTF-8').'...';
                else
                    echo html_entity_decode(htmlspecialchars_decode($v->article_name));
                ?></a>
                <?php if ($v->article_recommend==1){?><em></em><?php }?></h3>
                <div class="infor_newlist_left <?php if ($v->article_img==''){echo 'infor_newlist_left_noimg';}?>">
                  <label><b><?php echo date('Y年m月n日 H:i',$v->article_addtime)?>更新</b>
                  <em>标签：</em>
                  <?php if ($v->article_tag!=""){
                      $v->article_tag = str_replace("，", ',', $v->article_tag);
                      if (strrpos($v->article_tag,',')){
                          $tags = explode(',', $v->article_tag);
                          foreach ($tags as $tag)
                              echo '<a href="'.zxurlbuilder::tag($tag).'" title="bfghng">'.$tag.'</a><b>；</b>';
                      }
                      else
                          echo '<a href="'.zxurlbuilder::tag($v->article_tag).'" title="bfghng">'.$v->article_tag.'</a>';
                  }?>
                  </label>
                  <span class="infor_new_text"><?php echo UTF8::substr(zixun::setContentReplace($v->article_content), 0,100)?><?php if( UTF8::strlen( zixun::setContentReplace($v->article_content) )>100 ){?>...<?php }?></span>
                  <p>
                      <span class="infor_new_iconlist  infor_new_iconlist02">
                          <a href="#" class="infor_new_icon03" del="<?=$v->article_id;?>">取消收藏</a>
                      </span>
                  </p>
                  <div class="clear"></div>
                </div>
                <?php if ($v->article_img!=""){?>
                <?php echo '<div class="infor_newlist_right"><a href="'.zxurlbuilder::zixuninfo($v->article_id,date("Ym",$v->article_intime)).'" title="bfghgbfh"><img src="'.URL::imgurl($v->article_img).'" alt="" /></a></div>'?>
                <?php }?>
                <div class="clear"></div>
              </li>
              <?php }?>
              <?php echo $page;?>
              <?php }?>
                          </ul>
                       </div>
              <!-- 无收藏 -->
              <?php }else{?>
                        <div class="noserchresult">
                        <div class="notishibox">
                            <p class="noserchfz18">您还没有收藏过资讯文章。</p> 
                            <p class="noserchfz14 mt10">知识就是财富，去学习投资开店知识吧！</p>  
                        </div>
                        <p><a href="<?php echo URL::website("/zixun/")?>">去学做生意</a></p>
                    </div>
              <?php }?>

                      <div class="clear"></div>
                    </div>
                </div>
                <!--主体部分结束-->
<!--弹出框开始-->
<div id="opacity_box"></div>
<div id="mess_delete_box">
    <a href="#" class="close">关闭</a>
    <p style="text-align: left;height: 20px;line-height: 20px; margin-left: 120px;"><img src="<?php echo URL::webstatic("images/information_ht/wen.jpg");?>" style="padding-right: 5px; float:left;padding-left: 20px;"/><span style="height: 20px;line-height: 20px;">您确定要取消收藏这篇文章吗？</span></p>
    <p><a href="#" class="ensure"><img src="http://static.myczzs.com/images/my_messages/ensure.png" /></a><a href="#" class="cancel"><img src="http://static.myczzs.com/images/my_messages/cancel.png" /></a></p>
</div>
<!--弹出框结束-->
<script>
//删除从业经验
var clo = $(".close");
var cancel = $(".cancel")
var delete_one = $(".infor_new_icon03");
delete_one.click(function(){
    var del_id = $(this).attr("del");
    var del_url = $config.siteurl+"company/member/article/cancelFavorite?article_id="+del_id;
    // $("#opacity_box").show();
    // $("a.ensure").attr({'href':del_url});
    // $("#mess_delete_box").slideDown(500);
    $("body")[0].show({
      title:"取消收藏文章",
      content:[
      '<p>',
      '<img src="'+$config.staticurl+'/images/information_ht/wen.jpg" style="padding-right: 5px; float:left;padding-left: 20px;">',
      '<span style="height: 20px;line-height: 20px;">您确定要取消收藏这篇文章吗？</span>',
      '</p>',
      '<p class="btn">',
      '<a href='+del_url+' class="ok">确定</a><a href="#" class="cancel">取消</a></p>'
      ].join("")
    });
    return false;
});
clo.click(function(){
    $(this).parent().slideUp(500,function(){
        $("#opacity_box").hide();
    });
    return false;
});
cancel.click(function(){
    $("#mess_delete_box").slideUp(500,function(){
        $("#opacity_box").hide();
    });
    return false;
});
</script>