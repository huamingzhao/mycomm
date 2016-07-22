<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 创业问题分类表
 * @author 钟涛
 *
 */
class Model_Zixun_AskIndustry extends ORM{

    /**
     * 数据表名czzs_ask_industry
     */
    protected $_table_name  = 'ask_industry';

    /**
     * 主键名称
     */
    protected $_primary_key = 'ask_industry_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Askindustry Model