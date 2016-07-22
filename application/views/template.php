<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $title!="" ? $title : "一句话|投资赚钱好项目，一句话的事。";?></title>
    <meta name="Keywords" content="<?php echo $keywords!="" ? $keywords : "投资，赚钱，好项目，一句话，投资赚钱";?>" />
    <meta name="description" content="<?php echo $description!="" ? $description : "投资，赚钱，好项目，一句话，投资赚钱";?>" />
    <?php echo Helper_URL::webcss("bootstrap.min.css")?>
    <?php echo Helper_URL::webcss("fancybox/jquery.fancybox.css")?>
    <?php echo Helper_URL::webcss("jcarousel.css")?>
    <?php echo Helper_URL::webcss("flexslider.css")?>
    <?php echo Helper_URL::webcss("owl-carousel/owl.carousel.css")?>
    <?php echo Helper_URL::webcss("style.css")?>
</head>
<body>
    <div id="wrapper">
        <header>
            <div class="navbar navbar-default navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="index.html"><img src="/images/logo.png" alt="logo"/></a>
                    </div>
                    <div class="navbar-collapse collapse ">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="index.html">首页</a></li>
                            <li><a href="portfolio.html">公司介绍</a></li>
                            <li><a href="pricing.html">产品介绍</a></li>
                            <li><a href="about.html">关于我们</a></li>
                            <li><a href="contact.html">联系我们</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>


        <?php echo $content;?>
    </div>
<!--尾部开始-->
    <div class="footer">

    </div>
<!-- 尾部结束 -->

<!-- Placed at the end of the document so the pages load faster -->
<script src="/js/jquery.js"></script>
<script src="/js/jquery.easing.1.3.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/jquery.fancybox.pack.js"></script>
<script src="/js/jquery.fancybox-media.js"></script>
<script src="/js/portfolio/jquery.quicksand.js"></script>
<script src="/js/portfolio/setting.js"></script>
<script src="/js/jquery.flexslider.js"></script>
<script src="/js/animate.js"></script>
<script src="/js/custom.js"></script>
<script src="/js/owl-carousel/owl.carousel.js"></script>

</body>
</html>

