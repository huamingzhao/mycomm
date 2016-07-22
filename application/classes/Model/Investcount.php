<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 招商会统计 主要针对日期城市类别
 * @author 周进
 *
 */
class Model_Investcount extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'investment_count';

    /**
     * 主键名称
     */
    protected $_primary_key = 'count_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}