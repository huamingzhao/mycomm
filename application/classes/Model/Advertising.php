<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 资讯广告管理表
 * @author 许晟玮
 *
 */
class Model_Advertising extends ORM{

    /**
     * 数据表名czzs_zx_advertising
     */
    protected $_table_name  = 'zx_advertising';

    /**
     * 主键名称
     */
    protected $_primary_key = 'ad_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model