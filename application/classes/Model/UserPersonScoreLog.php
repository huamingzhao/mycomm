<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户活跃度Model
 * @author 钟涛
 * 2013-07-15
 */
class Model_UserPersonScoreLog extends ORM{

    /**
     * 数据表名czzs_user_person_score_log
     */
    protected $_table_name  = 'user_person_score_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End