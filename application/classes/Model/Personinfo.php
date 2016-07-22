<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户信息表Model
 * @author 钟涛
 *
 */
class Model_Personinfo extends ORM{

    /**
     * 加密手机号码
     * (non-PHPdoc)
     * @see Kohana_ORM::filters()
     */
    public function filters()
    {
        return array(
                'per_phone' => array(
                        array("common::encodeMoible",array(":value")),
                )
        );
    }

    public function deFileters(){
        return array(
                'per_phone' => array(
                        array("common::decodeMoible",array(":value")),
                )
        );
    }

    /**
     * 数据表名czzs_user_person
     */
    protected $_table_name  = 'user_person';

    /**
     * 主键名称
     */
    protected $_primary_key = 'per_id';

    /**
     * 数据库连接配置
     */
    protected $_db_group = 'default';

    /**
     * 配置需要搜索的列
     */
    protected $_search_row = array(
            'per_industry',//意向行业
            'per_amount',//投资金额
            'per_join_project',//投资者加盟项目方式
            'per_connections',//人脉关系
            'per_identity',//投资者身份
            'per_investment_style',//个人投资风格
    		'per_education',//学历
    		'per_area',//个人所在地省份
    		'per_city',//个人所在地城市
    );
}//End Personinfo Model