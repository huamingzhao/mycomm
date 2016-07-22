
<?php echo URL::webjs("my_business.js")?>
     <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>我的投资考察会</span><div class="clear"></div></h2>
                    <?php if($num>0){?>
                    <div class="noserchresult">
                        <div class="notishibox">
                            <p class="noserchfz18">您还没有发布过投资考察会。</p> 
                            <p class="noserchfz14 mt10">发布投资考察会，可以与投资者面对面交流，合作的机会更大哦！</p>
                        </div>
                        <p><a href="<?php echo URL::website('/company/member/project/addinvest')?>">发布投资考察会</a></p>
                    </div>
                    <?php }else{?>
                    <div class="noserchresult">
                        <div class="notishibox">
                            <p class="noserchmt18">您需要先发布项目才可以发布投资考察会哦！</p> 
                        </div>
                        <p><a href="<?php echo URL::website('/company/member/project/addproject')?>">发布项目</a></p>
                    </div>
                    <?php }?>
                </div>
                <!--主体部分结束-->

