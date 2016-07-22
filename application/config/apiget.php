<?php defined('SYSPATH') OR die('No direct script access.');

return array(
    'BI_API' => 'http://bi.yijuhua-alpha.net/sapi/', //配置统计api基础路径
    'SEARCH_API' => 'http://10.201.6.34:8080/', //配置sora搜索路径
	'INVESTOR_API' => 'http://10.201.6.36:8080/', //配置sora搜索路径[搜索投资者]
    'CURLOPT_USERAGENT'      => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0',
    'CURLOPT_CONNECTTIMEOUT' => 5,
    'CURLOPT_TIMEOUT'        => 5,
    'CURLOPT_RETURNTRANSFER' => TRUE,
    'CURLOPT_HEADER'         => FALSE,
    'CURLOPT_POST' => TRUE
);