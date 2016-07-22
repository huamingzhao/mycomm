  <!-- <body style="background:#fbf8ef!important">
	<div class="htmllink">
            <img src="<?=URL::webstatic('/images/quickrelease/linkimg.png')?>" width="350" height="460">
		<div class="linkfont">
			<h1>页面跳转中，请稍候......</h1>
			<p>超过30秒页面无法应答，请<a href="javascript:;" class="refreshlink">刷新</a>页面</p>
		</div>
	</div>
</body>
<style type="text/css">
.htmllink{width: 850px; margin:80px auto 80px; height: 460px;}
.htmllink img{float:left;}
.linkfont{float: left; margin-top: 185px; margin-left: 50px;}
.linkfont h1{font-size: 36px; color: #a19889;}
.linkfont p{font-size: 18px; color: #999999;}
.linkfont p a{color: #436bff;}
</style>
    -->
<?php $tongji_name = parse_url(URL::website(""));  echo URL::webjs("stat/{$tongji_name['host']}.tongji.js")?>
<script>

var tj_type_id= 8;
var tj_pn_id= <?=$info['id']?>;


</script>
<script language="javascript" type="text/javascript">
    window.location.href="<?=$info['link']?>";
    $(".refreshlink").click(function(){
    	window.location.href="<?=$info['link']?>";
    })
</script>