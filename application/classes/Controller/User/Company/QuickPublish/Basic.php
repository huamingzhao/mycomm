<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 快速发布 企业中心基类
 * @author stone shi
 *
 */
class Controller_User_Company_QuickPublish_Basic extends Controller_User_Company_Template {
    
    public function before(){
        parent::before();
    }
    
    public function action_index() {
        echo 1;
    }
}