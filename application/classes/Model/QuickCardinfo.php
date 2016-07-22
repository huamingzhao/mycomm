<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 发送名片
 * @author 兔毛 2014-05-21
 */
class Model_QuickCardinfo extends ORM{

    /**
     * 数据表名czzs_Cardinfo_log
     */
    protected $_table_name  = 'quick_card_info';

    /**
     * 主键名称
     */
    protected $_primary_key = 'card_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

    /**
     * 添加对应关系
     * @var array
     */
    protected $_belongs_to = array(
        'user' => array(
            'model'       => 'User',
            'foreign_key' => 'from_user_id',
        ),
        'user_to' => array(
                'model'       => 'User',
                'foreign_key' => 'to_user_id',
        ),
    );

    /**
     * 配置需要搜索的列
     */
    protected $_search_row = array(
            'send_count',//发送次数
            'to_read_status',//我收到的名片是否已阅读
            'from_read_status',//我递出的名片是否已阅读
            'send_time',//名片发送时间
            'exchange_status',//名片是否已交换
    );
}//End Cardinfo Model