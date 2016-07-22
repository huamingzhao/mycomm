<?php
return array(
        'email' => array(
                'not_empty' => '邮件不能为空',
                'email' => '邮件格式不正确',
                'unique' => '该邮件已经被注册',
        ),
        'password' => array(
                'not_empty' => '密码不能为空',
                'min_length' => '密码长度最少为6位',
                'max_length' => '密码长度最大不能超过20位',
        ),
        'confirm' => array(
                'not_empty' => '确认密码不能为空',
                'matches' => '两次输入密码不一致',
        ),
        'valid_code'=>array(
                'not_empty' => '验证码不能为空',
        ),
        /*
        '_external'=> array(
                'password'=>'密码不能为空',
                'confirm' => '两次输入密码不一致',
        )
        */
);