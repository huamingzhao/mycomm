<?php echo URL::webcss("platform/search_tzz.css")?>
<?php echo URL::webjs("platform/home/login_fu.js")?>
<script type="text/javascript">
$(document).ready(function(){
     var boxa = $("#error_box_renling");
        var viewperinfo = $(".viewperinfo");//查看详情
        var closebox=$("#error_box_renling .tan_floor_close");
        viewperinfo.click(function(){
            $("#opacity").show();
            boxa.slideDown(500);
            return false;
        })
        closebox.click(function(){
            boxa.slideUp(500,function(){
                $("#opacity").hide();
            });
            return false;
        })
});

</script>
<div class="searchIndex_banner_bg">
    <div class="top_bg">
        <div class="search_box">
            <form action="<?php echo urlbuilder::qiye("touzizhe");?>" method="get" enctype="application/x-www-form-urlencoded">
            <div class="search_select">
                <div class="select_flo">
                           <select id="address" name="per_area"  >
                                 <option value="">选择所在地</option>
                                  <?php foreach ($area as $v){?>
                                  <option value="<?=$v['cit_id']?>" <?php if($v['cit_id']==arr::get($postlist, 'per_area')){echo "selected='selected'";}?>><?=$v['cit_name']?></option>
                                 <?php }?>
                            </select>

                                <select name="per_amount" >
                                    <option  value="" >选择意向投资金额</option>
                                    <?php $moneylist = common::moneyArr(); foreach ($moneylist as $k=>$v): ?>
                                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'per_amount')==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                                    <?php endforeach; ?>
                                </select>
                </div>
                <div class="select_flo">
                            <select name="parent_id" >
                            <option  value="" >选择意向投资行业</option>
                            <?php $primarylist = common::primaryIndustry(0); foreach ($primarylist as $v):?>
                            <option value="<?=$v->industry_id;?>" <?php if(arr::get($postlist,'parent_id')==$v): echo 'selected="selected"';endif;?> ><?=$v->industry_name;?></option>
                            <?php endforeach; ?>
                            </select>
                </div>
                <div class="button_flo"><button type="submit"></button></div>
                <div class="clear"></div>
            </div>
             </form>
        </div>
    </div>
</div>
<div class="searchIndex_content">
    <div class="case">
        <div class="case_tp"><span class="case_geren_bg">为您找到符合的投资者<?php echo $totalcount;?>名</span>
        <?php if($totalcount>8){?>
        <a href="<?php if(isset($t_url)){echo urlbuilder::qiye("touzizhe").$t_url;}else{echo urlbuilder::qiye("touzizhe");}?>" class="change">换一批</a>
        <?php }?>
        </div>
        <div class="case_geren_bg">
            <ul>
             <?php foreach ($list as $per_v){ ?>
                <li>
                    <div class="case_geren_box">
                        <div class="geren_left"><img style="cursor:pointer;" <?php if($islogin){echo 'class="viewperinfo"';}else{echo 'class="person_search_login"';}?> src="<?php  if(!empty($per_v['per_photo'])) { echo URL::imgurl($per_v['per_photo']);} else{ echo URL::webstatic("images/getcards/photo.png") ;}?>"/></div>
                        <div class="geren_right" id="personlist">
                            <p class="a"><span class="name <?php if($islogin){echo ' viewperinfo';}else{echo ' person_search_login';}?>" style="cursor:pointer;"><?php echo  mb_substr($per_v['per_realname'],0,3) ; if($per_v['per_gender']==2){ echo '  女士';}else{echo '  先生';}?></span>
                            <span><?php if($per_v['this_per_area']){echo $per_v['this_per_area'];}else{echo '暂无数据';} ?></span>
                            <span><?php if(isset($per_v['per_education']) && $per_v['per_education']){echo $edu_arr[$per_v['per_education']];}?></span>
                            <a <?php if($islogin){echo 'class="viewperinfo"';}else{echo 'class="person_search_login"';}?> href="javascript:void(0)">查看详情 &gt;</a></p>
                            <p class="b"><span class="fs"><?php if($per_v['this_per_industry']){echo $per_v['this_per_industry'];}else{echo '暂无数据';} ?></span>
                            <span class="je"><?php $monarr= common::moneyArr(); echo $per_v['per_amount'] == 0 ? '无': $monarr[$per_v['per_amount']];?></span></p>
                            <p class="c"><?php echo mb_substr($per_v['per_remark'],0,40); ?></p>
                        </div>
                    </div>
                </li>
              <?php }?>
            </ul>
        </div>
        <div class="clear"></div>
        <div class="case_tp" style="border-top:1px solid #e5e5e5;border-bottom:none;margin-top:10px;">
        <?php if($totalcount>8){?>
            <a href="<?php if(isset($t_url)){echo urlbuilder::qiye("touzizhe").$t_url;}else{echo urlbuilder::qiye("touzizhe");}?>" class="change">换一批</a>
        <?php }?>
        </div>
    </div>
 <!--透明背景开始-->
<div id="opacity"></div>
<!--透明背景结束-->
</div>
<div style="z-index: 999; display: none;" id="send_box">
    <a class="close" href="#">关闭</a>
    <div class="btn" id="msgcontent">
    <p class="errorbox">只有企业用户才能查看详情，赶紧注册企业用户吧！
    </p></div>
</div>
   <!--弹出层-->
   <div id="error_box_renling" class="tan_floor" style="position:fixed;top:50%;left:50%;margin:-144px 0 0 -327px;display:none;z-index:9999">
     <a href="#" class="tan_floor_close"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_close.jpg')?>" /></a>
     <div class="clear"></div>
     <span style="display:block;padding-top:90px;">只有企业用户才能查看详情，赶紧注册企业用户吧！</span>
     <p></p>
   </div>
<script>
$(".case_geren_bg ul li").hover(function(){
    $(this).children(".case_geren_box").css("background-color","#fffdf8");
},function(){
    $(this).children(".case_geren_box").css("background-color","#fff");
});
</script>