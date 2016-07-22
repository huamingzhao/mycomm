<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 支付返回日志表Model
 * @author 周进
 *
 */
class Model_Payreturnlog extends ORM{

    /**
     * 数据表名czzs_Cardinfo_log
     */
    protected $_table_name  = 'pay_return_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'log_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model