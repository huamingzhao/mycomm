<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 权限表
 * @author 施磊
 */
class Model_Permission extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "permission";

    /**
     * 主键名称
     */
    protected $_primary_key = 'permission_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Personcrowd Model
