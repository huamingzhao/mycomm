<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @推广来源 ORM
 * @author 许晟玮
 */
class Model_Source extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "bi_stat_promotion_source";

    /**
     * 主键名称
     */
    protected $_primary_key = 'promotion_source_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
