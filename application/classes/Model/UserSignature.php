<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 用户签名表
 * @author stone shi
 *
 */
class Model_UserSignature extends ORM{
    /**
    * 表名称
    */
    protected $_table_name = "user_signature";

    /**
    * 主键名称
    */
    protected $_primary_key = 'id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';
}