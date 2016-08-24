<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Index extends Controller {


    public function action_index()
    {
        $info = Cache::instance()->get('userLoginData');
        if (empty($info)) {
            self::redirect("anxin/login");
        } else {
            self::redirect('anxin/index');
        }
    }

    /**
     * 缓存使用demo
     *
     * @author 龚湧
     */
    public function action_cacheDemo(){

        $cache = Cache::instance("memcache"); //这里也可以使用 页面缓存，默认就是

        if($cache->get("hello")){
            echo $cache->get("hello");
            exit();
        }
        $cache->set("hello", "work");//也可以自己设置超时时间 这
        $cache->get("hello");


    }

} // End Welcome
