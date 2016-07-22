<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @author 嵇烨
 */
class Model_Usercompany extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_company";

    /**
     * 主键名称
     */
    protected $_primary_key = 'com_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
