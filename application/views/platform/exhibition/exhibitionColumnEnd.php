<title><?php echo $title!="" ? $title : "一句话|投资赚钱好项目，一句话的事。";?></title>
<meta name="Keywords" content="<?php echo $keywords!="" ? $keywords : "投资，赚钱，好项目，一句话，投资赚钱";?>" />
<meta name="description" content="<?php echo $description!="" ? $description : "一句话平台专业、快速、精准匹配帮助加盟商与投资者达成真实、诚信的招商投资互动。投资好项目赚钱一句话的事。";?>" />
<?php echo URL::webcss("reset.css")?>
<?php echo URL::webcss("zhanhui.css")?>
<?php echo URL::webcss("common_plugIn.css")?>
<?php echo URL::webjs("zhanhui.js")?>
<?php echo URL::webjs("platform/home/login_fu.js")?>
<div class="headerwrap1">
        <div class="header1 hederend">
            <div class="timewrap">
                <div class="timecontent">
                    <h1><?=arr::get($arr_data['Exhibition_list'], "exhibition_name")?></h1>
                    <div class="clearfix conwrap">
                        <img class="fl" src="<?php echo URL::imgurl(arr::get($arr_data['Exhibition_list'], 'exhibition_logo_second', ''))?>" width="150" height="110" alt="<?=arr::get($arr_data['Exhibition_list'], "exhibition_name")?>">
                    <div class="fl confont">
                        <p class="exhbend">展会圆满结束</p>
                    </div>
                    </div>
                    <p class="timeicon"><?php echo date('m月d日', $arr_data['Exhibition_list']['exhibition_start']);?>-<?php echo date('m月d日', $arr_data['Exhibition_list']['exhibition_end']);?></p>
                </div>
            </div>
            <div class="endpo">
                <p>本期展会签约率达<span class="fz48"><?=arr::get($chenggonglu, "signatureRate",50)?>%</span></p>
                <p>参展项目达<span><?=arr::get($arr_data, "project_count",20)?></span>个，成功签约项目达<span><?=isset($sucprojectnum) ? $sucprojectnum : 5?></span>个</p>
                <p>参展人数达<span><?=arr::get($arr_data,"people_num",0);?></span>人，成功签约达<span><?=isset($sucpeoplenum) ? $sucpeoplenum : 1;?></span>次</p>
            </div>
            <div class="main">
                <a href="<?=urlbuilder::exhbInfo(arr::get($arr_data,"exhibition_id"))?>" <?php if(arr::get($arr_data, "catalog_id") ==""){echo 'class="on"';}?>>展会页</a>
                <?php if(count($arr_catalog)> 0){ foreach ($arr_catalog as $key=>$val){?>
                	 <a href="<?=  urlbuilder::catalogid($arr_data["exhibition_id"], $val['catalog_id'],'',1)?>"  <?php if(arr::get($arr_data, "catalog_id") == arr::get($val, "catalog_id")){echo 'class="on"';}?>><?=arr::get($val, "catalog_name")?></a>
                <?php }}?>
            </div>
        </div>
		<a href="<?=url::website('/');?>" class="returnyjh"><img src="<?php echo URL::webstatic('images/zhanhui/returnyjh.png')?>" ></a>
 </div>
 <div class="content">
    <input type="hidden" value="<?php echo $reg_fu_platform_num?>" id="reg_fu_platform_num_id">
    <input type="hidden" value="<?php echo $reg_fu_user_num?>" id="reg_fu_user_num_id">
		<div class="bobaobox clearfix" >
        <?php if($bobao){?>
        <p class="bobao fl">成果播报：</p>
        <div class="bobaobox2" >
            <ul class="bobaolist fl" data-direction="horizontal" id="searchNews1" style="width:10000px">            	
            	<?php foreach($bobao as $v){?>
                <li><?php echo $v;?></li> 
                <?php }?>   
            </ul>
        </div>
        <?php }?>
    </div>
        <div class="pabtn">
            <a href="javascript:;" <?php if($int_type == 1 || $int_type == 0){echo 'class="reda"';}else{echo 'class="white"';}?> id="reda">在线沟通</a><a href="javascript:;" <?php if($int_type == 2){echo 'class="reda"';}else{echo 'class="white"';}?> id="white">名片咨询</a>
        </div>
        <ul class="pubulist">
        <?php if(count($arr_data['list']) > 0){foreach ($arr_data['list'] as $key=>$val){?>
        	<li>
               <img width="150" height="120" src="<?=arr::get($val, "project_logo")?>" onerror="$(this).attr('src', '<?= URL::webstatic("/images/common/company_default.jpg");?>')" alt="<?=arr::get($val,"project_brand_name")?>">
                <p class='pubuh4title' title="<?=arr::get($val,"project_introduction")?>"><?=arr::get($val,"advertisement")?></p>
                <div class="pubupbox">
                    <p class="fz16" title="<?=arr::get($val,"project_introduction")?>"><?=arr::get($val,"project_brand_name")?></p>
                    <p><?=arr::get($val,"project_introduction")?></p>
                </div>
                <?php /*?>
                <i><em>咨询量：<?=arr::get($val, "inquiries")?></em></i>
                <?php */?>
            </li>
        <?php }}?>
        </ul>
        	<?php if(count(arr::get($arr_data, "list")) >= 10 ){?>
        	 	<div class="loading">正在加载...</div>
        	<?php }?>
       
        <div class="ryl_search_result_page" style="margin-bottom: 20px;"> 
        	<?php if(count(arr::get($arr_data, "list")) < 10 && count(arr::get($arr_data, "list")) > 0){echo arr::get($arr_data, "page");}?>
        </div>
</div>
<input type="hidden" id="offset_num" value="<?=arr::get($arr_data, "offset_num")?>"/>
<script type="text/javascript">
//咨询沟通
$("#reda").live("click",function(){tiaozhuan(1);})
//意向加盟
$("#white").live("click",function(){ tiaozhuan(2);})
function tiaozhuan(type){
	//判断是父类
	var exhibition_id = <?=arr::get($arr_data, "exhibition_id"); ?>;
	//alert(exhibition_id)
	var catalog_id = <?= arr::get($arr_data,"catalog_id")?>;
	var page_num = <?=$page_num ? $page_num : 1 ?>;
	if(exhibition_id && exhibition_id !=0){
		//降序
		window.location.href  = "/zhanhui/"+exhibition_id+"-t"+type+"/";
	}
	if(catalog_id && catalog_id !=0){
		//降序
		window.location.href  = "/zhanhui/"+exhibition_id+"-"+catalog_id+"-t"+type+"/";
	}
}
    $(function(){
    	var exhibition_id = <?=arr::get($arr_data, "exhibition_id");?>;
    	var catalog_id = <?= arr::get($arr_data,"catalog_id")?>;
    	var page_num = <?=$page_num ? $page_num : 1 ?>;
    	var new_page_num = <?=arr::get($arr_data, "offset_num")?>;
    	var int_type = <?=isset($int_type) ? $int_type : 0;?>;
    	var project_count = <?=count(arr::get($arr_data, "list"))?>;
    	var data = "";
    	var offset_num =$("#offset_num");
        var j=1;
        var page_html = $(".ryl_search_result_page");
        var pubulist = $(".pubulist");
        var num = 0;
        function run(){
            var oli=$(".pubulist li");
            var arr=[0,0,0,0,0];
            for(var i=0, num=0;i<j*oli.length;i++, num=i%5){
                oli.eq(i).css({"left":num*195,"top":arr[num]});
                arr[num] += oli.eq(i).outerHeight(true);
            }
            //alert(Math.max.apply(null, arr))
            $(".pubulist").height(Math.max.apply(null, arr));
        }
        var onLoadFlag = false;
        $(function(){ 
        	offset_num.val(new_page_num);
        	$(window).scrollTop(0); return false;
        });
        run();
        $(window).scroll(function(){
       		if($(window).height()+$(window).scrollTop()>=$(".loading").offset().top && !onLoadFlag){
        		onLoadFlag = true;
                j++;
                if(j>3){$(".loading").hide();return false;}
                if(exhibition_id && exhibition_id !=0){
                    if(int_type == 0){
                        //默认数据
                		data = "exhibition_id="+exhibition_id+"&offset_num="+(offset_num.val())+"&page="+(page_num);
                    }else{
                        //在线沟通总量  and 意向加盟(名片数量)
                    	data = "exhibition_id="+exhibition_id+"&offset_num="+(offset_num.val())+"&page="+(page_num)+"&type="+int_type;
                    }
                 } 
                if(catalog_id &&　catalog_id　!= 0){
                    if(int_type == 0){
					//默认数据
                   	 data = "exhibition_id="+exhibition_id+"&catalog_id="+catalog_id+"&offset_num="+(offset_num.val())+"&page="+(page_num);
                    }else{
                   	 data = "exhibition_id="+exhibition_id+"&catalog_id="+catalog_id+"&offset_num="+(offset_num.val())+"&page="+(page_num)+"&type="+int_type;
                    }
                 }
               if(project_count < 10 ){ return false;} 
                $.ajax({
   		           type: "get",
   		           dataType: "json",
   		           url: "/platform/ajaxcheck/getWaterfallList",
   		           data: data,
   		           complete :function(){
   		           },
   		           success: function(msg){
      		        	onLoadFlag = false;
      		        	$str = "";
      		        	num = msg['list'].length;
   	   		           	for(var i=0;i< msg['list'].length;i++){
   	   	   		          $str +='<li><img onerror="$(this).attr(\'\src\'\, \'\<?= URL::webstatic("/images/common/company_default.jpg");?>\'\)" src="'+msg['list'][i]['project_logo']+'" alt="'+msg['list'][i]['project_brand_name']+'"><p class="pubuh4title" title="'+msg['list'][i]['project_brand_name']+'">'+msg['list'][i]['project_brand_name']+'</p><div class="pubupbox"><p class="fz16" title="'+msg['list'][i]['project_brand_name']+'">'+msg['list'][i]['project_brand_name']+'</p><p>'+msg['list'][i]['project_introduction']+'</p></div> </li>'
   	   	   		         }
   	   	   		      	pubulist.append($str);
   	     	   	   		offset_num.val(msg['offset_num']);
                        run();
   		             }
   		       });
                if(j == 3){
                    if(exhibition_id && exhibition_id !=0 && catalog_id == 0){
                    	if(int_type == 0){
                    		$.get("/platform/ajaxcheck/getWaterfallListPage",{"exhibition_id":exhibition_id,"offset_num":(offset_num.val()),"page":page_num},function(data){
    							if(data){
    									page_html.html(data);
    								}
    			 	  	    });
                        }else{
                        	$.get("/platform/ajaxcheck/getWaterfallListPage",{"exhibition_id":exhibition_id,"offset_num":(offset_num.val()),"page":page_num,"type":int_type},function(data){
    							if(data){
    									page_html.html(data);
    								}
    			 	  	    });
                         }
                    }
                    if(catalog_id && catalog_id !=0){
                        if(int_type == 0){
                        	$.get("/platform/ajaxcheck/getWaterfallListPage",{"exhibition_id":exhibition_id,"catalog_id":catalog_id,"offset_num":(offset_num.val()),"page":page_num},function(data){
    							if(data){
    									page_html.html(data);
    								}
    			 	  	    });
                         }else{ 
                            $.get("/platform/ajaxcheck/getWaterfallListPage",{"exhibition_id":exhibition_id,"catalog_id":catalog_id,"offset_num":(offset_num.val()),"page":page_num,"type":int_type},function(data){
	        					 if(data){
	        							page_html.html(data);
	        					 }
        			 	  	});
                         }
                    }
   	   		    }
            }
        });   
    })
</script>