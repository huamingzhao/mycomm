<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 招商行业
 * @author 曹怀栋
 *
 */
class Model_Projectindustry extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'project_industry';

    /**
     * 主键名称
     */
    protected $_primary_key = 'pi_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';
}