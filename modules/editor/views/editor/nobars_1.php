<script charset="utf-8" src="<?php echo URL::webstatic("editor/kindeditor.js")?>"></script>
<script charset="utf-8" src="<?php echo URL::webstatic("editor/lang/zh_CN.js")?>"></script>
<script>
    var editors_1;
    KindEditor.ready(function(K) {
        editors_1 = K.create('textarea[name="<?php echo $field_name?>"]', {
            uploadJson : '<?php echo URL::website("upload/editor")?>',
            allowFileManager : false,
            resizeType : 1,
            allowPreviewEmoticons : false,
            allowImageUpload : false,
            items : ['bold','italic','underline','forecolor', 'hilitecolor','justifyleft', 'justifycenter', 'justifyright','link','table','image']
        });
    });
</script>
<textarea name="<?php echo $field_name?>" style="width:<?php echo $width?>px;height:<?php echo $height?>px;visibility:hidden;"><?php echo $content;?></textarea>