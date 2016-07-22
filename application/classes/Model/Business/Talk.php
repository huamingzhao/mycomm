<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生意帮-类型信息表（话题）
 * @author 兔毛 2014-06-09
 */
class Model_Business_Talk extends ORM{

    /**
     * 数据表名czzs_business_talk
     */
    protected $_table_name  = 'business_talk';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model