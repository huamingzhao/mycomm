<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * 供外部调用的api 
 * @author stone shi
 *
 */
class Controller_Sapi_Platform_StaticPage extends Controller_Sapi_Basic {

    /**
     * 生成首页静态
     * @author stone shi
     */
    public function action_makeIndexPage() {
        $confToken = Kohana::$config->load("staticpage.STATIC_PAGE_TOKEN");
        $param = $this->request->post();
        $token = Arr::get($param, 'token', '');
        if($confToken != $token) $this->setApiReturn(405);
        //$url = 'http://www.yjh.com/platform/index';
        $url = URL::website('platform/index');
        $outPut = '';
        $content = '';
        try{
            $content = Request::factory($url)
            ->method(HTTP_Request::GET)
            ->execute()
            ->body(); 
        }catch(Kohana_Exception $e) {
            $outPut = $e;
        }
        
        if($outPut || !$content) $this->setApiReturn(500, 'Please try again.Error :'.$outPut);
        $outPut = '';
        try {
            $return = file_put_contents(Kohana::$config->load("staticpage.INDEX_PATH"), $content);
        }catch(Kohana_Exception $e) {
            $outPut = $e;
        }
        if($outPut || !$return) $this->setApiReturn(501, 'Please try again.Error :'.$outPut);
        $this->setApiReturn(200);
    }
    
    /**
     * 生成找项目静态
     * @author stone
     */
    public function action_makeSearchPage() {
        $confToken = Kohana::$config->load("staticpage.STATIC_PAGE_TOKEN");
        $param = $this->request->post();
        $token = Arr::get($param, 'token', '');
        if($confToken != $token) $this->setApiReturn(405);
        //$url = 'http://www.yjh.com/platform/index/search';
        $url = URL::website('/platform/index/search');
        $outPut = '';
        $content = '';
        try{
            $content = Request::factory($url)
            ->method(HTTP_Request::GET)
            ->execute()
            ->body(); 
        }catch(Kohana_Exception $e) {
            $outPut = $e;
        }
        
        if($outPut || !$content) $this->setApiReturn(500, 'Please try again.Error :'.$outPut);
        $outPut = '';
        try {
            $return = file_put_contents(Kohana::$config->load("staticpage.SEARCH_PATH"), $content);
        }catch(Kohana_Exception $e) {
            $outPut = $e;
        }
        if($outPut || !$return) $this->setApiReturn(501, 'Please try again.Error :'.$outPut);
        $this->setApiReturn(200);
    }
    
    /**
     * 生成分类静态
     * @author stone
     */
    public function action_makeFenleiPage() {
        $confToken = Kohana::$config->load("staticpage.STATIC_PAGE_TOKEN");
        $param = $this->request->post();
        $token = Arr::get($param, 'token', '');
        if($confToken != $token) $this->setApiReturn(405);
        //$url = 'http://www.yjh.com/platform/projectGuide/ProjectGuideIndustry';
        $url = URL::website('/platform//projectGuide/ProjectGuideIndustry');
        $outPut = '';
        $content = '';
        try{
            $content = Request::factory($url)
            ->method(HTTP_Request::GET)
            ->execute()
            ->body(); 
        }catch(Kohana_Exception $e) {
            $outPut = $e;
        }
        
        if($outPut || !$content) $this->setApiReturn(500, 'Please try again.Error :'.$outPut);
        $outPut = '';
        try {
            $return = file_put_contents(Kohana::$config->load("staticpage.FENLEI_PATH"), $content);
        }catch(Kohana_Exception $e) {
            $outPut = $e;
        }
        if($outPut || !$return) $this->setApiReturn(501, 'Please try again.Error :'.$outPut);
        $this->setApiReturn(200);
    }
}