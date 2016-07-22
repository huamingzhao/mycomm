<?php echo URL::webjs("mycard.js")?>
    <?php echo URL::webcss("card_zg.css")?>
     <!--右侧开始-->
     <div id="right">
        <div id="right_top"><span><a href="<?php echo URL::website('/company/member/card/mycard')?>">我的名片</a>选择名片模板</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="mycard2" style="height:650px;">
                <ul>
                <?php foreach( $imglist as $key=>$value){ ?>
                    <li><span><img src="<?php echo URL::webstatic($value) ?>"  /></span><a  href="javascrpt:void(0)">
                    <img src="<?php echo URL::webstatic("/images/mycard/mycard_li_btn.png") ?>"  id="show_<?php echo $key?>" /></a></li>
                    <a id="card_<?php echo $key;?>" style="display:none"><?php echo $key;?></a>
                <?php } ?>
                </ul>
            </div>
            <div class="clear"></div>
            <?=$page;?>
            <div id="mycard2_back"><a href="<?php echo URL::website('/company/member/card/mycard')?>">
            <img src="<?php echo URL::webstatic("/images/mycard/mycard2_back.png") ?>" /></a></div>
        </div>
    </div>
    <!--右侧结束-->
<!--透明图层开始-->
<div id="opacity"></div>
<!--透明图层结束-->
<!--弹出框开始-->
<div id="mycard2_box"><a href="#" class="close">关闭</a>
  <div class="card">
                    <dl>
                        <dt>
                           <?php
                                if ($logo==0)
                                    echo HTML::image(URL::imgurl($companyinfo->com_logo),array('alt'=>$companyinfo->com_logo,'class'=>"mycard_img"));
                                else{
                                    foreach ($pro as $v){
                                        if ($logo>0 and $logo==$v->project_id)
                                            echo HTML::image(URL::imgurl($v->project_logo),array('alt'=>$v->project_logo,'class'=>"mycard_img"));
                                    }
                                }
                                ?>
                        </dt>
                        <dd>
                            <h1><?php echo mb_substr($companyinfo->com_name,0,17,'UTF-8')?></h1>
                            <h2><p><?php echo mb_substr($companyinfo->com_contact,0,4,'UTF-8')?></p><span><?php echo $companyinfo->com_phone;?></span></h2>
                            <div class="cardDetail">
                              <p>公司地址：<?php echo $companyinfo->com_adress?></p>
                             <p>公司网址：<?php echo $companyinfo->com_site?></p>
                            </div>

                        </dd>
                    </dl>
                    <div class="clear"></div>
                    <div class="proPara">
                    <h3>>>企业旗下项目：</h3>
                          <?php
                                foreach ($pro as $v){
                                    if ($brand!="")
                                    {
                                        $ids = $brand;
                                        foreach ($ids as $j){
                                            if ($v->project_id==$j)
                                                echo "<p><em><a href=". urlbuilder::project($v->project_id).">".$v->project_brand_name.'</a>——</em><a href="'.urlbuilder::project($v->project_id).'">'.strip_tags($v->project_summary)."</a></p>";
                                        }
                                    }
                                }
                            ?>
                    </div>
                </div>
    <div class="box_btn">
    <a href="<?php echo URL::website('/company/member/card/savecardstyle?cardkey=')?>">
    <img src="<?php echo URL::webstatic("/images/mycard/box_btn1.png") ?>" /></a>
    <a href="<?php echo URL::website('/company/member/card/cardstyle')?>">
    <img src="<?php echo URL::webstatic("/images/mycard/box_btn2.png") ?>" /></a>
    <a href="<?php echo URL::website('/company/member/card/mycard')?>">
    <img src="<?php echo URL::webstatic("/images/mycard/box_btn3.png") ?>" /></a></div>
</div>
<!--弹出框结束-->