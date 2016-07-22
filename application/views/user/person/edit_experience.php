<?php echo URL::webjs("header.js")?>
<?php echo URL::webjs("my_jobs_new.js")?>
<?php echo URL::webcss("per_infor_0502.css")?>
<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>
<style>
.my_cyjy .add .line input { margin-top:0; font-size:12px;}
.my_cyjy .add .line input.text01{ margin-top:3px;}
.my_cyjy .add .add_rt {width: 560px;}
.my_cyjy .add .rq .one span,.my_cyjy .add .rq .one input,.my_cyjy .add .rq .one select,.my_cyjy .add .rq .one a{ float:left; line-height:24px; height:24px; padding:0 3px;}
.my_cyjy h3 p{ float:left;}
.my_cyjy h3 a{ float:left; padding-left:10px;}
</style>
<!--主体部分开始-->
<div class="right" style="height:auto !important;height:620px;min-height:620px;">
                    <h2 class="user_right_title"><span>我的从业经验</span><div class="clear"></div></h2>
    <div class="per_infor_0502">
    <ul class="per_infor_title">
      <li><a href="<?php echo URL::website("/person/member/basic/person")?>">我的基本信息</a></li>
      <li><a href="<?php echo URL::website("/person/member/basic/personInvestShow")?>">意向投资信息</a></li>
      <li class="last"><a href="<?php echo URL::website("/person/member/basic/experience")?>" class="current"><img src="<?php echo URL::webstatic("images/per_infor/icon01.jpg")?>" style="padding:21px 10px 0 75px;">从业经验</a></li>
      </ul>
     </div>
    <div class="my_cyjy">
        <?php  if($experiences){?>
        <table>
<!--			<tr>
                <th>从业时间</th>
                <th>从事职业</th>
                <th>从业地点</th>
                <th>规模</th>
                <th colspan="2"><a href="#" class="view">我的从业经验</a></th>
            </tr>-->
        <tr>
            <th>从业时间</th>
            <th>公司名称</th>
            <th>公司性质</th>
            <th>职位名称</th>
            <th colspan="2"><a href="/person/member/basic/experiencesave" class="view">我的从业经验</a></th>
        </tr>
            <?php foreach($experiences as $experience){?>
            <tr>
            <td><?php echo substr($experience['exp_starttime'],0,4)."年".substr($experience['exp_starttime'],4)."月"?>到<?php if( $experience['exp_endtime']=='0' ){ echo '今天'; }else{ echo substr($experience['exp_endtime'],0,4)."年".substr($experience['exp_endtime'],4)."月";}?></td>

            <td><?php echo $experience['exp_company_name'];?></td>

            <td><?php foreach ( common::comnature_new() as $k=>$vs ){ if( $k==$experience['exp_nature'] ){ echo $vs; } }?></td>

            <td><?php echo $experience['occ_name']?></td>

            <td><a class="edit" href="<?php echo URL::website("person/member/basic/editexperience"); ?>?exp_id=<?php echo $experience['exp_id'];?>" class="bji">编辑</a></td>

            <td><a class="delete" href="javascript:void(0);" del="<?php echo $experience['exp_id'];?>">删除</a></td>
        </tr>
            <?php }?>
        </table>
        <?php }?>

        <?php /**?><h3><p>修改从业经验<span>( <em>*</em>为必填)</span></p><a href="/person/member/basic/experience"><img src="<?php echo URL::webstatic("/images/my_cyjy/add_jy.png") ?>"  width="107" height="21" /></a></h3>**/?>
        <form id="edit_form" action="" method="post" >

        <div class="add">
            <div class="line">
                <div class="add_lf"><em>*</em>工作时间：</div>
                <div class="add_rt rq">
                    <p class="one">
                        <span>从</span>
                        <input id="exp_starttime_id_1" value="<?php echo $exp_start_year."-".$exp_start_month?>" name="exp_starttime" type="text" class="text01" size="10" onclick="WdatePicker({dateFmt:'yyyy-MM'})" readonly="readonly" onchange="yz_tishi1(1)"/>
                        <span>到</span>
                        <input id="exp_endtime_id_1" value="<?php if( $exp_end_month!="" ){echo $exp_end_year."-".$exp_end_month;}?>" name="exp_endtime" type="text" class="text01" size="10" onclick="WdatePicker({dateFmt:'yyyy-MM'})" readonly="readonly" onblur="yz_tishi11(1)"/>
                        <span>(后一项不选表示至今)</span>
                        <a href="javascript:void(0)" onclick="clearInputVal( 1 )" style="padding-top:3px;"><img src="<?php echo URL::webstatic("/images/my_cyjy/del.png") ?>"  width="67" height="21" /></a>

                    </p>
                    <p class="tip aa" id="tishi1_error_id_1"><!--请选择您从业的时间段--></p>
                </div>
                <div class="clear"></div>
            </div>


            <div class="line">
                <div class="add_lf"><em>*</em>工作地点：</div>
                <div class="add_rt zy">
                    <p class="one">
                        <!-- 省份 -->
                        <?php echo Form::select("pro_id",$area,$edit->pro_id,array("class"=>"ddd","id"=>"address_1","onclick"=>"yz_tishi2(1)","onchange"=>"getarea(1)"))?>
                        <!-- 市 -->
                        <?php echo Form::select("area_id",$area_sub,$edit->area_id,array("class"=>"eee","id"=>"address1_1"))?>
                    </p>
                    <p class="tip cc" id="tishi2_error_id_1"><!--请选择您从业的地点--></p>
                </div>
                <div class="clear"></div>
            </div>

            <div class="line">
            <div class="add_lf"><em>*</em>企业名称：</div>
            <div class="add_rt zy">
                <p class="one">
                   <input id="exp_company_name_id_1" name="exp_company_name" type="text" class="text01" value="<?php echo $edit->exp_company_name?>" onblur="yz_tishi3(1)"/>
                </p>
                <p class="tip cc" id="tishi3_error_id_1"><!--请选择您工作的地点--></p>
            </div>
            <div class="clear"></div>
            </div>

        <div class="line">
            <div class="add_lf"><em>*</em>企业性质：</div>
            <div class="add_rt zy">
                <p class="one">
                    <SELECT onclick="yz_tishi4(1)" calss="fff" id="exp_nature_id_1"  name="exp_nature">
                    <option value="">请选择</option>
                    <?php foreach( common::comnature_new() as $k=>$vs ){?>
                        <OPTION value="<?php echo $k?>" <?php if( $edit->exp_nature==$k ){?>selected="selected" <?php }?> ><?php echo $vs?></OPTION>
                    <?php }?>
                    </SELECT>
                </p>
                <p class="tip dd" id="tishi4_error_id_1"><!--请选择您的工作规模--></p>
            </div>
            <div class="clear"></div>
        </div>



        <div class="line">
            <div class="add_lf">企业规模：</div>
            <div class="add_rt zy">
                <p class="one">
                    <?php echo Form::select("exp_scale",$scale,$edit->exp_scale,array("class"=>"fff","id"=>"exp_scale_id_1"));?>
                </p>
                <p class="tip dd"><!--请选择您的工作规模--></p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="line">
            <div class="add_lf">行业类别：</div>
            <div class="add_rt zy">
                <p class="one">
                    <input type="button" value="<?php if( $rs_exp_industry_sort['profession_name']=='' ){ echo '请选择'; }else{echo $rs_exp_industry_sort['profession_name'];}?>" id="profession_val_id_1" class="" onclick="show_profession(1)">
                    <INPUT type="hidden" value="<?php echo $edit->exp_industry_sort?>" name="exp_industry_sort" id="profession_id_val_id_1">
                </p>
                <p class="tip dd" id="tishi5_error_id_1"><!--请选择您的工作规模--></p>
                <!--行业类别弹出框-->
                <div class="cyjy_hy_fc" style="display:none;" id="profession_show_div_1">
                  <a href="#" class="close"></a>
                  <div class="clear"></div>
                  <div class="cyjy_hy_fc_cont cyjy_hy_fc_cont_hy">
                  <p>行业类别</p><div class="clear"></div>
                  <ul>
                <?php foreach( $professoin as $k=>$vp ){?>
                  <li><a href="javascript:void(0)" onclick="setProfessionVal( '<?php echo $vp->profession_name?>','<?php echo $vp->profession_id?>','1' )"><?php echo $vp->profession_name?></a></li>
                <?php }?>
                  </ul>
                  <div class="clear"></div>
                  </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>

        <div class="line">
            <div class="add_lf">所在部门：</div>
            <div class="add_rt zy">
                <p class="one">
                    <input name="exp_department" type="text" value="<?php echo $edit->exp_department?>"  class="text01" onblur="yz_tishi6(1)" id="exp_department_id_1"/>
                </p>
                <p class="tip dd" id="tishi6_error_id_1"><!--请选择您的工作规模--></p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="line">
            <div class="add_lf">职位类别：</div>
            <div class="add_rt zy">
                <p class="one">
                    <INPUT type="button" value="<?php if( $rs_exp_occupation_type['position_name']=='' ){ echo '请选择'; }else{echo $rs_exp_occupation_type['position_name'];}?>" id="position_val_id_1" onclick="show_position(1)">
                    <INPUT type="hidden" value="<?php if( $rs_exp_occupation_type['position_name']=='' ){ echo '0'; }else { echo $edit->exp_occupation_type;}?>" name="exp_occupation_type" id="position_id_val_id_1">
                </p>
                <p class="tip bb" id="tishi7_error_id_1"><!--请选择您所从事的职业--></p>

                <!--职位类别弹出框-->
                <div class="cyjy_hy_fc" style="display:none;" id="position_show_div_1">
                  <a href="#" class="close"></a>
                  <div class="clear"></div>
                  <div class="cyjy_hy_fc_cont">
                  <p>职位类别</p><div class="clear"></div>
                  <ul>
                <?php foreach ( $position as $v ){?>
                  <li><a href="javascript:void(0)" onclick="setPositionVal( '<?php echo $v->position_name?>','<?php echo $v->position_id?>','1' )"><?php echo $v->position_name?></a></li>
                <?php }?>
                  </ul>
                  <div class="clear"></div>
                  </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="line">
            <div class="add_lf">职位名称：</div>
            <div class="add_rt zy">
                <p class="one">
                    <INPUT type="button" name="" id="position_down_val_id_1" value="<?php if( $edit->exp_occupation_name!="0" || $edit->exp_occupation_name!=""  ){ if( $occ_name=='' ){ echo '请选择'; }else{echo $occ_name;}}else{ echo "请选择"; }?>" onclick="show_down_pos(1)" <?php if( $edit->exp_occupation_name!="0" || $edit->exp_occupation_name!=""  ){ if( $occ_name=='' ){ echo 'disabled="disabled"'; }}else{ echo 'disabled="disabled"'; }?> >
                    <INPUT type="hidden" value="<?php echo $edit->exp_occupation_name?>" name="exp_occupation_name" id="position_down_id_val_id_1">

                    <input name="exp_user_occupation_name" type="text"  class="text01" value="<?php if( $edit->exp_occupation_name=="0" ){echo $occ_name;}?>" style="width:180px; padding-left:15px; color:#797979;" onblur="yz_tishi9(1)" id="exp_user_occupation_name_id_1"  />

                </p>
                <p class="tip bb" id="tishi8_error_id_1"><!--请选择您所从事的职业--></p>
<!--职位名称弹出框-->
                <div class="cyjy_hy_fc" style="display:none;" id="show_down_pos_div_id_1">
                  <a href="#" class="close"></a>
                  <div class="clear"></div>
                  <div class="cyjy_hy_fc_cont cyjy_hy_fc_cont_name">
                  <p>职位名称</p><div class="clear"></div>
                  <ul id="show_li_down_pos_id_1">
                <?php foreach( $rs_down_occ as $v ){?>
                  <li><a href="javascript:void(0)" onclick="set_down_pos( '<?php echo $v->position_name?>','<?php echo $v->position_id?>','1' )"><?php echo $v->position_name?></a></li>
                <?php }?>
                  </ul>
                  <div class="clear"></div>
                  </div>
                </div>


            </div>
            <div class="clear"></div>
        </div>

            <div class="line">
                <div class="add_lf">工作描述：</div>
                <div class="add_rt zy">
                    <p class="one">
                        <textarea onblur="yz_tishi10(1)" id="exp_description_id_1"  name="exp_description" class="ggg"><?=$edit->exp_description?></textarea>
                    </p>
                    <p class="tip ee" id="tishi10_error_id_1"><!--您输入的内容不得超过200个字符--></p>
                </div>
                <div class="clear"></div>
            </div>
            <div class="line">
                <div class="add_lf"></div>
                <div class="add_rt zy">
                    <p class="one">
                        <a href="javascript:void(0)" onclick="goto_edit()"  style="margin-right:10px;"><img  src="<?php echo URL::webstatic("images/my_cyjy/save.jpg")?>" class="save"/></a><a href="/person/member/basic/experience"><IMG src="<?php echo URL::webstatic("images/my_cyjy/cancle.jpg")?>"></a>
                    </p>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        </form>
    </div>
</div>
<!--主体部分结束-->

<!--弹出框开始-->
<div id="opacity_box"></div>
<div id="mess_delete_box">
    <a href="#" class="close">关闭</a>
    <p>一旦删除，将无法取回。您确定要删除此经验吗？</p>
    <p><a href="#" class="ensure"><img src="<?php echo URL::webstatic("images/my_messages/ensure.png")?>" /></a><a href="#" class="cancel"><img src="<?php echo URL::webstatic("images/my_messages/cancel.png")?>" /></a></p>
</div>
<!--弹出框结束-->
