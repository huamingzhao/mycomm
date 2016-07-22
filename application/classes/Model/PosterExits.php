<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 外采海报是否存在
 * @author 龚湧
 *
 */
class Model_PosterExits extends ORM{
    /**
    * 表名称
    */
    protected $_table_name = "is_poster_exits";

    /**
    * 主键名称
    */
    protected $_primary_key = 'poster_id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';

}