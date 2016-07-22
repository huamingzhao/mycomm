
<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @操作原因记录表 ORM
 * @author 花文刚
 */
class Model_Reason extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "operate_reason";

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
