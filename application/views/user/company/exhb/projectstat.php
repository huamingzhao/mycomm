<?php echo URL::webjs("My97DatePicker/WdatePicker.js")?>

<div id="right">
        <div class="traffic_statistics_title">
            <span><font><?php echo $pro_name?>项目：</font><b>流量统计</b></span>
            <a id="traffic_statistics_btn" href="">?  鼠标移至此处查看备注</a>
        <div class="traffic_statistics_msg" style="z-index:111;">
            <i></i>
            <p class="first_content"><b>项目官网访问量：</b>一段时间内访问项目官网的所有访问量</p>
        </div>
            <div class="clear"></div>
        </div>
        <FORM action="/company/member/exhb/showProjectPv" method="get" id="bu_form_id">
            <div class="traffic_statistics_selected">
                <input name="begin" id='begin_time' type="text" onclick="WdatePicker()" readonly="readonly" value="<?php echo $begin?>"/><font>至</font><input name="end" id="end_time" type="text" readonly="readonly" onclick="WdatePicker()" value="<?php echo $end?>"/><INPUT type='hidden' value='<?php echo $projectid?>' name='project_id'>
                <input id="traffic_statistics_submit" type="submit" value="搜索" />
            </div>
        </FORM>
        <div class="traffic_statistics_result">
            <!-- 这里了显示统计的图表 -->
            <?php if( $begin!='' || $end!='' ){?>
            <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,19,0" name="fileId4" width="700" height="456" id="flashrek2">
                <param name="movie" value="<?php echo URL::webstatic('/flash/lineChat2.swf?url='.url::site('/platform/ajaxcheck/showProjectPvApi').'&pid='.$projectid.'&begin='.$begin.'&end='.$end)?>" />
                   <param name="quality" value="high" />
                  <param name="wmode" value="transparent" />
                  <param name="allowScriptAccess" value="always" />
                     <embed src="<?php echo URL::webstatic('/flash/lineChat2.swf?url='.url::site('/platform/ajaxcheck/showProjectPvApi').'&pid='.$projectid.'&begin='.$begin.'&end='.$end)?>" allowscriptaccess="always" id="fileId4" wmode="transparent" width="700" height="456" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"> </embed>
           </object>
            <?php }?> 

        </div>
    </div>
<script type="text/javascript">

$("#traffic_statistics_btn").hover(function(){
    $(".traffic_statistics_msg").show(300);
},function(){
     $(".traffic_statistics_msg").hide(300);
})
$("#traffic_statistics_submit").click(function(){
  var aDate, iDays;
  var sDate1 = $("#begin_time").val();
  var sDate2 = $("#end_time").val();
  if(!sDate1 || !sDate2){
    alert("请先选择日期");
    return false;
  }

  aDate = sDate1.split("-")     
  oDate1 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0])   //转换为12-13-2008格式     
  aDate = sDate2.split("-")     
  oDate2 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0])     
  iDays = parseInt((oDate1 - oDate2) / 1000 / 60 / 60 /24);   //把相差的毫秒数转换为天数 

  if(iDays > 0){
    alert("开始日期不能大于结束日期");
    return false;
  }
  if(Math.abs(iDays) > 30){
    alert("查询日期间隔需小于30天");
    return false;
  }
});

</script>