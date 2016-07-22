<!-- 采用IE7模式解析：解决IE8 image max-length bug END-->
<?php header('X-UA-Compatible: IE=7'); ?>
<!-- 采用IE7模式解析：解决IE8 image max-length bug END-->
<?php echo URL::webcss("find_invester.css")?>
<!--公共背景框-->
<div style="height:auto; background-color:#fff; padding:10px 0 5px 0;" class="main">
   <div class="map_bg">
       <div class="map_bg01"></div>
       <div class="map_bg02">
          <!--找投资者-->
          <div class="find_invester_main">
             <!--搜索框-->
             <?php /*?>
             <div class="find_invester_title">
                <label><a href="#" target="_blank"><img src="<?php echo URL::webstatic('images/find_invester/logo.jpg')?>" alt="一句话" /></a></label>
                <form action="/zhaotouzi/" method="get" class="formStyle" id="formStyle">
                <div class="find_invester_search">
                    <div class="invester_search_cont">
                        <p> <input type="text" maxlength="38" autocomplete="off" class="ryl_index_searchtext"  name="w" id="word" value="<?php if($wordShow){echo $wordShow; }else{ echo '';}?>"placeholder="请输入您要搜索的条件。如： 餐饮 10万 上海"/></p>
                        <input type="button" class="ryl_index_searchbtn" id="inputSubmit">
                    </div>
                      <div class="invester_search_text" style="width:600px;"><span>热门搜索：</span><span id="tag">
                       <?php foreach($tag as $v1){
                       	echo '<a href="/zhaotouzi/?w='.$v1['tag_name'].'">'.$v1['tag_name'].'</a>';}?></span><a href="javascript:void(0)" id="change" class="change_other"><img  src="<?php echo URL::webstatic('images/find_invester/btn01.jpg')?>" /></a></div>                    
                </div>
                  </form>
                <div class="find_invester_title_r">有<em><?php echo $alltotalcount;?></em>名投资者正在找项目</div>
                <div class="clear"></div>
             </div>
             <?php */?>
             <!--内容-->
             <div class="find_invester_cont">
               <!--右侧-->
                <div class="find_invester_cont_left">
                  <div class="invester_cont_title01">
                    <label id="hide" style="display:block;"><img src="<?php echo URL::webstatic('images/find_invester/icon02.jpg')?>" /></label>
                    <label id="show"><img src="<?php echo URL::webstatic('images/find_invester/icon03.jpg')?>" /></label>
                    <em class="invester_icon_01"><img src="<?php echo URL::webstatic('images/find_invester/icon01.jpg')?>" /></em>
                    <?php if($totalcount>0 && $input_value){?>
                    <span>一句话为您找到符合<em><?php 
          			  if(mb_strlen($selectvalue)>15){
							echo mb_substr($selectvalue,0,15,'UTF-8').'...';
                      }
                      else{
                          echo $selectvalue;
                      }?></em>条件的有<em class="invester_num"><?php echo $totalcount;?></em>名投资者</span>
                   	<?php }else{ ?>
                   	         <?php if($selectvalue){?>
		                   	 <span>抱歉！没有找到<em><?php 
		          			  if($selectvalue && mb_strlen($selectvalue)>15){
									echo mb_substr($selectvalue,0,15,'UTF-8').'...';
		                      }
		                      else{
		                          echo $selectvalue;
		                      }?></em>相关的投资人，请看看活跃度高的意向投资人吧。</span>
		                      <?php }else{ echo '<span>根据您的条件搜索心仪的投资者吧</span>';}?>
                   	 <?php }?>
					 <a class="conditions" href="#">条件筛选</a>
                   	<input id="hiddenvalue" type="hidden"  value="" />
                  </div>
					<input type="hidden"  value="<?php if($wordShow){echo $wordShow; }else{ echo '';}?>">
                  <div class="invester_cont_title02" style="display:block;">
                    <form action="/zhaotouzi/" method="get" class="formStyle1" id="formStyle3">
                    <ul>
                    <li style="width:180px;"><em>所在地：</em>
                     <div style="position: relative; display:inline-block;" >
			                  <a href="#" style="width:100px;" class="select_area_toggle select_area_toggle_0" data-url="/ajaxcheck/getArea" first-result=".per_area_id" second-result=".per_area_id" box-title="省级"><?php if(isset($keyList['allow'][2])) {$areaName = array_slice($keyList['allow'][2], -1);$areaName_v= arr::get($areaName, 0, '');echo $areaName_v;}else{$areaName_v='';echo '不限';}?></a>
                        <input type="hidden" value="" class="per_area_id" name="per_area_id">
                     </div>
                    </li>
                    <li>意向行业：<select name="parent_id"><option value="">不限</option>
                      <?php if(isset($keyList['allow'][6])) { $testv=$keyList['allow'][6];}else{$testv=array();}
                      $primarylist = common::primaryIndustry(0); foreach ($primarylist as $k=>$v):?>
                            <option value="<?=$v->industry_id;?>" <?php if(arr::get($postlist,'parent_id')==$v || in_array($v->industry_name,$testv)): echo 'selected="selected"';endif;?> ><?=$v->industry_name;?></option>
                            <?php endforeach; ?>
                     </select></li>
                    <li>投资能力：<select name="per_amount"><option value="">不限</option>
                    <?php $money = arr::get($allTag, 'atype', 0);$moneylist = common::moneyArr(); foreach ($moneylist as $k=>$v): ?>
                                    <option  value="<?=$k;?>" <?php if(arr::get($postlist,'per_amount')==$k || $money==$k): echo 'selected="selected"';endif;?>><?=$v;?></option>
                    <?php endforeach; ?>
                     </select></li>
                    </ul>
                    <input type="submit"  value="" class="select_btn" />
                    </form>
                  </div>
                  
                  <!-- 以下搜索的列表信息 -->
                  <div class="invester_cont_list">
                     <ul>
					<?php $monarr= common::moneyArr(); $ii=0;foreach($personlist as $v){ $ii++;?>
                     <li <?php if($ii%2==1){echo 'class="right"';}?>>
                         <dl>
                          <!-- 头像处理开始 -->
                         <?php 
       					 $perphoto1=URL::imgurl($v['per_photo']);
                         if($loginStatus){//已经登录
                         	if($user_type==1){//企业登录
                         		if($perphoto1 != URL::imgurl('')){//有存在的图片
                         			$per_photostatus = 1; //获取头像状态，用在发信弹出层
                         			echo '<dt class="invester_img"><p class="img"><a title="'.$v['per_realname'].'" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$v['per_user_id'].'"><img alt="投资者'.$v['per_realname'].'" src="'.$perphoto1.'"></a></p></dt>';
                         		}else{//没有头像
                         			if($v['per_gender']==1){//男
                         				$per_photostatus = 2;
                         				echo '<dt class="invester_man"><a title="'.$v['per_realname'].'" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$v['per_user_id'].'"></a></dt>';
                         			}else{
                         				$per_photostatus = 3;
                         				echo '<dt class="invester_woman"><a title="'.$v['per_realname'].'" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$v['per_user_id'].'"></a></dt>';
                         			}
                         		}
                         		//企业登录头像处理结束
                         	}else{//个人用户登录
                         		if($perphoto1 != URL::imgurl('')){//有存在的图片
                         			$per_photostatus = 1; //获取头像状态，用在发信弹出层
                         			echo '<dt class="invester_img"><p class="img"><a  class="viewperinfo" type="2" href="javascript:void(0)"><img alt="投资者'.$v['per_realname'].'" src="'.$perphoto1.'"></a></p></dt>';
                         		}else{//没有头像
                         			if($v['per_gender']==1){//男
                         				$per_photostatus = 2;
                         				echo '<dt class="invester_man"><a class="viewperinfo" type="2" href="javascript:void(0)"></a></dt>';
                         			}else{
                         				$per_photostatus = 3;
                         				echo '<dt class="invester_woman"><a  class="viewperinfo" type="2" href="javascript:void(0)"></a></dt>';
                         			}
                         		}
                         	}
                         	//个人登录头像处理结束
                         }else{//未登录
                         	if($perphoto1 != URL::imgurl('')){//有存在的图片
                         		$per_photostatus = 1; //获取头像状态，用在发信弹出层
                         		echo '<dt class="invester_img"><p class="img"><a class="person_search_login" href="javascript:void(0)"><img alt="投资者'.$v['per_realname'].'" src="'.$perphoto1.'"></a></p></dt>';
                         	}else{//没有头像
                         		if($v['per_gender']==1){//男
                         			$per_photostatus = 2;
                         			echo '<dt class="invester_man"><a class="person_search_login"  href="javascript:void(0)"></a></dt>';
                         		}else{
                         			$per_photostatus = 3;
                         			echo '<dt class="invester_woman"><a  class="person_search_login"  href="javascript:void(0)"></a></dt>';
                         		}
                         	}
                         }
                         //未登录头像处理结束
						?>
						<!-- 头像处理结束 -->
                         <dd><?php echo $v['last_logintime'];?>登录</dd>
                         </dl>
                         <div class="invester_infor">
                            <p class="invester_infor_name">
                            	<!-- 姓名处理开始 -->
                            	<?php 
	                            	if($loginStatus){//已经登录
	                            		if($user_type==1){?>
	                            			<a target='_blank' href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['per_user_id'];?>"><?php echo mb_substr($v['per_realname'],0,6,'UTF-8');?></a>
	                            	<?php }else{?>
	                            			<a  href="javascript:void(0)" class="viewperinfo" type="2"><?php echo mb_substr($v['per_realname'],0,6,'UTF-8');?></a>
	                            	<?php }
	                            	}else{?>
	                            		<a  href="javascript:void(0)" class="person_search_login"><?php echo mb_substr($v['per_realname'],0,6,'UTF-8');?></a>
	                            <?php }?>	
                            	<!-- 姓名处理结束 -->	                            
	                            <?php if($v['isMobile']){?>
	                            <span class="checked">已验证</span>
	                            <?php }else{?>
								<span>未验证</span>
	                            <?php }?>
                                <!-- 金jinspan  银yinspan 铜tongspan -->
                                <!-- 给标志项目发送名片 结果标志开始 -->
                                <?php 
                                	if( time()>$setting['end_time'] && isset($specific_users) && is_array($specific_users)){
                                		if(array_key_exists($v['per_user_id'], $specific_users)){
                                			echo "<i class='jypspan ".$setting['medal_class'][$specific_users[$v['per_user_id']]]."'></i>";
                                		}
                                	}
                                ?>
                                <!-- 给标志项目发送名片 结果标志开始 结束-->
                            </p>
                            <p class="invester_infor_active"><?php echo $v['this_huoyuedu']; if(isset($v['per_shop_status']) && $v['per_shop_status']){echo '<span class="shopno">店铺</span>';}?></p>
                            <p class="invester_infor_choose"><?if($v['this_per_industry']){foreach($v['this_per_industry'] as $keyIarr => $valIarr){?> <?php if($valIarr){?><a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('inid'=>$keyIarr));?>"><?=$valIarr?>加盟</a>&nbsp;<?}else{echo '无';}}}else{echo "无";}?>，<?if(arr::get($v, 'per_amount', 0) != 0) {?><a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('atype'=>arr::get($v, 'per_amount', 0)));?>"><?php $monarr= common::moneyArr(); echo  arr::get($v, 'per_amount') == 0 ? '无': $monarr[arr::get($v, 'per_amount')];?></a><?}else{echo "无";}?><?php if($v['this_per_area']){echo '，';}?><?php 
                            if($v['this_per_area'] && mb_strlen($v['this_per_area'])>4){
                            	echo mb_substr($v['this_per_area'],0,4,'UTF-8').'...';
                            }
                            else{
                            	echo $v['this_per_area'];
                            }              
                            ?></p>                           
                            <p class="invester_infor_btn">
                            <!-- 发信判断开始 -->                          	
								<?php if($loginStatus){
								    if($user_type==1){//企业登录?>
											<?php if(isset($v['card_type']) && $v['card_type']==3){//已交换 ?>
									        <span><a href="javascript:void(0)" class="gray">已发信</a></span>
									
									         <?php }elseif (isset($v['card_type']) && $v['card_type']==2){//递出的名片?>
									         <?php if(time()-(604800+$v['sendtime'])>0){ //7天后又可以重复递出?>
									         <a href="javascript:void(0)"  class="orange send_letter" id="letter_<?php echo $v['per_user_id'];?>_1_<?php echo $v['card_id'];?>_1" perinfo="<?php echo URL::imgurl($v['per_photo']);?>|<?php echo mb_substr($v['per_realname'],0,3,'UTF-8');?>|<?php echo $v['this_per_area'] ;?>|<?if($v['this_per_industry']){foreach($v['this_per_industry'] as $keyIarr => $valIarr){echo $valIarr;}}else{echo "";}?>|<?php echo $v["per_amount"]== 0 ? '无': $monarr[$v["per_amount"]];?>|<?php echo $v['per_gender'];?>|<?php echo $per_photostatus;?>|<?php echo $v['this_huoyuedu'];?>" rel="nofollow" >发信</a>
									         <a id="resendcard_<?php echo $v['card_id'];?>_<?php echo $v['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($v['per_realname'],0,4,'utf-8') ?></a>
									          <?php }else{?>
									         <span><a href="javascript:void(0)" class="gray">已发信</a></span>
									         <?php }?>
									
									         <?php }elseif (isset($v['card_type']) && $v['card_type']==1){//收到的名片?>
									         <a href="javascript:void(0)" class="orange send_letter" id="letter_<?php echo $v['per_user_id'];?>_2_<?php echo $v['card_id'];?>_1" perinfo="<?php echo URL::imgurl($v['per_photo']);?>|<?php echo mb_substr($v['per_realname'],0,3,'UTF-8');?>|<?php echo $v['this_per_area'] ;?>|<?if($v['this_per_industry']){foreach($v['this_per_industry'] as $keyIarr => $valIarr){echo $valIarr;}}else{echo "";}?>|<?php echo $v["per_amount"]== 0 ? '无': $monarr[$v["per_amount"]];?>|<?php echo $v['per_gender'];?>|<?php echo $per_photostatus;?>|<?php echo $v['this_huoyuedu'];?>" rel="nofollow" >发信</a>
									         <a id="exchangecard_<?php echo $v['card_id'];?>_<?php echo $v['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($v['per_realname'],0,4,'utf-8') ?></a>
									         <?php }else{?>
									         <a href="javascript:void(0)" class="orange send_letter" id="letter_<?php echo $v['per_user_id'];?>_3_0_1" perinfo="<?php echo URL::imgurl($v['per_photo']);?>|<?php echo mb_substr($v['per_realname'],0,3,'UTF-8');?>|<?php echo $v['this_per_area'] ;?>|<?if($v['this_per_industry']){foreach($v['this_per_industry'] as $keyIarr => $valIarr){echo $valIarr;}}else{echo "";}?>|<?php echo $v["per_amount"]== 0 ? '无': $monarr[$v["per_amount"]];?>|<?php echo $v['per_gender'];?>|<?php echo $per_photostatus;?>|<?php echo $v['this_huoyuedu'];?>" rel="nofollow" >发信</a>
									         <a id="exchangecard_<?php echo  $v['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($v['per_realname'],0,4,'utf-8') ?></a>
						               <?php }?>		
								    <?php }else{//个人登录?>
								        	<a href="javascript:void(0)" class="viewperinfo orange"  type="0" rel="nofollow">发信</a>
								 <?php }
								}else{//未登录?>
								    	<a href="javascript:void(0)" class="person_search_login orange" rel="nofollow" >发信</a>
								<?php }?>
		                    			
                            <!-- 发信判断结束 -->
                            <!-- 马上联系开始 -->
                            <?php if($loginStatus){//登录过
                            		if($user_type==1){//企业登录
                            			if(isset($v['is_pay']) && !$v['is_pay']){?>
                            				<a href="javascript:void(0)"   id="getonecard_<?php echo $v['per_user_id'].'_0';?>" class="viewcard orange" rel="nofollow">马上联系</a>
                            			<?php }else{?>
                            				<a href="javascript:void(0)"  id="getonecard_<?php echo $v['per_user_id'].'_0';?>" class="viewcard orange" rel="nofollow">马上联系</a>
                            			<?php }
                            		}else{//个人登录?>
                            			<a href="javascript:void(0)"   id="getonecard_<?php echo $v['per_user_id'].'_0';?>" class="viewperinfo orange" type="1" rel="nofollow">马上联系</a>
                            		<?php }?>
                            	
                            <?php }else{//未登录?>
                            	<a href="javascript:void(0)"   id="getonecard_<?php echo $v['per_user_id'].'_0';?>" class="person_search_login orange" rel="nofollow">马上联系</a>
                            <?php }?>
                            <!-- 马上联系结束 -->
                       
                            <?php if($loginStatus){
                            	if($user_type==1){//企业登录?>
                            			<a target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid=<?php echo $v['per_user_id']?>" class="gray" rel="nofollow">查看详情</a>
                            <?php }else{//个人登录?>
                            			<a  href="javascript:void(0)" class="viewperinfo gray" type="2" rel="nofollow">查看详情</a>
                            <?php }
                            }else{//未登录 ?>
                            	<a  href="javascript:void(0)" class="person_search_login gray" rel="nofollow">查看详情</a>
                            <?php }?>                 
                            </p>
                            <div class="clear"></div>
                         </div>
                         <div class="clear"></div>
                     </li>
					<?php }?>
                     </ul>
                     <div class="clear"></div>
                     <!--翻页-->
                    <div class="ryl_search_result_page">
                        <?=$page;?>
                    </div>
                     <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                </div>
                <!--右侧-->
                <div class="find_invester_cont_right">
                  <div class="invester_cont_right_title">
                  <span><?php if($loginStatus && $user_type==1){
                                                  echo '他们收藏您的项目';
                                               }else{
												echo '您可能感兴趣的人';
											}?></span>
                  <!--<span></span>-->
                  </div>
                  <ul class="imgSelfAdaption">
                  
                  <?php foreach($tuijian_personlist as $tuijianv){  ?>
                  <li class="first">
                    <span>
                    <!-- 推荐头像处理开始 -->
	                    <?php $perphoto=URL::imgurl($tuijianv['per_photo']);
	                    if($loginStatus){
	                    	if(isset($user_type) && $user_type==1){//企业登录
	                    		if($perphoto != URL::imgurl('')){//有存在的图片
									$tui_photostatus = 1; //获取头像状态，用在发信弹出层
									echo '<p class="img"><span><a target="_blank" title="'.$tuijianv['per_realname'].'" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$tuijianv['per_user_id'].'"><img alt="投资者'.mb_substr($tuijianv['per_realname'],0,5,'UTF-8').'" src="'.$perphoto.'"></a></span></p>';
								}else{//没有头像
									if($tuijianv['per_gender']==1){//男
										$tui_photostatus = 2;
										echo '<a class="invester_man" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$tuijianv['per_user_id'].'"></a>';
									}else{
										$tui_photostatus = 3;
										echo '<a class="invester_woman" target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$tuijianv['per_user_id'].'"></a>';
									}
								}
							//推荐企业登录处理结束
	                    	}else{//个人登录
	                    		if($perphoto != URL::imgurl('')){//有存在的图片
									$tui_photostatus = 1; //获取头像状态，用在发信弹出层
									echo '<p class="img"><span><a  target="_blank" title="'.$tuijianv['per_realname'].'" class="viewperinfo"  type="2" href="javascript:void(0)"><img alt="投资者'.mb_substr($tuijianv['per_realname'],0,5,'UTF-8').'" src="'.$perphoto.'"></a></span></p>';
								}else{//没有头像
									if($tuijianv['per_gender']==1){//男
										$tui_photostatus = 2;
										echo '<a class="viewperinfo invester_man"  type="2"   href="javascript:void(0)"></a>';
									}else{
										$tui_photostatus = 3;
										echo '<a class="viewperinfo invester_woman"  type="2"  href="javascript:void(0)"></a>';
									}
								}
	                    	}
	                    	//推荐个人登录处理结束
	                    }else{//未登录
							   if($perphoto != URL::imgurl('')){//有存在的图片
									$tui_photostatus = 1; //获取头像状态，用在发信弹出层
									echo '<p class="img"><span><a title="'.$tuijianv['per_realname'].'" class="person_search_login" href="javascript:void(0)"><img alt="投资者'.mb_substr($tuijianv['per_realname'],0,5,'UTF-8').'" src="'.$perphoto.'"></a></span></p>';
								}else{//没有头像
									if($tuijianv['per_gender']==1){//男
										$tui_photostatus = 2;
										echo '<a class="person_search_login invester_man"  href="javascript:void(0)"></a>';
									}else{
										$tui_photostatus = 3;
										echo '<a class="person_search_login invester_woman"  href="javascript:void(0)"></a>';
									}
								}//推荐未登录用户处理结束
						 }
	                    ?>
	                  <!-- 推荐头像处理结束 -->
                    </span>
                    <p class="invester_r_name clearfix">
                    <!-- 推荐名字处理开始 -->
                    <?php 
                    	if($loginStatus){
							if($user_type==1){//推荐企业登录
								echo '<a target="_blank" href="/platform/SearchInvestor/showInvestorProfile?userid='.$tuijianv['per_user_id'].'">'.mb_substr($tuijianv['per_realname'],0,5,'UTF-8').'</a>';
							}else{//推荐个人登录
								echo '<a class="viewperinfo" href="javascript:void(0)" type="2">'.mb_substr($tuijianv['per_realname'],0,5,'UTF-8').'</a>';
							}
                    		
                    	}else{//推荐未登录
                    		echo '<a class="person_search_login" href="javascript:void(0)">'.mb_substr($tuijianv['per_realname'],0,5,'UTF-8').'</a>';
                    	}
                    ?>
                    <!-- 推荐名字处理结束 -->                
                    <em><?php
	                    if( $tuijianv['this_per_area'] && mb_strlen($tuijianv['this_per_area'])>5){
	                    	echo mb_substr($tuijianv['this_per_area'],0,5,'UTF-8').'...';
	                    }
	                    else{
	                    	echo $tuijianv['this_per_area'];
	                    }
                    ?></em>
                    <!-- 金jinspan  银yinspan 铜tongspan -->
                    <!-- 给标志项目发送名片 结果标志开始 -->
                    <?php 
	                    if( time()>$setting['end_time'] && isset($specific_users) && is_array($specific_users)){
		                    if(array_key_exists($tuijianv['per_user_id'], $specific_users)){
		                   		echo "<i class='jypspan ".$setting['medal_class'][$specific_users[$tuijianv['per_user_id']]]."'></i>";
		                    }
		                }
	                ?>
                    <!-- 给标志项目发送名片 结果标志开始 结束-->
                    </p>                    
                    <p class="invester_r_active"><?php echo $tuijianv['this_huoyuedu'];?></p>
                    <?php if(isset($tuijianv['per_shop_status']) && $tuijianv['per_shop_status']){echo '<p><span class="shopno" style="margin-left:0">店铺</span></p>';}?>
                    <p class="invester_r_choose"><?if($tuijianv['this_per_industry']){foreach($tuijianv['this_per_industry'] as $keyIarr => $valIarr){?><a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('inid'=>$keyIarr));?>"><?=$valIarr?></a>&nbsp;<?}}else{echo "无";}?>，<?if(arr::get($v, 'per_amount', 0) != 0) {?><a target="_blank" href="<?php echo urlbuilder:: fenleiCond(array('pid'=>0,'inid'=>0,'atype'=>0,'pmodel'=>0),array('atype'=>arr::get($v, 'per_amount', 0)));?>"><?php $monarr= common::moneyArr(); echo  arr::get($v, 'per_amount') == 0 ? '无': $monarr[arr::get($v, 'per_amount')];?></a><?}else{echo "无";}?></p>
                    
                    <p class="invester_r_btn"><!-- <a href="#" class="first orange">发信</a>-->
                    <!--<a href="#" class="first gray">已发信</a>-->
                    <!-- 推荐发信开始 -->
					 <?php if($loginStatus){
					    if($user_type==1){//企业登录?>
								<?php if(isset($tuijianv['card_type']) && $tuijianv['card_type']==3){//已交换 ?>
						        <a href="javascript:void(0)" class="first gray">已发信</a>
						
						         <?php }elseif (isset($tuijianv['card_type']) && $tuijianv['card_type']==2){//递出的名片?>
						         <?php if(time()-(604800+$tuijianv['sendtime'])>0){ //7天后又可以重复递出?>
						         <a href="javascript:void(0)"  class="first orange send_letter" id="letter_<?php echo $tuijianv['per_user_id'];?>_1_<?php echo $tuijianv['card_id'];?>_2" perinfo="<?php echo URL::imgurl($tuijianv['per_photo']);?>|<?php echo mb_substr($tuijianv['per_realname'],0,3,'UTF-8');?>|<?php echo $tuijianv['this_per_area'] ;?>|<?if($tuijianv['this_per_industry']){foreach($tuijianv['this_per_industry'] as $keyIarr => $valIarr){echo $valIarr;}}else{echo "";}?>|<?php echo $tuijianv["per_amount"]== 0 ? '无': $monarr[$tuijianv["per_amount"]];?>|<?php echo $tuijianv['per_gender'];?>|<?php echo $tui_photostatus;?>|<?php echo $tuijianv['this_huoyuedu'];?>" >发信</a>
						         <a id="resendcard_<?php echo $tuijianv['card_id'];?>_<?php echo $tuijianv['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($tuijianv['per_realname'],0,4,'utf-8') ?></a>
						          <?php }else{?>
						         <a href="javascript:void(0)" class="first gray">已发信</a>
						         <?php }?>
						
						         <?php }elseif (isset($tuijianv['card_type']) && $tuijianv['card_type']==1){//收到的名片?>
						         <a href="javascript:void(0)" class="first orange send_letter" id="letter_<?php echo $tuijianv['per_user_id'];?>_2_<?php echo $tuijianv['card_id'];?>_2" perinfo="<?php echo URL::imgurl($tuijianv['per_photo']);?>|<?php echo mb_substr($tuijianv['per_realname'],0,3,'UTF-8');?>|<?php echo $tuijianv['this_per_area'] ;?>|<?if($tuijianv['this_per_industry']){foreach($tuijianv['this_per_industry'] as $keyIarr => $valIarr){echo $valIarr;}}else{echo "";}?>|<?php echo $tuijianv["per_amount"]== 0 ? '无': $monarr[$tuijianv["per_amount"]];?>|<?php echo $tuijianv['per_gender'];?>|<?php echo $tui_photostatus;?>|<?php echo $tuijianv['this_huoyuedu'];?>">发信</a>
						         <a id="exchangecard_<?php echo $tuijianv['card_id'];?>_<?php echo $tuijianv['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($tuijianv['per_realname'],0,4,'utf-8') ?></a>
						         <?php }else{?>
						         <a href="javascript:void(0)" class="first orange send_letter" id="letter_<?php echo $tuijianv['per_user_id'];?>_3_0_2" perinfo="<?php echo URL::imgurl($tuijianv['per_photo']);?>|<?php echo mb_substr($tuijianv['per_realname'],0,3,'UTF-8');?>|<?php echo $tuijianv['this_per_area'] ;?>|<?if($tuijianv['this_per_industry']){foreach($tuijianv['this_per_industry'] as $keyIarr => $valIarr){echo $valIarr;}}else{echo "";}?>|<?php echo $tuijianv["per_amount"]== 0 ? '无': $monarr[$tuijianv["per_amount"]];?>|<?php echo $tuijianv['per_gender'];?>|<?php echo $tui_photostatus;?>|<?php echo $tuijianv['this_huoyuedu'];?>" >发信</a>
						         <a id="exchangecard_<?php echo  $tuijianv['per_user_id'];?>_name" style="display:none"><?php echo mb_substr($tuijianv['per_realname'],0,4,'utf-8') ?></a>
						         <?php }?>	
					    <?php }else{//个人登录?>
					        	<a href="javascript:void(0)" class="viewperinfo first orange" type="0" rel="nofollow">发信</a>
					    <?php }
					}else{//未登录?>
					    <a href="javascript:void(0)" class="person_search_login first orange" rel="nofollow" >发信</a>
					<?php }?>
					
					<!-- 推荐发信结束 -->
					<!-- 推荐马上联系开始 -->
					   <?php if($loginStatus){//登录过
                            	if($user_type==1){//企业登录
                            			if(isset($tuijianv['is_pay']) && !$tuijianv['is_pay']){?>
                            				<a href="javascript:void(0)"   id="getonecard_<?php echo $tuijianv['per_user_id'].'_0';?>" class="viewcard first orange">马上联系</a>
                            			<?php }else{?>
                            				<a href="javascript:void(0)"  id="getonecard_<?php echo $tuijianv['per_user_id'].'_0';?>" class="viewcard first orange">马上联系</a>
                            			<?php }
                            	}else{//个人登录?>
                            			<a href="javascript:void(0)"   id="getonecard_<?php echo $tuijianv['per_user_id'].'_0';?>" class="viewperinfo first orange" type="1">马上联系</a>
                            		<?php }?>                            	
                       <?php }else{//未登录?>
                            	<a href="javascript:void(0)"   id="getonecard_<?php echo $tuijianv['per_user_id'].'_0';?>" class="person_search_login first orange">马上联系</a>
                      <?php } ?>
                    <!-- 推荐联系结束 -->					 
                    <!-- <a href="javascript:void(0)"  class="viewcard orange" id="getonecard_<?php echo  $tuijianv['per_user_id'].'_0';?>">马上联系</a>-->
                    </p>
                    <div class="clear"></div>
                  </li>
                  <?php }?>
                  
                  </ul>
                  <div class="clear"></div>
                </div>
                <div class="clear"></div>
             </div>
          
          </div>
          <div class="clear"></div>
       </div>
       <div class="map_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>
<!--查看名片开始-->
<div id="getcards_view">
    <div id="getcards_view_repeat">
        <a href="#" class="close">关闭</a>
        <div id="getcards_view_content">

      </div>
    </div>
</div>
<!--查看名片结束-->
<!--从业经验弹出框开始-->
<div class="my_cyjy_view_box" style="z-index:99999">
    <a href="#" class="close">关闭</a>
    <h3>从业经验</h3>
    <div id="cyjy_content">
    </div>
</div>
<!--从业经验弹出框结束-->
<!--批量收藏名片开始-->
<div id="getcards_savebox">
    <a href="#" class="close">关闭</a>
    <div class="text">
        <p></p>
    </div>
</div>
<!--批量收藏名片结束-->
<!--交换名片开始-->
<div id="getcards_change">
    <a href="#" class="close">关闭</a>
    <div class="getcards_change_suc">
    </div>
</div>
<!--交换名片结束-->
<a id="mycardtype" style="display:none">3</a>
<!--名片服务提醒框开始-->
<div id="getcards_deletebox">
    <a href="#" class="close">关闭</a>
    <div class="text" style="background:none;padding-left:0px;text-align: left;color: #000;">
        <p></p>
        <p id="this_content2" style="width:270px; margin:0 auto;"><a href="#" class="ensure"><img src="<?php echo URL::webstatic("/images/getcards/ensure1.jpg") ?>" /></a>
           <a href="#" class="cancel"><img src="<?php echo URL::webstatic("/images/getcards/cancel1.jpg") ?>" /></a>
        </p>
        <input id="getcards_deletebox_hid" type="hidden" value="0"></input>
    </div>
</div>
<!--名片服务提醒框结束-->
<input type="hidden" id="words_id" value="<?php if($wordShow){echo $wordShow; }else{ echo '';}?>">
<!-- 个人用户点击马上联系弹出层开始 -->
<!-- 企业用户没有项目也是在这里显示 -->
<div style="z-index: 999; display: none;" id="send_box">
    <a class="close" href="#">关闭</a>
    <div class="btn" id="msgcontent">
    <p class="errorbox">只有企业用户才能查看详情，赶紧注册企业用户吧！
    </p></div>
</div>
<!--弹出层-->
<div id="error_box_renling" class="tan_floor" style="position:fixed;top:50%;left:50%;margin:-144px 0 0 -327px;display:none;z-index:9999">
    <input type="hidden" id="get_type" value="<?php echo $user_type;?>" qiye_value="<?php echo $ishasproject;?>" />
    <a href="#" class="tan_floor_close"><img src="<?php echo URL::webstatic('images/platform/login_new/icon_close.jpg')?>" /></a>
    <div class="clear"></div>
    <span id="error_box_renling_content" style="display:block;padding-top:90px;"></span>
    <p></p>
</div>
<!-- 个人用户点击马上联系弹出层结束-->
<!-- 发信弹出层开始 -->
<div class="message_box" id="letter_box" style=" width:640px; margin-left:-321px; margin-top:-309px;">
  <dl>
    <dt>
      <input type="hidden"  id="text_output_judge" value="2"/>
      <font>向“<em id="letter_name" style="font-style:normal;">张先生</em>”递送名片并留言吧</font>
      <a class="close" href="#" title="关闭"></a>
    </dt>
    <dd>
      <div class="invester_deliver_shadow"></div>
      <div class="invester_deliver_cont">
   
      <!-- 个人资料显示区 -->
      <div class="invester_deliver_cont_l" id="per_info_letter"></div>
      <form>
      <div class="invester_deliver_cont_r">
          <div class="invester_fc_r_title" >自定义信件内容</div>
          <!-- 模板信件内容 -->
          <div id="modify_box">
            <div class="invester_fc_letter01">
              <input name="template" type="radio" value="0" checked="checked"/>
              <p>
                <span id="tem_content"></span>
                <a href="javascript:void(0)" id="tem_modify">修改</a>
              </p>
              <div class="clear"></div>
            </div>
          </div>
          <!-- 精选信件内容 -->
          <div class="invester_fc_r_title">精选信件内容</div>
          <ul>
            <li><input name="template" type="radio" value="1" /><p><span>想你的钱生钱吗？让我们满足您的愿望吧！</span></p></li>
            <li><input name="template" type="radio" value="2" /><p><span>刚毕业找不到满意的工作，投资做生意吧！买车买房，并不是那不遥远！</span></p></li>
            <li><input name="template" type="radio" value="3" /><p><span>有权有钱是人们一生的追求，您还在等什么呢？</span></p></li>
            <li><input name="template" type="radio" value="4" /><p><span>有钱有时间的您，想坐等收钱环游世界吗？</span></p></li>
            <li><input name="template" type="radio" value="5" /><p><span>想让家人过的更舒适吗？让小投资大回报帮您实现愿望吧！</span></p></li>
          </ul>
          <p class="invester_fc_change"><a href="#"><!-- 换一批 --></a></p>
          <p class="btn" id="submit_box">
            <a href="javascript:void(0)" class="ok">确定</a>
            <a href="javascript:void(0)" class="btn_cancle cancel">取消</a>
          </p>
          <div class="clear"></div>
      </div>
      </form>
      <div class="clear"></div>
    </div>
    </dd>
  </dl>
</div>
<!-- 发信弹出层结束 -->
<?php echo URL::webjs("getcards.js")?>