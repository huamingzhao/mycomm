<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @用户邀请表 ORM
 * @author 施磊
 */
class Model_UserInviter extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_inviter";

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

} //End UserHuodong Model
