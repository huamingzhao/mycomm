<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 短信验证码记录log
 * @author 周进
 *
 */
class Model_MobileCodeLog extends ORM{

    /**
     * 数据表名czzs_mobile_code_log
     */
    protected $_table_name  = 'mobile_code_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


}//End Accountrecharge Model