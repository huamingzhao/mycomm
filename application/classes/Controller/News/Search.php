<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 资讯搜索页面
 * @author 龚湧
 *
 */
class Controller_News_Search extends Controller_News_Template{
    /**
     * 资讯搜索
     *
     * @author 龚湧
     */
    public function action_key(){

        $query = $this->request->query();
        if(Arr::get($query,"a")){
            $query['a'] = strip_tags(Arr::get($query,"a"));
        }
        $query = Arr::map("HTML::chars",$query);
        $search = new Service_News_Search();
        $keywords = Arr::get($query,"a");
        $keywords = secure::secureInput(secure::secureUTF($keywords));
        $page = "";
        $list = false;
        $is_tag = (int)Arr::get($query, "is_tag")?(int)Arr::get($query, "is_tag"):false;
        //是否是项目标签
        $is_project = (int)Arr::get($query, "is_project")?(int)Arr::get($query, "is_project"):false;

        if ($is_tag==false){//正常搜索
            $view = View::factory("news/searchlist");
            $this->template->title = mb_convert_encoding($keywords.'_'.$keywords.'资讯_一句话创业资讯',"utf-8");
            $this->template->keywords = mb_convert_encoding($keywords.','.$keywords.'资讯,一句话创业资讯',"utf-8");
            $this->template->description = mb_convert_encoding('这里提供大量的'.$keywords.'资讯，大量的资讯文字都是围绕'.$keywords.'展开的，是创业、投资、代理、加盟、开店、学习？你都能找到你想要的，关注'.$keywords.'请时刻关注一句话创业资讯！',"utf-8");
        }
        else{//标签搜索
            $view = View::factory("news/searchtaglist");
            $pn= Arr::get($query, 'page',0);
            if( $pn<=0 ){
                if($is_project==false){
                    $this->template->title = mb_convert_encoding($keywords.'_'.$keywords.'资讯_一句话创业资讯',"utf-8");
                    $this->template->keywords = mb_convert_encoding($keywords.','.$keywords.'资讯,一句话创业资讯',"utf-8");
                    $this->template->description = mb_convert_encoding('这里提供大量的'.$keywords.'资讯，大量的资讯文字都是围绕'.$keywords.'展开的，是创业、投资、代理、加盟、开店、学习？你都能找到你想要的，关注'.$keywords.'请时刻关注一句话创业资讯！',"utf-8");
                }
                else{
                    $this->template->title = mb_convert_encoding('【'.$keywords.'】_'.$keywords.'新闻_一句话创业资讯',"utf-8");
                    $this->template->keywords = mb_convert_encoding($keywords.','.$keywords.'新闻,一句话创业资讯',"utf-8");
                    $this->template->description = mb_convert_encoding($keywords.'专区提供'.$keywords.'新闻、'.$keywords.'资讯等，所有'.$keywords.'相关新闻资讯都是您创业、加盟、开店必读的文章，全面的'.$keywords.'新闻文章是一句话网为您提供的财富。',"utf-8");
                }

            }else{
                if($is_project==false){
                    $this->template->title= mb_convert_encoding($keywords.'_'.$keywords.'资讯_一句话创业资讯',"utf-8");
                    $this->template->keywords= mb_convert_encoding($keywords.','.$keywords.'资讯,一句话创业资讯',"utf-8");
                    $this->template->description= mb_convert_encoding('第'.$pn.'这里提供大量的'.$keywords.'资讯，大量的资讯文字都是围绕'.$keywords.'展开的，是创业、投资、代理、加盟、开店、学习？你都能找到你想要的，关注'.$keywords.'请时刻关注一句话创业资讯！',"utf-8");
                }
                else{
                    $this->template->title = mb_convert_encoding('【'.$keywords.'】_'.$keywords.'新闻_一句话创业资讯',"utf-8");
                    $this->template->keywords = mb_convert_encoding($keywords.','.$keywords.'新闻,一句话创业资讯',"utf-8");
                    $this->template->description = mb_convert_encoding('第'.$pn.$keywords.'专区提供'.$keywords.'新闻、'.$keywords.'资讯等，所有'.$keywords.'相关新闻资讯都是您创业、加盟、开店必读的文章，全面的'.$keywords.'新闻文章是一句话网为您提供的财富。',"utf-8");
                }

            }
        }
        $this->content->rightcontent = $view;
        //判断keywords是否合法
        $total = 0;
        if($keywords){
            $res = $search->searchZixun($keywords,$is_tag,false,$is_project);
            $page = Arr::get($res,"page");
            $list = Arr::get($res,"list");
            $total = Arr::get($res,"total");
        }
        //没有搜索或者搜索结果为空, 采用推荐文章
        if(!$list){
            $zxarticle = new Service_News_Article();
            $list = $zxarticle->getHotRecommend();
        }
        $view->page = $page;
        $view->list = $list;
        $view->total = $total;
        $view->keywords = $keywords;



    }
    
    /**
     * 行业新闻标签
     * @author 郁政
     */
    public function action_getHangYeNewsTagList(){
    	$view = View::factory("news/searchhangyetaglist");
    	$this->content->rightcontent = $view;
    	$get = Arr::map("HTML::chars", $this->request->query());
    	$keywords = Arr::get($get, 'w');
    	$keywords = secure::secureInput(secure::secureUTF($keywords));
    	$search = new Service_News_Search();
    	$res = array();
    	$res = $search->getHangYeNewsTagList($keywords);
    	//echo "<pre>";print_r($res);exit;
    	$page = Arr::get($res,"page");
       	$list = Arr::get($res,"list");
      	$total = Arr::get($res,"total");      	
    	$view->page = $page;
        $view->list = $list;
        $view->total = $total;
        $view->keywords = $keywords;    
        $this->template->title = mb_convert_encoding('【'.$keywords.'】_'.$keywords.'新闻_一句话商机速配网',"utf-8");
    	$this->template->keywords = mb_convert_encoding($keywords.','.$keywords.'新闻,一句话商机速配网',"utf-8");
    	$this->template->description = mb_convert_encoding('这里有大量包含'.$keywords.'的行业新闻，所有'.$keywords.'新闻都是您创业、加盟、开店必读的行业新闻文章，全面的'.$keywords.'新闻文章是一句话商机速配网为您提供的创业财富。',"utf-8");
    }
}