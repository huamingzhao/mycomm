<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 用户中心消息表
 * @author 龚湧
 *
 */
class Model_UserRegIp extends ORM{
    /**
    * 表名称
    */
    protected $_table_name = "bi_stat_user_reg";

    /**
    * 主键名称
    */
    protected $_primary_key = 'id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';
}