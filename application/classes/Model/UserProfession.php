<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @行业类别表 ORM
 * @author 许晟玮
 */
class Model_UserProfession extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_profession";

    /**
     * 主键名称
     */
    protected $_primary_key = 'profession_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model