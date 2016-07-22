<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 名片收藏表
 * @author 周进
 *
 */
class Model_Favorite extends ORM{
    /**
    * 表名称
    */
    protected $_table_name = "favorite";

    /**
    * 主键名称
    */
    protected $_primary_key = 'favorite_id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';
}