<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Redis extends Controller {



    /**
     * redis使用demo
     * @author许晟玮
     */
    public function action_test(){
        $cache= Cache::instance('redis');
        $cache->set('hello', 'world');
        echo $cache->get("hello");

    }
} // End Welcome
