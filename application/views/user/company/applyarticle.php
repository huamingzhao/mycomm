<?php echo URL::webcss("information_ht.css"); ?>
<!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>我投稿的文章</span>&nbsp;<a class="floright tougaoenter" href="<?php echo URL::website("/zixun/zixun/tougao")?>">投稿</a><div class="clear"></div></h2>
                	<div class="my_business_infor">

                        <!--我收藏的文章-->
                         <div class="infor_newlist">
                          <ul>

                          <?php foreach ($list as $v){?>
                          <li class="tougao_ht" id="show_tougao_<?php echo $v->article_id?>">
                            <h3>
                               <a target="_black" class="tougao_ht_title"  href="<?php if($v->article_status==2){echo zxurlbuilder::zixuninfo($v->article_id,date("Ym",$v->article_intime));}else{echo URL::site('/member/showzixun?id='.$v->article_id);}?>" title="<?=$v->article_tag?>"><?=$v->article_name?></a>
                               <p>
                                 <span class="tougao_shenhe01"></span>
                                 <?php if($v->article_status==1){?>
                                 <span class="tougao_shenhe02 tougao_audit01"><img src="<?php echo URL::webstatic("images/information_ht/icon_01.jpg")?>"><b>文章审核中</b></span>
                                 <?php }elseif($v->article_status==2){?>
                                 <span class="tougao_shenhe02 tougao_audit02"><img src="<?php echo URL::webstatic("images/information_ht/icon_02.jpg")?>"><b>审核通过已发布</b></span>
                                 <?php }elseif($v->article_status==3){?>
                                 <span class="tougao_shenhe02 tougao_audit03"><img src="<?php echo URL::webstatic("images/information_ht/icon_03.jpg")?>"><b>审核未通过</b></span>
                                 <?php }?>
                                 <span class="tougao_shenhe03"></span>
                               </p>
                               <span class="tougao_edit">
                                 <?php if($v->article_status==3){?>
                                 <a href="javascript:void(0)" onclick="deletetougao('<?php echo $v->article_id;?>')" class="tougao_edit03"><img src="<?php echo URL::webstatic("images/information_ht/icon_06.jpg")?>"><b>删除</b></a>
                                 <?php }?>
                                 <?php if($v->article_status==3){?>
                                 <a href="<?php echo URL::site('/zixun/zixun/updateMyArticle?articleid='.$v->article_id)?>" class="tougao_edit02"><img src="<?php echo URL::webstatic("images/information_ht/icon_05.jpg")?>"><b>修改</b></a>
                                 <?php }?>
                                 <?php if($v->article_status==2){?>
                                 <a href="<?php echo zxurlbuilder::zixuninfo($v->article_id,date("Ym",$v->article_intime));?>" class="tougao_edit01"><img src="<?php echo URL::webstatic("images/information_ht/icon_04.jpg")?>"><b>查看</b></a>
                                 <?php }?>
                               </span>
                            </h3>
                            <div class="infor_newlist_left infor_newlist_left02 infor_newlist_left_2  <?php if(empty($v->article_img)){?>infor_newlist_left_noimg<?php }?>">
                              <label><b><?=date("Y.m.d H:i",$v->article_intime);?>投稿 </b><em><? echo common::getcolumnname($v->parent_id)?></em><?php if(!empty($v->column_id)){?><b>></b><a href="#" title="<?=$v->article_tag?>"><? echo common::getcolumnname($v->column_id)?></a><?php }?></label>
                              <span class="infor_new_text"><?php echo UTF8::substr(zixun::setContentReplace($v->article_content), 0,100)?><?php if( UTF8::strlen( zixun::setContentReplace($v->article_content) )>100 ){?>...<?php }?></span>
                              <div class="clear"></div>
                            </div>
                            <?php if(!empty($v->article_img)){?><div class="infor_newlist_right02"><a href="<?php if($v->article_status==2){echo zxurlbuilder::zixuninfo($v->article_id,date("Ym",$v->article_intime));}else{echo 'javascript:void(0);';}?>" title="<?=$v->article_tag?>"><img src="<?php echo URL::imgurl($v->article_img);?>" /></a></div><?php }?>
                            <div class="clear"></div>
                          </li>
                         <?php }?>


                          </ul>
                       </div>

                      <div class="clear"></div>
                    </div>
                </div>
                <!--主体部分结束-->