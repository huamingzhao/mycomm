<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 邮件发送日志表Model
 * @author 钟涛
 *
 */
class Model_EmailSendQueueLog extends ORM{

    /**
     * 数据表名czzs_email_send_queue_log
     */
    protected $_table_name  = 'email_send_queue_log';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End EmailSendQueueLog Model