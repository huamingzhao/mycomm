<div id="right">
	<div id="right_top">
		<span>好友邀请</span>
		<div class="clear"></div>
	</div>
	<div id="right_con">
		<div class="invite_friend">
			<p>

				当前您已邀请 <i><?=$user_count?></i>位好友参与注册，已成功注册（<font>指手机号码验证通过</font>）<i><?=$inviteNum?></i>人

			</p>
			<p>
				当前您已累计获赠<i><?=$addNum?></i>抽奖机会（含递送名片获得），已使用<i><?=abs($minNum)?></i>次，还剩<i><?=$nowNum?></i>次
			</p>
			<p><font class="red">友情提示：</font><font>获赠抽奖机会请在第4期活动（2月26日-4月25日）内使用，超过时间剩余的额外抽奖机会将失效。</font></p>
			<div class="invite_friend_main">
				<p>
					<label id="invite_link_l" for="invite_link">
						<input type="radio" id="invite_link" name="invite_type" checked="checked"/>                
						复制粘贴邀请
					</label>
					<label id="invite_mail_l" for="invite_mail">
						<input type="radio" id="invite_mail" name="invite_type"/>                
						邮箱邀请
					</label>
					<input id="invite_mail_i" type="text" onkeyup="this.value=this.value.replace(/[\u4e00-\u9fa5]/ig, '')" placeholder="请输入好友的邮箱地址"/>
				</p>
				<p class="clearfix">
					<span>邀请语：</span>
					<span>
						<textarea id="copy_data">一句话网会员注册第4期，100%中奖，更有mini iPad、小虫手机、瑞士十字电脑背包、20元话费、10元话费、高达1000万创业币等丰厚奖品等你拿，赶快行动吧！
你还在为抽奖机会太少、丰厚奖品与您擦肩而过发愁么，从现在开始，你就可以狂赚抽奖机会，活动开始后，你可以一次抽个够！第4期活动近期即将展开，敬请随时关注。</textarea>
                                            <a href="#" title="抽奖" id="lottery_link"><?php echo URL::website('/zt4/zhuce.shtml');?>?inviter_user_id=<?=$user_id?>&inviter_type=2</a>
						<button id="invite_link_b">复制</button>
						<button id="invite_mail_b">邀请</button>
					</span>
				</p>
			</div>
			<form action="<?php echo URL::website('/person/member/huodong/showInviteFriends') ?>" method="get" id="firend_type">
			<p>
				我邀请的朋友：
				<select name="type" onchange="$('#firend_type')[0].submit();">
                                    <option value="0" <?if($type == 0) echo 'selected="selected"';?>>全部好友</option>
					<option value="1" <?if($type == 1) echo 'selected="selected"';?>>邮箱邀请</option>
					<option value="2" <?if($type == 2) echo 'selected="selected"';?>>直接复制粘贴邀请</option>
				</select>
			</p>
			</form>
			<table cellspacing="0" cellpadding="0">
				<tbody>
					<tr>
						<th width="298">邮箱邀请（直接复制粘贴邀请）</th>
						<th width="184">截至目前注册情况</th>
						<th width="218">成功注册时间</th>
					</tr>
                                        <?if($allData['list']) {?>
                                        <?foreach($allData['list'] as $val) {
                                        ?>
					<tr>
						<td><?= $val['invite_type'] == 1 ? '邮箱邀请' : '直接复制粘贴邀请';?></td>
						<td><?= $val['userInfo']['valid_mobile'] == 1 ? '成功注册' : '手机未验证';?></td>
						<td><?=date('Y-m-d H:i:s' ,arr::get($val['userInfo'], 'reg_time'))?></td>
					</tr>
					
                                        <?}}?>
				</tbody>
			</table>
			<div class="page-effect">
				<?=$allData['page']?>
			</div>
		</div>
	</div>
</div>
<?php echo URL::webjs("invite_friend.js")?>