<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 账号余额表Model
 * @author 周进
 *
 */
class Model_Account extends ORM{

    /**
     * 数据表名czzs_Cardinfo_log
     */
    protected $_table_name  = 'account';

    /**
     * 主键名称
     */
    protected $_primary_key = 'account_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


    /**
     * 获取用户金额在表中是否存在
     */
    public function get_num($user_id){
        return $this->where("account_user_id", "=", $user_id)->count_all();
    }

}//End Account Model