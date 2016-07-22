<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 账号冻结表Model
 * @author 周进
 *
 */
class Model_Accountblocked extends ORM{

    /**
     * 数据表名czzs_Cardinfo_log
     */
    protected $_table_name  = 'account_blocked';

    /**
     * 主键名称
     */
    protected $_primary_key = 'blocked_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model