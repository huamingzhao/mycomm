<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 供外部调用的api 标签操作
 * @author 潘宗磊
 *
 */
class Controller_Sapi_Platform_News extends Controller_Sapi_Basic{

    /**
    * 删除文章静态页面
    * @author 花文刚
    */
    public function action_delStaticHtml() {
        $post = $this->request->post();
        $month = $post['month'];
        $id = $post['id'];

        try{
            $file = "html-static/zixun/$month/$id.shtml";
            $return = unlink($file);
        }catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200','返回成功',$return);
    }

    /**
     * 获取时间段内发布的文章信息
     * @author 花文刚
     */
    public function action_getNewsInfoForBI() {
        $post = $this->request->post();
        $begin = $post['begin'];
        $end = $post['end'];
        $editor = $post['editor'];
        $parent = $post['parent'];
        $child = $post['child'];
        $ac_t= Arr::get($post, 'ac_t',0);

        $service = new Service_News_Zixun();
        try{
            $info = $service->getCMSArticleByCreateTime($begin,$end,$editor,$parent,$child,$ac_t);
            $a = array();
            foreach($info as $k=>$v){
                $a[$k]['article_id']=$v->article_id;
                $a[$k]['article_name']=$v->article_name;
                $a[$k]['article_addtime']=$v->article_addtime;
                $a[$k]['article_author']=ORM::factory("AdminUser",$v->user_id)->admin_user_name;
                $a[$k]['article_column_parent']=ORM::factory("Zixun_Column",$v->parent_id)->column_name;
                $a[$k]['article_column_child']=ORM::factory("Zixun_Column",$v->column_id)->column_name;
            }
            $return['article_list'] = $a;
        }catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200','返回成功',$return);
    }

    /**
     * 过滤掉不是后台管理员发布的文章
     * @author 花文刚
     */
    public function action_filterArticleForBI() {
        $post = $this->request->post();
        $id = $post['id'];
        $service = new Service_News_Zixun();
        try{
            $info = $service->getArticleInfoById($id);

            $admin = ORM::factory("AdminUser",$info->user_id);
            if($admin->admin_user_id){
                $return['admin_article'] = 1;
            }
            else{
                $return['admin_article'] = 0;
            }
        }catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200','返回成功',$return);
    }

    /**
     * 获取后台用户列表
     * @author 花文刚
     */
    public function action_getCMSAdminUser() {
        $post = $this->request->post();
        $service = new Service_News_Zixun();
        try{
            $admin_user = $service->getCMSAdminUser();

            $return['admin_user'] = $admin_user;
        }catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200','返回成功',$return);
    }

    /**
     * 获取一级栏目
     * @author 花文刚
     */
    public function action_getColumnParent() {
        $post = $this->request->post();
        $service = new Service_News_Zixun();
        try{
            $column_parent = $service->getColumnParent();

            $return['column_parent'] = $column_parent;
        }catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200','返回成功',$return);
    }

    /**
     * 获取二级栏目
     * @author 花文刚
     */
    public function action_getColumnChild() {
        $post = $this->request->post();
        $parent_id = $post['parent_id'];
        $service = new Service_News_Zixun();
        try{
            $column_child = $service->getColumnChild($parent_id);

            $return['column_child'] = $column_child;
        }catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200','返回成功',$return);
    }

    /**
     * 获取编辑发布的文章总数 分页用
     * @author 花文刚
     */
    public function action_getCountOfNewsByEditor() {
        $post = $this->request->post();
        $begin = $post['begin'];
        $end = $post['end'];
        $editor = $post['editor'];
        $parent = $post['parent'];
        $child = $post['child'];
        $ac_t= Arr::get($post, 'ac_t',0);
        $service = new Service_News_Zixun();
        try{
            $count = $service->getCountOfNewsByEditor($begin,$end,$editor,$parent,$child,$ac_t);

            $return['count'] = $count;
        }catch(Kohana_Exception $e){
            $this->setApiReturn('500', '服务器错误');
        }
        $this->setApiReturn('200','返回成功',$return);
    }

    /**
     * 标签首页
     * @author 潘宗磊
     */
    public function action_index() {
        exit("不能从外部调用");
    }
}
