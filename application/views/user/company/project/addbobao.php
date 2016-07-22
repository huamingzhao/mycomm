     <?php echo URL::webcss("my_bussines_bobao.css")?>
     <?php echo URL::webcss("renzheng.css"); ?>
     <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>投资考察会成果播报</span><div class="clear"></div></h2>

                    <!--投资考察会成果播报-->
                    <div class="my_bussines_bobao">

                       <p class="my_bussines_bobao_title"><b>请填写您的成果播报信息</b>（<strong>*</strong>为必填项）</p>
					<form method="post" action="/company/member/project/addbobao">
                       <div class="my_bussines_bobao_cont">
                         <div class="my_bussines_bobao_list">
                           <label><em>*</em> 报名人数：</label>
                           <p><input name="bobao_num" type="text" maxlength="4" class="my_bussines_bobao_text" value="<?=arr::get($bobao,'bobao_num')?>" id="baoming_num" onkeyup="this.value=this.value.replace(/[^0-9]/gi,'')" > 人<span id="baoming_error" style="color:#f00;float:none;padding-left:10px;"></span></p>
                           <div class="clear"></div>
                         </div>
                         <div class="my_bussines_bobao_list">
                           <label><em>*</em> 签约人数：</label>
                           <p><input name="bobao_sign" type="text" maxlength="4" class="my_bussines_bobao_text" value="<?=arr::get($bobao,'bobao_sign')?>" id="qianyue_num" onkeyup="this.value=this.value.replace(/[^0-9]/gi,'')" > 人<span id="qianyue_error" style="color:#f00;float:none;padding-left:10px;"></span></p>
                           <div class="clear"></div>
                         </div>
                          <div class="my_bussines_bobao_list">
                           <label><em>*</em> 签约率：</label>
                           <p id="qianyue_per2"><?php if(arr::get($bobao,'bobao_sign')){echo  floor(arr::get($bobao,'bobao_sign')/arr::get($bobao,'bobao_num')*100).'%';}else{echo '0%';} ?></p>
                           <div class="clear"></div>
                         </div>
                         <input type="hidden" name="invest_id" value="<?=$invest_id?>">
                           <input type="hidden" name="page_num" value="<?=$page_num?>">
                         <div class="my_bussines_bobao_list">
                           <label></label>
                           <p><input type="image" src="<?php echo url::webstatic('/images/my_business_infor/submit_btn.jpg');?>" id="submit_bobao"></p>
                         <div class="clear"></div>
                         </div>
                           <div class="clear"></div>
                       </div>
                       </form>
                       <div class="clear"></div>
                    </div>

              </div>
                <!--主体部分结束-->
        <script type="text/javascript">
            var thisSpan;


            $("#baoming_num").keyup(function(){
                if($(this).val()!=0){
                    if($("#qianyue_num").val() == ""){
                    }else{
                        var qianyue_per = Math.floor(($("#qianyue_num").val()/$("#baoming_num").val())*100);
                        //$("#qianyue_per").val(qianyue_per+"%");
                        $("#qianyue_per2").text(qianyue_per+"%");
                    }
                }
                if($("#baoming_num").val()*1<$("#qianyue_num").val()*1){
                    $("#baoming_error").text("报名人数不能小于签约人数");
                    $("#qianyue_error").text("");
                }else{
                    $("#baoming_error").text("");
                    $("#qianyue_error").text("");
                }
            });
            $("#qianyue_num").keyup(function(){
                if($("#baoming_num").val()!=0){
                    var qianyue_per = Math.floor(($("#qianyue_num").val()/$("#baoming_num").val())*100);
                    //$("#qianyue_per").val(qianyue_per+"%");
                    $("#qianyue_per2").text(qianyue_per+"%");
                }
                if($("#baoming_num").val()*1<$("#qianyue_num").val()*1){
                    $("#qianyue_error").text("签约人数不能大于报名人数");
                    $("#baoming_error").text("");
                }else{
                    $("#baoming_error").text("");
                    $("#qianyue_error").text("");
                }
            });

            $("#submit_bobao").click(function(){
                var test_num = 1;
                if($("#baoming_num").val() == ""){
                    $("#baoming_error").text("请输入招商会的参会人数");
                    test_num = 0;
                }
                if($("#qianyue_num").val() == ""){
                    $("#qianyue_error").text("请输入招商会的签约人数");
                    test_num = 0;
                }else{
                    if($("#baoming_num").val()*1<$("#qianyue_num").val()*1){
                        $("#qianyue_error").text("签约人数不能大于报名人数");
                        test_num = 0;
                    }else{
                        $("#qianyue_error").text("");
                    }
                }
                if(test_num == 0){
                    return false;
                }
            });
            $("#flashBtn1").click(function(){
                $("#flashPopup").slideDown(function(){
                    $("#opacityBg").show();
                })
            })
    </script>
                <!--上传图片弹出框 END-->