<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 创业问题表
 * @author 钟涛
 *
 */
class Model_Zixun_AskTitle extends ORM{

    /**
     * 数据表名czzs_ask_title
     */
    protected $_table_name  = 'ask_title';

    /**
     * 主键名称
     */
    protected $_primary_key = 'ask_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Asktitle Model