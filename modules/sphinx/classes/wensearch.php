<?php

defined('SYSPATH') OR die('No direct script access.');

/**
 * 搜索服务封装
 * @author stone shi
 *
 */
class wensearch {
     static $instance;
    /**
     * 创建单例
     * @return searchService
     */
    static function getInstance() {
        if (self::$instance == NULL) {
            self::$instance = new wensearch();
        }
        return self::$instance;
    }
    
    /**
     * 预先检查关键词
     * @param string $word
     * @return array
     */
    public function checkWord($word) {
        switch ($word) {
            case "":
                $res['code'] = '201';
                $res['error'] = '搜索的关键词不能为空';
                break;
            default:
                $res['code'] = '200';
                break;
        }

        return $res;
    }
    
    /**
     * 搜索提问
     */
    public function searchWenTitle($word, $limit = 3, $page = 1) {
        $res = $this->checkWord($word);
        
        $Search = new Service_Api_Search();
        $offset = ($page - 1) * $limit;
        
        //设置排序
        $search = $Search->getWenSearch($word , $offset, '', $limit);
        
        $search['words'] = $word;
        return $search;
        
    }
    
    /**
     * 搜索用户
     */
    public function searchWenUser($word, $limit = 3, $page = 1) {
        $res = $this->checkWord($word);
        
        $Search = new Service_Api_Search();
        $offset = ($page - 1) * $limit;
        
        //设置排序
        $search = $Search->getWenUserSearch($word , $offset, '', $limit);
        
        $search['words'] = $word;
        return $search;
        
    }
    
}