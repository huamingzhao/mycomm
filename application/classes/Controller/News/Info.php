<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 咨询首页
 * @author 许晟玮
 *
 */
class Controller_News_Info extends Controller_News_Template
{

    /**
     * 咨询详情页
     * @许晟玮
     */
    public function action_index()
    {
        $memcache = Cache::instance('memcache');

        $id = Arr::get($this->request->query(), 'id', 0);
        $get_month = Arr::get($this->request->query(), 'month', 0);
        //获取资讯文章数据
        $article_service = new Service_News_Article();
        $rs = $memcache->get('cache_zixun_info_' . $id);
        if (empty($rs)) {
            $rs = $article_service->getInfoRow($id);
            $memcache->set('cache_zixun_info_' . $id, $rs, 86400);
        }

        $month = date("Ym",$rs['article_intime']);

        if($month != $get_month){
            self::redirect("zixun/$month/$id.shtml",301);
        }
        else{

            $content = View::factory("news/info");

            $this->content->rightcontent = $content;

            $this->content->currentcolumnid = $rs['parent_id'];
            if ($rs === false || $rs['article_status'] != '2') {
                exit;
            } else {
                $content->info = $rs;

            }
            //如果不是后台编辑的,获取操作的用户名
            if ($rs['user_type'] == '2') {
                $user_service = new Service_User();
                $rs_user = $user_service->getUserInfoById($rs['user_id']);
                $content->author = $rs_user->user_name;

            } else {

            }


            //标签数组化
            if ($rs['article_tag'] != "") {
                $cache_like_art_id_arr = $memcache->get("cache_like_art_id_arr_" . $id);
                $cache_like_art_name_arr = $memcache->get("cache_like_art_name_arr_" . $id);
                $cache_like_art_time_arr = $memcache->get("cache_like_art_time_arr_" . $id);

                $tags_arr = explode(',', $rs['article_tag']);

                if ($cache_like_art_id_arr === null && $cache_like_art_name_arr === null) {
                    //获取您可能也喜欢的数据
                    $search_service = new Service_News_Search();
                    $like_art_id_arr = array();
                    $like_art_name_arr = array();
                    $like_art_time_arr = array();
                    $like_art_count = 0;
                    foreach ($tags_arr as $k => $tags_vss) {
                        $rs_search = $search_service->searchZixun($tags_vss);
                        if ($rs_search['list'] != false) {
                            foreach ($rs_search['list'] as $vss) {
                                //判断重复
                                if (count($like_art_id_arr) && !in_array($vss->article_id, $like_art_id_arr) && $vss->article_id != $id) {
                                    if ($like_art_count < 10) {
                                        $like_art_id_arr[] = $vss->article_id;
                                        $like_art_name_arr[] = strip_tags($vss->article_name);
                                        $like_art_time_arr[] = $vss->article_intime;
                                        $like_art_count++;
                                        //break;
                                    }
                                }
                            }
                        }
                    }
                    $memcache->set("cache_like_art_id_arr_" . $id, $like_art_id_arr, 86400);
                    $memcache->set("cache_like_art_name_arr_" . $id, $like_art_name_arr, 86400);
                    $memcache->set("cache_like_art_time_arr_" . $id, $like_art_time_arr, 86400);
                } else {
                    $like_art_id_arr = $cache_like_art_id_arr;
                    $like_art_name_arr = $cache_like_art_name_arr;
                    $like_art_time_arr = $cache_like_art_time_arr;
                }


            } else {
                $like_art_id_arr = array();
                $like_art_name_arr = array();
                $like_art_time_arr = array();
                $tags_arr = array();
            }

            $content->tags_arr = $tags_arr;
            $content->like_art_id_arr = $like_art_id_arr;
            $content->like_art_name_arr = $like_art_name_arr;
            $content->like_art_time_arr = $like_art_time_arr;

            $col_service = new Service_News_Column();
            //获取限制的栏目名称(父类)
            $rs_xz = zixun::getLimitCol();
            $col_id_arr = array();
            //获取对应的栏目数据
            foreach ($rs_xz as $vs) {
                $col_info = $col_service->getColumnByName($vs);
                $col_id_arr[] = $col_info['column_id'];
            }

            $show_act = "no";
            $allow = zixun::setContentApply(true);
            $show_content = zixun::setContentReplaceByAddKey($rs['article_content'], $allow, $id);
            //$content->show_content= htmlspecialchars_decode($rs['article_content']);
            $content->show_content = $show_content;

            $content->show_act = $show_act;

            $col_name = $memcache->get("cache_col_name_" . $id);
            if (empty($col_name)) {
                //获取栏目名称(子),如果没有选择子栏目,则获取父栏目
                if ($rs['column_id'] == "0" || $rs['column_id'] == "") {
                    $rs_col = $col_service->getCloInfo($rs['parent_id']);
                } else {
                    $rs_col = $col_service->getCloInfo($rs['column_id']);
                }
                if ($rs_col === false) {
                    $col_name = "";
                } else {
                    $col_name = $rs_col['column_name'];
                }
                $memcache->set("cache_col_name_" . $id, $col_name, 86400);
            }
            $content->col_name = $col_name;

            //获取当前子栏目下的所有文章ID

            $rs_partl = $article_service->getArtInfoCol($rs['column_id']);

            if (count($rs_partl) < 3) {
                $page_show = "no";
            } else {
                $page_show = "yes";
                //获取当前在第几个
                $up_art = $memcache->get("cache_up_art_" . $id);
                $down_art = $memcache->get("cache_down_art_" . $id);

                if (empty($up_art) || empty($down_art)) {
                    foreach ($rs_partl as $k => $vs_p) {
                        if ($vs_p['id'] == $id) {
                            $wz = $k + 1;
                            break;
                        }
                    }

                    //获取当前的上一篇,如果wz=1 ,则第一篇就是最后一篇
                    if ($wz == 1) {
                        $up_art = $rs_partl[count($rs_partl) - 1];
                    } else {
                        $up_art = $rs_partl[$wz - 2];
                    }


                    //获取下一篇,如果是最好一篇,则最后一篇是第一篇
                    if ($wz == count($rs_partl)) {
                        $down_art = $rs_partl[0];
                    } else {
                        $down_art = $rs_partl[$wz];
                    }
                    $memcache->set("cache_up_art_" . $id, $up_art, 86400);
                    $memcache->set("cache_down_art_" . $id, $down_art, 86400);
                }

                $content->up_art = $up_art;
                $content->down_art = $down_art;
            }

            $content->page_show = $page_show;

            //获取当前资讯的PV数
            $pv_count = $memcache->get("cache_zixun_pv_count_" . $id);
            if (empty($pv_count)) {
                $zixun_api_service = new Service_Api_Zixun();
                $pv_count_rs = $zixun_api_service->getPvNum($id);
                $pv_count = $pv_count_rs['data'];
                $memcache->set("cache_zixun_pv_count_" . $id, $pv_count, 86400);
            }

            //先取初始围观数 @花文刚
            $onlooker = $rs['article_onlooker'];
            $rand_pv = $pv_count + $onlooker;
            $content->pv = ceil($rand_pv);

            //seo
            $this->template->title = mb_convert_encoding($rs['article_name'] . '_' . $col_name . '_一句话创业资讯', "utf-8");
            $this->template->description = mb_convert_encoding('一句话创业资讯（www.yjh.com）投资创业平台分享' . $rs['article_name'] . '，各种' . $rs['article_tag'] . '创业机会协助你更好的选择项目。' . UTF8::substr(zixun::setContentReplace($rs['article_content']), 0, 70), "utf-8");

            $this->template->keywords = mb_convert_encoding($rs['article_name'] . "," . $rs['article_tag'] . "," . $col_name . ',一句话创业资讯', "utf-8");

            //右侧同栏目推荐
            $hotrecommend = $memcache->get("cache_zixun_hotrecommend_" . $id);
            if (empty($hotrecommend)) {
                if ($rs['column_id'] != "") {
                    $hotrecommend = $article_service->getHotColumnRecommend($rs['column_id']);
                } else {
                    $hotrecommend = $article_service->getHotColumnRecommend($rs['parent_id']);
                }
                $memcache->set("cache_zixun_hotrecommend_" . $id, $hotrecommend, 86400);
            }

            $this->content->hotrecommend = $hotrecommend;

            //获取父栏目

            $rs_col_parent = $col_service->getCloInfo($rs['parent_id']);
            $parent_col_name = $rs_col_parent['column_name'];

            //获取子栏目
            if ($rs['column_id'] == "0" || $rs['column_id'] == "") {
                $col_col_name = '';
            } else {
                $rs_col_col = $col_service->getCloInfo($rs['column_id']);
                $col_col_name = $rs_col_col['column_name'];
            }

            $content->parent_col_name = $parent_col_name;
            $content->col_col_name = $col_col_name;

            /***rewrite规则还没写好，暂时去掉生成静态文件代码 @花文刚
            $month = date("Ym",$rs['article_intime']);
            //生成静态html文件 @花文刚 20130917
            if (!is_dir("html-static")){
            mkdir("html-static");
            }
            if (!is_dir("html-static/zixun")){
            mkdir("html-static/zixun");
            }
            if (!is_dir("html-static/zixun/$month")){
            mkdir("html-static/zixun/$month");
            }
            $static_html = $this->template->render();
            $handle = fopen("html-static/zixun/$month/$id.shtml",'w');
            fputs($handle,$static_html);
            fclose($handle);
             */
        }



    }

    public function action_industryinfo(){
        $memcache = Cache::instance('memcache');

        $id = Arr::get($this->request->query(), 'id', 0);
        $get_month = Arr::get($this->request->query(), 'month', 0);
        //获取资讯文章数据
        $article_service = new Service_News_Article();
        $rs = $memcache->get('cache_industry_zixun_info_' . $id);
        if (empty($rs)) {
            $rs = $article_service->getIndustryInfoRow($id);
            $memcache->set('cache_industry_zixun_info_' . $id, $rs, 86400);
        }

        $month = date("Ym",$rs['article_intime']);

        if($month != $get_month){
            self::redirect("zixun/$month/$id.shtml",301);
        }
        else{

            $content = View::factory("news/industryinfo");

            $this->content->rightcontent = $content;

            //获取行业新闻的栏目id @花文刚
            $column = new Service_News_Column();
            $selectColumns = $column->getColumnByName("行业新闻");
            $this->content->currentcolumnid = $selectColumns['column_id'];
            if ($rs === false || $rs['article_status'] != '2') {
                exit;
            } else {
                $content->info = $rs;

            }
            //如果不是后台编辑的,获取操作的用户名
            if ($rs['user_type'] == '2') {
                $user_service = new Service_User();
                $rs_user = $user_service->getUserInfoById($rs['user_id']);
                $content->author = $rs_user->user_name;

            } else {

            }

            //标签数组化
            if ($rs['article_tag'] != "") {
                $cache_like_art_id_arr = $memcache->get("cache_like_art_id_arr_" . $id);
                $cache_like_art_name_arr = $memcache->get("cache_like_art_name_arr_" . $id);
                $cache_like_art_time_arr = $memcache->get("cache_like_art_time_arr_" . $id);

                $tags_arr = explode(',', $rs['article_tag']);

                if ($cache_like_art_id_arr === null && $cache_like_art_name_arr === null) {
                    //获取您可能也喜欢的数据
                    $search_service = new Service_News_Search();
                    $like_art_id_arr = array();
                    $like_art_name_arr = array();
                    $like_art_time_arr = array();
                    $like_art_count = 0;
                    foreach ($tags_arr as $k => $tags_vss) {
                        $rs_search = $search_service->searchZixun($tags_vss);
                        if ($rs_search['list'] != false) {
                            foreach ($rs_search['list'] as $vss) {
                                //判断重复
                                if (count($like_art_id_arr) && !in_array($vss->article_id, $like_art_id_arr) && $vss->article_id != $id) {
                                    if ($like_art_count < 10) {
                                        $like_art_id_arr[] = $vss->article_id;
                                        $like_art_name_arr[] = strip_tags($vss->article_name);
                                        $like_art_time_arr[] = $vss->article_intime;
                                        $like_art_count++;
                                        //break;
                                    }
                                }
                            }
                        }
                    }
                    $memcache->set("cache_like_art_id_arr_" . $id, $like_art_id_arr, 86400);
                    $memcache->set("cache_like_art_name_arr_" . $id, $like_art_name_arr, 86400);
                    $memcache->set("cache_like_art_time_arr_" . $id, $like_art_time_arr, 86400);
                } else {
                    $like_art_id_arr = $cache_like_art_id_arr;
                    $like_art_name_arr = $cache_like_art_name_arr;
                    $like_art_time_arr = $cache_like_art_time_arr;
                }


            } else {
                $like_art_id_arr = array();
                $like_art_name_arr = array();
                $like_art_time_arr = array();
                $tags_arr = array();
            }

            $content->tags_arr = $tags_arr;
            $content->like_art_id_arr = $like_art_id_arr;
            $content->like_art_name_arr = $like_art_name_arr;
            $content->like_art_time_arr = $like_art_time_arr;

            $col_service = new Service_News_Column();
            //获取限制的栏目名称(父类)
            $rs_xz = zixun::getLimitCol();
            $col_id_arr = array();
            //获取对应的栏目数据
            foreach ($rs_xz as $vs) {
                $col_info = $col_service->getColumnByName($vs);
                $col_id_arr[] = $col_info['column_id'];
            }

            $show_act = "no";
            $allow = zixun::setContentApply(true);
            $show_content = zixun::setContentReplaceOfIndustry($rs['article_content'], $allow, $id);
            //$content->show_content= htmlspecialchars_decode($rs['article_content']);
            $content->show_content = $show_content;

            $content->show_act = $show_act;

            //获取当前子栏目下的所有文章ID
            $content->page_show = "";

            //获取当前资讯的PV数
            $pv_count = $memcache->get("cache_zixun_pv_count_" . $id);
            if (empty($pv_count)) {
                $zixun_api_service = new Service_Api_Zixun();
                $pv_count_rs = $zixun_api_service->getPvNum($id);
                $pv_count = $pv_count_rs['data'];
                $memcache->set("cache_zixun_pv_count_" . $id, $pv_count, 86400);
            }

            //先取初始围观数 @花文刚
            $onlooker = $rs['article_onlooker'];
            $rand_pv = $pv_count + $onlooker;
            $content->pv = ceil($rand_pv);

            $parent_col_name = "行业新闻";
            $content->parent_col_name = $parent_col_name;

            //一级行业
            $content->parent_id = $rs['parent_id'];
            if($rs['parent_id']){
                $parent_name = ORM::factory('Industry',$rs['parent_id'])->industry_name;
            }
            else{
                $parent_name = "";
            }
            $content->parent_name = $parent_name;

            //二级行业
            $content->industry_id = $rs['industry_id'];
            if($rs['industry_id']){
                $industry_name = ORM::factory('Industry',$rs['industry_id'])->industry_name;
            }
            else{
                $industry_name = "";
            }
            $content->industry_name = $industry_name;

            //seo
            $this->template->title = mb_convert_encoding($rs['article_name'] . '_' . $industry_name. '最新新闻_一句话商机速配网', "utf-8");
            $this->template->description = mb_convert_encoding($industry_name. '最新新闻' . $rs['article_name'] . '，' . UTF8::substr(zixun::setContentReplace($rs['article_content']), 0, 110), "utf-8");
            $this->template->keywords = mb_convert_encoding($rs['article_name'] . ","  . $rs['article_tag'] .  ',一句话商机速配网', "utf-8");

        }
    }

    //end function

}