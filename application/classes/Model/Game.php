<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 抽奖活动表
 * @author stone.shi
 *
 */
class Model_Game extends ORM{

    /**
     * 数据表名czzs_Cardinfo_log
     */
    protected $_table_name  = 'game';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Account Model