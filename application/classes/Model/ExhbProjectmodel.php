<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @网络展会地区信息 ORM
 * @author 郁政
 */
class Model_ExhbProjectmodel extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "exhb_project_model";

    /**
     * 主键名称
     */
    protected $_primary_key = 'project_model_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
