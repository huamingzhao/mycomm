<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Default Industry
 *
 * @package    Kohana/Industry
 * @author     stone shi 
 */
class Model_QuickProjectstatistics extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "quick_project_statistics";

    /**
     * 主键名称
     */
    protected $_primary_key = 'statistics_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
