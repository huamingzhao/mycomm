<?php defined('SYSPATH') OR die('No direct script access.');
/*
 * 短信发送接口，用户名和密码
 * 这个是和ip绑定的
 */
return array(
    'url' => "http://app-sms.tonglukuaijian.com/smsservice/send",
    //本地 ,特定ip 10.27.50.9
    'local' => array(
        'acode' => '123',//用户名
        'token' => '123456', //密码
    ),
    //测试服务器 192.168.1.60
    'online' => array(
        'acode' => '002',//用户名
        'token' => 'jv4b#1kax8O4@tur',//密码
    ),

    'expire' => array(
            'mobile'=>7*3600, //手机短信验证码过期时间
            'email'=>10*24*3600,//email验证链接的优先时间默认10天
    ),

    'space_time' =>array(
        'mobile' => 60,//手机验证码发送最短时间间隔
    )
);