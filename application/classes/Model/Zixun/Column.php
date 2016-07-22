<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 资讯栏目表
 * @author 周进
 *
 */
class Model_Zixun_Column extends ORM{

    /**
     * 数据表名czzs_zx_column
     */
    protected $_table_name  = 'zx_column';

    /**
     * 主键名称
     */
    protected $_primary_key = 'column_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model