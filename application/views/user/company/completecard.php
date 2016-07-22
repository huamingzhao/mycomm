    <?php echo URL::webjs("completecard.js")?>
    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>你好，欢迎进入名片中心！</span><div class="clear"></div></div>
        <div id="right_con">
          <?php echo Form::open(URL::website('/company/member/card/actcompletecard'), array('method' => 'post'))?>
            <div id="mycard3">
               <p><input type="checkbox" checked disabled/><b>公司名称：<?php echo $companyinfo->com_name;?></b></p>
                <p class="tishi">品牌logo和公司logo只能选择一个</p>
                <div id="userlogo">
                    <ul>
                        <li><img src="<?php echo URL::imgurl($companyinfo->com_logo); ?>" /><p><input type="radio" name="logo" <?php if($logo ==0): echo 'checked="checked"';endif;?> value="0" /><?php echo  mb_substr($companyinfo->com_name,0,5).' logo';?></p></li>
                         <?php foreach ($pro as $v){
                                if ($logo>0 and $logo==$v->project_id){ ?>
                                <li><img src="<?if($v->project_source != 1) {echo project::conversionProjectImg($v->project_source, 'logo', $v->as_array());} else {echo URL::imgurl($v->project_logo);}?>" /><p><input type="radio" name="logo" checked="checked" value="<?php echo $v->project_id?>"/><?php echo mb_substr($v->project_brand_name,0,5) ?>&nbsp;logo</p></li>
                               <?php }else{?>
                                <li><img src="<?if($v->project_source != 1) {echo project::conversionProjectImg($v->project_source, 'logo', $v->as_array());} else {echo URL::imgurl($v->project_logo);}?>" /><p><input type="radio" name="logo" value="<?php echo $v->project_id?>"/><?php echo mb_substr($v->project_brand_name,0,5) ?>&nbsp;logo</p></li>
                          <?php } }?>
                    </ul>
                </div>
                <div class="clear"></div>
                <div class="check">
                    <p><input type="checkbox" checked disabled/>联系人：<?php echo $companyinfo->com_contact ?></p>
                    <p><input type="checkbox" checked disabled/>联系电话：<?php echo $companyinfo->com_phone ?></p>
                    <p><input type="checkbox" checked disabled/>公司地址：<?php echo $companyinfo->com_adress ?></p>
                    <p><input type="checkbox" checked disabled/>公司网址：<?php echo $companyinfo->com_site ?></p>
                </div>
                <div class="infor_title">
                    <p><b>选择您想在名片上显示的项目内容，最多可选择<span>3个</span>，当您的项目简介有多行时，名片上只显示一行</b></p>
                </div>
                <div class="select">
                  <?php
                    foreach ($pro as $v){
                        $sumary_texts=htmlspecialchars_decode($v->project_summary);
                        if ($brand!="" && $brand){
                            $ids = $brand;
                            if (in_array($v->project_id, $ids)){ ?>
                               <p><input name='brand[]' type="checkbox" value="<?php echo $v->project_id;?>" checked/><span><?php echo $v->project_brand_name.':'.mb_substr(strip_tags($sumary_texts),0,46,'UTF-8').'...'  ?></span></p>
                            <?php } else { ?>
                                 <p><input name='brand[]' type="checkbox" value="<?php echo $v->project_id;?>" /><span><?php echo $v->project_brand_name.':'.mb_substr(strip_tags($sumary_texts),0,46,'UTF-8').'...' ?></span></p>
                            <?php }  } else{ ?>
                              <p><input name='brand[]' type="checkbox"  value="<?php echo $v->project_id;?>"/><span><?php echo $v->project_brand_name.':'.mb_substr(strip_tags($sumary_texts),0,46,'UTF-8').'...' ?></span></p>
                       <?php } } ?>
                </div>
                <div class="btn">
                    <input type="image" src="<?php echo URL::webstatic("images/mycard/btn_a.png") ?>" />&nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <?php echo Form::close();?>
        </div>
    </div>
<!--右侧结束-->
