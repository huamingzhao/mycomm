<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @创业币 ORM
 * @author 郁政
 */
class Model_Usercurrency extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_currency";

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Usercurrency Model
