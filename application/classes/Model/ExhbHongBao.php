<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 红包领取记录表
 * @author 郁政
 *
 */
class Model_ExhbHongBao extends ORM{

    /**
     * 数据表名czzs_exhb_hongbao_fetch
     */
    protected $_table_name  = 'exhb_hongbao_fetch';

    /**
     * 主键名称
     */
    protected $_primary_key = 'fetch_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End ExhbHongBao Model