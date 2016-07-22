<?php echo URL::webjs("del_shenhe.js")?>
<?php echo URL::webcss("my_bussines.css")?>
<script type="text/javascript">
$(document).ready(function() {
    var is_renling=$("#renling").html();
    if(is_renling=='renling'){
        // var opacity = $("#getcards_opacity");
        // var deleteBox = $("#getcards_delete");
        // opacity.show();
        // deleteBox.slideDown(500);
        $("body")[0].show({
          title:"认领项目",
          content:"<p>您的认领信息已经提交审核，我们的工作人员将会<br/>在两个工作日内联系您，请耐心等待！</p>",
          btn:"ok",
          onclose:function(){
            var is_renling=$("#renling").html();
            if(is_renling=='renling'){
              window.location.href = '/company/member/project/showprojectrenling';
            }
          }
        });
        return false;
    }
    var showfalsebox=$(".check_reason");
    var opacity = $("#getcards_opacity");
    showfalsebox.click(function(){
	   	 var parameter_value= $(this).attr('id');
	     var pos_start=parameter_value.split("_");
	     var projectid=pos_start[1];
         // $("#getcards_deletebox").slideDown(500);
			$.ajax({
				type: "post",
				dataType: "json",
				url: "/ajaxcheck/getCausesFailure/",
				data: "projectid="+projectid,
				complete :function(){
				},
				success: function(msg){
          if(msg['error']==1){
      		  // opacity.show();
      		  if(msg['content']){
		          // $("#getcards_deletebox_content").html(msg['content']);
              $("body")[0].show({
                title:"审核备注",
                content:"<div class='mb_content'>"+msg['content']+"</div>",
                btn:"ok"
              });
		        }
    			  // $("#getcards_deletebox").slideDown(500);
          }
				}
			});
        return false;
    });
    //关闭删除名片弹出层
    $("#getcards_deletebox .close").click(function(){
        $("#getcards_deletebox").slideUp(500,function(){
        	opacity.hide();
        	$("#getcards_deletebox_content").html('您提交的项目认领信息中，数据不完善，请更新后重新提交认领。');
        });
        return false;
    });
    //关闭删除名片弹出层
    $("#getcards_deletebox .ensure").click(function(){
        $("#getcards_deletebox").slideUp(500,function(){
        	opacity.hide();
        	$("#getcards_deletebox_content").html('您提交的项目认领信息中，数据不完善，请更新后重新提交认领。');
        });
        return false;
    });
});
</script>
<style>
#getcards_delete .btn,#getcards_delete11 .btn{padding-top:50px;}
#getcards_delete .btn p,#getcards_delete11 .btn p{ line-height:40px;}
</style>
<div class="right">
    <h2 class="user_right_title"><span>认领的项目</span><div class="clear"></div></h2>
    <div class="my_business">
     <form id="serchform" method="get" action="<?php echo URL::website('/company/member/project/searchProjectRenling')?>">
        <div class="detailTop">
          <input class="condition" name="condition" onfocus="if(this.value=='请输入您要搜索的公司名称或者项目名称')this.value=''" onblur="if(this.value=='')this.value='请输入您要搜索的公司名称或者项目名称'" value="<?php if(isset($inputvalue) && $inputvalue){echo $inputvalue;}else{echo '请输入您要搜索的公司名称或者项目名称';}?>" type="text">
          <input type="image" class="submit" width="67" height="26" src="<?php echo URL::webstatic("images/account/account_cz_03.png") ?>">
          <div class="clear"></div>
        </div>
      </form>
        <div class="clear"></div>
      <ul class="ryl_myproject_list">
        <?php foreach ($list as $value):?>
          <li>
            <p class="ryl_myproject_logo"><a href="<?php echo urlbuilder::project($value['project_id']); ?>" target="_blank"><img src="<?if($value['project_source'] != 1) {$img =  project::conversionProjectImg($value['project_source'], 'logo', $value);if(!project::checkProLogo($img)){$img = URL::webstatic("/images/common/company_default.jpg");}echo $img;} else {echo URL::imgurl($value['project_logo']);}?>" /></a></p>
            <div class="ryl_myproject_intro">
              <div class="ryl_myproject_intro_tit">
                 <b><a style="padding-left:0;color:#000;" href="<?php echo urlbuilder::project($value['project_id'])?>" target="_blank" ><?=$value['project_brand_name'];?></a></b>
                 <div  class="shenhe_kuang">

                 <?php if(false){//$value['isrenling_project']==0?>
                   <?php if($value['project_status']==1){?>
                     <a href="#" class="icon_shenhe_now" onmouseout="$(this).next('.ryl_shenhebg').hide();return false;" onmouseover="$(this).next('.ryl_shenhebg').show();return false;">项目正在审核中...</a>
                        <!--未通过审核 开始-->
                        <div class="ryl_shenhebg" id="ryl_shenhebg_now">
                           <div class="ryl_shenhebg_top"></div>
                           <div class="ryl_shenhebg_center">您的项目还在审核中，审核通过后投资者可以查看您的项目官网。
                           <div class="clear"></div>
                           </div>
                           <div class="ryl_shenhebg_bot"></div>
                        </div>
                        <!--未通过审核 结束-->

                    <?php }elseif($value['project_status']==2){?>
                        <a href="#" class="icon_shenhe_has">项目已通过审核</a>

                    <?php } elseif($value['project_status']==0){?>
                     <a href="#" class="icon_publish_fail">项目发布失败</a>
                     <a href="/company/member/project/updateproject?project_id=<?=$value['project_id']?>" class="icon_go_perfect">继续完善我的项目</a>
                     <a href="/company/member/project/submitProject?project_id=<?=$value['project_id']?>" class="icon_publish">发布我的项目</a>

                     <?php }elseif($value['project_status']==3){?>
                        <a href="#" class="icon_shenhe_no" onmouseout="$(this).next('.ryl_shenhebg').hide();return false;" onmouseover="$(this).next('.ryl_shenhebg').show();return false;">项目未通过审核</a>
                        <!--未通过审核 开始-->
                        <div class="ryl_shenhebg" id="ryl_shenhebg_no">
                           <p class="ryl_shenhebg_top"></p>
                           <p class="ryl_shenhebg_center">您的项目未通过审核，审核通过后投资者才可以查看您的项目官网。
                           <div class="clear"></div>
                           </p>
                           <p class="ryl_shenhebg_bot"></p>
                        </div>
                        <!--未通过审核 结束-->
                       <?}else{	}?>
                       <!--认领项目开始-->
                      <?}else{
                        if($value['isrenling_project_status']==0){//审核中
                            echo '<a href="javascript:void(0)" class="icon_shenhe_now">认领项目正在审核中...</a>';
                        }elseif($value['isrenling_project_status']==1){//审核通过
                            echo '<a href="javascript:void(0)" class="icon_shenhe_has">认领项目已通过审核</a>';
                        }elseif($value['isrenling_project_status']==2){//审核未通过
                            echo '<a href="javascript:void(0)" class="icon_shenhe_no">认领项目未通过审核</a><a id="notfalse_'.$value['project_id'].'" href="javascript:void(0)" class="check_reason" title="查看原因">查看原因</a>';
                        }else{	}

                      }?>
                 </div>
              </div>

              <span>所属行业：<?=$value['project_industry_id'];?><br/>招商电话：<?php echo $value['project_phone'];?><br/>项目简介：<?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars($value['project_summary'], 0)));echo mb_strimwidth(strip_tags($string), 0, 125, "......");?></span>
             </div>
            <p class="ryl_myproject_check">
                <a href="<?php echo urlbuilder::project($value['project_id']);?>" target="_blank" class="icon_yulan">查看项目官网</a>
                
                
			   <?php if($value['isrenling_project_status']==0){//审核中
					echo '<a href="/platform/project/renlingProjectInfo?project_id='.$value['project_id'].'" target="_blank"  class="icon_yulan">查看提交的认领信息</a>';
				}elseif($value['isrenling_project_status']==1){//审核通过
					echo '<a href="/platform/project/renlingProjectInfo?project_id='.$value['project_id'].'" target="_blank"  class="icon_yulan">查看提交的认领信息</a>';
					echo '<a style="text-decoration:none;" class="btn_renling btn_renling_ok">已认领</a>';
				}elseif($value['isrenling_project_status']==2){//审核未通过
					echo '<a target="_blank" href="'.URL::website('platform/project/updateProjectInfo').'?project_id='.$value['project_id'].'" class="btn_renling btn_renling_again">重新认领</a>';
				}elseif($value['isrenling_project_status']==3){//显示去认领
					echo ' <a target="_blank" href="'.URL::website('platform/project/claim').'?project_id='.$value['project_id'].'" class="btn_renling btn_renling_go">去认领此项目</a>';
				}elseif($value['isrenling_project_status']==4){//显示已被其他公司认领
					echo '<a style="text-decoration:none;" class="btn_renling btn_renling_ok">已被其他公司认领</a>';
				}
				else{	}
				?>

            </p>

        </li>

        <?php endforeach;?>
        <div class="clear"></div>
        <a id="renling" style="display:none"><?php if(isset($isrenling)){echo $isrenling;}else{echo '';}?></a>
      </ul>
    </div>

<?=$page;?>
</div>
<!--透明背景开始-->
<div id="getcards_opacity"></div>
<!--透明背景结束-->

<!--删除项目开始-->
<div id="getcards_delete">
    <a href="#" class="close">关闭</a>
    <div class="btn">
        <p>您的认领信息已经提交审核，我们的工作人员将会</p>
        <p>在两个工作日内联系您，请耐心等待！</p>
    </div>
</div>
<!--删除项目结束-->

<!--查看原因开始-->
<div id="getcards_deletebox" style="display: none;">
    <a class="close" href="#">关闭</a>
    <div style="background:none;padding-left:0px;text-align: left;color: #000;" class="text">
        <p id="getcards_deletebox_content" style="width:440px;padding-top:2px;">您提交的项目认领信息中，数据不完善，请更新后重新提交认领。</p>
        <p style="width: 270px; margin: 0px auto;" id="this_content2"><a class="ensure" href="javascript:void(0)"><img src="http://static.yijuhua-alpha.net/images/getcards/ensure1.jpg"></a>
        </p>
    </div>
</div>
<!--查看原因结束-->
