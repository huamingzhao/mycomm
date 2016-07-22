<?php echo URL::webjs("search_pro.js")?>
<?php echo URL::webjs("invitepro_zg.js")?>
<style>
*{ font-family:"宋体";}
.search_pro .search_list p span{ width:48%;}
</style>
             <!--主体部分开始-->
                <div class="right" style="height:auto !important;height:620px;min-height:620px;">
                    <h2 class="user_right_title"><span>搜索项目</span><div class="clear"></div></h2>
                    <form method="get" action="<?php echo URL::site('xiangdao/fenlei/');?>">
                       <div class="search_pro">
                        <div class="search_list" <?php if(!$hidden){ echo 'style="height:200px"';} ?> >
                            <p>
                                <span>
                                    <em>项目所属行业：</em>
                                    <select class="long" id="hye" name="question6_id" >
                                        <option value="" >不选</option>
                                        <?php foreach ($list_industry as $value): ?>
                                        <option value="<?=$value->industry_id;?>" <?php if(arr::get($search,'question6_id')==$value->industry_id): echo 'selected="selected"';endif;?> ><?=$value->industry_name;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </span>
                                <span>
                                    <em>项目地区：</em>
                                   <select id="address" class="short" name="pro_id">
                                        <option value="">不选</option>
                                        <?php foreach ($area as $v){?>
                                        <option value="<?=$v['cit_id']?>" <?php if($v['cit_id']==arr::get($search, 'pro_id')){echo "selected='selected'";}?>><?=$v['cit_name']?></option>
                                        <?php }?>
                                    </select>
                                    <select id="address1" class="short" name="area_id" >
                                          <option value="" >不选</option>
                                            <?php if(arr::get($search,'pro_id')!='' && count($cityarea)){ foreach ($cityarea as $v): ?>
                                             <option value="<?=$v->cit_id ;?>" <?php if($v->cit_id == arr::get($search,'area_id')) echo "selected='selected'"; ?> ><?=$v->cit_name;?></option>
                                             <?php endforeach; } ?>
                                     </select>
                                </span>
                            </p>
                            <p>
                                <span>
                                    <em>项目金额：</em>
                                  <select id="money" class="long" name="question7_id">
                                    <option value="">不选</option>
                                    <?php foreach ($money as $k=>$v){?>
                                    <option value="<?=$k?>" <?php if($k==arr::get($search, 'question7_id')){echo "selected='selected'";}?>><?=$v?></option>
                                    <?php }?>
                                  </select>
                                </span>
                                <span>
                                    <em>项目风险：</em>
                                  <select id="question10_id" class="short" name="question10_id">
                                    <option value="">不选</option>
                                    <?php foreach ($question10 as $k=>$v){?>
                                    <option value="<?=$k?>" <?php if($k==arr::get($search, 'question10_id')){echo "selected='selected'";}?>><?=$v?></option>
                                    <?php }?>
                                  </select>
                                </span>
                            </p>
                            <p>
                                <span>
                                    <em>项目投资回报率：</em>
                                  <select id="question8_id" class="long" name="question8_id">
                                    <option value="">不选</option>
                                    <?php foreach ($question8 as $k=>$v){?>
                                    <option value="<?=$k?>" <?php if($k==arr::get($search, 'question8_id')){echo "selected='selected'";}?>><?=$v?></option>
                                    <?php }?>
                                  </select>
                                </span>
                                <span>
                                    <em>项目适合人脉关系：</em>
                                  <select id="question5_id" class="long" name="question5_id">
                                    <option value="">不选</option>
                                    <?php foreach ($question5 as $k=>$v){?>
                                    <option value="<?=$k?>" <?php if($k==arr::get($search, 'question5_id')){echo "selected='selected'";}?>><?=$v?></option>
                                    <?php }?>
                                  </select>
                                </span>
                            </p>
                            <p>
                                <span>
                                    <em>项目投资形式：</em>
                                  <select id="question1_id" class="long" name="question1_id">
                                    <option value="">不选</option>
                                    <?php foreach ($question1 as $k=>$v){?>
                                    <option value="<?=$k?>" <?php if($k==arr::get($search, 'question1_id')){echo "selected='selected'";}?>><?=$v?></option>
                                    <?php }?>
                                  </select>
                                </span>
                            </p>
                            <?php if($hidden){  ?>
                            <a href="#" class="toggle">更多搜索</a>
                            <?php } else { ?>
                            <a class="toggle" href="#">收起更多搜索</a>
                            <?php } ?>
                        </div>
                        <input type="image" src="<?php echo URL::webstatic("images/search_btn/search_btn.png") ?>" class="search_btn"/>
                        <div class="oneword"><b>您搜索的一句话：<?php echo $content_text;  ?></b></div>
                        <div class="result">
                            <?php if($search_count){  ?>
                            <p class="a">根据您一句话，现在为您匹配项目为<span><?php echo $totalcount ?></span>个项目
                                   <?php if($totalcount){  ?>
                                    <!--<a target="_blank" href="<?php echo URL::website('platform/guide/projectlist')?>">前去查看>></a>-->
                                    <?php } ?>
                            </p>
                            <?php } else { ?>
                             <p class="b">&nbsp;&nbsp;您还未搜索过项目，想找到心仪的投资项目赶快行动吧！</p>
                             <?php } ?>
                        </div>
                    </div>
                    </form>
                </div>
                <!--主体部分结束-->