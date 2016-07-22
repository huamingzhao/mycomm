<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 企业用户信息——通过项目表里面的out_com_id来获取企业信息的专用表
 * @author 赵路生
 *
 */
class Model_OutComUser extends ORM {
    /**
     * 表名称 czzs_out_user_company
     */
    protected $_table_name = "out_user_company";

    /**
     * 主键名称
     */
    protected $_primary_key = 'com_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';
}