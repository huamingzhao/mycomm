<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @中奖 ORM
 * @author 郁政
 */
class Model_UserHuoDong extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_huodong";

    /**
     * 主键名称
     */
    protected $_primary_key = 'tid';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End UserHuodong Model
