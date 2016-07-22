<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业用户基本信息
 * @author 龚湧
*/
class Controller_User_Company_Article extends Controller_User_Company_Template{
    /**
     * 我的收藏文章
     *
     * @author 龚湧
     */
    public function action_favorite(){
        $view = View::factory("user/company/article/favorite");
        $this->content->rightcontent = $view;
        $service_zixun = new Service_News_Zixun();
        $result = $service_zixun->getFavoriteList($this->userInfo()->user_id);
        $view->list = $result['list'];
        $view->page = $result['page'];
    }

    /**
     * 取消收藏文章
     *
     * @author 龚湧
     */
    public function action_cancelFavorite(){
        $article_id = $this->request->query("article_id");
        $service = new Service_News_Favorite();
        $service->cancelFavorite($this->userInfo()->user_id, $article_id);
        self::redirect("company/member/article/favorite");
    }

    /**
     * 投稿的文章
     * @author 周进
     */
    public function action_getApplyArticle(){
        $service=new Service_User_Company_User();
        $article  = new Service_News_Zixun();
        $result = $article->getTougaoList($this->userId());
        if(arr::get($result,'list')==false){
            $content = View::factory("user/company/noapplyarticle");
            $this->content->rightcontent = $content;
        }else{
            $content = View::factory("user/company/applyarticle");
            $this->content->rightcontent = $content;
            $content->list = $result['list'];
            $content->page = $result['page'];
        }
    }

    /**
     * 项目投稿
     * @author许晟玮
     */
    public function action_projectArticle(){

        $user_id= ceil( $this->userId() );

        if( $user_id==0 ){
            self::redirect( '/' );
         }else{
            $company_service= new Service_User_Company_User();
            $company_result= $company_service->getCompanyInfo($user_id);
            if( !empty( $company_result->com_id ) ){
                $com_id= $company_result->com_id;
            }else{
                $com_id= 0;
            }

            $project_service= new Service_User_Company_Project();
            //判断用户下是否发布过审核过或者认领过的项目
            $project_count_all = 0;
            $project_count = 0;
            if($com_id>0){
                $project_count_all = ORM::factory('Project')->where('com_id', '=', $com_id)->count_all();
                $project_count= $project_service->isHasProject($com_id);
            }

            $total_items= 0;
            //判断用户是否发布过项目文章
            if( $project_count>0 ){
                $service_article= new Service_News_Article();
                $all_project_obj= $project_service->getALLProject($com_id);
                foreach( $all_project_obj as $vp ){
                    $rss= $service_article->getUserProjectZixunCount( $vp->project_id,$user_id );
                    $total_items= count($rss);
                    if( $total_items>0 ){
                        break;
                    }
                }
            }

            if( $project_count_all == 0 || ($project_count_all > 0 && $project_count == 0) || $total_items<=0  ){
                $content = View::factory("user/company/article/projectzixun");
                $this->content->rightcontent = $content;
                $content->project_count= ceil( $project_count );
                $content->project_count_all= ceil( $project_count_all );
                $content->com_id= ceil($com_id);
                $content->tougao_num= $total_items;
            }else{

                self::redirect( '/company/member/article/projecttougaolist' );


            }
        }

    }
    //end function

    /**
     *企业用户投稿
     * @author 许晟玮
     */
    public function action_projecttougao(){
        $content = View::factory("user/company/article/projecttougao");
        $this->content->rightcontent = $content;
        $user_id= ceil( $this->userId() );
        if( ceil( $user_id )<=0 ){
            self::redirect('/');
        }else{
            //如果传入了文章的自增ID 判断匹配
            $get = Arr::map("HTML::chars", $this->request->query());
            $article_id= Arr::get($get, 'id',0);
            if( ceil( $article_id )>0 ){
                //获取当前文章的信息
                $article_service= new Service_News_Article();
                $rs_article= $article_service->getInfoRow($article_id);
                //获取关联的项目文章数据
                $rs_project_article= $article_service->getProjectZixunRow($article_id);
                if( empty( $rs_article ) || empty( $rs_project_article ) ){
                    self::redirect('/company/member/article/projectArticle');
                }

                //验证合法性
                if( ceil( $rs_article['user_id'] )!=ceil( $user_id ) ){
                    self::redirect('/company/member/article/projectArticle');
                }

                $content->rs_article= $rs_article;
                $content->txt= $rs_article['article_content'];
                $content->rs_project_article= $rs_project_article;
            }else{
                $content->txt= '';
            }


            $project_service= new Service_User_Company_Project();
            $company_service= new Service_User_Company_User();

            $company_result= $company_service->getCompanyInfo($user_id);
            if( !empty( $company_result->com_id ) ){
                $com_id= $company_result->com_id;
            }
            //获取用户下的所有项目
            $all_project_obj= $project_service->getALLProject($com_id);

            $content->all_project= $all_project_obj->as_array();
            $project_platform_service= new Service_Platform_Project();

            $project_id= Arr::get($get, 'project_id',0);
            if( ceil( $project_id )>0 ){
                $rs_project= $project_platform_service->getProjectInfoByIDAll($project_id);
                if( ceil( $rs_project->com_id )!=ceil( $com_id ) ){
                    self::redirect( '/company/member/article/projecttougaolist' );
                }else{
                }
            }else{
            }
            $content->project_id= ceil( $project_id );


        }

    }
    //end function

    /**
     * 企业用户项目投稿列表
     * @author许晟玮
     */
    public function action_projecttougaolist(){
        $uid= $this->userId();
        $content = View::factory("user/company/article/projecttougaolist");
        $this->content->rightcontent = $content;
        $get = Arr::map("HTML::chars", $this->request->query());
        $pid= Arr::get($get, 'project_id',0);

        $article_service= new Service_News_Article();
        $project_serivce= new Service_Platform_Project();
        $company_service= new Service_User_Company_User();
        $com_project_service= new Service_User_Company_Project();

        $result= $article_service->getUserTouGaoZixunList($uid,'15',$pid);
        $list= $result['list'];
        $page= $result['page'];
        //获取对应的项目名称
        if( !empty( $list ) ){
            $memcache= Cache::instance('memcache');
            foreach( $list as $k=>$v ){
                //获取浏览次数（ 针对审核通过的文章 ）
                if( $v['article_status']=='2' ){
                    $pv_count= $memcache->get( "cache_zixun_pv_count_".$v['article_id'] );
                    if( empty( $pv_count ) ){
                        $zixun_api_service= new Service_Api_Zixun();
                        $pv_count_rs= $zixun_api_service->getPvNum($v['article_id']);
                        $pv_count= $pv_count_rs['data'];
                        $memcache->set( "cache_zixun_pv_count_".$v['article_id'],$pv_count,86400 );
                    }
                }else{
                    $pv_count= 0;
                }
                $list[$k]['pv_count']= $pv_count;

                $project_id = ceil( $v['project_id'] );
                if( $project_id>0 ){
                    $rs= $project_serivce->getProjectInfoByIDAll( $project_id );
                    $list[$k]['project_name']= $rs->project_brand_name;
                }else{
                    $list[$k]['project_name']= '';
                }
            }
        }

        //获取当前用户所有可以发布新闻的项目
        //获取用户下的所有项目

        $company_result= $company_service->getCompanyInfo($uid);
        if( !empty( $company_result->com_id ) ){
            $com_id= $company_result->com_id;
        }
        $all_project_obj= $com_project_service->getALLProject($com_id);

        $content->all_project= $all_project_obj->as_array();

        $content->list= $list;
        $content->page= $page;

        $content->pid= $pid;

    }
    //end function
}