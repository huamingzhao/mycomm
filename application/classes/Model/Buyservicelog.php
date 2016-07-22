<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 服务表使用记录表
 * @author 周进
 *
 */
class Model_Buyservicelog extends ORM{

    /**
     * 数据表名czzs_buy_service_log
     */
    protected $_table_name  = 'buy_service_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'log_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model