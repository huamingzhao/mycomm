<!--右侧开始-->
    <div id="right">
        <div id="right_top"><span><?=$title?></span><div class="clear"></div></div>
        
        <div id="right_con">
            <div class="noserchresult">
                <div class="notishibox">
                    <p class="noserchmt18">
                        <?php $str = "";if(isset($display_project_type) && $display_project_type ==1){
                                if(isset($display_type) && $display_type == 1){
                                echo "您需要先验证手机号码且上传企业资质图片才可以发布项目哦！";
                                $str = "验证手机号";
                                }elseif(isset($display_type) && $display_type == 2){
                                    echo "您需要先验证手机号码且上传企业资质图片才可以发布项目哦！";
                                    $str = "验证手机号";
                                }elseif(isset($display_type) && $display_type == 3){
                                    echo " 您需要先上传企业资质图片才可以发布项目哦！";
                                    $str = "上传企业资质图片";
                                }elseif(isset($display_type) && $display_type == 4){
                                    echo " 请先完善企业基本信息，这样您才有自己的名片哦！";
                                    $str = "完善基本信息";
                                }
                            }else{
                                 echo "请先完善企业基本信息，这样您才可以使用账户中心功能！";
                                 $str = "完善基本信息";
                            }
                    ?>
                    </p> 
                </div>
                <p>
                    <a href="<?php echo URL::website('').isset($hrefUrl) ? $hrefUrl : "";?>"><?=$str?></a>
                </p>
            </div>
        </div>
    </div>
<!-- Baidu Button BEGIN -->