<?php defined('SYSPATH') or die('No direct script access.');

return array(

        'project_brand_name' => array(
            'not_empty' => '请填写您要招商的品牌名称',
        ),
        'project_brand_birthplace' => array(
                'not_empty' => '品牌发源地不能为空',
        ),
        'project_logo' => array(
                'not_empty' => '请选择您需要上传的品牌logo',
        ),
        'project_industry_id' => array(
                'not_empty' => '请选择所属行业',
        ),
        'project_principal_products' => array(
                'not_empty' => '请填写招商项目的主营产品',
        ),
        'project_amount_type' => array(
                'not_empty' => '请选择投资金额',
        ),
        'project_joining_fee' => array(
                'not_empty' => '请填写招商项目的加盟费用',
                'digit' => '请填写数字',
        ),
        'project_security_deposit' => array(
                'digit' => '请填写数字',
        ),
        'project_co_model' => array(
                'not_empty' => '请选择招商形式',
        ),
        'project_phone' => array(
                'not_empty' => '请填写座机号码或者手机号码',
        ),
        'project_join_conditions' => array(
                'not_empty' => '请填写加盟条件',
        ),
        'project_summary' => array(
                'not_empty' => '请填写项目简介',
        ),
);
