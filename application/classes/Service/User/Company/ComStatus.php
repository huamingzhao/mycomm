<?php
defined ( 'SYSPATH' ) or die ( 'No direct script access.' );
/**
 * 用户服务等状态操作
 *
 * @author 许晟玮
 *
 */
class Service_User_Company_ComStatus extends Service_User_Company_User {

    /**
     * 获取保障状态
     *
     * @param
     *        	type all-获取全部 safe-安全保障 server-服务保障 base-基础保障 quality-品质保障
     * @return 0为审核中，1为审核通过，2为审核未通过,3为未提交审核 4-false
     * @author 许晟玮
     */
    public function getCompanyStatusInfo($uid, $type = "safe") {
        $tn= comserver::getComStatusRow($type);

        switch ($tn) {
            case "0" :
                $result = self::getComStatusAll ( $uid );
                break;

            case "1" :
                 $result = self::getComStatusSafe ( $uid );
                 break;

            case "2" :
                $result = self::getComStatusServer ( $uid );
                break;

            case "3" :
                $result = self::getComStatusBasis ( $uid );
                break;

            case "4" :
                $result = self::getComStatusQuality ( $uid );
                break;

            default :
                $result = self::getComStatusBasis ( $uid );
                break;
        }
        if( $result===false ){
            $result='4';
        }
        return $result;
    }
    // end function

    /**
     * 基础保障状态
     *
     * @return 0为审核中，1为审核通过，2为审核未通过,3为未提交审核
     * @author 许晟玮
     */
    private function getComStatusBasis($uid) {
        // 获取用户信息
        $rs_com = self::getCompanyInfo ( $uid )->as_array ();
        if ($rs_com ['com_id'] != "") {
            // 税务登记证1( 暂时用不到这个认证 )
            $tax_certificate_status = $rs_com ['tax_certificate_status'];
            // 工商营业执照1
            $com_business_licence_status = $rs_com ['com_business_licence_status'];
            // 组织机构代码证1
            $organization_credit_status = $rs_com ['organization_credit_status'];
            if ($com_business_licence_status == "1" && $organization_credit_status == "1") {
                return "1";
            } elseif ($com_business_licence_status == "2" || $organization_credit_status == "2") {
                return "2";
            } elseif ($com_business_licence_status == "0" || $organization_credit_status == "0") {
                return "0";
            } elseif ($com_business_licence_status == "3" || $organization_credit_status == "3") {
                return "3";
            }
        } else {
            return "3";
        }
    }
    // end function
    /**
     * 品质保障状态
     *
     * @return 0为审核中，1为审核通过，2为审核未通过,3为未提交审核
     * @author 许晟玮
     */
    private function getComStatusQuality($uid) {
        $rs_com = self::getCompanyInfo ( $uid )->as_array ();
        if ($rs_com ['com_id'] != "") {
            $com_id = $rs_com ['com_id'];
            $project = ORM::factory ( 'Project' );
            $project->where ( 'com_id', '=', $com_id );
            $project->where ( "project_status", "!=", "4" );
            $project->where ( "project_status", "!=", "5" );
            $num = $project->count_all ();
            if ($num == 0) {
                return "3";
            } else {
                //判断是否有通过审核的项目
                $project = ORM::factory ( 'Project' );
                $project->where ( "project_status", "=", "2" );
                $project->where ( 'com_id', '=', $com_id );
                $result = $project->count_all ();
                if ($result > 0) {
                    return "1";
                } else {
                    //没有审核通过的项目,判断是否有存在审核中的项目
                    $project = ORM::factory ( 'Project' );
                    $project->where ( 'com_id', '=', $com_id );
                    $project->where ( "project_status", "=", "1" );
                    $result = $project->count_all ();
                    if( $result>0 ){
                        return "0";
                    }else{
                        return "2";
                    }
                }
            }
        } else {
            return "3";
        }
    }
    // end function

    /**
     * 安全保障状态
     *
     * @return 0为审核中，1为审核通过，2为审核未通过,3为未提交审核
     * @author 许晟玮
     */
    private function getComStatusSafe($uid) {
        $safe_status_id = comserver::getComStatusRow ( "safe" );
        $orm = ORM::factory ( "CompanyStatusInfo" );
        $orm->where ( "user_status_id", "=", $safe_status_id );
        $orm->where ( "user_id", "=", $uid );
        $num = $orm->count_all ();
        if ($num == 0) {
            return "3";
        } else {
            $orm = ORM::factory ( "CompanyStatusInfo" );
            $orm->where ( "user_status_id", "=", $safe_status_id );
            $orm->where ( "user_id", "=", $uid );
            $orm->where ( "user_status_info", "=", "1" );

            $result = $orm->count_all ();
            if ($result > 0) {
                return "1";
            } else {
                $orm = ORM::factory ( "CompanyStatusInfo" );
                $orm->where ( "user_status_id", "=", $safe_status_id );
                $orm->where ( "user_id", "=", $uid );
                $rss= $orm->find()->as_array();
                return $rss['user_status_info'];
            }
        }
    }
    // end function

    /**
     * 服务保障状态
     *
     * @return 0为审核中，1为审核通过，2为审核未通过,3为未提交审核
     * @author 许晟玮
     */
    private function getComStatusServer($uid) {
        $server_status_id = comserver::getComStatusRow ( "server" );
        $orm = ORM::factory ( "CompanyStatusInfo" );
        $orm->where ( "user_status_id", "=", $server_status_id );
        $orm->where ( "user_id", "=", $uid );
        $num = $orm->count_all ();
        if ($num == 0) {
            return "3";
        } else {
            $orm = ORM::factory ( "CompanyStatusInfo" );
            $orm->where ( "user_status_id", "=", $server_status_id );
            $orm->where ( "user_id", "=", $uid );
            $orm->where ( "user_status_info", "=", "1" );
            $result = $orm->count_all ();
            if ($result > 0) {
                return "1";
            } else {
                $orm = ORM::factory ( "CompanyStatusInfo" );
                $orm->where ( "user_status_id", "=", $server_status_id );
                $orm->where ( "user_id", "=", $uid );
                $rss= $orm->find()->as_array();

                return $rss['user_status_info'];
            }
        }
    }
    // end function

    /**
     * 获取4个保障状态
     *
     * @return array
     * @author 许晟玮
     */
    private function getComStatusAll($uid) {
        $array = array ();

        $array ['base'] = self::getComStatusBasis ( $uid );
        $array ['quality'] = self::getComStatusQuality ( $uid );
        $array ['safe'] = self::getComStatusSafe ( $uid );
        $array ['server'] = self::getComStatusServer ( $uid );
        return $array;
    }
    // end function

    /**
     * 提交用户审核状态
     *
     *@author许晟玮
     */
    public function setComStatus( $uid,$type,$status=1 ){
        //先获取用户这个类型的状态是否有记录

        $tid= self::getCompanyStatusInfo( $uid,$type );
        $server_status_id = comserver::getComStatusRow ( $type );

        if( $tid=="4" ){
            //false
            return false;
        }elseif ( $tid=="3" ){
            //add
            $orm = ORM::factory ( "CompanyStatusInfo" );
            $orm->user_id= $uid;
            $orm->user_status_id= $server_status_id;
            $orm->user_status_info= $status;
            $result= $orm->create();

        }else{
            //edit
            $orm = ORM::factory ( "CompanyStatusInfo" );
            $orm->where("user_id", "=", $uid);
            $orm->where("user_status_id", "=", $server_status_id);
            $rs= $orm->find()->as_array();
            $orm->factory( "CompanyStatusInfo",$rs["id"] );
            $orm->user_status_info= $status;
            $orm->update();

        }
        return true;


    }
    //end function


    /**
     * 保证金缴纳后的状态改变
     * @author许晟玮
     */
    public function setSafeStatus( $uid ){
        $service_account= new Service_Account();
        $safe_account= comserver::getUserSafeAccount();//定义的保证金数额
        $eof= $service_account->checkAccountNumber( $uid,$safe_account );
        if( $eof===false ){
            //账户金额不够
            return false;
        }else{
            //账户钱够了,冻结之
            $result= $service_account->blockedAccount( $uid,$safe_account,1,"购买安全保障的资金冻结" );
            $result["status"]=true;
               if( $result["status"]===false ){
                    //保证金冻结失败
                    return false;
               }else{
                    //保证金冻结成功,改变状态
                    self::setComStatus( $uid,"safe","1" );
                    return true;
               }
        }


    }
    //end function



}