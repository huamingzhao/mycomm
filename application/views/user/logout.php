<?php echo URL::webjs("jquery-1.4.2.min.js")?>
<script language="javascript">
var type='<?php echo $type?>';
$(document).ready(function(e) {
    var url='<?php echo Kohana::$config->load("site.web875")?>ajaxcheck/clearlogincache';

    var sjy_url= 'http://www.shengyijie.net/ajaxdo/user?is_login=2&user_id=<?php echo Session::instance ()->get('user_id');?>&login_name=<?php echo Session::instance ()->get('username')?>&user_type=<?php echo Session::instance ()->get('user_type')?>';
    SYJ_loadIframe(sjy_url);
    loadIframe(url,test)
});

function SYJ_loadIframe(src){
    var iframe = document.createElement("iframe");
    iframe.src = src;
    iframe.style.display='none'
    document.body.appendChild(iframe);
}
function loadIframe(src, callback){
    var iframe = document.createElement("iframe");
    iframe.src = src;
    iframe.style.display='none'

    if(iframe.attachEvent){ // IE
        iframe.attachEvent('onload', callback);
    }else{ // nonIE
        iframe.onload = callback
    }

    document.body.appendChild(iframe);
}
function test(){
    if(type==1 || type==''){
        window.location='<?php echo URL::website('member/login')?>'
    }else{
        window.location=type
    }
}
</script>