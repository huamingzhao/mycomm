<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 项目SEO ORM
 * @author 郁政
 */
class Model_ProjectSeo extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "project_seo";

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}
?>