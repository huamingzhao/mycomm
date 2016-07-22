<?php echo URL::webcss("zhaoshang.css")?>
<?php echo URL::webjs("My97DatePicker1/WdatePicker.js")?>
<?php echo URL::webjs("invitepro_zg.js")?>
<!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>搜索投资考察会</span><div class="clear"></div></div>
        <div id="right_con">
            <div class="invest">
                <form method="get" action="<?php echo URL::site('/person/member/invest/searchInvest');?>">
                <ul class="formul" style=" padding-right:15px;">
                    <li>
                        <label for = "hangye">行业：</label>
                        <select id="hye" name="parent_id">
                            <option value="">请选择</option>
                            <?php foreach ($listIndustry as $v){?>
                            <option value="<?=$v->industry_id?>" <?php if($v->industry_id==arr::get($search, 'parent_id')){echo "selected='selected'";}?>><?=$v->industry_name?></option>
                            <?php }?>
                        </select>
                        <select id="hye1" name="project_industry_id">
                            <option value=''>请选择</option>
                            <?php if(!empty($indust)){foreach ($indust as $v){?>
                            <option value="<?=$v->industry_id?>" <?php if($v->industry_id==arr::get($search, 'project_industry_id')){echo "selected='selected'";}?>><?=$v->industry_name?></option>
                            <?php }}?>
                        </select>
                    </li>
                    <li>
                        <label for="address">地区：</label>
                        <select id="address" name="investment_province">
                            <option value="">请选择</option>
                            <?php foreach ($area as $v){?>
                            <option value="<?=$v['cit_id']?>" <?php if($v['cit_id']==arr::get($search, 'investment_province')){echo "selected='selected'";}?>><?=$v['cit_name']?></option>
                            <?php }?>
                        </select>
                        <select id="address1" name="investment_city">
                        <option value="">请选择</option>
                        <?php if(!empty($citys)){foreach ($citys as $v){?>
                        <option value="<?=$v['cit_id']?>" <?php if($v['cit_id']==arr::get($search, 'investment_city')){?>selected='selected'<?php }?>><?=$v['cit_name']?></option>
                        <?php }}?>
                        </select>
                    </li>
                    <li>
                        <label for="money">金额：</label>
                        <select id="money" class="moreWidth" name="project_amount_type">
                            <option value="">请选择</option>
                            <?php foreach ($money as $k=>$v){?>
                            <option value="<?=$k?>" <?php if($k==arr::get($search, 'project_amount_type')){echo "selected='selected'";}?>><?=$v?></option>
                            <?php }?>
                        </select>
                    </li>
                    <li>
                        <label for="time">召开时间：</label>
                        <input type="text" id="d4311" onclick = "WdatePicker({minDate:'1970-01-01',maxDate:'#F{$dp.$D(\'d4312\')||\'2020-10-01\'}'})"  name="investment_start" value="<?=arr::get($search, 'investment_start')?>"/>
                        <span>至</span>
                        <input type="text" id="d4312" onclick = "WdatePicker({minDate:'#F{$dp.$D(\'d4311\')}',maxDate:'2020-10-01'})" name="investment_end" value="<?=arr::get($search, 'investment_end')?>"/>
                    </li>
                </ul>
                <div class="clear"></div>
                <div class="btn"><input type="image" src="<?php echo URL::webstatic('/images/zhaoshang/btn1.gif'); ?>" width="124" height="35"></div>
                </form>
                <?php if(!empty($list)){?>
                <?php foreach ($list as $value){?>
                <div class="renqiList" style="width:730px; margin-top:10px;">
                    <ul>
                        <li><a href="<?php echo urlbuilder::projectInvest($value['investment_id']);?>"><img src="<?=$value['investment_logo']?>" width="120" height="96"/></a></li>
                        <li class="liSecond">
                            <h4><a href="<?php echo urlbuilder::projectInvest($value['investment_id']);?>"><?=$value['investment_name']?></a></h4>
                            <p>投资考察会详情：<?=$value['investment_details']?></p>
                            <p>投资考察会时间：<?=$value['investment_start']?> - <?=$value['investment_end']?></p>
                            <p>投资考察会地址：<?=$value['investment_address']?></p>
                        </li>
                        <li class="liThird">
                        <span class="<?=$userid?>" id="user_id"></span>
                            <a href="<?php echo urlbuilder::projectInvest($value['investment_id']);?>" class="aLink_0">查看详情</a>
                            <?php if($value['is_apply']==1){?><a href="#" class="aLink_3">已报名</a><?php }else{?><a href="#" class="aLink_1" id="<?=$value['investment_id']?>">我要参加</a><?php }?>
                        </li>
                        <div class="clear"></div>
                    </ul>
                    <div class="clear"></div>
                    <p class="number"><span>倒计时：还有<ins> <?=$value['spantime']?> </ins>天</span></p>
                </div>
                <?php }?>
                <?=$page?>
                <?php }else{?>
                <div style="margin-top:20px;margin-left:200px;">
                            没有找到您想要的投资考察会，建议您放宽搜索条件
                <em>
                <a href="<?php echo URL::site('/person/member/invest/searchInvest');?>">展示全部投资考察会</a>
                </em>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
    <!--右侧结束-->
    <div class="opacityDiv" id="opacityDiv"></div>
<div class="invitePopup" id="invitePopup">
    <div class="close" id="close"></div>
    <div class="top">
        <h3>参会邀请函</h3>
    </div>
    <form method="post" action="<?php echo URL::site('/person/member/invest/applyInvest');?>">
        <div class="inviteDiv">
            <h4>如果您对此项目有兴趣，请花上1分钟的时间填写邀请函</h4>
            <ul>
                <li><label for="name">姓名：</label><input type="text" value="<?=$personinfo->per_realname?>" id="name" name="apply_name"/><ins>*姓名不能为空</ins></li>
                <li class="noPaddingBottom"><label>性别：</label>
                    <input type="radio" name="apply_sex" id="man" class="radio" <?php if($personinfo->per_gender==1){?>checked='checked' value="1" <?php }?>/><label class="radioLabel" for="man">男</label>
                    <input type="radio" name="apply_sex" id="woman" class="radio" <?php if($personinfo->per_gender==2){?>checked='checked' value="2" <?php }?>/><label class="radioLabel" for="woman">女</label><ins>*性别不能为空</ins>
                </li>
                <li><label for="tel">手机：</label><input type="text" value="<?=$mobile?>" id="tel" name="apply_mobile"/><ins>*请输入正确的手机号</ins></li>
                <div id="changci"></div>
                <li style="padding-left:90px;color:#f00; padding-bottom:5px; height:16px; line-height:16px;display:none;" id="chanci">请选择投资考察会场次！</li>
                <li class="jiudian"><label for="hotel" class="moreWidth">是否需要公司统一安排酒店：</label>
                    <input type="radio" name="is_hotel" value="1" id="yes" class="radio"/><label for="yes" class="radioLabel">是</label>
                    <input type="radio" name="is_hotel" value="2" id="no" class="radio"/><label for="no" class="radioLabel">否</label><ins class="insStyle">*安排酒店不能为空</ins>
                </li>
                <input type="hidden" name="user_id" value="<?=$userid?>">
            </ul>
            <div class="popupBtn">
                <input type="submit" class="sure inputSure" id="sure" value="">
                <input type="button" class="cancel inputCancel" id="cancel" value="">
            </div>
            <div class="clear"></div>
        </div>
    </form>
</div>