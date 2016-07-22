<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 用户留言表
 * @author 
 *
 */
class Model_UserLetter extends ORM{
    /**
    * 表名称
    */
    protected $_table_name = "user_letter";

    /**
    * 主键名称
    */
    protected $_primary_key = 'id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';
}