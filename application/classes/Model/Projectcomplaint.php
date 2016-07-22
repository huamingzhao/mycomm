<?php defined('SYSPATH') or die('No direct script access.');


/**
 * 项目投诉举报信息
 * author: 兔毛  2014-05-15
 */
class Model_Projectcomplaint extends ORM{

    /**
     * 数据表名czzs_project_out_log
     */
    protected $_table_name  = 'project_complaint';

    /**
     * 主键名称
     */
    protected $_primary_key = 'complaint_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End