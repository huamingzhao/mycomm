<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @地区信息 ORM
 * @author 曹怀栋
 */
class Model_Projectcrowd extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "project_crowd";

    /**
     * 主键名称
     */
    protected $_primary_key = 'crowd_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
