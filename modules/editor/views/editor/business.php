<script charset="utf-8" src="<?php echo URL::webstatic("editor/kindeditor.js")?>"></script>
<script charset="utf-8" src="<?php echo URL::webstatic("editor/lang/zh_CN.js")?>"></script>
<script>
    var wenEditor;
    KindEditor.ready(function(K) {
    	wenEditor = K.create('textarea[name="<?php echo $field_name?>"]', {
        	uploadJson : '<?php echo URL::website("upload/editor")?>',
            allowFileManager : false,
            resizeType : 1,
            allowPreviewEmoticons : false,
            allowImageUpload : true,
            bodyClass : 'answertext',
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
                    var num=wenEditor.count('html');
                        if((num)>=1900&&(num)<=2000){
                            $(".detailtishi").show();
                            $(".detailtishi").text("还可以输入"+parseInt(2000-num)+"字");
                        }
                        else if((num)>2000){
                            $(".detailtishi").show();
                            $(".detailtishi").text("已经超出"+parseInt(num-2000)+"字").addClass("colred");
                        }
                        else{
                            $(".detailtishi").hide();
                            $(".detailtishi").text("已经超出"+parseInt(num-2000)+"字").removeClass("colred")
                        }
					
				});},
    	});
    });
</script>
<textarea   name="<?php echo $field_name?>" style="width:680px;height:<?php echo $height?>px;visibility:hidden;"><?php echo $content;?></textarea>