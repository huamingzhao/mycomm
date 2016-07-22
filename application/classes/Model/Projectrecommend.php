<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Default Industry
 *
 * @package    Kohana/Industry
 * @author     jiye
 */
class Model_Projectrecommend extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "project_recommend";

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}
