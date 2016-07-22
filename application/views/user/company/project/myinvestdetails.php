<?php echo URL::webcss("my_bussines.css")?>
<?php echo URL::webcss("account_zg.css")?>
<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>
<?php echo URL::webcss("postinvestment.css")?>
<script type="text/javascript">
$(function(){

    var user_type = $("#user_type");
    user_type.change(function(){
            $("#search_form").submit();
            return false;
        }
    );
    $("#imagessubmit").click(function(){
        var firstDate = $("#startDate").val();
        var lastDate = $("#stopDate").val();
        if(firstDate == ""){
            $(".tishi5").text("时间不能为空");
            $(this).find("input[type='text']:first").focus();
            return false;
        }
        if(lastDate == ""){
            $(".tishi5").text("时间不能为空");
            $(this).find("input[type='text']:last").focus();
            return false;
        }
        firstDate = firstDate.split("-");
        lastDate = lastDate.split("-");
        var startDate = new Date(firstDate[0], firstDate[1], firstDate[2]);
        var endDate = new Date(lastDate[0], lastDate[1], lastDate[2]);
        if(startDate > endDate){
            $(".tishi5").text("请选择正确的浏览日期");
            $(this).find("input[type='text']:first").focus();
            return false;
        }else{
            $(".tishi5").text("");
            $("#search_form").submit();
            return false;
        }
        
    })
    
    var user_type_val = '<?=isset($form['user_type']) ? $form['user_type'] : ''?>';
    user_type.val(user_type_val);

})
</script>
<!--主体部分开始-->
<div id="right">
    <div id="right_top">
        <span>投资考察会 > 谁看了我的投资考察会</span>
        <div class="clear"></div>
    </div>
    <div id="right_con">
        <div class="accountDetail accountDetail_new invest_flow_count">
            <div>
                <form method="get" id="search_form" action="/company/member/project/myInvestDetails">
                    <div class="choosebox">
                        <label>浏览日期：</label>
                        <input style="height:28px;width:140px; border:1px solid #ccc;" id="startDate" name="start"  readonly="readonly" onClick="WdatePicker()" value="<?=isset($form['start']) ? $form['start'] : ''?>"/>
                        <label class="date_text">至</label>
                        <input style="height:28px;width:140px; border:1px solid #ccc;" id="stopDate" name="end" type="text" readonly="readonly" onClick="WdatePicker()" value="<?=isset($form['end']) ? $form['end'] : ''?>"/>
                        <label class="select_text" >用户类型：</label>
                        <select style="height: 30px;width:140px; vertical-align: middle;" id="user_type" name="user_type" >
                            <option value="0" selected>不限</option>
                            <option value="1" >登录用户</option>
                            <option value="2" >匿名用户</option>
                        </select>
                        <input type="hidden" name="invest_id" value="<?=$invest_id?>" />
                        <input id="imagessubmit" type="image" name="search" style="vertical-align: middle;margin-bottom:4px; margin-left:5px;" class="submit" width="65" height="30" src="<?php echo URL::webstatic('images/touzi/serch.png')?>">
                        <p class="tishi5" style="color: red; margin-left:65px;"></p>
                    </div>
                </form>
            </div>
        </div>
        <div class="touzitabelbox">
            <table cellspacing="0" cellpadding="0">
                <tr>
                    <th width="200">浏览时间</th>
                    <th width="125">姓名</th>
                    <th width="150">浏览IP地址</th>
                    <th width="145" class="tr">浏览IP所属地区</th>
                    <th width="85">操作</th>
                </tr>
                <?php foreach ($list as $v):?>
                <tr>
                    <td><?=$v['add_time']?></td>
                    <td><p class="jchar wt115"><?=$v['user_name']?></p></td>
                    <td><?=$v['ip']?></td>
                    <td class="tr"><?=$v['address']?></td>
                    <td>
                        <?php if($v['user_id'] > 0){ ?>
                        <a href="/platform/SearchInvestor/showInvestorProfile?userid=<?=$v['user_id']?>" target="_blank">查看详情</a>
                        <?php }else{ ?>
                        <i>查看详情</i>
                        <?php } ?>
                    </td>
                </tr>
                <?php endforeach;?>
            </table>
        </div>
    </div>
    <?=$page;?>
</div>
<!--主体部分结束-->