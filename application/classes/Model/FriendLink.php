<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 友情链接 ORM
 * @author 郁政
 */
class Model_FriendLink extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "friend_link";

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}
?>