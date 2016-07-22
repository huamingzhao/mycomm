<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 咨询首页
 * @author 许晟玮
 *
 */
class Controller_News_Index extends Controller_News_Template{

    /**
     * 咨询首页
     * @许晟玮
     */
    public function action_index(){
        $content= View::factory("news/index");
        $this->content->rightcontent= $content;

        $memcache = Cache::instance ('memcache');
        //广告数据 不需要 放入缓存  @author 花文刚
        $news_service= new Service_News_Advert();
        $rs_advert= $news_service->getAdvertMore( 5 );

        //获取资讯文章数据
        $article_service= new Service_News_Article();
        $list= $article_service->getList();

        $content->list= $list['list'];
        $content->page= $list['page'];
        $content->advert= $rs_advert;
        $now_page = Arr::get($this->request->query(), 'page',"");
        $this->content->currentcolumnid = "index";

        $friend_link = $memcache->get('friend_cache_zixun');
        if(empty($friend_link)){
            $f_service = new Service_Platform_FriendLink();
            $friend_link = $f_service->getFriendLinkList('zixun');
            $memcache->set('friend_cache_zixun', $friend_link,604800);
        }
        $this->template->friend_link = $friend_link;
        if($now_page == "" || $now_page == 1){
            $this->template->title = mb_convert_encoding('一句话创业资讯_专注创业指导_一句话商机速配网创业资讯',"utf-8");
            $this->template->description = mb_convert_encoding('一句话创业资讯频道是最专业的创业指导平台，在1句话资讯平台上你可以学习如何找项目创业、如何投资项目、如何创业，并可以了解别人的创业历程及创业投资中遇到的问题及处理方法。',"utf-8");
        }else{
            $this->template->title = mb_convert_encoding('第'.$now_page.'页一句话创业资讯_专注创业指导_一句话商机速配网创业资讯',"utf-8");
            $this->template->description = mb_convert_encoding('第'.$now_page.'页一句话创业资讯频道是最专业的创业指导平台，在1句话资讯平台上你可以学习如何找项目创业、如何投资项目、如何创业，并可以了解别人的创业历程及创业投资中遇到的问题及处理方法。',"utf-8");
        }
        $this->template->keywords = mb_convert_encoding('一句话创业资讯,一句话学开店,创业指导,创业资讯,加盟资讯',"utf-8");



    }
    //end function

    /**
     * 资讯栏目页
     * @author 周进
     */
    public function action_column(){
        $query = Arr::map("HTML::chars",$this->request->query());
        $content = View::factory("news/column");
        $this->content->rightcontent = $content;
        $column = new Service_News_Column();

        $selectColumn = $column->getColumnByName(common::getcolumnnames(arr::get($query, 'parent')));
        $query['id'] = $selectColumn['column_id'];
        if ($query['id']==""){
            $selectColumns = $column->getColumnByName(arr::get($query, 'parent'));
            $query['id'] = $selectColumns['column_id'];
        }

        $content->columnlist = $column->getParInfoById(arr::get($query, 'id'));
        $article_service = new Service_News_Article();
        $list = $article_service->getListByColumnId(arr::get($query, 'id'));
        $this->content->currentcolumnid = arr::get($query, 'id');
        $content->currentcolumnid = arr::get($query, 'id');
        $content->currentparentid = $selectColumn['parent_id'];
        $content->list = $list['list'];
        $content->page = $list['page'];
        $columns = $column->getCloInfo(arr::get($query, 'id'));

        $zxarticle = new Service_News_Article();
        $this->content->hotrecommend = $zxarticle->getHotColumnRecommend(arr::get($query, 'id'));
        //如果是项目新闻 seo by 许晟玮
        if( $columns['column_name']=='项目新闻' ){
            if (arr::get($query, 'page')!=""){
                $this->template->title= "第{$query['page']}页项目新闻_最新项目加盟新闻_项目新闻专区_一句话网";
                $this->template->keywords= "第{$query['page']}页,项目新闻,项目加盟新闻,项目新闻专区,一句话";
                $this->template->description= "第{$query['page']}页一句话网资讯频道项目新闻专区聚集大量的入住一句话网站的项目新闻、各个最新项目加盟新闻、项目推广新闻、项目品牌推广故事等，审查项目加盟、调研项目，上一句话资讯频道项目新闻专区可以学习大量的学开店知识。";

            }else{
                $this->template->title= "项目新闻_最新项目加盟新闻_项目新闻专区_一句话网";
                $this->template->keywords= "项目新闻,项目加盟新闻,项目新闻专区,一句话";
                $this->template->description= "一句话网资讯频道项目新闻专区聚集大量的入住一句话网站的项目新闻、各个最新项目加盟新闻、项目推广新闻、项目品牌推广故事等，审查项目加盟、调研项目，上一句话资讯频道项目新闻专区可以学习大量的学开店知识。";
            }
        }else{
            if (arr::get($query, 'page')!=""){
                $this->template->title = mb_convert_encoding('第'.$query['page'].'页'.$columns['column_name'].' | 找投资，关注一句话创业资讯',"utf-8");
                $this->template->description = mb_convert_encoding('关注'.$columns['column_name'].'，上一句话创业资讯(www.yjh.com)，找投资，关注'.$columns['column_name'].'，这里是第'.$query['page'].'页'.$columns['column_name'].'资讯专区。',"utf-8");
                $this->template->keywords = mb_convert_encoding($columns['column_name'].'，一句话创业资讯',"utf-8");
            }else{
                $this->template->title = mb_convert_encoding(arr::get($columns, 'column_title'),"utf-8");
                $this->template->description = mb_convert_encoding(arr::get($columns, 'column_description'),"utf-8");
                $this->template->keywords = mb_convert_encoding(arr::get($columns, 'column_keywords'),"utf-8");
            }
        }

		



    }
    //end function

    /**
     * 项目向导页
     * @author 周进
     */
    public function action_xiangmu(){
        $query = Arr::map("HTML::chars",$this->request->query());
        $content = View::factory("news/xiangmu");
        $this->content->rightcontent = $content;
        $column = new Service_News_Column();

        $selectColumn = $column->getColumnByName(common::getcolumnnames(arr::get($query, 'parent')));
        $query['id'] = $selectColumn['column_id'];
        if ($query['id']==""){
            $selectColumns = $column->getColumnByName(arr::get($query, 'parent'));
            $query['id'] = $selectColumns['column_id'];
        }
        elseif (arr::get($query, 'id')==''){
            $columns = $column->getColumnByName('项目向导');
            $query['id'] = $columns['column_id'];
        }
        $content->columnlist = $column->getParInfoById(arr::get($query, 'id'));
        $article_service = new Service_News_Article();
        if (arr::get($query, 'xmid')!=""){
            $data = $article_service->getInfoRowByxiangmu(arr::get($query, 'xmid'));
            $this->content->currentcolumnid = $data->parent_id;
        }
        else{
            $data = $article_service->getLastByColumnId(arr::get($query, 'id'));
            $this->content->currentcolumnid = arr::get($query, 'id');
        }
        $list = $article_service->getListByColumnId(arr::get($query, 'id'));

        $this->content->hotrecommend = $article_service->getHotColumnRecommend(arr::get($query, 'id'));

        //文章内容进行内连接
        $memcache = Cache::instance ( 'memcache' );
        $_cache_get_time = 86400;//一天
        $memcahcename='xiangmuContent'.$data->article_id;
        if($memcache->get($memcahcename)){
        	$data->article_content=$memcache->get($memcahcename);
        }else{
        	//seo优化添加内链接
        	$keyarr=common::zixunkey();
        	foreach($keyarr as $zixun_k=>$zixun_v){
        		$reg ='/'.$zixun_k.'(?![^a]*\<\/a\>)/';
        		$reg2 = '<a class="key_words" target="_blank" href="'.$zixun_v.'">'.$zixun_k.'</a>';
        		$data->article_content= preg_replace($reg,$reg2,$data->article_content,1);
        	}
        	$memcache->set($memcahcename,$data->article_content,$_cache_get_time);
        }

        /*start推荐项目*/
        $search = new Service_Platform_Search();
        $searchresult = $search->getWordSearch($data->article_name, array());
        $project_id_list=isset($searchresult['matches'])?$searchresult['matches']:array();
        $arr=NULL;
        foreach ($project_id_list as $val){
            $arr['result'][]=$val['id'];
        }
        $total = 0;
        if(isset($searchresult['total'])) {
            $total = $searchresult['total'];
        }
        $result = $search->getProjectSqlSearch($arr, $total);
        if (count($result['list'])>0)
            $content->project = arr::get($result, 'list');
        else
            $content->project = arr::get($result, 'list_make_up');
        /*end推荐项目*/


        $content->data = $data;
        $content->currentcolumnid = arr::get($query, 'id');
        $content->currentparentid = $selectColumn['parent_id'];
        $content->list = $list['list'];
        $content->page = $list['page'];

        $columns = $column->getCloInfo(arr::get($query, 'id'));
        $this->template->title = mb_convert_encoding(arr::get($columns, 'column_title'),"utf-8");
        $this->template->description = mb_convert_encoding(arr::get($columns, 'column_description'),"utf-8");
        $this->template->keywords = mb_convert_encoding(arr::get($columns, 'column_keywords'),"utf-8");
    }
    //end function
    /**
     * 项目专栏页
     * @author 赵路生
     */
    public function action_zhuanlan(){
        $query = Arr::map("HTML::chars",$this->request->query());
        $content = View::factory("news/zhuanlan");
        $this->content->rightcontent = $content;
        $zl_service = new Service_News_Zhuanlan();
        //文章列表
        $list= $zl_service->getZhuanlanList();
        //右侧专栏推荐
        $tui_list = $zl_service->getTjZl();
        $this->content->action = 'zhuanlan';
        $this->content->tui_list= $tui_list;
        $this->content->currentcolumnid = arr::get($query, 'id',28);//写死的 用来显示左边导航栏的变红
        $this->content->loop_count = true;//用在template右侧循环标志
        $content->list= $list['list'];
        $content->page= $list['page'];

        //seo优化
        $column = new Service_News_Column();
        $columns = $column->getCloInfo(arr::get($query, 'id',28));//

        if($list['page']->current_page==1){
            $this->template->title = mb_convert_encoding(arr::get($columns, 'column_title'),"utf-8");
            $this->template->description = mb_convert_encoding(arr::get($columns, 'column_description'),"utf-8");
            $this->template->keywords = mb_convert_encoding(arr::get($columns, 'column_keywords'),"utf-8");
        }else{
            $this->template->title ='第'.$list['page']->current_page.'页专栏 | 学开店专栏引导您更好的学习开店知识';
            $this->template->description ='第'.$list['page']->current_page.'页，开店专栏,创业专栏,1句话,开店知识';
            $this->template->keywords ='第'.$list['page']->current_page.'页专栏频道集学开店所有文章，为您打造学开店知识专栏，更好的学习开店知识！开店专栏引导您更好、更快、更方便的查阅您想看的学开店知识。创页开店、加盟开店、投资开店，上第'.$list['page']->current_page.'页学开店专栏一定能解决您在开店中遇到的困惑。';
        }
    }
    //end function

    /**
     * 行业新闻
     * @author 花文刚
     */
    public function action_industrynews(){
        $query = Arr::map("HTML::chars",$this->request->query());
        $content = View::factory("news/industrynews");
        $this->content->rightcontent = $content;
        $column = new Service_News_Column();

        $industry = common::getIndustryList ();
        $industry_child = array();
        //print_r($industry);

        $industry_id = arr::get($query, 'industry_id');
        $parent_id = ORM::factory('Industry',$industry_id)->parent_id;

        $selectColumn = $column->getColumnByName(common::getcolumnnames(arr::get($query, 'parent')));

        $query['id'] = $selectColumn['column_id'];
        if ($query['id']==""){
            $selectColumns = $column->getColumnByName(arr::get($query, 'parent'));
            $query['id'] = $selectColumns['column_id'];
        }

        $content->columnlist = $column->getParInfoById(arr::get($query, 'id'));
        $article_service = new Service_News_Article();
        $list = $article_service->getIndustryNews($parent_id,$industry_id,"hyxw",10);
        if($industry_id){
            if($parent_id){
                $industry_child = $industry[$parent_id]['secord'];
            }
            else{
                $industry_child = $industry[$industry_id]['secord'];
                $parent_id = $industry_id;
            }
        }


        $this->content->currentcolumnid = arr::get($query, 'id');
        $content->currentcolumnid = arr::get($query, 'id');
        $content->currentparentid = $selectColumn['parent_id'];
        $content->industry = $industry;
        $content->industry_child = $industry_child;
        $content->parent_id = $parent_id;
        $content->industry_id = $industry_id;
        $content->list = $list['list'];
        $content->page = $list['page'];

        if($industry_id){
            $industry_name = ORM::factory('Industry',$industry_id)->industry_name;
        }
        else{
            $industry_name = "";
        }

        //行业新闻 seo by 花文刚
        if (arr::get($query, 'page')!=""){
            $this->template->title= "第{$query['page']}页行业新闻_最新行业加盟新闻_行业新闻专区_一句话网";
            $this->template->keywords= "第{$query['page']}页,行业新闻,行业加盟新闻,行业新闻专区,一句话";
            $this->template->description= "第{$query['page']}页一句话网资讯频道行业新闻专区聚集大量的入住一句话网站的行业新闻、各个最新行业加盟新闻、行业推广新闻、行业品牌推广故事等，审查行业加盟、调研行业，上一句话资讯频道行业新闻专区可以学习大量的学开店知识。";

            if($industry_name){
                $this->template->title= "第{$query['page']}页{$industry_name}新闻_{$industry_name}最新新闻_一句话商机速配网";
                $this->template->keywords= "第{$query['page']}页，{$industry_name}新闻,{$industry_name}最新新闻,{$industry_name}新闻专区,一句话商机速配网";
                $this->template->description= "一句话商机速配网第{$query['page']}页{$industry_name}新闻专区提供{$industry_name}最新新闻、最新{$industry_name}新闻，这里有大量的{$industry_name}新闻、{$industry_name}行业动向等，找{$industry_name}新闻上一句话商机速配网，我们拥有最新、最及时、最全面的{$industry_name}新闻。";
            }
            else{
                $this->template->title= "第{$query['page']}页行业新闻_最新行业加盟新闻_行业新闻录_一句话商机速配网";
                $this->template->keywords= "第{$query['page']}页行业新闻,行业加盟新闻,行业新闻录,一句话商机速配网";
                $this->template->description= "第{$query['page']}页一句话商机速配网学做生意频道行业新闻录有大量的入住行业新闻、各个最新行业加盟新闻、行业推广新闻、行业品牌宣传新闻等，创业加盟找行业项目从了解行业新闻开始、审查行业加盟、调研行业项目，上一句话学做生意频道行业新闻专区可以学习大量的学做生意知识。";
            }
        }else{
            if($industry_name){
                $this->template->title= "{$industry_name}新闻_{$industry_name}最新新闻_一句话商机速配网";
                $this->template->keywords= "{$industry_name}新闻,{$industry_name}最新新闻,{$industry_name}新闻专区,一句话商机速配网";
                $this->template->description= "一句话商机速配网{$industry_name}新闻专区提供{$industry_name}最新新闻、最新{$industry_name}新闻，这里有大量的{$industry_name}新闻、{$industry_name}行业动向等，找{$industry_name}新闻上一句话商机速配网，我们拥有最新、最及时、最全面的{$industry_name}新闻。";
            }
            else{
                $this->template->title= "行业新闻_最新行业加盟新闻_行业新闻录_一句话商机速配网";
                $this->template->keywords= "行业新闻,行业加盟新闻,行业新闻录,一句话商机速配网";
                $this->template->description= "一句话商机速配网学做生意频道行业新闻录有大量的入住行业新闻、各个最新行业加盟新闻、行业推广新闻、行业品牌宣传新闻等，创业加盟找行业项目从了解行业新闻开始、审查行业加盟、调研行业项目，上一句话学做生意频道行业新闻专区可以学习大量的学做生意知识。";
            }
        }

    }
}
