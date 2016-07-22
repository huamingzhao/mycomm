<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 网络展会招商行业
 * @author 郁政
 *
 */
class Model_ExhbProjectindustry extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'exhb_project_industry';

    /**
     * 主键名称
     */
    protected $_primary_key = 'pi_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';
}