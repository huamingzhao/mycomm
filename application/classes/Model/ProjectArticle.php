<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 项目关联的资讯新闻表
 * @author 许晟玮
 *
 */
class Model_ProjectArticle extends ORM{

    /**
     * 数据表名czzs_zx_project_article
     */
    protected $_table_name  = 'zx_project_article';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


}//End Accountlog Model