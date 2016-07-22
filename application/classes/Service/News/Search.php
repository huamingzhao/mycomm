<?php
/**
 * 资讯搜索service
 * @author 龚湧
 *
 */
class Service_News_Search extends searchservice{

    /**
     * 每页显示数量
     * @var unknown
     * @author 龚湧
     */
    public $limit = 10;

    /**
     * 搜索索引
     * @var unknown
     * @author 龚湧
     */
    public $index = "zixun";

    public function index(){
        $search = Kohana::$config->load("search");
        return Arr::get($search,"zixun_search");
    }

    /**
     * 资讯搜索
     * @author 龚湧
     * @$from调用接口，为了区分使用里面的分页地址
     */
    public function searchZixun($keywords,$is_tag=false,$from=false,$is_project=false){
        $service_search_api = new Service_Api_Search();
        $get = Request::initial()->query();

        $page_num = Arr::get($get,"page")?intval(Arr::get($get,"page")):1;
        $off = ($page_num - 1) * $this->limit;

        $sort = "";
        //是否为搜索标签
        if($is_tag){
            $keywords = "articleTag:\"".$keywords.'"';
            $sort = 'articleId desc';
        }
        //同时是项目新闻标签搜索
        if($is_project){
            $keywords .=" and parentId:29";
        }
        //solr接口返回搜索结果
        $solr_result = $service_search_api->getSearchZixun($keywords,$off,$this->limit,$sort);
        //print_r($solr_result);exit();

        /*
        $this->search->SetServer(self::$searchServerIp, self::$searchServerPort);
        $this->search->SetConnectTimeout(self::$searchServerTimeout);
        $this->search->SetArrayResult(true);
        //分页
        $page_num = Arr::get($get,"page")?intval(Arr::get($get,"page")):1;
        $off = ($page_num - 1) * $this->limit;
        $this->search->SetLimits($off, $this->limit);
        //设置搜索模式
        $this->search->SetMatchMode(SPH_MATCH_EXTENDED2);
        //设置权重
        $this->search->SetFieldWeights(
                array(
                        'article_name'=>20,
                        'article_tag' =>30,
                        'article_content' =>15
                )
        );
        //通过审核的才会被搜索到
        $this->search->SetFilter("article_status", array(2));
        //标签搜索
        if($is_tag){
            $keywords = "@article_tag ".$keywords;
        }
        //分词
        //$kes = $this->search->BuildKeywords("名称中国航天", $this->index,true);
        //var_dump($kes);
        //按照权重排序
        $this->search->SetSortMode('SPH_SORT_EXPR', '@weight');
        $result = $this->search->Query($keywords,$this->index());
        $total_count = Arr::get($result,"total");
        */
        $total_count = Arr::get($solr_result,"total");
        //$from用来区分使用不同的分页结构
        if($from){ //这里是专栏使用
            if($total_count>50){
                $total_count = 50; //专栏设定最多相关文章只显示50条
            }
            $page = Pagination::factory(
                    array(
                            'total_items'       => $total_count,
                            'items_per_page'    => $this->limit,
                            'view' => 'pagination/Simple',
                            'current_page'   => array('source' => 'zhuanlan', 'key' => 'page'),
                    )
            );
        }else{
            $page = Pagination::factory(
                    array(
                            'total_items'       => $total_count,
                            'items_per_page'    => $this->limit,
                            'view'           => 'pagination/zixun',
                    )
            );
        }
        /*
        //搜索失败处理
        if($result ===false){
            echo $this->search->GetLastError();exit();
            throw new Kohana_Exception("搜索失败");
        }
        */
        //对结果进行处理,获取匹配结果得到的文章id
        $res = array();
        $res['list'] = false;//初始化列表
        $matches = Arr::get($solr_result,"matches");
        if($matches){
            $service_zixun = new Service_News_Zixun();
            $higl = Arr::get($solr_result,"highlighting");
            foreach ($matches as $key=>$article_id){
                 $articleinfo=$service_zixun->getArticleInfoById($article_id);
                 if($articleinfo){
                     $abstract = $articleinfo->as_array();
                 }else{
                     $abstract=array();
                 }
                 $hl = isset($higl[$article_id])?$higl[$article_id]:0;
                 if($hl){
                     $title = Arr::get($hl,"articleName");
                     if($title){
                         $abstract['article_name'] = $title[0];
                     }
                     $tag = Arr::get($hl,"articleTag");
                     if($tag){
                         $abstract['article_tag'] = $tag[0];
                     }
                     $content = Arr::get($hl,"articleContent");
                     if($content){
                         $abstract['article_content'] = $content[0];
                     }
                 }
                 //var_dump($hl);exit();
                 $res['list'][] = (object)$abstract;
                 //print_r($res);exit();
            }
        }

        $res['page'] = $page;
        $res['total'] = $total_count;
        //$res['highlighting'] = Arr::get($solr_result,"highlighting");
        //print_r($res);exit();
        return $res;
    }

    /**
     * 资讯搜索
     * @author 龚湧
     * @$from调用接口，为了区分使用里面的分页地址
     */
    public function searchZixunZl($tags,$keywords,$is_tag=false,$from=false){
        $service_search_api = new Service_Api_Search();
        $get = Request::initial()->query();

        $page_num = Arr::get($get,"page")?intval(Arr::get($get,"page")):1;
        $off = ($page_num - 1) * $this->limit;

        //是否为搜索标签
        if($is_tag){
            $keywords = "articleTag:\"".$keywords.'"';
        }
        //solr接口返回搜索结果
        $solr_result = $service_search_api->getSearchSpecialColumn($tags,$keywords,$off,$this->limit);
        //print_r($solr_result);exit();

        /*
         $this->search->SetServer(self::$searchServerIp, self::$searchServerPort);
        $this->search->SetConnectTimeout(self::$searchServerTimeout);
        $this->search->SetArrayResult(true);
        //分页
        $page_num = Arr::get($get,"page")?intval(Arr::get($get,"page")):1;
        $off = ($page_num - 1) * $this->limit;
        $this->search->SetLimits($off, $this->limit);
        //设置搜索模式
        $this->search->SetMatchMode(SPH_MATCH_EXTENDED2);
        //设置权重
        $this->search->SetFieldWeights(
                array(
                        'article_name'=>20,
                        'article_tag' =>30,
                        'article_content' =>15
                )
        );
        //通过审核的才会被搜索到
        $this->search->SetFilter("article_status", array(2));
        //标签搜索
        if($is_tag){
        $keywords = "@article_tag ".$keywords;
        }
        //分词
        //$kes = $this->search->BuildKeywords("名称中国航天", $this->index,true);
        //var_dump($kes);
        //按照权重排序
        $this->search->SetSortMode('SPH_SORT_EXPR', '@weight');
        $result = $this->search->Query($keywords,$this->index());
        $total_count = Arr::get($result,"total");
        */
        $total_count = Arr::get($solr_result,"total");
        //$from用来区分使用不同的分页结构
        if($from){ //这里是专栏使用
            if($total_count>50){
                $total_count = 50; //专栏设定最多相关文章只显示50条
            }
            $page = Pagination::factory(
                    array(
                            'total_items'       => $total_count,
                            'items_per_page'    => $this->limit,
                            'view' => 'pagination/Simple',
                            'current_page'   => array('source' => 'zhuanlan', 'key' => 'page'),
                    )
            );
        }else{
            $page = Pagination::factory(
                    array(
                            'total_items'       => $total_count,
                            'items_per_page'    => $this->limit,
                            'view'           => 'pagination/zixun',
                    )
            );
        }
        /*
         //搜索失败处理
        if($result ===false){
        echo $this->search->GetLastError();exit();
        throw new Kohana_Exception("搜索失败");
        }
        */
        //对结果进行处理,获取匹配结果得到的文章id
        $res = array();
        $res['list'] = false;//初始化列表
        $matches = Arr::get($solr_result,"matches");
        if($matches){
            $service_zixun = new Service_News_Zixun();
            $higl = Arr::get($solr_result,"highlighting");
            foreach ($matches as $key=>$article_id){
                $articleinfo=$service_zixun->getArticleInfoById($article_id);
                if($articleinfo){
                    $abstract = $articleinfo->as_array();
                }else{
                    $abstract=array();
                }
                $hl = isset($higl[$article_id])?$higl[$article_id]:0;
                if($hl){
                    $title = Arr::get($hl,"articleName");
                    if($title){
                        $abstract['article_name'] = $title[0];
                    }
                    $tag = Arr::get($hl,"articleTag");
                    if($tag){
                        $abstract['article_tag'] = $tag[0];
                    }
                    $content = Arr::get($hl,"articleContent");
                    if($content){
                        $abstract['article_content'] = $content[0];
                    }
                }
                //var_dump($hl);exit();
                $res['list'][] = (object)$abstract;
                //print_r($res);exit();
            }
        }

        $res['page'] = $page;
        $res['total'] = $total_count;
        //$res['highlighting'] = Arr::get($solr_result,"highlighting");
        //print_r($res);exit();
        return $res;
    }
    
    /**
     * 行业新闻标签
     * @author 郁政
     */
    public function getHangYeNewsTagList($keywords){
    	$service_search_api = new Service_Api_Search();
    	$get = Request::initial()->query();
        $page_num = Arr::get($get,"page")?intval(Arr::get($get,"page")):1;
        $off = ($page_num - 1) * $this->limit;
        $keywords = '"'.$keywords.'"';
    	$solr_result = $service_search_api->getHangYeNewsTagList($keywords,$off,$this->limit);
    	//echo "<pre>";print_r($solr_result);exit;
    	$total_count = Arr::get($solr_result,"total");
    	$page = Pagination::factory(
      		array(
   				'total_items'       => $total_count,
         		'items_per_page'    => $this->limit,
             	'view'           => 'pagination/zixun',
      			'current_page' => array('source' => 'hangyeNewsTag', 'key' => 'page')
        	)
    	);
    	$res = array();
        $res['list'] = false;//初始化列表
        $matches = Arr::get($solr_result,"matches");
        if($matches){
            $service_zixun = new Service_News_Zixun();
            $higl = Arr::get($solr_result,"highlighting");
            foreach ($matches as $key=>$article_id){
                $articleinfo=$service_zixun->getHangYeArticleInfoById($article_id);
                if($articleinfo){
                    $abstract = $articleinfo->as_array();
                }else{
                    $abstract=array();
                }
                $hl = isset($higl[$article_id])?$higl[$article_id]:0;
                if($hl){
                    $title = Arr::get($hl,"articleName");
                    if($title){
                        $abstract['article_name'] = $title[0];
                    }
                    $tag = Arr::get($hl,"articleTag");
                    if($tag){
                        $abstract['article_tag'] = $tag[0];
                    }
                    $content = Arr::get($hl,"articleContent");
                    if($content){
                        $abstract['article_content'] = $content[0];
                    }
                }
                //var_dump($hl);exit();
                $res['list'][] = (object)$abstract;
                //print_r($res);exit();
            }
        }
        $res['page'] = $page;
        $res['total'] = $total_count;
        return $res;
    }


}