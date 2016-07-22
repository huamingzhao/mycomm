<?php defined('SYSPATH') or die('No direct script access.');

return array(

        'apply_name' => array(
            'not_empty' => '姓名不能为空',
        ),
        'apply_mobile' => array(
                'not_empty' => '手机号码不能为空',
                'regex' => '手机号码格式不正确'
        ),
        'is_hotel' => array(
                'not_empty' => '是否入住酒店不能为空',
        ),
        'invest_id' => array(
                'not_empty' => '招商会不能为空',
        ),
        'investment_name' => array(
                'not_empty' => '招商会标题不能为空',
                'max_length' => '招商会标题不能超过20个字',
        ),
        'com_name' => array(
                'not_empty' => '招商会联系人不能为空',
        ),
        'com_phone' => array(
                'not_empty' => '招商会联系电话不能为空',
                'regex' => '招商会联系电话格式不正确'
        ),
        'investment_logo' => array(
                'not_empty' => '招商会logo不能为空',
        ),
        'investment_address' => array(
                'not_empty' => '招商会会议地点不能为空',
                'max_length' => '招商会会议地点不能超过30个字',
        ),
        'investment_start' => array(
                'not_empty' => '招商会开始时间不能为空',
        ),
        'investment_end' => array(
                'not_empty' => '招商会结束时间不能为空',
        ),
        'investment_province' => array(
                'not_empty' => '招商会所在省份不能为空',
        ),
        'investment_city' => array(
                'not_empty' => '招商会所在城市不能为空',
        ),
        'investment_agenda' => array(
                'not_empty' => '招商会议流程不能为空',
                'max_length' => '招商会议流程不能超过200个字',
        ),
        'investment_details' => array(
                'not_empty' => '招商会详情不能为空',
                'max_length' => '招商会详情不能超过200个字',
        ),
        'putup_type' => array(
                'not_empty' => '安排住宿不能为空',
        ),
);
