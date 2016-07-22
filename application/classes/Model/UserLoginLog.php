<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 用户登陆记录表
 * @author 施磊
 */
class Model_UserLoginLog extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_login_log";

    /**
     * 主键名称
     */
    protected $_primary_key = 'log_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
