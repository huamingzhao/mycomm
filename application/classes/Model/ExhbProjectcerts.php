<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 网络展会产品图 ORM
 * @author 郁政
 *
 */
class Model_ExhbProjectcerts extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'exhb_project_certs';

    /**
     * 主键名称
     */
    protected $_primary_key = 'project_certs_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';
}