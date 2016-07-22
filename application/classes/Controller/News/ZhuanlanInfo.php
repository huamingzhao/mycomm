<?php defined('SYSPATH') or die('No direct script access.');
class Controller_News_ZhuanlanInfo extends Controller_News_Template{

    /**
     * 专栏详情页
     * @赵路生
     */
    public function action_index(){
        $content= View::factory("news/zhuanlan_info");
        $this->content->rightcontent= $content;

        $id= intval(Arr::get($this->request->query(), 'id',0));
        $article_service= new Service_News_Zhuanlan();
        //设置右边专栏推荐
        $tui_list = $article_service->getTjZl();
        $this->content->tui_list= $tui_list;
        //获取资讯文章数据

        $article = $article_service->getZlInfoRow($id);
        //获取相关文章数据
        $api_search_service = new Service_News_Search();
        $zixun_service= new Service_News_Article();
        $column_service = new Service_News_Column();
        $rs_zixun = array();
        if($article){
            $key_word = str_replace(',','',$article->zl_key);
            $api_search_service->limit = 20;
            $rs_search= $api_search_service->searchZixunZl($article->zl_key,$key_word,0,4);
            if( !empty( $rs_search ) ){
                //$ids= $rs_search['matches'];
                $page = Arr::get($rs_search,"page");
                $list = Arr::get($rs_search,"list");
                $total = Arr::get($rs_search,"total");
                //获取资讯文章对应的父栏目
                if(!empty( $list )){
                    foreach($list as $xv){
                        //获取栏目名称(子),如果没有选择子栏目,则获取父栏目
                        if( $xv->column_id =="0" || $xv->column_id =="" ){
                            $rs_col= $column_service->getCloInfo( $xv->parent_id );
                        }else{
                            $rs_col= $column_service->getCloInfo( $xv->column_id );
                        }
                        if( $rs_col===false ){
                            $col_name= "";
                        }else{
                            $col_name= $rs_col['column_name'];
                        }
                        $xv->column_name = $col_name;

                    }
                }
            }//
            $this->content->loop_count = true;//用在template右侧循环标志
            $this->content->currentcolumnid = 28;//写死的 用来显示左边导航栏的变红
            $this->content->action = 'zhuanlan';
            $content->article = $article;
            $content->rel_list = $list;
            $content->page = $page;
            $content->total = $total;

            //seo优化
            if($page->current_page==1){
                $this->template->title =$article->zl_title.' | 开店专栏_一句话';
                $this->template->keywords =$article->zl_title.','.$article->zl_key.',开店专栏,一句话';
                $this->template->description =mb_substr($article->zl_introduce,0,110,'UTF-8');
            }else{
                $this->template->title ='第'.$page->current_page.'页'.$article->zl_title.' | 开店专栏_一句话';
                $this->template->keywords =$article->zl_title.','.$article->zl_key.',开店专栏,一句话';
                $this->template->description ='第'.$page->current_page.'页'.mb_substr($article->zl_introduce,0,110,'UTF-8');
            }

        }else{
            exit;
             //$content = View::factory('platform/page_404');
            //$this->content->rightcontent = $content;
        }
    }//end function

}