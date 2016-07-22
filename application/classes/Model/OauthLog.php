<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @地区信息 ORM
 * @author 曹怀栋
 */
class Model_OauthLog extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "oauth_log";

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
