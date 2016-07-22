<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @搜索词库匹配表
 * @author 周进
 */
class Model_Customer extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "exhb_customer";

    /**
     * 主键名称
     */
    protected $_primary_key = 'customer_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
