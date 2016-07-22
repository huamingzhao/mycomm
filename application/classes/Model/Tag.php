<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @系统标签 ORM
 * @author 周进
 */
class Model_Tag extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "tag";

    /**
     * 主键名称
     */
    protected $_primary_key = 'tag_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
