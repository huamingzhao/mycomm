<div>
<section id="content">

    <div class="container">
        <?php foreach ($list as $key => $pro_info):?>
        <div class="row">
            <div class="col-lg-12">
                <div class="aligncenter" style="text-align:center;">
                    <a href="/product/info?id=<?php echo $pro_info['url_name']?>">
                        <?php echo $pro_info['pro_name'];?>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</section>
</div>