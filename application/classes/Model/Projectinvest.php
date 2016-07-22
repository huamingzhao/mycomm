<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 招商会记录 ORM
 * @author 潘宗磊
 *
 */
class Model_Projectinvest extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'project_investment';

    /**
     * 主键名称
     */
    protected $_primary_key = 'investment_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

    /**
     * 配置需要搜索的列
     */
    protected $_search_row = array(
            'investment_province',//一级地区
            'investment_city',//二级地区
            'investment_start',//招商会开始时间
            'investment_end'//招商会结束时间
    );
}