<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 邮箱修改日志记录表
 * @author 许晟玮
 *
 */
class Model_UserEmailStatus extends ORM{

    /**
     * 数据表名czzs_user_email_status
     */
    protected $_table_name  = 'user_email_status';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model