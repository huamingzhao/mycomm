<script charset="utf-8" src="<?php echo URL::webstatic("editor/kindeditor.js")?>"></script>
<script charset="utf-8" src="<?php echo URL::webstatic("editor/lang/zh_CN.js")?>"></script>
<script>
    var wenEditor;
    KindEditor.ready(function(K) {
    	quickEditor = K.create('textarea[name="<?php echo $field_name?>"]', {
    		
        	uploadJson : '<?php echo URL::website("upload/editor")?>',
            allowFileManager : false,
            resizeType : 1,
            allowPreviewEmoticons : false,
            allowImageUpload : true,
            bodyClass : 'content-main',
            cssPath : '<?php echo url::webstatic("/css/quickrelease.css")?>',
//             items : [ 'undo', 'redo', '|', 'preview', 'template', 'cut', 'copy', 'paste',
//               		'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
//               		'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
//               		'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
//               		'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
//               		'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
//               		 'media', 'table', 'hr',  'link', 'unlink']
        
           items:['bold','underline','fontsize','forecolor'],
    	afterCreate : function() {
			K(this.edit.doc).keyup(
			function(e) { 
				//alert(obj.val());
				alert(wenEditor.count('text'));
			});},
    	});
    });
</script>
<textarea name="<?php echo $field_name?>" style="width:<?php echo $width?>px;height:<?php echo $height?>px;visibility:hidden;"><?php echo $content;?></textarea>