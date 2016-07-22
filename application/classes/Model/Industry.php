<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Default Industry
 *
 * @package    Kohana/Industry
 * @author     cao
 */
class Model_Industry extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "industry";

    /**
     * 主键名称
     */
    protected $_primary_key = 'industry_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
