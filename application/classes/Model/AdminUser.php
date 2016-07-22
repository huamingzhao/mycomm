<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 管理员表
 * @author 施磊
 */
class Model_AdminUser extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "admin_user";

    /**
     * 主键名称
     */
    protected $_primary_key = 'admin_user_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


} //End Personcrowd Model
