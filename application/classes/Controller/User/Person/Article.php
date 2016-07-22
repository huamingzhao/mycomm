<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户名片信息
 */
class Controller_User_Person_Article extends Controller_User_Person_Template{
    /**
     * 个人中心收藏文章
     * @author 潘宗磊
     */
    public function action_getFavoriteArticle(){
            $service=new Service_User_Person_User();
            //获取登录user_id
            $userid = $this->userid();
            //获得个人基本信息
            $personinfo = $service->getPersonInfo($userid);

                //view页面加载
                $article  = new Service_User_Person_Article();
                $result = $article->getListFavorite($userid);
                if($result['count']==0){
                    $content = View::factory("user/person/nofavoritearticle");
                    $this->content->rightcontent = $content;
                }else{
                    $content = View::factory("user/person/favoritearticle");
                    $this->content->rightcontent = $content;
                    $content->list = $result['list'];
                    $content->page = $result['page'];
                }

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
            $content = View::factory("user/person/noapplyarticle");
            $this->content->rightcontent = $content;
        }else{
            $content = View::factory("user/person/applyarticle");
            $this->content->rightcontent = $content;
            $content->list = $result['list'];
            $content->page = $result['page'];
        }
    }



}