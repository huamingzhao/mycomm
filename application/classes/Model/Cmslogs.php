<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 后台日志表
 * @author 施磊
 */
class Model_Cmslogs extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "cms_logs";

    /**
     * 主键名称
     */
    protected $_primary_key = 'cms_logs_id';
    
    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Personcrowd Model
