<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 发送名片记录表Model
 * @author 钟涛
 *
 */
class Model_Cardinfolog extends ORM{

    /**
     * 数据表名czzs_Cardinfo_log
     */
    protected $_table_name  = 'card_info_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


}//End Cardinfolog Model