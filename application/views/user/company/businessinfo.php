<?php echo URL::webjs("cardstyle.js");?>
<?php echo URL::webcss("card_zg.css")?>
<!--右侧开始--><div id="right">
<style>
.card dd h2 span {
    color: #FFFFFF;
    float: left;
    font: bold 24px/24px Arial;
    height: 24px;
    padding: 0 0 0 18px;
    width: 190px;
    word-break: break-all;
}
</style>
       <a id="cardstyle_<?php echo $cardstyleid;?>" style="display:none"></a>
        <div id="right_top"><span>你好，欢迎进入我的名片！</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="index">
                <div class="card">
                    <dl style="padding:0 0px 30px 18px;">
                        <dt>
                           <?php
                                if ($logo==0)
                                    echo HTML::image(URL::imgurl($companyinfo->com_logo),array('alt'=>$companyinfo->com_logo,'class'=>"mycard_img"));
                                else{
                                    foreach ($pro as $v){
                                        if ($logo>0 and $logo==$v->project_id)
                                        {
                                        	if($v->project_source != 1) {
													$imgurlpro= project::conversionProjectImg($v->project_source, 'logo', $v->as_array());
                                             } else {$imgurlpro= URL::imgurl($v->project_logo);}
                                            echo HTML::image($imgurlpro,array('alt'=>$v->project_logo,'class'=>"mycard_img"));
                                        }
                                      }
                                }
                                ?>
                        </dt>
                        <dd>
                            <h1><?php echo mb_substr($companyinfo->com_name,0,16,'UTF-8')?></h1>
                            <h2><p><?php echo mb_substr($companyinfo->com_contact,0,4,'UTF-8')?></p><span><?php echo $companyinfo->com_phone;?></span></h2>
                            <div class="cardDetail">
                              <p>公司地址：<?php echo $companyinfo->com_adress?></p>
                               <p>
                               <?php if($companyinfo->com_site!='') { ?>
                                 公司网址：<?php echo $companyinfo->com_site?>
                               <?php } ?>
                              </p>
                            </div>
                            <div class="clear"></div>
                        </dd>
                        <div class="clear"></div>
                    </dl>
                    <div class="clear"></div>
                    <div class="proPara" style="height:116px;">
                          <?php
                          if ($brand!="" && $brand && count($pro)>0){
                               echo '<h3><span>>></span>企业旗下项目：</h3>';
                               foreach ($pro as $v){
                                   if ($brand!="" && $brand)
                                   {
                                       $ids = $brand;
                                       foreach ($ids as $j){
                                           if ($v->project_id==$j){
                                               $sumary_texts=htmlspecialchars_decode($v->project_summary);
                                               echo "<p><span><em><a href=". urlbuilder::project($v->project_id) .' target="_blank">'.$v->project_brand_name.'</a>——</em><a href="'.urlbuilder::project($v->project_id).'" target="_blank">'.mb_substr(strip_tags($sumary_texts),0,32,'UTF-8')."...</a></span></p>";
                                           }
                                       }
                                   }
                               }
                          }else{
                              echo '<span style="text-align:center;padding-top:55px;color: #7B7B7B; height:30px;display: block;font-size: 14px;line-height:30px;">暂无项目信息</span>';
                          }
                         ?>
                    </div>
                    <div class="modify_btnlist" style="margin-top:30px;">
                         <a href="<?php echo URL::website('/company/member/basic/editCompany') ?>?type=2" class="btnlist_modify_card"></a>
                    </div>

                    <div class="cardDecoration" style="padding:25px 0 0 120px;">
                    <?php if ($isfisrthasproject){ ?>
                      <span>您发布的项目正在审核中，无法添加至名片上，请耐心等待！</span>
                     <?php } elseif ($newprojectcount>0){ ?>
                      <span>您又增加了<em><?php echo $newprojectcount ?></em>个新项目，要设置名片内容？</span>
                      <a href="<?php echo URL::website('/company/member/card/completecard') ?>">点击这里</a>
                      <?php } elseif($ishasproject==0){ ?>
                      <span>您的名片上还没有项目信息哦，去发布项目完善名片吧!</span>
                      <a href="<?php echo URL::website('/company/member/project/addproject') ?>">点击这里</a>
                     <?php } elseif(!$brand){ ?>
                      <span><span>您还未添加项目至名片上>></span><a href="<?php echo URL::website('/company/member/card/completecard') ?>">设置名片内容</a></span>

                     <?php } else{?>
                    <span>需要更改名片上的显示信息？</span>
                    <a href="<?php echo URL::website('/company/member/card/completecard') ?>"> 重新选择名片内容</a>
                    <?php }?>

                    <div id="bdshare"  data="{'url':'<?php if($brand && isset($brand[0])){echo urlbuilder::project($brand[0]);} else{ echo URL::website('');}?>'}" class="bdshare_t bds_tools get-codes-bdshare" style="float:left;padding-top:5px;padding-left:30px; line-height:16px;">
                        <span style="float:left; padding-top:5px; color:#000;">分享我的名片至：</span>
                        <a class="bds_tsina"></a>
                        <a class="bds_tqq"></a>
                    </div>
                    </div>
                    <img id="card_image_url" src="<?php echo URL::imgurl($companyinfo->com_card_image);?>" style="display:none" />
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <!--右侧结束-->
    <div class="clear"></div>
<!-- Baidu Button BEGIN -->
<script type="text/javascript" id="bdshare_js" data="type=tools" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
//分享图片
var shareimg=$("#card_image_url").attr("src");
var bds_config = {'bdText':'我在一句话网站生成一张招商名片，你也来试试吧！','bdPic':shareimg};
document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
</script>
<!-- Baidu Button END -->