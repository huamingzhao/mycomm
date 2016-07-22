<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 记录企业免费查看个人名片log日志【暂时每天只可免费查看3张】Model
 * @author 钟涛
 * 2013-06-08
 */
class Model_Cardfreeviewcardlog extends ORM{

    /**
     * 数据表名czzs_card_freeviewcard_log
     */
    protected $_table_name  = 'card_freeviewcard_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';


}//End Cardfreeviewcardlog Model