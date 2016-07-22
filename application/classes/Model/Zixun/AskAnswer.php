<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 创业问题回答表
 * @author 钟涛
 *
 */
class Model_Zixun_AskAnswer extends ORM{

    /**
     * 数据表名czzs_ask_answer
     */
    protected $_table_name  = 'ask_answer';

    /**
     * 主键名称
     */
    protected $_primary_key = 'ask_answer_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Askanswer Model