<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @招商会播报 ORM
 * @author 曹怀栋
 */
class Model_Bobao extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "invest_bobao";

    /**
     * 主键名称
     */
    protected $_primary_key = 'invest_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
