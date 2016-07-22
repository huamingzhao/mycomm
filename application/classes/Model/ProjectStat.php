<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 项目统计表
 * @author 许晟玮
 *
 */
class Model_ProjectStat extends ORM{

    /**
     * 数据表名czzs_stat_project
     */
    protected $_table_name  = 'stat_project';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


}//End Accountrecharge Model