<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业用户信息 ORM
 * @author 龚湧
 *
 */
class Model_Companyinfo extends ORM{

    /**
     * 加密手机号码
     * (non-PHPdoc)
     * @see Kohana_ORM::filters()
     */
    public function filters()
    {
        return array(
                'com_phone' => array(
                        array("common::encodeMoible",array(":value")),
                )
        );
    }

    public function deFileters(){
        return array(
                'com_phone' => array(
                        array("common::decodeMoible",array(":value")),
                )
        );
    }

    /**
    * 对应关系
    * @author 龚湧
    */
    protected $_has_many = array(
        'com_othercertifications' => array( //其他企业认证信息
            'model'       => 'Othercerts',
            'foreign_key' => 'com_id',
        ),
        'com_project' => array( //公司项目关联 一对多 author周进2012/11/25
            'model'       => 'Project',
            'foreign_key' => 'com_id',
        ),
        'com_projectcertifications' => array( //公司项目资质认证关联 一对多
            'model'       => 'Projectcerts',
            'foreign_key' => 'com_id',
        ),
    );

    /**
     * 企业资质已验证状态
     */
    const ENABLE_STATUS= 1;

    /**
    * 企业用户信息表
    */
    protected $_table_name  = 'user_company';

    /**
    * 主键名称
    */
    protected $_primary_key = 'com_id';

    /**
    * 数据库连接配置
    */
    protected $_db_group = 'default';

}