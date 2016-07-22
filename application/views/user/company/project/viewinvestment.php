 <!--主体部分开始-->
 <style>
 .my_business_infor .nav li{width:147px;}
 </style>
                <div class="right">
                    <h2 class="user_right_title"><span><a href="/company/member/project/showproject">项目管理</a></span><div class="clear"></div></h2>
                    <div class="ryl_add_project"><b><?=arr::get($project_info, 'project_brand_name')?></b>
                     <?php if($project_info['project_status'] !=0){?>
                    <a href="<?php echo url::site("/company/member/project/addproject");?>">添加新项目</a><?php }?></div>
                    
                    <p class="ryl_bussin_proname">
                       <span><?=arr::get($forms, 'investment_name')?></span>
                       <a href="<?php if(arr::get($forms, 'investment_start')>0){echo urlbuilder::projectInvest(arr::get($forms, 'project_id'));}else{echo urlbuilder::projectInvest(arr::get($forms, 'project_id')).'?investid='.arr::get($forms, 'investment_id');}?>" class="preview_officialweb" target="_blank">预览项目官网</a>
                       <a href="/company/member/project/addProInvestment?project_id=<?=arr::get($forms, 'project_id')?>" class="modify_infor">修改信息</a>
                    </p>
                    <ul class="ryl_mybussine_mess">
                    <li>
                        <b>投资考察会联系人：</b>
                        <span><?=arr::get($forms, 'com_name')?></span>
                        <div class="clear"></div>
                    </li>
                    <li><b>投资考察会电话：</b><span><?=arr::get($forms, 'com_phone')?></span><div class="clear"></div></li>
                    <li><b>投资考察会图片：</b><span><img src="<?php echo URL::imgurl(arr::get($forms, 'investment_logo'));?>"></span><div class="clear"></div></li>
                    <li><b>投资考察会时间/地址：</b><span class="ryl_mybussine_text">
                     <?php foreach ($invests as $v){?>
                    <?php echo date('Y-m-d',$v->investment_start)?>至<?php echo date('Y-m-d',$v->investment_end)?><br/><?=$v->investment_address?><br/>
                    <?php }?>
                    </span>
                    <div class="clear"></div></li>
                    <li><b>投资考察会详情：</b><span class="ryl_mybussine_text"><?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($forms, 'investment_details'), 0)));echo mb_strimwidth(strip_tags($string), 0, 225, "......");?></span><div class="clear"></div></li>
                    <li><b>投资考察会流程说明：</b><span><?$string = htmlspecialchars_decode(htmlspecialchars_decode(HTML::chars(arr::get($forms, 'investment_agenda'), 0)));echo mb_strimwidth(strip_tags($string), 0, 225, "......");?></span><div class="clear"></div></li>
                    <li><b>公司统一安排住宿：</b><span><?php if(arr::get($forms, 'putup_type') ==1){echo "是";}else{echo "否";}?></span><div class="clear"></div></li>
                    </ul>
                </div>
                <!--主体部分结束-->