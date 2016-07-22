<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 防多次注册表
 */
class Model_PrevioReg extends ORM{

    /**
     * 数据表名czzs_zx_article
     */
    protected $_table_name  = 'previo_reg';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model