<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 个人用户活跃度计算
 */
class points_person_type{
    /**
    * 活跃度类型
    * @author 钟涛
    */
    public static function types(){
        return array(
        		'register'=>array(//用户注册
        				'id'=>1,
        				'points' => 100,
        				'chuanyebi_id' => 13,
        				'is_repeat'=>0,//一次性
        				'cishu'=>1,//最多统计一次
        				'points_desc' => "用户注册"
        		),
        		'valid_email'=>array(//邮箱验证
        				'id'=>2,
        				'points' => 50,
        				'chuanyebi_id' => 14,
        				'is_repeat'=>0,//一次性
        				'cishu'=>1,//最多统计一次
        				'points_desc' => "邮箱验证"
        		),
        		'valid_mobile'=>array(//手机认证
        				'id'=>3,
        				'points' => 100,
        				'chuanyebi_id' => 15,
        				'is_repeat'=>0,//一次性
        				'cishu'=>1,//最多统计一次
        				'points_desc' => "手机号码验证"
        		),
        		'per_auth_status'=>array(//身份证认证
        				'id'=>4,
        				'points' => 100,
        				'chuanyebi_id' => 16,
        				'is_repeat'=>0,//一次性
        				'cishu'=>1,//最多统计一次
        				'points_desc' => "身份证认证"
        		),
        		'per_perfect_one'=>array(//完善基本信息
        				'id'=>5,
        				'points' => 20,
        				'chuanyebi_id' => 17,
        				'is_repeat'=>0,//一次性
        				'cishu'=>1,//最多统计一次
        				'points_desc' => "完善基本信息"
        		),
        		'per_perfect_two'=>array(//完善意向投资信息
        				'id'=>6,
        				'points' => 20,
        				'chuanyebi_id' => 18,
        				'is_repeat'=>0,//一次性
        				'cishu'=>1,//最多统计一次
        				'points_desc' => "完善意向投资信息"
        		),
        		'per_perfect_three'=>array(//完善从业经验
        				'id'=>7,
        				'points' => 20,
        				'chuanyebi_id' => 19,
        				'is_repeat'=>0,//一次性
        				'cishu'=>1,//最多统计一次
        				'points_desc' => "完善从业经验"
        		),
                'login'=>array(//每日用户登录
                        'id'=>8,//类型id
                		'points'=> 5,
        				'chuanyebi_id' => 20,
                        'is_repeat'=>1,//非一次性
                		'cishu'=>2,//一天最多统计2次[日上限为10分]
                        'points_desc' => "用户登录"
                ),
        		'view_project'=>array(//查看项目
        				'id'=>9,//类型id
        				'points'=> 2,
                		'chuanyebi_id' => 21,
        				'is_repeat'=>1,//非一次性
        				'cishu'=>5,//一天最多统计5次[日上限为10分]
        				'points_desc' => "查看项目"
        		),
        		'send_card'=>array(//递送名片
        				'id'=>10,//类型id
        				'points'=> 10,
        				'chuanyebi_id' => 22,
        				'is_repeat'=>1,//非一次性
        				'cishu'=>5,//一天最多统计5次[日上限为50分]
        				'points_desc' => "递送名片"
        		),
        		'sign_up_investment'=>array(//报名投资考察会
        				'id'=>11,//类型id
        				'points'=> 10,
        				'chuanyebi_id' => 23,
        				'is_repeat'=>1,//非一次性
        				'cishu'=>5,//一天最多统计5次[日上限为50分]
        				'points_desc' => "报名投资考察会"
        		),
        		'favorite_project'=>array(//收藏项目
        				'id'=>12,//类型id
        				'points'=> 3,
        				'chuanyebi_id' => 24,
        				'is_repeat'=>1,//非一次性
        				'cishu'=>5,//一天最多统计5次[日上限为15分]
        				'points_desc' => "收藏项目"
        		),
        		'add_zixun'=>array(//资讯投稿
        				'id'=>13,//类型id
        				'points'=> 20,
        				'chuanyebi_id' => 25,
        				'is_repeat'=>1,//非一次性
        				'cishu'=>1,//一天最多统计1次[日上限为20分]
        				'points_desc' => "资讯投稿"
        		),
        		'share_project'=>array(//分享项目&资讯
        				'id'=>14,//类型id
        				'points'=> 5,
        				'chuanyebi_id' => 26,
        				'is_repeat'=>1,//非一次性
        				'cishu'=>4,//一天最多统计4次[日上限为20分]
        				'points_desc' => "分享项目&资讯"
        		),
        		'share_zixun'=>array(//分享资讯【暂时无用】
        				'id'=>15,//类型id
        				'points'=> 5,
        				'chuanyebi_id' => 27,
        				'is_repeat'=>1,//非一次性
        				'cishu'=>4,//一天最多统计4次[日上限为20分]
        				'points_desc' => "分享资讯"
        		),
        		'chuanyebi'=>array(//创业币,暂时不用
        				'id'=>16,//类型id
        				'points'=> 1000,
        				'chuanyebi_id' => 28,
        				'is_repeat'=>1,//非一次性
        				'cishu'=>1,//一天最多统计1次[日上限为1000分]
        				'points_desc' => "创业币"
        		)
        );
    }

    /**
     * 活跃度类型
     * @author 钟涛
     */
    public static function typesid(){
    	return array(
    			'1'=> "用户注册",
    			'2'=> "邮箱验证",
    			'3'=> "手机号码验证",
    			'4'=> "身份证认证",
    			'5'=> "完善基本信息",
    			'6'=> "完善意向投资信息",
    			'7'=> "完善从业经验",
    			'8'=> "用户登录",
    			'9'=> "查看项目",
    			'10'=> "递送名片",
    			'11'=> "报名投资考察会",
    			'12'=> "收藏项目",
    			'13'=> "资讯投稿",
    			'14'=> "分享项目|资讯",
    			'15'=> "分享资讯",
    			'16'=> "创业币"
    	);
    }
    /**
     * 根据类型id获取积分描述
     * @author 钟涛
     * @param unknown_type $id
     */
    public static function getTypeById($id){
        $desc = self::typesid();
        return Arr::get($desc, $id,'');
    }

    /**
     * 根据类型名称获取积分描述
     * @author 钟涛
     * @param unknown_type $name
     */
    public static function getByName($name){
        $types = self::types();
        return Arr::get($types,$name,array());
    }
}