<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 网络展会 项目表
 * @author stone shi
 *
 */
class Model_ExhbProject extends ORM{

    /**
     * 数据表名czzs_exhb_project
     */
    protected $_table_name  = 'exhb_project';

    /**
     * 主键名称
     */
    protected $_primary_key = 'project_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End EmailSendQueueLog Model