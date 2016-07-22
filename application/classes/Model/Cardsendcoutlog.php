<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 记录当天名片发送次数总数统计表Model
 * @author 钟涛
 *
 */
class Model_Cardsendcoutlog extends ORM{

    /**
     * 数据表名czzs_card_sendcount_log
     */
    protected $_table_name  = 'card_sendcount_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


}//End Cardsendcoutlog Model