<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 记录从项目官网页调转到公司官网页记录信息 【可用于计算页面跳转率】Model
 * @author 钟涛
 * 2013-06-13
 */
class Model_Projectoutlog extends ORM{

    /**
     * 数据表名czzs_project_out_log
     */
    protected $_table_name  = 'project_out_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End