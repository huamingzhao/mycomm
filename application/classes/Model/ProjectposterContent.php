<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 项目海报表内容表
 * @author 龚湧
 *
 */
class Model_ProjectposterContent extends ORM{
    /**
    * 表名称
    */
    protected $_table_name  = 'project_poster_content';

    /**
     * 主键名称
     */
    protected $_primary_key = 'project_id';

    /**
     * 对应关系
     * @var unknown_type
     */
    protected $_belongs_to = array(
            'project' => array(
                    'model'       => 'Projectposter',
                    'foreign_key' => 'project_id',
            ),
    );

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';
}