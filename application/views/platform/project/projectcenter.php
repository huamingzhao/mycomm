<?php echo URL::webjs("platform/template/yellow.js");?>
<!--中部开始-->
<div id="yellow">
    <div id="center-e">
        <div class="left">
            <div class="door"><img src="<?php echo URL::webstatic("/images/platform/yellow/door.png") ?>" /></div>
            <div class="enter"><a href="<?php echo urlbuilder::project($project_id);?>"> <img src="<?php echo URL::webstatic("/images/platform/yellow/enter.png") ?>" /></a></div>
        </div>
        <div class="right">
            <div class="name1">
                <div class="pro"><?php echo mb_substr($project_name,0,10,'UTF-8');?></div>
                <div class="oneword">一句话@通路快建</div>
            </div>
            <div class="name2">
                <div class="top">
                    <div class="other_letter">
                        <p class="a"><?php echo mb_substr($project_name,0,10,'UTF-8');?></p>
                        <p class="c"><?php echo $company_list['com_name'];?><span><?php echo $company_list['com_contact'];?></span></p>
                    </div>
                </div>
                <div class="bottom">
                    <!--<p>联系电话：<?php if($branch_phone!=''){echo $com_phone.'分机'.$branch_phone;}else{echo $com_phone;}?></p>-->
                    <p>公司地址：<?php if($company_list['com_adress']){echo $company_list['com_adress'];}else{echo "暂无相关信息";}?></p>
                    <!--<p>公司网址：<?php echo $company_list['com_site'];?></p>-->
                    <p>项目简介：<?php $summary=htmlspecialchars_decode($project_summary); echo mb_substr(strip_tags($summary),0,85,'UTF-8').'...'?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--中部结束-->