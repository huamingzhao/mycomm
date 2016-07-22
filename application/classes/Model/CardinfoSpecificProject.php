<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 为特定项目增加的名片信息的记录表
 * @author 赵路生
 *
 */
class Model_CardinfoSpecificProject extends ORM{

    /**
     * 数据表名 czzs_card_info_specific_project
     */
    protected $_table_name  = 'card_info_specific_project';

    /**
     * 主键名称
     */
    protected $_primary_key = 'id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

}//End Model