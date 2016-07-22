<?php echo URL::webcss("/platform/information.css")?>
<?php echo URL::webjs('news/news.js')?>
<!--主体部分-->
<div class="main" style="background-color:#e3e3e3; height:auto;">

  <div class="infor_main">
    <!--菜单-->
    <div class="infor_menu">
    <div id="infor_menu">
        <ul>
            <?php if (isset($currentcolumnid)&&$currentcolumnid=="index"){?>
            <li class="cur"><h1><a class="infor_menu_icon01" href="<?php echo zxurlbuilder::zixun()?>">资讯首页</a></h1></li>
            <?php }else{?>
            <li><a class="infor_menu_icon01" href="<?php echo zxurlbuilder::zixun()?>">资讯首页</a></li>
            <?php }?>

            <?php
            foreach ($column as $k=>$v){
                if (isset($currentcolumnid)&&$currentcolumnid==$v->column_id&&$v->column_id!=2)
                    echo '<li class="cur"><h1>';
                elseif (isset($currentcolumnid)&&$currentcolumnid==$v->column_id&&$v->column_id==2)
                    echo '<li class="cur">';
                else
                    echo '<li>';
                if ($v->column_id==2){
                    echo '<a class="infor_menu_icon0'.($v->column_id+1).'" href="'.zxurlbuilder::column($v->column_name).'">'.$v->column_name.'</a>';
                }else{
                    echo '<a class="infor_menu_icon0'.($v->column_id+1).'" href="'.zxurlbuilder::column($v->column_name).'">'.$v->column_name.'</a>';
                }
                if (isset($currentcolumnid)&&$currentcolumnid==$v->column_id&&$v->column_id!=2)
                    echo '</h1></li>';
                else
                    echo '</li>';
            }
            ?>
        </ul>
    </div>
    </div>
    <!--内容-->
    <div class="infor_content">
      <div class="infor_bg01"></div>
      <div class="infor_bg02">

       <!--投稿-->
       <div class="infor_center contribut">
          <h1>一句话专业资讯投稿</h1>

          <div class="contribut_cont">
             <form method="post" id="article_form">
             <p class="contribut_cont_title">（ <em>*</em> 为必填项）</p>
             <div class="contribut_table">
                <div class="contribut_table_li">
                    <label><em>*</em> 题目：</label>
                    <p><input class="tg_text01" id="text01" name="article_name" type="text" placeholder="请填写您投稿的文章的题目"><ins style="display:none" id="article_name_tishi">* 请填写文章标题</ins></p>
                    <div class="clear"></div>
                </div>
                <div class="contribut_table_li">
                    <label><em>*</em> 栏目：</label>
                    <p>
                    <?php
                    $ncol= array();
                    foreach ( $columns as $v ){
                        if( $v!='项目新闻' ){
                            $ncol[]= $v;
                        }
                    }
                    ?>
                    <?php echo Form::select("parent_id",$ncol,null,array("class"=>"tg_text02","id"=>'parent_col'))?>
                    <ins style="display:none" id="parent_id_tishi">* 请选择栏目</ins></p>
                    <!--<span>子栏目<span id="sub_column"></span></span> -->
                    <div class="clear"></div>
                </p>
                </div>
                <div class="contribut_table_li">
                    <label><em>*</em> 内容：</label>
                    <p><?php echo Editor::factory("请输入文章的内容。","zixun",array("field_name"=>"article_content","width"=>"600","height"=>"300"));?><ins style="display:none" id="article_content_tishi">* 请填写文章内容</ins></p>
                    <div class="clear"></div>
                </div>
                <div class="contribut_table_li">
                    <label>标签：</label>
                    <p><input class="tg_text01" id="text02" name="article_tag" type="text" placeholder="挑选文章关键字作为标签，有利于文章搜索推荐。标签之间以'，'隔开。"></p>
                    <div class="clear"></div>
                </div>
                <!--
                <div class="contribut_table_li">
                    <label>附件：</label>
                    <p>
                    <input class="tg_text03" name="" type="text" value="选择文章关键字作为标签，有利于文章搜索推荐。">
                    <input class="tg_text04" name="" type="button" >
                   <input class="tg_text04" name="" type="file" value="选择文章关键字作为标签，有利于文章搜索推荐。">
                  </p>
                     <div class="clear"></div>
                </div>
                -->
                <div class="clear"></div>
             </div>

             <p class="contribut_cont_title">请认真填写以下信息，以利于一句话网站对您的文章做详尽报道。</p>
             <div class="contribut_table">
                <div class="contribut_table_li">
                    <label><em>*</em> 联系人：</label>
                    <p><input class="tg_text05" name="article_contact" type="text" value="<?=$article_contact?>"><ins style="display:none" id="article_cintact_tishi">* 请填写联系人姓名</ins></p>
                    <div class="clear"></div>
                </div>
                <div class="contribut_table_li">
                    <label><em>*</em> 联系电话：</label>
                    <p><input class="tg_text05" name="article_mobile" type="text" value="<?=$article_mobile?>"><ins style="display:none" id="article_mobile_tishi">* 请填写您的11位手机号码</ins></p>
                    <div class="clear"></div>
                </div>
                <div class="contribut_table_li">
                    <label><em>*</em> 投稿理由：</label>
                    <p><textarea  class="tg_textarea02" name="article_reason" cols="" rows=""></textarea><ins style="display:none" id="article_reason_tishi">* 请填写投稿理由</ins></p>
                    <div class="clear"></div>
                    <input type="hidden" name="token" value='<?php echo Security::token(true);?>'>
                </div>
                <div class="contribut_table_li">
                    <label></label>
                    <p><button class="tg_text06" type="button" id="form_submit"></button></p>
                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
             </div>

             <div class="clear"></div>
          </div>
          </form>
          <div class="clear"></div>
       </div>



      <div class="clear"></div>
      </div>
      <div class="infor_bg03"></div>

     <div class="clear"></div>
    </div>

  <div class="clear"></div>
  </div>
  <div class="clear"></div>
</div>

<script>
var val1 = "请填写您投稿的文章的题目"

var val3 = "挑选文章关键字作为标签，有利于文章搜索推荐。标签之间以'，'隔开。"
    $("#text01").focus(function(){
        if($(this).val() == val1)
        $(this).val("");

    });
    $("#text02").focus(function(){
        if($(this).val() == val3)
        $(this).val("");

    });
    $("#text01").blur(function(){
        if($(this).val() == "")
        $(this).val(val1);

    });
    $("#text02").blur(function(){
        if($(this).val() == "")
        $(this).val(val3);

    });

$(function(){


    $("#form_submit").click(function(){
        //alert(editor.html());
        var article_name = $("input[name=article_name]").val();
        var article_content = editor.html();
        var article_tag = $("input[name=article_tag]").val();
        var parent_id = $("#parent_col option:selected'").val();
        var article_contact = $("input[name=article_contact]").val();
        var article_mobile = $("input[name=article_mobile]").val();
        var article_reason = $('textarea[name="article_reason"]').val();
        if(article_name=="")
        {
            $("#article_name_tishi").show();
            $("input[name=article_name]").focus();
            return false;
        }
        if(parent_id == 0)
        {
            $("#parent_id_tishi").show();
            return false;
        }
        if(article_content=="")
        {
            $("#article_content_tishi").show();
            $("input[name=article_content]").focus();
            return false;
        }
        if(article_contact=="")
        {
            $("#article_contact_tishi").show();
            $("input[name=article_contact]").focus();
            return false;
        }
        if(article_mobile=="")
        {
            $("#article_mobile_tishi").show();
            $("input[name=article_mobile]").focus();
            return false;
        }
        if(article_reason=="")
        {
            $("#article_reason_tishi").show();
            $("input[name=article_reason]").focus();
            return false;
        }
        //判断合法提交
        var token = $("input[name=token]").val();
        var article_id = $("input[name=article_id]").val();
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/zixun/ajaxcheck/createMyArticle/",
            data: 'article_name='+article_name+"&parent_id="+parent_id+"&article_content="+article_content+"&article_tag="+article_tag+"&article_contact="+article_contact+"&article_mobile="+article_mobile+"&article_reason="+article_reason+"&article_id="+article_id+"&token="+token,
            complete :function(){
            },
            success: function(msg){
                if(msg['status']){
                    $("#getcards_savebox p").remove();
                    $("body")[0].show({
                        title:"生意街提示您",
                        content:[
                                 $("#getcards_savebox").html(),
                                 "<p class='btn'>",
                                 "<a href='<?php echo URL::website($gourl);?>' class='ok'>确定</a>",
                                 "</p>"].join("")
                    });
                }
                else{
                    $("body")[0].show({
                        title:"生意街提示您",
                        content:"<div class='mb_content'>"+msg['msg']+"</div>",
                        btn:"ok"
                    });
                }
            }
        });
        return false;
    });

    //关闭
    $("#getcards_savebox a.close").click(function(){
        $(this).parent().slideUp("500",function(){
            $("#getcards_opacity").hide();
        })
        return false;
    });
    //取消
    $(".cz_suc a.cancel").click(function(){
        $("#getcards_savebox").slideUp("500",function(){
            $("#getcards_opacity").hide();
        })
        return false;
    });
    //确定
    /*
    $("#getcards_deletebox a.ensure").click(function(){
        $("#getcards_deletebox").slideUp("500",function(){
            $("#getcards_opacity").hide();
        })
        return false;
    });
    */



});
</script>
<div id="getcards_opacity"></div>
<div id="getcards_savebox">
    <!--  <a class="close" href="#">关闭</a>-->
    <div class="tougao_clum">
       <img class="tg_succ" src="<?php echo URL::webstatic("images/platform/information/tg_succ.jpg")?>"/>
       <div class="tougao_clum_right">
       <h4>投稿成功</h4>
       <span>我们会在2个工作日内对您的投稿进行审核。请您耐心等待！</span>
       </div>
       <div class="clear"></div>
       <p><a href="<?php echo URL::website($gourl);?>"><img src="<?php echo URL::webstatic("images/getcards/ensure.jpg")?>"></a></p>
       <div class="clear"></div>
    </div>
</div>


<script>
/*
$("#parent_col").change(function(){
        var parent_id = $(this).val();
        $.ajax({
              type: "post",
              dataType: "text",
              url: "/zixun/ajaxcheck/getSubColumn",
              data: "parent_id="+parent_id,
              complete :function(){
              },
              success: function(msg){
                  $("#sub_column").html(msg);
              }
         });
});
*/
</script>