<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 企业用户搜索订阅投资者表
 * @author 施磊
 */
class Model_Subscription extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "subscription";

    /**
     * 主键名称
     */
    protected $_primary_key = 'subscription_id';
   
    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End Personcrowd Model
