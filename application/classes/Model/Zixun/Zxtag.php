<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @资讯标签 ORM
 * @author 潘宗磊
 */
class Model_Zixun_Zxtag extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "zx_tag";

    /**
     * 主键名称
     */
    protected $_primary_key = 'tag_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Industry Model
