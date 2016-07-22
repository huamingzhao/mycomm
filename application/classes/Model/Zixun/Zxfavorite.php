<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 资讯收藏表
 * @author 周进
 *
 */
class Model_Zixun_Zxfavorite extends ORM{

    /**
     * 数据表名czzs_zx_column
     */
    protected $_table_name  = 'zx_favorite';

    /**
     * 主键名称
     */
    protected $_primary_key = 'favorite_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

     /*
     * 对应关系
     */
    protected $_belongs_to = array(
            'article' => array(
                    'model'       => 'Zixun_Article',
                    'foreign_key' => 'favorite_article_id',
            ),
    );


}//End Account Model