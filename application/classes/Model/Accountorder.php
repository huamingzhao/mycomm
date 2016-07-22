<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 订单记录表Model
 * @author 周进
 *
 */
class Model_Accountorder extends ORM{

    /**
     * 数据表名czzs_Cardinfo_log
     */
    protected $_table_name  = 'account_order';

    /**
     * 主键名称
     */
    protected $_primary_key = 'order_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


}//End Accountrecharge Model