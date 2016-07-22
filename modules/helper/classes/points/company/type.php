<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 企业获取和消耗积分类型
 */
class points_company_type{
    /**
    * 积分类型
    * @author 龚湧
    */
    public static function types(){
        return array(
                'login'=>array(//每日用户登录
                        'id'=>1,//类型id
                        'is_plus'=>1,
                        'is_repeat'=>1,
                        'points'=> 2,
                        'points_desc' => "用户登录"
                ),
                'register'=>array(//用户注册
                        'id'=>2,
                        'is_plus'=>1,
                        'is_repeat'=>0,//一次性
                        'points' => 20,
                        'points_desc' => "用户注册"
                ),
                'valid_email'=>array(//邮箱验证
                        'id'=>3,
                        'is_plus'=>1,
                        'is_repeat'=>0,//一次性
                        'points' => 20,
                        'points_desc' => "邮箱通过验证"
                ),
                'valid_mobile'=>array(//手机认证
                        'id'=>4,
                        'is_plus'=>1,
                        'is_repeat'=>1,//
                        'points' => 50,
                        'points_desc' => "手机通过验证"
                ),
                'complete_basic'=>array(
                        'id'=>5,
                        'is_plus'=>1,
                        'is_repeat'=>0,//
                        'points' => 20,
                        'points_desc' => "完善企业基本信息"
                ),
                'project_pass'=>array(
                        'id'=>6,
                        'is_plus'=>1,
                        'is_repeat'=>1,//
                        'points' => 100,
                        'points_desc' => "项目审核通过" //传描述
                ),
                'cert_pass'=>array(
                        'id'=>7,
                        'is_plus'=>1,
                        'is_repeat'=>0,//
                        'points' => 100,
                        'points_desc' => "通过诚信认证"
                ),
                'account_recharge'=>array(
                        'id'=>8,
                        'is_plus'=>1,
                        'is_repeat'=>1,//
                        'points' => 0,//传分数
                        'points_desc' => "账户充值"
                ),
                'adopt_sgt'=>array(
                        'id'=>9,
                        'is_plus'=>1,
                        'is_repeat'=>1,//
                        'points' => 0,//传分数
                        'points_desc' => "建议通过"//传描述
                )
        );
    }

    /**
     * 积分筛选类
     * @author 龚湧
     */
    public static function sort(){
        return array(
                0=>"请选择积分类型",
                1=>"用户充值",
                2=>"其他"
        );
    }

    /**
     * 根据积分类型id获取积分描述
     * @author 龚湧
     * @param unknown_type $id
     */
    public static function getTypeById($id){
        $desc = self::desc();
        return Arr::get($desc, $id);
    }

    /**
     * 更具类型获取消息配置
     * @author 龚湧
     * @param unknown_type $name
     * @return Ambigous <mixed, array>
     */
    public static function getByName($name){
        $types = self::types();
        return Arr::get($types,$name);
    }
}