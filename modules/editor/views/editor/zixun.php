<script charset="utf-8" src="<?php echo URL::webstatic("editor/kindeditor.js")?>"></script>
<script charset="utf-8" src="<?php echo URL::webstatic("editor/lang/zh_CN.js")?>"></script>
<script>
    var editor;
    KindEditor.ready(function(K) {
        editor = K.create('textarea[name="<?php echo $field_name?>"]', {
            uploadJson : '<?php echo URL::website("upload/editor")?>',
            allowFileManager : false,
            pasteType:1,
            items : ['bold','italic','underline','forecolor', 'hilitecolor','justifyleft', 'justifycenter', 'justifyright','link','table','image'],
             afterFocus : function(e){

                 if (editor.html().indexOf("请输入文章的内容。")>=0){
                    editor.html('');
                 }

             }/*,
              afterBlur : function(e){

                 if (editor.html()==''){

                    //editor.html("请输入文章的内容。");
                 }

             }*/

        });
        K('input[name=getHtml]').click(function(e) {
            alert(editor.html());
        });
        K('input[name=isEmpty]').click(function(e) {
            alert(editor.isEmpty());
        });
        K('input[name=getText]').click(function(e) {
            alert(editor.text());
        });
        K('input[name=selectedHtml]').click(function(e) {
            alert(editor.selectedHtml());
        });
        K('input[name=setHtml]').click(function(e) {
            editor.html('<h3>Hello KindEditor</h3>');
        });
        K('input[name=setText]').click(function(e) {
            editor.text('<h3>Hello KindEditor</h3>');
        });
        K('input[name=insertHtml]').click(function(e) {
            editor.insertHtml('<strong>插入HTML</strong>');
        });
        K('input[name=appendHtml]').click(function(e) {
            editor.appendHtml('<strong>添加HTML</strong>');
        });
        K('input[name=clear]').click(function(e) {
            editor.html('');
        });
    });
</script>
<textarea name="<?php echo $field_name?>" style="width:<?php echo $width?>px;height:<?php echo $height?>px;visibility:hidden;"><?php echo $content;?></textarea>