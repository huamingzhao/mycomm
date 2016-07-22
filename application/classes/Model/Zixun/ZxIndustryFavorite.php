<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 行业新闻收藏表
 * @author 花文刚
 *
 */
class Model_Zixun_ZxIndustryFavorite extends ORM{

    /**
     * 数据表名czzs_zx_industry_favorite
     */
    protected $_table_name  = 'zx_industry_favorite';

    /**
     * 主键名称
     */
    protected $_primary_key = 'favorite_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'zixun';

     /*
     * 对应关系
     */
    protected $_belongs_to = array(
            'industry_article' => array(
                    'model'       => 'Zixun_IndustryArticle',
                    'foreign_key' => 'industry_article_id',
            ),
    );


}//End Account Model