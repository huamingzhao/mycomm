<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 个人基本信息中我的标签新西兰Model
 * @author 钟涛
 */
class Model_Personcrowd extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_person_crowd";

    /**
     * 主键名称
     */
    protected $_primary_key = 'crowd_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Personcrowd Model
