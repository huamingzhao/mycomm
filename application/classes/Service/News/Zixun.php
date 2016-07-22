<?php
/**
 * 资讯
 * @author 龚湧
 *
 */
class Service_News_Zixun{
    /**
     * @sso
     * 添加资讯
     * 默认$type=1添加资讯；$type=2修改资讯
     */
    public function craeteZixun(array $zixun,$type=1){
        $zixun = Arr::map(array(array("HTML","chars")), $zixun);
        $result = array("error"=>false,"result"=>array());
        if($type==2){
            $article = ORM::factory("Zixun_Article",Arr::get($zixun, "article_id"));
        }else{
            $article = ORM::factory("Zixun_Article");
        }
        //文章内容
        $info = array();
        $info['article_name'] = Arr::get($zixun, "article_name");
        $info['article_tag'] = Arr::get($zixun,"article_tag");
        $info['article_content'] = Arr::get($zixun,"article_content");
        $info['parent_id'] = Arr::get($zixun,"parent_id");
        //$info['column_id'] = Arr::get($zixun,"column_id"); //目前只保存父级目录
        $info['user_type'] = 2;
        $info['user_id'] = Arr::get($zixun, "user_id");
        $info['link_name'] = Arr::get($zixun,"article_contact");
        $info['link_phone'] = Arr::get($zixun,"article_mobile");
        $info['push_reason'] = Arr::get($zixun,"article_reason");

        //校验
        $validation = new Validation($info);
        //TODO 后端校验
        $validation
        //标题检测
        ->rule(
                "article_name",
                function(Validation $array, $field, $value){
                    if(!trim($value)){
                        $array->error($field,"标题不能为空");
                    }
                },
                array(':validation', ':field', ':value')
        )
        //内容检测
        ->rule(
                "article_content",
                function(Validation $array, $field, $value){
                    if(!trim($value)){
                        $array->error($field,"内容不能为空");
                    }
                },
                array(':validation', ':field', ':value')
        )
        //文章分类检测
        ->rule(
                "column_id",
                function(Validation $array, $field, $value){
                    //TODO 检测文章分类是否合法
                    if(!is_numeric($value) and $value){
                        $array->error($field,"文章分类错误");
                    }
                },
                array(':validation', ':field', ':value')
        )
        ->rule(
                "parent_id",
                function(Validation $array, $field, $value){
                    //TODO 检测文章分类是否合法
                    if(!is_numeric($value) and $value){
                        $array->error($field,"一级分类错误");
                    }
                },
                array(':validation', ':field', ':value')
        );

        if($validation->check()){
            try{
                if($type==2){//更新
                    $info['article_status'] = '1';
                    $info['aritcle_modtime'] = time();
                    $article->values($info)->update();
                }else{//添加
                    //初始围观数，取一个随机数  @花文刚
                    $article_onlooker = rand(40,120);
                    //初始顶、踩数
                    $article_ding = $article_onlooker - rand(20,30);
                    $article_cai = rand(5,25);

                    $info['article_intime'] = time();
                    $info['article_onlooker'] = $article_onlooker;
                    $info['article_ding'] = $article_ding;
                    $info['article_cai'] = $article_cai;

                    $article->values($info)->create();
                    if($info['user_id']){//个人用户添加活跃度by钟涛
                        //$usera =ORM::factory('User',$info['user_id']);
                        $usera  = Service_Sso_Client::instance()->getUserInfoById( $info['user_id'] );
                        if($usera->user_type==2){
                            $ser1=new Service_User_Person_Points();
                            $ser1->addPoints($info['user_id'], 'add_zixun');//资讯投稿
                        }
                    }
                }
                $result['result'] = $article;
            }
            catch(Kohana_Exception $e){
                throw new Kohana_Exception("数据写入失败");
            }
        }
        else{
            //返回错误信息
            $result['error'] = true;
            $result['result'] = $validation->errors();
        }
        return $result;
    }

    /**
     * 更具文章ids获取文章内容
     * @param array $ids
     * @author 龚湧
     */
    public function getArticleListByIds(array $ids){
        if(!empty($ids)){
            $articles = ORM::factory("Zixun_Article")->where("article_id","in",$ids)->where('article_status', '=', '1')->find_all();
            if($articles->count()){
                return $articles;
            }
        }
        return false;
    }

    /**
     * 根据文章id获取当前文章数据
     * @param int $ids
     * @author 钟涛
     */
    public function getArticleInfoById($id){
        if(intval($id)){
            $articles = ORM::factory("Zixun_Article",$id);
            if(!$articles->article_id){
                return false;
            }
            return $articles;
        }
        return false;
    }

    /**
     * 根据文章id获取当前文章数据(行业新闻)
     * @param int $ids
     * @author 郁政
     */
    public function getHangYeArticleInfoById($id){
        if(intval($id)){
            $articles = ORM::factory("Zixun_IndustryArticle",$id);
            if(!$articles->article_id){
                return false;
            }
            return $articles;
        }
        return false;
    }

    /**
     * 删除文章
     * @param int $id
     * @author 周进
     */
    public function deleteZixun($id){
        if(intval($id)){
            $article = ORM::factory("Zixun_Article",$id);
            $article->article_status = 4;
            try {
                $article->update();
                return true;
            } catch (Kohana_Exception $e) {
                throw new Kohana_Exception("删除失败");
            }
        }
        return false;
    }

    /**
     * 获取推荐文章列表
     * @param unknown $user_id
     * @author 龚湧
     */
    public function getFavoriteList($user_id,$page_size=10){


        $orm= ORM::factory('Zixun_Zxfavorite');
        //count
        $orm->where('user_id', '=', $user_id)->where("favorite_status","=",1)->reset(false);
        $count= $orm->count_all();
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => $page_size,
        ));
        $list= $orm->limit($page->items_per_page)->offset($page->offset)->order_by('favorite_time', 'DESC')->find_all( );
        $result= array();
        $result['list']= $list;
        if(!$result['list']->count()){
            $result['list'] = false;
        }
        $result['page']= $page;
        return $result;
    }

    /**
     * 获取投稿文章列表
     * @param unknown $user_id
     * @param $noallow 过滤掉栏目ID 29的文章
     * @author 周进
     */
    public function getTougaoList($user_id,$noallow=true){
        $orm= ORM::factory('Zixun_Article');
        //count
        $orm->where('user_id', '=', $user_id)->where('article_status','!=','4');
        if( $noallow===true ){
            $orm->where('parent_id', '!=', '29');
        }
        $orm->reset(false);
        $count= $orm->count_all();
        $page = Pagination::factory(array(
                'total_items'    => $count,
                'items_per_page' => 10,
        ));
        $list= $orm->limit($page->items_per_page)->offset($page->offset)->order_by('article_addtime', 'DESC')->find_all();
        $result= array();
        $result['list']= $list;
        if($count==0){
            $result['list'] = false;
        }
        $result['page']= $page;
        return $result;
    }

    /**
     * 获取后台编辑组发布的文章列表
     * @param array $ids
     * @author 花文刚
     */
    public function getCMSArticleByCreateTime($begin,$end,$editor,$parent,$child,$ac_t=0){
        $model = ORM::factory('Zixun_Article');
        $where=array();
        $where[] = array("article_status", "=","2");
        if($begin){
            $where[] = array("article_addtime", ">=",$begin);
        }
        if($end){
            $where[] = array("article_addtime", "<=",$end);
        }
        if($parent){
            $where[] = array("parent_id", "=",$parent);
        }
        if($child){
            $where[] = array("column_id", "=",$child);
        }
        if($editor){
            $where[] = array("user_id", "=",$editor);
        }
        if( $ac_t ){
            $where[] = array("article_type", "=",$ac_t);
        }
        else{
            $admin_user = array();
            $admin = ORM::factory("AdminUser")->where("admin_user_status","=","1")->where("admin_user_group_id","=","15")->find_all();
            foreach ($admin as $v){
                $admin_user[] = $v->admin_user_id;
            }
            $where[] = array("user_id", "in",$admin_user);
        }

        if(!empty($where)){//根据查询条件查询
            foreach($where as $v){
                $model->where($v[0], $v[1], $v[2]);
            }
        }

        $articles = $model->find_all();
        return $articles;

    }

    /**
     * 获取后台后台编辑组用户列表
     * @param array $ids
     * @author 花文刚
     */
    public function getCMSAdminUser(){
        $admin_user = array();
        $admin = ORM::factory("AdminUser")->where("admin_user_status","=","1")->where("admin_user_group_id","=","15")->find_all();
        foreach ($admin as $k=>$v){
            $admin_user[$k]['admin_user_id'] = $v->admin_user_id;
            $admin_user[$k]['admin_user_name'] = $v->admin_user_name;
        }
        return $admin_user;

    }

    /**
     * 获取一级栏目列表
     * @param array $ids
     * @author 花文刚
     */
    public function getColumnParent(){
        $column_parent = array();
        $column = ORM::factory("Zixun_Column")->where("column_type","=","0")->where("parent_id","=","0")->where("column_status","=","1")->find_all();
        foreach ($column as $k=>$v){
            $column_parent[$k]['column_id'] = $v->column_id;
            $column_parent[$k]['column_name'] = $v->column_name;
        }
        return $column_parent;

    }

    /**
     * 获取二级栏目列表
     * @param array $ids
     * @author 花文刚
     */
    public function getColumnChild($parent_id){
        $column_child = array();
        $column = ORM::factory("Zixun_Column")->where("parent_id","=",$parent_id)->where("column_status","=","1")->find_all();
        foreach ($column as $k=>$v){
            $column_child[$k]['column_id'] = $v->column_id;
            $column_child[$k]['column_name'] = $v->column_name;
        }
        return $column_child;

    }

    /**
     * 获取编辑发布的文章总数 分页用
     * @author 花文刚
     */
    public function getCountOfNewsByEditor($begin,$end,$editor,$parent,$child,$ac_t=0){
        $model = ORM::factory('Zixun_Article');
        $where=array();
        $where[] = array("article_status", "=","2");
        if($begin){
            $where[] = array("article_addtime", ">=",$begin);
        }
        if($end){
            $where[] = array("article_addtime", "<=",$end);
        }
        if($parent){
            $where[] = array("parent_id", "=",$parent);
        }
        if($child){
            $where[] = array("column_id", "=",$child);
        }

        if($editor){
            $where[] = array("user_id", "=",$editor);
        }
        if( $ac_t ){
            $where[] = array("article_type", "=",$ac_t);
        }
        else{
            $admin_user = array();
            $admin = ORM::factory("AdminUser")->where("admin_user_status","=","1")->where("admin_user_group_id","=","15")->find_all();
            foreach ($admin as $v){
                $admin_user[] = $v->admin_user_id;
            }
            $where[] = array("user_id", "in",$admin_user);
        }

        if(!empty($where)){//根据查询条件查询
            foreach($where as $v){
                $model->where($v[0], $v[1], $v[2]);
            }
        }

        $count = $model->count_all();
        return $count;
    }

}