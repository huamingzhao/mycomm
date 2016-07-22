    <!--右侧开始-->
    <div id="right">
        <div id="right_top"><span>我的活跃度</span><div class="clear"></div></div>
        <div id="right_con">
            <div id="my_points">
                <div class="title"><span>总活跃度指数：<b><?php echo $score_total;?>点</b></span>
                    <span class="dengj">总活跃度等级：
                    <em>
                    <a href="<?php echo urlbuilder::help("huoyuedu");?>" target="_blank">
                    <img src="<?php echo URL::webstatic("images/integrity/".$score_level['level'].".png");?>"></a>
                    </em>
                    <div class="hover">
                        <p>等级：<?php echo $score_level['level'];?>级</p>
                    </div>
                    </span>
					<br />
					<br />
					<span>月活跃度指数：<b><?php echo $score_near_month;?>点</b></span>
                    <span><a href="<?php echo urlbuilder::help("huoyuedu");?>" target="_blank">查看活跃度指数>></a></span>
                    <br />
                    <br />
                    <span>总创业币数：<b><?php echo $chuangyebiCount;?>元</b></span>
                </div>
                <form action="" method="get">
                <div class="select person_itylist" id="select_all">请选择月份：
                <select  name="yearandmonth">             	
                	<?php foreach($month_arr as $key=>$month){?>
                		<option value='<?php echo $key;?>' <?php if($key == arr::get($postlist,'yearandmonth')) echo "selected='selected'"; ?>><?php echo $month;?></option>
                	<?php }?>        	
				</select>
				<select name="type">
                    <option value="1" <?php if($type == 1){?>selected="selected"<?php }?>>活跃度</option>
                    <option value="2" <?php if($type == 2){?>selected="selected"<?php }?>>创业币</option>
                </select>
                <input type="submit" value="查询" />
                </div>
                </form>
                <?php if($type == 1){?>
                <table class="person_itylist_data">
                    <tr>
                        <th>时间</th>
                        <th>活跃度指数类型</th>
                        <th>获取活跃度指数</th>
                    </tr>
                    <?php if(count($score_list)){ ?>
	                    <?php foreach($score_list as $v){?>
							<tr>
								<td><?php  echo date("Y-m-j H:i:s",$v->add_time);?></td>
								<td><b><?php echo points_person_type::getTypeById($v->score_type);?></b></td>
								<td><font>+<?php echo $v->score; ?></font></td>
							</tr>
						<?php }}?>               
                </table>
                <?php }elseif($type == 2){?>
                <table class="person_itylist_data">
                    <tr>
                        <th>时间</th>
                        <th>创业币指数类型</th>
                        <th>获取创业币指数</th>
                    </tr>
                    <?php if(count($score_list)){ ?>
	                    <?php foreach($score_list as $v){?>
							<tr>
								<td><?php  echo $v['date'];?></td>
								<td><b><?php echo getScoretype($v['score_type']); ?></b></td>
								<td><font <?php if(!$v['score_operating']){?>style="color:red;"<?php }?>><?php echo $v['score_operating']?'+':'-';?><?php echo $v['score']; ?></font></td>
							</tr>
						<?php }}?>               
                </table>
                <?php }?>
                <br/><br/><br/>
                <?php echo $page;?>
            </div>
        </div>
    </div>
    <?php 
    	// 获取创业币类型 调用方法
    	function getScoretype($type){
    		$score_array = common::getScoreType();
    		$type = intval($type);
    		if($type && array_key_exists($type, $score_array)){
    			return $score_array[$type];
    		}else{
    			return '其他';
    		}
    	}
    ?>
    <!--右侧结束-->