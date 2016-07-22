<?php echo URL::webcss("platform/site_map.css")?>
<?php echo URL::webcss("platform/search_result.css")?>
<div class="ad_sitemap">
    <div class="breadcrumbs"><a href="<?php echo URL::website('')?>">首页</a>&gt; <?php if($isdisplay == 1) {echo "<span>网站地图</span>";}else{
       echo "<span>按热门关键词字母分类({$type})</span>";
    }?></div>
    <?php if($isdisplay == 2){ echo "<h1>按热门关键词字母分类（{$type}）</h1>";}?>
    <div class="list_box">
        <div class="list_title"><span>按行业字母分类</span><a href="<?php echo urlbuilder::siteMap("A"); ?>" <?php if($type == "A"){ echo "class='current'";}?>>A</a><a href="<?php echo urlbuilder::siteMap("B");?>" <?php if($type == "B"){ echo "class='current'";}?>>B</a><a href="<?php echo urlbuilder::siteMap("C");?>"<?php if($type == "C"){ echo "class='current'";}?>>C</a><a href="<?php echo urlbuilder::siteMap("D");?>"<?php if($type == "D"){ echo "class='current'";}?> >D</a><a href="<?php echo urlbuilder::siteMap("E");?>" <?php if($type == "E"){ echo "class='current'";}?>>E</a><a href="<?php echo urlbuilder::siteMap("F");?>" <?php if($type == "F"){ echo "class='current'";}?>>F</a><a href="<?php echo urlbuilder::siteMap("G");?>" <?php if($type == "G"){ echo "class='current'";}?>>G</a><a href="<?php echo urlbuilder::siteMap("H");?>" <?php if($type == "H"){ echo "class='current'";}?>>H</a><a href="<?php echo urlbuilder::siteMap("I");?>" <?php if($type == "I"){ echo "class='current'";}?>>I</a><a href="<?php echo urlbuilder::siteMap("J");?>" <?php if($type == "J"){ echo "class='current'";}?>>J</a><a href="<?php echo urlbuilder::siteMap("K");?>" <?php if($type == "K"){ echo "class='current'";}?>>K</a><a href="<?php echo urlbuilder::siteMap("L");?>" <?php if($type == "L"){ echo "class='current'";}?>>L</a><a href="<?php echo urlbuilder::siteMap("M");?>" <?php if($type == "M"){ echo "class='current'";}?>>M</a><a href="<?php echo urlbuilder::siteMap("N");?>" <?php if($type == "N"){ echo "class='current'";}?>>N</a><a href="<?php echo urlbuilder::siteMap("O");?>" <?php if($type == "O"){ echo "class='current'";}?>>O</a><a href="<?php echo urlbuilder::siteMap("P");?>" <?php if($type == "P"){ echo "class='current'";}?>>P</a><a href="<?php echo urlbuilder::siteMap("Q");?>" <?php if($type == "Q"){ echo "class='current'";}?>>Q</a><a href="<?php echo urlbuilder::siteMap("R");?>" <?php if($type == "R"){ echo "class='current'";}?>>R</a><a href="<?php echo urlbuilder::siteMap("S");?>" <?php if($type == "S"){ echo "class='current'";}?>>S</a><a href="<?php echo urlbuilder::siteMap("T");?>" <?php if($type == "T"){ echo "class='current'";}?>>T</a><a href="<?php echo urlbuilder::siteMap("U");?>" <?php if($type == "U"){ echo "class='current'";}?>>U</a><a href="<?php echo urlbuilder::siteMap("V");?>" <?php if($type == "V"){ echo "class='current'";}?>>V</a><a href="<?php echo urlbuilder::siteMap("W");?>" <?php if($type == "W"){ echo "class='current'";}?>>W</a><a href="<?php echo urlbuilder::siteMap("X");?>" <?php if($type == "X"){ echo "class='current'";}?>>X</a><a href="<?php echo urlbuilder::siteMap("Y");?>" <?php if($type == "Y"){ echo "class='current'";}?>>Y</a><a href="<?php echo urlbuilder::siteMap("Z");?>" <?php if($type == "Z"){ echo "class='current'";}?>>Z</a></div>
       	<?php if($isdisplay == 1){?>
        <div class="mapwrap">
            <ul class="clearfix">
                <li><a href="<?php echo URL::website('');?>">一句话首页</a></li>
                <li><a href="<?php echo urlbuilder::rootDir ("xiangdao");?>">找项目</a></li>
                <li><a href="<?php echo urlbuilder::rootDir ("zhaotouzi");?>">找投资者</a></li>
                <li><a href="<?php echo urlbuilder::rootDir ("touzikaocha");?>">投资考察</a></li>
                <li><a href="<?php echo urlbuilder::rootDir ("zixun");?>">学做生意</a></li>
                <li><a href="<?php echo URL::webwen('');?>">创业问答网</a></li>
            </ul>
            <h3><a href="<?php echo urlbuilder::rootDir ("xiangdao/fenlei");?>">项目向导</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo urlbuilder::rootDir ("xiangdao/fenlei");?>">分类导航</a></li>
                <li><a href="<?php echo urlbuilder::rootDir ("xiangdao/top");?>">排行榜</a></li>
                <li><a href="<?php echo urlbuilder::rootDir ("xiangdao/people");?>">按人群找</a></li>
                <li><a href="<?php echo urlbuilder::rootDir ("xiangdao/diqu");?>">按地区找</a></li>
                <li><a href="<?php echo urlbuilder::root ("xiangdao/people/1");?>">女性创业首选项目</a></li>
                <li><a href="<?php echo urlbuilder::root ("xiangdao/people/2");?>">大学生创业首选项目</a></li>
                <li><a href="<?php echo urlbuilder::root ("xiangdao/people/3");?>">农民创业首选项目</a></li>
                <li><a href="<?php echo urlbuilder::root ("xiangdao/people/4");?>">白领创业首选项目</a></li>
            </ul>
            <h3><a href="<?php echo urlbuilder::rootDir ("touzikaocha");?>">投资考察</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo urlbuilder::rootDir ("touzikaocha");?>">投资考察会</a></li>
                <li><a href="<?php echo urlbuilder::rootDir ("lishizhaoshang");?>">历史投资考察会</a></li>
            </ul>
            <h3><a href="<?php echo urlbuilder::rootDir ("zixun");?>">学做生意</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo urlbuilder::root ("zixun/invest");?>">投资前沿</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/fugle-a");?>">项目导航</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/guide");?>">开店指导</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/shop");?>">开店管理</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/people");?>">财富人物</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/dialys");?>">行业透视</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/kaocha");?>">考察报告</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/hot");?>">专栏</a></li>
            	<li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a");?>">行业新闻</a></li>
            </ul>
             <h3><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a");?>">行业新闻</a></h3>
             <ul class="clearfix">
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy2");?>">连锁零售</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy3");?>">服饰箱包</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy4");?>">家居建材</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy5");?>">教育培训</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy6");?>">酒水饮料</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy109");?>">生活服务</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy119");?>">珠宝饰品</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy7");?>">新奇特</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy1");?>">餐饮</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy72");?>">娱乐</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy80");?>">美容</a></li>
             </ul>
             
             <ul class="clearfix">
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy89");?>">五金</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy95");?>">家纺</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy103");?>">母婴</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy129");?>">汽车</a></li>
              <li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy137");?>">环保</a></li>
             </ul>
             
            <h3><a href="<?php echo urlbuilder::root ("zixun/fugle-a");?>">项目导航</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo urlbuilder::root ("zixun/fugle/touzihangye-a");?>">投资行业向导</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/fugle/touzirenqun-a");?>">投资人群向导</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/fugle/touzijine-a");?>">投资金额向导</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/fugle/touzileixing-a");?>">项目类型向导</a></li>
            </ul>
            <h3><a href="<?php echo urlbuilder::root ("zixun/guide");?>">开店指导</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo urlbuilder::root ("zixun/guide/kaidianbidu");?>">开店必读</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/guide/jiamengchoubei");?>">加盟筹备</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/guide/shichangdingwei");?>">市场定位</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/guide/zhuangxiujiqiao");?>">装修技巧</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/guide/touziyusuan");?>">投资预算</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/guide/kaidianliucheng");?>">开店流程</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/guide/xuanzhimijue");?>">选址秘诀</a></li>
            </ul>
            <h3><a href="<?php echo urlbuilder::root ("zixun/shop");?>">开店管理</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo urlbuilder::root ("zixun/shop/jiqiao");?>">经营技巧</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/shop/cuxiao");?>">推广促销</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/shop/renyuan");?>">人员管理</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/shop/guke");?>">顾客管理</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/shop/yingye");?>">如何提高营业额</a></li>
            </ul>
            <h3><a href="<?php echo urlbuilder::root ("zixun/people");?>">财富人物</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo urlbuilder::root ("zixun/tongluren");?>">创业同路人</a></li>
                <li><a href="<?php echo urlbuilder::root ("zixun/qiyejiashuo");?>">企业家说</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ('');?>">创业问答网</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("daili/");?>">代理</a></li>
                <li><a href="<?php echo URL::webwen ("jiameng/");?>">加盟</a></li>
                <li><a href="<?php echo URL::webwen ("pifa/");?>">批发</a></li>
                <li><a href="<?php echo URL::webwen ("kaidian/");?>">开店</a></li>
                <li><a href="<?php echo URL::webwen ("chuangye/");?>">创业</a></li>
                <li><a href="<?php echo URL::webwen ("xiaoshou/");?>">销售</a></li>
                <li><a href="<?php echo URL::webwen ("touzi/");?>">投资</a></li>
                <li><a href="<?php echo URL::webwen ("shangji/");?>">商机</a></li>
                <li><a href="<?php echo URL::webwen ("shengyi/");?>">生意</a></li>
                <li><a href="<?php echo URL::webwen ("zhuanqian/");?>">赚钱</a></li>
                <li><a href="<?php echo URL::webwen ("maimai/");?>">买卖</a></li>
                <li><a href="<?php echo URL::webwen ("zhifu/");?>">致富</a></li>
                <li><a href="<?php echo URL::webwen ("facai/");?>">发财</a></li>
                <li><a href="<?php echo URL::webwen ("xiangmu/");?>">项目</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("daili/");?>">代理</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("daili/101/");?>">代理创业</a></li>
                <li><a href="<?php echo URL::webwen ("daili/102/");?>">产品代理</a></li>
                <li><a href="<?php echo URL::webwen ("daili/103/");?>">品牌代理</a></li>
                <li><a href="<?php echo URL::webwen ("daili/104/");?>">免费代理</a></li>                
                <li><a href="<?php echo URL::webwen ("daili/105/");?>">代理项目</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("jiameng/");?>">加盟</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("jiameng/106/");?>">餐饮加盟</a></li>
                <li><a href="<?php echo URL::webwen ("jiameng/107/");?>">服饰加盟</a></li>
                <li><a href="<?php echo URL::webwen ("jiameng/108/");?>">美容加盟</a></li>
                <li><a href="<?php echo URL::webwen ("jiameng/109/");?>">饰品加盟</a></li>
                <li><a href="<?php echo URL::webwen ("jiameng/110/");?>">建材加盟</a></li>
                <li><a href="<?php echo URL::webwen ("jiameng/111/");?>">家纺加盟</a></li>
                <li><a href="<?php echo URL::webwen ("jiameng/112/");?>">连锁加盟</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("pifa/");?>">批发</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("pifa/113/");?>">批发零售</a></li>
                <li><a href="<?php echo URL::webwen ("pifa/114/");?>">批发代理</a></li>
                <li><a href="<?php echo URL::webwen ("pifa/115/");?>">批发加盟</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("kaidian/");?>">开店</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("kaidian/116/");?>">网上开店</a></li>
                <li><a href="<?php echo URL::webwen ("kaidian/117/");?>">千元开店</a></li>
                <li><a href="<?php echo URL::webwen ("kaidian/118/");?>">万元开店</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("chuangye/");?>">创业</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("chuangye/119/");?>">怎么创业</a></li>
                <li><a href="<?php echo URL::webwen ("chuangye/120/");?>">千元创业</a></li>
                <li><a href="<?php echo URL::webwen ("chuangye/121/");?>">万元创业</a></li>
                <li><a href="<?php echo URL::webwen ("chuangye/122/");?>">创业项目</a></li>
                <li><a href="<?php echo URL::webwen ("chuangye/123/");?>">个人创业</a></li>
                <li><a href="<?php echo URL::webwen ("chuangye/124/");?>">创业经验</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("xiaoshou/");?>">销售</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("xiaoshou/125/");?>">销售技巧</a></li>
                <li><a href="<?php echo URL::webwen ("xiaoshou/126/");?>">销售管理</a></li>
                <li><a href="<?php echo URL::webwen ("xiaoshou/127/");?>">销售计划</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("touzi/");?>">投资</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("touzi/128/");?>">如何投资</a></li>
                <li><a href="<?php echo URL::webwen ("touzi/129/");?>">投资赚钱</a></li>
                <li><a href="<?php echo URL::webwen ("touzi/130/");?>">小本投资</a></li>
                <li><a href="<?php echo URL::webwen ("touzi/131/");?>">投资项目</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("shangji/");?>">商机</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("shangji/132/");?>">创业商机</a></li>
                <li><a href="<?php echo URL::webwen ("shangji/133/");?>">加盟商机</a></li>
                <li><a href="<?php echo URL::webwen ("shangji/134/");?>">赚钱商机</a></li>
                <li><a href="<?php echo URL::webwen ("shangji/135/");?>">投资商机</a></li>
                <li><a href="<?php echo URL::webwen ("shangji/136/");?>">商机项目</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("shengyi/");?>">生意</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("shengyi/137/");?>">小本生意</a></li>
                <li><a href="<?php echo URL::webwen ("shengyi/138/");?>">投资生意</a></li>
                <li><a href="<?php echo URL::webwen ("shengyi/139/");?>">生意项目</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("zhuanqian/");?>">赚钱</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("zhuanqian/140/");?>">赚钱生意</a></li>
                <li><a href="<?php echo URL::webwen ("zhuanqian/141/");?>">生意赚钱</a></li>
                <li><a href="<?php echo URL::webwen ("zhuanqian/142/");?>">什么赚钱</a></li>
                <li><a href="<?php echo URL::webwen ("zhuanqian/143/");?>">快速赚钱</a></li>
                <li><a href="<?php echo URL::webwen ("zhuanqian/144/");?>">2012赚钱</a></li>
                <li><a href="<?php echo URL::webwen ("zhuanqian/145/");?>">怎么赚钱</a></li>
                <li><a href="<?php echo URL::webwen ("zhuanqian/146/");?>">赚钱项目</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("maimai/");?>">买卖</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("maimai/147/");?>">买卖项目</a></li>
                <li><a href="<?php echo URL::webwen ("maimai/148/");?>">小本买卖</a></li>
                <li><a href="<?php echo URL::webwen ("maimai/149/");?>">赚钱买卖</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("zhifu/");?>">致富</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("zhifu/150/");?>">致富项目</a></li>
                <li><a href="<?php echo URL::webwen ("zhifu/151/");?>">投资致富</a></li>
                <li><a href="<?php echo URL::webwen ("zhifu/152/");?>">赚钱致富</a></li>
                <li><a href="<?php echo URL::webwen ("zhifu/153/");?>">快速致富</a></li>
                <li><a href="<?php echo URL::webwen ("zhifu/154/");?>">致富网</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("facai/");?>">发财</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("facai/155/");?>">发财项目</a></li>
                <li><a href="<?php echo URL::webwen ("facai/156/");?>">发财生意</a></li>
            </ul>
            <h3><a href="<?php echo URL::webwen ("xiangmu/");?>">项目</a></h3>
            <ul class="clearfix">
                <li><a href="<?php echo URL::webwen ("xiangmu/157/");?>">热门项目</a></li>
                <li><a href="<?php echo URL::webwen ("xiangmu/158/");?>">项目投资</a></li>
                <li><a href="<?php echo URL::webwen ("xiangmu/159/");?>">招商项目</a></li>
                <li><a href="<?php echo URL::webwen ("xiangmu/160/");?>">最新项目</a></li>
                <li><a href="<?php echo URL::webwen ("xiangmu/161/");?>">2012项目</a></li>
                <li><a href="<?php echo URL::webwen ("xiangmu/162/");?>">2013项目</a></li>
            </ul>
        </div>
        <?php }?>
        <?php if(count($projectList) >0) { ?>
        <div class="list_wrap">
            <div class="list_nav">
                    <ul>
                        <li><a title="投资前沿" href="<?php echo URL::website('/zixun/invest.html')?>" target="_blank">投资前沿</a></li>
                        <li><a title="项目向导" href="<?php echo URL::website('/zixun/fugle-a.html')?>" target="_blank">项目向导</a></li>
                        <li><a title="开店指导" href="<?php echo URL::website('/zixun/guide.html')?>" target="_blank">开店指导</a></li>
                        <li><a title="开店管理" href="<?php echo URL::website('/zixun/shop.html')?>" target="_blank">开店管理</a></li>
                        <li><a title="财富人物" href="<?php echo URL::website('/zixun/people.html')?>" target="_blank">财富人物</a></li>
                        <li><a title="行业透视" href="<?php echo URL::website('/zixun/dialys.html')?>" target="_blank">行业透视</a></li>
                        <li><a title="考察报告" href="<?php echo URL::website('/zixun/kaocha.html')?>" target="_blank">考察报告</a></li>
                        <li><a title="分类导航" href="<?php echo URL::website('/xiangdao/fenlei/')?>" target="_blank">分类导航</a></li>
                     	<li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a");?>">行业新闻</a></li>
                     	<li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy2");?>">连锁零售</a></li>
                     	<li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy3");?>">服饰箱包</a></li>
                     	<li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy4");?>">家居建材</a></li>
                     	<li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy5");?>">教育培训</a></li>
                     	<li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy6");?>">酒水饮料</a></li>
                     	<li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy109");?>">生活服务</a></li>
                     	<li><a href="<?php echo urlbuilder::root ("zixun/industryinfo-a/hy119");?>">珠宝饰品</a></li>
                        <div class="clear"></div>
                        
                    </ul>
                </div>
          
          <div class="list_pro">
            <?php if(isset($projectList[0])){foreach ($projectList[0] as $k0=>$v0){?>
                <ul>
                    <li><a  target="_blank" href="<?php echo urlbuilder::project($v0['project_id'], 2); ?>" title="<?php echo $v0['project_brand_name']?>"><?php echo $v0['project_brand_name'];?></a></li>
                </ul>
                <?php }}?>
            </div>
               <div class="list_pro">
               <?php if(isset($projectList[1])){ foreach ($projectList[1] as $k1=>$v1){?>
                <ul>
                    <li><a  target="_blank" href="<?php echo urlbuilder::project($v1['project_id'], 2); ?>" title="<?php echo $v1['project_brand_name']?>"><?php echo $v1['project_brand_name']?></a></li>
                </ul>
                <?php }}?>
            </div>
            <div class="list_pro">
            <?php if (isset($projectList[2])){foreach ($projectList[2] as $k2=>$v2){?>
                <ul>
                    <li><a target="_blank"  href="<?php echo urlbuilder::project($v2['project_id'], 2); ?>" title="<?php echo $v2['project_brand_name']?>"><?php echo $v2['project_brand_name']?></a></li>

                </ul>
                <?php }}?>
            </div>
            <div class="list_pro">
             <?php if(isset($projectList[3])){foreach ($projectList[3] as $k3=>$v3){?>
                <ul>
                    <li><a  target="_blank" href="<?php echo urlbuilder::project($v3['project_id'], 2); ?>" title="<?php echo $v3['project_brand_name'];?>"><?php echo $v3['project_brand_name']?></a></li>
                </ul>
                <?php }}?>
            </div>
        
        </div>
        <div class="ryl_search_result_page" style="margin-bottom:20px; margin-right:20px; float: right;text-align:right;width: 935px;">
       <?php echo $page;?>       
        </div>
        <?php }?>
        <div class="clear"></div>
    </div>
</div>
