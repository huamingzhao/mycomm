<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 搜索统计表
 * @author 龚湧
 *
 */
class Model_Bisearchlog extends ORM{
    /**
    * 表名称
    */
    protected $_table_name = "bi_search_log";

    /**
    * 主键名称
    */
    protected $_primary_key = 'id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';
}