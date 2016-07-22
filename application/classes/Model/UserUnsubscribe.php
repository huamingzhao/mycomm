<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 用户【手机、邮件】退订记录表
 * @author 钟涛
 */
class Model_UserUnsubscribe extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_unsubscribe";

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End UserUnsubscribe Model
