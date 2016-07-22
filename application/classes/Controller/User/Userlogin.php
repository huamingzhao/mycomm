<?php
defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * 用户注册登录 退出登录 公共部分 各种类型用户通用
 *
 * @author 龚湧
 *
 */
class Controller_User_Userlogin extends Controller_Template {
    private $_cache_get_project_total = 'getProjectTotal';
    private $_cache_get_user_total = 'getUserTotal';
    private $_cache_get_total_time = 86400;

    /**
     * 用户模板设置
     */
    public $template = 'logintemplate';
    /**
     * sso用户跳转
     */
    public function action_logout(){
        $to_url = Arr::get ( $this->request->query (), 'to_url' );
        $content = View::factory ( 'user/logout' );
        $content->type = $to_url;
        $this->template->content = $content;
        $token = Cookie::get ( "authautologin" );
        $service = Service_Sso_Client::instance ()->logout ( $token );
        //if ($to_url == '') { self::redirect ( "member/login" ); } else { self::redirect ( $to_url ); }
    }
    /**
     * index
     */
    public function action_index(){

    }
}