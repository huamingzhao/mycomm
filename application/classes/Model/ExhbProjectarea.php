<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @网络展会地区信息 ORM
 * @author 郁政
 */
class Model_ExhbProjectarea extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "exhb_project_area";

    /**
     * 主键名称
     */
    protected $_primary_key = 'project_area_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

    /**
     * 配置需要搜索的列
     */
    protected $_search_row = array(
            'area_id',//城市id
            'pro_id',//省份id
    );

} //End Industry Model
