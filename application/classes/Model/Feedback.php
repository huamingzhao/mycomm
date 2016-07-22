<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 帮助中心-用户反馈 ORM
 * @author 钟涛
 *
 */
class Model_Feedback extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'feedback';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';
}