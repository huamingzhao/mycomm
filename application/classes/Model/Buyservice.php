<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 购买服务表
 * @author 周进
 *
 */
class Model_Buyservice extends ORM{

    /**
     * 数据表名czzs_Cardinfo_log
     */
    protected $_table_name  = 'buy_service';

    /**
     * 主键名称
     */
    protected $_primary_key = 'buy_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model