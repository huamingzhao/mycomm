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
                            <li <?php if($page == 'index') : echo 'class="active"'; endif;?>><a href="/index.html">首页</a></li>
                            <!--<li <?php if($page == 'company') : echo 'class="active"'; endif;?>><a href="/company/index">公司介绍</a></li>-->
                            <li <?php if($page == 'product') : echo 'class="active"'; endif;?>><a href="/pro_list.html">产品介绍</a></li>
                            <li <?php if($page == 'about') : echo 'class="active"'; endif;?>><a href="/about.html">加盟代理</a></li>
                            <li <?php if($page == 'contact') : echo 'class="active"'; endif;?>><a href="/contact.html">联系我们</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <!-- 内容 -->
        <?php echo $content;?>
        <!-- end -->
    </div>
<!--尾部开始-->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="widget">
                        <h5 class="widgetheading">联系我们</h5>
                        <!--
                        <address>
                            <strong>安馨</strong>
                        </address>-->
                        <p>
                            <i class="icon-phone"></i> 15021186361 <br>
                            <i class="icon-envelope-alt"></i> email@domainname.com
                        </p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="widget">
                        <h5 class="widgetheading">友情链接</h5>
                        <ul class="link-list">
                            <li><a href="#">安馨</a></li>
                        </ul>
                    </div>
                </div>
                <!--
                <div class="col-lg-3">
                    <div class="widget">
                        <h5 class="widgetheading">Latest posts</h5>
                        <ul class="link-list">
                            <li><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></li>
                            <li><a href="#">Pellentesque et pulvinar enim. Quisque at tempor ligula</a></li>
                            <li><a href="#">Natus error sit voluptatem accusantium doloremque</a></li>
                        </ul>
                    </div>
                </div>
                -->
                <div class="col-lg-3">
                    <div class="widget">
                        <h5 class="widgetheading">最新产品</h5>
                        <ul class="link-list">
                            <li><a href="/jy.html">精油</a></li>
                            <li><a href="/nj.html">凝胶</a></li>
                            <li><a href="/xm.html">胸膜</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id="sub-footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="copyright">
                            <p>
                                Copyright &copy; 2016.Company name All rights reserved.<a target="_blank" href="http://sc.chinaz.com/moban/">&#x7F51;&#x9875;&#x6A21;&#x677F;</a>
                            </p>
                        </div>
                    </div>
                    <!--
                    <div class="col-lg-6">
                        <ul class="social-network">
                            <li><a href="#" data-placement="top" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#" data-placement="top" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#" data-placement="top" title="Linkedin"><i class="fa fa-linkedin"></i></a></li>
                            <li><a href="#" data-placement="top" title="Pinterest"><i class="fa fa-pinterest"></i></a></li>
                            <li><a href="#" data-placement="top" title="Google plus"><i class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div>
                    -->
                </div>
            </div>
        </div>
    </footer>
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

