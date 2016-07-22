<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业其他认证 记录 ORM
 * @author 潘宗磊
 *
 */
class Model_Projectcerts extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'project_certs';

    /**
     * 主键名称
     */
    protected $_primary_key = 'project_certs_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';
}