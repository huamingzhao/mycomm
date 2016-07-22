<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @职业类别/名称 ORM
 * @author 许晟玮
 */
class Model_UserPosition extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_position";

    /**
     * 主键名称
     */
    protected $_primary_key = 'position_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
