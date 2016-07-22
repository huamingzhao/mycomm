<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人收藏的项目  MODEL
 * @author 钟涛
 *
 */
class Model_Projectwatch extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'project_watch';

    /**
     * 主键名称
     */
    protected $_primary_key = 'watch_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

    /**
     * 配置需要搜索的列
     */
    protected $_search_row = array(
            'watch_update_time',//收藏项目时间
    );
}