<?php echo URL::webjs("favorite_company.js")?>
     <!--右侧开始-->
     <div id="right">
        <div id="right_top"><span>个人名片</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="mycard2" style="height:700px;">
                <ul>
                <?php foreach( $imglist as $key=>$value){ ?>
                    <li><span><img src="<?php echo URL::webstatic($value) ?>"  /></span><a href="javascrpt:void(0)">
                    <img src="<?php echo URL::webstatic("/images/mycard/mycard_li_btn.jpg") ?>"  id="show_<?php echo $key?>" /></a></li>
                    <a id="card_<?php echo $key;?>" style="display:none"><?php echo $key;?></a>
                <?php } ?>
                </ul>
                <div class="clear"></div>
  <!--              <div class="page-effect" style="padding:20px 0 10px 0; margin-top:30px;">
                    	<a href="#" class="arrow">首页</a>
                        <a href="#" class="arrow">上一页</a>
                    	<a href="#" class="current">1</a>
                        <a href="#">2</a>
                        <a href="#">3</a>
                        <a href="#">4</a>
                        <a href="#">5</a>
                        <a href="#">6</a>
                        <a href="#">7</a>
                        <a href="#">8</a>
                        <a href="#">9</a>
                        <a href="#">10</a>
                        <a href="#" class="arrow">下一页</a>
                        <a href="#" class="arrow">最后一页</a>
                    </div>
                  <div class="clear"></div>-->
            </div>
            <div class="clear"></div>
            <?php echo $page;?>
            
            <div id="mycard2_back"><a href="<?php echo URL::website('/person/member/card/mycard')?>">
            <img src="<?php echo URL::webstatic("/images/mycard/mycard2_back.jpg") ?>" /></a></div>
            <div class="clear"></div>
        </div>
    </div>
    <!--右侧结束-->
    <div class="clear"></div>
</div>
<!--透明图层开始-->
<div id="opacity"></div>
<!--透明图层结束-->
<!--弹出框开始-->
<div id="mycard2_box1"><a href="#" class="close">关闭</a>
    <div id="cardBg">
        <div id="cardBg_top">
            <div id="top_left">
                <p><b><?php echo mb_substr($personinfo->per_realname,0,4,'UTF-8')?></b> <span><?php if($personinfo->per_gender== 1):?>先生<?php else:?>女士<?php endif;?></span> <em class="tel"><?php echo $per_phone;?></em></p>
                <p><em class="email"><?php echo $email;?></em></p>
            </div>
            <div id="top_right">
                <?php echo HTML::image(URL::imgurl($personinfo->per_photo))?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="content">
            <p><b>个人所在地：</b><?php echo $personinfo->per_area;?>　　　<b>投资金额：</b>20－30万</p>
            <p><b>投资行业：</b><?php $list = common::primaryIndustry(0,$personinfo->per_industry);echo $list[0]->industry_name; ?></p>
            <p><b>投资地区：</b>湖南长沙、山东济南、上海徐汇、北京朝阳、安徽合肥</p>
            <p><b>我的标签：</b><?php echo $personinfo->per_per_label;?></p>
            <!-- <p style="height:50px;overflow:hidden;"><b>个性说明：</b><?php // echo $personinfo->per_remark;?></p> -->        
         </div>
    </div>
    <div class="box_btn">
    <a href="<?php echo URL::website('/person/member/card/savecardstyle?cardkey=')?>">
    <img src="<?php echo URL::webstatic("/images/mycard/box_btn1.jpg") ?>" /></a>
    <a href="<?php echo URL::website('/person/member/card/cardstyle')?>">
    <img src="<?php echo URL::webstatic("/images/mycard/box_btn2.jpg") ?>" /></a>
    <a href="<?php echo URL::website('/person/member/card/mycard')?>">
    <img src="<?php echo URL::webstatic("/images/mycard/box_btn3.jpg") ?>" /></a></div>
</div>
<!--弹出框结束-->