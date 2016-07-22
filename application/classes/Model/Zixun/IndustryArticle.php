<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 行业新闻表
 * @author 花文刚
 *
 */
class Model_Zixun_IndustryArticle extends ORM{

    /**
     * 数据表名czzs_zx_industry_article
     */
    protected $_table_name  = 'zx_industry_article';

    /**
     * 主键名称
     */
    protected $_primary_key = 'article_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'zixun';

}//End Askindustry Model