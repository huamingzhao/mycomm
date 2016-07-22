<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 搜索记录 施磊
 * @author 施磊
 */
class Model_SearchClick extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "search_click";

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