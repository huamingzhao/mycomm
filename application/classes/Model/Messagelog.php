<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 短信发送记录日志 ORM
 * @author 龚湧
 *
 */
class Model_Messagelog extends ORM{

    /**
    * 企业用户信息表
    */
    protected $_table_name  = 'message_log';

    /**
    * 主键名称
    */
    protected $_primary_key = 'id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';

}