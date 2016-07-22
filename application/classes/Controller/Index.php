<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller {

    /**
     * 首页映射到平台用户中心
     * @author 龚湧
     */
    public function action_index()
    {
        $content= View::factory("home/index");

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


    /**
     * redis使用demo
     * @author许晟玮
     */
    public function action_redisDemo(){
        $cache= Cache::instance('redis');
        $cache->set('hello', 'world');
        echo $cache->get("hello");

    }


    public function action_mr(){
        //header("Content-type: text/html; charset=gb2312");
        $dir = DOCROOT.'doc/sso.csv';
        $str= file_get_contents($dir);
        $str_gb= mb_convert_encoding($str,'utf-8', 'GBK');
        //街区字符串
           $str_str= mb_substr($str_gb, 6);
           $str_exp= explode('|', $str_str);
        $sso= Service_Sso_Client::instance();
        foreach( $str_exp as $v ){
            $v_exp= explode(';', $v);
            $name= $v_exp[0];
            if( $name!='' ){
                $email= $v_exp[1];
            }
            $info= $sso->getUserInfoByEmail($email);
            $uid= $info->id;
            $sso->updateBasicInfoById($uid, array('user_portrait'=>'http://static.yjh.com/images/common/user_icon/animal/default_icon_6.jpg'));

        }

    }

} // End Welcome
