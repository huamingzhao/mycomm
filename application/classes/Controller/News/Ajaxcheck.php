<?php defined('SYSPATH') or die('No direct script access.');

/**
 * 资讯平台ajax
 * @author 龚湧
 *
 */

class Controller_News_Ajaxcheck extends Controller{
    /**
     * 检测是否为ajax请求
     * (non-PHPdoc)
     * @see Kohana_Controller::before()
     */
    public function before(){
        parent::before();
        if(!$this->request->is_ajax()){
            exit();
        }
    }
    /**
     * 返回二级文章分类
     *
     * @author 龚湧
     */
    public function action_getSubColumn(){
        $parent_id = $this->request->post("parent_id");
        $columns = array(0=>"请选择");
        if($parent_id and is_numeric($parent_id)){
            $service = new Service_News_Column();
            $cols = $service->getParInfoById($parent_id);
            if(count($cols)){
                foreach ($cols as $col){
                    $columns[$col->column_id] = $col->column_name;
                }
            }
        }
        echo Form::select("column_id",$columns,null,array("class"=>"tg_text02"));
    }


    /**
     *顶踩 数据更新
     *@author许晟玮
     */
    public function action_setDindCai(){
        $service= new Service_News_Article();
        $id = Arr::get($this->request->post(), 'id',0);
        $act= Arr::get($this->request->post(), 'act');
        $arr= array();
        if( $id==0 ){
            $arr['error']= '500';
        }else{
            $result= $service->setAppreciation( $id,$act );
            if( $result===false ){
                $arr['error']= '500';
            }else {
                $arr['error']= '200';
                //获取数量
                $rs= $service->getInfoRow($id);
                $arr['ding']= $rs['article_ding'];
                $arr['cai']= $rs['article_cai'];
            }
        }
        echo json_encode( $arr );
    }
    //end function

    /**
     *行业新闻顶踩 数据更新
     *@author 花文刚
     */
    public function action_setDindCaiIndustry(){
        $service= new Service_News_Article();
        $id = Arr::get($this->request->post(), 'id',0);
        $act= Arr::get($this->request->post(), 'act');
        $arr= array();
        if( $id==0 ){
            $arr['error']= '500';
        }else{
            $result= $service->setDindCaiIndustry( $id,$act );
            if( $result===false ){
                $arr['error']= '500';
            }else {
                $arr['error']= '200';
                //获取数量
                $rs= $service->getIndustryInfoRow($id);
                $arr['ding']= $rs['article_ding'];
                $arr['cai']= $rs['article_cai'];
            }
        }
        echo json_encode( $arr );
    }
    //end function

    /**
     *顶踩 首次通过ajax拿值
     *@author赵路生
     */
    public function action_getDingCai(){
    	$service= new Service_News_Article();
    	$id = Arr::get($this->request->post(), 'article_id',0);
    	$arr= array();
    	if($id == 0){
    		$arr['error']= 500;
    		$arr['ding']=0;
    		$arr['cai']=0;
    	}else{
    		$arr['error']= 0;
    		$result = $service->getInfoUpandDownRow($id);
    		$arr['ding']=$result['ding'];
    		$arr['cai']=$result['cai'];
    	}
    	echo json_encode( $arr );
    }

    /**
     *顶踩 首次通过ajax拿值
     *@author赵路生
     */
    public function action_getDingCaiIndustry(){
        $service= new Service_News_Article();
        $id = Arr::get($this->request->post(), 'article_id',0);
        $arr= array();
        if($id == 0){
            $arr['error']= 500;
            $arr['ding']=0;
            $arr['cai']=0;
        }else{
            $arr['error']= 0;
            $rs= $service->getIndustryInfoRow($id);
            $arr['ding']= $rs['article_ding'];
            $arr['cai']= $rs['article_cai'];
        }
        echo json_encode( $arr );
    }

    /**
     * 添加收藏资讯
     * @author 周进
     */
    public function action_addfavorite(){
        if ($this->isLogins()==false)
            return false;
        $arr= array('status'=>false,'msg'=>'收藏失败');
        $article_id = $this->request->post("article_id");
        if($this->isLogins()==false){
            $arr= array('status'=>false,'msg'=>'收藏失败，请先登录！');
            echo json_encode( $arr );
            exit;
        }
        $service = new Service_News_Favorite();
        //是否收藏
        $eof= $service->getFavoriteStatus($this->userId(), $article_id);
        if( $eof===true ){
            $arr= array('status'=>false,'msg'=>'已收藏');
            echo json_encode( $arr );
            exit;
        }else{
            $result = $service->addFavorite($this->userId(),$article_id);
            if ($result==false) {
                echo json_encode( $arr );
                exit;
            }
            else{
                $arr= array('status'=>true,'msg'=>'收藏成功');
                echo json_encode( $arr );
                exit;
            }
        }
    }

    /**
     * 收藏行业新闻
     * @author 花文刚
     */
    public function action_addfavorite_industry(){
        if ($this->isLogins()==false)
            return false;
        $arr= array('status'=>false,'msg'=>'收藏失败');
        $article_id = $this->request->post("article_id");
        if($this->isLogins()==false){
            $arr= array('status'=>false,'msg'=>'收藏失败，请先登录！');
            echo json_encode( $arr );
            exit;
        }
        $service = new Service_News_Favorite();
        //是否收藏
        $eof= $service->getFavoriteStatusIndustry($this->userId(), $article_id);
        if( $eof===true ){
            $arr= array('status'=>false,'msg'=>'已收藏');
            echo json_encode( $arr );
            exit;
        }else{
            $result = $service->addFavoriteIndustry($this->userId(),$article_id);
            if ($result==false) {
                echo json_encode( $arr );
                exit;
            }
            else{
                $arr= array('status'=>true,'msg'=>'收藏成功');
                echo json_encode( $arr );
                exit;
            }
        }
    }

    /**
     * 修改资讯
     * @author 周进
     */
    public function action_updateMyArticle(){
        $arr = array('status'=>false,'msg'=>'修改投稿失败');
        if ($this->isLogins()==false){
            echo json_encode( $arr );
            exit;
        }
        $post = Arr::map("HTML::chars", $this->request->post());
        if(Security::check(arr::get($post, "token"))){
            $service = new Service_News_Zixun();
            $post['user_id'] = $this->userInfo()->user_id;
            $post['article_id'] = arr::get($post, 'article_id');
            try{
                $result = $service->craeteZixun($post,2);
            }catch(Kohana_Exception $e){
                echo json_encode( $arr );
                exit;
            }
            if(!$result['error']){
                $arr = array('status'=>true,'msg'=>'修改投稿成功');
                echo json_encode( $arr );
                exit;
            }
        }
        echo json_encode( $arr );
        exit;
    }

    /**
     * 修改资讯
     * @author 周进
     */
    public function action_createMyArticle(){
        $arr = array('status'=>false,'msg'=>'投稿失败');
        if ($this->isLogins()==false){
            echo json_encode( $arr );
            exit;
        }
        $post = Arr::map("HTML::chars", $this->request->post());
        if(Security::check(arr::get($post, "token"))){
            $service = new Service_News_Zixun();
            $post['user_id'] = $this->userInfo()->user_id;
            $post['article_id'] = arr::get($post, 'article_id');
            try{
                $result = $service->craeteZixun($post);
            }catch(Kohana_Exception $e){
                echo json_encode( $arr );
                exit;
            }
            if(!$result['error']){
                $arr = array('status'=>true,'msg'=>'投稿成功');
                Security::token(true);//更新token,防止重复提交
                echo json_encode( $arr );
                exit;
            }
        }
        else{
            $arr = array('false'=>true,'msg'=>'不能重复提交');
        }
        echo json_encode( $arr );
        exit;
    }



    /**
     * 删除资讯
     * @author 周进
     */
    public function action_deletetougao(){
        $arr = array('status'=>false,'msg'=>'删除投稿失败');
        if ($this->isLogins()==false){
            echo json_encode( $arr );
            exit;
        }
        $post = Arr::map("HTML::chars", $this->request->post());
        //获取当前登陆用户id
        $userid=$this->userId();
        $service_zixun = new Service_News_Zixun();
        //获取当前数据
        $result=$service_zixun->getArticleInfoById(arr::get($post, 'article_id'));
        if(!intval(arr::get($post, 'article_id')) || $result==false){//传入参数错误或者没有文章报404错误
            echo json_encode( $arr );
            exit;
        }
        else{
            if($userid != $result->user_id){//如果不是本人更新当前文章即报404页面
                echo json_encode( $arr );
                exit;
            }
            else
            {
                try{
                    $resultdata = $service_zixun->deleteZixun(arr::get($post, 'article_id'));
                }catch(Kohana_Exception $e){
                    echo json_encode( $arr );
                    exit;
                }
                if ($resultdata){
                    $arr = array('status'=>true,'msg'=>'删除投稿成功');
                    echo json_encode( $arr );
                    exit;
                }
            }
        }
        echo json_encode( $arr );
        exit;
    }

    /**
     * 获取是否收藏
     * @author许晟玮
     */
    public function action_getfavoriteeof(){
        if ($this->isLogins()==false){
            $arr = array('msg'=>'0');
        }else{
            $service = new Service_News_Favorite();
            $id = Arr::get($this->request->post(), 'id',0);
            if( $id==0 ){
                $arr = array('msg'=>'0');
            }else{
                $result = $service->getFavoriteStatus($this->userId(),$id);
                if( $result===true ){
                    $arr = array('msg'=>'1');
                }else{
                    $arr = array('msg'=>'0');
                }
            }
        }
        echo json_encode( $arr );
        exit;
    }
    //end function

    /**
     * 获取是否收藏
     * @author许晟玮
     */
    public function action_getfavoriteofindustry(){
        if ($this->isLogins()==false){
            $arr = array('msg'=>'0');
        }else{
            $service = new Service_News_Favorite();
            $id = Arr::get($this->request->post(), 'id',0);
            if( $id==0 ){
                $arr = array('msg'=>'0');
            }else{
                $result = $service->getFavoriteStatusIndustry($this->userId(),$id);
                if( $result===true ){
                    $arr = array('msg'=>'1');
                }else{
                    $arr = array('msg'=>'0');
                }
            }
        }
        echo json_encode( $arr );
        exit;
    }
    //end function

}