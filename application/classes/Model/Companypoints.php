<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业用户积分表
 * @author 龚湧
 *
 */
class Model_Companypoints extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'user_company_points';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';
}