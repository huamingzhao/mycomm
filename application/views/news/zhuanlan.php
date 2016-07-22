 
 <!-- 中间 -->
          <div class="infor_center ad-pt">
            <div class="ad_caifu_tp">
              <ul></ul>
              <div class="clear"></div>
            </div>
            <div class="infor_newlist" style="padding:0;">
              <ul style="border:none;">
                <!-- 文章循环列表开始 -->
          <?php foreach($list as $k=>$v){
			if($v['count']<5){
                if($v['zl_pic']){?>
              	<!-- 第一种有图片开始 -->            	
                <li>
                  <div class="infor_newlist_right">
                    <a href="<?php echo zxurlbuilder::zhuanlaninfo($v['zl_id']);?>" title="<?php echo $v['zl_title'];?>">
                      <img width="150" height="120" src="<?php echo URL::imgurl($v['zl_pic']);?>" alt="<?php echo $v['zl_title'];?>" />
                    </a>
                  </div>
                  <div class="infor_newlist_left ">
                    <h3>
                      <a target="_blank" href="<?php echo zxurlbuilder::zhuanlaninfo($v['zl_id']);?>" title="<?php echo $v['zl_title'];?>"><?php echo mb_substr($v['zl_title'],0,20,'UTF-8');?></a>
                      <?php if($v['zl_tj']){echo '<em></em>';}?>
                    </h3>
                    <label> <b><?php echo date('Y年n月d日 H:i',$v['zl_shtime'])?>更新</b> <em>标签：</em>
                    	<?php if( $v['zl_key']!='' ){$tags_arr=  explode(',', $v['zl_key'] );foreach ( $tags_arr as $k=>$tags_vs ){ if($k>3){break;}; if( $k+1==count($tags_arr) ){ $t=''; }else{ $t=';'; } echo "<a href='".zxurlbuilder::ptag($tags_vs)."'>".$tags_vs.$t."</a>"; } }?>
                    </label>
                    <span class="infor_new_text">
                    	<?php echo UTF8::substr(zixun::setContentReplace($v['zl_introduce']), 0,85)?><?php if( UTF8::strlen( zixun::setContentReplace($v['zl_introduce']) )>100 ){?>...<?php }?>
                    </span>
                    <div class="clear"></div>
                  </div>
                  <ul class="infor_newlist_right_other">
                  	<?php 
                  		for($i=0;$i<4;$i++){
                  			if(isset($v[$i]) && $v[$i]){
                  				echo '<li><a href="'.zxurlbuilder::zixuninfo($v[$i]['zixun_id'],date("Ym",$v[$i]['zixun_time'])).'" title="'.$v[$i]['zixun_title'].'">'.mb_substr($v[$i]['zixun_title'],0,12,'UTF-8').'</a></li>';
                  			}else{
                  				break;
                  			}
                  		}
                  	?>
                    <li class="more"><a href="<?php echo zxurlbuilder::zhuanlaninfo($v['zl_id']);?>" title="更多">更多 》</a></li>
                    <div class="clear"></div>
                  </ul>
                  <div class="clear"></div>
                </li>
                <?php }else{?>
                <!-- 第一种没图片开始 -->
                <li class="no_img">
                  <div class="infor_newlist_left ">
                    <h3>
                      <a target="_blank" href="<?php echo zxurlbuilder::zhuanlaninfo($v['zl_id']);?>" title="<?php echo $v['zl_title'];?>"><?php echo mb_substr($v['zl_title'],0,20,'UTF-8');?></a>
                      <?php if($v['zl_tj']){echo '<em></em>';}?>	
                    </h3>
                    <label> <b><?php echo date('Y年n月d日 H:i',$v['zl_shtime'])?>更新</b> <em>标签：</em>
                    	<?php if( $v['zl_key']!='' ){$tags_arr=  explode(',', $v['zl_key'] );foreach ( $tags_arr as $k=>$tags_vs ){ if($k>6){break;}; if( $k+1==count($tags_arr) ){ $t=''; }else{ $t=';'; } echo "<a href='".zxurlbuilder::ptag($tags_vs)."'>".$tags_vs.$t."</a>"; } }?>
                    </label>
                    <span class="infor_new_text">
                      <?php echo UTF8::substr(zixun::setContentReplace($v['zl_introduce']), 0,85)?><?php if( UTF8::strlen( zixun::setContentReplace($v['zl_introduce']) )>100 ){?>...<?php }?>
                    </span>
                    <div class="clear"></div>
                  </div>
                  <ul class="infor_newlist_right_other">
                    <?php 
                  		for($i=0;$i<4;$i++){
                  			if(isset($v[$i]) && $v[$i]){
                  				echo '<li><a href="'.zxurlbuilder::zixuninfo($v[$i]['zixun_id'],date("Ym",$v[$i]['zixun_time'])).'" title="'.$v[$i]['zixun_title'].'">'.mb_substr($v[$i]['zixun_title'],0,12,'UTF-8').'</a></li>';
                  			}else{
                  				break;
                  			}
                  		}
                  	?>
                    <li class="more"><a href="<?php echo zxurlbuilder::zhuanlaninfo($v['zl_id']);?>" title="更多">更多 》</a></li>
                    <div class="clear"></div>
                  </ul>
                  <div class="clear"></div>
                </li>
                <!-- 循环1结束 -->
                <!-- 循环2开始 -->
                <?php }}else{
                	if($v['zl_pic']){
                ?> 
                <!-- 第2种有图片开始 -->
                <li class="infor_newlist_zl2">
                  <h3>
                    <a target="_blank" href="<?php echo zxurlbuilder::zhuanlaninfo($v['zl_id']);?>" title="<?php echo $v['zl_title'];?>"><?php echo mb_substr($v['zl_title'],0,20,'UTF-8');?></a>
                  	<?php if($v['zl_tj']){echo '<em></em>';}?>
                  </h3>
                  <label> <b><?php echo date('Y年n月d日 H:i',$v['zl_shtime'])?>更新</b> <em>标签：</em>
                  	<?php if( $v['zl_key']!='' ){$tags_arr=  explode(',', $v['zl_key'] );foreach ( $tags_arr as $k=>$tags_vs ){if($k>6){break;}; if( $k+1==count($tags_arr) ){ $t=''; }else{ $t=';'; } echo "<a href='".zxurlbuilder::tag($tags_vs)."'>".$tags_vs.$t."</a>"; } }?>
                  </label>
                  <div class="infor_newlist_right">
                    <a href="<?php echo zxurlbuilder::zhuanlaninfo($v['zl_id']);?>" title="<?php echo $v['zl_title'];?>">
                      <img width="80" height="65" src="<?php echo URL::imgurl($v['zl_pic']);?>" alt="<?php echo $v['zl_title'];?>" />
                    </a>
                  </div>
                  <div class="infor_newlist_left ">
                    <span class="infor_new_text">
                    	<?php echo UTF8::substr(zixun::setContentReplace($v['zl_introduce']), 0,100)?><?php if( UTF8::strlen( zixun::setContentReplace($v['zl_introduce']) )>100 ){?>...<?php }?>
                    </span>
                    <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                </li>
                <?php }else{?>
                <!-- 第2种没图片开始 -->
                <li class="infor_newlist_zl2 no_img">
                  <h3>
                    <a href="<?php echo zxurlbuilder::zhuanlaninfo($v['zl_id']);?>" title="<?php echo $v['zl_title'];?>"><?php echo mb_substr($v['zl_title'],0,20,'UTF-8');?></a>
                  	<?php if($v['zl_tj']){echo '<em></em>';}?>
                  </h3>
                  <label> <b><?php echo date('Y年n月d日 H:i',$v['zl_shtime'])?>更新</b> <em>标签：</em>
                  	<?php if( $v['zl_key']!='' ){$tags_arr=  explode(',', $v['zl_key'] );foreach ( $tags_arr as $k=>$tags_vs ){ if($k>6){break;};if( $k+1==count($tags_arr) ){ $t=''; }else{ $t=';'; } echo "<a href='".zxurlbuilder::tag($tags_vs)."'>".$tags_vs.$t."</a>"; } }?>
                  </label>
                  <div class="infor_newlist_left ">
                    <span class="infor_new_text">
                      	<?php echo UTF8::substr(zixun::setContentReplace($v['zl_introduce']), 0,100)?><?php if( UTF8::strlen( zixun::setContentReplace($v['zl_introduce']) )>100 ){?>...<?php }?>
                    </span>
                    <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                </li>
              <?php }}}?> 
              </ul>
            </div>
            <div class="ryl_search_result_page">
				<?php echo $page;?>
            </div>
            <div class="clear"></div>
          </div>
          <!-- 中间 END -->     