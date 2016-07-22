<?php echo URL::webcss("platform/lottery.css?version=4")?>
<?php echo URL::webcss("cj3.css")?>
<?php echo URL::webjs("lottery2.js")?>
<div class="cjbanner"></div>
   <div class="xiabiao"></div>
   <div class="cjcenterbox">
     <div class="cjcenter">
       <h2>第三期活动近期开展，敬请随时关注！</h2>
     <h3 class="jpyl">奖品预览</h3>
     <ul class="clearfix jp">
       <li><img src="<?php echo URL::webstatic('/images/cj3/img.png')?>"><p>mini Ipad</p></li>
       <li class="ml9"><img src="<?php echo URL::webstatic('/images/cj3/img4.png')?>"><p>iPod</p></li>
       <li><img src="<?php echo URL::webstatic('/images/cj3/img1.png')?>"><p>瑞士十字电脑包</p></li>
       <li class="ml9"><img src="<?php echo URL::webstatic('/images/cj3/img2.png')?>"><p>U盘（8G）</p></li>
       <li><img src="<?php echo URL::webstatic('/images/cj3/img3.png')?>"><p>20元话费</p></li>
       <li><img src="<?php echo URL::webstatic('/images/cj3/img5.png')?>"><p>100创业币</p></li>
     </ul>
     <div class="text mt30">
       <p class="fz24 ml100">你还在为梦想中的奖品而发愁</p>
       <p class="fz26 ml130">为每天仅有一次的抽奖机会而崩溃吗？</p>
       <p class="fz36 ml190">NO!NO!NO!</p>
       <p class="fz26 ml92">扳指苦等的时代已经过去了，光明大道即将铺展！</p>
       <p class="fz28 ml140">让我们打起土豪，分起土地，轻松坐享土豪金的光辉！</p>
       <p class="fz18 ml86">你可以通过以下方式：</p>
       <p class="fz36 ml134"><strong class="fz36">获取更多抽奖机会，</strong></p>
       <p class="fz28 ml190">让你  <strong class="fz36">多次抽奖毫无压力!!!</strong></p>
       <div class="textfot">
         <p>1.邀请好友加入我们一句话网站平台会员，每邀请 成功注册（指邮箱、手机号码都验证通过）1人，您将获赠 1次 额外的抽奖机会。
</p>
         <p class="mt15">2.给意向投资项目投递名片，您将获赠 1次 额外的抽奖机会（当天重复投递名片，仅获赠1次额外的抽奖机会）</p>
       </div>
     </div>
      <h3 class="zs">历史奖品展示</h3>
      <div class="lottery_prize lottery_winners_info lottery_winners_info1">
          <h4><a href="<?php echo URL::website('/zt2/zhuce.shtml')?>" target="_blank" class="floright">更多中奖信息查看>></a>第二期中奖名单</h4>
          <ul class="clearfix">
            <li>
              <table width="836">
                <tbody>
                  <tr>
                    <th width="120">姓名</th>
                    <th width="172">手机</th>
                    <th width="169">奖品</th>
                    <th width="120">姓名</th>
                    <th width="172">手机</th>
                    <th width="83">奖品</th>
                  </tr>
                </tbody>
              </table>
            </li>
            <li class="lottery_winners_list">
              <ul style="top: -3px;" class="clearfix" id="lottery_winners_data">
	              	<li class="first" style="width: 120px;">潘媛媛</li>
	                <li style="width: 172px;">139****6932</li>
	                <li style="width: 120px;"><font>iPhone5S</font></li>
                <?php if($big_prize_list_2){?>
	              	<?php foreach($big_prize_list_2 as $v){?>
	                <li class="first" style="width: 120px;"><?php echo $v['name'] != '' ? $v['name'] : '佚名';?></li>
	                <li style="width: 172px;"><?php echo $v['mobile'];?></li>
	                <li style="width: 120px;"><font><?php echo $v['prize'];?></font></li>
	                <?php }?>
              	<?php }?>               
              </ul>
            </li>
          </ul>
     </div>
     <div class="lottery_prize lottery_winners_info lottery_winners_info1" style="margin-top: 0;">
          <h4 class="ht15"><a href="<?php echo URL::website('/zt/zhuce.shtml')?>" target="_blank" class="floright">更多中奖信息查看>></a>第一期中奖名单</h4>
          <ul class="clearfix">
            <li>
              <table width="836">
                <tbody>
                  <tr>
                    <th width="120">姓名</th>
                    <th width="172">手机</th>
                    <th width="169">奖品</th>
                    <th width="120">姓名</th>
                    <th width="172">手机</th>
                    <th width="83">奖品</th>
                  </tr>
                </tbody>
              </table>
            </li>
            <li class="lottery_winners_list">
              <ul style="top: -3px;" class="clearfix" id="lottery_winners_data1">
              		<li class="first" style="width: 120px;">刘栋</li>
	                <li style="width: 172px;">139****9476</li>
	                <li style="width: 120px;"><font>iPad</font></li>
                <?php if($big_prize_list_1){?>
	              	<?php foreach($big_prize_list_1 as $v){?>
	                <li class="first" style="width: 120px;"><?php echo $v['name'] != '' ? $v['name'] : '佚名';?></li>
	                <li style="width: 172px;"><?php echo $v['mobile'];?></li>
	                <li style="width: 120px;"><font><?php echo $v['prize'];?></font></li>
	                <?php }?>
              	<?php }?>           	
              </ul>
            </li>
          </ul>
     </div>
   </div>
  </div>
