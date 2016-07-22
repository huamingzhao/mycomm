<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @项目-标签关系表 ORM
 * @author 沈鹏飞
 */
class Model_Projecttag extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "project_tag";

    /**
     * 主键名称
     */
    protected $_primary_key = 'pt_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
