<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 记录用户最近浏览的项目与招商会情况Model
 * @author 钟涛
 * 2013-06-24
 */
class Model_UserViewProjectLog extends ORM{

    /**
     * 数据表名czzs_user_view_project_log
     */
    protected $_table_name  = 'user_view_project_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End