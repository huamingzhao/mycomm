<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 名片行为动作日志表
 * @author 钟涛
 *
 */
class Model_Cardinfobehaviour extends ORM{

    /**
     * 数据表名czzs_cardinfo_behaviour
     */
    protected $_table_name  = 'card_info_behaviour';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


}//End Cardinfobehaviour Model