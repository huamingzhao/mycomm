<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 个人地域表
 * @author 施磊
 *
 */
class Model_UserPerIndustry extends ORM{
    /**
    * 表名称
    */
    protected $_table_name = "user_personal_industry";

    /**
     * 主键名称
     */
    protected $_primary_key = 'per_industry_id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';

    /**
     * 配置需要搜索的列
     */
    protected $_search_row = array(
            'industry_id',//2级行业ID
            'parent_id',//1级行业ID
    );
}
