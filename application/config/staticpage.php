<?php defined('SYSPATH') OR die('No direct script access.');

return array(
    'STATIC_PAGE_TOKEN' => 'woshihonglingjing',
    'STATIC_STATUS' => '0',//是否开启静态访问
    'INDEX_PATH' => dirname(substr(APPPATH,0,-1))."/html/index.html",
    'SEARCH_PATH' => dirname(substr(APPPATH,0,-1))."/html/xiangdao/index.html",
    'FENLEI_PATH' => dirname(substr(APPPATH,0,-1))."/html/xiangdao/fenlei/index.html",
    'ALL_PATH' => array('/' => 'INDEX_PATH', '/xiangdao/' => 'SEARCH_PATH', '/xiangdao/fenlei/' => 'FENLEI_PATH')
);