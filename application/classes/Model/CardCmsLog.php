<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 875项目留言导入CRM系统log表Model
 * @author 钟涛
 *
 */
class Model_CardCmsLog extends ORM{

    /**
     * 数据表名czzs_card_crm_log
     */
    protected $_table_name  = 'card_crm_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End CardCmsLog Model