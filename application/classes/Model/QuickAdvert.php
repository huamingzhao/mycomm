<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 快速发布广告表  
 * @author stone shi
 *
 */
class Model_QuickAdvert extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'quick_advert';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

    
}