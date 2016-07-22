<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 行业推荐
 * @author 嵇烨
 *
 */
class Model_Projecthotindustry extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'project_hot_industry';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';
}
