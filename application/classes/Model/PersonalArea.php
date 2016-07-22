<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 个人地域信息
 * @author 施磊
 */
class Model_PersonalArea extends ORM {

    /**
     * 表名称
     */
    protected $_table_name = "user_personal_area";

    /**
     * 主键名称
     */
    protected $_primary_key = 'per_area_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

    /**
     * 配置需要搜索的列
     */
    protected $_search_row = array(
            'area_id',//城市ID
            'pro_id',//省份ID
    );

}
