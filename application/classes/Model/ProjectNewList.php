<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @最新项目排行榜锁定 ORM
 * @author 郁政
 */
class Model_ProjectNewList extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "project_new_list";

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
