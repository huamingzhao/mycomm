<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @投资人群 ORM [由原先的tag表改进而来]
 * @author 周进
 */
class Model_Usertype extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_type";

    /**
     * 主键名称
     */
    protected $_primary_key = 'tag_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
