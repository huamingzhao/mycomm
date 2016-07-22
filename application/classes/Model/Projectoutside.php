<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 抓取875未入库项目日志记录
 * @package    Kohana/Project
 * @author     zhoujin
 */
class Model_Projectoutside extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "project_outside";

    /**
     * 主键名称
     */
    protected $_primary_key = 'outside_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} // End Auth User Model
