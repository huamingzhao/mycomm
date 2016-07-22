<?php echo URL::webcss("platform/renling.css")?>
<!--公共背景框-->
<div class="main" style="background-color:#fff; height:auto;">
   <div class="ryl_main_bg">
       <div class="ryl_main_bg01"></div>
       <div class="ryl_main_bg02">
          <!--认领-->
          <div class="renling_main">
             <h3><span><a href="<?php echo URL::website('company/member/project/showproject')?>">我的项目</a> > 提交的认领信息</span>
             <?php if($renlinglist['project_status']!=1){?>
             	<a href="<?php echo URL::website('platform/project/updateProjectInfo').'?project_id='.$project_id;?>" class="modify_renling">修改信息</a>
             <?php }?>
             </h3>
             <!--查看认领信息-->
             <div class="renling_view">
             <ul>
             <li><label>项目名称：</label><span><?php echo $renlinglist['project_brand_name'];?></span></li>
             <li><label>公司名称：</label><span><?php echo $renlinglist['company_name'];?></span></li>
             <li><label>主营产品：</label><span><?php echo $renlinglist['project_principal_products'];?></span></li>
             <li><label>联系方式：</label><span><?php  echo $renlinglist['project_phone']; ?></span></li>
             <li><label>联系人：</label><span><?php  echo $renlinglist['project_contact_people']; ?></span></li>
             <li><label>项目介绍：</label><span class="renling_item_intro">
             <?php
	             if($renlinglist['project_summary']){
	             	$renglingstring = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars($renlinglist['project_summary'], 0)));
					echo mb_strimwidth(strip_tags($renglingstring), 0, 350, "......");
	             }else{
	             	echo "暂无相关信息";
	             }
             ?></span>
             <div class="clear"></div>
             </li>
            <?php if($list){?> 
             <li><label>项目图片：</label>
             <span class="renling_item_pic">
                            <?php foreach ($list as $value){ ?>
                            <a href="javascript:vode(0)"><img src="<?php echo URL::imgurl($value->project_img);?>" width="158" height="124" class="imgStyle"/></a>
                            <?php }?>
             </span>
              <?=$page;?>
             </li>
             <?php }?>
             <div class="clear"></div>
             </ul>
             <div class="clear"></div>
             </div>
          </div>
          <div class="clear"></div>
       </div>
       <div class="ryl_main_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>
<div class="clear"></div>