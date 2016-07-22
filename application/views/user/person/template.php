<?php echo URL::webcss("common_header.css")?>
<?php echo URL::webcss("platform/index0514.css")?>
<?php echo URL::webcss("browse_record.css");?>
<?php echo URL::webcss("common.css") ?>
<?php echo URL::webjs("jquery.cookies.2.2.0.js")?>
<script type="text/javascript">
    $(document).ready(function() {
            $(".returnbtn").click(function(){
                window.history.go(-1)
            })
        var method=$("#actionmethod").html();
        var linkstyle=$("#left_content dl dd a");
        linkstyle.removeClass();
        if(method=="Basic_personupdate" || method=="Basic_personInvestShow" || method=="Basic_experience"){
            $("#Basic_person").addClass("select");
        }else if(method=="Card_cardStyle"){
            $("#Card_mycard").addClass("select");
        }else if(method=="Valid_mobile"){
            $("#Safe_index").addClass("select");
        }else if(method == "Card_receivecard"){
            $("#receivedcardcount").addClass("select");
        }
        else if(method=="Card_outcard"){
            $("#outcardcount").addClass("select");
        }else if(method=="Card_historyconsult"){
            $("#History_consult").addClass("select");
        }else if(method=="Medal_index"){
            $("#my_medal").addClass("select");
        }else if(method == 'Card_historyConsult'){
            $("#Card_historyConsult").addClass("select");
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
<?php echo URL::webjs("header.js") ?>
<?php echo URL::webjs("message_close.js") ?>
<?php echo URL::webjs("cms/ajaxfileupload.js") ?>
<?php echo URL::webjs("platform/AC_RunActiveContent.js") ?>
<script type="text/javascript">
function viewImage(_str){

       var obj=new Image();
       obj.src=_str

       obj.onload = function()
       {

           $("#perLogo").attr('src', _str);
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

        $("#perLogo").css('width', new_w+'px')
        $("#perLogo").css('height', new_h+'px')
        if(!-[1,])
        {

            $("#perLogo").css('margin-top', (112-new_h)/2 + 5+'px')

        }else{
            $("#perLogo").css('margin-top', (112-new_h)/2+'px')

        }
    }

        function ajaxFileUpload(){

            $.ajaxFileUpload
            (
            {
                url:'/person/member/ajaxcheck/uploadPerLogo',//用于文件上传的服务器端请求地址
                secureuri:false,//一般设置为false
                fileElementId:'avatar',//文件上传空间的id属性  <input type="file" id="file" name="file" />
                dataType: 'json',//返回值类型 一般设置为json
                success: function (data, status)  //服务器成功响应处理函数
                {
                    var code = data.code;
                        var msg = data.msg;
                        if(code == '200'){
                            $("#perLogo").attr('src', msg);
                        }else {
                            alert(msg.error);
                        }
                },
                error: function (data)//服务器响应失败处理函数
                {
                   var code = data.code;
                        var msg = data.msg;
                        if(code == '200'){
                            $("#perLogo").attr('src', msg);
                        }else {
                            alert(msg.error);
                        }
                }
            }
        )

            return false;

        }
        function loadLogo(){
            $(function(){
                $(".logo #perLogo").each(function () {
                    DrawImage(this, 144, 136);
                });
            })
        }
        //loadLogo();
function DrawImage(ImgD, FitWidth, FitHeight) {
    var image = new Image();
    image.src = ImgD.src;
    if (image.width > 0 && image.height > 0) {

        if (image.width / image.height >= FitWidth / FitHeight) {
            if (image.width > FitWidth) {
                ImgD.width = FitWidth;
                ImgD.height = (image.height * FitWidth) / image.width;
                ImgD.style.paddingTop = (FitHeight-(image.height * FitWidth) / image.width)/2 + "px"
            } else {
                ImgD.width = image.width;
                ImgD.height = image.height;
                ImgD.style.paddingTop = (FitHeight-image.height)/2 + "px";
                ImgD.style.paddingLeft = (FitWidth-image.width)/2 + "px";
            }
        } else {
            if (image.height > FitHeight) {
                ImgD.height = FitHeight;
                ImgD.width = (image.width * FitHeight) / image.height;
                ImgD.style.paddingLeft = (FitWidth-(image.width * FitHeight) / image.height)/2 + "px";

            } else {
                ImgD.width = image.width;
                ImgD.height = image.height;
                ImgD.style.paddingTop = (FitHeight-image.height)/2 + "px";
                ImgD.style.paddingLeft = (FitWidth-image.width)/2 + "px";
            }
        }
    }
}
</script>
<!--中部开始-->
<div id="wrap">
    <div id="wrap_outer">
        <div id="wrap_inner" style="position: relative">
        <?php if($actionmethod !='Basic_index'){?>
            <a class="returnbtn">返回</a>
        <?php }?>

            <div id="wrap_repeat">
                <i class="left_icon"></i>
                <!--左导航开始-->
                <div class="left">
                    <div class="back_index"><a href="<?php echo URL::website('/person/member') ?>" class="com">个人中心首页</a></div>
                    <a id="actionmethod" style="display:none"><?php echo $actionmethod; ?></a>
                    <div class="nav" id="left_content">
                        <dl>
                            <dt><span>资料管理</span></dt>
                            <dd id="Basic_person"><a href="<?php
                                if ($per_user_id == "ok") {
                                    echo URL::website('/person/member/basic/person');
                                } else {
                                    echo URL::website('/person/member/basic/personupdate');
                                }
                                ?>">基本信息</a>
                            </dd>
                            <!--<dd><a id="Basic_experience" href="<?php echo URL::website('/person/member/basic/experience') ?>">从业经验</a>
                            </dd>-->

                            <!-- 未绑定的帐号 不显示 -->
                            <?php if( $pefo===true ){?>
                                <dd id="Safe_index"><a href="<?php echo URL::website('/person/member/safe') ?>">安全中心</a></dd>
                                <dd id="Basic_modifypassword"><a href="<?php echo URL::website('person/member/basic/modifypassword') ?>">修改密码</a></dd>
                            <?php }?>

                            <?php if( $oauth_eof===true ){?>
                                <dd id="Basic_modifypassword"><a href="<?php echo URL::website('person/member/basic/oauthjc') ?>">解除绑定</a></dd>
                            <?php }?>


                        </dl>
                        <dl>
                            <dt><span class="mp">名片管理</span></dt>
                            <dd id="Card_mycard"><a href="<?php echo URL::website('/person/member/card/mycard') ?>">我的名片</a></dd>
                            <dd id="receivedcardcount"><a id="Card_receivecard" href="<?php echo URL::website('/person/member/card/receivecard') ?>">我收到的</a>
                                <?php if ($receivecard_count) { ?>
                                    <span class="left_new_icon"><sub></sub><b><?php echo $receivecard_count ?></b>条</span>
                                <?php } ?>
                            </dd>
                            <dd id="History_consult"><a href="<?php echo URL::website('/person/member/card/historyconsult') ?>">我的历史咨询</a>
                            </dd>
                        </dl>
                        <dl>
                            <dt><span class="fw">我的应用</span></dt>
                            <dd id="Project_searchProject"><a href="<?php echo URL::website('/xiangdao/') ?>">找项目</a>
                            </dd>
                            <dd id="Project_watchProject"><a href="<?php echo URL::website('person/member/project/watchProject') ?>">我收藏的项目</a>
                            </dd>
                            <!-- 
                            <dd id="Invest_searchInvest"><a href="<?php echo URL::website('person/member/invest/searchInvest') ?>">找投资考察会</a>
                            </dd>                             
                            <dd id="Invest_myInvest"><a href="<?php echo URL::website('person/member/invest/myInvest') ?>">我报名的投资考察会</a>
                            </dd>    
                            -->
                            <?php /*?>      
                            <dd id="Exhb_myHongBao"><a href="<?php echo URL::website('person/member/exhb/myHongBao') ?>">我的红包</a>
                            
                            </dd> 
                            <?php */?>
                                                         
                        </dl>
                        <dl>
                            <dt><span class="zh">资讯管理</span></dt>
                            <dd id="Article_getFavoriteArticle"><a href="<?php echo URL::website('person/member/article/getFavoriteArticle') ?>">我收藏的文章</a>
                            </dd>
                            <dd id="Article_getApplyArticle"><a href="<?php echo URL::website('person/member/article/getApplyArticle') ?>">我投稿的文章</a>
                            </dd>
                        </dl>
                        <!-- 
                        <dl>
                            <dt><span class="zh">抽奖管理</span></dt>
                            <dd id="Huodong_mygame"><a href="<?php echo URL::website('/person/member/huodong/mygame') ?>">抽奖记录</a>
                            </dd>
                            <?php /*?>
                            <dd id="Huodong_exchangeChuangYeBi"><a href="<?php echo URL::website('/person/member/huodong/exchangeChuangYeBi') ?>">创业币兑换</a>
                            </dd>
                            <?php */?>
                            <dd id="Huodong_showInviteFriends"><a href="<?php echo URL::website('/person/member/huodong/showInviteFriends') ?>">好友邀请</a>
                            </dd>
                        </dl>
                         -->
						<!-- 所获奖励注释掉 暂不上线 -->
                        <!-- 
                        <dl>
                            <dt><span class="medal">所获奖励</span></dt>
                            <dd id="my_medal"><a href="<?php //echo URL::website('/person/member/medal/index') ?>">我的奖励</a></dd>
                            </dd>
                        </dl>
                        -->
                        <?php /*?>
                        <dl>
                            <dt><span class="mp">发出的咨询</span></dt>                                                   
                            <dd id="Card_historyConsult"><a href="<?php echo URL::website('/person/quick/card/historyConsult') ?>">留言地址</a>
                            </dd>
                        </dl>
                        <? */?>
                    </div>
                    <!-- 消息提示 -->
                    <div id="message_box">
                        <p><span></span></p>
                        <div id="sort_count_msg"></div>
                    </div>
                    <!-- 消息提示 -->
                </div>
                <!--左导航结束--><!--主体部分开始--><?= $rightcontent ?><!--主体部分结束-->
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>

<!--弹出框开始-->
<div id="modify_opcity"></div>
<div id="modify_alert"><a href="<?php echo URL::website("/userlogin/logout/");?>" class="close">关闭</a>
    <p> <a href="<?php echo URL::website("/userlogin/logout/");?>" >确定</a></p>
</div>
<!--弹出框结束-->