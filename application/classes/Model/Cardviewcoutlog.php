<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 记录个人用户被企业所查看限制【30天内只能被5个企业所查看】Model
 * @author 钟涛
 *
 */
class Model_Cardviewcoutlog extends ORM{

    /**
     * 数据表名czzs_card_viewcount_log
     */
    protected $_table_name  = 'card_reviewcount_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


}//End Cardsendcoutlog Model