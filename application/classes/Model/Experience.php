<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户从业经验Model
 * @author 钟涛
 *
 */
class Model_Experience extends ORM{

    /**
     * 数据表名czzs_user_experience
     */
    protected $_table_name  = 'user_experience';

    /**
     * 主键名称
     */
    protected $_primary_key = 'exp_id';

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
            "foreign_key"=> "exp_user_id"
        )
    );

}//End Experience Model