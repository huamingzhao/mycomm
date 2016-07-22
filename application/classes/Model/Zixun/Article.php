<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 资讯表
 * @author 许晟玮
 *
 */
class Model_Zixun_Article extends ORM{

    /**
     * 数据表名czzs_zx_article
     */
    protected $_table_name  = 'zx_article';

    /**
     * 主键名称
     */
    protected $_primary_key = 'article_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

    /**
     * 项目资讯对应关系
     * @var unknown
     * @author 龚湧
     */
    protected $_has_one = array(
            'xz_project'=>array(
                    'model'       => 'ProjectArticle',
                    'foreign_key' => 'article_id',
            ),
    );

}//End Account Model