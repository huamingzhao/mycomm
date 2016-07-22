<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @author 周进
 */
class Model_UserOauth extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_oauth";

    /**
     * 主键名称
     */
    protected $_primary_key = 'user_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
