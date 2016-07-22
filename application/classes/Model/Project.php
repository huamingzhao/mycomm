<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Default auth user
 *
 * @package    Kohana/Project
 * @author     cao
 */
class Model_Project extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "project";

    /**
     * 主键名称
     */
    protected $_primary_key = 'project_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

    /**
     * 一一对应关系
     * @var unknown_type
     */
    protected $_has_one = array(
            'poster' => array(
                    'model'       => 'Projectposter',
                    'foreign_key' => 'project_id',
            ),
    );

    /**
     * 对应关系
     */
    protected $_belongs_to = array(
            "project_area"=>array(
                    "model"=>"projectarea",
                    "foreign_key"=> "project_id",
            ),
            "project_model"=>array(
                    "model"=>"projectmodel",
                    "foreign_key"=> "project_id",
            ),
            "user_company" =>array(
                    "model"=>"Companyinfo",
                    "foreign_key"=> "com_id",
            )
    );

    /**
     * 配置需要搜索的列
     */
    protected $_search_row = array(
            'project_industry_id',//所属行业
            'project_amount_type',//投资金额
    );
} // End Auth User Model
