<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 验证码表
 * @author 龚湧
 *
 */
class Model_Validcode extends ORM{
    /**
    * 表名称
    */
    protected $_table_name = "valid_code";

    /**
    * 主键名称
    */
    protected $_primary_key = 'id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';

    /**
     * 对应关系
     */
    protected $_belongs_to = array(
            "user"=>array(
                "model"=>"User",
                "foreign_key"=> "user_id"
            )
    );
}