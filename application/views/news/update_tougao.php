<?php echo URL::webcss("/platform/information.css")?>
<?php echo URL::webjs('news/news.js')?>
<!--主体部分-->
<div class="main" style="background-color:#e3e3e3; height:auto;">
<div class="infor_main">
  <div class="infor_menu">
  <!--菜单-->
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
                    <p><input class="tg_text01 validate required" name="article_name" type="text" value="<?php echo $list->article_name?>"><ins style="display:none" id="article_name_tishi">* 请填写文章标题</ins></p>
                    <div class="clear"></div>
                </div>
                <div class="contribut_table_li">
                    <label><em>*</em> 栏目：</label>
                    <p>
                    <?php //echo  Form::select("parent_id",$columns,$list->parent_id,array("class"=>"tg_text02","id"=>'parent_col'))?>
                    <select id="parent_id" name="parent_id" class="tg_text02 validate required">
                    <?php
                     foreach ($column as $v){
                        if( $v->column_name!='项目新闻' ){
                    ?>
                        <option value="<?=$v->column_id;?>" <?php if($list->parent_id== $v->column_id) echo "selected='selected'"; ?> ><?=$v->column_name;?></option>
                    <?php }} ?>
                    </select>
                    <div class="clear"></div>
                    </p>
                </div>
                <div class="contribut_table_li">
                    <label><em>*</em> 内容：</label>
                    <p><?php echo Editor::factory(isset($list->article_content) ? htmlspecialchars_decode($list->article_content):'',"zixun",array("field_name"=>"article_content","width"=>"600","height"=>"300"));?><ins style="display:none" id="article_content_tishi">* 请填写文章内容</ins></p>
                    <div class="clear"></div>
                </div>
                <div class="contribut_table_li">
                    <label>标签：</label>
                    <p><input class="tg_text01 validate required" name="article_tag" type="text" value="<?php echo $list->article_tag;?>"></p>
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
                    <p><input class="tg_text05 validate required" name="article_contact" type="text" value="<?=$article_contact?>"><ins style="display:none" id="article_cintact_tishi">* 请填写联系人姓名</ins></p>
                    <div class="clear"></div>
                </div>
                <div class="contribut_table_li">
                    <label><em>*</em> 联系电话：</label>
                    <p><input class="tg_text05 validate required" name="article_mobile" type="text" value="<?=$article_mobile?>"><ins style="display:none" id="article_mobile_tishi">* 请填写您的11位手机号码</ins></p>
                    <div class="clear"></div>
                </div>
                <div class="contribut_table_li">
                    <label><em>*</em> 投稿理由：</label>
                    <p><textarea  class="tg_textarea02 validate required" name="article_reason" cols="" rows=""><?php echo  $list->push_reason;?></textarea><ins style="display:none" id="article_reason_tishi">* 请填写投稿理由</ins></p>
                    <div class="clear"></div>
                    <input type="hidden" name="token" value='<?php echo Security::token(true);?>'>
                    <input type="hidden" name="article_id" value='<?php echo $list->article_id;?>'>
                </div>
                <div class="contribut_table_li">
                    <label></label>
                    <p><input class="tg_text06" type="image" value="&nbsp;" id="form_submit"></p>
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

<?php echo URL::webjs("jquery.validate.js")?>
<script>

window.onload=function(){
console.log(editor.html())
    $("#article_form").validate({
        ignore:false,
        rules: {
        article_name:"required",
        article_content:{"required":true,remote:function(){return editor.html()==""}},
        article_contact:"required",
        article_mobile:"required",
        article_reason:"required"
        },
        messages:{
            article_name:"* 请填写文章标题",
            article_content:{"required":"* 请填写文章内容","remote":"* 请填写文章内容"},
            article_contact:"请填写联系人",
            article_mobile:"请输入正确的手机号码",
            article_reason:"* 请填写投稿理由"
        },
        submitHandler:function(form){
            alert("asdf")
            var dt=$.ajaxsubmit("/zixun/ajaxcheck/updateMyArticle/",$("#article_form").serialize())
            window.MessageBox(dt.msg);
        }
    })
}
/*$(function(){
    $("#form_submit").click(function(){
        var article_name = $("input[name=article_name]").val();
        var parent_id = $("#parent_id option:selected'").val();
        var article_content = editor.html();
        var article_tag = $("input[name=article_tag]").val();
        var article_contact = $("input[name=article_contact]").val();
        var article_mobile = $("input[name=article_mobile]").val();
        var article_reason = $('textarea[name="article_reason"]').val();
        if(article_name=="")
        {
            $("#article_name_tishi").show();
            $("input[name=article_name]").focus();
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
            url: "/zixun/ajaxcheck/updateMyArticle/",
            data: 'article_name='+article_name+"&parent_id="+parent_id+"&article_content="+article_content+"&article_tag="+article_tag+"&article_contact="+article_contact+"&article_mobile="+article_mobile+"&article_reason="+article_reason+"&article_id="+article_id+"&token="+token,
            complete :function(){
            },
            success: function(msg){
                console.log(msg)
                if(msg['status']){

                    $(".showmsg").html('您的投稿信息已经修改成功，请耐心等待审核。');
                    $("#getcards_deletebox .text").addClass("suc");
                    $("#getcards_deletebox").slideDown("500",function(){
                        $("#getcards_opacity").show();
                    });
                }
                else{
                    $(".showmsg").html(msg['msg']);
                    $("#getcards_deletebox").slideDown("500",function(){
                        $("#getcards_opacity").show();
                    });
                    $("#getcards_deletebox .text").removeClass("suc");
                }
            }
        });
        return false;
    });

    //关闭
    $("#getcards_deletebox a.close").click(function(){
        $(this).parent().slideUp("500",function(){
            $("#getcards_opacity").hide();
        })
        return false;
    });
    //取消
    $(".cz_suc a.cancel").click(function(){
        $("#getcards_deletebox").slideUp("500",function(){
            $("#getcards_opacity").hide();
        })
        return false;
    });
    //确定
    $("#getcards_deletebox a.ensure").click(function(){
        $("#getcards_deletebox").slideUp("500",function(){
            $("#getcards_opacity").hide();
        })
        return false;
    });
});*/
</script>