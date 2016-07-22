<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 填写认领项目信息
 * @author 钟涛
 */
class Model_ProjectRenling extends ORM {

    /**
     * 表名称czzs_project_renling
     */
    protected $_table_name = "project_renling";

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} 
