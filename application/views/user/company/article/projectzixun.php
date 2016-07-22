
                    <!--右侧开始-->
                    <div id="right">
                        <div id="right_top">
                            <span>项目新闻</span>
                            <div class="clear"></div>
                        </div>
                        <div id="right_con">
                        <?php if( $com_id <= 0 ){?>
                            <div class="noserchresult">
                                <div class="notishibox">
                                    <p class="noserchmt18">您需要先完善企业信息才可以发布项目新闻哦！</p>
                                </div>
                                <p><a href="<?php echo URL::website('/company/member/basic/company')?>">完善企业信息</a></p>
                            </div>
                        <?php
                        }
                        elseif( $project_count_all == 0 ){?>
                            <div class="noserchresult">
                                <div class="notishibox">
                                    <p class="noserchmt18">您需要先有项目才可以发布项目新闻哦！</p> 
                                </div>
                                <p><a href="<?php echo URL::website('/company/member/project/addproject')?>">发布项目</a></p>
                            </div>
                        <?php
                        }
                        elseif( $project_count_all > 0 && $project_count == 0){
                        ?>
                            <div class="noserchresult">
                                <div class="notishibox">
                                    <p class="noserchmt18">您的项目审核通过后才可发布项目新闻哦！</p> 
                                </div>
                                <p><a href="<?php echo URL::website('/company/member/project/showproject')?>">查看我的项目</a></p>
                            </div>
                        <?php
                        }
                        elseif( $tougao_num<=0 ){
                        ?>
                            <div class="noserchresult">
                                <div class="notishibox">
                                    <p class="noserchfz18">你还没有发布项目新闻。</p> 
                                    <p class="noserchfz14 mt10">发布项目新闻可以宣传您的项目信息，增强投资者信赖。</p>
                                </div>
                                <p><a href="<?php echo URL::website('/company/member/article/projecttougao')?>">发布项目新闻</a></p>
                            </div>
                        <?php }?>

                        </div>
                    </div>
