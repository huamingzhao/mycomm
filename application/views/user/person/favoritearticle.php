 <?php echo URL::webcss("information_ht.css"); ?>
 <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>我收藏的文章</span><div class="clear"></div></h2>
                	<div class="my_business_infor">
                        <!--我收藏的文章-->
                         <div class="infor_newlist">
                          <ul>
                          <?php foreach ($list as $v){?>
                          <li>
                            <h3><a target="_blank" href="<?php echo  zxurlbuilder::zixuninfo($v['article_id'],date("Ym",$v['article_intime']));?>" title="<?=$v['article_name']?>">
                               <?php
                if (strlen(html_entity_decode(htmlspecialchars_decode($v['article_name'])))>80)
                	echo mb_substr(html_entity_decode(htmlspecialchars_decode($v['article_name'])),0,20,'UTF-8').'...';
				else
					echo html_entity_decode(htmlspecialchars_decode($v['article_name']));
                ?>
                         </a><em></em></h3>
                            <div class="infor_newlist_left <?php if(empty($v['article_img'])){?>infor_newlist_left_noimg<?php }?>">
                              <label><b><?=date("Y年m月d日  H:i",$v['add_time']);?>更新</b><em>标签：</em>
                               <?php if ($v['article_tag']!=""){
			                  	$v['article_tag'] = str_replace("，", ',', $v['article_tag']);
			                  	if (strrpos($v['article_tag'],',')){
			                  		$tags = explode(',', $v['article_tag']);
			                  		foreach ($tags as $tag)
			                  			echo '<a href="'.zxurlbuilder::tag($tag).'" title="'.$tag.'">'.$tag.'</a><b>；</b>';
			                  	}
			                  	else
			                  		echo '<a href="'.zxurlbuilder::tag($v['article_tag']).'" title="'.$v['article_tag'].'">'.$v['article_tag'].'</a>';
			                  }?></label>
                              <span class="infor_new_text"><?php echo UTF8::substr(zixun::setContentReplace($v['article_content']), 0,100)?><?php if( UTF8::strlen( zixun::setContentReplace($v['article_content']) )>100 ){?>...<?php }?></span>
                             <p><span class="infor_new_iconlist"><a href="#" class="infor_new_icon03"  del="<?=$v['article_id'];?>">取消收藏</a></span></p>
                              <div class="clear"></div>
                            </div>
                           <?php if(!empty($v['article_img'])){?> <div class="infor_newlist_right"><a href="<?php echo zxurlbuilder::zixuninfo($v['article_id'],date("Ym",$v['article_intime']));?>" title="bfghgbfh"><img src="<?php echo URL::imgurl($v['article_img']);?>" alt="<?=$v['article_name']?>" /></a></div><?php }?>
                            <div class="clear"></div>
                          </li>
                          <?php }?>
                          </ul>
                       </div>
                       <?=$page?>
                      <div class="clear"></div>
                    </div>
                </div>
                <!--主体部分结束-->
<!--弹出框开始-->
<div id="opacity_box"></div>
<div id="mess_delete_box">
    <p style="text-align: left;height: 20px;line-height: 20px; margin-left: 120px;"><img src="<?php echo URL::webstatic("images/information_ht/wen.jpg");?>" style="padding-right: 5px; float:left;padding-left: 20px;"/><span style="height: 20px;line-height: 20px;">您确定要取消收藏这篇文章吗？</span></p>
    <p class="btn"><a href="#" class="ok">确定</a>
      <a href="#" class="cancel">取消</a></p>
</div>
<!--弹出框结束-->
<script>
//删除从业经验
var clo = $(".close");
var cancel = $(".cancel")
var delete_one = $(".infor_new_icon03");
delete_one.click(function(){
    // $("#opacity_box").show();
    var del_id = $(this).attr("del");
    var del_url = "/zixun/zixun/cancelFavorite?article_id="+del_id;
    $(".btn a.ok").attr({'href':del_url});
    $("body")[0].show({title:"取消收藏文章", content:$("#mess_delete_box").html()});
    // $("#mess_delete_box").slideDown(500);
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