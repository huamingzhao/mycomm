<style>
.integrity table td span {display: inline-block;}
</style>
  <div class="help_cont">
              <p class="help_title">总活跃度指数与会员等级<em>ACTIVITY INDEX & MEMBERSHIP LEVELS</em></p>
              <div class="help_center_right" style="margin:0;">
              <div class="integrity yhydsm">
                        <h3>月活跃度说明： </h3>
						<h5>1、月活跃度概念及作用：</h5>
                        <p class="ts sm">
						月活跃度是衡量投资者最近一个月在一句话平台的行为频率高低的指标，不同的行为就会累积不同的分值（具体行为与分值见活跃度指数来源），月活跃度指数就是最近一个月行为分值的累积。
						</p>
						<p class="ts sm last">
						投资者月活跃度越高，越容易被企业在搜索投资者时优先搜索显示，促进投资者和项目建立最佳联系，既让投资者找到最适合的项目，又让企业找到最适合的投资者。
						</p>
						<h5>2、月活跃度时间统计范围：</h5>
						<p class="ts sm">
						当天的前一天逆推一个自然月，例如当天是7月9日，当天前一天逆推一个自然月就是6月8日，那么本月月活跃度时间统计范围就是从6月8日0点0时0分0秒到7月8日23点59分59秒截止。
						</p>
                        <h3>会员等级</h3><a name="level"></a>
                        <p class="ts">会员的总活跃度等级根据会员的总活跃度指数进行升级，会员的累积指数和等级之间的对应关系见下表格：  </p>
                        <table id="user_grade" class="user_grade_cxy">
                            <tr>
                                <th>等级</th>
                                <th>升级所需分数</th>
                                <th>累计指数分值</th>
                                <th>等级显示</th>
                            </tr>
                            <tr>
                                <td>1级</td>
                                <td><span>0</span></td>
                                <td><span>0—99</span></td>
                                <td><img src="<?php echo URL::webstatic("images/integrity/1.png")?>" /></td>
                            </tr>
                            <tr>
                                <td>2级</td>
                                <td><span>100</span></td>
                                <td><span>100—199</span></td>
                                <td><img src="<?php echo URL::webstatic("images/integrity/2.png")?>" /></td>
                            </tr>
                            <tr>
                                <td>3级</td>
                                <td><span>200</span></td>
                                <td><span>200-499</span></td>
                                <td><img src="<?php echo URL::webstatic("images/integrity/3.png")?>" /></td>
                            </tr>
                            <tr>
                                <td>4级</td>
                                <td><span>500</span></td>
                                <td><span>500-999</span></td>
                                <td><img src="<?php echo URL::webstatic("images/integrity/4.png")?>" /></td>
                            </tr>
                            <tr>
                                <td>5级</td>
                                <td><span>1000</span></td>
                                <td><span>1000-1699</span></td>
                                <td><img src="<?php echo URL::webstatic("images/integrity/5.png")?>" /></td>
                            </tr>
                            <tr>
                                <td>6级</td>
                                <td><span>1700</span></td>
                                <td><span>1700-2599</span></td>
                                <td><img src="<?php echo URL::webstatic("images/integrity/6.png")?>" /></td>
                            </tr>
                            <tr>
                                <td>7级</td>
                                <td><span>2600</span></td>
                                <td><span>2600-3699</span></td>
                                <td><img src="<?php echo URL::webstatic("images/integrity/7.png")?>" /></td>
                            </tr>
                            <tr>
                                <td>8级</td>
                                <td><span>3700</span></td>
                                <td><span>3700-4999</span></td>
                                <td><img src="<?php echo URL::webstatic("images/integrity/8.png")?>" /></td>
                            </tr>
                            <tr>
                                <td>9级</td>
                                <td><span>5000</span></td>
                                <td><span>5000-6499</span></td>
                                <td><img src="<?php echo URL::webstatic("images/integrity/9.png")?>" /></td>
                            </tr>
                            <tr>
                                <td>10级</td>
                                <td><span>6500</span></td>
                                <td><span>6500-8199</span></td>
                                <td><img src="<?php echo URL::webstatic("images/integrity/10.png")?>" /></td>
                            </tr>
                            <tr>
                                <td>……</td>
                                <td><span>100*（n-2）^2+100<br />
n为等级</span></td>
                                <td><span>……</span></td>
                                <td><font>……</font></td>
                            </tr>
                        </table>
                        <h3>活跃度指数来源   </h3>
                        <p class="ts">会员的活跃度指数来源于以下行为： </p>
                        <table >
                            <tr>
                                <th>行为</th>
                                <th>获取分数</th>
                                <th>备注</th>
                            </tr>
                            <tr>
                                <td>注册</td>
                                <td><span>100</span>分</td>
                                <td>一次性</td>
                            </tr>
                            <tr>
                                <td>邮箱认证</td>
                                <td><span>50</span>分</td>
                                <td>一次性</td>
                            </tr>
                            <tr>
                                <td>手机号码认证</td>
                                <td><span>100</span>分</td>
                                <td>一次性</td>
                            </tr>
                            <tr>
                                <td class="other_table">
									<table>
										<tr>
											<td rowspan="3" style="border:none;">完善信息</td>
											<td style="border-top:none;border-right:none;">基本信息</td>
										</tr>
										<tr>
											<td style="border-top:none;border-right:none;">意向投资</td>
										</tr>
										<tr>
											<td style="border-top:none;border-right:none; border-bottom:none;">从业经验</td>
										</tr>
									</table>
								</td>
								<td colspan="2" class="other_table">
									<table>
										<tr>
											<td style="border-left:none; border-top:none;"><span>20</span>分</td>
											<td style="border-right:none; border-top:none;">一次性</td>
										</tr>
										<tr>
											<td style="border-left:none; border-top:none;"><span>20</span>分</td>
											<td style="border-right:none;"">一次性</td>
										</tr>
										<tr>
											<td style="border-left:none; border-top:none; border-bottom:none;"><span>20</span>分</td>
											<td style="border-right:none; border-bottom:none;">一次性</td>
										</tr>
									</table>
								</td>
                            </tr>
                            <tr>
                                <td>登录</td>
                                <td><span>5</span>分</td>
                                <td>日上限为10分</td>
                            </tr>
                            <tr>
                                <td>查看项目</td>
                                <td><span>2</span>分</td>
                                <td>日上限为10分（不同项目）</td>
                            </tr>
                            <tr>
                                <td>递送名片（我要咨询、 索要资料，发送意向）</td>
                                <td><span>10</span>分</td>
                                <td>日上限为50分</td>
                            </tr>
                            <tr>
                                <td>报名投资考察会</td>
                                <td><span>10</span>分</td>
                                <td>日上限为50分</td>
                            </tr>
                            <tr>
                                <td>收藏项目</td>
                                <td><span>3</span>分</td>
                                <td>日上限为15分</td>
                            </tr>
                            <tr>
                                <td>投稿（资讯）</td>
                                <td><span>20</span>分</td>
                                <td>日上限为20分</td>
                            </tr>
                            <tr>
                                <td>分享项目（资讯）</td>
                                <td><span>5</span>分</td>
                                <td>日上限为20分</td>
                            </tr>
                        </table>
                        <div class="cantact">
                            <p><b>客服联系方式：</b></p>
                            <p><span class="tel">电话：<b><?php $arrphone=common::getCustomerPhone();echo $arrphone['1']; ?></b></span><span class="email">邮箱：kefu@yjh.com</span></p>
                            <p>一句话网站保留对该规则的修改权和解释权</p>
                        </div>
                    </div>
             </div>
           </div>