<!--右侧开始-->
<?php echo URL::webjs("jquery.validate.js");?>
<div id="right">
  <div id="right_top"> <span>发布项目新闻</span>
    <div class="clear"></div>
  </div>
  <div id="right_con">
    <form id="project_news_release">
      <ul class="project_news_release"><input type="hidden" id="edit_zixun_id" value="<?php if( isset( $rs_article ) ){ echo $rs_article['article_id']; }else{ echo '0'; }?>">
        <li class="label"><font>* </font>关联项目：</li>
        <li class="content">
          <select id="relevance_project" class="validate required" name="sel_project_id" <?php if( isset( $rs_article ) ){?>disabled="disabled"<?php }?> onchange="getFreeTgNum( this.value )">
            <OPTION value=''>请选择关联的项目</OPTION>
            <?php
                if( !empty( $all_project) ){
                     foreach( $all_project as $vs_all ){
            ?>

            <option value="<?php echo $vs_all->project_id?>" <?php if( isset( $rs_project_article ) && $rs_project_article['project_id']==$vs_all->project_id ){?>selected="selected"<?php }?> <?php if( $project_id==$vs_all->project_id ){?>selected="selected"<?php }?> ><?php echo $vs_all->project_brand_name?></option>

            <?php }}?>
          </select>
          <font id="can_free_fb_num_id" class="can_free_fb_num_id"></font> <span class="info">请选择关联项目</span> </li>
        <li class="label"><font>* </font>标题：</li>
        <li class="content">
          <input type="text" class="max_width validate required" id="news_title" name="article_name" value="<?php if( isset( $rs_article ) ){ echo $rs_article['article_name']; }?>"/>
          <span class="info">请输入标题</span> </li>
        <li class="label"><font>* </font>内容：</li>
        <li class="content"> <?php echo Editor::factory($txt,"zixun",array("field_name"=>"article_content","width"=>"510","height"=>"300"));?> <span class="info" id="article_content_id">请输入内容</span> </li>
        <li class="label">标签：</li>
        <li class="content">
          <input type="text" class="max_width" id="news_tags" placeholder="选择文章关键字作为标签，有利于文章搜索推荐。标签之间以逗号隔开。" value="<?php if( isset( $rs_article ) ){ echo $rs_article['article_tag']; }?>" name="article_tag"/>
        </li>
        <li class="label"></li>
        <li class="content">
          <input type="submit" class="red" value="提  交"/>
        </li>
        <div class="clear"></div>
      </ul>
    </form>
  </div>
</div>
<div id="getcards_opacity"></div>
<div id="project_news_box_confirm2_3" class="message_box" style=" margin-top:-115px; ">
    <dl>
        <dt>
            <font>生意街提示您</font>
            <a class="close" href="javascript:void(0)" onclick="close_window('project_news_box_confirm2_3')" title="关闭"></a>
        </dt>
        <dd>
            <p>免费发布项目新闻篇数已超限，继续发布需从账户余额中支付<font class="red">300</font>元。确认继续此操作？</p>
        </dd>
        <dd class="btn">
            <a href="javascript:void(0)" onclick="kq_fabu_zixun()" class="ok">确定</a>
            <a href="javascript:void(0)" onclick="close_window('project_news_box_confirm2_3')" class="cancel">取消</a>
        </dd>
    </dl>
</div>
<div id="project_news_box_confirm2_x" class="message_box" style=" margin-top:-115px; ">
    <dl>
        <dt>
            <font>生意街提示您</font>
            <a class="close" href="javascript:void(0)" onclick="close_window('project_news_box_confirm2_x')" title="关闭"></a>
        </dt>
        <dd>
            <p>您将从账户余额中支付300元。确认继续此操作？</p>
        </dd>
        <dd class="btn">
            <a href="javascript:void(0)" onclick="kq_fabu_zixun()" class="ok">确定</a>
            <a href="javascript:void(0)" onclick="close_window('project_news_box_confirm2_x')" class="cancel">取消</a>
        </dd>
    </dl>
</div>
<div id="project_news_box_confirm2_2" class="message_box" style=" margin-top:-115px; ">
    <dl>
        <dt>
            <font>生意街提示您</font>
            <a class="close" href="javascript:void(0)" onclick="close_window('project_news_box_confirm2_2')" title="关闭"></a>
        </dt>
        <dd>
            <p>免费发布项目新闻篇数已经用完，继续发布将扣费。如想免费发布更多，请查看<a class="blue" href="<?php echo URL::website('/company/member/account/platformAccountAbout')?>">充值服务</a>。 </p>
        </dd>
        <dd class="btn">
            <a href="javascript:void(0)" onclick="open_kq()" class="ok">继续发布</a>
            <a href="<?php echo URL::website('/company/member/account/accountindex')?>" class="cancel">去充值</a>
        </dd>
    </dl>
</div>
<div id="project_news_box_confirm2_1" class="message_box" style=" margin-top:-115px; ">
    <dl>
        <dt>
            <font>生意街提示您</font>
            <a class="close" href="javascript:void(0)" onclick="close_window('project_news_box_confirm2_1')" title="关闭"></a>
        </dt>
        <dd>
            <p>您的免费发布项目新闻篇数已经用完。<br />
    申请成为招商通会员，即可免费发布更多项目新闻。<br />
    详情请查看<a class="blue" href="<?php echo URL::website('/company/member/account/applyPlatformServiceFee')?>">招商通服务</a>。 </p>
        </dd>
        <dd class="btn">
            <a href="<?php echo URL::website('/company/member/account/applyPlatformServiceFee')?>" onclick="close_window('project_news_box_confirm2_1')" target="_blank" class="ok">申请招商通会员</a>
            <a href="javascript:void(0)" onclick="close_window('project_news_box_confirm2_1')" class="cancel">以后再说</a>
        </dd>
    </dl>
</div>
<div id="project_news_box_confirm2_0" class="message_box" style=" margin-top:-115px; ">
    <dl>
        <dt>
            <font>生意街提示您</font>
            <a class="close" href="javascript:void(0)" onclick="close_window('project_news_box_confirm2_0')" title="关闭"></a>
        </dt>
        <dd>
            <p>您发布的项目新闻已提交。<br/>
    我们会在2个工作日内进行审核。请您耐心等待！ </p>
        </dd>
        <dd class="btn">
            <a href="<?php echo URL::website('/company/member/article/projecttougaolist')?>" class="ok" >我知道了</a>
        </dd>
    </dl>
</div>

<div id="project_news_box_confirm2_s" class="message_box" style=" margin-top:-115px; ">
    <dl>
        <dt>
            <font>生意街提示您</font>
            <a class="close" href="javascript:void(0)" onclick="close_window('project_news_box_confirm2_s')" title="关闭"></a>
        </dt>
        <dd>
            <p class="msg" id="msg_show_id"> </p>
        </dd>
        <dd class="btn">
            <a href="<?php echo URL::website('/company/member/account/accountindex')?>" target="_blank" class="ok" >前去充值</a>
        </dd>
    </dl>
</div>

<script>
function getFreeTgNum( pid ){
    if( pid=='' || pid==null ){
        $("#can_free_fb_num_id").html( '' );
    }else{
        $.ajax({
            type: "post",
            dataType: "json",
            url:$config.siteurl + "company/member/ajaxcheck/getProjuectZixunNum",
            data: "pid="+pid,
            complete :function(){
            },
            success: function(data){
                var type= data['type'];
                if( type=='error' ){
                    alert('error:错误操作');return false;
                }else{
                    var msg= data['msg'];
                    //type= '2';
                    switch( type ){
                        case "0":
                            var shtml= "还可免费发布<em>"+msg+"</em>篇";
                        break;
                        case "1":
                            var shtml= "还可免费发布0篇，想免费发布更多，申请成为<a href='/company/member/account/applyPlatformServiceFee' target='_blank'>招商通会员</a>。";
                        break;
                        case "2":
                            var shtml= "还可免费发布0篇，想免费发布更多，详看<a href='/company/member/account/platformAccountAbout' target='_blank'>充值及服务</a>。";
                        break;
                        case "3":
                            var shtml= "免费发布篇数已超过限制，继续发布将收费，详看<a href='/company/member/account/platformAccountAbout' target='_blank'>充值及服务</a>。";
                        break;

                    }

                    $("#can_free_fb_num_id").html( shtml );
                }
            }
        });
    }

}
</script>

<!-- 发布信息表单验证 -->
<script type="text/javascript">
$("#project_news_release").submit(function(){
   
        var article_content = editor.html();
        var article_id= Number( $("#edit_zixun_id").val() );
        if( article_id==0 || article_id==null ){
            //add
            //验证用户投稿资格
            $.ajax({
                type: "post",
                dataType: "json",
                url:$config.siteurl + "company/member/ajaxcheck/getUserAllAccountAndFreeTgNum",
                data: "pid="+$("#relevance_project").val()+"&article_name="+$("#news_title").val()+"&article_content="+article_content+"&article_tag="+$("#news_tags").val(),
                complete :function(){
                },
                success: function(msg){
                    if( msg!='error' ){
                        //打开浮层
                        $("#project_news_box_confirm2_"+msg).slideDown("500",function(){
                            $("#getcards_opacity").show();
                        })
                    }
                }
            });

        }else{
            //edit
            $.ajax({
                type: "post",
                dataType: "json",
                url:$config.siteurl + "company/member/ajaxcheck/editprojectzixun",
                data: "pid="+$("#relevance_project").val()+"&article_name="+$("#news_title").val()+"&article_content="+article_content+"&article_tag="+$("#news_tags").val()+"&article_id="+article_id,
                complete :function(){
                },
                success: function(msg){
                    if( msg!='error' ){
                        //打开浮层
                        $("#project_news_box_confirm2_"+msg).slideDown("500",function(){
                            $("#getcards_opacity").show();
                        })
                    }
                }
            });

        }
        return false;

    
});
// $("#project_news_release").validate({
//     onkeyup:false,
//     submitHandler:function(form){
//         var article_content= editor.html();
//         if(article_content==""){
//             $("#article_content_id").show();
//         }
//         else{
//             $("#article_content_id").hide();
//         }
//         //return false;
//     }
// })

//继续投稿，保存为草稿
function setArticle( tgs,tgr ){
    
    $.ajax({
        type: "post",
        dataType: "json",
        url:$config.siteurl + "company/member/ajaxcheck/setArticleSave",
        data: "pid="+$("#relevance_project").val()+"&article_name="+$("#news_title").val()+"&article_content="+article_content+"&article_tag="+$("#news_tags").val()+"&tgs="+tgs+"&tgr="+tgr,
        complete :function(){
        },
        success: function(msg){
            //跳转列表
            window.location.href='/company/member/article/projecttougaolist';
        }
    });

}

function open_kq(){
    //打开扣钱提示
    $(".project_news_box").css("display","none");
    $("#project_news_box_confirm2_x").css("display","block");
}

//关闭弹出
function close_window( id ){
    $("#"+id).slideUp("500",function(){
        $("#getcards_opacity").hide();
    })
}

//扣钱发布
function kq_fabu_zixun(){
    $.ajax({
        type: "post",
        dataType: "json",
        url:$config.siteurl + "company/member/ajaxcheck/kouqian",
        data: "title="+$("#news_title").val(),
        complete :function(){
        },
        success: function(data){
            var msg= data['msg'];
            var type= data['type'];
            if( msg=='ok' ){
                //扣钱成功 发布
                setArticle(1,1);
            }else{
                if( type=='2' ){
                    $("#msg_show_id").html( msg );
                    $("#project_news_box_confirm2_s").css("display","block");
                }else{
                    alert(msg);
                }
            }
        }
    });

}
</script>
<!-- 发布信息表单验证 END -->
