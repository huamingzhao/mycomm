<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 精准搜索问题 已登录用户配置
 * @author 龚湧
 *
 */
class Model_Searchconfig extends ORM{
    /**
    * 表名称
    */
    protected $_table_name = "search_status";

    /**
    * 主键名称
    */
    protected $_primary_key = 'id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';
}