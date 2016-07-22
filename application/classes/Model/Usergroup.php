<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 用户组表
 * @author 施磊
 */
class Model_Usergroup extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_group";

    /**
     * 主键名称
     */
    protected $_primary_key = 'user_group_id';
    
    protected $_has_one = array('admin_user' => array('model' => 'AdminUser', 'foreign_key' => 'user_group_id'));
    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Personcrowd Model
