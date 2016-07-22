<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 项目海报表
 * @author 龚湧
 *
 */
class Model_Projectposter extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'project_poster';

    /**
     * 主键名称
     */
    protected $_primary_key = 'project_id';

    /**
     * 一一对应关系
     * @var unknown_type
     */
    protected $_has_one = array(
            'content' => array(
                    'model'       => 'ProjectposterContent',
                    'foreign_key' => 'project_id',
            ),
    );

    protected $_belongs_to = array(
            'project' => array(
                    'model'       => 'Project',
                    'foreign_key' => 'project_id',
            ),
    );

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';
}