<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 搜索记录 ORM
 * @author 郁政
 */
class Model_SearchRecord extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "search_record";

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}
?>