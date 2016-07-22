<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Default apply
 *
 * @package    Kohana/apply
 * @author     潘宗磊
 */
class Model_Applyinvest extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "apply";

    /**
     * 主键名称
     */
    protected $_primary_key = 'apply_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
