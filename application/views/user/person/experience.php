<?php echo URL::webjs("header.js")?>
<?php echo URL::webjs("my_jobs_new.js")?>
<?php echo URL::webcss("per_infor_0502.css")?>
<?php echo URL::webcss("platform/competit_entrance.css")?>
<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>
<style>
.my_cyjy .add .line input { margin-top:0; font-size:12px;}
.my_cyjy .add .line input.text01{ margin-top:3px;}
.my_cyjy .add .add_rt {width: 560px;}
.my_cyjy .add .rq .one span,.my_cyjy .add .rq .one input,.my_cyjy .add .rq .one select,.my_cyjy .add .rq .one a{ float:left; line-height:24px; height:24px; padding:0 3px;}
.my_cyjy .add .rq .one a{ padding-top:3px;}
.ryl_will_meet {
    height: auto !important;
    height:100px;
    min-height: 100px;
    position:absolute; left:170px; top:0; z-index:888;float:left;
}
.ryl_will_meet_calendar_cont {
    height: auto !important;
    height:100px;
    min-height:130px;
}
.ryl_will_meet_calendar {
    height: auto !important;
    height:100px;
    min-height: 100px;
    background-color:#fff;
}
.ryl_will_meet_calendar_cont p strong,.ryl_will_meet_calendar_cont li { font-weight:normal; font-size:12px;}
.my_cyjy h3 .ryl_will_meet_year span {font: 14px/33px "微软雅黑";}
.my_cyjy h3{ position:relative;}
.tishi{color:red;}
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
<!--		<tr>
            <th>时间</th>
            <th>从事职业</th>
            <th>工作地点</th>
            <th>规模</th>
            <th colspan="2"><a href="#" class="view">我的从业经验</a></th>
        </tr>-->
        <tr>
            <th>从业时间</th>
            <th>公司名称</th>
            <th>公司性质</th>
            <th>职位名称</th>
            <!-- <th colspan="2"><a href="/person/member/basic/experiencesave" class="view">我的从业经验</a></th> -->
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

    <h3><p style="float:left;">添加从业经验<span>( <em>*</em>为必填)</span></p>


    <div class="clear"></div>
    </h3>
    <form id="add_form" action="/person/member/basic/experience" method="post" >
    <input type="hidden" id="now_add_div_num_id" name="now_add_div_num_name" value="1">
    <div class="add">
    <!-- add beging -->
    <?php
       $num= 11;
       for ( $an=1;$an<=$num;$an++ ){
        if( $an>1 ){
            $style= 'style="border-top:1px dashed #ccc; padding-top:20px; "';
            $show_style= 'style="display:none" ';
            $hidd= 0;
        }else{
            $style= '';
            $show_style= '';
            $hidd= 1;
        }
    ?>
    <div id="show_div_id_<?php echo $an?>" <?php echo $show_style?>><INPUT type="hidden" name="add_div_name[]" value="<?php echo $hidd?>" id="show_div_id_hidden_<?php echo $an?>" >
        <div class="line" id="show_div_id_first_line_id_<?php echo $an?>" <?php echo $style?>>
            <div class="add_lf"><em>*</em>工作时间：</div>
            <div class="add_rt rq">
                <p class="one">
                    <span>从</span>
                    <input id="exp_starttime_id_<?php echo $an?>" name="exp_starttime_<?php echo $an?>" type="text" class="text01" size="10" onclick="WdatePicker({dateFmt:'yyyy-MM'})" readonly="readonly" value=" "  onchange="yz_tishi1(<?php echo $an?>)"/>
                    <span>到</span>
                    <input id="exp_endtime_id_<?php echo $an?>" name="exp_endtime_<?php echo $an?>" type="text" class="text01" size="10" onclick="WdatePicker({dateFmt:'yyyy-MM'})" readonly="readonly" onblur="yz_tishi11(<?php echo $an?>)"/>
                    <span>(后一项不选表示至今)</span>

                        <a href="javascript:void(0)" onclick="clearInputVal( <?php echo $an?> )" style="padding-top:3px;"><img src="<?php echo URL::webstatic("/images/my_cyjy/del.png") ?>"  width="67" height="21" /></a>
                        <?php //if ( $an>1 ){?>
                        <span id="del_act_id_<?php echo $an?>" style="display: none;">
                        <a href="javascript:void(0)" onclick="del_div(<?php echo $an?>)" style="padding-top:5px;"><img src="<?php echo URL::webstatic("/images/my_cyjy/del_home.jpg") ?>"  width="19" height="17" /></a>
                        <a href="javascript:void(0)" onclick="del_div(<?php echo $an?>)" style="padding-left:5px; color:#0365c0; line-height:24px;">删除</a>
                        </span>
                        <?php //}?>

                        </p>
                <p class="tip aa" id="tishi1_error_id_<?php echo $an?>"><!--请选择您工作的时间段--></p>
            </div>
            <div class="clear"></div>
        </div>

        <div class="line">
            <div class="add_lf"><em>*</em>工作地点：</div>
            <div class="add_rt zy">
                <p class="one">
                    <!-- 省份 -->
                    <SELECT onclick="yz_tishi2(<?php echo $an?>)" class="ddd" name="pro_id_<?php echo $an?>" id="address_<?php echo $an?>" onchange="getarea(<?php echo $an?>)">
                        <?php foreach ( $area as $k=>$va ){?>
                            <option value="<?php echo $k?>"><?php echo $va?></option>
                        <?php }?>
                    </SELECT>
                    <?php //echo Form::select( "pro_id_".$an,$area,'',array( "class"=>"ddd","id"=>"address" ) )?>
                    <!-- 市 -->
                    <select id="address1_<?php echo $an?>" class="short" name="area_id_<?php echo $an?>" class="eee" >
                         <option value="0" >请选择</option>
                    </select>
                </p>
                <p class="tip cc" id="tishi2_error_id_<?php echo $an?>"><!--请选择您工作的地点--></p>
            </div>
            <div class="clear"></div>
        </div>

        <div class="line">
            <div class="add_lf"><em>*</em>企业名称：</div>
            <div class="add_rt zy">
                <p class="one">
                   <input id="exp_company_name_id_<?php echo $an?>" name="exp_company_name_<?php echo $an?>" type="text" class="text01" onblur="yz_tishi3(<?php echo $an?>)"/>
                </p>
                <p class="tip cc" id="tishi3_error_id_<?php echo $an?>"><!--请选择您工作的地点--></p>
            </div>
            <div class="clear"></div>
        </div>

        <div class="line">
            <div class="add_lf"><em>*</em>企业性质：</div>
            <div class="add_rt zy">
                <p class="one">
                    <SELECT onclick="yz_tishi4(<?php echo $an?>)" calss="fff" id="exp_nature_id_<?php echo $an?>" name="exp_nature_<?php echo $an?>">
                    <option value="">请选择</option>
                    <?php foreach( common::comnature_new() as $k=>$vs ){?>
                        <OPTION value="<?php echo $k?>"><?php echo $vs?></OPTION>
                    <?php }?>
                    </SELECT>

                </p>
                <p class="tip dd" id="tishi4_error_id_<?php echo $an?>"><!--请选择您的工作规模--></p>
            </div>
            <div class="clear"></div>
        </div>

        <div class="line">
            <div class="add_lf">企业规模：</div>
            <div class="add_rt zy">
                <p class="one">
                    <?php echo Form::select("exp_scale_".$an,$scale,"",array("class"=>"fff","id"=>"exp_scale_id_".$an));?>
                </p>
                <p class="tip dd"><!--请选择您的工作规模--></p>
            </div>
            <div class="clear"></div>
        </div>

        <div class="line">
            <div class="add_lf">行业类别：</div>
            <div class="add_rt zy">
                <p class="one">
                    <input type="button"  value="请选择" name="" id="profession_val_id_<?php echo $an?>" class="" onclick="show_profession(<?php echo $an?>)">
                    <INPUT type="hidden" value="0" name="exp_industry_sort_<?php echo $an?>" id="profession_id_val_id_<?php echo $an?>">

                </p>
                <p class="tip dd" id="tishi5_error_id_<?php echo $an?>"><!--请选择您的工作规模--></p>

                <!--行业类别弹出框-->
                <div class="cyjy_hy_fc" style="display:none;" id="profession_show_div_<?php echo $an?>">
                  <a href="#" class="close"></a>
                  <div class="clear"></div>
                  <div class="cyjy_hy_fc_cont cyjy_hy_fc_cont_hy">
                  <p>行业类别</p><div class="clear"></div>
                  <ul>
                <?php foreach( $professoin as $k=>$vp ){?>
                  <li><a href="javascript:void(0)" onclick="setProfessionVal( '<?php echo $vp->profession_name?>','<?php echo $vp->profession_id?>','<?php echo $an?>' )"><?php echo $vp->profession_name?></a></li>
                <?php }?>
                  </ul>
                  <div class="clear"></div>
                  </div><div class="clear"></div>
                </div>


            </div>
            <div class="clear"></div>
        </div>
        <div class="line">
            <div class="add_lf">所在部门：</div>
            <div class="add_rt zy">
                <p class="one">
                    <input onblur="yz_tishi6(<?php echo $an?>)" id="exp_department_id_<?php echo $an?>" name="exp_department_<?php echo $an?>" type="text"  class="text01"/>
                </p>
                <p class="tip dd" id="tishi6_error_id_<?php echo $an?>"><!--请选择您的工作规模--></p>
            </div>
            <div class="clear"></div>
        </div>

        <div class="line">
            <div class="add_lf">职位类别：</div>
            <div class="add_rt zy">
                <p class="one">
                    <INPUT type="button" value="请选择" name="" id="position_val_id_<?php echo $an?>" onclick="show_position(<?php echo $an?>)">
                    <INPUT type="hidden" value="0" name="exp_occupation_type_<?php echo $an?>" id="position_id_val_id_<?php echo $an?>">
                </p>
                <p class="tip bb" id="tishi7_error_id_<?php echo $an?>"><!--请选择您所从事的职业--></p>
                <!--职位类别弹出框-->
                <div class="cyjy_hy_fc cyjy_hy_fc_zwlb" style="display:none;" id="position_show_div_<?php echo $an?>">
                  <a href="#" class="close"></a>
                  <div class="clear"></div>
                  <div class="cyjy_hy_fc_cont">
                  <p>职位类别</p><div class="clear"></div>
                  <ul>
                <?php foreach ( $position as $v ){?>
                  <li><a href="javascript:void(0)" onclick="setPositionVal( '<?php echo $v->position_name?>','<?php echo $v->position_id?>','<?php echo $an?>' )"><?php echo $v->position_name?></a></li>
                <?php }?>
                    <div class="clear"></div>
                  </ul>
                  <div class="clear"></div>
                  </div><div class="clear"></div>
                </div>

            </div>
            <div class="clear"></div>
        </div>

        <div class="line">
            <div class="add_lf">职位名称：</div>
            <div class="add_rt zy">
                <p class="one">
                    <INPUT type="button" name="" id="position_down_val_id_<?php echo $an?>" value="请选择" onclick="show_down_pos(<?php echo $an?>)" disabled="disabled">
                    <INPUT type="hidden" value="0" name="exp_occupation_name_<?php echo $an?>" id="position_down_id_val_id_<?php echo $an?>">
                    <input onblur="yz_tishi9(<?php echo $an?>)" id="exp_user_occupation_name_id_<?php echo $an?>" name="exp_user_occupation_name_<?php echo $an?>" type="text"  class="text01" value="若无适合选项请在此填写" style="width:180px; padding-left:15px; color:#797979;" onclick="delval('若无适合选项请在此填写',this.id)" />

                </p>
                <p class="tip bb" id="tishi8_error_id_<?php echo $an?>"><!--请选择您所从事的职业--></p>

                <!--职位名称弹出框-->
                <div class="cyjy_hy_fc" style="display:none;" id="show_down_pos_div_id_<?php echo $an?>">
                  <a href="#" class="close"></a>
                  <div class="clear"></div>
                  <div class="cyjy_hy_fc_cont cyjy_hy_fc_cont_name">
                  <p>职位名称</p><div class="clear"></div>
                  <ul id="show_li_down_pos_id_<?php echo $an?>">

                  </ul>
                  <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                </div>

            </div>
            <div class="clear"></div>
        </div>
        <div class="line">
            <div class="add_lf">工作描述：</div>
            <div class="add_rt zy">
                <p class="one">
                    <textarea onclick="delval('请详细描述您的职责范围、工作任务以及取得的成绩等。',this.id)" onblur="yz_tishi10(<?php echo $an?>)" id="exp_description_id_<?php echo $an?>" name="exp_description_<?php echo $an?>" class="ggg" style="color: #797979;font-size:12px;" >请详细描述您的职责范围、工作任务以及取得的成绩等。</textarea>
                </p>
                <p class="tip ee" id="tishi10_error_id_<?php echo $an?>"><!--您输入的内容不得超过200个字符--></p>
            </div>
            <div class="clear"></div>
        </div>
    </div>

        <?php }?>
        <!-- end -->

        <div class="line">
            <div class="add_lf"></div>
            <div class="add_rt zy">
                <p class="one">
                    <a href="javascript:void(0)" onclick="add_div()" style="margin-right:10px;"><img src="<?php echo URL::webstatic("images/my_cyjy/add.jpg")?>" /></a><a href="javascript:void(0)" onclick="goto_sumbit()"><img src="<?php echo URL::webstatic("images/my_cyjy/save.jpg")?>"/></a>
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
<div id="opacity_box" style="display:none;"></div>
<div id="mess_delete_box">
    <a href="#" class="close">关闭</a>
    <p>一旦删除，将无法取回。您确定要删除此经验吗？</p>
    <p><a href="#" class="ensure"><img src="<?php echo URL::webstatic("images/my_messages/ensure.png")?>" /></a><a href="#" class="cancel"><img src="<?php echo URL::webstatic("images/my_messages/cancel.png")?>" /></a></p>
</div>
<!--弹出框结束-->
