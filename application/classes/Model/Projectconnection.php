<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @人脉关系表 ORM
 * @author 曹怀栋
 */
class Model_Projectconnection extends ORM {
    /**
     * 表名称
     */
    protected $_table_name  = 'project_connection';

    /**
     * 主键名称
     */
    protected $_primary_key = 'pc_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
