<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 充值记录表Model
 * @author 周进
 *
 */
class Model_Accountlog extends ORM{

    /**
     * 数据表名czzs_Cardinfo_log
     */
    protected $_table_name  = 'account_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'account_log_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


}//End Accountlog Model