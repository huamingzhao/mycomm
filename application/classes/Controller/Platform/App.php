<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生意人下载页面
 * @author 钟涛
 *
 */

class Controller_Platform_App extends Controller_Platform_Template{
    /**
     * 生意人下载页面
     * @author 钟涛
     */
    public function action_index(){
        $content = View::factory("app/download");
        $this->content->maincontent = $content ;
    }

  
}
