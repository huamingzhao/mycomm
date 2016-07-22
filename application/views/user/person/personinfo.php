<?php echo URL::webcss("card_zg.css")?>
<?php echo URL::webjs("personcard.js")?>
    <!--右侧开始-->
    <div id="right">
           <a id="cardstyle_<?php echo $cardstyleid;?>" style="display:none"></a>
        <div id="right_top"><span>我的名片</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="person_card">
                <div id="cardBg" class="cardTop_zg">
                    <div id="cardBg_top">
                        <div id="top_left">
                            <p><b><?php echo mb_substr($personinfo->per_realname,0,4,'UTF-8')?></b> <span><?php if($personinfo->per_gender== 1):?>先生<?php else:?>女士<?php endif;?></span> <em class="tel"><?php echo $per_phone;?></em></p>
                            <p><em class="email"><?php echo $email;?></em></p>
                        </div>
                        <div id="top_right">
                        <?php if($personinfo->per_photo==''){echo HTML::image(URL::webstatic("/images/getcards/photo.png"));} else{ echo HTML::image(URL::imgurl($personinfo->per_photo));}?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="content">
                        <p><b>个人所在地：</b><?php if($personinfo->per_area == 1){echo "上海";}else{echo "北京";}?>　　　　　　　　　<b>投资金额：</b><?php $monarr= common::moneyArr(); echo $personinfo->per_amount== 0 ? '无': $monarr[$personinfo->per_amount];?></p>
                        <p><b>投资行业：</b><?php $list = common::primaryIndustry(0,$personinfo->per_industry);echo $list[0]->industry_name; ?></p>
                        <p><b>投资地区：</b>湖南长沙、山东济南、上海徐汇、北京朝阳、安徽合肥</p>
                        <p><b>我的标签：</b><?php echo $personinfo->per_per_label;?></p>
                        <!-- <p style="height:50px;overflow:hidden;"><b>个性说明：</b><?php // echo $personinfo->per_remark;?></p> -->
                    </div>
                </div>
                
                <div class="btn">
                    <p>
                    <?php if($personinfo->per_open_stutas == 2):?>
                                        您的名片现在可以被“<?php if(isset($personinfo->per_industry)){echo $list[0]->industry_name;} ?>”搜索到，如要设置名片公开度，
                    <?php elseif($personinfo->per_open_stutas == 3):?>
                                        您的名片现在“不允许任何企业”搜索到，如要设置名片公开度，
                    <?php elseif($personinfo->per_open_stutas == 4):?>
                                        您的名片现在“允许诚信认证的企业”搜索到，如要设置名片公开度，
                    <?php else:?>
                                        您的名片现在可以被所有企业搜索到，如要设置名片公开度，
                    <?php endif;?>
                    <a href="#" class="show">点击这里</a>　 <a href="<?php echo URL::website('/person/member/card/cardstyle') ?>"><?php echo HTML::image( URL::webstatic("images/person_card/select_btn.jpg"))?></a></p>
                </div>
                <div class="radio">
                  <form id="personForm" method="post" action="<?php echo URL::website('/person/member/card/cardopendegree')?>" enctype="multipart/form-data">
                    <input type="hidden" name="per_id" value="<?php echo $personinfo->per_id;?>" />
                    <p><input type="radio" name="cardtype" value="1"  <?php if($personinfo->per_open_stutas == 1){ ?>checked <?php }?>/> 允许所有的企业搜索到我的名片</p>
                    <p><input type="radio" name="cardtype"  value="2" <?php if($personinfo->per_open_stutas == 2){ ?>checked <?php }?> /> 只允许您的意向投资行业：“<?php if(isset($personinfo->per_industry)){echo $list[0]->industry_name;} ?>”类企业搜索到我的名片</p>
                    <p><input type="radio" name="cardtype"  value="3" <?php if($personinfo->per_open_stutas == 3){ ?>checked <?php }?> /> 不允许任何企业搜索到我的名片</p>
                    <p class="btn"><input type="submit" value="确认" />　<input type="button" value="取消"  class="cancel"/></p>
                  </form>
                </div>
            </div>
        </div>
    </div>
<!-- Baidu Button BEGIN -->

<!--右侧结束-->
<div class="clear"></div>
<script type="text/javascript" id="bdshare_js" data="type=tools" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
//分享图片
var shareimg=$("#top_right img").attr("src");
var bds_config = {'bdText':"我在一句话生成一张投资名片，分享给亲们！",'bdPic':shareimg};
document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
</script>
<!-- Baidu Button END -->