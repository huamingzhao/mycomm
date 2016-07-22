<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 手机用户表
 * @author 郁政
 *
 */
class Model_MobileAccount extends ORM{

    /**
     * 数据表名czzs_mobile_account
     */
    protected $_table_name  = 'mobile_account';

    /**
     * 主键名称
     */
    protected $_primary_key = 'mobile_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End MobileAccount Model