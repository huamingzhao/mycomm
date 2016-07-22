<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生意帮-搜索记录信息
 * @author 兔毛 2014-06-24
 */
class Model_Business_Search extends ORM{

    /**
     * 数据表名czzs_business_stat
     */
    protected $_table_name  = 'business_search';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model