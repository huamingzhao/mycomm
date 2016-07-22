<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生意帮-用户操作统计
 * @author 兔毛 2014-06-09
 */
class Model_Business_UserStat extends ORM{

    /**
     * 数据表名czzs_business_user_stat
     */
    protected $_table_name  = 'business_user_stat';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model