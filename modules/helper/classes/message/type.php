<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 消息类型配置
 * @author 龚湧
 *
 */
class message_type{

    /**
    * 消息分类
    * @author 龚湧
    * @return multitype:multitype:number string
    */
    public static function types(){
        return array(
                'company_receive_card'=>array(
                        'id' => 11,
                        'to_url'=>URL::website("company/member/card/receivecard"), //企业收到的名片
                        'desc'=>"企业收到的名片",
                        'group'=>'cards',
                        'href'=>'查看名片'//个人向企业递送名片
                ),
                'company_exchange_card'=>array(
                        'id' => 12,
                        'to_url'=>URL::website("company/member/card/outcard"),//企业收到交换的名片
                        'desc'=>"企业收到交换的名片",
                        'group'=>'cards',
                        'href'=>'查看名片'//个人与企业交换名片
                ),
                'company_audit'=>array(
                        'id' => 13,
                        'to_url'=>"",//暂时不固定
                        'desc'=>"企业项目审核",//通过或不通过都用此状态
                        'group'=>'audit',
                        'href'=>'查看项目官网'//发布的项目审核通过
                ),
                'company_account'=>array(
                        'id' => 14,
                        'to_url'=>"",//暂时不固定
                        'desc'=>"企业账户中心",//通过或不通过都用此状态
                        'group'=>"account",
                        'href'=>''//账户余额不足（账户余额低于30元提醒）

                ),
                'company_account_sp'=>array(
                        'id' => 16,
                        'to_url'=>"",//暂时不固定
                        'desc'=>"企业账户中心",//特殊账户提醒
                        'group'=>"account",
                        'href'=>''
                ),
                'company_integrity'=>array(
                        'id' => 15,
                        'to_url'=>"",//暂时不固定
                        'desc'=>"企业诚信消息",//通过或不通过都用此状态
                        'group'=>'integrity',
                        'href'=>'我的诚信'
                ),//企业诚信
                'company_project'=>array(
                        'id'=>17,
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业项目消息",
                        'group'=>'project',
                        'href'=>''

                ),//企业项目
                'company_zixun_true'=>array(
                    'id'=>'18',
                    'to_url'=>'',//暂时不固定
                    'desc'=>"企业投稿",
                    'group'=>'audit',
                    'href'=>'查看我投稿的文章'//投稿文章审核通过
                ),//企业资讯
                'company_zixun_false'=>array(
                        'id'=>'19',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'重新投稿'//投稿文章审核不通过
                ),//企业资讯
                'company_sc'=>array(
                        'id'=>'30',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'project',
                        'href'=>'查看投资者信息'//个人收藏我的项目
                ),
                'company_grbmwdtzkc'=>array(
                        'id'=>'31',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'project',
                        'href'=>'查看报名详情'//个人报名我的投资考察会
                ),
                'company_qyzzrz_true'=>array(
                        'id'=>'32',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'project',
                        'href'=>'立即发布项目'//企业资质认证通过审核
                ),
                'company_qyzzrz_false'=>array(
                        'id'=>'33',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'重新认证'//企业资质认证审核不通过
                ),
                'company_audit_false'=>array(
                        'id'=>'34',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'重新发布'//发布的项目审核不通过
                ),
                'company_audit_false'=>array(
                        'id'=>'35',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'重新发布'//发布的项目审核不通过
                ),
                'company_rlxm_audit_true'=>array(
                        'id'=>'36',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'查看我认领的项目'//认领的项目审核通过
                ),
                'company_rlxm_audit_false'=>array(
                        'id'=>'37',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>''//认领项目审核不通过
                ),
                'company_tzkchcgbb_audit_true'=>array(
                        'id'=>'38',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'查看播报详情'//投资考察会成果播报审核通过
                ),
                'company_tzkchcgbb_audit_false'=>array(
                        'id'=>'39',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'查看播报详情'//投资考察会成果播报审核不通过
                ),
                'company_fwbz_audit_true'=>array(
                        'id'=>'50',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'查看我的服务保障'//服务保障审核通过
                ),
                'company_fwbz_audit_false'=>array(
                        'id'=>'51',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'修改信息'//服务保障审核不通过
                ),
                'company_xmhb_audit_true'=>array(
                        'id'=>'52',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'查看项目海报'//项目海报审核通过
                ),
                'company_xmhb_audit_false'=>array(
                        'id'=>'53',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'重新上传海报'//项目海报审核不通过
                ),
                'company_server'=>array(
                        'id'=>'54',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'立即去享用'//购买的服务快到期提醒（服务到期前10天提醒）
                ),
                'company_project_zixun_true'=>array(
                        'id'=>'55',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'查看项目新闻'//项目海报审核通过
                ),
                'company_project_zixun_false'=>array(
                        'id'=>'56',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业投稿",
                        'group'=>'audit',
                        'href'=>'查看审核详情'//项目海报审核通过
                ),
                'company_account_refund'=>array(
                        'id'=>'57',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业退款成功",
                        'group'=>'account',
                        'href'=>'去查看'//项目海报审核通过
                ),
                'company_kefu_true'=>array(
                        'id'=>'58',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"客服审核通过",
                        'group'=>'audit',
                        'href'=>'查看客服详情'//项目海报审核通过
                ),
                'company_kefu_false'=>array(
                        'id'=>'59',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"客服审核不通过",
                        'group'=>'audit',
                        'href'=>'查看客服详情'//项目海报审核通过
                ),



                'person_receive_card'=>array(
                        'id' => 21,
                        'to_url'=>URL::website("person/member/card/receivecard"), //个人收到的名片
                        'desc'=>"个人收到的名片",
                        'group'=>"cards",
                        'href'=>'查看名片'//企业向个人递送名片
                ),
                'person_exchange_card'=>array(
                        'id' => 22,
                        'to_url'=>URL::website("person/member/card/outcard"),//个人收到交换的名片
                        'desc'=>"个人收到交换的名片",
                        'group'=>'cards',
                        'href'=>'查看名片'//企业与个人交换名片
                ),
                'person_audit'=>array(
                        'id' => 23,
                        'to_url'=>URL::website("person/member/card/auditstatus"),//个人通过审核
                        'desc'=>"个人通过审核",
                        'group'=>"audit",
                        'href'=>'立即去找项目',//实名认证审核通过
                ),
                'person_project'=>array(
                        'id' => 24,
                        'to_url'=>URL::website("person/member/card/auditstatus"),//企业收到交换的名片
                        'desc'=>"关注项目动态",
                        'group'=>'project',
                        'href'=>''
                ),
                'person_zixun_true'=>array(
                        'id'=>'25',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"个人投稿",
                        'group'=>'audit',
                        'href'=>'查看我投稿的文章'//投稿文章审核通过
                ),
                'person_zixun_false'=>array(
                        'id'=>'26',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"个人投稿",
                        'group'=>'audit',
                        'href'=>'重新投稿'//投稿文章未审核通过
                ),
                'person_zzp'=>array(
                        'id'=>'27',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"收藏的企业资质图片",
                        'group'=>'project',
                        'href'=>'查看项目资质图片'//企业上传项目资质图片
                ),
                'person_sc'=>array(
                        'id'=>'28',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"企业项目收藏",
                        'group'=>'project',
                        'href'=>'查看产品图片>'//企业上传产品图片
                ),
                'person_xmhb'=>array(
                        'id'=>'29',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"查看项目海报",
                        'group'=>'project',
                        'href'=>'查看项目海报>'//项目海报审核通过
                ),
                'person_tzkch'=>array(
                        'id'=>'40',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"查看项目海报",
                        'group'=>'project',
                        'href'=>'查看考察会详情>'//企业发布投资考察会
                ),
                'person_tzkch'=>array(
                        'id'=>'41',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"查看项目海报",
                        'group'=>'project',
                        'href'=>'查看考察会详情>'//企业发布投资考察会
                ),
                'person_tzkchcgbb'=>array(
                        'id'=>'42',
                        'to_url'=>'',//暂时不固定
                        'desc'=>"查看项目海报",
                        'group'=>'audit',
                        'href'=>'查看播报详情>'//投资考察会成果播报审核通过
                ),
                'person_audit_false'=>array(
                        'id' => 43,
                        'to_url'=>URL::website("person/member/card/auditstatus"),//个人通过审核
                        'desc'=>"个人通过审核",
                        'group'=>"audit",
                        'href'=>'重新认证',//实名认证审核不通过
                ),


                //投资考察会审核消息 @花文刚
                'company_tzkchsh_audit_true'=>array(
                    'id'=>'44',
                    'to_url'=>URL::website("company/member/project/myinvestment"),//暂时不固定
                    'desc'=>"企业投稿",
                    'group'=>'audit',
                    'href'=>'查看详情'//投资考察会成果播报审核通过
                ),
                'company_tzkchsh_audit_false'=>array(
                    'id'=>'45',
                    'to_url'=>URL::website("company/member/project/myinvestment"),//暂时不固定
                    'desc'=>"企业投稿",
                    'group'=>'audit',
                    'href'=>'查看详情'//投资考察会成果播报审核不通过
                ),



        );
    }


    /**
     * 更具类型名获取类型信息 或者属性
     * @author 龚湧
     * @param string $name
     * @param string $attr
     * @return boolean | array | string
     */
    public static function getTypeByName($name,$attr=""){
        $type = Arr::get(self::types(),$name);
        if(!$type){
            return false;
        }
        if($attr and Arr::get($type, $attr)){
            return $type[$attr];
        }
        return $type;
    }


    /**
     * TODO 消息id分组
     * 暂时写在配置里面
     * 消息组
     * @author 龚湧
     * @return multitype:multitype:multitype:
     */
    public static function msgGroup(){
        return array(
            'company'=>array(//企业用户组
                'cards'=>array(
                        self::getTypeByName("company_receive_card","id"),
                        self::getTypeByName("company_exchange_card","id"),
                        self::getTypeByName("company_exchange_card","id"),


                ),//收到和交换名片
                "audit" => array(
                        self::getTypeByName("company_audit","id"),
                        self::getTypeByName("company_zixun_true","id"),
                        self::getTypeByName("company_zixun_false","id"),
                        self::getTypeByName("company_qyzzrz_true","id"),
                        self::getTypeByName("company_qyzzrz_false","id"),
                        self::getTypeByName("company_audit_false","id"),
                        self::getTypeByName("company_audit_false_x","id"),
                        self::getTypeByName("company_rlxm_audit_true","id"),
                        self::getTypeByName("company_rlxm_audit_false","id"),
                        self::getTypeByName("company_tzkchcgbb_audit_true","id"),
                        self::getTypeByName("company_tzkchcgbb_audit_fasle","id"),
                        self::getTypeByName("company_fwbz_audit_true","id"),
                        self::getTypeByName("company_fwbz_audit_false","id"),
                        self::getTypeByName("company_xmhb_audit_true","id"),
                        self::getTypeByName("company_xmhb_audit_false","id"),
                        self::getTypeByName("company_server","id"),
                        self::getTypeByName("company_project_zixun_true","id"),
                        self::getTypeByName("company_project_zixun_false","id"),

                        //投资考察会审核消息 @花文刚
                        self::getTypeByName("company_tzkchsh_audit_true","id"),
                        self::getTypeByName("company_tzkchsh_audit_false","id"),
                        //客服
                        self::getTypeByName("company_kefu_true","id"),
                        self::getTypeByName("company_kefu_false","id"),


                ),//审核状态
                "account"=>array(
                        self::getTypeByName("company_account","id"),
                        self::getTypeByName("company_account_sp","id"),
                        self::getTypeByName("company_account_refund","id")
                ), //账户信息
                "integrity"=>array(
                        self::getTypeByName("company_integrity","id")
                ),//诚信指数分组
                "project"=>array(
                        self::getTypeByName("company_project","id"),
                        self::getTypeByName("company_sc","id"),
                        self::getTypeByName("company_grbmwdtzkc","id"),
                ),//项目消息


            ),
            'person'=>array(//个人用户组
                'cards'=>array(
                        self::getTypeByName("person_receive_card","id"),
                        self::getTypeByName("person_exchange_card","id")
                ),//收到和交换名片
                "project"=>array(
                        self::getTypeByName("person_project","id"),
                        self::getTypeByName("person_zzp","id"),
                        self::getTypeByName("person_sc","id"),
                        self::getTypeByName("person_xmhb","id"),
                        self::getTypeByName("person_tzkch","id"),
                        self::getTypeByName("person_tzkch_x","id"),
                        self::getTypeByName("person_tzkchcgbb","id"),

                ), //项目信息
                "audit" => array(
                        self::getTypeByName("person_audit","id"),
                        self::getTypeByName("person_zixun_true","id"),
                        self::getTypeByName("person_zixun_false","id"),
                        self::getTypeByName("person_audit_false","id"),

                ),//审核状态


            ),
        );
    }

    /**
     * 消息组跳转url
     * @author 龚湧
     * @return multitype:multitype:string
     */
    public static function groupUrl(){
        return array(
                'company'=>array(
                        'cards'=>"/company/member/msg",
                        'audit'=>"/company/member/msg/auditstatus",
                        'account'=>"/company/member/msg/account",
                        'integrity'=>"/company/member/msg/integrity"
                ),
                'person'=>array(
                        'cards'=>"/person/member/msg",
                        'project'=>"/person/member/msg/project",
                        'audit'=>"/person/member/msg/auditstatus"
                )
        );
    }

    /**
     * 配置消息ID对应的消息组
     * @author 许晟玮
     */
    public static function msggroupdy(){
        return array(
                "11"=>"cards",
                "12"=>"cards",
                "13"=>"audit",
                "14"=>"account",
                "15"=>"integrity",
                "16"=>"account",
                "17"=>'project',
                "18"=>'audit',
                '19'=>'audit',
                '30'=>'project',
                '31'=>'project',
                '32'=>'project',
                '33'=>'audit',
                '34'=>'audit',
                '35'=>'audit',
                '36'=>'audit',
                '37'=>'audit',
                '38'=>'audit',
                '39'=>'audit',
                '50'=>'audit',
                '51'=>'audit',
                '52'=>'audit',
                '53'=>'audit',
                '54'=>'audit',
                '55'=>'audit',
                '56'=>'audit',
                '57'=>'account',


                "21"=>"cards",
                "22"=>"cards",
                "23"=>"audit",
                "24"=>"project",
                '25'=>'audit',
                '26'=>'audit',
                '27'=>'project',
                '28'=>'project',
                '29'=>'project',
                '40'=>'project',
                '41'=>'project',
                '42'=>'audit',
                '43'=>'audit',

                //44,45-投资考察会审核信息 @花文刚
                '44'=>'audit',
                '45'=>'audit',
                //客服
                '58'=>'audit',
                '59'=>'audit',


        );

    }
    //end function

    /**
     * 针对组配置不同的class
     * @author许晟玮
     */
    public static function msggroupcalss(){
        return array(
            "cards"=>"card",
            "audit"=>"ok",
            "account"=>"money",
            "project"=>"info",
            "server"=>"user",
            "false"=>"no",
            "integrity"=>"",

        );
    }
    //end function



}