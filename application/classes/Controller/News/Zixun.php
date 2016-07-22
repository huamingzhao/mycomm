<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 资讯发布
 * @author 龚湧
 *
 */
class Controller_News_Zixun extends Controller_News_Template{
    /**
     * 投稿
     * @author 龚湧
     */
    public function action_tougao(){
        //检测是否要登录
        $this->isLogin();
        $view = View::factory("news/tougao");
        //$this->content->rightcontent = $view;
        $this->template->content= $view;
        //一级栏目标题
        $service_column = new Service_News_Column();
        $column = $service_column->getColumnMenu();
        $columns = array(0=>"请选择");
        if($column){
            foreach($column as $col){
                $columns[$col->column_id] = $col->column_name;
            }
        }
        $view->columns = $columns;
        //左边栏导航
        $view->column = $column;

        //初始化投稿人信息
        $view->article_contact = $this->userInfo()->user_name;
        $view->article_mobile = $this->userInfo()->mobile;

        //保存并check文章
        if($this->request->method() == Request::POST){
            if(Security::check($this->request->post("token"))){
                $service = new Service_News_Zixun();
                $post = $this->request->post();
                $post['user_id'] = $this->userInfo()->user_id;
                try{
                    $result = $service->craeteZixun($post);
                }catch(Kohana_Exception $e){
                    self::redirect("zixun/zixun/tougao");
                }
                //TODO 我的投稿
                if($this->userInfo()->user_type==1)
                    self::redirect("company/member/article/getApplyArticle");
                else
                    self::redirect("person/member/article/getApplyArticle");
            }
            else{
                //echo "请不要重复提交";
            }
        }
        if($this->userInfo()->user_type==1){
            $gourl = "company/member/article/getApplyArticle";
        }
        else{
            $gourl = "person/member/article/getApplyArticle";
        }
        $view->gourl = $gourl;
        $this->template->title = mb_convert_encoding('一句话专业资讯投稿 | 开店、加盟、投资上一句话创业资讯投稿',"utf-8");
        $this->template->description = mb_convert_encoding('一句话创业资讯投稿专区，开店、加盟、投资上一句话创业资讯投稿。欢迎广大网友对创业、开店、加盟、投资发表看法，我们将以热诚的态度为您提供优质服务，欢迎来电、来稿，谢谢。',"utf-8");
        $this->template->keywords = mb_convert_encoding('投稿,资讯投稿,1句话,一句话投稿,一句话创业资讯',"utf-8");

    }

    /**
     * 修改资讯
     * @author 钟涛
     */
    public function action_updateMyArticle(){
        //检测是否要登录
        $this->isLogin();
        $query_data = Arr::map("HTML::chars", $this->request->query());
        //文章id
        $article_id = arr::get($query_data,'articleid','');
        //获取当前登陆用户id
        $userid=$this->userId();
        $service_zixun = new Service_News_Zixun();
        //获取当前数据
        $result=$service_zixun->getArticleInfoById($article_id);
        if(!intval($article_id) || $result==false){//传入参数错误或者没有文章报404错误
            echo '传入参数错误或者没有文章';exit;
        }else{
            if($userid != $result->user_id){//如果不是本人更新当前文章即报404页面
                echo '当前文章不是您本人添加的，无法操作';exit;
            }else{
                $content = View::factory("news/update_tougao");
                $this->template->content = $content;
                //一级栏目标题
                $service_column = new Service_News_Column();
                $column = $service_column->getColumnMenu();
                $columns = array(0=>"请选择");
                if($column){
                    foreach($column as $col){
                        $columns[$col->column_id] = $col->column_name;
                    }
                }
                $content->column = $column;
                $content->columns = $columns;
                //初始化投稿人信息
                $content->article_contact = $this->userInfo()->user_name;
                $content->article_mobile = $this->userInfo()->mobile;
                $content->list = $result;
                   /*
                //保存并check文章
                if($this->request->method() == Request::POST){
                    if(Security::check($this->request->post("token"))){
                        $service = new Service_News_Zixun();
                        $post = $this->request->post();
                        $post['user_id'] = $this->userInfo()->user_id;
                        $post['article_id']=$article_id;
                        try{
                            //修改资讯
                            $result = $service->craeteZixun($post,2);
                        }catch(Kohana_Exception $e){
                            self::redirect("zixun/zixun/tougao");
                        }
                        if($result['error']){//错误信息
                            self::redirect("zixun/zixun/updateMyArticle?articleid=".$article_id);
                            exit;
                        }else{//TODO 跳转
                            if($this->userInfo()->user_type==1)
                                self::redirect("company/member/article/getApplyArticle");
                            else
                                self::redirect("person/member/article/getApplyArticle");
                        }
                    }
                    else{
                        //echo "请不要重复提交";
                    }
                }
                */
            }
        }
    }

    /**
     * 删除资讯
     * @author 周进
     */
    public function action_deletearticle(){
        //检测是否要登录
        $this->isLogin();
        $query_data = Arr::map("HTML::chars", $this->request->query());
        //文章id
        $article_id=arr::get($query_data,'id','');
        //获取当前登陆用户id
        $userid=$this->userId();
        $service_zixun = new Service_News_Zixun();
        //获取当前数据
        $result=$service_zixun->getArticleInfoById($article_id);
        if(!intval($article_id) || $result==false){//传入参数错误或者没有文章报404错误
            echo '传入参数错误或者没有文章';exit;
        }else{
            if($userid != $result->user_id){//如果不是本人更新当前文章即报404页面
                echo '当前文章不是您本人添加的，无法操作';exit;
            }
            else
            {
                try{
                    $result = $service_zixun->deleteZixun($article_id);
                    if($this->userInfo()->user_type==1)
                        self::redirect("company/member/article/getApplyArticle");
                    else
                        self::redirect("person/member/article/getApplyArticle");
                }catch(Kohana_Exception $e){
                    if($this->userInfo()->user_type==1)
                        self::redirect("company/member/article/getApplyArticle");
                    else
                        self::redirect("person/member/article/getApplyArticle");
                }
            }
        }
    }

    /**
     * 取消收藏文章
     *
     * @author 龚湧
     */
    public function action_cancelFavorite(){
        $article_id = $this->request->query("article_id");
        $service = new Service_News_Favorite();
        $service->cancelFavorite($this->userid(), $article_id);
        $type=$this->userInfo()->user_type;
        $usertype="";
        if($type==1){
            $usertype="company";
        }elseif($type==2){
            $usertype="person";
        }
        self::redirect("".$usertype."/member/article/getFavoriteArticle");
    }
}