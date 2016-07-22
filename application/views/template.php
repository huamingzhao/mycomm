<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $title!="" ? $title : "一句话|投资赚钱好项目，一句话的事。";?></title>
    <meta name="Keywords" content="<?php echo $keywords!="" ? $keywords : "投资，赚钱，好项目，一句话，投资赚钱";?>" />
    <meta name="description" content="<?php echo $description!="" ? $description : "投资，赚钱，好项目，一句话，投资赚钱";?>" />
    <?php echo Helper_URL::webcss("reset.css")?>
    <?php echo Helper_URL::webcss("quickrelease.css")?>
    <?php echo Helper_URL::webjs("jquery-1.4.2.min.js")?>
</head>
<body style="width:auto;background:none;">
<?php echo $content;?>
<!--尾部开始-->
<div class="footer"></div>
</body>
</html>

