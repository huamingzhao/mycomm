<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @城市地区 ORM
 * @author 曹怀栋
 */
class Model_City extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "city";

    /**
     * 主键名称
     */
    protected $_primary_key = 'cit_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
