<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @来源为5的company ORM
 * @author 郁政
 */
class Model_TestCompany extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "test_company";

    /**
     * 主键名称
     */
    protected $_primary_key = 'com_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
