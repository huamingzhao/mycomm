<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 项目属性表Model: 个人中心-收到的&递出的&收藏的名片筛选条件用到此表
 * @author 钟涛
 *
 */
class Model_ProjectSearchCard extends ORM{

    /**
     * 数据表名czzs_project_search_card
     */
    protected $_table_name  = 'project_search_card';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

    /**
     * 配置需要搜索的列
     */
    protected $_search_row = array(
            'parent_id',//一级行业id
            'project_industry_id',//2级行业id
            'project_amount_type',//投资金额类型
            'project_status',//项目状态
    );
}//End ProjectSearchCard Model