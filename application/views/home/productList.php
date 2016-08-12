<div>

    <section class="callaction">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="aligncenter">
                        <h1 class="aligncenter">产品介绍</h1>
                        安馨美到底，中国私密护理龙头

                        根据权威机构调查报告显示，仅国内二三线城市就有万亿级市场销售额，如此大的市场，你还在等什么，加入我们，成就你的土豪梦！

                        产品使用人群调查显示，80%受访女性对私密护理产品原料关注度最高，要求原料纯天然不含化学成分；50.77%的受访女性认为市面私密护理产品应该提高皮肤舒适度，消除过敏；49.23%受访女性认为产品使用便利、方便携带很重要。

                        根据调查结果得出，不含化学成分、抗过敏、使用方便、安全无副作用的私密护理产品将会受到广大女性的欢迎。
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="content">
        <div class="container">
            <div class="row">
                <div class="skill-home">
                    <div class="skill-home-solid clearfix">

                        <?php foreach ($list as $key => $pro_info):?>
                        <div class="col-md-3 text-center" style="margin-left:60px;">
                            <span class="icons c<?php echo $key+1;?>">
                                <a href="/<?php echo $pro_info['url_name']?>.html">
                                    <img src="<?php echo $pro_info['image_url']?>" style="height:100px;width:100px"/>
                                </a>
                            </span>
                            <div class="box-area">
                                <a href="/<?php echo $pro_info['url_name']?>.html">
                                    <h3><?php echo $pro_info['pro_name'];?></h3>
                                    <p>安馨美到底，中国护理龙头</p>
                                </a>
                            </div>
                        </div>
                        <?php endforeach;?>

                    </div>
                </div>
            </div>

        </div>
    </section>

</div>