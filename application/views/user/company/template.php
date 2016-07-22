<?php echo URL::webcss("common_header.css")?>
<?php echo URL::webcss("platform/index0514.css")?>
<?php echo URL::webcss("browse_record.css");?>
<?php echo URL::webcss("common.css")?>
<?php echo URL::webjs("jquery.cookies.2.2.0.js")?>
<script type="text/javascript">
$(document).ready(function() {
    $(".returnbtn").click(function(){
            window.history.go(-1)
        })
    var method=$("#actionmethod").html();    
    var linkstyle=$("#left_content dl dd");
    linkstyle.removeClass();
    if(method=="Basic_editCompany" || method=="Basic_editcompany"){
        $("#Basic_company").addClass("select");
     }else if(method=="Project_projectinfo" || method=="Project_updateproject" || method=="Project_addproimg" || method=="Project_addprocertsimg" || method=="Project_addproplaybill" || method=="Project_proinvestment" || method=="Project_viewProInvestment" || method=="Project_addproinvestment" || method=="Project_addpropublish"){
         $("#Project_showproject").addClass("select");
     }else if(method=="Basic_uploadCertification"){
        $("#Basic_comCertification").addClass("select");
     }else if(method=="Card_completecard" || method=="Card_cardstyle"){
         $("#Card_mycard").addClass("select");
     }else if(method=="Valid_mobile"){
         $("#Safe_index").addClass("select");
     }
     else if(method == "Card_receivecard"){
         $("#receivedcardcount").addClass("select");
     }
     else if(method == "Account_accountlist"){
         $("#Account_accountindex").addClass("select");
     }
     else if(method == "Project_addInvest"){
         $("#Project_addinvest").addClass("select");
     }
     else if(method == "Card_outcard"){
         $("#outcardcount").addClass("select");
     }else if(method =='Project_searchProjectRenling'){
         $("#Project_showprojectrenling").addClass("select");
     }else if(method == 'Exhb_projectList'){
         $("#Exhb_projectList").addClass("select");
     }else if(method == 'Exhb_showYiXiangList'){
         $("#Exhb_showYiXiangList").addClass("select");
     }else if(method == 'Exhb_showCommunication' || method == 'Exhb_UpdateCommunication'){
         $("#Exhb_ApplyForCommunication").addClass("select");
     }else if(method == 'Card_receiveCard'){
         $("#Card_receiveCard").addClass("select");
     }else{
         $("#"+method).addClass("select");
     }
     try{
        var left_icon = $(".left_icon")[0];
        left_icon.parentNode.removeChild(left_icon);
        $("#"+method)[0].parentNode.appendChild(left_icon);
        left_icon.style.display = "block";
     }
     catch(e){}
});
</script>
<?php echo URL::webjs("message_close.js")?>
<?php echo URL::webjs("header.js")?>
<?php echo URL::webjs("message_close.js")?>
<?php echo URL::webjs("cms/ajaxfileupload.js") ?>
<?php echo URL::webjs("platform/AC_RunActiveContent.js") ?>
<script type="text/javascript">

   function viewImage(_str){

       var obj=new Image();
       obj.src=_str

       obj.onload = function()
       {

           $("#comLogo").attr('src', _str);
           resizeImage(obj)

       }

    }

    function resizeImage(_img)
    {

        var def_w = _img.width
        var def_h =  _img.height
        var scale = def_w/def_h
        var scale_new = 150/112
        var new_w
        var new_h

        if(scale >= scale_new)
        {
            new_w = 150
            new_h = 150/scale

        }else if(scale < scale_new){

            new_h = 112
            new_w = 112*scale

        }

        $("#comLogo").css('width', new_w+'px')
        $("#comLogo").css('height', new_h+'px')
        if(!-[1,])
        {

            $("#comLogo").css('margin-top', (112-new_h)/2 + 5+'px')

        }else{
            $("#comLogo").css('margin-top', (112-new_h)/2+'px')

        }
    }
</script>
<!--中部开始-->
<div id="wrap">
    <div id="wrap_outer">
        <div id="wrap_inner" style="position: relative">
         <?php if($actionmethod !='Basic_index'){?>
               <?php if($actionmethod != 'Project_showGaoshi'){?>
            <a class="returnbtn">返回</a>
            <?php }?>
         <?php }?>
            <div id="wrap_repeat">
                <!--左导航开始-->
                <div class="left" style="height:auto !important;height:650px;min-height:650px;">
                    <div class="back_index"><a href="<?php echo URL::website("company/member/basic")?>" class="com">我的企业中心</a></div>
                    <a id="actionmethod" style="display:none"><?php echo $actionmethod;?></a>
                    <div class="nav" id="left_content">
                        <dl>
                            <i class="left_icon"></i>
                            <dt><span class="zl">资料管理</span></dt>
                                 <dd id="Basic_company"><a href="<?php echo URL::website('/company/member/basic/company')?>">基本信息</a>
                                 </dd>
                                 <dd id="Safe_index"><a href="<?php echo URL::website('/company/member/safe')?>">安全中心</a>
                                 </dd>
                                 <dd id="Basic_modifypassword"><a href="<?php echo URL::website('company/member/basic/modifypassword')?>">修改密码</a>
                                 </dd>
                        </dl>
                          <dl>
                            <dt><span class="mp">名片管理</span></dt>
                                <dd id="Card_mycard"><a href="<?php echo URL::website('/company/member/card/mycard')?>">我的名片</a>
                                </dd>
                                <dd id="receivedcardcount"><a id="Card_receivecard" href="<?php echo URL::website('/company/member/card/receivecard')?>">我收到的</a>
                                  <?php if($receivecard_count) {?>
                                        <span class="left_new_icon"><sub></sub><b><?php echo $receivecard_count?></b>条</span>
                                   <?php } ?>
                                </dd>
                                <dd id="outcardcount"><a id="Card_outcard" href="<?php echo URL::website('/company/member/card/outcard')?>">我递出的</a>
                                     <?php if($exchangecard_count) {?>
                                        <span class="left_new_icon"><sub></sub><b><?php echo $exchangecard_count?></b>条</span>
                                   <?php } ?>
                                </dd>
                                <dd id="Card_alreadyviewcard"><a  href="<?php echo URL::website('/company/member/card/alreadyviewcard')?>">我查看的</a>
                                </dd>
                                <dd id="Card_favorite"><a  href="<?php echo URL::website('/company/member/card/favorite')?>">我收藏的</a>
                                </dd>


                        </dl>
                         <dl>
                            <dt><span class="xm">项目管理</span></dt>
                               <dd id="Project_showproject"><a href="<?php if($project_count > 0){
                                        echo URL::website('/company/member/project/showproject');
                                     }else{
                                        echo URL::website('/company/member/project/addproject');
                                     } ?>">我的项目</a>
                                </dd>
                                <dd id="Project_addproject">
                                <a href="<?php echo URL::website('/company/member/project/addproject');?>">发布项目</a>
                                </dd>
                                <dd id="Project_showprojectrenling">
                                 <!-- <a href="<?php echo URL::website('/company/member/project/showprojectrenling')?>">认领项目</a> -->
                                </dd>
                                <dd id="Project_myinvestment">
                                 <!-- <a href="<?php echo URL::website('/company/member/project/myinvestment')?>">我的投资考察会</a> -->
                                </dd>

                                <dd id="Project_addinvest">
                                <!-- <a href="<?php echo URL::website('/company/member/project/addinvest');?>">发布投资考察会</a> -->
                                </dd>
                                <dd id="Article_projectArticle">
                                <a href="<?php echo URL::website('/company/member/article/projectArticle');?>">项目新闻</a>
                                </dd>
                        </dl>
                        <?php /*?>
                        <dl>
                            <dt><span class="cz">参展项目管理</span></dt>
                            <dd id="Exhb_projectList">
                                <a href="<?php echo URL::website('/company/member/exhb/projectList');?>">我的参展项目</a>
                              </dd>
                              <dd id="Exhb_showYiXiangList">
                                <a href="<?php echo URL::website('/company/member/exhb/showYiXiangList');?>">意向加盟</a>
                              </dd>
                              <dd id="Exhb_ApplyForCommunication">
                                <a href="<?php echo URL::website('/company/member/exhb/ApplyForCommunication');?>">申请在线沟通服务</a>
                              </dd>
                        </dl>
                        <?php */?>
                        <dl>
                            <dt><span class="fw">我的服务</span></dt>
                                <!-- <dd id="Investor_search"><a href="<?php echo URL::website('/company/member/investor/search').$search_url ;?>">找投资者</a> -->
                                </dd>
                                <?php /*?>
                                <dd id="Merchants_applyBusiness"><a href="<?php echo URL::website('/company/member/merchants/applyBusiness')?>">申请招商外包服务</a>
                               <?php */?>
                                </dd>
                                <!-- 消息提示<dd id="Account_servicelist"><a href="<?php echo URL::website('/company/member/account/servicelist')?>">已有服务</a></dd> -->
                                <dd id="Guard_index"><a href="<?php echo URL::website('/company/member/guard')?>">投资保障</a></dd>
                        </dl>
                        <dl>
                            <dt><span class="zh">账户中心</span></dt>
                            <dd id="Account_accountindex"><a href="<?php echo URL::website('/company/member/account/accountindex')?>">我的账户</a></dd>
                            <!-- <dd id="Account_applyPlatformServiceFee"><a href="<?php echo URL::website('company/member/account/applyPlatformServiceFee')?>">申请招商通会员</a></dd> -->
                        </dl>
                        <dl>
                            <dt><span class="zx">资讯管理</span></dt>
                            <dd id="Article_favorite"><a href="<?php echo URL::website('/company/member/article/favorite')?>">我收藏的文章</a></dd>
                            <dd id="Article_getApplyArticle"><a href="<?php echo URL::website('/company/member/article/getApplyArticle')?>">我投稿的文章</a></dd>
                        </dl>
                        <?php /*?>
                        <dl>
                            <dt><span class="mp">收到的咨询</span></dt>
                            <dd id="Card_receiveCard"><a href="<?php echo URL::website('/company/quick/card/receiveCard')?>">留言地址</a>
                           	</dd>
                        </dl>
                        <? */?>
                    </div>

                    <div   id="message_box">
                        <p><span></span></p>
                        <div id="sort_count_msg"></div>
                    </div>
                    <!-- 消息提示 -->
                </div>
                <!--左导航结束-->
                <!--主体部分开始--><?=$rightcontent?><!--主体部分结束-->
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<!--中部结束-->
