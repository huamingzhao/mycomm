   <?php echo URL::webcss("my_bussines_bobao.css")?>
  <!--主体部分开始-->
                <div class="right">
                    <h2 class="user_right_title"><span>投资考察会成果播报</span><div class="clear"></div></h2>

                    <!--投资考察会成果播报-->
                    <div class="my_bussines_bobao">


                       <div class="my_bussines_bobao_cont my_bussines_bobao_cont_save">
                         <div class="my_bussines_bobao_list">
                           <label>参会人数：</label>
                           <p><?=arr::get($bobao,'bobao_num')?>人</p>
                           <div class="clear"></div>
                         </div>
                         <div class="my_bussines_bobao_list">
                           <label>签约人数：</label>
                           <p><?=arr::get($bobao,'bobao_sign')?>人</p>
                           <div class="clear"></div>
                         </div>
                         <div class="my_bussines_bobao_list">
                           <label>签约率：</label>
                           <p><?php echo floor(arr::get($bobao,'bobao_sign')/arr::get($bobao,'bobao_num')*100).'%';?></p>
                           <div class="clear"></div>
                         </div>

                         <div class="clear"></div>
                       </div>
                       <div class="clear"></div>
                    </div>

              </div>
                <!--主体部分结束-->