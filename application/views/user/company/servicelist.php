<?php echo URL::webcss("my_serve0506.css")?>
<?php $getBuyType = common::getBuyType();?>
                <!--主体部分开始-->
                <div class="right">
    <h2 class="user_right_title"><span>已有服务</span><div class="clear"></div></h2>

                      <!--我的服务-->
                      <div class="my_serve0506">
                          <ul>
                          <li class="my_serve_view_title">
                               <span class="my_serve_view_time">购买/申请时间</span>
                               <span class="my_serve_view_name">服务名称</span>
                               <span class="my_serve_view_form">服务形式</span>
                               <span class="my_serve_view_status">服务状态</span>
                               <span class="my_serve_view_operate">操作</span>
                               <div class="clear"></div>
                          </li>
                          <?php if (count($list)==0&&$applyinfo==""){?>
                          <div class="no_my_serve">
                             <span>您还未申请服务</span>
                             <span><a href="<?php echo URL::site("/company/member/merchants/applyBusiness")?>">申请招商通服务</a></span>
                          </div>
                          <?php }?>
                          <?php if ($applyinfo!=""){?>
                          <li class="my_serve_view_first">
                               <span class="my_serve_view_time"><?php echo date('Y.m.d H:i',$applyinfo->business_addtime);?></span>
                               <span class="my_serve_view_name">申请招商通服务</span>
                               <span class="my_serve_view_form">已申请</span>
                               <span class="my_serve_view_status">已申请</span>
                               <span class="my_serve_view_operate"><a href="<?php echo URL::site("/company/member/merchants/applyBusiness")?>">查看</a></span>
                               <div class="clear"></div>
                          </li>
                          <?php }?>
                          <?php foreach ($list as $k=>$v){?>
                          <li class="my_serve_view_first">
                               <span class="my_serve_view_time"><?php echo date('Y.m.d H:i',$v->buy_time);?></span>
                               <span class="my_serve_view_name"><?php echo $getBuyType[$v->buy_type]['remarks'];?></span>
                               <span class="my_serve_view_form"><?php echo $getBuyType[$v->buy_type][$v->buy_type_config]['describe']."/￥".$getBuyType[$v->buy_type][$v->buy_type_config]['price']?></span>
                               <?php if ($v->buy_status==0){?>
                                   <?php if ($v->buy_confine_number==0){?>
                                   <span class="my_serve_view_status my_serve_color01">已用完</span>
                                   <span class="my_serve_view_operate">
                                       <a href="<?php echo URL::site('company/member/account/cardservice')?>" class="my_serve_view_buy">再次购买</a>
                                       <a href="<?php echo URL::site('company/member/account/servicelist?status=0&buy_id='.$v->buy_id)?>" class="my_serve_view_del">删除</a>
                                   </span>
                                   <?php }else{?>
                                   <span class="my_serve_view_status my_serve_color01">已过期</span>
                                   <span class="my_serve_view_operate">
                                       <a href="<?php echo URL::site('company/member/account/cardservice')?>" class="my_serve_view_buy">再次购买</a>
                                       <a href="<?php echo URL::site('company/member/account/servicelist?status=0&buy_id='.$v->buy_id)?>" class="my_serve_view_del">删除</a>
                                   </span>
                                   <?php }?>
                               <?php }elseif ($v->buy_status==1){?>
                                   <?php if ($v->buy_use_number>0){?>
                                   <span class="my_serve_color02 my_serve_view_status">
                                       <a href="#" class="test" id="test_<?php echo $v->buy_id;?>">使用中</a>
                                       <div class="my_serve_fc" id="my_serve_fc_test_<?php echo $v->buy_id;?>">
                                       </div>
                                   </span>
                                   <?php }else{?>
                                   <span class="my_serve_view_status my_serve_color01">已购买</span>
                                   <span class="my_serve_view_operate">
                                     <a href="<?php echo URL::site('company/member/account/cardservice')?>" class="my_serve_view_buy">再次购买</a>
                                   </span>
                                   <?php }?>
                               <?php }?>

                               <div class="clear"></div>
                          </li>
                          <?php }?>
                          <div class="clear"></div>
                          </ul>
                          <div class="clear"></div>
                      </div>
                      <div class="clear"></div>

                      <div style="padding-top:30px; text-align:center;"><?=$page;?></div>
                </div>
                <!--主体部分结束-->
                <div class="clear"></div>
            </div>
<script type="text/javascript">
$(".test").hover(function(){
        var _this = $(this);
        var buy_id = $(this).attr('id');
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/company/member/ajaxcheck/getCardServiceLog",
            data: "buy_id="+buy_id,
            complete :function(){
            },
            success: function(data){
                var content ='';
                for(var o in data){
                   content+= '<b>'+data[o].log_time+'</b><em>'+data[o].log_action_id+'</em><label>还有'+data[o].log_nums+'张</label><div class="clear"></div>'
                }
                var newhtml = '<div class="my_serve_fc_top"></div><div class="my_serve_fc_center" style="padding-top:10px;"><div class="clear"></div>'+content+'</div><div class="my_serve_fc_bot"></div>';
                $('#my_serve_fc_'+$("#"+buy_id).attr('id')).html(newhtml);
                //_this.parent().parent("li").siblings("li").find(".my_serve_fc").hide();
                //$('#my_serve_fc_'+$("#"+buy_id).attr('id')).show();
                _this.siblings(".my_serve_fc").show();
                _this.parent().parent("li").siblings("li").find(".my_serve_fc").hide();
            }
        });

},function(){
    $(this).siblings(".my_serve_fc").hide();
});
$(".my_serve_fc_center .cls").live('click',function(){
    $(this).parent().parent().hide();
    return false;
});
</script>