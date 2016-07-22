<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业其他认证 记录 ORM
 * @author 龚湧
 *
 */
class Model_Othercerts extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'other_certs';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';
}