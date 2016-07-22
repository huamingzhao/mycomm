<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 排行榜倍数表
 * @author 郁政
 */
class Model_ProjectRankingList extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "project_ranking_list";

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
