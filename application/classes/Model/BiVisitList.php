<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 访问记录统计表
 * @author 龚湧
 *
 */
class Model_BiVisitList extends ORM{
    /**
    * 表名称
    */
    protected $_table_name = "bi_stat_visitlist";

    /**
    * 主键名称
    */
    protected $_primary_key = 'id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';
}