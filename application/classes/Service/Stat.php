<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 统计ßservice
 * @author 龚湧
 *
 */
class Service_Stat{

    /**
     * 注册完成写如用户统计 调用api
     * @return boolean
     */
    public function setUserRegStat( $user_id,$type,$date,$domain,$sid ){



        //写入IP地址,同步bi_stat_userregip,用来注册统计
        $user_regip_model       		= ORM::factory('UserRegIp');
        $user_regip_model->user_type    = $type;
        $user_regip_model->user_id      = $user_id;
        $user_regip_model->user_reg_ip  = ip2long( Request::$client_ip );
        $user_regip_model->user_reg_time= $date;
        $user_regip_model->sid			= $sid;
        $user_regip_model->fromdomain	= $domain;
        $user_regip_model->convert		= common::convertip( Request::$client_ip );
        $user_regip_model->save();
        //print_r ($user_regip_model);exit;
        return true;

    }

}