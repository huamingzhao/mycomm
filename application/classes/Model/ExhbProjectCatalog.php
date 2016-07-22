<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 展会表
 * @author 花文刚
 *
 */
class Model_ExhbProjectCatalog extends ORM{

    /**
     * 数据表名czzs_exhb_exhibition
     */
    protected $_table_name  = 'exhb_project_catalog';

    /**
     * 主键名称
     */
    protected $_primary_key = 'catalog_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model