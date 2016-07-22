<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 消息控制model
 * @author 许晟玮
 *
 */
class Model_MsgControl extends ORM{

    /**
    * 企业用户信息表
    */
    protected $_table_name  = 'msg_control';

    /**
    * 主键名称
    */
    protected $_primary_key = 'id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';

}