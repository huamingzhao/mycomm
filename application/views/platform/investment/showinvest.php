<?php echo URL::webcss("platform/competit_entrance.css"); ?>
<?php
$yyyy = $calendar[0];
$mm   = $calendar[1];
$dd   = $calendar[2];
?>

<style>
.main {height: auto;}
</style>

<!--公共背景框-->
<div class="main" style="background-color:#fff;">
   <div class="ryl_main_bg">
       <div class="ryl_main_bg01"></div>
       <div class="ryl_main_bg02">
          <!--投资考察会入口-->
          <div class="competit_entrance">
             <!--投资考察会入口-左侧-->
             <div class="competit_entrance_left">
                <!--即将召开的投资考察会-->
                <div class="ryl_will_meet">
                  <div class="ryl_will_meet_title"><span><h1>即将召开投资考察会</h1></span><em><?=$investNum?>场</em></div>
                  <div class="ryl_will_meet_calendar">
                    <p class="ryl_will_meet_calendar_tit">
                       <select name="selectyyyy" id="selectyyyy"  class="ryl_will_meet_year">
                     <?php
                        // 输出可选的年份
                        for($i = $dateconfig['YYYY_MIN']; $i <= $dateconfig['YYYY_MAX']; $i++)
                        {
                            if ($i == $calendar[0])
                            {
                                echo '<option value="'.$i.'" selected>'.$i."年</option>";
                            }
                            else
                            {
                                echo '<option value="'.$i.'">'.$i.'年</option>';
                            }
                        }
                        ?>
                       </select>
                       <select name="selectmm" id="selectmm" class="ryl_will_meet_month">
                        <?php
                        for($i = 1; $i <= 12; $i++)
                        {
                            if ($yyyy==date('Y')&&$i<date('m')){
                                //小于当前月份
                            }else{
                                if ($i == $calendar[1])
                                {
                                    echo '<option value="'.$i.'" selected>'.$i."月</option>";
                                }
                                else
                                {
                                    echo '<option value="'.$i.'">'.$i.'月</option>';
                                }
                            }
                        }
                        ?>
                       </select>
                    </p>
                    <div class="ryl_will_meet_calendar_cont">
                       <p><span>日</span><span>一</span><span>二</span><span>三</span><span>四</span><span>五</span><span>六</span></p>
                       <ul>
                        <?php
                        $idx = 0;

                        // 显示日历
                        if (arr::get($dayarray, 5)=="")
                            $daynum = 42;
                        else
                            $daynum = 35;
                        for ($j = 0; $j < $daynum; $j++)
                        {
                            if (arr::get($dayarray, $idx)=="")
                                echo '<li class="calendar_no_this"></li>';
                            else{
                                //$day = $dd!=""?$dd:date('d');
                                if ((arr::get($dayarray, $idx)==date('d')&&($dd==date('d')||$dd=='')&&$mm==date('m')&&$yyyy==date('Y'))||(arr::get($dayarray, $idx)==$dd))
                                    echo '<li id="showdate_'.$yyyy."_".$mm."_".arr::get($dayarray, $idx).'" class="ryl_meet_calendar_curr"><span>'.arr::get($dayarray, $idx).'</span><div class="shownum" style="display:none"></div></li>';
                                elseif ((arr::get($dayarray, $idx)<date('d')&&$mm==date('m')&&$yyyy==date('Y')))
                                    echo '<li id="showdate_'.$yyyy."_".$mm."_".arr::get($dayarray, $idx).'" class="rili_date rili_date_gq"><span>'.arr::get($dayarray, $idx).'</span><div class="shownum" style="display:none"></div></li>';
                                else
                                    echo '<li id="showdate_'.$yyyy."_".$mm."_".arr::get($dayarray, $idx).'" class="rili_date"><span>'.arr::get($dayarray, $idx).'</span><div class="shownum" style="display:none"></div></li>';
                            }
                            $idx++;
                        }
                        ?>
						<div class="clear"></div>
                       </ul>
                    </div>
                  </div>
                </div>


                <!--投资考察会查询-->
                <div class="competit_invest_select">
                  <div class="title"><h2>投资考察会查询</h2></div>
                  <p class="invest_list"><span>月份：</span>
                  <input type="hidden" id="monthly" name="monthly" value="<?php echo $monthly_yyyy."-".$monthly_mm?>"/>
                  <input type="hidden" name="from" value="monthly"/>
                  <select class="select01" name="monthly_yyyy" id="monthly_yyyy">
                    <?php
                    foreach ($monthly_config['yyyy'] as $k=>$v){
                    	if ($v == $monthly_yyyy)
                    	{
                    		echo '<option value="'.$v.'" selected="selected">'.$v."年</option>";
                    	}
                    	else
                    	{
                    		echo '<option value="'.$v.'">'.$v.'年</option>';
                    	}
                    }
                    ?>
                  </select>
                  <select class="select02" name="monthly_mm" id="monthly_mm">

                   <?php
                   		$key = array_search($monthly_yyyy, $monthly_config['yyyy']);
                   		foreach ($monthly_config['mm'][$key] as $v){
                   			if ($v == $monthly_mm)
							{
								echo '<option value="'.$v.'" selected="selected">'.$v."月</option>";
							}
							else
							{
								echo '<option value="'.$v.'">'.$v.'月</option>';
							}
                   		}
                        ?>
                  </select></p>
                  <p class="invest_list"><span>分类：</span>
                  <select class="select03" name="in_id" id="in_id">
                  <option value="" <?php if ($search['indust']['in_id']=="")echo 'selected';?>>不限</option>
                  <?php foreach ($listIndustry as $v){?>

                  <option value="<?=$v->industry_id?>" <?php if($v->industry_id==$search['indust']['in_id']){?>selected<?php }?>><?=$v->industry_name?></option>
                  <?php
                        }?>
                  </select></p>
                  <div class="competit_invest_choose">
                     <label>地点：</label><input box-title="省级" class="select03 competit_entrance_area_choose select_area_toggle" name="" type="text"data-url="/ajaxcheck/getArea" first-result=".per_area_id" second-result=".per_area_id" value="<?php if(empty($search['area']['cit_name'])||$search['area']['cit_name']=='全国'){echo '不限';}else{echo $search['area']['cit_name'];} ?>" id="area_name"/>
                  </div>
                  
                    <input type="hidden" value="<?php if($search['area']['cit_id']!=88){echo $search['area']['cit_id'];}else{echo '';} ?>" class="per_area_id" name="areaid" id="areaid">
                  <p class="invest_list"><span></span><img id="imgsubmit" style="cursor:pointer;" src="<?php echo URL::webstatic("images/platform/zsh_enter/kc_btn.jpg");?>"></p>
                </div>

                <!--历史投资考察会-->
                <div class="ryl_history_meet">
                  <a href="<?php echo urlbuilder::rootDir("lishizhaoshang");?>">(<?=$investHistoryNum?>场)</a>
                </div>
                <div class="clear"></div>
             </div>

             <!--投资考察会入口-右侧-->
             <div class="competit_entrance_right">


                <?php if(isset($recomand)){?>
                  <div class="competit_entrance_choose_nojg">
                       <div class="competit_entrance_chooseed_left_no">
                              <span>抱歉！没有找到</span>
                              <a href="/platform/investment/index?time=<?=$search['time']?>"><?=$search['time']?></a>
                             <?php if($search['area']['cit_id']!=88){?><a href="/platform/investment/index?areaid=<?=$search['area']['cit_id']?>"><?=$search['area']['cit_name']?></a><?php }?>
                             <a href="/platform/investment/index?in_id=<?=$search['indust']['in_id']?>"><?=$search['indust']['in_name']?></a>
                              <span>相关的投资考察会</span>
                        </div>
                         <?php if(!isset($user_type)||$user_type==2){?>
                       <div class="competit_entrance_chooseed_right"><a href="#" id="fabu"><img src="<?php echo URL::webstatic("/images/platform/zsh_enter/icon02.jpg")?>"/>发布投资考察会</a></div>
                       <?php }elseif($user_type==1){?>
                         <div class="competit_entrance_chooseed_right"><a href="/company/member/project/addinvest"><img src="<?php echo URL::webstatic("/images/platform/zsh_enter/icon02.jpg")?>"/>发布投资考察会</a></div>
                        <?php }?>
                       <div class="clear"></div>
                  </div>
                  <div class="competit_entrance_chooseed">

                <?php }else{?>
                <div class="competit_entrance_chooseed">
                       <div class="meet_left"><a href="/">首页</a> > <a href="/touzikaocha/">投资考察会</a></div>
                        <?php if(!isset($user_type)||$user_type==2){?>
                       <div class="competit_entrance_chooseed_right">
                         <a title="发布投资考察会" href="#" id="fabu"><img src="<?php echo URL::webstatic("/images/platform/zsh_enter/icon02.jpg")?>"/>发布投资考察会</a></div>
                       <?php }elseif($user_type==1){?>
                       <div class="competit_entrance_chooseed_right">
                         <a title="发布投资考察会" href="/company/member/project/addinvest"><img src="<?php echo URL::webstatic("/images/platform/zsh_enter/icon02.jpg")?>"/>发布投资考察会</a>
                        </div><?php }?>
                       <div class="clear"></div>
                    </div>

                <div class="competit_entrance_chooseed">
                         <?php if(!empty($search['time'])||$search['area']['cit_id']!=88||!empty($search['indust']['in_name'])){?>
                       <div class="competit_entrance_chooseed_left">
                          <span>您已选择：</span>
                          <?php if(!empty($search['time'])){?><a href="/platform/investment/index?<?php echo preg_replace('/(\?|&|\/)(yyyymm=.*?(&))|(selectyyyy=.*?(&))|(selectmm=.*?(&))|(selectdd=.*?(&))/i','',$url_query_data);?>" class="area_selected"><?=$search['time']?></a><?php }?>
                          <?php if($search['area']['cit_id']!=88){?><a href="/platform/investment/index?<?php echo preg_replace('/(\?|&|\/)areaid(\/|=).*?(&)/i','&',$url_query_data);?>"><?=$search['area']['cit_name']?></a><?php }?>
                          <?php if(!empty($search['indust']['in_name'])){?><a href="/platform/investment/index?<?php echo preg_replace('/(\?|&|\/)in_id(\/|=).*?(&)/i','&',$url_query_data);?>"><?=$search['indust']['in_name']?></a><?php }?>
                       </div>
                       <?php }?>
                    <div class="competit_entrance_chooseed_right">
                        <p><span>场投资考察会</span><em id="show_data"></em><span><?php echo date('Y',strtotime($IndustryNumBydate));?>年<?php echo date('n',strtotime($IndustryNumBydate));?>月共</span></p>
                        <div class="competit_month_meetnum"></div>
                    </div>
						<?php }?>

                       <div class="clear"></div>
                    </div>
                    <?php if($count!=0){?>
                            <div class="competit_entrance_listcont">
                    <ul>
        <?php foreach ($list as $value){?>
                    <li>
                        <p class="competit_entrance_meet_left"><a href="<?php echo urlbuilder::projectInvest($value['investment_id']);?>" target="_blank"><img src="<?=$value['investment_logo']?>" alt="<?=$value['investment_name']?>" /></a></p>
                        <div class="competit_entrance_meet_center">
                           <div class="competit_entrance_meet_title"><h3 class="floleft"><a class="wt280 jchar"  href="<?php echo urlbuilder::projectInvest($value['investment_id']);?>" target="_blank" title="<?=$value['investment_name']?>"><?=$value['investment_name']?></a></h3><em>意向人数 <? echo $value['visit_num']?></em></div>
                           <span><label>召开时间：</label><b><?=$value['investment_start']?>-<?=$value['investment_end']?></b></span>
                           <span><label>召开地址：</label><b><?=$value['investment_address']?></b></span>
                           <a title="报名参会" class="competit_entrance_meet_btn" href="<?php echo urlbuilder::projectInvest($value['investment_id']);?>">报名参会</a>
                        </div>
                        <p class="competit_entrance_meet_right">
                           <span><em><?=$value['spantime']?></em></span>
                           <b>还剩<em><?=$value['spantime']?></em>天</b>
                        </p>
                        <div class="clear"></div>
                    </li>
        <?php }?>
                    </ul>

                   <div class="ryl_search_result_page"><?=$page?></div>
                    <div class="clear"></div>
                </div>
				</div>
<?php }else{?>
                <!--搜索无结果-->
                <div class="competit_entrance_nomeet">

                   <div class="clear"></div>
                   <h3>企业还未发布投资考察会，建议您搜索心仪的项目</h3>
                   <div class="competit_entrance_nomeet_line"></div>
                   <div class="competit_entrance_nomeet_btn"><!-- <a href="#" target="_blank">精准匹配</a> --><a href="<?php echo urlbuilder::rootDir ("xiangdao");?>" target="_blank">找项目</a></div>
                   <div class="clear"></div>
                </div>
<?php }?>
                <div class="clear"></div>
             </div>

       <div class="ryl_main_bg03"></div>
             <div class="clear"></div>
          </div>

          <div class="clear"></div>
       </div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>
<script type="text/javascript">
$(function(){
	var monthly_config = <?php echo json_encode($monthly_config)?>;
	//月历部分
	$("#monthly_yyyy").change(function(){
		var html ='';
		var monthly_yyyy = $("#monthly_yyyy option:selected").val();
		for(i=0;i<monthly_config['yyyy'].length;i++){
			if(monthly_config['yyyy'][i]==monthly_yyyy){
				for(k=0;k<monthly_config['mm'][i].length;k++){
					if(monthly_config['mm'][i][k]==<?php echo $monthly_mm?>)
						html+='<option value="'+monthly_config['mm'][i][k]+'" selected>'+monthly_config['mm'][i][k]+'月</option>';
					else
						html+='<option value="'+monthly_config['mm'][i][k]+'">'+monthly_config['mm'][i][k]+'月</option>';
				}
			}
        }
		$("#monthly_mm").html(html);
		$("#monthly").val($("#monthly_yyyy option:selected").val()+"-"+$("#monthly_mm option:selected").val());
	});
	$("#monthly_mm").change(function(){
		$("#monthly").val($("#monthly_yyyy option:selected").val()+"-"+$("#monthly_mm option:selected").val());
	});
  $("#fabu").click(function() {
      $("body")[0].show({
        title:"生意街提示您",
        content:"<div class='mb_content send_box'><p class='errorbox'>很抱歉，只有企业用户才能发布投资考察会哦！</p></div>",
        btn:"ok"
      });

  });
	$("#imgsubmit").click(function(){
		var monthly = $("#monthly").val();
		var areaid = $("#areaid").val();
		var in_id = $("#in_id").find("option:selected").val();
	    var url = "/platform/investment/index?<?php echo preg_replace('/(\?|&|\/)in_id(\/|=).*?(&)/i','&',preg_replace('/(\?|&|\/)areaid(\/|=).*?(&)/i','&',preg_replace('/(\?|&|\/)monthly(\/|=).*?(&)/i','&',preg_replace('/(\?|&|\/)from(\/|=).*?(&)/i','&',$url_query_data))));?>";
	    url = url+"&from=monthly&in_id="+in_id+"&areaid="+areaid+"&monthly="+monthly;
	    window.location.href = url;
	});
});
</script>

<script type="text/javascript">
jQuery(function($){
	var calendar_yyyy = <?php echo $calendar[0];?>;
    var calendar_mm = <?php echo $calendar[1];?>;
    var calendar_dd = <?php echo $calendar[2];?>;
    var mm = '<?php echo strlen($mm)==1?"0".$mm:$mm;?>'
    var strDate = "<?php echo $yyyy?>-"+mm+"-";
    var json = <?php echo json_encode($industrynumleft)?>;
    /*start*/
    var jsondata = <?php echo json_encode($industrynum)?>;
    var show_str = '';
    var show_date_num = 0;
    for (var one in jsondata)
    {
        if(jsondata[one]['investment_date'].substring(0,7)=="<?php echo $IndustryNumBydate?>"){
            if(jsondata[one]['investment_date'].substring(0,4)==<? echo date('Y')?>&&jsondata[one]['investment_date'].substring(5,7)==<?php echo date('m')?>){
                if(jsondata[one]['investment_date'].substring(8,10)><? echo date('d')?>){
                    show_str += '<a href="<?php echo URL::site('/platform/investment/index?&from=calendar')?>&calendar='+jsondata[one]['investment_date'].substring(0,4)+'-'+jsondata[one]['investment_date'].substring(5,7)+'-'+jsondata[one]['investment_date'].substring(8,10)+'" title=""><em>'+jsondata[one]['investment_date'].substring(0,10)+'</em><b>（'+jsondata[one]['investment_num']+'场）</b></a>';
                    show_date_num = parseFloat(show_date_num)+parseFloat(jsondata[one]['investment_num']);
                }
            }
            else{
                show_str += '<a href="<?php echo URL::site('/platform/investment/index?&from=calendar')?>&calendar='+jsondata[one]['investment_date'].substring(0,4)+'-'+jsondata[one]['investment_date'].substring(5,7)+'-'+jsondata[one]['investment_date'].substring(8,10)+'" title=""><em>'+jsondata[one]['investment_date'].substring(0,10)+'</em><b>（'+jsondata[one]['investment_num']+'场）</b></a>';
                show_date_num = parseFloat(show_date_num)+parseFloat(jsondata[one]['investment_num']);
            }
        }
    }
    $('#show_data').html(show_date_num);
    $('.competit_month_meetnum').hide().html(show_str);
    $('#show_data').stop(false,true).hover(
            function () {
                $('.competit_month_meetnum').show();
            },function(){
    });
    $('.competit_month_meetnum').stop(false,true).hover(
            function () {

            },function(){
                $('.competit_month_meetnum').hide();
    });
    /*end*/
    $(".ryl_will_meet_calendar_cont li").stop(false,true).hover(
        function () {
            var _this = $(this);
            var dd = $(this).html();
            var yearmonth = '';
            if($(this).children("span").html().length==1)
                yearmonth = strDate+"0"+_this.children("span").html();
            else
                yearmonth = strDate+_this.children("span").html();
            var date = yearmonth +' 00:00:00';
            var html = '<div class="ryl_meet_calendar_ap"><div class="ryl_meet_calendar_ap_01"></div><div class="ryl_meet_calendar_ap_02"><div id="ryl_meet_calendar_ap_bg"><p><em>'+date+'</em>';
            var str = '';
            var date_num = 0;
            for (var one in json)
            {
                if(json[one]['investment_date']==date){
                    //str += '<span><em>'+json[one]['cit_name']+'</em><label>'+json[one]['industry_name']+'</label>';
                    //str += '<b>（'+json[one]['investment_num']+'场）</b></span>';
                    str +='<a class="data_num" title="'+jsondata[one]['investment_num']+'"></a>';
                    date_num = parseFloat(date_num)+parseFloat(json[one]['investment_num']);
                }
            }
            html+= '<b>（'+date_num+'场）</b></p>'+str+'<div class="clear"></div></div><div class="clear"></div></div><div class="ryl_meet_calendar_ap_03"></div><div class="clear"></div></div>';
            if(js_strto_time(date)>js_strto_time('<?php echo date('Y-m-d 00:00:00')?>')){
                _this.children(".shownum").html(html);
                _this.children(".shownum").show();
            }
    },function(){
       $(this).children(".shownum").hide();
    });

    $(".ryl_will_meet_calendar_cont li").click(function(){
        var li_id = $(this).attr('id');
        var str = li_id.split("_");
        if($(this).find("a").attr('title')==undefined)
            return false;
        if(str[1]=='<?php echo date('Y')?>'&&parseFloat(str[2])==parseFloat('<?php echo date('n')?>')&&parseFloat(str[3])<=parseFloat('<?php echo date('j')?>')){
            return false;
        }else{
        	calendar = calendar_yyyy+"-"+calendar_mm+"-"+$(this).children("span").html();
        	var url = "/platform/investment/index?<?php echo preg_replace('/(\?|&|\/)calendar(\/|=).*?(&)/i','&',preg_replace('/(\?|&|\/)from(\/|=).*?(&)/i','&',$url_query_data));?>";
            url = url+"&calendar="+calendar+"&from=calendar";
            window.location.href = url;
        }
    });

    $("#selectyyyy").change(function(){
        var checkstr = $("#selectyyyy").find("option:selected").val();
        calendar = checkstr+"-"+calendar_mm+"-"+calendar_dd;
        var monthly = checkstr+"-"+calendar_mm;
        var url = "/platform/investment/index?<?php echo preg_replace('/(\?|&|\/)calendar(\/|=).*?(&)/i','&',preg_replace('/(\?|&|\/)from(\/|=).*?(&)/i','&',$url_query_data));?>";
        url = url+"&from=monthly&monthly="+monthly+"&calendar="+calendar;
        window.location.href = url;
    })
    $("#selectmm").change(function(){
        var checkstr = $("#selectmm").find("option:selected").val();
        calendar = calendar_yyyy+"-"+checkstr+"-"+calendar_dd;
        var monthly = calendar_yyyy+"-"+checkstr;
        var url = "/platform/investment/index?<?php echo preg_replace('/(\?|&|\/)calendar(\/|=).*?(&)/i','&',preg_replace('/(\?|&|\/)from(\/|=).*?(&)/i','&',$url_query_data));?>";
        url = url+"&from=monthly&monthly="+monthly+"&calendar="+calendar;
        window.location.href = url;
    })
});
function js_strto_time(str_time){
    var new_str = str_time.replace(/:/g,'-');
    new_str = new_str.replace(/ /g,'-');
    var arr = new_str.split("-");
    var datum = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],arr[3]-8,arr[4],arr[5]));
    return strtotime = datum.getTime()/1000;
}

function js_date_time(unixtime) {
    var timestr = new Date(parseInt(unixtime) * 1000);
    var datetime = timestr.toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
    return datetime;
}
</script>
