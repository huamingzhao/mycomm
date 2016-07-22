<?php echo URL::webcss("platform/help.css")?>
<script type="text/javascript">
    $(document).ready(function() {
        var method=$("#actionmethod").html();
        var linkstyle=$(".help_nav_big span a");
        linkstyle.removeClass();
        if(method=='Help_fankui'){
        	$("#"+method+"1").addClass("current");
        }else{
        $("#"+method).addClass("current");
        }
    });
</script>
<div class="main" style="height:auto; background-color:#e3e3e3; padding:13px 0 12px 0;">
   <div class="help_bg">
       <div class="help_bg01"></div>
       <div class="help_bg02">
           <!--帮助中心导航-->
           <div class="help_nav">
              <p class="help_logo"><img src="<?php echo URL::webstatic("images/platform/help_center/help_logo.jpg") ?>" /></p>

              <ul class="help_nav_big">
                  <li>
                  <p>新手上路</p>
                  <span><a id="Help_aboutus" href="<?php echo urlbuilder::help("aboutus");?>">关于一句话</a></span>
                  <span><a id="Help_mianze" href="<?php echo urlbuilder::help("mianze");?>">免责声明</a></span>
                  <span><a id="Help_tese" href="<?php echo urlbuilder::help("tese");?>">网站特色</a></span>
                  <span><a id="Help_xuzhi" href="<?php echo urlbuilder::help('xuzhi');?>">客户须知</a></span>
                  <span><a id="Help_chengxin" href="<?php echo urlbuilder::help('chengxin');?>">诚信与安全</a></span>
                  <span><a id="Help_yinsi" href="<?php echo urlbuilder::help('yinsi');?>">隐私声明</a></span>
                  <span><a id="Help_fankui1" href="<?php echo urlbuilder::help('fankui');?>">用户反馈</a></span>
                  <span><a id="Help_lianxi" href="<?php echo urlbuilder::help('lianxi');?>" >联系方式</a></span>
                  <div class="clear"></div>
                  </li>
                  <li>
                  <p>企业中心</p>
                  <span><a id="Help_qzhuce" href="<?php echo urlbuilder::help('qzhuce');?>">注册与激活</a></span>
                  <span><a id="Help_qmima" href="<?php echo urlbuilder::help('qmima');?>">忘记密码怎么办</a></span>
                  <span><a id="Help_qsoutouzi" href="<?php echo urlbuilder::help('qsoutouzi');?>">如何搜索投资者</a></span>
                 <span><a id="Help_howpbi" href="<?php echo urlbuilder::help('howpbi');?>">如何发布/管理生意信息</a></span>
                  <span><a id="Help_qfabu" href="<?php echo urlbuilder::help('qfabu');?>">如何发布招商项目</a></span>
                 <span><a id="Help_qcanzhan" href="<?php echo urlbuilder::help('qcanzhan');?>">如何参加网络展会</a></span>
                  <span><a id="Help_qzhishuji" href="<?php echo urlbuilder::help('qzhishuji');?>">诚信指数与会员等级</a></span>            
                  <div class="clear"></div>
                  </li>
                  <li>
                  <p>个人中心</p>
                  <span><a id="Help_gzhuce" href="<?php echo urlbuilder::help('gzhuce');?>">注册与激活</a></span>
                  <span><a id="Help_gmima" href="<?php echo urlbuilder::help('gmima');?>">忘记密码怎么办</a></span>
                  <span><a id="Help_gsousuo" href="<?php echo urlbuilder::help('gsousuo');?>">如何搜索项目</a></span>
                  
                   <span><a id="Help_howsearch" href="<?php echo urlbuilder::help('howsearch');?>">如何搜索生意信息</a></span>
                   
                 <span><a id="Help_howpbi2" href="<?php echo urlbuilder::help('howpbi2');?>">如何发布/管理生意信息</a></span>
                  <span><a id="Help_huoyuedu" href="<?php echo urlbuilder::help('huoyuedu');?>">活跃度指数与会员等级</a></span>
                  <div class="clear"></div>
                  </li>
              <div class="clear"></div>
              </ul>
           </div>
           <!--帮助中心内容-->
                <?php echo $help_maincontent;?>
          <div class="clear"></div>
          <a id="actionmethod" style="display:none"><?php echo $actionmethod; ?></a>
       </div>
       <div class="help_bg03"></div>
   <div class="clear"></div>
   </div>
   <div class="clear"></div>
</div>