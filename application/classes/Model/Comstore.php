<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 加盟店地址
 * @author 许晟玮
 *
 */
class Model_Comstore extends ORM{

    /**
     * 数据表名czzs_company_store_info
     */
    protected $_table_name  = 'company_store_info';

    /**
     * 主键名称
     */
    protected $_primary_key = 'store_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


}//End Accountlog Model