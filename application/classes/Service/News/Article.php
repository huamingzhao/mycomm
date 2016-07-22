<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 资讯相关
 * @author 许晟玮
 *
 */
class Service_News_Article{

    /**
     *获取单条的文章数据
     * @author许晟玮
     */
    public function getInfoRow( $id ){
        $orm= ORM::factory( 'Zixun_Article',$id );
        if( $orm->loaded()===false ){
            return false;
        }else{
            return $orm->as_array();
        }
    }
    //end function
    /**
     *获取单条的文章的顶踩数据
     * @author赵路生
     */
    public function getInfoUpandDownRow($id ){
        $orm= ORM::factory( 'Zixun_Article',$id );
        if($orm->loaded()=== false){
            return array(
                    'ding'=>0,
                    'cai'=>0
            );
        }else{
            return array(
                    'ding'=>$orm->article_ding,
                    'cai'=>$orm->article_cai
            );
        }
    }//function end
    /**
     * 文章列表
     * @author许晟玮
     */
    public function getList( $page_size=10 ){
        //$page_size= 10;
        $orm= ORM::factory( 'Zixun_Article' );
        $orm->where('article_show_type', '!=', '2')->where('article_status', '=', '2')->reset(false);
        $memcache = Cache::instance ('memcache');
        //count 取文章总数放入缓存 节省数据库负荷 @author 花文刚
        $count = $memcache->get('article_count_cache_zixun');
        if(empty($count)){
            $count= $orm->count_all();
            $memcache->set('article_count_cache_zixun', $count);
        }
        $page = Pagination::factory(array(
                'current_page'   => array('source' => 'zixun', 'key' => 'page'),
                'total_items'    => $count,
                'items_per_page' => $page_size,
                'view'           => 'pagination/zixun',
        ));
        $list= $orm->limit($page->items_per_page)->offset($page->offset)->order_by('article_addtime', 'DESC')->find_all( );
        $result= array();
        $result['list']= $list;
        $result['page']= $page;
        return $result;


    }
    //end function

    /**
     * 顶/踩操作
     * @param $id 文章ID
     * @param $act 动作 up down
     * @author许晟玮
     */
    public function setAppreciation( $id,$act='up' ){
        $orm= ORM::factory('Zixun_Article',$id);
        if( $orm->loaded()===false ){
            return false;
        }else{
            $rs= $orm->as_array();

            if( $act=="up" ){
                $eof= Arr::get($_COOKIE, 'article_ding'.$id,'0');
                if( $eof=='0' ){
                    $up_num= ceil( $rs['article_ding'] )+1;
                    $orm->article_ding= $up_num;
                    setcookie( 'article_ding'.$id,'1' );
                }else{
                    return false;
                }
            }else{
                $eof= Arr::get($_COOKIE, 'article_cai'.$id,'0');
                if( $eof=='0' ){
                    $down_num= ceil( $rs['article_cai'] )+1;
                    $orm->article_cai= $down_num;
                    setcookie( 'article_cai'.$id,'1' );
                }else{
                    return false;
                }
            }
            $orm->update();return true;

        }

    }
    //end function

    /**
     * 文章栏目列表
     * @param int $column_id
     * @param int $type 为0不确定，1为第一级栏目，2为第二级栏目
     * @author周进
     */
    public function getListByColumnId($column_id=0,$type=0,$page_size=10){
        $parent_id = ORM::factory('Zixun_Column')->where("column_type","=","0")->where('column_id', '=', $column_id)->find();
        //$page_size= 10;
        $orm = ORM::factory('Zixun_Article');
        if ($type==0&&$parent_id->parent_id==0)
            $orm->where('parent_id', '=', $column_id);
        elseif ($type==0&&$parent_id->parent_id!=0)
            $orm->where('column_id', '=', $column_id);
        elseif ($type==1)
            $orm->where('parent_id', '=', $column_id);
        elseif ($type==2)
            $orm->where('column_id', '=', $column_id);
        else
            $orm->and_where_open()->where('parent_id', '=', $column_id)->or_where('column_id', '=', $column_id)->and_where_close();
        $orm->where('article_status', '=', '2')->reset(false);
        $count= $orm->count_all();
        $page = Pagination::factory(array(
                'current_page'   => array('source' => 'zixun', 'key' => 'page'),
                'total_items'    => $count,
                'items_per_page' => $page_size,
                'view'           => 'pagination/zixun',
        ));
        $list= $orm->limit($page->items_per_page)->offset($page->offset)->order_by('article_addtime', 'DESC')->find_all( );
        $result['list']= $list;
        $result['page']= $page;
        return $result;
    }
    //end function
    /**
     * 项目向导子栏目打开推荐文章
     * @author周进
     */
    public function getLastByColumnId($column_id=0){
        $parent_id = ORM::factory('Zixun_Column')->where('column_id', '=', $column_id)->find();
        $page_size= 10;
        $orm = ORM::factory('Zixun_Article');
        if ($parent_id->parent_id==0){
            $column = ORM::factory('Zixun_Column')->where('parent_id', '=', $column_id)->order_by('column_order','asc')->find();
            $article = $orm->where('column_id','=', $column->column_id)->where('article_status', '=', '2')->order_by('article_addtime','desc')->find();
        }
        else{
            $article = $orm->where('column_id','=', $column_id)->where('article_status', '=', '2')->order_by('article_addtime','desc')->find();
        }
        return $article;
    }
    //end function

    /**
     * 模板右侧人们标签推荐文章
     * @author周进
     */
    public function getHotRecommend(){
        $article = ORM::factory('Zixun_Article')->where('article_recommend','=', 1)->where('article_status', '=', '2')->order_by('article_addtime','asc')->limit(10)->find_all()->as_array();
        return $article;
    }
    //end function

    /**
     * 模板右侧热门栏目推荐文章
     * @author周进
     */
    public function getHotColumnRecommend($id){
        $result  = array('title'=>'','list'=>'');
        $column  = new Service_News_Column();
        $columns = $column->getCloInfo($id);
        $result['title'] = $columns['column_name'];
        $article = ORM::factory('Zixun_Article')
                    ->where_open()
                    ->where('column_id','=',$id)->or_where('parent_id','=',$id)
                    ->where_close()
                    ->where('article_recommend','=', 1)
                    ->where('article_status', '=', '2')->order_by('aritcle_modtime','desc')->limit(10)->find_all()->as_array();
        $result['list'] = $article;
        return $result;
    }
    //end function

    /**
     * 传入子栏目ID 获取对应的所有文章数据
     * @author许晟玮
     */
    public function getArtInfoCol( $col_id ){
        $orm= ORM::factory('Zixun_Article');
        $orm->where('column_id', '=', $col_id);
        $orm->where('article_status','=','2');
        $result= $orm->find_all()->as_array();

        $orm= ORM::factory('Zixun_Article');
        $orm->where('article_status','=','2');
        $count= $orm->where('column_id', '=', $col_id)->count_all();
        $return= array();
        if( $count>0 ){
            foreach( $result as $k=>$v ){
                $return[$k]['id']= $v->article_id;
                $return[$k]['name']= $v->article_name;
                $return[$k]['article_intime']= $v->article_intime;
            }
        }else{
        }

        return $return;

    }
    //end function

    /**
     * BI排行文章列表
     * @author周进
     */
    public function getBiList($data){
        $ids = array(0);
        if ($data!=""){
            foreach ($data['data'] as $k=>$v){
                $ids[]=$v['pnid'];
            }
        }

        $orm= ORM::factory( 'Zixun_Article' );
        $orm->where('article_status', '=', '2');
        if(count($ids)){
        	$orm->where('article_id','in',$ids);
        }
        
        $list= $orm->limit(15)->order_by('article_addtime', 'DESC')->find_all()->as_array();
        return $list;
    }
    //end function
    
    /**
     * 行业新闻文章列表
     * @author 郁政
     */
    public function getHangYeNewsList($data){
        $ids = array(0);
        if ($data!=""){
            foreach ($data['data'] as $k=>$v){
                $ids[]=$v['pnid'];
            }
        }

        $orm= ORM::factory( 'Zixun_IndustryArticle' );
        $orm->where('article_status', '=', '2');
        if(count($ids)){
        	$orm->where('article_id','in',$ids);
        }
        
        $list= $orm->find_all()->as_array();
        return $list;
    }
    //end function

    /**
     * onlooker排行文章列表
     * @author花文刚
     */
    public function getListWithOnlooker($begin,$end){
        $orm= ORM::factory( 'Zixun_Article' );
        $list= $orm->where('article_status', '=', '2')->where('article_intime','>',$begin)->where('article_intime','<',$end)->limit(15)->order_by('article_onlooker', 'DESC')->find_all( )->as_array();
        return $list;
    }
    //end function

    /**
     *获取单条的项目文章数据
     * @author周进
     */
    public function getInfoRowByxiangmu( $id ){
        $orm= ORM::factory( 'Zixun_Article',$id )->where('article_status', '=', '1');
        if( $orm->loaded()===false ){
            return false;
        }else{
            return $orm;
        }
    }
    //end function

    /**
     * 传入项目ID 获取对应的资讯ID list方法,作用于项目官网的显示
     * @author许晟玮
     */
    public function getProjectArticleList( $project_id,$page_size=15 ){

        if( ceil( $project_id )<=0 ){
            return false;
        }else{
        }
        //list
        $query= DB::select( 'zx_article.*' )->from('zx_article');
        $query->join('zx_project_article')->on('zx_project_article.article_id','=','zx_article.article_id');
        $query->where('zx_project_article.project_id', '=', $project_id);
        $query->where('zx_project_article.tg_status', '=', '1');
        $query->where('zx_project_article.tg_recommend', '=', '1');
        $query->where('zx_article.article_status', '=', '2');

        $result= $query->execute()->as_array();

        $count= count($result);
        $page = Pagination::factory(array(
                'current_page'   => array('source' => 'projectzixun', 'key' => 'page'),
                'total_items'    => $count,
                'items_per_page' => $page_size,
                'view'           => 'pagination/zixun',
        ));

        $list= $query->limit($page->items_per_page)->offset($page->offset)->order_by('zx_article.article_id', 'DESC')->execute()->as_array();


        $result= array();
        $result['list']= $list;
        $result['page']= $page;
        return $result;

    }
    //end function

    /**
     * 项目资讯关联操作
     * $type=1 增加  2-修改
     * @author许晟玮
     */
    public function setProjectArticle( $info=array(),$type='1' ){
        if( empty( $info ) ){
            return false;
        }else{
        }
        $article_id= Arr::get($info, 'article_id',0);
        $project_id= Arr::get($info, 'project_id',0);
        $tg_status= Arr::get($info, 'tg_status',0);
        $tg_recommend= Arr::get($info, 'tg_recommend',0);
        $id= Arr::get($info, 'id',0);
        //项目ID 不是0，关联进项目资讯表 $info by 许晟玮
        if( $project_id>0  ){
            if( ceil($article_id)>0 ){
                if( $type=='1' ){
                    $orm= ORM::factory('ProjectArticle');
                    $orm->article_id= ceil($article_id);
                    $orm->project_id= ceil($project_id);
                    $orm->tg_status= $tg_status;
                    $orm->tg_recommend= $tg_recommend;
                    $orm->create();

                }else{
                    if( ceil( $id<=0 ) ){
                        return false;
                    }else{
                        $orm= ORM::factory('ProjectArticle',$id);
                        $orm->article_id= ceil($article_id);
                        $orm->project_id= ceil($project_id);
                        $orm->tg_status= $tg_status;
                        $orm->tg_recommend= $tg_recommend;

                        $orm->update();

                    }
                }

            }else{
                return false;
            }
        }else{
            return false;
        }


    }

    //end function

    /**
     * 获取会员投稿的资讯总数
     * @author许晟玮
     */
    public function getUserZixunCount( $user_id ){
        if( ceil($user_id)<=0 ){
            return false;
        }else{
        }
        $orm= ORM::factory('Zixun_Article');
        //count
        $orm->where('user_id', '=', $user_id)->where('article_status','!=','4');
        $count= $orm->count_all();
        return $count;
    }
    //end function

    /**
     * 传入项目ID 用户ID 获取对应的数据
     * @许晟玮
     *
     */
    public function getUserProjectZixunCount( $pid,$uid ){
        if( ceil( $pid )<=0 || ceil( $uid )<=0 ){
            return false;
        }else{
            $query= DB::select( 'zx_article.*','zx_project_article.project_id' );
            $query->from('zx_article');
            $query->join('zx_project_article')->on('zx_project_article.article_id','=','zx_article.article_id');
            $query->where('zx_project_article.project_id', '=', $pid);
            $query->where('zx_article.article_status', '!=', '4');
            $query->where('zx_article.user_id', '=', $uid);
            $query->where('zx_article.user_type', '=', '2');
            $result= $query->execute()->as_array();
            return $result;
        }
    }

    //end function


    /**
     * 获取对应的用户发布的项目文章list
     * @author许晟玮
     */
    public function getUserTouGaoZixunList( $uid,$page_size='15',$project_id='0' ){
        if( ceil( $uid )<=0 ){
            return false;
        }else{
        }

        $query= DB::select( 'zx_article.*','zx_project_article.*' )->from('zx_article');
        $query->join('zx_project_article')->on('zx_project_article.article_id','=','zx_article.article_id');
        $query->where('zx_article.article_status', '!=', '4');
        $query->where('zx_article.user_id', '=', $uid);
        $query->where('zx_article.user_type', '=', '2');
        if( ceil( $project_id )>0 ){
            $query->where('zx_project_article.project_id', '=', $project_id);
        }
        $result= $query->execute()->as_array();
        $count= count($result);
        $page = Pagination::factory(array(
                //'current_page'   => array('source' => 'zixun', 'key' => 'page'),
                'total_items'    => $count,
                'items_per_page' => $page_size,
                //'view'           => 'pagination/zixun',
        ));

        $list= $query->limit($page->items_per_page)->offset($page->offset)->order_by('zx_article.article_id', 'DESC')->execute()->as_array();


        $result= array();
        $result['list']= $list;
        $result['page']= $page;
        return $result;

    }
    //end function

    /**
     * 传入文章ID 获取对应关联的项目文章数据
     * @author许晟玮
     */
    public function getProjectZixunRow( $aid ){
        if( ceil( $aid )<=0 ){
            return false;
        }
        $orm= ORM::factory('ProjectArticle');
        $orm->where('article_id', '=', $aid);
        $result= $orm->find()->as_array();
        return $result;

    }
    //end function


    /**
     * 传入会员ID 获取对应的资讯 list方法,作用于项目官网的显示
     * @author许晟玮
     */
    public function getProjectArticleListByUid( $uid,$page_size=15 ){

        if( ceil( $uid )<=0 ){
            return false;
        }else{
        }
        //list
        $query= DB::select( 'zx_article.*','zx_project_article.*' )->from('zx_article');
        $query->join('zx_project_article')->on('zx_project_article.article_id','=','zx_article.article_id');
        $query->where('zx_article.user_id', '=', $uid);
        $query->where('zx_project_article.tg_status', '=', '1');
        $query->where('zx_project_article.tg_recommend', '=', '1');
        $query->where('zx_article.article_status', '=', '2');

        $result= $query->execute()->as_array();

        $count= count($result);
        $page = Pagination::factory(array(
                'current_page'   => array('source' => 'zixun', 'key' => 'page'),
                'total_items'    => $count,
                'items_per_page' => $page_size,
                'view'           => 'pagination/zixun',
        ));

        $list= $query->limit($page->items_per_page)->offset($page->offset)->order_by('zx_article.article_id', 'DESC')->execute()->as_array();


        $result= array();
        $result['list']= $list;
        $result['page']= $page;
        return $result;

    }
    //end function


    /**
     * 传入项目ID 判断是否发过文章
     * @author 许晟玮
     */
    public function getProjectZixunTof( $project_id ){
        if( ceil( $project_id )<=0 ){
            return false;
        }else{
            $orm= ORM::factory('ProjectArticle');
            $orm->where('project_id', '=', $project_id);
            $count= $orm->count_all();
            return $count;
        }

    }
    //end function


    /**
     * 项目官网用，更多相关新闻
     * @author许晟玮
     */
    public function getUserTouGaoZixunListProjectInfo( $uid,$page_size='15',$project_id='0' ){
        if( ceil( $uid )<=0 ){
            return false;
        }else{
        }

        $query= DB::select( 'zx_article.*','zx_project_article.*' )->from('zx_article');
        $query->join('zx_project_article')->on('zx_project_article.article_id','=','zx_article.article_id');
        $query->where('zx_article.article_status', '=', '2');
        $query->where('zx_article.user_id', '=', $uid);
        $query->where('zx_article.user_type', '=', '2');
        if( ceil( $project_id )>0 ){
            $query->where('zx_project_article.project_id', '=', $project_id);
        }
        $result= $query->execute()->as_array();
        $count= count($result);
        $page = Pagination::factory(array(
                //'current_page'   => array('source' => 'zixun', 'key' => 'page'),
                'total_items'    => $count,
                'items_per_page' => $page_size,
                //'view'           => 'pagination/zixun',
        ));

        $list= $query->limit($page->items_per_page)->offset($page->offset)->order_by('zx_article.article_id', 'DESC')->execute()->as_array();


        $result= array();
        $result['list']= $list;
        $result['page']= $page;
        return $result;

    }

    /**
     * 行业新闻列表
     * @author 花文刚
     */
    public function getIndustryNews($parent_id=0,$industry_id=0,$source='hyxw',$page_size=10){
        $orm = ORM::factory('Zixun_IndustryArticle');
        if($industry_id>0){
            if($parent_id>0){
                $orm->where('industry_id', '=', $industry_id);
            }
            else{
                $orm->where('parent_id', '=', $industry_id);
            }
        }

        $orm->where('article_status', '=', '2')->reset(false);
        $count= $orm->count_all();
        $page = Pagination::factory(array(
            'current_page'   => array('source' => $source, 'key' => 'page'),
            'total_items'    => $count,
            'items_per_page' => $page_size,
            'view'           => 'pagination/zixun',
        ));
        $list= $orm->limit($page->items_per_page)->offset($page->offset)->order_by('article_checktime', 'DESC')->find_all();
        $return= array();
        foreach($list as $k=>$v){
            $return[$k]['article_id'] = $v->article_id;
            $return[$k]['article_name'] = $v->article_name;
            $return[$k]['article_summary'] = $v->article_summary;
            $return[$k]['article_content'] = $v->article_content;
            $return[$k]['article_status'] = $v->article_status;
            $return[$k]['parent_id'] = $v->parent_id;
            $return[$k]['industry_id'] = $v->industry_id;
            $return[$k]['aritcle_modtime'] = $v->aritcle_modtime;
            $return[$k]['article_intime'] = $v->article_intime;
            $return[$k]['article_checktime'] = $v->article_checktime;
            $return[$k]['article_ding'] = $v->article_ding;
            $return[$k]['article_cai'] = $v->article_cai;
            $return[$k]['article_onlooker'] = $v->article_onlooker;
            $return[$k]['article_tag'] = $v->article_tag;
            $return[$k]['article_img'] = $v->article_img;
        }

        $result['list']= $return;
        $result['page']= $page;
        return $result;
    }

    /**
     *获取单条行业文章数据
     * @author 花文刚
     */
    public function getIndustryInfoRow( $id ){
        $orm= ORM::factory( 'Zixun_IndustryArticle',$id );
        if( $orm->loaded()===false ){
            return false;
        }else{
            return $orm->as_array();
        }
    }

    /**
     * 顶/踩操作 行业新闻
     * @param $id 文章ID
     * @param $act 动作 up down
     * @author 花文刚
     */
    public function setDindCaiIndustry( $id,$act='up' ){
        $orm= ORM::factory('Zixun_IndustryArticle',$id);
        if( $orm->loaded()===false ){
            return false;
        }else{
            $rs= $orm->as_array();

            if( $act=="up" ){
                $eof= Arr::get($_COOKIE, 'industry_article_ding'.$id,'0');
                if( $eof=='0' ){
                    $up_num= ceil( $rs['article_ding'] )+1;
                    $orm->article_ding= $up_num;
                    setcookie( 'industry_article_ding'.$id,'1' );
                }else{
                    return false;
                }
            }else{
                $eof= Arr::get($_COOKIE, 'industry_article_cai'.$id,'0');
                if( $eof=='0' ){
                    $down_num= ceil( $rs['article_cai'] )+1;
                    $orm->article_cai= $down_num;
                    setcookie( 'industry_article_cai'.$id,'1' );
                }else{
                    return false;
                }
            }
            $orm->update();return true;

        }

    }
    //end function

}

